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
				
				$typeActivity = array("New Entry", "Update Entry");

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
					$activity = "New Entry";
                }
				
				if (isset($_POST["save"])) {
					$sqlInsert = "INSERT INTO ejecucion_presupuesto (fecha_reporte, fecha_inicio_acta, fecha_fin_acta, acta, departamento, proceso, tecnologia, contrato, valor) 
							      VALUES ('".$date."', '".trim($_POST["initDate"])."', '".trim($_POST["endDate"])."', ".$_POST["payment"].", '".$_POST["department"]."', '".$_POST["process"]."', '".$_POST["tech"]."', '".$_POST["responsible"]."', ".trim(str_replace("$", "", str_replace(",", ".", $_POST["value"]))).")";
					
					$resultInsert = mysqli_query($db, $sqlInsert);
				}	
				
				// Table.
				$sqlTable = "SELECT id, acta, fecha_inicio_acta, fecha_fin_acta, departamento, proceso, tecnologia, contrato, valor FROM ejecucion_presupuesto WHERE contrato = 'HUAWEI'";
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
            <center><h3>BUDGET EXECUTION ADMON</h3>
            <br>
			<form id="budgetex" method="POST" action="load_budget_ex.php">
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
				<table id="allpayments" class="display">
					<?php
						if ($activity == "New Entry") {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Payment</th>";
									echo "<th>Init Date</th>";
									echo "<th>End Date</th>";
									echo "<th>Department</th>";
									echo "<th>Process</th>";
									echo "<th>Tech</th>";
									echo "<th>Responsible</th>";
									echo "<th>Value</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["acta"]."</td><td>"
										 .$result_table[$i]["fecha_inicio_acta"]."</td><td>"
										 .$result_table[$i]["fecha_fin_acta"]."</td><td>"
										 .$result_table[$i]["departamento"]."</td><td>"
										 .$result_table[$i]["proceso"]."</td><td>"
										 .$result_table[$i]["tecnologia"]."</td><td>"
										 .$result_table[$i]["contrato"]."</td><td>"
										 .$result_table[$i]["valor"]."</td>";
								echo "</tr>";    
							}
							
							echo "<tr>";
								echo "<td align='center'><input type='text' id='payment' name='payment' maxlenght='2' onkeypress='return validateNumber(event)' style='width:50px'></td>";
								echo "<td align='center'><input type='text' id='initDate' name='initDate' maxlenght='10' onkeypress='return validateDate(event)' style='width:75px'></td>";
								echo "<td align='center'><input type='text' id='endDate' name='endDate' maxlenght='10' onkeypress='return validateDate(event)' style='width:75px'></td>";
								echo "<td align='center'>";
									echo "<select id='department' name='department' style='width:140px;'>";
										echo "<option value='Antioquia Sur'>Antioquia Sur</option>";
										echo "<option value='Antioquia Norte'>Antioquia Norte</option>";
										echo "<option value='Atlantico'>Atlantico</option>";
										echo "<option value='Bolivar'>Bolivar</option>";
										echo "<option value='Caldas'>Caldas</option>";
										echo "<option value='Cundinamarca Sur'>Cundinamarca Sur</option>";
										echo "<option value='Cundinamarca Norte'>Cundinamarca Norte</option>";
										echo "<option value='Norte de Santander'>Norte de Santander</option>";
										echo "<option value='Quindio'>Quindio</option>";
										echo "<option value='Santander'>Santander</option>";
										echo "<option value='Santa Marta'>Santa Marta</option>";
										echo "<option value='Valle del Cauca'>Valle del Cauca</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'>";
									echo "<select id='process' name='process' style='width:100px;'>";
										echo "<option value='Correctivo'>Correctivo</option>";
										echo "<option value='Operacion'>Operacion</option>";
										echo "<option value='Preventivo'>Preventivo</option>";
										echo "<option value='Materiales'>Materiales</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'>";
									echo "<select id='tech' name='tech' style='width:85px;'>";
										echo "<option value='Cobre'>Cobre</option>";
										echo "<option value='Fibra'>Fibra</option>";
										echo "<option value='HFC'>HFC</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'>";
									echo "<select id='responsible' name='responsible' style='width:85px;'>";
										echo "<option value='HUAWEI'>HUAWEI</option>";
									echo "</select>";
								echo "</td>"; 
								echo "<td align='center'><input type='text' id='value' name='value' onkeypress='return validateValue(event)' style='width:100px'></td>";
							echo "</tr>";
						} else {
							echo "<thead>";
								echo "<tr>";
									echo "<th>Payment</th>";
									echo "<th>Init Date</th>";
									echo "<th>End Date</th>";
									echo "<th>Department</th>";
									echo "<th>Process</th>";
									echo "<th>Tech</th>";
									echo "<th>Responsible</th>";
									echo "<th>Value</th>";
									echo "<th>Option</th>";	
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["acta"]."</td><td>"
										 .$result_table[$i]["fecha_inicio_acta"]."</td><td>"
										 .$result_table[$i]["fecha_fin_acta"]."</td><td>"
										 .$result_table[$i]["departamento"]."</td><td>"
										 .$result_table[$i]["proceso"]."</td><td>"
										 .$result_table[$i]["tecnologia"]."</td><td>"
										 .$result_table[$i]["contrato"]."</td><td>"
										 .$result_table[$i]["valor"]."</td>"
										 ."<td><a href='update_ex.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
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
                var table = $('#allpayments').DataTable();
            });
			
			function validateNumber(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateDate(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==47) return true; // Slash.
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateValue(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==46) return true; // Dot.
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("budgetex").submit();
            }		
		</script>
    </body>
</html>
