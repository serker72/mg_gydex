<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');


//итем товар
class CurrencyItem extends AbstractLangItem{
	//protected $subkeyname;
	//protected $dict_kind_tablename='dict_kind';
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='currency';
		$this->lang_tablename='currency_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
//		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	//добавить 
	public function Add($params, $lang_params){
		$mid=AbstractLangItem::Add($params, $lang_params);
		//echo $mid; die();
		$this->ModCurrFlags($mid, $params);
		return $mid;
	}
	
	
	//править
	public function Edit($id,$params=NULL, $lang_params=NULL){
		
		
		
		AbstractLangItem::Edit($id,$params, $lang_params);
		$this->ModCurrFlags($id, $params);
		
		
	}
	
	
	//удалить
	public function Del($id){
		//удал€ть ¬—≈!!!!
		
		$this->DelChilds($id);
		
		
		$it1=new AbstractItem(); 
		
		//параметры не€зыковые
		$it1->SetTableName($this->tablename);
		$it1->Del($id);
		unset($it1);
		
		//параметры €зыковые
		$query = 'delete from '.$this->lang_tablename.' where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		$this->item=NULL;
	}	
	
	
	//удаление всех подчиненных таблиц данной валюты
	public function DelChilds($id){
	
		//использованние по €зыкам
		$query = 'delete from currency_use_lang where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		//использование по ценам товаров
		$query = 'delete from good_price where curr_id='.$id.';';
		$it=new nonSet($query);
		
	}
	
	//групповое удаление по перечисленным номерам разделов
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
	
	//сброс флагов основна€ и базова€ валюта у всех, кроме данной валюты
	protected function ModCurrFlags($notid, $params){
		//проверить и уточнить значени€ флагов основна€ и базова€ валюта.
		if(isset($params['is_base_shop'])&&($params['is_base_shop']==1)){
			//сбросить все кроме этой
			$sql='update '.$this->tablename.' set is_base_shop="0" where id<>"'.$notid.'"';
			$set=new nonSet($sql);
		}
		if(isset($params['is_base_rate'])&&($params['is_base_rate']==1)){
			//сбросить все кроме этой
			$sql='update '.$this->tablename.' set is_base_rate="0" where id<>"'.$notid.'"';
			$set=new nonSet($sql);
			
			$sql='update '.$this->tablename.' set rate="1" where id="'.$notid.'"';
			$set=new nonSet($sql);
		}
	}
	
	//показ подитемов
	
}
?>