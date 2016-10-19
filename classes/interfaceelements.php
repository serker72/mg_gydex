<?
require_once('smarty/SmartyAdm.class.php');

//класс вывода элементов интерфейса:
//чекбоксы, радиокнопки

class InterfaceElements{
	
	//собственные шаблоны итема меню
	protected $flags_check_template;
	protected $flags_radio_template;	
	
	public function __construct(){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
			
		$this->flags_check_template='subitem_check.html';
		$this->flags_radio_template='subitem_radio.html';
	
	}
	
	
	//вспом функция вывода чекбокса
	public function GetCheckbox($f,$pretext,$flagname, $caption, $value='', $template=NULL,$flagaddname=''){//$this->flags_check_template){
		$txt='';
		
		if($template===NULL) $template=$this->flags_check_template;
		
		$flagst=new SmartyAdm;
		
		$flagst->assign('itemno',$f['id']);
		$flagst->assign('pretext',$pretext);
			$flagst->assign('valuetext',$value);
		$flagst->assign('flagname',$flagaddname.$flagname);
		if($f[$flagname]==1) $flagst->assign('checkarea',' checked');
		else $flagst->assign('checkarea',' ');
		$flagst->assign('caption',$caption);
		
		$txt.=$flagst->fetch($template);
		
		//echo htmlspecialchars($txt)."<p>";
		return $txt;
	}
	
	//вспом функция вывода радиокнопка
	public function GetRadio($f,$pretext,$flagname,$radvalue, $caption){
		$txt='';
		
		$flagst=new SmartyAdm;
		$flagst->assign('itemno',$f['id']);
		$flagst->assign('pretext',$pretext);
		$flagst->assign('radvalue',$radvalue);
		if($f[$flagname]==1) $flagst->assign('checkarea',' checked');
		else $flagst->assign('checkarea',' ');
		$flagst->assign('caption',$caption);
		
		$txt.=$flagst->fetch($this->flags_radio_template);
		return $txt;
	}
	
	public function SetTemplates($flags_check_template,$flags_radio_template){
		$this->flags_check_template=$flags_check_template;
		$this->flags_radio_template=$flags_radio_template;
	}
};
?>
