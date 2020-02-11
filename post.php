<?php

include "configdb.php";
include "recursos.php";

$dbConn = connexion($db);


// LISTA LOS POST 

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if(isset($_GET['documento']))
	{
		$sqlcount = $dbConn->prepare("SELECT count(id_nvo) FROM nuevos WHERE usuario_reg_doc = :documento");
		$sqlcount->bindValue(':documento', $_GET['documento']);
		$sqlcount->execute();
		header("HTTP/1.1 200 OK");
		echo json_encode($sqlcount->fetch(PDO::FETCH_ASSOC));

		if($sqlcount->fetchAll() > 1)
		{
			$sql = $dbConn->prepare("SELECT * FROM nuevos WHERE usuario_reg_doc = :documento");
			$sql->bindValue(':documento', $_GET['documento']);
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 OK");
			echo json_encode($sql->fetchAll());
			// MOSTRAR UN POST 
		}
		else
		{	
				$sql = $dbConn->prepare("SELECT * FROM nuevos WHERE usuario_reg_doc = :documento");
				$sql->bindValue(':documento', $_GET['documento']);
				$sql->execute();
				header("HTTP/1.1 200 OK");
				echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
		}
		exit();
	}
	else
	{
		// MUESTRA TODOS LOS REGISTROS
		$sql = $dbConn->prepare("SELECT * FROM nuevos");
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		header("HTTP/1.1 200 OK");
		echo json_encode($sql->fetchAll());
		exit();	
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST))
	{
		$input	=	$_POST;
		$sql 	= 	"	INSERT INTO nuevos
						(imei_nvo, marca_nvo, usuario_reg_nom, usuario_reg_doc)
						VALUES
						(:imei, :marca, :usuario, :documento)";
		$statement = $dbConn->prepare($sql);
		bindAllValues($statement, $input);
		$statement->execute();
		$postID = $dbConn->lastInsertId();

		if($postID)
		{
			$input['id'] = $postID;
			header("HTTP/1.1 200 OK");
			echo json_encode($input);
			exit();
		}
	}
	else
	{
		echo json_encode('No envio datos');
	}
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['documento'];
    $fields = getParams($input);

    $sql = "
          UPDATE celulares
          SET $fields
          WHERE documento='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}



?>