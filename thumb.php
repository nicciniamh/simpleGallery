<?php
if(($config = parse_ini_file('sgallery.ini')) == false)
    $config = array();
$basedir = preg_replace('/(\/\/*)/', '/', "{$_SERVER["DOCUMENT_ROOT"]}/{$config['base_dir']}");
$extfnin = array(
    'jpeg' => 'imagecreatefromjpeg',
    'jpg' => 'imagecreatefromjpeg',
    'gif' => 'imagecreatefromgif',
    'png' => 'imagecreatefrompng'
);
$extfnout = array(
    'jpeg' => 'imagejpeg',
    'jpg' => 'imagejpeg',
    'gif' => 'imagegif',
    'png' => 'imagepng'
);
function make_thumb($src, $dest, $desired_height) {
    global $extfnin, $extfnout;
    /* read the source image */
    $ext = pathinfo($src, PATHINFO_EXTENSION);
    $fnin = $extfnin[$ext];
    $source_image = $extfnin[$ext]($src);
    if(($exif = @exif_read_data($src))) {
        $ort = 0;
        if (in_array('Orientation', $exif)) {
            if (@isset($exif['Orientation']))
                $ort = $exif['Orientation'];
        }
        elseif (in_array('IFD0', $exif)) {
            if (@isset($exif['IFDO']['Orientation']))
                $ort = $exif['IFD0']['Orientation'];
        }
        if (in_array($ort, array(3,6,8)))
            $source_image = rotate($source_image,$ort);
    }
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    
    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_width = floor($width * ($desired_height / $height));
    
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    
    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    
    /* create the physical thumbnail image to its destination */
    $ext = pathinfo($dest, PATHINFO_EXTENSION);

    $extfnout[$ext]($virtual_image, $dest);
    return $dest;
}
function rotate($image,$orientation) {
    switch($orientation) {
        case 3:
            $new = imagerotate($image, 180, 0);
            break;
        case 6:
            $new = imagerotate($image, -90, 0);
            break;
        case 8:
            $new = imagerotate($image, 90, 0);
            break;
    }
    if(is_bool($new))
        return $image;
    return $new;
}

function imageThumbnail($thumbdir,$filename) {
    $dir = dirname($filename);
    $base = basename($filename);
    $thumbname = "{$thumbdir}/{$base}";
    if(!file_exists($thumbdir))
        @mkdir($thumbdir,0775,true);
    $thumb = $filename;
    if(file_exists($thumbname)) {
        $itime = filemtime($filename);
        $ttime = filemtime($thumbname);
        if($itime <= $ttime)
            $thumb = $thumbname;
    }
    $thumb = make_thumb($filename,$thumbname,200);
    if (!file_exists($thumb)) {
        error_log("No actual thumbnail was created for {$filename}, falling back to {$filename}");
        $thumb = $filename;
    }
    return $thumb;
}
if(isset($_GET['i'])) {
    $thumb  = $_GET['i'];
    $src = "{$basedir}/albums/{$thumb}";
    $thumb = "{$basedir}/thumbs/{$thumb}";
    $thumbdir = dirname($thumb);
    //echo "thumb.php called for $thumb, src is $src, thumbdir is $thumbdir";
    //exit(0);
    $thumb = imageThumbnail($thumbdir, $src);
    $ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $image = $extfnin[$ext]($thumb);
    $mime = mime_content_type($thumb);
    header("Content-type: {$mime}");
    $extfnout[$ext]($image);
}
?>
