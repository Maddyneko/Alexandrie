<?php

class Series{


private $Series;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getSeries(){
		return $this->Series;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setSeries($bdd){
		$requete = "SELECT * FROM li_serie_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Series[] = $series;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}