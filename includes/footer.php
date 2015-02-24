<div id="cl1" class="footer-col">
<p>
   <h3 style="color: #E66000;">10 derniers torrents</h3><hr style="color: #E66000; margin: -8px 0 15px 0;">
	<ul class="blocklist">
                        <?php
                        $stmt = $db->query('SELECT blog_posts_seo.postID,blog_posts_seo.postTitle,blog_posts_seo.postSlug,xbt_files.seeders,xbt_files.leechers FROM blog_posts_seo,xbt_files WHERE blog_posts_seo.postID = xbt_files.fid ORDER BY postID DESC LIMIT 10');
                        while($row = $stmt->fetch()){
                        	echo '<li>&#187 <a style="text-decoration: none; color: white;" href="'.SITEURL.'/'.$row['postSlug'].'"><span>'.mb_strimwidth($row['postTitle'], 0, 35, "...").'</span></a>
				&nbsp;[&nbsp;<a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'"><span style="color: green; font-size: 10px;">'.$row['seeders'].'</span></a> | 
				<a style="text-decoration: none;" href="peers.php?id='.$row['postID'].'&torrent='.$row['postTitle'].'"><span style="color: red; font-size: 10px;">'.$row['leechers'].'</span></a>&nbsp;]</li>';
                        }
                        ?>
                </ul>
</p>
</div>

<div id="cl2" class="footer-col">
<p>             
   <h3 style="color: #E66000;">10 torrents les + vus</h3><hr style="color: #E66000; margin: -8px 0 15px 0;">
        <ul class="blocklist">
                <?php
                        $stmt = $db->query('SELECT postSlug,postTitle,postAuthor,postDate,postViews FROM blog_posts_seo ORDER BY postViews DESC LIMIT 10');
                        while($row = $stmt->fetch()) {
                                echo '<li>&#187 <a style="text-decoration: none; color: white;" href="'.SITEURL.'/'.$row['postSlug'].'">'.mb_strimwidth($row['postTitle'], 0, 35, "...").'</a> [<span style="color: green;">'.$row['postViews'].'</span>]</li>';
                        }
                        ?>
        </ul>
</p>
</div>



<div id="cl4" class="footer-col">
<p>
<h3 style="color: #E66000;">Besoin de seed</h3><hr style="color: #E66000; margin: -8px 0 15px 0;">
        <ul class="blocklist">
                <?php
                $stmt = $db->query('SELECT blog_posts_seo.postID,blog_posts_seo.postTitle,blog_posts_seo.postSlug,xbt_files.fid,xbt_files.seeders,xbt_files.leechers FROM blog_posts_seo,xbt_files WHERE blog_posts_seo.postID = xbt_files.fid AND xbt_files.seeders = 0 AND xbt_files.leechers > 0 ORDER BY postID DESC LIMIT 10');
                while($row = $stmt->fetch()) {
                        if(!empty($row)) {
                                echo '<li>&#187; <a style="text-decoration: none; color: white;" href="'.SITEURL.'/'.$row['postSlug'].'">'.mb_strimwidth($row['postTitle'], 0, 35, "...").' [ <span style="color: green;">'.$row['seeders'].'</span> | <span style="color: red;">'.$row['leechers'].'</span> ]</a></li>';
                        }
                        else {
                                echo 'Aucun besoin de seed';
                        }
                }
                ?>
        </ul>
</p>


</div>

<div id="cl3" class="footer-col">
<p>
<h3 style="color: #E66000;">Liens web</h3><hr style="color: #E66000; margin: -8px 0 15px 0;">
	<ul class="blocklist">
		<li>&#187; <a style="text-decoration: none; color: #fff;" href="http://www.freetorrent.fr">freetorrent.fr : pour une culture Libre</a></li>
		<li>&#187; <a style="text-decoration: none; color: #fff;" href="http://www.mumbly58.fr">mumbly58.fr : blog geek et Libre</a></li>
		<li>&#187; <a style="text-decoration: none; color: #fff;" href="http://azote.org">azote.org : noms de domaine gratuits</a></li>
	</ul>
</p>
</div>



<!--
<div id="cl5" class="footer-col">
<p>
<h3 style="color: #E66000">Nuage de mot-clés</h3>
</p>
</div>

<div id="cl6" class="footer-col">
<p>
<h3 style="color: #E66000">A propos...</h3>
</p>
</div>
-->

<div class="clear"></div>

<div class="footer-width footer-bottom">
	<p>Copyleft <span style="font-weight: bold;"><?php echo SITEURL; ?></span> | 2015 | 
		<a href="http://zypopwebtemplates.com/">Free Web Templates</a> by ZyPOP | 
		Noms de domaines gratuits <a href="http://azote.org">Azote.org</a> |

		<?php
		/**
		* Mesurer le temps de chargement d'une page HTML
		*/
 
		// relever le point de départ
		$timestart=microtime(true);
 
		/**
		* Charger la page index du site Fobec.com
		*/
		file_get_contents('http://www.fobec.com');
 
		//Fin du code PHP
		$timeend=microtime(true);
		$time=$timeend-$timestart;
 
		//Afficher le temps de chargement
		$page_load_time = number_format($time, 3);
		//echo "Debut du chargement: ".date("H:i:s", $timestart);
		//echo "<br>Fin de reception: ".date("H:i:s", $timeend);
		echo "Page chargée en " . $page_load_time . " sec";
		?>

 
		<span style="float:right; font-style: italic;">Version du site : <?php echo SITEVERSION; ?> du <?php echo SITEDATE; ?></span>
	</p>
</div>
