<?

require_once('abstractgroup.php');


// список видов прикреплений словарей
class AttKindsGroup extends AbstractGroup {
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='attach_d';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	
	
	//список итемов
	public function GetItems(){
		//список позиций
		$txt='';

		
		return $txt;
	}
	
	//список итемов в тегах радио
	public function GetItemsRadio($workmode,$selected_id, $tagname, $add_code='',$show_no=true){
		//список позиций
		$txt='';
		if($show_no){
			if($selected_id==0) $txt.='<nobr><input type="radio" name="'.$tagname.'" value="0" '.$add_code.' checked>Не прикреплен</nobr><br>';
			else $txt.='<nobr><input type="radio" name="'.$tagname.'" value="0" '.$add_code.'>Не прикреплен</nobr><br>';
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
	
	//список итемов в тегах option
	public function GetItemsOpt($workmode,$selected_id, $show_no=true){
		//список позиций
		$txt='';
		if($show_no){
			if($selected_id==0) $txt.='<option value="0" selected>Не прикреплен</option>';
			else $txt.='<option value="0" >Не прикреплен</option>';
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
	
	
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';

		
		return $txt;
	}
	
	
	
	
}
?>