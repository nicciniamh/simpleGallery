<?php
if(($config = parse_ini_file('sgallery.ini')) == false)
  $config = array();
require_once("{$_SERVER["DOCUMENT_ROOT"]}/{$config['lib_dir']}/albumcont.php");
$abase = $config['base_dir'];
if (isset($_GET['album']))
  $apath = ltrim($_GET['album'],'/');
else
  $apath = '.';
$album = new album($apath);
?>
<!DOCTYPE html>
<html>
<head>
<?php
echo "<script src=\"{$config['base_dir']}/js/jquery-3.3.1.min.js\"></script>\n";
echo "<script src=\"{$config['base_dir']}/js/jquery.colorbox.js\"></script>\n";
echo "<script src=\"{$config['base_dir']}/js/jquery.touchSwipe.min.js\"></script>\n";
echo "<script src=\"{$config['base_dir']}/lib/sgi.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"{$config['base_dir']}/js/jquery.colorbox.css\" />\n";
echo "<link rel=\"stylesheet\" href=\"{$config['base_dir']}/lib/style2.css\" />\n";
echo "<title>{$album->title}</title>\n";
?>
<style>
  /*
  CSS hacks for colorbox to stylr it more like i want. The loadingOverlay and loadingGraphic
  eliminate the spinner for loading images since this fails on chrome. 
  */
  #cboxCurrent{position:absolute; top: 0; left:58px; font-weight:bold; color:#afafaf; text-shadow: 2 2 #7c7c7c}
  #cboxContent {
    background-color: #4f4f4f;
  }
  #cboxSlideshow{position:absolute; bottom:4px; right:30px; color:#cf88ff;}
  #cboxLoadingOverlay{ background-image: none;}
  #cboxLoadingGraphic { background-image: none;}
</style>
</head>
<body>
<h1><?php echo $album->title; ?></h1>
<?php
$detail = "{$album->path}/detail.htm";
if(file_exists($detail)) readfile($detail);
?>
<div class="notation">Images are shown newest first.</div>
<div class="thumblist" id="albumlist">
<?php
  $ino = 0;
  if(is_array($album->children) && count($album->children)) {
    foreach($album->children as $c) {
      $apath = $c->path; //urldecode($album->path . "/" . $c->path);
      $cnt = count($c->images);
      $caption = "{$c->title}, {$cnt} images.";
      echo "<a class=\"tent aent\" title=\"{$c->title}\" ";
      echo "href=\"${abase}/album.php?album={$apath}\">";
      echo "<img src=\"{$c->images[0]->thumb}\" alt=\"{$c->title}\" title=\"{$c->title}\">";
      echo "<span class=\"ttext\">{$caption}</span>";
      echo "</a>\n";
    }
  }
?>  
</div><div style="clear: both;"></div>
<div class="thumblist" id="thumblist">
<?php  
  foreach($album->images as $i) {
    echo "<a class=\"tent gent\" title=\"{$i->desc}\" ";
    echo "href=\"{$i->path}\" rel=\"img-{$ino}\" onClick=\"popper('img-{$ino}');\" >";
    echo "<img src=\"{$i->thumb}\" alt=\"{$i->desc}\" title=\"{$i->desc}\" onClick=\"popper('img-{$ino}');\">";
    echo "<span class=\"ttext\">{$i->desc}</span>";
    echo "</a>\n";
    $ino ++;
  }
?>
</div>
</body>
</html>
