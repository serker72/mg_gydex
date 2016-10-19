<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
//require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/currencyitem.php');
include('../editor/fckeditor.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');


$li=new CurrencyItem();
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


if(!isset($_GET['lang_id']))
	if(!isset($_POST['lang_id'])) {
		//header('Location: index.php');
		//die();
		$lang_id=LANG_CODE;
	}
	else $lang_id = $_POST['lang_id'];		
else $lang_id = $_GET['lang_id'];		
$lang_id=abs((int)$lang_id);

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
	
	
	//смена языка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемся добавить язык
		
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 23)) {
		
			$li->AddLanguage($id,$lang_id);
			header('Location: ed_curr.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
			die();
		
		}else{
			header('Location: no_rights.php');
			die();	
			
		}
		
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 23)) {
	
		$lang=$li->GetItemById($id, $lang_id);
		
	}else{
		  header('Location: no_rights.php');
		  die();	
		  
	  }
	if($lang==false){
		header('Location: index.php');
		die();	
	}
}





if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['signat']=SecStr($_POST['signat']);
	//$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_base_shop'])) $params['is_base_shop']=1; else $params['is_base_shop']=0;
	if(isset($_POST['is_base_rate'])) $params['is_base_rate']=1; else $params['is_base_rate']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['rate']=abs((float)$_POST['rate']);
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 23)) {
	
	  $r_code=$li->Add($params,$lparams);
	  if(isset($_POST['doInp']))
		  header('Location: viewcurrs.php?from='.$from.'&to_page='.$to_page);
	  else if(isset($_POST['doApply']))
		  header('Location: ed_curr.php?action=1&id='.$r_code.'&from='.$from.'&to_page='.$to_page);
	  die();
	}else{
		 header('Location: no_rights.php');
		  die();	
	}
	
}

if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['signat']=SecStr($_POST['signat']);
	//$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_base_shop'])) $params['is_base_shop']=1; else $params['is_base_shop']=0;
	if(isset($_POST['is_base_rate'])) $params['is_base_rate']=1; else $params['is_base_rate']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['rate']=abs((float)$_POST['rate']);
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 23)) {
			$id=$li->Add($params,$lparams);
		}else{
			header('Location: no_rights.php');
		  die();	
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 23)) {
			$li->Edit($id,$params,$lparams);
		}else{
			header('Location: no_rights.php');
		  die();	
		}
	}
	if(isset($_POST['doInp']))
		header('Location: viewcurrs.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_curr.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 23)) {
		$li->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
		  die();	
	}
	header('Location: viewcurrs.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование валюты - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=28;
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


 
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_curr.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 $_bc->AddContext(new BcItem('Валюты магазина', 'viewcurrs.php'));

$_bc->AddContext(new BcItem('Правка валюты', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);



$smarty->display('page_top.html');
?>

	
	<form action="ed_curr.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование валюты</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	
	<?
	//многоязычность
	if($action==1){
	?>
		<label for="lang_id">Выберите язык:</label>
		<select name="lang_id" id="lang_id">
		<?
			$lg=new LangGroup();
			echo $lg->GetItemsOpt($lang_id,'lang_name');
		?>
		</select>
		
		<input type="submit" name="doLang" value="Перейти">
		<p>
	<?
	}
	?>
	
	
	<label for="name">Название валюты:</label><br>
	<input type="text" name="name"  id="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="40" maxlength="255"><p>


	<label for="signat">Обозначение валюты:</label><br>
	<input type="text" name="signat" id="signat"  value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['signat']));?>" size="20" maxlength="255"><p>			
				
				
		<label for="is_base_shop">Основная валюта магазина: </label><input class="unbord" name="is_base_shop" id="is_base_shop" type="checkbox" <? if(($action==1)&&($lang['is_base_shop']==1)) echo 'checked'; ?> /><p />
		
		<label for="is_base_rate">Базовая для расчета курса: </label><input class="unbord" name="is_base_rate"  id="is_base_rate" type="checkbox" <? if(($action==1)&&($lang['is_base_rate']==1)) echo 'checked'; ?> /><br>

		<label for="rate">Курс относительно базовой валюты:<br></label>
	<input type="text" name="rate"  id="rate" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['rate'])); else echo '1';?>" size="20" maxlength="255"><p>		
		
	

	<input type="submit" name="doInp" value="Внести изменения">
	<input type="submit" name="doApply" value="Применить изменения">
	
	<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле  Название валюты!");    
  frmvalidator.addValidation("signat","req","Заполните  поле  Обозначение валюты!");   
  
  frmvalidator.addValidation("rate","req","Заполните  поле  Курс относительно базовой валюты!");   
  frmvalidator.addValidation("rate","dec","В  поле  Курс относительно базовой валюты допустимы только положительные десятичные числа!");   
   frmvalidator.addValidation("rate","gt=0","В  поле  Курс относительно базовой валюты допустимы только положительные десятичные числа!"); 
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