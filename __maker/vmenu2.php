<?
if(!isset($vmenu)) $vmenu='';
$smarty_s=new SmartyAdm;



require_once('../classes/v2/menubuilder.php');

if(!isset($_menu_id)) $_menu_id=0;


$_menu=new MenuBuilder(2);

$menu=$_menu->BuildMenu($global_profile['id'], $_menu_id);

$smarty_s->assign("menu", $menu);



require_once('../classes/mmenulist.php');
require_once('../classes/distr_rights_man.php');
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
	$mm=new MmenuList();
	$vmenu=$mm->GetItemsAdmSm('vmenu.html',0);
}else{
	$vmenu=NO_RIGHTS;	
}

 

$vmenu=$smarty_s->fetch('vmenu2.html');
?>