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


$li=new AllDictItem();

if(!isset($_GET['parent_id']))
	if(!isset($_POST['parent_id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $parent_id = $_POST['parent_id'];		
else $parent_id = $_GET['parent_id'];		
$parent_id=abs((int)$parent_id);

if(!isset($_GET['kind']))
	if(!isset($_POST['kind'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $kind = $_POST['kind'];		
else $kind = $_GET['kind'];		
$kind=abs((int)$kind);
if(($kind!=1)&&($kind!=2)&&($kind!=3)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}


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
			echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	//смена языка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемся добавить язык
		
		$li->AddLanguage($id,$lang_id);
		header('Location: ed_dict_compact.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&parent_id='.$parent_id.'&kind='.$kind.'&nonvisual='.$nonvisual);
		die();
	}
	
	
	$lang=$li->GetItemById($id, $lang_id);
	if($lang==false){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
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
	
	$li->Edit($id, $params, $lparams);
	
	header('Location: alldicts.php?id='.$parent_id.'&kind='.$kind);
	die();
}


if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
	$params['kind_id']=abs((int)$_POST['kind_id']);
	
	$code=$li->Add($params,$lparams);


	if(isset($_POST['linked'])&&($_POST['linked']!=0)){
		$linked=abs((int)$_POST['linked']);
		$da=new DictAttachItem();
		$par=Array();
		$par['dict_id']=$code;
		$par['attach_code']=$linked;
		$par['key_value']=$parent_id;
		$da->Add($par);
	}
	
	
	
	//header('Location: alldicts.php?id='.$parent_id.'&kind='.$kind.'#'.$code);
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//
	
	
	
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['big_txt']=SecStr($_POST['big_txt']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
		$params['kind_id']=abs((int)$_POST['kind_id']);
		
	
	$li->Edit($id,$params,$lparams);
	
	if(isset($_POST['linked'])){
		$linked=abs((int)$_POST['linked']);
		$disp->ChangeAttach($id, $parent_id, $linked);
	}
	
	//header('Location: alldicts.php?id='.$parent_id.'&kind='.$kind.'#'.$id);
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
	
}


if($action==2){
//удаление
	$li->Del($id);
	unset($li);
	//header('Location: alldicts.php?id='.$parent_id.'&kind='.$kind);
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_noleft_top.html');

?>

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
	<form action="ed_dict_compact.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование словаря свойств</h1>
	
	
	<input type="hidden" name="parent_id" value="<?=$parent_id?>">
	<input type="hidden" name="kind" value="<?=$kind?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
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
	
	
	<strong>Название словаря:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="100" maxlength="255"><p>


	Описание словаря:<br />
    <textarea cols="80" rows="20" name="big_txt"><?if($action==1) echo htmlspecialchars(stripslashes($lang['big_txt']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					
				}
				?>
		        <p />
				
				
				
		<strong>Отображать на сайте: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; ?> /><p />
		
		
		<strong>Порядок показа:</strong> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $lang['ord']; else echo '1'; ?>" /><p />





	<?
	if($action==1){
	 	$adg=new AllDictItem(); $res=$adg->CheckBoundsByIdKind($id,$parent_id,$kind);
		/*echo $kind;
		foreach($res as $k=>$v){
			echo "<strong>$k=</strong>$v<br>";
		}*/
		if(isset($res['has_inh'])){
		?>
		<em>прикрепление унаследовано</em><P>
		<?
		}else{
			$selected_id=$res['flag'];
			echo $disp->GetAttachKinds($selected_id,'linked', '');
		}
	}
	else if($action==0){
		echo $disp->GetAttachKinds(0,'linked', '');
	}?>
	<p>


	

	<strong>Вид словаря:</strong>	<br>
	<select name="kind_id"><?if($action==1)  echo $li->GetBehaviorsOpt($lang['kind_id']); else echo $li->GetBehaviorsOpt(); ?></select><p>

	<input type="submit" name="doInp" value="Внести изменения">
	
	<input type="button" value="Закрыть текущее окно" onclick="window.close();">
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле  Название словаря!");    
</script>  
	
	</td>
	</tr>
	</table>
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_noleft_bottom.html');
?>