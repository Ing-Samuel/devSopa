<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
		<link rel="icon" href="../../images/fav-icon.ico"/>
        
        <!--Load Style Sheet-->
        <link rel="stylesheet" type="text/css" href="../../css/main4.css">
        
    </head>
    <body>

        <div>
            <?php
                // Verifying User's Session.
                echo "<center><img src='../../images/cabezote.png'></center>";
                include("../../includes/top_page.php");
                if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] != "AG")) {
                    echo "<script type='text/javascript'>";
                        echo "window.location = '../../index.php';";
                    echo "</script>";                    
                }

                date_default_timezone_set("America/Bogota");
                $registers = 1;
            ?>
        </div>
        
        <div id="main">
            <center><h3>LOAD FILE - CABLE PENDINGS</h3>
            <br>
            <form enctype="multipart/form-data" id="load" method="POST" action="load_file.php">
                <input type="file" name="fileLoad">    
                &nbsp
                <input type="submit" name="load" value="Load">
                <br />
                <br />
                <div id="msj">
                    <table><tr>
                        <td style='font-family: Calibri; color: #676767; font-size: 14px;'>Entradas Procesadas: <?php echo ($registers - 1);?></td>
                    </tr></table>    
                </div>
            </form> 
        </div>        
         
        <div>
            <?php        
                if (isset($_POST["load"])) {
                    if ($_FILES["fileLoad"]["error"]) {    
                        switch ($_FILES["file"]["error"]) {
                            case 1: // UPLOAD_ERR_INI_SIZE
                                echo "<script type='text/javascript'>";
                                    echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/error-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>Peso no permitido por el servidor.</td></tr></table>';";
                                echo "</script>";
                            break;
                        
                            case 2: // UPLOAD_ERR_FORM_SIZE
                                echo "<script type='text/javascript'>";
                                    echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/error-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>Peso no permitido por el formulario.</td></tr></table>';";
                                echo "</script>";
                            break;
                        
                            case 3: // UPLOAD_ERR_PARTIAL
                                echo "<script type='text/javascript'>";
                                    echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/error-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>Transferencia suspendida.</td></tr></table>';";
                                echo "</script>";
                            break;
                        
                            case 4: // UPLOAD_ERR_NO_FILE
                                echo "<script type='text/javascript'>";
                                    echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/error-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>Tamaño nulo.</td></tr></table>';";
                                echo "</script>";
                            break;
                        } 
                    } else {
                        // The File must be .csv.
                        if (pathinfo($_FILES['fileLoad']['name'], PATHINFO_EXTENSION) == "csv") {
                            $location = "cablePendings.csv"; 
                            move_uploaded_file($_FILES["fileLoad"]["tmp_name"], $location);
                            $file = @fopen("cablePendings.csv", 'r');
                            if ($file) {
                                // DB Parameters.
                                $mysql_host = "netvm-pnoc01";
                                $mysql_user = "gestion";
                                $mysql_password = "gestion";
                                $mysql_database = "devsopa";
                    
                                $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
                                
                                // We Find the Id Value First.
                                $sqlId = "SELECT id FROM pendientes_cable ORDER BY id DESC LIMIT 1";
                                $resultId = mysqli_query($db, $sqlId);
                                if (mysqli_num_rows($resultId)) {
                                    $id = mysqli_fetch_array($resultId);
                                } else {
                                    $id = 0;
                                }
                                
                                $cableAns = Array();
                                // We Save the Cable ANS.
                                $sqlCableAns = "SELECT concepto, ans_dias FROM ans_cable";
                                $resultCableAns = mysqli_query($db, $sqlCableAns);
                                while ($data = mysqli_fetch_array($resultCableAns)) {
                                    $cableAns[$data["concepto"]] = $data["ans_dias"];    
                                }
                                
                                // Translate Departments.
                                $departments = array("ANT" => "Antioquia", "ATL" => "Atlantico", "BOG" => "Cundinamarca", "BOL" => "Bolivar", "VAL" => "Valle del Cauca");
                                
                                // Current Date.
                                $dateArray = time();
                                $date = date("Y/m/d H:i:s", $dateArray);
                                
                                while (($line = fgets($file)) !==  FALSE) {
                                    // VALIDAR SI SE HACE CORRECCION DE ACENTOS Y Ñ.
                                    // AL PARECER LOS str_replace NO FUNCIONAN (FUNCIONAN ES CON EL CAMPO DE LA COLUMNA) DEPRONTO UBICARLO EN EL CAMPO DE LA OBSERVACION.
                                    /*$line = str_replace("\"", "", $line);
                                    $line = str_replace("\'", "", $line);
                                    $line = str_replace("'", "", $line);
                                    $line = str_ireplace("á", "a", $line);
                                    $line = str_ireplace("é", "e", $line);
                                    $line = str_ireplace("í", "i", $line);
                                    $line = str_ireplace("ó", "o", $line);
                                    $line = str_ireplace("ú", "u", $line);*/
                                
                                    $data = explode(';', $line);
                                    
                                    if (array_key_exists($data[4], $cableAns)) {
                                        if (str_replace(",", ".", $data[10]) > $cableAns[$data[4]]) {
                                            $statusAns = "Incumple";
                                        } else {
                                            $statusAns = "Cumple";
                                        }           
                                    } else {
                                        $statusAns = "";    
                                    }
                                    
                                    if (array_key_exists($data[12], $departments)) {
                                        $nameDepartment = $departments[$data[12]];        
                                    } else {
                                        $nameDepartment = $data[12];
                                    }
                                                                            
                                    $sqlInsert = "INSERT INTO pendientes_cable VALUES (".($id["id"] + $registers).", "
                                    ."'".$date."', "
                                    ."'".$data[0]."', "
                                    ."'".$data[4]."', "
                                    ."'".$data[3]."', "                                    
                                    ."'".$data[7]."', " 
                                    ."'".$data[8]."', "       
                                    .str_replace(",", ".", $data[9]).", "
                                    .str_replace(",", ".", $data[10]).", "
                                    ."'".$data[6]."', " 
                                    ."'".$data[11]."', "
                                    ."'".$nameDepartment."', " 
                                    ."'".$data[18]."', "    
                                    ."'".$data[20]."', "
                                    ."'".$data[21]."', "
                                    ."'".$data[25]."', "
                                    ."'".$statusAns."')";
                                
                                    mysqli_query($db, $sqlInsert);
                                    $registers = $registers + 1;     
                                }                             
                            }
                            
                            echo "<script type='text/javascript'>";
                                echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/ok-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>Entradas Procesadas: ".($registers - 1)."</td></tr></table>';";
                            echo "</script>";    
                            
                            @fclose($file);
                        } else {
                            echo "<script type='text/javascript'>";
                                echo "document.getElementById('msj').innerHTML = '<table><tr><td><img src=\'../../images/error-icon.png\'></td><td style=\'font-family: Calibri; color: #676767; font-size: 14px;\'>La extensión del archivo es inválida.</td></tr></table>';";
                            echo "</script>";     
                        }                            
                    }                    
                }

            ?>
        </div>
        
        
    </body>
</html>
