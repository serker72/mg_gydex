<?
require_once('abstractfileitem.php');
 

//����������� ����
class SolFileItem extends AbstractFileItem{
	 
	 
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='solution_file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='solution_id';
			
		 
		$this->storage_path=ABSPATH.'upload/solutions/';	
	}
	
	
	 
}
?>