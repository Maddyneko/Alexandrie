<?php include_once('C:\wamp64\www\alexandrie/config/parametres.php'); ?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title></title>

    <!-- Bootstrap -->
    <?php include_once('C:\wamp64\www\alexandrie/layout/styles.php'); ?>

  </head>
  <body>
    <?php include_once('C:\wamp64\www\alexandrie/layout/header.php'); ?>
    <?php include_once('C:\wamp64\www\alexandrie/layout/menu.php'); ?>

    	<form id="" method="" action="addEpisode">
		<div class="form-group">
	<label for="idserie">Idserie</label>
	<input type="number" class="form-control" id="idserie" placeholder="">
</div>
		<div class="form-group">
	<label for="idsaison">Idsaison</label>
	<input type="number" class="form-control" id="idsaison" placeholder="">
</div>
		<div class="form-group">
	<label for="nomepisode">Nomepisode</label>
	<input type="text" class="form-control" id="nomepisode" placeholder="">
</div>
	</form>



  <?php include_once('C:\wamp64\www\alexandrie/layout/javascript.php'); ?>

  </body>
</html>