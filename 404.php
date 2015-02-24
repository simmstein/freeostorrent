<?php
require_once('includes/config.php');
$pagetitle = 'Erreur 404';
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

	<div class="edito" style="text-align: center;">	
		<h1>4 0 4</h1>
		<h2>Erreur : désolé, cette page n'existe pas...</h2>
	</div>


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
