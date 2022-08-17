<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <title>:: Consulta de Datos a una BD ::</title>
</head>
<body>

<div class="flex-table">
<table class="table highchart" id="myTable">
    <thead>
        <?php 
            $hostname = "localhost";
            $user = "root";
            $password ="";
            $database = "devsopa";
            $where = "";
            
            $conexion = mysqli_connect($hostname,$user,$password,$database) or die("Ha fallado la conexion a la base de datos: ".mysqli_error($conexion));
    
            //Consulto el nombre de cada columna de la tabla
            $queryConsulta = "SELECT COLUMN_NAME
                FROM information_schema.columns WHERE
                table_schema = '$database'
                AND table_name = 'usuarios'";
            
            $response = mysqli_query($conexion,$queryConsulta);

            //Fila para la descripcion de cada columna
            echo "<tr>";
                while($fila = mysqli_fetch_row($response)){
                        $i=0;
                        echo "<td>".$fila[$i]."</td>";
                        $i++;
                }
            echo "</tr>";
            
            //cierro la conexion
            $conexion->close();
        ?>
    </thead>
    <tbody>

    <?php
        $hostname = "localhost";
        $user = "root";
        $password ="";
        $database = "devsopa";
        $where = "";
        
        $conexion = mysqli_connect($hostname,$user,$password,$database) or die("Ha fallado la conexion a la base de datos: ".mysqli_error($conexion));

        $queryConsulta = "SELECT * FROM `usuarios`";

        $response = mysqli_query($conexion,$queryConsulta);

        while($fila = mysqli_fetch_row($response)){
             echo "<tr>";
                for($i=0; $i<count($fila); $i++){
                    echo "<td>".$fila[$i]."</td>";
                }
            echo "</tr>";
        }
        
        //cierro la conexion
        $conexion->close();
    ?>
    </tbody>
    </table>
</div>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>
</body>
</html>