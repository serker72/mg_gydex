<?
require_once('abstractlangitem.php');

//������ ������
class StatusItem extends AbstractLangItem {
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='order_status';
		$this->lang_tablename='order_status_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='status_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	
	//�������
	public function Del($id){
		//��������, �������� �� ������ ���������, ���� �������� - �� �������!
		if(($id<1)||($id>5)){
			//������� ���!!!!
			//������� ������� �������
			$query = 'update orders set '.$this->mid_name.'="0" where '.$this->mid_name.'="'.$id.'";';
			$it=new nonSet($query);
			AbstractLangItem::Del($id);
		}
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
	}
	
}
?>