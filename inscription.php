<?php
   session_start();
   


require_once('inc/config.php');
include('inc/connect.php');

if($debug){
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  if($debug_phpinfo){
    phpinfo();
  }
}

   $login=$_POST["login"];
   $pass=$_POST["pass"];
   $repass=$_POST["repass"];
   $mail=$_POST["mail"];
   $valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){

      if(empty($mail)) $erreur="Email laissé vide!";
     
      elseif(empty($login)) $erreur="Login laissé vide!";
     
      elseif(empty($pass)) $erreur="Mot de passe laissé vide!";
     
      elseif($pass!=$repass) $erreur="Mots de passe non identiques!";
     
      else{
         include("inc/connect.php");
         $sel=$db->prepare("select id_user from user where login=? limit 1");
         $sel->execute(array($login));
         $tab=$sel->fetchAll();
         if(count($tab)>0)
            $erreur="Login existe déjà!";
         else{

          // Cryptage : https://www.php.net/manual/fr/function.hash.php

            $sth = $db->prepare('INSERT INTO user(mail,login,pass) VALUES (:mail,:login,:pass)');
            if (
            $sth->execute(array(':mail' => $mail, ':login' => $login, ':pass' => hash('sha256',$pass)))){
              header("location:login.php");
            }else{
              echo "l'enregistrement n'a pas fonctionné";
            }
         }   
      } 
   }
?>

<?php

// https://ns350014.ip-188-165-209.eu:8443/phpMyAdmin/index.php?db=admin_GC_BDD



if($debug){
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  if($debug_phpinfo){
    phpinfo();
  }
}

//Connexion à la BDD

  

?>



<?php include('inc/header.php');?>

Création de compte


<div class="erreur"><?php echo $erreur ?></div>
<form name="fo" method="post" action="">
   <input type="text" name="login" placeholder="Login" value="<?php echo $login?>" required /><br />
   <input type="email" name="mail" placeholder="Mail" value="<?php echo $mail?>" required /><br />
   <input type="password" name="pass" placeholder="Mot de passe" required /><br />
   <input type="password" name="repass" placeholder="Confirmer Mot de passe" required /><br />
   <input type="submit" name="valider" value="S'inscrire" />
</form>



<?php include('inc/footer.php');?>