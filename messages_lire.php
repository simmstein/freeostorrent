<?php
require_once('includes/config.php');

if(!$user->is_logged_in()) {
        header('Location: admin/login.php');
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

	<br />

	<?php
	// on teste si notre paramètre existe bien et qu'il n'est pas vide
	if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
		$error[] = 'Aucun message reconnu.';
	}
	else {
		// on prépare une requete SQL selectionnant la date, le titre et l'expediteur du message que l'on souhaite lire, tout en prenant soin de vérifier que le message appartient bien au membre connecté
		$stmtmess = $db->prepare('SELECT blog_messages.messages_titre, blog_messages.messages_date, blog_messages.messages_message, blog_members.memberID as memberid, blog_members.username as expediteur FROM blog_messages, blog_members WHERE blog_messages.messages_id_destinataire = :userid AND blog_messages.messages_id_expediteur = blog_members.memberID AND blog_messages.messages_id = :id_message');
		$stmtmess->execute(array(
			':userid' => $_SESSION['userid'],
			':id_message' => $_GET['id_message']
		));

		$nb = $stmtmess->rowCount();

		if ($nb == 0) {
			$error[] = 'Aucun message reconnu.';
		}
		else {
			// si le message a été trouvé, on l'affiche
			$data = $stmtmess->fetch();
			echo '<br /><fieldset>';
				echo '<h3>Message de : '.htmlentities($data['expediteur'], ENT_QUOTES, "UTF-8").'</h3>';
			echo '</fieldset>';
			echo '<fieldset>';
				sscanf($data['messages_date'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
				echo 'Le : '.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde.'<br />';
			echo '</fieldset>';
			echo '<fieldset>';
				echo 'Titre : '.htmlentities($data['messages_titre'], ENT_QUOTES, "UTF-8").'<br />';
			echo '</fieldset>';
			echo '<fieldset>';
				echo 'Message : <br /><div style="text-align: justify; background-color:#EFFBF8; padding: 2px 10px 2px 10px;">'.nl2br(htmlspecialchars_decode(htmlentities(trim(strip_tags($data['messages_message'])), ENT_QUOTES, "UTF-8"))).'</div>';
			echo '</fieldset>';

			echo '<p style="text-align: right;">';
				// on affiche un lien pour répondre au message
				echo '<a style="text-decoration: none;" href="messages_repondre.php?id_message=' , htmlentities($_GET['id_message']) , '&id_destinataire=' , $data['memberid'] ,'"><input type="button" class="button" value="Répondre" /></a> ';
				// on affiche également un lien permettant de supprimer ce message de la boite de réception
				echo '<a style="text-decoration: none;" href="messages_supprimer.php?id_message=' , htmlentities($_GET['id_message']) , '"><input type="button" class="button" value="Supprimer" /></a>';
			echo '</p>';
		}
		$stmtmess->closeCursor();
	}

		// On met à jour le champ "messages_lu" de blog_messages à 1 pour signifier que le message a été lu
		$stmt = $db->prepare('UPDATE blog_messages SET messages_lu = "1" WHERE messages_id = :messages_id');
		$stmt->execute(array(
			':messages_id' => $_GET['id_message']
		));


		//S'il y a des erreurs, on les affiche
        	if(isset($error)){
                	foreach($error as $error){
                        	echo '<p class="error"><span style="font-weight: bold;">ERREUR :</span> '.$error.'</p>';
                	}
        	}
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
