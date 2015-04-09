<script>
	$(document).ready(function()
	{
		$(".draggable").draggable();
	});

	function popUpInfo(nomC, nomD, unite, nivP)
	{
		alert(	"Le nom du capteur est : " + nomC + 
			"\nLe dispositif est : " + nomD + 
			"\nL'unité utilisée par le capteur : " + unite + 
			"\nNiveau de profondeur : " + nivP +"m");
	}

	function deleteSensor(arduinoID, port)
	{
		var data = {idA: arduinoID , port: port};
		$.ajax(
		{
			url : "admin/deleteBranchement.php",
			type: "POST",
			data: data,
		});

		var formData = {device: $("#deviceName :selected").val()};
		$.ajax(
		{
			url : "admin/loadSensor.php",
			type: "POST",
			data : formData,
			success: function(data, status)
			{
				$("#resDevice").html(data);
			}
		});

		var dat = {arduino: $("#arduinoName :selected").val()};
		$.ajax(
		{
			url : "admin/loadArduino.php",
			type: "POST",
			data : dat,
			success: function(data, status)
			{
				$("#resArduino").html(data);
			}
		});

		$.ajax(
		{
			url : "admin/timeUpdate.php",
			type : "POST",
			success: function(data, status)
			{
				$("#resConnected").html(data);
			}
		});

	}
</script>

<?php
require_once "GestionBase.php";

// Affiche les capteur d'un dispositif
if( isset($_POST["device"]) )
{
	$id = getIdByName($_POST["device"]);
	foreach ($id as $key) 
	{
		$stock = $key;
	}
	$sensor = getSensorByDevice($stock);

	// Verification si le capteur est déja connecté
	$arrayIdSensor = array();

	foreach (getBranchement() as $k)
	{
		array_push($arrayIdSensor, $k["idC"]);
	}

	if ($sensor)
	{
		echo "<div id=res>";
		foreach ($sensor as $val) 
		{
			$nomCapteur = $val["nomC"];
			$idDispo = $val["idD"];
			foreach (getNameById($idDispo) as $key)
			{
				$nameDispo = $key;
			}
			$unite = $val["unite"];
			$nivProf = $val["nivProfond"];


			if ( !(in_array($val["idC"], $arrayIdSensor)) )
			{
				if ( $val['typeC'] == "A")
				{
					echo "<div style='background:#1a82fd' id='".$val['idC']."' class='ui-widget-content draggable analogSensor'>".$val['nomC'];
				// bleu Analogique
					echo "<input type='button' id='".$val['idC']."' name='info' class='info' onclick='popUpInfo(\"".str_replace('"', '\"', $nomCapteur)."\",\"".str_replace('"', '\"', $nameDispo)."\",\"".str_replace('"', '\"', $unite)."\",\"".$nivProf."\");'>";
					echo "</div>";
				}
				else
				{
					echo "<div style='background:#23fd32' id='".$val['idC']."' class='ui-widget-content draggable numericSensor'>".$val['nomC'];
				// Vert Numerique
					echo "<input type='button' id='".$val['idC']."' name='info' class='info' onclick='popUpInfo(\"".str_replace('"', '\"', $nomCapteur)."\",\"".str_replace('"', '\"', $nameDispo)."\",\"".str_replace('"', '\"', $unite)."\",\"".$nivProf."\");'>";
					echo "</div>";
				}
			}
		}
		echo "</div>";
	}
	else
	{
		echo "No sensor";
	}
}
?>