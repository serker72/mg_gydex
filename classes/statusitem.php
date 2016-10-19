<?
require_once('abstractlangitem.php');

//статус заказа
class StatusItem extends AbstractLangItem {
	
	//установка всех имен
	protected function init(){
		$this->tablename='order_status';
		$this->lang_tablename='order_status_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='status_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	
	//удалить
	public function Del($id){
		//проверка, €вл€етс€ ли статус системным, если €вл€етс€ - не удал€ть!
		if(($id<1)||($id>5)){
			//удал€ть ¬—≈!!!!
			//обнулим статусы заказов
			$query = 'update orders set '.$this->mid_name.'="0" where '.$this->mid_name.'="'.$id.'";';
			$it=new nonSet($query);
			AbstractLangItem::Del($id);
		}
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
}
?>