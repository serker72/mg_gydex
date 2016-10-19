<?
define("DebugMode","DM_TRUE"); //DM_FALSE
/*define("ITEMS_PER_PAGE",10);
//define("COMMENTS_PER_PAGE",10);
define("PHOTOS_PER_PAGE",12);
define("GOODS_PER_PAGE",20);

define("LOGNAME",'../logs/admlog.log');
define("FEEDBACK_EMAIL",'info@nnnnnnnnn.ru');
define("SITETITLE",'NT');
define("MODEL_NAME_CAPT",'номер');

//функция обработки строки перед занесением в бд
function SecStr($str="",$level=0){
	if($level==10){
	//удаляем все теги и им подобные символы, строгая зачистка
		$str=strip_tags($str);
		$str=eregi_replace("<", "", $str);
		$str=eregi_replace(">", "", $str);		
		$str=htmlspecialchars($str);		
	}
	
	if($level==9){
	//заменяем теги на спецсимволы, нестрогая зачистка
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
	$fld=strtr($fld, "абвгдеёжзиклмнопрстуфхцчшщйьъыэюя", "abvgdeejziklmnoprstufhc4ww___yeua");
	$fld=strtr($fld, "АБВГДЕЁЖЗИКЛМНОПРСТУФХЦЧШЩЙЬЪЫЭЮЯ", "ABVGDEEJZIKLMNOPRSTUFHC4WW___YEUA");
	
	return $fld;
}


function SecurePath($fld){
	$fld=strtr($fld, "?* :", "____");
	
	$fld=SecureCyr($fld);
	
	return $fld;
}	





//строка кода джаваскрипт для определения координат события
define("COORDFUNC", "
	  e = event;
	  coord=GetCoords(e);
");
*/
?>