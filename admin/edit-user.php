<?php
//include config
require_once('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in() || $_SESSION['username'] != 'mumbly') {
        header('Location: login.php');
}

// titre de la page
$pagetitle = 'Admin : édition du profil de '.$_SESSION['username'];
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
        <p><a href="users.php">Liste des membres</a></p>

        <h2>Edition du profil membre</h2>


        <?php

        //if form has been submitted process it
        if(isset($_POST['submit'])){

                //collect form data
                extract($_POST);

                //very basic validation
                if($username ==''){
                        $error[] = 'Veuillez entrer un pseudo.';
                }

                if( strlen($password) > 0){

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


                if($email ==''){
                        $error[] = 'Veuillez entrer une adresse e-mail.';
                }
                if(!isset($error)){

                        try {

                                if(isset($password)){

                                        $hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

                                        //update into database
                                        $stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
                                        $stmt->execute(array(
                                                ':username' => $username,
                                                ':password' => $hashedpassword,
                                                ':email' => $email,
                                                ':memberID' => $memberID
                                        ));


                                } else {

                                        //update database
                                        $stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID') ;
                                        $stmt->execute(array(
                                                ':username' => $username,
                                                ':email' => $email,
                                                ':memberID' => $memberID
                                        ));

                                }


                                //redirect to index page
                                header('Location: users.php?action=updated');
                                exit;

                        } catch(PDOException $e) {
                            echo $e->getMessage();
                        }

                }

        }

        ?>
        <?php
        //check for any errors
        if(isset($error)){
                foreach($error as $error){
                        echo $error.'<br />';
                }
        }

                try {

                        $stmt = $db->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID = :memberID') ;
                        $stmt->execute(array(':memberID' => $_GET['id']));
                        $row = $stmt->fetch();

                } catch(PDOException $e) {
                    echo $e->getMessage();
                }

        ?>

        <form action='' method='post'>
                <input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>'>

                <p><label>Pseudo</label><br />
                <input type='text' name='username' value='<?php echo $row['username'];?>'></p>

                <p><label>Mot de passe (seulement en cas de changement)</label><br />
                <input type='password' name='password' value=''></p>

                <p><label>Confirmez le mot de passe</label><br />
                <input type='password' name='passwordConfirm' value=''></p>

                <p><label>E-mail</label><br />
                <input type='text' name='email' value='<?php echo $row['email'];?>'></p>

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
