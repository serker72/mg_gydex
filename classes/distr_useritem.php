<?
require_once('abstractitem.php');
require_once('useritem.php');


//���� ���-��
class DistrUserItem extends UserItem{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='discr_users';
	
		$this->item=NULL;
		$this->pagename='page.php';		
		
		
		$this->vis_name='is_blocked';
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		//
		$query = 'delete from discr_users_by_groups where user_id='.$id.';';
		$it=new nonSet($query);
		
		$query = 'delete from discr_user_rights where user_id='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractItem::Del($id);
	}	
	
	
	//�������� ���-�� � ������
	public function AddUserToGroup($group_id, $user_id){
		$s1= 'select count(*) from discr_users_by_groups where user_id='.$user_id.' and group_id='.$group_id.' ';
		$set=new mysqlSet($s1);
		$rs=$set->GetResult();
		$fs=mysqli_fetch_array($rs);
		//echo 'qqqqq'; die();
		
		if($fs[0]==0){
			
			
			$s2='insert into discr_users_by_groups (user_id, group_id) values('.$user_id.','.$group_id.')';
			$it=new nonSet($s2);
			
			return true;
		}else return false;
	}
	
	//������� ���-�� �� ������
	public function DelUserFromGroup($group_id, $user_id){
		$s1='delete from discr_users_by_groups where user_id='.$user_id.' and group_id='.$group_id.'';
		//echo $s1;
		$it=new nonSet($s1);
			
		return true;
	}
}
?>