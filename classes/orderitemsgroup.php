<?
require_once('abstractgroup.php');
require_once('orderitemitem.php');
require_once('priceitem.php');
require_once('useritem.php');
require_once('dictattdisp.php');
require_once('orderitemoptgroup.php');
require_once('dictnvdisp.php');
require_once('currencygroup.php');

//группа позиций заказа
class OrderItemsGroup extends AbstractGroup {
	protected $item_row;
	protected $items_set;
	
	//установка всех имен
	protected function init(){
		$this->tablename='order_item';
		$this->pagename='vieworderitems.php';		
		$this->subkeyname='order_id';	
		$this->vis_name='is_shown';		
	}
	
	
	
	//список позиций заказа
	public function GetItemsById($id, $from=0,$to_page=10, $lang_id=LANG_CODE,$user_id=0){
		//список позиций
		$txt='';
		
		//polu4im uzera i ego skidku
		$u=new UserItem();
		$user=$u->GetItemById($user_id);
		if($user!=false){
			$user_skidka=$user['skidka'];
		}else $user_skidka=0;
		
		$r_str=' distinct i.id, i.price_value, i.quantity, i.comment, g.id, gl.name, curl.signat, cur.id ';
		$sql='select '.$r_str.'
		from 
		((('.$this->tablename.' as i LEFT JOIN price_item as g ON i.good_id=g.id)
		LEFT JOIN price_lang as gl on (g.id=gl.price_id and gl.lang_id="'.$lang_id.'"))
		LEFT JOIN currency as cur ON i.currency_id=cur.id)
		LEFT JOIN currency_lang as curl ON (cur.id=curl.value_id and curl.lang_id="'.$lang_id.'")
		where i.order_id="'.$id.'" 
		order by gl.name
		';
		$sql_count='select count(distinct i.id)
		from 
		((('.$this->tablename.' as i LEFT JOIN price_item as g ON i.good_id=g.id)
		LEFT JOIN price_lang as gl on (g.id=gl.price_id and gl.lang_id="'.$lang_id.'"))
		LEFT JOIN currency as cur ON i.currency_id=cur.id)
		LEFT JOIN currency_lang as curl ON (cur.id=curl.value_id and curl.lang_id="'.$lang_id.'")
		where i.order_id="'.$id.'" 
		';
		
		$set=new mysqlSet($sql,$to_page, $from, $sql_count);
		$rc=$set->GetResultNumRows();
		$totalcount=$set->GetResultNumRowsUnf();
		$rs=$set->GetResult();
		//echo $rc;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('orderno',$id);
		$smarty->assign('listpagename',$this->pagename);
		
		
		
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&id='.$id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$strs='';
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//distinct i.id, i.price_value, i.quantity, i.comment, g.id, gl.name, curl.signat, cur.id 
			
			//работаем с товаром
			if($f[4]===NULL){
				$goodname='товар не найден в базе данных!';
				//опции
				$options=''; //$parse_item->set_tpl('{options}', '');
			}else{
				$goodname=stripslashes($f[5]);
				//опции
				$oog=new OrderItemOptGroup();
				//перебор всех свойств
				//перебор только выбранных свойств: $disp->DrawNameValsByValIdGoodId($vv['value_id'],$v['good_id'],$lang_id);
				$opts=$oog->GetOrderItemOptions($f[4],$lang_id,$this->items_set,$this->item_row,$f[0],'');
				$options=$opts;
			}
			
			//цены
			//на момент заказа
			//$parse_item->set_tpl('{price_by_positions_value}', FormatPrice($f[1], '', 2) ); 
			$cur_g=new CurrencyGroup();
			//$parse_item->set_tpl('{currs}', $cur_g->GetItemsOptByLang_id($f[7],'signat',$lang_id) ); 
			//$parse_item->set_tpl('{price_by_positions}', '*'.$f[2].'= <strong>'.FormatPrice(($f[1]*$f[2]), ' '.stripslashes($f[6]), 2).'</strong>'   ); //$f[1]);
			//$parse_item->set_tpl('{price_by_positions_curr_id}', $f[7]  );
			
			
			//актуальная цена - используем прайсфайндер
			
			$itm=new OrderItemItem();
			$price_by_base=$itm->FindPrice($f[4],$lang_id,$id,$user_skidka);
			//$parse_item->set_tpl('{price_by_base}', FormatPrice($price_by_base['value'], '&nbsp;'.stripslashes($price_by_base['currency_sign']), 2).'*'.$f[2].'= <strong>'.FormatPrice(($price_by_base['value']*$f[2]), ' '.stripslashes($price_by_base['currency_sign']), 2).'</strong>' );
			//$parse_item->set_tpl('{price_by_base_value}', FormatPrice($price_by_base['value'], '', 2) );
			//$parse_item->set_tpl('{price_by_base_curr_id}',$price_by_base['currency_id'] );
			
			$alls[]=Array(
			'itemno'=>$f[0],
			'goodid'=>$f[4],
			'quantity'=>$f[2],
			'goodname'=>$goodname,
			'options'=>$options,
			'comment'=>htmlspecialchars(stripslashes($f[3])),
			'price_by_positions_value'=>FormatPrice($f[1], '', 2),
			'currs'=>$cur_g->GetItemsOptByLang_id($f[7],'signat',$lang_id),
			'price_by_positions'=>'*'.$f[2].'= <strong>'.FormatPrice(($f[1]*$f[2]), ' '.stripslashes($f[6]), 2).'</strong>',
			'price_by_positions_curr_id'=>$f[7],
			'price_by_base'=> FormatPrice($price_by_base['value'], '&nbsp;'.stripslashes($price_by_base['currency_sign']), 2).'*'.$f[2].'= <strong>'.FormatPrice(($price_by_base['value']*$f[2]), ' '.stripslashes($price_by_base['currency_sign']), 2).'</strong>',
			'price_by_base_value'=>FormatPrice($price_by_base['value'], '', 2),
			'price_by_base_curr_id'=>$price_by_base['currency_id']
			);
		}
		//итого считаем 2х видов
		$itogo_txt='';
		$oi=new OrderItem();
		$cost_as_ordered = $oi->CalcCostAsOrdered($id,$lang_id,0);
		$itogo_txt.='Итого на момент заказа: '.FormatPrice($cost_as_ordered['cost'],' '.stripslashes($cost_as_ordered['currency_sign']),2).'<br>';
		$cost_as_is = $oi->CalcCostAsIs($id,$lang_id,0);
		$itogo_txt.='Итого по актуальным ценам: '.FormatPrice($cost_as_is['cost'],' '.stripslashes($cost_as_is['currency_sign']),2).'<p>';
		if($user_skidka>0){
			$itogo_txt.='Скидка '.$user_skidka.'%<br>';
			$cost_as_ordered_sk = $oi->CalcCostAsOrdered($id,$lang_id,$user_skidka);
			$itogo_txt.='Итого на момент заказа со скидкой: '.FormatPrice($cost_as_ordered_sk['cost'],' '.stripslashes($cost_as_ordered_sk['currency_sign']),2).'<br>';
			$cost_as_is_sk = $oi->CalcCostAsIs($id,$lang_id,$user_skidka);
			$itogo_txt.='Итого по актуальным ценам cо скидкой: '.FormatPrice($cost_as_is_sk['cost'],' '.stripslashes($cost_as_is_sk['currency_sign']),2).'<p>';
			
		}
		
		$smarty->assign('itogo',$itogo_txt);
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		if($rc>0) $txt=$smarty->fetch($this->all_menu_template);
		else $txt='<em>позиций не найдено!</em>';
		
		return $txt;
	}
	
	
	
	
	
	//клиентский список позиций заказа
	public function GetItemsCliById($id, $lang_id=LANG_CODE,$user_id=0, $user_skidka=0, $blocked=true){
		//список позиций
		$txt=''; 
		$dispnv=new DictNVDisp();
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$r_str=' distinct i.id, i.price_value, i.quantity, i.comment, g.id, gl.name, curl.signat, cur.id ';
		$sql='select '.$r_str.'
		from 
		((('.$this->tablename.' as i LEFT JOIN price_item as g ON i.good_id=g.id)
		LEFT JOIN price_lang as gl on (g.id=gl.price_id and gl.lang_id="'.$lang_id.'"))
		LEFT JOIN currency as cur ON i.currency_id=cur.id)
		LEFT JOIN currency_lang as curl ON (cur.id=curl.value_id and curl.lang_id="'.$lang_id.'")
		where i.order_id="'.$id.'" 
		order by gl.name
		';
		$sql_count='select count(distinct i.id)
		from 
		((('.$this->tablename.' as i LEFT JOIN price_item as g ON i.good_id=g.id)
		LEFT JOIN price_lang as gl on (g.id=gl.price_id and gl.lang_id="'.$lang_id.'"))
		LEFT JOIN currency as cur ON i.currency_id=cur.id)
		LEFT JOIN currency_lang as curl ON (cur.id=curl.value_id and curl.lang_id="'.$lang_id.'")
		where i.order_id="'.$id.'" 
		';
		
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		$strs='';
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//distinct i.id, i.price_value, i.quantity, i.comment, g.id, gl.name, curl.signat, cur.id 
			
			if($f[4]===NULL){
				//товара нет в базе
				$good_name=$rf->GetValue('vieworders.php','good_not_exists',$lang_id);
				$good_url=PATH404;
				$options='';
				
			}else{
				
				$good_name=stripslashes($f[5]);
				$pi=new PriceItem();
				$url=$pi->ConstructPath($f[4],$lang_id,'/');
				$good_url=$url;
				
				//опции
				$oog=new OrderItemOptGroup();
				//перебор всех свойств
				if($blocked){
					//свойства просто строчками
					$opts=$dispnv->DrawNamesValsByItemIdLangId($f[0],$lang_id);
				}else $opts=$oog->GetOrderItemOptions($f[4],$lang_id,$this->items_set,$this->item_row,$f[0],$id,true,$id);
				//$parse_item->set_tpl('{options}', '<pre>'.htmlspecialchars($opts).'</pre>');
				$options=$opts;
				
			}
			
			//актуальная цена - используем прайсфайндер
			//$itm=new OrderItemItem();
			//$price_by_base=$itm->FindPrice($f[4],$lang_id,$id,$user_skidka);
			//$parse_item->set_tpl('{price}', FormatPrice($price_by_base['value'], '&nbsp;'.stripslashes($price_by_base['currency_sign']), 2) );
			//$parse_item->set_tpl('{cost}', '<strong>'.FormatPrice(($price_by_base['value']*$f[2]), ' '.stripslashes($price_by_base['currency_sign']), 2).'</strong>' );
			
			$alls[]=Array(
				'orderno'=> $id,
				'itemno'=>$f[0],
				'goodid'=>$f[4],
				'quantity'=>$f[2],
				'comment'=>htmlspecialchars(stripslashes($f[3])),
				
				//цены
				//на момент заказа
				'price'=> FormatPrice($f[1], '&nbsp;'.stripslashes($f[6]), 2).'*'.$f[2].'= <strong>'.FormatPrice(($f[1]*$f[2]), ' '.stripslashes($f[6]), 2).'</strong>',    //$f[1]);
				'cost'=> '<strong>'.FormatPrice(($f[1]*$f[2]), ' '.stripslashes($f[6]), 2).'</strong>',
				
				'good_name'=>$good_name,
				'good_url'=>$good_url,
				'options'=>$options
			);
		}
		
		return $alls;
	}
	
	
	
	
	
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	//сколько итемов
	public function CalcItemsById($id, $mode=0){
		
		if($mode==0){
			$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.';';
		}else{
			$query='select count(*) from '.$this->tablename.' where '.$this->vis_name.'=1 and '.$this->subkeyname.'='.$id.';';
		}
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}
	
	
	//подсчет числа итемов (подразделов, ...)
	public function CountChildsById($id,$flagname='parent_id'){
		$sql='select count(*) from '.$this->tablename.' where '.$flagname.'="'.$id.'"';
		$set=new mysqlSet($sql);
		$r=$set->GetResult();
		
		$f=mysqli_fetch_array($r);
		
		return $f['count(*)'];
		
	}
	
	
	
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template,$items_set,$item_row){
		$this->all_menu_template=$all_menu_template;
		$this->menuitem_template=$menuitem_template;	
		$this->menuitem_template_blocked=$menuitem_template_blocked;
		$this->razbivka_template=$razbivka_template;
		$this->items_set=$items_set;
		$this->item_row=$item_row;
		
		
		return true;
	}
	
	
	
	
}
?>