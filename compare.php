<?
session_start();
require_once('classes/global.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/goodscomp.php');

 if (!defined('_SAPE_USER')){
        define('_SAPE_USER', 'c4e1d40f6b5b331a411274d200b17802'); 
    }
    require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'); 
    $sape_context = new SAPE_context();
	$sape = new SAPE_client();


if(!HAS_PRICE){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

$comp=new GoodsComparison();
$pi=new PriceItem();

//добавка товара к списку сравнения
if(isset($_GET['doAdd'])){
	foreach($_GET as $k=>$v){
		if(eregi("^id",$k)){
			$good_id=abs((int)$v);
			$good=$pi->GetItemById($good_id, $lang, 1);
			if($good!=false){
				$comp->Add($good_id);
			}
		}
	}
	
	//$backurl=getenv('HTTP_REFERER');
	//header('Location: '.$backurl);
	header('Location: /compare.php');
	die();
}

//очистка сессии сравнения
if(isset($_GET['doClear'])){
	$comp->Clear();
	$backurl=getenv('HTTP_REFERER');
	header('Location: '.$backurl);
	die();
}

//удаление товара из сессии сравнения
if(isset($_GET['doDel'])){
	foreach($_GET as $k=>$v){
		if(eregi("^id",$k)){
			$good_id=abs((int)$v);
				$comp->Del($good_id);
		}
	}
	//$backurl=getenv('HTTP_REFERER');
	header('Location: /compare.php');
	die();
}


$rf=new ResFile(ABSPATH.'cnf/resources.txt');
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

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

$smarty->display('mini_top_top.html');
unset($smarty);

?>

<table width="*" border="0" cellspacing="1" cellpadding="8">
<tr align="left" valign="top">
	<td width="*">
	<h1><?=$rf->GetValue('compare.php','compare_title',$lang)?></h1>
	</td>
	<td width="20%" align="center">
	<input type="button" value="<?=$rf->GetValue('compare.php','go_back',$lang)?>" onclick="history.back();">
	</td>
	<td width="30%" align="left">
	<strong><?=$rf->GetValue('compare.php','compare_address_title',$lang)?></strong><br>
	<?
	$addr=SITEURL.'/compare.php?doAdd=1';
	foreach($_SESSION[$comp->GetSessionName()] as $k=>$v) $addr.='&id_'.$k.'='.$k;
	?>
	<a href="<?=$addr?>"><?=$rf->GetValue('compare.php','compare_address',$lang)?></a>
	</td>
	
</tr>
</table>


<?
//шаблоны вывода
$tpls=Array();
$tpls['alltable']='compare/alltable.html';
$tpls['common_row']='';
$tpls['merged_row']='';
$tpls['common_cell']='';
$tpls['action_cell']='';

$comp->SetTemplates($tpls);
echo $comp->Compare($lang);
?>

<p>
<div align="center"><input type="button" value="<?=$rf->GetValue('compare.php','do_clear',$lang)?>" onclick="location.href='/compare.php?doClear=1';">  <input type="button" value="<?=$rf->GetValue('compare.php','do_close',$lang)?>" onclick="window.close();"></div>
<?

//нижний код
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->display('mini_bottom.html');
unset($smarty);

?>
