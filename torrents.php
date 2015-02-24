<?php
require_once('includes/config.php');

$pagetitle = 'Liste des torrents';

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
<br />

	<div style="text-align: center;"><h1>Liste des torrents :</h1></div>
		
        <?php
        // On affiche : torrent ajouté ! 
        if(isset($_GET['action'])){
                echo '<div class="alert-msg rnd8 success">Torrent ajouté : '.htmlentities($_GET['action']).' !</div>';
        }
        ?>

        <table>
        <tr>
                <th style="width: 250px;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postTitle&ordre=desc">&#x2191;</a>Nom<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postTitle&ordre=asc">&#x2193;</a></th>
		<th style="width: 15px; text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postViews&ordre=desc">&#x2191;</a>Lu<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postViews&ordre=asc">&#x2193;</a></th>
		<th style="text-align: center;"></th>
		<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postTaille&ordre=desc">&#x2191;</a>Taille<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postTaille&ordre=asc">&#x2193;</a></th>
		<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postDate&ordre=desc">&#x2191;</a>Ajouté<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postDate&ordre=asc">&#x2193;</a></th>
		<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postAuthor&ordre=desc">&#x2191;</a>Par<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=postAuthor&ordre=asc">&#x2193;</a></th>
		<th style="text-align: center;">Catégorie</th>
		<th style="text-align: center;">Licence</th>
		<th style="width: 30px; text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=seeders&ordre=desc">&#x2191;</a>S<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=seeders&ordre=asc">&#x2193;</a></th>
		<th style="width: 30px; text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=leechers&ordre=desc">&#x2191;</a>L<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=leechers&ordre=asc">&#x2193;</a></th>
		<th style="width: 15px; text-align: center;"><a style="color: #fff; text-decoration: none;" href="torrents.php?tri=completed&ordre=desc">&#x2191;</a>T<a style="color: #fff; text-decoration: none;" href="torrents.php?tri=completed&ordre=asc">&#x2193;</a></th>
        </tr>
        <?php
                try {
			// On affiche 15 torrents par page
			$pages = new Paginator('10','p');

			$stmt = $db->query('SELECT postID FROM blog_posts_seo');
			$pages->set_total($stmt->rowCount());

			// Tri de colonnes
			$tri = 'postID';
			$ordre = 'DESC';

			if(isset($_GET['tri'])) {
				$tri = htmlentities($_GET['tri']);
			}
			else {
				$tri = 'postID';
			}

			if(isset($_GET['ordre'])) {
				$ordre = htmlentities($_GET['ordre']);
			}
			else {
				$ordre = 'DESC';
			}


                        $stmt = $db->query('SELECT * FROM blog_posts_seo b LEFT JOIN xbt_files x ON x.fid = b.postID ORDER BY '.$tri.' '.$ordre.' '.$pages->get_limit());
	                while($row = $stmt->fetch()){

			$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID ORDER BY catTitle ASC');
			$stmt2->execute(array(':postID' => $row['postID']));

			$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

			// On affiche des couleurs de row alternées
			//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';
			//$x++; 
			//$tablerow = ($x%2 == 0)? 'tableRow1': 'tableRow2';

                        //echo '<tr class="'.$tablerow.'">';
			echo '<tr>';
                        echo '<td style="font-size: 10pt; font-weight: bold;"><a style="text-decoration: none;" href="'.stripslashes(htmlspecialchars($row['postSlug'])).'">'.stripslashes(htmlspecialchars($row['postTitle'])).'</a></td>';
			echo '<td style="font-size: 8pt; text-align: center;">'.$row['postViews'].'</td>';
			echo '<td style="text-align: center;">';
			echo '<a style="text-decoration: none;" href="admin/download.php?id='.stripslashes(htmlspecialchars($row['postID'])).'"><span style="font-size: 9px;">Télécharger</span><br /><img src="images/download.png" title="Télécharger !" alt="Télécharger" /></a>';
			echo '</td>';

			echo '<td style="font-size: 8pt; text-align: center;">'.makesize($row['postTaille']).'</td>';

			sscanf($row['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
			echo '<td style="text-align: center; font-size: 8pt;">'.$jour.'-'.$mois.'-'.$annee.'</td>';
			
			echo '<td style="font-size: 8pt; text-align: center;"><a style="text-decoration: none;" href="admin/profil.php?membre='.$row['postAuthor'].'">'.$row['postAuthor'].'</a></td>';

			$links = array();
			foreach ($catRow as $cat) {
                               	$links[] = '<a style="text-decoration: none;" href="c-'.htmlspecialchars($cat['catSlug']).'">'.htmlspecialchars($cat['catTitle']).'</a>';
                        }

			echo '<td style="text-align: center; font-size: 8pt; line-height: 120%;">'.implode(" | ", $links).'</td>';

			$stmt3 = $db->prepare('SELECT * FROM blog_posts_seo,xbt_files WHERE blog_posts_seo.postID = :postID AND xbt_files.fid = blog_posts_seo.postID');
			$stmt3->execute(array(':postID' => $row['postID']));
			$xbt = $stmt3->fetch();

			$stmt4 = $db->prepare('SELECT licenceID,licenceTitle FROM blog_licences, blog_post_licences WHERE blog_licences.licenceID = blog_post_licences.licenceID_BPL AND blog_post_licences.postID_BPL = :postID_BPL ORDER BY licenceTitle ASC');
                	$stmt4->execute(array(':postID_BPL' => $row['postID']));
                	$licenceRow = $stmt4->fetchALL(PDO::FETCH_ASSOC);
                	$liclist = array();
                	foreach($licenceRow as $lic) {
                        	$liclist[] = htmlspecialchars($lic['licenceTitle']);
                	}
                	echo '<td style="text-align: center; font-size: 8pt; line-height: 120%;">'.implode("<br />", $liclist).'</span></td>';

			echo '<td style="text-align: center; color: green; font-size: 10pt;"><img src="images/up.png" alt="seeders" /> <a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'">'.$xbt['seeders'].'</a></td>';
			echo '<td style="text-align: center; color: red; font-size: 10pt;"><img src="images/down.png" alt="leechers" /> <a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'">'.$xbt['leechers'].'</a></td>';
			echo '<td style="text-align: center; font-size: 10pt;">'.$xbt['completed'].'</td>';
			echo '</tr>';
                        }


                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
        ?>
        </table>

	<br />
	<p class="edito" style="font-size: 10px; font-style: italic;"><span style="font-weight: bold;">Légende :</span> S = Nb de Seeders, L = Nb de Leechers, T = Nb de Téléchargements</p>

	<?php echo $pages->page_links('?tri='.$tri.'&ordre='.$ordre.'&'); ?>


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
