<?php
session_start();
   if($_SESSION["autoriser"]!="oui"){
      header("location:login.php");
      exit();
   }


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


<h1>TITRE DU PROJET</h1>

<main>
  
  <section>Page gauche</section>
  
  <section>

<?php

  $id_user = $_GET['id_user'];
  $id_story = $_GET['id_story'];
  $id_game_in_progress = $_GET['id_game_in_progress'];

  if($id_user != $_SESSION["id_user"] ){
    echo "vous n'avez pas le droit d'accèder à cette page !";
    die();
  }
?>

    <div>
<?php

  $query = $db->prepare('
    SELECT *
    FROM save, game_in_progress, paragraph, story
    WHERE save.id_game_in_progress = game_in_progress.id_game_in_progress
    AND paragraph.id_paragraph = save.id_paragraphe
    AND story.id_story = :id_story
    AND game_in_progress.id_user = :id_user
    AND game_in_progress.id_game_in_progress = :id_game_in_progress
    ORDER BY save.id_save ASC
    ');
  $query->execute(array( 'id_story' => $id_story, 'id_user' => $id_user, 'id_game_in_progress' => $id_game_in_progress  ));
 
  foreach ($query as $row) {
      echo '<h2>'.$row['title'].'</h2>';
      echo '<p>'.$row['content'].'</p>';
    }
?>



</div>


   
   

  </section>

</main>


<?php include('inc/footer.php');?>