<?php 
require_once "admin/GestionBase.php";
require_once "admin/loadSelect.php";
?>

	<link rel="stylesheet" href="assets/css/general.css">
	<link rel="stylesheet" href="assets/css/arduinoBoard.css">
	
	<script src="assets/vendor/jquery/jquery-1.9.1.min.js"></script>
	<link rel="stylesheet" href="assets/vendor/jquery/jquery-ui.css">
	<script src="assets/vendor/jquery/jquery-ui.js"></script>

	<script>
		$(document).ready(function()
		{
			$("#deviceName").change(function()
			{
				var formData = {device: this.value};
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
			});

			$("#arduinoName").change(function()
			{
				var value = {arduino: this.value};
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
		});
	</script>
</head>
<body>
	<div id="global">
		<div id="gauche">
			<div id='device'>
				<?php getDevice(); ?>
				<div id="resDevice">
				</div>
			</div>
		</div>
		
		<div id="droit">
			<div id="arduino">
				<?php getArduino(); ?>
				<div id="resArduino">
				</div>
			</div>
		</div>
		<div id="arduinoBoard">
			<img src="assets/images/arduino.png">
		</div>
	</div>

	<div id="time">
	<p>Gestion de l'intervalle de temps : </p>
		<div id="resConnected">
		</div>
		<div id="res2">
		</div>
	</div>
</body>

</html>