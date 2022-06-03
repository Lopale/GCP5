<?php
    session_start();
   if(!$_SESSION["login"]){
        header("location:login.php");
        exit();
    }

if($debug){
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  if($debug_phpinfo){
    phpinfo();
  }
}

require_once('inc/config.php');
include("inc/connect.php");
include('inc/header.php');


if(isset($_POST["valider"])){
    $pass      = $_POST["pass"];
    $arrayError = array();

    echo $pass;

    if(empty($pass)){
        array_push($arrayError,"Mot de passe laissé vide !");
    } 

    if(empty($arrayError)){
        $sel=$db->prepare("select login,id_user from user where login=? and pass=? limit 1");
        $sel->execute(array($_SESSION["login"],hash('sha256',$pass)));
        $tab=$sel->fetchAll();
        if(count($tab)>0){
             $sth = $db->prepare('DELETE FROM user WHERE login = :login AND id_user= :id_user ;');
              if (
              $sth->execute(array(':login' => $_SESSION["login"], 'id_user' => $_SESSION["id_user"]))){
                  //array_push($arrayError,"l'effacement a fonctionné !"); 
                  header("location:deconnexion.php?supprMDP=true");
              }else{
                  array_push($arrayError,"l'effacement n'a pas fonctionné !"); 
              }
        }
        else{
            array_push($arrayError,"Mauvais mot de passe!");
        }


    }





}


?>

<h1>Suppression de compte</h1>

<p>Vous allez supprimer votre compte. Pour confirmer merci de saisir à nouveau votre mot de passe</p>


<div class="erreur">
    <?php 
    if(!empty($arrayError)){

        foreach($arrayError as $element){
           echo $element . '<br />'; // affichera $arrayError[0], $arrayError[1] etc.
        }
    }
    ?>
</div>

      <form name="fo" method="post" action="">
         <input type="password" name="pass" placeholder="Mot de passe" />
         <input type="submit" name="valider" value="Supprimer mon compte" />
      </form>




<?php include('inc/footer.php');?>