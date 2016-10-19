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
	header("Location: index.php");
	die();
}

//���������������� �����������
require_once('inc/adm_header.php');


$co=new CondItem();
$cg=new CondGroup();
if(!isset($_GET['price_id1']))
	if(!isset($_POST['price_id1'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $price_id1 = $_POST['price_id1'];		
else $price_id1 = $_GET['price_id1'];		
$price_id1=abs((int)$price_id1);
$pr=new PricesItem();

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 24)) {

  $price=$pr->GetItemById($price_id1);
  if($price==false){
	  echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		  die();
  }

}else{
	header('Location: no_rights.php');
					die();	
}

if(!isset($_GET['good_id']))
	if(!isset($_POST['good_id'])) {
		//header('Location: index.php');
		$good_id=0;
	}
	else $good_id = $_POST['good_id'];		
else $good_id = $_GET['good_id'];		
$good_id=abs((int)$good_id);




//��������� � ����� ������� ��� ������ ����!
if($price['cond_id']==0){
	//������ ��������� �������
	$action=0;
	
	
}else{
	//���������, ���� ������� ����������, �� �������� � ���
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 24)) {
	
		$cond=$co->GetItemById($price['cond_id']);
		if($cond==false){
		//���� ��� - ��� ����� �������� �������
			$action=0;
		}else $action=1;
	
	}else{
		header('Location: no_rights.php');
					die();	
	}
}


if(($action==0)&&isset($_POST['doInp'])){
	//������ ����� �������
	$params=Array(); $lparams=Array(); $mod=Array();
	
	$params['ffrom']=abs((int)$_POST['ffrom']);
	$params['tto']=abs((int)$_POST['tto']);
	$lparams['name']=SecStr($_POST['name']);
	$lparams['lang_id']=LANG_CODE;
	$params['area_id']=abs((int)$_POST['area_id']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
	
	  $ai=new AreaItem();
	  $ait=$ai->GetItemById(abs((int)$_POST['area_id']));
	  
	  
	  if($ait!=false){
		  
		  if($_POST[$ait['key_name']]!='0') {
			  $params['key_value']=abs((int)$_POST[$ait['key_name']]);
		  }else $params['key_value']=0;
	  }else $params['key_value']=0;
	  
	  $co_id=$co->Add($params,$lparams);
	  
	  $mod['cond_id']=$co_id;
	  
	  $pr->Edit($price_id1,$mod);
	  
	  echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	  die();
	}else{
		header('Location: no_rights.php');
					die();		
	}
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//������� ������������ �������
	$params=Array(); $lparams=Array(); $mod=Array();
	
	$params['ffrom']=abs((int)$_POST['ffrom']);
	$params['tto']=abs((int)$_POST['tto']);
	$lparams['name']=SecStr($_POST['name']);
	$lparams['lang_id']=LANG_CODE;
	$params['area_id']=abs((int)$_POST['area_id']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 24)) {
	
	  
	  $ai=new AreaItem();
	  $ait=$ai->GetItemById(abs((int)$_POST['area_id']));
	  
	  
	  if($ait!=false){
		  if($_POST[$ait['key_name']]!='0'){
			  $params['key_value']=abs((int)$_POST[$ait['key_name']]);
		  }else $params['key_value']=0;
	  }else $params['key_value']=0;
	  
	  $co->Edit($price['cond_id'], $params,$lparams);
	  
	  echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	  die();
	}else{
		header('Location: no_rights.php');
					die();		
	}
}



require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'������� ���������� ���� - '.SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="100%"  class="pole">
		
		<input type="button" name="closer" id="closer" value="������� ������� ����" onclick="opener.location.reload(); window.close();">
		
		<h2>������� ���������� ����: <?=stripslashes($price['name'])?></h2>
		<form action="viewconds.php" method="post" name="inpp" id="inpp">
		<input type="hidden" name="price_id1" value="<?=$price_id1?>">
		
		
		<p>
		
		
		<strong>�������� �������:</strong>	<br>
			<input type="text" name="name" id="name" size="60" maxlength="255" value="<?if($action==1) echo stripslashes($cond['name']); else if($action==0) echo '����� ������� ���������� ���� '.stripslashes($price['name']);?>">
		
		
		<h3>���������� ������:</h3>
		
		<table cellspacing="2" cellpadding="2" border="0">
		<tr>
		    <td width="50%"><strong>��</strong><br>
		<input type="text" name="ffrom" id="ffrom" size="5" maxlength="10" value="<?if($action==0) echo '1'; if($action==1) echo stripslashes($cond['ffrom']);?>"></td>
		    <td width="50%"><strong>��</strong><br>
		<input type="text" name="tto" id="tto" size="5" maxlength="10" value="<?if($action==0) echo '5'; if($action==1) echo stripslashes($cond['tto']);?>"></td>
		</tr>
		</table>

		<h3>������� ��������</h3>
		<select name="area_id" id="area_id" onChange="if((this.value==1)||(this.value==2)){m=document.getElementById('sel_good'); m.style.display='none'; m=document.getElementById('sel_razd'); m.style.display='block';}else if(this.value==3){m=document.getElementById('sel_razd'); m.style.display='none'; m=document.getElementById('sel_good'); m.style.display='block';} else if(this.value==0){m=document.getElementById('sel_razd'); m.style.display='none'; m=document.getElementById('sel_good'); m.style.display='none';}">
			<option value="0" >-�����-</option>
		<?
			if($action==0) echo $co->DrawAreas(0);
			if($action==1) echo $co->DrawAreas($cond['area_id']);
		?>
		</select><p>
		
		<div id="sel_razd" style="display: <?if($action==0) echo 'none;'; else if(($action==1)&&(($cond['area_id']==1)||($cond['area_id']==2))) echo 'block;'; else echo 'none';?>">
		<strong>������</strong><br>
		
		
		<select name="mid" id="mid" style="width: 300px;">
			<?
			$ml=new MmenuList();
			if($action==0) $mid=0;
			else if($action==1){
				if(($cond['area_id']==1)||($cond['area_id']==2)) $mid=$cond['key_value'];
				else $mid=0;
			}
			echo $ml->GetItemsOptByParentIdLangId($mid);
			?>
		</select><p></div>
		
		<div id="sel_good" style="display:  <?if($action==0) echo 'none;'; else if($cond['area_id']==3) echo 'block'; else echo 'none';?>">
		<strong>�����</strong><br>
		<select name="price_id" id="price_id" style="width: 300px; ">
			<option value="0">-�������� �����-</option>
			<?
			
			$pg=new PriceGroup();
			if($action==1){
				if($cond['area_id']==3) $good_id=$cond['key_value'];
			}
			
			echo $pg->GetItemsTotalOpt($good_id);
			?>
		</select><p>
		</div>
		
		
		
		
		
		<input type="submit" name="doInp" id="doInp" value="������ ���������">
	
		</form>
		
		<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","���������  ����  �������� �������!");    
 // frmvalidator.addValidation("signat","req","���������  ����  ����������� ������!");   
  
 // frmvalidator.addValidation("rate","req","���������  ����  ���� ������������ ������� ������!");   
  frmvalidator.addValidation("ffrom","req","���������  ����  ���������� ������: �� !");
  frmvalidator.addValidation("ffrom","num","�   ����  ���������� ������: �� ��������� ������ �����!");   
  frmvalidator.addValidation("ffrom","gt=0","�������� �������� � ����  ���������� ������: ��!"); 
  
  frmvalidator.addValidation("tto","req","���������  ����  ���������� ������: �� !");
  frmvalidator.addValidation("tto","num","�   ����  ���������� ������: �� ��������� ������ �����!");   
  frmvalidator.addValidation("tto","gt=0","�������� �������� � ����  ���������� ������: ��!");
  
</script>  
	</td>
</tr>
</table>
	
	
	<?
		
		//����� ���
		?>





	
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_noleft_bottom.html');
?>