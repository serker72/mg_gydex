<?
require_once('abstractgroup.php');
require_once('firmitem.php');

// список всех фирм
class FirmsGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='firms';
		$this->lang_tablename='firms_lang';
		$this->pagename='viewfirms.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='firmid';
		$this->lang_id_name='lang_id';	
		
		
		$this->all_menu_template='firms/items.html';
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
		
		$smarty->assign('filename','ed_firm.php');
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
				$mi=new FirmItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['info']) );
			}
			
			$alls[]=Array('itemno'=>$f['id'], 'pic'=>stripslashes($f['photo_small']), 'nameitems'=>$names, 'valitems'=>$params);
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
        // KSK 20.11.2016 - вывод списка фирм, товары которых входят в выбранную категорию
	public function GetItemsByIdCli($template1, $template2, $template3, $template4, $parent_id, $lang_id=LANG_CODE, $from=0, $to_page=ITEMS_PER_PAGE){
		$txt='';
		
		$query = 'select * 
                    from '.$this->tablename.' as p, firms_lang as l
                    where p.id in (select distinct firmid from price_item where mid="'.$parent_id.'")
                        and l.firmid=p.id 
                        and l.lang_id="'.$lang_id.'"
                        and l.is_shown=1 
                    order by p.ord desc, p.id desc';
		
		$query_count='select count(*)
                    from '.$this->tablename.' as p, firms_lang as l
                    where p.id in (select distinct firmid from price_item where mid="'.$parent_id.'")
                        and l.firmid=p.id 
                        and l.lang_id="'.$lang_id.'"
                        and l.is_shown=1';
		
		$items=new mysqlSet($query, $to_page, $from, $query_count);
		$rs=$items->GetResult();
		//echo $query;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$rc=$items->GetResultNumRows();
		
		$totalcount=$items->GetResultNumRowsUnf();
		
		$cter=1; $max=2;
		$strs='';
		
		if(HAS_URLS){ 
			$mi=new MmenuItem();
			$url_path=$mi->ConstructPath($parent_id,$lang_id,1,'/');
			$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,'&show_firms_only=1');
		}else {
                    $url_path = $this->pagename;
                    $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&show_firms_only=1&id='.$parent_id);
                }
                
		$navig->setFirstParamName('from');
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
                    $f = mysqli_fetch_array($rs);
			
                    $im = GetImageSize(ABSPATH.stripslashes($f['photo_big']));
                    if($im != false){
                        $image_w = $im[0];
                        $image_h = $im[1];
                    }else{
                        $image_w = 320;
                        $image_h = 240;
                    }
                    
                    $alls[]=Array(
                        'is_code'=>false,
                        'id'=>$f['id'],
                        'image_src'=>stripslashes($f['photo_small']),
                        'image_big'=>stripslashes($f['photo_big']),
                        'image_w'=>$image_w,
                        'image_h'=>$image_h,
                        'name'=>stripslashes($f['name']),
                        'annot'=>stripslashes($f['info']),
                        'altname'=>strip_tags(stripslashes($f['name'])),
                        'td_width'=>floor(100/$max),
                        'page_url'=>stripslashes($f['url']),
                        'url_path' => stripslashes($url_path),
                    );
		}
		
		$smarty->assign('max',$max);
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($template1);
		
		return $txt;
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