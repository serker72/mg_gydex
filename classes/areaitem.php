<?
require_once('abstractitem.php');

//область действия условия цены
class AreaItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='area_cond_price';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	
}
?>