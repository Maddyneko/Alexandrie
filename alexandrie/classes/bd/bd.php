<?php

class Bd{


	private $Id;
	private $NomBd;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getNomBd(){
		return $this->NomBd;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setNomBd($valeur){
		 $this->NomBd = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['NomBd'])){
			$this->NomBd = $data['NomBd'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getBd($bdd){
		$requete = "SELECT * FROM bd_bd_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setBd($stmt);
	}



	pubic function getBdDetail($bdd){
		$requete = "SELECT  Id, NomBd FROM bd_bd_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setBd($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}