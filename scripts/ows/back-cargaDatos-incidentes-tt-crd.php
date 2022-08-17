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

    //Recorrido de Filas
    foreach ($hojaAcutal->getRowIterator() as $fila) {

        //Reinicio la sentencia
        $query = "";

        $query = "INSERT INTO incidentes_tt_cerrados VALUES (";


        //En la primera fila solo cambiará el estado de la bandera
        if ($bandera) {

            $bandera = false;
        } else {

            //Recorro las Columnas de cada Fila
            foreach ($fila->getCellIterator() as $celda) {

                $valor = $celda->getCalculatedValue();

                //Con esta funcion escapo las comillas simples para que no hayan errores en la insercion de datos
                $valor = addslashes($valor);

                //Concateno cada elemento a la sentencia
                $query = $query . "'" . $valor . "',";
            }

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
                echo "<h1>
                            <p>ERROR EN LA INSERCIÓN DE DATOS</p>
                            <br>
                         </h1>" .
                    mysqli_error($conection);
            }
        }
    }
    mysqli_close($conection);
    
    echo "<script>alert(`Número de Filas Insertadas en total = " . $contador . "`)</script>";
    echo "<script> window.location = \"front-cargaDatos-Incidente-tt-crd.php\"; </script>";
}
