<?
if(!isset($hmenu)) $hmenu='';

$smarty_s=new SmartyAdm;

require_once('../classes/mmenulist.php');
require_once('../classes/distr_rights_man.php');
$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
	$mm=new MmenuList();
	$_hmenu=$mm->GetItemsAdmSm('hmenu.html');
}else{
	$_hmenu=NO_RIGHTS;
}

require_once('../classes/v2/menubuilder.php');

if(!isset($_menu_id)) $_menu_id=0;

$_menu=new MenuBuilder(1);

$menu=$_menu->BuildMenu($global_profile['id'], $_menu_id);

$smarty_s->assign("menu", $menu);


$smarty_s->assign("login",$global_profile['login']);
$smarty_s->assign("username",$global_profile['username']);


$hmenu=$smarty_s->fetch('hmenu2.html');
?>