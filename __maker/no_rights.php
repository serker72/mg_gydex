<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",SITETITLE);
$smarty->assign("NAVIMENU",'');


$smarty->display('page_mini_top.html');
unset($smarty);

?>
<div class="pole">
  <h2><?=NO_RIGHTS?></h2>
  
  <em><a href="<?=getenv('HTTP_REFERER')?>">вернуться и попробовать что-то другое...</a></em>
  
  
  
  
</div>
<?
//нижний шаблон

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
echo $smarty->fetch('page_mini_bottom.html');
unset($smarty);
?>