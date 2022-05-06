<?php
try {
  $db = new PDO("mysql:host=".$bdd_host.";dbname=".$bdd_name, $bdd_user, $bdd_mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch(PDOException $pe){
    throw new Exception($pe->getMessage());
}
?> 