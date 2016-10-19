<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
//require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/pricesitem.php');
include('../editor/fckeditor.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');


$li=new PricesItem();
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
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
			$li->AddLanguage($id,$lang_id);
			header('Location: ed_pr.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
			die();
		}else{
			header('Location: no_rights.php');
			die();	
		}
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 24)) {
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



//смена порядка показа 
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$lang['ord']<245)	$params['ord']=(int)$lang['ord']+5;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$lang['ord']>=5) $params['ord']=(int)$lang['ord']-5;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
	
		$li->Edit($id, $params);
	}else{
		header('Location: no_rights.php');
			die();	
	}
	
	header('Location: viewprices.php?from='.$from.'&to_page='.$to_page);
	die();
}


if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['descr']=SecStr($_POST['descr']);
	//$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_base'])) $params['is_base']=1; else $params['is_base']=0;
	if(isset($_POST['use_formula'])) $params['use_formula']=1; else $params['use_formula']=0;
	$lparams['lang_id']=$lang_id;	
	//$params['formula']=SecStr($_POST['formula']);
		$params['formula']=abs((int)$_POST['formula']); //SecStr($_POST['formula']);
	$params['formula_name']=SecStr($_POST['formula_name']);
	
	
	$params['ord']=abs((int)$_POST['ord']);
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 24)) {
		$li->Add($params,$lparams);
	}else{
		header('Location: no_rights.php');
			die();	
	}
	
	header('Location: viewprices.php?from='.$from.'&to_page='.$to_page);
	die();
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['descr']=SecStr($_POST['descr']);
	//$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_base'])) $params['is_base']=1; else $params['is_base']=0;
	if(isset($_POST['use_formula'])) $params['use_formula']=1; else $params['use_formula']=0;
	$lparams['lang_id']=$lang_id;	
	$params['formula']=abs((int)$_POST['formula']); //SecStr($_POST['formula']);
	$params['formula_name']=SecStr($_POST['formula_name']);
	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
		$li->Edit($id,$params,$lparams);
	}else{
		header('Location: no_rights.php');
			die();	
	}
	
	header('Location: viewprices.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 24)) {
		$li->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
			die();	
	}
	header('Location: viewprices.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование вида цены - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=27;
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


 
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_pr.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 $_bc->AddContext(new BcItem('Виды цен магазина', 'viewprices.php'));

$_bc->AddContext(new BcItem('Правка цены', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);





$smarty->display('page_top.html');

?>

	
	<form action="ed_pr.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование вида цены</h1>
	
	
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
	
	
	<label for="name">Название вида цены:</label><br>
	<input type="text" name="name" id="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="40" maxlength="255"><p>


	<label for="descr">Описание вида цены:</label><br>
	<textarea cols="30" rows="3" name="descr" id="descr"><?if($action==1) echo htmlspecialchars(stripslashes($lang['descr']));?></textarea><p>

	<label for="is_base">Базовая цена: </label><input class="unbord" name="is_base"   id="is_base"  type="checkbox" <? if(($action==1)&&($lang['is_base']==1)) echo 'checked'; ?> onclick="m=document.getElementById('formula_name'); if(this.checked){ m.value='base';}else{m.value=old};" /><p />
	
	
	
	
		



	<label for="use_formula">Использовать скидку от базовой цены: </label><input class="unbord" name="use_formula" id="use_formula" type="checkbox" onclick="u=document.getElementById('fblock'); if(this.checked){u.style.display='block';}else{u.style.display='none';};" <? if(($action==1)&&($lang['use_formula']==1)) echo 'checked'; ?> />

	<div id="fblock" style="display: <?if($action==0) echo 'none'; else{ if(($action==1)&&($lang['use_formula']==1)){echo 'block';} else echo 'none';}?>;">
	<label for="formula">% скидки:</label><br>
	<input type="text" name="formula" id="formula"  value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['formula']));?>" size="3" maxlength="3"><p>
	</div>		
				
		

		<label for="ord">Порядок показа:</label><br>
	<input type="text" name="ord"  id="ord" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['ord'])); else echo '0';?>" size="3" maxlength="3"><p>		


	<label for="formula_name">Идентификатор для формулы:</label><br>
	<input type="text" name="formula_name" id="formula_name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['formula_name']));?>" size="10" maxlength="40"><p>		
	

	<input type="submit" name="doInp" value="Внести изменения">
	</form>
	
	
	<script  language="JavaScript">  
	m=document.getElementById('formula_name'); 
	old=m.value;
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле  Название вида цены!");    
  
  frmvalidator.addValidation("ord","req","Заполните  поле  Порядок показа!");   
  frmvalidator.addValidation("ord","dec","В  поле  Порядок показа допустимы только десятичные числа!");   
  
   frmvalidator.addValidation("formula","num","В  поле  % скидки допустимы только цифры!");
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