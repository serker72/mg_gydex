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
require_once('classes/basket.php');
require_once('classes/propvalitem.php');
require_once('classes/propnameitem.php');

require_once('classes/authuser.php');
if((!HAS_PRICE)&&(!HAS_BASKET)){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');
/*foreach($_GET as $k=>$v){
		echo "$k=>$v<br>";
	}
	die();*/

$basket=new Basket();



if(isset($_GET['doOrder'])){
	//товар кладем в корзину
	
	
	// определим товар
	if(!isset($_GET['good_id']))
		if(!isset($_POST['good_id'])) {
			header('Location: /404.php');
			die();
		}
		else $good_id = $_POST['good_id'];		
	else $good_id = $_GET['good_id'];		
	$good_id=abs((int)$good_id);	
	
	$gd=new PriceItem();	
	$good=$gd->GetItemById($good_id,$lang,1);
	
	if($good==false){
		
		header("Location: /404.php");
		die();
	}
	
	//определим количество товара
	if(!isset($_GET['qty']))
		if(!isset($_POST['qty'])) {
			$qty=1;
		}
		else $qty = $_POST['qty'];		
	else $qty = $_GET['qty'];		
	$qty=abs((int)$qty);	
	if($qty==0) $qty=1;
	
	
	$disp=new DictNVDisp();
	
	$values_arr=Array();
	//обработка опций заказа
	foreach($_GET as $k=>$v){
			
			if(eregi("name_id_",$k)){
				if($v!=0){
					//опция выбрана
					//echo "$k=>$v<br>";
					$prop_id=abs((int)eregi_replace("name_id_","",$k));
					$val_id=abs((int)$v);
					//echo "$prop_id=>$val_id<br>";
					//проверим значение свойства
					$err_code=$disp->CheckPropertyByValId($returned_good_id,$returned_name_id,$val_id,$lang);
					if(($err_code==0)&&($returned_good_id==$good_id)&&($returned_name_id==$prop_id)){
						//echo 'order!!';
						//все в порядке, можно заносить это значение опции в корзину!
						$values_arr[]=Array(
							'value_id' => $val_id
						);
					}
					
				}
			}
		}
	
	
	//обработка товара
	$params=Array(
		'good_id' => $good_id,
		'lang_id'=> $lang,
		'quantity' => $qty,
		'values_arr' => $values_arr,
		'comment' => '');
	
	
	$basket->Add($params);
}
if(isset($_GET['clearBasket'])){
	//очистка корзины
	$basket->Clear();
}


if(isset($_GET['deleteMarked'])){
	//удаляем из корзины отмеченные позиции
	foreach($_GET as $k=>$v){
		//echo "$k=>$v<br>";
		if(eregi("_do_process",$k)){
			$basket->Del($v);
		}
	}
	//die();
}

//applyChanges
if(isset($_GET['applyChanges'])){
	//редактируем отмеченные позиции
	foreach($_GET as $k=>$v){
		//echo "$k=>$v<br>";
		if(eregi("_do_process",$k)){
			//echo "$k=>$v<br>";
			$number=eregi_replace("_do_process",'',$k);
			$params=Array();
			$quant=abs((int)$_GET[$number.'_quant']);
			if($quant==0) $quant=1;
			$params['quantity']=$quant;
			$params['comment']=SecStr($_GET[$number.'_comment'],10);
			
			//формируем новые свойства
			$values_arr=Array();
			
			$prefix=$v; //$basket->ConstructPrefix($basket->GetItemByNo($v-1));
			foreach($_GET as $kk=>$vv){
				if(eregi($prefix,$kk)){
					//echo "<strong>records to process:</strong> $kk=>$vv<br>";
					if($vv!='0'){
						$values_arr[]=Array(
							'value_id' => $vv
						);
					}
				}
			}
			$params['values_arr']=$values_arr;
			
			//внести изменения в текущую запись
			$basket->Edit($v,$params);
		}
	}
	//die();
}


//отправка заказа
if(isset($_POST['sendOrder'])){
	//Проверим авторизацию
	$au=new AuthUser();
	$profile=$au->Auth();
	if($profile!==NULL){
		//сканируем введенные адрес, имейл, телефон
		$params=Array();
		$params['email']=SecStr($_POST['email'],10);
		$params['phone']=SecStr($_POST['phone'],10);
		$params['address']=SecStr($_POST['address'],10);				
		
		$err_code=0;
		//по имейлу: длина, синтаксис
		if(strlen($params['email'])>=6){
			if(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,5})$', $params['email'])){
				//$err_code=0;
				//$tryagain=0;
			}else{
				$err_code=11;
			}
		}else{
			$err_code=11;
		}
		
		if(strlen($params['address'])>=10){
			
		}else{
			$err_code=20;
		}
		
		if($err_code==0){
			//все нормально, вызываем отправщик заказа
			$order_id=$basket->Send($params);
			//редирект на страницу с выводом номера заказа
			header('Location: /orderaccepted.php?order_id='.$order_id);
			die();
		}
	}
	//редирект на главную, типа ошибки
	header('Location: /index.php');
	die();
}


$backurl=getenv('HTTP_REFERER');


//вывод из шаблона

require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.'	<meta http-equiv="Robots" content="none">'.$l['lang_meta']);
$smarty->assign('keywords','');
$smarty->assign('description','');

$smarty->display('mini_top.html');
unset($smarty);



$rf=new ResFile(ABSPATH.'cnf/resources.txt');
?>

<div align="center">
<?=$rf->GetValue('addtobasket.php','wait_text',$lang)?><br>
			<script language="JavaScript" type="text/javascript">
			<!--
			function goAway(){
				location.href="<?=$backurl?>";
			}
			window.setTimeout("goAway()",1000);
			//-->
			</script>

<input type="button" value="<?=$rf->GetValue('addtobasket.php','back_caption',$lang)?>" onclick="location.href='<?=$backurl?>';"></div>




<?
unset($rf);
//нижний код

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->display('mini_bottom.html');
unset($smarty);

?>