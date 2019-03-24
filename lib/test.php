<?php
$debug = true;
require_once('lib/albumcont.php');
$a = new album("albums/Oregon");
echo "Children " . count($a->children) . "\n";
foreach($a->children as $c) {
	echo "Child {$c->title}\n";
}
?>
