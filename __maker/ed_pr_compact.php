<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


require_once('../classes/pricesitem.php');
require_once('../classes/conditem.php');
require_once('../classes/condgroup.php');
require_once('../classes/mmenulist.php');
require_once('../classes/pricegroup.php');
require_once('../classes/areaitem.php');

if(!HAS_PRICE){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
}

//административная авторизация
require_once('inc/adm_header.php');


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

if(!isset($_GET['mode']))
	if(!isset($_POST['mode'])) {
		$mode=3;
	}
	else $mode = $_POST['mode'];		
else $mode = $_GET['mode'];		
$mode=abs((int)$mode);

if(($mode!=1)&&($mode!=3)) $mode=3;

if(!isset($_GET['value_id']))
	if(!isset($_POST['value_id'])) {
		$value_id=0;
	}
	else $value_id = $_POST['value_id'];		
else $value_id = $_GET['value_id'];		
$value_id=abs((int)$value_id);



$li=new PricesItem();
$co=new CondItem();


if(($action==1)||($action==2)){
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
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
		}else{
			header('Location: no_rights.php');
			die();	
		}
		header('Location: ed_pr.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
		die();
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 24)) {
	  $lang=$li->GetItemById($id, $lang_id);
	  if($lang==false){
		  echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
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
		$pp_id=$li->Add($params,$lparams);
	}else{
		header('Location: no_rights.php');
			die();	
	}
	
	
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	//header('Location: viewprices.php?from='.$from.'&to_page='.$to_page);
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
	
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	//header('Location: viewprices.php?from='.$from.'&to_page='.$to_page);
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
		<td width="100%"  class="pole">
		
		<input type="button" name="closer" id="closer" value="Закрыть текущее окно" onclick="opener.location.reload(); window.close();">
		
		
		<form action="ed_pr_compact.php" method="post" name="inpp" id="inpp">
	<h2>Редактирование вида цены</h2>
	
	
	<input type="hidden" name="mode" value="<?=$mode?>">
	<input type="hidden" name="value_id" value="<?if($action==1) echo $value_id;?>">
	<input type="hidden" name="action" value="<?=$action?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	
	
	
	<strong>Название вида цены:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="40" maxlength="255"><p>


	<strong>Описание вида цены:</strong><br>
	<textarea cols="30" rows="3" name="descr" id="descr"><?if($action==1) echo htmlspecialchars(stripslashes($lang['descr']));?></textarea><p>

	<strong>Базовая цена: </strong><input class="unbord" name="is_base" type="checkbox" <? if(($action==1)&&($lang['is_base']==1)) echo 'checked'; ?> onclick="m=document.getElementById('formula_name'); if(this.checked){ m.value='base';}else{m.value=old};" /><p />
	
	
	<strong>Использовать скидку от базовой цены: </strong><input class="unbord" name="use_formula" id="use_formula" type="checkbox" onclick="u=document.getElementById('fblock'); if(this.checked){u.style.display='block';}else{u.style.display='none';};" <? if(($action==1)&&($lang['use_formula']==1)) echo 'checked'; ?> />

	<div id="fblock" style="display: <?if($action==0) echo 'none'; else{ if(($action==1)&&($lang['use_formula']==1)){echo 'block';} else echo 'none';}?>;">
	<strong>% скидки:</strong><br>
	<input type="text" name="formula" id="formula"  value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['formula']));?>" size="3" maxlength="3"><p>
	</div>		
				
		

		<strong>Порядок показа:</strong><br>
	<input type="text" name="ord" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['ord'])); else echo '0';?>" size="3" maxlength="3"><p>		


	Идентификатор для формулы:<br>
	<input type="text" name="formula_name" id="formula_name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['formula_name']));?>" size="10" maxlength="40"><p>	
		
		
		<input type="submit" name="doInp" id="doInp" value="Внести изменения">
	
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
		
		
	</td>
</tr>
</table>
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>