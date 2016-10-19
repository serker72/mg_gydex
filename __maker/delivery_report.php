<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/allmenu_template_group.php');

require_once('../classes/v2/delivery_templates.class.php');
require_once('../classes/v2/delivery_lists.class.php');
require_once('../classes/v2/delivery.class.php');

require_once('../classes/v2/delivery_report.class.php');

//административная авторизация
require_once('inc/adm_header.php');

$razd=new Delivery_Item;
$_list=new Delivery_Group;

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

 

if(!isset($_GET['step'])){
	if(!isset($_POST['step'])){
		$step=1;
	}else $step = $_POST['step'];	
}else $step = $_GET['step'];	
$step=abs((int)$step);	

if(($step<1)||($step>6)) $step=1;
 
if(!isset($_GET['action'])){
	if(!isset($_POST['action'])){
		$action=1;
	}else $action = $_POST['action'];	
}else $action = $_GET['action'];	
$action=abs((int)$action); 
 
$editable_status_ids=array(1);	
 
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	if($id!=0){
	
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
		
			$razdel=$razd->GetItemById($id);
		}else{
			header('Location: no_rights.php');
			die();		
		}

	}else{
		 header('Location: index.php');
			die();
		
	}
	//echo $razdel['parent_id']; die();
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
  
 

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Отчет по рассылке - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=46;
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



//меню рассылки
$_dmenu_id=50;
require_once('delivery_menu.php');
$smarty->assign("MODULE_HMENU",$vmenu);




require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

$_context->AddContext(new ContextItem( 26, 'r', "", "Обзорный отчет", "delivery_report.php?id=$id&step=1",  false , $global_profile  ));

$_context->AddContext(new ContextItem( 26, 'r', "", "Кому отправлена", "delivery_report.php?id=$id&step=2",  false , $global_profile  ));

$_context->AddContext(new ContextItem( 26, 'r', "", "Кем открыта", "delivery_report.php?id=$id&step=3",  false , $global_profile  ));

$_context->AddContext(new ContextItem( 26, 'r', "", "Кто кликал по ссылкам", "delivery_report.php?id=$id&step=4",  false , $global_profile  ));
$_context->AddContext(new ContextItem( 26, 'r', "", "Не открыта", "delivery_report.php?id=$id&step=5",  false , $global_profile  ));

$_context->AddContext(new ContextItem( 26, 'r', "", "Отписка от рассылки", "delivery_report.php?id=$id&step=6",  false , $global_profile  ));

$_context->AddContext(new ContextItem( "", '',  "", "Справка", "delivery_reports.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', 'Быстрые действия');


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('GYDEX.Рассылки', 'delivery_index.php'));
$_bc->AddContext(new BcItem('Отчеты', 'delivery_reports.php'));
$_bc->AddContext(new BcItem('Отчет по рассылке '.$razdel['name'], 'delivery_report.php?id='.$id));
 

 
$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
 

$smarty->display('page_delivery_top.html');



$sm=new SmartyAdm();

$sm->assign('pagename', $razd->GetPageName());
$sm->assign('list_pagename', $_list->GetPageName());
$sm->assign('step', $step);
$sm->assign('from', $from);
$sm->assign('to_page', $to_page);

$_per=new Delivery_Reports($step);
	 
	foreach($razdel as $k=>$v) $razdel[$k]=stripslashes($v);
	
	
	$sm->assign('rep', $_per->GetDataArr($id, $razdel)); 
	 
	
	$razdel['pdate_status_change']=date('d.m.Y H:i:s', $razdel['pdate_status_change']);
	
	
	
	$sm->assign('data', $razdel);
	$sm->assign('id', $id);
	
	 
	
	
	 
		 
	$page=$sm->fetch('delivery/report_'.$step.'.html');
 

echo $page;




//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>