<?php
//include config
require_once('../includes/config.php');

//log user out
$user->logout();

//on supprime le user de la base SQL connectes
$stmt = $db->prepare('DELETE FROM connectes WHERE pseudo = :pseudo') ;
$stmt->execute(array(
    ':pseudo' => $_SESSION['username']
));


header('Location: login.php'); 

?>
