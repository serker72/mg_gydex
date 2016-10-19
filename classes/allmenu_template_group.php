<?

require_once('NonSet.php');
require_once('MysqlSet.php');
require_once('PageNavigator.php');

require_once('abstractgroup.php');
//require_once('smarty/SmartyAdm.class.php');


// абстрактная группа итемов (без языковых таблиц)
class AllmenuTemplateGroup extends AbstractGroup {
 
	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu_template';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	
}
?>