<?php

require_once('includes/config.php');

$name = $_REQUEST["name"];
$subject = $_REQUEST["subject"];
$message = strip_tags($_REQUEST["message"]);
$from = $_REQUEST["from"];
$verif_box = $_REQUEST["verif_box"];

$name = stripslashes($name); 
$message = stripslashes(strip_tags($message)); 
$subject = stripslashes($subject); 
$from = stripslashes($from); 


if(md5($verif_box).'a4xn' == $_COOKIE['tntcon']){
	$message = "Nom: ".$name."\n".$message;
	$message = "De: ".$from."\n".$message;
	mail(SITEMAIL, 'Message: '.$subject, $_SERVER['REMOTE_ADDR']."\n\n".$message, "From: $from");
        header("Location: contact.php?action=ok");
	setcookie('tntcon','');
} else {
	header("Location:".$_SERVER['HTTP_REFERER']."?subject=$subject&from=$from&message=$message&wrong_code=true");
        //header("Location: contact.php");
}
?>

