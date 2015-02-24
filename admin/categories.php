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

//show message from add / edit page
if(isset($_GET['delcat'])){

        $stmt = $db->prepare('DELETE FROM blog_cats WHERE catID = :catID') ;
        $stmt->execute(array(':catID' => $_GET['delcat']));

        header('Location: categories.php?action=deleted');
        exit;
}

// titre de la page
$pagetitle= 'Admin : gestion des catégories';
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
        //show message from add / edit page
        if(isset($_GET['action'])){
                echo '<h3>Catégorie '.$_GET['action'].'.</h3>';
        }
        ?>

        <table>
        <tr>
                <th>Titre</th>
                <th>Action</th>
        </tr>
        <?php
                try {
                        $stmt = $db->query('SELECT catID, catTitle, catSlug FROM blog_cats ORDER BY catTitle ASC');
                        while($row = $stmt->fetch()){

                                echo '<tr>';
                                echo '<td style="width: 80%;">'.$row['catTitle'].'</td>';
                                ?>

                                <td>
                                        <a style="text-decoration: none;" href="edit-category.php?id=<?php echo $row['catID'];?>"><input type="button" class="button" value="Edit."></a> |
                                        <a style="text-decoration: none;" href="javascript:delcat('<?php echo $row['catID'];?>','<?php echo $row['catSlug'];?>')"><input type="button" class="button" value="Suppr."</a>
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
	<a href="add-category.php" style="text-decoration: none;"><input type="button" class="button" value="Ajouter une catégorie" /></a>
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
