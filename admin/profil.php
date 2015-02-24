<?php
require('../includes/config.php');

//Si pas connecté OU si le profil n'appartient pas au membre = pas d'accès 
if(!$user->is_logged_in()) {
        header('Location: login.php');
}


if(!isset($_GET['membre'])) {
	 header('Location: '.SITEURL.'/index.php');
}

/*
if($_GET['membre'] != $_SESSION['username']) {
        header('Location: '.SITEURL.'/index.php');
}
*/

else {

// titre de la page
$pagetitle = 'Page Profil de '.htmlentities($_GET['membre'], ENT_QUOTES, "UTF-8");

require('../includes/header.php');
?>

<body>
<div id="container">

        <?php
	   require('../includes/header-logo.php');
           require('../includes/nav.php');
        ?>


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

		<?php
        	//On affiche le résultat de l'édition du profil
        	if(isset($_GET['action'])){
                	echo '<h3 style="color: green;">Profil mis à jour : '.htmlentities($_GET['action'], ENT_QUOTES, "UTF-8").' !</h3>';
        	}
        	?>

		<?php
                //On affiche le résultat de l'envoi de message interne
                if(isset($_GET['message'])){
                        echo '<h3 style="color: green;">Le message a bien été envoyé : '.htmlentities($_GET['message'], ENT_QUOTES, "UTF-8").' !</h3>';
                }
                ?>



		<?php
		try {
			$stmt = $db->prepare('SELECT * FROM blog_members,xbt_users WHERE blog_members.memberID = xbt_users.uid AND username = :username');
			$stmt->execute(array(
				':username' => $_GET['membre']
			));
			$row = $stmt->fetch();
		}
		catch(PDOException $e) {
		    echo $e->getMessage();
		}
		?>


	<?php
	if(isset($_SESSION['username']) && $_SESSION['username'] != $_GET['membre']) {
	?>

		<table>
                        <tr>
				<td>ID de membre : </td><td><?php echo $row['memberID']; ?></td>
				<?php   
                        	if(empty($row['avatar'])) {
                        	?> 
					<td rowspan="6" style="text-align: center;"><img style="max-width: 125px; max-height: 125px;" src="<?php echo SITEURL; ?>/images/avatars/avatar-profil.png" alt="Pas d'avatar pour <?php echo $row['username']; ?>" /></td>
                        	<?php }
                                else {
                        	?>
                        		<td rowspan="7" style="text-align: center;"><img style="max-width: 125px; max-height: 125px;" src="<?php echo SITEURL; ?>/images/avatars/<?php echo $row['avatar']; ?>" alt="Avatar de <?php echo $row['username']; ?>" /></td>
                        	<?php } ?>
			</tr>
			<tr><td>Pseudo :</td><td style="font-weight: bold;"><?php echo $row['username']; ?>
				<?php
				if($row['username'] == 'mumbly') {
					echo '<span style="font-weight: bold; color: green;"> [ Webmaster ]</span>';
				}
				?>
			</td></tr>
                        <tr><td>Date d'inscription : </td><td>
                        
                        <?php
                                sscanf($row['memberDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
                                echo 'Le '.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde;
                        ?>      
                        
                        </td></tr>

                        <tr><td>Envoyé :</td><td><?php echo makesize($row['uploaded']); ?></td></tr>
                        <tr><td>Téléchargé :</td><td><?php echo makesize($row['downloaded']); ?></td></tr>
                        
                        <?php
			//Peer Ratio
      			if (intval($row["downloaded"])>0) {
         			$ratio=number_format($row["uploaded"]/$row["downloaded"],2);
			}
      			else {
				$ratio='&#8734;';
			}
                        ?>      
                        
                        <tr><td>Ratio de partage :</td><td><?php echo $ratio; ?></td></tr>
                </table>

		<br />

		<?php
		if($_SESSION['username'] == 'mumbly') {
		?>
	
			<!-- Historique téléchargements -->
			<table>
				<tr><td colspan="6"><h3 id="historique">Vos téléchargements :</h3></td></tr>
        			<?php
				$pages = new Paginator('10','p');
				//$stmt = $db->query('SELECT postID FROM blog_posts_seo WHERE postAuthor = "'.$row['username'].'"');
				$stmt = $db->prepare('SELECT fid FROM xbt_files_users WHERE uid = :uid');
				$stmt->execute(array(
					':uid' => $row['memberID']
	 			));

			$pages->set_total($stmt->rowCount());

        		$stmtorr1 = $db->prepare('
        			SELECT * FROM xbt_files_users 
                		LEFT JOIN blog_posts_seo ON blog_posts_seo.postID = xbt_files_users.fid 
				LEFT JOIN xbt_files ON xbt_files.fid = blog_posts_seo.postID 
                		WHERE xbt_files_users.uid = :uid
                		ORDER BY blog_posts_seo.postDate DESC '.$pages->get_limit()
			);
			$stmtorr1->execute(array(
				':uid' => $row['memberID']
			));
		?>

		<tr>
			<th>Nom</th>
			<th>Ajouté</th>
			<th>Taille</th>
			<th style="text-align: center;">S</th>
			<th style="text-align: center;">L</th>
			<th style="text-align: center;">T</th>
		</tr>

		<?php
		while($rowtorr = $stmtorr1->fetch()) {
		//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';
		//$x++;
                //$tablerow = ($x%2 == 0)? 'tableRow1': 'tableRow2';
		?>	
		<tr>
			<td style="font-weight: bold;">
				<a style="text-decoration: none;" href="<?php echo SITEURL; ?>/<?php echo $rowtorr['postSlug']; ?>"><?php echo $rowtorr['postTitle'];?></a>
			</td>
			<?php
			sscanf($rowtorr['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
			echo '<td>'.$jour.'-'.$mois.'-'.$annee.'</td>';
			?>
			<td><?php echo makesize($rowtorr['postTaille']); ?></td>
			<td style="text-align: center; font-weight: bold;"><a style="text-decoration: none;" href="../peers.php?id=<?php echo $rowtorr['postID']; ?>&torrent=<?php echo $rowtorr['postTitle']; ?>"><?php echo $rowtorr['seeders']; ?></a></td>
			<td style="text-align: center; font-weight: bold;"><a style="text-decoration: none;" href="../peers.php?id=<?php echo $rowtorr['postID']; ?>&torrent=<?php echo $rowtorr['postTitle']; ?>"><?php echo $rowtorr['leechers']; ?></a></td>
			<td style="text-align: center;"><?php echo $rowtorr['completed']; ?></td>
		</tr>
		<?php }// while ?>

</table>
<!-- //historique téléchargements -->

<?php
	echo '<div style="text-align: center;">';
        	echo $pages->page_links('?membre='.$row['username'].'&');
        echo '</div>';
?>

<br />

		<?php
		}
		?>


	<?php
	}// fin if($_SESSION)


	else {
	?>

		<h2>
                Profil membre de : <?php echo $row['username']; ?>
                <a style="text-decoration: none;" href="<?php echo SITEURL; ?>/admin/edit-profil.php?membre=<?php echo $row['username']; ?>"><input type="button" class="button" value="Editer votre profil" /></a>
                </h2>
                <br />


		<table>	
			<tr>
				<td>ID de membre : </td><td><?php echo $row['memberID']; ?></td>

				<?php
                        	if(empty($row['avatar'])) {
                        	?>
                                	<td rowspan="7" stule="text-align: center;"><img style="max-width: 125px; max-height: 125px;" src="<?php echo SITEURL; ?>/images/avatars/avatar-profil.png" alt="Pas d'avatar pour <?php echo $row['username']; ?>" /></td>
                        	<?php }
                        	else {
                        	?>
                                	<td rowspan="7" style="text-align: center;"><img style="max-width: 125px; max-height: 125px;" src="<?php echo SITEURL; ?>/images/avatars/<?php echo $row['avatar']; ?>" alt="Avatar de <?php echo $row['username']; ?>" /></td>
                        	<?php } ?>
			</tr>
			<tr><td>E-mail : </td><td><?php echo $row['email']; ?></td></tr>
			<tr><td>Pid : </td><td><?php echo $row['pid']; ?></td></tr>
			<tr><td>Date d'inscription : </td><td>

			<?php
				sscanf($row['memberDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
				echo 'Le '.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde;
			?>

			</td></tr>
			<tr><td>Envoyé :</td><td><?php echo makesize($row['uploaded']); ?></td></tr>
			<tr><td>Téléchargé :</td><td><?php echo makesize($row['downloaded']); ?></td></tr>

			<?php
				$ratio = $row['uploaded'] / $row['downloaded'];
                                $ratio = number_format($ratio, 2);
			?>

			<tr><td>Ratio de partage :</td><td><?php echo $ratio; ?></td></tr>

		</table>

<br />



<!-- Historique téléchargements -->
<table>
	<tr><td colspan="6"><h3 id="historique">Vos téléchargements :</h3></td></tr>
        <?php
	$pages = new Paginator('10','p');
	//$stmt = $db->query('SELECT postID FROM blog_posts_seo WHERE postAuthor = "'.$row['username'].'"');
	$stmt = $db->prepare('SELECT fid FROM xbt_files_users WHERE uid = :uid');
	$stmt->execute(array(
		':uid' => $row['memberID']
	 ));

	$pages->set_total($stmt->rowCount());
	
	// TRI
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

        $stmtorr1 = $db->prepare('
        	SELECT * FROM xbt_files_users xfu 
                LEFT JOIN blog_posts_seo bps ON bps.postID = xfu.fid 
		LEFT JOIN xbt_files xf ON xf.fid = bps.postID 
                WHERE xfu.uid = :uid
                ORDER BY '.$tri.' '.$ordre.' '.$pages->get_limit()
		);
	$stmtorr1->execute(array(
		':uid' => $row['memberID']
	));
	?>
		<tr>
			<th style="width: 420px;"><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postTitle&ordre=desc">&#x2191;</a>Nom<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postTitle&ordre=asc">&#x2193;</a></th>
			<th><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postDate&ordre=desc">&#x2191;</a>Ajouté<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postDate&ordre=asc">&#x2193;</a></th>
			<th><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postTaille&ordre=desc">&#x2191;</a>Taille<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=postTaille&ordre=asc">&#x2193;</a></th>
			<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=seeders&ordre=desc">&#x2191;</a>S<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=seeders&ordre=asc">&#x2193;</a></th>
			<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=leechers&ordre=desc">&#x2191;</a>L<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=leechers&ordre=asc">&#x2193;</a></th>
			<th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=xf.completed&ordre=desc">&#x2191;</a>T<a style="color: #fff; text-decoration: none;" href="profil.php?membre=<?php echo $row['username']; ?>&tri=xf.completed&ordre=asc">&#x2193;</a></th>
		</tr>

		<?php
		while($rowtorr = $stmtorr1->fetch()) {
		//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';
		//$x++;
                //$tablerow = ($x%2 == 0)? 'tableRow1': 'tableRow2';
		?>	
		<tr>
			<td style="font-weight: bold;">
				<a style="text-decoration: none;" href="<?php echo SITEURL; ?>/<?php echo $rowtorr['postSlug']; ?>"><?php echo $rowtorr['postTitle'];?></a>
			</td>
			<?php
			sscanf($rowtorr['postDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
			echo '<td>'.$jour.'-'.$mois.'-'.$annee.'</td>';
			?>
			<td><?php echo makesize($rowtorr['postTaille']); ?></td>
			<td style="text-align: center; font-weight: bold;"><a style="text-decoration: none;" href="../peers.php?id=<?php echo $rowtorr['postID']; ?>&torrent=<?php echo $rowtorr['postTitle']; ?>"><?php echo $rowtorr['seeders']; ?></a></td>
			<td style="text-align: center; font-weight: bold;"><a style="text-decoration: none;" href="../peers.php?id=<?php echo $rowtorr['postID']; ?>&torrent=<?php echo $rowtorr['postTitle']; ?>"><?php echo $rowtorr['leechers']; ?></a></td>
			<td style="text-align: center;"><?php echo $rowtorr['completed']; ?></td>
		</tr>
		<?php } ?>

</table>
<!-- //historique téléchargements -->

<?php
	echo '<div style="text-align: center;">';
        	//echo $pages->page_links('?membre='.$row['username'].'&');
		echo $pages->page_links('?membre='.$row['username'].'&tri='.$tri.'&ordre='.$ordre.'&');
        echo '</div>';
?>

<br />


<!-- Messages internes -->
<?php
$pages = new Paginator('5','m');
$stmt = $db->query('SELECT messages_id FROM blog_messages');
$pages->set_total($stmt->rowCount());

// on prépare une requete SQL cherchant tous les titres, les dates ainsi que l'auteur des messages pour le membre connecté
$stmt = $db->prepare('SELECT blog_messages.messages_titre, blog_messages.messages_date, blog_members.username as expediteur, blog_messages.messages_id as id_message FROM blog_messages, blog_members WHERE blog_messages.messages_id_destinataire = :id_destinataire AND blog_messages.messages_id_expediteur = blog_members.memberID ORDER BY blog_messages.messages_date DESC '.$pages->get_limit());
$stmt->execute(array(
	':id_destinataire' => htmlspecialchars($_SESSION['userid'])
	));
?>

<table>
	<tr>
		<td colspan="5">
			<h3 id="messages">Vos messages : 
				<a style="text-decoration: none;" href="<?php echo SITEURL; ?>/messages_envoyer.php"><input type="button" class="button" value="Envoyer un message à un membre" /></a>
			</h3>
		</td>
	</tr>
	<tr>
		<th style="width: 150px;">Date</th>
		<th>Titre</th>
                <th style="width: 120px;">Expéditeur</th>
	</tr>

	<?php
	while($data = $stmt->fetch()){
		// couleurs de row alternées
		//$x++; 
		//$tablerow = ($x%2 == 0)? 'tableRow1': 'tableRow2';
		//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';

		//echo '<tr class="'.$tablerow.'">';
		echo '<tr>';
			sscanf($data['messages_date'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
                        echo '<td>'.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde.'</td>';
			echo '<td><a style="text-decoration: none;" href="'.SITEURL.'/messages_lire.php?id_message='.$data['id_message'].'">'.stripslashes(htmlentities(trim($data['messages_titre']), ENT_QUOTES, "UTF-8")).'</a></td>';
			echo '<td>'.stripslashes(htmlentities(trim($data['expediteur']), ENT_QUOTES, "UTF-8")).'</td>';
		echo '</tr>';
	}
	?>
</table>

<?php echo $pages->page_links('?membre='.$row['username'].'&'); ?>

	<br /><br />

	<?php
	}// fin else
	?>

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
<?php } ?>
