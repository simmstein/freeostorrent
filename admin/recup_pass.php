<?php
require('../includes/config.php');

/*
1 - on récupère $_POST['email'] rentré dans le questionnaire
2 - on cherche à qui appartient cet e-mail dans la base sql
3 - si on trouve, on calcule un nouveau mot de passe
4 - on insert le nouveau mot de passe en sha1 dans la base sql
5 - on envoie un e-mail avec le mot de passe non codé
6 - on affiche un message de réussite
7 - si on NE TROUVE PAS l'e-mail
*/


// Une fois le formulaire envoyé
if(isset($_POST["recuperationpass"]))
{

	if(!empty($_POST['email'])) {
		$email = htmlentities($_POST['email']);
	}
	
	else {
		$error[] = 'veuillez renseigner votre adresse email.';
	}

	$stmt = $db->query("SELECT email FROM blog_members WHERE email = '".$email."' ");

	//si le nombre de lignes retourne par la requete != 1
	if ($stmt->rowCount() != 1) {
		$error[] = 'adresse e-mail inconnue.';
	}

	else {
		$row1 = $stmt->fetch();
		
		$retour = $db->query("SELECT password FROM blog_members WHERE email = '".$email."' ");
		$row2 = $retour->fetch();
		$new_password = fct_passwd(); //création d'un nouveau mot de passe
		$hashedpassword = $user->password_hash($new_password, PASSWORD_BCRYPT); // cryptage du password

		//On crée le mail
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: Freeostorrent.fr <webmaster@freeostorrent.fr>'."\r\n";
		//$headers .= '\r\n';

		$objet = 'Votre nouveau mot de passe sur '.SITEURL;

		$message = "Bonjour,<br/>\n";
		$message .= "Vous avez demandé un nouveau mot de passe pour votre compte sur " . SITEURL . ".<br/>\n";
		$message .= "Votre nouveau mot de passe est : " . $new_password . "<br/>\n\n";
		$message .= "Cordialement,<br/>\n\n";
		$message .= "L'equipe de " . SITENAME;

	if(!mail($row1['email'], $objet, $message, $headers)) {
		$error[] = "Problème lors de l'envoi du mail.";
	}

	else {
		//mise à jour de la base de données de l'utilisateur
		$stmt = $db->prepare('UPDATE blog_members SET password = :password WHERE email = :email') ;
        	$stmt->execute(array(
            		':password' => $hashedpassword,
            		':email' => $email
        	));
	
		$cok = "Un mail contenant votre nouveau mot de passe vous a été envoyé.<br/>Veuillez le consulter avant de vous reconnecter sur " . SITEURL.'.';
	}

	}
	
}

$pagetitle = 'Demande de nouveau mot de passe';
require('../includes/header.php');
?>

<div id="container">

	<?php
	   require('../includes/header-logo.php');
	   require('../includes/nav.php');
	?>

    	<div id="body">
		<div id="content">
           <h2><span>Vous avez oublié votre mot de passe ?</span></h2>
           

	<!-- formulaire -->
	<form action='' method='post'>
		<p class="edito">
			Vous allez faire une demande de nouveau mot de passe. Ce nouveau mot de passe vous sera envoyé par e-mail.<br/>
			Une fois connecté avec vos identifiants, vous pourrez éventuellement redéfinir un mot de passe à partir de votre page profil.<br/>
			Veuillez donc entrer ci-dessous l'adresse e-mail correspondant à votre compte :
		</p>
	
		<p>
			Entrez l'adresse e-mail de votre compte : <input type="text" name="email" />
		</p>
     
		<p>
        <input type="submit" name="recuperationpass" class="searchsubmit formbutton" value="Envoyer" />  <input type="reset" value="Annuler" />
		</p>
	</form>	
	
	
	<?php
	   //check for any errors
        if(isset($error)){
                foreach($error as $error){
                        echo '<p class="error">ERREUR : '.$error.'</p>';
                }
        }
		elseif (isset($cok)) {
			echo '<h4 style="color: green;">' . $cok . '</h4>';
		}
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
