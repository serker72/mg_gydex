<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/newsitem.php');
require_once('classes/commentgroup.php');
require_once('classes/newsfilegroup.php');

if(!HAS_NEWS){
	
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	
	//header("Location: /404.php");
	//die();
}


$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

$mi=new MmenuItem();
$ne=new NewsItem();


//проверим id
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		header('Location: /404.php');
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

$news=$ne->GetItemById($id,$lang,1);
if($news==false){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}


$mm=$mi->GetItemById($news['mid'],$lang,1);
$current_mid=$mm['id'];
if($mm==false){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$m_ex=$mi->CheckFullExistance($news['mid'],$lang,1);
if($m_ex==false){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
if($mm['is_news']!=1){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}



//вывод из шаблона
require_once('classes/template.php');

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;


if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.''.$l['lang_meta']);

if(strlen(stripslashes($news['title']))==0) 
	$smarty->assign("SITETITLE",$news['name'].' &gt; '.SITETITLE);
else $smarty->assign('SITETITLE',stripslashes($news['title']));

if(strlen(stripslashes($news['meta_keywords']))==0) 	
	$smarty->assign('keywords','');
else
	$smarty->assign('keywords',stripslashes($news['meta_keywords']));
			

if(strlen(stripslashes($news['meta_description']))==0) 	
	$smarty->assign('description',trim(strip_tags(stripslashes($news['small_txt']))));
else
	$smarty->assign('description',stripslashes($news['meta_description']));
		

$smarty->assign('do_index', 1);
$smarty->assign('do_follow', 1);

$canonical=SITEURL.$ne->ConstructUrl($id, $lang,NULL,1);
$smarty->assign('canonical', $canonical);


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
unset($smarty);


$smarty_content=new SmartyAdm;

//навигация
$smarty_content->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, 'tpl/navi.html','tpl/naviitem.html','tpl/naviitem.html','',' / ',  true));

 
foreach($mm as $k=>$v) $mm[$k]=stripslashes($v);
$smarty_content->assign('mm', $mm);
foreach($news as $k=>$v) $news[$k]=stripslashes($v);
$news['pdate']=DateFromYmd($news['pdate']);
$smarty_content->assign('news', $news);


//блок комментариев
$_cg=new CommentGroup;
$_cg->GetNode($news['id'], 'news', 'news/page.html', 1, false, $comments);

$smarty_content->assign('comments', $comments);


$_fg=new NewsFileGroup;
//блок файлов
$smarty_content->assign('pictures', $_fg->GetImagesArr($id,'news_file.html', '/news_file_view.html'));

$smarty_content->assign('files', $_fg->GetFilesArr($id,'news_file.html', '/news_file_view.html'));

//print_r($_fg->HasImages($news['id']));

//блок фотографий



$smarty_content->display('news/page.html');

//нижний код

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//работа с футером
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//работа с правой колонкой
/*$ml2=new MmenuList;

if($mm['parent_id']!=0)
	$menu_to_draw=$ml2->GetClientHierMenu('vmenu.html', 'tpl/mmenu-test-2.html', 'tpl/mmenu-test-3.html', 0, $current_mid, $lang, true);
else 
	$menu_to_draw=$ml2->GetSubsByIdCli($current_mid, 'submenu.html', 'tpl/submenu_rows.html', 'tpl/submenu_item.html', $lang, 'img/bullet-sub.gif');
*/
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