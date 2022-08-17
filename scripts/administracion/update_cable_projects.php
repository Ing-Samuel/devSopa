<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
        
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
				
				$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
				$error1 = false;
				
				// Current Date.
				date_default_timezone_set("America/Bogota");			
                $dateArray = time();
                $date = date("Y/m/d H:i", $dateArray);
				
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
					if (!($_POST["registeredDate"] == "") && !($_POST["projects"] == "") && !($_POST["inactive_projects"] == "") && !($_POST["expired_projects"] == "") && !($_POST["old_projects"] == "")) {
						// Continue with Update.
						$sqlUpdate = "UPDATE cable_projects SET fecha_balance = '".$date."', fecha_registro = '".trim($_POST["registeredDate"])."', departamento = '".$_POST["department"]."', projects = ".trim($_POST["projects"]).", inactivos = ".trim($_POST["inactive_projects"]).", construccion_vencidos = ".trim($_POST["expired_projects"]).", sin_cerrar = ".trim($_POST["old_projects"])." WHERE id = ".$id;  
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						//echo $sqlUpdate;
						echo "<script type='text/javascript'>";
							echo "window.location = 'load_cable_projects.php';";
						echo "</script>";
					} else {
						$error1 = true;
					}
				}	
				
				// Selects.
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
				$sqlTable = "SELECT id, fecha_registro, departamento, projects, inactivos, construccion_vencidos, sin_cerrar FROM cable_projects WHERE id = ".$id;
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
            <center><h3>UPDATE CABLE PROJECTS</h3>
            <br>
			<form id="updateprojects" method="POST" action="update_cable_projects.php">
				<input type="submit" name="save" value="Save" />
				<input name="id" type="hidden" value="<?php echo $id?>">
				<?php
					if ($error1) {
						echo "<br />";
						echo "<br />";
						echo "<table><tr>"
						    ."<td><img src='../../images/error-icon.png'></td>"
							."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Existen Campos Vacíos</td>"
							."</tr></table>";
					}
				?>
				<br />
			    <br />
				<div style='width:850px;'>
				<table id="projects" class="display">
					<?php
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
						echo "<tr>";
							echo "<td align='center'><input type='text' id='registeredDate' name='registeredDate' value='".$result_table[0]["fecha_registro"]."' maxlenght='10' onkeypress='return validateDate(event)' style='width:80px'></td>";
							///echo "<td></td>";
							
							
							echo "<td align='center'>";
								echo "<select id='department' name='department' style='width:180px;'>";
									for ($i = 0; $i < mysqli_num_rows($resultDepartmentsSelect); $i++) {
										if ($departmentsSelect[$i]["departamento"] == $result_table[0]["departamento"]) {
											echo "<option value='".$departmentsSelect[$i]["departamento"]."' selected>".$departmentsSelect[$i]["departamento"]."</option>";
										} else {
											echo "<option value='".$departmentsSelect[$i]["departamento"]."'>".$departmentsSelect[$i]["departamento"]."</option>";
										}										
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='projects' name='projects' value='".$result_table[0]["projects"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='inactive_projects' name='inactive_projects' value='".$result_table[0]["inactivos"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='expired_projects' name='expired_projects' value='".$result_table[0]["construccion_vencidos"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='old_projects' name='old_projects' value='".$result_table[0]["sin_cerrar"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
						
						echo "</tr>";						
					?>
					</tbody>    
				</table>
				</div>
			</form></center>
            <br />
        </div>
		
		<?php
		
		?>
               
        <script type="text/javascript">
			$(document).ready(function() {
                var table = $('#projects').DataTable({
					"paging": false,
					"ordering": false,
					"searching": false				
				});
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
                document.getElementById("updatecable").submit();
            }		
		</script>
    </body>
</html>