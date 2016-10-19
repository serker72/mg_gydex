<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/mmenuitem.php');
require_once('classes/ordersgroup.php');
require_once('classes/authuser.php');

if(!(HAS_PRICE&&HAS_BASKET)){
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}

$lg=new LangGroup();
require_once('inc/lang_define.php');

/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}*/
	
	

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=5;
	}else $to_page = $_POST['to_page'];	
}else $to_page = $_GET['to_page'];	
$to_page=abs((int)$to_page);	


$rf=new ResFile(ABSPATH.'cnf/resources.txt');

//обработчик изменений
if(isset($_POST['doDelete'])){
	//proverim avtorizaciu
	$tau=new AuthUser();
	$profile=$tau->Auth();
	if($profile!==NULL){
		//удаляем указанные заказы
		foreach($_POST as $k=>$v){
			if(eregi("_do_process",$k)){
				//echo $k;
				$lid=eregi_replace("_do_process","",$k);
				$oi=new OrderItem();
				$oi->UserDel($lid,$profile['id']);
				
			}
		}
	}
	unset($tau);
	
	if(isset($_POST['order_no'])&&($_POST['order_no']!=0)){
		header('Location: vieworders.php?order_no='.abs((int)$_POST['order_no']));
	}else{
		header('Location: vieworders.php');
	}
	die();
}

if(isset($_POST['doAlter'])){
	//proverim avtorizaciu
	$tau=new AuthUser();
	$profile=$tau->Auth();
	if($profile!==NULL){
		//изменяем указанные заказы
		foreach($_POST as $k=>$v){
			if(eregi("_do_process",$k)){
				//echo $k;
				$lid=eregi_replace("_do_process","",$k);
				$params=Array();
				//конструируем параметры
				$params['address']=SecStr($_POST[$lid.'_address'],10);
				$params['email']=SecStr($_POST[$lid.'_email'],10);				
				$params['phone']=SecStr($_POST[$lid.'_phone'],10);	
				//вносим позиции заказа
				$params['positions']=Array();
				foreach($_POST as $kk=>$vv){
					//смотрим, чтобы позиция обрабатываемая принадлежала данному заказу:
					$word="^".$lid.'_'."[[:digit:]]+"."_position$";
					if(eregi($word,$kk)){
						//перебор по позициям
					//	echo " posiciya: $kk=>$vv<p>";
						$pos_id=abs((int)$vv);
						$values_arr=Array();
						foreach($_POST as $kkk=>$vvv){
							if(eregi($lid.'_'.$pos_id.'_name_id',$kkk)){
							//	echo "svoystvo: $kkk=>$vvv<p>";
								$values_arr[]=abs((int)$vvv);
							}
						}
						
						$params['positions'][$pos_id]=Array(
							'quantity'=>abs((int)$_POST[$pos_id.'_quantity']),
							'comment'=>SecStr($_POST[$pos_id.'_comments'],10),
							'values_arr'=>$values_arr);
					}
				}
				$oi=new OrderItem();
				$oi->UserEdit($lid,$profile['id'],$params);
			}
		}
	}
	unset($tau);
	if(isset($_POST['order_no'])&&($_POST['order_no']!=0)){
		header('Location: vieworders.php?order_no='.abs((int)$_POST['order_no']));
	}else{
		header('Location: vieworders.php?from='.$from.'&to_page='.$to_page);
	}
	
	die();
}




//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE.' - '.$rf->GetValue('vieworders.php','title',$lang));

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

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
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);

?>


<h2><?=$rf->GetValue('vieworders.php','title',$lang)?></h2>
<?
$au=new AuthUser();
$profile=$au->Auth();
if($profile===NULL){
	echo '<strong>'.$rf->GetValue('vieworders.php','not_reg',$lang).'</strong>';
}else{
	$og=new OrdersGroup();
	$og->SetTemplates('orders/all_orders.html', '', '', '');
	
	$inner=Array();
	$inner['all_menu_template']=''; $inner['menuitem_template']='';
	$inner['menuitem_template_blocked']=''; 		
	$inner['razbivka_template']='';
	$inner['items_set']='basket/options_set.html';
	$inner['item_row']='';
	
	if(isset($_GET['order_no'])){
		$order_no=abs((int)$_GET['order_no']);
		//if($order_no!=0){
			echo $og->CheckOrderCliById($profile['id'], $order_no, $profile['lang_id'],$inner);
		//}else echo $og->GetItemsCliById($profile['id'],$from,$to_page,$profile['lang_id'],$inner);
	}else echo $og->GetItemsCliById($profile['id'],$from,$to_page,$profile['lang_id'],$inner);
?>
<?
}
?>



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
