<?
require_once('classes/global.php');
require_once('classes/resoursefile.php');
require_once('classes/smarty/SmartyAdm.class.php');

$smarty_s = new SmartyAdm;
$smarty_s->debugging = DEBUG_INFO;
/*
if(isset($current_mid)){
  $ml2=new MmenuList();
  $smarty_s->assign('menu', $ml2->GetSubsByIdCli($current_mid, 'submenu.html', 'tpl/submenu_rows.html', 'tpl/submenu_item.html', $lang, 'img/bullet-sub.gif'));
  
}
*/
/*
$tmp=$fi->GetItem('parts/razd3-'.$_SESSION['lang'].'.txt');
$smarty_s->assign('text1',stripslashes($tmp));

if(isset($menu_to_draw)){
	$smarty_s->assign('menu',$menu_to_draw);
}

//блок корзины
if((HAS_BASKET)&&(HAS_PRICE)){
	require_once('classes/basket.php');
	$basket=new Basket();
	
	$basket->SetShowErrors(false);
	$bres=$basket->DrawBasketSmall($lang);
	$smarty_s->assign('basketplace',$bres);
}else 	$smarty_s->assign('basketplace','');



//блок авторизации
if((HAS_BASKET)&&(HAS_PRICE)){
	require_once('classes/authuser.php');
	$smarty_s->assign('has_auth',true);
	$au=new AuthUser();
	//проверим авторизацию
	$profile=$au->Auth();
	if($profile===NULL){
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$smarty_s->assign('authorized', false);
		$smarty_s->assign('login_prompt',$rf->GetValue('auth_block.php','login_prompt',$lang));
		$smarty_s->assign('auth_title',$rf->GetValue('auth_block.php','auth_title',$lang));
		$smarty_s->assign('def_log',$rf->GetValue('auth_block.php','def_log',$lang));
		
		$smarty_s->assign('pass_prompt',$rf->GetValue('auth_block.php','pass_prompt',$lang));
		$smarty_s->assign('remme_prompt',$rf->GetValue('auth_block.php','remme_prompt',$lang));
		$smarty_s->assign('log_caption',$rf->GetValue('auth_block.php','log_caption',$lang));
		$smarty_s->assign('reg_prompt',$rf->GetValue('auth_block.php','reg_prompt',$lang));
		$smarty_s->assign('forget_prompt',$rf->GetValue('auth_block.php','forget_prompt',$lang));
		
	} else{
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
				$smarty_s->assign('authorized', true);
		$smarty_s->assign('well_prompt',$rf->GetValue('auth_block.php','well_prompt',$lang));
		$smarty_s->assign('username',stripslashes($profile['username']));
		$smarty_s->assign('your_profile',$rf->GetValue('auth_block.php','your_profile',$lang));
		$smarty_s->assign('your_orders',$rf->GetValue('auth_block.php','your_orders',$lang));
		$smarty_s->assign('go_away',$rf->GetValue('auth_block.php','go_away',$lang));		
		
	}
	
	unset($au);
}else $smarty_s->assign('has_auth',false);



//проверка статуса заказа
if(HAS_BASKET&&HAS_PRICE){
	$smarty_s->assign('has_chk',true);
	$rf=new ResFile(ABSPATH.'cnf/resources.txt');
	$smarty_s->assign('ch_value','');
	$smarty_s->assign('ch_title',$rf->GetValue('ch_ord_block.php','ch_title',$lang));
	$smarty_s->assign('ch_caption',$rf->GetValue('ch_ord_block.php','ch_caption',$lang));
	
}else $smarty_s->assign('has_chk',false);



//новостной блок
if(isset($has_news)){
	//покажем календарь
	require_once('classes/calendar_news.php');
	require_once('classes/newsgroup.php');
	$c= new CalendarNews($lang);
	//сгенерим путь к странице
	if(HAS_URLS)
		$news_url=''.$mi->ConstructPath($mm['id'],$lang,1,'/');
	else
		$news_url='razds.php?id='.$mm['id'];
		
	$news_t_res='<div class="dis_cons_block_2">'.$c->Draw($pdate,$news_url,'pdate','&datesortmode=1&nfrom=0',$pdate,$mm['mid']).'</div>';	
	
	
	
	
}else{
	 $news_t_res='';
	 
}
$smarty_s->assign('newsblock',$news_t_res);

$recent_news='';
if(HAS_NEWS){
	require_once('classes/newsgroup.php');
	//недавние новости
	$_newsgroup=new Newsgroup();
	
	$recent_news=$_newsgroup->GetItemsRecent('news/small_items.html',  $lang);
}
$smarty_s->assign('recent_news',$recent_news);


if(($_SERVER['REQUEST_URI']=='/')||($_SERVER['REQUEST_URI']=='/index.php')){
	$tmp=$fi->GetItem('parts/razd5-'.$_SESSION['lang'].'.txt');
	$smarty_s->assign('righttext',stripslashes($tmp));	
}


//Отзывы
require_once('classes/otzgroup.php');
if(HAS_FEEDBACK_FORMS){
	$_otg=new OtzGroup;
	
	$otgg=$_otg->GetItemsArrTwo();
	
	$smarty_s->assign('otz',$otgg);	
}
*/


$right_res=$smarty_s->fetch('common_right.html');
unset($smarty_s);
?>