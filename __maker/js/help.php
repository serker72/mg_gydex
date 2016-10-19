<?
session_start();
header('Content-type: text/html; charset=windows-1251');

require_once('../../classes/global.php');
require_once('../../classes/authuser.php');
require_once('../../classes/smarty/SmartyAdm.class.php');
require_once('../../classes/smarty/SmartyAj.class.php');



$ret='';
if(isset($_POST['action'])&&($_POST['action']=="help")){
	//вывод справки из файла
	$file=basename(($_POST['file']));
	
	//echo ABSPATH.'help/'.$file;
	if($f=fopen(ABSPATH.'help/'.$file,'r')){
		
		$file = fread($f, filesize(ABSPATH.'help/'.$file)); 
		
		
		$file=str_replace('src="', 'src="/help/', $file);
		
		
		//$file=eregi_replace("[.]*\<body lang=RU\>","",$file);
		
		//echo $file;
		$ret=$file;
		fclose($f);	
	}
		
	
	 
}
	
//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
echo $ret;	
?>