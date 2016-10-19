<?
require_once('NonSet.php');
require_once('MysqlSet.php');
require_once('abstractitem.php');

//привязка словаря
class DictAttachItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='dict_attach_d';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	
}
?>