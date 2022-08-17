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
					if (!($_POST["year"] == "") && !($_POST["homepassed"] == "")) {
						$sqlInsert = "INSERT INTO cable_rollout (fecha_balance, year, mes, zona, ciudad, homepassed) 
									  VALUES ('".$date."', ".trim($_POST["year"]).", '".$_POST["month"]."', '', '".$_POST["city"]."', ".trim($_POST["homepassed"]).")";
					
						$resultInsert = mysqli_query($db, $sqlInsert);
						//echo $sqlInsert;
					} else {
						echo "<script>";
							echo "alert('Revise campos en blanco!!!');";
						echo "</script>";						
					}
				}	
				
				// Selects.
				$sqlCities = "SELECT DISTINCT(ciudad) FROM meta_cable WHERE year = ".substr($date, 0, 4)." ORDER BY id";
				$resultCitiesSelect = mysqli_query($db, $sqlCities);
                if (mysqli_num_rows($resultCitiesSelect)) {
                    while ($data = mysqli_fetch_array($resultCitiesSelect)) {
                        $citiesSelect[] = $data;    
                    }
                } else {
					$citiesSelect = array();
				}
				
				// Table.
				$sqlTable = "SELECT id, year, mes, ciudad, homepassed FROM cable_rollout WHERE year = ".substr($date, 0, 4);
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
            <center><h3>CABLE ROLLOUT ADMON</h3>
            <br>
			<form id="cable_rollout" method="POST" action="load_cable_rollout.php">
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
				<table id="cable" class="display">
					<?php
						if ($activity == "New Entry") {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Year</th>";
									echo "<th>Month</th>";
									echo "<th>City</th>";
									echo "<th>Homepassed</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["year"]."</td><td>"
										 .$result_table[$i]["mes"]."</td><td>"
										 .$result_table[$i]["ciudad"]."</td><td>"
										 .$result_table[$i]["homepassed"]."</td>";
								echo "</tr>";    
							}
							echo "<td align='center'><input type='text' id='year' name='year' value='2016' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'>";
								echo "<select id='month' name='month' style='width:100px;'>";
									for ($i = 0; $i < count($months); $i++) {
										echo "<option value='".$months[$i]."'>".$months[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'>";
								echo "<select id='city' name='city' style='width:250px;'>";
									for ($i = 0; $i < mysqli_num_rows($resultCitiesSelect); $i++) {
										echo "<option value='".$citiesSelect[$i]["ciudad"]."'>".$citiesSelect[$i]["ciudad"]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='homepassed' name='homepassed' onkeypress='return validateNumber(event)' style='width:50px'></td>";
						} else {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Year</th>";
									echo "<th>Month</th>";
									echo "<th>City</th>";
									echo "<th>Homepassed</th>";
									echo "<th>Option</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["year"]."</td><td>"
										 .$result_table[$i]["mes"]."</td><td>"
										 .$result_table[$i]["ciudad"]."</td><td>"
										 .$result_table[$i]["homepassed"]."</td><td><a href='update_cable_rollout.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
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
                var table = $('#cable').DataTable();
            });
			
			function validateNumber(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("cable_rollout").submit();
            }		
		</script>
    </body>
</html>
