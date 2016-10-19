<?
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
require_once('inc/lang_define.php');

$rf=new ResFile(ABSPATH.'cnf/resources.txt');
//вывод из шаблона

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE.' - '.$rf->GetValue('map.php','map_title',$lang));

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


$sm1=new SmartyAdm;

 $content='<h1>'.$rf->GetValue('map.php','map_title',$lang).'</h1>';

 $content.='<strong>'.$rf->GetValue('map.php','map_text',$lang).'</strong><p>';
 

 $tree=new SiteTree($lang);
$tpls=Array(); 
//$tpls['level']='tpl/tree/level.html';
$tpls['level']='tree.html';

$tpls['item']='tpl/tree/item.html';
$tree->SetTemplates($tpls);
$tree->SetNewPages(false);
$content.= $tree->DrawTreeCli();
 
// echo $content;
 $sm1->assign('mm', array('name'=>'Карта сайта'));
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
