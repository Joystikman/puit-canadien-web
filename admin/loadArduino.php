<script>
	$(document).ready(function()
	{	
		$( ".analogPort" ).droppable({
			accept: ".analogSensor",
			activeClass: "ui-state-hover",
			hoverClass: "ui-state-active",
			drop: function( event, ui ) 
			{
				// Sauvegarde du port
				var p = $(this).attr('id');
				// Recup de l'id de la carte arduino
				var arduinoId = $("#arduinoName :selected").val();
				// Sauvegarde de l'état initial de la div
				var saveDiv = $(this).html();

				console.log($(this));

				$( this ).addClass( "ui-state-highlight" );
				$( this ).find( "p" );
				var saveIdCapteur = $(ui.draggable).attr('id');
				// Copie du contenu draggé
				$( this ).html( $(ui.draggable).html() );
				// Ajout du bouton Suppresion
				var bt = $('<input type="button" class="delete" onclick="deleteSensor('+arduinoId+","+p+');"/>');
				bt.appendTo($(this));
				//$(this).append(bt);
				// Suppresion de la div draggé aprés recopie du code html
				ui.draggable.remove();

				
				var data = {idArduino: arduinoId, port: p, idCapteur: saveIdCapteur};
				$.ajax(
				{
					url : "admin/insertBranchement.php",
					type: "POST",
					data: data
				});

				var d = {arduino: arduinoId};
				$.ajax(
				{
					url : "admin/loadArduino.php",
					type: "POST",
					data: d,
					success: function(data, status)
					{
						$("#resArduino").html(data);
					}
				});

				var data = {idCapteur: saveIdCapteur};
				$.ajax(
				{
					url : "admin/timeUpdate.php",
					type : "POST",
					data: data,
					success: function(data, status)
					{
						$("#resConnected").html(data);
					}
				});
			}
		});	

$( ".numericPort" ).droppable({
	accept: ".numericSensor",
	activeClass: "ui-state-hover",
	hoverClass: "ui-state-active",
	drop: function( event, ui ) 
	{
			// Sauvegarde du port
			var p = $(this).attr('id');
			// Sauvegarde de l'état initial de la div
			var saveDiv = $(this).html();
			$( this ).addClass( "ui-state-highlight" );
			$( this ).find( "p" );
			var saveIdCapteur = $(ui.draggable).attr('id');
			// Copie du contenu draggé
			$( this ).html( $(ui.draggable).html() );
			// Ajout du bouton Suppresion
			var bt = $('<input type="button" class="delete" onclick="deleteSensor('+arduinoId+","+p+');"/>');
			bt.appendTo($(this));
			// Suppresion de la div draggé aprés recopie du code html
			ui.draggable.remove();

			var arduinoId = $("#arduinoName :selected").val();
			var data = {idArduino: arduinoId, port: p, idCapteur: saveIdCapteur};
			$.ajax(
			{
				url : "admin/insertBranchement.php",
				type: "POST",
				data: data,
			});

			var d = {arduino: arduinoId};
			$.ajax(
			{
				url : "admin/loadArduino.php",
				type: "POST",
				data: d,
				success: function(data, status)
				{
					$("#resArduino").html(data);
				}
			});

			var data = {idCapteur: saveIdCapteur};
			$.ajax(
			{
				url : "admin/timeUpdate.php",
				type : "POST",
				data: data,
				success: function(data, status)
				{
					$("#resConnected").html(data);
				}
			});
		}
	});	

$(".play").click(function(){
	if ( $(this).attr("id").charAt(0) == 's')
	{
		var p = $(this).attr("id").charAt(5) ;
		$(this).css("background-image", "url(assets/images/rounded57.png)");
		$(this).attr("id", "pause"+p);

		p = parseInt(p);
		var enregistre = 1;
		var arduinoId = parseInt($("#arduinoName :selected").val());
		var donnees = {bool: enregistre, port: p, idArd: arduinoId};
		$.ajax(
		{
			url : "admin/updateConnection.php",
			type : "POST",
			data: donnees,
		});
	}
	else if ( $(this).attr("id").charAt(0) == 'p')
	{
		p = $(this).attr("id").charAt(5) ;
		$(this).css("background-image", "url(assets/images/play43.png)");
		$(this).attr("id", "start"+p);

		p = parseInt(p);
		var enregistre = 0;
		var arduinoId = $("#arduinoName :selected").val();
		var donnees = {bool: enregistre, port: p, idArd: arduinoId};
		$.ajax(
		{
			url : "admin/updateConnection.php",
			type : "POST",
			data: donnees,
		});
	}
});

$("#sendTime").click(function(){
	alert("App");
});

});

</script>


<?php
require_once "GestionBase.php";

if ( isset($_POST["arduino"]) )
{
	$id = $_POST["arduino"];

	// Contient en clé les ports et en valeur les id des capteurs
	$dico = array();
	$allBranch = getBranchementByArduinoID($id);
	foreach ($allBranch as $branch) {
		$dico[$branch["port"]] = $branch["idC"];
	}

	function sensorAnalogConnected($idCapteur, $port, $idA)
	{	
		$data = getInfoSensorID($idCapteur);
		$nomCapteur = $data["nomC"];
		$idDispo = $data["idD"];
		foreach (getNameById($idDispo) as $key)
		{
			$nameDispo = $key;
		}

		$unite = $data["unite"];
		$temps = $data["temps"];
		echo "<div id='".$port."'class='ui-widget-header droppable'><p>".getLastValueSave($idA,$port)."</p>";
		echo "<input id='start".$port."' class='play' type='button'></input>";
		echo "<input id='".$port."' class='info' type='button' onclick='popUpInfo(\"".str_replace('"', '\"', $nomCapteur)."\",\"".str_replace('"', '\"', $nameDispo)."\",\"".str_replace('"', '\"', $unite)."\",\"".$temps."\");' name='info'></input>";
		echo "<input class='delete' type='button' onclick='deleteSensor(".$idA.",".$port.");'></input>";
		echo "</div>";
	}

	function sensorNumericConnected($idCapteur, $port, $idA)
	{	
		$data = getInfoSensorID($idCapteur);
		$nomCapteur = $data["nomC"];
		$idDispo = $data["idD"];
		foreach (getNameById($idDispo) as $key)
		{
			$nameDispo = $key;
		}

		$unite = $data["unite"];
		$temps = $data["temps"];

		echo "<div id='".$port."'class='ui-widget-header droppable'><p>".getLastValueSave($idA,$port)."</p>";
		echo "<input id='start".$port."' class='play' type='button'></input>";
		echo "<input id='".$port."' class='info' type='button' onclick='popUpInfo(\"".str_replace('"', '\"', $nomCapteur)."\",\"".str_replace('"', '\"', $nameDispo)."\",\"".str_replace('"', '\"', $unite)."\",\"$temps\");' name='info'></input>";
		echo "<input class='delete' type='button' onclick='deleteSensor(".$idA.",".$port.");'></input>";
		echo "</div>";
	}

	echo "<div id='analog'>";
	echo "<h3>Ports Analogiques</h3>";

	for ($i = 0 ; $i < getPin("analog", $id) ; $i++)
	{	
		if ( array_key_exists($i, $dico) )
		{
			sensorAnalogConnected($dico[$i], $i, $_POST["arduino"]);
		}
		else
		{
			echo "<div id='".$i."'class='ui-widget-header droppable analogPort'><p>A".$i."</p></div>";
		}
	} 
	echo "</div>";

	echo "<h3>Ports Numériques</h3>";
	echo "<div id='numeric' name='numeric'>";

	for ($i = getPin("analog", $id) ; $i < getPin("analog", $id) + getPin("numeric", $id) ; $i++)
	{
		if ( array_key_exists($i, $dico) )
		{
			sensorNumericConnected($dico[$i], $i, $_POST["arduino"]);
		}
		else
		{
			echo "<div id='".$i."'class='ui-widget-header droppable numericPort'><p>D".$i."</p></div>";
		}

	} 
	echo "</div> ";
}
else
{
	echo "Pas de carte Arduino de ce nom";
}

?>