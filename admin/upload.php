<?php
require('../includes/config.php');

//Si pas connecté pas de connexion à l'espace d'admin --> retour sur la page login
if(!$user->is_logged_in()) {
        header('Location: login.php');
}

// titre de la page
$pagetitle= 'Ajouter un torrent';
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
//Si le formulaire a été soumis = GO !
if(isset($_POST['submit'])) {


	//Collecte des données ...
	//extract($_POST);

	// *****************************************
	// upload image torrent
	// *****************************************

	$image_torrent = $_FILES['imagetorrent']['name'];

	//si erreur de transfert
	if ($_FILES['imagetorrent']['error'] > 0) {
		$error[] = "Erreur lors du transfert";
	}

	// taille de l'image
	if ($_FILES['imagetorrent']['size'] > MAX_SIZE) {
		$error = "L'image est trop grosse.";
	}

	$extensions_valides = array( 'jpg' , 'png' );
	//1. strrchr renvoie l'extension avec le point (« . »).
	//2. substr(chaine,1) ignore le premier caractère de chaine.
	//3. strtolower met l'extension en minuscules.
	$extension_upload = strtolower(  substr(  strrchr($_FILES['imagetorrent']['name'], '.')  ,1)  );

	if(!in_array($extension_upload,$extensions_valides)) {
		$error[] = "Extension d'image incorrecte (.png ou .jpg seulement !)";
	}

	$image_sizes = getimagesize($_FILES['imagetorrent']['tmp_name']);
	if ($image_sizes[0] > WIDTH_MAX OR $image_sizes[1] > HEIGHT_MAX) {
		$error = "Image trop grande (dimensions)";
	}

	// on upload l'image
	if(!move_uploaded_file($_FILES['imagetorrent']['tmp_name'], $REP_IMAGES_TORRENTS.$_FILES['imagetorrent']['name'])) {
		$error[] = 'Problème de téléchargement de l\'image.';
	}

	// ***************************************
	// fin image torrent upload
	// ***************************************


	// ***************************************
	// upload fichier torrent
	// ***************************************

	// si il y a bien un fichier .torrent, on poursuit ...
	if (!isset($_FILES["torrent"]) && empty($torrent)) {
        	$error[] = 'Veuillez choisir un fichier .torrent';
	}

	else {
		//Collecte des données ...
                extract($_POST);

		$type_file = $_FILES['torrent']['type'];
                $tmp_file = $_FILES['torrent']['tmp_name'];
                $name_file = $_FILES['torrent']['name'];


		$fd = fopen($_FILES["torrent"]["tmp_name"], "rb");
			
		$length=filesize($_FILES["torrent"]["tmp_name"]);
		if ($length) {
			$alltorrent = fread($fd, $length);
		}

		$array = BDecode($alltorrent);
			
		$hash = sha1(BEncode($array["info"]));
			
		fclose($fd);

		if (isset($array["info"]) && $array["info"]) {
			$upfile=$array["info"];
		}
		else {
			$upfile = 0;
		}

		if (isset($upfile["length"])) {
			$size = (float)($upfile["length"]);
		}
		else if (isset($upfile["files"])) {
		// multifiles torrent
			$size=0;
			foreach ($upfile["files"] as $file) {
				$size+=(float)($file["length"]);
                	}
		}
		else {
			$size = "0";
		}

		$announce=trim($array["announce"]);


		// on vérifie si le torrent existe déjà : on compare les champs info_hash
		//$stmt = $db->query("SELECT * FROM xbt_files WHERE LOWER(hex('info_hash')) = '".$hash."'");
		$stmt = $db->query("SELECT * FROM xbt_files WHERE info_hash = 0x$hash");
		$exists = $stmt->fetch();
		if(!empty($exists)) {
        		$error[] = "Ce torrent existe déjà dans la base.";
		}

		// on vérifie l'url d'announce	
		if($array['announce'] != $ANNOUNCEURL) {
        		$error[] = 'Vous n\'avez pas fournit la bonne adresse d\'announce dans votre torrent : l\'url d\'announce doit etre '.$ANNOUNCEURL;
		}


		// si le nom du torrent n'a pas été fournit (facultatif), on récupère le nom public du fichier
    		if (empty($_POST['postTitle']))
    		{
    			// on calcule le nom du fichier SANS .torrent à la fin
    			$file = $_FILES['torrent']['name'];
    			$var = explode(".",$file);
    			$nb = count($var)-1;
    			$postTitle = substr($file, 0, strlen($file)-strlen($var[$nb])-1);
    		}
    		else
    		{
    			// sinon on prend le nom fournit dans le formulaire d'upload
    			$postTitle = $_POST['postTitle'];
    		}

		// on vérifie la taille du fichier .torrent
		if ($_FILES['torrent']['size'] > $MAX_FILE_SIZE){
			$error[] = 'Le fichier .torrent est trop gros. Etes-vous certain qu\'il s\'agisse d\'un fichier .torrent ?';
		}

		if(!strstr($type_file, 'torrent')){
        		$error[] = 'Le fichier n\'est pas un fichier .torrent !';
    		}	


		/*
                if($postTitle ==''){
                       	$error[] = 'Veuillez entrer un titre.';
                }
		*/

                if($postDesc ==''){
                       	$error[] = 'Veuillez entrer une courte description.';
                }

                if($postCont ==''){
                       	$error[] = 'Veuillez entrer un contenu.';
                }

		if($catID ==''){
                       	$error[] = 'Veuillez choisir une catégorie.';
                }

		if($licenceID ==''){
                       	$error[] = 'Veuillez choisir une licence.';
                }

		}// fin if (isset($_FILES["torrent"]))



		// s'il n'y a pas d'erreur on y va !!!
                if(!isset($error)) {
		
		// on upload le fichier .torrent
		if(!move_uploaded_file($_FILES['torrent']['tmp_name'], $REP_TORRENTS.$_FILES['torrent']['name'])) {
			$error[] = 'Problème lors de l\'upload du fichier .torrent';
		}

// ***************************************
// fin upload fichier torrent
// ***************************************
                        try {

                                $postSlug = slug($postTitle);
                                $postAuthor = $_SESSION['username'];

                                //On insert les données dans la table blog_posts_seo
                                $stmt = $db->prepare('INSERT INTO blog_posts_seo (postTitle,postAuthor,postSlug,postDesc,postCont,postTaille,postDate,postTorrent,postImage) VALUES (:postTitle, :postAuthor, :postSlug, :postDesc, :postCont, :postTaille, :postDate, :postTorrent, :postImage)') ;
                                $stmt->execute(array(
                                        ':postTitle' => $postTitle,
                                        ':postAuthor' => $postAuthor,
                                        ':postSlug' => $postSlug,
                                        ':postDesc' => $postDesc,
                                        ':postCont' => $postCont,
					':postTaille' => $size,
                                        ':postDate' => date('Y-m-d H:i:s'),
					':postTorrent' => $name_file,
					':postImage' => $image_torrent
                                ));

                                $postID = $db->lastInsertId();

				//On insert les données dans la table xbt_files également
				$stmt2 = $db->query("INSERT INTO xbt_files SET info_hash=0x$hash, ctime=UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE flags=0");
				//$stmt2 = $db->query("INSERT INTO xbt_files (info_hash,mtime,ctime) VALUES(X'".$info['info_hash']."',UNIX_TIMESTAMP(),UNIX_TIMESTAMP())");

                                //On ajoute les données dans la table categories
                                if(is_array($catID)){
                                        foreach($_POST['catID'] as $catID){
                                                $stmt = $db->prepare('INSERT INTO blog_post_cats (postID,catID)VALUES(:postID,:catID)');
                                                $stmt->execute(array(
                                                        ':postID' => $postID,
                                                        ':catID' => $catID
                                                ));
                                        }
                                }


                                //On ajoute les données dans la table licences
                                if(is_array($licenceID)){
                                        foreach($_POST['licenceID'] as $licenceID){
                                                $stmt = $db->prepare('INSERT INTO blog_post_licences (postID_BPL,licenceID_BPL)VALUES(:postID_BPL,:licenceID_BPL)');
                                                $stmt->execute(array(
                                                        ':postID_BPL' => $postID,
                                                        ':licenceID_BPL' => $licenceID
                                                ));
                                        }
                                }



                                //On redirige vers la page torrents = action = ok
                                header('Location: '.SITEURL.'/torrents.php?action=ok');
                                exit;

                        } catch(PDOException $e) {
                            echo $e->getMessage();
                        }

                }

        }

        //S'il y a des erreurs, on les affiche
        if(isset($error)){
                foreach($error as $error){
                        echo '<div class="alert-msg rnd8 error">ERREUR : '.$error.'</div>';
                }
        }
        ?>

	<!-- DEBUT du formulaire -->

	<h2>Ajouter un torrent</h2>
	<h4 class="edito">URL d'annonce : <?php echo $ANNOUNCEURL; ?></h4>

	<br />

        <form action='' method='post' enctype='multipart/form-data'>

		<p><label style="font-weight: bold;">Fichier .torrent</label><br />
		<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
		<input type='file' name='torrent'>

		<p><label style="font-weight: bold;">Titre (facultatif)</label><br />
                <input type='text' size="35" name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

                <p><label style="font-weight: bold;">Courte description (Résumé de quelques lignes, sans image)</label><br />
                <textarea name='postDesc' cols='60' rows='5'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

                <p><label style="font-weight: bold;">Contenu</label><br />
                <textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><label style="font-weight: bold;">Icone d'illustration (page accueil et article)<br />
		<span style="font-style: italic;">PNG ou JPG seulement | max. <?php echo makesize($MAX_SIZE_ICON); ?> | max. <?php echo $WIDTH_MAX_ICON; ?>px X <?php echo $HEIGHT_MAX_ICON; ?>px</span></label><br />
                <input type='file' name='imagetorrent'></p>

                <fieldset>
                        <legend>Catégories</legend>

                        <?php
                        $stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
                        while($row2 = $stmt2->fetch()){

				/*
                        	if(isset($_POST['catID'])){
                                	if(in_array($row2['catID'], $_POST['catID'])){
                        			$checked="checked='checked'";
                    			}
					else {
                       				$checked = null;
                    			}
                                }
				*/

                        	echo "<input type='radio' name='catID[]' value='".$row2['catID']."'> ".$row2['catTitle']."<br />";
                        }
                        ?>

                </fieldset>

		<br />
                <fieldset>
                        <legend>Licences</legend>

                        <?php
                        $stmt3 = $db->query('SELECT licenceID, licenceTitle FROM blog_licences ORDER BY licenceTitle');
                        while($row3 = $stmt3->fetch()){

				/*
                                if(isset($_POST['licenceID'])){

                                        if(in_array($row3['licenceID'], $_POST['licenceID'])){
                       				$checked="checked='checked'";
                    			} 
					else {
                       				$checked = null;
                    			}
                                }
				*/

                        	echo "<input type='checkbox' name='licenceID[]' value='".$row3['licenceID']."'> ".$row3['licenceTitle']."<br />";
                        }

                        ?>

                </fieldset>

                <p><input type='submit' class="searchsubmit formbutton" name='submit' value='Ajouter'></p>

        </form>

	<!-- FIN du formulaire -->

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
