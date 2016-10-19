<?
require_once('abstractlanggroup.php');
require_once('photoitem.php');
require_once('PageNavigatorKri.php');

// список фото
class PhotosGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $gal_view_templates=Array(); //список шаблонов для списка-галереи с прокруткой
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='photo_item';
		$this->pagename='viewphotos.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->all_menu_template='tpl/itemstable.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/to_page.html';
		
		$this->name_multilang='tpl/photogal/subitem_name.html';
	}
	
	
	
	//список итемов
	public function GetItemsById($id,$mode=0,$from=0,$to_page=PHOTOS_PER_PAGE){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('filename','ed_photo.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$id);
		$smarty->assign('listpagename',$this->pagename);
		$smarty->assign('manyfilename','add_photos.php');
		
		$params=Array();
		$params[$this->subkeyname]=$id;
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		$paramsord[]=' id ';
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
			
			//параметры имени
			//параметры
			$names=Array(); $params=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new PhotoItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				$params[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'descr'=>stripslashes($mmi['small_txt']) );
			}
			
			$alls[]=Array('itemno'=>$f['id'],'nameitems'=>$names, 'valitems'=>$params, 'photopath'=>stripslashes($f['photo_small']));
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
	
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! список фотографий клиентский !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function GetItemsByIdCli($template1, $template2, $template3, $parent_id, $lang_id=LANG_CODE, $from=0, $to_page=PHOTOS_PER_PAGE){
		$txt='';
		
		$query='select * from '.$this->tablename.' as p, photo_lang as l where l.photo_id=p.id and p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 order by p.ord desc, p.id'; //$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		
		$query_count='select count(*) from '.$this->tablename.' as p, photo_lang as l where l.photo_id=p.id and p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1';
		
		$items=new mysqlSet($query,$to_page,$from,$query_count);
		$rs=$items->GetResult();
		//echo $query;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$rc=$items->GetResultNumRows();
		
		$totalcount=$items->GetResultNumRowsUnf();
		
		$cter=1; $max=4;
		$strs='';
		
		if(HAS_URLS){ 
			$mi=new MmenuItem();
			$url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
			$navig = new PageNavigatorKri($url_path,$totalcount,$to_page,$from,10,'');
			
		}else $navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&id='.$parent_id);
		$navig->setFirstParamName('ffrom');
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array(); $pi=new PhotoItem;
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($cter==1){
				//соберем ряд
				$row=Array();
				//echo 'Ряд начат!!!<br>';
			}
			
			
			$im=GetImageSize(ABSPATH.stripslashes($f['photo_small']));
			if($im!=false){
				$image_w=$im[0];
				$image_h=$im[1];
			}else{
				$image_w=320;
				$image_h=240;
			}
			
			//разобраться с page_url!!!
			/*if(HAS_URLS){
				$path=$url_path.'photos'.$f['id'];
			}else $path='photo.php?id='.$f['id'];
			*/
			$path=$pi->ConstructPath($f['id'],$lang_id);
			
			$row[]=Array(
				'image_src'=>stripslashes($f['photo_small']),
				'image_w'=>$image_w,
				'image_h'=>$image_h,
				'page_url'=>$path,
				
				'name'=>stripslashes($f['small_txt']),
				'altname'=>strip_tags(stripslashes($f['name'])),
				'td_width'=>floor(100/$max)
				
			);
			
			if(($i==($rc-1))||($cter>=$max)){
				$cter=1;
				$alls[]=Array('cells'=>$row);
				//echo 'Ряд ended!!!<br>';
			}else $cter++;
		}
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		$txt=$smarty->fetch($template1);
		
		return $txt;
	}
	
	
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! список фотографий клиентский !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function GetItemsByIdJq($parent_id, $lang_id=LANG_CODE, $template='q', $max=4){
		$txt='';
		
		$query='select * from '.$this->tablename.' as p, photo_lang as l where l.photo_id=p.id and p.mid="'.$parent_id.'" and l.lang_id="'.$lang_id.'" and l.is_shown=1 order by p.ord desc, p.id'; 
		
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		//echo $query;
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$rc=$items->GetResultNumRows();
		
		$totalcount=$items->GetResultNumRowsUnf();
		
		$cter=1; 
		$strs='';
		
		$alls=Array(); $pi=new PhotoItem;
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			
			$im=GetImageSize(ABSPATH.stripslashes($f['photo_small']));
			if($im!=false){
				$image_w=$im[0];
				$image_h=$im[1];
			}else{
				$image_w=320;
				$image_h=240;
			}
			
			$path=$pi->ConstructPath($f['id'],$lang_id);
			
			$alls[]=Array(
				'image_src'=>stripslashes($f['photo_small']),
				'image_big'=>stripslashes($f['photo_big']),
				'image_w'=>$image_w,
				'image_h'=>$image_h,
				'page_url'=>$path,
				
				'name'=>stripslashes($f['small_txt']),
				'altname'=>strip_tags(stripslashes($f['name'])),
				'td_width'=>floor(100/$max)
				
			);
			
			
		}
		
		$smarty->assign('max',$max);
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		$txt=$smarty->fetch($template);
		
		return $txt;
	}
	
	
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! список из фото+4 ближайшие+ пред, след !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

	public function GetGalView($current_id, $parent_id, $lang_id=LANG_CODE){
		$txt=''; $max_row_photo=5; $half_max=floor($max_row_photo/2);
		$pi=new PhotoItem;
		$has_next=false; $has_prev=false;

		$mi=new MmenuItem(); 
		if(HAS_URLS) $url_path='/'.$mi->ConstructPath($parent_id,$lang_id,1,'/');
		else $url_path='/';

		
		//строим массив из всех фото
		$sql='select  p.id, p.photo_small, pl.name, pl.small_txt  from '.$this->tablename.' as p, photo_lang as pl where pl.photo_id=p.id and p.mid="'.$parent_id.'" and pl.lang_id="'.$lang_id.'" and pl.is_shown=1 order by p.ord desc, p.id';
		
		
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		if($rc==0) return '';
		
		unset($total);
		$total=Array();
		
		$pos=0; unset($positions); $positions=Array();
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$total[$i]=Array(
				'id'=>$f[0],
				'photo_small'=>$f[1],
				'name'=>$f[2],
				'small_txt'=>$f[3]
			);
			if($f[0]==$current_id) $pos=$i; //позиция текущего фото в массиве
		}
		//массив построили
		
		if($pos!=0) {
			$has_prev=true;
			$prev=$total[$pos-1];
		}
		if(($pos+1)!=$rc) {
			$has_next=true;
			$next=$total[$pos+1];
		}
		
		//высчитываем, га сколько надо сместиться влево и вправо
		$smes_right=0;
		for($j=$half_max; $j>0; $j--){
			if(!isset($total[ ($pos-$j) ])){
				$smes_right++;
			}
		}
		
		$smes_left=0;
		for($j=1; $j<=$half_max; $j++){
			if(!isset($total[ ($pos+$j) ])){
				$smes_left++;
			}
		}
		
		
		//делаем смещение на -$half_max
		for($j=($half_max+$smes_left); $j>0; $j--){
			if(isset($total[ ($pos-$j) ])){
				$positions[]=$total[ ($pos-$j) ];
				//echo ' -- ';
			}
			
		}
		
		$positions[]=$total[$pos];
		
		//делаем смещение на $half_max
		for($j=1; $j<=($half_max+$smes_right); $j++){
			if(isset($total[ ($pos+$j) ])){
				$positions[]=$total[ ($pos+$j) ];
				//echo ' ++ ';
			}
		}
		
		
		//вывод
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		//выведем стрелку пред
		$smarty->assign('has_prev',$has_prev);
		if($has_prev){
			/*if(HAS_URLS) $path=$url_path.'photos'.$prev['id'];
			else $path=$url_path.'photo.php?id='.$prev['id'];
			*/
			$path=$pi->ConstructPath($prev['id'],$lang_id);
			$smarty->assign('prev_item_url',$path);
		}else{
		}
		
		//выведем стрелку next
		$smarty->assign('has_next',$has_next);
		if($has_next){
			/*if(HAS_URLS) $path=$url_path.'photos'.$next['id'];
			else $path=$url_path.'photo.php?id='.$next['id'];
			*/
			$path=$pi->ConstructPath($next['id'],$lang_id);
			
			$smarty->assign('next_item_url',$path);
		}else{}
		
		$alls=Array();
		foreach($positions as $k=>$v){
			/*if(HAS_URLS) $path=$url_path.'photos'.$v['id'];
			else $path=$url_path.'photo.php?id='.$v['id'];
			*/
			$path=$pi->ConstructPath($v['id'],$lang_id);
			
			if($v['id']==$current_id) $is_active=true;
			else $is_active=false;
			
			$alls[]=Array(
				'is_active'=>$is_active,
				'item_url'=>$path,
				'item_src'=>stripslashes($v['photo_small']),
				'item_name'=>stripslashes($v['name']),
				'item_txt'=>stripslashes($v['small_txt'])
			);
		}
		
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch('photogal/subline.html');
		return $txt;
	}
}
?>