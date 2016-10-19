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

if(($step<1)||($step>5)) $step=1;
 
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
			$id=0;
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
		//создаем рассылку. получим ее id
		$params=array();
		$params['name']='Черновик рассылки от '.date('d.m.Y H:i');
		$params['pdate_status_change']=time();
		
		$id=$razd->Add($params);
		
		header('Location: '.$razd->GetPageName().'?id='.$id.'&from='.$from.'&to_page='.$to_page);
	  	die();
		
		//$razdel=$razd->GetItemById($id);
		
	}
	//echo $razdel['parent_id']; die();
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
	 
if($action==3){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26)) {
		  //$razd->Edit($id, $params);
	  	  
		  $params=array();
		  $params['name']=SecStr($razdel['name']).' (копия)';
		  $params['topic']=SecStr($razdel['topic']);
		  $params['from_name']=SecStr($razdel['from_name']);
		  $params['from_email']=SecStr($razdel['from_email']);
		  $params['to_is_personal']=SecStr($razdel['to_is_personal']);
		  $params['to_field']=SecStr($razdel['to_field']);
		  $params['has_tracking']=SecStr($razdel['has_tracking']);
		  $params['has_clicks_tracking']=SecStr($razdel['has_clicks_tracking']);
		  $params['status_id']=1;
		  $params['pdate_status_change']=time();
		  $params['html_content']=SecStr($razdel['html_content']);
		  $params['plain_text_content']=SecStr($razdel['plain_text_content']);
		  $params['template_id']=SecStr($razdel['template_id']);
		  $params['list_id']=SecStr($razdel['list_id']);
		  $params['segment_id']=SecStr($razdel['segment_id']);
		   
		  	
			
		  $r_code=$razd->Add($params);
		  	
		  header('Location: '.$razd->GetPageName().'?id='.$r_code.'&from='.$from.'&to_page='.$to_page);
	   
	  die();
	}else{
		header('Location: no_rights.php');
			    	  die();	
	}
}



if(isset($_POST['step'])&&($_POST['step']==5)&&(isset($_POST['do_plan'])||isset($_POST['send_now']))){
	//
	$params=array(); 
	
	
	if(in_array($razdel['status_id'], $editable_status_ids)){
	
	//$params['name']=SecStr($_POST['name']);
	 
	//$params['html_content']=SecStr($_POST['html_content']);	
		$_check=new Delivery_Check($razdel); $total_check=true;
		for($i=1; $i<=4;$i++) $total_check=$total_check&&$_check->CheckStage($i,$rss);
		if($total_check){
			//запустить на рассылку
			if(isset($_POST['send_now'])){
				$params['schedule_pdate']=time();		
			}else{
				$pdate=DateFromdmy($_POST['date'])+60*60*(int)$_POST['ptime_beg_h']+60*(int)$_POST['ptime_beg_m'];
				$params['schedule_pdate']=$pdate;
			}
			
			$params['status_id']=4;
			$params['pdate_status_change']=time();
		}
		
	}
	 
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26)) {
		  $razd->Edit($id, $params);
	  
		  header('Location: '.$_list->GetPageName().'?id='.$parent_id.'&from='.$from.'&to_page='.$to_page);
	   
	  die();
	}else{
		header('Location: no_rights.php');
			    	  die();	
	}
}
 
/* 

if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=array(); 
	$params['name']=SecStr($_POST['name']);
	 
	$params['html_content']=SecStr($_POST['html_content']);	
	
	 
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
	 
	$params['html_content']=SecStr($_POST['html_content']);	
	
	
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

*/
 

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка рассылки - '.SITETITLE);
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
$_dmenu_id=47;
require_once('delivery_menu.php');
$smarty->assign("MODULE_HMENU",$vmenu);


 

$smarty->display('page_delivery_top.html');



$sm=new SmartyAdm();

$sm->assign('pagename', $razd->GetPageName());
$sm->assign('list_pagename', $_list->GetPageName());
$sm->assign('step', $step);
$sm->assign('from', $from);
$sm->assign('to_page', $to_page);

 
	foreach($razdel as $k=>$v) $razdel[$k]=stripslashes($v);
	
	$sm->assign('data', $razdel);
	$sm->assign('id', $id);
	
	$sm->assign('can_edit', in_array($razdel['status_id'], $editable_status_ids));
	
	//шаг 1
	$sm->assign('lists', $razd->GetListsArr($id));
	
	
	//шаг 3
	$_dti=new Delivery_TemplateItem;
	$dti=$_dti->GetItemById($razdel['template_id']);
	$sm->assign('template_name', $dti['name']);
	$sm->assign('templates', $razd->GetTemplatesArr($id));
	
	//шаг 5
	if($step==5){
		$_check=new Delivery_Check($razdel);
		
		for($i=1;  $i<=4; $i++){
			$check=$_check->CheckStage($i, $rss);
			$sm->assign('step_ok_'.$i, $check);
			$sm->assign('step_message_'.$i, $rss);	
		}
		 
		if($razdel['schedule_pdate']!=""){
			$date=date('d.m.Y', $razdel['schedule_pdate']);
			$hours=date('H', $razdel['schedule_pdate']);
			$mins=date('i', $razdel['schedule_pdate']);
		}else{
			$date=date('d.m.Y');
			$hours=date('H');
			$mins=date('i');
		}
		$sm->assign('date', $date);
		$sm->assign('hours', $hours);
		$sm->assign('mins', $mins);
		
		 $from_hrs=array();
		$from_hrs[]='';
		for($i=0;$i<=23;$i++) $from_hrs[]=sprintf("%02d",$i);
		$sm->assign('ptime_beg_h',$from_hrs);
		
				
		$from_ms=array();
		$from_ms[]='';
		for($i=0;$i<=59;$i++) $from_ms[]=sprintf("%02d",$i);
		$sm->assign('ptime_beg_m',$from_ms);
		
	}
		 
	$page=$sm->fetch('delivery/edit_campaign_'.$step.'.html');
 

echo $page;




//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>