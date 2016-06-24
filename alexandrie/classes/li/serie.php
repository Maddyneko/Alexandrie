<?php

class Serie{


	private $Id;
	private $serie;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getserie(){
		return $this->serie;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setserie($valeur){
		 $this->serie = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['serie'])){
			$this->serie = $data['serie'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getSerie($bdd){
		$requete = "SELECT * FROM li_serie_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSerie($stmt);
	}



	pubic function getSerieDetail($bdd){
		$requete = "SELECT  Id, serie FROM li_serie_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setSerie($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}