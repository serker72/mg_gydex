<?
require_once('abstractgroup.php');
require_once('banitem.php');

// список всех баннеров
class BansGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='banners';
		$this->lang_tablename='banners_lang';
		$this->pagename='viewads.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='ban_id';
		$this->lang_id_name='lang_id';	
		
		
		$this->all_menu_template='banners/items.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
		$this->name_multilang='tpl/firms/subitem_name.html';
		
		$this->name_vis_check='tpl/firms/subitem_lang_vis_check.html';
	}
	
	
	
	//список итемов
	public function GetItems($mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_ban.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('listpagename',$this->pagename);
		
		$params=Array();
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		$query=$this->GenerateSQL($params,NULL, $paramsord, $query_count);
		
		//echo $query;
		
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//параметры имени
			//параметры
			$names=Array(); $params=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new BanItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				
				$pt=@pathinfo(stripslashes($mmi['photo_small']));
					
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['small_txt'])  );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'pict'=>stripslashes($mmi['photo_small']),
				'is_flash'=>$mmi['is_flash'],
				'flash_width'=>$mmi['flash_width'],
				'flash_height'=>$mmi['flash_height'],
				'flash_src'=>'/'.$pt['dirname'].'/'.$pt['filename']
				
				
				 );
			}
			
			$alls[]=Array('itemno'=>$f['id'], 'nameitems'=>$names, 'url'=>stripslashes($f['url']), 'kind'=>$f['kind'], 'valitems'=>$params);
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
	
	
	
	protected function GenerateSQL($params, $notparams=NULL, $orderbyparams=NULL, &$sql_count=''){
		$sql='';
		
		$sql='select * from '.$this->tablename;
		
		//запрос для посчета общего числа итемов
		$sql_count='select count(*) from '.$this->tablename;
		
		if(($notparams!=NULL)||($params!=NULL)){
			$sql.='  where ';
			$sql_count.='  where ';
		}
		
		$qq=''; $cter=0;
		foreach($params as $k=>$v){
			if($cter!=0) $qq.=' and ';
			$qq.=$k.'="'.$v.'" ';
			$cter++;
		}
		if($notparams!=NULL){
			$cter=0;
			foreach($notparams as $k=>$v){
				if($cter!=0) $qq.=' and ';
				$qq.=$k.'<>"'.$v.'" ';
				$cter++;
			}
		}
		
		$qq2='';
		if($orderbyparams!=NULL){
			$cter=0;
			foreach($orderbyparams as $k=>$v){
				if($cter==0) $qq2.=' order by ';
				$qq2.=$v.'';
				$cter++;
				if($cter!=count($orderbyparams)) $qq2.=', ';
			}
		}
		
		$sql=$sql.$qq.$qq2;
		$sql_count=$sql_count.$qq;
		
		return $sql;
	}
	
	
	
	
	//!!!!!!!!!!!!!!клиентский вывоД
	
	//вывод одного баннера
	public function GetBanner($lang_id=LANG_CODE, $template='bannersblock.html', $kind=0){
		$sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		$sql= 'select t.url, l.photo_small, l.small_txt  from '.$this->tablename.' as t inner join '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'") where t.kind="'.$kind.'" and l.is_shown="1" order by t.ord desc, rand() limit 1';
		$items=new mysqlSet($sql);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			$sm->assign('has_banner',true);
			$sm->assign('url',stripslashes($f['url']));
			$sm->assign('pict',stripslashes($f['photo_small']));
			$sm->assign('alttext',htmlspecialchars(strip_tags(stripslashes($f['small_txt']))));
			
		}else $sm->assign('has_banner',false);
		
		
		return $sm->fetch($template);
	}
	
	//вывод одного баннера
	public function GetBannerArr($lang_id=LANG_CODE, $kind=0){
		$sql= 'select t.url, l.photo_small, l.small_txt, l.is_flash, l.flash_width, l.flash_height  from '.$this->tablename.' as t inner join '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'") where l.is_shown="1" and t.kind="'.$kind.'" order by t.ord desc, rand() limit 1';
		$items=new mysqlSet($sql);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f;
			/*$f=mysqli_fetch_array($rs);
			$sm->assign('has_banner',true);
			$sm->assign('url',stripslashes($f['url']));
			$sm->assign('pict',stripslashes($f['photo_small']));
			$sm->assign('alttext',htmlspecialchars(strip_tags(stripslashes($f['small_txt']))));*/
			
		}else return NULL; //$sm->assign('has_banner',false);
		
		
	}
	
	
	//вывод одного баннера
	public function GetBanners($lang_id=LANG_CODE, $template='bannersblock.html', $kind=0){
		$sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		
		
		$arr=$this->GetBannersArr($lang_id, $kind);
		$sm->assign('items',$arr);
		
		return $sm->fetch($template);
	}
	
	//вывод одного баннера
	public function GetBannersArr($lang_id=LANG_CODE, $kind=0){
		$arr=array();
		$sql= 'select t.url, t.align_mode,  	t.break_after, l.photo_small, l.small_txt, l.is_flash, l.flash_width, l.flash_height  from '.$this->tablename.' as t inner join '.$this->lang_tablename.' as l on (t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'="'.$lang_id.'") where l.is_shown="1" and t.kind="'.$kind.'" order by t.ord desc, t.id asc ';
		$items=new mysqlSet($sql);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		
		for($i=0;$i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
		 
			$arr[]=$f;
		}
		
		return $arr;
	}
	
	
	
	
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang='tpl/firms/subitem_name.html', $name_vis_check='tpl/firms/subitem_lang_vis_check.html'){
		$this->all_menu_template=$all_menu_template;
		$this->menuitem_template=$menuitem_template;	
		$this->menuitem_template_blocked=$menuitem_template_blocked;
		$this->razbivka_template=$razbivka_template;
		$this->name_multilang=$name_multilang;
		$this->name_vis_check=$name_vis_check;
		
		return true;
	}
}
?>