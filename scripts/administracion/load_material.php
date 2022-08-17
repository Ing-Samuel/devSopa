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
				echo "<header>";
				echo "<center><img src='../../images/cabezote.png'></center>";
				include("../../includes/top.php");
				echo "</header>";
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
                
				// Verifying POST Variables.
                if (isset($_POST["activity"])) {
					$activity = $_POST["activity"];
                } else {
					$activity = "New Entry";
                }
				
				if (isset($_POST["save"])) {
					if (($_POST["id"] != "") && ($_POST["name"] != "") && ($_POST["id"] != "Input Id") && ($_POST["name"] != "Input Name")) {
						$sqlInsert = "INSERT INTO materiales_expansion (id_material, nombre, tecnologia) 
									  VALUES (".trim($_POST["id"]).", '".strtoupper(trim($_POST["name"]))."', '".$_POST["tech"]."')";					
						$resultInsert = mysqli_query($db, $sqlInsert);
						//echo $sqlInsert;
					} else {
						echo "<script>";
							echo "alert('Revise campos!!!');";
						echo "</script>";						
					}
				}	
				
				// Selects.
				$tech = array("Fibra", "HFC", "Cobre", "Plataformas");
				
				// Table.
				$sqlTable = "SELECT id, id_material, nombre, tecnologia FROM materiales_expansion ORDER BY id_material";
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
            <center><h3>LOAD MATERIAL</h3>
            <br>
			<form id="material" method="POST" action="load_material.php">
				<label for="activity" style="font-family: Calibri; color: #676767; font-size: 14px;">Activity: </label>
                <select id="activity" name="activity" style="width:150px;" onchange="selected(document.getElementById('activity'))">
					<?php
                        for ($i = 0; $i < count($typeActivity); $i++) {
                            if ($typeActivity[$i] == $activity)
                                echo "<option value='".$typeActivity[$i]."' selected>".$typeActivity[$i]."</option>";
                            else 
                                echo "<option value='".$typeActivity[$i]."'>".$typeActivity[$i]."</option>";
                        }
                    ?>
                </select>
				<?php
					if ($activity == "New Entry") {
						echo "&nbsp";
						echo "<input type='submit' name='save' value='Save' />";					
					}
				?>

				<?php
					// ERRORES
				?>
				<br />
			    <br />
				<div style='width:900px;'>
				<table id="id_material" class="display">
					<?php
						if ($activity == "New Entry") {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Id</th>";
									echo "<th>Name</th>";
									echo "<th>Tech</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["id_material"]."</td><td>"
										 .$result_table[$i]["nombre"]."</td><td>"
										 .$result_table[$i]["tecnologia"]."</td>";
								echo "</tr>";    
							}

							echo "<td align='center'><input type='text' id='id' name='id' value='Input Id' onkeypress='return validateNumber(event)' style='width:150px'></td>";
							echo "<td align='center'><input type='text' id='name' name='name' value='Input Name' style='width:450px'></td>";
							echo "<td align='center'>";
								echo "<select id='tech' name='tech' style='width:100px;'>";
									for ($i = 0; $i < count($tech); $i++) {
										echo "<option value='".$tech[$i]."'>".$tech[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
						} else {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Id</th>";
									echo "<th>Name</th>";
									echo "<th>Tech</th>";
									echo "<th>Option</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";

							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["id_material"]."</td><td>"
										 .$result_table[$i]["nombre"]."</td><td>"
										 .$result_table[$i]["tecnologia"]."</td><td><a href='update_material.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
								echo "</tr>";    
							}	
						}
					?>
					</tbody>    
				</table>
				</div>
			</form></center>
            <br />
        </div>
               
        <script type="text/javascript">
			$(document).ready(function() {
                var table = $('#id_material').DataTable();
            });
			
			function validateNumber(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("material").submit();
            }		
		</script>
    </body>
</html>
