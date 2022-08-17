<?php
session_start();
// We use this segment of code, to play with the information displayed on the 
// page according to the role.

echo "<ul class='menu'>";
    echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/home/main.php'>Home</a>";
    echo "</li>";
    if (($_SESSION["rol"] == "AG") || ($_SESSION["rol"] == "EO") || ($_SESSION["rol"] == "E")) {
        echo "<li><a href='#'>Cable</a>";
            echo "<ul>";
                echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/cable_rollout.php'>Cable Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/cable_pendings.php'>Cable Pendings</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/cable_projects.php'>Cable Projects</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/warehouses_exp.php'>Warehouses Report</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/resources.php'>Resources</a></li>";
				echo "<li><a href='http://d-sinac/scripts/expansion/expansion7.php'>Budget Execution</li>";
				echo "<li><a href='#'>Budget Execution</li>";
            echo "</ul>";
        echo "</li>";
		echo "<li><a href='#'>B2B</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/fibra_rollout.php'>F.O Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/gpon_rollout.php'>GPON Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/gpon_etp_rollout.php'>GPON ETP Rollout</a></li>";					
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/hfcb2b_rollout.php'>HFC B2B Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/expansion/fibra_pendings.php'>F.O Pendings</a></li>";
			echo "</ul>";
        echo "</li>";
    }
    if ((($_SESSION["rol"] == "AG") || ($_SESSION["rol"] == "EO") || ($_SESSION["rol"] == "O")) && ($_SESSION["empresa"] != "Huawei")) {
        echo "<li><a href='#'>Operación</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/budget.php'>Budget Execution</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/budget_new.php'>Budget Execution HUAWEI</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/op_pendings.php'>O&M Pendings</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/pm.php'>PM Report</a></li>";
                echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/snr.php'>SNR</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/tiquetes_tecnicos.php'>Other TTs</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/warehouses_op.php'>Warehouses Report</a></li>";
				//echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/warehouse_almaviva.php'>Almaviva Report</a></li>";
            echo "</ul>";
        echo "</li>";
		/*echo "<li><a href='#'>Otros Operación</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/pqr.php'>PQRs</a></li>";
			echo "</ul>";
        echo "</li>";*/
    }
	if ((($_SESSION["rol"] == "AG") || ($_SESSION["rol"] == "EO") || ($_SESSION["rol"] == "O")) && ($_SESSION["empresa"] == "Huawei")) {
        echo "<li><a href='#'>Operación</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/op_pendings.php'>O&M Pendings</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/pm.php'>PM Report</a></li>";
                echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/snr.php'>SNR</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/tiquetes_tecnicos.php'>Other TTs</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/warehouses_op.php'>Warehouses Report</a></li>";
				//echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/warehouse_almaviva.php'>Almaviva Report</a></li>";
            echo "</ul>";
        echo "</li>";
		/*echo "<li><a href='#'>Otros Operación</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/operacion/pqr.php'>PQRs</a></li>";
			echo "</ul>";
        echo "</li>";*/
    }
	if (($_SESSION["rol"] == "AG") || ($_SESSION["rol"] == "EO") || ($_SESSION["rol"] == "O")) {
		echo "<li><a href='#'>PathTrak</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/pathtrak/knowledge/casos.php'>Knowledge</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/pathtrak/knowledge/faq.php'>FAQs & Tips </a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/pathtrak/knowledge/info.php'>A cerca de </a></li>";
            echo "</ul>";
        echo "</li>";
	}
    if ($_SESSION["rol"] == "AG") {
        echo "<li><a href='#'>Administrar I</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/traffic.php'>Web Page Traffic</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/users.php'>Users Admon</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_materiales_exp.php'>Load Exp. Warehouses</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_materiales_op.php'>Load Op. Warehouses</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_pm.php'>Load PM</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_material.php'>Load Materials</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_budget_ex.php'>Load Budget Ex.</a></li>";
            echo "</ul>";
        echo "</li>";
    }
	if ($_SESSION["rol"] == "AG") {
		echo "<li><a href='#'>Administrar II</a>";
            echo "<ul>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_cable_rollout.php'>Load Cable Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_cable_projects.php'>Load Cable Projects</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_gpon_rollout.php'>Load GPON Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_gpon_etp_rollout.php'>Load GPON ETP Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_fibra_rollout.php'>Load F.O Rollout</a></li>";
				echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_hfcb2b_rollout.php'>Load HFCB2B Rollout</a></li>";
				//echo "<li><a href='http://netvm-pnoc01/devSopa/scripts/administracion/load_pqr.php'>Load PQR</a></li>";
            echo "</ul>";
        echo "</li>";
	}
    echo "<li><a href='../../includes/logout.php'>Cerrar Sesión</a></li>";
echo "</ul>";    

?>

