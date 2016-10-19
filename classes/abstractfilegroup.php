<?
require_once('abstractgroup.php');

// абстрактная группа файлов
class AbstractFileGroup extends AbstractGroup {
	protected $storage_id;
	protected $storage_name;
	protected $storage_path;
	
	public function __construct(){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->tablename='file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='news_id';
			
	
		$this->storage_name='storage_id';	
		$this->storage_path='.';	
	}
	
	
	public function ShowFiles($id, $template,  $pagename='files.php', $loadname='load.html', $viewname='view.html'){
		
		$sm=new SmartyAdm;
		
		$sql='select f.*  
			 from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" ';
		
		$sql_count='select count(*) from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" ';
		
		
		 
		
	 
			$sql.=' order by id asc';
		 
		//echo $sql;
		
		$set=new mysqlSet($sql); //$to_page, $from,$sql_count);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		 
		
		
	 
		
		$alls=array();
		for($i=0; $i<$rc; $i++){
			
			
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			$f['pdate']=date("d.m.Y H:i:s",$f['pdate']);
			
			$f['size']=filesize($this->storage_path.$f['filename'])/1024/1024;
			
			
			//фото ли это?
			$extension=0;
			if(eregi("^(.*)\\.(jpg|jpeg|jpe)$", $f['orig_name'],$P)) $extension='.jpg';
			if(eregi("^(.*)\\.(gif)$",  $f['orig_name'],$P)) $extension='.gif';
			if(eregi("^(.*)\\.(png)$",  $f['orig_name'],$P)) $extension='.png';
		
			
		 
			if($extension!==0) { $f['is_image']=true;    }
			else $f['is_image']=false;
			
			//print_r($f);	
			$alls[]=$f;
		}
		
		 
		
		$sm->assign('from',$from);
		$sm->assign('to_page',$to_page);
		$sm->assign('pages',$pages);
		$sm->assign('items',$alls);
		
	 
		
		$sm->assign('storage_name', $this->storage_name);
	 
		
		$sm->assign('session_id',session_id());
		
		$sm->assign('uploader_name',$uploader_name);
		$sm->assign('pagename',$pagename);
		$sm->assign('loadname',$loadname);	
		$sm->assign('viewname',$viewname);		
			
		return $sm->fetch($template);
	}
	
	
	
	public function GetItemsByIdArr($id){
		$arr=Array();
		
		$set=new MysqlSet('select * from '.$this->tablename.' where '.$this->subkeyname.'="'.$id.'" and '.$this->storage_name.'="'.$this->storage_id.'" order by id asc');
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0; $i<$rc; $i++){
			
			$f=mysqli_fetch_array($rs);
			
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			$f['size']=filesize($this->storage_path.$f['filename'])/1024/1024;
			//$f['address']=nl2br($f['address']);
			$arr[]=$f;
		}
		
		return $arr;
	}
	
	
	
	
	public function ClearLostFiles($ttl=86400){
		//$files=array();
		$message_files=array();
		
		$message_files=$this->GetFilenamesArr();
		//print_r($message_files);
		$iterator = new DirectoryIterator($this->storage_path);
		foreach ($iterator as $fileinfo) {
			if ($fileinfo->isFile()) {
				//echo $fileinfo->__toString();
				//$filenames[$fileinfo->getMTime()] = $fileinfo->getFilename();
				if(!in_array($fileinfo->__toString(), $message_files)){
					
					$tm=$fileinfo->getMTime();
					if($tm<(time()-$ttl)){
					  //проверять дату
					  @unlink($this->storage_path.$fileinfo->__toString());
					}
				}
			}
		}
		
	}
	
	protected function GetFilenamesArr(){
		//список позиций

		$arr=Array();
		$set=new MysqlSet('select * from '.$this->tablename.' where '.$this->storage_name.'="'.$this->storage_id.'" order by id asc');
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$arr[]=$f['filename'];
		}
		
		return $arr;
		
	}
	
	public function GetStoragePath(){
		return $this->storage_path;	
	}
	
	public function SetStoragePath($path){
		$this->storage_path=$path;	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function HasFiles($id){
		$sql_count='select count(*) from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" and (
		f.orig_name NOT REGEXP "^(.*)\\.(jpg|jpeg|jpe)$" and
		f.orig_name  NOT  REGEXP "^(.*)\\.(gif)$" and
		f.orig_name  NOT  REGEXP "^(.*)\\.(png)$" )
		  ';	
		  
		$set=new mysqlSet($sql_count); //$to_page, $from,$sql_count);
		$rs=$set->GetResult();
		
		
		$f=mysqli_fetch_array($rs);
		
		return (int)$f[0];
	}
	
	
	public function HasImages($id){
		$sql_count='select count(*) from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" and (
		f.orig_name  REGEXP "^(.*)\\.(jpg|jpeg|jpe)$" or
		f.orig_name    REGEXP "^(.*)\\.(gif)$" or
		f.orig_name    REGEXP "^(.*)\\.(png)$" )
		  ';	
		  
		$set=new mysqlSet($sql_count); //$to_page, $from,$sql_count);
		$rs=$set->GetResult();
		
		
		$f=mysqli_fetch_array($rs);
		
		return (int)$f[0];
	}
	
	
	
	
	public function GetFilesArr($id, $loadname='load.html', $viewname='view.html'){
	 
		
		$sql='select * from '.$this->tablename.' as f    where '.$this->subkeyname.'="'.$id.'" and (
		f.orig_name NOT REGEXP "^(.*)\\.(jpg|jpeg|jpe)$" and
		f.orig_name  NOT  REGEXP "^(.*)\\.(gif)$" and
		f.orig_name  NOT  REGEXP "^(.*)\\.(png)$" )
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
			
			eregi('\.([a-zA-Z])+$', $f['orig_name'], $regs);
			
			//var_dump($regs);
			
			$f['ext']=str_replace('.', '', $regs[0]);
			
			
			$f['size']=round(filesize($this->storage_path.$f['filename'])/1024/1024, 3);
			$f['full_url']=$loadname.'?id='.$f['id']; //.'&width=310&height=200';
			
		 
			//print_r($f);	
			$alls[]=$f;
		}
		
		 
	 	return $alls;
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
			
			eregi('\.([a-zA-Z])+$', $f['orig_name'], $regs);
			
			//var_dump($regs);
			
			$f['ext']=str_replace('.', '', $regs[0]);
			
			
			$f['size']=round(filesize($this->storage_path.$f['filename'])/1024/1024, 3);
			$data=getimagesize($this->storage_path.$f['filename']);
			$f['width']=$data[0];
			$f['height']=$data[1];
			
			
		 	$f['small_url']=$viewname.'?id='.$f['id'].'&width=315&height=200';
			$f['full_url']=$viewname.'?id='.$f['id']; //.'&width=310&height=200';
			
			//print_r($f);	
			$alls[]=$f;
		}
		
		 
	 	return $alls;
	}
}
?>