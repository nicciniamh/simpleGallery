<?php
function albumCounts($a) {
	$icnt = count($a->images);
	$acnt = 1; //1 + count($a->children);
	if(count($a->children)) {
		foreach($a->children as $c) {
			list($ic,$ac) = albumCounts($c);
			$icnt += $ic;
			$acnt += $ac;
		}
	}
	return array($icnt,$acnt);
}
class image {
	public $name;
	public $path;
	public $desc;
	public $thumb;
	public function __construct($album,$filename) {
		if (!file_exists($filename)) {
			throw new Exception("Cannot find image \"{$filename}\"");
		}
		$this->name = basename($filename);
		$this->path = $filename;
		$this->thumb = "thumb.php?i={$this->path}";
		$this->thumb = dirname($filename) . "/.thumbs/" . $this->name;
		$this->thumb = preg_replace('/^albums\//','thumbs/',$this->path);
		if (file_exists($this->path . ".dsc")) {
			$this->desc = trim(file_get_contents($this->path.".dsc"));
		} else {
			$this->desc = $album;
		}
	}
}
function __csortcmp($o1,$o2) {
	if ($o1 instanceof album && $o2 instanceof album)
		return strcmp($o1->title, $o2->title);
	return 0;
}
class album {
	public $title;
	public $images;
	public $path;
	public $children;
	public function __construct($path) {
		$this->path = $path;
		if(file_exists($path . "/album.dsc")) {
			$this->title = trim(file_get_contents($path . "/album.dsc"));
		} else {
			throw new Exception('Cannot instantiate album without description');
			$this->title = basename($path);
		}
		$this->images = array();
		$files = array();
		$this->children = array();
		foreach(glob($this->path . "/*", GLOB_ONLYDIR) as $dir) {
			if($GLOBALS['debug'] == true)
				echo "Checking " . $dir . " for album\n";
			if(file_exists($dir . "/album.dsc")) {
				if($GLOBALS['debug'] == true)
					echo $dir . " is an album\n";
				$this->children[] = new album($dir);
			}
		}
		usort($this->children,'__csortcmp');
		foreach(glob($path . "/*.{jpg,gif}", GLOB_BRACE) as $filename) {
			$time = filemtime($filename);
			while(array_key_exists(strval($time), $files))
				$time  = $time + 1 / rand(1,9);
			$files[strval($time)] = $filename;
		}
		arsort($files);
		foreach($files as $time => $filename) {
			$this->images[] = new image($this->title,$filename);
		}
	}
}
?>