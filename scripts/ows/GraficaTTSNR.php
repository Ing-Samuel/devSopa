<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


    <link rel="icon" href="../../images/favicon.ico" />

    <!--Load Style Sheet-->

    <link rel="stylesheet" type="text/css" href="../../css/main4.css">

    <link rel="stylesheet" type="text/css" href="practica.css">

    <title>:: TT_TroubleTicket_Export ::</title>
</head>

<body>
    <div class="flex-content">
        <div>
            <?php
            //error_reporting("E_ALL ^ E_NOTICE ^ E_WARNING");

            // Verifying User's Session.
            //session_start();
            echo "<header>";
            echo "<center><img src='../../images/cabezote.png'></center>";
            include("../../includes/top.php");
            echo "</header>";
            if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] == "O")) {
                echo "<script type='text/javascript'>";
                echo "window.location = '../../index.php';";
                echo "</script>";
            }


            // DB Parameters.
            $mysql_host = "localhost";
            $mysql_user = "root";
            $mysql_password = "";
            $mysql_database = "devsopa";

            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            // Verifying POST Variables.
            if (isset($_POST["fechaCreacion"]) || isset($_POST["Region"])) {
                $anyoCreacion = $_POST["anyo"];
                $fechaCreacion = $_POST["fechaCreacion"];
                $Region = $_POST["Region"];
                // $TipoOrden = $_POST["TipoOrden"];
            } else {

                $anyoCreacion = "ALL";
                $fechaCreacion = "ALL";
                $Region = "ALL";
                // $TipoOrden = "ALL";
            }


            //FILTER DINAMICO GENERAL
            // $arrayFilter = array(" tt.`Fecha de Creación` = '" . $fechaCreacion . "'", " tt.`Región` = '" . $Region . "'", " tt.`Tipo de Orden` = '" . $TipoOrden . "'");
            // $filter = "";
            // for ($i = 0; $i < count($arrayFilter); $i++) {
            //     if (strpos($arrayFilter[$i], "ALL") == FALSE) {
            //         if (strlen($filter) > 0)
            //             $filter = $filter . " AND " . $arrayFilter[$i];
            //         else
            //             $filter = $filter . " WHERE " . $arrayFilter[$i];
            //     }
            // }

            //---------------------------------------------------------------------------------------------------------------------------------

            // LLENAR LOS SELECTS DINAMICAMENTE BASADO EN OTROS SELECTS

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, \"%d-%m-%Y\") LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");
            $filterRegion = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {
                if (strpos($arrayFilter[$i], "ALL") == FALSE) {
                    if (strlen($filterRegion) > 0)
                        $filterRegion = $filterRegion . " AND " . $arrayFilter[$i];
                    else
                        $filterRegion = $filterRegion . " WHERE " . $arrayFilter[$i];
                }
            }

            //REGION
            $sqlRegionSelect = "SELECT DISTINCT(tt.Región) FROM `tt_troubleticket_export` as tt" . $filterRegion . " ORDER BY tt.Región";
            $resultRegionSelect = mysqli_query($db, $sqlRegionSelect);
            if (mysqli_num_rows($resultRegionSelect)) {
                while ($data = mysqli_fetch_array($resultRegionSelect)) {
                    $RegionSelect[] = $data;
                }
            }



            // $arrayFilter = array(" date_format(tt.`Fecha de Creación`, \"%d-%m-%Y\") LIKE '" . $fechaCreacion . "%'", " tt.`Región` = '" . $Region . "'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");
            // $filterTipoOrden = "";
            // for ($i = 0; $i < count($arrayFilter); $i++) {
            //     if (strpos($arrayFilter[$i], "ALL") == FALSE) {
            //         if (strlen($filterTipoOrden) > 0)
            //             $filterTipoOrden = $filterTipoOrden . " AND " . $arrayFilter[$i];
            //         else
            //             $filterTipoOrden = $filterTipoOrden . " WHERE " . $arrayFilter[$i];
            //     }
            // }
            //TIPO DE ORDEN
            // $sqlOrdenSelect = "SELECT DISTINCT(tt.`Tipo de Orden`) FROM `tt_troubleticket_export` as tt" . $filterTipoOrden . " ORDER BY tt.`Tipo de Orden`";
            // $resultOrdenSelect = mysqli_query($db, $sqlOrdenSelect);
            // if (mysqli_num_rows($resultOrdenSelect)) {
            //     while ($data = mysqli_fetch_array($resultOrdenSelect)) {
            //         $TipoOrdenSelect[] = $data;
            //     }
            // }

            //echo "<script>console.log('" . addslashes($sqlOrdenSelect) . "')</script>";

            //Filtro Fecha Registro
            $arrayFilter = array(" tt.`Región` = '" . $Region . "'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");

            $filterFechaRegistro = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterFechaRegistro) > 0)
                        $filterFechaRegistro = $filterFechaRegistro . " AND " . $arrayFilter[$i];
                    else
                        $filterFechaRegistro = $filterFechaRegistro . " WHERE " . $arrayFilter[$i];
                }
            }


            //FECHAS DE REGISTRO
            $sqlFechaSelect = "SELECT DISTINCT(date_format(tt.`Fecha de Creación`, \"%d-%M\")) FROM `tt_troubleticket_export` as tt" . $filterFechaRegistro .
                " ORDER BY tt.`Fecha de Creación`";

            $resultFechaSelect = mysqli_query($db, $sqlFechaSelect);

            if (mysqli_num_rows($resultFechaSelect)) {

                while ($data = mysqli_fetch_row($resultFechaSelect)) {

                    $FechaSelect[] = $data;
                }
            }


            //AÑO DE REGISTRO PARA EL SELECT
            $sqlAnyoSelect = "SELECT DISTINCT(date_format(tt.`Fecha de Creación`, \"%Y\")) FROM `tt_troubleticket_export` as tt ORDER BY tt.`Fecha de Creación`";
            $resultAnyoSelect = mysqli_query($db, $sqlAnyoSelect);
            if (mysqli_num_rows($resultAnyoSelect)) {
                while ($data = mysqli_fetch_row($resultAnyoSelect)) {
                    $AnyoSelect[] = $data;
                }
            }

            mysqli_close($db);
            ?>
        </div>


        <form action="GraficaTTSNR.php" method="POST" id="form-data">

            <label for="fechaCreacion">Año</label>
            <select name="anyo" id="anyoCreacionSeelect">
                <option value="ALL">ALL</option>
                <?php
                for ($i = 0; $i < count($AnyoSelect); $i++) {

                    if ($AnyoSelect[$i][0] == $anyoCreacion) {

                        echo "<option value = '" . $AnyoSelect[$i][0] . "' selected>" . $AnyoSelect[$i][0] . "</option>";
                    } else {

                        echo "<option value = '" . $AnyoSelect[$i][0] . "'>" . $AnyoSelect[$i][0] . "</option>";
                    }
                }
                ?>

            </select>

            <label for="fechaCreacion">Fecha de Creación: </label>
            <select name="fechaCreacion" id="fechaCreacionSeelect">
                <option value="ALL">ALL</option>
                <?php
                for ($i = 0; $i < count($FechaSelect); $i++) {

                    if ($FechaSelect[$i][0] == $fechaCreacion) {

                        echo "<option value = '" . $FechaSelect[$i][0] . "' selected>" . $FechaSelect[$i][0] . "</option>";
                    } else {

                        echo "<option value = '" . $FechaSelect[$i][0] . "'>" . $FechaSelect[$i][0] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="Region">Region: </label>
            <select name="Region" id="regionSelect">
                <option value="ALL">ALL</option>
                <?php

                for ($i = 0; $i < count($RegionSelect); $i++) {

                    if ($RegionSelect[$i][0] == $Region) {
                        echo "<option value = '" . $RegionSelect[$i][0] . "' selected>" . $RegionSelect[$i][0] . "</option>";
                    } else {
                        echo "<option value = '" . $RegionSelect[$i][0] . "'>" . $RegionSelect[$i][0] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
        <h4>Tiquetes Técnicos SNR</h4>
        <div class="size-chart">
            <canvas id="myChart"></canvas>
        </div>
    </div>


    <?php
    error_reporting("E_ALL ^ E_NOTICE ^ E_WARNING");
    if ($anyoCreacion == "ALL") {
        $generalData[] = null;
    } else {
        //-------------------------------------------------------CONSULTA PARA LOS LABELS EN LA AXISA X-----------------------------------------------
        #region
        $mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password = "";
        $mysql_database = "devsopa";

        $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

        $arrayFilter = array(" date_format(tt.`Fecha de Creación`,'%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`,'%Y') LIKE '" . $anyoCreacion . "%'");

        $filterGraficasUno = "";

        for ($i = 0; $i < count($arrayFilter); $i++) {
            if (strpos($arrayFilter[$i], "ALL") == FALSE) {
                if (strlen($filterGraficasUno) > 0) {
                    $filterGraficasUno = $filterGraficasUno . " AND " . $arrayFilter[$i];
                } else {
                    $filterGraficasUno = $filterGraficasUno . " WHERE " . $arrayFilter[$i];
                }
            }
        }


        $queryLabelData = "SELECT DISTINCT(date_format(`Fecha de Creación`, '%d-%M')) FROM `tt_troubleticket_export` as tt" . $filterGraficasUno . " ORDER BY `Fecha de Creación`";

        $numRow = mysqli_query($db, $queryLabelData);

        $labelData[] = array();
        $i = 0;
        if (mysqli_num_rows($numRow)) {

            while ($data = mysqli_fetch_row($numRow)) {

                $labelData[$i] = $data;
                $i++;
            }
        }

        #endregion
        //------------------------------------------------------------------------------------------------------------------------------



        //---------------------------------------------------FILTER---------------------------------------------------------
        $arrayFilter = array(" tt.`Región` = '" . $Region . "'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");
        $filterGraficasUno = "";

        for ($i = 0; $i < count($arrayFilter); $i++) {
            if (strpos($arrayFilter[$i], "ALL") == FALSE) {
                $filterGraficasUno = $filterGraficasUno . " AND " . $arrayFilter[$i];
            }
        }

        //----------------------------------------------CONSULTA DE DATOS TT_PROBLEMA_SNR------------------------------------------------
        #region

        $dataTTPSNR[] = array();

        $filterFecha = array_values($labelData);
        $filterFecha = array_values($filterFecha);

        for ($i = 0; $i < count($labelData); $i++) {

            $queryDataTTPSNR = "SELECT COUNT(`ID Tiquete`) FROM `tt_troubleticket_export` as tt WHERE `Segmento` = 'TIQUETE PROBLEMA' AND date_format(`Fecha de Creación`,'%d-%M') = '" . addslashes($filterFecha[$i][0]) . "' " . $filterGraficasUno;

            //echo "<script>console.log('" . addslashes($queryDataTTPSNR) . "')</script>";

            $numRow = mysqli_query($db, $queryDataTTPSNR);

            if (mysqli_num_rows($numRow)) {
                while ($data = mysqli_fetch_row($numRow)) {
                    $dataTTPSNR[$i] = $data;
                }
            }
        }


        #endregion
        //----------------------------------------------CONSULTA DE DATOS TT_SNR----------------------------------------------------------
        #region
        for ($i = 0; $i < count($labelData); $i++) {
            $queryDataTT_SNR = "SELECT COUNT(`ID Tiquete`)
                FROM `tt_troubleticket_export` as tt
                WHERE `Tipo de Orden` = 'TT_SNR' AND date_format(`Fecha de Creación`,'%d-%M') = '" . addslashes($filterFecha[$i][0]) . "' " .$filterGraficasUno."
                GROUP BY date_format(`Fecha de Creación`,'%d-%m-%Y')
                ORDER BY `Fecha de Creación`";

            $numRow = mysqli_query($db, $queryDataTT_SNR);

            $dataTT_SRN[] = array();

            if (mysqli_num_rows($numRow)) {
                while ($data = mysqli_fetch_row($numRow)) {
                    $dataTT_SRN[$i] = $data;
                }
            }
        }
        #endregion

        // echo "<script>console.log('$dataTTPSNR')</script>";
        // echo "<script>console.log('$dataTT_SRN')</script>";


        $generalData[] = [$labelData, $dataTTPSNR, $dataTT_SRN];
    }



    mysqli_close($db);
    #endregion
    ?>

    <script>
        //const labels = ["enero", "febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio"];

        //Paso la Matriz con los datos de php a una variable JS
        let generalData;
        generalData = <?php echo json_encode($generalData); ?>;
        console.log(generalData);
        //Desestructuro los datos
        let labels, tt_p_snr, tt_snr;

        labels = generalData[0][0];
        tt_p_snr = generalData[0][1];
        tt_snr = generalData[0][2];

        console.log(labels);
        console.log(tt_p_snr);
        console.log(tt_snr);

        //Mapeo y Convierto los Arrays de String a Numeros Enteros, como son conteos precisos los paso a INT, tener en cunata para valores flotantes

        tt_p_snr = tt_p_snr.map(elemento => {
            return parseInt(elemento);
        });

        tt_snr = tt_snr.map(elemento => {
            return parseInt(elemento);
        });



        const data = {
            labels: labels,
            datasets: [{
                    label: 'TIQUETE PROBLEMA_SNR',
                    data: tt_p_snr,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                },
                {
                    label: 'TT_SNR',
                    data: tt_snr,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                },
                // { DATOS DE PRUEBA
                //     label: 'My First Dataset',
                //     data: [1, 100, 25],
                //     backgroundColor: [
                //         'rgba(255, 99, 132, 0.2)',
                //         'rgba(255, 159, 64, 0.2)',
                //         'rgba(255, 205, 86, 0.2)',
                //         'rgba(75, 192, 192, 0.2)',
                //         'rgba(54, 162, 235, 0.2)',
                //         'rgba(153, 102, 255, 0.2)',
                //         'rgba(201, 203, 207, 0.2)'
                //     ],
                //     borderColor: [
                //         'rgb(255, 99, 132)',
                //         'rgb(255, 159, 64)',
                //         'rgb(255, 205, 86)',
                //         'rgb(75, 192, 192)',
                //         'rgb(54, 162, 235)',
                //         'rgb(153, 102, 255)',
                //         'rgb(201, 203, 207)'
                //     ],
                //     borderWidth: 1
                // }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        //El eje de Y inicia en 0
                        beginAtZero: true
                    },
                    x: {
                        //Inician los valores desde Enero
                        //min: 'enero'
                    }
                },

                plugins: {
                    legend: {
                        display: true,
                    }
                },

                interaction: {
                    mode: 'index'
                }
            },
        };

        const chart = new Chart(
            document.getElementById("myChart"),
            config
        );
    </script>
    <script src="tt_troubleticket_export.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                scrollX: true,
                stateSave: true,
                scrollY: '50vh',
            });
        });
    </script>
</body>

</html>