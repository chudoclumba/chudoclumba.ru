<?php

$dir = "cache";
define('CACHE_LIFE_TIME',3600);// 1 час
require_once "includes/kanfih.php";
ini_set('output_buffering',1);
ini_set('implicit_flush',0);
ob_start();
if(@strpos('https://',$_GET['id']) !== 0)
{
	$_GET['id'] = str_replace('https://'.str_replace('www.','',SITE_NAME).'/','',$_GET['id']);
}
$ob_req=str_replace('/thumb.php?id=data/','',$_SERVER['REQUEST_URI']);
$ob_filename='cache/'.sha1($ob_req);
//file_put_contents ( 'rdata.txt', print_r(array($_GET['id']=>$ob_filename),true) , FILE_APPEND  );

if(file_exists($ob_filename))
{
	$tm=filemtime($ob_filename);
	if(($tm<(time()-CACHE_LIFE_TIME)) || (filesize($ob_filename) == 0) || (filemtime(ROOT_DIR.''.$_GET['id']) >$tm)){
		unlink($ob_filename);
	}
	else {
		header("Content-type: image/jpeg");
		ob_start();
		readfile($ob_filename);
		$file = ob_get_contents();
		ob_end_clean();
		header("Content-Length: " . strlen($file) .""); 
		$expires = 60*60*24*14;
		header("Pragma: public");
		header("Etag: ".md5($ob_filename.$tm));
		header("Cache-Control: maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');	
		echo $file;
		exit;
	}
}


function image_resize($src, $width, $height, $crop=0){
  global	$ob_req;
  if(!list($w, $h) = getimagesize($src)) {
    	file_put_contents ( 'cache/rdata.txt', print_r([$ob_req=>"Unsupported picture type!"],true) , FILE_APPEND  );
    	return false;
  }
  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : {
    	file_put_contents ( 'cache/rdata.txt', print_r([$ob_req=>"Unsupported picture type!"],true) , FILE_APPEND  );
    	return false;
    	}
  }

  // resize
  $dwidth=$width;
  $dheight=$height;
  $dx=0; $dy=0;

  if($crop){
  	$coef=1;
  	if ($w<10 or $h<10) {
    	file_put_contents ( 'cache/rdata.txt', print_r([$ob_req=>"Picture is too small!"],true) , FILE_APPEND  );
    	return false;
	}
    if($w < $width and $h < $height) {
    	$coef=min($w/$width,$h/$height);
    }
    elseif($w < $width){
    	$coef=$w/$width;
	}
    elseif ($h < $height) {
    	$coef=$h/$height;
    }
    	
    $ratio = max($width*$coef/$w, $height*$coef/$h);
    $x = ($w - $width*$coef / $ratio) / 2;
    $w = $width*$coef / $ratio;
    $y = ($h - $height*$coef / $ratio)/ 2;
    $h = $height*$coef / $ratio;
  }
  else{
	$ratio_orig = $w/$h;
	if ($width/$height > $ratio_orig) {
	   $dwidth = $height*$ratio_orig;
	   $dx=($width-$dwidth)/2;
	} else {
	   $dheight = $width/$ratio_orig;
	   $dy=($height-$dheight)/2;
	}
	
  }

  $new = imagecreatetruecolor($width, $height);
  $col=imagecolorallocate($new,255,255,255);
  imagefilledrectangle($new,0,0,$width,$height,$col); 
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, $dx, $dy, $x, $y, $dwidth, $dheight, $w, $h);

  switch($type){
    case 'bmp': return imagewbmp($new); break;
    case 'gif': return imagegif($new); break;
    case 'jpg': return imagejpeg($new,NULL,90); break;
    case 'png': return imagepng($new); break;
  }
  return true;
}
function imemp($src, $width, $height){
	$im = @imagecreatetruecolor($width, $height)
	      or die('Cannot Initialize new GD image stream');
	$text_color = imagecolorallocate($im, 233, 14, 91);
	imagestring($im, 1, 5, 5,  'No file..', $text_color);
	return imagejpeg($im);
//	imagedestroy($im);	
}


if($_GET['id'])
{
	$x=(!empty($_GET['x']))?$_GET['x']:100;
	$y=(!empty($_GET['y']))?$_GET['y']:100;
	$cr=(isset($_GET['crop']))?1:0;
	if  (!file_exists(ROOT_DIR.''.$_GET['id'])){
//    	file_put_contents ( 'cache/rdata.txt', print_r([$ob_req=>"File not exist!"],true) , FILE_APPEND  );
 	}
//	file_put_contents ( 'cache/rdata.txt', print_r(array($x,$y,$cr),true) , FILE_APPEND  );
	header("Content-type: image/jpeg");
	ob_start();
	if  (!file_exists(ROOT_DIR.''.$_GET['id'])){
		echo imemp(ROOT_DIR.''.$_GET['id'],$x,$y);
	}
	else {
		echo image_resize(ROOT_DIR.''.$_GET['id'],$x,$y,$cr);
	}
	$file = ob_get_contents();
	ob_end_clean();
	header("Content-Length: " . strlen($file) .""); 
	if (strlen($file)==0) {$expires = 60;} else {$expires = 60*60*24*14;}
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');	
	echo $file;
}




$content=ob_get_contents();
if (strlen($file)!==0) {file_put_contents($ob_filename,$content);} else {
	file_put_contents ( 'data/System/errdata.txt', print_r($_SERVER[HTTP_REFERER].' cсылка '.$ob_req."\n",true) , FILE_APPEND  );
}
ob_end_flush();
