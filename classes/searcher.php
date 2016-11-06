<?
require_once('MysqlSet.php');
require_once('global.php');
require_once('PageNavigator.php');
require_once('resoursefile.php');
require_once('smarty/SmartyAdm.class.php');

//поисковик
class Searcher{
	protected $s_mode; //режим вывода найденных итемов
	
	protected $tablename;//='mmenu';
	protected $pagename;//='viewsubs.php';	
	protected $vis_name;
	//protected $subkeyname='mid';
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;
	protected $index_defs;
	protected $from_name;
	protected $other_params; //другие параметры для разбивки по страницам
	protected $found_title;
	protected $not_found_title;
	protected $rf;
	protected $lang_id=LANG_CODE;
	
	protected $templates=Array();
	
	protected $is_fulltext=true;
	
	public function __construct(){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu';
		$this->lang_tablename='menu_lang';
		$this->pagename='/search.php';		
		$this->mid_name='mid';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
		$this->index_defs='name,txt,title';
		$this->from_name='from';
		$this->rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$this->other_params='';
		$this->s_mode=0; //разделы
		/*
		0 разделы
		1 stati
		2 firmy
		3 tovary
		4 foto
		*/
	}
	
	//блок поиска
	public function Search($word,$lang_id,$from=0,$to_page=ITEMS_PER_PAGE,$extra_params=NULL,$sort_params=NULL){
		$txt=''; $extra=''; $sort='';
		$this->lang_id=$lang_id;
		
		
		
		if($this->is_fulltext){
			if($extra_params!==NULL) $extra=', '.join(', ',$extra_params).', ';
			if($sort_params!==NULL) $sort=', '.join(', ',$sort_params);
			$sql='select t.id '.$extra.' MATCH('.$this->index_defs.') AGAINST("'.$word.'"  IN BOOLEAN MODE) as rel from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'" and l.'.$this->vis_name.'="1") having rel>0 order by rel desc '.$sort.' ';
			
			$sql_count='select count(t.id), MATCH('.$this->index_defs.') AGAINST("'.$word.'"  IN BOOLEAN MODE) as rel from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'" and l.'.$this->vis_name.'="1") group by rel having rel>0  ';
		}else{
			if($extra_params!==NULL) $extra=', '.join(', ',$extra_params).' ';
			if($sort_params!==NULL) $sort='order by '.join(', ',$sort_params);
			$like_params=split(',',$this->index_defs);
			
			$like_string=join(' LIKE "%'.$word.'%" or ',$like_params).' LIKE "%'.$word.'%" ';
			
			$sql='select t.id '.$extra.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'" and l.'.$this->vis_name.'="1") where '.$like_string.'   '.$sort.' ';
			
			$sql_count='select count(t.id) from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'" and l.'.$this->vis_name.'="1") where '.$like_string.' ';
		
		}
		
		$set=new MysqlSet($sql,$to_page,$from,$sql_count);
		//echo " $sql <p>";
		//echo $set->GetResultNumRows();
		$total=$set->GetResultNumRowsUnf();
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		
		if($total>0){
			
			$smarty=new SmartyAdm;
			$smarty->debugging=DEBUG_INFO;
			
			$smarty->assign('titletext',$this->found_title.' '.$total);
			
			//разбивка по страницам
			$navig = new PageNavigator($this->pagename,$total,$to_page,$from,10,'&qry='.$word.$this->other_params);
			$navig->setFirstParamName($this->from_name);
			$navig->setDivWrapperName('alblinks');
			$navig->setPageDisplayDivName('alblinks1');			
			$pages= $navig->GetNavigator();
			$smarty->assign('pages',$pages);
			$smarty->assign('mode',$this->s_mode);
			
			$rows=Array();
			for($i=0;$i<$rc;$i++){
				$f=mysqli_fetch_array($rs);
				$rows[]=$this->DrawItem($f);
				//echo " $f[rel] <br>";
			}
			$smarty->assign('items',$rows);
			$txt=$smarty->fetch($this->templates['section']);
		}else $txt=$this->not_found_title;
		
		return $txt;
	}
	
	//функция-диспетчер вывода каждой записи-резульатат поиска
	public function DrawItem($f){
		$res=Array();
		switch($this->s_mode){
			case 0:
				$res=$this->DrawRazd($f);
			break;
			case 1:
				$res=$this->DrawPaper($f);
			break;
			case 2:
				$res=$this->DrawFirm($f);
			break;
			case 3:
				$res=$this->DrawGood($f);
			break;
			case 4:
				$res=$this->DrawPhoto($f);
			break;
			case 5:
				//$res=$this->DrawPhoto($f);
			break;
			case 6:
				$res=$this->DrawNews($f);
			break;
			default:
				$res=$this->DrawRazd($f);
			break;
		}
		
		return $res;
	}
	
	
	
	//функция вывода раздела
	public function DrawRazd($f){
		
		$res=Array();
		$res['title']=stripslashes($f[1]);
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		if(HAS_URLS){
			$mi=new MmenuItem();
			$url=$mi->ConstructPath($f[0],$this->lang_id,1,'/');
		}else{
			$url='/razds.php?id='.$f[0];
		}
		//echo " $url ";
		$res['url']=$url;
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	//функция вывода статьи
	public function DrawPaper($f){
		$res=Array();
		
		$res['title']=stripslashes($f[1]);
		$res['altname']=htmlspecialchars(stripslashes($f[1]));
		$res['image_src']=stripslashes($f[3]);
		
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		$pap=new PaperItem();
		$url=$pap->ConstructPath($f[0],$this->lang_id,'/');
		//echo " $url ";
		$res['url']=$url;
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	//функция вывода фирмы
	public function DrawFirm($f){
		$res=Array();
		$res['name']=stripslashes($f[1]);
		$res['altname']=htmlspecialchars(stripslashes($f[1]));
		$res['image_src']=stripslashes($f[3]);
		
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		$fi=new FirmItem();
		$firm=$fi->GetItemById($f[0],$this->lang_id,1);
		if($firm!=false){
			$res['url']='/firm.php?id='.$f[0];
		}else $res['url']='';
		
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	//функция вывода товара
	public function DrawGood($f){
		$res=Array();
		$res['name']=stripslashes($f[1]);
		$res['altname']=htmlspecialchars(stripslashes($f[1]));
		$res['image_src']=stripslashes($f[3]);
		
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		$pap=new PriceItem();
		$pap->price_disp->SetShowErrors(false);
		$url=$pap->ConstructPath($f[0],$this->lang_id,'/');
		$res['url']=$url;
		//цена
		$price=$pap->price_disp->GetGoodBasePrice($f[0],$this->lang_id,true);
		$res['price']=$price;
		//фирма
		$fi=new FirmItem();
		$firm=$fi->GetItemById($f[4],$this->lang_id,1);
		if($firm!=false){
			$res['firm']='<strong>'.($this->rf->GetValue('good.php','firm-manufacturer',$this->lang_id)).'</strong> <a href="/firm.php?id='.$f[4].'" target="_blank">'.stripslashes($firm['name']).'</a>';
		}else $res['firm']='';
		
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	
	//функция вывода foto
	public function DrawPhoto($f){
		$res=Array();
		$res['name']=stripslashes($f[1]);
		$res['altname']=htmlspecialchars(stripslashes($f[1]));
		$res['image_src']=stripslashes($f[3]);
		
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		$pap=new PhotoItem();
		$url=$pap->ConstructPath($f[0],$this->lang_id,'/');
		//echo " $url ";
		$res['url']=$url;
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	//функция вывода новости
	public function DrawNews($f){
		$res=Array();
		
		$res['title']=stripslashes($f[1]);
		$res['altname']=htmlspecialchars(stripslashes($f[1]));
		$res['image_src']=stripslashes($f[3]);
		
		$annot=stripslashes(substr(strip_tags($f[2]),0,255));
		if($annot!='') $annot.='...';
		$res['annot']=$annot;
		//url
		$pap=new NewsItem();
		$url=$pap->ConstructUrl($f[0],$this->lang_id,NULL,1);
		//echo " $url ";
		$res['url']=$url;
		$res['more']=$this->rf->GetValue('search.php','more_caption',$this->lang_id);
		return $res;
	}
	
	
	
	public function SetParams($tablename,$lang_tablename,$mid_name, $lang_id_name,$vis_name,$from_name,$index_defs,$found_title,$not_found_title,$other_params){
		$this->tablename=$tablename;
		$this->lang_tablename=$lang_tablename;
		$this->mid_name=$mid_name;
		$this->lang_id_name=$lang_id_name;
		$this->vis_name=$vis_name;
		$this->from_name=$from_name;
		$this->index_defs=$index_defs;
		$this->found_title=$found_title;
		$this->not_found_title=$not_found_title;
		$this->other_params=$other_params;
	}
	
	
	public function SetTemplates($templates){
		$this->templates=$templates;
	}
	
	public function SetMode($mode){
		$this->s_mode=$mode;
	}
}
?>