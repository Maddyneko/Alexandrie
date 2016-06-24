<?php

class Episodes{


private $Episodes;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getEpisodes(){
		return $this->Episodes;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setEpisodes($bdd){
		$requete = "SELECT * FROM sr_episode_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Episodes[] = $episodes;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}