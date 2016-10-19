<?
require_once('global.php');
require_once('abstractlanggroup.php');
require_once('newsitem.php');

require_once('newsfilegroup.php');
require_once('commentgroup.php');



// группа новостей
class NewsGroup extends AbstractLangGroup{
	protected $name_multilang;
	
	//установка всех имен
	protected function init(){
		$this->tablename='news_item';
				$this->lang_tablename='news_lang';
		$this->pagename='viewnews.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='news_id';
		$this->lang_id_name='lang_id';	
		$this->name_multilang='tpl/news/subitem_name.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($id,$sortmode,$sortparams=NULL,$from=0,$to_page=ITEMS_PER_PAGE){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		if($sortmode==1){
			$sql='select * from 
			'.$this->tablename.'  where pdate="'.$sortparams['pdate'].'" and '.$this->subkeyname.'="'.$id.'" order by ord desc, id desc ';
			$sql_count='select count(*) from 
			'.$this->tablename.'  where pdate="'.$sortparams['pdate'].'"  and '.$this->subkeyname.'="'.$id.'"';
			
		}else{
			$sql='select * from 
			'.$this->tablename.' where '.$this->subkeyname.'="'.$id.'"  order by pdate desc, ord desc, id desc ';
			$sql_count='select count(*) from 
			'.$this->tablename.' where  '.$this->subkeyname.'="'.$id.'" ';
		}
		
		$set=new mysqlSet($sql,$to_page,$from,$sql_count);
		$rc=$set->GetResultNumRows();
		$totalcount=$set->GetResultNumRowsUnf();
		$rs=$set->GetResult();
		//echo $rc;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_news.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$id);
		$smarty->assign('listpagename',$this->pagename);
		$smarty->assign('sortmode',$sortmode);
		
		$srt_str='';
		if($sortparams===NULL){
			$smarty->assign('sortparamname','some');
			$smarty->assign('sortparamvalue','0');			
		}else {
			foreach($sortparams as $k=>$v){
				$smarty->assign('sortparamname',$k);
				$smarty->assign('sortparamvalue',$v);				
				$srt_str.="&$k=$v";
			}
		}
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&sortmode='.$sortmode.$srt_str.'&id='.$id);
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
				$mi=new NewsItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['small_txt']) );
			}
			
			$alls[]=Array('itemno'=>$f['id'], 'itemdate'=>DateFromYmd($f['pdate']),'nameitems'=>$names,'valitems'=>$params, 'photo_small'=>$f['photo_small'] );
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
	public function CalcItemsByDateId($id, $pdate, $lang_id=0){
		
		if($lang_id==0){
			$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.' and pdate="'.$pdate.'";';
		}else{
			$query = 'select count(*) from '.$this->tablename.' as t where t.'.$this->subkeyname.'='.$id.' and t.id in(select l.'.$this->mid_name.' from '.$this->lang_tablename.' as l where l.'.$this->mid_name.'=t.id and l.'.$this->lang_id_name.'="'.$lang_id.'") and t.pdate="'.$pdate.'"';
		}
		
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}
	
	
	
	
//***************************клиентский вывоД!!!!!!!!!!!!!!**********************************************
	
	//список новостей клиентский
	public function GetItemsByIdCli($template1, $parent_id, $lang_id=LANG_CODE,$from=0, $to_page=ITEMS_PER_PAGE,$flt_params=NULL,$sortmode=0){
		
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt'); $gd=new NewsItem();
		if($parent_id==0){
			$url_path='/';
		}else{
			$mi=new MmenuItem(); $mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
			if(HAS_URLS) $url_path=''.$mi->ConstructPath($parent_id,$lang_id,1,'/');
			else $url_path='/';
		}
		
		$ord_add=''; $ord_url='';
		if($sortmode==0) $ord_add=' order by t.pdate desc, t.ord desc, t.id desc ';
		if($sortmode==1) $ord_add=' order by t.ord desc, t.id desc ';
		
		 $ord_url='&datesortmode='.$sortmode;
		
		$flt_add=''; $flt_url='';
		if($flt_params!==NULL){
			foreach($flt_params as $k=>$v){
				if($v!=0){
					if($flt_add!=''){
					 	$flt_add.=', ';
					}else $flt_add.=' and ';
					$flt_add.=$k.'="'.$v.'"';
				}
				//для pdate исключение
				if($k=='t.pdate') $flt_url.='&pdate='.$v;
				else $flt_url.='&'.$k.'='.$v;
			}
		}
		
		$res_str=' t.id, t.pdate, l.name, l.small_txt, t.mid,  t.photo_small  ';
		if($parent_id==0){
			//1e 5 новостей по всем разделам
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where  l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add.' '.$ord_add.' limit 5';
			
			
		}else{
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where t.'.$this->subkeyname.'="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add.' '.$ord_add;
			
			$query_count = 'select count(*) from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where t.'.$this->subkeyname.'="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add;
		}
		//echo $query;
		if($parent_id==0){
			$items=new mysqlSet($query);
			$rs=$items->GetResult();
			$rc=$items->GetResultNumRows();
			$totalcount=$rc;
		}else{
			$items=new mysqlSet($query,$to_page,$from,$query_count);
			$rs=$items->GetResult();
			$rc=$items->GetResultNumRows();
			$totalcount=$items->GetResultNumRowsUnf();
		}
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$cter=1; $max=1;
		$strs='';
		if($parent_id==0){
			$pages='';
		}else{
			if(HAS_URLS){ 
				$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,$flt_url.$ord_url);
			}else $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&id='.$parent_id.$flt_url.$ord_url);
			$navig->setFirstParamName('nfrom');
			$navig->setDivWrapperName('alblinks');
			$navig->setPageDisplayDivName('alblinks1');			
			$pages= $navig->GetNavigator();
		}
		
		
		$_ni=new NewsItem;
		$_cg=new CommentGroup;
		$_fg=new NewsFileGroup;
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//t.id, t.pdate, l.name, l.small_txt
			 
			 
			$path=$_ni->ConstructUrl($f['id'], $lang_id);
			 
			
			
			$f['path']=$path;
			$f['num_comments']=$_cg->Calc($f['id'],'news', 1);
			$f['newsdate']=DateFromYmd($f[1]);
			
			$f['has_files']=$_fg->HasFiles($f['id']);
			$f['has_images']=$_fg->HasImages($f['id']);
			
			$f['images']=$_fg->GetImagesArr($f['id'],  'news_file.html', '/news_file_view.html');
			
			
			$alls[]=$f;
		 
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		if($rc>0) $txt.=$smarty->fetch($template1);
		else $txt.=$rf->GetValue('news_block.php','news_not_found',$lang_id);
		return $txt;
	//	return '<pre>'.htmlspecialchars($txt).'</pre>';
	}
	
	
	//список новостей клиентский
	public function GetItemsRecent($template1,  $lang_id=LANG_CODE, $limit=6, &$alls){
		
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt');  
		 
		$ord_add=''; $ord_url='';
		$ord_add=' order by t.pdate desc, t.ord desc, t.id desc ';
	  
		
		$flt_add=''; $flt_url='';
		
		
		$res_str=' t.id, t.pdate, l.name, l.small_txt, t.mid, t.photo_small ';
		
			//1e 6 новостей по всем разделам
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where  l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add.' '.$ord_add.' limit '.$limit;
			
			
	
	//echo $query;
		
			$items=new mysqlSet($query);
			$rs=$items->GetResult();
			$rc=$items->GetResultNumRows();
			 
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$cter=1; $max=1;
		$strs='';
		 
		$_ni=new NewsItem;
		$_cg=new CommentGroup;
		$_fg=new NewsFileGroup;
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//t.id, t.pdate, l.name, l.small_txt
			 
			 
			$path=$_ni->ConstructUrl($f['id'], $lang_id);
			 
			
			
			$f['path']=$path;
			$f['num_comments']=$_cg->Calc($f['id'],'news', 1);
			$f['newsdate']=DateFromYmd($f[1]);
			
			$f['has_files']=$_fg->HasFiles($f['id']);
			$f['has_images']=$_fg->HasImages($f['id']);
			
			$f['images']=$_fg->GetImagesArr($f['id'],  'news_file.html', '/news_file_view.html');
			
			
			$alls[]=$f;
		 
		}
		
	 
		$smarty->assign('items',$alls);
		
		 $txt.=$smarty->fetch($template1);
		 
		return $txt;
	//	return '<pre>'.htmlspecialchars($txt).'</pre>';
	}
	
	
	
	/*
		
	//список новостей клиентский
	public function GetItemsByIdCli($template1, $template2, $template3, $template4, $parent_id, $lang_id=LANG_CODE,$from=0, $to_page=ITEMS_PER_PAGE,$flt_params=NULL,$sortmode=0){
		
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt'); $gd=new NewsItem();
		if($parent_id==0){
			$url_path='/';
		}else{
			$mi=new MmenuItem(); $mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
			if(HAS_URLS) $url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
			else $url_path='/';
		}
		
		$ord_add=''; $ord_url='';
		if($sortmode==0) $ord_add=' order by t.pdate desc, t.ord desc, t.id desc ';
		if($sortmode==1) $ord_add=' order by t.ord desc, t.id desc ';
		
		 $ord_url='&datesortmode='.$sortmode;
		
		$flt_add=''; $flt_url='';
		if($flt_params!==NULL){
			foreach($flt_params as $k=>$v){
				if($v!=0){
					if($flt_add!=''){
					 	$flt_add.=', ';
					}else $flt_add.=' and ';
					$flt_add.=$k.'="'.$v.'"';
				}
				//для pdate исключение
				if($k=='t.pdate') $flt_url.='&pdate='.$v;
				else $flt_url.='&'.$k.'='.$v;
			}
		}
		
		$res_str=' t.id, t.pdate, l.name, l.small_txt, t.mid ';
		if($parent_id==0){
			//1e 5 новостей по всем разделам
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where  l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add.' '.$ord_add.' limit 5';
			
			
		}else{
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where t.'.$this->subkeyname.'="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add.' '.$ord_add;
			
			$query_count = 'select count(*) from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where t.'.$this->subkeyname.'="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$flt_add;
		}
		//echo $query;
		if($parent_id==0){
			$items=new mysqlSet($query);
			$rs=$items->GetResult();
			$rc=$items->GetResultNumRows();
			$totalcount=$rc;
		}else{
			$items=new mysqlSet($query,$to_page,$from,$query_count);
			$rs=$items->GetResult();
			$rc=$items->GetResultNumRows();
			$totalcount=$items->GetResultNumRowsUnf();
		}
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$cter=1; $max=1;
		$strs='';
		if($parent_id==0){
			$pages='';
		}else{
			if(HAS_URLS){ 
				$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,$flt_url.$ord_url);
			}else $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&id='.$parent_id.$flt_url.$ord_url);
			$navig->setFirstParamName('nfrom');
			$navig->setDivWrapperName('alblinks');
			$navig->setPageDisplayDivName('alblinks1');			
			$pages= $navig->GetNavigator();
		}
		
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//t.id, t.pdate, l.name, l.small_txt
			if($cter==1){
				//соберем ряд
				$row=Array();
				//echo 'Ряд начат!!!<br>';
			}
			
			
			 
			if(HAS_URLS) $path='news_'.$f[0].'.html';
			else $path='readnews.php?id='.$f[0];
			$row[]=Array(
				'itemno'=>stripslashes($f[0]),
				'newsdate'=>DateFromYmd($f[1]),
				'name'=>stripslashes($f[2]),
				'td_width'=>floor(100/$max),
				'annot'=>stripslashes($f[3]),
				'page_url'=>$path,
				'more'=>$rf->GetValue('news_block.php','more',$lang_id)
			);
			
			
			
			if(($i==($rc-1))||($cter>=$max)){
				$cter=1;
				$alls[]=Array('cells'=>$row);
				//echo 'Ряд ended!!!<br>';
			}else $cter++;
		}
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		if($rc>0) $txt.=$smarty->fetch($template1);
		else $txt.=$rf->GetValue('news_block.php','news_not_found',$lang_id);
		return $txt;
	//	return '<pre>'.htmlspecialchars($txt).'</pre>';
	}
	*/


	//список новостей клиентский RSS
	public function GetItemsByIdCliRSS($template1, $template2, $parent_id, $lang_id=LANG_CODE, $to_page=ITEMS_PER_PAGE){
		$txt=''; $rf=new ResFile(ABSPATH.'cnf/resources.txt'); //$gd=new NewsItem();
		if($parent_id==0){
			$url_path='/';
		}else{
			$mi=new MmenuItem(); $mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
			if(HAS_URLS) $url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
			else $url_path='/';
		}
		
		$ord_add=''; $ord_url='';
		$ord_add=' order by t.pdate desc, t.ord desc, t.id desc ';
		
		// $ord_url='&datesortmode='.$sortmode;
		
		
		
		$res_str=' t.id, t.pdate, l.name, l.small_txt, t.mid ';
		if($parent_id==0){
			//по всем разделам
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where  l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$ord_add.' limit '.ITEMS_PER_PAGE;
			
			
		}else{
			$query = 'select '.$res_str.' from '.$this->tablename.' as t INNER JOIN '.$this->lang_tablename.' as l
			ON t.id=l.'.$this->mid_name.'
			where t.'.$this->subkeyname.'="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 '.$ord_add.' limit '.ITEMS_PER_PAGE;;
			
			
		}
		//echo $query;
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$totalcount=$rc;
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$strs='';
		$_ni=new NewsItem;
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			//t.id, t.pdate, l.name, l.small_txt
			
			if(HAS_URLS) $path=SITEURL.$_ni->ConstructUrl($f[0],1,null,1); //'readnews'.$f[0];
			else $path=SITEURL.'readnews.php?id='.$f[0];
			
			$alls[]=Array(
				'itemurl'=>$path,
				'more'=>$rf->GetValue('news_block.php','more',$lang_id),
				'annot'=>stripslashes($f[3]),
				'itemno'=>stripslashes($f[0]),
				'itemname'=>stripslashes($f[2]),
				'itemdate'=>UnixDateFromYmd($f[1])
			);
			
		}
		
		$smarty->assign('sitetitle',SITETITLE);
		$smarty->assign('siteurl',SITEURL);
		$smarty->assign('sitedescription',$rf->GetValue('news-rss','sitedescription',$lang_id));				
		$smarty->assign('language',$rf->GetValue('news-rss','language',$lang_id));				
		$smarty->assign('encoding',$rf->GetValue('news-rss','encoding',$lang_id));				
		$smarty->assign('builddate',date("r"));
		
		$smarty->assign('items',$alls);
		
		$txt=trim($smarty->fetch($template1),"\n");
		
		return $txt;
	}
	
}
?>