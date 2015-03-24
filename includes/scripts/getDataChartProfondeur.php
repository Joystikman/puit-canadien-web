<?php
//fonction
function getCapteursProf($p){
  $sql = "SELECT idC  FROM capteur WHERE nivProfond='$p' order by idC";
  $profondeurs = $connexion->query($sql)->fetch()[0];
  return $profondeurs;
}

// graphe 

require_once(__dir__."/../../admin/ConnexionBD.php");

// set UTC time
$connexion->exec("SET time_zone = '+00:00'");
// set some utility variables
$range = $end - $start;
$startTime = gmstrftime('%Y-%m-%d %H:%M:%S', $start / 1000);
$endTime = gmstrftime('%Y-%m-%d %H:%M:%S', $end / 1000);

  echo "console.log('graphe profondeur data');";
  $capteurs = getCapteursProf(@$_GET["profondeur"]);
  $rows = [];
  foreach($capteurs as $c){
    $sql = "Select avg(valeur) FROM donnees WHERE idC='$c' and between '$startTime' and '$endTime'";
    $nom = getCapteurNameById($c);
    $result = $connexion->query($sql);
    $rows= "["$nom.",".$result[0]."]";
  }
  header('Content-Type: text/javascript');
  echo $callback ."([\n" . join(",\n", $rows) ."\n]);";
  }

?>