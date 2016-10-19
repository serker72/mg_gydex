<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

require_once('../classes/v2/delivery_templates.class.php');

//административная авторизация
require_once('inc/adm_header.php');


 $rights_man=new DistrRightsManager;
if(!$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	header('Location: no_rights.php');
	die();		
}


$_list=new Delivery_TemplateGroup;



$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	  
	  
	  
  if(isset($_POST['Update'])||isset($_POST['Update1'])){
	  $kind=(int)$_POST['kind'];
	  
	  
	  
	  
	  if($kind==2){
		  //Обновляем базу
		  foreach($_POST as $key=>$val){
			  if(eregi("_do_process",$key)){
				  //echo $key; echo $val;
				  
				  //удаляем 
				  
				  $lid=(int)$val;
				  $rights_man=new DistrRightsManager;
				  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 26)) {
				  	$r=new Delivery_TemplateItem;
				    $r->Del($lid);
				  }else{
					header('Location: no_rights.php');
			    	  die();	  
				  }  
				  
				  
			  }
		  }
	  }
	  
	  
	  
	  
	  /*if(($kind==4)||($kind==5)){
		  //Обновляем базу
		  
		  
		  foreach($_POST as $key=>$val){
			  if(eregi("_do_process",$key)){
				  //echo $key; echo $val;
				  $lid=(int)$val;
				  
				   $rights_man=new DistrRightsManager;
				  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {
					$r=new MmenuItem();
					
					if($kind==4) $r->ToggleVisible($lid,1);
					if($kind==5) $r->ToggleVisible($lid,0);
				  }else{
					header('Location: no_rights.php');
			    	  die();	  
				  }    
				}
		  }
	  }*/
	  header('Location: '.$_list->GetPageName().'?from='.$from.'&to_page='.$to_page);
	  die();
  }
}else{
	header('Location: no_rights.php');
	 die();		
}


//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'Список шаблонов - GYDEX.Рассылки - '.SITETITLE);
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
$_dmenu_id=48;
require_once('delivery_menu.php');
$smarty->assign("MODULE_HMENU",$vmenu);




require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

$_context->AddContext(new ContextItem( 26, 'w', "", "Создать шаблон", "delivery_ed_template.php",  false , $global_profile  ));

$_context->AddContext(new ContextItem( "", '',  "", "Справка", "delivery_templates.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', 'Быстрые действия');



//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('GYDEX.Рассылки', 'delivery_index.php'));
$_bc->AddContext(new BcItem('Шаблоны', 'delivery_templates.php'));

 

 
$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);


$smarty->display('page_delivery_top.html');
unset($smarty);









 
$decorator=new DBDecorator;

$_list=new Delivery_TemplateGroup;


 		


$decorator->AddEntry(new UriEntry('to_page',$to_page));


if(!isset($_GET['sortmode'])){
		$sortmode=1;	
	}else{
		$sortmode=abs((int)$_GET['sortmode']);
	}
$decorator->AddEntry(new UriEntry('sortmode',$sortmode));
switch($sortmode){
		case 0:
			$decorator->AddEntry(new SqlOrdEntry('p.name',SqlOrdEntry::DESC));
		break;
		case 1:
			$decorator->AddEntry(new SqlOrdEntry('p.name',SqlOrdEntry::ASC));
		break;
		
		
		default:
			$decorator->AddEntry(new SqlOrdEntry('p.pdate',SqlOrdEntry::DESC));

 		
			$decorator->AddEntry(new SqlOrdEntry('p.id',SqlOrdEntry::DESC));
		break;	
		
	}		
 
 
echo $_list->GetItems('delivery/templates.html', $from, $to_page, $decorator, 
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26) ,
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 26),
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 26) );
 
 
 
 











//нижний шаблон

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_bottom.html');
unset($smarty);
?>