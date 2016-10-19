<?
require_once('classes/global.php');

require_once('classes/smarty/SmartyAdm.class.php');

$smarty_s = new SmartyAdm;
$smarty_s->debugging = DEBUG_INFO;


//работа с поиском
require_once('classes/resoursefile.php');
$rf=new ResFile(ABSPATH.'cnf/resources.txt');
$smarty_s->assign('srch_value','');
$smarty_s->assign('srch_caption',$rf->GetValue('searchblock.php','srch_caption',$lang));
$smarty_s->assign('srch_title',$rf->GetValue('searchblock.php','srch_title',$lang));


$ml=new MmenuList();
//левое меню
/*if(!isset($current_mid)) $current_mid=0;
$add=Array(); $add['is_v_active']=1;

$smarty_s->assign('items', $ml->GetActiveArr($lang, 1, $current_mid));
*/


/*$tmp=$fi->GetItem('parts/razd8-'.$_SESSION['lang'].'.txt');
$arr=explode("\r\n", $tmp);
if(count($arr)>0){
	$_str=$arr[rand(0,count($arr)-1)];
	$smarty_s->assign('bigtitle',stripslashes($_str));
}
*/


require_once('classes/bansgroup.php');
$bangr=new BansGroup();
$smarty_s->assign('upbanners',$bangr->GetBannersArr($lang,1));

 


if(!isset($has_slider)) $has_slider=false;
$smarty_s->assign('has_slider', $has_slider);

$left_res=$smarty_s->fetch('common_left.html');
unset($smarty_s);

?>