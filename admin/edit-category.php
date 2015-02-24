<?php
//include config
require_once('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in()) {
        header('Location: login.php');
}

elseif($_SESSION['userid'] != 1) {
	header('Location: '.SITEURL);
}

// titre de la page
$pagetitle= 'Admin : Edition des catégories';
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
				<p><a href="categories.php">Catégories Index</a></p>

        <h2>Edition de la catégorie</h2>
		
		        <?php

        //if form has been submitted process it
        if(isset($_POST['submit'])){

                $_POST = array_map( 'stripslashes', $_POST );

                //collect form data
                extract($_POST);

                //very basic validation
                if($catID ==''){
                        $error[] = 'Ce post possède un ID invalide !.';
                }

                if($catTitle ==''){
                        $error[] = 'Veuillez entrer un titre.';
                }

                if(!isset($error)){

                        try {

                                $catSlug = slug($catTitle);

                                //insert into database
                                $stmt = $db->prepare('UPDATE blog_cats SET catTitle = :catTitle, catSlug = :catSlug WHERE catID = :catID') ;
                                $stmt->execute(array(
                                        ':catTitle' => $catTitle,
                                        ':catSlug' => $catSlug,
                                        ':catID' => $catID
                                ));

                                //redirect to index page
                                header('Location: categories.php?action=updated');
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

                        $stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catID = :catID') ;
                        $stmt->execute(array(':catID' => $_GET['id']));
                        $row = $stmt->fetch();

                } catch(PDOException $e) {
                    echo $e->getMessage();
                }

        ?>

        <form action='' method='post'>
                <input type='hidden' name='catID' value='<?php echo $row['catID'];?>'>

                <p><label>Titre</label><br />
                <input type='text' name='catTitle' value='<?php echo $row['catTitle'];?>'></p>

                <p><input type='submit' class="searchsubmit formbutton" name='submit' value='Mettre à jour'></p>

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
