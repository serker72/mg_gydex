<?
require_once('abstractlangitem.php');
require_once('photoitem.php');
require_once('priceitem.php');
require_once('paperitem.php');
require_once('linkitem.php');
require_once('alldictitem.php');
require_once('newsitem.php');



//итем меню
class MmenuItem extends AbstractLangItem{

	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu';
		$this->lang_tablename='menu_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='mid';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	public function ShowFirst($id, $lang=1, $is_shown=0){
		
		if($is_shown==0)	$query='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.parent_id='.$id.' and t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'='.$lang.' order by ord desc, id asc limit 1';
		else $query='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.parent_id='.$id.' and t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'='.$lang.' and l.'.$this->vis_name.'=1 order by ord desc, id asc limit 1';
		
		$item=new mysqlSet($query);
		
		$result=$item->getResult();
		$rc=$item->getResultNumRows();
		unset($item);
		if($rc!=0){
			$res=mysqli_fetch_array($result);
			$this->item= Array();
			foreach($res as $key=>$val){
				$this->item[$key]=$val;
			}
			
			return $this->item;
		} else {
//			echo 'ccc'; die();
			$this->item=NULL;
			return false;
		}
		
	}
	
	//добавить 
	public function Add($params, $lang_params){
		
		
		$it1=new AbstractItem(); $it2=new AbstractItem();
		
		//параметры неязыковые
		$it1->SetTableName($this->tablename);
		
		//проверить URL
		if(HAS_URLS){
			$url=$params['path'];
			if(isset($params['parent_id'])) 	$url=$this->GenUniqURL($url,NULL,$params['parent_id']);
			else $url=$this->GenUniqURL($url);
			$params['path']=$url;
		}
		
		
		$mid=$it1->Add($params);
		
		//параметры языковые
		$lang_params[$this->mid_name]=$mid;
		$it2->SetTableName($this->lang_tablename);
		$it2->Add($lang_params);
		
		unset($it1,$it2);
		return $mid;
	}
	
	
	
	//генерим урл уникальный!
	public function GenUniqURL($url,$except=NULL,$parent_id=NULL){
		//$url;
		$cou=$this->CheckURL($url,$except,$parent_id);
		
		$cter=1; $over=0;
		while($cou>0){
			$url.=$cter;
			$cou=$this->CheckURL($url,$except,$parent_id);
			if(strlen($url)>80) {
				$url=$over;
				$over++;
				$cter=1;
			}
			$cter++;
		}
		
		return $url;
	}
	
	
	//подсчет количества разделов с таким урл
	public function CheckURL($url,$except=NULL,$parent_id=NULL){
		$sql='select count(*) from '.$this->tablename.' where path="'.$url.'"';
		if($except!==NULL) $sql.=' and id<>"'.$except.'"';
		if($parent_id!==NULL) $sql.=' and parent_id="'.$parent_id.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		
		return $f['count(*)'];
	}
	
	
	//определение раздела по урл
	public function GetItemByURL($url,$lang_id,$is_shown=1){
		//перебрать урл, от начала до конца проверить все совпадения, и только у конечного непустого подурла выдать результат
		$has=false; 
		/*
		
		$has=false; $parent_id=0;
		if(NUM_LEVELS!='-')
			$arr=explode('/',$url,NUM_LEVELS+2);
		else $arr=explode('/',$url);

		foreach($arr as $k=>$v){
			//echo "<br>$k -   $v<br>";
			$v=SecStr($v,10);
			if($v!=''){
				
				//делаем проверки
				if($is_shown==1) $sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.path="'.$v.'" and l.'.$this->mid_name.'=t.id and l.is_shown="1" and t.parent_id="'.$parent_id.'" and l.'.$this->lang_id_name.'="'.$lang_id.'"';
				else if($is_shown==0) $sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.path="'.$v.'" and l.'.$this->mid_name.'=t.id and t.parent_id="'.$parent_id.'" and l.'.$this->lang_id_name.'="'.$lang_id.'"';
				
				//echo $sql;
				$set=new MysqlSet($sql);
				$rs=$set->GetResult();
				if($set->GetResultNumRows()>0){
					$f=mysqli_fetch_array($rs);
					$res=$f;
					$parent_id=$f['id'];
					$has=true;
					//echo "<br>$k -   $v<br>";
				}else{
					$has=false;
					//не найден такой раздел, выходим!!!
					break;
				}
			}
		}
		
		*/
		
		//делаем проверки
				if($is_shown==1) $sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.path="'.$url.'" and l.'.$this->mid_name.'=t.id and l.is_shown="1"  and l.'.$this->lang_id_name.'="'.$lang_id.'"';
				else if($is_shown==0) $sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.path="'.$url.'" and l.'.$this->mid_name.'=t.id and l.'.$this->lang_id_name.'="'.$lang_id.'"';
				
				//echo $sql;
				$set=new MysqlSet($sql);
				$rs=$set->GetResult();
				if($set->GetResultNumRows()>0){
					$f=mysqli_fetch_array($rs);
					$res=$f;
					$parent_id=$f['id'];
					$has=true;
					//echo "<br>$k -   $v<br>";
				}else{
					$has=false;
					//не найден такой раздел, выходим!!!
				//	break;
				}
		
		if($has){
			return $res;
		}else{
			return false;
		}
		
	}
	
	
	//править
	public function Edit($id,$params=NULL, $lang_params=NULL){
		
		$it1=new AbstractItem(); 
		
		//параметры неязыковые
		if($params!==NULL){
			$it1->SetTableName($this->tablename);
			
			//проверить URL
			if(HAS_URLS){
				if(isset($params['path'])){
					$url=$params['path'];
					//$url=$this->GenUniqURL($url,$id);
					if(isset($params['parent_id'])) 	$url=$this->GenUniqURL($url,$id,$params['parent_id']);
					else $url=$this->GenUniqURL($url,$id);
					$params['path']=$url;
				}
			}
			
			$it1->Edit($id,$params);
			unset($it1);
		}
		
		
		//параметры языковые
		if($lang_params!==NULL){
			$qq='';
			foreach($lang_params as $key=>$val){
				if(($key!=$this->mid_name)&&($key!=$this->lang_id_name)){
					if($qq=='') $qq.=$key.'="'.$val.'"';
					else $qq.=','.$key.'="'.$val.'"';
				}
			}
			
			$query='update '.$this->lang_tablename.' set '.$qq.' where '.$this->mid_name.'="'.$id.'" and '.$this->lang_id_name.'="'.$lang_params[$this->lang_id_name].'"';
			
			$it=new nonSet($query);
			if(DEBUG_INFO) {
					echo $query;
					echo mysql_error();
			}
			
			unset($it);
		}
		
	}
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//список всех вложенных
		$arr=Array();
		
		//находим все подразделы
		$this->SubsListView($id, $arr);
		
		//добавим сам раздел в список
		$arr[]=$id;
		
		//строим запрос на удаление найденных подразделов+самого раздела
		$cter=1; $q='';
		
		foreach($arr as $k=>$v){
			$q.=' '.$v;
			if($cter<count($arr)) $q.=',';
			$cter++;
		}
		
		
		//удалить все итемы!!!
		$dict=new AllDictItem();
		$dict->DelGroup($q);
		
		$news=new NewsItem();
		$news->DelGroup($q);
		
		$links=new LinkItem();
		$links->DelGroup($q);
		
		$papers=new PaperItem();
		$papers->DelGroup($q);
		
		
		$photos=new PhotoItem();
		$photos->DelGroup($q);
		
		$goods=new PriceItem();
		$goods->DelGroup($q);
		
		
		$q1='delete from '.$this->tablename.' where id in(';
		$q2='delete from '.$this->lang_tablename.' where '.$this->mid_name.' in(';
		
		$q1.=$q.');';	$q2.=$q.');';
		
		//удаляем раздел+подразделы
		$ns=new nonSet($q1);
		
		$ns=new nonSet($q2);
		
		
		//удаляем сам раздел
		//$this->DelOne($id);
		
		$this->item=NULL;
	}	
	
	
	
	//Вспомогательная функция при удалении раздела	
	//РЕКУРСИЯ по списку
	//строим список всех подразделов
	protected function SubsListView($id,&$arr){
		$l_arr=$this->GetSubsList($id, $arr);
		if(count($l_arr)>0){
			foreach($l_arr as $k=>$v){
				$this->SubsListView($v,$arr);
			}
		}
		
	}
	
	//Вспомогательная функция при удалении раздела
	//список всех вложенных подразделов
	protected function GetSubsList($id, &$arr){
		$l_arr=Array();
		$query='select * from '.$this->tablename.' where parent_id="'.$id.'"';
		$set=new mysqlSet($query);
		$count=$set->GetResultNumRows();
		if($count>0){
			$rs=$set->GetResult();
			for($i=0;$i<$count;$i++){
				$f=mysqli_fetch_array($rs);
				$arr[]=$f['id'];
				$l_arr[]=$f['id'];
			}
		}
		return $l_arr;
	}
	
	
	//удаление одного итема
	public function DelOne($id){
		
		$it1=new AbstractItem(); 
		
		//параметры неязыковые
		$it1->SetTableName($this->tablename);
		$it1->Del($id);
		unset($it1);
		
		//параметры языковые
		$query = 'delete from '.$this->lang_tablename.' where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		if(DEBUG_INFO) {
				echo $query;
				echo mysql_error();
			}
		unset($it);				
	
	}
	
	
	
	
	//строим навигацию админскую
	public function DrawNavig($id, $lang_code=1, $is_shown=0,$endtext=' правка раздела '){
		$txt='';
		$arr=$this->RetrievePath($id, $flaglost, $vloj, $lang_code, $is_shown);
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		$alls=Array();
		
		$alls[]=Array(
			'itemname'=>'главная',
			'filepath'=>'index.php',
			'has_symb'=>true
		);
		
		$alls[]=Array(
			'itemname'=>'начало',
			'filepath'=>'razds.php',
			'has_symb'=>true
		);
		
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				//$strs.= "$kk $vv<br>";
				
				$alls[]=Array(
					'itemname'=>stripslashes($vv['name']),
					'filepath'=>'razds.php?id='.$kk,
					'has_symb'=>true
				);
			}
		}
		
		$sm->assign('items',$alls);
		$sm->assign('symb',' &gt; ');
		
		$sm->assign('aftertext',$endtext);
		$txt=$sm->fetch('navi.html');
		
		return $txt;
	}
	
	
	//строим навигацию Клиентскую
	//игнорируем аргументы-шаблоны!!!
	public function DrawNavigCli($id, $lang_code=1, $is_shown=0, $template1='tpl/navi.html',$template2='tpl/naviitem.html',$template3='tpl/naviitem.html',$fintext=' правка раздела ',$separator='/', $has_last=true){
		$txt='';
		
		$arr=Array();
		$arr=$this->RetrievePath($id, $flaglost, $vloj, $lang_code, $is_shown);
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		$alls=Array();
		//впишем ссылку на корневую страницу
		$alls[]=Array(
			'itemname'=>'Главная',
			'filepath'=>'/',
			'has_symb'=>true,
			'symb'=>$separator
		);
		
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				
				if(HAS_URLS){
					$path=$this->ConstructPath($kk,$lang_code,$is_shown,'/');
				}else{
					$path='razds.php?id='.$kk;
				}
				
				if($kk!=$id) $has_symb=true; else $has_symb=false;
				
				$alls[]=Array(
					'itemname'=>stripslashes($vv['name']),
					'filepath'=>$path,
					'has_symb'=>$has_symb,
					'symb'=>$separator
				);
			}
		}
		
		$sm->assign('items',$alls);
		$sm->assign('aftertext',$fintext);
		$sm->assign('has_last',$has_last);
		$txt=$sm->fetch('navi.html');
		
		return $txt;
	}
	
	
	//конструируем строку вида /path1/path2/...
	//описывающую путь к текущему разделу в урлах
	public function ConstructPath($id,$lang_code=LANG_CODE,$is_shown=0,$separator='/'){
		$path='';
		$t_arr=Array();
		$t_arr=$this->RetrievePath($id, $flaglost, $vloj, $lang_code, $is_shown);
		
		if($flaglost) {
			//echo ' LOST ';
			return $this->error404;//'/404.php';
		}
		
		//echo 'beg <p>';
		foreach($t_arr as $tk=>$tv){
			//echo "odna strukt <p>";
			foreach ($tv as $key=>$value){
				//echo "podstr <p>";
				//echo "$key $value<p>";
				if($key!=0){
					/*$tm=new MmenuItem();
					$ttm=$tm->GetItemById($key,$lang_code);
					$path.=stripslashes($ttm['path']).$separator;*/
					//unset($tm);
					$path.=stripslashes($value['path']).$separator;
					//echo "$key = $value<p>";
				}else $path=$separator;
			}
		}
		//echo " end: $path<p>";
		return '/'.$path;
	}
	
	
	
	//получаем путь к разделу
	//а также глубину вложенности
	public function RetrievePath($id, &$flaglost, &$vloj, $lang_code=1, $is_shown=0){
		unset($path);
		$path=Array(); 
		//если тру, то есть потеря пути!
		$flaglost=false;
		$vloj=0;
		
		
		$x=$this->GetItemById($id, $lang_code, $is_shown);
		
		if($x!=false){
			$temp_arr=Array();
			//$temp_arr[$x['id']]=$x['name'];
			$temp_arr[$x['id']]=Array(
						'name'=>$x['name'],
						'path'=>$x['path']
					);
			
			$path[]=$temp_arr;
		
		}else $flaglost=true;
		
		
		if($x['parent_id']!=0){
			$parent_id=$x['parent_id'];
			$count=999;
			while(($count!=0)&&($parent_id!=0)){
				//echo $is_shown;
				//echo $x['parent_id'];
				
				$x=$this->GetItemById($parent_id, $lang_code, $is_shown);
				if($x!=false){
					$count=999;
					$parent_id=$x['parent_id'];
										//echo "$f[id]<br>";
					
					$temp_arr=Array();
					//$temp_arr[$x['id']]=$x['name'];
					$temp_arr[$x['id']]=Array(
						'name'=>$x['name'],
						'path'=>$x['path']
					);
					$path[]=$temp_arr;
					$vloj++;
				}else{
					$count=0;
					$flaglost=true;
					//echo 'qqqqqqqqqqqqqqqqqqqqqqq';
				}
			}
		
		}
		
		$path=array_reverse($path);
		//array_reverse($path);
		return $path;
	}
	
	
	
	//проверка на существование-видимость со всей возможной вложенностью
	public function CheckFullExistance($id,$lang_id=1,$is_shown=1){
		$t_arr=Array();
		$t_arr=$this->RetrievePath($id, $flaglost, $vloj, $lang_id, $is_shown);
		if($flaglost) return false;
		else return true;
	}
	
}
?>