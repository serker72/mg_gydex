<?
require_once('abstractitem.php');

//итем цена товара
class GoodsPrice extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='good_price';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	//нахождение цены по айди товара, айди вида цены
	public function GetPriceByGoodIdPriceId($good_id, $price_id){
		$sql='select * from '.$this->tablename.' where good_id="'.$good_id.'" and price_id="'.$price_id.'"';
		//$sql='select * from '.$this->tablename.' as t INNER JOIN price as p ON (t.price_id=p.id) where t.good_id="'.$good_id.'" and t.price_id="'.$price_id.'"';
		//echo $sql;
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		//echo " $rc ";
		
		if($rc==0) return false;
		
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		return $f;
	}
}
?>