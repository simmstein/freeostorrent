<?php

//Sessions
ob_start();
session_start();

// ----------------------------------------------------------------------------------
// PARAMETRES
// ----------------------------------------------------------------------------------

//Identifiants SQL
define('DBHOST','localhost');
define('DBUSER','xxxxxxxxxxxxxxxxxxxxxxxxx');
define('DBPASS','xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('DBNAME','xxxxxxxxxxxxxxxxxxxx');

//Connexion SQL
$db = new PDO("mysql:host=".DBHOST.";port=8889;dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//Paramètres pour le site
define('SITENAME','xxxxxxxxxxxxxxx'); // nom court du site EX : freeostorrent
define('SITENAMELONG','xxxxxxxxxxxxxxxxxxxxxx'); // nom long du site EX : freeostorrent.fr
define('SITESLOGAN','xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // Slogan du site
define('SITEDESCRIPTION','xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // DEscription du site
define('SITEURL','xxxxxxxxxxxxxxxxxxxxxxxxx'); // URL complete du site
define('SITEMAIL','xxxxxxxxxxxxxxxxxxxxxxxxx'); // E-mail de contact pour le site
define('SITEAUTHOR','xxxxxxxxxxxxxx'); // Auteur/webmaster
define('ANNOUNCEPORT','xxxxx'); // port d'announce de XBTT
define('SITEVERSION','xxxxxx'); // N° de version de votre site
define('SITEDATE','xxxxxxxxxxx'); // Date de la dernière mise à jour de votre site


//Deconnexion auto au bout de 15 minutes d'inactivité
if (isset($_SESSION['time']) && (time() - $_SESSION['time'] > 900)) {
	header ('Location: '.SITEURL.'/admin/logout.php');

}

// Announce
$ANNOUNCEURL = SITEURL.':'.ANNOUNCEPORT.'/announce';

// Répertoire des images
$REP_IMAGES = '/var/www/freeostorrent.fr/web/images/';

//set timezone
date_default_timezone_set('Europe/Paris');

// Edito - Page d'accueil
$EDITO = 'xxxxxxxxxxxxxxxxxxxxxxxxx'; // Edito en page d'accueil du site

//Paramètres pour le fichier torrent (upload.php)
define('MAX_SIZE', 100000); // Taille maxi en octets du fichier .torrent
define('WIDTH_MAX', 500); // Largeur max de l'image en pixels
define('HEIGHT_MAX', 500); // Hauteur max de l'image en pixels
$REP_TORRENTS = 'xxxxxxxxxxxx'; // Répertoire des fichiers .torrents

//Paramètres pour l'icone de présentation du torrent (index.php, edit-post.php, ...)
$WIDTH_MAX_ICON = 150; //largeur maxi de l'icone de présentation dut orrent
$HEIGHT_MAX_ICON = 150; //Hauteur maxi de l'icone de présentation du torrent
$MAX_SIZE_ICON = 30725; // Taille max en octet de l'icone de présentation du torrent (100 Ko)
$REP_IMAGES_TORRENTS = 'xxxxxxxxxxxx'; // Répertoire des images de torrents

//Paramètres pour l'avatar membre (profile.php, edit-profil.php, ...)
$MAX_SIZE_AVATAR = 51200; // Taille max en octets du fichier (50 Ko)
$WIDTH_MAX_AVATAR = 150; // Largeur max de l'image en pixels
$HEIGHT_MAX_AVATAR = 150; // Hauteur max de l'image en pixels
$REP_IMAGES_AVATARS = 'xxxxxxxxxxxxxxxxxxx'; // Répertoire des avatars utilisateurs
$EXTENSIONS_VALIDES = array( 'jpg' , 'jpeg' , 'png' );


// -----------------------------------------------------------------------------------
// CLASSES
// -----------------------------------------------------------------------------------

//load classes as needed
function __autoload($class) {
   
   $class = strtolower($class);

   //if call from within assets adjust the path
   $classpath = 'classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }  
   
   //if call from within admin adjust the path
   $classpath = '../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }
   
   //if call from within admin adjust the path
   $classpath = '../../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }     
    
}

$user = new User($db); 


// Fonctions torrents
include_once('functions.php');
include_once('BDecode.php');
include_once('BEncode.php');

?>
