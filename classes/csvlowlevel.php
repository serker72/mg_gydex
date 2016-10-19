<?
require_once('global.php');

//класс дл€ манипул€ций с csv и временными файлами
class CsvLowLevel{
	//путь к папке с файлами
	protected $path;
	
	protected $sessionname='csvmaster';
	//врем€ жизни временных файлов
	protected $max_live_time=86400; //60*60*24; сутки
	
	function __construct($path){
		$this->path=$path;
	}
	
	//создание временного файла
	public function CreateTempFile(){
		$_SESSION[$this->sessionname]['process_filename']=tempnam($this->path.'temp/','');
		
		//очистка старых файлов
		$this->ClearOldTemps();
	}
	
	//очистка временного файла
	public function ClearTempFile(){
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "w")){
			fclose($f);
			return true;
		}else return false;
	}
	
	
	//удаление старых временных файлов
	protected function ClearOldTemps(){
		$hnd=opendir($this->path.'temp/');
		while(($name=readdir($hnd))!=false){
			if(($name==".")||($name=="..")) continue;
			if(!@is_dir($this->path.'temp/'.$name)){
				$last_time=filemtime($this->path.'temp/'.$name);
				if(($last_time+$this->max_live_time)<time()){
					unlink($this->path.'temp/'.$name);
				}
			}
		}
		closedir($hnd);
	}
	
	
	//проверка позиции ‘ром в исходном файле
	public function CheckFromPosition($from){
		//открываем исходный файл и смещаемс€ на фром записей
		if($f=@fopen($this->path.$_SESSION[$this->sessionname]['filename'], "r")){
			$counter=0; 
			
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
				if($counter<=$from) continue;
				
				fclose($f);
				return 0;
			}
			fclose($f);
			return -2;
			
		} else return -1;
	}
	
	//подсчет общего числа записей ishodnogo файла
	public function CountAllRecords(){
		//открываем исходный файл и смещаемс€ на фром записей
		if($f=@fopen($this->path.$_SESSION[$this->sessionname]['filename'], "r")){
			$counter=0; 
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
			}
			return $counter;
		} else return 0;
	}
	
	
	//проверка позиции ‘ром в rez файле
	public function CheckFromPositionRez($from,&$counter){
		//открываем рез файл и смещаемс€ на фром записей
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r")){
			$counter=0; 
			
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
				if($counter<$from) continue;
				
				fclose($f);
				return 0;
			}
			fclose($f);
			return -2;
			
		} else return -1;
	}
	
	//заполнение пустыми позици€ми рез файла с позиции pos до позиции фром
	public function InsertBlanksRez($pos,$from){
		$aa=$from-$pos; 
		if($aa<0) $aa=0;
		
		//открываем рез файл и смещаемс€ на фром записей
		
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r+")){
			$counter=0; 
			
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
				if($counter<=$pos) continue;
				
				break; 
			}
			
			for($i=0; $i<$aa; $i++){
				//echo '<br>рисуем пустую строку<br>';
				$cdata=Array();
				for($j=0; $j<5; $j++) $cdata[]='-';
				
				$sdata=implode($_SESSION[$this->sessionname]['separator'],$cdata);
				fwrite($f,$sdata);
			}
			
			fclose($f);
		} 
	}
	
	//считывание Ќной записи из рез файла
	public function GetData($pos_no, &$flag){
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r")){
			$counter=0; 
			
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
				if($counter<$pos_no) continue;
				if($counter==$pos_no){
					$flag=0;
					fclose($f);
					return $data;
				}
				if($counter>$pos_no){
					$flag=-2;
					fclose($f);
					return false;
				}
				
			}
			
			$flag=-2;
			fclose($f);
			return false;
		} 
		$flag=-1;
		return false;
	}
	
	//запись в Ќную строку файла
	//строка существует - перезаписать
	//строки нет - добавить
	public function SetData($pos_no, $data){
		$string=implode($_SESSION[$this->sessionname]['separator'],$data);
		
		$file_arr=@file($_SESSION[$this->sessionname]['process_filename']);
		$new_arr=Array();
		
		$srabotalo=false;
		foreach( $file_arr as $k=>$v){
			if(($k+1)==$pos_no){
				//echo "aaread data: $string\n<br>";
				$srabotalo=true;
				$new_arr[]=$string."\n";
			}else {
				$new_arr[]=$v;
				//echo "read data $k: $v\n<br>";
			}
		}
		if(!$srabotalo){
			$new_arr[]=$string."\n";
		}	
		
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "w")){
			foreach($new_arr as $k=>$v){
				//echo "written data: $v\n<br>";
				fwrite($f,$v);
			}
			fclose($f);
		}
	}
	
	//редактирование строки в рез файле
	public function EditRecord($from, $articul, $params){
		//сместитьс€ на фром+1
		//крутить до достижени€ фром+записей на страницу или конца файла
		//вносить изменени€ по совпадению артикула
		$file_arr=@file($_SESSION[$this->sessionname]['process_filename']);
		$new_arr=Array();
		
		$srabotalo=false;
		
		foreach($file_arr as $k=>$v){
			if(($k>=$from)&&($k<($from+$_SESSION[$this->sessionname]['records_per_page']))){
				$data=explode($_SESSION[$this->sessionname]['separator'],$v);
				
				if(isset($data[1])&&($data[1]==$articul)){
					$srabotalo=true;
					
					//echo "modifiing:<br>";
					//echo "from=$from art=$articul action=$params[action] name=$params[name] ost=$params[ost] mid=$params[mid] \n<br>";
					$new_data=Array();
					
					$new_data[0]=$params['action'];
					$new_data[1]=$data[1];
					$new_data[2]=$params['name'];
					$new_data[3]=$params['ost'];										
					$new_data[4]=$params['mid'];					
					
					$newstr=implode($_SESSION[$this->sessionname]['separator'],$new_data)."\n";
					$new_arr[]=$newstr;
				}else $new_arr[]=$v;
				
			}else {
				$new_arr[]=$v;
			}
		}
		
		//записываем в файл измененный массив
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "w")){
			foreach($new_arr as $k=>$v){
				fwrite($f,$v);
			}
			fclose($f);
		}
		
		
		return $srabotalo;
	}
}
?>