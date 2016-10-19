<?
session_start();

require_once('classes/global.php');
require_once('classes/authuser.php');
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/smarty/Smarty.class.php');
 

 require_once('classes/langgroup.php');
  require_once('classes/solfileitem.php');
//открытие почтового вложения


$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');
 



$id=abs((int)$_GET['id']);

$_fmi=new SolFileItem;
$fmi=$_fmi->GetItemById(abs((int)$_GET['id']));
if($fmi===false){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	die();	
}
  




$filename=$_fmi->GetStoragePath().$fmi['filename'];
if(is_file($filename)){
	
	/* header("HTTP/1.1 200 OK");
	  header("Connection: close");
	  header("Content-Type: application/octet-stream");
	  header("Accept-Ranges: bytes");
	  header("Content-Disposition: Attachment; filename=\"".eregi_replace("[[:space:]]","_",$fmi['orig_name'])."\"");
	  header("Content-Length: ".filesize($_fmi->GetStoragePath().$fmi['filename'])); */
	 
	 $extension=0;
	if(eregi("^(.*)\\.(jpg|jpeg|jpe)$", $fmi['orig_name'],$P)){
		 $extension='.jpg';
		 $image=imagecreatefromjpeg($_fmi->GetStoragePath().$fmi['filename']);
		 header("Content-type: image/jpeg");

	}
	elseif(eregi("^(.*)\\.(gif)$",  $fmi['orig_name'],$P)){
		 $extension='.gif';
		 header("Content-type: image/gif");
		 $image=imagecreatefromgif($_fmi->GetStoragePath().$fmi['filename']);	 
	}
	elseif(eregi("^(.*)\\.(png)$",  $fmi['orig_name'],$P)){
		 $extension='.png';
		 header("Content-type: image/png");
		 $image=imagecreatefrompng($_fmi->GetStoragePath().$fmi['filename']);
	}else die('Incorrect image type: '. $fmi['orig_name']);
	
	
	$size = GetImageSize($_fmi->GetStoragePath().$fmi['filename']);
	
	if(isset($_GET['width'])&&isset($_GET['height'])){
		$width=abs((int)$_GET['width']);
		$height=abs((int)$_GET['height']);
		if(((int)$size[0]>$width)||((int)$size[1]>$height)){
					
					 
					
					 
					
					$ratio = (int)$size[0]/(int)$size[1];
					
					
					if($ratio>=1){
						$w=$width; $h=ceil($w/$ratio);
					}else{
						$h=$height; $w=ceil($ratio*$h);
					}
					
					//что не вписывается - то уменьшаем, берем за Основу.
					if($w>$width){
						$coef=$width/$w;
						$w=$width; $h=ceil($coef*$h);		
					}elseif($h>$height){
						$coef=$height/$h;
						$h=$height; $w=ceil($coef*$w); 
					}
					
					
					$image2 = imagecreatetruecolor($width,$height);
					imagefill ( $image2 , 0 ,0 , imagecolorallocate (  $image2 , 255, 255, 255 ) );
					
					
					//Отцентруем изображение
					$dst_x=0; $dst_y=0;
					
					$dst_x=round(($width-$w)/2); if($dst_x<0) $dst_x=0;
					$dst_y=round(($height-$h)/2); if($dst_y<0) $dst_y=0;
					
			
					imagecopyresampled($image2, $image, $dst_x,$dst_y,0,0, $w,$h, $size[0],$size[1]);
					 
					if($extension=='.jpg') imageJpeg($image2, NULL, 86);							
					if($extension=='.gif') imageGif($image2);										
					if($extension=='.png') imagePng($image2);	
					if($extension=='.wbm') imageWbmp($image2);
		}
	}else readfile($_fmi->GetStoragePath().$fmi['filename']); 	
	  
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	die();	
}
exit(0);
?>