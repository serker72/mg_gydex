<?
require_once('abstractgroup.php');
require_once('abstractlanggroup.php');

require_once('priceitem.php');
require_once('pricedisp.php');
require_once('dictattdisp.php');
require_once('firmitem.php');
require_once('firmsgroup.php');
require_once('resoursefile.php');
require_once('rekom_group.php');
require_once('dictnvdisp.php');

// список товаров
class PriceGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $minor_templates=Array(); //дополнительные шаблоны
	
	//установка всех имен
	protected function init(){
		$this->tablename='price_item';
		
		$this->lang_tablename='price_lang';
		$this->pagename='viewpriceitems.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->mid_name='price_id';
		$this->lang_id_name='lang_id';	
		
		$this->all_menu_template='tpl/itemstable.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
		$this->name_multilang='tpl/price/subitem_name.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($id,$mode=0,$from=0,$to_page=GOODS_PER_PAGE){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		$fg=new FirmsGroup();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('filename','ed_price.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$id);
		$smarty->assign('listpagename',$this->pagename);
		$smarty->assign('manyfilename','add_goods.php');
		
		
		
		$params=Array();
		$params[$this->subkeyname]=$id;
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		$query=$this->GenerateSQL($params,NULL, $paramsord, $query_count);
		
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&id='.$id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			$names=Array(); $params=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new PriceItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['small_txt']) );
			}
			
			//реком товары
			$rg=new RekomGroup();
			$crg=$rg->CalcItemsById($f['id']);
			
			
			//цена
			$it1=new PriceItem();
			
			$alls[]=Array('itemno'=>$f['id'], 'photopath'=>stripslashes($f['photo_small']), 'nameitems'=>$names, 'valitems'=>$params, 'firmopt'=>$fg->GetItemsOptByLang_id($f['firmid'],'name'),'rekom_count'=>$crg, 'priceplace'=>$it1->price_disp->GetGoodBasePrice($f['id']));
			
		}
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	
	
	
	//список итемов
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
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
	
	
	
	protected function GenerateSQL($params, $notparams=NULL, $orderbyparams=NULL, &$sql_count=''){
		$sql='';
		
		$sql='select * from '.$this->tablename.'  where ';
		
		//запрос для посчета общего числа итемов
		$sql_count='select count(*) from '.$this->tablename.'  where ';
		
		$qq=''; $cter=0;
		foreach($params as $k=>$v){
			if($cter!=0) $qq.=' and ';
			$qq.=$k.'="'.$v.'" ';
			$cter++;
		}
		if($notparams!=NULL){
			$cter=0;
			foreach($notparams as $k=>$v){
				if($cter!=0) $qq.=' and ';
				$qq.=$k.'<>"'.$v.'" ';
				$cter++;
			}
		}
		
		$qq2='';
		if($orderbyparams!=NULL){
			$cter=0;
			foreach($orderbyparams as $k=>$v){
				if($cter==0) $qq2.=' order by ';
				$qq2.=$v.'';
				$cter++;
				if($cter!=count($orderbyparams)) $qq2.=', ';
			}
		}
		
		$sql=$sql.$qq.$qq2;
		$sql_count=$sql_count.$qq;
		
		return $sql;
	}
	
	
	
	
	
	
	
//***************************клиентский вывоД!!!!!!!!!!!!!!**********************************************
		
	//список товаров клиентский
	public function GetItemsByIdCli($template1, $template2, $template3, $parent_id, $lang_id=LANG_CODE,$from=0, $to_page=GOODS_PER_PAGE,$flt_params=NULL,$sortmode=0){
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt'); $gd=new PriceItem();
		$mi=new MmenuItem(); $mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
		if(HAS_URLS) $url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
		else $url_path='/';
		
		$price_disp=new PriceDisp();
		$disp_nv=new DictNVDisp();
		
		//таблица свойств товара
		$dicts=new DictAttDisp(3);
		
		
		$price_disp->SetShowErrors(false); 
		//получим базовую цену
		$base_price=$price_disp->GetBasePriceF();
		
		//получим базовую валюту
		$base_curr= $price_disp->GetBaseCurrencyF();
		
		$ord_add=''; $ord_url='';
		if($sortmode==0) $ord_add=' order by p.ord desc ';
		if($sortmode==1) $ord_add=' order by gp.value asc ';
		if($sortmode==2) $ord_add=' order by gp.value desc ';
		
		 $ord_url='&sortmode='.$sortmode;
		
		$flt_add=''; $flt_url=''; $name_val_arr=Array(); $by_val_add='';
		if($flt_params!==NULL){
			foreach($flt_params as $k=>$v){
				if(($v!='0')&&(!eregi('name_id_',$k))&&(!eregi('toprice',$k))&&(!eregi('fromprice',$k))){
					$flt_add.=' and ';
					$flt_add.=$k.'="'.$v.'"';
				}
				//для firmid исключение
				if($k=='p.firmid') $flt_url.='&firmid='.abs((int)$v);
				else if(HAS_URLS) $flt_url.='&'.$k.'='.urlencode(urlencode($v));
					 else $flt_url.='&'.$k.'='.(($v));
				
				//для нейм_ид особое исключение
				if(eregi("name_id_",$k)){
					if($v!='0'){
						$name_id=abs((int)eregi_replace('name_id_', '', $k));
						$val_id=$v; //echo htmlspecialchars($v);
						$name_val_arr[]=Array(
							'name_id'=>$name_id,
							'val_name' =>$val_id);
							
						if($by_val_add!='') $by_val_add.=' and ';
						if(HAS_URLS) $by_val_add.=' p.id in(select distinct item_id from prop_value as pv, prop_value_lang as pvl where pv.id=pvl.value_id and pvl.lang_id='.$lang_id.' and pvl.name="'.($val_id).'" and pv.name_id="'.$name_id.'") ';	
						else $by_val_add.=' p.id in(select distinct item_id from prop_value as pv, prop_value_lang as pvl where pv.id=pvl.value_id and pvl.lang_id='.$lang_id.' and pvl.name="'.urldecode($val_id).'" and pv.name_id="'.$name_id.'") ';	
					}
					
				}
				
				//исключение для диапазона цены
				if(eregi("fromprice",$k)&&(abs((float)$v)>0)){
					if($by_val_add!='') $by_val_add.=' and ';
					$by_val_add.=' gp.value >= "'.abs((float)$v).'" ';
				}
				if(eregi("toprice",$k)&&(abs((float)$v)>0)){
					if($by_val_add!='') $by_val_add.=' and ';
					$by_val_add.=' gp.value <= "'.abs((float)$v).'" ';
				}
			}
		}
		//вставка в запрос для отбора по собранным значениям свойств
		if($by_val_add!='') $flt_add.=' and  ('.$by_val_add.') ';
		

		$query='select distinct p.id, p.photo_small, p.firmid, l.name, l.small_txt, gp.value from
		((('.$this->tablename.' as p INNER JOIN price_lang as l ON p.id=l.price_id)
		LEFT JOIN good_price as gp ON (p.id=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")
		))
		where p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown="1" '.$flt_add.' '.$ord_add;
		
		$query_count='select count(distinct p.id) from
		((('.$this->tablename.' as p INNER JOIN price_lang as l ON p.id=l.price_id)
		LEFT JOIN good_price as gp ON (p.id=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")
		))
		where p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add;

		
		//echo htmlspecialchars($query);
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$totalcount=$items->GetResultNumRowsUnf();
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		//форма фильтрации
		$smarty->assign('not_firm_selected_text',$rf->GetValue('razds.php','notselected',$lang_id));
		$smarty->assign('parent_id',$parent_id);
		
		$smarty->assign('bypricename',$rf->GetValue('razds.php','bypricename',$lang_id));
		$smarty->assign('byfirmname',$rf->GetValue('razds.php','byfirmname',$lang_id));
		$smarty->assign('dosort',$rf->GetValue('razds.php','dosort',$lang_id));
		$smarty->assign('sortmode',$sortmode);
		
		//отбор по ценам
		$smarty->assign('bypricefrom',$rf->GetValue('razds.php','bypricefrom',$lang_id));
		$smarty->assign('bypriceto',$rf->GetValue('razds.php','bypriceto',$lang_id));
		if(isset($flt_params['fromprice'])&&(abs((float)$flt_params['fromprice'])>0)) $smarty->assign('frompriceval',abs((float)$flt_params['fromprice']));
		else $smarty->assign('frompriceval','');
		
		if(isset($flt_params['toprice'])&&(abs((float)$flt_params['toprice'])>0)) $smarty->assign('topriceval',abs((float)$flt_params['toprice']));
		else $smarty->assign('topriceval','');
		
		$fg=new FirmsGroup();
		if(isset($flt_params['p.firmid'])) {
			$smarty->assign('firms', $fg->GetItemsOptByLang_id($flt_params['p.firmid'],'name',$lang_id)); 
			if($flt_params['p.firmid']==0) $smarty->assign('not_firm_selected',true);
			else $smarty->assign('not_firm_selected',false);
		}else{
			$smarty->assign('firms', $fg->GetItemsOptByLang_id(0,'name',$lang_id)); 
			$smarty->assign('not_firm_selected',false);
		}
		
		
		//!!!!!!!!!!!!!работаем с наборами свойств-значений
		$nv=$disp_nv->DrawFilterForms($parent_id, $lang_id, $name_val_arr, $this->minor_templates['byname_set'], $this->minor_templates['byname_val']);
		$smarty->assign('props',$nv);
		
		
		$cter=1; $max=2;
		$strs='';
		if(HAS_URLS){ 
			$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,$flt_url.$ord_url);
		}else $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&id='.$parent_id.$flt_url.$ord_url);
		$navig->setFirstParamName('from');
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array(); $pi=new PriceItem;
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($cter==1){
				//соберем ряд
				$row=Array();
				//echo 'Ряд начат!!!<br>';
			}
			
			$im=GetImageSize(ABSPATH.stripslashes($f[1]));
			if($im!=false){
				$image_w=$im[0];
				$image_h=$im[1];
			}else{
				$image_w=320;
				$image_h=240;
			}
			
			//разобраться с page_url!!!
			/*if(HAS_URLS) $path=$url_path.'goods'.$f[0];
			else $path=$url_path.'good.php?id='.$f[0];*/
			$path=$pi->ConstructPath($f[0],$lang_id);
			
			//вывод цены
			if($f[5]!==NULL){
				$price=$price_disp->ConvertPriceValueByLangId($err_code, $f[5],$lang_id,true);
				if($err_code==0) $price=$price;
				else $price='';
			}else $price='';
			
			
			//рисуем корзину
			//при условии, что вкл. глобальный флаг магазина+вкл. "можно заказывать" для раздела
			if((HAS_BASKET)&&($mmenuitem['is_basket']==1)){
				$has_basket=true;
			}else $has_basket=false;
			
			
			//покажем фирму-производителя (если не задано, не покажем ничего!)
			
			$fi=new FirmItem();
			$firm=$fi->GetItemById($f[2],$lang_id,1);
			if($firm!=false){
				$has_firm=true;
				$firm_caption=$rf->GetValue('good.php','firm-manufacturer',$lang_id);
				$firm_name=stripslashes($firm['name']);
				$firm_id=$f[2];
			}else{
				$has_firm=false;
				$firm_caption=$rf->GetValue('good.php','firm-manufacturer',$lang_id);
				$firm_name='';
				$firm_id='';
			}
			
			
			$row[]=Array(
				'td_width'=>floor(100/$max),
				'page_url'=>$path,
				'altname'=>strip_tags(stripslashes($f[3])),
				'name'=>stripslashes($f[3]),
				'image_src'=>stripslashes($f[1]),
				'image_w'=>$image_w,
				'image_h'=>$image_h,
				'price'=>$price,
				'has_basket'=>$has_basket,
				'good_id'=>$f[0],
				'has_firm'=>$has_firm,
				'has_firm'=>$has_firm,
				'firm_caption'=>$firm_caption,
				'firm_name'=>$firm_name,
				'firm_id'=>$firm_id,
				'props'=>$dicts->DrawDictsCli(1, $f[0], $lang_id,'price/prop_tables.html', '', ''),
				'smalltxt'=>stripslashes($f[4])
			);
			
			if(($i==($rc-1))||($cter>=$max)){
				$cter=1;
				$alls[]=Array('goods'=>$row);
				//echo 'Ряд ended!!!<br>';
			}else $cter++;
		}
		$smarty->assign('to_compare',$rf->GetValue('razds.php','to_compare',$lang_id));
		
		$smarty->assign('pages',$pages);
		$smarty->assign('rows',$alls);
		$txt=$smarty->fetch($template1);
		
		
		if($rc==0) $txt.=$rf->GetValue('razds.php','goods_not_found',$lang_id);
		return $txt;
	//	return '<pre>'.htmlspecialchars($txt).'</pre>';
	}
	
	
	//список товаров на главной странице сайта
	public function GetItemsMainCli($template1, $template2, $template3, $parent_id, $lang_id=LANG_CODE){
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt'); $gd=new PriceItem();
		$mi=new MmenuItem(); 
		
		
		$price_disp=new PriceDisp();
		$disp_nv=new DictNVDisp();
		
		//таблица свойств товара
		$dicts=new DictAttDisp(3);
		
		$price_disp->SetShowErrors(false); 
		//получим базовую цену
		$base_price=$price_disp->GetBasePriceF();
		
		//получим базовую валюту
		$base_curr= $price_disp->GetBaseCurrencyF();
		
		

		$query='select distinct p.id, p.photo_small, p.firmid, l.name, l.small_txt, gp.value, p.mid from
		((('.$this->tablename.' as p INNER JOIN price_lang as l ON p.id=l.price_id)
		LEFT JOIN good_price as gp ON (p.id=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")
		))
		where p.is_new="1" and l.lang_id="'.$lang_id.'" and l.is_shown="1"';
		
		$query_count='select count(distinct p.id) from
		((('.$this->tablename.' as p INNER JOIN price_lang as l ON p.id=l.price_id)
		LEFT JOIN good_price as gp ON (p.id=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")
		))
		where p.is_new="1" and l.lang_id="'.$lang_id.'" and l.is_shown="1" ';

		
		//echo htmlspecialchars($query);
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$totalcount=$items->GetResultNumRows();
		
		$parse=new parse_class();
		$parse->get_tpl($template1); //Файл который мы будем парсить
		
		$cter=1; $max=1;
		$strs='';
		
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//проверка видимости раздела
			$ress=$mi->CheckFullExistance($f['mid'],$lang_id,1);
			if($ress==false) continue;
			
			if($cter==1){
				//соберем ряд
				$parse2=new parse_class();
				$parse2->get_tpl($template2);
				$row='';
				//echo 'Ряд начат!!!<br>';
			}
			
			$parse3=new parse_class();
			$parse3->get_tpl($template3); //Файл который мы будем парсить
			$parse3->set_tpl('{image_src}',stripslashes($f[1]));
			
			$im=GetImageSize(ABSPATH.stripslashes($f[1]));
			if($im!=false){
				$parse3->set_tpl('{image_w}',$im[0]);
				$parse3->set_tpl('{image_h}',$im[1]);
			}else{
				$parse3->set_tpl('{image_w}',320);
				$parse3->set_tpl('{image_h}',240);
			}
			
			//разобраться с page_url!!!
			 $mmenuitem=$mi->GetItemById($f[6],$lang_id,1);
			if(HAS_URLS){
				 $url_path='/'.$mi->ConstructPath($f[6],$lang_id,1,'/');
				
			}else $url_path='/';
			
			if(HAS_URLS) $path=$url_path.'goods'.$f[0];
			else $path=$url_path.'good.php?id='.$f[0];
			$parse3->set_tpl('{page_url}',$path);
			
			//вывод цены
			if($f[5]!==NULL){
				$price=$price_disp->ConvertPriceValueByLangId($err_code, $f[5],$lang_id,true);
				if($err_code==0) $parse3->set_tpl('{price}',$price);
				else $parse3->set_tpl('{price}','');
			}else $parse3->set_tpl('{price}','');
			
			
			//рисуем корзину
			//при условии, что вкл. глобальный флаг магазина+вкл. "можно заказывать" для раздела
			if((HAS_BASKET)&&($mmenuitem['is_basket']==1)){
				$parse4=new parse_class();
				$parse4->get_tpl('tpl/price/basket_small.html');
				$parse4->set_tpl('{good_id}',$f[0]);
				$parse4->tpl_parse();
				$parse3->set_tpl('{basket}',$parse4->template);
			}else $parse3->set_tpl('{basket}','');
			
			//рисуем форму для сравнения товара
			$parse4=new parse_class();
			$parse4->get_tpl('tpl/price/compare_form.html');
			$parse4->set_tpl('{itemno}',$f[0]);
			$parse4->set_tpl('{to_compare}',$rf->GetValue('razds.php','to_compare',$lang_id));
			$parse4->tpl_parse();
			$parse3->set_tpl('{compare_form}',$parse4->template);
			
			
			

			$fi=new FirmItem();
			$firm=$fi->GetItemById($f[2],$lang_id,1);
			if($firm!=false){
				$parse3->set_tpl('{firm}','<span style="background-color: #ecf7e9; padding: 5px 5px 5px 5px;"><strong>'.($rf->GetValue('good.php','firm-manufacturer',$lang_id)).'</strong> <a href="/firm.php?id='.$f[2].'" target="_blank">'.stripslashes($firm['name']).'</a></span><br><br>');
			}else{
				$parse3->set_tpl('{firm}','');
			}
			
			//покажем словари свойств
			$parse3->set_tpl('{PROPS}',$dicts->DrawDictsCli(1, $f[0], $lang_id,'tpl/price/prop_table.html', 'tpl/price/prop_group.html', 'tpl/price/prop_item.html'));
			
			$parse3->set_tpl('{name}',stripslashes($f[3]));
			$parse3->set_tpl('{smalltxt}',stripslashes($f[4]));
			$parse3->set_tpl('{altname}',strip_tags(stripslashes($f[3])));
			$parse3->set_tpl('{td_width}',floor(100*1/$max).'%');
			$parse3->tpl_parse(); //Парсим
			$row.=$parse3->template; //Выводим нашу страничку
			
			
			if(($i==($rc-1))||($cter>=$max)){
				$cter=1;
				$parse2->set_tpl('{items}',$row);
				$parse2->tpl_parse(); //Парсим
				$strs.=$parse2->template;
			}else $cter++;
		}
		$parse->set_tpl('{item_rows}',$strs);
		$parse->tpl_parse(); //Парсим
	
		$txt.=$parse->template;
		return $txt;
	}
	
	
	//список "похожих" товаров
	public function GetSimilarGoodsCli($good_id, $template1, $template2, $template3, $percent=5, $lang_id=LANG_CODE, $is_shown=1){

		$pri=new PriceItem();
		$good=$pri->GetItemById($good_id, $lang_id, $is_shown);
		if($good==false) return '';
				
		$pri->price_disp->SetShowErrors(false);
		$priceblock=$pri->price_disp->GetGoodBasePrice($good_id, $lang_id, false);
		//echo $priceblock;
		
		
		//получим базовую цену
		$base_price=$pri->price_disp->GetBasePriceF();
		
		//получим базовую валюту
		$base_curr= $pri->price_disp->GetBaseCurrencyF();
		
		if((strlen($priceblock)==0)||(!is_numeric($priceblock))) return '';
		//$pri_q='and (gp.value >= '.((float)$priceblock-($percent/100)*(float)$priceblock).' and gp.value <='.((float)$priceblock+($percent/100)*(float)$priceblock).') ';
		$pri_q='and gp.value between '.((float)$priceblock-($percent/100)*(float)$priceblock).' and '.((float)$priceblock+($percent/100)*(float)$priceblock).' ';
		
		$txt='';
		
		$query='select distinct p.id, p.photo_small, p.firmid, l.name, l.small_txt, gp.value from
		((('.$this->tablename.' as p INNER JOIN price_lang as l ON p.id=l.price_id)
		LEFT JOIN good_price as gp ON (p.id=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")
		))
		where p.id<>"'.$good_id.'" and p.mid="'.$good['mid'].'" and l.lang_id="'.$lang_id.'" and l.is_shown="'.$is_shown.'" '.$pri_q.' order by p.ord desc ';
		//echo $query;
		
		
		$set=new mysqlSet($query);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		//echo $rc;
		
		$tpl1=new parse_class();
		$tpl1->get_tpl($template1);
		
		$row=''; $strs=''; $per_row=4; $cter=1;
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			
			if($cter==1){
					//соберем ряд
					$parse2=new parse_class();
					$parse2->get_tpl($template2);
					$row='';
					//echo 'Ряд начат!!!<br>';
			}
				
			$tpl3=new parse_class();
			$tpl3->get_tpl($template3);
			$tpl3->set_tpl('{itemno}', $f[0]);
			$tpl3->set_tpl('{itemname}', stripslashes($f[3]));
			$tpl3->set_tpl('{item_photo}', stripslashes($f[1]));
			
			$path=$pri->ConstructPath($f[0],$lang_id,'/');
			$tpl3->set_tpl('{item_url}', $path);
			
			//вывод цены
			if($f[3]!==NULL){
				$price=$pri->price_disp->ConvertPriceValueByLangId($err_code, $f[5],$lang_id,true);
				if($err_code==0) $tpl3->set_tpl('{price}',$price);
				else $tpl3->set_tpl('{price}','');
			}else $tpl3->set_tpl('{price}','');
			
			//echo 'zzfdds';
			$tpl3->tpl_parse();
			$row.=$tpl3->template;
			
			if(($i==($rc-1))||($cter>=$per_row)){
				$cter=1;
				$parse2->set_tpl('{someitem}',$row);
				$parse2->tpl_parse(); //Парсим
				$strs.=$parse2->template;
				//echo 'Ряд ended!!!<br>';
			}else $cter++;
		}
		$tpl1->set_tpl('{someitem}', $strs);
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$tpl1->set_tpl('{rekomtitle}', $rf->GetValue('good.php','simtitle',$lang_id));
		
		$tpl1->tpl_parse();
		if($rc>0) $txt=$tpl1->template;
		else $txt='';
		return $txt;
		
	}
	
	
	
	
	
	
	
	
	
	//список всех товаров в тегах оптион
	//список итемов
	public function GetItemsTotalOpt($current_id=0){
		//список позиций
		return AbstractLangGroup::GetItemsOptByLang_id($current_id,'name',LANG_CODE);
	}
	
	public function SetMinorTemplates($templates){
		$this->minor_templates=$templates;
	}
	
}
?>