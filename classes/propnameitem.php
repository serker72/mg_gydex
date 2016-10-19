<?
require_once('abstractlangitem.php');

//итем свойство в словаре
class PropNameItem  extends  AbstractLangItem{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;

	
	
	//установка всех имен
	protected function init(){
		$this->tablename='prop_name';
		$this->lang_tablename='prop_name_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='name_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	//проверка по коду значени€ наличи€ значени€, имени, товара
	public function CheckPropertyByValId(&$returned_good_id,&$returned_name_id,$val_id,$lang_id=LANG_CODE){
		$result=0;
		
		$vi=new PropValItem();
		$val=$vi->GetItemById($val_id,$lang_id,1);
		if($val==false){
			$err_code=8; //нет такого значени€
			return $err_code;
		}
		$returned_good_id=$val['item_id'];
		$returned_name_id=$val['name_id'];
		//есть ли такое свойство
		$pn=new PropNameItem();
		$pname=$pn->GetItemById($val['name_id'],$lang_id,1);
		if($pname==false){
			$err_code=9; //нет такого имени
			return $err_code;
		}
		
		
		return $result;
	}
	
	
	
	//удалить
	public function Del($id){
		//удал€ть ¬—≈!!!!
		
		//из опций заказа
		$query = 'delete from order_item_option where value_id in(select id from prop_value where '.$this->mid_name.'='.$id.');';
		$it=new nonSet($query);
		
		//свойства
		$query = 'delete from prop_value_lang where value_id in(select id from prop_value where '.$this->mid_name.'='.$id.');';
		$it=new nonSet($query);
		
		$query = 'delete from prop_value where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractLangItem::Del($id);
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
	
}
?>