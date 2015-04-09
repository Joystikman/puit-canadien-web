<?php
require_once "GestionBase.php";

if ( isset($_POST["port"]) && isset($_POST["idA"]))
{
	$idArdui = $_POST["idA"];
	$port = $_POST["port"];

	getLastValueSave($idArdui,$port);
}

?>