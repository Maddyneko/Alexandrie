
<?php

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

function creerFichier($bdd, $nom, $extension, $chemin, $categorie, $template='', $data=''){
	if($chemin != '')
		$chemin = $chemin . '/';
	$nomFichier = $chemin . $nom . '.'.$extension;
	$fichier = fopen($nomFichier . '.', 'w+');
	if ($template != ''){
		switch ($template){
			case 'page':
				$contenu = templatePage();
				fputs($fichier, $contenu);
			break;
			case 'style':
				$contenu = templateStyle();
				fputs($fichier, $contenu);
			break;
			case 'javascript':
				$contenu = templateJavascript();
				fputs($fichier, $contenu);
			break;
			case 'parametres' :
				$contenu = templateParametres($bdd, $data);
				fputs($fichier, $contenu);
				break;
			case 'menu':
				$contenu = templateMenu($bdd, $data);
				fputs($fichier, $contenu);
			break;
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
function templatePage(){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$php = "";
	$title = "";
	$body = "";
	$css = "";
	$js = "";
	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$domaine = SITE_DOMAINE;
	$title = ''; //$datas['title'];
	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);
	$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

	return $contenu;
}
function templateJavascript(){
	$contenu = '';
	$contenu = file_get_contents ('templates/layout/js.php');

	$js = "";
	$js = DIR_JAVASCRIPT;
	$title = ''; //$datas['title'];
	$contenu = str_replace('|*JS*|', $js, $contenu);


	return $contenu;
}
function templateStyle(){
	$contenu = '';
	$contenu = file_get_contents ('templates/layout/css.php');

	$css = "";
	$js = "";
	$css = DIR_CSS;
	$title = ''; //$datas['title'];
	$contenu = str_replace('|*CSS*|', $css, $contenu);

	return $contenu;
}
function templateMenu($bdd, $bdd_classes){
	$contenu = '';
	$contenu = file_get_contents ('templates/layout/menu.php');
	$menu = "";
	$menu = "<ul>\n";
	foreach($bdd_classes as $categorie => $elements_classe){
		$menu = $menu . "\t<li>" . $categorie. "\n\t\t<ul>\n";
		foreach($elements_classe as $classe){
			$menu = $menu . "\t\t\t<li><a href=\"views/pages/".$categorie."/".$classe."s.php\">" . $classe . "</a></li>\n";
		}
		$menu = $menu . "\t\t</ul>\n\t</li>\n";

	}
	$menu = $menu . "</ul>\n";

		
	$contenu = str_replace('|*MENU*|', $menu, $contenu);

	return $contenu;
}
function templateParametres(){
	$contenu = '';
	$contenu = "<?php \$bdd = new PDO(\"mysql:host=" . BDD_hote . ";dbname=" . BDD_base . ";charset=utf8\", '" . BDD_login . "', '". BDD_mdp . "');?>";
	return $contenu;

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

	$variables = "private \$" . $nomClasse . ";\n";

	$getters = "\tpublic function get". $nomClasse . "(){\n"
		."\t\treturn \$this->" . $nomClasse . ";\n"
		. "\t}\n\n"
		;
	$bdd_base = $bdd_base . "\tpublic function set" . $nomClasse . "(\$bdd){\n"
        . "\t\t\$requete = \"SELECT * FROM " . $table . "\";\n"
        . "\t\tforeach (\$bdd->query(\$requete) as \$stmt){\n"
        . "\t\t\t\$this->" .$nomClasse."[] = \$".$classe.";\n"
        . "\t\t}\n"
   		. "\t}\n"
   		;
	/*public function vehicules(){
        $this->vehicules = array();
        $positionMax = 0;
    }*/
	$contenu = str_replace('|*NOMCLASSE*|', $nomClasse, $contenu);
	$contenu = str_replace('|*VARIABLES*|', $variables, $contenu);
	$contenu = str_replace('|*GETTERS*|', $getters, $contenu);
	$contenu = str_replace('|*SETTERS*|', $setters, $contenu);
	$contenu = str_replace('|*BDD*|', $bdd_base, $contenu);
	$contenu = str_replace('|*BDDDETAIL*|', $bddDetail, $contenu);
	$contenu = str_replace('|*SETELEMENT*|', $setElement, $contenu);

	return $contenu;
}
//Vues
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
	$domaine = "";
	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$domaine = SITE_DOMAINE;
	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
		$liste_fields[] = $stmt['Field'];
	}
	
	$php = $php . "<?php "
		. "\$idElement = '';\n"
		. "\tif(isset(\$GET_['id'])){\n"
		. "\t\t\$idElement = \$GET_['id'];\n"
		. "\t}\n"
		. "\telse{\n"
		. "\t\theader('Location:../../../index.php');"
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
	$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

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
		$domaine = "";

		$css = DIR_CSS;
		$js = DIR_JAVASCRIPT;
		$domaine = SITE_DOMAINE;
		$title = ''; //$datas['title'];


		//On récupère les colonnes de la table
		$requete = "SHOW columns from " . $table;
		$liste_fields = array();
		foreach($bdd->query($requete) as $stmt){
					$liste_fields[] = $stmt['Field'];
		}
		
		$php = $php . "<?php\n "
			. "\tinclude_once('". SITE_DOMAINE . "/" . DIR_CLASSES . "/". $categorie . "/". $classe . ".php');\n"
			. "\t\$". $classe . " = new ". $nomClasse . "();\n"
			. "\t\$". $classe . "->set" . $nomClasse . "(\$bdd);\n"
			."?>"
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
		$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

		return $contenu;
	}

function templateFormAdd($bdd, $classe, $categorie, $datas){
	$contenu = '';
	$contenu = file_get_contents ('templates/page.php');
	$template_input=file_get_contents ('templates/form/input.php');
		$template_checkbox=file_get_contents ('templates/form/checkbox.php');
		$template_radiobox=file_get_contents ('templates/form/radiobox.php');
		$template_select=file_get_contents ('templates/form/select.php');
		$template_select_option=file_get_contents ('templates/form/select_option.php');
		$template_textarea=file_get_contents ('templates/form/textarea.php');
	$classe = substr ($classe, 4);
	$table = $categorie . "_" . $classe . "_t";

	echo $classe;
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
	$domaine = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$domaine = SITE_DOMAINE;

	$title = ''; //$datas['title'];

	$requete = "SHOW columns from " . $table;
	$liste_fields = array();
	foreach($bdd->query($requete) as $stmt){
$liste_fields[] = $stmt;
	}
	
	$php = $php . "";
	//Creation du formulaire
		$body = $body . "\t<form id=\"\" method=\"\" action=\"add". $nomClasse ."\">\n";
		foreach($liste_fields as $field){
			if($field['Key'] != 'PRI'){//On ne tient pas compte de la clé primaire
				$type = substr ($field['Type'], 0, 3);
				switch ($type){
					case 'int' :
						$body = $body . "\t\t" . $template_input . "\n";
						$body = str_replace('|*NOM*|', strtolower($field['Field']), $body);
						$body = str_replace('|*LABEL*|', ucfirst(strtolower($field['Field'])), $body);
						$body = str_replace('|*TYPE*|', "number", $body);
					break;
					case 'var' :
						$body = $body . "\t\t" .  $template_input. "\n";
						$body = str_replace('|*NOM*|', strtolower($field['Field']), $body);
						$body = str_replace('|*LABEL*|', ucfirst(strtolower($field['Field'])), $body);
						$body = str_replace('|*TYPE*|', "text", $body);	
					break;
				}
			}
		}
				$body = $body . "\t</form>\n";

	$contenu = str_replace('|*CSS*|', $css, $contenu);
	$contenu = str_replace('|*JS*|', $js, $contenu);
	$contenu = str_replace('|*TITLE*|', $title, $contenu);
	$contenu = str_replace('|*BODY*|', $body, $contenu);
	$contenu = str_replace('|*PHP*|', $php, $contenu);
	$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

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
	$domaine = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$domaine = SITE_DOMAINE;

	
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
	$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

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
	$domaine = "";

	$css = DIR_CSS;
	$js = DIR_JAVASCRIPT;
	$domaine = SITE_DOMAINE;
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
	$contenu = str_replace('|*DOMAINE*|', $domaine, $contenu);

	return $contenu;
}