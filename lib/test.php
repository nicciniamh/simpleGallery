<?php
//$debug = true;
require_once('lib/albumcont.php');
$a = new album("albums/Oregon");
list($icnt,$acnt) = albumCounts($a);
echo "{$a->title} has {$icnt} total images in {$acnt} albums.\n"
?>
