<?
session_start();
header('Content-type: text/html; charset=windows-1251');

require_once('../../classes/global.php');
require_once('../../classes/authuser.php');
require_once('../../classes/smarty/SmartyAdm.class.php');
require_once('../../classes/smarty/SmartyAj.class.php');

 
 
require_once('../../classes/solfileitem.php');
 
	

setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');

 

$_fi=new SolFileItem;
	
$ret='';

// 
if(isset($_POST['action'])&&($_POST['action']=="delete_file")){
	
	
	
	 
	if(isset($_POST['id'])) $id=abs((int)$_POST['id']);
	else{
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		include("404.php");
		die();
	}	
	
	$_fi->Del($id);
	 
	
} 
//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
echo $ret;	
?>