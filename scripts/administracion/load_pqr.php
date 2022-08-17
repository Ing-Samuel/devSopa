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
		
		<style>
		  td.details-control {
			  background: url('../../images/open.png') no-repeat center center;
			  cursor: pointer;
		  }
		  tr.shown td.details-control {
			  background: url('../../images/close.png') no-repeat center center;
		  }
        </style> 
    </head>
    <body>
        <div>
            <?php
                // Verifying User's Session.
				echo "<center><img src='../../images/cabezote.png'></center>";
				include("../../includes/top.php");
				if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] == "E")) {
                    echo "<script type='text/javascript'>";
                        echo "window.location = '../../index.php';";
                    echo "</script>";                    
                }
                
				
				$typeActivity = array("New PQR", "Update PQR");
				//$error1 = false;
				$error2 = false;
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
                
				// Verifying POST Variables.
                if (isset($_POST["activity"])) {
					$activity = $_POST["activity"];
                } else {
					$activity = "New PQR";
                }
				
				if (isset($_POST["save"])) {
					if (!($_POST["address"] == "") || !($_POST["city"] == "") || !($_POST["emailDate"] == "")) {
						$sqlInsert = "INSERT INTO pqr (fecha_creacion, id_tarea, isp, prioridad, activo, fecha_correo, direccion, ciudad) 
								      VALUES ('".$date."', '".$_POST["task"]."', '".$_POST["isp"]."', '".$_POST["priority"]."', 1, '".$_POST["emailDate"]."', '".$_POST["address"]."', '".$_POST["city"]."')";
						$resultInsert = mysqli_query($db, $sqlInsert);	
						//echo $sqlInsert;
					} else {
						$error2 = true;
					}
				}	
				
				// Selects.
				$sqlTasks = "SELECT id_tarea, tarea FROM tareas_pqr ORDER BY id_tarea";
				$resultTasksSelect = mysqli_query($db, $sqlTasks);
                if (mysqli_num_rows($resultTasksSelect)) {
                    while ($data = mysqli_fetch_array($resultTasksSelect)) {
                        $taskSelect[] = $data;    
                    }
                } else {
					$taskSelect = array();
				}

				$sqlIsp = "SELECT id_isp, isp FROM pqr_isp ORDER BY id_isp";
				$resultIspSelect = mysqli_query($db, $sqlIsp);
                if (mysqli_num_rows($resultIspSelect)) {
                    while ($data = mysqli_fetch_array($resultIspSelect)) {
                        $ispSelect[] = $data;    
                    }
                } else {
					$ispSelect = array();
				}				
				
				$priority = array("1" => "Urgente", "2" => "Media", "3" => "Normal");
				$activo = array("1" => "Si", "0" => "No");
				
				// Table.
				$sqlTable = "SELECT tareas_pqr.tarea, pqr.id, pqr.prioridad, pqr.activo, pqr.fecha_correo, DATEDIFF(CURRENT_TIMESTAMP, pqr.fecha_correo) as dias, pqr.direccion, pqr.ciudad, pqr_isp.isp 
							 FROM pqr LEFT JOIN tareas_pqr ON pqr.id_tarea = tareas_pqr.id_tarea LEFT JOIN pqr_isp ON pqr.isp = pqr_isp.id_isp
							 WHERE pqr.activo = 1 ORDER BY dias DESC";
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
            <center><h3>LOAD PQR</h3>
            <br>
			<form id="load_pqr" method="POST" action="load_pqr.php">
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
				&nbsp
				<input type="submit" name="save" value="Save" />
				<?php
					/*if ($error1) {
						echo "<br />";
						echo "<br />";
						echo "<table><tr>"
						    ."<td><img src='../../images/error-icon.png'></td>"
							."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Usuario ya Registrado</td>"
							."</tr></table>";				
					} */
					
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
				<div style='width:1100px;'>
				<!--table id="allpqr" class="display"-->
					<?php
						if ($activity == "New PQR") {
							echo "<table id='pqr' class='display'>";
							echo "<thead>";
								echo "<tr>";
									echo "<th>Task</th>";
									echo "<th>ISP</th>";
									echo "<th>Priority</th>";
									echo "<th>Active?</th>";
									echo "<th>E-mail Date <br />[AAAA/MM/DD]</th>";
									echo "<th>Address</th>";
									echo "<th>City</th>";	
								echo "</tr>";								
							echo "</thead>";
							echo "<tbody>";
							
							echo "<tr>";
								echo "<td align='center'>";
									echo "<select id='task' name='task' style='width:200x;'>";
										for ($i = 0; $i < count($taskSelect); $i++) {
											echo "<option value='".$taskSelect[$i]["id_tarea"]."'>".$taskSelect[$i]["tarea"]."</option>";
										}
									echo "</select>";
								echo "</td>";
								echo "<td align='center'>";
									echo "<select id='isp' name='isp' style='width:150x;'>";
									for ($i = 0; $i < count($ispSelect); $i++) {
										echo "<option value='".$ispSelect[$i]["id_isp"]."'>".$ispSelect[$i]["isp"]."</option>";
									}
								echo "</select>";
								echo "</td>";
							
								echo "<td align='center'>";
									echo "<select id='priority' name='priority' style='width:100x;'>";
										for ($i = 1; $i < count($priority)+1; $i++) {
											echo "<option value='".$i."'>".$priority[$i]."</option>";
										}
									echo "</select>";
								echo "</td>";

								echo "<td align='center'>Si";
									/*echo "<select id='active' name='active' style='width:80x;'>";
										for ($i = 0; $i < count($activo); $i++) {
											echo "<option value='".$i."'>".$activo[$i]."</option>";
										}
									echo "</select>";*/
								echo "</td>";							
							
								echo "<td align='center'><input type='text' id='emailDate' name='emailDate' maxlenght='10' onkeypress='return validateDate(event)' style='width:80px'></td>";
								echo "<td align='left'><input type='text' placeholder='Address' id='address' name='address' maxlenght='50' onkeypress='return validateAddress(event)' style='width:200px'></td>";
								echo "<td align='left'><input type='text' placeholder='City' id='city' name='city' maxlenght='15' onkeypress='return validateCity(event)' style='width:150px'></td>";
							echo "</tr>";
							echo "</tbody>";
							echo "</table>";
						} else {
							echo "<table id='allpqr' class='display'>";
							echo "<thead>";
								echo "<tr>";
									echo "<th>Task</th>";
									echo "<th>ISP</th>";
									echo "<th>Priority</th>";
									echo "<th>Active?</th>";
									echo "<th>E-mail Date <br />[AAAA/MM/DD]</th>";
									echo "<th>Address</th>";
									echo "<th>City</th>";
									echo "<th>Option</th>";				
								echo "</tr>";								
							echo "</thead>";
							echo "<tbody>";
							for ($i = 0; $i < count($result_table); $i++) {
								echo "<tr align='center'>";	
									echo "<td>".$result_table[$i]["tarea"]."</td><td>".$result_table[$i]["isp"]."</td><td>".$priority[$result_table[$i]["prioridad"]]."</td><td>".$activo[$result_table[$i]["activo"]]."</td><td>".$result_table[$i]["fecha_correo"]."</td><td>".$result_table[$i]["direccion"]."</td><td>".$result_table[$i]["ciudad"]."</td><td><a href='update_pqr.php?id=".$result_table[$i]["id"]."'>Change</a></td>";
								echo "</tr>";    
							}
							echo "</tbody>";
							echo "</table>";
						}
					?>
					<!--/tbody-->    
				<!--/table-->
				</div>
			</form></center>
            <br />
        </div>
               
        <script type="text/javascript">
			function validateDate(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==47) return true; // Slash.
				
				pattern = /[0-9]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateAddress(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				if (key==32) return true; // Space Bar.
				if (key==35) return true; // Symbol (#).
				if (key==45) return true; // Symbol (-).
				if (key==48) return true; // 0.
				if (key==49) return true; // 1.
				if (key==50) return true; // 2.
				if (key==51) return true; // 3.
				if (key==52) return true; // 4.
				if (key==53) return true; // 5.
				if (key==54) return true; // 6.
				if (key==55) return true; // 7.
				if (key==56) return true; // 8.
				if (key==57) return true; // 9.
				
				pattern = /[aA-zZ]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			function validateCity(e) {
				key = (document.all) ? e.keyCode : e.which;
				
				pattern = /[aA-zZ]/ // Pattern.
				keyPressed = String.fromCharCode(key);
				return pattern.test(keyPressed); // Test.
			}
			
			$(document).ready(function() {
                var table = $('#pqr').DataTable({
					"paging": false,
					"searching": false,
				});
	        });
			
			$(document).ready(function() {
                var table = $('#allpqr').DataTable();
	        });
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("load_pqr").submit();
            }		
		</script>
    </body>
</html>
