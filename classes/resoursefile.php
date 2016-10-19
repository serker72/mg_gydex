<?
require_once('langitem.php');
//работа с ресурсами
class ResFile{
	protected $filename;
	protected $resfiles;
	protected $resstrings;
	protected $reslangs;
	
	//шаблоны
	protected $table='tpl/reseditor/table.html';
	protected $filerow='tpl/reseditor/filerow.html';
	protected $resrow='tpl/reseditor/resrow.html';	
	protected $valuerow='tpl/reseditor/valuerow.html';
	
	public function __construct($filename='../cnf/resources.txt'){
		$this->filename=$filename;
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
	
		$this->resfiles=Array();
		$this->resstrings=Array();
		$this->reslangs=Array();
		$this->GetResources();
	}
	
	//установка имени файла
	public function SetFileName($name){
		$this->filename=$name;
		return true;
	}
	
	//установка шаблонов
	public function SetTemplates($table,$filerow,$resrow,$valuerow){
		$this->table=$table;
		$this->filerow=$filerow;
		$this->resrow=$resrow;
		$this->valuerow=$valuerow;
	}
	
	
	
	
	//получение файла
	public function GetFile($file_id){
		$res=false;
		foreach($this->resfiles as $k=>$file){
			if($file['file_id']==$file_id){
				//return $file;
				$res=$file; break;
			}
		}
		return $res;
	}
	
	
	//добавление файла
	public function AddFile($params){
		if($this->GetFile($params['file_id'])!=false){
			$this->EdFile($params['file_id'],$params);
		}else{
			$this->resfiles[]=Array(
				'file_id'=>$params['file_id'],
				'descr'=>  $params['descr'],
				'res' => Array()
			);
			
			//запишем в файл
			$this->SetResources();
		}
	}
	//правка файла (имя, пояснения, строки)
	public function EdFile($file_id,$params){
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$v){
			if($v['file_id']!=$file_id){
				$tmp[]=$v;
			}else{
				//правим
				$tmp[]=Array(
					'file_id'=>$params['file_id'],
					'descr'=>  $params['descr'],
					'res' => $v['res']
				);
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	//удаление файла
	public function DelFile($file_id){
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$v){
			if($v['file_id']!=$file_id){
				$tmp[]=$v;
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	
	
	
	
	//получение строкового ресурса
	public function GetRes($file_id,$res_id){
		$res=false; $file_it=NULL;
		foreach($this->resfiles as $k=>$file){
			if($file['file_id']==$file_id){
				$file_it=$file;
			}
		}
		if($file_it!=NULL){
			foreach($file_it['res'] as $k=>$v){
				if($v['res_id']==$res_id){
					$res=$v; break;
				}
			}
		}
		return $res;
	}
	
	//добавить строковый ресурс в файл
	public function AddRes($file_id,$params){
		//проверим, есть ли такой ресурс
		if($this->GetRes($file_id,$params['res_id'])!=false){
			//есть ресурс, правим
			$this->EdRes($file_id,$params['res_id'],$params);
		}else{
			//нету ресурса
			//перебираем файлы
			
			
			$tmp=Array(); 
			foreach($this->resfiles as $k=>$file){
				if($file['file_id']!=$file_id){
					$tmp[]=$file;
				}else{
					//добавляем такой ресурс этому файлу
					$our_res=$file['res'];
					$our_res[]=Array(
						'res_id'=>$params['res_id'],
						'descr'=>$params['descr'],
						'reslang'=>Array()
					);
					
					$tmp[]=Array(
						'file_id'=>$file['file_id'],
						'descr'=>  $file['descr'],
						'res' => $our_res
					);
				}
			}
			$this->resfiles=Array(); $this->resfiles=$tmp;
			//запишем в файл
			$this->SetResources();
		}
		
		
	}
	//править строковый ресурс в файле
	public function EdRes($file_id,$res_id,$params){
		//переберем файлы
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$v){
			if($v['file_id']!=$file_id){
				$tmp[]=$v;
			}else{
				//переберем ресурсы
				$temp_res=Array();
				foreach($v['res'] as $kk=>$res){
					if($res['res_id']==$res_id){
						$temp_res[]=Array(
							'res_id'=>$params['res_id'],
							'descr'=>$params['descr'],
							'reslang'=>$res['reslang'],
						);
					}else{
						$temp_res[]=$res;
					}
				}
				
				//правим
				$tmp[]=Array(
					'file_id'=>$v['file_id'],
					'descr'=>  $v['descr'],
					'res' => $temp_res
				);
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	//удалить строковый ресурс из файла
	public function DelRes($file_id,$res_id){
		//переберем файлы
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$v){
			if($v['file_id']!=$file_id){
				$tmp[]=$v;
			}else{
				//переберем ресурсы
				$temp_res=Array();
				foreach($v['res'] as $kk=>$res){
					if($res['res_id']==$res_id){
						
					}else{
						$temp_res[]=$res;
					}
				}
				
				//правим
				$tmp[]=Array(
					'file_id'=>$v['file_id'],
					'descr'=>  $v['descr'],
					'res' => $temp_res
				);
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	
	//получить значение (с возвратом res#:__ undefined value)
	public function GetValue($file_id,$res_id,$lang_id){
		$res=$this->GetResLang($file_id,$res_id,$lang_id);
		if($res==false){
			return 'file_id: '.$file_id.' resource #'.$res_id.': undefined value';
		}else return stripslashes($res['value']);
	}
	
	//получить языковое значение
	public function GetResLang($file_id,$res_id,$lang_id){
		$res=false; $file_it=NULL; $res_it=NULL;
		foreach($this->resfiles as $k=>$file){
			if($file['file_id']==$file_id){
				$file_it=$file;
			}
		}
		if($file_it!=NULL){
			foreach($file_it['res'] as $k=>$v){
				if($v['res_id']==$res_id){
					//$res=$v; break;
					$res_it=$v;
				}
			}
			
			if($res_it!=NULL){
				foreach($res_it['reslang'] as $k=>$v){
					if($v['lang_id']==$lang_id){
						$res=$v; break;
					}
				}
			}
		}
		return $res;
		
	}
	
	//добавить языковое значение к ресурсу
	public function AddResLang($file_id,$res_id,$params){
		if($this->GetResLang($file_id,$res_id,$params['lang_id'])!=false){
			//есть ресурс, правим
			$this->EdResLang($file_id,$res_id,$params['lang_id'],$params);
		}else{
			//нету ресурса
			//перебираем файлы
			$tmp=Array(); 
			foreach($this->resfiles as $k=>$file){
				if($file['file_id']!=$file_id){
					$tmp[]=$file;
				}else{
					//тот файл
					$our_res=Array();
					//перебираем ресурсы, ищем нужный
					foreach($file['res'] as $kk=>$res){
						if($res['res_id']==$res_id){
							//нашли нужный ресурс, добавим нужное яз значение
							$our_reslang=$res['reslang'];
							$our_reslang[]=Array(
								'lang_id'=>$params['lang_id'],
								'value'=>$params['value']
							);
							$our_res[]=Array(
								'res_id'=>$res['res_id'],
								'descr'=>$res['descr'],
								'reslang'=>$our_reslang
							);
						}else{
							$our_res[]=$res;
						}
					}
					
										
					$tmp[]=Array(
						'file_id'=>$file['file_id'],
						'descr'=>  $file['descr'],
						'res' => $our_res
					);
				}
			}
			$this->resfiles=Array(); $this->resfiles=$tmp;
			//запишем в файл
			$this->SetResources();
		}
	}
	//править языковое значение 
	public function EdResLang($file_id,$res_id,$lang_id,$params){
		//перебираем файлы
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$file){
			if($file['file_id']!=$file_id){
				$tmp[]=$file;
			}else{
				//тот файл
				$our_res=Array();
				//перебираем ресурсы, ищем нужный
				foreach($file['res'] as $kk=>$res){
					if($res['res_id']==$res_id){
						//нашли нужный ресурс
						//перебираем яз значения
						$our_reslang=Array();
						foreach($res['reslang'] as $kkk=>$reslang){
							if($reslang['lang_id']==$lang_id){
								//нужная яз значение
								$our_reslang[]=Array(
									'lang_id'=>$lang_id,
									'value'=>$params['value']
								);
							}else{
								$our_reslang[]=$reslang;
							}
						}
						$our_res[]=Array(
							'res_id'=>$res['res_id'],
							'descr'=>$res['descr'],
							'reslang'=>$our_reslang
						);
					}else{
						$our_res[]=$res;
					}
				}
				$tmp[]=Array(
					'file_id'=>$file['file_id'],
					'descr'=>  $file['descr'],
					'res' => $our_res
				);
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	//удалить языковое значение из ресурса
	public function DelResLang($file_id,$res_id,$lang_id){
		//перебираем файлы
		$tmp=Array(); 
		foreach($this->resfiles as $k=>$file){
			if($file['file_id']!=$file_id){
				$tmp[]=$file;
			}else{
				//тот файл
				$our_res=Array();
				//перебираем ресурсы, ищем нужный
				foreach($file['res'] as $kk=>$res){
					if($res['res_id']==$res_id){
						//нашли нужный ресурс
						//перебираем яз значения
						$our_reslang=Array();
						foreach($res['reslang'] as $kkk=>$reslang){
							if($reslang['lang_id']==$lang_id){
								//нужная яз значение
								/**/
							}else{
								$our_reslang[]=$reslang;
							}
						}
						$our_res[]=Array(
							'res_id'=>$res['res_id'],
							'descr'=>$res['descr'],
							'reslang'=>$our_reslang
						);
					}else{
						$our_res[]=$res;
					}
				}
				$tmp[]=Array(
					'file_id'=>$file['file_id'],
					'descr'=>  $file['descr'],
					'res' => $our_res
				);
			}
		}
		$this->resfiles=Array(); $this->resfiles=$tmp;
		//запишем в файл
		$this->SetResources();
	}
	
	
	
//********************** дерево яз ресурсов ********************************************************************	
//ПЕРЕВЕСТИ НА ШАБЛОНЫ!!!!!!!!!!!!!!!!!!	
	/*public function DrawTree(){
		$txt='';
		
		$t1=new parse_class();
		$t1->get_tpl($this->table); 
		$tt1='';		
		foreach($this->resfiles as $k=>$file){
			$t2=new parse_class();
			$t2->get_tpl($this->filerow); 
			$tt2='';		
			
			$t2->set_tpl('{fileno}',stripslashes($file['file_id']));
			$t2->set_tpl('{filename}',stripslashes($file['file_id']));
			$t2->set_tpl('{filedescr}',stripslashes($file['descr']));
			
			foreach($file['res'] as $kk=>$res){
				$t3=new parse_class();
				$t3->get_tpl($this->resrow); 
				$tt3='';		
				
				$t3->set_tpl('{fileno}',stripslashes($file['file_id']));
				$t3->set_tpl('{resno}',stripslashes($res['res_id']));
				$t3->set_tpl('{resdescr}',stripslashes($res['descr']));
				$t3->set_tpl('{resname}',stripslashes($res['res_id']));
				
				foreach($res['reslang'] as $kkk=>$reslang){
					
					$li=new LangItem();
					$lang=$li->GetItemById($reslang['lang_id']);
					if($lang!=false){
						$t4=new parse_class();
						$t4->get_tpl($this->valuerow); 
						
						$t4->set_tpl('{fileno}',stripslashes($file['file_id']));
						$t4->set_tpl('{resno}',stripslashes($res['res_id']));
						$t4->set_tpl('{langno}',stripslashes($reslang['lang_id']));
						$t4->set_tpl('{value}',htmlspecialchars(stripslashes($reslang['value'])));
						$t4->set_tpl('{lang_flag}',stripslashes($lang['lang_flag']));
						
						$t4->tpl_parse();
						$tt3.=$t4->template;
					}
				}
				$t3->set_tpl('{values}',$tt3);
				$t3->tpl_parse();
				$tt2.=$t3->template;
			}
			$t2->set_tpl('{resourses}',$tt2);
			$t2->tpl_parse();
			$tt1.=$t2->template;
			
		}
		$t1->set_tpl('{items}',$tt1);
		$t1->tpl_parse(); //Парсим
		$txt.=$t1->template;
	
		
		return $txt;
	}*/
	
	
	//дерево через Смарти
	public function DrawTree($full_tree=false){
		$txt='';
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$alls=Array();
		foreach($this->resfiles as $k=>$file){
			$ress=Array();
			foreach($file['res'] as $kk=>$res){
				$langs=Array();
				foreach($res['reslang'] as $kkk=>$reslang){
					$li=new LangItem();
					$lang=$li->GetItemById($reslang['lang_id']);
					if($lang!=false){
						$langs[]=Array(
							'langno'=>stripslashes($reslang['lang_id']),
							'value'=>htmlspecialchars(stripslashes($reslang['value'])),
							'lang_flag'=>stripslashes($lang['lang_flag'])
						);
					}
				}
				$ress[]=Array(
					'resno'=>stripslashes($res['res_id']),
					'resname'=>stripslashes($res['res_id']),
					'resdescr'=>stripslashes($res['descr']),
					'langs'=>$langs
				);
			}
			$alls[]=Array(
				'fileno'=>stripslashes($file['file_id']),
				'filename'=>stripslashes($file['file_id']),
				'filedescr'=>stripslashes($file['descr']),
				'ress'=>$ress
			);
		}
		$smarty->assign('files',$alls);
		$smarty->assign('full_tree',$full_tree);
		$txt=$smarty->fetch($this->table);
		
		
		 
		
		return $txt;
	}
	
	
	
	//сортировка библиотеки по имени файла методом пузырька
	public function SortRF(){
		//проходим по файлам и сортируем ресурсы
		foreach($this->resfiles as $k=>$v){
			$resset=Array(); $resset=$v['res'];
			$len=count($resset);
			for($i=0; $i<$len; $i++){
				 for( $j = $len-1; $j > $i; $j-- ) {     // внутренний цикл прохода
	      			
					$minus_item=$resset[$j-1]; $plus_item=$resset[$j];
					if($minus_item['res_id']>$plus_item['res_id']){
						$old=$minus_item; $minus_item=$plus_item; $plus_item=$old;
						$resset[$j-1]=$minus_item; $resset[$j]=$plus_item;
					}
				}
			}
			
			$new_arr=Array('file_id'=>$v['file_id'], 'descr'=>$v['descr'], 'res'=>$resset);
			unset($this->resfiles[$k]); 
			$this->resfiles[$k]=$new_arr;
		}
		
		//сортируем по айди файла
		$len=count($this->resfiles);
		for($i=0; $i<$len; $i++){
			 for( $j = $len-1; $j > $i; $j-- ) {     // внутренний цикл прохода
      			
				$minus_item=$this->resfiles[$j-1]; $plus_item=$this->resfiles[$j];
				if($minus_item['file_id']>$plus_item['file_id']){
					$old=$minus_item; $minus_item=$plus_item; $plus_item=$old;
					$this->resfiles[$j-1]=$minus_item; $this->resfiles[$j]=$plus_item;
				}
			}
		}
		
		//запишем в файл
		$this->SetResources();
	}
	
//******************************************** Блок служебных функций ********************************************************
	
	//считывание Ресурсов из файла
	protected function GetResources(){
		$txt=$this->ReadFileToString();
		if($txt!=false){
			$sets=unserialize($txt);
			$this->resfiles=$sets;
		}
		//return $this->resfiles;
	}
	
	//запись Ресурсов в файл
	protected function SetResources(){
		$txt=serialize($this->resfiles);
		$this->WriteStringToFile($txt);
		//return $this->resfiles;
	}
	
	
	
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
	
}
?>