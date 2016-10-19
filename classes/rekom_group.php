<?
require_once('global.php');
require_once('abstractgroup.php');


// список рекомендуемых товаров
class RekomGroup extends AbstractGroup {
	
	protected $templates=Array();
	
	//установка всех имен
	protected function init(){
		$this->tablename='goods_rekommend';
		$this->pagename='viewrekoms.php';		
		$this->subkeyname='pri_lid';		
		
		$this->templates['table']='price/rekoms.html';
		$this->templates['row']='tpl/price/rekomsrow.html';
		$this->templates['item']='tpl/price/rekomsitem.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($id,$lang_id=LANG_CODE){
		//список позиций
		$txt='';
		$sql= 'select gr.sec_lid, pl.name, pi.photo_small, gr.id from
		('.$this->tablename.' as gr INNER JOIN price_item as pi on (gr.sec_lid=pi.id and gr.'.$this->subkeyname.'="'.$id.'"))
		INNER JOIN price_lang as pl on (pi.id=pl.price_id and pl.lang_id='.$lang_id.')';
		
		$set=new MysqlSet($sql);
		$per_row=4; $cter=1; $strs='';
		$totalcount=$set->GetResultNumRows();
		$res=$set->GetResult();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$alls=Array();
		for($i=0; $i<$totalcount; $i++){
			$f=mysqli_fetch_array($res);
			
			if($cter==1){
					//соберем ряд
					//echo 'Ряд начат!!!<br>';
					$row=Array();
			}
			$row[]=Array('itemno'=>$f[0], 
			'itemname'=>stripslashes($f[1]),
			'link_no'=>$f[3],
			'item_photo'=>stripslashes($f[2])
			);
			
			if(($i==($totalcount-1))||($cter>=$per_row)){
				$cter=1;
				//echo 'Ряд ended!!!<br>';
				$alls[]=Array('cells'=>$row);
			}else $cter++;
		}
		$smarty->assign('rows',$alls);
		$txt=$smarty->fetch($this->templates['table']);
		return $txt;
	}
	
	
	
	//список итемов клиентский
	public function GetItemsByIdCli($id,$lang_id=LANG_CODE){
		$result=Array(
			'caption'=>'',
			'set'=>Array(),
			'count'=>0
		);
		
		$pi=new PriceItem();
		
		$price_disp=new PriceDisp();
		$disp_nv=new DictNVDisp();
		
		$price_disp->SetShowErrors(false); 
		//получим базовую цену
		$base_price=$price_disp->GetBasePriceF();
		
		//получим базовую валюту
		$base_curr= $price_disp->GetBaseCurrencyF();
		
		$sql= 'select gr.sec_lid, pl.name, pi.photo_small, gp.value from
		('.$this->tablename.' as gr INNER JOIN price_item as pi on (gr.sec_lid=pi.id and gr.'.$this->subkeyname.'="'.$id.'")) 
		INNER JOIN price_lang as pl on (pi.id=pl.price_id and pl.lang_id='.$lang_id.' and pl.is_shown=1)
		LEFT JOIN good_price as gp ON (gr.sec_lid=gp.good_id and gp.price_id="'.$base_price['id'].'" and gp.curr_id="'.$base_curr['id'].'")';
		
		
		$set=new MysqlSet($sql);
		$per_row=4; $cter=1; $strs='';
		$totalcount=$set->GetResultNumRows();
		$res=$set->GetResult();
		
		$result['count']=$totalcount;
		
		//$row='';
		$alls=Array();
		for($i=0; $i<$totalcount; $i++){
			$f=mysqli_fetch_array($res);
			
			if($cter==1){
					//соберем ряд
					$row=Array();
					//echo 'Ряд начат!!!<br>';
			}
			
			//вывод цены
			if($f[3]!==NULL){
				$price=$price_disp->ConvertPriceValueByLangId($err_code, $f[3],$lang_id,true);
				if($err_code==0) {}
				else $price='';
			}else $price='';
			
			
			$row[]=Array(
				'itemno'=> $f[0],
				'itemname'=> stripslashes($f[1]),
				'item_photo'=> stripslashes($f[2]),
				'item_url'=> $pi->ConstructPath($f[0],$lang_id,'/'),
				'price'=>$price
			);
			
			if(($i==($totalcount-1))||($cter>=$per_row)){
				$cter=1;
				$alls[]=Array('cells'=>$row);
				//echo 'Ряд ended!!!<br>';
			}else $cter++;
		}
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$result['caption']=$rf->GetValue('good.php','rekomtitle',$lang_id);
		$result['set']=$alls;
		
		return $result;
	}
	
	
	
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	//сколько итемов
	public function CalcItemsById($id){
		
		$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.';';
		
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}
	
	
	
	
	
	//установка шаблонов
	public function SetTemplates($templates){
		unset($this->templates);
		$this->templates=$templates;
	}
}
?>