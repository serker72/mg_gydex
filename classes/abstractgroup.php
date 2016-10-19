<?

require_once('NonSet.php');
require_once('MysqlSet.php');
require_once('PageNavigator.php');

require_once('interfaceelements.php');
//require_once('smarty/SmartyAdm.class.php');


// абстрактная группа итемов (без языковых таблиц)
class AbstractGroup {
	protected $tablename;//='mmenu';
	protected $pagename;//='viewsubs.php';	
	protected $subkeyname;//='mmenu';
	protected $vis_name;
	
	protected $all_menu_template;
	protected $menuitem_template;	
	protected $menuitem_template_blocked;
	protected $razbivka_template;
	
	
	
	//класс для вывода чекбоксов и пр.
	protected $interface;
	
	public function __construct(){
		$this->interface=new InterfaceElements();
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->tablename='table';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';

		
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
	
	
	//подсчет числа итемов (подразделов, ...)
	public function CountChildsById($id,$flagname='parent_id'){
		$sql='select count(*) from '.$this->tablename.' where '.$flagname.'="'.$id.'"';
		$set=new mysqlSet($sql);
		$r=$set->GetResult();
		
		$f=mysqli_fetch_array($r);
		
		return $f['count(*)'];
		
	}
	
	
	//установка наименования поля id
	public function SetIdName($name='mid'){
		$this->subkeyname=$name;
	}
	
	//установка имени страницы для вывода номеров навигатора
	public function SetPageName($name='subs.php'){
		$this->pagename=$name;
	}
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template){
		$this->all_menu_template=$all_menu_template;
		$this->menuitem_template=$menuitem_template;	
		$this->menuitem_template_blocked=$menuitem_template_blocked;
		$this->razbivka_template=$razbivka_template;
		
		
		return true;
	}
	
	
	
	//получение наименования поля id
	public function GetIdName(){
		return $this->subkeyname;
	}
	
	
	protected function GenerateSQL($params){
		$sql='';
		return $sql;
	}
	
	
	
	//итемы в тегах option
	public function GetItemsOpt($current_id=0,$fieldname='name'){
		$txt='';
		$sql='select * from '.$this->tablename;
		$set=new mysqlSet($sql);
		$tc=$set->GetResultNumRows();
		if($tc>0){
			$rs=$set->GetResult();
			for($i=0;$i<$tc;$i++){
				$f=mysqli_fetch_array($rs);
				$txt.="<option value=\"$f[id]\" ";
				
				if($current_id==$f['id']) $txt.='SELECTED';
				
				$txt.=">".htmlspecialchars(stripslashes($f[$fieldname]))."</option>";
			}
		}
		return $txt;
	}
	
	public function GetPageName(){ return $this->pagename; }
	
}
?>