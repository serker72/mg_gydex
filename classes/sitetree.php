<?
require_once('global.php');
require_once('langitem.php');
require_once('langgroup.php');
require_once('mmenuitem.php');
require_once('resoursefile.php');


require_once('photoitem.php');
require_once('newsitem.php');
require_once('priceitem.php');
require_once('paperitem.php');

require_once('smarty/SmartyAdm.class.php');

//класс - дерево сайта
class SiteTree{
	protected $lang_id;
	protected $templates=Array();
	protected $non_tabs=Array(); //нетабличные разделы сайта
	protected $rf;
	protected $langs=Array(); //список языков для перебора по нему в админском дереве
	protected $is_new_pages=false; //открывать ссылки с карты в новых окнах
	
	public function __construct($lang_id=LANG_CODE){
		$this->init($lang_id);
	}
	
	//установка всех имен
	protected function init($lang_id){
		$this->lang_id=$lang_id;
		$this->rf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		//УСТАНОВКА имен нетабличных разделов
		$this->non_tabs[]=Array(
			'url' => '/',
			'name' => $this->rf->GetValue('hmenu.php','main_caption',$this->lang_id),
			'before_all' => true
		);
		
		/*$this->non_tabs[]=Array(
			'url' => '/search.php',
			'name' => $this->rf->GetValue('hmenu.php','search_caption',$this->lang_id),
			'before_all' => false
		);
		
		if(HAS_BASKET){
		$this->non_tabs[]=Array(
			'url' => '/profile.php',
			'name' => $this->rf->GetValue('hmenu.php','profile_caption',$this->lang_id),
			'before_all' => false
		);
		}
		
		$this->non_tabs[]=Array(
			'url' => '/map.php',
			'name' => $this->rf->GetValue('hmenu.php','tree_caption',$this->lang_id),
			'before_all' => false
		);*/
		
	}
	
//*************************************** КЛИЕНТСКИЕ ФУНКЦИИ *****************************************
	
	//клиентское дерево сайта
	public function DrawTreeCli(){
		
		$txt='';
		//рисуем разделы до табличных
		$txt.=$this->DrawNonTabs(true);
		
		//вызываем служебную функцию для нулевого уровня вложенности
		$previous_path='/';
		$txt.=$this->GetTreeCli(0, $previous_path);
		
				//рисуем разделы после табличных
		$txt.=$this->DrawNonTabs(false);
		return $txt;
	}
	
	//служебный блок
	//рекурсивная функция отрисовки уровня дерева клиенсткая
	protected function GetTreeCli($id, $previous_path){
		$txt='';
		$sql=' select t.id, t.path, l.name, t.is_price, t.is_news, t.is_papers
		from allmenu as t INNER JOIN menu_lang as l ON(t.id=l.mid and l.lang_id="'.$this->lang_id.'" and l.is_shown="1")
		where  t.parent_id="'.$id.'"
		order by t.is_hor desc, t.ord desc, t.id
		';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		$alls=Array();
		$mi=new MmenuItem;
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$path=$mi->ConstructPath($f[0],$this->lang_id); //$previous_path.stripslashes($f[1]).'/';
			//выводим этот подраздел
			if(HAS_URLS) {}
			else $path='/razds.php?id='.$f[0];
			
			//выводим все подразделы этого раздела (рекурсия)
			$subs=$this->GetTreeCli($f[0], $path);
			
			
			//статьи, товары, новости
			$subs.=$this->GetContents($f[0], $f, $this->lang_id);
			 
			$alls[]=Array(
				'url'=>$path,
				'name'=>stripslashes($f[2]),
				'subs'=>$subs
			);
		}
		
		$sm->assign('is_new', $this->is_new_pages);
		$sm->assign('items',$alls);
		$txt=$sm->fetch($this->templates['level']);
		return $txt;
	}
	
	
	//получение контента, если есть
	protected function GetContents($id, $f, $lang_id){
		
		
		$txt.='';
			//получить фото, статьи, товары, нововсти
			if($f['is_news']==1){
				
				$_ni=new NewsItem;  
				$sm=new SmartyAdm;
				$sm->debug=DEBUG_INFO;
				$alls=array();
				
				$sql1='select t.*, l.name
		from 	news_item as t INNER JOIN 	news_lang as l ON(t.id=l.news_id and l.lang_id="'.$lang_id.'" and l.is_shown="1")
		where  t.mid="'.$f[0].'"
		order by t.ord desc, t.id';
		
				//echo $sql1;
				$set1=new mysqlSet($sql1);
				$rs1=$set1->GetResult();
				$rc1=$set1->GetResultNumRows();
				for($i1=0;$i1<$rc1;$i1++){				
					$f1=mysqli_fetch_array($rs1);
					
					$path1=SITEURL.$_ni->ConstructUrl($f1['id'], $lang_id, NULL, 1);
					$f1['url']=$path1;
					
					$f1['name']=DateFromYmd($f1['pdate']).' '.$f1['name'];
					
					$alls[]=$f1;
				}
				
					
				$sm->assign('is_new', $this->is_new_pages);
				$sm->assign('items',$alls);
				$txt.=$sm->fetch($this->templates['level']);
			}
			if($f['is_papers']==1){
				$_pi=new PaperItem; 
				$sm=new SmartyAdm;
				$sm->debug=DEBUG_INFO;
				$alls=array();
				$sql1='select t.*, l.name
		from 	paper_item as t INNER JOIN 	paper_lang as l ON(t.id=l.paper_id and l.lang_id="'.$lang_id.'" and l.is_shown="1")
		where  t.mid="'.$f[0].'"
		order by t.ord desc, t.id';
		
				//echo $sql1;
				$set1=new mysqlSet($sql1);
				$rs1=$set1->GetResult();
				$rc1=$set1->GetResultNumRows();
				for($i1=0;$i1<$rc1;$i1++){				
					$f1=mysqli_fetch_array($rs1);
					
					$path1=SITEURL.$_pi->ConstructPath( $f1['id'],  $lang_id);
					$f1['url']=$path1;
					
					if($f1['pdate']!="") $f1['name']=DateFromYmd($f1['pdate']).' '.$f1['name'];
					
					$alls[]=$f1;
				}
				
					
				$sm->assign('is_new', $this->is_new_pages);
				$sm->assign('items',$alls);
				$txt.=$sm->fetch($this->templates['level']);
			}
			
			if($f['is_price']==1){
				$_pri=new PriceItem;
				
				$sm=new SmartyAdm;
				$sm->debug=DEBUG_INFO;
				$alls=array();
						
				$sql1='select t.*, l.name
		from 	price_item as t INNER JOIN 	price_lang as l ON(t.id=l.price_id and l.lang_id="'.$lang_id.'" and l.is_shown="1")
		where  t.mid="'.$f[0].'"
		order by t.ord desc, t.id';
		
				//echo $sql1;
				$set1=new mysqlSet($sql1);
				$rs1=$set1->GetResult();
				$rc1=$set1->GetResultNumRows();
				for($i1=0;$i1<$rc1;$i1++){				
					$f1=mysqli_fetch_array($rs1);
					
					$path1=SITEURL.$_pri->ConstructPath( $f1['id'],  $lang_id);
					
					$f1['url']=$path1;
					
					$alls[]=$f1;
				}
				
					
				$sm->assign('is_new', $this->is_new_pages);
				$sm->assign('items',$alls);
				$txt.=$sm->fetch($this->templates['level']);
			}
		return $txt;
	}
	
	
	
	//отрисовка 1 элемента дерева
	/*protected function DrawOneCli($f,$path){
		$txt='';
		$tpl=new parse_class();
		$tpl->get_tpl($this->templates['item']);
		if(HAS_URLS) $tpl->set_tpl('{url}', $path);
		else $tpl->set_tpl('{url}', '/razds.php?id='.$f[0]);
		
		$tpl->set_tpl('{name}', stripslashes($f[2]));
		if($this->is_new_pages) $tpl->set_tpl('{newzone}', 'target="_blank"');
		else  $tpl->set_tpl('{newzone}', '');
		$tpl->tpl_parse();
		$txt=$tpl->template;
		return $txt;
	}*/
	
	//отрисовка нетабличных эл-тов дерева
	protected function DrawNonTabs($before_all){
		$txt='';
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		$alls=Array();
		
		foreach($this->non_tabs as $k=>$v){
			if($v['before_all']==$before_all){
				$alls[]=Array(
					'url'=>$v['url'],
					'name'=>$v['name'],
					'subs'=>''
				);
			}
		}
		
		$sm->assign('is_new', $this->is_new_pages);
		$sm->assign('items',$alls);
		$txt=$sm->fetch($this->templates['level']);
		return $txt;
	}
//****************************** ENDOF КЛИЕНТСКИЕ ФУНКЦИИ *****************************************


//*************************************** АДМИНСКИЕ ФУНКЦИИ *****************************************
	//админское дерево сайта
	public function DrawTreeAdm(){
		$txt='';
		$lg=new LangGroup();
		$this->langs=$lg->GetLangsList();
		//вызываем служебную функцию для нулевого уровня вложенности
		$previous_path='/';
		$txt.=$this->GetTreeAdm(0, $previous_path);
		
		return $txt;
	}
	//админские отрисовки
	//рекурсивная функция отрисовки уровня дерева админская
	protected function GetTreeAdm($id, $previous_path){
		$txt='';
		$sql=' select *
		from allmenu as t
		where  t.parent_id="'.$id.'"
		order by t.is_hor desc, t.ord desc, t.id
		';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		
		$mi=new MmenuItem();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$path=$previous_path.stripslashes($f['path']).'/';
			
			//выводим этот подраздел
			$defs='';
		
			if(HAS_PRICE&&($f['is_price']==1)) $defs.='<a href="viewpriceitems.php?id='.$f[0].'" target="_blank" title="Содержит каталог товаров"><img src="/img/newdis/modules/price18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит каталог товаров" ></a>';
			
			if(HAS_BASKET&&($f['is_basket']==1)) $defs.='<a href="viewpriceitems.php?id='.$f[0].'" target="_blank" title="Можно заказывать товар"><img src="/img/newdis/modules/basket18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Можно заказывать товар" ></a>';
			
			if(HAS_NEWS&&($f['is_news']==1)) $defs.='<a href="viewnews.php?id='.$f[0].'" target="_blank" title="Содержит новости"><img src="/img/newdis/modules/news18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит новости" ></a>';
			
			if(HAS_LINKS&&($f['is_links']==1)) $defs.='<a href="viewlinks.php?id='.$f[0].'" target="_blank" title="Содержит каталог ссылок"><img src="/img/newdis/modules/links18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит каталог ссылок" ></a>';
			
			if(HAS_PAPERS&&($f['is_papers']==1)) $defs.='<a href="viewpapers.php?id='.$f[0].'" target="_blank" title="Содержит статьи"><img src="/img/newdis/modules/papers18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит статьи" ></a>';
	
			if(HAS_GALLERY&&($f['is_gallery']==1)) $defs.='<a href="viewphotos.php?id='.$f[0].'" target="_blank" title="Содержит фотогалерею"><img src="/img/newdis/modules/photos18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит фотогалерею" ></a>';
	
			if(HAS_FEEDBACK_FORMS&&($f['is_feedback_forms']==1)) $defs.='<img src="/img/newdis/modules/feedback18.png" alt="" width="18" height="18" hspace="2" vspace="0" border="0" alt="Содержит формы обратной связи" >';
				
			//языки
			$langs=Array();
			$obst_name=$path;
			foreach($this->langs as $k=>$v){
				
				$mm=$mi->GetItemById($f['id'],$v['id']);
				if($mm!=false){
					//if(!HAS_URLS&&($v['id']==LANG_CODE)) $obst_name=stripslashes($mm['name']);
					
					
					$langs[]=Array('url'=>'ed_razd.php?action=1&id='.$f[0].'&doLang=1&lang_id='.$v['id'],
					'lang_flag'=>stripslashes($v['lang_flag']),
					 'name'=>stripslashes($mm['name']));
				}
			}
			
			//выводим все подразделы этого раздела (рекурсия)
			$subs=$this->GetTreeAdm($f['id'], $path);
			
			$obst_name=$mi->ConstructPath($f[0],LANG_CODE);
			
			$alls[]=Array('subs'=>$subs, 'obst_name'=>$obst_name, 'defs'=>$defs, 'url_into'=>'razds.php?id='.$f[0], 'langs'=>$langs);
		}
		
		$sm->assign('in_new', (int)$this->is_new_pages);
		$sm->assign('items',$alls);
		$txt=$sm->fetch($this->templates['level']);
		return $txt;
	}
	
	
	
	
//**********************************ENDOF АДМИНСКИЕ ФУНКЦИИ *****************************************	
	
	
	
	
	
	
	//инициализация шаблонов и признака открытия ссылок из дерева в новых окнах
	public function SetTemplates($templates){
		$this->templates=$templates;
	}
	
	public function SetNewPages($is_new_pages){
		$this->is_new_pages=$is_new_pages;
	}
}

?>