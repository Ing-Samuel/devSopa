<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>:: Subdirección Operación Acceso ::</title>
		<link rel="icon" href="../../images/favicon2.ico"/>
        
        <!--Load Style Sheet-->
        <link rel="stylesheet" type="text/css" href="../../css/main.css">
        <style>
                body{
                    display: flex;
                    align-items: center;
                    flex-direction: column;
                }
                #imgMain{
                    width: 20vw;
                    height: 30vh;
                }
                
        </style>
    </head>
    <body>
        <div>
            <?php
				echo "<header>";
                echo "<center><img src='../../images/cabezote2.png'></center>";
                include("../../includes/top.php");
				echo "</header>";
				/*if (!isset($_SESSION["user"])) {
					echo "<script type='text/javascript'>";
						echo "window.location = '../../index.php';";
					echo "</script>"; 
				}*/
            ?>
        </div>
        
        <div id="main">
            <center>
                <h2>BIENVENIDO AL GESTOR DE INDICADORES</h2>
                <!-- <h4>Usuario:<?php echo $_SESSION["user"]?></h4> -->
                <br>
                <img id="imgMain" src='../../images/imagenTigo.png'>
            </center>    
        </div>
        
    </body>
</html>
