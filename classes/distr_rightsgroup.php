<?
require_once('abstractgroup.php');
require_once('distr_groupitem.php');
require_once('distr_rightitem.php');
require_once('distr_objitem.php');



//���� ������ ����
class DistrRightsGroup extends AbstractGroup{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='discr_rights';
	
		$this->item=NULL;
		$this->pagename='page.php';		
		//$this->vis_name='is_blocked';	
		
	}
	
	
	//������ ������
	public function GetItemsArr(){
		//������ �������
		$alls=Array();
		
		$sql='select * from '.$this->tablename.' order by id';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$alls[]=$f;
		}
		
		
		return $alls;
	}
	
	
	
	
	
}
?>