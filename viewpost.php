<?php
require('includes/config.php');

$stmt = $db->prepare('SELECT postID,postTitle,postSlug,postAuthor,postDesc,postCont,postTaille,postDate,postTorrent,postImage FROM blog_posts_seo WHERE postSlug = :postSlug');
$stmt->execute(array(':postSlug' => $_GET['id']));
$row = $stmt->fetch();

//Si le post n'existe pas on redirige l'utilisateur
if($row['postID'] == ''){
        header('Location: ./');
        exit;
}


$pagetitle = $row['postTitle'];
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
				<div class="post">
	
		                        <?php
                                	//echo '<div style="margin: 5px 5px 0 5px; background-image:url(images/footer-bg.png);">';
					echo '<div class="title"';
                                        	echo '<span style="font-size: 14pt; font-weight: bold;">'.$row['postTitle'].'</span>';

						if(isset($_SESSION['username'])) {
							if($row['postAuthor'] == $_SESSION['username'] || $_SESSION['username'] == 'mumbly') {
                                        			echo '<a style="text-decoration: none; padding-left: 100px;" href="admin/edit-post.php?id='.stripslashes(htmlspecialchars($row['postID'])).'"><input type="button" class="button" value="Editer" /></a>';                           
                                        		}
						}

                                        	sscanf($row['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
					
						echo '<br /><span style="font-size: 9px;">';
							echo 'Posté le '.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde.', par ';
                                        		echo '<a href="'.SITEURL.'/admin/profil.php?membre='.stripslashes(htmlspecialchars($row['postAuthor'])).'">'.stripslashes(htmlspecialchars($row['postAuthor'])).'</a>, dans ';

                                        		$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID ORDER BY catTitle ASC');
                                        		$stmt2->execute(array(':postID' => $row['postID']));
                                        		$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                                        		$links = array();
                                        		foreach ($catRow as $cat) {
                                        			$links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                                        		}
                                        		echo implode(" - ", $links);
						echo '</span>';

						echo '<div style="background-color: #FFFFCC; font-size: 10px;">';
						echo '<span style="font-weight: bold;">Télécharger :</span> <a href="admin/download.php?id='.stripslashes(htmlspecialchars($row['postID'])).'"><img src="images/download.png" alt="" /></a> | ';
							echo '<span style="font-weight: bold;">Taille :</span> '.makesize($row['postTaille']).' | ';

						   	$filetorrent = $REP_TORRENTS.$row['postTorrent'];
						   	
							//On décode le fichier torrent...	
							//$fd = fopen($_FILES["torrent"]["tmp_name"], "rb");
							//$length=filesize($_FILES["torrent"]["tmp_name"]);
							$fd = fopen($filetorrent, "rb");
							$length = filesize($filetorrent);			
	
							if ($length) {
								$alltorrent = fread($fd, $length);
							}
							$array = BDecode($alltorrent);
							$hash = sha1(BEncode($array["info"]));
							fclose($fd);

							if (isset($array["info"]) && $array["info"]) {
								$upfile=$array["info"];
							}
							else {
								$upfile = 0;
							}

							if (isset($upfile["length"])) {
								$size = (float)($upfile["length"]);
							}
							else if (isset($upfile["files"])) {
								//Pour les torrents multifichiers (Lupin - Xbtit - Btiteam - 2005)
								$size=0;
								foreach ($upfile["files"] as $file) {
									$size+=(float)($file["length"]);
                						}
							}
							else {
								$size = "0";
							}

							$ffile=fopen($filetorrent,"rb");
							$content=fread($ffile,filesize($filetorrent));
							fclose($ffile);

							$content=BDecode($content);
							$numfiles=0;

							if (isset($content["info"]) && $content["info"]) {
								$thefile=$content["info"];
								if (isset($thefile["length"])) {
									$dfiles[$numfiles]["filename"]=htmlspecialchars($thefile["name"]);
									$dfiles[$numfiles]["size"]=makesize($thefile["length"]);
									$numfiles++;
								}

								elseif (isset($thefile["files"])) {
									foreach($thefile["files"] as $singlefile) {
										$dfiles[$numfiles]["filename"]=htmlspecialchars(implode("/",$singlefile["path"]));
										$dfiles[$numfiles]["size"]=makesize($singlefile["length"]);
										$numfiles++;
									}
								}

							else {
								// Impossible ... mais bon ...
							}

							}

						   	echo '<span style="font-weight: bold;">Nb de fichiers :</span> '.$numfiles.' | ';
							
						   	$stmt3 = $db->prepare('SELECT * FROM blog_posts_seo,xbt_files WHERE blog_posts_seo.postID = :postID AND xbt_files.fid = blog_posts_seo.postID');
                                                   	$stmt3->execute(array(':postID' => $row['postID']));
                                                   	$xbt = $stmt3->fetch();
						
						   	echo '<img src="images/up.png"><span style="font-weight: bold;">S :</span> <a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'">'.$xbt['seeders'].'</a> | '; 
						   	echo '<img src="images/down.png"><span style="font-weight: bold;">L :</span> <a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'">'.$xbt['leechers'].'</a> | ';

							// on met à jour le nb de vues de l'article
							$stmt33 = $db->query('UPDATE blog_posts_seo SET postViews = postViews+1 WHERE postID = '.$row['postID']);

							// on affiche le nombre de vue de l'article
							$stmt333 = $db->prepare('SELECT postViews FROM blog_posts_seo WHERE postID = :postID');
                                                        $stmt333->execute(array(':postID' => $row['postID']));
                                                        $views = $stmt333->fetch();

						   	echo '<span style="font-weight: bold;">T :</span> '.$xbt['completed'].' | ';
							echo '<span style="font-weight: bold;">Lu : </span> '.$views['postViews'].' fois | ';
						   	echo '<span style="font-weight: bold;">Licence :</span> ';	
	
						   	$stmt3 = $db->prepare('SELECT licenceID,licenceTitle FROM blog_licences, blog_post_licences WHERE blog_licences.licenceID = blog_post_licences.licenceID_BPL AND blog_post_licences.postID_BPL = :postID_BPL ORDER BY licenceTitle ASC');
                                                   	$stmt3->execute(array(':postID_BPL' => $row['postID']));
						   	$licenceRow = $stmt3->fetchALL(PDO::FETCH_ASSOC);
							$liclist = array();
							foreach($licenceRow as $lic) {
								$liclist[] = $lic['licenceTitle'];
							}
							echo htmlspecialchars(implode(" - ", $liclist));
						echo '</div>';

					echo '</div>';

					//echo '<div style="padding: 0 15px 0 15px;">';
                                        	echo '<p>';
						    if (!empty($row['postImage']) && file_exists($REP_IMAGES_TORRENTS.$row['postImage'])) {
    							echo '<img src="images/imgtorrents/'.stripslashes(htmlspecialchars($row['postImage'])).'" alt="'.stripslashes(htmlspecialchars($row['postTitle'])).'" style="float: left; margin-right: 10px; margin-top: 20px; max-width: 150px; max-height: 150px;" />';
						    }
						    else {
    						        echo '<img src="images/noimage.png" alt="Image" style="float: left; margin-right: 10px; margin-top: 20px; max-width: 150px; max-height: 150px;" />';
						    }
							echo $row['postDesc'];
							echo $row['postCont'];
						echo '</p>';
					?>

					<br />
		
				<div style="background-color: #EFF5FB; padding: 5px;">
					<!-- icones partage réseaux sociaux -->
					<!-- FACEBOOK -->
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
  				   		var js, fjs = d.getElementsByTagName(s)[0];
  				   		if (d.getElementById(id)) return;
  				   		js = d.createElement(s); js.id = id;
  				   		js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.0";
  				   		fjs.parentNode.insertBefore(js, fjs);
				   		}(document, 'script', 'facebook-jssdk'));
					</script>

					<div class="fb-like" data-href="<?php echo SITEURL; ?>/<?php echo $xbt['postSlug']; ?>" data-layout="button_count" data-action="recommend" data-show-faces="true" data-share="true"></div>

					<!-- GOOGLE+ -->
					<!-- Placez cette balise où vous souhaitez faire apparaître le gadget Bouton +1. -->
					<div class="g-plusone"></div>

			   		<!-- Placez cette ballise après la dernière balise Bouton +1. -->
					<script type="text/javascript">
  						window.___gcfg = {lang: 'fr'};

  						(function() {
    						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    						po.src = 'https://apis.google.com/js/platform.js';
    						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  						})();
					</script>

					<!-- TWITTER -->
					<a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-hashtags="freeostorrent">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>	

				</div>
				<br />

			</div>
			
			<br />

			<!-- disqus commentaires -->
			<div id="disqus_thread"></div>
    			<script type="text/javascript">
        			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        			var disqus_shortname = 'freeostorrentfr'; // required: replace example with your forum shortname

        			/* * * DON'T EDIT BELOW THIS LINE * * */
        			(function() {
            			   var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            			   dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            			   (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        			})();
    			</script>
    			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

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
