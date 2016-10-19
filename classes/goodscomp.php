<?
require_once('global.php');
require_once('priceitem.php');
require_once('attdictsgroup.php');
require_once('pricedisp.php');
require_once('firmitem.php');
require_once('mmenuitem.php');
require_once('dictattdisp.php');
require_once('smarty/SmartyAdm.class.php');

//сравнение произвольного числа товаров
class GoodsComparison{
	protected $price_disp;
	protected $price_item;
	protected $firm_item;
	protected $attached_list;
	//имя сессии для хранения списка товаров
	protected $sessionname;
	
	//шаблоны
	protected $templates=Array();
		
	public function __construct($sessionname='compare'){
		$this->init($sessionname);
	}
	//установка всех имен
	protected function init($sessionname){
		$this->sessionname=$sessionname;
		$this->price_disp=new PriceDisp();
		$this->price_disp->SetShowErrors(false);
		$this->price_item=new PriceItem();
		$this->firm_item=new FirmItem();
		$this->attached_list=new AttDictsGroup();
		if(!isset($_SESSION[$this->sessionname])) $_SESSION[$this->sessionname]=Array();
	}
	
//************************************** БЛОК ВЫВОДА СРАВНЕНИЙ *******************************	
	public function Compare($lang_id=LANG_CODE){
		//удалим неверные товары из сравнения
		$this->Check($lang_id);
		$txt=''; $rows=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt');
		if(count($_SESSION[$this->sessionname])==0) return '<h1>'.$rf->GetValue('compare.php','no_goods',$lang_id).'</h1>';
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		$totalrows=Array();
		
		//1 ряд - наименования и фото товаров
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$good=$this->price_item->GetItemById($k,$lang_id,1);
			
			$cells[]=Array(
				'is_common_cell'=>true,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'classname'=>'pole',
				'contents'=>'<a href="'.$this->price_item->ConstructPath($k,$lang_id,'/').'" target="_blank">'.stripslashes($good['name']).'<br>
		<img src="/'.stripslashes($good['photo_small']).'" alt="" border="0"></a>'
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>$rf->GetValue('compare.php','good_name_caption',$lang_id),
			'align'=>'center',
			'cells'=>$cells
		);
		
		
		//2 ряд - фирма-производитель
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$good=$this->price_item->GetItemById($k,$lang_id,1);
			
			$firm=$this->firm_item->GetItemById($good['firmid'],$lang_id,1);
			if($firm!==false){
				$contents='<a href="/firm.php?id='.$good['firmid'].'" target="_blank">'.stripslashes($firm['name']).'</a>';
			}else $contents='<div align="center">-</div>';
			
			$cells[]=Array(
				'is_common_cell'=>true,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'classname'=>'pole',
				'contents'=>$contents
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>$rf->GetValue('good.php','firm-manufacturer',$lang_id),
			'align'=>'left',
			'cells'=>$cells
		);
		
		
		//3 ряд - цена
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$cells[]=Array(
				'is_common_cell'=>true,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'classname'=>'pole',
				'contents'=>$this->price_disp->GetGoodBasePrice($k,$lang_id,true)
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>$rf->GetValue('compare.php','price_name_caption',$lang_id),
			'align'=>'left',
			'cells'=>$cells
		);
		
		
		//4 ряд - краткое описание
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$good=$this->price_item->GetItemById($k,$lang_id,1);
			$cells[]=Array(
				'is_common_cell'=>true,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'classname'=>'pole',
				'contents'=>'<em>'.stripslashes($good['small_txt']).'</em>'
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>$rf->GetValue('compare.php','good_annot_caption',$lang_id),
			'align'=>'left',
			'cells'=>$cells
		);
		
		//5 - общие словари
		$common_dicts=$this->attached_list->GetPropTablesForGoods($_SESSION[$this->sessionname],$dicts_arr, $lang_id, $this->templates['merged_row'], $this->templates['common_row'], $this->templates['common_cell']);
		foreach($common_dicts as $ck=>$cv){
			$totalrows[]=$cv;
		}
		
		
		//6 - прочие словари
		$totalrows[]=Array(
			'is_common_row'=>false,
			'row_title'=>$rf->GetValue('compare.php','other_info_caption',$lang_id)
		);
		
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$cells[]=Array(
				'is_common_cell'=>true,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'classname'=>'pole',
				'contents'=>$this->attached_list->GetPropTablesByGoodIdEXCEPT($k, $dicts_arr, $lang_id, 'price/prop_tables.html', '', '')
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>'',
			'align'=>'left',
			'cells'=>$cells
		);
		
		
		//7 - ряд действий
		$cells=Array();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$cells[]=Array(
				'is_common_cell'=>false,
				'width'=>floor(100/count($_SESSION[$this->sessionname])).'%',
				'contents'=>$this->attached_list->GetPropTablesByGoodIdEXCEPT($k, $dicts_arr, $lang_id, 'price/prop_tables.html', '', ''),
				'good_no'=>$k
			);
		}
		$totalrows[]=Array(
			'is_common_row'=>true,
			'row_title'=>$rf->GetValue('compare.php','action_caption',$lang_id),
			'align'=>'left',
			'cells'=>$cells
		);
		
		$smarty->assign('del_item',$rf->GetValue('compare.php','del_good_caption',$lang_id));
		$smarty->assign('rows',$totalrows);
		$smarty->assign('cou',count($_SESSION[$this->sessionname])+1);
		$txt=$smarty->fetch($this->templates['alltable']);
		return $txt;
	}
//************************************** ENDOF БЛОК ВЫВОДА СРАВНЕНИЙ **************************	
	
	
	
	
//***************************************** РАБОТА С СЕССИЕЙ ************************************
	public function Add($id){
		$_SESSION[$this->sessionname][$id]=1;
	}
	
	public function Del($id){
		unset($_SESSION[$this->sessionname][$id]);
	}
	
	public function Clear(){
		unset($_SESSION[$this->sessionname]);
		$_SESSION[$this->sessionname]=Array();
	}
	
	//проверка товаров сессии (неверные удаляем)
	protected function Check($lang_id=LANG_CODE){
		$mi=new MmenuItem();
		foreach($_SESSION[$this->sessionname] as $k=>$v){
			$good=$this->price_item->GetItemById($k,$lang_id,1);
			if($good==false){
				unset($_SESSION[$this->sessionname][$k]);
			}
			$mm=$mi->GetItemById($good['mid'],$lang_id,1);
			if(($mm==false)||($mm['is_price']!=1)){
				unset($_SESSION[$this->sessionname][$k]);
			}
			
			$m_ex=$mi->CheckFullExistance($good['mid'],$lang_id,1);
			if($m_ex==false){
				unset($_SESSION[$this->sessionname][$k]);
			}
		}
	}
//*********************************** END OF РАБОТА С СЕССИЕЙ ************************************

	public function SetTemplates($templates){
		unset($this->templates);
		$this->templates=$templates;
	}
	
	public function GetSessionName(){
		return $this->sessionname;
	}
}
?>