<?
require_once('abstractitem.php');
require_once('ordersgroup.php');

//юзер итем
class UserItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='clients';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//добавить 
	public function Add($params){
		
		$login=$params['login'];
		$cou=$this->CheckLogin($login,NULL);
		
		if($cou==0) return AbstractItem::Add($params);
		else return false;
	}
	
	//добавить с корректировкой логина
	public function AddAdmin($params){
		
		$login=$params['login'];
		//$cou=$this->CheckLogin($login,NULL);
		$params['login']=$this->GenUniqLogin($login,NULL);
		
		return AbstractItem::Add($params);
	}
	
	//генерим ЛОГИН уникальный!
	public function GenUniqLogin($url,$except=NULL){
		//$url;
		$cou=$this->CheckLogin($url,$except);
		$cter=1; $over=0;
		while($cou>0){
			$url.=$cter;
			$cou=$this->CheckLogin($url,$except);
			if(strlen($url)>80) {
				$url=$over;
				$over++;
				$cter=1;
			}
			$cter++;
		}
		return $url;
	}
	
	//править
	public function Edit($id,$params){
		//если логин содержится в параметрах, то проверять его
		if(isset($params['login'])){
			$login=$params['login'];
			$cou=$this->CheckLogin($login,$id);
			if($cou==0) {
				AbstractItem::Edit($id,$params);
				return true;
			}else return false;
		}else{
			AbstractItem::Edit($id,$params);
			return true;
		}
	}
	
	//править с корректировкой логина
	public function EditAdmin($id,$params){
		//если логин содержится в параметрах, то проверять его
		if(isset($params['login'])){
			$login=$params['login'];
			$params['login']=$this->GenUniqLogin($login,$id);
			AbstractItem::Edit($id,$params);
			return true;
		}else{
			AbstractItem::Edit($id,$params);
			return true;
		}
	}
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		//удалить из заказов
		
		//из групп
		$query = 'delete from cl_by_groups where clid='.$id.';';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
	}
	
	//добавление юзера в группу
	public function AddUserToGroup($clid, $group_id){
		$qtest='select count(*) from cl_by_groups where clid="'.$clid.'" and gr_id="'.$group_id.'" ';
		$s=new mysqlSet($qtest);
		$rs=$s->GetResult();
		$f=mysqli_fetch_array($rs);
		if($f['count(*)']==0){
			$query = 'insert into cl_by_groups (clid, gr_id) values("'.$clid.'","'.$group_id.'");';
			$it=new nonSet($query);
		}
	}
	
	//удаление юзера из группы
	public function DelUserFromGroup($clid,$group_id){
		//из групп
		$query = 'delete from cl_by_groups where clid="'.$clid.'" and gr_id="'.$group_id.'" ;';
		$it=new nonSet($query);
	}
	
	//поиск юзера по логину
	public function GetUserByLogin($login){
		$sql='select * from '.$this->tablename.' where login="'.$login.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f;
		}else{
			return NULL;
		}
	}
	
	//поиск юзеров по email (их может быть много с тем же имэйлом)
	public function GetUsersByEmail($email,&$cou){
		$sql='select * from '.$this->tablename.' where email="'.$email.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$cou=$rc;
		if($rc>0){
			return $rs;
		}else{
			return NULL;
		}
	}
	
	
	
	//подсчет количества клиентов с таким логином
	public function CheckLogin($login,$except=NULL){
		$sql='select count(*) from '.$this->tablename.' where login="'.$login.'"';
		
		//echo $sql;
		if($except!==NULL) $sql.=' and id<>"'.$except.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		return $f['count(*)'];
	}
	
	
	//подсчет общего количества заказов покупателя
	public function CalcItemsByClid($clid){
		$og=new OrdersGroup();
		return $og->CalcItemsByClid($clid);
	}
	
}
?>