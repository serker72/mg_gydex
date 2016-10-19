<?
require_once('global_resolver.php');
require_once(ABSPATH.'classes/smarty/SmartyAdm.class.php');
require_once(ABSPATH.'classes/smarty/SmartyAj.class.php');
require_once(ABSPATH.'classes/resoursefile.php');

class FoldersList_S{
	//начальный физический путь
	protected $begin_fiz_path;
	
	//начальный логический путь
	protected $begin_log_path;
	
	//активный логический путь
	protected $active_log_path;
	
	//имя файла-сценария
	protected $prog_name='index.php';
	
	//шаблон для вывода дерева папок
	protected $template='photofolder/folders_tree.html';
	//минорный шаблон
	protected $minor_template='photofolder/folders.html';
	
	//флаг-признак использования ксаджакса
	protected $has_xajax=false;
	
	protected $has_root=true;
	
	
	//признак вывода файлов
	protected $has_files=false;
	
	protected $fragments=Array(); //массив, содержащий фрагменты пути к файлу
	
	protected $delim='/'; //символ-граница разбиения
	protected $folder_plus_img='/img/plus.gif';
	protected $folder_minus_img='/img/minus.gif';
	
	//массивы для папок и файлов
	protected $dir_arr=Array();
	protected $file_arr=Array();
	
	protected $total_dir_arr=Array();
	protected $total_file_arr=Array();
	
	public function  __construct($begin_log_path,$folder){
		$this->init($begin_log_path,$folder);
	}
	
	protected function init($begin_log_path,$folder){
		$this->begin_log_path=$begin_log_path;
		//$this->begin_fiz_path= str_replace('//','/',PHOTOS_BASEPATH.$begin_log_path.'/');
		
		$this->begin_fiz_path=PHOTOS_BASEPATH;
		if($folder=='') {
			
		}
		//echo "<h1>$folder</h1>"; die();
		$this->active_log_path=$folder;
	}
	
	//строим дерево каталогов
	public function CreateTree($params=Array()){
		$txt='';
		
		$alls=Array();
		
		if($this->has_xajax) $sm=new SmartyAj;
		else $sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		
		$this->MakeFragments();
		
		$path=$this->begin_log_path;
		
//		echo "<h1> $this->begin_log_path</h1>"; die();
		
		for($i=0;$i<count($this->fragments);$i++){
			
			if($i!=0) $path.='/'.$this->fragments[$i];
			else $path.=$this->fragments[$i];
			
			//echo " $path<br>";
			
			$this->ReadDirect($path);
			
			ksort($this->dir_arr);
			ksort($this->file_arr);
			
			$this->total_dir_arr[]=$this->dir_arr;
			$this->total_file_arr[]=$this->file_arr;
			
			$this->dir_arr=Array();
			$this->file_arr=Array();
			
		}
		
		//есть массивы, обработать их и вывести рекурсивно
		$subs=$this->RekursArrays($this->begin_fiz_path.$this->begin_log_path,0,$params);
		
		$files_s=Array();
		if($this->has_files){
			foreach($this->total_file_arr[0] as $k=>$v){
				$files_s[]=Array(
					'name'=>basename($k)
				);
			}
		}
		
		$str=DeParams($params);
		
		
		$alls[]=Array(
				'has_root'=>false,	
				'rel_path'=>'subroot',		
				'subs'=>$subs
			);
		
		
		$sm->assign('has_border_div',true);
		$sm->assign('rel_path','root');
		$sm->assign('items',$alls);
		if($this->has_root){
			return $sm->fetch($this->template);
		}else{
			return $subs;
		}
	}
	
	
	protected function RekursArrays($currpath, $ct, $params=Array()){
		//
		$level=$ct;
		//echo "<strong>уровень рекурсии $level</strong><p>";
		
		
		$alls=Array();
		if($this->has_xajax) $sm=new SmartyAj;
		else $sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		
		
		if($ct<count($this->fragments)){
			if($ct!=0) $currpath.='/'.$this->fragments[$ct];
			else $currpath.=$this->fragments[$ct];
			
			//echo "<strong>".realpath($currpath)."</strong><br>";
			//echo "<strong>".realpath($this->begin_fiz_path)."</strong><br>";
			
			$sm->assign('rel_path', 'subroot'.str_replace($this->begin_fiz_path, '', $currpath));
			
			foreach($this->total_dir_arr[$ct] as $k=>$v){
				//$checkpoint =(isset($this->fragments[$ct+1])&&(($currpath.'/'.$this->fragments[$ct+1])==$k));
				$checkpoint =(isset($this->fragments[$ct+1])&&(($currpath.'/'.$this->fragments[$ct+1])==($this->begin_fiz_path.$k)));
				
				$str=DeParams($params);
				
				//echo "perebir kat <strong>level $level</strong>: $k <em>path:</em> $currpath   <em>add:</em> ".$this->fragments[$ct+1]."<p>";
				//echo "<strong>".($currpath.'/'.$this->fragments[$ct+1])."</strong> vs <strong>".($this->begin_fiz_path.$k)."</strong>";
				
				$subs=''; $files_s=Array();
				if($checkpoint){
					$ct++;
					
					$subs=$this->RekursArrays($currpath,$ct,$params);
					
					if($this->has_files)
						foreach($this->total_file_arr[$ct] as $kk=>$vv){
							$files_s[]=Array(
								'name'=>basename($kk)
							);
						}
					$pict=$this->folder_minus_img;
				}else $pict=$this->folder_plus_img;
				
				//echo "<h3>$v[rel_path]</h3>";
				$alls[]=Array(
					'url'=>"$this->prog_name?from=0&folder=$v[rel_path]$str",
					'pict'=>$pict,
					'name'=>$v['name'],
					'has_files'=>$this->has_files,
					'files'=>$files_s,
					'has_controls'=>true,
					'rel_path'=>$v['rel_path'],
					'prev_folder'=>str_replace('/'.$v['name'],'',$v['rel_path']),
					'has_root'=>true,
					'COORDFUNC'=>COORDFUNC,
					'subs'=>$subs
				);
			}
		}
		
		//echo "<strong>".realpath($currpath)."</strong><br>";
		//echo "<strong>точка выхода уровня рекурсии $level</strong><p>";
		$sm->assign('items',$alls);
		
		//фолс только если 1) перерисовка через аджакс + 2) верхний уровень
		if(
		//(realpath($currpath)==realpath($this->begin_fiz_path))&&
		//((($level==count($this->fragments)-1))||(count($this->fragments)==0))&&
		//???????????????
		
		$this->has_xajax
		) $sm->assign('has_border_div',false);
		else 
		$sm->assign('has_border_div',true);
		
		return $sm->fetch($this->template);
	
	}
	
	
	
	
	
	
	//функция показа папок
	public function ReadDirect($path){
		$this->ReadDirectory($path,$this->has_files);
	}
	
	
	
		//служебная функция показа папок
	protected function ReadDirectory($path, $has_files=false){
		$hnd=opendir(str_replace('//','/',$this->begin_fiz_path.$path));
		
		//echo "scanning: $this->begin_fiz_path$path<br>";
		
		while(($name=readdir($hnd))!=false){
			if(($name==".")||($name=="..")) continue;
			$this->number++;
			
			

			
			if(@is_dir($this->begin_fiz_path.$path.'/'.$name)){
				//это каталог, загоним его в массив
				//echo "$name <br>";
				$this->dir_arr[$path.'/'.$name]=Array(
					'rel_path'=>$path.'/'.$name,
					'name'=>$name,
					//'path'=>substr($path, strlen($this->begin_fiz_path))
					'path'=>str_replace('//','/',$this->begin_fiz_path.$path)
				);
			}else{
				//это файл, загоним его в массив
				//echo "file: $name <br>";
				if($has_files) $this->file_arr[$path.'/'.$name]=Array(
					//'rel_path'=>substr($path.'/'.$name, strlen($this->begin_fiz_path)),
					'rel_path'=>$path.'/'.$name,
					'name'=>$name,
					//'path'=>substr($path, strlen($this->begin_fiz_path))
					'path'=>str_replace('//','/',$this->begin_fiz_path.$path)
				);
			}

		}
		closedir($hnd);
	}
	
	
	//разбиваем исходную строку на куски по символу /
	public function MakeFragments(){
		$this->fragments=explode( $this->delim, $this->active_log_path);
	}

	//получение массива фрагментов
	public function GetFragments(){
		return $this->fragments;
	}
	
	//назначим имя сценария
	public function SetProgName($name){
		$this->prog_name=$name;
		return true;
	}
	
	public function SetTemplate($template){
		$this->template=$template;
		return true;
	}
	public function SetXajax($xajax){
		$this->has_xajax=$xajax;
		return true;
	}
	public function SetRoot($root){
		$this->has_root=$root;
		return true;
	}
	
	
}
?>