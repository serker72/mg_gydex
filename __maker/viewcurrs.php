<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
//require_once('../classes/langgroup.php');
//require_once('../classes/langitem.php');

require_once('../classes/currencygroup.php');

require_once('../classes/v2/currencygroup_new.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 23)) {}
else{
	header('Location: no_rights.php');
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
		//echo "$key => $val <br>";
			if(eregi("_do_process",$key)){
				
				
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 23)) {

				
				  $r=new CurrencyItem();
				  $params=Array(); $lparams=Array();
				  if($_POST['is_base_shop']==$lid) {
					  $params['is_base_shop']=1;
				  }else  $params['is_base_shop']=0;
				  if($_POST['is_base_rate']==$lid) {
					  $params['is_base_rate']=1;
				  }else  $params['is_base_rate']=0;
				  $r->Edit($lid,$params);
				}else{
					header('Location: no_rights.php');
					die();
				}
				
			}
		}
	}
	
	
	
	if($kind==2){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удаляем 
				
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 23)) {
					$r=new CurrencyItem();
				
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					die();
				}
				
			}
		}
	}
	
	header('Location: viewcurrs.php?from='.$from.'&to_page='.$to_page);
	die();
}

//работа с правилами использования валют и языков
foreach($_POST as $key=>$val){
	//добавка правила
	if(eregi("_addRule",$key)){
		$curr_id=abs((int)eregi_replace("addRule", "", $key));
		$lang_id=abs((int)$_POST[$curr_id.'_lang_code']);
		$params=Array(); $params['lang_id']=$lang_id; $params['value_id']=$curr_id; 
		
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 23)) {
			$rules=new CurrUse();
			$rules->Add($params);
		}else{
			header('Location: no_rights.php');
			die();
		}
		
	}
}
//удаление правила
if(isset($_GET['action'])){
	$action=abs((int)$_GET['action']);
	if($action==2){
		$id=abs((int)$_GET['id']);
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 23)) {
			$rules=new CurrUse();
			$rules->Del($id);
		}else{
			header('Location: no_rights.php');
			die();
		}	
	}
	header('Location: viewcurrs.php?from='.$from.'&to_page='.$to_page);
	die();
}



require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Валюты магазина - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=28;
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



$_context->AddContext(new ContextItem( 11, 'a', "", "Создать валюту", "ed_curr.php?action=0", false , $global_profile  ));
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "viewcurrs.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 

$_bc->AddContext(new BcItem('Валюты магазина', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);




$smarty->display('page_top.html');
?>




	<?
	
	$l=new CurrencyGroupNew();
	
	echo $l->GetItems($from,$to_page, 'currs/items.html');
	
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