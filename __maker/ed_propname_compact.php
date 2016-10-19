<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/alldictitem.php');
require_once('../classes/dictattachitem.php');
include('../editor/fckeditor.php');
require_once('../classes/dictattdisp.php');
require_once('../classes/dictnvdisp.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


//режим работы
if(!isset($_SESSION['mode'])){
	$mode=1;
}else $mode=abs((int)$_SESSION['mode']);
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();
//echo $mode;

$disp=new DictNVDisp();
$li=new PropNameItem();


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



if(!isset($_GET['item_id']))
	if(!isset($_POST['item_id'])) {
		$item_id=0;
	}
	else $item_id = $_POST['item_id'];		
else $item_id = $_GET['item_id'];		
$item_id=abs((int)$item_id);

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=PROPS_PER_PAGE;
	}else $to_page = abs((int)$_POST['to_page']);	
}else $to_page = abs((int)$_GET['to_page']);	

if($action==0){
	//проверить dict_id
	if(!isset($_GET['dict_id']))
		if(!isset($_POST['dict_id'])) {
			header('Location: index.php');
			die();
		}
		else $dict_id = $_POST['dict_id'];		
	else $dict_id = $_GET['dict_id'];		
	$dict_id=abs((int)$dict_id);
	
	$di=new AllDictItem();
	$dict=$di->GetItemById($id);
	if($dict==false){
		header('Location: index.php');
		die();
	}
}


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
		
		$li->AddLanguage($id,$lang_id);
		header('Location: ed_propname_compact.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&nonvisual='.$nonvisual.'&item_id='.$item_id.'&from='.$from.'&to_page='.$to_page);
		die();
	}
	
	
	$lang=$li->GetItemById($id, $lang_id);
	if($lang==false){
		header('Location: index.php');
		die();
	}
}


//смена порядка показа 
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$lang['ord']<245)	$params['ord']=(int)$lang['ord']+10;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$lang['ord']>10) $params['ord']=(int)$lang['ord']-10;
	}
	
	$disp->EditName($id, $params, $lparams);
	
	header('Location: viewnames.php?dict_id='.$lang['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page);
	die();
}




if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['default_val']=SecStr($_POST['default_val']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
	$params['dict_id']=abs((int)$_POST['dict_id']);
	if(isset($_POST['is_criteria'])) $params['is_criteria']=1; else $params['is_criteria']=0;
	
	$code=$disp->AddName($params,$lparams);
	
	header('Location: viewnames.php?dict_id='.$lang['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$code);
	die();
}



if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['default_val']=SecStr($_POST['default_val']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
	//$params['dict_id']=abs((int)$_POST['dict_id']);
	if(isset($_POST['is_criteria'])) $params['is_criteria']=1; else $params['is_criteria']=0;
	
	
	$disp->EditName($id, $params, $lparams);
	
	header('Location: viewnames.php?dict_id='.$lang['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
}



if($action==2){
//удаление
	$disp->DelName($id);
	unset($li);
	header('Location: viewnames.php?dict_id='.$lang['dict_id'].'&id='.$item_id);
	die();	
}

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
	<form action="ed_propname_compact.php" method="post" name="inpp" id="inpp">
	<h3>Редактирование имени свойства</h3>
	
	<input type="hidden" name="dict_id" value="<?if($action==0) echo $dict_id;?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	<input type="hidden" name="item_id" value="<?=$item_id?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	
	<?
	//многоязычность
	if($action==1){
	?>
		<strong>Выберите язык:</strong>
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
	
	
	<strong>Имя свойства:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="50" maxlength="255"><p>

	<strong>Значение по умолчанию:</strong><br>
	<input type="text" name="default_val" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['default_val']));?>" size="50" maxlength="255"><p>
	
		<strong>Является критерием отбора товара: </strong><input class="unbord" name="is_criteria" type="checkbox" <? if(($action==1)&&($lang['is_criteria']==1)) echo 'checked'; else if($action==0) echo 'checked';?> /><p />		
				
				
		<strong>Отображать на сайте: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; else if($action==0) echo 'checked';?> /><p />
		
		
		<strong>Порядок показа:</strong> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $lang['ord']; else echo '1'; ?>" /><p />
	
	<p>
	

	

	<input type="submit" name="doInp" value="Внести изменения">
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле  Имя свойства!");    
</script>  
	
	</td>
	</tr>
	</table>
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>