<?
session_start();
require_once('../classes/global.php');
require_once('../classes/orderitem.php');
require_once('../classes/orderitemitem.php');
require_once('../classes/useritem.php');
require_once('../classes/statusgroup.php');
require_once('../classes/langitem.php');
if(!(HAS_PRICE&&HAS_BASKET)){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
}


//административная авторизация
require_once('inc/adm_header.php');

$oi=new OrderItem();

if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 15)) {}
else{
	header('Location: no_rights.php');
	die();		
}

//проверим id
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

$order=$oi->GetItemById($id);
if($order==false){
	//header('Location: index.php');
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();	
}

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=ITEMS_PER_PAGE;
	}else $to_page = $_POST['to_page'];	
}else $to_page = $_GET['to_page'];	
$to_page=abs((int)$to_page);	


if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	
	if($kind==1){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 15)) {
				  $r=new OrderItemItem();
				  $params=Array();
				  $params['comment']=SecStr($_POST[$lid.'_comment']);
				  $params['quantity']=abs((int)$_POST[$lid.'_quantity']);				
				  $params['currency_id']=abs((int)$_POST[$lid.'_currency_id']);				
				  $params['price_value']=abs((float)$_POST[$lid.'_price_value']);
				  
				  
				  //пробежимся по опциям заказа
				  $option_values=Array();
				  foreach($_POST as $kk=>$vv){
					  if(eregi($lid."_name_id",$kk)){
						  //echo "$kk= $vv<br>";
						  if($vv!=0) $option_values[]=$vv;
					  }
				  }
				  $r->Edit($lid,$params,$option_values);
				}else{
					header('Location: no_rights.php');
					die();		
				}
				
			}
		}
	}
//	die();
	
	
	if($kind==2){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удаляем 
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 15)) {
					$r=new OrderItemItem();
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					die();	
				}
			}
		}
	}
	
	header('Location: vieworderitems.php?&from='.$from.'&to_page='.$to_page.'&id='.$id);
	die();
}



require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' - Позиции заказа');

$smarty->display('page_noleft_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<a href="ed_order.php?id=<?=$id?>&action=1">к описанию заказа</a>
		<h3>Позиции заказа №<?=$id?> </h3>
		
		<?
		$templates=Array();
		$templates['all_menu_template']='orders/order_items.html';
		$templates['menuitem_template']='tpl/orders/items/itemsrow.html';
		$templates['menuitem_template_blocked']='';
		$templates['razbivka_template']='tpl/orders/items/to_page.html';
		$templates['items_set']='orders/options_set.html';
		$templates['item_row']='tpl/orders/items/options_item.html';
		echo $oi->GetOrderItems($id,$from,$to_page,$templates,$order['lang_id'],$order['clid']);
		
		?>
		
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