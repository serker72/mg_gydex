<?
require_once('abstractfilegroup.php');

// абстрактная группа файлов
class SolFileGroup extends AbstractFileGroup {
	protected $storage_id;
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='solution_file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='solution_id';
			
	
		$this->storage_name='storage_id';	
		$this->storage_path=ABSPATH.'upload/solutions/';	
	}
	
	
	
	
	 public function GetImagesArr($id, $loadname='load.html', $viewname='view.html'){
	 
		
		$sql='select * from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" and (
		f.orig_name  REGEXP "^(.*)\\.(jpg|jpeg|jpe)$" or
		f.orig_name    REGEXP "^(.*)\\.(gif)$" or
		f.orig_name    REGEXP "^(.*)\\.(png)$" )
		  ';	
		
		$sql.=' order by id asc';
		 
		//echo $sql;
		
		$set=new mysqlSet($sql); //$to_page, $from,$sql_count);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		 
		
		
	 
		
		$alls=array();
		for($i=0; $i<$rc; $i++){
			
			
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			//$f['pdate']=date("d.m.Y H:i:s",$f['pdate']);
			
			$f['size']=filesize($this->storage_path.$f['filename'])/1024/1024;
			
		 	$f['small_url']=$viewname.'?id='.$f['id'].'&width=425&height=243';
			$f['full_url']=$viewname.'?id='.$f['id']; //.'&width=310&height=200';
			
			//print_r($f);	
			$alls[]=$f;
		}
		
		 
	 	return $alls;
	}
	 
}
?>