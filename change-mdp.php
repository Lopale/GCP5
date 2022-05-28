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
    $oldPass   = $_POST["oldPass"];
    $pass      = $_POST["pass"];
    $repass    = $_POST["repass"];

    //echo "Old : ".$oldPass." New :".$pass." 2nd New :".$repass;

    $arrayError = array();

    if(empty($oldPass)){
        array_push($arrayError,"Ancien Mot de passe laissé vide !");     
    } 

    if(empty($pass)){
        array_push($arrayError,"Mot de passe laissé vide !");   
    } 

    if($pass!=$repass){
        array_push($arrayError,"Mots de passe non identiques !");  
    } 



    if(empty($arrayError)){
        include("inc/connect.php");
        $sel=$db->prepare("select pass from user where pass=? and login=? ");
        $sel->execute(array(hash('sha256',$oldPass),$_SESSION["login"]));
        $tab=$sel->fetchAll();
        
        if(count($tab)<1){
            array_push($arrayError,"Votre ancien mot de passe ne correspond pas à votre Login !");  
        }else{
            $sth = $db->prepare('UPDATE user SET pass = :pass WHERE login = :login ;');
            if (
            $sth->execute(array(':pass' => hash('sha256',$pass), ':login' => $_SESSION["login"]))){
              header("location:compte.php?MdpValid");
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
            <td>Ancien Mot de passe</td>
            <td>
                <input type="password" name="oldPass" placeholder="Mot de passe" required />
            </td>
        </tr>
        <tr>
            <td>Nouveau Mot de passe</td>
            <td>
                <input id="mdp" type="password" name="pass" placeholder="Mot de passe" minlength="6" pattern=".[a-zA-Z0-9]{6,}" title="6 caractères alphanumériques minimum" onchange="form.mdp_confirm.pattern = (this.value == '' ? '.[a-zA-Z0-9]{6,}' : this.value);" required />
            </td>
        </tr>
        <tr>
            <td>Confirmer Nouveau Mot de passe</td>
            <td>
                <input type="password" name="repass" placeholder="Confirmer Mot de passe" minlength="6" required  pattern=".[a-zA-Z0-9]{6,}" title="6 caractères alphanumériques minimum"  required />
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