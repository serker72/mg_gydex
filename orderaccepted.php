<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/authuser.php');

if((!HAS_BASKET)||(!HAS_PRICE)){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//��������� ����� ����
require_once('inc/lang_define.php');



$rf=new ResFile(ABSPATH.'cnf/resources.txt');
 

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
$smarty->assign('metalang',$rss_lnk.'	<meta http-equiv="Robots" content="none">'.$l['lang_meta']);

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
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);


?>

<h2><?=$rf->GetValue('orderaccepted.php','accepted_title',$lang);?></h2>


<?if(isset($_GET['order_id'])){
	?>
	<strong><?=$rf->GetValue('orderaccepted.php','accepted_pre_text',$lang);?></strong><p>
	
	<?=$rf->GetValue('orderaccepted.php','accepted_text',$lang);?> <strong><?=abs((int)$_GET['order_id'])?></strong><p>
	
	<?=$rf->GetValue('orderaccepted.php','accepted_post_text',$lang);?>
	
	<?
}?>
<br>
<br>
<input type="button" name="tomain" value="<?=$rf->GetValue('orderaccepted.php','to_index_caption',$lang);?>" onclick="location.href='/index.php';"><br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


<?
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
