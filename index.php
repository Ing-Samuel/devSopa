<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>:: Subdirección Operación Acceso ::</title>
    <link rel="icon" href="images/favicon2.ico" />
    <!--Load Styles Sheet-->
    <link rel="stylesheet" href="scripts/ows/practica.css">
    <link rel="stylesheet" type="text/css" href="css/login_style.css">

</head>

<body>
    <div>
        <img src="images/cabezote2.png">
    </div>
    <div id="main">
        <h4>POR FAVOR INGRESE SU USUARIO DE RED</h4>

        <form id="login" method="POST" action="index.php">

            <label style="font-family: Calibri; color: #676767; font-size: 14px;">Usuario: </label>
            <input type="text" name="login" title="Ingrese su Usuario" required="yes" placeholder="Usuario de Red" class="input" />


            <button type="submit" name="ingresar" value="">
                <div class="svg-wrapper-1">
                    <div class="svg-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                        </svg>
                    </div>
                </div>
                <span>Enviar</span>
            </button>

            
        </form>
        <?php
        include("includes/login.php");
        //include("scripts/home/main.php");
        ?>
    </div>
</body>

</html>