<?

require_once('abstractlanggroup.php');
require_once('pricesitem.php');
require_once('conditem.php');


// ���� ���
class PricesGroup extends AbstractLangGroup {
		
	//��������� ���� ����
	protected function init(){
		$this->tablename='price';
		$this->lang_tablename='price__lang';
		$this->pagename='viewprices.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';	
		
		
		$this->all_menu_template='prices/items.html';
		$this->menuitem_template='tpl/prices/itemsrow.html';
		$this->menuitem_template_blocked='tpl/prices/itemsrow.html';
		$this->razbivka_template='tpl/prices/to_page.html';
		
		$this->name_multilang='tpl/prices/subitem_name.html';
		
		$this->name_vis_check='tpl/prices/subitem_lang_vis_check.html';
	}
	
	
	
	//������ ������
	public function GetItems($from=0,$to_page=ITEMS_PER_PAGE){
		//������ �������
		$txt=''; $lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_pr.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$params=Array(); $ord=Array(); $ord[]=' ord desc '; $ord[]=' id ';
		$query=$this->GenerateSQL($params,NULL,$ord,$query_count);
		//$txt.=$query;
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
			
			//�������� ����
			$names=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new PricesItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=1;
				}else {
					$is_exist=0;
				}
				
				$names[]=Array('is_exist'=>$is_exist,  'lang_name'=>strip_tags($g['lang_name']),
				 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'name'=>stripslashes($mmi['name']), 'descr'=>stripslashes($mmi['descr']) );
			}
			
			
			//������� �������� ����
			$condd=new CondItem();
			if($f['cond_id']==0){
				$has_cond=0;
			}else{
				$has_cond=1;
			}
			
			$all_[]=Array('itemno'=>$f['id'], 'is_formula'=>$f['use_formula'], 'is_base'=>$f['is_base'], 'formula'=>$f['formula'], 'ident'=>$f['formula_name'], 'has_cond'=>$has_cond, 'used'=>$condd->ShowCond($f['cond_id']), 'nameitems'=>$names);
		}
		
		$smarty->assign('items',$all_);
		$smarty->assign('pages',$pages);
		$txt.=$smarty->fetch($this->all_menu_template); //������� ���� ���������
		
		return $txt;
	}
	
	
	//������ ������
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//������ �������
		$txt='';
		
		
		return $txt;
	}
	
	
	
	
	
	//������ ������
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//������ �������
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
	}
	
	//������ ������ ������ ��� ������
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//������ �������
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	
	//��������� ������ ���� �� ������ ������
	/*
	protected function GetNameParams($f,$from,$to_page){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			
			$names->set_tpl('{filename}','ed_pr.php');
			$names->set_tpl('{fromno}',$from);
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="������ �� ����� '.strip_tags($g['lang_name']).'" title="������ �� ����� '.strip_tags($g['lang_name']).'" border="0">');
			
			$mi=new PricesItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{descr}','<br>'.stripslashes($mmi['descr']));
				//$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', '�����','','tpl/alldicts/subitem_lang_vis_check.html',$g['id'].'_'));		
			}else {
				$names->set_tpl('{itemname}','<em>�� �������</em>');
				$names->set_tpl('{descr}','');
				//$names->set_tpl('{vistext}','');		
			}
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		return $txt;
	}*/
	
	
	
	protected function GenerateSQL($params, $notparams=NULL, $orderbyparams=NULL, &$sql_count=''){
		$sql='';
		
		$sql='select * from '.$this->tablename;
		
		//������ ��� ������� ������ ����� ������
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
	
	
	//������� ���������� id ������� ���� �����
	public function GetBasePriceId(){
		$sql='select id from '.$this->tablename.' where is_base="1"';
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f[0];
		}else return false;
	}
	
	//������� ���������� ������� ���� ����� �� ����� �� ���������
	public function GetBasePrice(){
		$sql='select * from price as t, price__lang as l where t.id=l.value_id and t.is_base="1" and l.lang_id="'.LANG_CODE.'"';
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f;
		}else return false;
		
	}
}
?>