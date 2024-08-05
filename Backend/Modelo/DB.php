<?php
class Conectar
{

	public static function conexion()
	{
		$conexion = new mysqli("db", "devAutomotora", "3KV6Nn5gxRCpnk4", "automotora");
		if ($conexion->connect_errno) {

			echo "<p>Codigo de error: $conexion->connect_errno </p><br><br>";
			echo "<p>Error: $conexion->connect_error </p><br><br>";
			exit;
		}

		$conexion->query("SET NAMES 'utf8'");
		
		return $conexion;
	}
}
/*
CREATE USER 'ba'@'%' IDENTIFIED WITH mysql_native_password BY 'mypassword';
GRANT ALL PRIVILEGES ON *.* TO 'ba'@'%';
FLUSH PRIVILEGES;
*/

?>