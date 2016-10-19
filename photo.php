<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/photoitem.php');
//require_once('classes/dictattdisp.php');

 

if(!HAS_GALLERY){
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

// определим товар
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);	


$ph=new PhotoItem();	
$photo=$ph->GetItemById($id,$lang,1);

if($photo==false){
	
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
	


$mi=new MmenuItem();
/*if(HAS_URLS){
	//проверим по имени раздела
	if(!isset($_GET['name']))
		if(!isset($_POST['name'])) {
			header('Location: /404.php');
			die();
		}
		else $name = $_POST['name'];		
	else $name = $_GET['name'];		
	$name=SecStr($name,10);
	if((strlen($name)>0)&&($name!='/')){
		//проверка, есть ли такой подраздел
		$mm=$mi->GetItemByURL($name,$lang,1);
	}else{
		header("Location: /404.php");
		die();
		$mm=false;
	}
}else{*/
	//проверим pphto[mid
	$mm=$mi->GetItemById($photo['mid'],$lang,1);
	
	//проверка существования всех разделов на пути к данному
	$m_ex=$mi->CheckFullExistance($photo['mid'],$lang,1);
	if($m_ex==false){
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
//}
if($mm==false){
	
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}else{
	//echo 'найден! '.$mm['name'];
}
if($mm['is_gallery']!=1){
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
$current_mid=$photo['mid'];

//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
if(strlen(stripslashes($mm['title']))==0) 
	$smarty->assign('SITETITLE',SITETITLE.' &gt; '.stripslashes($mm['name']).' &gt; '.stripslashes($photo['name']));
else $smarty->assign('SITETITLE',SITETITLE.' &gt; '.stripslashes($mm['title']).' &gt; '.stripslashes($photo['title']));

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
$smarty->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, 'tpl/navi.html','tpl/naviitem.html','tpl/naviitem.html','','&nbsp;&gt; '));

$smarty->display('common_top.html');
unset($smarty);

?>



<?
//выводить данные по photo
//через шаблон
/*
$gt=new parse_class();
$gt->get_tpl('tpl/photogal/total.html');


$gt->set_tpl('{photo}', stripslashes($photo['photo_big']));
$gt->set_tpl('{text}', stripslashes($photo['big_txt']));
$gt->set_tpl('{alttext}', htmlspecialchars(stripslashes($photo['name'])));

//нижний ряд превью
$phg=new PhotosGroup();
$gt->set_tpl('{PREVIEWS}',$phg->GetGalView($id,$photo['mid'],$lang));

$gt->tpl_parse();
echo $gt->template;

*/

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
		

$smarty->assign('photo', stripslashes($photo['photo_big']));
$smarty->assign('text', stripslashes($photo['big_txt']));
$smarty->assign('alttext', htmlspecialchars(stripslashes($photo['name'])));

//нижний ряд превью
$phg=new PhotosGroup();
$smarty->assign('PREVIEWS',$phg->GetGalView($id,$photo['mid'],$lang));



$smarty->display('photogal/total.html');
unset($smarty);
?>







<?
//выведем фото с общей инфой
if((strlen($mm['photo_for_goods'])!=0)&&($mm['photo_for_goods']!='img/no.gif')){
	echo "<br clear=\"all\"><img src=\"/$mm[photo_for_goods]\" alt=\"\" border=\"0\"><br>";
}

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
