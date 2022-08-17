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

    <title>:: OWS_SNR_&_FEC_x_Regionales ::</title>
</head>

<body>
    <div class="flex-content">
        <div>
            <?php
            #region valida la session
            error_reporting("E_ALL ^ E_NOTICE ^ E_WARNING");
            session_start();
            echo "<header>";
            echo "<center><img src='../../images/cabezote.png'></center>";

            include("../../includes/top.php");
            echo "</header>";
            if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] == "O")) {
                echo "<script type='text/javascript'>";
                echo "window.location = '../../index.php';";
                echo "</script>";
            }
            #endregion

            #region Recibir y validar variables
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
            }

            #endregion

            // DB Parameters.
            $mysql_host = "localhost";
            $mysql_user = "root";
            $mysql_password = "";
            $mysql_database = "devsopa";

            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            #region Datos para el select de Región
            //---------------------------------------------------------------------------------------------------------------------------------

            // LLENAR LOS SELECTS DINAMICAMENTE BASADO EN OTROS SELECTS

            // $arrayFilter = array(" date_format(tt.`Fecha de Creación`, \"%d-%m-%Y\") LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");
            // $filterRegion = "";

            // for ($i = 0; $i < count($arrayFilter); $i++) {
            //     if (strpos($arrayFilter[$i], "ALL") == FALSE) {
            //         if (strlen($filterRegion) > 0)
            //             $filterRegion = $filterRegion . " AND " . $arrayFilter[$i];
            //         else
            //             $filterRegion = $filterRegion . " WHERE " . $arrayFilter[$i];
            //     }
            // }

            // //REGION
            // $sqlRegionSelect = "SELECT DISTINCT(tt.Región) FROM `tt_troubleticket_export` as tt" . $filterRegion . " ORDER BY tt.Región";
            // $resultRegionSelect = mysqli_query($db, $sqlRegionSelect);
            // if (mysqli_num_rows($resultRegionSelect)) {
            //     while ($data = mysqli_fetch_array($resultRegionSelect)) {
            //         $RegionSelect[] = $data;
            //     }
            // }

            #endregion

            #region Datos para el select de FechaCreacion
            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'");

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
            #endregion

            #region Datos para el select de Año
            $sqlAnyoSelect = "SELECT DISTINCT(date_format(tt.`Fecha de Creación`, \"%Y\")) FROM `tt_troubleticket_export` as tt ORDER BY tt.`Fecha de Creación`";
            $resultAnyoSelect = mysqli_query($db, $sqlAnyoSelect);
            if (mysqli_num_rows($resultAnyoSelect)) {
                while ($data = mysqli_fetch_row($resultAnyoSelect)) {
                    $AnyoSelect[] = $data;
                }
            }

            mysqli_close($db);

            #endregion

            ?>
        </div>


        <form action="Grafica_ttSNR_vs_Regional.php" method="POST" id="form-data">

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
        </form>
        <h4>OWS - SNR & FEC x Regionales</h4>
        <div class="size-chart">
            <canvas id="myChart"></canvas>
        </div>
    </div>


    <?php

    if ($anyoCreacion == "ALL") {

        $generalData[] = array();

    } else {

        $mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password = "";
        $mysql_database = "devsopa";

        $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

        #region CONSULTA PARA LOS LABELS EN LA AXIS X

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
        if (mysqli_num_rows($numRow)) {
            $i = 0;
            while ($data = mysqli_fetch_row($numRow)) {
                $labelData[$i] = $data[0];
                $i++;
            }
        }

        #endregion

        #region FILTER
        $arrayFilter = array(" date_format(tt.`Fecha de Creación`, \"%Y\") LIKE '" . $anyoCreacion . "%'", "`Tipo de Orden` = 'TT_SNR'");
        $filterGraficasDos = "";

        for ($i = 0; $i < count($arrayFilter); $i++) {
            if (strpos($arrayFilter[$i], "ALL") == FALSE) {
                if (strlen($filterGraficasDos) > 0) {
                    $filterGraficasDos = $filterGraficasDos . " AND " . $arrayFilter[$i];
                } else {
                    $filterGraficasDos = $filterGraficasDos . " WHERE " . $arrayFilter[$i];
                }
            }
        }
        #endregion

        #region CONSULTA DATOS GENERAL
        function ConsultaDatosAxis($conection,$labelData,$filterGraficasDos,$region){

            $query = "SELECT COUNT(`ID Tiquete`), date_format(`Fecha de Creación`,'%d-%M'), `Región` FROM `tt_troubleticket_export` as tt ".$filterGraficasDos." GROUP BY `Región`,date_format(`Fecha de Creación`,'%d-%M') HAVING `Región` = '".$region."'";

            echo "<script> console.log('".addslashes($query)."'); </script>";
    
            $promise = mysqli_query($conection, $query);
    
            if (mysqli_num_rows($promise)) {
    
                if ($datos = mysqli_fetch_all($promise)) {
    
                    $datosCompletos = $datos;
                }
            }
    
            $soloLabels = array_column($datosCompletos, 1);
            $soloDataNums = array_column($datosCompletos, 0);
    
            for ($i = 0; $i < count($labelData); $i++) {
    
                if (in_array($labelData[$i], $soloLabels)) { //El array de soloLabels contiene el valor que esta en LabelData[posicion] ?
    
                    //$dataCentroArray[$i] = $datosCompletos[$i][0]; //Asigno el valor que me arroja el COUNT(sql)
                    $dataArray[$i] = array_shift($soloDataNums); //Elimino el primer elemento del Array y lo asigno.
    
                } else {
    
                    $dataArray[$i] = null;
                }
            }
            return $dataArray;
        }

        #endregion

        #region CONSULTA DE DATOS Regional Centro
        $dataCentroArray = ConsultaDatosAxis($db,$labelData,$filterGraficasDos,"Centro");
        #endregion

        #region CONSULTA DE DATOS Regional Costa
        $dataCostaArray = ConsultaDatosAxis($db,$labelData,$filterGraficasDos,"Costa");
        #endregion

        #region CONSULTA DE DATOS Regional Noroccidente
        $dataNoroccidenteArray = ConsultaDatosAxis($db,$labelData,$filterGraficasDos,"Noroccidente");
        #endregion

        #region CONSULTA DE DATOS Regional Oriente
        $dataOrienteArray = ConsultaDatosAxis($db,$labelData,$filterGraficasDos,"Oriente");
        #endregion

        #region CONSULTA DE DATOS Regional Suroccidente
        $dataSuroccidenteArray = ConsultaDatosAxis($db,$labelData,$filterGraficasDos,"Suroccidente");
        #endregion

        #region Traspaso de datos a JS


        $generalData[] = array($labelData, $dataCentroArray, $dataCostaArray, $dataNoroccidenteArray, $dataOrienteArray, $dataSuroccidenteArray);
    }



    mysqli_close($db);
    #endregion
    ?>

    <script>
        //#region Importe y Transformacion de datos
        //Paso la Matriz con los datos de php a una variable JS
        let generalData;
        generalData = <?php echo json_encode($generalData); ?>;
        //Desestructuro los datos
        let labels, centro, costa, noroccidente, oriente, suroccidente;

        labels = generalData[0][0];
        centro = generalData[0][1];
        costa = generalData[0][2];
        noroccidente = generalData[0][3];
        oriente = generalData[0][4];
        suroccidente = generalData[0][5];

        // console.log(generalData);
        // console.log(labels);
        // console.log(centro);
        // console.log(costa);
        // console.log(noroccidente);
        // console.log(oriente);
        // console.log(suroccidente);

        //Mapeo y Convierto los Arrays de String a Numeros Enteros, como son conteos precisos los paso a INT, tener en cunata para valores flotantes

        centro = centro.map(elemento => {
            return parseInt(elemento);
        });

        costa = costa.map(elemento => {
            return parseInt(elemento);
        });

        noroccidente = noroccidente.map(elemento => {
            return parseInt(elemento);
        });

        oriente = oriente.map(elemento => {
            return parseInt(elemento);
        });

        suroccidente = suroccidente.map(elemento => {
            return parseInt(elemento);
        });

        //#endregion

        const data = {
            labels: labels,
            datasets: [{
                    label: 'Centro',
                    data: centro,
                    backgroundColor: [
                        'rgba(4, 48, 106,0.5)',
                    ],
                    borderColor: [
                        'rgb(4, 48, 106)'
                    ],
                    borderWidth: 2
                },
                {
                    label: 'Costa',
                    data: costa,
                    backgroundColor: [
                        'rgba(255, 109, 109,0.5)'
                    ],
                    borderColor: [
                        'rgb(255, 109, 109)'
                    ],
                    borderWidth: 2
                },
                {
                    label: 'Noroccidente',
                    data: noroccidente,
                    backgroundColor: [
                        'rgba(19, 108, 105,0.5)'
                    ],
                    borderColor: [
                        'rgb(19, 108, 105)'
                    ],
                    borderWidth: 2
                },
                {
                    label: 'Oriente',
                    data: oriente,
                    backgroundColor: [
                        'rgba(255, 191, 78,0.5)'
                    ],
                    borderColor: [
                        'rgb(255, 191, 78)'
                    ],
                    borderWidth: 2
                },
                {
                    label: 'Suroccidente',
                    data: suroccidente,
                    backgroundColor: [
                        'rgba(116, 162, 255,0.5)'
                    ],
                    borderColor: [
                        'rgb(116, 162, 255)'
                    ],
                    borderWidth: 2
                },
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

    <a href="http://localhost/PryWeb/devSopa/scripts/ows/CargaDatosSNR.php" target="_blank">CARGA DE DATOS</a>
</body>
</html>