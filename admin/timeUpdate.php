<script>
	$(document).ready(function()
	{	
		$(function(){
			if ( $('#sensorConnected option').eq(1).val() == null )
			{
				$("#time").hide();
			}
			else
			{
				$("#time").show();
			}
		});
		
		$("#sensorConnected").change(function()
		{
			if ( !($('#sensorConnected').click().val() == "Sélectionner un capteur"))
			{
				var data = {nomCap: this.value}
				$.ajax( 
				{
					url : "admin/loadTime.php",
					type: "POST",
					data : data,
					success: function(data, status)
					{
						$("#res2").html(data);
					}
				});
			}
		});	

	});
</script>

<?php

require_once "GestionBase.php";

$sensor = getSensorInBranchement();
echo "<select id='sensorConnected' name='sensorConnected'>";
echo "<option>Sélectionner un capteur</option>";
foreach ($sensor as $val) 
{
	echo "<option>".getSensorName($val["idC"])["nomC"]."</option>";
}
echo "</select>";

?>