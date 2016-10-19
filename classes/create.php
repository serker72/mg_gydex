<?Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
Header("Cache-Control: nocache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified:".gmdate("D, d M Y H:i:s")."GMT");	
function DBConnect(){

define("DBName","blackac");
define("HostName","localhost");
define("UserName", "blackac");
define("Password", "");
	if(!mysql_connect(HostName,UserName,Password)){
		echo "Невозможно соединиться с базой данных!<br>";
		echo mysql_error();
		die();
	}
	mysql_select_db(DBName);
}

DBConnect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>
<?

	/*таблица языков проекта*/
	$sql = "create table langs(
		id bigint unsigned auto_increment not null primary key,
		lang_name varchar(255) default '',
		lang_flag varchar(255) default 'img/no.gif',
		lang_flag_bigger varchar(255) default 'img/no.gif',
		lang_meta varchar(255) default '',
		is_shown tinyint unsigned default 1
	)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;";

	$c = mysql_query($sql);
	echo mysql_error();

	/*вставим русский*/
	$sql = "insert into langs(id, lang_name) values(1,\"Русский\");";
	$c = mysql_query($sql);
	echo mysql_error();

	$sql = "insert into langs(id, lang_name, is_shown) values(3,\"English\",0);";
	$c = mysql_query($sql);
	echo mysql_error();


	/*таблица меню всех уровней*/
	$sql = "create table allmenu(
			id bigint unsigned auto_increment not null primary key,
			parent_id bigint unsigned default 0,
			
			path varchar(80),		
			ord tinyint unsigned default 0,
			is_new_window  tinyint unsigned default 0, /*открывать раздел в новом окне*/
			is_pics_list tinyint unsigned default 0, /*отображается списком картинок*/
			is_hor tinyint unsigned default 0, /*содержится в горизонт. меню*/			
			is_hor2 tinyint unsigned default 0, /*содержится в горизонт. меню-2*/						
			is_right tinyint unsigned default 0, /*содержится в правом меню*/						
			is_price tinyint unsigned default 0, /*содержит прайс-лист*/
			is_news tinyint unsigned default 0, /*содержит новости*/									
			is_links tinyint unsigned default 0, /*содержит ссылки*/			
			is_gallery tinyint unsigned default 0, /*содержит галерею*/			
			is_papers tinyint unsigned default 0, /*содержит статьи*/						
			is_feedback_forms tinyint unsigned default 0, /*содержит обратную связь*/		
			is_basket tinyint unsigned default 0 /*можно заказывать товары в корзину*/
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();

	/*таблица языков меню*/
	$sql = "create table menu_lang(
			mlid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			mid bigint unsigned  default 0,
			
			name varchar(255),
			txt text,
			title varchar(255),
			
			is_shown tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_for_goods varchar(255) default 'img/no.gif',
   		    FULLTEXT (name,txt,title)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*итем фотогалереи*/
	$sql = "create table photo_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			
			ord tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif'
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*таблица языков итемa фотогалереи*/
	$sql = "create table photo_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			photo_id bigint unsigned  default 0,
			
			name varchar(255),
			small_txt text,
			big_txt text,
			title varchar(255),
			
			is_shown tinyint unsigned default 0,
			FULLTEXT (name,small_txt,big_txt,title)

		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*итем товар*/
	$sql = "create table price_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			
			ord tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif',
			firmid int unsigned default 0,
			is_new tinyint unsigned default 0,
			
			articul varchar(255) default 'img/no.gif'
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*товарные остатки*/
	$sql = "create table price_ost(
			id bigint unsigned auto_increment not null primary key,
			price_id bigint unsigned default 0,
			ostatok int unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*таблица языков итемa товара*/
	$sql = "create table price_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			price_id bigint unsigned  default 0,
			
			name varchar(255),
			small_txt text,
			big_txt text,
			title varchar(255),
			
			is_shown tinyint unsigned default 0,
			FULLTEXT (name,small_txt,big_txt,title)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
		//рекомендуемые товары
	$sql = "create table goods_rekommend(
			id int unsigned auto_increment not null primary key,
			pri_lid int unsigned,
			sec_lid int unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();	
	
	//фирмы-производители
	$sql = "create table firms(
			id int unsigned auto_increment not null primary key,
			photo_small varchar(255) default 'img/no.gif',									
			photo_big varchar(255) default 'img/no.gif',
			ord int unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "create table firms_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			firmid bigint unsigned  default 0,
			name varchar(255) default '',	
			info mediumtext,	
			is_shown tinyint unsigned default 1,
			FULLTEXT (name,info)																				
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	
	
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!! словари
	
	/*таблица видов словаря св-в*/
	$sql = "create table dict_kind(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*вставим виды словаря*/
	$sql = "insert into dict_kind(id, name) values(1,\"Таблица свойств\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into dict_kind(id, name) values(2,\"Таблица опций заказа\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into dict_kind(id, name) values(3,\"Дополнительные фото товара\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*таблица словарей св-в*/
	$sql = "create table dict(
			id bigint unsigned auto_increment not null primary key,
			kind_id bigint unsigned  default 1 not null,
			
			ord tinyint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	
	$c = mysql_query($sql);
	echo mysql_error();


	/*таблица языков словаря св-в*/
	$sql = "create table dict_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			dict_id bigint unsigned  default 0,
			name varchar(255),
			big_txt text,
			is_shown tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*виды прикреплений словаря*/
	$sql = "create table attach_d(
			id bigint unsigned auto_increment not null primary key,
			
			key_name varchar(255),
			name varchar(255),
			short_name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*вставим виды прикреплений словаря*/
	$sql = "insert into attach_d(id, key_name, name, short_name) values(1,\"mid\",\"К выбранному разделу\",\"-\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into attach_d(id, key_name, name, short_name) values(2,\"mid\",\"К выбранному разделу и всем подразделам\",\"+\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into attach_d(id, key_name, name, short_name) values(3,\"price_id\",\"К выбранному товару\",\"-\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*прикрепление словарей*/
		$sql = "create table dict_attach_d(
			id bigint unsigned auto_increment not null primary key,
			ord tinyint unsigned  default 0,
			dict_id bigint unsigned  default 0,
			attach_code bigint unsigned,
			key_value bigint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*имена свойств*/
		$sql = "create table prop_name(
			id bigint unsigned auto_increment not null primary key,
			dict_id bigint unsigned  default 0,
			is_criteria tinyint unsigned  default 0,
			ord tinyint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*языковые данные имен свойств*/
		$sql = "create table prop_name_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			name_id bigint unsigned  default 0,
			name varchar(255),
			default_val varchar(255) default '',
			is_shown tinyint unsigned default 0,
			FULLTEXT (name)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	/*Значения свойств*/
		$sql = "create table prop_value(
			id bigint unsigned auto_increment not null primary key,
			name_id bigint unsigned  default 0,
			item_id  bigint unsigned  default 0,
			
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif',
			ord tinyint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*языковые данные значений свойств*/
	$sql = "create table prop_value_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default '',
			is_shown tinyint unsigned default 0,
			FULLTEXT (name)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//!!!!!!!!!!!!!!!!!!!!! конец словарей св-в
	
	
	
	
//********************************* ЦЕНЫ И ВАЛЮТЫ ********************************************//	
	
	/*ВАЛЮТЫ*/
	$sql = "create table currency(
			id bigint unsigned auto_increment not null primary key,
			is_base_shop tinyint unsigned default 0,
			is_base_rate tinyint unsigned default 0,
			rate float
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*языковые данные валют*/
	$sql = "create table currency_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default '',
			signat varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*вставим рубль.*/
	$sql = "insert into currency(id, is_base_shop, is_base_rate, rate) values(1,1,1,\"1\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into currency_lang(plid, lang_id, value_id, name, signat) values(1,1,1,\"Рубль\",\"руб.\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	
	//использование валют по языкам (доп. правила)
	$sql = "create table currency_use_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//цены товаров
	$sql = "create table good_price(
			id bigint unsigned auto_increment not null primary key,
			good_id bigint unsigned,
			price_id bigint unsigned,
			curr_id bigint unsigned,
			
			value double(14,2),
			not_use_formula tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	//виды цен 
	$sql = "create table price(
			id bigint unsigned auto_increment not null primary key,
			cond_id bigint unsigned  default 0,
			is_base tinyint unsigned default 0,
			use_formula tinyint unsigned default 0,
			formula_name varchar(255) default '',
			formula varchar(255) default '',
			ord tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//языковые данные видов цен
	$sql = "create table price__lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default '',
			descr varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	//условие применения цены
	$sql = "create table cond_price(
			id bigint unsigned auto_increment not null primary key,
			area_id bigint unsigned,
			ffrom int unsigned default 0,
			tto int unsigned default 0,
			key_value bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//язык. данные условие применения цены
	$sql = "create table cond_price_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	
	//область действия условия исп-я цены
	$sql = "create table area_cond_price(
			id bigint unsigned auto_increment not null primary key,
			key_name varchar(255),
			name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*вставим виды область действия условия исп-я цены*/
	$sql = "insert into area_cond_price(id, key_name, name) values(1,\"mid\",\"К выбранному разделу\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into area_cond_price(id, key_name, name) values(2,\"mid\",\"К выбранному разделу и всем подразделам\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into area_cond_price(id, key_name, name) values(3,\"price_id\",\"К выбранному товару\");";
	$c = mysql_query($sql);
	echo mysql_error();
//*************************** ENDOF ЦЕНЫ И ВАЛЮТЫ ********************************************//

//*************************** РАБОТА С ПОЛЬЗОВАТЕЛЯМИ ***************************************//
	/*покупатели*/
	$sql = "create table clients(
			id bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			reg_id bigint unsigned default 1, /*код вида регистрации*/
			login varchar(255),
			passw varchar(255) default '',
			
			username varchar(255) default '',
			address text,
			email varchar(255) default '',
			phone varchar(255) default '',
			is_mailed tinyint unsigned default 0, /*подписан ли на рассылку*/
			is_blocked tinyint unsigned default 0,
			skidka float(4,2) default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();
	
		//виды регистрации покупателей
	$sql = "create table reg_kind(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*вставим виды регистрации покупателя*/
	$sql = "insert into reg_kind(id, name) values(1,\"Регистрация на сайте\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
		//группы покупателей
	$sql = "create table cl_groups(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			descr text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();
		
		//разбивка покупателей по группам
	$sql = "create table cl_by_groups(
			id bigint unsigned auto_increment not null primary key,
			clid bigint unsigned default 0,
			gr_id bigint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();

//*********************** ENDOF РАБОТА С ПОЛЬЗОВАТЕЛЯМИ *************************************//


//***********************  РАБОТА С ЗАКАЗАМИ *************************************//
	//заказ
	$sql = "create table orders(
			id bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			status_id bigint unsigned default 0,
			clid bigint unsigned default 0,			
			kupon_id bigint unsigned default 0,
			pdate date not null,
			address text,
			email tinytext,
			phone tinytext,
			comment text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();
	
	//виды статуса заказа
	$sql = "create table order_status(
			id bigint unsigned auto_increment not null primary key,
			is_changeable tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	//языковые данные  видов статуса заказа
	$sql = "create table order_status_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			status_id bigint unsigned default 0,
			name varchar(255) default '',
			descr text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*вставим виды статуса заказа*/
	$sql = "insert into order_status(id, is_changeable) values(1,1);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(1,1,1,\"Ожидание\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(2,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(2,1,2,\"Заказ обрабатывается\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(3,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(3,1,3,\"Курьер выехал\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(4,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(4,1,4,\"Заказ отправлен\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(5,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(5,1,5,\"Заказ выполнен\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	//Товар в заказе
	$sql = "create table order_item(
			id bigint unsigned auto_increment not null primary key,
			order_id bigint unsigned default 0,
			good_id bigint unsigned default 0,
			quantity bigint unsigned default 1,
			price_value float(14,2) default 0,
			currency_id bigint unsigned default 0,
			comment text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	//опция товара в заказе
	$sql = "create table order_item_option(
			id bigint unsigned auto_increment not null primary key,
			item_id bigint unsigned default 0,
			value_id bigint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
//*********************** ENDOF РАБОТА С ЗАКАЗАМИ *************************************//

	/*итем статья*/
	$sql = "create table paper_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			
			ord tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif'
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*таблица языков итемa статьи*/
	$sql = "create table paper_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			paper_id bigint unsigned  default 0,
			name varchar(255),
			small_txt text,
			big_txt mediumtext,
			title varchar(255),
			is_shown tinyint unsigned default 0,
			FULLTEXT(name,small_txt,big_txt,title)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*итем ссылка*/
	$sql = "create table link_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			ord tinyint unsigned default 0,
			url varchar(255) default '',
			use_simple_code tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*таблица языков итемa ссылки*/
	$sql = "create table link_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			link_id bigint unsigned  default 0,
			name varchar(255),
			photo_small varchar(255) default 'img/no.gif',	
			small_txt text,
			simple_code text,
			is_shown tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*итем новость*/
	$sql = "create table news_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			ord tinyint unsigned default 0,
			pdate date not null
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*таблица языков итемa новости*/
	$sql = "create table news_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			news_id bigint unsigned  default 0,
			name varchar(255),
			small_txt text,
			big_txt text,
			is_shown tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*система управления доступом*/
	/*пользователи*/
	$sql = "create table discr_users(
			id bigint unsigned auto_increment not null primary key,
			login varchar(255),
			passw varchar(255) default '',
			username varchar(255) default '',
			address text,
			email varchar(255) default '',
			phone varchar(255) default '',
			is_blocked tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*группы*/
	$sql = "create table discr_groups(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text,
			is_blocked tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	

	/*пользователь в группе*/
	$sql = "create table discr_users_by_groups(
			id bigint unsigned auto_increment not null primary key,
			user_id bigint unsigned,
			group_id bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*вид прав доступа*/
	$sql = "create table discr_rights(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	$sql = "insert into discr_rights(id, name, info) values(1,\"r\", \"чтение\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(2,\"a\", \"изменение\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(3,\"w\", \"создание\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(4,\"d\", \"удаление\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*объекты системы*/
	$sql = "create table discr_objects(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*права пользователя*/
	$sql = "create table discr_user_rights(
			id bigint unsigned auto_increment not null primary key,
			user_id bigint unsigned,
			right_id bigint unsigned,
			object_id bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	/*права группы*/
	$sql = "create table discr_group_rights(
			id bigint unsigned auto_increment not null primary key,
			group_id bigint unsigned,
			right_id bigint unsigned,
			object_id bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	
?>


</body>
</html>
