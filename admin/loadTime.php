<script>
	$(document).ready(function()
	{
		$("#updateCo").click(function()
		{
			var nC = $("#sensorConnected option:selected").text();
			var data = {time: $("#inputTime").val(), nomCapteur: nC};
			$.ajax(
			{
				url : "admin/updateCapteur.php",
				type: "POST",
				data : data,
				success: function(data, status)
				{
					$("#res2").html(data);
					$('#sensorConnected').prop('selectedIndex', 0);
				}
			});
		});
	});
</script>

<?php

require_once "GestionBase.php";

if ( isset($_POST["nomCap"]) )
{
	$time = getTime(getInfoSensor($_POST["nomCap"])["idC"])[0];
	echo "<input type='number' id='inputTime' value='".$time."' step='1'>";
	echo "<input type='button' id='updateCo' value='Envoyer' >";  
}
?>