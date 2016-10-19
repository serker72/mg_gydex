<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/dictattdisp.php');
require_once('../classes/mmenulist.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//административная авторизация
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


if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

if(!isset($_GET['kind']))
	if(!isset($_POST['kind'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $kind = $_POST['kind'];		
else $kind = $_GET['kind'];		
$kind=abs((int)$kind);
if(($kind!=1)&&($kind!=2)&&($kind!=3)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}


//проверка глобального режима работы
if(!isset($_GET['mode']))
	if(!isset($_POST['mode'])) {
		$mode=1;
	}
	else $mode = $_POST['mode'];		
else $mode = $_GET['mode'];		
$mode=abs((int)$mode);
$disp=new DictAttDisp($mode);
//пишем в сессию
$_SESSION['mode']=$disp->GetWorkMode();
//echo $disp->GetWorkMode();

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="420"  class="pole">
			<form action="eddicts.php" name="locr" id="locr">
			
			<input type="button" name="doChangeSost" id="doChangeSost" value="Все словари..." onclick="winop('alldicts.php?kind=<?=$kind?>&id=<?=$id?>','900','600', 'alldicts');">
&nbsp;
		<input type="button" name="closer" id="closer" value="Закрыть текущее окно" onclick="opener.location.reload(); window.close();">&nbsp;&nbsp;
			<input type="button" value="Обновить" onclick=" window.location.reload();">
			<input type="hidden" name="kind" id="kind" value="<?=$kind?>">
			<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
			
			
			<h4 style="margin: 3px 0 0 0;">
			<?
			if($mode==1){
				$mi=new MmenuItem();
				$mm=$mi->GetItemById($id,LANG_CODE);
				echo 'Раздел <a href="razds.php?id='.$id.'" target="_blank">'.stripslashes($mm['name']).'</a>';
			}else if($mode==3){
				$gd=new PriceItem();
				$good=$gd->GetItemById($id,LANG_CODE);
				echo 'Товар <a href="ed_price.php?id='.$id.'&action=1" target="_blank">'.stripslashes($good['name']).'</a>';
			}
			?>
									
			- прикрепленные словари:</h4>	
			
			<strong>выберите другой <?if($mode==1) echo 'раздел'; else if($mode==3) echo 'товар';?>: <select name="id" id="id" style="width: 220px;" onchange="m=document.getElementById('locr'); m.submit();"><?
			if($mode==1){
				$ml=new MmenuList();
				echo $ml->GetItemsOptByParentIdLangId($id,0,LANG_CODE,'name',false);//GetItemsOptByLang_id($id,'name',LANG_CODE);
			}else if($mode==3){
				$gd=new PriceItem();
				$good=$gd->GetItemById($id,LANG_CODE);
				
				$pg=new PriceGroup();
				$filter_params=Array();
				$filter_params['t.mid']=$good['mid'];
				echo $pg->GetItemsOptByLang_id($id,'name',LANG_CODE,$filter_params);
			}
			?>
</select></strong>
			
			</form>
			
			
			
			
			<iframe src="viewattdicts.php?kind=<?=$kind?>&id=<?=$id?>" name="viewattdicts" id="viewattdicts" width="400" height="280" marginwidth="0" marginheight="0"></iframe>
			<h4>Унаследованные словари:</h4>
			<iframe src="viewattdicts.php?kind=2&id=<?=$id?>" name="viewattdicts1" id="viewattdicts1" width="400" height="200" marginwidth="0" marginheight="0"></iframe>
				
		
		
		
		
		
		</td>
		<td width="*" class="pole">
		<h3>Свойства:</h3>
		<iframe src="viewnames.php" name="viewnames" id="viewnames" width="500" height="550" marginwidth="0" marginheight="0"></iframe>
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

$smarty->display('page_noleft_bottom.html');
?>