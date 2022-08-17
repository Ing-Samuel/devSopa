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
				
				$department = array("Antioquia Sur", "Antioquia Norte", "Atlantico", "Bolivar", "Caldas", "Cundinamarca Sur", "Cundinamarca Norte", "Norte de Santander", "Quindio", "Santander", "Santa Marta", "Valle del Cauca");
				$process = array("Correctivo", "Operacion", "Preventivo", "Materiales");
				$tech = array("Cobre", "Fibra", "HFC");
				$responsible = array("HUAWEI");
				$error1 = false;
				
				// Current Date.
				date_default_timezone_set("America/Bogota");			
                $dateArray = time();
                $date = date("Y/m/d", $dateArray);
				
                // DB Parameters.k
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
					if (($_POST["payment"] != "") && ($_POST["initDate"] != "") && ($_POST["endDate"] != "")) {
						// Continue with Update.
						$sqlUpdate = "UPDATE ejecucion_presupuesto SET fecha_reporte = '".$date."', fecha_inicio_acta = '".trim($_POST["initDate"])."', fecha_fin_acta = '".trim($_POST["endDate"])."', acta = ".trim($_POST["payment"]).", departamento = '".$_POST["department"]."', proceso = '".$_POST["process"]."', tecnologia = '".$_POST["tech"]."', contrato = '".$_POST["responsible"]."', valor = ".$_POST["value"]." WHERE id = ".$id;   
						$resultUpdate = mysqli_query($db, $sqlUpdate);
						echo "<script type='text/javascript'>";
							echo "window.location = 'load_budget_ex.php';";
						echo "</script>";
					} else {
						$error1 = true;
					}
				}	
				
				// Table.
				$sqlTable = "SELECT id, acta, fecha_inicio_acta, fecha_fin_acta, departamento, proceso, tecnologia, contrato, valor FROM ejecucion_presupuesto WHERE id = ".$id;
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
			<form id="update_ex" method="POST" action="update_ex.php">
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
				<table id="ex" class="display">
					<?php
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
						echo "<tr>";
							echo "<td align='center'><input type='text' id='payment' name='payment' maxlenght='2' value='".$result_table[0]["acta"]."' onkeypress='return validateNumber(event)' style='width:50px'></td>";
							echo "<td align='center'><input type='text' id='initDate' name='initDate' maxlenght='10' value='".$result_table[0]["fecha_inicio_acta"]."' onkeypress='return validateDate(event)' style='width:75px'></td>";
							echo "<td align='center'><input type='text' id='endDate' name='endDate' maxlenght='10' value='".$result_table[0]["fecha_fin_acta"]."' onkeypress='return validateDate(event)' style='width:75px'></td>";
							echo "<td align='center'>";
								echo "<select id='department' name='department' style='width:140px;'>";
									for ($i = 0; $i < count($department); $i++) {                         
										if ($department[$i] == $result_table[0]["departamento"])
											echo "<option value='".$department[$i]."' selected>".$department[$i]."</option>";
										else	 
											echo "<option value='".$department[$i]."'>".$department[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'>";
								echo "<select id='process' name='process' style='width:100px;'>";
									for ($i = 0; $i < count($process); $i++) {                         
										if ($process[$i] == $result_table[0]["proceso"])
											echo "<option value='".$process[$i]."' selected>".$process[$i]."</option>";
										else	 
											echo "<option value='".$process[$i]."'>".$process[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'>";
								echo "<select id='tech' name='tech' style='width:85px;'>";
									for ($i = 0; $i < count($tech); $i++) {                         
										if ($tech[$i] == $result_table[0]["tecnologia"])
											echo "<option value='".$tech[$i]."' selected>".$tech[$i]."</option>";
										else	 
											echo "<option value='".$tech[$i]."'>".$tech[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'>";
								echo "<select id='responsible' name='responsible' style='width:85px;'>";
									for ($i = 0; $i < count($responsible); $i++) {                         
										if ($responsible[$i] == $result_table[0]["contrato"])
											echo "<option value='".$responsible[$i]."' selected>".$responsible[$i]."</option>";
										else	 
											echo "<option value='".$responsible[$i]."'>".$responsible[$i]."</option>";
									}
								echo "</select>";
							echo "</td>";
							echo "<td align='center'><input type='text' id='value' name='value' value='".$result_table[0]["valor"]."' onkeypress='return validateValue(event)' style='width:100px'></td>";
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
                var table = $('#ex').DataTable({
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
                document.getElementById("update_ex").submit();
            }		
		</script>
    </body>
</html>