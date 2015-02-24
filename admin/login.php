<?php
require('../includes/config.php');

if($user->is_logged_in()) {
   header('Location: ../index.php');
}

$pagetitle= 'Connectez-vous !';
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
		//process login form if submitted
		if(isset($_POST['submit'])){

                $username = htmlentities(trim($_POST['username']));
                $password = htmlentities(trim($_POST['password']));

                if($user->login($username,$password)) {
                	//logged in return to index page
                	header('Location: ../index.php');
                	exit;

                }

		else {
                	$message = '<div class="alert-msg rnd8 error">Mauvais identifiants.</div>';
                }

		}//end if submit

		if(isset($message)) {
			echo $message;
		}

	?>

			<form action="" method="post">
			   <p><label>Pseudo</label> : <input type="text" name="username" value=""  /></p>
			   <p><label>Mot de passe</label> : <input type="password" name="password" value=""  /></p>
			   <p><label></label><input type="submit" class="searchsubmit formbutton" name="submit" value="Connexion"  /></p>
			</form>
			
			<div style="text-align: right;"><a href="recup_pass.php">Mot de passe oublié ?</a></div>

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
