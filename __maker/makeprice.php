<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/pricesitem.php');
require_once('../classes/conditem.php');
require_once('../classes/condgroup.php');
require_once('../classes/mmenulist.php');
require_once('../classes/pricegroup.php');
require_once('../classes/goodspriceitem.php');


if(!HAS_PRICE){
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
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}



$go=new PriceItem();
$pr=new PricesItem();
$pi=new GoodsPrice();

if(!isset($_GET['good_id']))
	if(!isset($_POST['good_id'])) {
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $good_id = $_POST['good_id'];		
else $good_id = $_GET['good_id'];		
$good_id=abs((int)$good_id);
$good=$go->GetItemById($good_id);
if($good==false){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}

if(!isset($_GET['price_id']))
	if(!isset($_POST['price_id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $price_id = $_POST['price_id'];		
else $price_id = $_GET['price_id'];		
$price_id=abs((int)$price_id);
$price=$pr->GetItemById($price_id);
if($price==false){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}


//��������� � ����� ������ ����
$pg=$pi->GetPriceByGoodIdPriceId($good_id, $price_id);
if($pg==false){
	//������ ��������� ����
	$action=0;
	
}else{
	//���� ���� ����������, �� �������� � ���
	
	$action=1;
	
}





//echo $action; die();
if(($action==0)&&isset($_POST['doInp'])){
	//������ ����� ����
	$params=Array(); 
	$params['value']=abs((float)$_POST['value']);
	$params['good_id']=$good_id;
	$params['price_id']=$price_id;	
	if(isset($_POST['not_use_formula'])) $params['not_use_formula']=1; else $params['not_use_formula']=0;
	
	//������� ���� �������� ������
	$bsc=new CurrencyGroup();
	$curr_id=$bsc->GetBaseCurrencyId();
	if($curr_id!=false) $params['curr_id']=$curr_id;
	
	$pi->Add($params);
	


	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
}
	
	
if(($action==1)&&isset($_POST['doInp'])){
	//������������ ����
	$params=Array(); 
	$params['value']=abs((float)$_POST['value']);
	$params['good_id']=$good_id;
	$params['price_id']=$price_id;	
	if(isset($_POST['not_use_formula'])) $params['not_use_formula']=1; else $params['not_use_formula']=0;
	
	//������� ���� �������� ������
	$bsc=new CurrencyGroup();
	$curr_id=$bsc->GetBaseCurrencyId();
	if($curr_id!=false) $params['curr_id']=$curr_id;
	
	$pi->Edit($pg['id'],$params);
	
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
}

require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->assign("no_header", true);


$smarty->display('page_noleft_top.html');

?>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="100%"  class="pole">
		
		
		
		<h3>���� �� �����: <?=stripslashes($good['name'])?></h3>
		<form action="makeprice.php" method="post" name="inpp" id="inpp">
		<input type="hidden" name="price_id" value="<?=$price_id?>">
		<input type="hidden" name="good_id" value="<?=$good_id?>">
		
		<?if(($price['is_base']==0)&&($price['use_formula']==1)){?>
		<input class="unbord" type="checkbox" name="not_use_formula" value="" <?if(($action==1)&&($pg['not_use_formula']==1)) echo 'checked';?>><strong> �� ������������ �������, ������������ ��������:</strong><br>
		<?}?>
		
		<strong>�����:</strong>	<br>
		<input type="text" name="value" id="value" size="20" maxlength="255" value="<?if($action==1) echo stripslashes($pg['value']); ?>"><p>
		
		<input type="submit" name="doInp" id="doInp" value="������ ���������">
		
		<input type="button" name="closer" id="closer" value="������" onclick="opener.location.reload(); window.close();">
	
		</form>
		
		
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
$smarty->assign("no_footer", true);


$smarty->display('page_noleft_bottom.html');
?>