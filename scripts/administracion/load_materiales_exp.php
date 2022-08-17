<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
		<link rel="icon" href="../../images/favicon.ico"/>
        
        <!--Load Style Sheet-->
        <link rel="stylesheet" type="text/css" href="../../css/main4.css">
        
    </head>
    <body>

        <div>
            <?php
                // Verifying User's Session.
				echo "<header>";
				echo "<center><img src='../../images/cabezote.png'></center>";
				include("../../includes/top.php");
				echo "</header>";
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
            <center><h3>LOAD FILE - EXP MATERIALS</h3>
            <br>
            <form enctype="multipart/form-data" id="load" method="POST" action="load_materiales_exp.php">
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
                            $location = "expMaterials.csv"; 
                            move_uploaded_file($_FILES["fileLoad"]["tmp_name"], $location);
                            $file = @fopen("expMaterials.csv", 'r');
                            if ($file) {
                                // DB Parameters.
                                $mysql_host = "netvm-pnoc01";
                                $mysql_user = "gestion";
                                $mysql_password = "gestion";
                                $mysql_database = "devsopa";
                    
                                $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
                                
								$mysql_database = "huawei";
								
								$db1 = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
                                
								// We Find the Id Value First.
                                $sqlId = "SELECT id FROM materiales_contratos ORDER BY id DESC LIMIT 1";
                                $resultId = mysqli_query($db, $sqlId);
                                if (mysqli_num_rows($resultId)) {
                                    $id = mysqli_fetch_array($resultId);
                                } else {
                                    $id = 0;
                                }
                                
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
									$idMaterial = $data[1];
									$idWarehouse = $data[0];
									$totalAmount = $data[2];
									$totalValue = $data[3];
									
									// Tech.
									$sqlTech = "SELECT tecnologia FROM materiales_expansion WHERE id_material = '".$idMaterial."'";
									$resultTech = mysqli_query($db, $sqlTech);
									if (mysqli_num_rows($resultTech)) {
										$data = mysqli_fetch_array($resultTech);
										$tech = $data["tecnologia"];
									} else {
										$tech = "";
									}
                                    
                                    $sqlInsert = "INSERT INTO materiales_contratos VALUES (".($id["id"] + $registers).", "
                                    ."'".$date."', "
                                    ."'".$idMaterial."', "
									."'".$tech."', "
                                    ."'".$idWarehouse."', "
                                    ."'Expansion', " 
									."'".trim(str_replace(",", "", str_replace(".", "", floor($totalAmount))))."', "	
									."'".trim(str_replace("$", "", str_replace(",", ".", str_replace(".", "", $totalValue))))."')";		

									mysqli_query($db, $sqlInsert);
									$sqlInsert = str_replace("materiales_contratos", "bodegas_stock_ex", $sqlInsert);
									mysqli_query($db1, $sqlInsert);
                                    $registers = $registers + 1;     
                                }

								mysqli_close($db);
								mysqli_close($db1);
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
