<?php

class Film{


	private $Id;
	private $NomFilm;
	private $Duree;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getNomFilm(){
		return $this->NomFilm;
	}

	public function getDuree(){
		return $this->Duree;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setNomFilm($valeur){
		 $this->NomFilm = $valeur;
	}

	public function setDuree($valeur){
		 $this->Duree = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['NomFilm'])){
			$this->NomFilm = $data['NomFilm'];
		}
		if (isset($data['Duree'])){
			$this->Duree = $data['Duree'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getFilm($bdd){
		$requete = "SELECT * FROM mv_film_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setFilm($stmt);
	}



	pubic function getFilmDetail($bdd){
		$requete = "SELECT  Id, NomFilm, Duree FROM mv_film_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setFilm($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}