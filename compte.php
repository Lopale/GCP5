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

if(isset($_GET["MdpValid"])){
    echo "<div class='valid'>Votre nouveau mot de passe est enregistré !</div>";
}

if(isset($_GET["LoginValid"])){
    echo "<div class='valid'>Votre nouveau Login est enregistré !</div>";
}

if(isset($_GET["supprSave"])){
    echo "<div class='valid'>Votre sauvegarde a été supprimé !</div>";
}


?>

<?php include('inc/connect.php');?>


<?php include('inc/header.php');?>

Option : changer login / pwd<br/>


<h2><?php echo $bienvenue?></h2>

[ <a href="deconnexion.php">Se déconnecter</a> ]
<p>Compte N°<?php echo $_SESSION["id_user"]; ?></p>


<div>
    <h3>Option</h3>
    <a href="change-login.php">Modifier Mon login</a><br/>
    <a href="change-mdp.php">Modifier mon mot de passe</a><br/>
    <a href="javascript:if(confirm('&Ecirc;tes-vous sûr de vouloir supprimer votre compte (irréversible) ?')) document.location.href='suppr-compte.php'">Supprimer mon compte</a>
</div>

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
        <a href="adventure.php?id_paragraphe_out=<?php echo $id_last_paragraphe; ?>&id_game_in_progress=<?php echo $id_game_in_progress;?>&id_story=<?php echo $id_story;?>&continue=true">Continuer au paragraphe n°<?php echo $id_last_paragraphe; ?></a>
        <!-- <a href="adventure.php?">Continuer l'aventure </a> -->
      </td>
      <td><a href="story.php?id_game_in_progress=<?php echo $id_game_in_progress;?>&id_story=<?php echo $id_story;?>"> Lire l'histoire</a></td>
      <td>
    <a href="javascript:if(confirm('&Ecirc;tes-vous sûr de vouloir supprimer cette Sauvegarde (irréversible) ?')) document.location.href='suppr-save.php?game_in_progress=<?php echo $id_game_in_progress; ?>'">Supprimer</a>)</td>
 		</tr>
    <?php 
  }
?>

 	</table>


</div>


<?php include('inc/footer.php');?>