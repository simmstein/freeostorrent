<?php
require_once('includes/config.php');
$pagetitle = 'Contactez-nous !';

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
// Affichage : message envoyé !
if(isset($_GET['action'])){
	echo '<div class="alert-msg rnd8 success">Message envoyé : '.htmlentities($_GET['action'], ENT_QUOTES, "UTF-8").'</div>';
}
?>

	<h2>Nous contacter :</h2>
	<p>Merci d'utiliser le formulaire ci-dessous pour nous contacter :</p>

<form action="mailer.php" method="post">

Votre nom :<br />
<input name="name" type="text" value=""/>
<br />
<br />

Votre e-mail :<br />
<input name="from" type="text" value=""/>
<br />
<br />

Sujet :<br />
<input name="subject" type="text" value=""/>
<br />
<br />

Anti-spam : veuillez recopier le code ci-dessous<br />
<input name="verif_box" type="text"/>
<img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="verification" width="50" height="24" align="absbottom" /><br />
<br />

<?php if(isset($_GET['wrong_code'])){?>
	<br /><div class="alert-msg rnd8 error">Mauvais code !</div><br /> 
<?php ;}?>

Message :<br />

<textarea name="message"></textarea>
<p><input name="Submit" class="searchsubmit formbutton" type="submit" value="Envoyer le message"/></p>
</form>

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
