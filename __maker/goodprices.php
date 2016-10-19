<?
session_start();
require_once('../classes/global.php');
require_once('../classes/pricedisp.php');


//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$disp=new PriceDisp();

if(!isset($_GET['good_id']))
	if(!isset($_POST['good_id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $good_id = $_POST['good_id'];		
else $good_id = $_GET['good_id'];		
$good_id=abs((int)$good_id);



require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');


$smarty->display('page_noleft_top.html');

?>

	<table width="100%" border="0" cellspacing="2" cellpadding="5">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<input type="button" value="Закрыть" onclick="opener.location.reload(); window.close();">&nbsp;
		<input type="button" value="Обновить" onclick=" window.location.reload();">
		
		<?
		echo $disp->GetPriceTableByGoodId($good_id);
		?>
		
	
	
	</td>
	</tr>
	</table>
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>