<?php
   session_start();
   if($_SESSION["autoriser"]!="oui"){
      header("location:login.php");
      exit();
   }
   if(date("H")<18)
      $bienvenue="Bonjour et bienvenue ".
      $_SESSION["login"].
      " dans votre espace personnel";
   else
      $bienvenue="Bonsoir et bienvenue ".
      $_SESSION["login"].
      " dans votre espace personnel";
      $id_user = $_SESSION["id_user"];


// https://ns350014.ip-188-165-209.eu:8443/phpMyAdmin/index.php?db=admin_GC_BDD


require_once('inc/config.php');

if($debug){
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  if($debug_phpinfo){
    phpinfo();
  }
}

//Connexion à la BDD

  

?>

<?php include('inc/connect.php');?>


<?php include('inc/header.php');?>

Mon login<br/>

Option : changer login / pwd<br/>
Nouvelle partie<br/>
Liste des parties en cours<br/>
Continuer => renvois directement sur le dernier pargraphe de la partie <br/>
Lire => Met tous les paragraphes d'une partie à la suite pour former l'histoire en cours

<h2><?php echo $bienvenue?></h2>
[ <a href="deconnexion.php">Se déconnecter</a> ]
<p>Compte N°<?php echo $id_user; ?></p>

<div>
	<h2>Nouvelle Partie</h2>
	<a href="adventure.php?newstory=true">Voulez vous commencer une nouvelle partie ? </a>
</div>


<!-- Avant :ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION 
Modification : SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))-->


<div>
	<h2>Liste des parties</h2>
	<?php

	//TMP
	$id_user = 1;

  // Liste des sauvegarde


  // SELECT * FROM game_in_progress INNER JOIN save ON game_in_progress.id_game_in_progress = save.id_game_in_progress WHERE game_in_progress.id_user=1

  //SELECT DISTINCT(game_in_progress.id_game_in_progress) AS id_game, game_in_progress.id_user, save.id_save, max(save.date_save) AS date_derniere_save FROM game_in_progress INNER JOIN save ON save.id_game_in_progress = game_in_progress.id_game_in_progress GROUP BY game_in_progress.id_game_in_progress;


$query = $db->prepare('SELECT DISTINCT(game_in_progress.id_game_in_progress) AS id_game, save.id_paragraphe, game_in_progress.id_user, game_in_progress.id_save, max(save.date_save) AS date_derniere_save FROM game_in_progress INNER JOIN save ON save.id_game_in_progress = game_in_progress.id_game_in_progress WHERE game_in_progress.id_user=:id_user GROUP BY game_in_progress.id_game_in_progress');


  $query->execute(array( 'id_user' => $id_user ));

?>

<table>
 		<tr>
 			<th>id_game_in_progress</th>
 			<th>id_user</th>
 			<th><!-- id_save --></th>
 			<th>id_paragraphe_out</th>
 		</tr>

<?php
  foreach ($query as $row) {
      $id_game_in_progress = $row['id_game'];
      $id_user = $row['id_user'];
      $id_save = $row['id_save'];
      $date_sauvegarde = $row['date_derniere_save'];
      $id_last_paragraphe = $row['id_paragraphe'];
    ?>
 	
 		<tr>
 			<td>Sauvegarde N°<?php echo $id_game_in_progress;?> du <?php echo $date_sauvegarde; ?></td>
 			<td><?php echo $id_user;?></td>
 			<td><?php //echo $id_save;?></td>
 			<td><a href="story.php?id_paragraphe_out=<?php echo $id_last_paragraphe; ?>">Continuer au paragraphe n°<?php echo $id_last_paragraphe; ?></a></td>
 		</tr>
    <?php 
  }
?>

 	</table>
<!-- SELECT save.id_save,save.id_game_in_progress,save.id_paragraphe,game_in_progress.id_game_in_progress,game_in_progress.id_user,game_in_progress.id_save,save.date_save
FROM save,game_in_progress
WHERE game_in_progress.id_user=1 AND game_in_progress.id_game_in_progress = save.id_game_in_progress
GROUP BY game_in_progress.id_game_in_progress
ORDER BY game_in_progress.id_game_in_progress; -->




</div>


<?php include('inc/footer.php');?>