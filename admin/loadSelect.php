<script>
	function firstCoSensor(first)
	{
		var formData = {device: first};
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
	}

	function firstCoArduino(first)
	{
		var value = {arduino: first};
		$.ajax(
		{
			url : "admin/loadArduino.php",
			type: "POST",
			data: value,
			success: function(data, status)
			{
				$("#resArduino").html(data);
			}
		});
	}

</script>

<?php
require_once "./arduino.php";
require_once "GestionBase.php";

//Affiche la liste deroulante pour charger les dispositifs de la BD
function getDevice()
{
	$res = getDevices();
	echo "<select id='deviceName' name='device'>";
	$cpt = 0;
	foreach ($res as $value) 
	{
		if ($cpt == 0)
		{
			echo "<script> firstCoSensor('".$value["nomD"]."'); </script>";
			echo "<option value='".$value["nomD"]."'>".$value["nomD"]."</option>";
		}
		else
		{
			echo "<option value='".$value["nomD"]."'>".$value["nomD"]."</option>";
		}
		
		$cpt++;
	}
	echo "</select>";
}

// Affiche la liste déroulante pour charger les cartes Arduino de la BD
function getArduino()
{
	$arduino = getArduinoBoard();
	$cpt = 0;
	echo "<select id='arduinoName' name='arduino'>";
	//echo "Avant for";
	foreach ($arduino as $val) 
	{
		if ( $cpt == 0)
		{
			echo "<script> firstCoArduino('".$val["idA"]."'); </script>";
			echo "<option value='".$val["idA"]."'>".$val["nom"]."</option>";
		}
		else
		{
			//echo "Julienfd ".$val["nom"];
			echo "<option value='".$val["idA"]."'>".$val["nom"]."</option>";
		}
		$cpt++;
	}
	echo "</select>";
}

?>