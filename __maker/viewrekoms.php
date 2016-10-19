<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


//require_once('../classes/dictattdisp.php');
require_once('../classes/priceitem.php');

require_once('../classes/rekom_group.php');
require_once('../classes/rekom_item.php');

if((!HAS_PRICE)||(!HAS_BASKET)){
	header("Location: index.php");
	die();
}

//административна€ авторизаци€
require_once('inc/adm_header.php');


if(!isset($_GET['good_id']))
	if(!isset($_POST['good_id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $good_id = $_POST['good_id'];		
else $good_id = $_GET['good_id'];		
$good_id=abs((int)$good_id);

$gd=new PriceItem();
$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {

	$good=$gd->GetItemById($good_id);
}else{
	header('Location: no_rights.php');
	die();	
}
if($good==false){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
}

$prg = new RekomGroup();

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_mini_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="50%"  class="pole">
		
			
		<input type="button" name="closer" id="closer" value="«акрыть текущее окно" onclick="opener.location.reload(); window.close();">&nbsp;&nbsp;
			<input type="button" value="ќбновить" onclick=" window.location.reload();">
			<h3>–екомендуемые товары дл€ товара "<?=stripslashes($good['name'])?>"</h3>
			
				<a href="javascript: winop('ed_rekom.php?action=0&good_id=<?=$good_id?>','800','600','ed_rekoms');">
			+добавить товары</a><br>

			<?
			echo $prg->GetItemsById($good_id);
			?>
			
			
		</td>
	</tr>
	</table>
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_mini_bottom.html');
?>