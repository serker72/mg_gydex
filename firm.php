<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/firmitem.php');
//require_once('classes/dictattdisp.php');




if(!HAS_PRICE){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');
/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}*/

// определим фирму
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);	


$ph=new FirmItem();	
$photo=$ph->GetItemById($id,$lang,1);

if($photo==false){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
	

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE.' &gt;  '.stripslashes($photo['name']));

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));


$smarty->assign('do_index', 1);
$smarty->assign('do_follow', 1);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

//работа с хедером
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//работа с гориз меню
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//левая колонка
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');



//навигация
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);

?>



<?
//выводить данные по firme
//через шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;



$smarty->assign('image_url', stripslashes($photo['photo_big']));
$im=GetImageSize(ABSPATH.stripslashes($photo['photo_big']));
if($im!=false){
	$smarty->assign('image_w',$im[0]);
	$smarty->assign('image_h',$im[1]);
}else{
	$smarty->assign('image_w',1);
	$smarty->assign('image_h',1);
}
			
$smarty->assign('firm_name', (stripslashes($photo['name'])));
$smarty->assign('alttext', htmlspecialchars(stripslashes($photo['name'])));
$smarty->assign('firm_descr', stripslashes($photo['info']));


$smarty->display('firms/total.html');
unset($smarty);

?>







<?

//нижний код
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//работа с футером
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//работа с правой колонкой
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');



//работа с гориз меню
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);
?>