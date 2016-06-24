<?php
	error_reporting(E_ALL);
	include_once('parametres.php');
	include_once('fonctions.php');

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

//Création des paramètres
makeArborescence('config', DIR_RACINE);
$chemin_config = DIR_RACINE . '/config';
creerFichier('', 'parametres', 'php', $chemin_config, '', 'parametres');

//Création du layout du site
makeArborescence('layout', DIR_RACINE);
$chemin_ly = DIR_RACINE . '/layout';

creerFichier('', 'styles', 'php', $chemin_ly, '', 'style');
creerFichier('', 'header', 'php', $chemin_ly, '');
creerFichier('', 'javascript', 'php', $chemin_ly, '', 'javascript');


//Création des fichiers du site
creerFichier('', 'index', 'php', DIR_RACINE, '', 'page');


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
creerFichier($bdd, 'menu', 'php', $chemin_ly, '', 'menu',$bdd_classes);

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
		creerFichier($bdd, 'add_' . $classe, 'php', $chemin_categorie, $categorie, 'addform');
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
