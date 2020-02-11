<?php
// ABRE CONEXION A LA BASE DE DATOS
function connexion($db)
{
	try
	{
		$conn = new PDO("mysql:host={$db['host']};dbname={$db['db']}",$db['username'],$db['password']);
		// CAPTURA DE EXCEPCION DE PDO
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	}
	catch(PDOException $exception)
	{
		exit($exception->getMessage());
	}
}

// UPDATES
function getParams($input)
{
	$parametroFiltro = [];

	foreach ($input as $param => $value) 
	{
		$parametroFiltro[] = "$param=:$param";
	}

	return implode(", ", $parametroFiltro);
}

// ASOCIA LOS PARAMETROS A UN SQL
function bindAllValues($statement, $params)
{
	foreach ($params as $param => $value) 
	{
		$statement->bindValue(':'. $param, $value);
	}
	print_r($statement);
	return $statement;
}
?>