<?
require_once('abstractgroup.php');
require_once('dictattdisp.php');


//группа опций заказа для итема заказа
class OrderItemOptGroup extends AbstractGroup {
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='order_item_option';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	//вывод всех опций для заказа с подсветкой выбранных и возможностью замены
	//по номеру итема заказа построить массив из имеющихся кодов значений
	//вызвать из ф-ии работы со словарями ф-ю для построения списка опций заказа
	public function GetOrderItemOptions($good_id,$lang_id,$set_tpl,$item_tpl,$order_item_id,$something='',$for_big_list=false,$order_id_in_list=''){
		$v=Array();
		 $dicts=new DictAttDisp(3);
		//строим массив
		$sql='select * from '.$this->tablename.' where item_id="'.$order_item_id.'"';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$v[]=Array(
				'value_id'=>$f['value_id']
			);
		}
		
		if($for_big_list) $res=$dicts->DrawDictsCli(2, $good_id, $lang_id,$set_tpl, $item_tpl,'',$v, ($order_id_in_list.'_'.$order_item_id.'_'),$something).'';
		else $res=$dicts->DrawDictsCli(2, $good_id, $lang_id,$set_tpl, $item_tpl,'',$v,$order_item_id.'_',$something).'';
		return $res;
	}
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';

		
		return $txt;
	}
	
	
	
	
	
	//список итемов
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
	}
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	
	
}
?>