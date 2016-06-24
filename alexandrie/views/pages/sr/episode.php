<?php include_once('C:\wamp64\www\alexandrie/config/parametres.php'); ?>
<?php $idElement = '';
	if(isset($GET_['id'])){
		$idElement = $GET_['id'];
	}
	else{
		header('Location:../../../index.php');	}
	$episode = new Episode();
	$episode->setId($idElement);
	$episode->getEpisode();
?>
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

    	<p>		<?php echo $episode->getId(); ?>	</p>
	<p>		<?php echo $episode->getIdSerie(); ?>	</p>
	<p>		<?php echo $episode->getIdSaison(); ?>	</p>
	<p>		<?php echo $episode->getnomEpisode(); ?>	</p>



  <?php include_once('C:\wamp64\www\alexandrie/layout/javascript.php'); ?>

  </body>
</html>