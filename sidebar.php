<div class="sidebar">

<ul>

<li>
        <h4><span>Catégories</span></h4>
                <ul class="blocklist">
                        <div class="select">
                        <select onchange="document.location.href = this.value">
                                <option>Choisir une catégorie</option>
                                <?php
                                $stmt = $db->query('SELECT catTitle, catSlug FROM blog_cats ORDER BY catTitle ASC');
                                while($row = $stmt->fetch()){
                                        echo '<option value="c-'.$row['catSlug'].'">'.$row['catTitle'].'</option>';
                                }
                                ?>
                        </select>
                        </div>
                </ul>
</li>

<li>
        <h4><span>Archives</span></h4>
                <ul class="blocklist">
                        <div class="select">
                        <select onchange="document.location.href = this.value">
                        <option>Choisir un mois</option>
                        <?php
                        $stmt = $db->query("SELECT Month(postDate) as Month, Year(postDate) as Year FROM blog_posts_seo GROUP BY Month(postDate), Year(postDate) ORDER BY postDate DESC");
                        while($row = $stmt->fetch()){
                                $monthName = date_fr("F", mktime(0, 0, 0, $row['Month'], 10));
                                $slug = 'a-'.$row['Month'].'-'.$row['Year'];
                                echo "<option value='".$slug."'>".$monthName."</option>";
                        }
                        ?>
                        </select>
                        </div>
                </ul>
</li>

<li>
        <h4><span>Menu</span></h4>

                <?php
                if($user->is_logged_in() && $_SESSION['username'] == 'mumbly') {
                ?>
                <ul class="blocklist">
                        <span style="font-weight: bold;">Bienvenue <?php echo $_SESSION['username']; ?> !</span>
                        <li><a href="<?php echo SITEURL; ?>/admin/logout.php">Déconnexion</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin/upload.php">Ajouter un torrent</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin/profil.php?membre=<?php echo $_SESSION['username']; ?>">Profil</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin">Admin</a></li>
                        <li><a href="<?php echo SITEURL; ?>/stats">Stats</a></li>
                </ul>
                <?php }

                elseif($user->is_logged_in()) {
                ?>
                <ul class="blocklist">
                        <span style="font-weight: bold;">Bienvenue <?php echo $_SESSION['username']; ?> !</span>
                        <li><a href="<?php echo SITEURL; ?>/admin/logout.php">Déconnexion</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin/upload.php">Ajouter un torrent</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin/profil.php?membre=<?php echo $_SESSION['username']; ?>">Profil</a></li>
                </ul>
                <?php }

                elseif(!$user->is_logged_in()) {

                        /*
                        if(isset($_SESSION['messageInactif'])) {
                                echo $_SESSION['messageInactif'];
                                unset($_SESSION['messageInactif']);
                        }
                        */

                ?>
                <ul class="blocklist">
                        <li><a href="<?php echo SITEURL; ?>/admin/login.php">Connexion</a></li>
                        <li><a href="<?php echo SITEURL; ?>/admin/signup.php"><span style="font-weight: bold;">> Créer un compte <</span></a></li>
                </ul>
                <?php } ?>

</li>


<!--
<li>
	<h4><span>Infos :</span></h4>
	   <ul>
		<li style="font-size: 8pt; font-weight: bold;">[21/01/15 - 19h15] Le site et le tracker piratix.fr.nf ont été désactivés. Merci de recharger tous les torrents depuis freeostorrent.fr.</li>
		<li style="font-size: 8pt;"><img src="<?php echo SITEURL; ?>/images/up.png"> il n'est pas possible - pour le moment - de supprimer ses propres torrents. En cas de "problème" sur un torrent, merci de l'éditer et de rajouter [A SUPPRIMER] au début du titre.</li>
	   </ul>
</li>
-->

<!--
<li>
           <ul>
		 <form method="post" class="searchform" action="recherche.php" >
                    <img src="<?php echo SITEURL; ?>/images/search.png" alt="search" /> <input type="text" size="19" placeholder="Rechercher un torrent" name="requete" class="s" />
                    <input type="submit" class="searchsubmit formbutton" value="Go" />
                 </form>		
           </ul>
</li>
-->

<li>
                <ul>
                        <li>
                                
                                        <a href="https://www.facebook.com/freeostorrent"><img src="<?php echo SITEURL; ?>/images/social/facebook-icon.png" alt="Facebook" style="width: 40px; height: 40px;" /></a>&nbsp;
                                        <a href="https://twitter.com/freeostorrent"><img src="<?php echo SITEURL; ?>/images/social/twitter-icon.png" alt="Twitter" style="width: 40px; height: 40px;" /></a>&nbsp;
                                        <a href="https://plus.google.com/u/0/113771664239490205939/posts"><img src="<?php echo SITEURL; ?>/images/social/google-plus-icon.png" alt="Google+" style="width: 40px; height: 40px;" /></a>&nbsp;
                                        <a href="<?php echo SITEURL; ?>/rss.php"><img src="<?php echo SITEURL; ?>/images/social/rss-icon.png" alt="RSS" style="width: 40px; height: 40px;" /></a>&nbsp;
					<a href="<?php echo SITEURL; ?>/contact.php"><img src="<?php echo SITEURL; ?>/images/social/mail-icon.png" alt="Mail" style="width: 40px; height: 40px;" /></a>&nbsp;
										
					<br /><br />
					
					<?php
					// MESSAGERIE INTERNE
					if($user->is_logged_in()) {

					$stmtmess = $db->query('SELECT blog_messages.messages_titre, blog_messages.messages_date, blog_members.username as expediteur, blog_messages.messages_id as id_message FROM blog_messages, blog_members WHERE blog_messages.messages_id_destinataire = "'.$_SESSION['userid'].'" AND blog_messages.messages_id_expediteur = blog_members.memberID AND blog_messages.messages_lu = "0"');
					$nbmessages = $stmtmess->rowCount();

						echo '<fieldset>';
						echo '<img style="vertical-align: text-bottom;" src="'.SITEURL.'/images/messagerie.jpg" /> ';
						echo 'Messagerie : ';
			
						$stmtnbmess = $db->query('SELECT blog_messages.messages_id, blog_members.memberID FROM blog_messages, blog_members WHERE blog_messages.messages_id_destinataire = "'.$_SESSION['userid'].'" AND blog_messages.messages_id_expediteur = blog_members.memberID');
						$nbstmtnbmess = $stmtnbmess->rowCount();
	
						if($nbstmtnbmess > 1) {
							echo '<a style="text-decoration: none;" href="'.SITEURL.'/admin/profil.php?membre='.$_SESSION['username'].'#messages">'.$nbstmtnbmess.' messages</a><br />';
						}
						else {
							echo '<a style="text-decoration: none;" href="'.SITEURL.'/admin/profil.php?membre='.$_SESSION['username'].'#messages">'.$nbstmtnbmess.' message</a><br />';
						}

						echo 'dont <a style="text-decoration: none;" href="'.SITEURL.'/admin/profil.php?membre='.$_SESSION['username'].'#messages">'.$nbmessages.'</a>';
			
							if($nbmessages > 1) {
								echo ' messages non lus';
							}
							else {
								echo ' message non lu';
							}
						echo '</fieldset>';
					}
					?>

					<?php
					// NOMBRE DE MEMBRES INSCRITS
					// On ne compte pas le compte visiteur qui porte l'ID 32
					$stmt3 = $db->query('SELECT COUNT(memberID) AS membres FROM blog_members WHERE memberID !=32');
					$row3 = $stmt3->fetch();

					echo '<fieldset>';
					echo '<span style="font-weight: bold;">Membres inscrits :</span> '.$row3['membres'].'<br />';		
								
					// NOMBRE DE PERSONNES CONNECTEES SUR LE SITE
					$stmt = $db->prepare('SELECT COUNT(*) AS nbre_entrees FROM connectes WHERE ip = :ip');
					$stmt->execute(array(
						':ip' => $_SERVER['REMOTE_ADDR']
					));
					$donnees = $stmt->fetch();

					if(isset($_SESSION['username'])) {

						/*
						if ($donnees['nbre_entrees'] == 0) // L'IP ne se trouve pas dans la table, on va l'ajouter.
						{
							$stmt1 = $db->prepare('INSERT INTO connectes VALUES (:ip, :pseudo, :timestamp)') ;
							$stmt1->execute(array(
								':ip' => $_SERVER['REMOTE_ADDR'],
								':pseudo' => $_SESSION['username'],
								':timestamp' => time()
							));
						}
						*/

						//else { // L'IP se trouve déjà dans la table, on met à jour le timestamp.
							$stmt2 = $db->prepare('UPDATE connectes SET timestamp = :timestamp, pseudo = :pseudo  WHERE ip = :ip') ;
							$stmt2->execute(array(
								':timestamp' => time(),
								':pseudo' => $_SESSION['username'],
								':ip' => $_SERVER['REMOTE_ADDR']
							));
						//}	

					}

					else { // Ou bien il n'y a aucune session (ce n'est pas un membre connecté), c'est un "Visiteur"

						$pseudo = 'Visiteur';

						if ($donnees['nbre_entrees'] == 0) // L'IP ne se trouve pas dans la table, on va l'ajouter.
                                                {
                                                        $stmt1 = $db->prepare('INSERT INTO connectes VALUES (:ip, :pseudo, :timestamp)') ;
                                                        $stmt1->execute(array(
                                                                ':ip' => $_SERVER['REMOTE_ADDR'],
								':pseudo' => $pseudo,
                                                                ':timestamp' => time()
                                                        ));
                                                }

                                                else // L'IP se trouve déjà dans la table, on met juste à jour le timestamp.
                                                {
                                                        $stmt2 = $db->prepare('UPDATE connectes SET timestamp = :timestamp WHERE ip = :ip') ;
                                                        $stmt2->execute(array(
                                                                ':timestamp' => time(),
                                                                ':ip' => $_SERVER['REMOTE_ADDR']
                                                        ));
                                                }

					}


					// -------
					// ÉTAPE 2 : on supprime toutes les entrées dont le timestamp est plus vieux que 1 minute.

					// On stocke dans une variable le timestamp qu'il était il y a 24 min :
					$timestamp_5min = time() - (60 * 24); // (60 * 24 = nombre de secondes écoulées en 24 minutes)

					$stmt3 = $db->query('DELETE FROM connectes WHERE timestamp < ' . $timestamp_5min);

					// -------
					// ÉTAPE 3 : on compte le nombre d'IP stockées dans la table. C'est le nombre total de visiteurs connectés.
					$stmt4 = $db->query('SELECT COUNT(*) AS nbre_entrees FROM connectes');
					$donnees = $stmt4->fetch();

					// On affiche le nombre total de connectés
					if ($donnees['nbre_entrees'] < 2) {
						echo '<span style="font-weight: bold;">Personne connectée :</span> '.$donnees['nbre_entrees'].'<br />';
					}
			
					else {
						echo '<span style="font-weight: bold;">Personnes connectées :</span> '.$donnees['nbre_entrees'].'<br />';
					}
		
					// -------
					// ETAPE 4 : on affiche si c'est un visiteur ou un membre (avec son nom de membre)
		
					// On cherche le nombre de Visiteurs
					$stmt5 = $db->query("SELECT pseudo FROM connectes WHERE pseudo = 'Visiteur'");
					$num = $stmt5->rowCount();
	
					if($num>0) {
        					$i=0;
        					while($dn2 = $stmt5->fetch()) {
                					$i++;
                				}
        				}							
						
					if($num<2) {
						echo '<span style="font-style: italic;"><img src="'.SITEURL.'/images/visitor.png" alt="" />&nbsp;'.$num.' visiteur</span><br />';
					}
					else {
						echo '<span style="font-style: italic;"><img src="'.SITEURL.'/images/visitor.png" alt="" />&nbsp;'.$num.' visiteurs</span><br />';
					}

					// On cherche le nombre de membres connectés avec leur speduo
					$stmt6 = $db->query("SELECT pseudo FROM connectes WHERE pseudo != 'Visiteur'");
                                        $num1 = $stmt6->rowCount();

                                        //if($num1>0) {
                                        //$i=0;
					//$i++;

						if($num1 < 1) {
                                                        echo '<span style="font-style: italic;"><img src="'.SITEURL.'/images/author.png" alt="" />&nbsp;'.$num1.' membre';
                                                }
                                                elseif($num1 > 1) {
                                                	echo '<span style="font-style: italic;"><img src="'.SITEURL.'/images/author.png" alt="" />&nbsp;'.$num1.' membres : ';
                                                }
                                                elseif($num1 < 2) {
                                                	echo '<span style="font-style: italic;"><img src="'.SITEURL.'/images/author.png" alt="" />&nbsp;'.$num1.' membre : ';
                                                }

							$links = array();
							foreach ($stmt6 as $s) {
								$links[] = '<a href="'.SITEURL.'/admin/profil.php?membre='.htmlspecialchars($s['pseudo']).'" style="text-decoration: none;">'.htmlspecialchars($s['pseudo']).'</a>';
							}
							echo implode(", ", $links);
							echo '</span>';
                                        //}

					echo '</fieldset>';	



					/**** compteur de visites ***/
					// ETAPE 1 : on vérifie si l'IP se trouve déjà dans la table
					// Pour faire ça, on n'a qu'à compter le nombre d'entrées dont le champ "ip" est l'adresse ip du visiteur
					$stmt5 = $db->prepare('SELECT COUNT(*) AS nbre_entrees FROM compteur WHERE ip = :adresseip');
					$stmt5->execute(array(
						':adresseip' => $_SERVER['REMOTE_ADDR']
					));
					$donnees2 = $stmt5->fetch();
 
					if ($donnees2['nbre_entrees'] == 0) // L'ip ne se trouve pas dans la table, on va l'ajouter
					{
						$stmt6 = $db->prepare('INSERT INTO compteur VALUES (:adresseip, :time)');
						$stmt6->execute(array(
							':adresseip' => $_SERVER['REMOTE_ADDR'],
							':time' => time()
						));
					}

					else // L'ip se trouve déjà dans la table, on met juste à jour le timestamp
					{
    						$stmt7 = $db->prepare('UPDATE compteur SET timestamp = :timestamp WHERE ip = :adresseip');
						$stmt7->execute(array(
							':timestamp' => time(),
							':adresseip' => $_SERVER['REMOTE_ADDR']
						));
					}

					$jour = date('d');
					$mois = date('m');
					$annee = date('Y');
					$aujourd_hui = mktime(0, 0, 0, $mois, $jour, $annee);
		
					$stmt8 = $db->prepare('SELECT COUNT(*) AS nbre_entrees FROM compteur WHERE timestamp > :timestamp');
					$stmt8->execute(array(
						':timestamp' => $aujourd_hui
            				));
					$donnees3 = $stmt8->fetch();

					echo '<fieldset>';
					echo '<span style="font-weight: bold;">Visites aujourd\'hui :</span> '.$donnees3['nbre_entrees'].'<br />';
 
					$stmt9 = $db->query('SELECT COUNT(*) AS nbre_entrees FROM compteur');
					$donnees4 = $stmt9->fetch();
					echo '<span style="font-weight: bold;">Visites totales :</span> ' . $donnees4['nbre_entrees'];

					echo '</fieldset>';
					/**** Fin compteur de visites ****/
					?>					

                                
                        </li>
                </ul>
</li>

<li>
	<h4><span>Derniers commentaires</span></h4>
		<ul>
		<li>
			<script type="text/javascript" src="http://freeostorrentfr.disqus.com/recent_comments_widget.js?num_items=5&hide_mods=0&color=grey&hide_avatar=0&avatar_size=100&excerpt_length=70"></script>
		</li>
		</ul>
</li>


<li>
	<h4><span>Statistiques du site</span></h4>
		<ul class="blocklist" style="font-size: 11px;">
			<?php
				$stmt = $db->query('SELECT info_hash, sum(completed) completed, sum(leechers) leechers, sum(seeders) seeders, sum(leechers or seeders) torrents FROM xbt_files');
				$result = $stmt->fetch();

        			$result['peers'] = $result['leechers'] + $result['seeders'];

        			echo '<table style="text-align: left;">';
        			echo '<tr><th>Torrents chargés : </th><td>'. $result['completed']. '</td></tr>';
        			echo '<tr><th>Clients : </th><td>'. $result['peers']. '</td></tr>';

        			if ($result['peers'])
        				{
                				printf('<tr><th>Leechs : <td>%d <span style="font-size:7pt;">(%d %%)</span>', $result['leechers'], $result['leechers'] * 100 / $result['peers'], '</td></tr>');
                				printf('<tr><th>Seeds : <td>%d <span style="font-size:7pt;">(%d %%)</span>', $result['seeders'], $result['seeders'] * 100 / $result['peers'], '</td></tr>');
        				}

        			echo '<tr><th>Torrents actifs : <td>'. $result['torrents']. '</td></tr>';

                		//$nbr = mysql_query("SELECT id_torr FROM torrents");
                		//$nbrtorrents = mysql_num_rows($nbr);
				$stmt = $db->query('SELECT postID FROM blog_posts_seo');
				$nbrtorrents =$stmt->rowCount();

        			printf('<tr><th>Torrents total : <td>%d</td>', $nbrtorrents ,'<td></td></tr>');

        			//$res = mysql_query("select sum(downloaded) as down, sum(uploaded) as up from xbt_users");
        			//$row = mysql_fetch_array($res);
				$stmt = $db->query('SELECT sum(downloaded) as down, sum(uploaded) as up FROM xbt_users');
				$row = $stmt->fetch();

       				$dled=makesize($row['down']);
       				$upld=makesize($row['up']);
       				$traffic=makesize($row['down'] + $row['up']);

       				printf('<tr><th>Download total : <td>'. $dled. '</td></tr>');
       				printf('<tr><th>Upload total : <td>'. $upld. '</td></tr>');
       				printf('<tr><th>Trafic total : <td>'. $traffic. '</td></tr>');

				print('</table>');
		
			?>
		</ul>
</li>

</div>
