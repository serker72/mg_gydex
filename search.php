<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/newsgroup.php');
require_once('classes/resoursefile.php');
require_once('classes/searcher.php');

$mi=new MmenuItem();

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

$rf=new ResFile(ABSPATH.'cnf/resources.txt');
//вывод из шаблона

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE.' - '.$rf->GetValue('search.php','search_title',$lang));

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));


$smarty->assign('do_index', 0);
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
//$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);

$search_items_per_page = 2;
$content='';
$smarty_content=new SmartyAdm();

//навигация

//впишем ссылку на корневую страницу
$alls=Array();
$alls[] = Array('itemname' => 'Главная', 'filepath' => '/', 'has_symb' => true, 'symb' => ' / ');
$alls[] = Array('itemname' => 'Результаты поиска', 'filepath' => '/', 'has_symb' => true, 'symb' => ' / ');

$sm=new SmartyAdm;
$sm->assign('items', $alls);
$sm->assign('aftertext', '');
$sm->assign('has_last', false);

$smarty_content->assign('navi', $sm->fetch('navi.html'));

unset($sm);

//получим условие для поиска
$qry='';
if(isset($_GET['qrya'])){
	if(strlen(SecStr($_GET['qrya'],9))>=4){
		$qry=SecStr($_GET['qrya'],9);
	}
}

if(isset($_GET['qry'])){
	if(strlen(SecStr($_GET['qry'],9))>=4){
		$qry=SecStr($_GET['qry'],9);
	}
}

$smarty_content->assign('qry', $qry);

//ищем
$search_rows = Array();
$search_all_rows = Array();
$search_count = 0;
$search_all_count = 0;
if($qry!=''){
	$srch=new Searcher();
	
	//собираем прочие параметры строки
	$other_params='';
	foreach($_GET as $k=>$v){
		//echo " $k = $v <br>";
		if(eregi('in_',$k)) $other_params.='&'.$k.'='.$v;
	}
	
	
	//поиск в разделах, доступен всегда
	if(!isset($_GET['from'])) $from=0;
	else $from = $_GET['from'];	
	$from=abs((int)$from);	
	$from=floor($from/$search_items_per_page)*$search_items_per_page;
	
	$srch->SetParams('allmenu','menu_lang','mid', 'lang_id','is_shown','from','name,txt,title',$rf->GetValue('search.php','found_entries',$lang),$rf->GetValue('search.php','no_entries',$lang),$other_params);
	$extra=Array();
	$extra[]='l.name';
	$extra[]='l.txt';
	$tpl=Array();
	$tpl['section']='search/section.html';
	$tpl['item']='';	
	$srch->SetTemplates($tpl);
	$srch->SetMode(0);
	$content .= $srch->Search($qry,$lang,$from,$search_items_per_page,$extra, NULL, $search_rows, $search_count);
        if ($search_count > 0) {
            $search_all_rows = array_merge($search_all_rows, $search_rows);
            $search_all_count += $search_count;
        }
	
	//поиск в каталоге товаров и в фирмах(если указаны флаги)
	if(HAS_PRICE&&isset($_GET['in_price'])){
		if(!isset($_GET['price_from'])) $price_from=0;
		else $price_from = $_GET['price_from'];	
		$price_from=abs((int)$price_from);	
		$price_from=floor($price_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('price_item','price_lang','price_id', 'lang_id','is_shown','from','name,small_txt,big_txt,title',$rf->GetValue('search.php','found_price_entries',$lang),'',$other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.small_txt';
		$extra[]='t.photo_small';
		$extra[]='t.firmid';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(3);
		$content .= $srch->Search($qry,$lang,$price_from,$search_items_per_page,$extra, NULL, $search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	
	}
	
	//поиск в фирмаъ(если указаны флаги)
	if(HAS_PRICE&&isset($_GET['in_firms'])){
		if(!isset($_GET['firms_from'])) $firms_from=0;
		else $firms_from = $_GET['firms_from'];	
		$firms_from=abs((int)$firms_from);	
		$firms_from=floor($firms_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('firms','firms_lang','firmid', 'lang_id','is_shown','from','name,info',$rf->GetValue('search.php','found_firms_entries',$lang),'',$other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.info';
		$extra[]='t.photo_small';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(2);
		$content .= $srch->Search($qry,$lang,$firms_from,$search_items_per_page,$extra, NULL, $search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	}
	
	
	
	
	//поиск в статьях(если указаны флаги)
	if(HAS_PAPERS&&isset($_GET['in_papers'])){
		if(!isset($_GET['pap_from'])) $pap_from=0;
		else $pap_from = $_GET['pap_from'];	
		$pap_from=abs((int)$pap_from);	
		$pap_from=floor($pap_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('paper_item','paper_lang','paper_id', 'lang_id','is_shown','from','name,small_txt,big_txt,title',$rf->GetValue('search.php','found_paper_entries',$lang),'',$other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.small_txt';
		$extra[]='t.photo_small';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(1);
		$content .= $srch->Search($qry,$lang,$pap_from,$search_items_per_page,$extra, NULL, $search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	}
	
	
	//поиск в foto(если указаны флаги)
	if(HAS_GALLERY&&isset($_GET['in_photos'])){
		if(!isset($_GET['photos_from'])) $photos_from=0;
		else $photos_from = $_GET['photos_from'];	
		$photos_from=abs((int)$photos_from);	
		$photos_from=floor($photos_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('photo_item','photo_lang','photo_id', 'lang_id','is_shown','from','name,small_txt,big_txt,title',$rf->GetValue('search.php','found_photos_entries',$lang),'',$other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.small_txt';
		$extra[]='t.photo_small';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(4);
		$content .= $srch->Search($qry,$lang,$photos_from,$search_items_per_page,$extra, NULL,$search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	}
	
	
	//поиск в статьях(если указаны флаги)
	if(HAS_LINKS&&isset($_GET['in_links'])){
		if(!isset($_GET['links_from'])) $links_from = 0;
		else $links_from = $_GET['links_from'];	
		$links_from = abs((int)$links_from);	
		$links_from = floor($links_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('link_item', 'link_lang', 'link_id', 'lang_id', 'is_shown', 'from', 'name,short_name,small_txt', $rf->GetValue('search.php','found_links_entries',$lang), '', $other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.small_txt';
		$extra[]='l.photo_small';
		$extra[]='t.mid';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(5);
		$content .= $srch->Search($qry, $lang, $links_from, $search_items_per_page, $extra, NULL, $search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	}
	
	
	//поиск в статьях(если указаны флаги)
	if(HAS_NEWS&&isset($_GET['in_news'])){
		if(!isset($_GET['news_from'])) $news_from=0;
		else $news_from = $_GET['news_from'];	
		$news_from=abs((int)$news_from);	
		$news_from=floor($news_from/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
		
		$srch->SetParams('news_item','news_lang','news_id', 'lang_id','is_shown','from','name,small_txt,big_txt,title',$rf->GetValue('search.php','found_news_entries',$lang),'',$other_params);
		$extra=Array();
		$extra[]='l.name';
		$extra[]='l.small_txt';
		$extra[]='t.photo_small';
		$tpl=Array();
		$tpl['section']='search/section.html';
		$tpl['item']='';	
		$srch->SetTemplates($tpl);
		$srch->SetMode(6);
		$content .= $srch->Search($qry,$lang,$news_from,$search_items_per_page,$extra, NULL, $search_rows, $search_count);
                if ($search_count > 0) {
                    $search_all_rows = array_merge($search_all_rows, $search_rows);
                    $search_all_count += $search_count;
                }
	}
}


$content='';
if ($search_all_count > 0) {
    $smarty=new SmartyAdm;
    $smarty->debugging=DEBUG_INFO;

    //$smarty->assign('titletext',$this->found_title.' '.$total);

    //разбивка по страницам
    $navig = new PageNavigator('/search.php', $search_all_count, $search_items_per_page, $from, 10, '&qry='.$qry.$other_params);
    $navig->setFirstParamName('from');
    $navig->setDivWrapperName('alblinks');
    $navig->setPageDisplayDivName('alblinks1');			
    $pages= $navig->GetNavigator();
    $smarty->assign('pages',$pages);
    //$smarty->assign('mode',$this->s_mode);
    
    $smarty->assign('items', $search_all_rows);
    $content = $smarty->fetch('search/section.html');
    
    unset($smarty);
}


$smarty_content->assign('search_all_count', $search_all_count);
$smarty_content->assign('content', $content);

$smarty_content->display('search/page.html'); 
unset($smarty_content);

?>

<h1><?=$rf->GetValue('search.php','search_title',$lang)?></h1>

<form action="/search.php" name="mainsrch" id="mainsrch" style="text-indent:0;">
<input type="text" name="qry" id="qry" size="50" value="<?if(isset($_GET['qrya'])) echo htmlspecialchars($_GET['qrya']); if(isset($_GET['qry'])) echo htmlspecialchars($_GET['qry']);?>" maxlength="255"> 
<input type="submit" name="doSrch" value="<?=$rf->GetValue('search.php','search_button',$lang)?>"><p>

<strong><? if(HAS_PRICE||HAS_PAPERS||HAS_GALLERY||HAS_NEWS) echo $rf->GetValue('search.php','add_places',$lang);?></strong><p>

<?
if(HAS_PRICE){
?>
<input type="checkbox" name="in_price" id="in_price" value="1" <?if(isset($_GET['in_price'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_price',$lang)?><br>
<input type="checkbox" name="in_firms" id="in_firms" value="1" <?if(isset($_GET['in_firms'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_firms',$lang)?><br>
<?
}
?>

<?
if(HAS_PAPERS){
?>
<input type="checkbox" name="in_papers" id="in_papers" value="1" <?if(isset($_GET['in_papers'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_papers',$lang)?><br>
<?
}
?>

<?
if(HAS_GALLERY){
?>
<input type="checkbox" name="in_photos" id="in_photos" value="1" <?if(isset($_GET['in_photos'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_photos',$lang)?><br>
<?
}
?>

<?
if(HAS_LINKS){
?>
<input type="checkbox" name="in_links" id="in_links" value="1" <?if(isset($_GET['in_links'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_links',$lang)?><br>
<?
}
?>

<?
if(HAS_NEWS){
?>
<input type="checkbox" name="in_news" id="in_news" value="1" <?if(isset($_GET['in_news'])) echo 'checked';?>> <?=$rf->GetValue('search.php','in_news',$lang)?><br>
<?
}
?>


</form>
<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("mainsrch");  
  frmvalidator.addValidation("qry","req","<?=$rf->GetValue('search.php','minlen_error',$lang)?>");    
  frmvalidator.addValidation("qry","minlen=4","<?=$rf->GetValue('search.php','minlen_error',$lang)?>");    
</script>  


<?


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
