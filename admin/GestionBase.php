<?php
  include("ConnexionBD.php");

// renvoie tous les dispositifs geothermique de la base
function getDevices()
{
  global $connexion;
  $result = $connexion->prepare("SELECT * from dispositif");
  $result->execute();
  return $result;
}

// Retourne l'id d'un dispositif par son nom
function getIdByName($nameDispositif)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT idD FROM dispositif where nomD = :n");
  $result -> bindParam(':n', $nameDispositif);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne le nom d'un capteur en fonction de son id
function getSensorName($id)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT nomC FROM capteur where idC = :i");
  $result -> bindParam(':i', $id);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne le nom d'un dispositif par son id
function getNameById($idDevice)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT nomD FROM dispositif where idD = :d");
  $result -> bindParam(':d', $idDevice);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne les capteurs d'un dispositif
function getSensorByDevice($idDispo)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT * FROM capteur WHERE idD= :i");
  $result -> bindParam(':i', $idDispo);
  $result -> execute();
  $data = $result->fetchAll(PDO::FETCH_ASSOC);

  return $data;
}

// Retournes toutes les info d'un capteur en fonction de son id
function getInfoSensorID($id)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT * FROM capteur where idC = :i");
  $result -> bindParam(':i', $id);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne toutes les info d'un capteur en fonction de nom
function getInfoSensor($name)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT * FROM capteur where nomC = :n");
  $result -> bindParam(':n', $name);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne les info du capteur en fonction de son ID
function getSensorById($id)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT * FROM capteur where idC = :c");
  $result -> bindParam(':c', $id);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Retourne tous les cartes Arduino de la base de données
function getArduinoBoard()
{
  global $connexion;
  $result = $connexion->prepare("SELECT * from arduino");
  $result->execute();
  return $result;
}

// Retourne l'id de la carte arduino en fonction du nom
function getIdArduinoByName($arduinoName)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT idA FROM arduino where nom = :n");
  $result -> bindParam(':n', $arduinoName);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

// Requete d'insertion dans la table branchement
function insertBranchement($idA, $port, $idC)
{
  global $connexion;
  $result = $connexion -> prepare("INSERT INTO branchement VALUES (:a, :p, :c, NULL, NULL)");
  $result -> bindParam(':a', $idA);
  $result -> bindParam(':p', $port);
  $result -> bindParam(':c', $idC);
  $result -> execute();
  echo $result;
}

// Requete de suppresion dans la table branchement
function deleteBranchement($arduinoID, $port)
{
  global $connexion;
  $req = "DELETE FROM branchement WHERE idA = :i and port = :p";
  $result = $connexion -> prepare($req);
  $result -> bindParam(':i', $arduinoID);
  $result -> bindParam(':p', $port);
  $result -> execute();
}

function getArduinoInfoByID($id)
{
  global $connexion;
  $result = $connexion -> prepare("SELECT * FROM arduino where idA = :i");
  $result -> bindParam(':i', $id);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  return $data;
}

function getBranchement()
{
  global $connexion;
  $result = $connexion->prepare("SELECT * from branchement");
  $result->execute();
  return $result;
}

// Retourne l'id des capteurs branché
function getSensorInBranchement()
{
  global $connexion;
  $result = $connexion->prepare("SELECT idC from branchement");
  $result->execute();
  return $result;
}

// Retourne les connexions d'une carte Arduino
function getBranchementByArduinoID($idArduino)
{
  global $connexion;
  $result = $connexion->prepare("SELECT * FROM branchement WHERE idA = :i");
  $result -> bindParam(':i', $idArduino);
  $result->execute();
  return $result->fetchAll(PDO::FETCH_ASSOC);
}

// Retourne le nombre de port analogique ou numeric d'une carte arduino en fonction du param
function getPin($var, $id)
{
  global $connexion;
  if ($var == "analog")
  {
    $result = $connexion -> prepare("SELECT nbPanalog FROM arduino where idA = :i");
  }
  else if ($var == "numeric")
  {
    $result = $connexion -> prepare("SELECT nbPNum FROM arduino where idA = :i");
  }
  $result -> bindParam(':i', $id);
  $result -> execute();
  $data = $result->fetch(PDO::FETCH_ASSOC);

  foreach ($data as $key)
  {
    $res = $key;
  }
  return $res;
}

// Retourne l'intervalle de temps entre chaque envoi de données (attribut stocké dans capteur)
function getTime($idC)
{
  global $connexion;
  $result = $connexion->prepare("SELECT temps FROM capteur WHERE idC = :i");
  $result -> bindParam(':i', $idC);
  $result->execute();

  foreach ($result as $key) 
  {
    $res = $key;
  }
  return $res;
}

// Pour modifié l'intervalle de temps d'un capteur
function updateSensor($time, $id)
{
  global $connexion;
  $result = $connexion->prepare("UPDATE capteur SET temps = :t WHERE idc = :i");
  $result -> bindParam(':t', $time);
  $result -> bindParam(':i', $id);
  $result->execute();
}

// Démarer ou arreter l'enregistrement des données
function updateConnection($bool, $port, $idA)
{
  global $connexion;
  $result = $connexion->prepare("UPDATE branchement SET enregistre = :e WHERE port = :p and idA = :i");
  $result -> bindParam(':e', $bool);
  $result -> bindParam(':p', $port);
  $result -> bindParam(':i', $idA);
  $result->execute();
}

// Retourne la dernier valeur enregistrer dans branchement
function getLastValueSave($idArduino, $port)
{
  global $connexion;
  $result = $connexion->prepare("SELECT valeurActuelle FROM branchement WHERE idA = :o and port = :p");
  $result -> bindParam(':o', $idArduino);
  $result -> bindParam(':p', $port);
  $result->execute();
  $res = $result->fetch(PDO::FETCH_ASSOC);

  if ($res["valeurActuelle"] == NULL)
  {
    return "null";
  }
  else
  {
    return $res["valeurActuelle"];
  }
}

  // renvoie le nom de tous les dispositifs
    function infoDispositif() {
    global $connexion;
    $result = $connexion->prepare("SELECT * from dispositif");
    $result->execute();
    return $result;
  }
  
  // renvoie les infos de tous les capteurs
  function infoCapteur() {
    global $connexion;
    $result = $connexion -> prepare("SELECT * from capteur");
    $result -> execute();
    return $result;
  }

  // renvoie les infos de tous les arduino
  function infoArduino() {
    global $connexion;
    $result = $connexion -> prepare("SELECT * from arduino");
    $result -> execute();
    return $result;
  }

function AjouterArduino($nom, $adress, $nbAnalogique, $nbNumerique) {
     global $connexion;
     $result = $connexion -> prepare("INSERT INTO arduino VALUES (NULL, :nom, :adress, :nbAnalogique, :nbNumerique)");
     $result -> bindParam(':nom', $nom);
     $result -> bindParam(':adress', $adress);
     $result -> bindParam(':nbAnalogique', $nbAnalogique);
     $result -> bindParam(':nbNumerique', $nbNumerique);
     $result -> execute();
   }

  function AjouterCapteur($idD, $nom, $type, $unite, $x, $z, $y) {
    if ($unite == "tempe") {
      $unite = "°C";
    }
    global $connexion;
    $connexion->exec("SET NAMES UTF8");
    $niveau = (420-$y)/100;
    $result = $connexion -> prepare("INSERT INTO capteur VALUES (NULL, :idD, :nomC, :typeC, :unite, :nivProfond, :posXC, :posYC, :posZC, 5)");
    $result -> bindParam(':idD', $idD);
    $result -> bindParam(':nomC', $nom);
    $result -> bindParam(':typeC', $type);
    $result -> bindParam(':unite', $unite);
    $result -> bindParam(':nivProfond', $niveau);
    $result -> bindParam(':posXC', $x);
    $result -> bindParam(':posYC', $y);
    $result -> bindParam(':posZC', $z);
    $result -> execute();
  }

  function AjouterDispositif($nom, $type, $lieu, $posx, $posy, $posz) {
     global $connexion;
     $result = $connexion -> prepare("INSERT INTO dispositif VALUES (NULL, :nom, :type, :lieu, :posx, :posy, :posz)");
     $result -> bindParam(':nom', $nom);
     $result -> bindParam(':type', $type);
     $result -> bindParam(':lieu', $lieu);
     $result -> bindParam(':posx', $posx);
     $result -> bindParam(':posy', $posy);
     $result -> bindParam(':posz', $posz);
     $result -> execute();
   }
  
  function suppressionDonnees($id) {
    global $connexion;
    $result = $connexion -> prepare("DELETE from donnees where idC = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }

  function suppressionBranchement($id) {
    global $connexion;
    $result = $connexion -> prepare("DELETE from branchement where idC = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }

  function suppressionArduino($id)
  {
    global $connexion;
    $result = $connexion -> prepare("DELETE from arduino where idA = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }

  function suppressionBranchementByArduinoId($id) {
    global $connexion;
    $result = $connexion -> prepare("DELETE from branchement where idA = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }

  function suppressionCapteur($id) {
    global $connexion;
    $result = $connexion -> prepare("DELETE from capteur where idC = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }

  function suppressionDispositif($id) {
    global $connexion;
    $result = $connexion -> prepare("DELETE from dispositif where idD = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }
  
  function suppressionDispositifCapteur($id) {
	global $connexion;
	$resultCapteur = $connexion -> prepare("SELECT idC from capteur where idD = :id");
	$resultCapteur -> bindParam('id', $id);
	$resultCapteur -> execute();
	$supprDonnee = $connexion -> prepare("DELETE from donnees where idC = :idc");
	while($data = $resultCapteur->fetch(PDO::FETCH_ASSOC)){
		$supprDonnee -> bindParam('idc', $data['idC']);
		$supprDonnee -> execute();
	}
	$supprCapt = $connexion -> prepare("DELETE from capteur where idD = :id");
    $supprCapt -> bindParam(':id', $id);
    $supprCapt-> execute();
    $result = $connexion -> prepare("DELETE from dispositif where idD = :id");
    $result -> bindParam(':id', $id);
    $result -> execute();
  }
?>
