<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/statusgroup.php');
require_once('../classes/langgroup.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административна€ авторизаци€
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 14)) {}
else{
	header('Location: no_rights.php');
	die();		
}

$razd=new MmenuItem();

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
	
	
	if($kind==2){
		//ќбновл€ем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удал€ем 
				
				$lid=(int)$val;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 14)) {
					$r=new StatusItem();
				
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					die();	
				}
				
				
			}
		}
	}
	
	header('Location: viewstatus.php?&from='.$from.'&to_page='.$to_page);
	die();
}
	
	
	
	
	require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' - —татусы заказов');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличи€ модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-им€
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');
	
?>

	
	<?
	//навигаци€
	//echo $razd->DrawNavig($id, $lang_id,0,' список товаров ');
	?>
	<h2>—татусы заказов</h2>
	
	<?
	
	$pl=new StatusGroup();
	$pl->SetTemplates('orders/status.html','tpl/status/itemsrow.html','tpl/status/itemsrow_blocked.html','tpl/status/to_page.html');

	echo $pl->GetItems($from,$to_page);
	
	?>
	
	
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>	