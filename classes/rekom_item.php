<?
require_once('abstractitem.php');

//рекомендуемые товары
class RekomItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='goods_rekommend';
		$this->item=NULL;
		$this->pagename='page.php';		
	}
	
	
	
	
	
	
}
?>