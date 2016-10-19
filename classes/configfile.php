<?

class ConfigFile{
	protected $filename;
	protected $settings;
	
	
	public function __construct(){
		$this->init();
	}
	
	//��������� ���� ����
	protected function init(){
		$this->filename='../cnf/file.txt';
		$this->settings=Array();
	}
	
	//��������� ����� �����
	public function SetFileName($name){
		$this->filename=$name;
		return true;
	}
	
	//��������� ������� ���������
	public function GetSettings(){
		
		return $this->settings;
	}
	
	//������ ��������� � ����
	public function SaveToFile($settings){
		$res=false;
		
		$txt=serialize($settings);
		//$this->Create();
		$this->WriteStringToFile($txt);
		
		return $res;
	}
	
	
	
	//���������� ��������� �� �����
	public function LoadFromFile(){
		
		$txt=$this->ReadFileToString();
		if($txt!=false){
			$sets=unserialize($txt);
			$this->settings=$sets;
		}
		
		return $this->settings;
	}
	
	
	
	
//----------------------------------------------------------------------------------------
	
	//��������� ������� �������� �����
	protected function Create(){
		
		if(!file_exists($this->filename)){
			$f=fopen($this->filename, "w");
			fclose($f);
		}
	}
	
	//��������� ������� ������ ����� � ������
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
	
	//��������� ������� ������ ������ � ����
	protected function WriteStringToFile($txt){
		$f=fopen($this->filename, "w");
		fwrite($f, ($txt), strlen($txt));
		fclose($f);
		return true;
	}
};
?>