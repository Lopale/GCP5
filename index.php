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

Résumé du site (histoire) <br/><br/><br/>

<?php
if(isset($_GET["SuppCompteValid"])){
    echo "<div class='valid'>Votre compte a été supprimé !</div>";
}
?>



<p>Prèt à vivre ton aventure ?</p>
<p><a href="login.php">A l'aventure compagnon </a></p>


<?php include('inc/footer.php');?>