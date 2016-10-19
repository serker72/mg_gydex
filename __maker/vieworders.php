<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/calendar_ord.php');
require_once('../classes/ordersgroup.php');
require_once('../classes/useritem.php');
require_once('../classes/orderitem.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 14)) {}
else{
	header('Location: no_rights.php');
	die();		
}


$razd=new MmenuItem();
$og=new OrdersGroup();

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


if(!isset($_GET['sortmode'])){
	if(!isset($_POST['sortmode'])){
		$sortmode=0;
	}else $sortmode = $_POST['sortmode'];	
}else $sortmode = $_GET['sortmode'];	
$sortmode=abs((int)$sortmode);	
if($sortmode>5) $sortmode=0;

//делаем переход по номеру заказа, значит сортмоде=5
if(isset($_POST['goOrderId'])){
	$sortmode=5;
}


if($sortmode==3){
	//ожидаем дату, если нет - то дату текущую
	if(!isset($_GET['pdate']))
		if(!isset($_POST['pdate'])) $pdate=date("Y-m-d");
		else $pdate = $_POST['pdate'];		
	else $pdate = $_GET['pdate'];	
	$pdate=SecStr($pdate);
}
if($sortmode==4){
	//ожидаем айди клиента, если такового нет - значит вылетаем
	if(!isset($_GET['clid'])){
		if(!isset($_POST['clid'])){
			header("Location: index.php");
			die();
		}else $clid = $_POST['clid'];	
	}else $clid = $_GET['clid'];	
	$clid=abs((int)$clid);	
}

if($sortmode==5){
	//ожидаем айди заказа, если такового нет - значит вылетаем
	if(!isset($_GET['order_id'])){
		if(!isset($_POST['order_id'])){
			header("Location: index.php");
			die();
		}else $order_id = $_POST['order_id'];	
	}else $order_id = $_GET['order_id'];	
	$order_id=abs((int)$order_id);	
	
}

/*foreach($_POST as $k=>$v){
		echo " $k  $v<br>";
	}
	//die();
*/
	

if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	
	if($kind==1){
		//Обновляем базу
		//получим список всех языков
		//$langs=Array();
		//$langgr=new LangGroup();
		//$langs=$langgr->GetLangsIdList();
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 14)) {
				  $r=new OrderItem();
				  $params=Array();
				  $params["status_id"]=abs((int)$_POST[$lid.'_status_id']);
				  $r->Edit($lid,$params);
				}else{
				  header('Location: no_rights.php');
				  die();		
				}
			}
		}
	}
	//die();
	
	
	if($kind==2){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удаляем 
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 14)) {
					$r=new OrderItem();
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					  die();		
				}
			}
		}
	}
	
	
	
	// die();
	if($sortmode==5) header('Location: vieworders.php?&from='.$from.'&to_page='.$to_page.'&sortmode=5&order_id='.$order_id);
	else if($sortmode==4) header('Location: vieworders.php?&from='.$from.'&to_page='.$to_page.'&sortmode=4&clid='.$clid);
	else if($sortmode==3) header('Location: vieworders.php?&from='.$from.'&to_page='.$to_page.'&sortmode=3&pdate='.$pdate);
	else header('Location: vieworders.php?&from='.$from.'&to_page='.$to_page.'&sortmode='.$sortmode);
	die();
}
	
require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Заказы - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=24;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);




//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;



$_context->AddContext(new ContextItem( '', '', "", "Новые заказы (".$og->CalcItemsByMode(0).")", "vieworders.php?sortmode=0", false , $global_profile  ));


$_context->AddContext(new ContextItem( '', '', "", "Заказы в обработке (".$og->CalcItemsByMode(1).")", "vieworders.php?sortmode=1", false , $global_profile  ));

$_context->AddContext(new ContextItem( '', '', "", "Выполненные заказы (".$og->CalcItemsByMode(2).")", "vieworders.php?sortmode=2", false , $global_profile  ));
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "vieworders.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 

$_bc->AddContext(new BcItem('Список заказов', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);






$smarty->display('page_top.html');
	
?>

	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
	    <td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr align="left" valign="top">
			<td width="*">
			 <h1><?
			//формируем заголовок
			switch($sortmode){
				case 0:
					echo 'Новые заказы';
					$params=NULL;
				break;
				case 1:
					echo 'Заказы в обработке';
					$params=NULL;
				break;
				case 2:
					echo 'Выполненные заказы';
					$params=NULL;
				break;
				case 3:
					echo 'Заказы по дате: '.DateFromYmd($pdate);
					$params=Array(); $params['pdate']=$pdate;
				break;
				case 4:
					echo 'Заказы покупателя: ';
					$ui=new UserItem();
					$user=$ui->GetItemById($clid);
					if($user!=false) echo '<a href="ed_client.php?action=1&id='.$clid.'" target="_blank">'.stripslashes($user['username']).'</a>';
					$params=Array(); $params['clid']=$clid;
				break;
				case 5:
					echo 'Заказ по номеру: '.$order_id;
					$params=Array(); $params['order_id']=$order_id;
				break;
				
			};
		?></h1>
			</td>
			<td align="right" valign="bottom">
			<form action="vieworders.php" method="get" name="bynum" id="bynum">
			<input type="hidden" name="sortmode" value="5">
			<b>По номеру заказа:</b>
			<input type="text" name="order_id" id="order_id" size="20" maxlength="20" value="<?=$order_id?>">
			<input type="submit" name="goOrderId" value="Перейти">
			</form>
			<script  language="JavaScript">  
			  var  frmvalidator    =  new  Validator("bynum");  
			  frmvalidator.addValidation("order_id","req","Заполните  поле  По номеру заказа!");    
			  frmvalidator.addValidation("order_id","num","В  поле  По номеру заказа допустимы только цифры!");    
			</script>  
			</td>
		</tr>
		</table>
		
		
		
		
		<?
		
		$og->SetTemplates('orders/items.html', 'tpl/orders/itemsrow.html', 'tpl/orders/itemsrow_blink.html', 'tpl/orders/to_page.html');
		echo $og->GetItems($sortmode,$params,$from,$to_page);
		?>
		
		
		
		
		</td>
	    <td width="200" align="right" valign="top">
		<?
			$c = new CalendarOrd();
			//$c= new Calendar();
			if(!isset($pdate)) echo $c->Draw(date('Y-m-d'),'vieworders.php','pdate','&sortmode=3&from='.$from,date('Y-m-d'),0);	
			else echo $c->Draw($pdate,'vieworders.php','pdate','&sortmode=3&from='.$from,$pdate,0);	
		?>
		
		<a href="viewstatus.php" target="_blank">все статусы...</a>
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

$smarty->display('page_bottom.html');
?>	