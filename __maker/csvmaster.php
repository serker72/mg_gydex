<?
//session_name("csvmaster");
session_start();
require_once('../classes/global.php');
require_once('../classes/langgroup.php');
require_once('csvsettings.php');

require_once('../classes/csvformer.php');
require_once('../classes/smarty/SmartyAdm.class.php');

if(!HAS_OST){
	header("Location: index.php");
	die();
}

//���������������� �����������
require_once('inc/adm_header.php');


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 22)) {}
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

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}



$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}


if(!isset($_GET['step'])){
	if(!isset($_POST['step'])){
		$step=1;
	}else $step = $_POST['step'];	
}else $step = $_GET['step'];	
$step=abs((float)$step);	
if(($step>5)||($step<1)) $step=1;

//������������� ���������� ������
if(!isset($_SESSION['csvmaster']['filename'])) $_SESSION['csvmaster']['filename']=NULL;
if(!isset($_SESSION['csvmaster']['process_filename'])) $_SESSION['csvmaster']['process_filename']=NULL;
if(!isset($_SESSION['csvmaster']['separator'])) $_SESSION['csvmaster']['separator']=';';
if(!isset($_SESSION['csvmaster']['records_per_page'])) $_SESSION['csvmaster']['records_per_page']=ITEMS_PER_PAGE;
if(!isset($_SESSION['csvmaster']['do_delete_file'])) $_SESSION['csvmaster']['do_delete_file']=1;

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if($step==1){
	//�������� ���� ������
	$_SESSION['csvmaster']['filename']=NULL;
	$_SESSION['csvmaster']['process_filename']=NULL;
}


//��������� ����������� ������� ����
if($step==1.5){
	//������� ��� � ������ � ��������� �������������� ����
	
	$_SESSION['csvmaster']['separator']=SecStr($_POST['separator']);
	$_SESSION['csvmaster']['records_per_page']=abs((int)$_POST['records_per_page']);
	if(isset($_POST['do_delete_file'])) $_SESSION['csvmaster']['do_delete_file']=1;
	else $_SESSION['csvmaster']['do_delete_file']=0;
	
	
	//������� ��������� ���� � ������� ��� � ������
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	$CsvFormer->CreateTempFile();
	
	//����������� � ����� ������������ �����
	//���� ��� ������, �� ������������ ����������� ����
	//���� �� ���� �������, � ��� ������ ������� ���� - �� ������ ��������� ��� � ������
	if($_POST['load_mode']==1){
		//��� ������
		if(isset($_FILES['file_to_upload'])){
			$res=@copy($_FILES['file_to_upload']['tmp_name'],PHOTOS_BASEPATH.$_FILES['file_to_upload']['name']);
			if($res) $_SESSION['csvmaster']['filename']=$_FILES['file_to_upload']['name'];
		}	
	}else if($_POST['load_mode']==2){
		
		$filename=SecStr($_POST['photo_small']);
		if(eregi("^(.*)\\.(csv)$",$filename,$P)&&file_exists(realpath(ABSPATH.$filename))) {
			$_SESSION['csvmaster']['filename']=basename($filename);
			//echo '��� ����� ��� ������ '.ABSPATH.$filename;
		}
	}
	//die();
	$step=2;
}

//��������, ���� �� � �������������� ����� ������� �������,
//�� ������� ������� ����
//� �� �������� �� � ���� 3
if($step==2){
	if(isset($_POST['doAnother'])){
		//�������� ���� ������
		$_SESSION['csvmaster']['filename']=NULL;
		$_SESSION['csvmaster']['process_filename']=NULL;
		$step=1;
	}else{
	
		
		//�������� ��������� �� ��������� ����
		if(isset($_POST['doMakeIt'])|| isset($_POST['doPrev'])|| isset($_POST['doNext'])){
			//������, � ����� �� ����
			//���, �� ����+1 �� ����+�������_��_��������
			
			$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
			//�� ��������
			foreach($_POST as $k=>$v){
				if(eregi("_art", $k)){
					//$v = articul
					//echo "$from:  $k - $v<br>";
					$csv_hash=md5($v);
					
					$params=Array();
					$params['action']=abs((int)$_POST[$csv_hash.'_action']);
					$params['articul']=SecStr($v);
					$params['name']=SecStr($_POST[$csv_hash.'_name']);
					$params['ost']=abs((int)$_POST[$csv_hash.'_ost']);
					$params['mid']=SecStr($_POST[$csv_hash.'_mid']);
					
					$CsvFormer->EditRecord($from, $v, $params);
					//die();
				}
			}
			
		}
		
		
		if(isset($_POST['doMakeIt'])){
			$step=3;
			$from=0;
		}else if(isset($_POST['doPrev'])){
			$from=$from-$_SESSION['csvmaster']['records_per_page'];
			if($from<0) $from=0;
		}else if(isset($_POST['doNext'])){
			$from=$from+$_SESSION['csvmaster']['records_per_page'];
		}
		
		//�������� ����
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		$fres=$CsvFormer->CheckFromPosition($from);
		if($fres==-2) {
			$step=3;
			$from=0;
		}
		
	}
}

if($step==3){
	if(isset($_POST['BackToStep2'])){
		
		//�������� ��������� ����
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		//$CsvFormer->ClearTempFile();
		$from=0;
		$step=2;
	}else{
		//�������� ��������� �� ��������� ����
		if(isset($_POST['doUpdateIt'])|| isset($_POST['doPrev1'])|| isset($_POST['doNext1'])){
			//������, � ����� �� ����
			//���, �� ����+1 �� ����+�������_��_��������
			
			$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
			//�� ��������
			foreach($_POST as $k=>$v){
				if(eregi("_art", $k)){
					//$v = articul
					$csv_hash=md5($v);
					
					$params=Array();
					$params['action']=abs((int)$_POST[$csv_hash.'_action']);
					$params['articul']=SecStr($v);
					$params['name']=SecStr($_POST[$csv_hash.'_name']);
					$params['ost']=abs((int)$_POST[$csv_hash.'_ost']);
					$params['mid']=SecStr($_POST[$csv_hash.'_mid']);
					
					$CsvFormer->EditRecord($from, $v, $params);
				}
			}
		}
		
		
		if(isset($_POST['doUpdateIt'])){
			$step=4;
		}else if(isset($_POST['doPrev1'])){
			$from=$from-$_SESSION['csvmaster']['records_per_page'];
			if($from<0) $from=0;
		}else if(isset($_POST['doNext1'])){
			$from=$from+$_SESSION['csvmaster']['records_per_page'];
		}
		
		//�������� ����
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		$fres=$CsvFormer->CheckFromPositionRez($from,$cc);
		if($fres==-2) $step=4;
	}
}

if($step==4){
	$from=0;
	
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	$update_result=$CsvFormer->UpdateBase('csvmaster/table3.html');
	
	
	//die();
}



	
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' - CSV-������');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//���� � ����������� �� ������� �������
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//�����-���
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');

?>

<h1>CSV-������ �������� ��������</h1>

<?
if($step==1){?>
<h2>��� 1. ����� ��������� �������������.</h2>
<h3>��������� ��� �������� csv-����:</h3>
<form action="csvmaster.php" enctype="multipart/form-data" method="post" name="filesel" id="filesel">
	<input type="hidden" name="step" id="step" value="1.5">
	
	<strong><input type="radio" name="load_mode" id="load_mode1" value="1" checked>��������� ����:</strong><br>
	<input type="file" name="file_to_upload" id="file_to_upload" size="80" onClick="m=document.getElementById('load_mode1'); m.checked=true;"><p>
	
	<strong><input type="radio" name="load_mode" id="load_mode2" value="2">������� ��� ����������� ����:</strong><br>
	<input type="text" name="photo_small" id="photo_small" size="80" maxlength="255" onClick="m=document.getElementById('load_mode2'); m.checked=true;">
	<a href="javascript: //" class="" onclick="m=document.getElementById('load_mode2'); m.checked=true; window.open('csvfiles.php?mode=2','file_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.filesel.photo_small.value=''; document.forms.filesel.photo_small.focus();">������� ����</a>
	<p>
	
	
	<strong>������� �� ��������:</strong><br>
	<input type="text" name="records_per_page" id="records_per_page" value="<?=$_SESSION['csvmaster']['records_per_page']?>" size="3" maxlength="3"><p>
	
	<strong>����������� � csv-�����:</strong><br>
	<input type="text" name="separator" id="separator" value="<?=$_SESSION['csvmaster']['separator']?>" size="1" maxlength="1"><p>
	
	<input class="unbord" type="checkbox" name="do_delete_file" id="do_delete_file" value="1" <?if($_SESSION['csvmaster']['do_delete_file']==1){?>checked<?}?>><strong>������� ����������� ����</strong><p>
	
	<input type="submit" name="doSver" id="doSver" value="���������� -&gt;"><p>
	
	<input type="button" name="doCancel" id="doCancel" value="������" onClick="window.close();">
</form>
<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("filesel");  
  frmvalidator.addValidation("records_per_page","req","���������  ���� ������� �� ��������!");    
  frmvalidator.addValidation("records_per_page","num","� ���� ������� �� �������� ��������� ������ �����!");    
  frmvalidator.addValidation("records_per_page","gt=0","� ���� ������� �� �������� ��������� ������ �������� ������ ����!"); 
  frmvalidator.addValidation("separator","req","���������  ���� ����������� � csv-�����!");    
</script>  
<?}?>	
	
<?if($step==2){?>
	<h2>��� 2. ��������� ������ csv-����� � ���� ������ �����.</h2>
	<h3>�������� �������� ��� ��������:</h3>
	<p><em>�� ���� ���� �� ���������� ������� �� ���������� ���� ������. ����� ���������� ������ �� ������������ ���� �� ���� 1 �����, ������ - ��������������� �� ������ ���� ������. ���� ������ � ���� ������ ������� - �� ������ ������� ���������� <span style="background-color: #ccffcc;">������� ������</span>, ���� �� ������� - ��  <span style="background-color: #ffcccc;">������� ������</span>.<br><br>

<strong>���������� ���������� ��� ������ csv-�����! ����� - ��������������� ������ �� ����� ������� � ���� ������!</strong>
</em></p>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	echo $CsvFormer->DrawTable1('csvmaster/table1.html',$from);
	
	?>

<?}?>	

<?
//echo $step;
?>


<?if($step==3){?>
	<h2>��� 3. �������� ������� �������������.</h2>
	<h3>����������� ��������� ���������:</h3>
	<p>
	<em>�� ���� ���� �� �������������� ��� �������� ���� �������� �� ���� 2, ������ �������������� ������ �� ���, � ������������� �������� ��������� � ���� ������.<br>
<br>
<strong>����� �� ������������ �� ����� ��� ������, ���� �� ��������� ������� � ����� ������, ��������� �� ���� 2.</strong>
</em>
	</p>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	echo $CsvFormer->DrawTable2('csvmaster/table2.html',$from);
	?>
<?}?>	

<?if($step==4){?>
	<h2>��� 4. �������� ���������.</h2>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	//echo $CsvFormer->UpdateBase();
	if(isset($update_result)) echo $update_result;
	?>
<?}?>	
	
	
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>	