<?
require_once('abstractitem.php');

// итем вид регистрации uzera
class RegKindItem extends AbstractItem{
	
	//установка всех имен
	protected function init(){
		$this->tablename='reg_kind';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//из юзеров - обнулить у них вид регистрации
		$query = 'update clients set reg_id=0 where reg_id="'.$id.'";';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
	
}
?>