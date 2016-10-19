<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/distr_matrgr.php');

require_once('../classes/xajax/xajax_core/xajax.inc.php');
require_once('js/dmg_xajax_common.php');


//административная авторизация
require_once('inc/adm_header.php');


if(!isset($_GET['gfrom'])){
	if(!isset($_POST['gfrom'])){
		$gfrom=0;
	}else $gfrom = $_POST['gfrom'];	
}else $gfrom = $_GET['gfrom'];	
$gfrom=abs((int)$gfrom);

if(!isset($_GET['ofrom'])){
	if(!isset($_POST['ofrom'])){
		$ofrom=0;
	}else $ofrom = $_POST['ofrom'];	
}else $ofrom = $_GET['ofrom'];	
$ofrom=abs((int)$ofrom);	


//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'Права групп - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=39;
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
 


$_context->AddContext(new ContextItem( "", '',  "", "Справка", "discr_matrix_group.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Права групп', basename(__FILE__)));

 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);




$smarty->assign('xajax_scripts',$xajax_read->getJavascript('../classes/xajax'));
$smarty->display('page_top_xajax.html');
unset($smarty);

?>

<h2>Права групп</h2>


<div id="tableplace">
<?
$mg= new DistrMatrGr;

echo $mg->Draw('discr/overal.html', false, $gfrom, $ofrom, 100);
?>
</div>

	
<?
//нижний шаблон

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_bottom.html');
unset($smarty);
?>