<?php

//Iniciar una nueva sesión o reanudar la existente
session_start();
// We don't need to show warnings.
//No necesito mostrar advertencias o errores
//error_reporting(0);

//Determina si una variable está definida y no es null.
if (isset($_POST["ingresar"])) {
	error_reporting(0);

    $user = $_POST["login"];
    $password = $_POST["password"];
	
	// $mysql_host = "netvm-pnoc01";
    // $mysql_user = "gestion";
    // $mysql_password = "gestion";
    // $mysql_database = "devsopa";

	//Esta es una prueba para una conexion local
	$mysql_host = "localhost";
    $mysql_user = "root";
    $mysql_password = "";
    $mysql_database = "devsopa";
	
	//Conexion a Mysql
	$db = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die ("Error durante la conexión a la base de datos");
    
	// We have problems with Tigo's login.
	$_SESSION["user"] = $user;

	$sqlCompanyUser  = "SELECT empresa, rol FROM usuarios WHERE inactivo = 0 AND usuario = '".$user."'";

	//Actualizacion del rol de jangeles
	// $usuarioNuevo = "UPDATE usuarios SET rol = 'AG' Where id = '170'";
	
	// if (($result = mysqli_query($db, $usuarioNuevo)) === false) {
	// 	die(mysqli_error($db));
	// }
	//Ejecución del query de Mysql paso de parametros la conexion y el query
	$resultCompanyUser = mysqli_query($db, $sqlCompanyUser);

	if (mysqli_num_rows($resultCompanyUser)) {

		 $data = mysqli_fetch_array($resultCompanyUser);
		 $_SESSION["empresa"] = $data["empresa"];
		 $_SESSION["rol"] = $data["rol"];
		 echo " mensaje 2: ",$data["empresa"],"<br>";

	} else {
		echo "<br />";
		echo "<table><tr>"
            ."<td><img src='images/error-icon.png'></td>"
            ."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Usuario sin Registrar - Contacte el Administrador de la Aplicación</td>"
		."</tr></table>";
	}
	
	if (($data["empresa"] == "UNE") || ($data["empresa"] == "Huawei")) {
		// Authentication wiht Active Directory.
		
		echo "mensaje 3: <br>";
		//Autenticación con directorio activo
		// $connection = ldap_connect("ldap://net-dc05", 389) or 
        //     die("<br /><table><tr>"
        //             ."<td><img src='images/error-icon.png'></td>"
        //             ."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Incapaz de Conectar con el Directorio Activo</td>"
        //         ."</tr></table>");

		// ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		// ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

		echo "mensaje 4:",$connection,"  <br>";
		
		//if ($connection) {
			//$authentication = ldap_bind($connection, $user."@epmtelco.com.co", $password);
			//if ($authentication) {
				//CONSULTA CON LA BASE DE DATOS PARA ASIGNAR ROL
				$sqlUserDB = "SELECT rol FROM usuarios WHERE inactivo = 0 AND usuario = '".$user."'";
				$resultUserDB = mysqli_query($db, $sqlUserDB);
				if (mysqli_num_rows($resultUserDB)) {
					// Session Variables.
					//$_SESSION["user"] = $user;
					//$data = mysqli_fetch_array($resultUserDB);
					//$_SESSION["rol"] = $data["rol"];
					                
					// Log Visits.
					date_default_timezone_set("America/Bogota"); 
					// Current Date.
					$dateArray = time();
					$date = date("Y/m/d H:i:s", $dateArray);

					if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					else if( isset( $_SERVER ['HTTP_VIA'] ))  
						$ip = $_SERVER['HTTP_VIA'];
					else if( isset( $_SERVER ['REMOTE_ADDR'] ))  
						$ip = $_SERVER['REMOTE_ADDR'];
					else $ip = "";                
                
					$sqlInsertVisit = "INSERT INTO visitas (fecha_visita, usuario, ip) VALUES ('".$date."', '".$user."', '".$ip."')";
					mysqli_query($db, $sqlInsertVisit);                      
                
					echo "<script type='text/javascript'>";
						echo "window.location = 'scripts/home/main.php';";
					echo "</script>";            
				}    
			// } else {
			// 	echo "<br />";
			// 	echo "<table><tr>"
            //             ."<td><img src='images/error-icon.png'></td>"
            //             ."<td style='font-family: Calibri; color: #676767; font-size: 14px;'>Autenticación de Usuario Fallida</td>"
			// 			."</tr></table>";
			// }
		//}		
    } else {
		/*CONSULTA CON LA BASE DE DATOS PARA ASIGNAR ROL*/
		$sqlUserDB = "SELECT rol FROM usuarios WHERE inactivo = 0 AND usuario = '".$user."'";
		$resultUserDB = mysqli_query($db, $sqlUserDB);
		if (mysqli_num_rows($resultUserDB)) {
			// Session Variables.
			//$_SESSION["user"] = $user;
			//$data = mysqli_fetch_array($resultUserDB);
			//$_SESSION["rol"] = $data["rol"];
               
			// Log Visits.
			date_default_timezone_set("America/Bogota"); 
			// Current Date.
			$dateArray = time();
			$date = date("Y/m/d H:i:s", $dateArray);

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if( isset( $_SERVER ['HTTP_VIA'] ))  
				$ip = $_SERVER['HTTP_VIA'];
			else if( isset( $_SERVER ['REMOTE_ADDR'] ))  
				$ip = $_SERVER['REMOTE_ADDR'];
			else $ip = "";                
                
			$sqlInsertVisit = "INSERT INTO visitas (fecha_visita, usuario, ip) VALUES ('".$date."', '".$user."', '".$ip."')";
			mysqli_query($db, $sqlInsertVisit);                     
                
			echo "<script type='text/javascript'>";
				echo "window.location = 'scripts/home/main.php';";
			echo "</script>"; 		
		}	
	}	
}
?>

