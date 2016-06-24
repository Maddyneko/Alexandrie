<?php

class Bds{


private $Bds;


/*********************/
/* GETTERS & SETTERS */
/*********************/

/*Getters*/
	public function getBds(){
		return $this->Bds;
	}




/*Setters*/



/*********************/
/*		 BDD 	   	 */
/*********************/
	public function setBds($bdd){
		$requete = "SELECT * FROM bd_bd_t";
		foreach ($bdd->query($requete) as $stmt){
			$this->Bds[] = $bds;
		}
	}




/*********************/
/*		 AUTRE 	   	 */
/*********************/
}