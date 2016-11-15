<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

//административная авторизация
require_once('inc/adm_header.php');

if(isset($_POST['doInp'])||isset($_POST['doInp1'])){
	
	
	$settings=Array();
	$cnf=new ConfigFile();
	
	if(isset($_POST['SITETITLE'])) $settings['SITETITLE']=SecStr($_POST['SITETITLE'],10);
	if(isset($_POST['NO_RIGHTS'])) $settings['NO_RIGHTS']=SecStr($_POST['NO_RIGHTS'],10);
	
	if(isset($_POST['OFFICE_ADDRESS'])) $settings['OFFICE_ADDRESS']=SecStr($_POST['OFFICE_ADDRESS'],10);
        
	if(isset($_POST['FEEDBACK_EMAIL'])) $settings['FEEDBACK_EMAIL']=SecStr($_POST['FEEDBACK_EMAIL'],10);
	
	if(isset($_POST['FEEDBACK_PHONE_HEADER'])) $settings['FEEDBACK_PHONE_HEADER']=SecStr($_POST['FEEDBACK_PHONE_HEADER'],10);
	if(isset($_POST['FEEDBACK_PHONE_FOOTER'])) $settings['FEEDBACK_PHONE_FOOTER']=SecStr($_POST['FEEDBACK_PHONE_FOOTER'],10);
	
	
	
	if(isset($_POST['ITEMS_PER_PAGE'])) $settings['ITEMS_PER_PAGE']=abs((int)$_POST['ITEMS_PER_PAGE']);
	
	if(isset($_POST['CRITICAL_OST'])) $settings['CRITICAL_OST']=abs((int)$_POST['CRITICAL_OST']);
	
	if(isset($_POST['COMMENTS_PER_PAGE'])) $settings['COMMENTS_PER_PAGE']=abs((int)$_POST['COMMENTS_PER_PAGE']);
	
	if(isset($_POST['PHOTOS_PER_PAGE'])) $settings['PHOTOS_PER_PAGE']=abs((int)$_POST['PHOTOS_PER_PAGE']);
	
	if(isset($_POST['GOODS_PER_PAGE'])) $settings['GOODS_PER_PAGE']=abs((int)$_POST['GOODS_PER_PAGE']);
	
	//if(isset($_POST['PROPS_PER_PAGE'])) $settings['PROPS_PER_PAGE']=abs((int)$_POST['PROPS_PER_PAGE']);
	if(isset($_POST['PROPS_PER_PAGE'])) $settings['PROPS_PER_PAGE']=SecStr($_POST['PROPS_PER_PAGE'],10);
	
	if(isset($_POST['NUM_LEVELS'])) $settings['NUM_LEVELS']=SecStr($_POST['NUM_LEVELS'],10);
	
	if(isset($_POST['HAS_PRICE'])) $settings['HAS_PRICE']='1';
	else $settings['HAS_PRICE']='0';
	
	if(isset($_POST['HAS_NEWS'])) $settings['HAS_NEWS']='1';
	else $settings['HAS_NEWS']='0';
	
	if(isset($_POST['HAS_LINKS'])) $settings['HAS_LINKS']='1';
	else $settings['HAS_LINKS']='0';
	
	if(isset($_POST['HAS_BASKET'])) $settings['HAS_BASKET']='1';
	else $settings['HAS_BASKET']='0';
	
	if(isset($_POST['HAS_OST'])) $settings['HAS_OST']='1';
	else $settings['HAS_OST']='0';
	
	if(isset($_POST['HAS_PAPERS'])) $settings['HAS_PAPERS']='1';
	else $settings['HAS_PAPERS']='0';
	
	if(isset($_POST['HAS_GALLERY'])) $settings['HAS_GALLERY']='1';
	else $settings['HAS_GALLERY']='0';
	
	if(isset($_POST['HAS_FEEDBACK_FORMS'])) $settings['HAS_FEEDBACK_FORMS']='1';
	else $settings['HAS_FEEDBACK_FORMS']='0';
	
	
	if(isset($_POST['HAS_URLS'])) $settings['HAS_URLS']='1';
	else $settings['HAS_URLS']='0';
	
	if(isset($_POST['DEBUG_INFO'])) $settings['DEBUG_INFO']='1';
	else $settings['DEBUG_INFO']='0';
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 10)){ 

	  $cnf->SaveToFile($settings);
	  
	  if(isset($_POST['doInp'])){
		  header('Location: index.php');
		  die();
	  } else if(isset($_POST['doInp1'])){
		  header('Location: ed_global.php');
		  die();
	  }
	}else{
		header('Location: no_rights.php');
		  die();	
	}
}

//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Глобальные настройки сайта - '.SITETITLE.' - Конфигуратор системы');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');

?>


<h1>Глобальные настройки сайта</h1>

<?
$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 10)){ 
?>


<form action="ed_global.php" method="post">

<table width="*" cellspacing="2" cellpadding="2" border="0">
<tr>
	 <td width="50%" valign="top">
	 	<h2>Модули системы</h2>
		
		<input class="unbord" type="checkbox" name="HAS_PRICE" value="" <?if(HAS_PRICE) echo 'checked';?>><strong><img src="../img/catalog-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Каталог товаров</strong><p>
		
		<input class="unbord" type="checkbox" name="HAS_BASKET" value="" <?if(HAS_BASKET) echo 'checked';?>><strong><img src="../img/basket.gif" alt="" width="16" height="14" hspace="2" vspace="0" border="0">Интернет-магазин</strong><p>
		
		<input class="unbord" type="checkbox" name="HAS_OST" value="" <?if(HAS_OST) echo 'checked';?>><strong><img src="../img/basket.gif" alt="" width="16" height="14" hspace="2" vspace="0" border="0">Учет товарных остатков</strong><p>
		
		
		<input class="unbord" type="checkbox" name="HAS_NEWS" value="" <?if(HAS_NEWS) echo 'checked';?>><strong><img src="../img/news-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Новости</strong><p>
		
		<input class="unbord" type="checkbox" name="HAS_LINKS" value="" <?if(HAS_LINKS) echo 'checked';?>><strong><img src="../img/links-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Каталог ссылок</strong><p>
		
		
		
		<input class="unbord" type="checkbox" name="HAS_PAPERS" value="" <?if(HAS_PAPERS) echo 'checked';?>><strong><img src="../img/papers-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Каталог статей</strong><p>
		
		<input class="unbord" type="checkbox" name="HAS_GALLERY" value="" <?if(HAS_GALLERY) echo 'checked';?>><strong><img src="../img/photos-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Фотогалерея</strong><p>
		
		<input class="unbord" type="checkbox" name="HAS_FEEDBACK_FORMS" value="" <?if(HAS_FEEDBACK_FORMS) echo 'checked';?>><strong><img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Формы обратной связи</strong><p>
	 
	 </td>

    <td width="50%" valign="top">
		<h2>Установки системы</h2>
		
		<strong>Заголовок сайта:</strong><br>
<input type="text" name="SITETITLE" value="<?=SITETITLE?>" size="60" maxlength="128"><p>

		<strong>Сообщение о недостаточных правах:</strong><br>
<input type="text" name="NO_RIGHTS" value="<?=NO_RIGHTS?>" size="60" maxlength="255"><p>
        
		<strong>Адрес офиса:</strong><br>
		<input type="text" name="OFFICE_ADDRESS" value="<?=OFFICE_ADDRESS?>" size="60" maxlength="255"><p>
		
                <strong>Контактные телефоны для шапки сайта (разеляются запятой):</strong><br>
		<input type="text" name="FEEDBACK_PHONE_HEADER" value="<?=FEEDBACK_PHONE_HEADER?>" size="40" maxlength="255"><p>
		
                <strong>Контактные телефоны для подвала сайта (разеляются запятой):</strong><br>
		<input type="text" name="FEEDBACK_PHONE_FOOTER" value="<?=FEEDBACK_PHONE_FOOTER?>" size="40" maxlength="255"><p>
        
		<strong>Контактный e-mail:</strong><br>
		<input type="text" name="FEEDBACK_EMAIL" value="<?=FEEDBACK_EMAIL?>" size="40" maxlength="40"><p>
		
		<strong>Критическое значение остатка товара:</strong><br>
		<input type="text" name="CRITICAL_OST" value="<?=CRITICAL_OST?>" size="5" maxlength="3"><p>
		
		
		<strong>Элементов на страницу:</strong><br>
		<input type="text" name="ITEMS_PER_PAGE" value="<?=ITEMS_PER_PAGE?>" size="5" maxlength="3"><p>
		
		
		<strong>Записей гостевой книги на страницу:</strong><br>
		<input type="text" name="COMMENTS_PER_PAGE" value="<?=COMMENTS_PER_PAGE?>" size="5" maxlength="3"><p>
		
		
		<strong>Фотографий на страницу:</strong><br>
		<input type="text" name="PHOTOS_PER_PAGE" value="<?=PHOTOS_PER_PAGE?>" size="5" maxlength="3"><p>
		
		<strong>Товаров на страницу:</strong><br>
		<input type="text" name="GOODS_PER_PAGE" value="<?=GOODS_PER_PAGE?>" size="5" maxlength="3"><p>

		<strong>Свойств товаров на страницу (неограничено - "-"):</strong><br>
		<input type="text" name="PROPS_PER_PAGE" value="<?=PROPS_PER_PAGE?>" size="5" maxlength="3"><p>
		
		<strong>Глубина вложенности (неограниченная - "-"):</strong><br>
		<input type="text" name="NUM_LEVELS" value="<?=NUM_LEVELS?>" size="5" maxlength="3"><p>
		
		<input class="unbord" type="checkbox" name="HAS_URLS" value="" <?if(HAS_URLS) echo 'checked';?>><strong>Использовать URL разделов</strong><p>
		
		
		<input class="unbord" type="checkbox" name="DEBUG_INFO" value="" <?if(DEBUG_INFO) echo 'checked';?>><strong>Отладочная информация</strong><i> включать только при отладке программы! т.к. произойдет вывод на экран всех sql-запросов.</i><p>
	
	</td>
   
</tr>
</table>






<input type="submit" name="doInp1" value="Применить изменения">

<input type="submit" name="doInp" value="Внести изменения">
</form>
<?
}else echo NO_RIGHTS;
?>


	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>