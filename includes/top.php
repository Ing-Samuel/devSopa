<?php
session_start();
// We use this segment of code, to play with the information displayed on the 
// page according to the role.

echo "<ul class='menu'>";
    echo "<li><a href='http://localhost/devSopaV22/scripts/home/main.php'>Home</a>";
    echo "</li>";
	if (isset($_SESSION["rol"])) {
		if ($_SESSION["rol"] == "AG") {
			echo "<li><a href='#'>Administrar I</a>";
				echo "<ul>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/traffic.php'>Web Page Traffic</a></li>";
					echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/users.php'>Users Admon</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_materiales_exp.php'>Load Exp. Warehouses</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_materiales_op.php'>Load Op. Warehouses</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_pm.php'>Load PM</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_material.php'>Load Materials</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_budget_ex.php'>Load Budget Ex.</a></li>";
				echo "</ul>";
			echo "</li>";
			echo "<li><a href='#'>Administrar II</a>";
				echo "<ul>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_cable_rollout.php'>Load Cable Rollout</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_cable_projects.php'>Load Cable Projects</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_gpon_rollout.php'>Load GPON Rollout</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_gpon_etp_rollout.php'>Load GPON ETP Rollout</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_fibra_rollout.php'>Load F.O Rollout</a></li>";
					// echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_hfcb2b_rollout.php'>Load HFCB2B Rollout</a></li>";
					//echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_pqr.php'>Load PQR</a></li>";
				echo "</ul>";
			echo "</li>";
			// echo "<li><a href='#'>Informes OWS 2022</a>";
			// 	echo "<ul>";
			// 		echo "<li><a href='http://localhost/PryWeb/devSopa/scripts/ows/CargaDatosSNR.php'>Exporte de OWS</a></li>";
			// 		echo "<li><a href='http://localhost/PryWeb/devSopa/scripts/ows/GraficaTTSNR.php'>Tiquetes Técnicos SNR</a></li>";
			// 		echo "<li><a href='http://localhost/PryWeb/devSopa/scripts/ows/Grafica_ttSNR_vs_Regional.php'>OWS_SNR_FEC_vs_Regionales</a></li>";
			// 	echo "</ul>";
			// echo "</li>";
			echo "<li><a href='#'>Dashboard MTTR & HOLD</a>";
				echo "<ul>";
					echo "<li><a href='http://localhost/devSopaV22/scripts/ows/front-cargaDatos-Incidente-tt-crd.php'> Exporte Datos Ows</a></li>";
					echo "<li><a href='http://localhost/devSopaV22/scripts/ows/graficaBackLog-SNR.php'>BACKLOG SNR</a></li>";
				echo "</ul>";
			echo "</li>";
		}
	}
    echo "<li><a href='../../includes/logout.php'>Cerrar Sesión</a></li>";
echo "</ul>";    

?>