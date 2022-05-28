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


    $sth = $db->prepare('DELETE FROM user WHERE login = :login AND id_user= :id_user ;');
    if (
    $sth->execute(array(':login' => $_SESSION["login"], 'id_user' => $_SESSION["id_user"]))){
        header("location:index.php?SuppCompteValid");
    }else{
        array_push($arrayError,"l'effacement n'a pas fonctionné !"); 
    }






?>