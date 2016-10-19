<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/smarty/SmartyAdm.class.php');

//административная авторизация
require_once('inc/adm_header.php');

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
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				$r=new LangItem();
				$params=Array();
				if(isset($_POST[$lid.'_is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 9)) {
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
				
				$r=new LangItem();
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 9)){ 
					$r->Del($lid);
				}else{
		header('Location: no_rights.php');
		  die();	
	}
				
				
			}
		}
	}
	
	if(($kind==3)||($kind==4)){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				$r=new LangItem();
				$params=Array();
				if($kind==3) $params['is_shown']=1;
				else if($kind==4) $params['is_shown']=0;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 9)){ 
					$r->Edit($lid,$params);
				}else{
		header('Location: no_rights.php');
		  die();	
	}
			}
		}
	}
	
	header('Location: viewlangs.php?from='.$from.'&to_page='.$to_page);
	die();
}


//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Управление языками сайта - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

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
 
 

$_context->AddContext(new ContextItem( 9, 'a', "", "Добавить язык", "ed_lang.php?action=0", false , $global_profile  ));

 


$_context->AddContext(new ContextItem( "", '',  "", "Справка", "viewlangs.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Управление языками сайта', basename(__FILE__)));

 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);


$smarty->display('page_top.html');
?>




	<?
	
	$l=new LangGroup();
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 9)) 
	echo $l->GetItems(0,$from,$to_page);
	else echo NO_RIGHTS;
	
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