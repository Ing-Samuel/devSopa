<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
		<link rel="icon" href="../../images/favicon.ico" />
        
        <!--Load Style Sheet-->
        <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/jquery.jqplot.min.css" />
        <link rel="stylesheet" type="text/css" href="../../css/main4.css">
                
        <!--Load jQuery Library-->
        <script type="text/javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../../js/jquery.jqplot.min.js"></script>
		
		<!--Load Extra Plugins jQplot-->
        <script type="text/javascript" src="../../js/plugins/jqplot.canvasTextRenderer.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
		<script type="text/javascript" src="../../js/plugins/jqplot.highlighter.min.js"></script>
		<script type="text/javascript" src="../../js/plugins/jqplot.cursor.min.js"></script>
		<script type='text/javascript' src="../../js/plugins/jqplot.dateAxisRenderer.min.js"></script>

		<!--CSS Charts-->
		<style>
		  .jqplot-title {
              font-family: Calibri;
              font-size: 18px;
          }
          
          .jqplot-axis {
              font-family: Calibri;
              font-size: 16px;
          }
        </style>            
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
				
				$months = array("01"=>"Enero", "02"=>"Febrero", "03"=>"Marzo", "04"=>"Abril", "05"=>"Mayo", "06"=>"Junio", "07"=>"Julio", "08"=>"Agosto", "09"=>"Septiembre", "10"=>"Octubre", "11"=>"Noviembre", "12"=>"Diciembre");
                $typeAnalysis = array("By Month", "Historic");
                
                // DB Parameters.
                $mysql_host = "netvm-pnoc01";
                $mysql_user = "gestion";
                $mysql_password = "gestion";
                $mysql_database = "devsopa";
                
                $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
                
				// Verifying POST Variables.
                if (isset($_POST["analysis"]) || isset($_POST["year"])) {
					$analysis = $_POST["analysis"];
                    $year = $_POST["year"];
                } else {
					$analysis = "By Month";
                    $year = 2015;
                }
				
				$sqlLastMonth = "SELECT DISTINCT(DATE_FORMAT(fecha_visita, '%m')) AS mes FROM visitas ORDER BY mes DESC LIMIT 1";
				$resultLastMonth = mysqli_query($db, $sqlLastMonth);
                if (mysqli_num_rows($resultLastMonth)) {
                    $data = mysqli_fetch_array($resultLastMonth);
                    $month = $months[$data["mes"]];
                }
                
                if (($analysis == "By Month") && (isset($_POST["month"]))) {
                    $month = $_POST["month"];    
                }
		
				// Months.
                $sqlMonth = "SELECT DISTINCT(DATE_FORMAT(fecha_visita, '%m')) AS mes FROM visitas WHERE usuario NOT LIKE 'jgallep' ORDER BY mes";
                $resultMonth = mysqli_query($db, $sqlMonth);
                if (mysqli_num_rows($resultMonth)) {
                    while ($data = mysqli_fetch_array($resultMonth)) {
                        $monthSelect[] = $data["mes"];    
                    }
                }

				// Year.
                $sqlYear = "SELECT DISTINCT(DATE_FORMAT(fecha_visita, '%Y')) AS year FROM visitas ORDER BY year";
                $resultYear = mysqli_query($db, $sqlYear);
                if (mysqli_num_rows($resultYear)) {
                    while ($data = mysqli_fetch_array($resultYear)) {
                        $yearSelect[] = $data["year"];    
                    }
                }

				$sqlLastReview = "SELECT DATE_FORMAT(fecha_visita, '%Y/%m/%d %H:%i') AS fecha FROM visitas ORDER BY fecha DESC LIMIT 1";
                $resultLastReview = mysqli_query($db, $sqlLastReview);
                $date = mysqli_fetch_array($resultLastReview);

				$filter = "";
				if ($analysis == "Historic") {
                    $filter = $filter." WHERE usuario NOT LIKE 'jgallep' AND fecha_visita LIKE '".$year."/%'";  				
				} else {
                    $filter = $filter." WHERE usuario NOT LIKE 'jgallep' AND fecha_visita LIKE '".$year."/".array_search($month, $months)."/%'";  
                }
				
				// Charts.
				$sqlchart1 = "SELECT DATE_FORMAT(fecha_visita, '%Y/%m/%d') AS fecha, COUNT(usuario) AS usuarios FROM visitas".$filter." GROUP BY fecha";
				$result = mysqli_query($db, $sqlchart1);
                if (mysqli_num_rows($result)) {
                    while ($data = mysqli_fetch_array($result)) {
                        $result_chart1[] = $data;    
                    }
                } else {
                    $result_chart1 = array();    
                }
				
				$sqlchart1 = "SELECT fecha, COUNT(usuario) AS usuarios FROM (SELECT DATE_FORMAT(fecha_visita, '%Y/%m/%d') AS fecha, usuario FROM visitas".$filter." GROUP BY fecha, usuario) AS search1 GROUP BY fecha";
				$result = mysqli_query($db, $sqlchart1);
                if (mysqli_num_rows($result)) {
                    while ($data = mysqli_fetch_array($result)) {
                        $result_chart2[] = $data;    
                    }
                } else {
                    $result_chart2 = array();    
                }		

                mysqli_close($db);
            ?>
        </div>
         
        <div id="main">
            <center><h3>TRAFFIC REPORT</h3>
			<h5>(Último Ingreso: <?php echo $date["fecha"];?>)</h5>
            <br>
			<form id="traffic" method="POST" action="traffic.php">
				<label for="analysis" style="font-family: Calibri; color: #676767; font-size: 14px;">Analysis: </label>
                <select id="analysis" name="analysis" style="width:100px;" onchange="selected(document.getElementById('analysis'))">
					<?php
                        for ($i = 0; $i < count($typeAnalysis); $i++) {
                            if ($typeAnalysis[$i] == $analysis)
                                echo "<option value='".$typeAnalysis[$i]."' selected>".$typeAnalysis[$i]."</option>";
                            else 
                                echo "<option value='".$typeAnalysis[$i]."'>".$typeAnalysis[$i]."</option>";
                        }
                    ?>
                </select>
				&nbsp
				<label for="month" style="font-family: Calibri; color: #676767; font-size: 14px;">Month: </label>
                <select id="month" name="month" style="width:100px;" onchange="selected(document.getElementById('month'))">
					<?php
                        for ($i = 0; $i < mysqli_num_rows($resultMonth); $i++) {                           
                            if ($months[$monthSelect[$i]] == $month)
                                echo "<option value='".$months[$monthSelect[$i]]."' selected>".$months[$monthSelect[$i]]."</option>";
                            else 
                                echo "<option value='".$months[$monthSelect[$i]]."'>".$months[$monthSelect[$i]]."</option>";
                        }
                    ?>
                </select>                
                &nbsp
				<label for="year" style="font-family: Calibri; color: #676767; font-size: 14px;">Year: </label>
                <select id="year" name="year" style="width:60px;" onchange="selected(document.getElementById('year'))">
					<?php
                        for ($i = 0; $i < mysqli_num_rows($resultYear); $i++) {
                            if ($yearSelect[$i] == $year)
                                echo "<option value='".$yearSelect[$i]."' selected>".$yearSelect[$i]."</option>";
                            else 
                                echo "<option value='".$yearSelect[$i]."'>".$yearSelect[$i]."</option>";
                        }
                    ?>
                </select>
			</form>
			<br />
            <div id='chart1' style='height:450px; width:1100px;'></div>
			</center>
            <br />
        </div>
                
        <script type="text/javascript">
			$(document).ready(function() {
				// Linear Chart.
                var arrayChart1 = <?php echo json_encode($result_chart1);?>;
				var arrayChart2 = <?php echo json_encode($result_chart2);?>;
                var data1 = [];
				var data2 = [];
                for (var i = 0; i < arrayChart1.length; i++) {
                    data1.push(["\'"+arrayChart1[i]["fecha"]+"\'", parseInt(arrayChart1[i]["usuarios"])]);                    
                }
				
				for (var i = 0; i < arrayChart2.length; i++) {
                    data2.push(["\'"+arrayChart2[i]["fecha"]+"\'", parseInt(arrayChart2[i]["usuarios"])]);                    
                }
                       
                var options = {
                    title: 'VISITS vs DAY',
                    seriesColors: ['rgb(0,46,108)', 'rgb(222,52,52)'],
                    seriesDefaults: {rendererOptions: {smooth: true}},
					series: [{label: 'Visits'}, {label: 'Users'}],
                    axesDefaults: {labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
                    axes: {
                        xaxis: {
                            renderer: $.jqplot.DateAxisRenderer,
                            tickOptions: {formatString: '%b %d'}, 
                            label: 'Days'
                        },
                        yaxis: {label: 'Amount', min: 0}
                    },
					highlighter: {
						show: true,
						sizeAdjust: 7.5
					},
					legend: {
						show: true,
						placement: 'outsideGrid'
					},
					cursor: {
						show: true,
						showTooltip: false,
						zoom: true
					}
                };
                $.jqplot ('chart1', [data1, data2] ,options);
			});
	
			if (document.getElementById("analysis").options.selectedIndex == 1)
			    document.getElementById("month").disabled = true;
		    else
		        document.getElementById("month").disabled = false; 
			
			// Refreshing Web Page.
			function selected(selectobj1){
                document.getElementById("traffic").submit();
            }		
		</script>
    </body>
</html>
