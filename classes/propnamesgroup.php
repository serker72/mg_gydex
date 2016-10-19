<?
require_once('abstractlanggroup.php');
require_once('propvalsgroup.php');

// группа имен свойств в словаре
class PropNamesGroup extends AbstractLangGroup{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;
	
	//установка всех имен
	protected function init(){
		$this->tablename='prop_name';
				$this->lang_tablename='prop_name_lang';
		$this->pagename='viewnames.php';		
		$this->subkeyname='dict_id';	
		
		$this->mid_name='name_id';
		$this->lang_id_name='lang_id';	
	}
	
	
	
	//список итемов
	public function GetItemsById($id, $item_id, $mode=0,$from=0,$to_page=PROPS_PER_PAGE){
		//список позиций
		$txt=''; $razbivka=''; $pages=''; $strs='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_propname_compact.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemid',$item_id);
		$smarty->assign('dict_id',$id);
		$smarty->assign('listpagename',$this->pagename);
		
		
		$sql= 'select * from '.$this->tablename.' where '.$this->subkeyname.'="'.$id.'" order by ord desc, id ';
		$sql_count= 'select count(*) from '.$this->tablename.' where '.$this->subkeyname.'="'.$id.'" ';
		if($to_page=='-'){
			//неограничено
			$set=new MysqlSet($sql);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			$total=$rc;		
			$pages='';
			$smarty->assign('has_razb',false);
		}else{
			$set=new MysqlSet($sql,$to_page,$from,$sql_count);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			$total=$set->GetResultNumRowsUnf();		
			$smarty->assign('has_razb',true);
			
			$navig = new PageNavigator($this->pagename,$total,$to_page,$from,10,'&to_page='.$to_page.'&'.$this->subkeyname.'='.$id.'&id='.$item_id);
			$navig->setDivWrapperName('alblinks');
			$navig->setPageDisplayDivName('alblinks1');			
			$pages= $navig->GetNavigator();
			
		}
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//параметры имени
			//параметры
			$names=Array(); $params=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new PropNameItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['default_val']) );
			}
			
			
			$alls[]=Array(
				'itemno'=>$f['id'],
				'nameitems'=>$names,
				'valitems'=>$params
			);
		}
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	//список имен и свойств
	public function GetNamesVals($dict_id, $item_id, $from=0,$to_page=PROPS_PER_PAGE){
		//список позиций
		$txt=''; $razbivka=''; $pages=''; $strs='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_propname_compact.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemid',$item_id);
		$smarty->assign('dict_id',$dict_id);
		$smarty->assign('val_filename','ed_value_compact.php');
		
		$sql= 'select * from '.$this->tablename.' where '.$this->subkeyname.'="'.$dict_id.'" order by ord desc, id ';
		$sql_count= 'select count(*) from '.$this->tablename.' where '.$this->subkeyname.'="'.$dict_id.'" ';
		if($to_page=='-'){
			//неограничено
			$set=new MysqlSet($sql);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			$total=$rc;		
			$pages='';
		}else{
			$set=new MysqlSet($sql,$to_page,$from,$sql_count);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			$total=$set->GetResultNumRowsUnf();		
			
			$navig = new PageNavigator($this->pagename,$total,$to_page,$from,10,'&to_page='.$to_page.'&'.$this->subkeyname.'='.$dict_id.'&id='.$item_id);
			$navig->setDivWrapperName('alblinks');
			$navig->setPageDisplayDivName('alblinks1');			
			$pages= $navig->GetNavigator();
		}
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);

			//параметры имени
			$names=Array(); 
			foreach($this->langs as $lk=>$g){
				$mi=new PropNameItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
			}
			
			//наборы свойств для имени
			$params=Array();
			$di=new AllDictItem();
			$dict=$di->GetItemById($dict_id);
			
			//ПЕРЕБОР ВСЕХ ЗНАЧЕНИЙ СВОЙСТВА
			//составим запрос на выборку свойств
			$sql_='select * from prop_value where name_id="'.$f['id'].'" and item_id="'.$item_id.'" order by ord desc ';
			//echo $sql;
			$set_=new mysqlSet($sql_);
			$rs_=$set_->GetResult();
			$rc_=$set_->GetResultNumRows();
			$can_add_value=false;
			$no_values=false;
			$is_photo=false;
			if(($rc_==0)||(($rc_>0)&&($dict['kind_id']==2))){
				$can_add_value=true;
				if($rc_==0) $no_values=true;
			}
			if($dict['kind_id']==3) $is_photo=true;
			
			for($j=0;$j<$rc_;$j++){
				$g_=mysqli_fetch_array($rs_);
				
				if($is_photo) $photo=stripslashes($g_['photo_small']);
				else $photo='';
				
				//сами значения
				$thenames=Array();
				foreach($this->langs as $lk=>$g){
					$mi=new PropValItem();
					$mmi=$mi->GetItemById($g_['id'],$g['id']);
					if($mmi!=false){
						$is_exist=true;
					}else {
						$is_exist=false;
					}
					$thenames[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				}
				$params[]=Array(
					'itemno'=>$g_['id'],
					'is_photo'=>$is_photo,
					'photo'=>$photo,
					'valnameitems'=>$thenames
				);
			}
			
			$alls[]=Array(
				'itemno'=>$f['id'],
				'nameitems'=>$names,
				'can_add_value'=>$can_add_value,
				'no_values'=>$no_values,
				'valitems'=>$params
			);
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	
	
	/*
	//получение списка имен на разных языках
	protected function GetNameParams($f,$item_id,$from,$to_page){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			$names->set_tpl('{itemid}',$item_id);
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			$mi=new PropNameItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', 'видим','',$this->name_vis_check,$g['id'].'_'));		
			}else {
				$names->set_tpl('{itemname}','<em>не создано</em>');
				$names->set_tpl('{vistext}','');		
			}
			//echo $this->name_multilang;
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		return $txt;
	}
	
	
	//получение списка описаний на разных языках
	protected function GetDescrParams($f,$item_id,$from,$to_page){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			$names->set_tpl('{itemid}',$item_id);
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			$mi=new PropNameItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['default_val']));
				$names->set_tpl('{vistext}','');		
			}else {
				$names->set_tpl('{itemname}','<em>не создано</em>');
				$names->set_tpl('{vistext}','');		
			}
			//echo $this->name_multilang;
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		return $txt;
	}
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	//по коду итема заказа, языка получить имена и значения выбранных свойств
	public function DrawNamesValsByItemIdLangId($item_id,$lang_id=LANG_CODE,$is_shown=1){
		$txt='';
		$sql='select pnl.name, pvl.name
		from
		(((prop_value as pv INNER JOIN prop_value_lang as pvl ON (pv.id=pvl.value_id and pvl.lang_id='.$lang_id.' and pvl.is_shown='.$is_shown.'))
		INNER JOIN order_item_option as op ON (op.value_id=pv.id and op.item_id='.$item_id.'))
		INNER JOIN prop_name as pn ON pn.id=pv.name_id)
		INNER JOIN prop_name_lang as pnl ON (pn.id=pnl.name_id and pnl.lang_id='.$lang_id.' and pnl.is_shown='.$is_shown.')
		order by pnl.name
		';
		
		//echo $sql;
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			if($i==0) $txt.='<br>';
			$f=mysqli_fetch_array($rs);
			
			$txt.="<strong>".stripslashes($f[0])."</strong>: <em>".stripslashes($f[1])."</em><br>";
		}
		
		return $txt;
	}
	
	
	//получение списка имен свойств для всех-всех подключенных к разделу словарей (вид словаря=1)(код прикр =1,2)
	public function GetNamesByMid($mid,$lang_id){
		$names=Array();
		//проверим привязки к родительским разделам по коду 2
		$mi=new MmenuItem();
		//$gd=new PriceItem();
		//$good=$gd->GetItemById($mid);			
		$arr=$mi->RetrievePath($mid, $flaglost, $vloj);
		$cter=1; $q='';
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				$q.=' '.$kk;
				if($cter<count($arr)) $q.=',';
				$cter++;
			}
		}
		
		$sql='select distinct pn.id, pnl.name
		from prop_name as pn INNER JOIN prop_name_lang as pnl ON (pn.id=pnl.name_id and pnl.lang_id='.$lang_id.' and pnl.is_shown=1 and pn.is_criteria=1)
		where pn.dict_id IN (select da.dict_id from dict_attach_d as da where 
							da.dict_id in(select distinct d.id from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.is_shown=1 and dl.lang_id='.$lang_id.' and d.kind_id=1) and
							(
								(da.attach_code=1 and key_value='.$mid.')
								OR (da.attach_code=2 and key_value in('.$q.'))
							)
		)
		order by pn.ord desc, pn.id,  pnl.name';
		
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$names[]=Array(
				'id'=>$f[0],
				'name'=>stripslashes($f[1])
			);
		}
		
		return $names;
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
	
	
	
	
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang='tpl/alldicts/subitem_name.html', $name_vis_check='tpl/dicts_comp/subitem_lang_vis_check.html'){
		$this->all_menu_template=$all_menu_template;
		$this->menuitem_template=$menuitem_template;	
		$this->menuitem_template_blocked=$menuitem_template_blocked;
		$this->razbivka_template=$razbivka_template;
		$this->name_multilang=$name_multilang;
		$this->name_vis_check=$name_vis_check;
		
		return true;
	}
	
}
?>