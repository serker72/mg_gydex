<?
require_once('abstractlanggroup.php');
require_once('photosgroup.php');
//require_once('questsgroup.php');
require_once('pricegroup.php');
require_once('papersgroup.php');
require_once('linksgroup.php');
require_once('newsgroup.php');
require_once('commentgroup.php');


require_once('smarty/SmartyAdm.class.php');




// абстрактная группа итемов c языковыми таблицами
class MmenuList extends AbstractLangGroup{
	
	//собственные шаблоны итема меню
	protected $flags_check_template;
	protected $flags_radio_template;	
	protected $name_multilang;
	protected $name_multilang_blocked;
	
	protected $non_tabs=Array(); //нетабличные разделы сайта
	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu';
		$this->lang_tablename='menu_lang';
		$this->pagename='razds.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='mid';
		$this->lang_id_name='lang_id';	
		
		
		$this->all_menu_template='tpl/hmenutable.html';
		$this->menuitem_template='tpl/hmenuitem.html';
		
		$this->flags_check_template='tpl/subitem/subitem_check.html';
		$this->flags_radio_template='subitem_radio.html';
		$this->name_multilang='tpl/subitem/subitem_name.html';
		$this->name_multilang_blocked='tpl/subitem/subitem_name_blocked.html';
		
		
		
	}
	
	

	
	
	
	
	
	
	
	//итемы в тегах option в зависимости от языка и текущего раздела
	public function GetItemsOptByParentIdLangId($current_parent_id=0,$current_id=0,$lang_id=LANG_CODE,$fieldname='name',$has_begin=true){
		$txt='';
		
		if($has_begin){
			if($current_parent_id==0) $txt.="<option value=\"0\" SELECTED>-начало-</option>";
			else $txt.="<option value=\"0\">-начало-</option>";
		}
		
		$params=Array();
		
		$params['l.lang_id']=1;
		$nparams=Array();
		$nparams['t.id']=$current_id;
		
		$orderbyparams=Array();
		$orderbyparams['t.parent_id']='t.parent_id';
		
		$query=$this->GenerateSQL($params,$nparams,$orderbyparams);
		$set=new mysqlSet($query);
		
		$tc=$set->GetResultNumRows();
		if($tc>0){
			$rs=$set->GetResult();
			for($i=0;$i<$tc;$i++){
				$f=mysqli_fetch_array($rs);
				
				$txt.="<option value=\"$f[id]\" ";
				if($current_parent_id==$f['id']) $txt.='SELECTED';
				$txt.=">";
				
				$mi=new MmenuItem();
				$pth=$mi->RetrievePath($f['id'], $flaglost, $vloj, $lang_id);
				
				$value='-начало- ';
				if($flaglost) $value.=' отсутствует связь - ';
				
				foreach($pth as $k=>$v){
					foreach($v as $kk=>$vv){
						$value.=' &gt; '.htmlspecialchars(stripslashes($vv['name']));
					}
				}
				$txt.=$value."</option>\n";
			}
		}
		
		return $txt;
	}
	
	
	
	
	
	//установка имен файлов шаблонов для меню  1го уровня
	public function SetTemplates1($all,$item){
		$this->all_menu_template=$all;
		$this->menuitem_template=$item;
		return true;
	}
	
	
	//получение списка имен на разных языках
	
	
	
	
	//!!!!!!!!!!!!!!! клиентский вывод
	
	
	
	
	//клиентское главное меню
	public function GetItemsCli($template1,$lang_id=LANG_CODE){
		//список позиций
		$txt='';
		
		
		return $txt;
	}
	
	//отрисовка нетабличных эл-тов дерева
	protected function DrawNonTabs($before_all,$template_all,$template_one,$is_new_pages=false){
		$txt='';
		$cells='';
		$tpl=new parse_class();
		$tpl->get_tpl($template_all);
		
		foreach($this->non_tabs as $k=>$v){
			if($v['before_all']==$before_all){
				$tpl2=new parse_class();
				$tpl2->get_tpl($template_one);
				$tpl2->set_tpl('{item_url}', $v['url']);
				$tpl2->set_tpl('{name}', $v['name']);
				$tpl2->set_tpl('{image_src}','img/01.gif');
				$tpl2->set_tpl('{subitems}','');
				if($is_new_pages) $tpl2->set_tpl('{newzone}', 'target="_blank"');
				else  $tpl2->set_tpl('{newzone}', '');
				
				$tpl2->tpl_parse();
				$cells.=$tpl2->template;
			}
		}
		
		$tpl->set_tpl('{items}', $cells);
		$tpl->tpl_parse();
		$txt=$tpl->template;
		
		return $txt;
	}
	
	
	//меню клиентское 
	//выдает массивы для смарти, шаблоны - пустые
	public function GetItemsMainCli($template1, $template2, $template3, $parent_id, $current_id=0, $lang_id=LANG_CODE, $as_thumbs=true,$add_params=NULL, $has_nontabs=true, &$ids, $paramsord=array()){
		$txt='';
		
		
		
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=$parent_id;
		$params['is_shown']='1';
		//$paramsord=Array();
		$paramsord[]=' ord desc ';
		$paramsord[]=' id asc ';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		//echo $query.'<br>';
		
		$alls=Array();
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		//УСТАНОВКА имен нетабличных разделов
		
		if($has_nontabs&&($parent_id==0)) $alls[]=Array(
			'item_url'=>'/',
			'is_new'=>false,
			'image_src'=>'img/no.gif',
			'name'=>$rf->GetValue('hmenu.php','main_caption',$lang_id),
			'is_current'=>$current_id==0
		);
		
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['parent_id']==0) $ids=array();
			
			$ids[]=$f['id'];
			
			if($f['is_new_window']=='1') $is_new=true; else $is_new=false;
			
			$mi=new MmenuItem();
			
			$path='';
			if(HAS_URLS){
				$tm=new MmenuItem();
				$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
				//echo "the path: $path<p>";
			}else{
				$path='razds.php?id='.$f['id'];
			}
			
				
		
			
			
			//$path='/'.$path;
			$subs=array();
			$subs=$this->GetItemsMainCli($template1, $template2, $template3, $f['id'],  $current_id, $lang_id, $as_thumbs,NULL, $has_nontabs, $ids);
			
		
			//$is_current=($current_id==$f['id'])^
			
			if ($current_id==$f['id']){
				$is_current=true;	
			}elseif(in_array($current_id, $ids)&&($f['parent_id']==0)){
				$is_current=true;	
			}else $is_current=false;
			
			
			$alls[]=Array(
				'is_new'=>$is_new,
				'id'=>$f['id'],
				'image'=>stripslashes($f['photo_small']),
				'image_active'=>stripslashes($f['photo_active']),
				'item_url'=>$path,
				'name'=>stripslashes($f['name']),
				'is_current'=>  $is_current, //($current_id==$f['id'])/*||($current_id==$f['parent_id'])*/,
				'do_open'=>in_array($current_id, $ids),
				'subs'=>$subs
			);
			
			
		}
		/*$alls[]=Array(
			'item_url'=>'/map.php',
			'is_new'=>false,
			'image_src'=>'img/no.gif',
			'name'=>$rf->GetValue('hmenu.php','tree_caption',$lang_id),
		);*/
		
		
		//var_dump($alls);
		return $alls;
	}
	 
	
	
	//меню клиентское нижнее c подразделами
	public function GetItemsFootCli($template1, $template2, $template3, $template4, $template5, $template6, $parent_id, $current_id=0, $lang_id=LANG_CODE, $as_thumbs=true,$add_params=NULL){
		$txt='';
		
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=$parent_id;
		$params['is_shown']='1';
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		//echo $query;
				
		$rc=$items->GetResultNumRows();
				
		$smarty=new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		//УСТАНОВКА имен нетабличных разделов
		$alls[]=Array(
			'subitems'=>Array(),
			'item_url'=>'/',
			'is_new'=>false,
			'image_src'=>'img/no.gif',
			'name'=> $rf->GetValue('hmenu.php','main_caption',$lang_id)
		);
			
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['is_new_window']=='1') $is_new=true; else $is_new=false;
			$mi=new MmenuItem();
			
			$path='';
			if(HAS_URLS){
				$tm=new MmenuItem();
				$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
				//echo "the path: $path<p>";
			}else{
				$path='razds.php?id='.$f['id'];
			}
						
			$params1=Array();
			$params1['l.lang_id']=$lang_id;
			$params1['t.parent_id']=$f['id'];
			$params1['l.is_shown']='1';
			$paramsord1=Array();
			$paramsord1[]=' ord desc ';
			
			$query1=$this->GenerateSQL($params1,NULL,$paramsord1,$sql_count1);
			$subss=new mysqlSet($query1);
			$srs=$subss->GetResult();
			$src=$subss->GetResultNumRows();
			
			$subs=Array();
			for($j=0;$j<$src;$j++){
				$g=mysqli_fetch_array($srs);
				
				if($g['is_new_window']=='1') $sis_new=true; else $sis_new=false;
				$mi=new MmenuItem();
				
				$spath='';
				if(HAS_URLS){
					$tm=new MmenuItem();
					$spath=$tm->ConstructPath($g['id'],$lang_id,1,'/');
				}else{
					$spath='razds.php?id='.$g['id'];
				}
								
				$subs[]=Array(
					
					'item_url'=>$spath,
					'is_new'=>$sis_new,
					'image_src'=>stripslashes($g['photo_small']),
					'name'=> stripslashes($g['name'])
				);
			}
			
			$alls[]=Array(
				'subitems'=>$subs,
				'item_url'=>$path,
				'is_new'=>$is_new,
				'image_src'=>stripslashes($f['photo_small']),
				'name'=> stripslashes($f['name'])
			);
		}
		//рисуем разделы после
		
		$alls[]=Array(
			'subitems'=>Array(),
			'item_url'=>'/map.php',
			'is_new'=>false,
			'image_src'=>'img/no.gif',
			'name'=> $rf->GetValue('hmenu.php','tree_caption',$lang_id)
		);
		
		
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($template1);
		
		return $txt;
	}
	
	
//**************************************МНОГОУРОВНЕВОЕ МЕНЮ*************************************************************
	
	//многоуровневое меню (от тек. раздела до корня)
	public function GetClientHierMenu($template1, $template2, $template3, $parent_id, $current_id=0, $lang_id=LANG_CODE, $as_thumbs=true,$add_params=NULL){
		$txt='';
		$vetv=$this->RetrieveRazds($current_id, $flaglost, $vloj, $lang_id, 1);
		$depth=1;
		if(isset($vetv[$depth])){
			$curr=$vetv[$depth];
			$txt.=$this->DrawLevelCli($vetv, $curr['parent_id'], $curr['id'], $lang_id, $as_thumbs,$depth,$template1, $template2, $template3, $add_params);
		}else $txt.=$this->DrawLevelCli($vetv, 0, 0, $lang_id, $as_thumbs,$depth,$template1, $template2, $template3, $add_params);
		
		return $txt;
	}
	
	//рекурсивная функция отображения 1 уровня вложенности
	protected function DrawLevelCli($vetv, $parent_id, $current_id, $lang_id, $as_thums, $depth, $template1, $template2, $template3, $add_params=NULL){
		
		$txt='';
		if((NUM_LEVELS!='-')&&($depth>NUM_LEVELS)) return $alls;
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=$parent_id;
		$params['is_shown']='1';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		$paramsord=Array();
		$paramsord[]=' ord desc, id asc ';
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		echo $query;
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			
			$level='';
			$f=mysqli_fetch_array($rs);
			
			if($f['parent_id']==0) $is_sub=false; 
			else $is_sub=true;
			
			if($f['id']==$current_id){
				if(isset($vetv[$depth+1])){
					//$txt.=" <em>open!</em> ";
					$new_curr=$vetv[$depth+1];
					$level.=$this->DrawLevelCli($vetv, $f['id'], $new_curr['id'], $lang_id, $as_thums, $depth+1,$template1, $template2, $template3,NULL);
				}else{
					//$txt.=" <em>this one!</em> ";
					if((NUM_LEVELS!='-')&&(($depth+1)>=NUM_LEVELS)){}
					else {
						$level.=$this->DrawOneCliServant($f['id'],$lang_id, $as_thums,$template1, $template2,  $template3);			
						
					}
				}
			}else {}
			if(HAS_URLS){
				$tm=new MmenuItem();
				$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
			}else{
				$path='razds.php?id='.$f['id'];
			}
			
			if($f['is_new_window']) $is_new=true; else $is_new=false;
			
			$alls[]=Array(
				'subitems'=>$level,
				'is_new'=>$is_new,
				'is_sub'=>$is_sub,
				'item_url'=>$path,
				'image_src'=>'img/no.gif',
				'name'=>stripslashes($f['name'])
			);
		}
		$sm->assign('items',$alls);
		$txt=$sm->fetch($template1);
		return $txt;
	}
	
	
	//служебная функция отрисовки подразделов раздела, вызывается для последнего текущего раздела в цепочке
	//в предыдущей функции
	protected function DrawOneCliServant($id,$lang_id, $as_thumbs,$template1, $template2,$template3){
		$txt='';
		$alls=Array();
		
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=$id;
		$params['is_shown']='1';
		$paramsord=Array();
		$paramsord[]=' ord desc, id asc ';
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		
		$sm=new SmartyAdm;
		$sm->debug=DEBUG_INFO;
		for($i=0;$i<$rc;$i++){
			$level='';
			
			$f=mysqli_fetch_array($rs);
			
			if($f['parent_id']==0) $is_sub=false; else $is_sub=true;
			
			if(HAS_URLS){
				$tm=new MmenuItem();
				$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
			}else{
				$path='razds.php?id='.$f['id'];
			}
			
			if($f['is_new_window']) $is_new=true; else $is_new=false;
			
			$alls[]=Array(
				'subitems'=>$level,
				'is_new'=>$is_new,
				'is_sub'=>$is_sub,
				'item_url'=>$path,
				'image_src'=>'img/no.gif',
				'name'=>stripslashes($f['name'])
			);
		}
		$sm->assign('items',$alls);
		$txt=$sm->fetch($template1);
		return $txt;
	}
	
	public function RetrieveRazds($id, &$flaglost, &$vloj, $lang_code=1, $is_shown=0){
	//а также глубину вложенности
		unset($path);
		$path=Array(); 
		//если тру, то есть потеря пути!
		$flaglost=false;
		$vloj=0;
		
		$mi=new MmenuItem();
		$x=$mi->GetItemById($id, $lang_code, $is_shown);
		
		if($x!=false){
			$path[]=$x;
		}
		if($x['parent_id']!=0){
			$parent_id=$x['parent_id'];
			$count=999;
			while(($count!=0)&&($parent_id!=0)){
				$x=$mi->GetItemById($parent_id, $lang_code, $is_shown);
				if($x!=false){
					$count=999;
					$parent_id=$x['parent_id'];
										//echo "$f[id]<br>";
					$path[]=$x;
					$vloj++;
					
				}else{
					$count=0;
					$flaglost=true;
					//echo 'qqqqqqqqqqqqqqqqqqqqqqq';
				}
			}
		}
		$path=array_reverse($path);
		return $path;
	}
//************************КОНЕЦ ****МНОГОУРОВНЕВОГО МЕНЮ*************************************************************	


//******************************* МЕНЮ НА СТРАНИЦЕ ПОДРАЗДЕЛА **********************************************************
//показ подитемов клиентский
	public function GetSubsByIdCli($id,$template1, $template2, $template3, $lang_id=LANG_CODE, $bullet_img='img/no.gif', $add_params=NULL){
		$txt='';
		
		$m=new MmenuItem();
		$mm=$m->GetItemById($id,$lang_id,1);
		if($mm==false) return $txt;
		
		
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=$id;
		$params['is_shown']='1';
		$paramsord=Array();
		$paramsord[]=' ord desc, id asc ';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		//echo $query;
		$rc=$items->GetResultNumRows();
		

		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			
			
			
			if($mm['is_pics_list']==1) {
				$img=stripslashes($f['photo_small']);
			}else {
				$img=$bullet_img;
			}
			
			if($f['is_new_window']=='1') $is_new=true;
			else $is_new=false;
			
			$mi=new MmenuItem();
			
			$path='';
			if(HAS_URLS){
				$tm=new MmenuItem();
				$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
				//echo "the path: $path<p>";
			}else{
				$path='razds.php?id='.$f['id'];
			}
			
			
			//echo 'qqq';
			$alls[]=Array(
				'is_new'=>$is_new,
				'item_url'=>$path,
				'name'=>stripslashes($f['name']),
				'image_src'=>$img,
				'altname'=>stripslashes($f['name'])
				
			);
		}
		
		
		$smarty->assign('items',$alls);
		if($rc>0) $txt=$smarty->fetch($template1);
		else $txt='';
		return $txt;
	}
//***************************КОНЕЦ МЕНЮ НА СТРАНИЦЕ ПОДРАЗДЕЛА **********************************************************









//***********************************************************работа на смарти 
//адм горизонт меню
	public function GetItemsAdmSm($template,$menu_kind=1){
		//список позиций
		$txt='';
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$params=Array();
		$params['is_menu_'.$menu_kind]=1;
		 
		$params['lang_id']=1;
		$params['parent_id']=0;
		
		$ord_params=array('ord desc');
		
		$query=$this->GenerateSQL($params, NULL, $ord_params);
		 
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$alls[]=Array('id'=>$f['id'], 'name'=>stripslashes($f['name']));
		}
		
		$smarty->assign('items',$alls);
		
		//новые комментарии к новостям
		$_cg=new CommentGroup;
		$cc=$_cg->CalcNew('news');
		$smarty->assign('comments_no', $cc);
		if($cc>0){
			$new=$_cg->FindNew('news');
			$smarty->assign('new_id', $new['item_id']);	
			$smarty->assign('comment_id', $new['id']);	
		}
		
		
		$txt=$smarty->fetch($template);
		
		return $txt;
	}

		//список подразделов раздела smarty
	public function GetItemsById($parent_id=0, $mode=0,$from=0,$to_page=10){
		//список позиций
		
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		$txt='';
		
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		
		$smarty->assign('filename','ed_razd.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$parent_id);
		$smarty->assign('listpagename',$this->pagename);
		
			
		$query='select * from allmenu as t where t.parent_id="'.$parent_id.'" order by t.ord desc, t.id';
		$sql_count='select count(*) from allmenu as t where t.parent_id="'.$parent_id.'"';
		$items=new mysqlSet($query,$to_page,$from,$sql_count);
		
		
		
		$rs=$items->GetResult();
		
		$rc=$items->GetResultNumRows();
		$totalcount=$items->GetResultNumRowsUnf();
		$strs='';
		
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&id='.$parent_id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			
			//URL
			if(HAS_URLS){
				$mi=new MmenuItem();
				$path=$mi->ConstructPath($f['id'], LANG_CODE,0,'/');
				$url=$path;
			}else{
				$url='-';
			}
			
			$names_=Array();
			//строим имя раздела
			foreach($this->langs as $lk=>$g){
				
				//$names=new parse_class();
				if(NUM_LEVELS!='-'){
					$tr=new MmenuItem();
					$tr->RetrievePath($f['id'], $flaglost, $vloj, LANG_CODE, 0);
					//echo $vloj+1;
					if(NUM_LEVELS<=($vloj+1)){
						$is_vloj=0; //$names->get_tpl($this->name_multilang_blocked);
					}else $is_vloj=1; //$names->get_tpl($this->name_multilang);
				}else{
					$is_vloj=1; //$names->get_tpl($this->name_multilang);
				}
				
				$mi=new MmenuItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names_[]=Array('is_vloj'=>$is_vloj, 'is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'lang_shown'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']), 'sub_count'=>$this->CountChildsById($mmi['id'],'parent_id') );
			}
			
			//строим параметры раздела
			$params_=Array();
			
			
			
			
			$alls[]=Array('id'=>$f['id'],'url'=>$url, 'nameitems'=>$names_, 'paramitems'=>$params_, 'itemdescr'=>$this->GetRazdParams($f));
		}
		
		
		
		
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		
		$txt=$smarty->fetch($this->all_menu_template);
		return $txt;
	}
	
	
	//получение списка параметров для раздела с радио и чекбоксами
	protected function GetRazdParams($f){
		$fla_text='';
		
		$flagst= new SmartyAdm;
		$flagst->debugging = DEBUG_INFO;
		
		$flagst->assign('itemno',$f['id']);
		$flagst->assign('pretext','');
		$flagst->assign('radvalue','0');
		if(($f['is_price']!=1)&&($f['is_news']!=1)&&($f['is_links']!=1)) $flagst->assign('checkarea',' checked');
		else $flagst->assign('checkarea',' ');
		$flagst->assign('caption','Обычный раздел');
		$fla_text.=$flagst->fetch($this->flags_radio_template);
		
		if(HAS_PRICE) {
			$pg=new PriceGroup();
			$fla_text.=$this->interface->GetRadio($f,'','is_price',1, '<img src="../img/catalog-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewpriceitems.php?id='.$f['id'].'">Каталог товаров ('.$pg->CalcItemsById($f['id']).')</a>');
			$fla_text.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript: winop('eddicts.php?kind=1&id=$f[id]&mode=1','950','600','dicts');\"><img src=\"../img/dictionary-16.gif\" alt=\"\" width=\"16\" height=\"16\" hspace=\"2\" vspace=\"0\" border=\"0\">Словари свойств раздела</a> <br>";
			$fla_text.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript: winop('pricestogood.php?value_id=$f[id]&mode=1','800','600','prices');\"><img src=\"../img/price-16.gif\" alt=\"\" width=\"16\" height=\"16\" hspace=\"2\" vspace=\"0\" border=\"0\">Цены, применяемые в разделе</a> <br>";
		}
		
		if(HAS_PRICE&&HAS_BASKET) $fla_text.=$this->interface->GetCheckbox($f,'&nbsp;&nbsp;','is_basket', '<img src="../img/basket.gif" alt="" width="16" height="14" hspace="2" vspace="0" border="0">Можно заказывать товар');
		if(HAS_NEWS) {
			$pg=new NewsGroup();
			$fla_text.=$this->interface->GetRadio($f,'','is_news',2, '<img src="../img/news-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewnews.php?id='.$f['id'].'">новости ('.$pg->CalcItemsById($f['id']).')</a>');
		}
		if(HAS_LINKS) {
			$pg=new LinksGroup();
			$fla_text.=$this->interface->GetRadio($f,'','is_links',3, '<img src="../img/links-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewlinks.php?id='.$f['id'].'">каталог ссылок ('.$pg->CalcItemsById($f['id']).')</a>');
		}
		if(HAS_PAPERS) {
			$pg=new PapersGroup();
			$fla_text.=$this->interface->GetCheckbox($f,'','is_papers', '<img src="../img/papers-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewpapers.php?id='.$f['id'].'">статьи ('.$pg->CalcItemsById($f['id']).')</a>');
		}
		if(HAS_GALLERY) {
			$pg=new PhotosGroup();
			$fla_text.=$this->interface->GetCheckbox($f,'','is_gallery', '<img src="../img/photos-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewphotos.php?id='.$f['id'].'">фотогалерею ('.$pg->CalcItemsById($f['id']).')</a>');
		}
		if(HAS_FEEDBACK_FORMS){
			$fla_text.=$this->interface->GetCheckbox($f,'','is_feedback_forms', '<img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит формы обратной связи ');
			
			$fla_text.=$this->interface->GetCheckbox($f,'','is_otzyv', '<img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewotzyv.php" target="_blank">отзывы</a> ');
			
			
			$fla_text.=$this->interface->GetCheckbox($f,'','is_callback', '<img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит форму обратного звонка ');
			
			$fla_text.=$this->interface->GetCheckbox($f,'','is_faq', '<img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewfaq.php" target="_blank">ЧаВо</a>');
		}
		
		return $fla_text; 
	}
	
	
	//получение произвольного меню
	public function GetArr($params, $lang_id, $paramsord=array(), $is_shown=1, $current_mid=0){
		$arr=array();
		
	//	$params=Array();
		$params['lang_id']=$lang_id;
		//$params['parent_id']=0;
		$params['is_shown']='1';
	
		  
		//$paramsord=Array();
		//$paramsord[]=' ord desc ';
		$paramsord[]=' id asc ';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
	
		//echo $query;
		
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		
		$rc=$items->GetResultNumRows();
		$tm=new MmenuItem();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			 
			
			$f['is_current']=($f['id']==$current_mid);
			$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
			$f['url']=$path;
			$f['subs']=$subs;
			$arr[]=$f;	
		}
		
		return $arr;
	}
	
	
	/*public function GetActiveArr($lang_id, $active=1, $current_mid=0){
		$arr=array();
		
		$params=Array();
		$params['lang_id']=$lang_id;
		$params['parent_id']=0;
		$params['is_shown']='1';
	
		$params['is_right']='0';
		$params['is_hor']='0';
		$params['is_hor2']='0';
		$params['is_v_active']=$active;
		$paramsord=Array();
		$paramsord[]=' ord desc ';
		$paramsord[]=' id asc ';
		if($add_params!==NULL){
			foreach($add_params as $k=>$v){
				$params[$k]=$v;
			}
		}
		
		
		$query=$this->GenerateSQL($params,NULL,$paramsord,$sql_count);
	
		
		$items=new mysqlSet($query);
		$rs=$items->GetResult();
		
		$rc=$items->GetResultNumRows();
		$tm=new MmenuItem();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$subs=array();
			
			$sql='select * from allmenu as a inner join menu_lang as l on a.id=l.mid and l.is_shown=1 and a.parent_id='.$f['id'].' and l.lang_id='.$lang_id.' order by a.ord desc, a.id asc';
			$set1=new mysqlSet($sql);
			$rs1=$set1->GetResult();
			$rc1=$set1->getResultNumRows();
			for($j=0; $j<$rc1; $j++){
				$g=mysqli_fetch_array($rs1);
				$g['url']=$tm->ConstructPath($g['id'],$lang_id,1,'/');
				
				$g['is_current']=$g['id']==$current_mid;
				$ssubs=array();
				
				$sql2='select * from allmenu as a inner join menu_lang as l on a.id=l.mid and l.is_shown=1 and a.parent_id='.$g['id'].' and l.lang_id='.$lang_id.' order by a.ord desc, a.id asc';
				$set2=new mysqlSet($sql2);
				$rs2=$set2->GetResult();
				$rc2=$set2->getResultNumRows();
				for($k=0; $k<$rc2; $k++){
					$h=mysqli_fetch_array($rs2);
					$h['url']=$tm->ConstructPath($h['id'],$lang_id,1,'/');
					
					$h['is_current']=$h['id']==$current_mid;
					$ssubs[]=$h;	
				}
				
				$g['subs']=$ssubs;
				
				$subs[]=$g;	
			}
			
			$f['is_current']=$f['id']==$current_mid;
			$path=$tm->ConstructPath($f['id'],$lang_id,1,'/');
			$f['url']=$path;
			$f['subs']=$subs;
			$arr[]=$f;	
		}
		
		return $arr;
	}*/
	
}
?>