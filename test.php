<?php
	include_once('parametres.php');

	$classe = "livre";
	$categorie = "li";
	$bdd = new PDO('mysql:host='.BDD_hote .';dbname='.BDD_base.';charset=utf8', BDD_login, BDD_mdp);
	
	$table = $categorie . "_" . $classe . "_t";
	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt;
;
	}
	echo "<pre>";
	//print_r($liste_fields);
	echo "</pre>";


	$body ="";
	$body = $body . "\t<form id=\"\" method=\"\" action=\"\">";
		foreach($liste_fields as $field){
			if($field['Key'] != 'PRI'){//On ne tient pas compte de la clé primaire
				$type = substr ($field['Type'], 0, 3);
				echo $type;
				switch ($type){
					case 'int' :
					break;
					case 'var' :
					break;
				}
			}
		}
		$body = $body . "\t</form>";
		echo $body;