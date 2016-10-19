<?
require_once('abstractgroup.php');




// список языков проекта
class LangGroup extends AbstractGroup {
	
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='langs';
		$this->pagename='viewlangs.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->all_menu_template='langs.html';
		$this->menuitem_template='tpl/langs/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
	}
	
	
	
	//список итемов
	public function GetItems($mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_lang.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$query='select * from '.$this->tablename;
		$query_count='select count(*) from '.$this->tablename;
		
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		
		$totalcount=$items->getResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['id']==LANG_CODE) $is_editable=false; else $is_editable=true;
			
			$alls[]=Array('is_editable'=>$is_editable, 'id'=>$f['id'], 'name'=>stripslashes($f['lang_name']), 'descr'=>'<img src="/'.stripslashes($f['lang_flag']).'" alt="" border="0">', 'is_visible'=>$f['is_shown']);
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		return $txt;
	}
	
	
	
	
	//список итемов
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
	}
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	
	//сколько итемов
	public function CalcItemsById($id, $mode=0){
		
		if($mode==0){
			$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.';';
		}else{
			$query='select count(*) from '.$this->tablename.' where '.$this->vis_name.'=1 and '.$this->subkeyname.'='.$id.';';
		}
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}
	
	
	
	//получение списка  id языков
	public function GetLangsIdList(){
		$arr=Array();
		$lan=new MysqlSet('select * from '.$this->tablename);
		$rs=$lan->GetResult();
		$rc=$lan->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			$arr[]=$f['id'];
		}
		
		return $arr;
	
	}
	
	//получение списка  языков
	public function GetLangsList(){
		$arr=Array();
		$lan=new MysqlSet('select * from '.$this->tablename);
		$rs=$lan->GetResult();
		$rc=$lan->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			$arr[]=Array(
				'id'=>$f['id'],
				'lang_flag'=>$f['lang_flag'],
				'lang_name'=>$f['lang_name'],
				
			);
		}
		
		return $arr;
	
	}
	
	//построение клиентского списка
	public function GetSwitchCli($as_big=false,$template=NULL){
		/*$txt='';
		$lan=new MysqlSet('select * from '.$this->tablename.' where '.$this->vis_name.'="1"');
		$rs=$lan->GetResult();
		$rc=$lan->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
		
			$razb=new parse_class();
			if($template==NULL) $razb->get_tpl('tpl/flag.tpl'); //Файл который мы будем парсить
			else $razb->get_tpl($template); 
			$razb->set_tpl('{langno}',$f['id']);
			if($as_big) $razb->set_tpl('{flag}',stripslashes($f['lang_flag_bigger']));
			else $razb->set_tpl('{flag}',stripslashes($f['lang_flag'])); 
			$razb->set_tpl('{langname}',strip_tags(stripslashes($f['lang_name'])));
			$razb->tpl_parse(); //Парсим
			$txt.=$razb->template;
		}
		
		return $txt;*/
		
		$alls=Array();
		$lan=new MysqlSet('select * from '.$this->tablename.' where '.$this->vis_name.'="1"');
		$rs=$lan->GetResult();
		$rc=$lan->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($as_big) $flag=stripslashes($f['lang_flag_bigger']);
			else $flag=stripslashes($f['lang_flag']); 
			$alls[]=Array(
				'langno'=>$f['id'],
				'langname'=>strip_tags(stripslashes($f['lang_name'])),
				'flag'=>$flag
			);
		}
		return $alls;
	}
	
	
}
?>