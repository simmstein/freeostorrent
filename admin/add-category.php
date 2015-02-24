<?php
require('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in()) { 
	header('Location: login.php');
}

elseif($_SESSION['userid'] != 1) {
        header('Location: '.SITEURL);
}

// titre de la page
$pagetitle = 'Admin : ajouter une catégorie';
require('../includes/header.php');
?>


<body>

<div id="container">
	<div id="header">
    		<h1><a href="<?php echo SITEURL; ?>"><?php echo SITESLOGAN; ?></a></h1>
        	<h2>Liberté, égalité, et le touti quanti</h2>
        	<div class="clear"></div>
    </div>

    <?php require('../includes/nav.php'); ?>

</div>

<div id="body">

		<div id="content">
			<?php include('menu.php');?>

			<p><a href="categories.php">Categories Index</a></p>
			<h2>Ajouter une catégorie</h2>

			<?php
			//if form has been submitted process it
			if(isset($_POST['submit'])){

			$_POST = array_map( 'stripslashes', $_POST );

			//collect form data
			extract($_POST);

			//very basic validation
			if($catTitle ==''){
				$error[] = 'Veuillez entrer un nom de catégorie.';
			}

			if(!isset($error)){

			try {

				$catSlug = slug($catTitle);

				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_cats (catTitle,catSlug) VALUES (:catTitle, :catSlug)') ;
				$stmt->execute(array(
					':catTitle' => $catTitle,
					':catSlug' => $catSlug
				));

				//redirect to index page
				header('Location: categories.php?action=added');
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

	<p><label>Titre</label><br />
	<input type='text' name='catTitle' value='<?php if(isset($error)){ echo htmlentities($_POST['catTitle']); } ?>'></p>

	<p><input type='submit' name='submit' class="searchsubmit formbutton" value='Ajouter la catégorie'></p>

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
