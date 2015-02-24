<?php
//include config
require_once('../includes/config.php');

//Si pas connecté en tant que mumbly, pas d'accès à la création de membre --> retour sur la page login
if(!$user->is_logged_in()) {
        header('Location: login.php');
}

elseif($_SESSION['userid'] != 1) {
        header('Location: '.SITEURL);
}

// titre de la page
$pagetitle= 'Admin : ajouter un membre';
require('../includes/header.php');
?>

<body>
<div id="container">
	<div id="header">
    		<h1><a href="<?php echo SITEURL; ?>"><?php echo SITESLOGAN; ?></a></h1>
        	<h2>Liberté, égalité, et le touti quanti</h2>
        	<div class="clear"></div>
    	</div>
    	<div id="nav">
    		<ul>
        		<li><a href="index.php">Accueil</a></li>
            	<li><a href="/admin">Admin</a></li>
            	<li><a href="#">Contact</a></li>
        	</ul>
    	</div>
    	<div id="body">
		<div id="content">
		
		        <?php include('menu.php');?>
				<p><a href="users.php">Membres Admin Index</a></p>

				<h2>Ajouter un membre</h2>
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

                if($passwordConfirm ==''){
                        $error[] = 'Veuillez confirmer le mot de passe.';
                }

                if($password != $passwordConfirm){
                        $error[] = 'Les mots de passe concordent pas.';
                }

                if($email ==''){
                        $error[] = 'Veuillez entrer une adresse e-mail.';
                }

                if(!isset($error)){

                        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

                        try {

                                //insert into database
                                $stmt = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username, :password, :email)') ;
                                $stmt->execute(array(
                                        ':username' => $username,
                                        ':password' => $hashedpassword,
                                        ':email' => $email
                                ));

                                //redirect to index page
                                header('Location: users.php?action=added');
                                exit;

                        } catch(PDOException $e) {
                            echo $e->getMessage();
                        }

                }

        }
        //check for any errors
        if(isset($error)){
                foreach($error as $error){
                        echo '<p class="error">'.$error.'</p>';
                }
        }
        ?>

        <form action='' method='post'>

                <p><label>Pseudo</label><br />
                <input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

                <p><label>Mot de passe</label><br />
                <input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

                <p><label>Confirmation mot de passe</label><br />
                <input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

                <p><label>E-mail</label><br />
                <input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>

                <p><input type='submit' class="searchsubmit formbutton" name='submit' value='Ajouter un membre'></p>

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
