<?php
require('includes/config.php');

// titre de la page
$pagetitle= 'A propos ...';
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

<p style="text-align: justify;">
<h4>Mentions légales</h4>
<?php echo SITENAMELONG; ?><br />
Olivier Prieur "mumbly58"<br />
16 rue de Chaudron 58200 Cosne Cours sur Loire<br />
Mail : mumbly_58 AT yahoo.fr<br /><br />

Hébergement :<br />
OVH - Kimsufi - Serveur dédié 100 Mb/s<br /><br />

<h4>A propos et Présentation :</h4>
<?php echo SITENAMELONG; ?> est un projet visant la création d'un front-end php "from-scratch" à <a href="http://xbtt.sourceforge.net/tracker/">XBT Tracker de Olaf Van Der Spek</a>.
<br />
<?php echo SITENAMELONG; ?> est issu de la famille de <a href="http://www.freetorrent.fr">freetorrent.fr</a>.
<br />
<h4>Un peu d'aide ?</h4>
Si vous vous sentez l'âme d'un codeur php, et si vous deviez décider de consacrer un peu de temps à <?php echo SITENAME; ?>, cela serait avec joie. Le code a certainement besoin d'être afiné, "nettoyé", sécurisé. N'hésitez pas à m'envoyer un petit mot par l'intermédiaire du <a href="contact.php">formulaire de contact</a>.


<h4>Conditions d'Utilisation :</h4>
<?php echo SITENAMELONG; ?> propose des médias (images de systèmes d'exploitation : OS) sous licences libres EXCLUSIVEMENT.<br />
Tout autre matériel sous une quelconque licence restrictive, commerciale ou propriétaire n'est pas admis sur <?php echo SITENAMELONG; ?>.<br />
Tout média "cracké" ou "piraté" (warez, etc.) est strictement interdit sur <?php echo SITENAMELONG; ?> et sera irrémédiablement et immédiatement effacé.<br />
Le compte de l'utilisateur responsable de l'upload de torrents interdits sera immédiatement détruit et son adresse IP transmise aux ayant-droits.<br />
En tant qu'utilisateur ou membre inscrit, la "personne" accepte les conditions générales d'utilisation.<br />

<h4>Download / Upload (Proposer des fichiers)</h4>
Pour uploader (proposer) des torrents ou downloader (télécharger), le visiteur devra devenir membre en créant un compte.<br />
<?php echo SITENAMELONG; ?> se réserve le droit de supprimer ou de modifier tout fichier envoyé et mis en partage sur son serveur et ne pourra être tenu responsable des écrits, prises de positions, convictions ou partis-pris exposés ou suggérés dans les fichiers proposés au téléchargement.
<br />
<?php echo SITENAMELONG; ?> n'est ainsi pas responsable des fichiers proposés par ses membres.
<br />
<?php echo SITENAMELONG; ?> s'engage néanmoins à faire tout ce qui est en son pouvoir pour lutter contre la diffusion de fichiers illégaux et/ou immoraux. Dans les cas les plus graves d'atteinte à la personne humaine notamment, <?php echo SITENAMELONG; ?> jouera pleinement son rôle citoyen et responsable en avertissant les autorités compétentes.
<br />
En tant qu'utilisateur et/ou membre de <?php echo SITENAMELONG; ?>, vous vous engagez à respecter la loi en général, et la loi sur les droits d'auteur en particulier.
<br />
Vous pourrez, à tout moment, avertir le webmaster de <?php echo SITENAMELONG; ?> de la présence de fichiers suspects ou illégaux sur le site en faisant un simple signalement par l'intermédiaire de la page de <a href="<?php echo SITEURL; ?>/contact.php">contact</a>.
<br />Ainsi, <?php echo SITENAMELONG; ?> n'incite pas à la délation péjorative mais souhaite de manière communautaire participer à la promotion du "Libre" et se protéger au niveau de la loi.<br />
<br />

<h4>Informatique et libertés</h4>
Informations personnelles collectées<br />
En France, les données personnelles sont notamment protégées par la loi n 78-87 du 6 janvier 1978, la loi n 2004-801 du 6 août 2004, l'article L. 226-13 du Code pénal et la Directive Européenne du 24 octobre 1995.<br />
En tout état de cause, <?php echo SITENAMELONG; ?> ne collecte des informations personnelles relatives à l'utilisateur (nom, adresse électronique, coordonnées ....) que pour le besoin des services proposés par le site web de <?php echo SITENAMELONG; ?>, notamment pour l'inscription à des événements par le biais de formulaires en ligne. L'utilisateur fournit ces informations en toute connaissance de cause, notamment lorsqu'il procède par lui-même à leur saisie. Il est alors précisé à l'utilisateur le caractère obligatoire ou non des informations qu'il serait amené à fournir.<br />
Aucune information personnelle de l'utilisateur du site de <?php echo SITENAMELONG; ?> n'est collectée à l'insu de l'utilisateur, publiée à l'insu de l'utilisateur, échangée, transférée, cédée ou vendue sur un support quelconque à des tiers.
<br />

<h4>Rectification des informations nominatives collectées</h4>
Conformément aux dispositions de l'article 34 de la loi n 48-87 du 6 janvier 1978, l'utilisateur dispose d'un droit de modification des données nominatives collectées le concernant.<br />
Pour ce faire, l'utilisateur envoie à <?php echo SITENAMELONG; ?> un courrier électronique en utilisant le formulaire de contact en indiquant son nom ou sa raison sociale, ses coordonnées physiques et/ou électroniques, ainsi que le cas échéant la référence dont il disposerait en tant qu'utilisateur du site de <?php echo SITENAMELONG; ?>. La modification interviendra dans des délais raisonnables à compter de la réception de la demande de l'utilisateur.
<br />

<h4>Limitation de responsabilité</h4>
<?php echo SITENAMELONG; ?> peut comporter des informations mises à disposition par des sociétés externes ou des liens hypertextes vers d'autres sites qui n'ont pas été développés par <?php echo SITENAMELONG; ?>. Le contenu mis à disposition sur le site est fourni à titre informatif. L'existence d'un lien de ce site vers un autre site ne constitue pas une validation de ce site ou de son contenu. Il appartient à l'internaute d'utiliser ces informations avec discernement et esprit critique. La responsabilité de <?php echo SITENAMELONG; ?> ne saurait être engagée du fait des informations, opinions et recommandations formulées par des tiers. 
</p>

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
