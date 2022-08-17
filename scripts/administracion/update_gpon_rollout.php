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
				$types = array("PM", "Exp", "Mig");
				
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
					if (($_POST["year"] != "") && ($_POST["homepassed"] != "")) {
						// Continue with Update.
						$sqlUpdate = "UPDATE gpon_rollout SET fecha_balance = '".$date."', year = ".trim($_POST["year"]).", mes = '".$_POST["month"]."', ciudad = '".$_POST["city"]."', tipo = '".$_POST["type"]."', homepassed = ".trim($_POST["homepassed"])." WHERE id = ".$id;   
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						//echo $sqlUpdate;
						echo "<script type='text/javascript'>";
							echo "window.location = 'load_gpon_rollout.php';";
						echo "</script>";
					} else {
						$error1 = true;
					}
				}	
				
				// Selects.
				//$sqlCities = "SELECT DISTINCT(ciudad) FROM meta_gpon WHERE year = ".substr($date, 0, 4)." ORDER BY ciudad";
				$sqlCities = "SELECT DISTINCT(ciudad) FROM meta_gpon WHERE year = 2016 ORDER BY ciudad";
				$resultCitiesSelect = mysqli_query($db, $sqlCities);
                if (mysqli_num_rows($resultCitiesSelect)) {
                    while ($data = mysqli_fetch_array($resultCitiesSelect)) {
                        $citiesSelect[] = $data;    
                    }
                } else {
					$citiesSelect = array();
				}
				
				// Table.
				$sqlTable = "SELECT id, year, mes, ciudad, tipo, homepassed FROM gpon_rollout WHERE id = ".$id;
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
            <center><h3>UPDATE GPON ROLLOUT</h3>
            <br>
			<form id="updategpon" method="POST" action="update_gpon_rollout.php">
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
				<table id="gpon" class="display">
					<?php
						echo "<thead>";
							echo "<tr>";
								echo "<th>Year</th>";
								echo "<th>Month</th>";
								echo "<th>City</th>";
								echo "<th>Type</th>";
								echo "<th>Homepassed</th>";
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
							echo "<td align='center'>";
								echo "<select id='type' name='type' style='width:70px;'>";
									for ($i = 0; $i < count($types); $i++) {                         
										if ($types[$i] == $result_table[0]["tipo"])
											echo "<option value='".$types[$i]."' selected>".$types[$i]."</option>";
										else 
											echo "<option value='".$types[$i]."'>".$types[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='homepassed' name='homepassed' value='".$result_table[0]["homepassed"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
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
                var table = $('#gpon').DataTable({
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
                document.getElementById("updategpon").submit();
            }		
		</script>
    </body>
</html>