<?
require_once('global.php');
require_once('priceitem.php');

//����� ����������� ���� � ����� ���������
/*�������� ��� ������:
[]=Array(
	good_id
	lang_id
	quantity
	values_arr=Array(
		value_id
	)
	comment
)*/

class PriceFinder{
	protected $goods=Array(); //������ �������
	protected $uniqs=Array(); //������ ���� �������
	
	public function __construct(){
	}
	
	//��������� ����� ������� � ���������� ��� ���� ������� ���������� �������
	public function SetArrays($goods){
		$this->goods=$goods;
		$this->GenerateUniqGoodId();
	}
	
	
	//��������� ����, ���-��, ��������� �� ���� ������
	//������� ��������� �������
	public function FindPrices($good_id,$lang_id){
		$priceblock=Array();
		//����������� ���-�� ������ � ���������� ������
		$quant=$this->FindQuantInUniq($good_id);
		
		$pi=new PriceItem();
		$pi->price_disp->DefineCurrencyByLangId($lang_id);
		$price=$pi->price_disp->GetPriceValue($good_id, $quant, $lang_id);//$_SESSION['lang']);
		
		if(!is_array($price)){
			 //������
			 $err_code=10; //������ ���������� ����
			 return $err_code;
		}
		
		$price['value']=$pi->price_disp->FormatPrice($price['value'],'',2);
		$cost=$pi->price_disp->FormatPrice($price['value']*$quant, '', 2);
		$priceblock=Array(
			'value'=>$price['value'],
			'currency_id'=>$price['currency_id'],
			'currency_sign'=>$price['currency_sign'],
			'quantity'=>$quant,
			'cost'=>$cost
		);
		
		return $priceblock;
	}
	
	//������� ����� ����� �������
	public function FindCost($lang_id){
		$costblock=Array();
		
		$total=0; $currency_sign=NULL; $currency_id=NULL;
		if(count($this->uniqs)==0){
			$costblock=Array(
				'cost'=>0,
				'currency_id'=>$currency_id,
				'currency_sign'=>$currency_sign,
			);
			return $costblock;
		}
		
		//���������� ������ ���������� �������, �������� ���� �� �-�� � ���������
		$pi=new PriceItem();
		$pi->price_disp->DefineCurrencyByLangId($lang_id);
		
		foreach($this->uniqs as $k=>$v){
			$price=$pi->price_disp->GetPriceValue($v['good_id'], $v['quantity'],  $lang_id);
			if(!is_array($price)){
			 //������ ��� ��������� ���� �������
				continue; 
			}else{
				//�������� ������ ��� ��������� � ���������
				if($currency_sign===NULL) $currency_sign=$price['currency_sign'];
				if($currency_id===NULL) $currency_id=$price['currency_id'];
				
				$total+=$price['value']*$v['quantity'];
			}
		}
		$total=$pi->price_disp->FormatPrice($total,'',2);
		if(($currency_sign===NULL)||($currency_sign===NULL)){
			//������, �� ���� �� ����� ������
			 $err_code=11; //
			 return $err_code;
		}
		
		$costblock=Array(
			'cost'=>$total,
			'currency_id'=>$currency_id,
			'currency_sign'=>$currency_sign
		);
		
		return $costblock;
	}
	
	
	
	
	
	
	
//********************************************* ��������� ������� *********************************************
	
	//����������� ���-�� ������ � ���������� ������
	protected function FindQuantInUniq($good_id){
		$qty=0;
		foreach($this->uniqs as $k=>$v){
			if($v['good_id']==$good_id){
				$qty=$v['quantity']; break;
			}
		}
		return $qty;
	}
	
	
	
	
	//���������� ������ ���������� �������-���-��
	protected function GenerateUniqGoodId(){
		$temparr=$this->goods;
		foreach($temparr as $k=>$v){
			foreach($temparr as $kk=>$vv){
				if($k!=$kk){
					if($v['good_id']==$vv['good_id']){
					// ��������� ������� �� ���� ������
						unset($temparr[$k]);
						unset($temparr[$kk]);
						$temparr[$kk]=Array(
							'good_id' => $v['good_id'],
							'lang_id' => $v['lang_id'],
							'quantity' => ((int)$v['quantity']+(int)$vv['quantity']),
							'values_arr' => Array(),
							'comment' => $v['comment']);
						
					}else{
						//������ �� �����
					}
				}
			}
		}
		unset($this->uniqs); $this->uniqs=$temparr;
		return $temparr;
	}
}
?>