<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/pricegroup.php');
require_once('../classes/v2/pricegroup_new.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
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


if(!isset($_GET['lang_id']))
		if(!isset($_POST['lang_id'])) {
			//header('Location: index.php');
			//die();
			$lang_id=LANG_CODE;
		}
		else $lang_id = $_POST['lang_id'];		
	else $lang_id = $_GET['lang_id'];		
	$lang_id=abs((int)$lang_id);
	
	
if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {

		$razdel=$razd->GetItemById($id, $lang_id);	
	}else{
		header('Location: no_rights.php');
		die();
	}
	
	
	

if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	
	if($kind==1){
		//Обновляем базу
		//получим список всех языков
		$langs=Array();
		$langgr=new LangGroup();
		$langs=$langgr->GetLangsIdList();
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {
								
				
				  $params['firmid']=abs((int)$_POST[$lid.'_firmid']);
				  if(HAS_OST) $ost=abs((int)$_POST[$lid.'_ost']);
				  else $ost=NULL;
				  
				  $r=new PriceItem();
				  $r->Edit($lid,$params,NULL,$ost);
				  
				   $lparams=Array();
				  //проверим видимости раздела
				  foreach($langs as $lk=>$lv){
					  if(isset($_POST[$lid.'_'.$lv.'_is_shown'])) $r->ToggleVisibleLang($lid, $lv, 1);
					  else $r->ToggleVisibleLang($lid, $lv, 0);
				  }
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
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 22)) {
					$r=new PriceItem();
				
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
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {
					$r=new PriceItem();
				
					if($kind==4) $r->ToggleVisible($lid,1);
					if($kind==5) $r->ToggleVisible($lid,0);
				}else{
					header('Location: no_rights.php');
					die();
				}
				
			}
		}
	}
	
	header('Location: viewpriceitems.php?id='.$id.'&from='.$from.'&to_page='.$to_page);
	die();

}
	
	
	
	
	
require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Список товаров - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);


$_menu_id=21;
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



$_context->AddContext(new ContextItem( 22, 'a', "", "Создать товар", "ed_price.php?action=0&mid=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 22, 'r', "", "Все товары", "viewpriceitems.php?sortmode=0&id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'w', "", "Редактировать раздел", "ed_razd.php?action=1&id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "Список подразделов", "razds.php?id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "Разделы верхнего урованя", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "viewphotos.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);






//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
$_razd_bc=$_r2->DrawNavigArr($id, LANG_CODE);
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Разделы и контент', 'razds.php'));

foreach($_razd_bc as $item) $_bc->AddContext(new BcItem($item['name'], $item['url']));

$_bc->AddContext(new BcItem('Список товаров', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);





$smarty->display('page_top.html');
	
?>

	
	 
	<?
	
	$pl=new PriceGroupNew();
	 
	echo $pl->GetItemsById($id, 0,$from,$to_page, 'price/items.html');
	
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