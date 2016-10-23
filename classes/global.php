<?
require_once('configfile.php');

define("ABSPATH",'/home/gydex.su/www/');

//путь к странице 404
define("PATH404",'/404.php');

//адрес сайта (дл€ рсс-ленты, генерации абсолютных url)
define("SITEURL",'http://www.gydex.su');

//включение отладки залогинивани€ в программах
 define("DEBUG_REDIRECT",false);

//загрузка общих настроек из файла
//при ошибке загрузки используем настройки по умолчанию
$cnf=new ConfigFile();
$cnf->SetFileName(ABSPATH.'cnf/file.txt');
$global_settings=$cnf->LoadFromFile();

if(isset($global_settings['DEBUG_INFO'])){
	if($global_settings['DEBUG_INFO']=='0') define("DEBUG_INFO",false);
	else define("DEBUG_INFO",true);
}else define("DEBUG_INFO",false);

if(isset($global_settings['ITEMS_PER_PAGE'])) define("ITEMS_PER_PAGE",$global_settings['ITEMS_PER_PAGE']);
else define("ITEMS_PER_PAGE",10);

if(isset($global_settings['CRITICAL_OST'])) define("CRITICAL_OST",$global_settings['CRITICAL_OST']);
else define("CRITICAL_OST",10);

if(isset($global_settings['COMMENTS_PER_PAGE'])) define("COMMENTS_PER_PAGE",$global_settings['COMMENTS_PER_PAGE']);
else define("COMMENTS_PER_PAGE",10);

if(isset($global_settings['PHOTOS_PER_PAGE'])) define("PHOTOS_PER_PAGE",$global_settings['PHOTOS_PER_PAGE']);
else define("PHOTOS_PER_PAGE",12);

if(isset($global_settings['GOODS_PER_PAGE'])) define("GOODS_PER_PAGE",$global_settings['GOODS_PER_PAGE']);
else define("GOODS_PER_PAGE",12);

//if(isset($global_settings['PROPS_PER_PAGE'])) define("PROPS_PER_PAGE",$global_settings['PROPS_PER_PAGE']);
//else define("PROPS_PER_PAGE",20);

if(isset($global_settings['NUM_LEVELS'])) {
	if($global_settings['NUM_LEVELS']=='-') define("NUM_LEVELS",'-');
	else define("NUM_LEVELS",abs((int)$global_settings['NUM_LEVELS']));
}
else define("NUM_LEVELS",'-');

if(isset($global_settings['PROPS_PER_PAGE'])) {
	if($global_settings['PROPS_PER_PAGE']=='-') define("PROPS_PER_PAGE",'-');
	else define("PROPS_PER_PAGE",abs((int)$global_settings['PROPS_PER_PAGE']));
}
else define("PROPS_PER_PAGE",'-');

//неудал€емый €зык
if(isset($global_settings['LANG_CODE'])) define("LANG_CODE",$global_settings['LANG_CODE']);
else define("LANG_CODE",1);


if(isset($global_settings['FEEDBACK_EMAIL'])) define("FEEDBACK_EMAIL",$global_settings['FEEDBACK_EMAIL']);
else define("FEEDBACK_EMAIL",'info@blackcat');

if(isset($global_settings['FEEDBACK_PHONE'])) define("FEEDBACK_PHONE",$global_settings['FEEDBACK_PHONE']);
else define("FEEDBACK_PHONE",'');

if(isset($global_settings['OFFICE_ADDRESS'])) define("OFFICE_ADDRESS",$global_settings['OFFICE_ADDRESS']);
else define("OFFICE_ADDRESS",'');


if(isset($global_settings['SITETITLE'])) define("SITETITLE",$global_settings['SITETITLE']);
else define("SITETITLE",'blackcat');

if(isset($global_settings['NO_RIGHTS'])) define("NO_RIGHTS",$global_settings['NO_RIGHTS']);
else define("NO_RIGHTS",'” ¬ас недостаточно прав дл€ данной операции!');


if(isset($global_settings['HAS_PRICE'])){
	if($global_settings['HAS_PRICE']=='0') define("HAS_PRICE",false);
	else define("HAS_PRICE",true);
}else define("HAS_PRICE",false);

if(isset($global_settings['HAS_BASKET'])){
	if($global_settings['HAS_BASKET']=='0') define("HAS_BASKET",false);
	else define("HAS_BASKET",true);
}else define("HAS_BASKET",false);

if(isset($global_settings['HAS_OST'])){
	if($global_settings['HAS_OST']=='0') define("HAS_OST",false);
	else define("HAS_OST",true);
}else define("HAS_OST",false);

if(isset($global_settings['HAS_NEWS'])){
	if($global_settings['HAS_NEWS']=='0') define("HAS_NEWS",false);
	else define("HAS_NEWS",true);
}else define("HAS_NEWS",false);

if(isset($global_settings['HAS_LINKS'])){
	if($global_settings['HAS_LINKS']=='0') define("HAS_LINKS",false);
	else define("HAS_LINKS",true);
}else define("HAS_LINKS",false);

if(isset($global_settings['HAS_PAPERS'])){
	if($global_settings['HAS_PAPERS']=='0') define("HAS_PAPERS",false);
	else define("HAS_PAPERS",true);
}else define("HAS_PAPERS",false);

if(isset($global_settings['HAS_GALLERY'])){
	if($global_settings['HAS_GALLERY']=='0') define("HAS_GALLERY",false);
	else define("HAS_GALLERY",true);
}else define("HAS_GALLERY",false);

if(isset($global_settings['HAS_FEEDBACK_FORMS'])){
	if($global_settings['HAS_FEEDBACK_FORMS']=='0') define("HAS_FEEDBACK_FORMS",false);
	else define("HAS_FEEDBACK_FORMS",true);
}else define("HAS_FEEDBACK_FORMS",false);


if(isset($global_settings['HAS_URLS'])){
	if($global_settings['HAS_URLS']=='0') define("HAS_URLS",false);
	else define("HAS_URLS",true);
}else define("HAS_URLS",false);




//функци€ обработки строки перед занесением в бд
function SecStr($str="",$level=0){
	if($level==10){
	//удал€ем все теги и им подобные символы, строга€ зачистка
		$str=strip_tags($str);
		$str=eregi_replace("<", "", $str);
		$str=eregi_replace(">", "", $str);		
		$str=htmlspecialchars($str);		
	}
	
	if($level==9){
	//замен€ем теги на спецсимволы, нестрога€ зачистка
		$str=htmlspecialchars($str);
	}	
	
	$str = trim($str);	
	$str=addslashes($str);
	
	return $str;
}



function DeParams($params){
	$str='';
	foreach($params as $k=>$v){
		$str.="&$k=$v";
	}
	
	return $str;
}

function SecureCyr($fld){
	$fld=strtr($fld, "абвгдеЄжзиклмнопрстуфхцчшщйьъыэю€", "abvgdeejziklmnoprstufhc4ww___yeua");
	$fld=strtr($fld, "јЅ¬√ƒ≈®∆«» ЋћЌќѕ–—“”‘’÷„Ўў…№ЏџЁёя", "ABVGDEEJZIKLMNOPRSTUFHC4WW___YEUA");
	
	return $fld;
}


function SecurePath($fld){
	$fld=strtr($fld, "?* :", "____");
	
	$fld=SecureCyr($fld);
	
	return $fld;
}	



function DateFromYmd($string='2008-01-01'){
	return date('d.m.Y',mktime(0,0,0,substr($string,5,2),substr($string,8,2),substr($string,0,4) ));
}

function UnixDateFromYmd($string='2008-01-01'){
	return date('r',mktime(0,0,0,substr($string,5,2),substr($string,8,2),substr($string,0,4) ));
}

//форматирование цены
function FormatPrice($value, $dims, $afternil=0){
	return sprintf("%.".$afternil."f".$dims, $value);
}


//строка кода джаваскрипт дл€ определени€ координат событи€
define("COORDFUNC", "
	  e = event;
	  coord=GetCoords(e);
");
?>