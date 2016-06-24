<?php

class Films{


private $Films;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getFilms(){
		return $this->Films;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setFilms($bdd){
		$requete = "SELECT * FROM mv_film_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Films[] = $films;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}