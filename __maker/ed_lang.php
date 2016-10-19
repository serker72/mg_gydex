<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

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


if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;



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
	$li=new LangItem();
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 9)) {
	  $lang=$li->GetItemById($id);
	  
	  
	  if($lang==false){
		  header('Location: index.php');
		  die();	
	  }
	}else{
		header('Location: no_rights.php');
		  die();	
	}
}



if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$params['lang_name']=SecStr($_POST['lang_name']);
	$params['lang_flag']=SecStr($_POST['banner_rus']);
		$params['lang_flag_bigger']=SecStr($_POST['photo_small']);
	$params['lang_meta']=SecStr($_POST['lang_meta']);
	if(isset($_POST['is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
	
	$li=new LangItem();
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 9)){
		$li->Add($params);
	
		header('Location: viewlangs.php?from='.$from.'&to_page='.$to_page);
		die();
	}else{
		header('Location: no_rights.php');
		  die();	
	}
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$params['lang_name']=SecStr($_POST['lang_name']);
	$params['lang_flag']=SecStr($_POST['banner_rus']);
	$params['lang_flag_bigger']=SecStr($_POST['photo_small']);
	$params['lang_meta']=SecStr($_POST['lang_meta']);
	if(isset($_POST['is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 9)){
		$li->Edit($id,$params);
	
		header('Location: viewlangs.php?from='.$from.'&to_page='.$to_page);
		die();
	}else{
		header('Location: no_rights.php');
		  die();		
	}
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 9)){
		$li->Del($id);
		unset($li);
		header('Location: viewlangs.php?from='.$from.'&to_page='.$to_page);
		die();	
	}else{
		header('Location: no_rights.php');
		  die();		
	}
	
}



//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка языка - '.SITETITLE);
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

 


$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_lang.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Управление языками сайта', 'viewlangs.php'));

$_bc->AddContext(new BcItem('Правка языка сайта', basename(__FILE__))); 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);



$smarty->display('page_top.html');


?>

	
	<form action="ed_lang.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование языка</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<label for="lang_name">Название языка:</label>
	<input type="text" name="lang_name" id="lang_name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['lang_name']));?>" size="40" maxlength="255"><p>


	 <label for="banner_rus">Значок флага<br /></label>
		<input name="banner_rus" id="banner_rus" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['lang_flag'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=0','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.banner_rus.value=''; document.forms.inpp.banner_rus.focus();">выбрать фото</a><p>

	<label for="photo_small">Значок флага крупный (для стартовой страницы)<br /></label>
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['lang_flag_bigger'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>
		<p />		
		
		
		<label for="lang_meta">Метатег языка:</label><br>
		<input type="text" name="lang_meta" id="lang_meta" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['lang_meta']));?>" size="100" maxlength="255"><p>

		<label for="is_shown">Отображать на сайте:</label> <input class="unbord" name="is_shown" id="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; ?> /><p />

	<input type="submit" name="doInp" value="Внести изменения">
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("lang_name","req","Заполните  поле  Название языка!");    
  frmvalidator.addValidation("banner_rus","req","Заполните  поле  Значок флага!");
</script>  
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>