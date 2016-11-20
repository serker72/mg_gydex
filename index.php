<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/newsgroup.php');
require_once('classes/otzgroup.php');
 
require_once('classes/linksgroup.php');
require_once('classes/numgroup.php');    



$lg=new LangGroup();
//��������� ����� ����
require_once('inc/lang_define.php');




//����� �� �������
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

//�������� �����
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//�������� �����
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));


$smarty->assign('do_index', 1);
$smarty->assign('do_follow', 1);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

//������ � �������
$has_slider=true;
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//������ � ����� ����
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//����� �������
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');


$smarty->display('common_top.html');
unset($smarty);


$smarty=new SmartyAdm;
$content='';

//���������
/*$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);

*/

//���� 2
$_ml_index=new MmenuList;
$mmenu2=$_ml_index->GetArr(array('is_menu_2'=>1), $lang, array(' ord_2 desc ')) ;
$smarty1=new SmartyAdm;
$smarty1->assign('mmenu2',$mmenu2);
$tmp=$fi->GetItem('parts/razd8-'.$_SESSION['lang'].'.txt');
$smarty1->assign('text',  stripslashes($tmp));

$smarty->assign('mmenu2', $smarty1->fetch('index_menu2.html'));


//������������

$smarty1=new SmartyAdm;
$smarty->assign('benefits', $smarty1->fetch('index_benefits.html'));

//������

$og=new OtzGroup;
$smarty->assign('opinions', $og->GetItemsCli('index_opinions.html'));


//����� �������
$smarty1=new SmartyAdm;
$tmp=$fi->GetItem('parts/razd4-'.$_SESSION['lang'].'.txt');
//$smarty1->assign('text', stripslashes($tmp));
//$smarty->assign('text',  $smarty1->fetch('index_how.html') );
$smarty->assign('text_home_1', stripslashes($tmp));

//������ � ������
$_nums=new NumGroup;
$smarty1=new SmartyAdm;
$nums=$_nums->GetItemsRandCli();
$smarty1->assign('nums', $nums);
$smarty->assign('numbers', $smarty1->fetch('index_numbers.html'));


//�������

$og=new LinksGroup;
//$smarty->assign('clients', $og->GetItemsCli('index_clients.html'));
$smarty->assign('clients', $og->GetItemsByIdCli('index_clients.html', '', '', '', 15));
$smarty->assign('services', $og->GetItemsByIdCli('index_services.html', '', '', '', 16));


//������� �� �������
if(HAS_NEWS){
    $ph_g = new NewsGroup();
//	echo $ph_g->GetItemsByIdCli('news/items.html', '','', '', 0, $lang);
    $smarty->assign('news', $ph_g->GetItemsRecent('news/main_page_items.html',  LANG_CODE, 2, $main_news));
}
 
//echo "<h1>$l[lang_name]</h1>";






$smarty->assign('content', $content);

$smarty->display('index.html');




//������ ���
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//������ � �������
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//������ � ������ ��������
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');



//������ � ����� ����
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);

?>