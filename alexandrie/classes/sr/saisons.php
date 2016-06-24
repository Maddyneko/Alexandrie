<?php

class Saisons{


private $Saisons;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getSaisons(){
		return $this->Saisons;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setSaisons($bdd){
		$requete = "SELECT * FROM sr_saison_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Saisons[] = $saisons;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}