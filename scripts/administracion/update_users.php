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
				include("../../includes/top_page.php");
                if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] != "AG")) {
                    echo "<script type='text/javascript'>";
                        echo "window.location = '../../index.php';";
                    echo "</script>";                    
                }
				
				$typeActivity = array("New User", "Update User");
				$roles = array("EO", "E", "O", "AG");
				$inactive = array("No", "Yes");
				$error1 = false;
				
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
				
				if (isset($_GET["id"]))
					$id = $_GET["id"];
				
				if (isset($_POST["id"]))
					$id = $_POST["id"];
				
				if (isset($_POST["save"])) {
					if (($_POST["userid"] != "") || ($_POST["name"] != "")) {
						// Continue with Update.
						if ($_POST["inactive"] == "No") {
							$inactiveValue = 0;
						} else {
							$inactiveValue = 1;
						}
						$sqlUpdate = "UPDATE usuarios SET fecha_modificacion = '".$date."', usuario = '".trim($_POST["userid"])."', nombre = '".trim($_POST["name"])."', rol = '".$_POST["rol"]."', inactivo = ".$inactiveValue." WHERE id = ".$id;   
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						//echo $sqlUpdate;
						echo "<script type='text/javascript'>";
							echo "window.location = 'users.php';";
						echo "</script>";
					} else {
						$error1 = true;
					}
				}	
				
				// Table.
				$sqlTable = "SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.rol, IF (usuarios.inactivo = 0, 'No', 'Yes') AS inactivo FROM usuarios WHERE id = ".$id;
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
            <center><h3>UPDATE USERS</h3>
            <br>
			<form id="updateusers" method="POST" action="update_users.php">
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
				<table id="user" class="display">
					<?php
						echo "<thead>";
							echo "<tr>";
								echo "<th>User</th>";
								echo "<th>Name</th>";
								echo "<th>Rol</th>";
								echo "<th>Inactive?</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						echo "<tr>";
							echo "<td align='center'><input type='text' id='userid' name='userid' value='".$result_table[0]["usuario"]."' maxlenght='20' onkeypress='return validateUserid(event)' style='width:100px'></td>";
							echo "<td align='center'><input type='text' id='name' name='name' value='".$result_table[0]["nombre"]."' maxlenght='50' onkeypress='return validateName(event)' style='width:300px'></td>";
							echo "<td align='center'>";
								echo "<select id='rol' name='rol' style='width:60px;'>";
									for ($i = 0; $i < count($roles); $i++) {                         
										if ($roles[$i] == $result_table[0]["rol"])
											echo "<option value='".$roles[$i]."' selected>".$roles[$i]."</option>";
										else 
											echo "<option value='".$roles[$i]."'>".$roles[$i]."</option>";
									}
								echo "</select>";
							echo "</td>"; 
							echo "<td align='center'>";
								echo "<select id='inactive' name='inactive' style='width:60px;'>";
									for ($i = 0; $i < count($inactive); $i++) {                         
										if ($inactive[$i] == $result_table[0]["inactive"])
											echo "<option value='".$inactive[$i]."' selected>".$inactive[$i]."</option>";
										else 
											echo "<option value='".$inactive[$i]."'>".$inactive[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
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
                var table = $('#user').DataTable({
					"paging": false,
					"ordering": false,
					"searching": false				
				});
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
                document.getElementById("updateusers").submit();
            }		
		</script>
    </body>
</html>