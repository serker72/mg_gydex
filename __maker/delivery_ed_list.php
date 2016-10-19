<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/allmenu_template_group.php');

require_once('../classes/v2/delivery_lists.class.php');

//административная авторизация
require_once('inc/adm_header.php');

$razd=new Delivery_ListItem;
$_list=new Delivery_ListGroup;

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


if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;

 
if(!isset($_GET['nonvisual']))
	if(!isset($_POST['nonvisual'])) $nonvisual = 0;
	else $nonvisual = $_POST['nonvisual'];		
else $nonvisual = $_GET['nonvisual'];		
$nonvisual=abs((int)$nonvisual);	




 
	
if(($action==1)||($action==2)){
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	 
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	
		$razdel=$razd->GetItemById($id);
	}else{
		header('Location: no_rights.php');
   	    die();		
	}

	
	//echo $razdel['parent_id']; die();
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
	 
}
 

if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=array(); 
	$params['name']=SecStr($_POST['name']);
	 
	$params['comment']=SecStr($_POST['comment']);	
	
	 
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26)) {
	
	  $r_code=$razd->Add($params);
	  
	  if(isset($_POST['doInp']))
		  header('Location: '.$_list->GetPageName().'?id='.$parent_id.'&from='.$from.'&to_page='.$to_page);
	  else if(isset($_POST['doApply']))
		  header('Location: '.$razd->GetPageName().'?action=1&id='.$r_code.'&from='.$from.'&to_page='.$to_page);
	  die();
	}else{
		header('Location: no_rights.php');
			    	  die();	
	}
}
 


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=array(); 
	$params['name']=SecStr($_POST['name']);
	 
	$params['comment']=SecStr($_POST['comment']);	
	
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26)) {
			$id=$razd->Add($params);
		}else{
			header('Location: no_rights.php');
		  die();	
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 26)) {
			$razd->Edit($id, $params);
		}else{
			header('Location: no_rights.php');
		  die();	
		}	
	}
	if(isset($_POST['doInp']))
		header('Location: '.$_list->GetPageName().'?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: '.$razd->GetPageName().'?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page);
	die();
}


if($action==2){
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 26)) {
		$razd->Del($id);
	
		header('Location: '.$_list->GetPageName().'?from='.$from.'&to_page='.$to_page);
		die();
	}else{
		header('Location: no_rights.php');
		  die();	
	}
}


 

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка списка рассылки - '.SITETITLE);
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
$_dmenu_id=49;
require_once('delivery_menu.php');
$smarty->assign("MODULE_HMENU",$vmenu);



require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

//$_context->AddContext(new ContextItem( 26, 'a', "", "Создать список", "delivery_ed_list.php",  false , $global_profile  ));

$_context->AddContext(new ContextItem( "", '',  "", "Справка", "delivery_lists.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', 'Быстрые действия');



//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('GYDEX.Рассылки', 'delivery_index.php'));
$_bc->AddContext(new BcItem('Списки', 'delivery_lists.php'));

$_bc->AddContext(new BcItem('Редактирование списка'));

//foreach($_razd_bc as $item) $_bc->AddContext(new BcItem($item['name'], $item['url']));


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);


$smarty->display('page_delivery_top.html');



$sm=new SmartyAdm();

$sm->assign('pagename', $razd->GetPageName());
$sm->assign('list_pagename', $_list->GetPageName());
$sm->assign('action', $action);
$sm->assign('from', $from);
$sm->assign('to_page', $to_page);


if($action==0){
	
	
	$page=$sm->fetch('delivery/create_list.html');	
}elseif($action==1){
	foreach($razdel as $k=>$v) $razdel[$k]=stripslashes($v);
	
	$sm->assign('data', $razdel);
	
	
	$page=$sm->fetch('delivery/edit_list.html');
}

echo $page;




//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>