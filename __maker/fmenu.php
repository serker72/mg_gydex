<?
if(!isset($fmenu)) $fmenu='';

 require_once('../classes/v2/menubuilder.php');

$smarty_s=new SmartyAdm();

$_menu=new MenuBuilder(3);

$menu=$_menu->BuildMenu($global_profile['id']);

$smarty_s->assign("menu", $menu);

$fmenu=$smarty_s->fetch('fmenu.html');
?>