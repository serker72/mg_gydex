<?
require_once('abstractitem.php');
 

//абстрактный файл
class AbstractFileItem extends AbstractItem{
	 
	protected $storage_name;
	protected $storage_path;
	
	
	public function __construct( ){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->tablename='file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='user_id';
			
		 
		$this->storage_path=ABSPATH.'upload/news/';	
	}
	
	
	public function GetStoragePath(){
		return $this->storage_path;	
	}
	
	
	 
	
	public function GetPageName(){
		return $this->pagename;	
	}
	

	
	//удалить
	public function Del($id){
		$item=$this->GetItemById($id);
		if($item!==false){
		
		  @unlink($this->storage_path.$item['filename']);
		  parent::Del($id);
		}
	}	
	
	
	public function SetPageName($pagename){
		$this->pagename=$pagename;	
	}
}
?>