<?
require_once('global.php');
class FileText{
	protected $text=NULL; //����� ������ �� ����� ������
	
	//�������� ���������
	public function Create($filename='../parts/footer1.txt'){
		//���� ��������� ��� ����������
		//if(!file_exists($filename)){
		$f=fopen($filename, "w");
		fclose($f);
		//}
	}
	
	//������ ���������
	public function Edit($txt, $filename='../parts/footer1.txt'){
		$f=fopen($filename, "w");
		fwrite($f, ($txt), strlen($txt));
		fclose($f);
	}
	
	//�������� �������� 
	public function GetItem($filename='../parts/footer1.txt'){
		if(!file_exists($filename)){
			$this->Create($filename);
		}
		if($f=fopen($filename, "r")) {}else{
			return false;
		}
		$txt='';
		while(!feof($f)){
			$l=fread($f,255);
			$txt=$txt.$l;
		}
		fclose($f);
		$this->text=($txt);
		return $txt;
	}
	
}
?>