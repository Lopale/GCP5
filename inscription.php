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

Création de compte


<div class="erreur"><?php echo $erreur ?></div>
<form name="fo" method="post" action="">
   <input type="text" name="login" placeholder="Login" value="<?php echo $login?>" /><br />
   <input type="email" name="email" placeholder="Mail" value="<?php echo $mail?>" /><br />
   <input type="password" name="pass" placeholder="Mot de passe" /><br />
   <input type="password" name="repass" placeholder="Confirmer Mot de passe" /><br />
   <input type="submit" name="valider" value="S'authentifier" />
</form>



<?php include('inc/footer.php');?>