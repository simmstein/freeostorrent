<?php  
define("_BBCLONE_DIR", "/var/www/freeostorrent.fr/web/stats/");  
define("COUNTER", _BBCLONE_DIR."mark_page.php");  
if (is_readable(COUNTER)) include_once(COUNTER);  
?>
