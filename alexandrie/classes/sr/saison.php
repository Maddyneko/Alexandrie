<?php

class Saison{


	private $Id;
	private $IdSerie;
	private $numeroSaison;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getIdSerie(){
		return $this->IdSerie;
	}

	public function getnumeroSaison(){
		return $this->numeroSaison;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setIdSerie($valeur){
		 $this->IdSerie = $valeur;
	}

	public function setnumeroSaison($valeur){
		 $this->numeroSaison = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['IdSerie'])){
			$this->IdSerie = $data['IdSerie'];
		}
		if (isset($data['numeroSaison'])){
			$this->numeroSaison = $data['numeroSaison'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getSaison($bdd){
		$requete = "SELECT * FROM sr_saison_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSaison($stmt);
	}



	pubic function getSaisonDetail($bdd){
		$requete = "SELECT  Id, IdSerie, numeroSaison FROM sr_saison_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSaison($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}