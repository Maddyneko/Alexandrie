<?php
	error_reporting(E_ALL);
	include_once('parametres.php');

//Création de la racine
makeArborescence(DIR_RACINE);

//Création des feuilles de style
makeArborescence(DIR_CSS, DIR_RACINE);
$chemin_style = DIR_RACINE . '/' . DIR_CSS;
importerFichier('bootstrap.min.css', TMPLT_RESSOURCES, $chemin_style);
creerFichier('', 'style', 'css', $chemin_style, '');

//Création du javascript
makeArborescence(DIR_JAVASCRIPT, DIR_RACINE);
$chemin_js = DIR_RACINE . '/' . DIR_JAVASCRIPT;
importerFichier('jquery.js', TMPLT_RESSOURCES, $chemin_js);
importerFichier('bootstrap.min.js', TMPLT_RESSOURCES, $chemin_js);

//Création des fichiers du site
creerFichier('', 'index', 'php', DIR_RACINE, '');

//Création des fichiers liés à la base de données
//Connexion à la base de données
$bdd = new PDO('mysql:host='.BDD_hote .';dbname='.BDD_base.';charset=utf8', BDD_login, BDD_mdp);
$requete = "SHOW TABLES FROM " . BDD_base;
$bdd_tables = array();
foreach($bdd->query($requete) as $stmt){
	$bdd_tables[] = $stmt[0];
}

$bdd_classes = array();
$bdd_liaisons = array();
foreach ($bdd_tables as  $table){
	$element_table = explode('_', $table);
	if(count($element_table) == 3){
		$bdd_classes[$element_table[0]][] = $element_table[1];
	}
	else if(count($element_table) == 4){
		$bdd_liaisons[] = $table;
	}
}
//Création des classes

makeArborescence(DIR_CLASSES, DIR_RACINE);
$chemin_classes = DIR_RACINE . '/' . DIR_CLASSES;

foreach($bdd_classes as $categorie => $elements_classe){
	makeArborescence($categorie, $chemin_classes);
	$chemin_categorie = $chemin_classes . '/' . $categorie;
	foreach($elements_classe as $classe){
		$variables = array();
		creerFichier($bdd, $classe, 'php', $chemin_categorie, $categorie, 'classe');
		creerFichier($bdd, $classe . 's', 'php', $chemin_categorie, $categorie, 'classes');

	}

}
//Création des vues
makeArborescence(DIR_VIEWS, DIR_RACINE);
$chemin_views = DIR_RACINE . '/' . DIR_VIEWS;

//Création des vues formulaires
makeArborescence(DIR_FORMS, $chemin_views);
$chemin_forms = $chemin_views. '/'. DIR_FORMS;
foreach($bdd_classes as $categorie => $elements_classe){
	makeArborescence($categorie, $chemin_forms);
	$chemin_categorie = $chemin_forms . '/' . $categorie;
	foreach($elements_classe as $classe){
		creerFichier($bdd, 'add_' . $classe, 'php', $chemin_categorie, $categorie);
		creerFichier($bdd, 'edit_' . $classe, 'php', $chemin_categorie, $categorie);
		creerFichier($bdd, 'del_' . $classe, 'php', $chemin_categorie, $categorie);
	}
}

//Création des vues simples
makeArborescence(DIR_PAGES, $chemin_views);
$chemin_pages = $chemin_views. '/'. DIR_PAGES;
echo $chemin_pages;
foreach($bdd_classes as $categorie => $elements_classe){
	makeArborescence($categorie, $chemin_pages);
	$chemin_categorie = $chemin_pages . '/' . $categorie;
	foreach($elements_classe as $classe){
		creerFichier($bdd, $classe, 'php', $chemin_categorie, $categorie, 'element');
		creerFichier($bdd, $classe . 's', 'php', $chemin_categorie, $categorie, 'elements');
	}
}

function makeArborescence($nomDossier, $chemin=''){
	if($chemin != '')
		$chemin = $chemin . '/';
	if(!file_exists($chemin.$nomDossier)){
		echo "<br/>Création du dossier " . $nomDossier;
		mkdir($chemin.$nomDossier);
	}
}

function importerFichier($nomFichier, $origine, $destination){
	if($origine != '')
		$origine = $origine . '/';
	if($destination != '')
		$destination = $destination . '/';
	if(!file_exists($destination.$nomFichier)){
		echo "<br/>Impotation du fichier " . $nomFichier;
		copy($origine . $nomFichier, $destination.$nomFichier);
	}

}

function creerFichier($bdd, $nom, $extension, $chemin, $categorie, $template=''){
	if($chemin != '')
		$chemin = $chemin . '/';
	$nomFichier = $chemin . $nom . '.'.$extension;

	$fichier = fopen($nomFichier . '.', 'w+');
	if ($template != ''){
		echo 'on est dans le if';
		switch ($template){
			case 'classe' :
				$contenu = templateClasse($bdd, $nom, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'classes' :
				$contenu = templateClasses($bdd, $nom, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'element' :
				$contenu = templatePageElement($bdd, $nom, $categorie, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'elements' :
				$contenu = templatePageElements($bdd, $nom, $categorie, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'addform' :
				$contenu = templateFormAdd($bdd, $nom, $categorie, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'editform' :
				$contenu = templateFormEdit($bdd, $nom, $categorie, $categorie);
				fputs($fichier, $contenu);
			break;
			case 'delform' :
				$contenu = templateFormDel($bdd, $nom, $categorie, $categorie);
				fputs($fichier, $contenu);
			break;
		}
	}
	fclose($fichier);
}

function templateClasse($bdd, $classe, $categorie){
	$contenu = '';
	$contenu = file_get_contents ('templates/classe.php');
	$table = $categorie . "_" . $classe . "_t";

	$requete = "SHOW columns from " . $table;
			
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$nomClasse = "";
	$variables = "";
	$getters = "";
	$setters = "";
	$base = "";
	$bddDetail = "";
	$setElement = "" ;

	$nomClasse = ucfirst(strtolower($classe));
	
	$getters = $getters . "\tpublic function getDesignation(){\n\t\treturn \"Un élément\";\n\t}";
	foreach($liste_fields as $field){
		$variables = $variables . "\tprivate $" . $field .";\n";
		$getters = $getters . "\tpublic function get". $field . "(){\n"
			."\t\treturn \$this->" . $field . ";\n"
			. "\t}\n\n"
		;
		$setters = $setters . "\tpublic function set". $field . "(\$valeur){\n"
			."\t\t \$this->" . $field . " = \$valeur;\n"
			. "\t}\n\n"
		;

	}
	$requete_fonction = "SELECT * FROM " . $table . " WHERE Id = \$this->Id LIMIT 0,1";
	$base = $base . "\tpubic function get" . $nomClasse . "(\$bdd){\n"
			. "\t\t\$requete = \"" . $requete_fonction . "\"; \n"
			. "\t\t\$stmt = \$bdd->query(\$requete)->fetch(); \n"
			. "\t\t\$this->set" . $nomClasse . "(\$stmt);\n"
			. "\t}\n\n"
			;

	$requete_fonction = "SELECT ";
	$i = 0;
	foreach($liste_fields as $field){
		$requete_fonction =  $requete_fonction . ($i > 0 ? "," : "") . " $field";
		$i++;
	}	
	$requete_fonction = $requete_fonction . " FROM " . $table . " WHERE Id = \$this->Id LIMIT 0,1";
	$bddDetail = $bddDetail . "\tpubic function get" . $nomClasse . "Detail(\$bdd){\n"
		. "\t\t\$requete = \"" . $requete_fonction . "\"; \n"
		. "\t\t\$stmt = \$bdd->query(\$requete)->fetch(); \n"
		. "\t\t\$this->set" . $nomClasse . "(\$stmt);\n"
		. "\t}\n\n"
		;

	$setElement = $setElement . "\tpublic function setVehicule(\$data){\n";
	foreach($liste_fields as $field){
		$setElement = $setElement . "\t\tif (isset(\$data['". $field . "'])){\n"
			. "\t\t\t\$this->". $field . " = \$data['". $field . "'];\n"
			. "\t\t}\n"
			;
	}
	$setElement = $setElement . "\t}\n\n";

	$contenu = str_replace('|*NOMCLASSE*|', $nomClasse, $contenu);
	$contenu = str_replace('|*VARIABLES*|', $variables, $contenu);
	$contenu = str_replace('|*GETTERS*|', $getters, $contenu);
	$contenu = str_replace('|*SETTERS*|', $setters, $contenu);
	$contenu = str_replace('|*BDD*|', $base, $contenu);
	$contenu = str_replace('|*BDDDETAIL*|', $bddDetail, $contenu);
	$contenu = str_replace('|*SETELEMENT*|', $setElement, $contenu);

	return $contenu;
}
function templateClasses($bdd, $classe, $categorie){
	$contenu = '';
	$contenu = file_get_contents ('templates/classe.php');
	$table = $categorie . "_" . substr ($classe, 0, -1) . "_t";
	$requete = "SHOW columns from " . $table;

	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$nomClasse = "";
	$variables = "";
	$getters = "";
	$setters = "";
	$bdd_base = "";
	$bddDetail = "";
	$setElement = "" ;

	$nomClasse = ucfirst(strtolower($classe));

	$variables = "private \$" . $nomClasse;

	$getters = "\tpublic function get". $nomClasse . "(){\n"
		."\t\treturn \$this->" . $nomClasse . ";\n"
		. "\t}\n\n"
		;
	$bdd_base = $bdd_base . "\tpublic function setVehicules(\$bdd){\n"
        . "\t\t\$requete = \"SELECT * FROM " . $table . "\";\n"
        . "\t\tforeach (\$bdd->query(\$requete) as \$stmt){\n"
        . "\t\t\t\$".$classe." = new ". $nomClasse ."();\n"
        . "\t\t\t\$".$classe."->set". $nomClasse ."(\$stmt);\n"
        . "\t\t\t\$this->" .$nomClasse."[] = \$".$classe.";\n"
        . "\t\t}\n"
   		. "\t}\n"
   		;

	$contenu = str_replace('|*NOMCLASSE*|', $nomClasse, $contenu);
	$contenu = str_replace('|*VARIABLES*|', $variables, $contenu);
	$contenu = str_replace('|*GETTERS*|', $getters, $contenu);
	$contenu = str_replace('|*SETTERS*|', $setters, $contenu);
	$contenu = str_replace('|*BDD*|', $bdd_base, $contenu);
	$contenu = str_replace('|*BDDDETAIL*|', $bddDetail, $contenu);
	$contenu = str_replace('|*SETELEMENT*|', $setElement, $contenu);

	return $contenu;
}
function templatePageElement($bdd, $classe, $categorie, $datas){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$table = $categorie . "_" . $classe . "_t";
	$nomClasse = ucfirst(strtolower($classe));

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$php = "";
	$title = "";
	$body = "";
	$css = "";
	$js = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}
	
	$php = $php . "<?php"
		. "\$idElement = '';\n"
		. "\tif(isset(\$GET_['id'])){\n"
		. "\t\t\$idElement = \$GET_['id'];\n"
		. "\t}\n"
		. "\telse{\n"
		. "\t\theader('Location:index.php');"
		. "\t}\n"
		. "\t\$" . $classe . " = new " . $nomClasse . "();\n"
		. "\t\$". $classe . "->setId(\$idElement);\n"
		. "\t\$" . $classe . "->get" . $nomClasse . "();\n"
		. "?>"
		;
	foreach($liste_fields as $field){
		$body = $body . "\t<p>"
			. "\t\t<?php echo \$" . $classe . "->get" . $field . "(); ?>"
			."\t</p>\n"
			;
	}
	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);

	return $contenu;
}

	function templatePageElements($bdd, $classe, $categorie){
		$contenu = '';
		$contenu = file_get_contents ('templates/page.php');
	$table = $categorie . "_" . substr ($classe, 0, -1) . "_t";
		$nomClasse = ucfirst(strtolower($classe));

		$requete = "SHOW columns from " . $table;
		$liste_fields = array();
		foreach($bdd->query($requete) as $stmt){
			$liste_fields[] = $stmt['Field'];
		}

		$php = "";
		$title = "";
		$body = "";
		$css = "";
		$js = "";

		$css = DIR_CSS;
		$js = DIR_JAVASCRIPT;
		$title = ''; //$datas['title'];


		//On récupère les colonnes de la table
		$requete = "SHOW columns from " . $table;
		$liste_fields = array();
		foreach($bdd->query($requete) as $stmt){
			$liste_fields[] = $stmt['Field'];
		}
		
		$php = $php . "<?php"
			. "\t\$". $classe . " = new " . $nomClasse . "();\n"
			. "\t\$". $classe . "->set" . $nomClasse . "();\n"
			. "?>"
			;
		foreach($liste_fields as $field){
			$body = $body . "<?php "
				. "\tforeach(\$". $classe . "->get" . $nomClasse . "() as \$element) { ?>\n"
				. "\t\t<p><?php echo \$element->getDesignation(); ?></p>\n"
				. "\t<?php } ?>\n"
				;
		}
		$contenu = str_replace('|*CSS*|', $css, $contenu);
		$contenu = str_replace('|*JS*|', $js, $contenu);
		$contenu = str_replace('|*TITLE*|', $title, $contenu);
		$contenu = str_replace('|*BODY*|', $body, $contenu);
		$contenu = str_replace('|*PHP*|', $php, $contenu);

		return $contenu;
	}

function templateFormAdd($bdd, $classe, $categorie, $datas){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$table = $categorie . "_" . $classe . "_t";
	$nomClasse = ucfirst(strtolower($classe));

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$php = "";
	$title = "";
	$body = "";
	$css = "";
	$js = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}
	
	$php = $php . "<?php"
		. "\$idElement = '';\n"
		. "\tif(isset(\$GET_['id'])){\n"
		. "\t\t\$idElement = \$GET_['id'];\n"
		. "\t}\n"
		. "\telse{\n"
		. "\t\theader('Location:index.php');"
		. "\t}\n"
		. "\t\$" . $classe . " = new " . $nomClasse . "();\n"
		. "\t\$". $classe . "->setId(\$idElement);\n"
		. "\t\$" . $classe . "->get" . $nomClasse . "();\n"
		. "?>"
		;
	foreach($liste_fields as $field){
		$body = $body . "\t<p>"
			. "\t\t<?php echo \$" . $classe . "->get" . $field . "(); ?>"
			."\t</p>\n"
			;
	}
	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);

	return $contenu;
}

function templateFormEdit($bdd, $classe, $categorie, $datas){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$table = $categorie . "_" . $classe . "_t";
	$nomClasse = ucfirst(strtolower($classe));

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$php = "";
	$title = "";
	$body = "";
	$css = "";
	$js = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}
	
	$php = $php . "<?php"
		. "\$idElement = '';\n"
		. "\tif(isset(\$GET_['id'])){\n"
		. "\t\t\$idElement = \$GET_['id'];\n"
		. "\t}\n"
		. "\telse{\n"
		. "\t\theader('Location:index.php');"
		. "\t}\n"
		. "\t\$" . $classe . " = new " . $nomClasse . "();\n"
		. "\t\$". $classe . "->setId(\$idElement);\n"
		. "\t\$" . $classe . "->get" . $nomClasse . "();\n"
		. "?>"
		;
	foreach($liste_fields as $field){
		$body = $body . "\t<p>"
			. "\t\t<?php echo \$" . $classe . "->get" . $field . "(); ?>"
			."\t</p>\n"
			;
	}
	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);

	return $contenu;
}

function templateFormDel($bdd, $classe, $categorie, $datas){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$table = $categorie . "_" . $classe . "_t";
	$nomClasse = ucfirst(strtolower($classe));

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}

	$php = "";
	$title = "";
	$body = "";
	$css = "";
	$js = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}
	
	$php = $php . "<?php"
		. "\$idElement = '';\n"
		. "\tif(isset(\$GET_['id'])){\n"
		. "\t\t\$idElement = \$GET_['id'];\n"
		. "\t}\n"
		. "\telse{\n"
		. "\t\theader('Location:index.php');"
		. "\t}\n"
		. "\t\$" . $classe . " = new " . $nomClasse . "();\n"
		. "\t\$". $classe . "->setId(\$idElement);\n"
		. "\t\$" . $classe . "->get" . $nomClasse . "();\n"
		. "?>"
		;
	foreach($liste_fields as $field){
		$body = $body . "\t<p>"
			. "\t\t<?php echo \$" . $classe . "->get" . $field . "(); ?>"
			."\t</p>\n"
			;
	}
	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);

	return $contenu;
}