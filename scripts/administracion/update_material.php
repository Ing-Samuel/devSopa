<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
		<link rel="icon" href="../../images/favicon.ico" />
        
        <!--Load Style Sheet-->
        <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="../../css/main4.css">
                
        <!--Load jQuery Library-->
        <script type="text/javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <div>
            <?php
                // Verifying User's Session.
				echo "<center><img src='../../images/cabezote.png'></center>";
				include("../../includes/top.php");
				if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] != "AG")) {
                    echo "<script type='text/javascript'>";
                        echo "window.location = '../../index.php';";
                    echo "</script>";                    
                }
                
				
				//$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                $typeActivity = array("New Entry", "Update Entry");

				// Current Date.
				/*date_default_timezone_set("America/Bogota");			
                $dateArray = time();
                $date = date("Y/m/d H:i:s", $dateArray);*/
				
                // DB Parameters.
                $mysql_host = "netvm-pnoc01";
                $mysql_user = "gestion";
                $mysql_password = "gestion";
                $mysql_database = "devsopa";
                
                $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
                
				if (isset($_GET["id"]))
					$id = $_GET["id"];
				
				if (isset($_POST["id"]))
					$id = $_POST["id"];
			
				if (isset($_POST["save"])) {
					if (($_POST["id_material"] != "") && ($_POST["name"] != "")) {
						$sqlUpdate = "UPDATE materiales_expansion SET id_material = ".trim($_POST["id_material"]).", nombre = '".strtoupper(trim($_POST["name"]))."', tecnologia = '".$_POST["tech"]."' WHERE id = ".$id; 
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						//echo $sqlUpdate;
						echo "<script type='text/javascript'>";
							echo "window.location = 'load_material.php';";
						echo "</script>";
					} else {
						echo "<script>";
							echo "alert('Revise campos!!!');";
						echo "</script>";						
					}
				}	
				
				// Selects.
				$tech = array("Fibra", "HFC", "Cobre", "Plataformas");
				
				// Table.
				$sqlTable = "SELECT id_material, nombre, tecnologia FROM materiales_expansion WHERE id = ".$id;
				$resultTable = mysqli_query($db, $sqlTable);
                if (mysqli_num_rows($resultTable)) {
                    while ($data = mysqli_fetch_array($resultTable)) {
                        $result_table[] = $data;    
                    }
                } else {
					$result_table = array();
				}

                mysqli_close($db);
            ?>
        </div>
         
        <div id="main">
            <center><h3>UPDATE MATERIAL</h3>
            <br>
			<form id="updatematerial" method="POST" action="update_material.php">
				<input type="submit" name="save" value="Save" />
				<input name="id" type="hidden" value="<?php echo $id?>">
				<?php
					// ERRORES
				?>
				<br />
			    <br />
				<div style='width:900px;'>
				<table id="id_material" class="display">
					<?php
						echo "<thead>";
							echo "<tr>";
								echo "<th>Id</th>";
								echo "<th>Name</th>";
								echo "<th>Tech</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						
						echo "<td align='center'><input type='text' id='id_material' name='id_material' value='".$result_table[0]["id_material"]."' onkeypress='return validateNumber(event)' style='width:150px'></td>";
						echo "<td align='center'><input type='text' id='name' name='name' value='".$result_table[0]["nombre"]."' style='width:450px'></td>";
						echo "<td align='center'>";
							echo "<select id='tech' name='tech' style='width:100px;'>";
								for ($i = 0; $i < count($tech); $i++) {
									if ($tech[$i] == $result_table[0]["tecnologia"]) {
										echo "<option value='".$tech[$i]."' selected>".$tech[$i]."</option>";
									} else {
										echo "<option value='".$tech[$i]."'>".$tech[$i]."</option>";
									}
								}
							echo "</select>";
						echo "</td>";
					?>
					</tbody>    
				</table>
				</div>
			</form></center>
            <br />
        </div>
               
        <script type="text/javascript">
			$(document).ready(function() {
                var table = $('#id_material').DataTable({
					"paging": false,
					"ordering": false,
					"searching": false	
				});
            });
			
			function validateNumber(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("updatematerial").submit();
            }		
		</script>
    </body>
</html>
