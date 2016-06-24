<?php

class Livre{


	private $Id;
	private $nomOeuvre;
	private $idType;
	private $idSerie;
	private $idEpisode;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getDesignation(){
		return "Un élément";
	}	public function getId(){
		return $this->Id;
	}

	public function getnomOeuvre(){
		return $this->nomOeuvre;
	}

	public function getidType(){
		return $this->idType;
	}

	public function getidSerie(){
		return $this->idSerie;
	}

	public function getidEpisode(){
		return $this->idEpisode;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setnomOeuvre($valeur){
		 $this->nomOeuvre = $valeur;
	}

	public function setidType($valeur){
		 $this->idType = $valeur;
	}

	public function setidSerie($valeur){
		 $this->idSerie = $valeur;
	}

	public function setidEpisode($valeur){
		 $this->idEpisode = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['nomOeuvre'])){
			$this->nomOeuvre = $data['nomOeuvre'];
		}
		if (isset($data['idType'])){
			$this->idType = $data['idType'];
		}
		if (isset($data['idSerie'])){
			$this->idSerie = $data['idSerie'];
		}
		if (isset($data['idEpisode'])){
			$this->idEpisode = $data['idEpisode'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getLivre($bdd){
		$requete = "SELECT * FROM li_livre_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setLivre($stmt);
	}



	pubic function getLivreDetail($bdd){
		$requete = "SELECT  Id, nomOeuvre, idType, idSerie, idEpisode FROM li_livre_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setLivre($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}