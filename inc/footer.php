<?
require_once('classes/global.php');
require_once('classes/smarty/SmartyAdm.class.php');

$smarty_s = new SmartyAdm;
$smarty_s->debugging = DEBUG_INFO;


$smarty_s->assign('OFFICE_ADDRESS', str_replace('ул.', '<br>ул.', OFFICE_ADDRESS));
$smarty_s->assign('FEEDBACK_PHONE', implode('<br>', explode(',', FEEDBACK_PHONE_FOOTER)));
$smarty_s->assign('FEEDBACK_EMAIL', FEEDBACK_EMAIL);



$smarty_s->assign('footer3',$mlss);

//подвал 1
$tmp=$fi->GetItem('parts/razd1-'.$_SESSION['lang'].'.txt');
$smarty_s->assign('footer1',stripslashes($tmp));



 

//подвал 2
$tmp=$fi->GetItem('parts/razd2-'.$_SESSION['lang'].'.txt');
//$smarty_s->assign('footer2',stripslashes($tmp));
$smarty_s->assign('footer2', strip_tags($tmp));




require_once('classes/mmenulist.php');

//вывод меню 3

$ml=new MmenuList();

$add=Array(); $add['is_menu_3']=1;
if(!isset($current_mid)) $current_mid=0;
$hmenu1_res=$ml->GetItemsMainCli('','','',   0,   $current_mid, $lang, false,$add, false, $temp_ids, array(' ord_3 desc ') );
$smarty_s->assign('hmenu',$hmenu1_res);


//вывод меню 4
 
$mmenu4=$ml->GetArr(array('is_menu_4'=>1), $lang, array(' ord desc '), 1, $current_mid) ;
$smarty_s->assign('mmenu4',$mmenu4);

// Список услуг в подвале
$og=new LinksGroup;
$smarty_s->assign('services_in_footer', $og->GetItemsByIdCli('index_services_in_footer.html', '', '', '', 17));

$footer_res=$smarty_s->fetch('common_footer.html');
unset($smarty_s);



if(!isset($do_count)) $do_count=true;
if($do_count){
	require_once('classes/v2/gydex_stat.php');
	$_gs=new GydexStat();
	
	
	 
	$_gs->Put(getenv('HTTP_X_REAL_IP') /*$_SERVER['REMOTE_ADDR']*/, $_SERVER['REQUEST_URI']);
}


/*$n=new mysqlSet();
echo $n->ShowInstCount();*/
?>