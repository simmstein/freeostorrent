<?php
require('../includes/config.php');

//if not logged in redirect to login page
//if(!$user->is_logged_in()){ header('Location: login.php'); }

//on détermine l'id du fichier
$fid = htmlentities($_GET['id']);

//on détermine l'id du membre
$stmt = $db->prepare('SELECT * FROM blog_members WHERE username = :username');
$stmt->execute(array(':username' => $_SESSION['username']));
$row = $stmt->fetch();

if(empty($row['memberID'])) {
	$uid = 32; // Si pas de $_SESSION, ID du compte visiteur
}
else {
	$uid = $row['memberID'];
}

/*
//on recherche le hash dans la base xbt_files
$stmt1 = $db->prepare('SELECT * FROM xbt_files WHERE fid = :fid');
$stmt1->execute(array(':fid' => $fid));
$row1 = $stmt1->fetch();
*/

//on recherche le torrent dans la base blog_posts_seo
$stmt2 = $db->prepare('SELECT * FROM blog_posts_seo WHERE postID = :postID');
$stmt2->execute(array(':postID' => $fid));
$row2 = $stmt2->fetch();

$torrent = $row2['postTorrent'];

$torrentfile = $REP_TORRENTS.'/'.$torrent;

// On décode le fichier torrent
$fd = fopen($torrentfile, "rb");
$alltorrent = fread($fd, filesize($torrentfile));
$array = BDecode($alltorrent);
fclose($fd);

//On cherche le pid
$stmt3 = $db->prepare('SELECT * FROM blog_members WHERE memberID = :uid');
$stmt3->execute(array(':uid' => $uid));
$row3 = $stmt3->fetch();

if ($row3['pid'] == '00000000000000000000000000000000') {
	$pid = '00000000000000000000000000000000';
}
else {
	$pid = $row3['pid'];
}

// On construit la nouvelle announce avec le pid (passkey ...)
$array["announce"] = SITEURL.":".ANNOUNCEPORT."/".$pid."/announce";
$alltorrent=BEncode($array);

// On construit le header
header("Content-Type: application/x-bittorrent");
header('Content-Disposition: attachment; filename="['.SITENAMELONG.']'.$torrent.'"');
print($alltorrent);

?>
