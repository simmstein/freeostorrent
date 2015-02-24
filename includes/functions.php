<?php
function slug($text){ 

  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;
}

function makesize($bytes) {
  if (abs($bytes) < 1000 * 1024)
    return number_format($bytes / 1024, 2) . " Ko";
  if (abs($bytes) < 1000 * 1048576)
    return number_format($bytes / 1048576, 2) . " Mo";
  if (abs($bytes) < 1000 * 1073741824)
    return number_format($bytes / 1073741824, 2) . " Go";
    return number_format($bytes / 1099511627776, 2) . " To";
}

function get_elapsed_time($ts)
{
  $mins = floor((time() - $ts) / 60);
  $hours = floor($mins / 60);
  $mins -= $hours * 60;
  $days = floor($hours / 24);
  $hours -= $days * 24;
  $weeks = floor($days / 7);
  $days -= $weeks * 7;
  $t = "";
  if ($weeks > 0)
    return "$weeks semaine" . ($weeks > 1 ? "s" : "");
  if ($days > 0)
    return "$days jour" . ($days > 1 ? "s" : "");
  if ($hours > 0)
    return "$hours heure" . ($hours > 1 ? "s" : "");
  if ($mins > 0)
    return "$mins min" . ($mins > 1 ? "s" : "");
  return "< 1 min";
}


function buildTreeArray($files)
{
    $ret = array();

    foreach ($files as $k => $v)
    {
        $filename=$v['filename'];

        $parts = preg_split('/\//', $filename, -1, PREG_SPLIT_NO_EMPTY);
        $leaf = array_pop($parts);

        // build parent structure
        $parent = &$ret;
        foreach ($parts as $part)
        {
                $parent = &$parent[$part];
        }

        if (empty($parent[$leaf]))
        {
                $v['filename']=$leaf;
                $parent[$leaf] = $v;
        }
    }

    return $ret;
}


function outputTree($files, $indent=1)
{
    echo "<table style=\"font-size: 7pt; width: 100%;\"";

    foreach($files as $k=>$v)
    {
        $entry=isset($v['filename']) ? $v['filename'] : $k;
        $size=$v['size'];

        if($indent==0)
        {
            // root
            $is_folder=true;
        }
        elseif(is_array($v) && (!array_key_exists('filename',$v) && !array_key_exists('size',$v)))
        {
            // normal node
            $is_folder=true;
        }
        else
        {
            // leaf node, i.e. a file
        $is_folder=false;
        }

        if($is_folder)
        {
            // we could output a folder icon here
        }
        else
        {
            // we could output an appropriate icon
            // based on file extension here
            $ext=pathinfo($entry,PATHINFO_EXTENSION);
        }

        echo "<tr><td style=\"border: 1px solid #D2D2D2;\">";
        echo $entry; // output folder name or filename

        if(!$is_folder)
        {
            // if it’s not a folder, show file size
            echo " (".makesize($size).")";
        }

        echo "</td></tr>";
 
        if(is_array($v) && $is_folder)
        {
            outputTree($v, ($indent+1));
        }
    }

    echo "</table>";
}


function unesc($x) {
    if (get_magic_quotes_gpc())
        return stripslashes($x);
    return $x;
}

/*
function benc($str) //bencoding
{
  if (is_string($str)) { //string
    return strlen($str) . ':' . $str;
  }
  
  if (is_numeric($str)) { //integer
    return 'i' . $str . 'e';
  }
  
  if (is_array($str)) {
    $ret_str = ''; //the return string
    
    $k = key($str); //we check the 1st key, if the key is 0 then is a list if not a dictionary
    foreach($str as $var => $val) {
      if ($k) { //is dictionary
        $ret_str .= benc($var); //bencode the var
      }
      $ret_str .= benc($val); //we recursivly bencode the contents
    }
    
    if ($k) { //is dictionary
      return 'd' . $ret_str . 'e';
    }
    
    return 'l' . $ret_str . 'e';
  }
}

function bdec_file($f, $ms) 
{
	$fp = fopen($f, "rb");
	if (!$fp)
		return;
	$e = fread($fp, $ms);
	fclose($fp);
	return bdec($e);
}

function bdec($str, &$_len = 0) //bdecoding
{
  $type = substr($str, 0, 1);
  
  if (is_numeric($type)) {
    $type = 's';
  }
  
  switch ($type) {
    case 'i': //integer
      $p = strpos($str, 'e');
      $_len = $p + 1; //lenght of bencoded data
      return intval(substr($str, 1, $p - 1));
    break;
  
    case 's': //string
      $p = strpos($str, ':');
      $len = substr($str, 0, $p);
      $_len = $len + $p + 1; //lenght of bencoded data
      return substr($str, $p + 1, $len);
    break;
    
    case 'l': //list
      $l = 1;
      $ret_array = array();
      while (substr($str, $l, 1) != 'e') {
        $ret_array[] = bdec(substr($str, $l), $len);
        $l += $len;
      }
      $_len = $l + 1; //lenght of bencoded data
      return $ret_array;
    break;
    
    case 'd': //dictionary
      $l = 1;
      $ret_array = array();
      while (substr($str, $l, 1) != 'e') {
        $var = bdec(substr($str, $l), $len);
        $l += $len;
        
        $ret_array[$var] = bdec(substr($str, $l), $len);
        $l += $len;
      }
      $_len = $l + 1; //lenght of bencoded data
      return $ret_array;
    break;
  }
}
*/


function date_fr($format, $timestamp=false) {
	if ( !$timestamp ) $date_en = date($format);
	else               $date_en = date($format,$timestamp);

	$texte_en = array(
		"Monday", "Tuesday", "Wednesday", "Thursday",
		"Friday", "Saturday", "Sunday", "January",
		"February", "March", "April", "May",
		"June", "July", "August", "September",
		"October", "November", "December"
	);
	$texte_fr = array(
		"Lundi", "Mardi", "Mercredi", "Jeudi",
		"Vendredi", "Samedi", "Dimanche", "Janvier",
		"F&eacute;vrier", "Mars", "Avril", "Mai",
		"Juin", "Juillet", "Ao&ucirc;t", "Septembre",
		"Octobre", "Novembre", "D&eacute;cembre"
	);
	$date_fr = str_replace($texte_en, $texte_fr, $date_en);

	$texte_en = array(
		"Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun",
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
		"Aug", "Sep", "Oct", "Nov", "Dec"
	);
	$texte_fr = array(
		"Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim",
		"Jan", "F&eacute;v", "Mar", "Avr", "Mai", "Jui",
		"Jui", "Ao&ucirc;", "Sep", "Oct", "Nov", "D&eacute;c"
	);
	$date_fr = str_replace($texte_en, $texte_fr, $date_fr);

	return $date_fr;
}


// ---------------------------------------------------------------------
//  Générer un mot de passe aléatoire
// ---------------------------------------------------------------------
function fct_passwd( $chrs = "")
{
   if( $chrs == "" ) $chrs = 10;
   $chaine = "";

   $list = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz!=$";
   mt_srand((double)microtime()*1000000);
   $newstring="";

   while( strlen( $newstring )< $chrs ) {
   $newstring .= $list[mt_rand(0, strlen($list)-1)];
   }
   return $newstring;
 }


function get_extension($nom) {
    $nom = explode(".", $nom);
    $nb = count($nom);
    return strtolower($nom[$nb-1]);
}


function breadcrumbs($separator = ' / ', $home = 'Accueil') {
    
    $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    $base_url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $breadcrumbs = array("<a href=\"$base_url\">$home</a>");
 
    $last = end(array_keys($path));
 
    foreach ($path AS $x => $crumb) {
        $title = ucwords(str_replace(array('.php', '_'), Array('', ' '), $crumb));
        if ($x != $last){
            $breadcrumbs[] = '<a href="$base_url$crumb">$title</a>';
        }else{
            $breadcrumbs[] = $title;
        }
    }
 
    return implode($separator, $breadcrumbs);
}


function detect_city($ip) {

        $default = 'UNKNOWN';

        if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
            $ip = '8.8.8.8';

        $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

        $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
        $ch = curl_init();

        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION  => 1,
            CURLOPT_HEADER      => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_USERAGENT   => $curlopt_useragent,
            CURLOPT_URL       => $url,
            CURLOPT_TIMEOUT         => 1,
            CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
        );

        curl_setopt_array($ch, $curl_opt);

        $content = curl_exec($ch);

        if (!is_null($curl_info)) {
            $curl_info = curl_getinfo($ch);
        }

        curl_close($ch);

        if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
            $city = $regs[1];
        }
        if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
            $state = $regs[1];
        }

        if( $city!='' && $state!='' ){
          $location = $city . ', ' . $state;
          return $location;
        }else{
          return $default;
        }

    }


?>
