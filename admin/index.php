<?php
require_once('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in()) { 
	header('Location: login.php');
}

if(isset($_SESSION['username'])) {
	if($_SESSION['username'] != 'mumbly') {
		header('Location: '.SITEURL);
	}
}

//Si le torrent est à supprimer ...
if(isset($_GET['delpost'])) {

       // 1 - on supprime le fichier .torrent dans le répertoire /torrents
        $stmt4 = $db->prepare('SELECT postID,postTorrent FROM blog_posts_seo WHERE postID = :postID') ;
        $stmt4->execute(array(
                ':postID' => $_GET['delpost']
        ));
        $efface = $stmt4->fetch();

        $file = $REP_TORRENTS.$efface['postTorrent'];
        if (file_exists($file)) {
                unlink($file);
        }

	// 2 - on supprime le torrent dans la base
        $stmt = $db->prepare('DELETE FROM blog_posts_seo WHERE postID = :postID') ;
        $stmt->execute(array(
		':postID' => $_GET['delpost']
	));

        // 3 - on supprime sa référence de catégorie
        $stmt1 = $db->prepare('DELETE FROM blog_post_cats WHERE postID = :postID');
        $stmt1->execute(array(
		':postID' => $_GET['delpost']
	));

        // 4 - on supprime sa référence de licence
        $stmt2 = $db->prepare('DELETE FROM blog_post_licences WHERE postID_BPL = :postID_BPL');
        $stmt2->execute(array(
                ':postID_BPL' => $_GET['delpost']
        ));

	// 5 - on supprime ses commentaires s'ils existent
	$stmt22 = $db->prepare('SELECT cid_torrent FROM blog_posts_comments WHERE cid_torrent = :cid_torrent');
	$stmt22->execute(array(
		':cid_torrent' => $_GET['delpost']
	));
	$commentaire = $stmt22->fetch();
	
	if(!empty($commentaire)) {
		$stmtsupcomm = $db->prepare('DELETE FROM blog_posts_comments WHERE cid_torrent = :cid_torrent');
		$stmtsupcomm->execute(array(
                	':cid_torrent' => $_GET['delpost']
        	));
	}

	// 6 - enfin, on met le flag à "1" pour supprimer le fichier dans la tables xbt_files
	$stmt3 = $db->prepare('UPDATE xbt_files SET flags = :flags WHERE fid = :fid') ;
        $stmt3->execute(array(
		':flags' => '1',
		':fid' => $_GET['delpost'] 
	));	


        header('Location: index.php?action=supprime');
        exit;

}//fin de if isset $_GET['delpost']

// titre de la page
$pagetitle = 'Admin : gestion des torrents';
require('../includes/header.php');

?>

<body>

<div id="container">

	<?php
		require('../includes/header-logo.php');
		require('../includes/nav.php'); ?>

    	<div id="body">
		<div id="content">
	
		<?php
// fil d'ariane
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
	
		<?php include('menu.php');?>

        <?php
        //show message from add / edit page
        if(isset($_GET['action'])){
                echo '<div class="alert-msg rnd8 success">Torrent '.$_GET['action'].'.</div>';
        }
        ?>

        <table>
        <tr>
                <th>Titre</th>
                <th style="text-align: center;">Date</th>
		<th style="text-align: center;">Uploader</th>
                <th style="text-align: center;">Action</th>
        </tr>
        <?php
                try {

			$pages = new Paginator('10','p');

            		$stmt = $db->query('SELECT postID FROM blog_posts_seo');

            		//pass number of records to
            		$pages->set_total($stmt->rowCount());

                        $stmt = $db->query('SELECT postID, postTitle, postAuthor, postDate FROM blog_posts_seo ORDER BY postID DESC '.$pages->get_limit());
                        while($row = $stmt->fetch()){

                                echo '<tr>';
                                echo '<td style="width:55%;">'.$row['postTitle'].'</td>';
				sscanf($row['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
				echo '<td style="font-size: 9px; text-align: center;">'.$jour.'-'.$mois.'-'.$annee.'</td>';
				echo '<td style="font-size: 9px; text-align: center;"><a href="profil.php?membre='.$row['postAuthor'].'">'.$row['postAuthor'].'</a></td>';
                                ?>

                                <td style="text-align: center;">
                                        <a style="text-decoration: none;" href="edit-post.php?id=<?php echo $row['postID'];?>"><input type="button" class="button" value="Edit." /></a>&nbsp;
                                        <a style="text-decoration: none;" href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')"><input type="button" class="button" value="Supp." /></a>
                                </td>

                                <?php
                                echo '</tr>';

                        }


                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
        ?>
        </table>

	<p>	
		<?php echo $pages->page_links(); ?>
	</p>	

	</div>
        
	<?php require('../sidebar.php'); ?>
        
    	<div class="clear"></div>
    </div>
</div>

<div id="footer">
	<?php require('../includes/footer.php'); ?>
</div>

</body>
</html>
