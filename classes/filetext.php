<?
require_once('global.php');
class FileText{
	protected $text=NULL; //ассоц массив со всеми полями
	
	//создание материала
	public function Create($filename='../parts/footer1.txt'){
		//если открываем для считывания
		//if(!file_exists($filename)){
		$f=fopen($filename, "w");
		fclose($f);
		//}
	}
	
	//правка материала
	public function Edit($txt, $filename='../parts/footer1.txt'){
		$f=fopen($filename, "w");
		fwrite($f, ($txt), strlen($txt));
		fclose($f);
	}
	
	//получить материал 
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