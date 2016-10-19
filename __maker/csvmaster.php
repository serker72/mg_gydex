<?
//session_name("csvmaster");
session_start();
require_once('../classes/global.php');
require_once('../classes/langgroup.php');
require_once('csvsettings.php');

require_once('../classes/csvformer.php');
require_once('../classes/smarty/SmartyAdm.class.php');

if(!HAS_OST){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}



$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}


if(!isset($_GET['step'])){
	if(!isset($_POST['step'])){
		$step=1;
	}else $step = $_POST['step'];	
}else $step = $_GET['step'];	
$step=abs((float)$step);	
if(($step>5)||($step<1)) $step=1;

//инициализация переменных сессии
if(!isset($_SESSION['csvmaster']['filename'])) $_SESSION['csvmaster']['filename']=NULL;
if(!isset($_SESSION['csvmaster']['process_filename'])) $_SESSION['csvmaster']['process_filename']=NULL;
if(!isset($_SESSION['csvmaster']['separator'])) $_SESSION['csvmaster']['separator']=';';
if(!isset($_SESSION['csvmaster']['records_per_page'])) $_SESSION['csvmaster']['records_per_page']=ITEMS_PER_PAGE;
if(!isset($_SESSION['csvmaster']['do_delete_file'])) $_SESSION['csvmaster']['do_delete_file']=1;

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if($step==1){
	//обнуляем поля файлов
	$_SESSION['csvmaster']['filename']=NULL;
	$_SESSION['csvmaster']['process_filename']=NULL;
}


//обработка результатов первого шага
if($step==1.5){
	//заносим все в сессию и формируем обрабатываемый файл
	
	$_SESSION['csvmaster']['separator']=SecStr($_POST['separator']);
	$_SESSION['csvmaster']['records_per_page']=abs((int)$_POST['records_per_page']);
	if(isset($_POST['do_delete_file'])) $_SESSION['csvmaster']['do_delete_file']=1;
	else $_SESSION['csvmaster']['do_delete_file']=0;
	
	
	//генерим временный файл и заносим его в сессию
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	$CsvFormer->CreateTempFile();
	
	//разбираемся с видом загруженного файла
	//если был аплоад, то пересохраним загруженный файл
	//если не было аплоада, а был выбран готовый файл - то просто передадим его в сессию
	if($_POST['load_mode']==1){
		//был аплоад
		if(isset($_FILES['file_to_upload'])){
			$res=@copy($_FILES['file_to_upload']['tmp_name'],PHOTOS_BASEPATH.$_FILES['file_to_upload']['name']);
			if($res) $_SESSION['csvmaster']['filename']=$_FILES['file_to_upload']['name'];
		}	
	}else if($_POST['load_mode']==2){
		
		$filename=SecStr($_POST['photo_small']);
		if(eregi("^(.*)\\.(csv)$",$filename,$P)&&file_exists(realpath(ABSPATH.$filename))) {
			$_SESSION['csvmaster']['filename']=basename($filename);
			//echo 'имя файла для работы '.ABSPATH.$filename;
		}
	}
	//die();
	$step=2;
}

//проверка, есть ли в обрабатываемом файле столько записей,
//на сколько смещает фром
//и не сместить ли к шагу 3
if($step==2){
	if(isset($_POST['doAnother'])){
		//обнуляем поля файлов
		$_SESSION['csvmaster']['filename']=NULL;
		$_SESSION['csvmaster']['process_filename']=NULL;
		$step=1;
	}else{
	
		
		//внесение изменений во временный файл
		if(isset($_POST['doMakeIt'])|| isset($_POST['doPrev'])|| isset($_POST['doNext'])){
			//понять, в каком мы фром
			//все, от фром+1 до фром+записей_на_страницу
			
			$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
			//по артикулу
			foreach($_POST as $k=>$v){
				if(eregi("_art", $k)){
					//$v = articul
					//echo "$from:  $k - $v<br>";
					$csv_hash=md5($v);
					
					$params=Array();
					$params['action']=abs((int)$_POST[$csv_hash.'_action']);
					$params['articul']=SecStr($v);
					$params['name']=SecStr($_POST[$csv_hash.'_name']);
					$params['ost']=abs((int)$_POST[$csv_hash.'_ost']);
					$params['mid']=SecStr($_POST[$csv_hash.'_mid']);
					
					$CsvFormer->EditRecord($from, $v, $params);
					//die();
				}
			}
			
		}
		
		
		if(isset($_POST['doMakeIt'])){
			$step=3;
			$from=0;
		}else if(isset($_POST['doPrev'])){
			$from=$from-$_SESSION['csvmaster']['records_per_page'];
			if($from<0) $from=0;
		}else if(isset($_POST['doNext'])){
			$from=$from+$_SESSION['csvmaster']['records_per_page'];
		}
		
		//проверки фром
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		$fres=$CsvFormer->CheckFromPosition($from);
		if($fres==-2) {
			$step=3;
			$from=0;
		}
		
	}
}

if($step==3){
	if(isset($_POST['BackToStep2'])){
		
		//очистить временный файл
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		//$CsvFormer->ClearTempFile();
		$from=0;
		$step=2;
	}else{
		//внесение изменений во временный файл
		if(isset($_POST['doUpdateIt'])|| isset($_POST['doPrev1'])|| isset($_POST['doNext1'])){
			//понять, в каком мы фром
			//все, от фром+1 до фром+записей_на_страницу
			
			$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
			//по артикулу
			foreach($_POST as $k=>$v){
				if(eregi("_art", $k)){
					//$v = articul
					$csv_hash=md5($v);
					
					$params=Array();
					$params['action']=abs((int)$_POST[$csv_hash.'_action']);
					$params['articul']=SecStr($v);
					$params['name']=SecStr($_POST[$csv_hash.'_name']);
					$params['ost']=abs((int)$_POST[$csv_hash.'_ost']);
					$params['mid']=SecStr($_POST[$csv_hash.'_mid']);
					
					$CsvFormer->EditRecord($from, $v, $params);
				}
			}
		}
		
		
		if(isset($_POST['doUpdateIt'])){
			$step=4;
		}else if(isset($_POST['doPrev1'])){
			$from=$from-$_SESSION['csvmaster']['records_per_page'];
			if($from<0) $from=0;
		}else if(isset($_POST['doNext1'])){
			$from=$from+$_SESSION['csvmaster']['records_per_page'];
		}
		
		//проверки фром
		$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
		$fres=$CsvFormer->CheckFromPositionRez($from,$cc);
		if($fres==-2) $step=4;
	}
}

if($step==4){
	$from=0;
	
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	$update_result=$CsvFormer->UpdateBase('csvmaster/table3.html');
	
	
	//die();
}



	
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' - CSV-импорт');
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

<h1>CSV-импорт товарных остатков</h1>

<?
if($step==1){?>
<h2>Шаг 1. Выбор источника синхронизации.</h2>
<h3>Загрузите или выберите csv-файл:</h3>
<form action="csvmaster.php" enctype="multipart/form-data" method="post" name="filesel" id="filesel">
	<input type="hidden" name="step" id="step" value="1.5">
	
	<strong><input type="radio" name="load_mode" id="load_mode1" value="1" checked>Загрузить файл:</strong><br>
	<input type="file" name="file_to_upload" id="file_to_upload" size="80" onClick="m=document.getElementById('load_mode1'); m.checked=true;"><p>
	
	<strong><input type="radio" name="load_mode" id="load_mode2" value="2">Выбрать уже загруженный файл:</strong><br>
	<input type="text" name="photo_small" id="photo_small" size="80" maxlength="255" onClick="m=document.getElementById('load_mode2'); m.checked=true;">
	<a href="javascript: //" class="" onclick="m=document.getElementById('load_mode2'); m.checked=true; window.open('csvfiles.php?mode=2','file_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.filesel.photo_small.value=''; document.forms.filesel.photo_small.focus();">выбрать файл</a>
	<p>
	
	
	<strong>Записей на страницу:</strong><br>
	<input type="text" name="records_per_page" id="records_per_page" value="<?=$_SESSION['csvmaster']['records_per_page']?>" size="3" maxlength="3"><p>
	
	<strong>Разделитель в csv-файле:</strong><br>
	<input type="text" name="separator" id="separator" value="<?=$_SESSION['csvmaster']['separator']?>" size="1" maxlength="1"><p>
	
	<input class="unbord" type="checkbox" name="do_delete_file" id="do_delete_file" value="1" <?if($_SESSION['csvmaster']['do_delete_file']==1){?>checked<?}?>><strong>удалять загруженный файл</strong><p>
	
	<input type="submit" name="doSver" id="doSver" value="Продолжить -&gt;"><p>
	
	<input type="button" name="doCancel" id="doCancel" value="Отмена" onClick="window.close();">
</form>
<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("filesel");  
  frmvalidator.addValidation("records_per_page","req","Заполните  поле Записей на страницу!");    
  frmvalidator.addValidation("records_per_page","num","В поле Записей на страницу допустимы только цифры!");    
  frmvalidator.addValidation("records_per_page","gt=0","В поле Записей на страницу допустимы только значения больше нуля!"); 
  frmvalidator.addValidation("separator","req","Заполните  поле Разделитель в csv-файле!");    
</script>  
<?}?>	
	
<?if($step==2){?>
	<h2>Шаг 2. Сравнение данных csv-файла и базы данных сайта.</h2>
	<h3>Выберите операции над записями:</h3>
	<p><em>На этом шаге Вы формируете задание на обновление базы данных. Слева приводятся записи из загруженного Вами на шаге 1 файла, справа - соответствующие им записи базы данных. Если запись в базе данных найдена - то строка таблицы помечается <span style="background-color: #ccffcc;">зеленым цветом</span>, если не найдена - то  <span style="background-color: #ffcccc;">красным цветом</span>.<br><br>

<strong>Необходимо пролистать все записи csv-файла! Иначе - непросмотренные записи не будут внесены в базу данных!</strong>
</em></p>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	echo $CsvFormer->DrawTable1('csvmaster/table1.html',$from);
	
	?>

<?}?>	

<?
//echo $step;
?>


<?if($step==3){?>
	<h2>Шаг 3. Проверка задания синхронизации.</h2>
	<h3>Подтвердите выбранные изменения:</h3>
	<p>
	<em>На этом шаге Вы просматриваете все заданные Вами операции на шаге 2, можете корректировать каждую из них, и подтверждаете вносимые изменения в базу данных.<br>
<br>
<strong>Можно не пролистывать до конца все записи, если Вы полностью уверены в Вашем выборе, сделанном на шаге 2.</strong>
</em>
	</p>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	echo $CsvFormer->DrawTable2('csvmaster/table2.html',$from);
	?>
<?}?>	

<?if($step==4){?>
	<h2>Шаг 4. Внесение изменений.</h2>
	<?
	$CsvFormer=new CsvFormer(PHOTOS_BASEPATH);
	//echo $CsvFormer->UpdateBase();
	if(isset($update_result)) echo $update_result;
	?>
<?}?>	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>	