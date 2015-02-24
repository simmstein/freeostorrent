<?php
require_once('../includes/config.php');

//Si pas connecté pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in()) {
        header('Location: '.SITEURL.'/admin/login.php');
}


//Si on supprime l'avatar...
if(isset($_GET['delavatar'])) {

	$delavatar = $_GET['delavatar'];

	// on supprime le fichier image
	$stmt = $db->prepare('SELECT avatar FROM blog_members WHERE memberID = :memberID');
	$stmt->execute(array(
		':memberID' => $delavatar
	));
	$sup = $stmt->fetch();

	$file = $REP_IMAGES_AVATARS.$sup['avatar']; 
	if (file_exists($file)) {
		unlink($file);
	}

	//puis on supprime l'avatar dans la base
	$stmt = $db->prepare('UPDATE blog_members SET avatar = NULL WHERE memberID = :memberID');
	$stmt->execute(array(
                ':memberID' => $delavatar
        ));

	header('Location: '.SITEURL.'/admin/profil.php?action=ok&membre='.$_SESSION['username']);

}

// titre de la page
$pagetitle = 'Edition du profil de '.$_SESSION['username'];
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
		
        <h2>Edition du profil membre de <?php echo htmlspecialchars($_GET['membre']); ?></h2>

        <?php
	$username = $_GET['membre'];


        //if form has been submitted process it
        if(isset($_POST['submit'])) {

		//collect form data
                extract($_POST);

		if(isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['name'])) {
		//if(isset($_POST['avatar']) && !empty($_POST['avatar'])) {

                	$target_dir = $REP_IMAGES_AVATARS;
                	$target_file = $target_dir . basename($_FILES["avatar"]["name"]);
                	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                	if ($_FILES['avatar']['error'] > 0) {
                	//if ($_FILES['avatar']['error'] > 0) {
                        	$error[] = 'Erreur lors du transfert de l\'avatar membre.';
                	}

                	// On cherche si l'image n'existe pas déjà sous ce même nom
                	if (file_exists($target_file)) {
                        	$error[] = 'Désolé, cet avatar membre existe déjà. Veillez en choisir un autre ou tout simplement changer son nom.';
                	}

                	// taille de l'image
                	if ($_FILES['avatar']['size'] > $MAX_SIZE_AVATAR) {
                        	$error[] = 'Aavatar membre trop gros. Taille maxi : '.makesize($MAX_SIZE_AVATAR);
                	}

                	// format de l'image
                	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        	$error[] = 'Désolé : seuls les fichiers .jpg, .png, .jpeg sont autorisés !';
                	}

                	// poids de l'image
                	$image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
                	if ($image_sizes[0] > $WIDTH_MAX_AVATAR OR $image_sizes[1] > $HEIGHT_MAX_AVATAR) {
                        	$error[] = 'Avatar trop grand : '.$WIDTH_MAX_AVATAR.' x '.$HEIGHT_MAX_AVATAR.' maxi !';
                	}

                	// on vérifie que c'est bien une image
                	if($image_sizes == false) {
                        	$error[] = 'L\'image envoyée n\'est pas une image !';
                	}

                	// on upload l'image s'il n'y a pas d'erreur
                	if(!isset($error)) {
                        	if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $REP_IMAGES_AVATARS.$_FILES['avatar']['name'])) {
                                	$error[] = 'Problème de téléchargement de l\'avatar membre.';
                        	}
                	}

		}//fin de if(isset($_FILES['avatar']['name']))


                //very basic validation
                if($username ==''){
                        $error[] = 'Veuillez entrer un pseudo.';
                }

                if( strlen($password) > 0) {

                        if($password ==''){
                                $error[] = 'Veuillez entrer un mot de passe.';
                        }

                        if($passwordConfirm ==''){
                                $error[] = 'Veuillez confirmer le mot de passe.';
                        }

                        if($password != $passwordConfirm){
                                $error[] = 'Les mots de passe ne concordent pas.';
                        }

                }

                if($email =='') {
                        $error[] = 'Veuillez entrer une adresse e-mail.';
                }


                if(!isset($error)) {

                        try {

                                if(isset($password) && !empty($password)){

                                        $hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

                                        //Mise à jour de la base avec le nouveau mot de passe
                                        $stmt = $db->prepare('UPDATE blog_members SET password = :password, email = :email WHERE username = :username') ;
                                        $stmt->execute(array(
                                                ':username' => $username,
                                                ':password' => $hashedpassword,
                                                ':email' => $email
                                        ));
                                }

				elseif(isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['name'])) {
					//Mise à jour de la base avec le nouvel avatar 
                                        $stmt = $db->prepare('UPDATE blog_members SET email = :email, avatar = :avatar WHERE username = :username') ;
                                        $stmt->execute(array(
                                                ':username' => $username,
                                                ':avatar' => $_FILES['avatar']['name'],
						':email' => $email
                                        ));
				}			

				else {
                                        //Mise à jour de la base avec adresse e-mail seulement. Aucun nouveau mot de passe n'a été soumis ni aucun avatar
                                        $stmt = $db->prepare('UPDATE blog_members SET email = :email WHERE username = :username') ;
                                        $stmt->execute(array(
                                                ':username' => $username,
                                                ':email' => $email
                                        ));
                                }


                                //redirect to page
                                header('Location: '.SITEURL.'/admin/profil.php?action=ok&membre='.$username);
                                exit;

				$stmt->closeCursor();

                        }

			catch(PDOException $e) {
                        	echo $e->getMessage();
                        }

                }

        }

        //check for any errors
        if(isset($error)) {
                foreach($error as $error) {
                        echo '<p class="error">'.$error.'</p>';
                }
        }

                try {

                        $stmt = $db->prepare('SELECT memberID,username,email,avatar FROM blog_members WHERE username = :username') ;
                        $stmt->execute(array(':username' => $username));
                        $row = $stmt->fetch();

                }

		catch(PDOException $e) {
                    echo $e->getMessage();
                }

        ?>

        <form action='' method='post' enctype='multipart/form-data'>

                <p><label>Pseudo</label><br />
                <input type='text' name='username' value='<?php echo $row['username']; ?>'></p>

                <p><label>Mot de passe (seulement en cas de changement)</label><br />
                <input type='password' name='password' value=''></p>

                <p><label>Confirmez le mot de passe</label><br />
                <input type='password' name='passwordConfirm' value=''></p>

                <p><label>E-mail</label><br />
                <input type='text' name='email' value='<?php echo $row['email'];?>'></p>

		<p><label>Avatar (PNG ou JPG | max. <?php echo makesize($MAX_SIZE_AVATAR); ?> | max. <?php echo $WIDTH_MAX_AVATAR; ?> x <?php echo $HEIGHT_MAX_AVATAR; ?> pix.)</label><br />
                <input type='file' name='avatar'>

		<br /><br />Avatar actuel :
			<?php
			if(!empty($row['avatar']) && file_exists($REP_IMAGES_AVATARS.$row['avatar'])) {
				echo '<img style="max-width: 125px; max-height: 125px;" src="/images/avatars/'.$row['avatar'].'" alt="Avatar de '.$row['username'].'" />';
			?>

			<a href="javascript:delavatar('<?php echo $row['memberID'];?>','<?php echo $row['avatar'];?>')">Supprimer</a>

			<?php
			}
			else {
				echo '<img style="width: 100px; height: 100px;" src="/images/noimage.png" alt="Pas d\'avatar pour '.$row['username'].'" />';
			}
			?>
		</p>

		<br />
                <p><input type='submit' class="searchsubmit formbutton" name='submit' value='Mise à jour du profil membre'></p>

        </form>
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
