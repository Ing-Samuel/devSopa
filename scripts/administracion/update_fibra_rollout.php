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
                $date = date("Y/m/d H:i:s", $dateArray);
				
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
					if (($_POST["year"] != "") && ($_POST["clientes"] != "")) {
						// Continue with Update.
						$sqlUpdate = "UPDATE fibra_rollout SET fecha_balance = '".$date."', year = ".trim($_POST["year"]).", mes = '".$_POST["month"]."', ciudad = '".$_POST["city"]."', clientes_uk = ".trim($_POST["clientes"])." WHERE id = ".$id;   
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						//echo $sqlUpdate;
						echo "<script type='text/javascript'>";
							echo "window.location = 'load_fibra_rollout.php';";
						echo "</script>";
					} else {
						$error1 = true;
					}
				}	
				
				// Selects.
				//$sqlCities = "SELECT DISTINCT(ciudad) FROM fibra_rollout WHERE year = ".substr($date, 0, 4)." ORDER BY ciudad";
				$sqlCities = "SELECT DISTINCT(ciudad) FROM fibra_rollout WHERE year = 2016 ORDER BY ciudad";
				$resultCitiesSelect = mysqli_query($db, $sqlCities);
                if (mysqli_num_rows($resultCitiesSelect)) {
                    while ($data = mysqli_fetch_array($resultCitiesSelect)) {
                        $citiesSelect[] = $data;    
                    }
                } else {
					$citiesSelect = array();
				}
				
				// Table.
				$sqlTable = "SELECT id, year, mes, ciudad, clientes_uk FROM fibra_rollout WHERE id = ".$id;
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
            <center><h3>UPDATE F.O ROLLOUT</h3>
            <br>
			<form id="updatefibra" method="POST" action="update_fibra_rollout.php">
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
				<table id="fibra" class="display">
					<?php
						echo "<thead>";
							echo "<tr>";
								echo "<th>Year</th>";
								echo "<th>Month</th>";
								echo "<th>Department</th>";
								echo "<th>Clients</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						echo "<tr>";
							echo "<td align='center'><input type='text' id='year' name='year' value='".$result_table[0]["year"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'>";
								echo "<select id='month' name='month' style='width:100px;'>";
									for ($i = 0; $i < count($months); $i++) {
										if ($months[$i] == $result_table[0]["mes"]) {
											echo "<option value='".$months[$i]."' selected>".$months[$i]."</option>";
										} else {
											echo "<option value='".$months[$i]."'>".$months[$i]."</option>";
										}										
									}
								echo "</select>";
							echo "</td>"; 
							echo "<td align='center'>";
								echo "<select id='city' name='city' style='width:140px;'>";
									for ($i = 0; $i < mysqli_num_rows($resultCitiesSelect); $i++) {
										if ($citiesSelect[$i]["ciudad"] == $result_table[0]["ciudad"]) {
											echo "<option value='".$citiesSelect[$i]["ciudad"]."' selected>".$citiesSelect[$i]["ciudad"]."</option>";
										} else {
											echo "<option value='".$citiesSelect[$i]["ciudad"]."'>".$citiesSelect[$i]["ciudad"]."</option>";
										}										
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='clientes' name='clientes' value='".$result_table[0]["clientes_uk"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
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
                var table = $('#fibra').DataTable({
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
                document.getElementById("updatefibra").submit();
            }		
		</script>
    </body>
</html>