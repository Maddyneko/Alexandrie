<?php

class Livres{


private $Livres;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getLivres(){
		return $this->Livres;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setLivres($bdd){
		$requete = "SELECT * FROM li_livre_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Livres[] = $livres;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}