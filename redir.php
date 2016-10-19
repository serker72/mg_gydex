<?
//файл редиректа дл€ формы отбора товара
//из любого файла -> в файл razds.php
//обеспечивает правильную трансл€цию URL при использовании пр€мой и кривой адресации
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/priceitem.php');
require_once('classes/dictattdisp.php');
require_once('classes/basket.php');
require_once('classes/propvalitem.php');
require_once('classes/propnameitem.php');

if((!HAS_PRICE)&&(!HAS_BASKET)){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//определим какой €зык
require_once('inc/lang_define.php');

/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}
echo getenv('QUERY_STRING');
die();*/

if(HAS_URLS){
	//конструируем путь по айди, подписываем к пути параметры
	//пересылаем на него
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: /404.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	$mi=new MmenuItem();
	$path='/'.$mi->ConstructPath($id,$lang,1,'/');
	
	foreach($_GET as $k=>$v){
		if(($k!='id')&&($k!='doSort')) $path.='&'.$k.'='.urlencode($v);
	}
	//echo $path; die();
	Header('Location: '.$path);
	die();
}else{
	Header('Location: /razds.php?'.getenv('QUERY_STRING'));
	die();
}
?>