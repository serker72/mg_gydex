<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/priceitem.php');
require_once('classes/dictattdisp.php');



if(!HAS_PRICE){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');
/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}*/

// определим товар
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);	

$gd=new PriceItem();	
$good=$gd->GetItemById($id,$lang,1);

if($good==false){
	
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
	
	//die();


$mi=new MmenuItem();
/*if(HAS_URLS){
	//проверим по имени раздела
	if(!isset($_GET['name']))
		if(!isset($_POST['name'])) {
			header('Location: /404.php');
			die();
		}
		else $name = $_POST['name'];		
	else $name = $_GET['name'];		
	$name=SecStr($name,10);
	if((strlen($name)>0)&&($name!='/')){
		//проверка, есть ли такой подраздел
		$mm=$mi->GetItemByURL($name,$lang,1);
	}else{
		header("Location: /404.php");
		die();
		$mm=false;
	}
}else{*/
	//проверим good[mid
	$mm=$mi->GetItemById($good['mid'],$lang,1);
	
	$m_ex=$mi->CheckFullExistance($good['mid'],$lang,1);
	if($m_ex==false){
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
//}
if($mm==false){
	
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}else{
	//echo 'найден! '.$mm['name'];
}
if($mm['is_price']!=1){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
$current_mid=$good['mid'];


//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
if(strlen(stripslashes($mm['title']))==0) 
	$smarty->assign("SITETITLE",SITETITLE.' &gt; '.stripslashes($mm['name']).' &gt; '.stripslashes($good['name']));
else $smarty->assign("SITETITLE",SITETITLE.' &gt; '.stripslashes($mm['title']).' &gt; '.stripslashes($good['name']));

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));

$canonical=SITEURL.$gd->ConstructPath($id, $lang);
$smarty->assign('canonical', $canonical);

$smarty->assign('do_index', 1);
$smarty->assign('do_follow', 1);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

//работа с хедером
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//работа с гориз меню
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//левая колонка
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');



//навигация
$smarty->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, 'tpl/navi.html','tpl/naviitem.html','tpl/naviitem.html','','&nbsp;&gt; '));

$smarty->display('common_top.html');
unset($smarty);
?>




<?
//выводить данные по товару
//через шаблон
$sm=new SmartyAdm;
$sm->debugging=DEBUG_INFO;

$sm->assign('good_name', stripslashes($good['name']));
$sm->assign('good_descr', stripslashes($good['big_txt']));

$sm->assign('image_url', stripslashes($good['photo_big']));
$im=GetImageSize(ABSPATH.stripslashes($good['photo_big']));
if($im!=false){
	$sm->assign('image_w',$im[0]);
	$sm->assign('image_col_w',$im[0]+4);
	$sm->assign('image_h',$im[1]);
}else{
	$sm->assign('image_w',320);
	$sm->assign('image_col_w',320+4);
	$sm->assign('image_h',240);
}

//цены
$price_tpls=Array(); $price_tpls['table_template']='';
$price_tpls['table_row']='';
$price_tpls['table_cell']='';
$gd->price_disp->SetTemplates($price_tpls);
$gd->price_disp->SetShowErrors(false);
$price_arr=$gd->price_disp->GetPriceTableByGoodIdLangId($id,$lang);
$sm->assign('prices_error',$price_arr['error']);
$sm->assign('prows',$price_arr['results']);


//фирма
$fir=new FirmItem();
$firm=$fir->GetItemById($good['firmid'],$lang,1);
if($firm==false){
	$sm->assign('has_firm',false);
}else{
	$rf=new ResFile(ABSPATH.'cnf/resources.txt');
	$sm->assign('has_firm',true);
	$sm->assign('firm_caption',$rf->GetValue('good.php','firm-manufacturer',$lang));
	$sm->assign('firm_id',$good['firmid']);
	$sm->assign('firm_name',stripslashes($firm['name']));
}
unset($fir);

//форма сравнения
$sm->assign('itemno', $id);
$rf=new ResFile(ABSPATH.'cnf/resources.txt');
$sm->assign('compare_caption',$rf->GetValue('good.php','compare_caption',$lang));
$sm->assign('to_compare',$rf->GetValue('good.php','to_compare',$lang));
$sm->assign('to_clear',$rf->GetValue('good.php','to_clear',$lang));
$sm->assign('all_comp',$rf->GetValue('good.php','all_comp',$lang));

$dicts=new DictAttDisp(3);

//форма заказа
if((HAS_BASKET)&&($mm['is_basket'])){
	$sm->assign('has_order_form',true);
	$sm->assign('good_id', $id);
	$rf=new ResFile(ABSPATH.'cnf/resources.txt');
	
	$sm->assign('prompt_text',$rf->GetValue('good.php','prompt_text',$lang));
	$sm->assign('quant_name',$rf->GetValue('good.php','quant_name',$lang));
	$sm->assign('quant_units',$rf->GetValue('good.php','quant_units',$lang));
	$sm->assign('capt_make_order',$rf->GetValue('good.php','capt_make_order',$lang));
	$sm->assign('err_quant_name',$rf->GetValue('good.php','err_quant_name',$lang));
	$sm->assign('err_quant_name0',$rf->GetValue('good.php','err_quant_name0',$lang));
	
	$sm->assign('OPTIONS',$dicts->DrawDictsCli(2, $id, $lang,'good/options_set.html', 'tpl/good/options_item.html'));
}else{
	$sm->assign('has_order_form',false);
}

//реком. товары
$rec_gr=new RekomGroup();
$r_tpl=Array(); $r_tpl['table']=''; $r_tpl['row']=''; $r_tpl['item']=''; $rec_gr->SetTemplates($r_tpl);
$recrec=$rec_gr->GetItemsByIdCli($id,$lang);
if($recrec['count']>0){
	$sm->assign('has_rekoms',true);
	$sm->assign('rekrows',$recrec['set']);
	$sm->assign('rekomtitle',$recrec['caption']);
}else{
	$sm->assign('has_rekoms',false);
}


//доп. фото
$addph=$dicts->DrawDictsCli(3, $id, $lang,'', '', '');
$sm->assign('photorows',$addph);

//таблицы свойств
$sm->assign('PROPS',$dicts->DrawDictsCli(1, $id, $lang,'price/prop_tables.html', '', ''));

$sm->display('good/total.html');
unset($sm);
?>




<?
//выведем фото с общей инфой
if((strlen($mm['photo_for_goods'])!=0)&&($mm['photo_for_goods']!='img/no.gif')){
	echo "<br clear=\"all\"><img src=\"/$mm[photo_for_goods]\" alt=\"\" border=\"0\"><br>";
}

//нижний код

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//работа с футером
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//работа с правой колонкой
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');



//работа с гориз меню
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);
?>