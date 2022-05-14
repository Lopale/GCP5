<?php

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

if (isset($_GET['newstory'])) {
  echo "Déclencher un enregistrement";
}



  if (isset($_GET['id_paragraphe_out']) && !empty($_GET['id_paragraphe_out'])) {
  
   $id_paragraphe_en_cours = $_GET['id_paragraphe_out'];
   
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
    <a href="story.php?id_paragraphe_out=<?php echo $id_paragraphe_out; ?>"><?php echo $texte_choice; ?></a>
    <?php 
  }



?>
</div>


   
   

  </section>

</main>


<?php include('inc/footer.php');?>