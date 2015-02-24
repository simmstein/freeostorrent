<?php
//include config
require_once('../includes/config.php');

//Si pas connecté OU si le membre n'est pas mumbly, pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in() || $_SESSION['username'] != 'mumbly') {
        header('Location: login.php');
}
//show message from add / edit page
if(isset($_GET['dellicence'])){

        $stmt = $db->prepare('DELETE FROM blog_licences WHERE licenceID = :licenceID') ;
        $stmt->execute(array(':licenceID' => $_GET['dellicence']));

        header('Location: licences.php?action=deleted');
        exit;
}

// titre de la page
$pagetitle= 'Admin : gestion des licences';
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
                echo '<h3>Licence '.$_GET['action'].'.</h3>';
        }
        ?>

        <table>
        <tr>
                <th>Titre</th>
                <th>Action</th>
        </tr>
        <?php
                try {
                        $stmt = $db->query('SELECT licenceID, licenceTitle, licenceSlug FROM blog_licences ORDER BY licenceTitle ASC');
                        while($row = $stmt->fetch()){

                                echo '<tr>';
                                echo '<td style="width: 80%;">'.$row['licenceTitle'].'</td>';
                                ?>

                                <td>
                                        <a style="text-decoration: none;" href="edit-licence.php?id=<?php echo $row['licenceID'];?>"><input type="button" class="button" value="Edit."</a> |
                                        <a style="text-decoration: none;" href="javascript:dellicence('<?php echo $row['licenceID'];?>','<?php echo $row['licenceSlug'];?>')"><input type="button" class="button" value="Suppr."</a>
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
	<a href="add-licence.php" style="text-decoration: none;"><input type="button" class="button" value="Ajouter une licence" /></a>
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
