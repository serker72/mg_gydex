<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


require_once('../classes/pricesitem.php');
require_once('../classes/conditem.php');
require_once('../classes/condgroup.php');
require_once('../classes/mmenulist.php');
require_once('../classes/pricegroup.php');
require_once('../classes/goodspriceitem.php');


//административная авторизация
require_once('inc/adm_header.php');


if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 24)) {}
else{
	header('Location: no_rights.php');
	die();
}

$go=new PriceItem();
$pr=new PricesItem();
$disp=new PriceDisp();
$mi=new MmenuItem();

//режим работы
/*if(!isset($_SESSION['mode'])){
	$mode=3;
}else $mode=abs((int)$_SESSION['mode']);*/
if(!isset($_GET['mode']))
	if(!isset($_POST['mode'])) {
		$mode=3;
	}
	else $mode = $_POST['mode'];		
else $mode = $_GET['mode'];		
$mode=abs((int)$mode);

if(($mode!=1)&&($mode!=3)) $mode=3;

if(!isset($_GET['value_id']))
	if(!isset($_POST['value_id'])) {
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $value_id = $_POST['value_id'];		
else $value_id = $_GET['value_id'];		
$value_id=abs((int)$value_id);

if($mode==3){
	//работа с товаром
	$good=$go->GetItemById($value_id);
	if($good==false){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
}

if($mode==1){
	//работа с разделом
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
		$good=$mi->GetItemById($value_id);	
	}
	else{
		header('Location: no_rights.php');
		die();
	}
	
	if($good==false){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
}


//удаление условия использования
$redraw_flag=false;
foreach($_POST as $key=>$val){
	//добавка правила
	$redraw_flag=true;
	if(eregi("doDelCond_",$key)){
		$del_pr_id=abs((int)eregi_replace("doDelCond_", "", $key));
		
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
		
		  $r=new PricesItem();
		  $rr=$r->GetItemById($del_pr_id);
		  if($rr!=false){
			  //удаляем условие
			  $usl=new CondItem();
			  //echo $rr['cond_id'];
			  $usl->Del($rr['cond_id']);
		  }
		}else{
			header('Location: no_rights.php');
			die();
		}
	}
}
if($redraw_flag){
	header('Location: pricestogood.php?mode='.$mode.'&value_id='.$value_id);
	die();
}

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');



$smarty->display('page_noleft_top.html');

?>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="100%"  class="pole">
		<h3>Виды цен для <?if($mode==3) echo 'товара'; if($mode==1) echo 'раздела';?>: <?=stripslashes($good['name'])?></h3>
		<form action="pricestogood.php" method="post" name="inpp" id="inpp">
		<input type="hidden" name="value_id" value="<?=$value_id?>">
		<input type="hidden" name="mode" value="<?=$mode?>">		
		
		<input type="button"  value="Добавить цену..." onclick="winop('ed_pr_compact.php?action=0&mode=<?=$mode?>&value_id=<?=$value_id?>','500','400', 'edprice');">
		
		<input type="button"  value="Все цены..." onclick="winop('viewprices.php','900','600', 'prices');">
		
		<input type="button"  value="Обновить" onclick="location.reload();">
		
		<input type="button" name="closer" id="closer" value="Закрыть" onclick="opener.location.reload(); window.close();">
		
		<table cellspacing="8" cellpadding="2" border="0">
		<tr>
		    <td width="50%">
			<strong>Применяемые цены:</strong>
			<div style="width: 360px; height: 500px; border: 1px solid Black; overflow: scroll;">
			<?
				//echo $disp->DrawPricesById($value_id,LANG_CODE,'tpl/goodsprices/table4apply.html','tpl/goodsprices/row4apply.html',false,$mode);

				echo $disp->DrawPricesById($value_id,LANG_CODE,'price/goodspricesapply.html',false,$mode);
			?>
			</div>
			</td>
		    <td width="50%">
			<strong>Прочие цены:</strong>
			<div style="width: 360px; height: 500px; border: 1px solid Black; overflow: scroll;">
			<?
				//echo $disp->DrawPricesById($value_id,LANG_CODE,'tpl/goodsprices/table4apply.html','tpl/goodsprices/row4apply.html',true,$mode);
				echo $disp->DrawPricesById($value_id,LANG_CODE,'price/goodspricesapply.html',true,$mode);
			?>
			</div>
			
			
			</td>
		</tr>
		</table>

			
	</form>
		
		
	</td>
</tr>
</table>
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>