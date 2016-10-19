<?
require_once('abstractitem.php');
require_once('pricefinder.php');
require_once('orderitemoptitem.php');
require_once('priceitem.php');
//����� � ������
class OrderItemItem extends AbstractItem{
	//����������������
	public $price_finder;
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='order_item';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
		$this->price_finder=new PriceFinder();
	}
	
	
	//�������
	public function Edit($id,$params,$option_values=NULL){
		AbstractItem::Edit($id,$params);
		if($option_values!==NULL) $this->SyncronizeVals($id,$option_values);
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		//�������� �������
		if(HAS_OST){
			$query='select order_id, good_id, quantity from  order_item  where id='.$id.' ';
			$it=new mysqlSet($query);
			$rs=$it->GetResult();
			$rc=$it->GetResultNumRows();
			if($rc!=0){
				$f=mysqli_fetch_array($rs);
				
				$gd=new PriceItem;
				$good=$gd->GetItemById($f[1], LANG_CODE);
				
				if($good!=false) $gd->EditOstatok($f[1], $good['ostatok']+$f[2]);
				
			}
		}
		
		
		//�� ����� ������� �� ������
		$query = 'delete from order_item_option where item_id='.$id.';';
		$it=new nonSet($query);
		
		
		AbstractItem::Del($id);
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
		
	}
	
	//����������������� ������ ����� ������ � ������ � ����������
	protected function SyncronizeVals($id,$option_values){
		//������ ��� ������������ �����
		$sql='delete from order_item_option where item_id="'.$id.'"';
		$ns=new NonSet($sql);
		
		$oopt=new OrderItemOptItem();
		//������ ����� �����
		foreach($option_values as $k=>$v){
			//echo "$k= $v<br>";
			if($v!='0'){
				$params=Array();
				$params['item_id']=$id;
				$params['value_id']=$v;
				$oopt->Add($params);
			}
		}
	}
	
	//���������� ���������� ���� ������� 
	public function FindPrice($good_id,$lang_id,$order_id,$user_skidka=0){
		$cost=0; $sumblock=Array(); $goods=Array();
		
		$sql1='select * from order_item where order_id="'.$order_id.'"';
		$set1=new mysqlSet($sql1);
		$rs1=$set1->GetResult();
		$rc1=$set1->GetResultNumRows();
		for($i=0;$i<$rc1;$i++){
			$f=mysqli_fetch_array($rs1);
			$goods[]=Array(
				'good_id'=>$f['good_id'],
				'lang_id'=>$lang_id,
				'quantity'=>$f['quantity'],
				'values_arr'=>Array(),
				'comment'=>''
			);
		}
		
		$this->price_finder->SetArrays($goods);
		$sumblock=$this->price_finder->FindPrices($good_id,$lang_id); //FindCost($lang_id);
		if(!is_array($sumblock)){
			unset($sumblock);
			$sumblock=Array(
				'value'=>NULL,
				'currency_id'=>NULL,
				'currency_sign'=>NULL,
				'quantity'=>NULL,
				'cost'=>NULL
			);
		}else{
			//����� ������
			$sumblock['cost']=$sumblock['cost']-($user_skidka*$sumblock['cost']/100);
		}
		return $sumblock;
	}
	
	
	
}
?>