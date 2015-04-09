<?php

require_once "GestionBase.php";

if ( isset($_POST["bool"]) && isset($_POST["port"]) && isset($_POST["idArd"]) )
{
	$enregistre = $_POST["bool"];
	$port = $_POST["port"];
	$idArd = $_POST["idArd"];
	updateConnection($enregistre, $port, $idArd);
}

?>