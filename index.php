<?php
if(($config = parse_ini_file('sgallery.ini')) == false)
	$config = array();
$libdir = "{$_SERVER["DOCUMENT_ROOT"]}/{$config['lib_dir']}";
require_once($libdir . "/albumcont.php");
$gdir = $config['album_dir'];
$glist = array();
foreach(glob($gdir . "/*", GLOB_ONLYDIR) as $dir) {
	if(file_exists($dir . "/toplevel.dsc")) {
		if(!file_exists($dir . "/noindex")) {
			array_push($glist,$dir);
			continue;
		}

	}
}
$alist = array();
foreach ($glist as $g) {
	$a = new album($g);
	array_push($alist,$a);
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href=<?php echo "\"{$config['css_dir']}/index.css\"";?> />
  <title><?php echo $config['title']; ?></title>
</head>
<body>
<?php
echo "<h1>{$config['heading']}</h1>\n";
if(isset($config['subheading'])) {
	if(preg_match('/[a-z]*\:/',$config['subheading'], $m)) {
		list($type,$resource) = explode(':',$config['subheading'],2);
		switch ($type) {
			case 'file':
				readfile($resource);
				break;
			
			default:
				# code...
				break;
		}
	} else {
		echo $config['subheading'];
	}
}
?>
<div class="gcont">
<?php
foreach($alist as $a) {
	$gpath = $a->path;
	$gname = ucfirst(basename($a->path));
	list($icnt,$acnt) = albumCounts($a);
	if($acnt >1) 
		$al = "albums";
	else 
		$al = "album";
	$caption = "{$gname}, {$icnt} images in ${acnt} {$al}";
	//echo "Gallery: {$gname} on {$gpath} thumb: {$a->images[0]->thumb}<br/>";
	//continue;
	echo "<div class=\"gele\">\n";
	echo "<a href=\"{$config['base_dir']}/album.php?album={$gpath}\"><img src=\"{$a->images[0]->thumb}\" /></a>\n";
	echo "<span class=\"gtext\">{$caption}</span>\n";
	echo "</div>\n";
}
?>
</div>
<div style="clear: both";/>
</body>
</html>
