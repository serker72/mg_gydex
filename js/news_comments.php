<?
session_start();
header('Content-type: text/html; charset=windows-1251');

require_once('../classes/global.php');
 

 
 
require_once('../classes/commentitem.php');
 
	

setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');

 

$_fi=new CommentItem;
	
$ret='';

// 
if(isset($_POST['action'])&&($_POST['action']=="send_comment")){
	
	if(isset($_POST['id'])) $id=abs((int)$_POST['id']);
	else{
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		include("404.php");
		die();
	}	
	
	
	$params=array();
	$params['item_id']=$id;
	$params['tablename']='news';
	$params['pdate']=time();
	$params['name']=SecStr(iconv('utf-8', 'windows-1251', $_POST['name']));
	$params['email']=SecStr(iconv('utf-8', 'windows-1251', $_POST['email']));
	$params['txt']=SecStr(iconv('utf-8', 'windows-1251', $_POST['txt']));
	$params['is_active']=0 ; //abs((int)$_POST['is_active']);
	$params['is_new']=1; //abs((int)$_POST['is_new']);
	
	
	$_fi->Add($params);
	$ret="Ваш комментарий успешно добавлен и отправлен на модерацию. Спасибо!";
}
//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
echo $ret;	
?>