<?
require_once('abstractgroup.php');
require_once('useritem.php');
require_once('groupsgroup.php');

// список клиентов
class UsersGroup extends AbstractGroup {
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='clients';
		$this->pagename='viewclients.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		$this->name_vis_check='tpl/clients/subitem_lang_vis_check.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($group_id=0,$from=0,$to_page=10){
		//список позиций
		$txt='';
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_client.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('group_id',$group_id);
		
		$smarty->assign('listpagename',$this->pagename);
		
		$query='select c.id, c.login, c.username, c.address, c.email, c.phone, c.is_mailed, c.is_blocked, rk.name, l.lang_flag, l.lang_name, c.skidka from 
		((clients as c LEFT JOIN langs as l ON c.lang_id=l.id) 
		LEFT JOIN reg_kind as rk ON c.reg_id=rk.id)';
		if($group_id!=0) $query.=' where c.id in (select clid from cl_by_groups where gr_id="'.$group_id.'") ';
		$query.='order by c.username, c.login ';
		
		$query_count='select count(*) from 
		((clients as c LEFT JOIN langs as l ON c.lang_id=l.id) 
		LEFT JOIN reg_kind as rk ON c.reg_id=rk.id)';
		if($group_id!=0) $query_count.=' where c.id in (select clid from cl_by_groups where gr_id="'.$group_id.'")';
		
//		echo $query;
		
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&group_id='.$group_id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$gg=new GroupsGroup();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//c.id, c.login, c.username, c.address, c.email, c.phone, c.is_mailed, c.is_blocked, rk.name, l.lang_flag, l.lang_name, c.skidka
			
			$u=new UserItem();
			
			$alls[]=Array('itemno'=>$f['id'], 
			'itemname'=>'<strong>'.stripslashes($f[1]).'</strong><br><em>'.stripslashes($f[2]).'</em><br>'.'<img src="/'.stripslashes($f[9]).'" alt="'.stripslashes($f[10]).'" border="0">',
			'email'=>stripslashes($f[4]),
			'is_mailed'=>$f[6],
			'phone'=>stripslashes($f[5]).'&nbsp;',
			'address'=>stripslashes($f[3]).'&nbsp;',
			'skidka'=>sprintf("%.0f",$f[11]),
			'is_banned'=>$f[7],
			'origin'=>stripslashes($f[8]),
			'orders_count'=>$u->CalcItemsByClid($f[0]),
			'in_groups'=>$gg->GetItemsByClid($f[0],$from,$to_page,$group_id,'clients/in_group.html'),
			'usein'=>$gg->DrawGroupsOptNot($f[0])
			);
		}
		
		
		$smarty->assign('all_groups',$gg->GetItemsOpt($group_id));
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		
		return $txt;
	}
	
	
	//список покупателей В и НЕ В группе
	public function GetItemsByGroupId($group_id=0,$inverse=false){
		//список позиций
		$txt='';
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('filename','ed_client.php');
		
		$smarty->assign('listpagename',$this->pagename);
		
		
		
		$query='select c.id, c.login, c.username, c.address, c.email, c.phone, c.is_mailed, c.is_blocked, rk.name, l.lang_flag, l.lang_name, c.skidka from 
		((clients as c LEFT JOIN langs as l ON c.lang_id=l.id) 
		LEFT JOIN reg_kind as rk ON c.reg_id=rk.id)';
		if($group_id!=0) {
			if($inverse) $query.=' where c.id not in (select clid from cl_by_groups where gr_id="'.$group_id.'") ';
			else $query.=' where c.id in (select clid from cl_by_groups where gr_id="'.$group_id.'") ';
		}else{
			if($inverse) $query.=' where c.id="0"';
		}
		$query.='order by c.login, c.username ';
//		echo $query;
		
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//c.id, c.login, c.username, c.address, c.email, c.phone, c.is_mailed, c.is_blocked, rk.name, l.lang_flag, l.lang_name, c.skidka
			
			if($f[7]==1) $is_banned ='блокирован<br>';
			else $is_banned ='';
			
			if($f[6]==1) $is_mailed='подписан на рассылку<br>';
			else $is_mailed='';
			
			$alls[]=Array('itemno'=>$f['id'], 
			'name'=>stripslashes($f[2]),
			'login'=>stripslashes($f[1]),
			'email'=>stripslashes($f[4]),
			'is_mailed'=>$is_mailed,
			'phone'=>stripslashes($f[5]).'&nbsp;',
			'address'=>stripslashes($f[3]).'&nbsp;',
			'skidka'=>sprintf("%.0f",$f[11]),
			'banned'=>$is_banned
			);
		}
		
		if($inverse) $smarty->assign('tabno','2');
		else  $smarty->assign('tabno','1');
		
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