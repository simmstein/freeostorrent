<?php
require_once('includes/config.php');

if(!$user->is_logged_in()) { 
	header('Location: login.php');
}

// on teste si l'id du message a bien été fourni en argument au script messages_envoyer.php
if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
	header('Location: '.SITEURL.'/admin/profil.php?membre='.$_SESSION['username']);
	exit();
}
else {
	$stmt = $db->prepare('DELETE FROM blog_messages WHERE messages_id = :messages_id AND messages_id_destinataire = :messages_id_destinataire');
	$stmt->execute(array(
		':messages_id' => $_GET['id_message'],
		':messages_id_destinataire' => $_SESSION['userid']
	));

	header('Location: '.SITEURL.'/admin/profil.php?membre='.$_SESSION['username']);
	exit();
}
?>
