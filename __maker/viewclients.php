<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/usersgroup.php');
require_once('../classes/groupsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/groupitem.php');

require_once('../classes/v2/usersgroup_new.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	header("Location: index.php");
	die();
}


//административная авторизация
require_once('inc/adm_header.php');


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

//разбивка по группам
if(!isset($_GET['group_id'])){
	if(!isset($_POST['group_id'])){
		$group_id=0;
	}else $group_id = $_POST['group_id'];	
}else $group_id = $_GET['group_id'];	
$group_id=abs((int)$group_id);	


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 16)) {}
else{
	header('Location: no_rights.php');
	die();		
}	

if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	
	if($kind==1){
		//Обновляем базу
				
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
				  $r=new UserItem();
				  
				  $params=Array();
				  if(isset($_POST[$lid.'_is_mailed'])){
					  $params['is_mailed']=1;
				  }else $params['is_mailed']=0;
				  
				  if(isset($_POST[$lid.'_is_blocked'])){
					  $params['is_blocked']=1;
				  }else $params['is_blocked']=0;
				  
				  $params['skidka']=abs((float)$_POST[$lid.'_skidka']);
				  
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
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 16)) {
					$r=new UserItem();
				
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					die();
				}
				
				
			}
		}
	}
	
	
	
	
	if(($kind==4)||($kind==5)){
		//Обновляем базу
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
				  $r=new UserItem();
				  $params=Array();
				  if($kind==4) $params['is_blocked']=0;//$r->ToggleVisible($lid,1);
				  if($kind==5) $params['is_blocked']=1;//$r->ToggleVisible($lid,0);
				  $r->Edit($lid,$params);
				}else{
					header('Location: no_rights.php');
					die();	
				}
			}
		}
	}
	
	header('Location: viewclients.php?from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();

}
	
//занесение юзера в группу
foreach($_POST as $k=>$v){
	if(eregi("_addRule",$k)){
		//echo " $k = $v <br>";
		
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
		  $r=new UserItem();
		  $l=new GroupItem();
		  
		  $lid=abs((int)eregi_replace("_addRule","",$k));
		  $gr_id=abs((int)$_POST[$lid.'_groups']);
		  if(($r->GetItemById($lid)!=false)&&($l->GetItemById($gr_id)!=false)){
			  $r->AddUserToGroup($lid, $gr_id);
		  }
		}else{
			header('Location: no_rights.php');
			die();		
		}
	}
}

//udalenue uzera iz gruppy
if(isset($_GET['doDelGr'])){
	$clid=abs((int)$_GET['clid']);
	$gr_id=abs((int)$_GET['gr_id']);	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
	
		$r=new UserItem();
		$r->DelUserFromGroup($clid,$gr_id);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	
	header('Location: viewclients.php?from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();	
}
	


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Список покупателей - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=25;
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



$_context->AddContext(new ContextItem( 16, 'a', "", "Создать покупателя", "ed_client.php?action=0", false , $global_profile  ));
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "viewclients.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 

$_bc->AddContext(new BcItem('Список покупателей', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);





$smarty->display('page_top.html');
?>

	
	<?
	//навигация
	//echo $razd->DrawNavig($id, $lang_id,0,' список товаров ');
	?>
	
	
	
	
	
	<?
	
	$pl=new UsersGroupNew;
	//$pl->SetTemplates('clients/items.html','tpl/clients/itemsrow.html','','tpl/clients/to_page.html');

	echo $pl->GetItemsById($group_id,$from,$to_page, 'clients/items.html');
	
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