<?php
session_start();
   if(!$_SESSION["login"]){
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

include('inc/connect.php');
include('inc/header.php');


    $arrayError = array();
    $afficheParagraph = false;
?>


<h1>TITRE DU PROJET</h1>

<main>
  
  <section>Page gauche</section>
  
  <section>

<?php

if (isset($_GET['newstory'])) {
  echo "Déclencher un enregistrement";

//  Nouvelle save + nouveau game in progress

/*
$sth = $db->prepare('INSERT INTO  save ( id_story, id_game_in_progress, id_paragraphe ) VALUES (:id_story, :id_game_in_progress, :id_paragraphe) ');

      //($_GET["id_story"],$_GET["game_in_progress"],$_GET["id_paragraphe_out"] 

      if (
      $sth->execute(array(':id_story' => $_GET["id_story"], ':id_game_in_progress' => $_GET["id_game_in_progress"], ':id_paragraphe' => $_GET["id_paragraphe_out"]))){
            array_push($arrayError,"l'enregistrement a fonctionné !"); 
      }else{
          array_push($arrayError,"l'enregistrement n'a pas fonctionné !"); 


      } */




    $afficheParagraph = true;

}



 if(isset($_GET['id_paragraphe_out']) && !empty($_GET['id_paragraphe_out'])) {


  // On récupère le dernier id_paragraphe enregistré
    $requete_prepare_0 = $db->prepare(" SELECT id_story, id_game_in_progress, id_paragraphe, date_save FROM save WHERE id_game_in_progress=:id_game_in_progress AND id_story = :id_story ORDER BY date_save DESC LIMIT 1 ");

    $requete_prepare_0->execute(array( ':id_game_in_progress' => $_GET["id_game_in_progress"], ':id_story' => $_GET["id_story"]));

    $lignes=$requete_prepare_0->fetch(PDO::FETCH_OBJ);
    $id_paragraphe = $lignes->id_paragraphe;




  if(isset($_GET['continue'])){
    echo "vous reprenez votre aventure :<br/>";

    // Vérifier qu'il s'agit bien du paragraphe où l'on c'est arrêté*
    
    // echo $id_paragraphe;

    // Si le dernier paragraphe est le même que celui qu'on a reçut
    if($id_paragraphe == $_GET["id_paragraphe_out"] ){
        echo "Le paragraphe est bien là où on en était arrêté<br/>";
        $afficheParagraph = true;
    }
    else
    {
      echo "Votre paragraphe ne correspond pas à votre avancé dans l'aventure";
      $afficheParagraph = false;
    }



  }else{
      // Si ce n'est pas un continue c'est que l'on est dans l'histoire

      // Récupérer le précédent paragraphe pour voir si le chemin qui a été pris est bien prévu

    $requete_prepare_2 = $db->prepare(" SELECT id_paragraphe_come, id_paragraphe_out FROM choice WHERE id_paragraphe_out=:id_paragraphe_out ");

    $requete_prepare_2->execute(array( ':id_paragraphe_out' => $_GET["id_paragraphe_out"]));

    $lignes2=$requete_prepare_2->fetch(PDO::FETCH_OBJ);
    $id_paragraphe_come = $lignes2->id_paragraphe_come;

    if(  $id_paragraphe_come == $id_paragraphe){
      echo "Vous venez par le bon chemin";
      // Si pas de souci de chemin on enregistre le nouveau paragraphe
      echo "Lancer enregistrement";

      $afficheParagraph = true;

      $sth = $db->prepare('INSERT INTO  save ( id_story, id_game_in_progress, id_paragraphe ) VALUES (:id_story, :id_game_in_progress, :id_paragraphe) ');

      //($_GET["id_story"],$_GET["game_in_progress"],$_GET["id_paragraphe_out"] 

      if (
      $sth->execute(array(':id_story' => $_GET["id_story"], ':id_game_in_progress' => $_GET["id_game_in_progress"], ':id_paragraphe' => $_GET["id_paragraphe_out"]))){
            array_push($arrayError,"l'enregistrement a fonctionné !"); 
      }else{
          array_push($arrayError,"l'enregistrement n'a pas fonctionné !"); 


      }


    }else{

      array_push($arrayError, "Le chemin que vous avez pris n'est pas prévu, essayez vous de tricher ?"); 

      $afficheParagraph = false;
      //echo "Vous venez de :".$id_paragraphe." au lieu de ".$id_paragraphe_come;
    }
      

      

  }

  


 
  /*
  Vérifier si : 
    - le paragragraphe d'entrée et bien le bon par rapport au choix
    - que l'id de l'histoire est le bon par rapport à la sauvegarde
  Si oui, 
    - Vérfier que l'enregistrement n'existe pas encore
  SI non tout enregistrer

  */
   $id_paragraphe_en_cours = $_GET["id_paragraphe_out"];
   
  }else{
    $id_paragraphe_en_cours = 1;
  }

  // Paragraphe
  $requete_prepare_1=$db->prepare("SELECT * FROM paragraph WHERE id_paragraph = :id"); // on prépare notre requête
  $requete_prepare_1->execute(array( 'id' => $id_paragraphe_en_cours ));
  $lignes=$requete_prepare_1->fetch(PDO::FETCH_OBJ);
  $title = $lignes->title;
  $content = $lignes->content;
?>


<div class="erreur">
    <?php 
    if(!empty($arrayError)){

        foreach($arrayError as $element){
           echo $element . '<br />'; // affichera $arrayError[0], $arrayError[1] etc.
        }
    }
    ?>
</div>


<?php

  if($afficheParagraph) {

?>

    <h2><?php echo $title; ?></h2>
    <div><?php echo $content; ?></div>

    <div>
<?php

  // Choix
  $query = $db->prepare('SELECT * FROM choice WHERE id_paragraphe_come=:id_paragraphe_come ORDER BY id_choice');
  $query->execute(array( 'id_paragraphe_come' => $id_paragraphe_en_cours ));
  //alternatively you could use PDOStatement::fetchAll() and get rid of the loop
  //this is dependent upon the design of your app
  foreach ($query as $row) {
      $texte_choice = $row['texte_choice'];
      $id_paragraphe_out = $row['id_paragraphe_out'];
    ?>
    <a href="adventure.php?id_paragraphe_out=<?php echo $id_paragraphe_out; ?>&id_game_in_progress=<?php echo $_GET['id_game_in_progress']; ?>&id_story=<?php echo $_GET['id_story']; ?>"><?php echo $texte_choice; ?></a>
    <?php 
  }



?>
</div>


   <?php } ?>
   

  </section>

</main>


<?php include('inc/footer.php');?>