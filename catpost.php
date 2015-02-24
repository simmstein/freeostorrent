<?php
require('includes/config.php');

$stmt = $db->prepare('SELECT catID,catTitle FROM blog_cats WHERE catSlug = :catSlug');
$stmt->execute(array(':catSlug' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['catID'] == ''){
        header('Location: ./');
        exit;
}

$pagetitle = 'Catégorie : '.$row['catTitle'];

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
                        try {

                                $pages = new Paginator('8','p');

                                $stmt = $db->prepare('SELECT blog_posts_seo.postID FROM blog_posts_seo, blog_post_cats WHERE blog_posts_seo.postID = blog_post_cats.postID AND blog_post_cats.catID = :catID');
                                $stmt->execute(array(':catID' => $row['catID']));

                                //pass number of records to
                                $pages->set_total($stmt->rowCount());

                                $stmt = $db->prepare('
                                        SELECT
                                                blog_posts_seo.postID, blog_posts_seo.postTitle, blog_posts_seo.postAuthor, blog_posts_seo.postSlug, blog_posts_seo.postDesc, blog_posts_seo.postDate
                                        FROM
                                                blog_posts_seo,
                                                blog_post_cats
                                        WHERE
                                                 blog_posts_seo.postID = blog_post_cats.postID
                                                 AND blog_post_cats.catID = :catID
                                        ORDER BY
                                                postID DESC
                                        '.$pages->get_limit());
                                $stmt->execute(array(':catID' => $row['catID']));
                                while($row = $stmt->fetch()){

                                        echo '<br /><div><fieldset>';
                                                echo '<span style="font-weight: bold; font-size: 12pt;"><a style="text-decoration: none;" href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></span>';
                                                //echo 'Posté le '.date('j M Y H:i:s', strtotime($row['postDate'])).' dans ';
						sscanf($row['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
						echo '<br />Posté le '.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde.', par ';

                                                $stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                                                $stmt2->execute(array(':postID' => $row['postID']));
                                                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

						echo $row['postAuthor'].', dans ';

                                                        $links = array();
                                                        foreach ($catRow as $cat)
                                                        {
                                                            $links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                                                        }
                                                        echo implode(", ", $links);

                                        echo '</fieldset></div>';

                                }

				echo '<br /><br />';
                               	echo $pages->page_links('c-'.$_GET['id'].'&');

                        } catch(PDOException $e) {
                            echo $e->getMessage();
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
