<?php
require_once('includes/config.php');

$pagetitle = 'Liste des membres';

require('includes/header.php');

define("_BBC_PAGE_NAME", $pagetitle);
define("_BBCLONE_DIR", "stats/");
define("COUNTER", _BBCLONE_DIR."mark_page.php");
if (is_readable(COUNTER)) include_once(COUNTER);
?>

<body>

<div id="container">

   	<?php
	   require('includes/header-logo.php');
	   require('includes/nav.php');
	?>

    	<div id="body">
	<div id="content">

<?php
$def = "index";
$dPath = $_SERVER['REQUEST_URI'];
$dChunks = explode("/", $dPath);

echo('<a class="dynNav" href="/">Accueil</a><span class="dynNav"> > </span>');
for($i=1; $i<count($dChunks); $i++ ){
	echo('<a class="dynNav" href="/');
	for($j=1; $j<=$i; $j++ ){
		echo($dChunks[$j]);
		if($j!=count($dChunks)-1){ echo("/");}
	}
	
	if($i==count($dChunks)-1){
		$prChunks = explode(".", $dChunks[$i]);
		if ($prChunks[0] == $def) $prChunks[0] = "";
		$prChunks[0] = $prChunks[0] . "</a>";
	}
	else $prChunks[0]=$dChunks[$i] . '</a><span class="dynNav"> > </span>';
	echo('">');
	echo(str_replace("_" , " " , $prChunks[0]));
} 
?>
<br />
		
        <?php
        //show message from add / edit page
        if(isset($_GET['action'])){
                echo '<div class="alert-msg rnd8 success">Création du compte : '.htmlentities($_GET['action'], ENT_QUOTES, "UTF-8").' !<br />Vous pouvez vous connecter avec le pseudo et le mot de passe que vous venez de fournir pour votre inscription.</div>';
        }
        ?>

	<div style="text-align: center;"><h1>Liste des membres :</h1></div>

        <table>
        <tr>
		<th style="width: 250px;"><a style="color: #fff; text-decoration: none;" href="membres.php?tri=username&ordre=desc">&#x2191;</a>Pseudo<a style="color: #fff; text-decoration: none;" href="membres.php?tri=username&ordre=asc">&#x2193;</a></th>
		<th><a style="color: #fff; text-decoration: none;" href="membres.php?tri=memberDate&ordre=desc">&#x2191;</a>Inscription le<a style="color: #fff; text-decoration: none;" href="membres.php?tri=memberDate&ordre=asc">&#x2193;</a></th>
                <th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="membres.php?tri=uploaded&ordre=desc">&#x2191;</a>Envoyé<a style="color: #fff; text-decoration: none;" href="membres.php?tri=uploaded&ordre=asc">&#x2193;</a></th>
                <th style="text-align: center;"><a style="color: #fff; text-decoration: none;" href="membres.php?tri=downloaded&ordre=desc">&#x2191;</a>Téléchargé<a style="color: #fff; text-decoration: none;" href="membres.php?tri=downloaded&ordre=asc">&#x2193;</a></th>
		<th style="text-align: center;">Ratio</th>
        </tr>

        <?php
                try {
			// On affiche 15 membres par page
			$pages = new Paginator('10','p');

			$stmt = $db->query('SELECT memberID FROM blog_members');
			$pages->set_total($stmt->rowCount());

			if(isset($_GET['tri'])) {
                                $tri = htmlentities($_GET['tri']);
                        }
                        else {
                                $tri = 'memberID';
                        }

                        if(isset($_GET['ordre'])) {
                                $ordre = htmlentities($_GET['ordre']);
                        }
                        else {
                                $ordre = 'DESC';
                        }

                        $stmt = $db->query('SELECT * FROM blog_members,xbt_users WHERE blog_members.memberID=xbt_users.uid AND blog_members.username != "visiteur" ORDER BY '.$tri.' '.$ordre.' '.$pages->get_limit());
                        while($row = $stmt->fetch()){

				// On affiche des couleurs de row alternées
                        	//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';
				//$x++;
                        	//$tablerow = ($x%2 == 0)? 'tableRow1': 'tableRow2';

                                //echo '<tr class="'.$tablerow.'">';
				echo '<tr>';

				if (!empty($row['avatar'])) {
					echo '<td style="font-weight: bold; font-size: 14px;"><img src="'.SITEURL.'/images/avatars/'.$row['avatar'].'" alt="'.$row['username'].'" style="float: left; margin-right: 5px; width: 30px; height: 30px;" /><a href="admin/profil.php?membre='.$row['username'].'">'.$row['username'].'</a></td>';
				}
				else {
					echo '<td style="font-weight: bold; font-size: 14px;"><img src="'.SITEURL.'/images/avatars/avatar.png" alt="'.$row['username'].'" style="float: left; margin-right: 5px; width: 30px; height: 30px;" /><a href="admin/profil.php?membre='.$row['username'].'">'.$row['username'].'</a></td>';
				}			

				sscanf($row['memberDate'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
				echo '<td style="font-size: 12px;">'.$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute.':'.$seconde.'</td>';
                                echo '<td style="text-align: center;">'.makesize($row['uploaded']).'</td>';
                                echo '<td style="text-align: center;">'.makesize($row['downloaded']).'</td>';

				if (intval($row["downloaded"])>0) {
					$ratio=number_format($row["uploaded"]/$row["downloaded"],2);
				}
				else {
					$ratio='&#8734;';
				}

				echo '<td style="text-align: center;">'.$ratio.'</td>';
                                echo '</tr>';

                        }


                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
        ?>
        </table>

	<?php echo $pages->page_links('?tri='.$tri.'&ordre='.$ordre.'&'); ?>

</div>

	<?php require('sidebar.php'); ?>
        
    	<div class="clear"></div>
    </div>
</div>

<div id="footer">
	<?php require('includes/footer.php'); ?>
</div>

</body>
</html>
