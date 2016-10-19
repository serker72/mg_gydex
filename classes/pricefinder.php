<?
require_once('global.php');
require_once('priceitem.php');

//класс определени€ цены и общей стоимости
/*стуктура дл€ работы:
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
	protected $goods=Array(); //список товаров
	protected $uniqs=Array(); //список уник товаров
	
	public function __construct(){
	}
	
	//установка файла корзины и нахождение дл€ него массива уникальных товаров
	public function SetArrays($goods){
		$this->goods=$goods;
		$this->GenerateUniqGoodId();
	}
	
	
	//получение цены, кол-ва, стоимости по коду товара
	//подсчет стоимости позиции
	public function FindPrices($good_id,$lang_id){
		$priceblock=Array();
		//запрашиваем кол-во товара в уникальном списке
		$quant=$this->FindQuantInUniq($good_id);
		
		$pi=new PriceItem();
		$pi->price_disp->DefineCurrencyByLangId($lang_id);
		$price=$pi->price_disp->GetPriceValue($good_id, $quant, $lang_id);//$_SESSION['lang']);
		
		if(!is_array($price)){
			 //ошибка
			 $err_code=10; //ошибка вычислени€ цены
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
	
	//подсчет общей суммы покупок
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
		
		//перебираем массив уникальных товаров, умножаем цену на к-во и суммируем
		$pi=new PriceItem();
		$pi->price_disp->DefineCurrencyByLangId($lang_id);
		
		foreach($this->uniqs as $k=>$v){
			$price=$pi->price_disp->GetPriceValue($v['good_id'], $v['quantity'],  $lang_id);
			if(!is_array($price)){
			 //ошибка при получении цены позиции
				continue; 
			}else{
				//запомним валюту дл€ занесени€ в результат
				if($currency_sign===NULL) $currency_sign=$price['currency_sign'];
				if($currency_id===NULL) $currency_id=$price['currency_id'];
				
				$total+=$price['value']*$v['quantity'];
			}
		}
		$total=$pi->price_disp->FormatPrice($total,'',2);
		if(($currency_sign===NULL)||($currency_sign===NULL)){
			//ошибка, ни разу не нашли валюту
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
	
	
	
	
	
	
	
//********************************************* —Ћ”∆≈ЅЌџ≈ ‘”Ќ ÷»» *********************************************
	
	//запрашиваем кол-во товара в уникальном списке
	protected function FindQuantInUniq($good_id){
		$qty=0;
		foreach($this->uniqs as $k=>$v){
			if($v['good_id']==$good_id){
				$qty=$v['quantity']; break;
			}
		}
		return $qty;
	}
	
	
	
	
	//построение списка уникальных товаров-кол-ва
	protected function GenerateUniqGoodId(){
		$temparr=$this->goods;
		foreach($temparr as $k=>$v){
			foreach($temparr as $kk=>$vv){
				if($k!=$kk){
					if($v['good_id']==$vv['good_id']){
					// равенство записей по коду товара
						unset($temparr[$k]);
						unset($temparr[$kk]);
						$temparr[$kk]=Array(
							'good_id' => $v['good_id'],
							'lang_id' => $v['lang_id'],
							'quantity' => ((int)$v['quantity']+(int)$vv['quantity']),
							'values_arr' => Array(),
							'comment' => $v['comment']);
						
					}else{
						//записи не равны
					}
				}
			}
		}
		unset($this->uniqs); $this->uniqs=$temparr;
		return $temparr;
	}
}
?>