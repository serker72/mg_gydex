<?
require_once('abstractgroup.php');
require_once('useritem.php');


// список групп клиентов
class GroupsGroup extends AbstractGroup {
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='cl_groups';
		$this->pagename='viewgroups.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		//$this->all_menu_template='';
	}
	
	
	
	//список всех групп
	public function GetItems($from=0,$to_page=10){
		//список позиций
		$txt='';
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_group.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		
		
		$query='select * from '.$this->tablename.' order by name';
		$query_count='select count(*) from '.$this->tablename.' ';
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
			
			$alls[]=Array('itemno'=>$f['id'], 'name'=>stripslashes($f['name']), 'descr'=>stripslashes($f['descr']));
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	//список групп по айди клиента
	public function GetItemsByClid($clid,$from=0,$to_page=10,$group_id=0,$template=''){
		//список позиций
		$txt='';
		
		$query='select t.id, t.name from 
		'.$this->tablename.' as t , cl_by_groups as cg where 
		t.id=cg.gr_id and cg.clid="'.$clid.'"
		order by t.name
		';
		
		$set=new mysqlSet($query);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			$sm=new SmartyAdm;
			$sm->debugging = DEBUG_INFO;
			
			$sm->assign('name',stripslashes($f[1]));
			$sm->assign('fromno',$from);
			$sm->assign('topageno',$to_page);
			$sm->assign('group_id',$group_id);
			$sm->assign('cl_id',$clid);
			$sm->assign('gr_id',$f[0]);
			
			$txt.=$sm->fetch($template);
		}
		return $txt;
	}
	
	//cписок групп в которых НЕ состоит клиент
	public function DrawGroupsOptNot($clid){
		$txt='';
		$sql='select t.id, t.name from '.$this->tablename.' as t where t.id not in(select gr_id from cl_by_groups where clid="'.$clid.'") order by t.name;';
		$item=new mysqlSet($sql);
		$rs=$item->getResult();
		$rc=$item->getResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$txt.='<option value="'.$f['id'].'">'.stripslashes($f['name']).'</option>';
		}
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
	
	
	
	//итемы в тегах option
	public function GetItemsOpt($current_id=0,$fieldname='name'){
		$txt='';
		$sql='select * from '.$this->tablename;
		$set=new mysqlSet($sql);
		$tc=$set->GetResultNumRows();
		
		$txt.="<option value=\"0\" ";
		if($current_id==0) $txt.='SELECTED';
		$txt.="> -все группы- </option>";
		
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