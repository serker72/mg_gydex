<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/alldictsgroup.php');

require_once('../classes/alldictitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/dictattachitem.php');

require_once('../classes/dictattdisp.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//���������������� �����������
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


//����� ������
if(!isset($_SESSION['mode'])){
	$mode=1;
}else $mode=abs((int)$_SESSION['mode']);
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();
//echo $mode;

if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

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

//������������ ������� �� ������ �� ������
if(isset($_POST['doAttach'])){
	if(isset($_POST['dict'])){
		$dict_id=abs((int)$_POST['dict']);
		//������� ������ ��������
		if(!isset($_GET['attach_kind']))
			if(!isset($_POST['attach_kind'])) {
				$attach_kind=$kind;
			}
			else $attach_kind = $_POST['attach_kind'];		
		else $attach_kind = $_GET['attach_kind'];		
		$attach_kind=abs((int)$attach_kind);
		
		if($dict_id!=0){
			$disp->AttachDict($dict_id, $id, $attach_kind);
		}
	}
	
	header('Location: viewattdicts.php?id='.$id.'&kind='.$kind);
	die();
}

//�������� � ������������ �������
if(isset($_POST['doCreateAttach'])){
	$params=Array();
	$lparams=Array();
	
	$lparams['name']=SecStr($_POST['dict_name']);
	$lparams['big_txt']='';
	$lparams['is_shown']=0;
	$lparams['lang_id']=LANG_CODE;	
	$lparams['is_shown']=1;	
	
	
	$params['kind_id']=abs((int)$_POST['kind_id']);
	$li=new AllDictItem();
	$dict_id=$li->Add($params,$lparams);
	
	//echo $dict_id;
	
	//������� ������ ��������
	if(!isset($_GET['attach_kind']))
		if(!isset($_POST['attach_kind'])) {
			$attach_kind=$kind;
		}
		else $attach_kind = $_POST['attach_kind'];		
	else $attach_kind = $_GET['attach_kind'];		
	$attach_kind=abs((int)$attach_kind);
	if($dict_id!=0){
		$disp->AttachDict($dict_id, $id, $attach_kind);
	}
	//echo $attach_kind;
	//die();
	header('Location: viewattdicts.php?id='.$id.'&kind='.$kind);
	die();
}

//����� ������� ������������
if(isset($_GET['changeOrd'])){
	$params=Array(); 
	
	if(!isset($_GET['parent_id'])) {
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $parent_id = $_GET['parent_id'];		
	//��� ��������
	$parent_id=abs((int)$parent_id);
	
	if($_GET['changeOrd']=='0') {
		$disp->ChangeOrd($parent_id,0);
	}
	
	if($_GET['changeOrd']=='1') {
		$disp->ChangeOrd($parent_id,1);
	}
	
	header('Location: viewattdicts.php?id='.$id.'&kind='.$kind);
	die();
}

//�������� ��������
if(isset($_GET['action'])){
	$action=abs((int)$_GET['action']);
	if(($action==2)&&($kind!=2)){
		if(!isset($_GET['parent_id'])) {
			echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
			die();
		}
		else $parent_id = $_GET['parent_id'];		
		//��� ��������
		$parent_id=abs((int)$parent_id);
		
		$disp->Del($parent_id);
	}
	
	header('Location: viewattdicts.php?id='.$id.'&kind='.$kind);
	die();
}


require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');
$smarty->assign('no_header', true);
$smarty->display('page_noleft_top.html');


$dg=new AllDictsGroup();
/*
foreach($_GET as $k=>$v){
	echo "$k = $v<br>";
}*/
//echo $mode;
?>
	

	<?if(($kind==1)||($kind==3)){?>
	<form action="viewattdicts.php" method="post" class="pole">
	<strong>����������:</strong>
	
	<select name="dict" id="dict" style="width: 200px;">
		<option value="0" SELECTED>-�������-</option>
		<?
		echo $disp->GetItemsOptExcept($id,$mode);
		?>
	</select>
	
	<br>
	<select name="attach_kind" id="attach_kind" style="width: 250px;">
		<?
		echo $disp->GetAttachKindsOpt($mode,false);
		?>
	</select>
	
	
	<input type="submit" name="doAttach" id="doAttach" value="��">
	
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="kind" value="<?=$kind?>">
	</form>
	<?}?>
	
	
	<?
		
		if($kind==2)	$disp->SetTemplates('alldicts/items-attached.html', 'tpl/dicts_comp/att_itemsrow_inh.html', 'tpl/dicts_comp/att_itemsrow_inh.html', 'tpl/to_page.html','tpl/dicts_comp/att_subitem_name.html','tpl/dicts_comp/att_subitem_lang_vis_check.html');
		else $disp->SetTemplates('alldicts/items-attached.html', 'tpl/dicts_comp/att_itemsrow.html', 'tpl/dicts_comp/att_itemsrow.html', 'tpl/to_page.html','tpl/dicts_comp/att_subitem_name.html','tpl/dicts_comp/att_subitem_lang_vis_check.html');
		echo $disp->ViewAttached($id,$kind);  //GetItemsTiny($id,$kind);
		
//		echo '<pre>'.htmlspecialchars($disp->ViewAttached($id,$kind)).'</pre>';  //GetItemsTiny($id,$kind);
		?>
	
	<?if(($kind==1)||($kind==3)){?>
	<form action="viewattdicts.php" method="post" class="pole">
	<h4>������� � ���������� �������:</h4>
	
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="kind" value="<?=$kind?>">
	<strong>��������:</strong>
	<input type="text" name="dict_name" id="dict_name" size="20" maxlength="255"><br>
	<strong>���:</strong><br>
	
	<select name="kind_id" style="width: 200px;">
	<?
	$li = new AllDictItem();
	echo $li->GetBehaviorsOpt();
	?>
	</select><br>
	
	
	<strong>���������� �:</strong><br>
	
	<select name="attach_kind" id="attach_kind" style="width: 280px;">
		<?
		echo $disp->GetAttachKindsOpt($mode,false);
		?>
	</select>
	<input type="submit" name="doCreateAttach" id="doCreateAttach" value="��">
	</form>
	<?}?>
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign('no_footer', true);
$smarty->display('page_noleft_bottom.html');
?>