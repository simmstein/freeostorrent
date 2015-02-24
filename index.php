<?php
require('includes/config.php');

$pagetitle= 'Bienvenue !';

require('includes/header.php');

// BBClone
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
			// Breadcrumbs
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

		<div class="edito">
			<?php echo $EDITO; ?>
		</div>

		<br />

		<div><h2>Les derniers torrents</h2></div>

	<?php
        try {
            $pages = new Paginator('7','p');

            $stmt = $db->query('SELECT postID FROM blog_posts_seo');

            //pass number of records to
            $pages->set_total($stmt->rowCount());

            $stmt = $db->query('SELECT postID,postTitle,postAuthor,postSlug,postDesc,postDate,postImage,postViews FROM blog_posts_seo ORDER BY postID DESC '.$pages->get_limit());
            while($row = $stmt->fetch()){

		 echo '<fieldset>';
		 echo '<div style="margin-top: 10px;">';
		 
		echo '<span style="font-size: 17px; font-weight: bold; padding: 5px 0 0 10px;"><a style="text-decoration: none; color: black;" href="'.stripslashes(htmlspecialchars($row['postSlug'])).'">'.stripslashes(htmlspecialchars($row['postTitle'])).'</a></span><br />';
                 sscanf($row['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
                 
		echo '<span style="font-size: 7pt; padding: 5px 0 0 10px;">';

		echo '<img src="images/date.png" style="vertical-align: bottom; margin-top: 5px;" alt="Date" /> '.$jour.'-'.$mois.'-'.$annee.' <img src="images/meta-separator.png" style="margin-left: 4px; margin-right: 4px;" alt="" /> <img src="images/author.png" style="vertical-align: bottom;" alt="Auteur" />';
               	echo '<a style="text-decoration: none;" href="'.SITEURL.'/admin/profil.php?membre='.stripslashes(htmlspecialchars($row['postAuthor'])).'">'.stripslashes(htmlspecialchars($row['postAuthor'])).'</a>';

               	$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
               	$stmt2->execute(array(':postID' => $row['postID']));
               	$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

               	$links = array();
               	foreach ($catRow as $cat) {
                	$links[] = "<a style=\"text-decoration: none;\" href='c-".htmlspecialchars($cat['catSlug'])."'>".htmlspecialchars($cat['catTitle'])."</a>";
                }
		echo '&nbsp;<img src="images/meta-separator.png" style="margin-left: 4px; margin-right: 4px;" alt="" /> ';
                echo '<img src="images/category.png" style="vertical-align: bottom;" alt="Catégories" /> '.implode(", ", $links);

		echo '<img src="images/meta-separator.png" style="margin-left: 4px; margin-right: 4px;" alt="" /> <img src="images/comments.png" style="vertical-align: bottom;" alt="Commentaires" /> <a style="text-decoration: none;" href="'.SITEURL.'/'.stripslashes(htmlspecialchars($row['postSlug'])).'#disqus_thread">#</a>';

		echo ' <img src="images/meta-separator.png" style="margin-left: 4px; margin-right: 4px;" alt="" /> <img src="images/licence.png" style="vertical-align: bottom;" alt="Licence" />'; 

		$stmt4 = $db->prepare('SELECT licenceID,licenceTitle FROM blog_licences, blog_post_licences WHERE blog_licences.licenceID = blog_post_licences.licenceID_BPL AND blog_post_licences.postID_BPL = :postID_BPL ORDER BY licenceTitle ASC');
		$stmt4->execute(array(':postID_BPL' => $row['postID']));
		$licenceRow = $stmt4->fetchALL(PDO::FETCH_ASSOC);
		$liclist = array();
                foreach($licenceRow as $lic) {
                	$liclist[] = htmlspecialchars($lic['licenceTitle']);
                }
                echo implode(" - ", $liclist);

		echo ' <img src="images/meta-separator.png" style="margin-left: 4px; margin-right: 4px;" alt="" /> <span style="font-weight: bold;">Lu : </span> '.$row['postViews'].' fois';
		
		echo '</span><br />';

		echo '</div>';

		if (!empty($row['postImage']) && file_exists($REP_IMAGES_TORRENTS.$row['postImage'])) {
			echo '<img src="images/imgtorrents/'.stripslashes(htmlspecialchars($row['postImage'])).'" alt="'.stripslashes(htmlspecialchars($row['postTitle'])).'" style="float: left; margin-right: 10px; margin-top: 20px; border: 1px solid #C0C0C0; padding: 6px; max-width: 100px; max-height: 100px;" />';
		}
		else {
			echo '<img src="images/noimage.png" alt="Image" style="float: left; margin-right: 10px; margin-top: 20px; max-width: 150px; max-height: 150px;" />';
		}

		$max = 540;
		$chaine = $row['postDesc'];
		if (strlen($chaine) >= $max) {
	     		$chaine = substr($chaine, 0, $max);
			$espace = strrpos($chaine, " ");
		$chaine = substr($chaine, 0, $espace).' ...';
		}

                 echo '<p style="text-align: justify;">'.$chaine.'<a style="text-decoration: none;" href="'.stripslashes(htmlspecialchars($row['postSlug'])).'"> <input type="button" class="button" value="Lire la suite ..." /></a></p>';
		 echo '</fieldset>';
             }


        } catch(PDOException $e) {
                 echo $e->getMessage();
          }
        ?>


	<?php
	echo '<div style="text-align: center;">';
		echo $pages->page_links();
	echo '</div>';
	?>

        </div>
        
	<?php require('sidebar.php'); ?>
        
    	<div class="clear"></div>
    </div>
</div>

<div id="footer">
	<?php require('includes/footer.php'); ?>
</div>

 <script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'freeostorrentfr'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
    </script>

</body>
</html>
