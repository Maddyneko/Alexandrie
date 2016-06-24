<?php

class Serie{


	private $Id;
	private $nomSerie;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getnomSerie(){
		return $this->nomSerie;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setnomSerie($valeur){
		 $this->nomSerie = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['nomSerie'])){
			$this->nomSerie = $data['nomSerie'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getSerie($bdd){
		$requete = "SELECT * FROM sr_serie_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSerie($stmt);
	}



	pubic function getSerieDetail($bdd){
		$requete = "SELECT  Id, nomSerie FROM sr_serie_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSerie($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}