<?
require_once('distr_groups.php');
require_once('distr_groupitem.php');
require_once('distr_rightsgroup.php');
require_once('distr_objectsgroup.php');
require_once('distr_rightitem.php');
require_once('distr_objitem.php');
require_once('distr_useritem.php');


//класс управления правами
class DistrRightsManager{
	
	//активные права для группы на объект
	public function GetActiveRightsGroup($group_id, $object_id){
		$alls=Array();
		$sql='select distinct d1.id, d1.name, d1.info from discr_rights as d1 inner join discr_group_rights as b2 on(d1.id=b2.right_id) where b2.object_id='.$object_id.' and b2.group_id='.$group_id.' order by d1.id';
		//echo "$sql ";
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		//echo $rc;
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$f['info_esc']=str_replace("\r\n",'\r\n', str_replace("\n",'\n', addslashes($f['info'])));
			$alls[]=$f;
		}
		
		return $alls;
	}
	
	//НЕактивные права для группы на объект
	public function GetInactiveRightsGroup($group_id, $object_id){
		$alls=Array();
		
		$sql ='select distinct d1.id, d1.name, d1.info from discr_rights as d1 where d1.id not in (select distinct b2.right_id from discr_group_rights as b2 where b2.object_id='.$object_id.' and b2.group_id='.$group_id.') order by d1.id';
		
		//echo $sql;
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
//		echo $rc;
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$f['info_esc']=str_replace("\r\n",'\r\n', str_replace("\n",'\n', addslashes($f['info'])));
			$alls[]=$f;
		}
		
		return $alls;
	}
	
	
	//установить права группе на объект
	public function GrantOnObjectToGroup($right_id, $object_id, $group_id){
		$sql ='select count(*) from discr_group_rights where right_id='.$right_id.' and object_id='.$object_id.' and group_id='.$group_id.' ';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		if($f[0]==0){
			//есть ли право
			$d1=new DistrRightItem; 
			$d11=$d1->GetItemById($right_id);
			//есть ли объект
			$d2=new DistrObjItem; 
			$d21=$d2->GetItemById($object_id);
			//есть ли группа
			$d3=new DistrGroupItem; 
			$d31=$d3->GetItemById($group_id);
			
			if(($d11!=false)&&($d21!=false)&&($d31!=false)){
								
				$sql='insert into discr_group_rights (right_id, object_id, group_id) values('."$right_id, $object_id, $group_id".')';
				$sset=new NonSet($sql);
				
				
			}else{
				return false;	
			}
			
			
			return true;
		}else{
			return false;	
		}
		
	}
	
	//удалить права у группы на объект
	public function RevokeFromObjectToGroup($right_id, $object_id, $group_id){
		
		$sql_qc='select count(*) from discr_group_rights where right_id="'.$right_id.'" and object_id="'.$object_id.'" and group_id="'.$group_id.'" ';
		
		//echo $sql_qc;
		
		$set=new MysqlSet($sql_qc);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		if($f[0]>0){
			$sql_del='delete from discr_group_rights where right_id='.$right_id.' and object_id='.$object_id.' and group_id='.$group_id.' ';
			$sset=new NonSet($sql_del);
			
			
			return true;
		}else{
			//echo ' qqqq';
			return false;	
		}
	}
	
	
///////////////////////////////////////////////////////////////////////////////////////////
//права пользователя
///////////////////////////////////////////////////////////////////////////////////////////

	////активные права для пол-ля на объект
	public function GetActiveRightsUser($user_id, $object_id){
		$alls=Array();
		
		
		$alls=$this->_summary_rights_union($user_id, $object_id); //($this->_rights_user_self_level($user_id, $object_id), $this->_rights_user_group_level($user_id, $object_id));
		
		return $alls;
	}
	
	//права на уровне пол-ля
	/*protected function _rights_user_self_level($user_id, $object_id){
		$alls=Array();
		
		$sql='select distinct d1.id, d1.name, d1.info from discr_rights as d1 inner join discr_user_rights as b2 on(d1.id=b2.right_id) where b2.object_id='.$object_id.' and b2.user_id='.$user_id.' order by d1.id';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));	
			}
			$alls[]=$f;
		}
		//echo count($alls);
		return $alls;
	}
	
	//права на уровне групп пол-ля
	protected function _rights_user_group_level($user_id, $object_id){
		$alls=Array();
		
		$sql='select distinct d1.id, d1.name, d1.info from discr_rights as d1 inner join discr_group_rights as b2 on(d1.id=b2.right_id) where b2.object_id='.$object_id.' and b2.group_id in (select distinct group_id from discr_users_by_groups where user_id='.$user_id.') order by d1.id';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));
			}
			$alls[]=$f;
		}
		//echo count($alls);
		return $alls;
	}
	
	//суммарные права
	protected function _summary_rights($rights_self, $rights_group){
		$summary=Array();
		$rights_self_=Array(); $rights_self_=$rights_self;
		$rights_group_=Array();  $rights_group_=$rights_group;
		
		//пробежим права пользователя
		 сравним попунктно с каждым элементом прав группы
		   если ==, то вносим пп в суммарный массив (self=true) и удаляем пг из прав группы
		   если !=, то вносим пп в сумм массив (self=true) не делаем ничего
		foreach($rights_self_ as $k=>$v){
			$v['self']=true;
			$summary[]=$v;
			foreach($rights_group_ as $kk=>$vv){
				if($v==$vv){
					unset($rights_group_[$kk]);
				}else{
					
				}
			}
		}
		
		//пробежим оставшиеся права групп
		сравним попунктно с уже имеющимся суммарным массивом
		  если == удаляем пг из прав группы
		  если != вносим пг в суммарный массив (self=false) и удаляем пг из прав группы
		 
		 foreach($rights_group_ as $k=>$v){
			 
			 
			 foreach($summary as $kk=>$vv){
				if($v==$vv){
					unset($rights_group_[$k]);	
				}else{
					
				}
			}
		 }
		
		//все что осталось в группах и чего нет в summary
		foreach($rights_group_ as $k=>$v){
			$v['self']=false;
			$summary[]=$v;
			unset($rights_group_[$k]);
			
		}
		
		return $summary;
	}*/
	
	
	//суммарные права
	protected function _summary_rights_union($user_id, $object_id){
		$summary=Array();
		
		
		$sql='select distinct d1.id, d1.name, d1.info, "0" from discr_rights as d1 inner join discr_group_rights as b2 on(d1.id=b2.right_id) where b2.object_id='.$object_id.' and b2.group_id in (select distinct group_id from discr_users_by_groups where user_id='.$user_id.') ';
		
		$sql2='select distinct d1.id, d1.name, d1.info, "1" from discr_rights as d1 inner join discr_user_rights as b2 on(d1.id=b2.right_id) where b2.object_id='.$object_id.' and b2.user_id='.$user_id.'  ';
		
		$sql=" ($sql) UNION ($sql2) order by 1";
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$f['self']=($f[3]==1);
			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));
			}
			$summary[]=$f;
		}
		
		return $summary;
	}
	
	
	//НЕактивные права для пол-ля на объект
	public function GetInactiveRightsUser($user_id, $object_id){
		$alls=Array(); $actives=Array();
		
		$actives=$this->_summary_rights_union($user_id, $object_id); //($this->_rights_user_self_level($user_id, $object_id), $this->_rights_user_group_level($user_id, $object_id));
		
		$nots=Array();
		foreach($actives as $k=>$v){
			$nots[]=$v['id'];
		}
		$nots_str=join(', ', $nots);
		
		if($nots_str!='') $sql ='select distinct d1.id, d1.name, d1.info from discr_rights as d1 where d1.id not in ('.$nots_str.') order by d1.id';
		else $sql ='select distinct d1.id, d1.name, d1.info from discr_rights as d1 order by d1.id';
			
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));
			}
			$alls[]=$f;
		}
		//echo count($alls);
		return $alls;
	}

	
	//установить права юзеру на объект
	public function GrantOnObjectToUser($right_id, $object_id, $user_id){
		$sql ='select count(*) from discr_group_rights where right_id='.$right_id.' and object_id='.$object_id.' and group_id in(select distinct group_id from discr_users_by_groups where user_id='.$user_id.') ';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		
		$sql1 ='select count(*) from discr_user_rights where right_id='.$right_id.' and object_id='.$object_id.' and user_id='.$user_id.'';
		$set1=new MysqlSet($sql1);
		$rs1=$set1->GetResult();
		$f1=mysqli_fetch_array($rs1);
		
		if(($f[0]==0)&&($f1[0]==0)){
			//есть ли право
			$d1=new DistrRightItem; 
			$d11=$d1->GetItemById($right_id);
			//есть ли объект
			$d2=new DistrObjItem; 
			$d21=$d2->GetItemById($object_id);
			//есть ли группа
			$d3=new DistrUserItem; 
			$d31=$d3->GetItemById($user_id);
			
			if(($d11!=false)&&($d21!=false)&&($d31!=false)){
								
				$sql='insert into discr_user_rights (right_id, object_id, user_id) values('."$right_id, $object_id, $user_id".')';
				$sset=new NonSet($sql);
				
				
			}else{
				return false;	
			}
			
			
			return true;
		}else{
			return false;	
		}
		
	}
	
	//удалить права у юзера на объект
	public function RevokeFromObjectToUser($right_id, $object_id, $user_id){
		
		$sql_qc='select count(*) from discr_user_rights where right_id="'.$right_id.'" and object_id="'.$object_id.'" and user_id="'.$user_id.'" ';
		
		//echo $sql_qc;
		
		$set=new MysqlSet($sql_qc);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		if($f[0]>0){
			$sql_del='delete from discr_user_rights where right_id='.$right_id.' and object_id='.$object_id.' and user_id='.$user_id.' ';
			$sset=new NonSet($sql_del);
			
			
			return true;
		}else{
			//echo ' qqqq';
			return false;	
		}
	}
	
	
	
	
//определим, есть ли у юзера данный доступ к данному объекту
	public function CheckAccess($login, $passw, $access_name, $object_id){
		$di=new DistrUserItem;
		
		$sql_u='select * from discr_users where login="'.$login.'" and passw="'.$passw.'" and is_blocked=0 limit 1';
		$set=new MysqlSet($sql_u);
		$rc=$set->GetResultNumRows();
		if($rc>0){
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
		
			//пол-ль есть, получим массив прав
			$user_id=$f['id'];
			$rights_summary=$this->_summary_rights_union($user_id, $object_id);
			
			foreach($rights_summary as $k=>$v){
				if($v['name']==$access_name)	return true;
			}
		}
		return false;
	}
	
}
?>