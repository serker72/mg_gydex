<?
require_once('abstractlanggroup.php');
require_once('alldictitem.php');

// список всех словарей
class AllDictsGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='dict';
		$this->pagename='viewdicts.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->all_menu_template='alldicts/items.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
		$this->name_multilang='tpl/alldicts/subitem_name.html';
		
		$this->name_vis_check='subitem_lang_vis_check.html';
	}
	
	
	
	//список итемов
	public function GetItems($mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_dict.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$params=Array();
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		$query=$this->GenerateSQL($params,NULL, $paramsord, $query_count);
		
		//echo $query;
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$all_=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//строим имя
			$names_=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new AllDictItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				if($mmi!=false){
					$is_exist=true;
					$is_shown=$mmi['is_shown'];
					
				}else {
					$is_exist=false;
					$is_shown=false;
				}
				$names_[]=Array( 'is_exist'=>$is_exist, 'is_visible'=>$is_shown, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'lang_shown'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
			}
			
			//вид словаря
			$params='';
			$mi=new AllDictItem();
			$params.=$mi->GetBehaviorsOpt($f['kind_id']);
			
			$all_[]=Array('itemno'=>$f['id'], 'nameitems'=>$names_, 'itemdescr'=>$params);
		}
		$smarty->assign('items',$all_);
		$smarty->assign('pages',$pages);
		$txt.=$smarty->fetch($this->all_menu_template); //Выводим нашу страничку
		
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
		
		$sql='select * from '.$this->tablename;
		
		//запрос для посчета общего числа итемов
		$sql_count='select count(*) from '.$this->tablename;
		
		if(($notparams!=NULL)||($params!=NULL)){
			$sql.='  where ';
			$sql_count.='  where ';
		}
		
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
	
	/*
	
	//получение списка имен на разных языках
	protected function GetNameParams($f,$from,$to_page){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			
			$mi=new AllDictItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', 'видим','','tpl/alldicts/subitem_lang_vis_check.html',$g['id'].'_'));		
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
	protected function GetDescrParams($f,$from,$to_page){
		$txt='';
		
		
		$names=new parse_class();
		$names->get_tpl('tpl/alldicts/its_behavior.html');
		$names->set_tpl('{itemno}',$f['id']);
		$mi=new AllDictItem();
		$res=$mi->GetBehaviorsOpt($f['kind_id']);
		$names->set_tpl('{opts}',$res);
		$names->tpl_parse();
		$txt.=$names->template;
		
		return $txt;
	}
	
	*/
	
	
	
	
	//!!!!!!!!!!!!!!клиентский вывоД
	
	
	
	
	
	
	
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