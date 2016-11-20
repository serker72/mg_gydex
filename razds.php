<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/otzgroup.php');
require_once('classes/mmenuitem.php');

 require_once('classes/allmenu_template_item.php'); 

require_once('classes/numgroup.php');  
require_once('classes/recaptcha-php/recaptchalib.php');  

require_once('classes/solitem.php');
require_once('classes/solfilegroup.php');

require_once('classes/faq_q_group.php');
require_once('classes/faq_g_group.php');
require_once('classes/faq_client_group.php');

//die();
$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

//echo getenv('QUERY_STRING'); die();


$mi=new MmenuItem();
if(HAS_URLS){
	//проверим по имени раздела
	if(!isset($_GET['name']))
		if(!isset($_POST['name'])) {
				header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
		}
		else $name = $_POST['name'];		
	else $name = $_GET['name'];		
	$name=SecStr($name,10);
	
	 
	
	if((strlen($name)>0)&&($name!='/')){
		//проверка, есть ли такой подраздел
		//echo $name; die();
		
		$names=explode('/', $name);
	 
		$name=$names[count($names)-2];
		
		$mm=$mi->GetItemByURL($name,$lang,1);
		
		
		if($mm['is_to_another_url']==1){
			header("HTTP/1.1 301 Moved Permanently",true,301);
			header("Location: /".$mm['another_path']);
		}
		
	}else{
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			include("404.php");
		$mm=false;
	}
}else{
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
				header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	$mm=$mi->GetItemById($id,$lang,1);
	
	$m_ex=$mi->CheckFullExistance($id,$lang,1);
	if($m_ex==false){
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
}
if($mm==false){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}else{
	//echo 'найден! '.$mm['name'];
}
$current_mid=$mm['id'];

//раскрываем первый подраздел
if($mm['show_first']==1){
	$first=$mi->ShowFirst($mm['id'],$lang,1);
	$path=$mi->ConstructPath($first['id'],$lang,1);
	header('HTTP/1.1 301 Moved Permanently');
	header("Location: ".$path);
	die();
	
}


//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
if(strlen(stripslashes($mm['title']))==0) 
	$smarty->assign('SITETITLE',strip_tags(stripslashes($mm['name'])).' &gt; '.SITETITLE);
else $smarty->assign('SITETITLE',stripslashes($mm['title']));

//ключевые слова
if(strlen(stripslashes($mm['meta_keywords']))==0) {
 // $tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
 // $smarty->assign('keywords',stripslashes($tmp));
}else $smarty->assign('keywords',stripslashes($mm['meta_keywords']));


//описание сайта
if(strlen(stripslashes($mm['meta_description']))==0) {
 // $tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
 // $smarty->assign('description',stripslashes($tmp));
}else $smarty->assign('description',stripslashes($mm['meta_description']));

$smarty->assign('do_index', $mm['do_index']);
$smarty->assign('do_follow', $mm['do_follow']);

$canonical=SITEURL.$mi->ConstructPath($mm['id'],  $lang);
$smarty->assign('canonical', $canonical);

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





$smarty->display('common_top.html');
unset($smarty);


$content='';
$smarty_content=new SmartyAdm();

//навигация
$smarty_content->assign('navi',$mi->DrawNavigCli($mm['id'], $lang, 1, '','','','',' / ', false));


/*if(!eregi("<h1", $mm['txt'])){ //strlen(stripslashes($mm['title']))==0){
	 echo "<h1 class=\"head1\">".stripslashes($mm['name'])."</h1>";
}*/
//}else  echo "<h1 class=\"head1\">".stripslashes($mm['title'])."</h1>";

//вывод подразделов
//(миниатюры, или просто списком)
//echo $ml->GetSubsByIdCli($mm['id'], 'submenu.html', 'tpl/submenu_rows.html', 'tpl/submenu_item.html', $lang, 'img/bullet-sub.gif');


//echo stripslashes($mm['txt']);
 
//выводить итемы
//echo getenv('QUERY_STRING');
//каталог товаров ли это
if((HAS_PRICE)&&($mm['is_price']==1)){
    //вывод товаров
    //положение в каталоге товара
    if(!isset($_GET['from'])) $from=0;
    else $from = $_GET['from'];	
    $from=abs((int)$from);	
    $from=floor($from/GOODS_PER_PAGE)*GOODS_PER_PAGE;

    if(!isset($_GET['show_firms_only'])) $show_firms_only = 0;
    else $show_firms_only = $_GET['show_firms_only'];

    $pr_g=new PriceGroup();
    $pr_g->SetPagename('razds.php');

//	$flt_params=NULL;
    $flt_params=Array();
    if(isset($_GET['firmid'])){
            $flt_params['p.firmid']=abs((int)$_GET['firmid']);
    }
    if(isset($_GET['fromprice'])){
            $flt_params['fromprice']=abs((float)$_GET['fromprice']);
    }

    if(isset($_GET['toprice'])){
            $flt_params['toprice']=abs((float)$_GET['toprice']);
    }
    //включим в список параметров и параметры отбора по значениям свойств
    foreach($_GET as $k=>$v){
            if(eregi('name_id_',$k)){
                    $flt_params[$k]=SecStr($v);
            }
    }

    if(isset($_GET['sortmode'])){
            $sortmode=abs((int)$_GET['sortmode']);
    }else $sortmode=0;


    $m_templates=Array();
    //$m_templates['byname_set']='price/props.html';
    //$m_templates['byname_val']='tpl/price/byname_val.html';
    $m_templates['byname_set']='';
    $m_templates['byname_val']='';
    $pr_g->SetMinorTemplates($m_templates);

    // KSK 20.11.2016 - вывод списка фирм, товары которых входят в выбранную категорию каталога
    if ($show_firms_only == 1) {
        $f_g = new FirmsGroup();
	$f_g->SetPagename('razds.php');
        
        $content_category = $f_g->GetItemsByIdCli('firms/catalog_items.html', '', '', '', $mm['id'], $lang, $from, GOODS_PER_PAGE);
    } else {
        //echo $pr_g->GetItemsByIdCli('tpl/price/alltable.html', 'tpl/price/row.html', 'tpl/price/cell.html', $mm['id'], $lang,$from,GOODS_PER_PAGE,$flt_params,$sortmode);
        $content_category = $pr_g->GetItemsByIdCli('price/items.html', '', '', $mm['id'], $lang, $from, GOODS_PER_PAGE, $flt_params, $sortmode);
    }
    
    $content_category = str_replace('CATEGORY_NAME', $mm['name'], $content_category);
    $content .= $content_category;
}



//проверка, фотогалерея ли это
if((HAS_GALLERY)&&($mm['is_gallery']==1)){
	//вывод фото
	
	
	
	if(!isset($_GET['ffrom'])) $ffrom=0;
	else $ffrom = $_GET['ffrom'];	
	$ffrom=abs((int)$ffrom);	
	$ffrom=floor($ffrom/PHOTOS_PER_PAGE)*PHOTOS_PER_PAGE;
	
	$ph_g=new PhotosGroup();
	$ph_g->SetPagename('razds.php');
	/*echo $ph_g->GetItemsByIdCli('photogal/items.html', '','', $mm['id'], $lang,$ffrom);
	*/
	
	$content.= $ph_g->GetItemsByIdJQ($mm['id'], $lang, 'photogal/jqitems.html', 2);
}



//проверка, каталог ли статей это
if((HAS_PAPERS)&&($mm['is_papers']==1)){
	//вывод статей
	if(!isset($_GET['pfrom'])) $pfrom=0;
	else $pfrom = $_GET['pfrom'];	
	$pfrom=abs((int)$pfrom);	
	$pfrom=floor($pfrom/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
	
	$ph_g=new PapersGroup();
	$ph_g->SetPagename('razds.php');
	$content.= $ph_g->GetItemsByIdCli('papers/items.html', '', '', $mm['id'], $lang,$pfrom);
}



//проверка, каталог ли ссылок это
if((HAS_LINKS)&&($mm['is_links']==1)){
	//вывод ссылок
	if(!isset($_GET['lfrom'])) $lfrom=0;
	else $lfrom = $_GET['lfrom'];	
	$lfrom=abs((int)$lfrom);	
	$lfrom=floor($lfrom/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
	
	$ph_g=new LinksGroup();
	$ph_g->SetPagename('razds.php');
        
        // KSK 24.10.2016 - вывод подкатегорий для каталога
        if ($mm['parent_id'] == 3) {
            $content_category = $ph_g->GetItemsByIdCli('links/catalog_items.html', '', '', '', $mm['id'], $lang, $lfrom);
            $content_category = str_replace('CATEGORY_NAME', $mm['name'], $content_category);
            $content .= $content_category;
        } else {
            //$content.= $ph_g->GetItemsCli('links/clients.html');
            $content.= $ph_g->GetItemsByIdCli('links/items.html', 'tpl/links/row.html', 'tpl/links/cell.html', 'tpl/links/cell_code.html', $mm['id'], $lang, $lfrom);
        }
}
 
//проверка, новости ли это
if((HAS_NEWS)&&($mm['is_news']==1)){

//работа с параметрами сортировки новостей
//передадим параметр для работы с новостями
	$has_news=true;
	if(!isset($_GET['datesortmode']))
		if(!isset($_POST['datesortmode'])) {
			$datesortmode=0;
		}
		else $datesortmode = $_POST['datesortmode'];		
	else $datesortmode = $_GET['datesortmode'];		
	$datesortmode=abs((int)$datesortmode);
	if($datesortmode>1) $datesortmode=0;
	
	if(!isset($_GET['pdate']))
		if(!isset($_POST['pdate'])) {
			$pdate=date('Y-m-d');
		}
		else $pdate = $_POST['pdate'];		
	else $pdate = $_GET['pdate'];	
	$pdate=SecStr($pdate,10);

	//вывод 
	if(!isset($_GET['nfrom'])) $nfrom=0;
	else $nfrom = $_GET['nfrom'];	
	$nfrom=abs((int)$nfrom);	
	$nfrom=floor($nfrom/8)*8;
	
	$flt_params=NULL;
	if(isset($pdate)&&($datesortmode==1)){
		$flt_params=Array();
		$flt_params['t.pdate']=$pdate;
	}
	
	
	$ph_g=new NewsGroup();
	$ph_g->SetPagename('razds.php');
	$content.= $ph_g->GetItemsByIdCli('news/items_news.html', $mm['id'], $lang, $nfrom, 8, $flt_params, $datesortmode);
}
 
//формы обр связи
if((HAS_FEEDBACK_FORMS)&&($mm['is_feedback_forms']==1)){

	/*$sm2=new SmartyAdm;
	
	 
	$content.=$sm2->fetch('feedback.html');*/
	if(isset($_GET['do_feedback']))  $smarty_content->assign('do_feedback', true);
	
	$smarty_content->assign('has_feedback_form', true);
}

if((HAS_FEEDBACK_FORMS)&&($mm['is_callback']==1)){

	/*$sm2=new SmartyAdm;
	
	$content.=$sm2->fetch('callback.html');*/
	if(isset($_GET['do_callback']))  $smarty_content->assign('do_callback', true);
	
	$smarty_content->assign('has_callback_form', true);
}
 
//работа с отзывами
$og=new OtzGroup;
if(HAS_FEEDBACK_FORMS&&($mm['is_otzyv']==1)){

	 
	//вывод 
	/*if(!isset($_GET['otfrom'])) $otfrom=0;
	else $otfrom = $_GET['otfrom'];	
	$otfrom=abs((int)$otfrom);	
	$otfrom=floor($otfrom/ITEMS_PER_PAGE)*ITEMS_PER_PAGE;
	$_og->fromname='otfrom';
	
	echo $_og-> GetItemsCli($mm['id'], $lang, 1, 'otz/items.html', $otfrom, 1);*/
	
	
	//echo 'AAAAAAAAAAAAAAAA';
	
	 
	
	
	$content.= $og->GetItemsCli(  'otz/items.html' );
	 
	 
}
//единичный отзыв
$otz=$og->GetItemsArrNum(1);
$sm1=new SmartyAdm;

$sm1->assign('item', $otz);
$smarty_content->assign('otz', $sm1->fetch('otz/item.html'));

//rint_r($mm);
 
//выведем фото с общей инфой
if((strlen($mm['photo_for_goods'])!=0)&&($mm['photo_for_goods']!='img/no.gif')){
	$content.= "<br clear=\"all\"><img src=\"/$mm[photo_for_goods]\" alt=\"\" border=\"0\"><br>";
}

/*найдем и подставим связанный продукт*/
$_sol=new SolItem;
$solution=$_sol->GetItemByFields(array('is_shown'=>1, 'parent_id'=>$mm['id']));
$smarty_content->assign('sol', $solution);
$smarty_content->assign('has_solution', $solution!==false);

if($solution!==false){
	$_fg=new SolFileGroup;
//блок файлов
	$smarty_content->assign('pictures', $_fg->GetImagesArr($solution['id'],'sol_file.html', '/sol_file_view.html'));	
}


/*FAQ*/
if(HAS_FEEDBACK_FORMS&&($mm['is_faq']==1)){
	
	$faq='';
	
	if(!isset($_GET['solution_id'])) $solution_id=-1;
	else $solution_id=(int)$_GET['solution_id'];
	
	if(!isset($_GET['group_id'])) $group_id=-1;
	else $group_id=(int)$_GET['group_id'];
	
	if(!isset($_GET['question_id'])) $question_id=0;
	else $question_id=(int)$_GET['question_id'];
	
	
	$_faq=new FaqClientGroup;
	
	$faq=$_faq->GetItems(1, 'faq/template1.html', 'faq/template2.html', -1, -1, $mi->ConstructPath($mm['id'],$lang,0), $question_id);
	
	/*if($solution_id==-1) $smarty_content->assign('faq1', $faq);	
	else  $smarty_content->assign('faq2', $faq);	
	*/
	$smarty_content->assign('faq1', $faq);	
	
	if($solution_id!=-1){
		$faq1=$_faq->GetItems(1, 'faq/template1.html', 'faq/template3.html', $group_id, $solution_id, $mi->ConstructPath($mm['id'],$lang,0), $question_id);
		
		 $smarty_content->assign('faq2', $faq1);
		 
	}
}





//последние новости
$ph_g=new NewsGroup();

$smarty_content->assign('recent_news', $ph_g->GetItemsRecent('news/recent_news.html', $lang, 6));

// KSK 22.10.2016
if (in_array($mm['path'], array('o_kompanii', 'uslugi'))) {
    $og = new LinksGroup;
    $content .= $og->GetItemsByIdCli('index_clients.html', '', '', '', 15);
}

// KSK 24.10.2016
if ($mm['path'] == 'katalog') {
    $ml = new MmenuList();
    $params = array();
    $params['parent_id'] = $mm['id'];
    $subrazds = $ml->GetArr($params, LANG_CODE);
    
    $sm1=new SmartyAdm;

    $sm1->assign('items', $subrazds);
    $content .= $sm1->fetch('index_katalog.html');
}

$smarty_content->assign('content', $content);


//гидекс в цифрах
$_nums=new NumGroup;
$smarty1=new SmartyAdm;
$nums=$_nums->GetItemsRandCli();
$smarty1->assign('nums', $nums);
$smarty_content->assign('numbers', $smarty1->fetch('index_numbers.html'));

foreach($mm as $k=>$v) $mm[$k]=stripslashes($v);

//if((strlen($mm['title'])>0)&&($mm['template_id']!=2)&&($mm['template_id']!=5)) $mm['name']=$mm['title'];

$smarty_content->assign('mm', $mm);

$smarty_content->assign('FEEDBACK_PHONE', implode('<br>', explode(',', FEEDBACK_PHONE_FOOTER)));
$smarty_content->assign('FEEDBACK_EMAIL', FEEDBACK_EMAIL);
$smarty_content->assign('OFFICE_ADDRESS', str_replace('ул.', '<br>ул.', OFFICE_ADDRESS));
$map_address_array = explode(',', OFFICE_ADDRESS);
$map_address = '';
for($i=1;$i<count($map_address_array);$i++) {
    $map_address .= $map_address_array[$i] . ' ';
}
//$smarty_content->assign('OFFICE_ADDRESS_MAP', iconv('windows-1251', 'utf-8', $map_address));
$smarty_content->assign('OFFICE_ADDRESS_MAP', $map_address);


$_prt=new AllmenuTemplateItem;
$prt=$_prt->GetItemById($mm['template_id']);

//$smarty_content->assign('content', $content);
$smarty_content->display('razd/'.$prt['template']); 





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