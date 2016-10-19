<?
require_once('global.php');
require_once('priceitem.php');
require_once('mmenuitem.php');
require_once('propvalitem.php');
require_once('dictnvdisp.php');
require_once('dictattdisp.php');
require_once('pricefinder.php');
require_once('orderitem.php');
require_once('orderitemitem.php');
require_once('orderitemoptitem.php');
require_once('smarty/SmartyAdm.class.php');
//класс корзины
class Basket{
	protected $sess_name; //имя сессии, где корзина
	protected $show_errors=true;
	protected $cart=Array();
	protected $err_mess=Array();
	protected $templates=Array();
	protected $lang;
	//ценоопределитель
	public $price_finder;
	
	//общая стоимость по корзине
	static protected $costblock;
	
	public function __construct($sess_name='blackcat_basket'){
		$this->init($sess_name);
	}
	
	//установка всех имен
	protected function init($sess_name){
		$this->sess_name=$sess_name;
		if(isset($_SESSION['lang'])){
			$this->lang=$_SESSION['lang'];
		}else $this->lang=LANG_CODE;
		
		$this->LoadFromSession(1);
		$this->price_finder=new PriceFinder();
		
		$this->err_mess[1]='товар отсутствует';
		$this->err_mess[2]='неправильный язык';
		$this->err_mess[3]='неверное значение опции';
		$this->err_mess[4]='ошибка структуры корзины';
		$this->err_mess[5]='раздела, из которого заказан товар, не существует';
		$this->err_mess[6]='в разделе, из которого заказан товар, нет каталога товаров';		
		$this->err_mess[7]='отключен интернет-магазин на сайте';		
		$this->err_mess[8]='не найдено выбранное значение опции';		
		$this->err_mess[9]='не найдена опция, значение которой выбрано';		
		$this->err_mess[10]='ошибка вычисления цены';		
		$this->err_mess[11]='ошибка вычисления стоимости';		
		
		
		$this->templates['full_basket_table']='basket/full_table.html';
		$this->templates['full_basket_item']='';
		$this->templates['full_basket_options']='basket/options_set.html';
		$this->templates['full_basket_options_item']='';
		
		$this->templates['short_basket_witems']='basket/short_basket.html';
		$this->templates['short_basket_woitems']='';
		
		$this->templates['send_segment']='';
		$this->templates['send_not_segment']='';		
	}
	
	//самый главный метод:))
	//отправка заказа на сервер
	public function Send($params){
		//все-все сердитые проверки
		$order_id=NULL;
		$temp_b=$this->SortBasket($_SESSION[$this->sess_name]);
		unset($_SESSION[$this->sess_name]);
		$_SESSION[$this->sess_name]=$temp_b;
		
		$this->CorrectQuants();
		
		$this->LoadFromSession(20);
		//проверка авторизации
		$tmp_auth=new AuthUser();
		$profile=$tmp_auth->Auth();
		if(($profile!==NULL)&&(count($this->cart)!=0)){
			//
			$trf=new ResFile(ABSPATH.'cnf/resources.txt');
			
			$oi=new OrderItem();
			
			$order_params=Array();
			$order_params['lang_id']=$this->lang;
			$order_params['status_id']=1;
			$order_params['clid']=$profile['id'];
			$order_params['address']=$params['address'];
			$order_params['phone']=$params['phone'];
			$order_params['email']=$params['email'];
			$order_params['pdate']=date("Y-m-d", time());
			
			$order_id=$oi->Add($order_params);
			
			$mail_subj=$trf->GetValue('order_mail','topic',$this->lang).''.$order_id;
			$mail_to=$params['email'];
			$admin_to=FEEDBACK_EMAIL;
			$admin_subj='Новый заказ на сайте '.SITETITLE.', №'.$order_id;
			
			$mail_text=$trf->GetValue('order_mail','usersalut',$this->lang).' '.stripslashes($profile['username']).$trf->GetValue('order_mail','usersign',$this->lang)."\n\n";
			$mail_text.=date("d.m.Y", time())."\n";
			$mail_text.=$mail_subj."\n\n";
			$mail_text.=$trf->GetValue('order_mail','order_overallpre',$this->lang)."\n\n";
			
			$admin_text=date("d.m.Y", time())."\n";
			$admin_text.='Новый заказ на сайте '.SITETITLE.', №'.$order_id.":\n\n";
			$admin_text.='Покупатель: '.stripslashes($profile['username'])."\n";
			$admin_text.='E-mail: '.stripslashes($params['email'])."\n";
			$admin_text.='Телефон: '.stripslashes($params['phone'])."\n";
			$admin_text.='Адрес: '.stripslashes($params['address'])."\n\n";
			
			//перебираем все позиции
			$gd=new PriceItem();
			$disp=new DictNVDisp();
			
			$this->price_finder->SetArrays($this->cart);
			$items='';
			foreach($this->cart as $k=>$v){
				
				$item_params=Array();
				$item_params['good_id']=$v['good_id'];
				$item_params['quantity']=$v['quantity'];
				$item_params['comment']=$v['comment'];
				$item_params['order_id']=$order_id;
				
				$priceblock=$this->price_finder->FindPrices($v['good_id'],$this->lang);
				if(!is_array($priceblock)){
					$item_params['price_value']=0;
					$item_params['currency_id']=0;
				}else{
					$item_params['price_value']=$priceblock['value'];
					$item_params['currency_id']=$priceblock['currency_id'];
				}
				
				$good=$gd->GetItemById($v['good_id'],$this->lang);
				
				if(HAS_OST){
					$gd->EditOstatok($v['good_id'], $good['ostatok']-$v['quantity']);
				}
				
				$mail_text.='-'.stripslashes($good['name']).' '.$v['quantity'].' '.$trf->GetValue('good.php','quant_units',$this->lang).' '.$item_params['price_value'].' '.stripslashes($priceblock['currency_sign']).'/'.$trf->GetValue('good.php','quant_units',$this->lang)."\n";
				$admin_text.='-'.stripslashes($good['name']).' '.$v['quantity'].' '.$trf->GetValue('good.php','quant_units',$this->lang).' '.$item_params['price_value'].' '.stripslashes($priceblock['currency_sign']).'/'.$trf->GetValue('good.php','quant_units',$this->lang)."\n";
				
				
				$ii=new OrderItemItem();
				$item_id=$ii->Add($item_params);
				//если есть опции заказа, переберем их
				$opts='';
				foreach($v['values_arr'] as $kk=>$vv){
					$opt_params=Array();
					$opt_params['item_id']=$item_id;
					$opt_params['value_id']=$vv['value_id'];
					
					$ioi=new OrderItemOptItem();
					$ioi->Add($opt_params);
					
					$pvi=new PropValItem(); $value=$pvi->GetItemById($vv['value_id'],$this->lang);
					$pni=new PropNameItem();
					if($value!=false){
						$name=$pni->GetItemById($value['name_id'],$this->lang);
						if($name!=false) {
							$mail_text.="\t".stripslashes($name['name']).': '.stripslashes($value['name'])."\n";
							$admin_text.="\t".stripslashes($name['name']).': '.stripslashes($value['name'])."\n";
						}
					}
				}
				$mail_text.="\t".$v['comment']."\n";
				$admin_text.="\t".$v['comment']."\n";				
			}
			
			$costblock=$this->price_finder->FindCost($this->lang);
			$mail_text.="\n\n".$trf->GetValue('order_mail','sumdescr',$this->lang).' ';
			$admin_text.="\n".'Итого: ';
			
			if(!is_array($costblock)){
				$mail_text.='';	
				$admin_text.=$this->ShowError($costblock);	
			}else {
				$mail_text.=$costblock['cost'].' '.stripslashes($costblock['currency_sign']);
				$admin_text.=$costblock['cost'].' '.stripslashes($costblock['currency_sign']);
			}
			$mail_text.="\n\n".$trf->GetValue('order_mail','posttext',$this->lang);
			
			/*if(DEBUG_INFO){
				echo '<pre>'.htmlspecialchars($mail_to).'</pre>';
				echo '<pre>'.htmlspecialchars($mail_subj).'</pre>';
				echo '<pre>'.htmlspecialchars($mail_text).'</pre>';
				
				echo '<pre>'.htmlspecialchars($admin_to).'</pre>';
				echo '<pre>'.htmlspecialchars($admin_subj).'</pre>';
				echo '<pre>'.htmlspecialchars($admin_text).'</pre>';
			}*/
			
			@mail($mail_to,$mail_subj, $mail_text, "From: \"".FEEDBACK_EMAIL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/plain; charset=\"windows-1251\"\n");
			@mail($admin_to,$admin_subj, $admin_text, "From: \"".FEEDBACK_EMAIL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/plain; charset=\"windows-1251\"\n");
			
			$this->Clear();
		}
		
		return $order_id;
	}
	
	
	
	//добавление позиции
	public function Add($params){
		if($this->CheckPosition($params,11)!=0) return false;

		$this->cart[]=Array(
			'good_id' => $params['good_id'],
			'lang_id' => $params['lang_id'],
			'quantity' => $params['quantity'],
			'values_arr' => $params['values_arr'],
			'comment' => $params['comment']
		);
		$this->SaveToSession(10);
		
		$this->CompactBasket();
		$this->CorrectQuants();
		return true;
	}
	
	//удаление позиции
	public function Del($number){
		$this->LoadFromSession(10);
		$temp_arr=Array();
		$cter=0;
		foreach($this->cart as $k=>$v){
			
			$idr=$this->ConstructPrefix($v);
			if($number!=$idr){
				$temp_arr[]=$v;
			}
			
			$cter++;
		}
		unset($this->cart);	$this->cart=Array();
		$this->cart=$temp_arr;
		$this->SaveToSession();
		$this->CorrectQuants();
	}
	
	//редактировать позицию 
	public function Edit($number,$params){
		$this->LoadFromSession(11);
		$cter=0; $temp_arr=Array();
		foreach($this->cart as $k=>$v){
			
			$idr=$this->ConstructPrefix($v);
			
			if($idr==$number){
				//echo $params['comment'];
				$newv=Array(
					'good_id' => $v['good_id'],
					'lang_id' => $v['lang_id'],
					'quantity' => $params['quantity'],
					'values_arr' => $params['values_arr'],
					'comment' => $params['comment']
				);
				
				//$this->CorrectQuant($newv);
				$temp_arr[]=$newv;
			}else{
				$temp_arr[]=$v;
			}
			$cter++;
		}
		
		unset($this->cart);	$this->cart=Array();
		$this->cart=$temp_arr;
		$this->SaveToSession();
		
		$this->CompactBasket();
		$this->CorrectQuants();
	}
	
	//покажем сокращенный вариант корзины
	//smarty
	public function DrawBasketSmall($lang_id){
		//$this->LoadFromSession();
		$this->CorrectQuants();
		
		$count_items=$this->CountItems();
		$txt='';
		$trf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		if($count_items>0) {
			//есть итемы
			$smarty->assign('has_items',true);
			
			$smarty->assign('on',$trf->GetValue('basketblock.php', 'on',$lang_id));
			$smarty->assign('goods',$trf->GetValue('basketblock.php', 'goods',$lang_id));
			$smarty->assign('count',$count_items);
			
			$this->price_finder->SetArrays($this->cart);
			
			if(!is_array(Basket::$costblock)){
				$costblock=$this->price_finder->FindCost($lang_id);
			}else{
				$costblock=Basket::$costblock;
			}
			if(!is_array($costblock)){
				$smarty->assign('cost',$this->ShowError($costblock));	
			}else {
				$smarty->assign('cost',$costblock['cost'].'&nbsp;'.stripslashes($costblock['currency_sign']));	
				Basket::$costblock=$costblock;
			}
			
		}else{
			//нет итемов
			$smarty->assign('has_items',false);
			
			$smarty->assign('empty',$trf->GetValue('basketblock.php', 'empty',$lang_id));
		}
		
		$smarty->assign('yourorder',$trf->GetValue('basketblock.php', 'yourorder',$lang_id));
		$txt=$smarty->fetch($this->templates['short_basket_witems']);
		return $txt;
	}
	
	
	
	//покажем всю корзину
	public function DrawBasket($lang_id){
		$txt='';
		$temp_b=$this->SortBasket($_SESSION[$this->sess_name]);
		unset($_SESSION[$this->sess_name]);
		$_SESSION[$this->sess_name]=$temp_b;
		$this->CorrectQuants();
		
		$this->LoadFromSession(11);
		
		//перебираем все позиции
		$gd=new PriceItem();
		$disp=new DictNVDisp();
		$dicts=new DictAttDisp(3);
		
		$tmp_auth=new AuthUser();
		$profile=$tmp_auth->Auth();
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		$trf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		$smarty->assign('numbername',$trf->GetValue('basket.php', 'numbername',$lang_id));
		$smarty->assign('paramsname',$trf->GetValue('basket.php', 'paramsname',$lang_id));
		$smarty->assign('quantname',$trf->GetValue('basket.php', 'quantname',$lang_id));
		$smarty->assign('pricename',$trf->GetValue('basket.php', 'pricename',$lang_id));
		$smarty->assign('overalname',$trf->GetValue('basket.php', 'overalname',$lang_id));

		$smarty->assign('titlename',$trf->GetValue('basket.php', 'titlename',$lang_id));
		
		$rows=''; 
		$counter=1; 
		$formrules='';
		$_old_gid_=''; //это "старый" good_id, нужен при показе одной цены и стоимости для разных позиций с одним и тем же товаром
		
		//массив с уникальными товарами:
		$rows=Array();
		$this->price_finder->SetArrays($this->cart);
		foreach($this->cart as $k=>$v){
			
			$good=$gd->GetItemById($v['good_id'],$lang_id,1);
			
			//работа с ценами
			if($_old_gid_!=$v['good_id']){
				$priceblock=$this->price_finder->FindPrices($v['good_id'],$this->lang);
				if(!is_array($priceblock)){
					$price=$this->ShowError($priceblock);
				}else{ 					$price=$priceblock['value'].'&nbsp;'.stripslashes($priceblock['currency_sign']).'*'.$priceblock['quantity'].'=<strong> '.$priceblock['cost'].'&nbsp;'.stripslashes($priceblock['currency_sign']).'</strong>';
				}
			}else{
				//это тот же товар, но в следующей позиции, поэтмоу ничего не пишем
				$price='-';
			}
			
			
			$opts='';
			//если есть опции заказа, переберем их
			
			//префикс для имени тега селект= мд5(сериализация(текущая запись_корзины))+-
			$prefix=$this->ConstructPrefix($v);//md5(serialize($v)).'_';
			//это будет идентификатор записи корзины
			
			//перебор всех свойств
			$opts.=''.$dicts->DrawDictsCli(2, $v['good_id'], $lang_id,$this->templates['full_basket_options'], $this->templates['full_basket_options_item'],'',$v['values_arr'],$prefix,$counter).'';//.$buff;
			//$formrules.='frmvalidator.addValidation("'.$counter.'_quant","req","'.$trf->GetValue('good.php','err_quant_name',$lang_id).'");    ';
			//$formrules.='frmvalidator.addValidation("'.$counter.'_quant","num","'.$trf->GetValue('good.php','err_quant_name0',$lang_id).'");    ';
			//$formrules.='frmvalidator.addValidation("'.$counter.'_quant","gt=0","'.$trf->GetValue('good.php','err_quant_name0',$lang_id).'");    ';			
			
			
			$rows[]=Array(
				'prefix'=>$prefix,
				'name'=>'<a href="'.$gd->ConstructPath($v['good_id'],$lang_id,'/').'" target="_blank">'.stripslashes($good['name']).'</a>',
				'quant'=>$v['quantity'],
				'number'=>$counter,
				'commentname'=>$trf->GetValue('basket.php', 'commentname',$lang_id),
				'commentplace'=>htmlspecialchars(stripslashes($v['comment'])),
				'price'=>$price,
				'params'=>$opts
				
			);
			
			$counter++;
			$_old_gid_=$v['good_id'];
		}
		
		$smarty->assign('rows',$rows);
		//проверки всех форм
		$smarty->assign('formrules',$formrules);
		$smarty->assign('do_apply',$trf->GetValue('basket.php','do_apply',$lang_id));
		$smarty->assign('do_clear',$trf->GetValue('basket.php','do_clear',$lang_id));
		$smarty->assign('do_delete',$trf->GetValue('basket.php','do_delete',$lang_id));		
		
		
		//работаем со стоимостью
		if(!is_array(Basket::$costblock)){
			$costblock=$this->price_finder->FindCost($this->lang);
		}else{
			$costblock=Basket::$costblock;
		}
		if(!is_array($costblock)){
			$smarty->assign('overalvalue}',$this->ShowError($costblock));	
			$smarty->assign('withskidka}','');	
		}else{ 
			Basket::$costblock=$costblock;
			if($profile!==NULL){
				
				$smarty->assign('overalvalue',$costblock['cost'].'&nbsp;'.stripslashes($costblock['currency_sign']));	
				if($profile['skidka']>0) $smarty->assign('withskidka','<strong>'.$trf->GetValue('basket.php', 'overalname_skidka',$lang_id).'&nbsp;'.sprintf("%.0f",$profile['skidka']).'%: '.sprintf("%.2f",($costblock['cost']-$costblock['cost']*$profile['skidka']/100)).'&nbsp;'.stripslashes($costblock['currency_sign']).'</strong><p>');	
				else $smarty->assign('withskidka','');
			}else{
				//нету скидки
				$smarty->assign('overalvalue',$costblock['cost'].'&nbsp;'.stripslashes($costblock['currency_sign']));	
				$smarty->assign('withskidka','');	
			}
		}
		
		$formrules2='';
		//проверка авторизации
		if($profile===NULL){
			//не авторизован
			
			$smarty->assign('auth',false);
			$smarty->assign('not_reg_msg',$trf->GetValue('basket.php','not_reg_msg',$lang_id));
			$smarty->assign('do_reg_msg',$trf->GetValue('basket.php','do_reg_msg',$lang_id));
		}else{
			//авторизован, покажем форму отправки заказа
			
			$smarty->assign('auth',true);
			$smarty->assign('send_order_title',$trf->GetValue('basket.php','send_order_title',$lang_id));
		$smarty->assign('greetings',stripslashes($profile['username']).$trf->GetValue('basket.php','greetings',$lang_id));
			$smarty->assign('phone_prompt',$trf->GetValue('basket.php','phone_prompt',$lang_id));
			$smarty->assign('email_prompt',$trf->GetValue('basket.php','email_prompt',$lang_id));
			$smarty->assign('addr_prompt',$trf->GetValue('basket.php','addr_prompt',$lang_id));
			$smarty->assign('send_order_caption',$trf->GetValue('basket.php','send_order_caption',$lang_id));
			
			$smarty->assign('phone_value',stripslashes($profile['phone']));
			$smarty->assign('email_value',stripslashes($profile['email']));
			$smarty->assign('addr_value',stripslashes($profile['address']));
			
			
			//добавим правила для валидации формы данных пользователя
			$formrules2.='frmvalidatorr.addValidation("email","req","'.$trf->GetValue('profile.php','email_error',$lang_id).'");';	
			$formrules2.='frmvalidatorr.addValidation("email","email","'.$trf->GetValue('profile.php','email_error',$lang_id).'");';	
			$formrules2.='frmvalidatorr.addValidation("address","req","'.$trf->GetValue('profile.php','address_error',$lang_id).'");';	
		$formrules2.='frmvalidatorr.addValidation("address","minlen=10","'.$trf->GetValue('profile.php','address_error',$lang_id).'");';	
		}
		//проверки всех форм
		$smarty->assign('formrules2',$formrules2);
		if($counter>1) $txt.= $smarty->fetch($this->templates['full_basket_table']);  //$tpl1->template;
		else $txt=$trf->GetValue('basket.php','basket_empty',$lang_id);
		
		return $txt;
		//return '<pre>'.htmlspecialchars($txt).'</pre>';
	}
	
	
	
	//подсчет числа единиц товара
	public function CountItems(){
		$cou=0;
		$this->LoadFromSession(9);
		foreach($this->cart as $k=>$v){
			$cou+=$v['quantity'];
		}
		return $cou;
	}
	
	//получение записи корзины по номеру
	public function GetItemByNo($number){
		$r=NULL;
		$this->LoadFromSession(9);
		$cter=0; 
		foreach($this->cart as $k=>$v){
			if($cter==$number){
				$r=$v; break;
			}
			$cter++;
		}
		return $r;
	}
	
	//конструирование префикса
	public function ConstructPrefix($v){
		return md5(serialize($v)).'_';
	}
	
	//сортировка корзины по коду товара методом пузырька
	public function SortBasket($cart){
		$len=count($cart); 
		for($i=0; $i<$len; $i++){
			 for( $j = $len-1; $j > $i; $j-- ) {     // внутренний цикл прохода
      			
				$minus_item=$cart[$j-1]; $plus_item=$cart[$j];
				if($minus_item['good_id']>$plus_item['good_id']){
					$old=$minus_item; $minus_item=$plus_item; $plus_item=$old;
					$cart[$j-1]=$minus_item; $cart[$j]=$plus_item;
				}
			}
		}
		
		return $cart;
	}
	
	//очистка сессии
	public function Clear(){
		unset($this->cart); unset($_SESSION[$this->sess_name]);
		
		$_SESSION[$this->sess_name]=Array();
		$this->cart=Array();
	}
	
//****************************************** СЛУЖЕБНЫЕ МЕТОДЫ *************************************************
	//загрузка сессии во внутренний массив
	protected function LoadFromSession($depth=10){
		unset($this->cart);  $this->cart=Array();
		if(!isset($_SESSION[$this->sess_name])) $_SESSION[$this->sess_name]=Array();
		
		
		foreach($_SESSION[$this->sess_name] as $k=>$v){
			if($this->CheckPosition($v,$depth)==0) $this->cart[]=$v;
		}
	}
	
	//выгрузка внутреннего массива в сессию
	protected function SaveToSession($depth=10){
		unset($_SESSION[$this->sess_name]); $_SESSION[$this->sess_name]=Array();
		foreach($this->cart as $k=>$v){
			if($this->CheckPosition($v,$depth)==0) $_SESSION[$this->sess_name][]=$v;
		}
	}
	
	//объединение в одну позицию позиций с совершенно одинаковыми полями (кроме кол-ва)
	protected function CompactBasket(){
		$this->LoadFromSession(1);
		$temparr=Array();
		foreach($this->cart as $k=>$v){
			foreach($this->cart as $kk=>$vv){
				if($k!=$kk){
					if(($v['good_id']==$vv['good_id'])/*&&($v['lang_id']==$vv['lang_id'])*/&&($v['values_arr']==$vv['values_arr'])&&($v['comment']==$vv['comment'])){
					//тотальное равенство записей
						unset($this->cart[$k]);
						unset($this->cart[$kk]);
						
						$newrecord=Array(
							'good_id' => $v['good_id'],
							'lang_id' => $v['lang_id'],
							'quantity' => ((int)$v['quantity']+(int)$vv['quantity']),
							'values_arr' => $v['values_arr'],
							'comment' => $v['comment']);
						//$this->CorrectQuant($newrecord);
						$this->cart[$kk]=$newrecord;
						
					}else{
						//записи не равны
					}
				}
			}
		}
				
		$this->SaveToSession(11);
	}
	
	
	
	//проверка на корректность позиции
	//проверять товар, код языка
	public function CheckPosition($v,$depth=10){
		$err_code=0; 
		//проверка, отключен ли магазин на сайте
		if((!HAS_PRICE)&&(!HAS_BASKET)){
			$err_code=7; //
			return $err_code;
		}
		
		
		//код языка
		if($depth>=11){
			if(isset($v['lang_id'])){
				$li=new LangItem();
				$lang=$li->GetItemById($v['lang_id']);
				if($lang==false){
					$err_code=2; //нет такого языка
					return $err_code;
				}
			}else{
				 $err_code=4; //неверная структура записи корзины
				 return $err_code;
			}
		}
		
		//код товара
		if(isset($v['good_id'])){
			$pi=new PriceItem();
			$good=$pi->GetItemById($v['good_id'],$this->lang,1);
			if($good==false){
				$err_code=1; //нет такого товара
				return $err_code;
			}
		}else{
			 $err_code=4; //неверная структура записи корзины
			 return $err_code;
		}
		
		
		//проверить, есть ли в разделе, где товар, каталог товаров, и можно ли заказывать (администратор мог отключить)
		$mi=new MmenuItem();
		$mm=$mi->GetItemById($good['mid'],$this->lang,1);
		if($mm==false){
			$err_code=5; //нет такого раздела
			return $err_code;
		}
		if(($mm['is_price']!=1)&&($mm['is_basket']!=1)){
			$err_code=6; //в разделе, из которого заказан товар, нет каталога товаров';	
			return $err_code;
		}
		
		if($depth>=10){
			if(isset($v['values_arr'])){
				foreach($v['values_arr'] as $kk=>$vv){
					//echo " <strong>$kk=>$vv</strong> ";
					$err_code1=$this->CheckOption($vv,$v);
					if($err_code1!=0) unset($v['values_arr'][$kk]);//return $err_code;
				}
			}else{
				 $err_code=4; //неверная структура записи корзины
				 return $err_code;
			}
		}
		
		return $err_code;
	}
	
	
	//проверка корректности опции
	public function CheckOption($option,$v){
		$err_code=0;
		//и, наконец, всевозможные проверки корректности выбранного значения опции
		$disp=new DictNVDisp();
		$err_code=$disp->CheckPropertyByValId($returned_good_id,$returned_name_id,$option['value_id'],$this->lang);
		if($returned_good_id!=$v['good_id']){
			$err_code=8; //нет такого значения
			return $err_code;
		}
		
		return $err_code;
	} 
	
	//получим уникальные товары
	protected function GetUniqs(){
		$uniqs=Array();
		
		$this->LoadFromSession();
		$pi=new PriceItem;
		foreach($this->cart as $k=>$v){
			if(!isset($uniqs[$v['good_id']])){
				$good=$pi->GetItemById($v['good_id'], $this->lang, 1);
								
				$uniqs[$v['good_id']]=Array('ostatok'=>$good['ostatok'], 'summa'=>0);
			}
		}
		
		return $uniqs;
	}
	
	//общая коррекция товаров корзины по остаткам
	public function CorrectQuants(){
		if(HAS_OST){
			$uniqs=$this->GetUniqs();
			$this->LoadFromSession();
			foreach($this->cart as $k=>$v){
				/*ищем такой товар в униках
								
				если остаток равен нулю - кол-во=0
				если остаток больше нуля:
					сравниваем сумму и остаток
					если сумма >= остатку
						кол-во=0
					если сумма < остатка
						проверим (сумма+ количество)? остаток:
						если (сумма+ количество)> остаток
							кол-во:= остаток-сумма
						если (сумма+ количество)<=остаток
							кол-во = кол-во
						сумма:= сумма+ кол-во
				*/
				$uni=Array(); $uni=$uniqs[$v['good_id']];
				if($uni['ostatok']<=0){
					$v['quantity']=0;
				}else{
					if($uni['summa']>=$uni['ostatok']){
						$v['quantity']=0;
					}else{
						if( ($uni['summa']+$v['quantity'])>$uni['ostatok']){
							$v['quantity']=$uni['ostatok']-$uni['summa'];
						}else{
							
						}
						$uni['summa']+=$v['quantity'];
						unset($uniqs[$v['good_id']]);
						$uniqs[$v['good_id']]=$uni;
					}
				}
				unset($this->cart[$k]);
				$this->cart[$k]=$v;
				
			}
			$this->SaveToSession();
		}
	}
	
	
	//вывод сообщения об ошибке по коду ошибки
	protected function ShowError($err_code){
		if($this->show_errors) return $this->err_mess[$err_code];
		else return '';
	}
	
	//установка флага показа ошибок
	public function SetShowErrors($flag){
		$this->show_errors=$flag;
	}
}
?>
