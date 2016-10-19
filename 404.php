<?
//header("HTTP/1.1 404 Not Found", true, 404);
header("Status: 404 Not Found");


session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/resoursefile.php');
require_once('classes/sitetree.php');

$lg=new LangGroup();
//определим какой язык
if(isset($_GET['lang'])){
	$lang=abs((int)$_GET['lang']);
	$li=new LangItem();
	$l=$li->GetItemById($lang,1);
	if($l!=false){
		$_SESSION['lang']=$lang;
	}else{
		//header("Location: 404.php");
		die();
	}
}



//хедер определения языка
if(isset($_SESSION['lang'])){
	$lang=abs((int)$_SESSION['lang']);
	$li=new LangItem();
	$l=$li->GetItemById($lang,1);
	if($l==false){
		//header("Location: 404.php");
		die();
		$_SESSION['lang']=LANG_CODE;
	}else{
		
	}
}else{
	$_SESSION['lang']=LANG_CODE;
	$lang=LANG_CODE;
	$li=new LangItem();
	$l=$li->GetItemById(LANG_CODE);
}


$rf=new ResFile(ABSPATH.'cnf/resources.txt');

//вывод из шаблона
require_once('classes/filetext.php');
$fi=new FileText();
require_once('classes/smarty/SmartyAdm.class.php');
$smarty = new SmartyAdm;
 
$smarty->assign("SITETITLE",strip_tags($rf->GetValue('404.php','header',$lang)));

//ключевые слова
$smarty->assign('keywords','');


//описание сайта
$smarty->assign('description','');

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

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

$smarty->display('common_top.html');
 



$smarty=new SmartyAdm;
//навигация
$smarty->assign('navi','');






$smarty->assign('name',  "".$rf->GetValue('404.php','header',$lang)."");;
$content= "".$rf->GetValue('404.php','text',$lang)."<p>";


//нарисуем дерево сайта
$tree=new SiteTree($lang);
$tpls=Array(); 
$tpls['level']='tree.html';
//$tpls['item']='tpl/tree/item.html';
$tree->SetTemplates($tpls);
$tree->SetNewPages(false);
$content.= $tree->DrawTreeCli();


$smarty->assign('content',$content);
$smarty->display('common_page.html');
//нижний код

$smarty = new SmartyAdm;
 

//работа с футером
$do_count=false;
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

die();
?>
