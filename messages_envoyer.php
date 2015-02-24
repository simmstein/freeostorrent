<?php
require_once('includes/config.php');

if(!$user->is_logged_in()) {
        header('Location: login.php');
}

$pagetitle = 'Messagerie';

require('includes/header.php');

define("_BBC_PAGE_NAME", $pagetitle);
define("_BBCLONE_DIR", "stats/");
define("COUNTER", _BBCLONE_DIR."mark_page.php");
if (is_readable(COUNTER)) include_once(COUNTER);
?>

<body>

<div id="container">

   	<?php
	   require('includes/header-logo.php');
	   require('includes/nav.php');
	?>

	<div id="body">

		<div id="content">

	<?php
	// Fil d'ariane
	$def = "index";
	$dPath = $_SERVER['REQUEST_URI'];
	$dChunks = explode("/", $dPath);

	echo('<a class="dynNav" href="/">Accueil</a><span class="dynNav"> > </span>');
	for($i=1; $i<count($dChunks); $i++ ){
		echo('<a class="dynNav" href="/');
		for($j=1; $j<=$i; $j++ ){
			echo($dChunks[$j]);
			if($j!=count($dChunks)-1){ echo("/");}
		}		

	if($i==count($dChunks)-1){
		$prChunks = explode(".", $dChunks[$i]);
		if ($prChunks[0] == $def) $prChunks[0] = "";
		$prChunks[0] = $prChunks[0] . "</a>";
	}
	else $prChunks[0]=$dChunks[$i] . '</a><span class="dynNav"> > </span>';
	echo('">');
	echo(str_replace("_" , " " , $prChunks[0]));
	} 
?>

	<br /><br />

<?php
// on teste si le formulaire a bien été soumis
if (isset($_POST['go']) && $_POST['go'] == 'Envoyer') {
	if (empty($_POST['destinataire']) || empty($_POST['titre']) || empty($_POST['message'])) {
		$error[] = 'Au moins un des champs est vide.';
	}
	else {
		// si tout a été bien rempli, on insère le message dans notre table SQL
		$stmt = $db->prepare('INSERT INTO blog_messages (messages_id_expediteur,messages_id_destinataire,messages_date,messages_titre,messages_message) VALUES (:messages_id_expediteur,:messages_id_destinataire,:messages_date,:messages_titre,:messages_message)');
		$stmt->execute(array(
			':messages_id_expediteur' => $_SESSION['userid'],
			':messages_id_destinataire' => $_POST['destinataire'],
			':messages_date' => date("Y-m-d H:i:s") ,
			':messages_titre' => $_POST['titre'],
			':messages_message' => $_POST['message']
		));
		//$stmt->closeCursor();

		header('Location: '.SITEURL.'/admin/profil.php?membre='.$_SESSION['username'].'&message=ok');
		$stmt->closeCursor();
		exit();
	}
}
?>


<?php
$desti = $db->prepare ('SELECT username as nom_destinataire, memberID as id_destinataire FROM blog_members WHERE memberID <> :session ORDER BY username ASC');
$desti->execute(array(
	':session' => $_SESSION['userid']
));


?>

<form action="messages_envoyer.php" method="post">
Pour : <div class="select">
	  <select name="destinataire">
		<?php
		// on alimente le menu déroulant avec les login des différents membres du site
		while ($data = $desti->fetch()) {
			echo '<option value="'.$data['id_destinataire'].'">'.stripslashes(htmlentities(trim($data['nom_destinataire']))).'</option>';
		}
		?>
	  </select>
       </div>

<br /><br />

Titre :<br />
<input type="text" name="titre" size="50" value="<?php if (isset($_POST['titre'])) echo stripslashes(htmlentities(trim($_POST['titre']), ENT_QUOTES, "UTF-8")); ?>">
<br /><br />
Message : <textarea name="message"><?php if (isset($_POST['message'])) echo stripslashes(htmlentities(trim($_POST['message']), ENT_QUOTES, "UTF-8")); ?></textarea><br />
<input type="submit" name="go" value="Envoyer">
</form>

<?php
$desti->closeCursor();
?>


	</div>

		<?php require('sidebar.php'); ?>
        
		<div class="clear"></div>
	</div>
</div>

<div id="footer">
	<?php require('includes/footer.php'); ?>
</div>

</body>
</html>
