<?php
require_once('includes/config.php');
$pagetitle = 'Recherche';
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
	if(isset($_POST['requete']) && $_POST['requete'] != NULL){
     
    		$requete = htmlspecialchars($_POST['requete']);
    		$req = $db->prepare('SELECT * FROM blog_posts_seo WHERE postTitle LIKE :requete ORDER BY postID DESC');
    		$req->execute(array('requete' => '%'.$requete.'%'));
      
    		$nb_resultats = $req->rowCount();
    		if($nb_resultats != 0) {
	?>
    <h3>Résultats de votre recherche de torrents</h3>
    <p>Nous avons trouvé <?php echo $nb_resultats;
		if($nb_resultats > 1) { echo ' résultats :'; } else { echo ' résultat :'; }
    ?>
    <br/>

    <ul>    
    <?php
    while($donnees = $req->fetch())
    {
    ?>
		<li><a href="<?php echo $donnees['postSlug']; ?>"><?php echo $donnees['postTitle']; ?><a></li>
		
    <?php
    } // fin de la boucle
    ?>
    </ul>

	<!-- <a href="recherche.php" style="text-decoration: none;"><input type="button" class="button" value="Faire une nouvelle recherche" /></a> -->

    <?php
    } // Fin d'affichage des résultats
    else
    {
    ?>
    
    <h3>Pas de résultats</h3>
    <p>Nous n'avons trouvé aucun résultat pour votre requête "<?php echo htmlspecialchars($_POST['requete']); ?>".
    <!-- <a href="recherche.php" style="text-decoration: none;"><input type="button" class="button" value="Faire une recherche avec un autre mot-clé" /></a> -->
    </p>
    
    <?php
    }// fin de l'affichage des erreurs
    $req->closeCursor(); // on ferme mysql
    }
	else
	{ // formulaire html
	?>
	
	<p>Vous allez faire une recherche sur notre site concernant les noms des torrents. Tapez une requête pour réaliser une recherche.</p>
		<form action="recherche.php" method="Post">
			<input type="text" name="requete" size="40" class="s">
			<input type="submit" class="searchsubmit formbutton" value="Recherche">
		</form>

<?php
}
// fin
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
