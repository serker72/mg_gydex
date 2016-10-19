<?
require_once('abstractgroup.php');
require_once('orderitem.php');
require_once('statusgroup.php');
require_once('statusgroup.php');
require_once('smarty/SmartyAdm.class.php');
// группа заказов
class OrdersGroup extends AbstractGroup {
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='orders';
		$this->pagename='vieworders.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	//список итемов
	public function GetItems($sortmode,$sortparams=NULL,$from=0,$to_page=ITEMS_PER_PAGE){
		//список позиций
		$txt='';
		
		$res_fields = 'distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka';
		
		if($sortmode==5){
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.id="'.$sortparams['order_id'].'" order by gr.pdate desc, gr.status_id, gr.id desc ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.id="'.$sortparams['order_id'].'"';
		}else if($sortmode==4){
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.clid="'.$sortparams['clid'].'" order by gr.pdate desc, gr.status_id, gr.id desc ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.clid="'.$sortparams['clid'].'"';
		}else if($sortmode==3){
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.pdate="'.$sortparams['pdate'].'" order by gr.pdate desc, gr.status_id, gr.id desc ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.pdate="'.$sortparams['pdate'].'"';
		}else if($sortmode==2){
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.status_id="5" order by gr.pdate desc, gr.status_id, gr.id desc  ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.status_id="5"';
		}else if($sortmode==1){
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where (gr.status_id="2" or gr.status_id="3" or gr.status_id="4") order by gr.pdate desc, gr.status_id, gr.id desc  ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where (gr.status_id="2" or gr.status_id="3" or gr.status_id="4")';
		}else{
			$sql='select '.$res_fields.' from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.status_id="1" order by gr.pdate desc, gr.status_id, gr.id desc  ';
			$sql_count='select count(distinct gr.id) from 
			(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
			LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id='.LANG_CODE.')) 
			LEFT JOIN clients as cl ON gr.clid=cl.id where gr.status_id="1"';
		
		}
		
		$set=new mysqlSet($sql,$to_page,$from,$sql_count);
		$rc=$set->GetResultNumRows();
		$totalcount=$set->GetResultNumRowsUnf();
		$rs=$set->GetResult();
		//echo $rc;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_order.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$smarty->assign('sortmode',$sortmode);
		$srt_str='';
		if($sortparams===NULL){
			$smarty->assign('sortparamname','some');
			$smarty->assign('sortparamvalue','0');			
		}else {
			foreach($sortparams as $k=>$v){
				$smarty->assign('sortparamname',$k);
				$smarty->assign('sortparamvalue',$v);				
				$srt_str.="&$k=$v";
			}
		}
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&sortmode='.$sortmode.$srt_str);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		$strs='';
		for($i=0;$i<$rc;$i++){
			//distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka
			$f=mysqli_fetch_array($rs);
			
			if($f[10]==1) $is_new=true; else $is_new=false;
			
			//язык заказа
			$lio=new LangItem();
			$lango=$lio->GetItemById($f[11]);
			if($lango!=false){
				$langdata='<img src="/'.stripslashes($lango['lang_flag']).'" alt="'.stripslashes($lango['lang_name']).'" border="0">';
			}else $langdata='<em>язык не определен</em>';
			
			//статус заказа
			$sg=new StatusGroup();
			if($f[2]===NULL) $statuses=$sg->GetItemsOptByLang_id(0,'name',LANG_CODE,'-не определен-');
			else  $statuses=$sg->GetItemsOptByLang_id($f[10],'name',LANG_CODE,'-не определен-');
			
			
			//работаем со стоимостями f[12]
			$oi=new OrderItem();
			$cost_as_ordered = $oi->CalcCostAsOrdered($f[0],$f[11],$f[12]);
			$cost_as_is = $oi->CalcCostAsIs($f[0],$f[11],$f[12]);
			
			//язык пользователя
			$liu=new LangItem();
			$langu=$liu->GetItemById($f[6]);
			if($langu!=false){
				$u_ld='<img src="/'.stripslashes($langu['lang_flag']).'" alt="'.stripslashes($langu['lang_name']).'" border="0">';
			}else $u_ld='<em>язык не определен</em>';
			
			$alls[]=Array('itemno'=>$f[0],
			'is_new'=>$is_new,
			'pdate'=>DateFromYmd($f[1]),
			'langdata'=>$langdata,
			'statuses'=>$statuses,
			'cost_by_positions'=>FormatPrice($cost_as_ordered['cost'],' '.stripslashes($cost_as_ordered['currency_sign']),2),
			'cost_by_base'=>FormatPrice($cost_as_is['cost'],' '.stripslashes($cost_as_is['currency_sign']),2),
			'userid'=>$f[3],
			'username'=>stripslashes($f[5]),
			'userlogin'=>stripslashes($f[4]),
			'email'=>stripslashes($f[8]),
			'phone'=>stripslashes($f[7]),
			'address'=>stripslashes($f[9]),
			'user_langdata'=>$u_ld
			);
		}
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		if($rc>0) $txt.=$smarty->fetch($this->all_menu_template); //Выводим нашу страничку
		else $txt='<em>заказов не найдено!</em>';
		
		return $txt;
	}
	
	
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10, $lang_id=LANG_CODE){
		//список позиций
		$txt='';
		
		
		
		return $txt;
	}
	
	
	//подсчет общего количества заказов покупателя
	public function CalcItemsByClid($clid){
		$sql='select count(*) from '.$this->tablename.' where clid="'.$clid.'"';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f['count(*)'];
		}else return 0;
	}
	
	
	//подсчет общего количества заказов по видам
	public function CalcItemsByMode($status_mode){
		if($status_mode==1) $sql='select count(*) from '.$this->tablename.' where ( status_id="2" or status_id="3" or status_id="4")';
		else if($status_mode==2) $sql='select count(*) from '.$this->tablename.' where status_id="5"';
		else $sql='select count(*) from '.$this->tablename.' where status_id="1"';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f['count(*)'];
		}else return 0;
	}
	
	
	//подсчет общего количества заказов по дате
	public function CalcItemsByDate($pdate,$status_mode){
		if($status_mode==1) $sql='select count(*) from '.$this->tablename.' where pdate="'.$pdate.'" and( status_id="2" or status_id="3" or status_id="4")';
		else if($status_mode==2) $sql='select count(*) from '.$this->tablename.' where pdate="'.$pdate.'" and status_id="5"';
		else $sql='select count(*) from '.$this->tablename.' where pdate="'.$pdate.'" and status_id="1"';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f['count(*)'];
		}else return 0;
	}
	
	
///****************************	КЛИЕНСТКИЙ ВЫВОД ***********************************************************

	//список заказов клиентский
	public function GetItemsCliById($id, $from=0,$to_page=10, $lang_id=LANG_CODE,$inner=Array()){
		$txt='';
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$res_fields = 'distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka';
		
		$sql='select '.$res_fields.' from 
		(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id) 
		LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id=gr.lang_id)) 
		LEFT JOIN clients as cl ON gr.clid=cl.id where gr.clid="'.$id.'" order by gr.pdate desc, gr.status_id, gr.id desc  ';
		$sql_count='select count(distinct gr.id) from 
		(('.$this->tablename.' as gr LEFT JOIN order_status as st ON  gr.status_id=st.id ) 
		LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id=gr.lang_id)) 
		LEFT JOIN clients as cl ON gr.clid=cl.id where gr.clid="'.$id.'"';
		
		//echo $sql;
		$set=new mysqlSet($sql,$to_page,$from,$sql_count);
		$rc=$set->GetResultNumRows();
		$totalcount=$set->GetResultNumRowsUnf();
		$rs=$set->GetResult();
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		$smarty->assign('topage',$to_page);
		$smarty->assign('order_no',0);
		$smarty->assign('razb_capt',$rf->GetValue('vieworders.php','razb_capt',$lang_id));
		$smarty->assign('razb_what',$rf->GetValue('vieworders.php','razb_what',$lang_id));
		$smarty->assign('has_razb',true);
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$strs=''; $formrules='';
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			//distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka
			$f=mysqli_fetch_array($rs);
			
			if($f[10]!=1) {
				$blocked=true;
			}
			else {
				$blocked=false;
			}
			
			//работаем со стоимостями f[12]
			$oi=new OrderItem();
			//по базе ???
			$cost_as_is = $oi->CalcCostAsIs($f[0],$f[11],$f[12]);
			//$parse_item->set_tpl('{itogo}',$rf->GetValue('vieworders.php','itogo_capt',$lang_id).' '.FormatPrice($cost_as_is['cost'],' '.stripslashes($cost_as_is['currency_sign']),2));
			//как заказано
			//$cost_as_ordered = $oi->CalcCostAsOrdered($f[0],$f[11],$f[12]);
			
			//skidka
			if($f[12]>0) $skidka=$rf->GetValue('vieworders.php','skidka_capt',$lang_id).' '.sprintf("%.0f",$f[12]).'%';
			else $skidka='';
			
			$itm='';
			$oggs=new OrderItemsGroup();
			$oggs->SetTemplates($inner['all_menu_template'], $inner['menuitem_template'], $inner['menuitem_template_blocked'], $inner['razbivka_template'],$inner['items_set'],$inner['item_row']);
			$itm=$oggs->GetItemsCliById($f[0], $f[11], $f[3], $f[12], $blocked);
			
			/*
$formrules.='frmvalidator.addValidation("'.$f[0].'_email","req","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','email_error',$lang_id).'");';
$formrules.='frmvalidator.addValidation("'.$f[0].'_email","email","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','email_error',$lang_id).'");';
$formrules.='frmvalidator.addValidation("'.$f[0].'_address","minlen=10","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','address_error',$lang_id).'");';
			*/
			
			if($f[2]!==NULL) $status=stripslashes($f[2]);
			else $status=$rf->GetValue('vieworders.php','status_undefined',$lang_id);
			
			$alls[]=Array(
				'is_blocked'=>$blocked,
				'email'=>htmlspecialchars(stripslashes($f[8])),
				'phone'=>htmlspecialchars(stripslashes($f[7])),
				'address'=>htmlspecialchars(stripslashes($f[9])),
				'orderno'=>$f[0],
				'status'=>$status,
				'date'=>DateFromYmd($f[1]),
				'itogo'=>$rf->GetValue('vieworders.php','itogo_capt',$lang_id).' '.FormatPrice($cost_as_is['cost'],'&nbsp;'.stripslashes($cost_as_is['currency_sign']),2),
				'skidka'=>$skidka,
				'pos'=>$itm
			);
		}
		
		$smarty->assign('change_caption',$rf->GetValue('vieworders.php','change_caption',$lang_id));
		$smarty->assign('ordertitle',$rf->GetValue('vieworders.php','ordertitle',$lang_id));
		$smarty->assign('good_title',$rf->GetValue('vieworders.php','good_title',$lang_id));
		$smarty->assign('price_title',$rf->GetValue('vieworders.php','price_title',$lang_id));
		$smarty->assign('quant_title',$rf->GetValue('vieworders.php','quant_title',$lang_id));
		$smarty->assign('cost_title',$rf->GetValue('vieworders.php','cost_title',$lang_id));
		$smarty->assign('email_caption',$rf->GetValue('vieworders.php','email_caption',$lang_id));
		$smarty->assign('phone_caption',$rf->GetValue('vieworders.php','phone_caption',$lang_id));
		$smarty->assign('address_caption',$rf->GetValue('vieworders.php','address_caption',$lang_id));
		
		$smarty->assign('orders',$alls);
		$smarty->assign('pages',$pages);
		$smarty->assign('formrules',$formrules);
		$smarty->assign('change_marked',$rf->GetValue('vieworders.php','change_marked',$lang_id));
		$smarty->assign('del_marked',$rf->GetValue('vieworders.php','del_marked',$lang_id));
		$smarty->assign('confirm_prompt',$rf->GetValue('vieworders.php','confirm_prompt',$lang_id));
		$smarty->assign('confirm_delete_prompt',$rf->GetValue('vieworders.php','confirm_delete_prompt',$lang_id));
		$smarty->assign('cancel_caption',$rf->GetValue('vieworders.php','cancel_caption',$lang_id));
		
		if($rc>0) $txt.=$smarty->fetch($this->all_menu_template); //Выводим нашу страничку
		else $txt=$rf->GetValue('vieworders.php','no_orders',$lang_id);
		return $txt;
	}
	
	
	
	
	//прроверка статуса заказа по его номеру
	public function CheckOrderCliById($user_id, $order_id, $lang_id=LANG_CODE,$inner=Array()){
		//список позиций
		$txt='';
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$res_fields = 'distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka';
		
		$sql='select '.$res_fields.' from 
		(('.$this->tablename.' as gr LEFT JOIN order_status as st ON gr.status_id=st.id ) 
		LEFT JOIN order_status_lang as stl ON (st.id=stl.status_id and stl.lang_id=gr.lang_id)) 
		LEFT JOIN clients as cl ON gr.clid=cl.id where gr.clid="'.$user_id.'" and gr.id="'.$order_id.'" order by gr.pdate desc, gr.status_id, gr.id desc  ';
		
		
		//echo $sql;
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$totalcount=$set->GetResultNumRowsUnf();
		$rs=$set->GetResult();
		
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		$smarty->assign('has_razb',false);
		$smarty->assign('from',0);
		$smarty->assign('topage',5);
		
		$razbivka='';
		$alls=Array();
		$strs=''; $formrules='';
		for($i=0;$i<$rc;$i++){
			//distinct gr.id, gr.pdate, stl.name, cl.id, cl.login, cl.username, cl.lang_id, gr.phone, gr.email, gr.address, gr.status_id, gr.lang_id, cl.skidka
			$f=mysqli_fetch_array($rs);
			if($f[10]!=1) {
				$blocked=true;
			}
			else {
				$blocked=false;
			}
			
			//работаем со стоимостями f[12]
			$oi=new OrderItem();
			$cost_as_is = $oi->CalcCostAsIs($f[0],$f[11],$f[12]);
			//$parse_item->set_tpl('{itogo}',$rf->GetValue('vieworders.php','itogo_capt',$lang_id).' '.FormatPrice($cost_as_is['cost'],' '.stripslashes($cost_as_is['currency_sign']),2));
			
			//skidka
			if($f[12]>0) $skidka=$rf->GetValue('vieworders.php','skidka_capt',$lang_id).' '.sprintf("%.0f",$f[12]).'%';
			else $skidka='';	
			
			$itm='';
			$oggs=new OrderItemsGroup();
			$oggs->SetTemplates($inner['all_menu_template'], $inner['menuitem_template'], $inner['menuitem_template_blocked'], $inner['razbivka_template'],$inner['items_set'],$inner['item_row']);
			$itm=$oggs->GetItemsCliById($f[0], $f[11], $f[3], $f[12], $blocked);
			
			/*
$formrules.='frmvalidator.addValidation("'.$f[0].'_email","req","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','email_error',$lang_id).'");';
$formrules.='frmvalidator.addValidation("'.$f[0].'_email","email","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','email_error',$lang_id).'");';
$formrules.='frmvalidator.addValidation("'.$f[0].'_address","minlen=10","'.$rf->GetValue('vieworders.php','ordertitle',$lang_id).' '.$f[0].': '.$rf->GetValue('vieworders.php','address_error',$lang_id).'");';
			*/
			
			if($f[2]!==NULL) $status=stripslashes($f[2]);
			else $status=$rf->GetValue('vieworders.php','status_undefined',$lang_id);
			
			$alls[]=Array(
				'is_blocked'=>$blocked,
				'email'=>htmlspecialchars(stripslashes($f[8])),
				'phone'=>htmlspecialchars(stripslashes($f[7])),
				'address'=>htmlspecialchars(stripslashes($f[9])),
				'orderno'=>$f[0],
				'status'=>$status,
				'date'=>DateFromYmd($f[1]),
				'itogo'=>$rf->GetValue('vieworders.php','itogo_capt',$lang_id).' '.FormatPrice($cost_as_is['cost'],'&nbsp;'.stripslashes($cost_as_is['currency_sign']),2),
				'skidka'=>$skidka,
				'pos'=>$itm
			);
		}
		
		$smarty->assign('change_caption',$rf->GetValue('vieworders.php','change_caption',$lang_id));
		$smarty->assign('ordertitle',$rf->GetValue('vieworders.php','ordertitle',$lang_id));
		$smarty->assign('good_title',$rf->GetValue('vieworders.php','good_title',$lang_id));
		$smarty->assign('price_title',$rf->GetValue('vieworders.php','price_title',$lang_id));
		$smarty->assign('quant_title',$rf->GetValue('vieworders.php','quant_title',$lang_id));
		$smarty->assign('cost_title',$rf->GetValue('vieworders.php','cost_title',$lang_id));
		$smarty->assign('email_caption',$rf->GetValue('vieworders.php','email_caption',$lang_id));
		$smarty->assign('phone_caption',$rf->GetValue('vieworders.php','phone_caption',$lang_id));
		$smarty->assign('address_caption',$rf->GetValue('vieworders.php','address_caption',$lang_id));
		
		$smarty->assign('orders',$alls);
		$smarty->assign('pages','');
		$smarty->assign('formrules',$formrules);
		$smarty->assign('change_marked',$rf->GetValue('vieworders.php','change_marked',$lang_id));
		$smarty->assign('del_marked',$rf->GetValue('vieworders.php','del_marked',$lang_id));
		$smarty->assign('confirm_prompt',$rf->GetValue('vieworders.php','confirm_prompt',$lang_id));
		$smarty->assign('confirm_delete_prompt',$rf->GetValue('vieworders.php','confirm_delete_prompt',$lang_id));
		$smarty->assign('cancel_caption',$rf->GetValue('vieworders.php','cancel_caption',$lang_id));
		
		$smarty->assign('order_no',$order_id);
		
		if($rc>0) $txt.=$smarty->fetch($this->all_menu_template); //Выводим нашу страничку
		else $txt=$rf->GetValue('vieworders.php','no_orders',$lang_id);
		return $txt;
	}
	
	
	
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template){
		$this->all_menu_template=$all_menu_template;
		$this->menuitem_template=$menuitem_template;	
		$this->menuitem_template_blocked=$menuitem_template_blocked;
		$this->razbivka_template=$razbivka_template;
		
		
		return true;
	}
	
	
	
	
	
	protected function GenerateSQL($params){
		$sql='';
		return $sql;
	}
	
	
	
	
}
?>