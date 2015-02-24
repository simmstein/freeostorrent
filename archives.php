<?php
require('includes/config.php');
$pagetitle= 'Archives';
require('includes/header.php');
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

                                        //collect month and year data
                                        $month = htmlentities($_GET['month']);
                                        $year = htmlentities($_GET['year']);

                                        //set from and to dates
                                        $from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
                                        $to = date('Y-m-31 23:59:59', strtotime("$year-$month"));


                                        $pages = new Paginator('10','p');

                                        $stmt = $db->prepare('SELECT postID FROM blog_posts_seo WHERE postDate >= :from AND postDate <= :to');
                                        $stmt->execute(array(
                                                ':from' => $from,
                                                ':to' => $to
                                        ));

                                        //pass number of records to
                                        $pages->set_total($stmt->rowCount());

                                        $stmt = $db->prepare('SELECT postID, postTitle, postSlug, postDesc, postDate FROM blog_posts_seo WHERE postDate >= :from AND postDate <= :to ORDER BY postID DESC '.$pages->get_limit());
                                        $stmt->execute(array(
                                                ':from' => $from,
                                                ':to' => $to
                                        ));
                                        while($row = $stmt->fetch()){
							echo '<br /><div><fieldset>';
                                                        echo '<span style="font-weight: bold; font-size: 12pt;"><a style="text-decoration: none;" href="'.$row['postSlug'].'">'.$row['postTitle'].'</a></span>';
                                                        echo '<br />Posté le '.date('j M Y', strtotime($row['postDate'])).', dans ';

                                                                $stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                                                                $stmt2->execute(array(':postID' => $row['postID']));

                                                                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                                                                $links = array();
                                                                foreach ($catRow as $cat)
                                                                {
                                                                    $links[] = "<a href='c-".$cat['catSlug']."'>".$cat['catTitle']."</a>";
                                                                }
                                                                echo implode(", ", $links);
							echo '</div></fieldset>';
                                        }
					echo '<br /><br />';
                                        echo $pages->page_links("a-$month-$year&");

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
