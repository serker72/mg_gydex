<?
require_once('global.php');

//����� ��� ����������� � csv � ���������� �������
class CsvLowLevel{
	//���� � ����� � �������
	protected $path;
	
	protected $sessionname='csvmaster';
	//����� ����� ��������� ������
	protected $max_live_time=86400; //60*60*24; �����
	
	function __construct($path){
		$this->path=$path;
	}
	
	//�������� ���������� �����
	public function CreateTempFile(){
		$_SESSION[$this->sessionname]['process_filename']=tempnam($this->path.'temp/','');
		
		//������� ������ ������
		$this->ClearOldTemps();
	}
	
	//������� ���������� �����
	public function ClearTempFile(){
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "w")){
			fclose($f);
			return true;
		}else return false;
	}
	
	
	//�������� ������ ��������� ������
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
	
	
	//�������� ������� ���� � �������� �����
	public function CheckFromPosition($from){
		//��������� �������� ���� � ��������� �� ���� �������
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
	
	//������� ������ ����� ������� ishodnogo �����
	public function CountAllRecords(){
		//��������� �������� ���� � ��������� �� ���� �������
		if($f=@fopen($this->path.$_SESSION[$this->sessionname]['filename'], "r")){
			$counter=0; 
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
			}
			return $counter;
		} else return 0;
	}
	
	
	//�������� ������� ���� � rez �����
	public function CheckFromPositionRez($from,&$counter){
		//��������� ��� ���� � ��������� �� ���� �������
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
	
	//���������� ������� ��������� ��� ����� � ������� pos �� ������� ����
	public function InsertBlanksRez($pos,$from){
		$aa=$from-$pos; 
		if($aa<0) $aa=0;
		
		//��������� ��� ���� � ��������� �� ���� �������
		
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r+")){
			$counter=0; 
			
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				$counter++;
				if($counter<=$pos) continue;
				
				break; 
			}
			
			for($i=0; $i<$aa; $i++){
				//echo '<br>������ ������ ������<br>';
				$cdata=Array();
				for($j=0; $j<5; $j++) $cdata[]='-';
				
				$sdata=implode($_SESSION[$this->sessionname]['separator'],$cdata);
				fwrite($f,$sdata);
			}
			
			fclose($f);
		} 
	}
	
	//���������� ���� ������ �� ��� �����
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
	
	//������ � ���� ������ �����
	//������ ���������� - ������������
	//������ ��� - ��������
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
	
	//�������������� ������ � ��� �����
	public function EditRecord($from, $articul, $params){
		//���������� �� ����+1
		//������� �� ���������� ����+������� �� �������� ��� ����� �����
		//������� ��������� �� ���������� ��������
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
		
		//���������� � ���� ���������� ������
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