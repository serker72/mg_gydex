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
		echo "���������� ����������� � ����� ������!<br>";
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

	/*������� ������ �������*/
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

	/*������� �������*/
	$sql = "insert into langs(id, lang_name) values(1,\"�������\");";
	$c = mysql_query($sql);
	echo mysql_error();

	$sql = "insert into langs(id, lang_name, is_shown) values(3,\"English\",0);";
	$c = mysql_query($sql);
	echo mysql_error();


	/*������� ���� ���� �������*/
	$sql = "create table allmenu(
			id bigint unsigned auto_increment not null primary key,
			parent_id bigint unsigned default 0,
			
			path varchar(80),		
			ord tinyint unsigned default 0,
			is_new_window  tinyint unsigned default 0, /*��������� ������ � ����� ����*/
			is_pics_list tinyint unsigned default 0, /*������������ ������� ��������*/
			is_hor tinyint unsigned default 0, /*���������� � ��������. ����*/			
			is_hor2 tinyint unsigned default 0, /*���������� � ��������. ����-2*/						
			is_right tinyint unsigned default 0, /*���������� � ������ ����*/						
			is_price tinyint unsigned default 0, /*�������� �����-����*/
			is_news tinyint unsigned default 0, /*�������� �������*/									
			is_links tinyint unsigned default 0, /*�������� ������*/			
			is_gallery tinyint unsigned default 0, /*�������� �������*/			
			is_papers tinyint unsigned default 0, /*�������� ������*/						
			is_feedback_forms tinyint unsigned default 0, /*�������� �������� �����*/		
			is_basket tinyint unsigned default 0 /*����� ���������� ������ � �������*/
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();

	/*������� ������ ����*/
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
	
	
	/*���� �����������*/
	$sql = "create table photo_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			
			ord tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif'
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*������� ������ ����a �����������*/
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
	
	
	/*���� �����*/
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
	
	
	/*�������� �������*/
	$sql = "create table price_ost(
			id bigint unsigned auto_increment not null primary key,
			price_id bigint unsigned default 0,
			ostatok int unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*������� ������ ����a ������*/
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
	
		//������������� ������
	$sql = "create table goods_rekommend(
			id int unsigned auto_increment not null primary key,
			pri_lid int unsigned,
			sec_lid int unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();	
	
	//�����-�������������
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
	
	
	
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!! �������
	
	/*������� ����� ������� ��-�*/
	$sql = "create table dict_kind(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� ���� �������*/
	$sql = "insert into dict_kind(id, name) values(1,\"������� �������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into dict_kind(id, name) values(2,\"������� ����� ������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into dict_kind(id, name) values(3,\"�������������� ���� ������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� �������� ��-�*/
	$sql = "create table dict(
			id bigint unsigned auto_increment not null primary key,
			kind_id bigint unsigned  default 1 not null,
			
			ord tinyint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	
	$c = mysql_query($sql);
	echo mysql_error();


	/*������� ������ ������� ��-�*/
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
	
	/*���� ������������ �������*/
	$sql = "create table attach_d(
			id bigint unsigned auto_increment not null primary key,
			
			key_name varchar(255),
			name varchar(255),
			short_name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� ���� ������������ �������*/
	$sql = "insert into attach_d(id, key_name, name, short_name) values(1,\"mid\",\"� ���������� �������\",\"-\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into attach_d(id, key_name, name, short_name) values(2,\"mid\",\"� ���������� ������� � ���� �����������\",\"+\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into attach_d(id, key_name, name, short_name) values(3,\"price_id\",\"� ���������� ������\",\"-\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������������ ��������*/
		$sql = "create table dict_attach_d(
			id bigint unsigned auto_increment not null primary key,
			ord tinyint unsigned  default 0,
			dict_id bigint unsigned  default 0,
			attach_code bigint unsigned,
			key_value bigint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*����� �������*/
		$sql = "create table prop_name(
			id bigint unsigned auto_increment not null primary key,
			dict_id bigint unsigned  default 0,
			is_criteria tinyint unsigned  default 0,
			ord tinyint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*�������� ������ ���� �������*/
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
	
	
	/*�������� �������*/
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
	
	/*�������� ������ �������� �������*/
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
	
	//!!!!!!!!!!!!!!!!!!!!! ����� �������� ��-�
	
	
	
	
//********************************* ���� � ������ ********************************************//	
	
	/*������*/
	$sql = "create table currency(
			id bigint unsigned auto_increment not null primary key,
			is_base_shop tinyint unsigned default 0,
			is_base_rate tinyint unsigned default 0,
			rate float
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();
	
	/*�������� ������ �����*/
	$sql = "create table currency_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default '',
			signat varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*������� �����.*/
	$sql = "insert into currency(id, is_base_shop, is_base_rate, rate) values(1,1,1,\"1\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into currency_lang(plid, lang_id, value_id, name, signat) values(1,1,1,\"�����\",\"���.\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	
	//������������� ����� �� ������ (���. �������)
	$sql = "create table currency_use_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//���� �������
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
	
	
	//���� ��� 
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
	
	//�������� ������ ����� ���
	$sql = "create table price__lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default '',
			descr varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	//������� ���������� ����
	$sql = "create table cond_price(
			id bigint unsigned auto_increment not null primary key,
			area_id bigint unsigned,
			ffrom int unsigned default 0,
			tto int unsigned default 0,
			key_value bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	//����. ������ ������� ���������� ����
	$sql = "create table cond_price_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			value_id bigint unsigned  default 0,
			name varchar(255) default ''
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	
	//������� �������� ������� ���-� ����
	$sql = "create table area_cond_price(
			id bigint unsigned auto_increment not null primary key,
			key_name varchar(255),
			name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";	

	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*������� ���� ������� �������� ������� ���-� ����*/
	$sql = "insert into area_cond_price(id, key_name, name) values(1,\"mid\",\"� ���������� �������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into area_cond_price(id, key_name, name) values(2,\"mid\",\"� ���������� ������� � ���� �����������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into area_cond_price(id, key_name, name) values(3,\"price_id\",\"� ���������� ������\");";
	$c = mysql_query($sql);
	echo mysql_error();
//*************************** ENDOF ���� � ������ ********************************************//

//*************************** ������ � �������������� ***************************************//
	/*����������*/
	$sql = "create table clients(
			id bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			reg_id bigint unsigned default 1, /*��� ���� �����������*/
			login varchar(255),
			passw varchar(255) default '',
			
			username varchar(255) default '',
			address text,
			email varchar(255) default '',
			phone varchar(255) default '',
			is_mailed tinyint unsigned default 0, /*�������� �� �� ��������*/
			is_blocked tinyint unsigned default 0,
			skidka float(4,2) default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();
	
		//���� ����������� �����������
	$sql = "create table reg_kind(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255)
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� ���� ����������� ����������*/
	$sql = "insert into reg_kind(id, name) values(1,\"����������� �� �����\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
		//������ �����������
	$sql = "create table cl_groups(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			descr text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();
		
		//�������� ����������� �� �������
	$sql = "create table cl_by_groups(
			id bigint unsigned auto_increment not null primary key,
			clid bigint unsigned default 0,
			gr_id bigint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();

//*********************** ENDOF ������ � �������������� *************************************//


//***********************  ������ � �������� *************************************//
	//�����
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
	
	//���� ������� ������
	$sql = "create table order_status(
			id bigint unsigned auto_increment not null primary key,
			is_changeable tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	//�������� ������  ����� ������� ������
	$sql = "create table order_status_lang(
			plid bigint unsigned auto_increment not null primary key,
			lang_id bigint unsigned default 0,
			status_id bigint unsigned default 0,
			name varchar(255) default '',
			descr text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� ���� ������� ������*/
	$sql = "insert into order_status(id, is_changeable) values(1,1);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(1,1,1,\"��������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(2,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(2,1,2,\"����� ��������������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(3,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(3,1,3,\"������ ������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(4,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(4,1,4,\"����� ���������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status(id, is_changeable) values(5,0);";
	$c = mysql_query($sql);
	echo mysql_error();
	$sql = "insert into order_status_lang(plid, lang_id, status_id, name) values(5,1,5,\"����� ��������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	//����� � ������
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
	
	//����� ������ � ������
	$sql = "create table order_item_option(
			id bigint unsigned auto_increment not null primary key,
			item_id bigint unsigned default 0,
			value_id bigint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	
	$c = mysql_query($sql);
	echo mysql_error();
	
//*********************** ENDOF ������ � �������� *************************************//

	/*���� ������*/
	$sql = "create table paper_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			
			ord tinyint unsigned default 0,
			photo_small varchar(255) default 'img/no.gif',			
			photo_big varchar(255) default 'img/no.gif'
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*������� ������ ����a ������*/
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
	
	/*���� ������*/
	$sql = "create table link_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			ord tinyint unsigned default 0,
			url varchar(255) default '',
			use_simple_code tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*������� ������ ����a ������*/
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
	
	
	/*���� �������*/
	$sql = "create table news_item(
			id bigint unsigned auto_increment not null primary key,
			mid bigint unsigned default 0,
			ord tinyint unsigned default 0,
			pdate date not null
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";

	$c = mysql_query($sql);
	echo mysql_error();
	
	
	/*������� ������ ����a �������*/
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
	
	
	/*������� ���������� ��������*/
	/*������������*/
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
	
	/*������*/
	$sql = "create table discr_groups(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text,
			is_blocked tinyint unsigned default 0
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	

	/*������������ � ������*/
	$sql = "create table discr_users_by_groups(
			id bigint unsigned auto_increment not null primary key,
			user_id bigint unsigned,
			group_id bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*��� ���� �������*/
	$sql = "create table discr_rights(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	$sql = "insert into discr_rights(id, name, info) values(1,\"r\", \"������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(2,\"a\", \"���������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(3,\"w\", \"��������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	$sql = "insert into discr_rights(id, name, info) values(4,\"d\", \"��������\");";
	$c = mysql_query($sql);
	echo mysql_error();
	
	/*������� �������*/
	$sql = "create table discr_objects(
			id bigint unsigned auto_increment not null primary key,
			name varchar(255) default '',
			info text
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	/*����� ������������*/
	$sql = "create table discr_user_rights(
			id bigint unsigned auto_increment not null primary key,
			user_id bigint unsigned,
			right_id bigint unsigned,
			object_id bigint unsigned
		)ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";
	$c = mysql_query($sql);
	echo mysql_error();	
	
	
	/*����� ������*/
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
