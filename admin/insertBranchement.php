<?php
require_once "GestionBase.php";

if ( isset($_POST["idArduino"]) && isset($_POST["port"]) && isset($_POST["idCapteur"]) )
{
	$idA = $_POST["idArduino"];
	$port = $_POST["port"];
	$idC = $_POST["idCapteur"];

	insertBranchement($idA, $port, $idC);
}

?>