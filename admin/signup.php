<?php
require_once('../includes/config.php');

if($user->is_logged_in()) {
   header('Location: ../index.php');
}

//titre de la page
$pagetitle= 'Créer un compte';
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
		
		<h2>Créer un compte</h2>

        <?php

        //if form has been submitted process it
        if(isset($_POST['submit'])){

                //collect form data
                extract($_POST);

                //very basic validation
                if($username ==''){
                        $error[] = 'Veuillez entrer un pseudo.';
                }

                if($password ==''){
                        $error[] = 'Veuillez entrer un mot de passe.';
                }

		if (strlen($password) < 6) {
                	$error[] = 'Le mot de passe est trop court ! (6 caractères minimum)';
                }

                if($passwordConfirm ==''){
                        $error[] = 'Veuillez confirmer le mot de passe.';
                }

                if($password != $passwordConfirm){
                        $error[] = 'Les mots de passe ne concordent pas.';
                }

                if($email ==''){
                        $error[] = 'Veuillez entrer une adresse e-mail.';
                }

		// On cherche si l'adresse e-mail est déjà dans la base
		if (isset($_POST['email'])) {
			$stmt = $db->prepare('SELECT email FROM blog_members WHERE email = :email');		
			$stmt->execute(array(
				':email' => $_POST['email']
			));
			$res = $stmt->fetch();

			if ($res) {
				$error[] = 'Cette adresse e-mail est déjà utilisée !';
			}
		}	

		// On cherche si le pseudo fait moins de 4 caractères et s'il est déjà dans la base
                if (strlen($_POST['username']) < 4) {
                	$error[] = 'Le pseudo est trop court ! (4 caractères minimum)';
		}

		else {
                        $stmt = $db->prepare('SELECT username FROM blog_members WHERE username = :username');
                        $stmt->execute(array(
                                ':username' => $_POST['username']
                        ));
                        $row = $stmt->fetch();

                        if (!empty($row['username'])) {
                                $error[] = 'Ce pseudo est déjà utilisé !';
                        }
                }

		$verif_box = $_REQUEST["captcha"];

		// on vérifie le captcha
		if(md5($verif_box).'a4xn' == $_COOKIE['tntcon']) {

                if(!isset($error)){
                        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);
			$pid=md5(uniqid(rand(),true));

                        try {
                                //On insert les données dans la table blog_members
                                $result1 = $db->prepare('INSERT INTO blog_members (username,password,email,pid,memberDate) VALUES (:username, :password, :email, :pid, :memberDate)') ;
                                $result1->execute(array(
                                        ':username' => $username,
                                        ':password' => $hashedpassword,
                                        ':email' => $email,
					':pid' => $pid,
					':memberDate' => date('Y-m-d H:i:s')
                                ));

				$newuid = $db->lastInsertId();

				//On insert aussi le PID et l'ID du membre dans la table xbt_users
				$result2 = $db->prepare('INSERT INTO xbt_users (uid, torrent_pass) VALUES (:uid, :torrent_pass)');
				$result2->execute(array(
					':uid' => $newuid,
					':torrent_pass' => $pid
				));

				if(!$result1 || !$result2)
                         	{
                              		$error[] = 'Erreur d\'accès à la base de données lors de la création du compte utilisateur.';
                         	}

                                //redirect to index page
                                header('Location: ../membres.php?action=ok');
                                exit;

                        } catch(PDOException $e) {
                            echo $e->getMessage();
                        }

                }

	} // captcha

	else {
    		$error[] = 'Mauvais code !';
	}

        }

        //check for any errors
        if(isset($error)){
                foreach($error as $error){
                        echo '<div class="alert-msg rnd8 error">'.$error.'</div>';
                }
        }
        ?>

        <form action='' method='post'>

                <p><label>Pseudo</label><br />
                <input type='text' name='username' id='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

                <p><label>Mot de passe</label> (6 caractères minimum)<br />
                <input type='password' name='password' id='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'><br />

		<!-- force du mot de passe -->
		<span style="font-weight: bold;" id='result'></span>

		</p>

                <p><label>Confirmation mot de passe</label><br />
                <input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

                <p><label>E-mail</label><br />
                <input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>

		<p><label>Code captcha</label><br />
   		<input type='text' name='captcha'>&nbsp;<img src="../verificationimage.php?<?php echo rand(0,9999);?>" alt="captcha" width="50" height="24" align="absbottom" />
		</p>

                <p><input type='submit' class="searchsubmit formbutton" name='submit' value='Créer un compte'></p>

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
