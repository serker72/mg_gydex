<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

 require_once('../classes/discr_authuser.php');


//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'�������������� ������ - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=20;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//���� � ����������� �� ������� �������
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);
 


$smarty->display('page_mini_top.html');
unset($smarty);

?>


<?
//var_dump($au->GetRightsTable());
$content='';
if(!isset($_GET['confirm'])||(strlen($_GET['confirm'])==0)){
	$content='
	<h1>������ ����� ������</h1>
	�� ����� ��� ������������� ��� ����� ������.
	
	';
}else{
	//�������� ���
	$_ui=new DistrUserItem;
	$user=$_ui->GetItemByFields(array('change_wait'=>1, 'change_password_confirm'=>SecStr($_GET['confirm'])));
	
	if($user===false){
		$content='
		<h1>������ ����� ������</h1>
		�������� ��� ������������� ��� ����� ������.
		';	
	}else{
		$_ui->Edit($user['id'], array('change_wait'=>0, 'passw'=>$user['new_password']));
		
		 
		
		$content='
		<h1>C���� ������</h1>
		������ ������� �������.<br />
		��� ����������� ������, ����������, ������������� �� ����� ������.
		';	
		
	 
	}
	
	
	
	
}

if($result===NULL){
	
	
	echo $content;
	
}else{
	
	
	 
}
?>
	
<?
//������ ������

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_mini_bottom.html');
unset($smarty);
?>