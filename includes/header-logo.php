        <div id="header">
			<a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>/images/logo.png" alt="<?php echo SITENAMELONG; ?>" style="float: left;" /></a>
			<!-- <a href="<?php echo SITEURL; ?>"><?php echo SITENAME; ?></a> -->
			<!-- <p style="font-weight: bold; font-size: 19px; padding-top: 20px; padding-left: 910px;">Bittorrent au service du libre !</p> -->

			<form action="<?php echo SITEURL; ?>/recherche.php" method="post" id="rechercher" name="rechercher"> 
				<div id="recherche">
   				<input type="text" alt="" name="requete" class="recherche" placeholder="Rechercher un torrent ...">
				<input type="submit" alt="" value="" class="valider"> Ex : Ubuntu, Manjaro, 64 bits, Gnome, XFCE, ... 
				</div> 
			</form>
                <div class="clear"></div>
        </div>
