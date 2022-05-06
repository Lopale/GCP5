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

Résumé du site (histoire) + connexion + Création de compte
Revois sur compte.php


https://www.chiny.me/exercice-authentification-via-une-base-de-donnees-en-php-8-13.php


<form action="" method="post">
  <div>
    <label for="name">Login: </label>
    <input type="text" name="name" id="name" required>
  </div>
  <div>
    <label for="email">Pass: </label>
    <input type="password" name="password" id="password" required>
  </div>
  <div>
    <input type="submit" value="Connexion">
  </div>
</form>



<?php include('inc/footer.php');?>