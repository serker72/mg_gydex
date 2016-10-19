<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/mmenulist.php');
require_once('../classes/v2/mmenulist_new.php');

require_once('../classes/smarty/SmartyAdm.class.php');

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

	
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		//die();
		$id=0;
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

$lang_id=LANG_CODE;

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
  $razdel=$razd->GetItemById($id, $lang_id);	
	  
	  
	  
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
				  
				  $params=Array();
				  
				  if(HAS_BASKET) if(isset($_POST[$lid.'_is_basket'])) $params['is_basket']=1; else $params['is_basket']=0;
				  
				  if(isset($_POST[$lid.'_usl'])){
					  if(HAS_PRICE) $params['is_price']=0;
					  if(HAS_NEWS) $params['is_news']=0;
					  if(HAS_LINKS) $params['is_links']=0;												
					  if(HAS_PRICE) if(abs((int)$_POST[$lid.'_usl'])==1) $params['is_price']=1;
					  if(HAS_NEWS) if(abs((int)$_POST[$lid.'_usl'])==2) $params['is_news']=1;					
					  if(HAS_LINKS) if(abs((int)$_POST[$lid.'_usl'])==3) $params['is_links']=1;					
				  }
				  
				  if(HAS_PAPERS) if(isset($_POST[$lid.'_is_papers'])) $params['is_papers']=1; else $params['is_papers']=0;
				  if(HAS_GALLERY) if(isset($_POST[$lid.'_is_gallery'])) $params['is_gallery']=1; else $params['is_gallery']=0;
				  if(HAS_FEEDBACK_FORMS) if(isset($_POST[$lid.'_is_feedback_forms'])) $params['is_feedback_forms']=1; else $params['is_feedback_forms']=0;		
				  
				   if(HAS_FEEDBACK_FORMS) if(isset($_POST[$lid.'_is_feedback_service'])) $params['is_feedback_service']=1; else $params['is_feedback_service']=0;	
				  
				  if(HAS_FEEDBACK_FORMS) if(isset($_POST[$lid.'_is_otzyv'])) $params['is_otzyv']=1; else $params['is_otzyv']=0;																				
				  
				  
				  $rights_man=new DistrRightsManager;
				  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {
				  
					$r=new MmenuItem();
					$r->Edit($lid,$params);
					
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
				  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 11)) {
				  	$r=new MmenuItem();
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
	  }
	  header('Location: razds.php?id='.$id.'&from='.$from.'&to_page='.$to_page);
	  die();
  }
}else{
	header('Location: no_rights.php');
	 die();		
}
	
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Список разделов - '.SITETITLE);
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
$_context->AddContext(new ContextItem( 11, 'r', "", "Разделы и контент", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "Перейти на уровень выше", "razds.php?id=".$id, false , $global_profile  ));

if($id!=0) $_context->AddContext(new ContextItem( 11, 'w', "", "Редактировать раздел", "ed_razd.php?action=1&id=".$id, false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'a', "", "Создать подраздел", "ed_razd.php?action=0&parent_id=".$id, false , $global_profile  ));

if($razdel['is_news']) $_context->AddContext(new ContextItem( 18, 'r', "HAS_NEWS", "Новости", "viewnews.php?id=".$id, false , $global_profile  ));

if($razdel['is_price']) $_context->AddContext(new ContextItem( 22, 'r', "HAS_PRICE", "Товары", "viewpriceitems.php?id=".$id, false , $global_profile  ));

if($razdel['is_papers']) $_context->AddContext(new ContextItem( 20, 'r', "HAS_PAPERS", "Статьи", "viewpapers.php?id=".$id, false , $global_profile  ));

if($razdel['is_links']) $_context->AddContext(new ContextItem( 19, 'r', "HAS_LINKS", "Ссылки", "viewlinks.php?id=".$id, false , $global_profile  ));


$_context->AddContext(new ContextItem( "", '',  "", "Справка", "razds.html", true , $global_profile  ));

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


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);


$smarty->display('page_top.html');

 
	$ml= new MmenuListNew;
	 
	echo $ml->GetItemsById($id, 0,$from,$to_page, 'subitems.html');
	
	 
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>	