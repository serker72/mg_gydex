<?
require_once('abstractlanggroup.php');
require_once('statusitem.php');


//группа статусов заказа
class StatusGroup extends AbstractLangGroup{
	protected $name_multilang;
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='order_status';
		$this->lang_tablename='order_status_lang';
		$this->pagename='viewstatus.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='status_id';
		$this->lang_id_name='lang_id';	
		$this->name_multilang='tpl/status/subitem_name.html';
		
		$this->name_vis_check='tpl/status/subitem_lang_vis_check.html';
	}
	
	
	
	//список итемов
	public function GetItems($from=0,$to_page=10){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_status.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$params=Array();
		$paramsord=Array();
		$paramsord[]=' id ';
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
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			/*
			$parse_item=new parse_class();
			
			if(($f['id']<1)||($f['id']>5)) $parse_item->get_tpl($this->menuitem_template);
			else $parse_item->get_tpl($this->menuitem_template_blocked);
			//параметры имени
			
			$np='';
			$np.=$this->GetNameParams($f,$from,$to_page);
			$parse_item->set_tpl('{itemname}',$np);
		//	$parse_item->set_tpl('{image_path}',stripslashes($f['photo_small']));
			
			//параметры
			$pars=$this->GetDescrParams($f,$from,$to_page);
			$parse_item->set_tpl('{itemdescr}',$pars);

			$parse_item->set_tpl('{itemno}',$f['id']);
			$parse_item->set_tpl('{fromno}',$from);
			$parse_item->set_tpl('{topage}',$to_page);
			$parse_item->set_tpl('{filename}','ed_status.php');
			
			$parse_item->tpl_parse();
			
			$strs.=$parse_item->template;
			//echo stripslashes($f['name']);
			//echo $strs;
			*/
			
			//параметры имени
			//параметры
			$names=Array(); $params=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new StatusItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'],  'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'],  'descr'=>stripslashes($mmi['descr']) );
			}
			
			if(($f['id']<1)||($f['id']>5)) $is_del=true; else $is_del=false;
			
			$alls[]=Array('itemno'=>$f['id'], 'nameitems'=>$names, 'valitems'=>$params, 'is_del'=>$is_del);
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		return $txt;
		
		
		return $txt;
	}
	
	
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';

		
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
	
	
	//получение списка имен на разных языках
	protected function GetNameParams($f,$from,$to_page){
		$txt='';
		
		$sql='select * from langs order by id';
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0;$i<$rc;$i++){
			$g=mysqli_fetch_array($rs);
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			
			$mi=new StatusItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
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
	
	
	//получение списка описаний на разных языках
	protected function GetDescrParams($f,$from,$to_page){
		$txt='';
		
		$sql='select * from langs order by id';
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0;$i<$rc;$i++){
			$g=mysqli_fetch_array($rs);
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			
			$mi=new StatusItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}','');
				$names->set_tpl('{vistext}',':'.stripslashes($mmi['descr']));		
			}else {
				$names->set_tpl('{itemname}','');
				$names->set_tpl('{vistext}','<em>не создано</em>');		
			}
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		return $txt;
	}
	
	
	//итемы в тегах option
	public function GetItemsOptByLang_id($current_id=0,$fieldname='name',$lang_id=LANG_CODE,$undef_name='-не определен-'){
		$txt='';
		$sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where l.'.$this->lang_id_name.'='.$lang_id.' and l.'.$this->mid_name.'=t.id order by t.id';
		$set=new mysqlSet($sql);
		$tc=$set->GetResultNumRows();
		
		$txt.="<option value=\"0\" ";
		if($current_id==0) $txt.='SELECTED';
		$txt.=">$undef_name</option>";
		
		if($tc>0){
			$rs=$set->GetResult();
			for($i=0;$i<$tc;$i++){
				$f=mysqli_fetch_array($rs);
				$txt.="<option value=\"$f[id]\" ";
				
				if($current_id==$f['id']) $txt.='SELECTED';
				
				$txt.=">".htmlspecialchars(stripslashes($f[$fieldname]))."</option>";
			}
		}
		return $txt;
	}
}
?>