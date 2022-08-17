<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js" integrity="sha512-klQv6lz2YR+MecyFYMFRuU2eAl8IPRo6zHnsc9n142TJuJHS8CG0ix4Oq9na9ceeg1u5EkBfZsFcV3U7J51iew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


    <link rel="icon" href="../../images/favicon2.ico" />

    <!--Load Style Sheet-->

    <link rel="stylesheet" type="text/css" href="../../css/main4.css">

    <link rel="stylesheet" type="text/css" href="practica.css">

    <title>:: OWS_SNR_&_FEC_x_Regionales ::</title>
</head>

<body>
    <div class="flex-content">

        <?php
        #region valida la session
        error_reporting("E_ALL ^ E_NOTICE ^ E_WARNING");
        echo "<header>";
        echo "<img src='../../images/cabezote2.png'>";
        include("../../includes/top.php");
        echo "</header>";
        session_start();
        if ((!isset($_SESSION["user"])) || ($_SESSION["rol"] == "O")) {
            echo "<script type='text/javascript'>";
            echo "window.location = '../../index.php';";
            echo "</script>";
        }
        #endregion

        #region Recibir y validar variables
        // Verifying POST Variables.
        if (isset($_POST["anyo"]) || isset($_POST["region"]) || isset($_POST["departamento"]) || isset($_POST["tecnologia"]) || isset($_POST["tipoOrden"]) || isset($_POST["categoria"]) || isset($_POST["subcategoria"]) || isset($_POST["fechaCreacion"]) || isset($_POST["mes"])) {

            $anyoCreacion = $_POST["anyo"];
            $region = $_POST["region"];
            $departamento = $_POST["departamento"];
            $tecnologia = $_POST["tecnologia"];
            $tipoOrden = $_POST["tipoOrden"];
            $categoria = $_POST["categoria"];
            $subcategoria = $_POST["subcategoria"];
            $fechaCreacion = $_POST["fechaCreacion"];
            $mesCreacion = $_POST["mes"];
        } else {

            $anyoCreacion = "ALL";
            $region = "ALL";
            $departamento = "ALL";
            $tecnologia = "ALL";
            $tipoOrden = "ALL";
            $categoria = "ALL";
            $subcategoria = "ALL";
            $fechaCreacion = "ALL";
            $mesCreacion = "ALL";
        }

        #endregion

        // DB Parameters.
        $mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password = "";
        $mysql_database = "devsopa";

        $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

        #region Datos para el select de Año
        $sqlAnyoSelect = "SELECT DISTINCT(date_format(tt.`Fecha de Creación`, '%Y')) FROM `incidentes_tt_cerrados` as tt ORDER BY tt.`Fecha de Creación`";

        $resultAnyoSelect = mysqli_query($db, $sqlAnyoSelect);

        if (mysqli_num_rows($resultAnyoSelect)) {
            while ($data = mysqli_fetch_row($resultAnyoSelect)) {
                $AnyoSelect[] = $data;
            }
        }

        mysqli_close($db);
        #endregion

        //Si la variable anyo no se ha seleccionado no hace nada
        if ($anyoCreacion !== "ALL") {

            #region Datos para el select de FechaCreacion

            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");


            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'");

            $filterFechaRegistro = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterFechaRegistro) > 0)
                        $filterFechaRegistro = $filterFechaRegistro . " AND " . $arrayFilter[$i];
                    else
                        $filterFechaRegistro = $filterFechaRegistro . " WHERE " . $arrayFilter[$i];
                }
            }

            mysqli_close($db);
            //FECHAS DE REGISTRO
            $sqlFechaSelect = "SELECT DISTINCT(date_format(tt.`Fecha de Creación`, '%d-%M')) FROM `incidentes_tt_cerrados` as tt" . $filterFechaRegistro .
                " ORDER BY tt.`Fecha de Creación`";

            $resultFechaSelect = mysqli_query($db, $sqlFechaSelect);

            if (mysqli_num_rows($resultFechaSelect)) {

                while ($data = mysqli_fetch_row($resultFechaSelect)) {

                    $FechaSelect[] = $data;
                }
            }

            #endregion

            #region Datos para el select de region
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'");


            $filterRegion = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterRegion) > 0)
                        $filterRegion = $filterRegion . " AND " . $arrayFilter[$i];
                    else
                        $filterRegion = $filterRegion . " WHERE " . $arrayFilter[$i];
                }
            }

            //echo "<script>console.log(".json_encode($filterRegion).");</script>";

            //REGIONES
            $sqlRegionSelect = "SELECT DISTINCT(`Región`) FROM `incidentes_tt_cerrados` as tt" . $filterRegion .
                " ORDER BY tt.`Región`";

            $resultRegionSelect = mysqli_query($db, $sqlRegionSelect);

            if (mysqli_num_rows($resultRegionSelect)) {
                while ($data = mysqli_fetch_row($resultRegionSelect)) {

                    $RegionSelect[] = $data;
                }
            } else {
                echo "<script>console.log('FALLO');</script>";
                echo "<script>console.log(" . json_encode($sqlRegionSelect) . ");</script>";
            }

            //echo "<script>console.log(" . json_encode($RegionSelect) . ");</script>";

            mysqli_close($db);
            #endregion

            #region Datos para el select de Departamento
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Región` = '" . $region . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'");

            $filterDepartamento = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterDepartamento) > 0)
                        $filterDepartamento = $filterDepartamento . " AND " . $arrayFilter[$i];
                    else
                        $filterDepartamento = $filterDepartamento . " WHERE " . $arrayFilter[$i];
                }
            }


            //DEPARTAMENTOS
            $sqlDepartamentoSelect = "SELECT DISTINCT(`Departamento`) FROM `incidentes_tt_cerrados` as tt" . $filterDepartamento .
                " ORDER BY tt.`Departamento`";

            $resultDepartamentoSelect = mysqli_query($db, $sqlDepartamentoSelect);

            if (mysqli_num_rows($resultDepartamentoSelect)) {

                while ($data = mysqli_fetch_row($resultDepartamentoSelect)) {

                    $DepartamentoSelect[] = $data;
                }
            }
            //echo "<script>console.log(" . json_encode($region) . ");</script>";
            //echo "<script>console.log(" . json_encode($sqlDepartamentoSelect) . ");</script>";

            mysqli_close($db);
            #endregion

            #region Datos para el select de TECNOLOGIAS
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Región` = '" . $region . "'", "`Departamento` = '" . $departamento . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'");

            $filterTecnologia = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterTecnologia) > 0)
                        $filterTecnologia = $filterTecnologia . " AND " . $arrayFilter[$i];
                    else
                        $filterTecnologia = $filterTecnologia . " WHERE " . $arrayFilter[$i];
                }
            }


            //TECNOLOGÍAS
            $sqlTecnologiaSelect = "SELECT DISTINCT(`Tecnología`) FROM `incidentes_tt_cerrados` as tt" . $filterTecnologia .
                " ORDER BY tt.`Tecnología`";

            $resultTecnologiaSelect = mysqli_query($db, $sqlTecnologiaSelect);

            if (mysqli_num_rows($resultTecnologiaSelect)) {

                while ($data = mysqli_fetch_row($resultTecnologiaSelect)) {

                    $TecnologiaSelect[] = $data;
                }
            }

            //echo "<script>console.log(".json_encode($sqlTecnologiaSelect).");</script>";
            mysqli_close($db);
            #endregion

            #region Datos para el select de TIPO ORDEN
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Región` = '" . $region . "'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'");

            $filterTipoOrden = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterTipoOrden) > 0)
                        $filterTipoOrden = $filterTipoOrden . " AND " . $arrayFilter[$i];
                    else
                        $filterTipoOrden = $filterTipoOrden . " WHERE " . $arrayFilter[$i];
                }
            }


            //TECNOLOGÍAS
            $sqlTipoOrdenSelect = "SELECT DISTINCT(`Tipo de Orden`) FROM `incidentes_tt_cerrados` as tt" . $filterTipoOrden .
                " ORDER BY tt.`Tipo de Orden`";

            $resultTipoOrdenSelect = mysqli_query($db, $sqlTipoOrdenSelect);

            if (mysqli_num_rows($resultTipoOrdenSelect)) {

                while ($data = mysqli_fetch_row($resultTipoOrdenSelect)) {

                    $TipoOrdenSelect[] = $data;
                }
            }

            //echo "<script>console.log(".json_encode($sqlTipoOrdenSelect).");</script>";
            mysqli_close($db);
            #endregion

            #region Datos para el select de Categoria
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Región` = '" . $region . "'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Subcategoría` = '" . $subcategoria . "'");

            $filterCategoria = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterCategoria) > 0)
                        $filterCategoria = $filterCategoria . " AND " . $arrayFilter[$i];
                    else
                        $filterCategoria = $filterCategoria . " WHERE " . $arrayFilter[$i];
                }
            }


            //CATEGORIA
            $sqlCategoriaSelect = "SELECT DISTINCT(`Categoría`) FROM `incidentes_tt_cerrados` as tt" . $filterCategoria .
                " ORDER BY tt.`Categoría`";

            $resultCategoriaSelect = mysqli_query($db, $sqlCategoriaSelect);

            if (mysqli_num_rows($resultCategoriaSelect)) {

                while ($data = mysqli_fetch_row($resultCategoriaSelect)) {

                    $CategoriaSelect[] = $data;
                }
            }

            //echo "<script>console.log(".json_encode($sqlCategoriaSelect).");</script>";
            mysqli_close($db);
            #endregion

            #region Datos para el select de SUBCategoria
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Región` = '" . $region . "'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'");

            $filterSubCategoria = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterSubCategoria) > 0)
                        $filterSubCategoria = $filterSubCategoria . " AND " . $arrayFilter[$i];
                    else
                        $filterSubCategoria = $filterSubCategoria . " WHERE " . $arrayFilter[$i];
                }
            }


            //CATEGORIA
            $sqlSubCategoriaSelect = "SELECT DISTINCT(`Subcategoría`) FROM `incidentes_tt_cerrados` as tt" . $filterSubCategoria .
                " ORDER BY tt.`Subcategoría`";

            $resultSubCategoriaSelect = mysqli_query($db, $sqlSubCategoriaSelect);

            if (mysqli_num_rows($resultSubCategoriaSelect)) {

                while ($data = mysqli_fetch_row($resultSubCategoriaSelect)) {

                    $SubCategoriaSelect[] = $data;
                }
            }

            //echo "<script>console.log(".json_encode($sqlSubCategoriaSelect).");</script>";
            mysqli_close($db);
            #endregion

            #region Datos para el select de MES
            $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`Fecha de Creación`, '%Y') LIKE '" . $anyoCreacion . "%'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'", "date_format(`Fecha de Creación`,'%M') IS NOT NULL");

            $filterMesRegistro = "";

            for ($i = 0; $i < count($arrayFilter); $i++) {

                if (strpos($arrayFilter[$i], "ALL") == FALSE) {

                    if (strlen($filterMesRegistro) > 0)
                        $filterMesRegistro = $filterMesRegistro . " AND " . $arrayFilter[$i];
                    else
                        $filterMesRegistro = $filterMesRegistro . " WHERE " . $arrayFilter[$i];
                }
            }


            //FECHAS DE REGISTRO
            $sqlMesSelect = "SELECT DISTINCT(date_format(`Fecha de Creación`,'%M')) FROM `incidentes_tt_cerrados` as tt" . $filterMesRegistro .
                " ORDER BY tt.`Fecha de Creación`";

            $resultMesSelect = mysqli_query($db, $sqlMesSelect);

            if (mysqli_num_rows($resultMesSelect)) {

                while ($data = mysqli_fetch_row($resultMesSelect)) {

                    $MesSelect[] = $data;
                }
            }
            mysqli_close($db);
            #endregion
        }
        ?>


        <div class="content-chart-labels-title">
            <form action="graficaBackLog-SNR.php" method="POST" id="form-data">

                <label for="anyo">Año
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
                </label>

                <label for="mes">Mes
                    <select name="mes" id="mesCreacionSeelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($MesSelect); $i++) {

                            if ($MesSelect[$i][0] == $mesCreacion) {

                                echo "<option value = '" . $MesSelect[$i][0] . "' selected>" . $MesSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $MesSelect[$i][0] . "'>" . $MesSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>

                </label>

                <label for="regionSelect">Región
                    <select name="region" id="regionSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($RegionSelect); $i++) {

                            if ($RegionSelect[$i][0] == $region) {

                                echo "<option value = '" . $RegionSelect[$i][0] . "' selected>" . $RegionSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $RegionSelect[$i][0] . "'>" . $RegionSelect[$i][0] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </label>

                <label for="departamentoSelect">Departamento
                    <select name="departamento" id="departamentoSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($DepartamentoSelect); $i++) {

                            if ($DepartamentoSelect[$i][0] == $departamento) {

                                echo "<option value = '" . $DepartamentoSelect[$i][0] . "' selected>" . $DepartamentoSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $DepartamentoSelect[$i][0] . "'>" . $DepartamentoSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </label>
                <label for="tecnologiaSelect">Tecnología
                    <select name="tecnologia" id="tecnologiaSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($TecnologiaSelect); $i++) {

                            if ($TecnologiaSelect[$i][0] == $tecnologia) {

                                echo "<option value = '" . $TecnologiaSelect[$i][0] . "' selected>" . $TecnologiaSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $TecnologiaSelect[$i][0] . "'>" . $TecnologiaSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </label>

                <label for="tipoOrdenSelect">Tipo de Orden
                    <select name="tipoOrden" id="tipoOrdenSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($TipoOrdenSelect); $i++) {

                            if ($TipoOrdenSelect[$i][0] == $tipoOrden) {

                                echo "<option value = '" . $TipoOrdenSelect[$i][0] . "' selected>" . $TipoOrdenSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $TipoOrdenSelect[$i][0] . "'>" . $TipoOrdenSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </label>
                <label for="categoriaSelect">Categoría
                    <select name="categoria" id="categoriaSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($CategoriaSelect); $i++) {

                            if ($CategoriaSelect[$i][0] == $categoria) {

                                echo "<option value = '" . $CategoriaSelect[$i][0] . "' selected>" . $CategoriaSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $CategoriaSelect[$i][0] . "'>" . $CategoriaSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </label>

                <label for="subcategoriaSelect">Subcategoría
                    <select name="subcategoria" id="subcategoriaSelect">
                        <option value="ALL">ALL</option>
                        <?php
                        for ($i = 0; $i < count($SubCategoriaSelect); $i++) {

                            if ($SubCategoriaSelect[$i][0] == $subcategoria) {

                                echo "<option value = '" . $SubCategoriaSelect[$i][0] . "' selected>" . $SubCategoriaSelect[$i][0] . "</option>";
                            } else {

                                echo "<option value = '" . $SubCategoriaSelect[$i][0] . "'>" . $SubCategoriaSelect[$i][0] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </label>
                <label for="fechaCreacion">Fecha de Creación:
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
                </label>
            </form>


            <main class="content-charts">
                <div class="size-chart">
                    <h4 id="chart-title">TOTAL ORDENES OWS</h4>
                    <canvas id="myChart"></canvas>
                    <hr>
                    <button onclick="resetZoomChart()">Reset</button>
                </div>
                <div class="size-chart">
                    <h4 id="chart-title2">TOTAL ORDENES OWS - CERRADAS</h4>
                    <canvas id="myChart2"></canvas>
                    <hr>
                    <button onclick="resetZoomChart2()">Reset</button>
                </div>
            </main>
        </div>
    </div>


    <?php

    if ($anyoCreacion == "ALL") {

        $generalData[] = array();

    } else {

        
        $db = "";
        #region CONSULTA PARA LOS LABELS EN LA AXIS X

        function ConsultaLabels($tipoFecha, $fechaCreacion, $anyoCreacion, $mesCreacion, $db)
        {

        $mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password = "";
        $mysql_database = "devsopa";

        $db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");


            $arrayFilter = array(" date_format(tt.`" . $tipoFecha . "`,'%d-%M') LIKE '" . $fechaCreacion . "%'", " date_format(tt.`" . $tipoFecha . "`,'%Y') LIKE '" . $anyoCreacion . "%'", " date_format(tt.`" . $tipoFecha . "`, '%M') = '" . $mesCreacion . "'");

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


            $queryLabelData = "SELECT DISTINCT(date_format(`" . $tipoFecha . "`, '%d-%M')) FROM `incidentes_tt_cerrados` as tt" . $filterGraficasUno . " ORDER BY `" . $tipoFecha . "`";

            $numRow = mysqli_query($db, $queryLabelData);

            $labelData[] = array();

            if (mysqli_num_rows($numRow)) {
                // $i = 0;
                // while ($data = mysqli_fetch_row($numRow)) {
                //     $labelData[$i] = $data[0];
                //     $i++;
                // }

                //Optimice la consulta a una sola línea de código aunque puede resultar siendo lo mismo por la función map
                $labelData = array_map(function ($e) {
                    return $e[0];
                }, mysqli_fetch_all($numRow));
            }
            mysqli_close($db);
            return $labelData;
        }

        #endregion
        $labelData = ConsultaLabels("Fecha Ingreso", $fechaCreacion, $anyoCreacion, $mesCreacion, $db);
        $labelData2 = ConsultaLabels("Fecha Cierre", $fechaCreacion, $anyoCreacion, $mesCreacion, $db);

        #region Consulta General: Contar la cantidad de tiquetes por mes validadando los filtros

        function ConsultaDatosAxis($conection, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, $segmento, $segmentoSNR, $SOservisdesk, $SObmc, $mesCreacion, $estado, $tipoFecha)
        {  
        $mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password = "";
        $mysql_database = "devsopa";

        $conection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Error durante la conexión a la base de datos");

            $arrayFilter = array(" date_format(tt.`" . $tipoFecha . "`, '%d-%M') LIKE '" . $fechaCreacion . "%'", "`Región` = '" . $region . "'", "`Departamento` = '" . $departamento . "'", "`Tecnología` = '" . $tecnologia . "'", "`Tipo de Orden` = '" . $tipoOrden . "'", "`Categoría` = '" . $categoria . "'", "`Subcategoría` = '" . $subcategoria . "'", "`segmento` NOT LIKE '" . $segmento . "%'", "`segmento` LIKE '" . $segmentoSNR . "%'", "`Sistema Origen` = '" . $SOservisdesk . "'", "`Sistema Origen` = '" . $SObmc . "'", " date_format(tt.`" . $tipoFecha . "`, '%M') = '" . $mesCreacion . "'", "`Estado` " . $estado, "date_format(tt.`" . $tipoFecha . "`, '%d-%M') IS NOT NULL");

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

            $query = "SELECT COUNT(`ID Tiquete`), date_format(`" . $tipoFecha . "`,'%d-%M') FROM `incidentes_tt_cerrados` as tt " . $filterGraficasDos . " GROUP BY date_format(`" . $tipoFecha . "`,'%d-%M') ORDER BY `" . $tipoFecha . "`";

            // echo "<script> console.log('" . addslashes($query) . "'); </script>";

            //Query
            $promise = mysqli_query($conection, $query);

            //Estraigo la totalidad de la consulta
            $datosCompletos = mysqli_fetch_all($promise);

            $soloLabels = array_column($datosCompletos, 1);
            $soloDataNums = array_column($datosCompletos, 0);

            //Debo recorrer el array de labelData dado que la consulta de datos agrupados por fecha no me trae los valores que sean cero
            //Por ende a la hora de graficar los datos por temporalidad de fechas me graficará datos continuos y no me va a graficar en las fechas donde la consulta debio haber arrojado cero.
            for ($i = 0; $i < count($labelData); $i++) {

                if (in_array($labelData[$i], $soloLabels)) { //El array de soloLabels contiene el valor que esta en LabelData[posicion] ?

                    //$dataCentroArray[$i] = $datosCompletos[$i][0]; //Asigno el valor que me arroja el COUNT(sql).
                    $dataArray[$i] = array_shift($soloDataNums); //Elimino el primer elemento del Array soloDataNums y lo asigno.

                } else {

                    $dataArray[$i] = null;
                }
            }
            mysqli_close($conection);
            return $dataArray;
        }
        #endregion
        
        
        $dataTtCerradosUno = array();
        $dataTtCerradosDOS = array();
        $dataTtCerradosTRES = array();

        //Consulto los datos en general: Cantidad de Tiquetes Por día - Cerrados y Abiertos
        $dataTtUNO = ConsultaDatosAxis($db, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "ALL", "ALL", $mesCreacion, "NOT LIKE 'Cerrado%'", "Fecha Ingreso");

        $dataTtCerradosUno = ConsultaDatosAxis($db, $labelData2, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "ALL", "ALL", $mesCreacion, "LIKE 'Cerrado%'", "Fecha Cierre");

        //echo "<script>console.log('".json_encode($dataTtCerradosUno)."')</script>";


        if ($tipoOrden == 'TT_SNR') {

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS SNR' DIFERENTE A CERRADOS por día
            $dataTtDOS = ConsultaDatosAxis($db, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "TIQUETE", "ALL", "ALL", "ALL", $mesCreacion, "NOT LIKE 'Cerrado%'", "Fecha Ingreso");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES PROBLEMAS SNR DIFERENTE A CERRADOS' por día
            $dataTtTRES = ConsultaDatosAxis($db, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "TIQUETE", "ALL", "ALL", $mesCreacion, "NOT LIKE 'Cerrado%'", "Fecha Ingreso");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS SNR' CERRADOS por día
            $dataTtCerradosDOS = ConsultaDatosAxis($db, $labelData2, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "TIQUETE", "ALL", "ALL", "ALL", $mesCreacion, "LIKE 'Cerrado%'", "Fecha Cierre");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES PROBLEMAS SNR' CERRADOS por día
            $dataTtCerradosTRES = ConsultaDatosAxis($db, $labelData2, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "TIQUETE", "ALL", "ALL", $mesCreacion, "LIKE 'Cerrado%'", "Fecha Cierre");

            //Arreglo general con los datos para graficar y sus respesctivos Titulos
            $generalData[] = array($labelData, $dataTtUNO, $dataTtDOS, $dataTtTRES, "TOTAL TIQUETES SNR", "TOTAL TIQUETES SNR", "TIQUETE TÉCNICO - SNR", "TIQUETE PROBLEMA - SNR", "ALL", $dataTtCerradosUno, $dataTtCerradosDOS, $dataTtCerradosTRES, $labelData2);

        } else if ($tipoOrden == 'TT_TIQUET') {

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS DE ORIGEN SERVISDEKS' por día
            $dataTtDOS = ConsultaDatosAxis($db, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "Servic", "ALL", $mesCreacion, "NOT LIKE 'Cerrado%'", "Fecha Ingreso");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS DE ORIGEN BMC' por día
            $dataTtTRES = ConsultaDatosAxis($db, $labelData, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "ALL", "BMC RE", $mesCreacion, "NOT LIKE 'Cerrado%'", "Fecha Ingreso");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS DE ORIGEN SERVISDEKS' por día
            $dataTtCerradosDOS = ConsultaDatosAxis($db, $labelData2, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "Servic", "ALL", $mesCreacion, "LIKE 'Cerrado%'", "Fecha Cierre");

            //Consulta por Tiquetes Señal a ruido (SNR) : Cantidad de 'TIQUETES TECNICOS DE ORIGEN BMC' por día
            $dataTtCerradosTRES = ConsultaDatosAxis($db, $labelData2, $fechaCreacion, $anyoCreacion, $region, $departamento, $tecnologia, $tipoOrden, $categoria, $subcategoria, "ALL", "ALL", "ALL", "BMC RE", $mesCreacion, "LIKE 'Cerrado%'", "Fecha Cierre");

            $generalData[] = array($labelData, $dataTtUNO, $dataTtDOS, $dataTtTRES, "TOTAL TIQUETES TÉCNICOS", "TOTAL TT_TIQUETE TÉCNICO", "ORIGEN SERVICE DESK", "ORIGEN BMC REMEDY", $mesCreacion, $dataTtCerradosUno, $dataTtCerradosDOS, $dataTtCerradosTRES, $labelData2);
            
        } else {

            $nulleable = array();
            $generalData[] = array($labelData, $dataTtUNO, $nulleable, $nulleable, "TOTAL ORDENES OWS - Abiertos", "TOTAL ORDENES: " . $tipoOrden, "", "", $mesCreacion, $dataTtCerradosUno, $dataTtCerradosDOS, $dataTtCerradosTRES, $labelData2);
        }
    }

    ?>

    <button id="loadData">
        <div class="svg-wrapper-1">
            <div class="svg-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"></path>
                    <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                </svg>
            </div>
        </div>
        <a href="http://localhost/PryWeb/devSopa/scripts/ows/CargaDatosSNR.php" target="_blank">CARGA DE DATOS</a>
    </button>

    <div class="content-cards">
        <div class="card tolal">TOTAL :</div>
        <div class="card totalSinTP">TOTAL SIN TP :</div>
        <div class="card totalConTP">TOTAL TIQUETE PROBLEMA :</div>
    </div>


    <script>
        let generalData = <?php echo json_encode($generalData); ?>;

        //Desestructuro los datos
        let labels, dataTTAbiertos, dataTTAbiertosDos, dataTTAbiertosTres, tituloGeneral, tituloDataLabelUno, tituloDataLabelDos, tituloDataLabelTres, dataTtCerrado, dataTtCerradoDOS, dataTtCerradoTRES, labelData2;

        labels = generalData[0][0];
        dataTTAbiertos = generalData[0][1]; // Total tiquetes Abiertos
        dataTTAbiertosDos = generalData[0][2]; // Total tiquetes Abiertos sin tt problema ó sin los de órigen BMC
        dataTTAbiertosTres = generalData[0][3]; // Total tiquetes Abiertos solo tt problema ó solo de órigen BMC
        tituloGeneral = generalData[0][4]; // Titulo Principal para las 2 graficas
        tituloDataLabelUno = generalData[0][5]; // Titulo de la tarja
        tituloDataLabelDos = generalData[0][6]; // Titulo de la tarja
        tituloDataLabelTres = generalData[0][7]; // Titulo de la tarja
        dataTtCerrado = generalData[0][9]; // Total tiquetes Cerrados
        dataTtCerradoDOS = generalData[0][10]; // Total tiquetes Cerrados sin tt problema ó sin los de órigen BMC
        dataTtCerradoTRES = generalData[0][11]; // Total tiquetes Cerrados solo tt problema ó solo de órigen BMC
        labelData2 = generalData[0][12]; // Labels para la segunda grafica


        document.getElementById("chart-title").innerText = tituloGeneral + " - ABIERTOS";
        document.getElementById("chart-title2").innerText = tituloGeneral + " - CERRADOS";

        //Mapeo y Convierto los Arrays de String a Numeros Enteros
        dataTTAbiertos = dataTTAbiertos.map(elemento => {
            return parseInt(elemento);
        });
        dataTTAbiertosDos = dataTTAbiertosDos.map(elemento => {
            return parseInt(elemento);
        });
        dataTTAbiertosTres = dataTTAbiertosTres.map(elemento => {
            return parseInt(elemento);
        });

        dataTtCerrado = dataTtCerrado.map(elemento => {
            return parseInt(elemento);
        });

        dataTtCerradoDOS = dataTtCerradoDOS.map(elemento => {
            return parseInt(elemento);
        });

        dataTtCerradoTRES = dataTtCerradoTRES.map(elemento => {
            return parseInt(elemento);
        });

        //Devuelvo la suma de cada Array y lo paso a una variable
        //Si es NaN sumele cero, sino le suma el valor que traiga el indice
        let sumaDataTTAbiertos = dataTTAbiertos.reduce((acumulador, actual) => {
            return isNaN(actual) ? acumulador + 0 : acumulador + actual;
        }, 0);
        let sumaDataTTAbiertosDos = dataTTAbiertosDos.reduce((acumulador, actual) => {
            return isNaN(actual) ? acumulador + 0 : acumulador + actual;
        }, 0);
        let sumaDataTTAbiertosTres = dataTTAbiertosTres.reduce((acumulador, actual) => {
            return isNaN(actual) ? acumulador + 0 : acumulador + actual;
        }, 0);


        //Inserto el valor acumulado en las tarjetas y lo convierto a un formato de número
        let tarjetaTotal = document.querySelector(".tolal");
        tarjetaTotal.innerHTML = tituloDataLabelUno + "<br> ABIERTOS <br>" + sumaDataTTAbiertos.toLocaleString();

        let tarjetaTotalSinTP = document.querySelector(".totalSinTP");
        tarjetaTotalSinTP.innerHTML = tituloDataLabelDos + "<br> ABIERTOS <br>" + sumaDataTTAbiertosDos.toLocaleString();

        let tarjetaTotalConTP = document.querySelector(".totalConTP");
        tarjetaTotalConTP.innerHTML = tituloDataLabelTres + "<br> ABIERTOS <br>" + sumaDataTTAbiertosTres.toLocaleString();


        //#endregion

        const data = {
            labels: labels,
            datasets: [{
                    label: tituloDataLabelUno,
                    data: dataTTAbiertos,
                    backgroundColor: [
                        'rgba(4, 48, 106,0.5)',
                    ],
                    borderColor: [
                        'rgb(4, 48, 106)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: tituloDataLabelDos,
                    data: dataTTAbiertosDos,
                    backgroundColor: [
                        'rgba(58, 157, 240,0.5)',
                    ],
                    borderColor: [
                        'rgb(58, 157, 240)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: tituloDataLabelTres,
                    data: dataTTAbiertosTres,
                    backgroundColor: [
                        'rgba(255, 196, 77,0.5)',
                    ],
                    borderColor: [
                        'rgb(255, 196, 77)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
            ]
        };

        //Data chart 2
        const data2 = {
            labels: labelData2,
            datasets: [{
                    label: tituloDataLabelUno,
                    data: dataTtCerrado,
                    backgroundColor: [
                        'rgba(4, 48, 106,0.5)',
                    ],
                    borderColor: [
                        'rgb(4, 48, 106)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: tituloDataLabelDos,
                    data: dataTtCerradoDOS,
                    backgroundColor: [
                        'rgba(58, 157, 240,0.5)',
                    ],
                    borderColor: [
                        'rgb(58, 157, 240)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: tituloDataLabelTres,
                    data: dataTtCerradoTRES,
                    backgroundColor: [
                        'rgba(255, 196, 77,0.5)',
                    ],
                    borderColor: [
                        'rgb(255, 196, 77)'
                    ],
                    borderWidth: 2,
                    tension: 0.3
                },
            ]
        };

        const zoomOptions = {

            pan: {
                enabled: true,
                mode: 'x',
                threshold: 5,
            },
            zoom: {
                drag: {
                    enabled: true,
                    backgroundColor: 'rgba(43, 98, 135,0.3)',
                },
                mode: 'x',
            },
            wheel: {
                enabled: true,
            },

        };


        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        //El eje de Y inicia en 0
                        //Elimino las cuadriculas
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback: (val, index, ticks) => index === 0 || index === ticks.length - 1 ? null : val,
                        }
                    },
                    x: {
                        //Inician los valores desde Enero
                        //min: 'enero'
                        //Elimino las cuadriculas
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                },

                plugins: {
                    legend: {
                        display: true,
                    },
                    zoom: zoomOptions,
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            },

        };
        //Chart 2
        const config2 = {
            type: 'line',
            data: data2,
            options: {
                scales: {
                    y: {
                        //El eje de Y inicia en 0
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        }
                    },
                    x: {
                        //Inician los valores desde Enero
                        //min: 'enero'
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                },

                plugins: {
                    legend: {
                        display: true,
                    },
                    zoom: zoomOptions,

                },

                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        };

        const chart = new Chart(
            document.getElementById("myChart"),
            config,
            function resetZoomChart() {
                chart.resetZoom();
            }
        );
        const chart2 = new Chart(
            document.getElementById("myChart2"),
            config2,
        );

        function resetZoomChart() {
            chart.resetZoom();
        }

        function resetZoomChart2() {
            chart2.resetZoom();
        }
    </script>
    <script src="subScript/dinami-backLog-Snr.js"></script>
</body>

</html>