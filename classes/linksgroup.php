<?
require_once('abstractlanggroup.php');
require_once('linkitem.php');
require_once('PageNavigatorKri.php');

// список ссылок
class LinksGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $gal_view_templates=Array(); //список шаблонов для списка-галереи с прокруткой
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='link_item';
		$this->pagename='viewlinks.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->all_menu_template='tpl/itemstable.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
		$this->name_multilang='tpl/links/subitem_name.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($id,$mode=0,$from=0,$to_page=ITEMS_PER_PAGE){
		//список позиций
		$txt=''; $lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_link.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$id);
		$smarty->assign('listpagename',$this->pagename);
		
		$params=Array();
		$params[$this->subkeyname]=$id;
		$paramsord=Array();
		
		$paramsord[]=' ord desc ';
	//	$paramsord[]=' short_name asc ';

		$paramsord[]=' id  asc ';
		$query=$this->GenerateSQL($params,NULL, $paramsord, $query_count);
		//echo $query;
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&id='.$id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['use_simple_code']==1){
				//ссылка в виде простого кода
				//параметры имени
				$names=Array(); $params=Array();
				foreach($this->langs as $lk=>$g){
					$mi=new LinkItem();
					$mmi=$mi->GetItemById($f['id'],$g['id']);
					
					if($mmi!=false){
						$is_exist=true;
					}else {
						$is_exist=false;
					}
					
					$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['simple_code']) );
					
				}
				
				$alls[]=Array('itemno'=>$f['id'],'nameitems'=>$names, 'is_code'=>$f['use_simple_code']);
			}else{
				//ссылка с полями
				//параметры имени
				$names=Array(); $params=Array();
				foreach($this->langs as $lk=>$g){
					$mi=new LinkItem();
					$mmi=$mi->GetItemById($f['id'],$g['id']);
					
					if($mmi!=false){
						$is_exist=true;
					}else {
						$is_exist=false;
					}
					
					$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']), 'photopath'=>'/'.stripslashes($mmi['photo_small']) );
					
				}
				
				$alls[]=Array('itemno'=>$f['id'],'nameitems'=>$names, 'is_code'=>$f['use_simple_code'], 'url'=>stripslashes($f['url']));
			}
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
		
		$sql='select * from '.$this->tablename.'  where ';
		
		//запрос для посчета общего числа итемов
		$sql_count='select count(*) from '.$this->tablename.'  where ';
		
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
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! список ssylok клиентский !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	public function GetItemsByIdCli($template1, $template2, $template3, $template4, $parent_id, $lang_id=LANG_CODE, $from=0, $to_page=ITEMS_PER_PAGE){
		$txt='';
		
		$query='select * from '.$this->tablename.' as p, link_lang as l where l.link_id=p.id and p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 order by p.ord desc, p.id desc'; //$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		
		$query_count='select count(*) from '.$this->tablename.' as p, link_lang as l where l.link_id=p.id and p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1';
		
		$items=new mysqlSet($query); //,$to_page,$from,$query_count);
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
			$url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
			$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,'');
			
		}else $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&id='.$parent_id);
		$navig->setFirstParamName('lfrom');
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			
			if($f['use_simple_code']==1) {
				$alls[]=Array(
					'is_code'=>true,
					'code'=>stripslashes($f['simple_code']),
					'td_width'=>floor(100/$max)
				);
			}else {
				
				$im=GetImageSize(ABSPATH.stripslashes($f['photo_small']));
				if($im!=false){
					$image_w=$im[0];
					$image_h=$im[1];
				}else{
					$image_w=320;
					$image_h=240;
				}
				
				$alls[]=Array(
					'is_code'=>false,
					'id'=>$f['id'],
					'image_src'=>stripslashes($f['photo_small']),
					'image_big'=>stripslashes($f['photo_big']),
					'image_w'=>$image_w,
					'image_h'=>$image_h,
					'name'=>stripslashes($f['name']),
					'annot'=>stripslashes($f['small_txt']),
					'altname'=>strip_tags(stripslashes($f['name'])),
					'td_width'=>floor(100/$max),
					'page_url'=>stripslashes($f['url'])
				);
			}
			
		}
		
		$smarty->assign('max',$max);
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($template1);
		
		return $txt;
	}
	
	
	public function GetItemsCli($template1,   $lang_id=LANG_CODE){
		$txt='';
		
		$query='select * from '.$this->tablename.' as p, link_lang as l where l.link_id=p.id and   l.lang_id="'.$lang_id.'" and l.is_shown=1 order by p.ord desc, l.short_name asc, p.id desc'; //$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		
		$query_count='select count(*) from '.$this->tablename.' as p, link_lang as l where l.link_id=p.id and   l.lang_id="'.$lang_id.'" and l.is_shown=1';
		
		$items=new mysqlSet($query); //,$to_page,$from,$query_count);
		$rs=$items->GetResult();
		//echo $query;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$rc=$items->GetResultNumRows();
		
		$totalcount=$items->GetResultNumRowsUnf();
		
		$cter=1; $max=2;
		$strs='';
		
		 
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			
			if($f['use_simple_code']==1) {
				$alls[]=Array(
					'is_code'=>true,
					'code'=>stripslashes($f['simple_code']),
					'td_width'=>floor(100/$max)
				);
			}else {
				
				$im=GetImageSize(ABSPATH.stripslashes($f['photo_big']));
				if($im!=false){
					$image_w=$im[0];
					$image_h=$im[1];
				}else{
					$image_w=320;
					$image_h=240;
				}
				
				$alls[]=Array(
					'is_code'=>false,
					'id'=>$f['id'],
					'image_src'=>stripslashes($f['photo_small']),
					'image_big'=>stripslashes($f['photo_big']),
					'image_w'=>$image_w,
					'image_h'=>$image_h,
					'name'=>stripslashes($f['name']),
					'annot'=>stripslashes($f['small_txt']),
					'small_txt'=>stripslashes($f['small_txt']),
					'otrasl'=>stripslashes($f['otrasl']),
					'vnedr'=>stripslashes($f['vnedr']),										
					'altname'=>strip_tags(stripslashes($f['name'])),
					'td_width'=>floor(100/$max),
					'page_url'=>stripslashes($f['url'])
				);
			}
			
		}
		
		 
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($template1);
		
		return $txt;
	}
	
	
	
}
?>