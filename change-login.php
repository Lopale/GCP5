<?php
    session_start();
   if(!$_SESSION["login"]){
        header("location:login.php");
        exit();
    }

    if(date("H") < 18) $greeting = "Bonjour"; else $greeting = "Bonsoir";
    
    $bienvenue = "<span class='greeting'>".$greeting."</span> et bienvenue ".$_SESSION['login']. " dans votre espace personnel";
    
require_once('inc/config.php');

if($debug){
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  if($debug_phpinfo){
    phpinfo();
  }
}

if(isset($_POST["valider"])){
    $NewLogin     = $_POST["login"];


    $arrayError = array();

    if(empty($NewLogin)){
        array_push($arrayError,"Login laissé vide !");     
    } 



    if(empty($arrayError)){
        include("inc/connect.php");
        $sel=$db->prepare("select login from user where login=? ");
        $sel->execute(array($NewLogin));
        $tab=$sel->fetchAll();
        
        if(count($tab)>0){
            if($tab[0]['login'] == $NewLogin ){
                array_push($arrayError,"Login déjà enregistré !");  
            }
        }            
         else{
            $sth = $db->prepare('UPDATE user SET login = :login WHERE login = :OldLogin AND id_user= :id_user ;');
            if (
            $sth->execute(array(':login' => $NewLogin, ':OldLogin' => $_SESSION["login"], 'id_user' => $_SESSION["id_user"]))){
                $_SESSION["login"]=($NewLogin);
                header("location:compte.php?LoginValid");
            }else{
                array_push($arrayError,"l'enregistrement n'a pas fonctionné !"); 
            }
        }


    }


}
  

?>



<?php include('inc/header.php');?>

Option : changer login / pwd<br/>


<h2><?php echo $bienvenue?></h2>

[ <a href="deconnexion.php">Se déconnecter</a> ]
<p>Compte N°<?php echo $_SESSION["id_user"]; ?></p>



<div>
	<h2>Modifier votre Mot de passe</h2>


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
    <table>
        <tr>
            <td>Login Actuel</td>
            <td>
                <?php echo $_SESSION["login"]; ?>
            </td>
        </tr>
        <tr>
            <td>Nouveau Login</td>
            <td>
                <input type="text" name="login" placeholder="Login" value="<?php if(isset($NewLogin)){ echo $NewLogin; } ?>" required /><br />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="valider" value="Valider" />
            </td>
        </tr>
    </table>
</form>



</div>


<?php include('inc/footer.php');?>