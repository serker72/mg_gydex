<?
require_once('abstractitem.php');
require_once('ordersgroup.php');

//���� ����
class UserItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='clients';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//�������� 
	public function Add($params){
		
		$login=$params['login'];
		$cou=$this->CheckLogin($login,NULL);
		
		if($cou==0) return AbstractItem::Add($params);
		else return false;
	}
	
	//�������� � �������������� ������
	public function AddAdmin($params){
		
		$login=$params['login'];
		//$cou=$this->CheckLogin($login,NULL);
		$params['login']=$this->GenUniqLogin($login,NULL);
		
		return AbstractItem::Add($params);
	}
	
	//������� ����� ����������!
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
	
	//�������
	public function Edit($id,$params){
		//���� ����� ���������� � ����������, �� ��������� ���
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
	
	//������� � �������������� ������
	public function EditAdmin($id,$params){
		//���� ����� ���������� � ����������, �� ��������� ���
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
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		//������� �� �������
		
		//�� �����
		$query = 'delete from cl_by_groups where clid='.$id.';';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
	}
	
	//���������� ����� � ������
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
	
	//�������� ����� �� ������
	public function DelUserFromGroup($clid,$group_id){
		//�� �����
		$query = 'delete from cl_by_groups where clid="'.$clid.'" and gr_id="'.$group_id.'" ;';
		$it=new nonSet($query);
	}
	
	//����� ����� �� ������
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
	
	//����� ������ �� email (�� ����� ���� ����� � ��� �� �������)
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
	
	
	
	//������� ���������� �������� � ����� �������
	public function CheckLogin($login,$except=NULL){
		$sql='select count(*) from '.$this->tablename.' where login="'.$login.'"';
		
		//echo $sql;
		if($except!==NULL) $sql.=' and id<>"'.$except.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		return $f['count(*)'];
	}
	
	
	//������� ������ ���������� ������� ����������
	public function CalcItemsByClid($clid){
		$og=new OrdersGroup();
		return $og->CalcItemsByClid($clid);
	}
	
}
?>