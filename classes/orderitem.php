<?
require_once('abstractitem.php');
require_once('pricefinder.php');
require_once('pricedisp.php');
require_once('currencyitem.php');
require_once('priceitem.php');
require_once('orderitemsgroup.php');

//���� �����
class OrderItem extends AbstractItem{
	
	//����������������
	public $price_finder;
	
	//������ ������ ������
	public $order_items;
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='orders';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
		
		$this->price_finder=new PriceFinder();
		$this->order_items=new OrderItemsGroup();
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		//�������������� �������
		if(HAS_OST){
			$query='select sum(quantity), good_id from  order_item  where order_id='.$id.' group by good_id ';
			
			$it=new mysqlSet($query);
			$rs=$it->GetResult();
			$rc=$it->GetResultNumRows();
			for($i=0; $i<$rc; $i++){
				$f=mysqli_fetch_array($rs);
				
				$gd=new PriceItem;
				$good=$gd->GetItemById($f[1], LANG_CODE);
				
				if($good!=false) $gd->EditOstatok($f[1], $good['ostatok']+$f[0]);
			}
		}
		
		//�� ����� ������� �� ������
		$query = 'delete from order_item_option where item_id in(select id from order_item where order_id='.$id.');';
		$it=new nonSet($query);
		
		//�� ������� ������
		$query = 'delete from order_item where order_id='.$id.';';
		$it=new nonSet($query);
		
		
		AbstractItem::Del($id);
	}	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
	}
	
	//���������������� ������
	public function UserEdit($order_id,$user_id,$params){
		if($this->OrderIsChangeable($order_id, $user_id)){
			
			$qq=''; $pi=new PriceItem;
			foreach($params as $key=>$val){
				if($key=='positions'){
					//������ ������� ������
					
					foreach($val as $kk=>$vv){
						//echo "$kk posiciya:<br>";
						//����� ���� ������� � vv
						$pos_params=Array(); $pos_opts=Array();
						$oii=new OrderItemItem();
						foreach($vv as $kkk=>$vvv){
							//echo "pp $kkk = $vvv<p>";
							if($kkk!='values_arr'){
								 
								 if(HAS_OST){
									 //�������� �������� �������
									 if($kkk=='quantity'){
										 //������ ������ �������
										 $old_pos=$oii->GetItemById($kk);
										 $old_quant=$old_pos['quantity'];
										 $good=$pi->GetItemById($old_pos['good_id'], LANG_CODE);
										 $old_ost=$good['ostatok'];
										 $new_ost=$good['ostatok']+($old_quant-$vvv);
										 if($new_ost<0) {
											$vvv= $vvv+$new_ost;
											$new_ost=$good['ostatok']+($old_quant-$vvv);
											
										 }
										 
										 $pi->EditOstatok($old_pos['good_id'], $new_ost);	
									}
								 }
								 $pos_params[$kkk] = $vvv;
							}else{
								
								foreach($vvv as $pk=>$pv){
									//echo "arar  $pk=>$pv<p>";
									$pos_opts[] = $pv;
								}
//								$pos_params['values_arr'] = $pos_;
							}
						}
						
						$oii->Edit($kk,$pos_params,$pos_opts);
					}
				}else{
					//�����, �����, �������...
					if($qq=='') $qq.=$key.'="'.$val.'"';
					else $qq.=', '.$key.'="'.$val.'"';
				}
			}
			
			$query='update '.$this->tablename.' set '.$qq.' where id="'.$order_id.'" and clid="'.$user_id.'";';
			//echo $query;
			$it=new nonSet($query);
		}
	}
	
	
	//���������������� ��������
	public function UserDel($order_id,$user_id){
		if($this->OrderIsChangeable($order_id, $user_id)){
			$this->Del($order_id);
		}
	}
	
	
	//��������, ����� �� (�� �������) ������������ ������� �����
	public function OrderIsChangeable($order_id, $user_id){
		$sql='select st.is_changeable from order_status as st RIGHT JOIN orders as i ON st.id=i.status_id where i.id="'.$order_id.'" and i.clid="'.$user_id.'"';
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			if($f[0]=='1') return true;
			else return false;
		}else return false;
		
	}
	
	
	
	
	
	//��������� ��������� �� ������� ������ � ������
	public function CalcCostAsOrdered($order_id,$lang_id,$user_skidka=0){
		$cost=0; $sumblock=Array(); $currency_id=0;
		
		$sql1='select * from order_item where order_id="'.$order_id.'"';
		$set1=new mysqlSet($sql1);
		$rs1=$set1->GetResult();
		$rc1=$set1->GetResultNumRows();
		$pd=new PriceDisp();
		for($i=0;$i<$rc1;$i++){
			$f=mysqli_fetch_array($rs1);
			//$cost+=($f['price_value']*$f['quantity']);
			//��������� � �������� ������ �����
			$cost+=($pd->ConvertToBaseValue($err_code, $f['price_value'], $f['currency_id']))*$f['quantity'];
			if($f['currency_id']!=0) $currency_id=$f['currency_id'];
			//echo ' '.$pd->ConvertToBaseValue($err_code, $f['price_value'], $f['currency_id']).' ';
		}
		//echo $cost;
		//������������ �� �������� ������ � ������ �� �����
		//������� ����������� �������� ���� �� �������� ������ � ������ �� �������� � �����
		$cost=$pd->ConvertPriceValueByLangId($err_code, $cost,$lang_id,false);
		
		//��� ����������� ������ ������� ���� ������ ��� ��������� �����
		$currency=$pd->GetCurrencyByLangId($err_code,$lang_id);
		
		if($currency!==NULL){
			$currency_id=$currency['id'];
			$currency_sign=$currency['signat'];
		}else{
			$currency_id=NULL;
			$currency_sign=NULL;
		}
		$cost=$cost-($user_skidka*$cost/100);
		$sumblock=Array(
			'cost'=>$cost,
			'currency_id'=>$currency_id,
			'currency_sign'=>$currency_sign
		);
		
		return $sumblock;
	}
	
	//��������� ���������� ���������
	public function CalcCostAsIs($order_id,$lang_id,$user_skidka=0){
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
		
		$sumblock=$this->price_finder->FindCost($lang_id);
		
		if(!is_array($sumblock)){
			unset($sumblock);
			$sumblock=Array(
				'cost'=>NULL,
				'currency_id'=>NULL,
				'currency_sign'=>NULL
			);
		}else{
			//����� ������
			$sumblock['cost']=$sumblock['cost']-($user_skidka*$sumblock['cost']/100);
		}
		
		
		return $sumblock;
	}
	
	
	//��������� ����� �������� ������
	public function GetOrderItems($id,$from=0,$to_page=ITEMS_PER_PAGE,$templates=Array(),$lang_id=LANG_CODE,$user_id=0){
		$this->order_items->SetTemplates($templates['all_menu_template'], $templates['menuitem_template'], $templates['menuitem_template_blocked'], $templates['razbivka_template'],$templates['items_set'],$templates['item_row']);
		return $this->order_items->GetItemsById($id, $from,$to_page,$lang_id,$user_id);
		
	}
}
?>