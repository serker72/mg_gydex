<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/priceitem.php');
require_once('classes/propvalitem.php');
require_once('classes/propnameitem.php');

if(!HAS_PRICE){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}


$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

$mi=new MmenuItem();
$gd=new PriceItem();
$ph=new PropValItem();
$pn=new PropNameItem();
$di=new AllDictItem();


//проверим id
if(!isset($_GET['photo_id']))
	if(!isset($_POST['photo_id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $photo_id = $_POST['photo_id'];		
else $photo_id = $_GET['photo_id'];		
$photo_id=abs((int)$photo_id);

$photo=$ph->GetItemById($photo_id,$lang,1);
if($photo==false){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$pname=$pn->GetItemById($photo['name_id'],$lang,1);
if($pname==false){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}


$good=$gd->GetItemById($photo['item_id'],$lang,1);
if($good==false){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$mm=$mi->GetItemById($good['mid'],$lang,1);
if($mm==false){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}



//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.'	<meta http-equiv="Robots" content="none">'.$l['lang_meta']);
$smarty->assign('keywords','');
$smarty->assign('description','');

$smarty->assign('do_index', 1);
$smarty->assign('do_follow', 1);

$smarty->display('mini_top.html');
unset($smarty);



?>

<div align="center">
<h4><?=stripslashes($pname['name'])?></h4>
<img src="/<?=stripslashes($photo['photo_big'])?>" alt="" border="0"></div>
<div align="center"><br>
<?
$rf=new ResFile(ABSPATH.'cnf/resources.txt');
	
	$capt=$rf->GetValue('viewaddphoto.php','do_close',$lang);
?>
<input type="button" value="<?=$capt?>" onclick="window.close();"></div>

<div align="center">
<?
//вывод в полосе прокрутки
echo $di->ShowAddPhotosByDictIdGoodId($pname['dict_id'], $good['id'], $lang, 'good/addph_table.html', '','');

?></div>





<?

//нижний код
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->display('mini_bottom.html');
unset($smarty);

?>
