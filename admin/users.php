<?php
//include config
require_once('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in() || $_SESSION['username'] != 'mumbly') {
        header('Location: login.php');
}

//show message from add / edit page
if(isset($_GET['deluser'])){

        //if user id is 1 ignore
        if($_GET['deluser'] !='1'){

		// On supprime l'avatar du membre
                $stmt = $db->prepare('SELECT avatar FROM blog_members WHERE memberID = :memberID');
                $stmt->execute(array(':memberID' => $_GET['deluser'] ));
                $sup = $stmt->fetch();
                $file = $REP_IMAGES_AVATARS.$sup['avatar'];
                if (file_exists($file)) {
                        unlink($file);
                }

		// on supprime le membre
                $stmt = $db->prepare('DELETE FROM blog_members WHERE memberID = :memberID') ;
                $stmt->execute(array(':memberID' => $_GET['deluser']));

		// on supprime les données torrent du membre
		$stmt1 = $db->prepare('DELETE FROM xbt_users WHERE uid = :uid') ;
		$stmt1->execute(array(':uid' => $_GET['deluser']));

		// on supprime les commentaires du membre
		$stmt2 = $db->prepare('DELETE FROM blog_posts_comments WHERE cuser = :cuser') ;
                $stmt2->execute(array(':cuser' => $_GET['delname']));

                header('Location: users.php?action=supprime');
                exit;

        }
}

// titre de la page 
$pagetitle= 'Admin : gestion des membres';
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
		
		        <?php include('menu.php');?>

        		<?php
        		//show message from add / edit user 
        		if(isset($_GET['action'])){
                		echo '<h3>Membre '.$_GET['action'].' !</h3>';
        		}
        		?>

        <table>
        <tr>
		<th>ID</th>
                <th>Pseudo</th>
		<th>PID</th>
                <th>Email</th>
		<th style="text-align: center;">Inscription</th>
                <th style="text-align: center;">Action</th>
        </tr>
        <?php
                try {
			$pages = new Paginator('10','p');

			$stmt = $db->query('SELECT memberID FROM blog_members');

			//pass number of records to
			$pages->set_total($stmt->rowCount());

                        $stmt = $db->query('SELECT memberID,username,pid,email,memberDate FROM blog_members ORDER BY memberID DESC '.$pages->get_limit());
                        while($row = $stmt->fetch()){

                                echo '<tr>';
				echo '<td>'.$row['memberID'].'</td>';
                                echo '<td style="width: 20%;">'.$row['username'].'</td>';
				echo '<td style="font-size: 9px;">'.$row['pid'].'</td>';
                                echo '<td style="font-size: 9px;">'.$row['email'].'</td>';

				sscanf($row['memberDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
				echo '<td style="font-size: 9px; text-align: center;">'.$jour.'-'.$mois.'-'.$annee.'</td>';
                                ?>

                                <td style="text-align: center;">
                                        <a style="text-decoration: none;" href="edit-user.php?id=<?php echo $row['memberID'];?>"><input type="button" class="button" value="Edit." /></a>
                                        <?php if($row['memberID'] != 1){?>
                                                | <a style="text-decoration: none;" href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')"><input type="button" class="button" value="Supp." /></a>
                                        <?php } ?>
                                </td>

                                <?php
                                echo '</tr>';

                        }

                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
        ?>
        </table>

	<br />

	<?php
	echo $pages->page_links();
	?>

	<p style="text-align: right;">
		<a href="add-user.php" style="text-decoration: none;"><input type="button" class="button" value="Ajouter un membre" /></a>
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
