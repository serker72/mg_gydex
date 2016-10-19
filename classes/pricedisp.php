<?
require_once('currencygroup.php');
require_once('pricesgroup.php');
require_once('goodspriceitem.php');
require_once('mmenuitem.php');
require_once('conditem.php');
require_once('pricefinder.php');

//��������� ��� ��� ������
class PriceDisp{
	protected $tablename='good_price';
	protected $currslist;
	protected $priceslist;
	//���� ���� ������
	protected $goodsprice;
	//����������������
	//public $price_finder;
	
	//protected $table_template='tpl/goodsprices/table.html';
	protected $table_template='price/goodsprices.html';
	protected $table_h='tpl/goodsprices/header.html';
	protected $table_row='tpl/goodsprices/row.html';
	protected $table_cell='tpl/goodsprices/cell.html';		
	protected $button='tpl/goodsprices/button.html';		
	
	protected $currsitem;
	protected $pricesitem;
	protected $curruse;
	
	protected $show_errors=true;
	protected $err_mess=Array();
	
	//��������� ������������ ����:
	protected $base_price; //������� ����
	protected $base_currency; //�������� ������
	protected $base_rate_currency; //������� ������ ��� �����
	protected $work_currency; //������� ������
	
	//����������� ������� ����������� �����
	//�� �������� - � ��������
	protected $to_convert_curr_id=0;
	protected $to_convert_curr_id_rate=0;
	//�� �������� - � ��������
	protected $from_convert_curr_id=0;
	protected $from_convert_curr_id_rate=0;
	
	
	
	public function __construct(){
		$this->init();
	}
	
	//��������� ���� ����
	protected function init(){
		$this->currslist=new CurrencyGroup();
		$this->priceslist=new PricesGroup();
		$this->pricesitem=new PricesItem();
		
		$this->goodsprice=new GoodsPrice();
		
		$this->currsitem=new CurrencyItem();
		$this->curruse=new CurrUse();
		
		
		$this->err_mess[1]='������� ���� ������ �� ������';
		$this->err_mess[2]='���� ������ �� ������';
		$this->err_mess[3]='�� ���������� ������� ���� ��� �����';
		$this->err_mess[4]='�� ������� ��������� ������';
		$this->err_mess[5]='�� ���������� ������� ������ ��� �����';
		$this->err_mess[6]='�� ���������� �������� ������ ��� �����';
		
		$this->base_price=$this->GetBasePrice();
		$this->base_currency= $this->GetBaseCurrency();
		$this->base_rate_currency=$this->GetBaseRateCurrency();
		
		$this->work_currency=false;
	}
	
	//��������� ����� ������ ������
	public function SetShowErrors($flag){
		$this->show_errors=$flag;
	}
	
	//��������� ������� ������� ������, �������� ���� �� ����� �����
	public function GetBasePriceF(){
		return $this->base_price;
	}
	public function GetBaseCurrencyF(){
		return $this->base_currency;
	}
	public function GetBaseRateCurrencyF(){
		return $this->base_rate_currency;
	}
	
	//��������� ������� ������ �� ���� �����, ����� �� � ���� � �������� � ���� �����
	public function DefineCurrencyByLangId($lang_id=LANG_CODE){
		$base_shop_currency=$this->GetBaseCurrencyF();
		$currency=$base_shop_currency;
		
		$privyazka=$this->curruse->GetCurrIdByLangId($lang_id);
		if($privyazka!==false){
			$currency=$this->currsitem->GetItemById($privyazka,$lang_id);
		}
		$this->work_currency=$currency;
		return $currency;
	}
	
	
	//��������� ���� ��������
	public function SetTemplates($templates){
		if(isset($templates['table_template'])) $this->table_template=$templates['table_template'];
		if(isset($templates['table_row'])) $this->table_row=$templates['table_row'];
		if(isset($templates['table_cell'])) $this->table_cell=$templates['table_cell'];		
	}
	
	//����� ��������� �� ������ �� ���� ������
	protected function ShowError($err_code){
		if($this->show_errors) return $this->err_mess[$err_code];
		else return '';
	}
	
	
	//������� ������ ���, ����������� � �� ����������� � ������ � �������
	//SMARTY
	public function DrawPricesById($id,$lang_id=LANG_CODE,$template1='',$inverse=false,$mode=3){
		$txt='';
		//������� ������ ���
		if($mode==3){
			if(!$inverse) $prices=$this->GetAllPricesByGoodId($id,$lang_id);
			else $prices=$this->GetNOTPricesByGoodId($id,$lang_id);
		}else if($mode==1){
			if(!$inverse) $prices=$this->GetAllPricesByMid($id,$lang_id);
			else $prices=$this->GetNOTPricesByMid($id,$lang_id);
		}
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('mode',$mode);
		
		$ci=new CondItem();
		$alls=Array();
		foreach($prices as $k=>$v){
			if($v[2]==1){
				$is_base=true;
				$is_formula=false;
				$formula='';
			}else{
				$is_base=false;
				if($v[3]==1) $is_formula=true; else $is_formula=false;
				$formula=$v[4].'%';
			}
			
			if($v[1]==0){
				$has_cond=false; //$tm2->set_tpl('{useddisp_'.$v[0].'}','none');
			}else{
				$has_cond=true; //$tm2->set_tpl('{useddisp_'.$v[0].'}','block');				
			}
			$alls[]=Array(
				'itemno'=>$v[0],
				'itemname'=>stripslashes($v[6]),
				'used'=>$ci->ShowCond($v[1]),
				'is_base'=>$is_base,
				'is_formula'=>$is_formula,
				'formula'=>$formula,
				'has_cond'=>$has_cond
			);
		}
		$smarty->assign('prices',$alls);
		$txt=$smarty->fetch($template1);
		return $txt;
	}
	
	
	
	//���������� �������� ���� (�����, ���������� ������) �� ���� ������, ���������� ������, ���� �����
	//��� �������, ������
	public function GetPriceValue($good_id, $quant, $lang_id){
		
		//�������� ������ ��� ��� ������ �� ���� ������
		$prices=$this->GetAllPricesByGoodId($good_id,$lang_id);
		
		//������� �������� ������ �����
		$currency=$this->work_currency;
		
		//������� ������� ����, �� ����� ����� � ���� ��� ���� ������
		//��������� ��� ���� ������, �������� ������� (�� ����������)
		//���� �������� ���� �� ���� ������� - ������ ��� ���� ����� ������� �����.
		$do_format=false;
		$base_price=$this->GetGoodBasePriceInBaseCurrency($good_id,$do_format);
		$the_price=$base_price;
		//����������� ���� �� �������� ������ � ������ ��������
		$the_price=$this->ConvertPriceValueByCurrId($err_code, $the_price, $currency['id']);
		
		$co=new CondItem();
		//������� �� �����
		foreach($prices as $k=>$v){
			//p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr
			//p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value
			
			//������� ������� ���������� ����
			if($v[1]!=0){
				//������ �������
				if(($quant>=$v[8])&&($quant<=$v[9])){
					//���� ������������
					
					//��������� �������� ����
					$value=$this->RetrievePrice($good_id, $v[0], $currency['id'], $err_code);
					if($err_code!=0) {
						//echo ' ff ';
						return $this->ShowError($err_code);
					}
					$the_price=$value;
					break;
				}
			}
		}
		
		$price_res=Array(
			'value'=>$the_price,
			'currency_id'=> $currency['id'],
			'currency_sign'=> $currency['signat']
		);
		
		return $price_res;
	}
	
	//���������� ������� ��������� ������� ���� � ������� ������ �� ���� ������, �����
	public function GetGoodBasePriceInBaseCurrency($good_id,$do_format=true){
		$res='';
		$err_code=0;
		
		
		//������ �������� ������
		$curr=$this->GetBaseCurrencyF();
		if($curr==false){
			$err_code=6;
			return $this->ShowError($err_code);
		}
		//������� � ����
		$signat=$curr['signat'];
		
		//������ �������� ����
		$price=$this->GetBasePriceF();
		if($price==false) {
			$err_code=3;
			return $this->ShowError($err_code);
		}//return '<em>�� ���������� ������� ���� ��� �����!</em>';
		
		//������ ��� ������ ������� ���� � ������� ������
		$gp=$this->goodsprice->GetPriceByGoodIdPriceId($good_id, $price['id']);
		if($gp==false) {
			$err_code=1;
			return $this->ShowError($err_code);
		}//return '<em>�� ������ ������� ���� ������!</em>';
		
		
		$value=$gp['value'];
		
		
		if($do_format) $res.='<strong>'.$value.'</strong>';
		else $res=$value;
		return $res;
	}
	
	
	//���������� ������� ��������� ������� ���� �� ���� ������, �����
	public function GetGoodBasePrice($good_id,$lang_id=LANG_CODE,$do_format=true){
		$res='';
		$err_code=0;
		
		
		//������ �������� ������
		$curr=$this->GetBaseCurrencyF();
		if($curr==false){
			$err_code=6;
			return $this->ShowError($err_code);
		}
		//������� � ����
		$signat=$curr['signat'];
		
		//������ �������� ����
		$price=$this->GetBasePriceF();
		if($price==false) {
			$err_code=3;
			return $this->ShowError($err_code);
		}//return '<em>�� ���������� ������� ���� ��� �����!</em>';
		
		//������ ��� ������ ������� ���� � ������� ������
		$gp=$this->goodsprice->GetPriceByGoodIdPriceId($good_id, $price['id']);
		if($gp==false) {
			$err_code=1;
			return $this->ShowError($err_code);
		}//return '<em>�� ������ ������� ���� ������!</em>';
		
		
		$value=$gp['value'];
		//� value � ��� ����� ���� � �������� ������.
		//������� �������� ������ ��� ��������� �����
		//���� �������� ���, �� ������� ����� ��� ����
		$value=$this->ConvertPriceValueByLangId($err_code, $value,$lang_id,$do_format);
		if($err_code!=0) {
			//echo ' ff ';
			return $this->ShowError($err_code);
		}
		
		if($do_format) $res.='<strong>'.$value.'</strong>';
		else $res=$value;
		return $res;
	}
	
	
	//������� ��������� �������� ����
	public function RetrievePrice($good_id, $price_id, $curr_id, &$err_code){
		$txt=''; $value='';
		$err_code=0;
		
		$current_price_record=$this->goodsprice->GetPriceByGoodIdPriceId($good_id, $price_id);
		
		//�������� �� ��� ���� �������
		$curr_price=$this->pricesitem->GetItemById($price_id);
		if($curr_price['is_base']==1){
		//������� ����
			$base_price=$curr_price;
			if($current_price_record==false){
				//return '������� ���� ������ �� ������';
				$err_code=1;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}else{
				$value=$current_price_record['value'];
			}
			
		}else{
		//�� ������� ����
			//������ ���� �������
			$base_price=$this->GetBasePriceF();
			if($base_price==false) {
				//return '������� ���� ������ �� ������';
				$err_code=3;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			
			//��������� ��� ����� ���� ����, ������ �� ��� �� ������� �� ������� ����, ��� �������� ����
			if($curr_price['use_formula']==1){
				//��, �� �������
				//���������, ���� �� ���������� ��� ���� ����
				//���� ���� ������ � ���������� ����, ������� ��������
				//���� ��� - ������� �� �������
				if(($current_price_record!=false)&&($current_price_record['not_use_formula']==1)){
					//���� ����������, ������� ����� ��������
					$value=$current_price_record['value'];
				}else{
					//������� �� �������
					$base_price_record=$this->goodsprice->GetPriceByGoodIdPriceId($good_id, $base_price['id']);
					if($base_price_record==false) {
						//return '������� ���� ������ �� ������';
						$err_code=1;
						return $this->ShowError($err_code);//$this->err_mess[$err_code];
					}
					$value=(float)$base_price_record['value']-(float)$base_price_record['value']*(int)$curr_price['formula']/100;
				}
				
			}else{
				//�������� ����
				if($current_price_record==false){
					//return '���� ������ �� ������';
					$err_code=2;
					return $this->ShowError($err_code);//$this->err_mess[$err_code];
				}else{
					//���� ������, ������� �� ��������
					$value=$current_price_record['value'];
				}
			}
		}
		
		//����������� ����� �� �������� ������ � ��������
		$value=$this->ConvertPriceValueByCurrId($err_code,$value, $curr_id);
		
		if($err_code!=0) {
			//echo ' ff ';
			return $this->ShowError($err_code);
		}
		//����������� ����
		$value=$this->FormatPrice($value, '', 2);
		
		$txt.=$value;
		return $txt;
	}
	
	
	
	
	
	
	
	
	
	
	//����������� ������� ��� ��� ������
	//smarty!
	public function GetPriceTableByGoodId($id){
		$txt='';
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		//������ ��� ���������:
		$this->currslist->GetItemsForOverall($currids);
		$smarty->assign('currs',$currids);
		$smarty->assign('goodid',$id);
		
		//�������� ������ ��� ��� ������ �� ���� ������
		$prices=$this->GetAllPricesByGoodId($id,LANG_CODE);
		
		//������� �������� ������ �����
		$base_shop_currency=$this->GetBaseCurrencyF();
		
		//$rows='';
		$alls=Array();
		//������� �� �����
		
		foreach($prices as $k=>$v){
			//p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr
			
			$cells=Array();
			if($v[2]=='1') $classname='itemshead'; //��������� ���������� 
			else $classname='itemscell'; //��������� ���������� 
			
			$cells[]=Array(
				'align'=>'left',
				'valign'=>'middle',
				'class'=>$classname,
				'has_button'=>false,
				'textplace'=>'<strong>'.stripslashes($v[6]).'</strong>'
			);
			
			//���������� ������� �� �������
			//���� ������, � ������ � ������ ������
			foreach($currids as $kk=>$vv){
				$curr_id=$vv['id'];
				
				if(($v[2]=='1')||($base_shop_currency['id']==$curr_id)) $classname='itemshead';  //��������� ���������� 
				else $classname='itemscell'; //��������� ���������� 
				
				//������� ����
				$value=$this->RetrievePrice($id, $v[0], $curr_id,$err_code);
				//err_code==1 ==2 - �������� ������ ��� ������� ����
				
				if((($err_code==1)||($err_code==2))&&($curr_id==$base_shop_currency['id'])){
					 $has_button=true; //$tm4->set_tpl('{buttonplace}',$tm5->template);
				}else if(($err_code==0)&&($curr_id==$base_shop_currency['id'])){
					 $has_button=true; 
				}else $has_button=false; //$tm4->set_tpl('{buttonplace}','<br><br>');
				
				$cells[]=Array(
					'align'=>'center',
					'valign'=>'middle',
					'class'=>$classname,
					'has_button'=>$has_button,
					'textplace'=>$value,
					'priceid'=>$v[0]
				);
			}
			$alls[]=Array('vals'=>$cells);
		}
		
		$smarty->assign('prices',$alls);
		$txt=$smarty->fetch($this->table_template);
		return $txt;
	}
	
	
	
	
	
	
	
	//������� ����������� �������� ���� �� �������� ������ � ������ �� �������� � �����
	public function ConvertPriceValueByLangId(&$err_code, $value,$lang_id=LANG_CODE,$do_format=true){
		$err_code=0;
		//� value � ��� ����� ���� � �������� ������.
		//������� �������� ������ ��� ��������� �����
		//���� �������� ���, �� ������� ����� ��� ����
		$privyazka=$this->curruse->GetCurrIdByLangId($lang_id);
		
		
		if($privyazka!==false){
			$value=$this->ConvertPriceValueByCurrId($err_code, $value, $privyazka,$lang_id,$do_format);
			
			if($err_code!=0) return $this->ShowError($err_code);
			
			$given_currency=$this->currsitem->GetItemById($privyazka,$lang_id);
			$signat=$given_currency['signat'];
		}else{
			$base_shop_currency=$this->GetBaseCurrencyF();
			if($base_shop_currency==false){
				$err_code=6;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			$signat=$base_shop_currency['signat'];
		}
		
	//	echo " <strong>$err_code</strong> ";
		if($do_format) $value = $this->FormatPrice($value, '&nbsp;'.stripslashes($signat),2);
		return $value;
	}
	
	//����������� ���� �� �������� ������ � ������ ��������
	public function ConvertPriceValueByCurrId(&$err_code, $value, $curr_id){
		$err_code=0;
		
		/*if(($this->to_convert_curr_id_rate!=0)&&( $this->to_convert_curr_id==$curr_id)){
			return $value/$this->to_convert_curr_id_rate;
		}else{*/
			$this->to_convert_curr_id=$curr_id;
			//�������� ������
			$given_currency=$this->currsitem->GetItemById($curr_id);
			$base_rate_currency=$this->GetBaseRateCurrencyF();
			$base_shop_currency=$this->GetBaseCurrencyF();
			
			if($given_currency==false){
				$err_code=4;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			if($base_rate_currency==false){
				$err_code=5;
				//echo " <strong>$err_code</strong> ";
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			if($base_shop_currency==false){
				$err_code=6;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			
			//value - �������� � �������� ������
			//�� �������� - � ������� ��� �����
			$old_value=$value;
			$value=$value/(float)$base_shop_currency['rate'];
			//�� ������� ��� ����� - � ��������
			$value=$value*(float)$given_currency['rate'];
			
			if($value!=0) $this->to_convert_curr_id_rate=$old_value/$value;
		//}
		return $value;
	}
	
	
	//����������� ���� � �������� ������ � �������� ������ �����
	public function ConvertToBaseValue(&$err_code,$value,$curr_id){
		$err_code=0;
		/*if(($this->from_convert_curr_id_rate!=0)&&( $this->from_convert_curr_id==$curr_id)){
			return $value/$this->from_convert_curr_id_rate;
		}else{*/
			$this->from_convert_curr_id=$curr_id;
			//�������� ������
			$given_currency=$this->currsitem->GetItemById($curr_id);
			$base_rate_currency=$this->GetBaseRateCurrencyF();
			$base_shop_currency=$this->GetBaseCurrencyF();
			
			if($given_currency==false){
				$err_code=4;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			if($base_rate_currency==false){
				$err_code=5;
				//echo " <strong>$err_code</strong> ";
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			if($base_shop_currency==false){
				$err_code=6;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			
			//value - �������� � �������� ������
			//�� �������� ������ - � ������� ��� �����
			$value=$value/(float)$given_currency['rate'];//$base_shop_currency['rate'];
			
			//�� ������� ��� ����� - � ��������
			$old_value=$value;
			$value=$value*(float)$base_shop_currency['rate'];
			if($value!=0) $this->from_convert_curr_id_rate=$old_value/$value;
		//}
		return $value;
	}
	
	
	//��������� ������ �� ���� �����
	public function GetCurrencyByLangId(&$err_code,$lang_id=LANG_CODE){
		$err_code=0;
		//� value � ��� ����� ���� � �������� ������.
		//������� �������� ������ ��� ��������� �����
		//���� �������� ���, �� ������� ����� ��� ����
		$privyazka=$this->curruse->GetCurrIdByLangId($lang_id);
		$block=NULL;
		
		if($privyazka!==false){
			if($err_code!=0) return $this->ShowError($err_code);
			$block=$this->currsitem->GetItemById($privyazka,$lang_id);
		}else{
			$base_shop_currency=$this->GetBaseCurrencyF();
			if($base_shop_currency==false){
				$err_code=6;
				return $this->ShowError($err_code);//$this->err_mess[$err_code];
			}
			$block=$base_shop_currency;
		}
		return $block;
	}
	
	
	
	
	
	
	//��������� ������� ������
	public function GetBaseCurrency(){
		return $this->currslist->GetBaseCurrency();
	}
	
	//��������� ������� ����
	public function GetBasePrice(){
		return $this->priceslist->GetBasePrice();
	}
	
	
	//��������� ������� ������ ��� ���������� ������
	protected function GetBaseRateCurrency(){
		return $this->currslist->GetBaseRateCurrency();
	}
	
	
	
//*************************���������� ������� ��� �� �����(��� ������, ��� �����) **********************************************
//����������� ������� ��� ��� ������
public function GetPriceTableByGoodIdLangId($good_id,$lang_id){
		$txt=''; $all='';
		//rez massiv: owibka, rez-ty
		$result=Array(
			'error'=>'',
			'results'=>Array()
		);
		$alls=Array();
		
		//������ �������� ������
		$curr=$this->GetBaseCurrencyF();
		if($curr==false){
			$err_code=6;
			$result['error']=$this->ShowError($err_code);
			return $result;
			//$this->ShowError($err_code);
		}
		//������� � ����
		$signat=$curr['signat'];
		
		//������ �������� ����
		$base_price=$this->GetBasePriceF();
		if($base_price==false) {
			$err_code=3;
			$result['error']=$this->ShowError($err_code);
			return $result;
			//return $this->ShowError($err_code);
		}
		
		//����� ��� ���� ��� ������
		//�������� ������ ��� ��� ������ �� ���� ������
		$prices=$this->GetAllPricesByGoodId($good_id,$lang_id);
		
		//����� ��������� ������� - ������� �� �����
		$row=Array();
		foreach($prices as $k=>$v){
			//p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr
			
			$row[]=Array(
				'class'=>'itemshead',
				'align'=>'center',
				'valign'=>'top',
				'buttonplace'=>'<strong>'.stripslashes($v[7]).'</strong>',
				'textplace'=>''
			);
			
		}
		$alls[]=Array(
			'pcells'=>$row
		);
		
		$row=Array();
		//����� ��������������� ��� - ������� �� �����
		foreach($prices as $k=>$v){
			//p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr
			
			
			//��������� �������� ����
			$value=$this->RetrievePrice($good_id, $v[0], $curr['id'], $err_code);
			if($err_code!=0) {
				//echo ' ff ';
				//return $this->ShowError($err_code);
				$result['error']=$this->ShowError($err_code);
				return $result;
			}
			
			
			//� value - ���� � �������� ������
			//����� ������ ��� �����, ��������� � ���
			$value=$this->ConvertPriceValueByLangId($err_code, $value,$lang_id);
			if($err_code!=0) {
				$result['error']=$this->ShowError($err_code);
				return $result;
				//return $this->ShowError($err_code);
			}
			
			$row[]=Array(
				'class'=>'itemscell',
				'align'=>'center',
				'valign'=>'middle',
				'buttonplace'=>'',
				'textplace'=>$value
			);
		}
		
		$alls[]=Array(
			'pcells'=>$row
		);
		$result['results']=$alls;
		return $result;
	}	
//********************����� ���������� ������� ��� �� �����(��� ������, ��� �����) **********************************************
	
		
	
//************************** ���� ��������� ������� ���, ���. � �� ���. ��� ������ � �������� � ��������� ************************//
	//!!!!!! ������ ��� ��� ������ �� ���� ������
		//1- ���������� ����
		//1.1 � ������������ �� ����������, �� ��� ��� �� ������� ��������
		//2 - ���� �� ����������������� ������������ � ����� ������ (���=3)
		//3 - �� ��������, ��� ����� ���� ����� (���=1)
		//4 - ���=2 �� �������� � ���� ��� ������������ ���������
		//5 - ������ ������ ���� ������� ����
	public function GetAllPricesByGoodId($id,$lang_id=LANG_CODE){
		//1
		$sql1='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value  from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where (p.cond_id="0" or p.cond_id in(select id from cond_price where area_id="0")) ';
		
		//$sql1='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where (p.cond_id="0" or p.cond_id in(select id from cond_price where area_id="0")) and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql1;
		
		//2
		$sql2='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value  from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="3" and key_value="'.$id.'")  ';
		
		//$sql2='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="3" and key_value="'.$id.'")  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql2;
		
		//3
		$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="1" and key_value in(select mid from price_item where id="'.$id.'"))  ';
		
		//$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="1" and key_value in(select mid from price_item where id="'.$id.'"))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql3;
		
		$sql5='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.is_base="1" and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//$sql5='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.is_base="1" and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql5;
		
		
		//4 
		$str=''; 		$cter=0;
		$i=new PriceItem();
		$ii=$i->GetItemById($id);
		if($ii!=false){
			$mi=new MmenuItem();
			
			//!!!! ��� ���������, �� �����������!!! :)
			$razds=$mi->RetrievePath($ii['mid'], $flaglost, $vloj);
	
			foreach($razds as $k=>$v){
				foreach($v as $key=>$val){
					//echo "$key - $val<br>";
					if($cter!=0)  $str.=',';
					$str.=" $key ";
					$cter++;
				}
			}
			//echo $str;
			$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="2" and key_value in('.$str.'))  ';
			
			//$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="2" and key_value in('.$str.'))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
			$sql="($sql1) UNION ($sql2) UNION ($sql3) UNION ($sql4) UNION ($sql5) order by 6 desc, 7";
			$set=new mysqlSet($sql);
		}else{
			//������ �� ������� � ����
			$sql="($sql1) UNION ($sql2) UNION ($sql3) UNION ($sql5) order by 6 desc, 7";
			$set=new mysqlSet($sql);
		}
		
		
		$result=Array();
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$result[]=$f;
		}
		return $result;
	}
	
	//!!!!!! ������ ��� ��� ������ ����� ���� ������
	public function GetNOTPricesByGoodId($id,$lang_id=LANG_CODE){
		
		//2
		$sql2='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="3" and key_value<>"'.$id.'")  ';
		
		//$sql2='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="3" and key_value<>"'.$id.'")  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql2;
		
		//3
		$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="1" and key_value in(select mid from price_item where id<>"'.$id.'"))  ';
		
		//$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="1" and key_value in(select mid from price_item where id<>"'.$id.'"))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql3;
		
		//4 
		$str=''; 		$cter=0;
		$i=new PriceItem();
		$ii=$i->GetItemById($id);
		if($ii!=false){
			$mi=new MmenuItem();
			
			//!!!! ��� ���������, �� �����������!!! :)
			$razds=$mi->RetrievePath($ii['mid'], $flaglost, $vloj);
	
			foreach($razds as $k=>$v){
				foreach($v as $key=>$val){
					//echo "$key - $val<br>";
					if($cter!=0)  $str.=',';
					$str.=" $key ";
					$cter++;
				}
			}
			//echo $str;
			$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr, c.ffrom, c.tto, c.key_value from price as p INNER JOIN price__lang as l ON(p.id=l.value_id and l.lang_id="'.$lang_id.'") LEFT JOIN cond_price as c ON(p.cond_id=c.id) where p.cond_id in(select id from cond_price where area_id="2" and key_value not in('.$str.')) ';
			
			//$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="2" and key_value not in('.$str.'))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
			$sql="($sql2) UNION ($sql3) UNION ($sql4)  order by 6 desc, 7";
		}else $sql="($sql2) UNION ($sql3)  order by 6 desc, 7";
		
		$sql="($sql2) UNION ($sql3) UNION ($sql4)  order by 6 desc, 7";
		$set=new mysqlSet($sql);
		
		$result=Array();
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$result[]=$f;
		}
		return $result;
	}
	
	
	
	//!!!!!!!!!!!!! ���� ��� �������
	public function GetAllPricesByMid($id,$lang_id=LANG_CODE){
		//1
		$sql1='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where (p.cond_id="0" or p.cond_id in(select id from cond_price where area_id="0")) and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql1;
		
				
		//3
		$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="1" and key_value ="'.$id.'")  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql3;
		
		//4 
		$str=''; 		$cter=0;
		
		$mi=new MmenuItem();
		//!!!! ��� ���������, �� �����������!!! :)
		$razds=$mi->RetrievePath($id, $flaglost, $vloj);

		foreach($razds as $k=>$v){
			foreach($v as $key=>$val){
				//echo "$key - $val<br>";
				if($cter!=0)  $str.=',';
				$str.=" $key ";
				$cter++;
			}
		}
		//echo $str;
		$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="2" and key_value in('.$str.'))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		
		$sql="($sql1) UNION ($sql3) UNION ($sql4)  order by 6 desc, 7";
		$set=new mysqlSet($sql);
		
		$result=Array();
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$result[]=$f;
		}
		return $result;
	}
	
	
	//������ ��� ��� ������ ����� ���� �������
	public function GetNOTPricesByMid($id,$lang_id=LANG_CODE){
		$sql2='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="3")  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		
		
		
		//3
		$sql3='select  p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="1" and key_value<>"'.$id.'")  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		//echo $sql3;
		
		//4 
		$str=''; 		$cter=0;
		
		$mi=new MmenuItem();
		
		//!!!! ��� ���������, �� �����������!!! :)
		$razds=$mi->RetrievePath($id, $flaglost, $vloj);

		foreach($razds as $k=>$v){
			foreach($v as $key=>$val){
				//echo "$key - $val<br>";
				if($cter!=0)  $str.=',';
				$str.=" $key ";
				$cter++;
			}
		}
		//echo $str;
		$sql4='select p.id, p.cond_id, p.is_base, p.use_formula, p.formula, p.ord, l.name, l.descr from price as p, price__lang as l where p.cond_id in(select id from cond_price where area_id="2" and key_value not in('.$str.'))  and p.id=l.value_id and l.lang_id="'.$lang_id.'"';
		
		$sql="($sql2) UNION ($sql3) UNION ($sql4)  order by 6 desc, 7";
		$set=new mysqlSet($sql);
		
		$result=Array();
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$result[]=$f;
		}
		return $result;
	}
//********************����� ����� ��������� ������� ���, ���. � �� ���. ��� ������ � �������� � ��������� ************************//
	
	
	
	
	
	
	
	
	//�������������� ���� � ���.��� 0.00
	public function FormatPrice($value, $dims, $afternil=0){
		//return sprintf("%.".$afternil."f".$dims, $value);
		return FormatPrice($value,$dims, $afternil);
		//return number_format($value,$afternil,'.',' ').$dims;
	}
}
?>