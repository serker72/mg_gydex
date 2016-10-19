<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');



//���������������� �����������
require_once('inc/adm_header.php');


 $rights_man=new DistrRightsManager;
if(!$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	header('Location: no_rights.php');
	die();		
}


//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'GYDEX.�������� - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=46;
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


//���� ��������
require_once('delivery_menu.php');
$smarty->assign("MODULE_HMENU",$vmenu);


/*
//����������� �������
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

$_context->AddContext(new ContextItem( 11, 'r', "", "������ ������", "tree.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "������� � �������", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'a', "", "������� ������", "ed_razd.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 18, 'a', "HAS_NEWS", "������� �������", "ed_news.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 20, 'a', "HAS_PAPERS", "������� ������", "ed_paper.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 22, 'a', "HAS_PRICE", "������� �����", "ed_price.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 21, 'a', "HAS_PHOTOS", "������� ����", "ed_photo.php?action=0", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "������", "viewotzyv.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "�������", "viewads.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 14, 'r', "HAS_BASKET", "������", "vieworders.php", false , $global_profile  ));


$_context->AddContext(new ContextItem( 4, 'r', "", "������������ � �����", "discr_matrix_user.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 3, 'r', "", "����� �����", "discr_matrix_group.php", false , $global_profile  ));



$_context->AddContext(new ContextItem( "", '',  "", "�������", "common.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', '������� ��������');
*/
$smarty->display('page_delivery_top.html');
unset($smarty);

?>

 
    
    
	
	<?
	/*require_once('../classes/v2/gydex_stat.php');
	
	$stat=new GydexStat;
	
	print_r($stat->GetTotal('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetAverageTime('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetOrders('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetSubPerDay('2014-10-09'));*/
	
	?>
	
<?
//������ ������

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_bottom.html');
unset($smarty);
?>