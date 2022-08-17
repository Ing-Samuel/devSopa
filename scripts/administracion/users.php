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
				include("../../includes/top_page.php");
				echo "</header>";
                if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] != "AG")) {
                    echo "<script type='text/javascript'>";
                        echo "window.location = '../../index.php';";
                    echo "</script>";                    
                }
				
				$typeActivity = array("New User", "Update User");
				$error1 = false;
				$error2 = false;
				// Current Date.
				date_default_timezone_set("America/Bogota");			
                $dateArray = time();
                $date = date("Y/m/d", $dateArray);
				
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
					$activity = "New User";
                }
				
				if (isset($_POST["save"])) {
					if (!($_POST["userid"] == "Insert Login") || !($_POST["name"] == "Insert Name") || !($_POST["name"] == "Insert") || !($_POST["name"] == "Name")) {
						// Last Search of UserId.
						$sqlUserId = "SELECT * FROM usuarios WHERE usuario = '".trim($_POST["userid"])."'";
						$resultUserId = mysqli_query($db, $sqlUserId);
						if (mysqli_num_rows($resultUserId)) {
							// Error Message.
							$error1 = true;							
						} else {
							// Continue with Insert.
							$sqlInsert = "INSERT INTO usuarios (fecha_creacion, usuario, nombre, rol, empresa, inactivo) 
									      VALUES ('".$date."', '".trim($_POST["userid"])."', '".trim($_POST["name"])."', '".$_POST["rol"]."', '".$_POST["company"]."', ".$_POST["inactive"].")";
							$resultInsert = mysqli_query($db, $sqlInsert);
						}
					} else {
						$error2 = true;
					}
				}	
				
				// Table.
				$sqlTable = "SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.rol, usuarios.empresa, IF (usuarios.inactivo = 0, 'No', 'Yes') AS inactivo FROM usuarios ORDER BY usuario";
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
            <center><h3>USERS ADMON</h3>
            <br>
			<form id="users" method="POST" action="users.php">
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
					if ($activity == "New User") {
						echo "&nbsp";
						echo "<input type='submit' name='save' value='Save' />";					
					}	
				?>
				
				<?php
					if ($error1) {
						echo "<br />";
						echo "<br />";
						echo "<table><tr>"
						    ."<td><img src='../../images/error-icon.png'></td>"
							."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Usuario ya Registrado</td>"
							."</tr></table>";				
					}
					
					if ($error2) {
						echo "<br />";
						echo "<br />";
						echo "<table><tr>"
						    ."<td><img src='../../images/error-icon.png'></td>"
							."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Modifique los Campos</td>"
							."</tr></table>";
					}
				?>
				<br />
			    <br />
				<div style='width:850px;'>
				<table id="allusers" class="display">
					<?php
						if ($activity == "New User") {
							echo "<thead>";
								echo "<tr>";
									echo "<th>User</th>";
									echo "<th>Name</th>";
									echo "<th>Rol</th>";
									echo "<th>Company</th>";
									echo "<th>Inactive?</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							echo "<tr>";
								echo "<td align='center'><input type='text' id='userid' name='userid' value='Insert Login' maxlenght='20' onkeypress='return validateUserid(event)' style='width:100px'></td>";
								echo "<td align='center'><input type='text' id='name' name='name' value='Insert Name' maxlenght='50' onkeypress='return validateName(event)' style='width:300px'></td>";
								echo "<td align='center'>";
									echo "<select id='rol' name='rol' style='width:60px;'>";
										echo "<option value='EO'>EO</option>";
										echo "<option value='E'>E</option>";
										echo "<option value='O'>O</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'>";
									echo "<select id='company' name='company' style='width:60px;'>";
										echo "<option value='UNE'>UNE</option>";
										echo "<option value='Tigo'>Tigo</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'>";
									echo "<select id='inactive' name='inactive' style='width:60px;'>";
										echo "<option value='0'>No</option>";
										echo "<option value='1'>Yes</option>";
									echo "</select>";
								echo "</td>";
							echo "</tr>";
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["usuario"]."</td><td>".$result_table[$i]["nombre"]."</td><td>".$result_table[$i]["rol"]."</td><td>".$result_table[$i]["empresa"]."</td><td>".$result_table[$i]["inactivo"]."</td>";
								echo "</tr>";    
							}
						} else {
							echo "<thead>";
								echo "<tr>";
									echo "<th>User</th>";
									echo "<th>Name</th>";
									echo "<th>Rol</th>";
									echo "<th>Company</th>";
									echo "<th>Inactive?</th>";
									echo "<th>Option</th>";									
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["usuario"]."</td><td>".$result_table[$i]["nombre"]."</td><td>".$result_table[$i]["rol"]."</td><td>".$result_table[$i]["empresa"]."</td><td>".$result_table[$i]["inactivo"]."</td><td><a href='update_users.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
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
                var table = $('#allusers').DataTable();
            });
			
			function validateUserid(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==46) return true; // Punto.
					
				pattern = /[a-z]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateName(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==32) return true;
					
				pattern = /[a-zA-Z]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("users").submit();
            }		
		</script>
    </body>
</html>
