<?
	require_once("../classes_s/global.php");
	require_once("photosettings.php");	
	function DefineExtension($name=''){
		$extension=0;
		if(eregi("^(.*)\\.(jpg|jpeg|jpe)$",$name,$P)) $extension='.jpg';
		if(eregi("^(.*)\\.(gif)$",$name,$P)) $extension='.gif';
		if(eregi("^(.*)\\.(png)$",$name,$P)) $extension='.png';		
		if(eregi("^(.*)\\.(wbm)$",$name,$P)) $extension='.wbm';	
		return $extension;
	}

	$picname=$_GET['picname'];
	$widthnew=$_GET['w1'];
	$heightnew=$_GET['h1'];		
	$extension=DefineExtension($picname);
	
	$picname=$basepath.$picname;
	
	
	if($extension=='.jpg') $im=ImageCreateFromJpeg($picname);
	if($extension=='.gif') $im=ImageCreateFromGif($picname);	
	if($extension=='.png') $im=ImageCreateFromPng($picname);	
	if($extension=='.wbm') $im=ImageCreateFromWbmp($picname);

	$size = GetImageSize($picname);
	$im2 = imagecreatetruecolor($widthnew,$heightnew);
	if(($widthnew<=$size[0])&&($heightnew<=$size[1]))
		imagecopyresampled($im2, $im, 0,0,0,0, $widthnew,$heightnew, $size[0],$size[1]);
	else
		$im2=$im;
	
	if($extension=='.jpg'){
		Header("Content-type: image/jpeg");
		ImageJpeg($im2);
	}
	if($extension=='.gif'){
		Header("Content-type: image/gif");
		ImageGif($im2);
	}
	if($extension=='.png'){
		Header("Content-type: image/png");
		ImagePng($im2);
	}		
	if($extension=='.wbm'){
		Header("Content-type: image/wbmp");
		ImagePng($im2);
	}
	
	ImageDestroy($im);
	ImageDestroy($im2);	
?>