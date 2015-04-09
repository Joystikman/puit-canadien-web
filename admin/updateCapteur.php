<?php

require_once "GestionBase.php";

if ( isset($_POST["time"]) && isset($_POST["nomCapteur"]) )
{
	updateSensor($_POST["time"], getInfoSensor($_POST["nomCapteur"])["idC"]);
}

?>