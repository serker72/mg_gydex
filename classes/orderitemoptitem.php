<?
require_once('abstractitem.php');

//опция для Товара в заказе
class OrderItemOptItem extends AbstractItem{
	
	//установка всех имен
	protected function init(){
		$this->tablename='order_item_option';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
		
	}
	
	
	
	
	
	
}
?>