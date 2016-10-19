<?

class ConfigFile{
	protected $filename;
	protected $settings;
	
	
	public function __construct(){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->filename='../cnf/file.txt';
		$this->settings=Array();
	}
	
	//установка имени файла
	public function SetFileName($name){
		$this->filename=$name;
		return true;
	}
	
	//получение массива установок
	public function GetSettings(){
		
		return $this->settings;
	}
	
	//запись установок в файл
	public function SaveToFile($settings){
		$res=false;
		
		$txt=serialize($settings);
		//$this->Create();
		$this->WriteStringToFile($txt);
		
		return $res;
	}
	
	
	
	//считывание установок из файла
	public function LoadFromFile(){
		
		$txt=$this->ReadFileToString();
		if($txt!=false){
			$sets=unserialize($txt);
			$this->settings=$sets;
		}
		
		return $this->settings;
	}
	
	
	
	
//----------------------------------------------------------------------------------------
	
	//служебная функция создания файла
	protected function Create(){
		
		if(!file_exists($this->filename)){
			$f=fopen($this->filename, "w");
			fclose($f);
		}
	}
	
	//служебная функция чтения файла в строку
	protected function ReadFileToString(){
		if(!file_exists($this->filename)){
			$this->Create();
		}
		if($f=fopen($this->filename, "r")) {}else{
			return false;
		}
		$txt='';
		while(!feof($f)){
			$l=fread($f,255);
			$txt=$txt.$l;
		}
		fclose($f);
		return $txt;
	}
	
	//служебная функция записи строки в файл
	protected function WriteStringToFile($txt){
		$f=fopen($this->filename, "w");
		fwrite($f, ($txt), strlen($txt));
		fclose($f);
		return true;
	}
};
?>