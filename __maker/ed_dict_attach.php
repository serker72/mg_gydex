<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/priceitem.php');
require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/alldictitem.php');
require_once('../classes/dictattachitem.php');
include('../editor/fckeditor.php');
require_once('../classes/dictattdisp.php');

require_once('../classes/attkindsgroup.php');


if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//административна€ авторизаци€
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


//режим работы
if(!isset($_SESSION['mode'])){
	$mode=1;
}else $mode=abs((int)$_SESSION['mode']);
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();
//echo $mode;


$li=new AllDictItem();
$att=new DictAttachItem();

if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;

if(($action==1)||($action==2)){
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			//header('Location: index.php');
			echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	$attach=$att->GetItemById($id);
	if($attach==false){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
}


if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$params['attach_code']=abs((int)$_POST['kind']);

	$att->Edit($id,$params);
	
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
	
}



require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->assign('no_header', true);
$smarty->display('page_noleft_top.html');

?>

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
	<form action="ed_dict_attach.php" method="post" name="inpp" id="inpp">
	<h3>–едактирование прикреплени€ словар€</h3>
	
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<strong>—ловарь:</strong> 
	<?
	$dict=$li->GetItemById($attach['dict_id']);
	echo stripslashes($dict['name']);
	?><br>
	

	<strong>прикреплен к:</strong> 
	<?
	if($mode==1){
		$mi=new MmenuItem();
		$mm=$mi->GetItemById($attach['key_value']);
		
		echo 'разделу '.stripslashes($mm['name']);
	}else if($mode==3){
		$mi=new PriceItem();
		$mm=$mi->GetItemById($attach['key_value']);
		
		echo 'товару '.stripslashes($mm['name']);
	}
	?><p>
	
	<strong>способ прикреплени€:</strong><br>
	<select name="kind" id="kind">
	<?
	$kg=new AttKindsGroup();
	echo $kg->GetItemsOpt($mode,$attach['attach_code'], false);
	?>
	</select>
	
	<p>
	<input type="submit" name="doInp" value="¬нести изменени€">
	
	<input type="button" value="«акрыть текущее окно" onclick="window.close();">
	
	</form>
	
	
	</td>
	</tr>
	</table>
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);
$smarty->assign('no_footer', true);

$smarty->display('page_noleft_bottom.html');
?>