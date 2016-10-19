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

$razd=new Delivery_UserItem;
$_list=new Delivery_UserGroup;
$_par=new Delivery_ListItem;

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


if($action==0){
	//проверим id
	if(!isset($_GET['list_id']))
		if(!isset($_POST['list_id'])) {
			header('Location: index.php');
			die();
		}
		else $list_id = $_POST['list_id'];		
	else $list_id = $_GET['list_id'];		
	$list_id=abs((int)$list_id);
	
	
	
	$par=$_par->GetItemById($list_id);
	
	
	if($par==false){
		header('Location: index.php');
		die();	
	}
	 
}

 

if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	
	$txt=$_POST['users'];
	
	$users=explode("\n", $txt);
	
	//print_r($users);
	foreach($users as $k=>$user){
		$fields=explode("\t",$user);
		 
		if(!isset($fields[0]) || (strlen(trim($fields[0]))==0) ) continue;
		
		$params=array(); 
		if(isset($fields[0])) $params['email']=SecStr($fields[0]);
		
		if(isset($fields[1])) $params['f']=SecStr($fields[1]);
		if(isset($fields[2])) $params['i']=SecStr($fields[2]);
		if(isset($fields[3])) $params['o']=SecStr($fields[3]);
		 
	 
		 
		
		
		
		$params['list_id']=abs((int)$_POST['list_id']);
		
		 
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26)) {
		
		   
		  
		  $test_u=$razd->GetItemByFields(array('email'=>$params['email'], 'list_id'=>$params['list_id']));
		 
		  if(isset($_POST['do_update'])){
			   if($test_u===false) {
				   $params['is_subscribed']=1;
				   $r_code=$razd->Add($params);
			   }
			   else  $razd->Edit($test_u['id'], $params); 
		  }else{
			  if($test_u===false) {
				  $params['is_subscribed']=1;
				  $r_code=$razd->Add($params);
			  }
		  }
		  
		  
		  
		 
			  
		}else{
			header('Location: no_rights.php');
						  die();	
		}
			
	}
	 
	 
	  
 
  header('Location: '.$_list->GetPageName().'?id='.$list_id.'&from='.$from.'&to_page='.$to_page);
	die();
	 
}
 

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Добавить подписчиков рассылки - '.SITETITLE);
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

$_bc->AddContext(new BcItem('Список '.$par['name'], 'delivery_list_users.php?id='.$list_id));

$_bc->AddContext(new BcItem('Добавить подписчиков списком'));

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
$sm->assign('list_id', $list_id);


if($action==0){
	
	
	$page=$sm->fetch('delivery/create_mass_user.html');	
 
}

echo $page;




//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>