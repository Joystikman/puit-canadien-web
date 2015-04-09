<?php
  include('GestionBase.php');

  suppressionBranchementByArduinoId($_POST['id']);
  suppressionArduino($_POST['id']);

  header("Location: ../administration.php");
?>