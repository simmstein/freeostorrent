<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR">
<head>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
   <meta http-equiv="content-language" content="fr-FR" />
   <link rel="alternate" hreflang="fr" href="http://www.freeostorrent.fr/" />
   <title><?php echo SITESLOGAN; ?> - <?php echo $pagetitle; ?></title>
   <meta name="language" content="fr-FR" />
   <meta name="robots" content="all" />
   <meta name="description" content="<?php echo SITEDESCRIPTION; ?>" />
   <link rel="icon" href="favicon.ico" />
   <link rel="author" href="mailto:webmaster@freeostorrent.fr" xml:lang="fr-FR" title="Olivier Prieur" />
   <link rel="stylesheet" href="<?php echo SITEURL; ?>/style/normalize.css">
   <link rel="stylesheet" href="<?php echo SITEURL; ?>/style/main.css">
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
   <script language="javascript" src="<?php echo SITEURL; ?>/js/passwd.js"></script>
   <script language="javascript">
	jQuery(document).ready(function() {
		$('#username').keyup(function(){$('#result').html(passwordStrength($('#password').val(),$('#username').val()))})
		$('#password').keyup(function(){$('#result').html(passwordStrength($('#password').val(),$('#username').val()))})
	})
	function showMore()
	{
		$('#more').slideDown()
	}
   </script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/js/tinymce/tinymce.min.js"></script>
   <script type="text/javascript">
          tinymce.init({
      		mode : "textareas",
		language : "fr_FR",
              	plugins: [
                  "advlist autolink lists link print preview image",
                  "searchreplace visualblocks code",
                  "contextmenu smileys paste"
              	],
              	toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link smileys code image"
          });
    </script>

    <!-- DELETE -->
    <script language="JavaScript" type="text/javascript">
	function delpost(id, title) {
		if (confirm("Etes-vous sur de vouloir supprimer '" + title + "'")) {
			window.location.href = 'index.php?delpost=' + id;
		}
	}
    </script>
    
    <script language="JavaScript" type="text/javascript">
	function delcat(id, title) {
		if (confirm("Etes-vous sur de vouloir suprimer '" + title + "'")) {
			window.location.href = 'categories.php?delcat=' + id;
		}
	}
    </script>

    <script language="JavaScript" type="text/javascript">
	function dellicence(id, title) {
		if (confirm("Etes-vous sur de vouloir suprimer '" + title + "'")) {
			window.location.href = 'licences.php?dellicence=' + id;
		}
	}
    </script>

    <script language="JavaScript" type="text/javascript">
	function deluser(id, title) {
		if (confirm("Etes-vous sur de vouloir supprimer '" + title + "'")) {
			window.location.href = 'users.php?deluser=' + id + '&delname=' + title;
		}
	}
    </script>


    <!-- EDIT -->
    <script language="JavaScript" type="text/javascript">
	function delimage(id, title) {
		if (confirm("Etes-vous sur de vouloir supprimer '" + title + "'")) {
			window.location.href = 'edit-post.php?delimage=' + id;
		}
	}
    </script>

</head>
