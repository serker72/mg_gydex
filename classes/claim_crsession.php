<?
require_once('abstractitem.php');

//������ ������������
class ClaimCreationSession extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='claim_creation_session';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='mid';
		
	}
	//������� ����� ������� �� ���� �������, ����� ������� 
	public function CountLogins($login,$sid){
		$this->DelSession($user_id);
		
		$set=new mysqlSet('select count(*) from '.$this->tablename.' where login="'.$login.'" and SID<>"'.$sid.'"');
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$f=mysqli_fetch_array($rs);	
		
		return $f[0];
	}
	
	
	public function AddSession(  $login){
		$this->Add(array(  'login'=>$login, 'pdate'=>time(), 'SID'=>session_id(), 'ip'=>getenv('REMOTE_ADDR')));
	}
	
	
	//������ ������
	public function DelSession($SID){
		new NonSet('delete from '.$this->tablename.' where SID="'.$SID.'"');
	}
	
	
	
	//������� ������ ������
	public function ClearOldSessions($time_interval=900){
		//���� ����� �� ������� ����� ���� ����� - �������� ������� ������!
		$etalon=time()-(2*60*60);
		$set=new mysqlSet('select count(*) from '.$this->tablename.' where pdate>='.$etalon);
		$rs=$set->getResult(); 
		$f=mysqli_fetch_array($rs);
		if($f[0]==0){
			new NonSet('truncate '.$this->tablename);	
		}
		
		
		//������� ������, �� ����������� 15 ����� � �����
		new NonSet('delete from '.$this->tablename.' where pdate<'.(time()-$time_interval).'');	
	}
	
}
?>