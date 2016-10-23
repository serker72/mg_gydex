<?
require_once('classes/global.php');
require_once('classes/smarty/SmartyAdm.class.php');

$smarty_s = new SmartyAdm;
$smarty_s->debugging = DEBUG_INFO;

$smarty_s->assign('flags',$lg->GetSwitchCli(false));

$tmp=$fi->GetItem('parts/razd3-'.$_SESSION['lang'].'.txt');
$smarty_s->assign('text1',stripslashes($tmp));


$smarty_s->assign('FEEDBACK_PHONE',FEEDBACK_PHONE);
$smarty_s->assign('FEEDBACK_EMAIL', FEEDBACK_EMAIL);

/*
$tmp=$fi->GetItem('parts/razd8-'.$_SESSION['lang'].'.txt');
$smarty_s->assign('text2',eregi_replace("<p>|</p>|<p />|<br>|<br />","",stripslashes($tmp)));
*/

//работа с гориз меню
require_once('classes/mmenulist.php');
$ml=new MmenuList();
$add=Array(); $add['is_menu_1']=1;
if(!isset($current_mid)) $current_mid=0; $temp_ids=array();
$hmenu1_res=$ml->GetItemsMainCli('','','',   0,  $current_mid, $lang, false,$add,true, $temp_ids);
$smarty_s->assign('hmenu',$hmenu1_res);



/*$tmp=$fi->GetItem('parts/razd8-'.$_SESSION['lang'].'.txt');
$arr=explode("\r\n", $tmp);
if(count($arr)>0){
	$_str=$arr[rand(0,count($arr)-1)];
	$smarty_s->assign('bigtitle',stripslashes($_str));
}*/

$header_res=$smarty_s->fetch('common_header.html');
unset($smarty_s);
?>