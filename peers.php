<?php
require('includes/config.php');
$pagetitle= 'Clients connectés';
require('includes/header.php');
?>

<body>

<div id="container">

	<?php
	   require('includes/header-logo.php');
	   require('includes/nav.php');
	?>

    	<div id="body">
		<div id="content">
	
		<h3>Torrent : <?php echo htmlspecialchars($_GET['torrent']); ?></h3>

		<table>
		 <tr>
		  <th>Pseudo</th>
		  <th>Statut</th>
		  <th>Client</th>
		  <th>Port</th>
		  <th>Téléchargé</th>
		  <th>Uploadé</th>
		  <th>Ratio</th>
		  <th>Mis à jour</th>
		 </tr>		

		<?php
		$stmt = $db->prepare('
			SELECT xal.id,xal.ipa,xal.port,xal.peer_id,xal.downloaded down,xal.uploaded up,xal.uid,xfu.mtime time,b.username, IF(xal.left0=0,"seeder","leecher") as status 
			FROM xbt_announce_log xal 
			LEFT JOIN blog_members b ON b.memberID = xal.uid 
			LEFT JOIN xbt_files xf ON xf.info_hash = xal.info_hash 
			LEFT JOIN blog_posts_seo bps ON bps.postID = xf.fid 
			LEFT JOIN xbt_files_users xfu ON xfu.fid = xf.fid   
			WHERE bps.postID = :idtorrent AND xfu.active = 1 AND xal.mtime < (UNIX_TIMESTAMP() - 30)
			GROUP BY xal.ipa
		');
		$stmt->execute(array(
			':idtorrent' => $_GET['id']
		));

		while($row = $stmt->fetch()) {

			// on trouve le client bittorrent
                	$peer = substr($row['peer_id'], 1, 2);
	
			if($peer == 'AZ') {
				$client = 'Azureus';
			}
			elseif($peer == 'BC') {
                                $client = 'BitComet';
                        }
			elseif($peer == 'AZ') {
                                $client = 'Azureus';
                        }
			elseif($peer == 'BP') {
                                $client = 'Bittorrent Pro';
                        }
			elseif($peer == 'DE') {
                                $client = 'Deluge Torrent';
                        }
			elseif($peer == 'FX') {
                                $client = 'Freebox BitTorrent';
                        }
			elseif($peer == 'KT') {
                                $client = 'KTorrent';
                        }
			elseif($peer == 'LT') {
                                $client = 'libTorrent';
                        }
			elseif($peer == 'lt') {
                                $client = 'rTorrent';
                        }
			elseif($peer == 'qB') {
                                $client = 'qBittorrent';
                        }
			elseif($peer == 'TR') {
                                $client = 'Transmission';
                        }
			elseif($peer == 'UT') {
                                $client = '&#181;Torrent';
                        }

			else {
				$client = 'Client inconnu';
			}

			//$tablerow = ++$i % 2 ? 'tableRow1':'tableRow2';
			//echo '<tr class="'.$tablerow.'">';
			echo '<tr>';
			  echo '<td>'.$row['username'].'</td>';
			  echo '<td>'.$row['status'].'</td>';
			  echo '<td>'.$client.'</td>';
			  echo '<td>'.$row['port'].'</td>';
			  echo '<td>'.makesize($row['down']).'</td>';
			  echo '<td>'.makesize($row['up']).'</td>';
			  //Peer Ratio
			  if (intval($row["down"])>0) {
                        	$ratio=number_format($row["up"]/$row["down"],2);
			  }
			  else {
				$ratio='&#8734;';
			  }
			  echo '<td>'.$ratio.'</td>';
			  echo '<td>'.get_elapsed_time($row['time']).'</td>';
			echo '</tr>';
		}
		?>
		
		</table>

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
