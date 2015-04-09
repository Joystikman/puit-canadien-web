<?php  
  include('GestionBase.php');

  AjouterArduino($_POST['nom'],$_POST['address'], $_POST['nbAnalogique'], $_POST['nbNumerique']);
  
  header("Location: ../administration.php");
?>
