<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/paperitem.php');
require_once('classes/papersgroup.php'); 
require_once('classes/newsgroup.php'); 

if(!HAS_PAPERS){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$lg=new LangGroup();
//��������� ����� ����
require_once('inc/lang_define.php');
/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}*/

// ��������� �����
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);	


$ph=new PaperItem();	
$photo=$ph->GetItemById($id,$lang,1);

if($photo==false){
	
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
	


$mi=new MmenuItem();
/*if(HAS_URLS){
	//�������� �� ����� �������
	if(!isset($_GET['name']))
		if(!isset($_POST['name'])) {
			header('Location: /404.php');
			die();
		}
		else $name = $_POST['name'];		
	else $name = $_GET['name'];		
	$name=SecStr($name,10);
	if((strlen($name)>0)&&($name!='/')){
		//��������, ���� �� ����� ���������
		$mm=$mi->GetItemByURL($name,$lang,1);
	}else{
		header("Location: /404.php");
		die();
		$mm=false;
	}
}else{*/
	//�������� pphto[mid
	$mm=$mi->GetItemById($photo['mid'],$lang,1);
	
	//�������� ������������� ���� �������� �� ���� � �������
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
	//echo '������! '.$mm['name'];
}
if($mm['is_papers']!='1'){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
$current_mid=$photo['mid'];

//����� �� �������
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
if(strlen(stripslashes($mm['title']))==0) 
	$smarty->assign('SITETITLE',stripslashes($photo['name']).': '.SITETITLE); //.' &gt; '.stripslashes($mm['name']).' &gt; ');
else $smarty->assign('SITETITLE',stripslashes($photo['title']));

//�������� �����
if(strlen(stripslashes($photo['meta_keywords']))==0) {
  $tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
  $smarty->assign('keywords',stripslashes($tmp));
}else $smarty->assign('keywords',stripslashes($photo['meta_keywords']));


//�������� �����
if(strlen(stripslashes($photo['meta_description']))==0) {
  $tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
  $smarty->assign('description',stripslashes($tmp));
}else $smarty->assign('description',stripslashes($photo['meta_description']));

$smarty->assign('do_index', $photo['do_index']);
$smarty->assign('do_follow', $photo['do_follow']);

$canonical=SITEURL.$ph->ConstructPath($id,  $lang);
$smarty->assign('canonical', $canonical);


if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

//������ � �������
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



//���������
//$smarty->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, 'tpl/navi.html','tpl/naviitem.html','tpl/naviitem.html','','&nbsp;&gt; '));

$smarty->display('common_top.html');
unset($smarty);
?>



<?
//�������� ������ �� paper
//����� ������

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
		
$smarty->assign('title', stripslashes($photo['name']));
$smarty->assign('alttext', htmlspecialchars(stripslashes($photo['name'])));

$smarty->assign('annot', stripslashes($photo['small_txt']));

$smarty->assign('photo', stripslashes($photo['photo_big']));
$smarty->assign('text', stripslashes($photo['big_txt']));


//���������
$smarty->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, 'tpl/navi.html','tpl/naviitem.html','tpl/naviitem.html','',' / ',  true));

foreach($mm as $k=>$v) $mm[$k]=stripslashes($v);
$smarty->assign('mm', $mm);

$smarty->assign('FEEDBACK_PHONE', FEEDBACK_PHONE);


//����� ���������� � ��������� ��������, ���� ������ �� ���
$materials=array();
$_pg=new PapersGroup;
$materials=$_pg->GetPrevNextArr($id, $lang);
//var_dump($materials);

$smarty->assign('materials', $materials);

//��������� �������
$_ng=new NewsGroup;

$_ng->GetItemsRecent('news/recent_news.html', $lang, 2, $news);
 
$smarty->assign('news', $news);
 
 
 

$smarty->display('papers/total.html');
unset($smarty);
?>







<?
//������� ���� � ����� �����
if((strlen($mm['photo_for_goods'])!=0)&&($mm['photo_for_goods']!='img/no.gif')){
	echo "<br clear=\"all\"><img src=\"/$mm[photo_for_goods]\" alt=\"\" border=\"0\"><br>";
}

//������ ���

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
