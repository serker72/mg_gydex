<?
require_once('abstractgroup.php');
require_once('dictattdisp.php');


//������ ����� ������ ��� ����� ������
class OrderItemOptGroup extends AbstractGroup {
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='order_item_option';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	//����� ���� ����� ��� ������ � ���������� ��������� � ������������ ������
	//�� ������ ����� ������ ��������� ������ �� ��������� ����� ��������
	//������� �� �-�� ������ �� ��������� �-� ��� ���������� ������ ����� ������
	public function GetOrderItemOptions($good_id,$lang_id,$set_tpl,$item_tpl,$order_item_id,$something='',$for_big_list=false,$order_id_in_list=''){
		$v=Array();
		 $dicts=new DictAttDisp(3);
		//������ ������
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
	
	//������ ������
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//������ �������
		$txt='';

		
		return $txt;
	}
	
	
	
	
	
	//������ ������
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//������ �������
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
	}
	
	//������ ������ ������ ��� ������
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//������ �������
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	
	
}
?>