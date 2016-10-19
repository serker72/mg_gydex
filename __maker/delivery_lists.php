<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');
require_once('../classes/v2/delivery_lists.class.php');


//административная авторизация
require_once('inc/adm_header.php');


 $rights_man=new DistrRightsManager;
if(!$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	header('Location: no_rights.php');
	die();		
}

 
if(isset($_GET['from'])) $from=abs((int)$_GET['from']);
else $from=0; 

if(isset($_GET['to_page'])) $to_page=abs((int)$_GET['to_page']);
else $to_page=ITEMS_PER_PAGE;
 
 

$_list=new Delivery_ListGroup;

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
				  	$r=new Delivery_ListItem;
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

$smarty->assign("SITETITLE",'Списки рассылок - GYDEX.Рассылки - '.SITETITLE);
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

$_context->AddContext(new ContextItem( 26, 'w', "", "Создать список", "delivery_ed_list.php",  false , $global_profile  ));

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

 

 
$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);


/*


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
$_razd_bc=$_r2->DrawNavigArr($mid, LANG_CODE);
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Разделы и контент', 'razds.php'));

foreach($_razd_bc as $item) $_bc->AddContext(new BcItem($item['name'], $item['url']));

$_bc->AddContext(new BcItem('Правка товара', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);



*/


/*
//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

$_context->AddContext(new ContextItem( 11, 'r', "", "Дерево сайтов", "tree.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "Разделы и контент", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'a', "", "Создать раздел", "ed_razd.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 18, 'a', "HAS_NEWS", "Создать новость", "ed_news.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 20, 'a', "HAS_PAPERS", "Создать статью", "ed_paper.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 22, 'a', "HAS_PRICE", "Создать товар", "ed_price.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 21, 'a', "HAS_PHOTOS", "Создать фото", "ed_photo.php?action=0", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "Отзывы", "viewotzyv.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "Баннеры", "viewads.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 14, 'r', "HAS_BASKET", "Заказы", "vieworders.php", false , $global_profile  ));


$_context->AddContext(new ContextItem( 4, 'r', "", "Пользователи и права", "discr_matrix_user.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 3, 'r', "", "Права групп", "discr_matrix_group.php", false , $global_profile  ));



$_context->AddContext(new ContextItem( "", '',  "", "Справка", "common.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', 'Быстрые действия');
*/
$smarty->display('page_delivery_top.html');
unset($smarty);

 
 
 
 
 
 
$decorator=new DBDecorator;

$_list=new Delivery_ListGroup;


 		


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
 
 
echo $_list->GetItems('delivery/lists.html', $from, $to_page, $decorator, 
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