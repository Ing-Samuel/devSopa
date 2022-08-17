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
				
				$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                $typeActivity = array("New Entry", "Update Entry");

				// Current Date.
				date_default_timezone_set("America/Bogota");			
                $dateArray = time();
                $date = date("Y/m/d H:i:s", $dateArray);
				
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
					if (!($_POST["registeredDate"] == "") && !($_POST["projects"] == "") && !($_POST["inactive_projects"] == "") && !($_POST["expired_projects"] == "") && !($_POST["old_projects"] == "")) {
						$sqlInsert = "INSERT INTO cable_projects (fecha_balance, fecha_registro, zona, departamento, projects, inactivos, construccion_vencidos, sin_cerrar) 
									  VALUES ('".$date."', '".trim($_POST["registeredDate"])."', '', '".$_POST["department"]."', ".trim($_POST["projects"]).", ".trim($_POST["inactive_projects"]).", ".trim($_POST["expired_projects"]).", ".trim($_POST["old_projects"]).")";
					
						$resultInsert = mysqli_query($db, $sqlInsert);
						//echo $sqlInsert;
					} else {
						echo "<script>";
							echo "alert('Revise campos en blanco!!!');";
						echo "</script>";						
					}
				}	
				
				// Selects.
				//$sqlDepartments = "SELECT DISTINCT(departamento) FROM cable_projects WHERE year = ".substr($date, 0, 4)." ORDER BY id";
				$sqlDepartments = "SELECT DISTINCT(departamento) FROM cable_projects ORDER BY id";
				$resultDepartmentsSelect = mysqli_query($db, $sqlDepartments);
                if (mysqli_num_rows($resultDepartmentsSelect)) {
                    while ($data = mysqli_fetch_array($resultDepartmentsSelect)) {
                        $departmentsSelect[] = $data;    
                    }
                } else {
					$departmentsSelect = array();
				}
				
				// Table.
				//$sqlTable = "SELECT id, year, mes, departamento, projects FROM cable_projects WHERE year = ".substr($date, 0, 4);
				$sqlTable = "SELECT id, fecha_registro, departamento, projects, inactivos, construccion_vencidos, sin_cerrar FROM cable_projects";
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
            <center><h3>CABLE PROJECTS ADMON</h3>
            <br>
			<form id="cable_projects" method="POST" action="load_cable_projects.php">
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
				<table id="projects" class="display">
					<?php
						if ($activity == "New Entry") {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Reported Date <br />[AAAA/MM/DD]</th>";
									echo "<th>Department</th>";
									echo "<th>Total Projects</th>";
									echo "<th>Inactive Projects</th>";
									echo "<th>Expired Projects</th>";
									echo "<th>Old Projects</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["fecha_registro"]."</td><td>"
										 .$result_table[$i]["departamento"]."</td><td>"
										 .$result_table[$i]["projects"]."</td><td>"
										 .$result_table[$i]["inactivos"]."</td><td>"
										 .$result_table[$i]["construccion_vencidos"]."</td><td>"
										 .$result_table[$i]["sin_cerrar"]."</td>";
								echo "</tr>";    
							}
							echo "<td align='center'><input type='text' id='registeredDate' name='registeredDate' maxlenght='10' onkeypress='return validateDate(event)' style='width:80px'></td>";
							echo "<td align='center'>";
								echo "<select id='department' name='department' style='width:180px;'>";
									for ($i = 0; $i < mysqli_num_rows($resultDepartmentsSelect); $i++) {
										echo "<option value='".$departmentsSelect[$i]["departamento"]."'>".$departmentsSelect[$i]["departamento"]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='projects' name='projects' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='inactive_projects' name='inactive_projects' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='expired_projects' name='expired_projects' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='old_projects' name='old_projects' onkeypress='return validateNumber(event)' style='width:50px'></td>";
						} else {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Reported Date</th>";
									echo "<th>Department</th>";
									echo "<th>Total Projects</th>";
									echo "<th>Inactive Projects</th>";
									echo "<th>Expired Projects</th>";
									echo "<th>Old Projects</th>";
									echo "<th>Option</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["fecha_registro"]."</td><td>"
										 .$result_table[$i]["departamento"]."</td><td>"
										 .$result_table[$i]["projects"]."</td><td>"
										 .$result_table[$i]["inactivos"]."</td><td>"
										 .$result_table[$i]["construccion_vencidos"]."</td><td>"
										 .$result_table[$i]["sin_cerrar"]."</td><td><a href='update_cable_projects.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
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
                var table = $('#projects').DataTable();
            });
			
			function validateDate(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==47) return true; // Slash.
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateNumber(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("cable_projects").submit();
            }		
		</script>
    </body>
</html>
