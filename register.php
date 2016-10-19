<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/authuser.php');
require_once('classes/program_group.php');
require_once('classes/program_item.php');

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

 

$au=new AuthUser();
//проверим авторизацию
$profile=$au->Auth();

/*if($profile!==NULL){
	//незачем восстанавливать пароль, если авторизованы
	header("Location: /index.php");
	die();
}*/

$rf=new ResFile(ABSPATH.'cnf/resources.txt');
 

//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",'Заявка на регистрацию в CRM GYDEX');

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

$current_mid=-1;

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


 




$content='';
 	
		

$sm1=new SmartyAdm;

$sm2=new SmartyAdm;



$content=$sm2->fetch('register_crm.html'); 
 
 
 
// echo $content;
 $sm1->assign('mm', array('name'=>'Регистрация'));
 $sm1->assign('content', $content);
 
 $sm1->assign('has_no_slogan', true);
 $sm1->display('razd/page_simple.html');
 
 
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
