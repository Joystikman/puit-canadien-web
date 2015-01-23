<?php include('/admin/ConnexionBD.php'); ?>

<?php

//fonction qui génére un graph en fonction des capteurs et des dates selectionnées
	function generer_graph()
  {
  	$donnees = [];
  	date_default_timezone_set('UTC');
  	if(isset($_POST['generer'])){
			if(isset($_POST['capteursId'])&& isset($_POST['dateDebut']) && isset($_POST['dateFin'])){
				$dateDebut = $_POST['dateDebut'];
				$dateFin = $_POST['dateFin'];
				foreach($_POST['capteursId'] as $cap) {
					$capteur = getCapteur($cap);
					$dataCapteur = getData($capteur, $dateDebut, $dateFin);
					array_push($donnees, $dataCapteur);
				}
			}
			else{
				$idCapteurs = [1,7,10,16,19,22];
				foreach($idCapteurs as $cap) {
					$capteur = getCapteur($cap);
					$dateDebut = $_POST['dateDebut'];
					$dateFin = $_POST['dateFin'];
					$dataCapteur = getData($capteur, $dateDebut, $dateFin);
					array_push($donnees, $dataCapteur);
				}
			}
		}
		else{
				$idCapteurs = [1,7,10,16,19,22];
				foreach($idCapteurs as $cap) {
					$capteur = getCapteur($cap);
					$dateDebut = date('Y/j/m',mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")));
					$dateFin = date('Y/j/m',mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
					$dataCapteur = getData($capteur, $dateDebut, $dateFin);
					array_push($donnees, $dataCapteur);
				}
			}
			return $donnees;
	}

//fonction qui prend en paramètre le nom d'un capteur et renvoie le capteur
	function getCapteur($id){
		global $connexion;
		$pstmt = $connexion->prepare("Select * from capteur where idC = :id");
    	$pstmt -> bindParam(':id', $id);
    	$pstmt -> execute();
   	 	$capteur = $pstmt -> fetch();
    	return $capteur;
	}

//fonction qui prend en paramètre un capteur, une date de début et une date de fin et retourne les données enregistrer par le capteur pendant cet interval de temps
	function getData($capteur , $dateDebut, $dateFin){
		global $connexion;

		//transformation des dates pour qu'elles soient reconnu par MySQL
		$date_explosee = explode("/", $dateDebut);  // la fonction explode permet de séparer la chaine en tableau selon un délimiteur

  	$jourDeb = $date_explosee[1];
 		$moisDeb = $date_explosee[0];
 		$anneeDeb = $date_explosee[2];

		$date_explosee = explode("/", $dateFin);

 	 	$jourFin = $date_explosee[1];
 	 	$moisFin = $date_explosee[0];
 	 	$anneeFin = $date_explosee[2];

	 	$dateDebut = $anneeDeb."-".$jourDeb."-".$moisDeb;
 		$dateFin = $anneeFin."-".$jourFin."-".$moisFin;

 		//requete de selection des données
		$stmt = $connexion->prepare("Select date,valeur from donnees where date >= :date_d and date <= :date_f and idC = :id_capteur ");
		//'2014-01-15 19:00:13'
    $stmt -> bindParam(':date_d' , $dateDebut);
    $stmt -> bindParam(':date_f' , $dateFin);
    $stmt -> bindParam(':id_capteur' , $capteur[0]);
    $stmt -> execute();

    $i = 0;
    $data = array();

    foreach($stmt as $requete) {
    	$donneeX = array();

    	$date_explosee = explode("-", $requete[0]);
  		$jourDeb = $date_explosee[2];
 			$moisDeb = $date_explosee[1]-1;
 			$anneeDeb = $date_explosee[0];

 			$jourHeure = explode(" ", $jourDeb);
 			$jour = $jourHeure[0];

 			$temp = explode(":", $jourHeure[1]);
 			$heure = $temp[0];
 			$minute = $temp[1];
 			$seconde = $temp[2];


    	$donnee = "[Date.UTC(".$anneeDeb.", ".$moisDeb.", ".$jour.", ".$heure.", ".$minute.", ".$seconde."), ".$requete[1]." ]";
      //$donnee += ", ".$requete[1]." ]";
      $data[$i] = $donnee;
      $i = $i+1;
    }
    return $data;
	}

	function testPrint($data){
		echo "<br> Données : ";
		foreach ($data as $d) {
			echo " / ";
			echo $d;
		}
	}

	function printDonnees($donnees){
		echo "<br> Liste donnes :";
		foreach ($donnees as $data) {
			testPrint($data);
		}
	}




?>



