<?php
//Realizado por Samuel Jimenez 26/07/2022
//Correo: samuelhz1998@gmail.com
//Cell: 323 287 4989

//.....................LE INDICIO QUE ME REPORTE TODOS LOS ERRORES EXCEPTO LAS DE WARNING Y LAS DE NOTICE.....................
error_reporting("E_ALL ^ E_NOTICE ^ E_WARNING");

//.....................HAGO EL LLAMADO A LA LIBRERIA DE EXCEL PARA PODER INTERACTUAR CON EL ARCHIVO.....................
require("../../librerias/PHPExcel-1.8/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

//LOGIN
session_start();

//.....................CONEXION A LA BASE DE DATOS.....................
$hostname = "localhost";
$username = "root";
$password = "";
$database = "devsopa";

$conection = mysqli_connect($hostname, $username, $password, $database);


//Imprimo las propiedades del archivo para poder interacturar con ellas
// foreach($_FILES["form-control"] as $clave => $valor){
//     echo "Propiedad: $clave ----- valor: $valor";
//     echo "<br>";
// }

//.....................VALIDO QUE EL ELMENTO CONTENGA VALOR PARA POSTERIORMENTE REALIZAR TODA LA OPERACION DE INSERCION.....................
if (isset($_FILES["form-control"])) {

    if ($_FILES["form-control"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {

        //Capturo el elemento que se encuentra en la variable temporal
        $objPHPExcel = PHPExcel_IOFactory::load($_FILES["form-control"]["tmp_name"]);

        //Asigno la hoja con la cual voy a trabajar
        $objPHPExcel->setActiveSheetIndex(0);

        //Obtengo la hoja
        $hojaAcutal = $objPHPExcel->getSheet(0);

        //Creo una bandera para que el programa no me inserte los titulos del archivo
        //Esto lo hago dado que vamos a trabajar con indeces dinamicos bajo un forEach.
        //No se puede extraer de forma directa el numero de columnas pero, si el de las filas.
        $bandera = true;

        //INSERT
        $query = "";

        //Contador de filas
        $contador = 0;

        $contadorGeneral = 0;

        //Acumulador de errores por fila
        $stringError = "";
        //Recorrido de Filas
        foreach ($hojaAcutal->getRowIterator() as $fila) {

            //Bandera para validar el número de columnas
            $contadorDeColumnas = 0;

            //Reinicio la sentencia
            $query = "";

            // $query = "INSERT INTO tt_general_ows VALUES (";
            $query = "REPLACE INTO tt_general_ows VALUES (";


            //En la primera fila solo cambiará el estado de la bandera
            if ($bandera) {

                $bandera = false;
            } else {
                $contadorGeneral++;

                //Recorro las Columnas de cada Fila
                $indice = 1;
                foreach ($fila->getCellIterator() as $celda) {

                    $contadorDeColumnas++;

                    //Exceptuo las columnas de Descripción, Descripción Cierre, Avances y Solución para no ingresarlas a la BD.
                    //Esto se hace con el fin de no colapsar las consultas y el mysql.
                    if ($indice !== 17 && $indice !== 64 && $indice !== 70 && $indice !== 75) {

                        $valor = $celda->getCalculatedValue();

                        //Con esta funcion escapo las comillas simples para que no hayan errores en la insercion de datos
                        $valor = addslashes($valor);

                        //Concateno cada elemento a la sentencia
                        $query = $query . "'" . $valor . "',";

                    }

                    $indice++;
                }

                if ($contadorDeColumnas == 106) {
                    //Retiro la ultima coma (,) que quedo sobrando en la sentencia
                    $query = substr($query, 0, strlen($query) - 1);

                    //Cierro la sentencia
                    $query = $query . ")";

                    //Capturo el query que voy a ejecutar
                    $promise = mysqli_query($conection, $query);

                    //Valido el Query            
                    if (mysqli_affected_rows($conection) > 0) {
                        //echo "Numero de Filas Afectadas".mysqli_affected_rows($conection)."<br>";
                        //echo "<script> window.location = \"CargaDatosSNR.php\"; </script>";
                        $contador++;
                    } else {

                        $stringError .= "<center><h3>ERROR EN LA INSERCIÓN DE DATOS : </h3><p>" . mysqli_error($conection) . "</p></center><br>";
                    }
                } else {
                    echo "<h1>Error en la Inserción de Datos</h1>";
                    echo "<h1 class = 'h1-error'>El archivo debe tener un total de 106 Columnas</h1><br>";
                    echo "<h1>Por favor valide el contenido del archivo</h1>";
                    echo "<h1>Número de Columnas Leidas = " . $contadorDeColumnas . "</h1><br>";
                    break;
                }
            }
        }

        echo "<h1>Nombre del Archivo = " . $_FILES["form-control"]["name"] . "</h1><br>";
        echo "<h1>Número de Filas Leidas en total = " . $contadorGeneral . "</h1>";
        echo "<h1>Número de Filas Insertadas en total = " . $contador . "</h1>";
        echo "<br><br>" . $stringError;

        //CAPTURA DE BACK LOG = CANTIDAD DE TIQUETES QUE AMANECIERON ABIERTOS

        $sentencia = "INSERT INTO tt_backlog_ows 
        SELECT `ID Tiquete`, `Fecha de Creación`, `Estado`, `Fecha Ingreso`, `Tecnología`, `Tipo de Orden`, `Categoría`, `Subcategoría`, `Segmento`, `Urgencia`, `Sistema Origen`, `Región`, `Departamento`, `Ciudad`, `Fecha Cierre`,now()as díaActual FROM tt_abiertos_cerrados 
        WHERE `Estado` != 'Cerrado' AND `Estado` != 'Cancelado'";

        $bandera = mysqli_query($conection,$sentencia);

        if($bandera){
            echo "<br><h1>La captura del BackLog se ha realizado Satisfactoriamente</h1>";
        }else{
            echo "<h1 class = 'h1-error'>La captura del BackLog ha Fallado</h1>";
            echo "<h1>".json_encode(mysqli_error($conection))."</h1>";
        }

        mysqli_close($conection);
    } else {
        echo "<h1>Error en la Inserción de Datos</h1>";
        echo "<h1 class = 'h1-error'>El archivo debe tener un formato XLSX (EXCEL)</h1><br>";
        echo "<h1>El archivo: (" . $_FILES["form-control"]["name"] . ") tiene un formato = " . $_FILES["form-control"]["type"] . "</h1>";
    }
}
