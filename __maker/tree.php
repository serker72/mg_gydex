<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

require_once('../classes/v2/sitetree_new.php');

//���������������� �����������
require_once('inc/adm_header.php');



//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'������ �������� - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=41;
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


//����������� �������
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;
$_context->AddContext(new ContextItem( 11, 'r', "", "������� �������� �������", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'a', "", "������� ������", "ed_razd.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( "", '',  "", "�������", "tree.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);






//������� ������
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('�����', 'index.php'));
$_bc->AddContext(new BcItem('������ ��������', basename(__FILE__)));

 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);












$smarty->display('page_top.html');
unset($smarty);

?>
<link href="/js/ui/jquery-ui-custom.css" rel="stylesheet" type="text/css" />
<style>
.accordion, #accordion{
	min-width:500px;
	width:auto;
	max-width:1600px;
	margin-top:10px;	
}
</style>	
<script>
$(function() {
$( ".accordion" ).accordion({ collapsible: true, heightStyle: 'content', active: false });
});
</script> 
	<?
		
		//����� ���
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
		?>
	
		<h1>������ �������� �����</h1>
        <div class="accordion_inside">
		
	<?
$tree=new SiteTreeNew(LANG_CODE);
$tpls=Array(); 
$tpls['level']='tree_new_test.html';
$tree->SetTemplates($tpls);
$tree->SetNewPages(false);
echo $tree->DrawTreeAdm();

		}else{
			echo NO_RIGHTS;	
		}
?>
	
		</div>
	
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