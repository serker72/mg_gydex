<?

require_once('abstractgroup.php');


// ������ ����� ������������ ��������
class AttKindsGroup extends AbstractGroup {
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='attach_d';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	
	
	//������ ������
	public function GetItems(){
		//������ �������
		$txt='';

		
		return $txt;
	}
	
	//������ ������ � ����� �����
	public function GetItemsRadio($workmode,$selected_id, $tagname, $add_code='',$show_no=true){
		//������ �������
		$txt='';
		if($show_no){
			if($selected_id==0) $txt.='<nobr><input type="radio" name="'.$tagname.'" value="0" '.$add_code.' checked>�� ����������</nobr><br>';
			else $txt.='<nobr><input type="radio" name="'.$tagname.'" value="0" '.$add_code.'>�� ����������</nobr><br>';
		}
		
		if($workmode==1){
			$sql='select * from '.$this->tablename.' where id=1 or id=2';
		}else if($workmode==3){
			$sql='select * from '.$this->tablename.' where id=3';
		}
		
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			if($f['id']==$selected_id) $txt.='<nobr><input type="radio" name="'.$tagname.'" value="'.$f['id'].'" checked '.$add_code.'>'.stripslashes($f['name']).'</nobr><br>';
			else $txt.='<nobr><input type="radio" name="'.$tagname.'" value="'.$f['id'].'" '.$add_code.'>'.stripslashes($f['name']).'</nobr><br>';
		}
		
		return $txt;
	}
	
	//������ ������ � ����� option
	public function GetItemsOpt($workmode,$selected_id, $show_no=true){
		//������ �������
		$txt='';
		if($show_no){
			if($selected_id==0) $txt.='<option value="0" selected>�� ����������</option>';
			else $txt.='<option value="0" >�� ����������</option>';
		}
		
		if($workmode==1){
			$sql='select * from '.$this->tablename.' where id=1 or id=2';
		}else if($workmode==3){
			$sql='select * from '.$this->tablename.' where id=3';
		}
		
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			if($f['id']==$selected_id) $txt.='<option value="'.$f['id'].'" selected>'.stripslashes($f['name']).'</option>';
			else $txt.='<option value="'.$f['id'].'">'.stripslashes($f['name']).'</option>';
		}
		
		return $txt;
	}
	
	
	
	//������ ������
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//������ �������
		$txt='';

		
		return $txt;
	}
	
	
	
	
}
?>