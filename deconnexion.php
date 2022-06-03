<?php
   session_start();
   session_destroy();

   if(isset($_GET["supprMDP"])){
      header("location:index.php?SuppCompteValid");
   }else{
      header("location:login.php");
   }
?>