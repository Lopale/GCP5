<?php
    session_start();
   if(!$_SESSION["login"]){
        header("location:login.php");
        exit();
    }

    if(date("H") < 18) $greeting = "Bonjour"; else $greeting = "Bonsoir";
    
    $bienvenue = "<span class='greeting'>".$greeting."</span> et bienvenue ".$_SESSION['login']. " dans votre espace personnel";
    




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

Option : changer login / pwd<br/>


<h2><?php echo $bienvenue?></h2>

[ <a href="deconnexion.php">Se déconnecter</a> ]
<p>Compte N°<?php echo $_SESSION["id_user"]; ?></p>



<!-- Avant :ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION 
Modification : SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))-->


<div>
	<h2>Liste des parties</h2>

	<?php


    $queryNbSave = $db->prepare("SELECT COUNT(id_game_in_progress) FROM game_in_progress WHERE id_user =:id_user GROUP BY id_save");
    $queryNbSave->execute(array( 'id_user' => $_SESSION["id_user"] ));
    $tab=$queryNbSave->fetchAll();
    $nbSaveRestante  = $max_game - count($tab);
    ?>




<div>
    <h2>Nouvelle Partie</h2>
    <?php
        if($nbSaveRestante > 0 ){
            echo '<p>Il vous reste '.$nbSaveRestante.' sauvegardes de disponibles (sur '.$max_game.')</p>';
            echo '<a href="adventure.php?newstory=true">Voulez vous commencer une nouvelle partie ? </a>';
        }else{
            echo '<p>Vous n\'avez plus de bloc de sauvegarde disponibles, veuillez en supprimer pour pouvoir lancer une nouvelle partie</>';
        }
     ?>
    
</div>


<?php
  // Liste des sauvegarde


  // SELECT * FROM game_in_progress INNER JOIN save ON game_in_progress.id_game_in_progress = save.id_game_in_progress WHERE game_in_progress.id_user=1

  //SELECT DISTINCT(game_in_progress.id_game_in_progress) AS id_game, game_in_progress.id_user, save.id_save, max(save.date_save) AS date_derniere_save FROM game_in_progress INNER JOIN save ON save.id_game_in_progress = game_in_progress.id_game_in_progress GROUP BY game_in_progress.id_game_in_progress;


//$query = $db->prepare('SELECT DISTINCT(game_in_progress.id_game_in_progress) AS id_game, save.id_paragraphe, game_in_progress.id_user, game_in_progress.id_save, max(save.date_save) AS date_derniere_save FROM game_in_progress INNER JOIN save ON save.id_game_in_progress = game_in_progress.id_game_in_progress WHERE game_in_progress.id_user=:id_user GROUP BY game_in_progress.id_game_in_progress');


/*
$query = $db->prepare("SELECT
    id_game_in_progress,
    id_paragraphe,
    date_save
FROM
    save where id_save in (
        SELECT
            max(s.id_save) as 'MAX_ID_SAVE'
        FROM 
            game_in_progress gip
            INNER JOIN save s on s.id_game_in_progress = gip.id_game_in_progress
            WHERE gip.id_user=:id_user
            group by s.id_game_in_progress
    )"
  );
*/
$query = $db->prepare("SELECT
    id_game_in_progress,
    id_paragraphe,
    date_save,
    story.id_story,
    story.name
FROM
story,
    save where id_save in (
        SELECT
            max(s.id_save) as 'MAX_ID_SAVE'
        FROM 
            game_in_progress gip
            INNER JOIN save s on s.id_game_in_progress = gip.id_game_in_progress
            WHERE gip.id_user=:id_user
      AND story.id_story = save.id_story
            group by s.id_game_in_progress
    )"
  );


  $query->execute(array( 'id_user' => $_SESSION["id_user"] ));

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
      $id_game_in_progress = $row['id_game_in_progress'];
      // $_SESSION["id_user"] = $row['id_user'];
      // $id_save = $row['id_save'];
      $date_sauvegarde = $row['date_save'];
      $id_last_paragraphe = $row['id_paragraphe'];
      $story_name = $row['name'];
      $id_story = $row['id_story'];
    ?>
 	
 		<tr>
 			<td>Sauvegarde N°<?php echo $id_game_in_progress;?> du <?php echo $date_sauvegarde; ?> de l'histoire <?php echo $story_name; ?></td>
 			<td><?php echo $_SESSION["id_user"];?></td>
 			<td><?php //echo $id_save;?></td>
 			<td>
        <!-- <a href="story.php?id_paragraphe_out=<?php echo $id_last_paragraphe; ?>">Continuer au paragraphe n°<?php echo $id_last_paragraphe; ?></a> -->
        <a href="adventure.php">Continuer l'aventure </a>
      </td>
      <td><a href="story.php?id_game_in_progress=<?php echo $id_game_in_progress;?>&id_story=<?php echo $id_story;?>"> Lire l'histoire</a></td>
      <td>Supprimer (mettre validation)</td>
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