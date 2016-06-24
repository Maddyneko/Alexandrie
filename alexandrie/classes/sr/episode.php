<?php

class Episode{


	private $Id;
	private $IdSerie;
	private $IdSaison;
	private $nomEpisode;


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

	public function getIdSaison(){
		return $this->IdSaison;
	}

	public function getnomEpisode(){
		return $this->nomEpisode;
	}




/*Setters*/
	public function setId($valeur){
		 $this->Id = $valeur;
	}

	public function setIdSerie($valeur){
		 $this->IdSerie = $valeur;
	}

	public function setIdSaison($valeur){
		 $this->IdSaison = $valeur;
	}

	public function setnomEpisode($valeur){
		 $this->nomEpisode = $valeur;
	}



	public function setVehicule($data){
		if (isset($data['Id'])){
			$this->Id = $data['Id'];
		}
		if (isset($data['IdSerie'])){
			$this->IdSerie = $data['IdSerie'];
		}
		if (isset($data['IdSaison'])){
			$this->IdSaison = $data['IdSaison'];
		}
		if (isset($data['nomEpisode'])){
			$this->nomEpisode = $data['nomEpisode'];
		}
	}


/*********************/
/*		 BDD 	   	 */
/*********************/
	pubic function getEpisode($bdd){
		$requete = "SELECT * FROM sr_episode_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setEpisode($stmt);
	}



	pubic function getEpisodeDetail($bdd){
		$requete = "SELECT  Id, IdSerie, IdSaison, nomEpisode FROM sr_episode_t WHERE Id = $this->Id LIMIT 0,1"; 
		$stmt = $bdd->query($requete)->fetch(); 
		$this->setEpisode($stmt);
	}



/*********************/
/*		 AUTRE 	   	 */
/*********************/
}