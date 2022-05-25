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

   if(isset($_POST["valider"])){
    $login     = $_POST["login"];
    $pass      = $_POST["pass"];
    $repass    = $_POST["repass"];
    $mail      = $_POST["mail"];
    //$erreur    = "";

    $arrayError = array();


        if(empty($mail)){
           // $erreur      .= "Email laissé vide !<br/>";
            array_push($arrayError,"Email laissé vide !");
        } 
     
      if(empty($login)){
           // $erreur     .= "Login laissé vide !<br/>";   
            array_push($arrayError,"Login laissé vide !");     
      } 
     
      if(empty($pass)){
       // $erreur      .= "Mot de passe laissé vide !<br/>";
        array_push($arrayError,"Mot de passe laissé vide !");   
      } 
     
      if($pass!=$repass){
        //$erreur    .= "Mots de passe non identiques !<br/>";
        array_push($arrayError,"Mots de passe non identiques !");  
      } 
     
      //if(!$erreur){
      if(empty($arrayError)){
         include("inc/connect.php");
         $sel=$db->prepare("select id_user, login, mail from user where login=? or mail=? limit 1");
         $sel->execute(array($login,$mail));
         $tab=$sel->fetchAll();
         // var_dump($tab);
         // var_dump($tab[0]);
         if(count($tab)>0){
            if($tab[0]['mail'] == $mail ){
                // $erreur  .= "Email déjà enregistré !<br/>";
                array_push($arrayError,"Email déjà enregistré !");  
            }
            if($tab[0]['login'] == $login ){
                // $erreur .= "Login déjà enregistré !<br/>";
                array_push($arrayError,"Login déjà enregistré !");  
            }
        }            
         else{

          // Cryptage : https://www.php.net/manual/fr/function.hash.php

            $sth = $db->prepare('INSERT INTO user(mail,login,pass) VALUES (:mail,:login,:pass)');
            if (
            $sth->execute(array(':mail' => $mail, ':login' => $login, ':pass' => hash('sha256',$pass)))){
              header("location:login.php");
            }else{
              echo "l'enregistrement n'a pas fonctionné";
                array_push($arrayError,"l'enregistrement n'a pas fonctionné !"); 
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

<?php //var_dump($arrayError); ?>
<div class="erreur">
    <?php 
    if(!empty($arrayError)){

        foreach($arrayError as $element){
           echo $element . '<br />'; // affichera $arrayError[0], $arrayError[1] etc.
        }
        //print_r($arrayError);
    }
    ?>

    <?php// if(isset($erreur)){ echo $erreur; } ?>
</div>



<form name="fo" method="post" action="">
   <input type="text" name="login" placeholder="Login" value="<?php if(isset($login)){ echo $login; } ?>" required /><br />
   <input type="email" name="mail" placeholder="Mail" value="<?php if(isset($mail)){ echo $mail; } ?>" required /><br />
   <input type="password" name="pass" placeholder="Mot de passe" required /><br />
   <input type="password" name="repass" placeholder="Confirmer Mot de passe" required /><br />
   <input type="submit" name="valider" value="S'inscrire" />
</form>



<?php include('inc/footer.php');?>