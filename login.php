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

   $login=$_POST["login"];
   $pass=hash('sha256',$_POST["pass"]);
   $valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){
      $sel=$db->prepare("select login,id_user from user where login=? and pass=? limit 1");
      $sel->execute(array($login,$pass));
      $tab=$sel->fetchAll();
      if(count($tab)>0){
         $_SESSION["login"]=($tab[0]["login"]);
         $_SESSION["id_user"]=($tab[0]["id_user"]);
         header("location:compte.php");
      }
      else
         $erreur="Mauvais login ou mot de passe!";
   }

}


  

?>



<?php include('inc/header.php');?>

Création de compte


<body onLoad="document.fo.login.focus()">
      <h1>Authentification [ <a href="inscription.php">Créer un compte</a> ]</h1>
      <div class="erreur"><?php echo $erreur ?></div>
      <form name="fo" method="post" action="">
         <input type="text" name="login" placeholder="Login" /><br />
         <input type="password" name="pass" placeholder="Mot de passe" /><br />
         <input type="submit" name="valider" value="S'authentifier" />
      </form>
   </body>



<?php include('inc/footer.php');?>