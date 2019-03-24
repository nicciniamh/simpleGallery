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

$ovlCssText = <<<EOT
#cboxOverlay{background:url({$config['base_dir']}/js/colorbox-images/overlay.png) repeat 0 0; opacity: 0.9; filter: alpha(opacity = 90);}
    #cboxTopLeft{width:21px; height:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -101px 0;}
    #cboxTopRight{width:21px; height:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -130px 0;}
    #cboxBottomLeft{width:21px; height:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -101px -29px;}
    #cboxBottomRight{width:21px; height:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -130px -29px;}
    #cboxMiddleLeft{width:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) left top repeat-y;}
    #cboxMiddleRight{width:21px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) right top repeat-y;}
    #cboxTopCenter{height:21px; background:url({$config['base_dir']}/js/colorbox-images/border.png) 0 0 repeat-x;}
    #cboxBottomCenter{height:21px; background:url({$config['base_dir']}/js/colorbox-images/border.png) 0 -29px repeat-x;}
        #cboxLoadingOverlay{background:url({$config['base_dir']}/js/colorbox-images/loading_background.png) no-repeat center center;}
        #cboxLoadingGraphic{background:url({$config['base_dir']}/js/colorbox-images/loading.gif) no-repeat center center;}
        #cboxPrevious{position:absolute; bottom:0; left:0; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -75px 0; width:25px; height:25px; text-indent:-9999px;}
        #cboxNext{position:absolute; bottom:0; left:27px; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -50px 0; width:25px; height:25px; text-indent:-9999px;}
        #cboxClose{position:absolute; bottom:0; right:0; background:url({$config['base_dir']}/js/colorbox-images/controls.png) no-repeat -25px 0; width:25px; height:25px; text-indent:-9999px;}
EOT;
echo "<style>\n${ovlCssText}\n</style>\n";
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
      list($icnt,$acnt) = albumCounts($c);
      if($acnt > 1) {
        $al = "albums";
      } else {
        $al = "album";
      }
      $caption = "{$c->title}, {$icnt} images, in {$acnt} {$al}";
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
