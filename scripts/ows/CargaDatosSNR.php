<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/login_style.css">
    <link rel="stylesheet" type="text/css" href="../../css/main4.css">
    <link rel="stylesheet" type="text/css" href="../../css/estiloReportes2022.css">

    <!--CDN CSS bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">

    <!--CDN JS bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <!--Libreria para leer datos de excel desde javaScript-->
    <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="practica.css">

    <link rel="icon" href="../../images/favicon.ico" />

    <title>Carga de Datos desde excel</title>
</head>

<body>
    <!--Obtego la cabecera-->
    <?php
    echo "<header>";
    echo "<center><img src='../../images/cabezote.png'></center>";
    include("../../includes/top.php");
    echo "</header>";
    ?>

    <form name="enviar-archivo" action="CargaDatosSNR.php" method="POST" enctype="multipart/form-data">
        <div class="container py-5">
            <div class="row mb-4">
                <div class="col12 col-md-15">
                    <input type="file" name="form-control" id="excel-file" class="form-control">
                </div>
            </div>

            <div class="button ">
                <button value="" class="btn btn-outline-primary state">CARGAR INFORMACIÓN A LA BD</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-primary" id="excel-table">
                    <thead>
                        <tr></tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <script src="extraerDatosSNR.js"></script>
    <?php
        require("cargarDatosSNR-BD.php");
    ?>
</body>

</html>