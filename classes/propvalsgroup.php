<?
require_once('abstractlanggroup.php');
require_once('alldictitem.php');
require_once('propvalitem.php');

// группа имен свойств в словаре
class PropValsGroup extends AbstractLangGroup{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;
	
	//шаблоны вывода свойств
	protected $templates=Array();
	
	//установка всех имен
	protected function init(){
		$this->tablename='prop_value';
		$this->lang_tablename='prop_value_lang';
		$this->pagename='viewnames.php';		
		$this->subkeyname='name_id';	
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';	
		
		//инициализация шаблонов
		$this->init_templates();
	}
	
	
	/*
	
	//вывод списка значений по имени и айди итема
	public function GetVals($f,$item_id,$from,$to_page){
		$txt=''; $strs=''; $lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		//проверим вид словаря:
		$di=new AllDictItem();
		$dict=$di->GetItemById($f['dict_id']);
		if($dict!=false){
			//проверим вид словаря
			$template=$this->ChooseTemplate($dict['kind_id']);
						
			$parse=new parse_class();
			$parse->get_tpl($template['all_menu_template']);
			
			//составим запрос на выборку свойств
			$sql='select * from '.$this->tablename.' where '.$this->subkeyname.'="'.$f['id'].'" and item_id="'.$item_id.'" order by ord desc ';
			//echo $sql;
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			if(($rc==0)||(($rc>0)&&($dict['kind_id']==2))){
				$itm=new parse_class();
				$itm->get_tpl($template['menuitem_template_blocked']);
				$itm->set_tpl('{nameid}',$f['id']);
				$itm->set_tpl('{itemid}',$item_id);				
				$itm->set_tpl('{fromno}',$from);
				$itm->set_tpl('{topage}',$to_page);
				$itm->set_tpl('{filename}','ed_value_compact.php');
				$itm->tpl_parse(); //Парсим
				$strs.=$itm->template; 
			}
			for($i=0;$i<$rc;$i++){
				$g=mysqli_fetch_array($rs);
				
				$itm=new parse_class();
				$itm->get_tpl($template['menuitem_template']);
				
				$namepar=$this->GetNameParams($g,$from,$to_page,$template['name_multilang']);
				$itm->set_tpl('{itemname}',$namepar);
				//echo stripslashes($g['photo_small']);
				
				$itm->set_tpl('{photo}','<img src="/'.stripslashes($g['photo_small']).'" alt="" border="0" align="left" hspace="2">');
				
				$itm->set_tpl('{nameid}',$f['id']);
				$itm->set_tpl('{itemno}',$g['id']);				
				$itm->set_tpl('{fromno}',$from);
				$itm->set_tpl('{topage}',$to_page);
				$itm->set_tpl('{filename}','ed_value_compact.php');
				$itm->tpl_parse(); //Парсим
				$strs.=$itm->template; 
			}
			
			$parse->set_tpl('{fromno}',$from);
			$parse->set_tpl('{dict_id}',$f['dict_id']);
			$parse->set_tpl('{topage}',$to_page);
			$parse->set_tpl('{someitem}',$strs); //Установка переменной 
			
			$parse->tpl_parse(); //Парсим
			$txt.=$parse->template; //Выводим нашу страничку
			
		
		}
		//return htmlspecialchars($txt);
		return $txt;
	}
	
	
	
	
	
	//получение списка имен на разных языках
	protected function GetNameParams($f,$from,$to_page,$templatename){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			$names=new parse_class();
			$names->get_tpl($templatename);
			$names->set_tpl('{itemno}',$f['id']);
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			$mi=new PropValItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', 'в.','','tpl/dict_vals/subitem_lang_vis_check.html',$g['id'].'_'));		
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
	
	
	protected function init_templates(){
		$this->templates[1]=Array(
			'all_menu_template' => 'tpl/dict_vals/oneval/itemstable.html',
			'menuitem_template' => 'tpl/dict_vals/oneval/itemsrow.html',
			'name_multilang' => 'tpl/dict_vals/oneval/subitem_name.html',
			'menuitem_template_blocked' => 'tpl/dict_vals/oneval/itemsrow_none.html'
			
		);
		
		$this->templates[2]=Array(
			'all_menu_template' => 'tpl/dict_vals/oneval/itemstable.html',
			'menuitem_template' => 'tpl/dict_vals/oneval/itemsrow.html',
			'name_multilang' => 'tpl/dict_vals/oneval/subitem_name.html',
			'menuitem_template_blocked' => 'tpl/dict_vals/oneval/itemsrow_add.html'
		);
		
		$this->templates[3]=Array(
			'all_menu_template' => 'tpl/dict_vals/photo/itemstable.html',
			'menuitem_template' => 'tpl/dict_vals/photo/itemsrow.html',
			'name_multilang' => 'tpl/dict_vals/photo/subitem_name.html',
			'menuitem_template_blocked' => 'tpl/dict_vals/photo/itemsrow_none.html'
		);
	}
	
	//выбор активного шаблона
	public function ChooseTemplate($kind){
		$temp=$this->templates[$kind];
		return $temp;
	}
	
	
	//КЛИЕНТСКИЙ ВЫВОД
	////по коду значения, товара, языка получить имя свойства и остальные доступные значения свойства
	//(для управления корзиной, заказом)
	public function DrawNameValsByValIdGoodId($val_id,$good_id,$lang_id=LANG_CODE){
		$txt='';
		
		//получим значение 
		$pv=new PropValItem();
		$pvalue=$pv->GetItemById($val_id,$lang_id,1);
		if(($pvalue==false)||($pvalue['item_id']!=$good_id)){
			return $txt;
		}
		
		//получим имя свойства
		$pn=new PropNameItem();
		$pname=$pn->GetItemById($pvalue['name_id'], $lang_id,1);
		if($pname==false){
			return $txt;
		}
		
		$txt.='<strong>'.stripslashes($pname['name']).':</strong><br>';
		
		//получим все доступные значения свойства
		$query2='select pv.id, pl.name from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$good_id.'" and pv.name_id="'.$pvalue['name_id'].'" order by pv.ord desc';
		//echo " $query2 ";
		$vset=new mysqlSet($query2);
		$vrs=$vset->GetResult();
		$vrc=$vset->GetResultNumRows();
		for($i=0; $i<$vrc; $i++){
			$f=mysqli_fetch_array($vrs);
			
			if($f['id']==$val_id) $txt.='<div style="color: Red;">'.stripslashes($f['name']).'</div>';
			else $txt.='<i>'.stripslashes($f['name']).'</i><br>';
		}
		
		
		
		return $txt;
	}
	
	
	
	//получить список свойств по имени, по коду раздела 
	public function GetValsByNameIdMid($name_id,$mid,$lang_id){
		$names=Array();
		$sql='select distinct pvl.name
		from prop_value as pv INNER JOIN prop_value_lang as pvl ON(pv.id=pvl.value_id and pvl.lang_id='.$lang_id.' and pvl.is_shown=1)
		where pv.name_id='.$name_id.' and pv.item_id in(
			select pr.id from price_item as pr, price_lang as pl where pr.id=pl.price_id and pl.lang_id='.$lang_id.' and pl.is_shown=1 and pr.mid='.$mid.'
		)
		order by pvl.name;
		';
		
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$names[]=Array(
				'id'=>$f[0],
				'name'=>stripslashes($f[0])
			);
		}
		
		
		return $names;
	}
}
?>