<?
require_once('abstractlanggroup.php');
require_once('alldictitem.php');
require_once('attkindsgroup.php');
require_once('attkinditem.php');
require_once('resoursefile.php');
require_once('smarty/SmartyAdm.class.php');

// список прикрепленных словарей
class AttDictsGroup extends AbstractLangGroup {
	
	protected $name_multilang;
	protected $name_vis_check;
	
	//установка всех имен
	protected function init(){
		$this->tablename='dict';
		$this->pagename='viewdicts.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		$this->all_menu_template='tpl/itemstable.html';
		$this->menuitem_template='tpl/itemsrow.html';
		$this->menuitem_template_blocked='tpl/itemsrow_blocked.html';
		$this->razbivka_template='tpl/alldicts/all_to_page.html';
		
		$this->name_multilang='tpl/alldicts/subitem_name.html';
		
		$this->name_vis_check='tpl/alldicts/subitem_lang_vis_check.html';
	}
	
	//список итемов для компактного списка в окне
	public function GetItemsForWindow($id,$kind,$from=0,$to_page=10){
		//список позиций
		$txt='';
		$lg=new LangGroup(); $this->langs=$lg->GetLangsList();

		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('filename','ed_dict_compact.php');
		$smarty->assign('fromno',$from);
		$smarty->assign('topage',$to_page);
		$smarty->assign('itemno',$id);
		$smarty->assign('kindno',$kind);
		$smarty->assign('listpagename','alldicts.php');
		
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
		
		
		$navig = new PageNavigator('alldicts.php',$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&kind='.$kind.'&id='.$id);
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//параметры имени
			$names=Array();
			foreach($this->langs as $lk=>$g){
				$mi=new AllDictItem();
				$mmi=$mi->GetItemById($f['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				
			}
			
			//вид словаря
			$params='';
			$mi=new AllDictItem();
			$params=$mi->GetBehaviorsOpt($f['kind_id']);
			//echo "<strong>$f[kind_id]</strong> ";
			
			//прикрепления
			$a=new AllDictItem();
			$att=$a->CheckBoundsByIdKind($f['id'],$id,$kind);
			$atts='';
			if(isset($att['has_inh'])){
				$attach_has_inh=true;
				$atts='';
			}else{
				$attach_has_inh=false;
				//список видов прикреплений
				$ak=new AttKindsGroup();
				
				$selected_id=$att['flag'];
				$tagname=$f['id'].'_is_attached';
				$add_code="onchange=\"m=document.getElementById('".$f['id']."_do_process'); m.checked=true;\"";
				
				$atts=$ak->GetItemsRadio($kind,$selected_id, $tagname, $add_code);
				
			}
			
			$alls[]=Array('itemno'=>$f['id'],'nameitems'=>$names, 'itemdescr'=>$params, 'attach_has_inh'=>$attach_has_inh, 'attaches'=>$atts);
			
		}
		$smarty->assign('pages',$pages);
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	//прикрепленные к разделу итемы
	//или же наследуемые разделом итемы
	public function GetAttachedToRazd($id,$kind){
		//список позиций
		$txt=''; $lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('filename','viewattdicts.php');
		$smarty->assign('parentid',$id);
		$smarty->assign('kindno',$kind);
				$kk=abs((int)$_SESSION['mode']);
		$smarty->assign('kk',$kk);
		$smarty->assign('listpagename','alldicts.php');
		
		if($kind==1) {
			$query='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl where dl.dict_id=d.id and (dl.attach_code="1" or dl.attach_code=2) and dl.key_value="'.$id.'" order by dl.ord desc';
			
			$query_count='select count(distinct d.id) from dict as d, dict_attach_d as dl where dl.dict_id=d.id and (dl.attach_code="1" or dl.attach_code=2) and dl.key_value="'.$id.'"';
		}else if($kind==2){
			//проверим привязки к родительским разделам по коду 2
			
			
			$mi=new MmenuItem();
			$mmi=$mi->GetItemById($id);
			if($mmi['parent_id']==0) return $txt;
			
			$arr=$mi->RetrievePath($id, $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					if($kk!=$id){
						$q.=' '.$kk;
						if($cter<count($arr)-1) $q.=',';
						$cter++;
					}
				}
			}
			$query='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl where dl.dict_id=d.id and dl.attach_code=2 and dl.key_value in ('.$q.') order by dl.ord desc';
			$query_count='select count(distinct d.id) from dict as d, dict_attach_d as dl where dl.dict_id=d.id and dl.attach_code=2 and dl.key_value in ('.$q.')';
		
		}
		$items=new mysqlSet($query,NULL,NULL,$query_count);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//параметры имени
			$names=Array();
			
			//параметры имени
			$ad=new AllDictItem();
			$add=$ad->GetItemById($f[0]);
			
			foreach($this->langs as $lk=>$g){
				$mi=new AllDictItem();
				$mmi=$mi->GetItemById($add['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				
			}
			
			//привязка
			$a=new AttKindItem();
			$aa=$a->GetItemById($f[3]);
			if($kind==2) $is_inherit=0; else $is_inherit=1;
			$alls[]=Array(
				'itemno'=>$f['id'],
				'dictno'=>$f[0],
				'valitems'=>$names,
				'attachsymb'=>stripslashes($aa['short_name']),
				'is_inherit'=>$is_inherit
			);
		}
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	//прикрепленные к товару итемы
	//или же наследуемые товаром итемы
	public function GetAttachedToGood($id,$kind){
		//список позиций
		$txt=''; $lg=new LangGroup(); $this->langs=$lg->GetLangsList();
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$smarty->assign('filename','viewattdicts.php');
		$smarty->assign('parentid',$id);
		$kk=abs((int)$_SESSION['mode']);
		$smarty->assign('kk',$kk);
		$smarty->assign('kindno',$kind);
		$smarty->assign('listpagename','alldicts.php');
		
		//echo $kind;
		if($kind==3) {
			$query='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl where dl.dict_id=d.id and dl.attach_code="3" and dl.key_value="'.$id.'" order by dl.ord desc';
			
			$query_count='select count(distinct d.id) from dict as d, dict_attach_d as dl where dl.dict_id=d.id and dl.attach_code="3" and dl.key_value="'.$id.'"';
		}else if($kind==2){
			//проверим привязки к родительским разделам по коду 2
			$mi=new MmenuItem();
			$gd=new PriceItem();
			$good=$gd->GetItemById($id);			
			$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					$q.=' '.$kk;
					if($cter<count($arr)) $q.=',';
					$cter++;
				}
			}
			$query='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl where dl.dict_id=d.id and ((dl.attach_code=2 and dl.key_value in ('.$q.')) or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) order by dl.ord desc';
			$query_count='select count(distinct d.id) from dict as d, dict_attach_d as dl where dl.dict_id=d.id and ((dl.attach_code=2 and dl.key_value in ('.$q.')) or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'"))';
		
		}
		$items=new mysqlSet($query,NULL,NULL,$query_count);
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		
		$totalcount=$items->getResultNumRowsUnf();
		$alls=Array();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			//параметры имени
			$names=Array();
			
			//параметры имени
			$ad=new AllDictItem();
			$add=$ad->GetItemById($f[0]);
			
			foreach($this->langs as $lk=>$g){
				$mi=new AllDictItem();
				$mmi=$mi->GetItemById($add['id'],$g['id']);
				
				if($mmi!=false){
					$is_exist=true;
				}else {
					$is_exist=false;
				}
				
				$names[]=Array('is_exist'=>$is_exist, 'lang_name'=>strip_tags($g['lang_name']), 'lang_flag'=>stripslashes($g['lang_flag']), 'lang_id'=>$g['id'], 'is_visible'=>$mmi['is_shown'], 'name'=>stripslashes($mmi['name']) );
				
			}
			
			//привязка
			$a=new AttKindItem();
			$aa=$a->GetItemById($f[3]);
			if($kind==2) $is_inherit=0; else $is_inherit=1;
			$alls[]=Array(
				'itemno'=>$f['id'],
				'dictno'=>$f[0],
				'valitems'=>$names,
				'attachsymb'=>stripslashes($aa['short_name']),
				'is_inherit'=>$is_inherit
			);
		}
		
		$smarty->assign('items',$alls);
		$txt=$smarty->fetch($this->all_menu_template);
		
		return $txt;
	}
	
	
	
	//список итемов в тегах оптион, за исключением с заданными флагами
	public function GetItemsOptExceptToRazd($id,$kind){
		$txt='';
		if($kind!=3) {
			//словари для раздела
			
			$mi=new MmenuItem();
			$arr=$mi->RetrievePath($id, $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					if($kk!=$id){
						$q.=' '.$kk;
						if($cter<count($arr)-1) $q.=',';
						$cter++;
					}
				}
			}
			if($q!=''){
			
				
				$query='select distinct d.id, d.ord, dl.name from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where   ((attach_code="1" or attach_code=2) and key_value="'.$id.'")   or  (attach_code="2" and key_value in ('.$q.'))     ) order by ord desc';
				
				
				$query_count='select count(distinct d.id) from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where ((attach_code="1" or attach_code=2) and key_value="'.$id.'")   or  (attach_code="2" and key_value in ('.$q.')) )';
			}else{
				$query='select distinct d.id, d.ord, dl.name from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="1" or attach_code=2) and key_value="'.$id.'") order by ord desc';
				$query_count='select count(distinct d.id) from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="1" or attach_code=2) and key_value="'.$id.'")';
				
					
			}
		}else{
			//словари для товара
			
			$mi=new MmenuItem();
			$gd=new PriceItem();
			$good=$gd->GetItemById($id);			
			$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					$q.=' '.$kk;
					if($cter<count($arr)) $q.=',';
					$cter++;
				}
			}
			if($q!=''){
			
				$query='select distinct d.id, d.ord, dl.name from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="3" and key_value="'.$id.'") or (attach_code=2 and key_value in ('.$q.')) or (attach_code=1 and key_value="'.$good['mid'].'") ) order by ord desc';
				$query_count='select count(distinct d.id) from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="3" and key_value="'.$id.'") or (attach_code=2 and key_value in ('.$q.')) or (attach_code=1 and key_value="'.$good['mid'].'") )';
			
			}else{
				$query='select distinct d.id, d.ord, dl.name from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="3" and key_value="'.$id.'") or (attach_code=1 and key_value="'.$good['mid'].'")) order by ord desc';
				$query_count='select count(distinct d.id) from dict as d, dict_lang as dl where d.id=dl.dict_id and dl.lang_id='.LANG_CODE.' and d.id not in(select dict_id from dict_attach_d where (attach_code="3" and key_value="'.$id.'") or (attach_code=1 and key_value="'.$good['mid'].'"))';
				
				//die();
			}
		}
		
		$items=new mysqlSet($query,NULL,NULL,$query_count);
		
		$rs=$items->GetResult();
		$rc=$items->GetResultNumRows();
		$strs='';
		$totalcount=$items->getResultNumRowsUnf();
		
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$txt.="<option value=\"$f[0]\">".stripslashes($f[2])."</option>";
		}
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
	
	
	/*
	//получение списка имен на разных языках
	protected function GetNameParams($f,$from,$to_page){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			
			$names->set_tpl('{fromno}',$from);
						
			$names->set_tpl('{topage}',$to_page);
			$names->set_tpl('{langid}',$g['id']);
			$names->set_tpl('{langtext}','<img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0">');
			
			$mi=new AllDictItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', 'видим','','tpl/alldicts/subitem_lang_vis_check.html',$g['id'].'_'));		
			}else {
				$names->set_tpl('{itemname}','<em>не создано</em>');
				$names->set_tpl('{vistext}','');		
			}
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		return $txt;
	}
	
	//получение списка имен на разных языках для компактного окна
	protected function GetNameParamsForWin($f,$id,$kind){
		$txt='';
		
		
		foreach($this->langs as $lk=>$g){
			
			$names=new parse_class();
			$names->get_tpl($this->name_multilang);
			$names->set_tpl('{itemno}',$f['id']);
			$names->set_tpl('{parentno}',$id);
			$names->set_tpl('{langid}',$g['id']);
			
			$kk=abs((int)$_SESSION['mode']);
			$names->set_tpl('{kind}',$kk);
			$names->set_tpl('{parentid}',$id);
			
			$names->set_tpl('{langtext}','<a href="#" onclick="winop('."'ed_dict_compact.php?action=1&lang_id=$g[id]&id=$f[id]&doLang=1&kind=$kk&parent_id=$id'".',800,700,'."'dictt'".')" title="Нажмите для редактирования словаря"><img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0"></a>'.' <a href="#" onclick="winop('."'ed_dict_compact.php?action=1&lang_id=$g[id]&id=$f[id]&doLang=1&kind=$kk&parent_id=$id&nonvisual=1'".',800,700,'."'dictt'".')" title="Нажмите для редактирования словаря"><img src="/'.stripslashes($g['lang_flag']).'" alt="правка на языке '.strip_tags($g['lang_name']).'" title="правка на языке '.strip_tags($g['lang_name']).'" border="0"></a>');
			
			
			$mi=new AllDictItem();
			$mmi=$mi->GetItemById($f['id'],$g['id']);
			
			if($mmi!=false){
				$names->set_tpl('{itemname}',stripslashes($mmi['name']));
				$names->set_tpl('{vistext}',$this->interface->GetCheckbox($mmi,'','is_shown', 'видим','',$this->name_vis_check,$g['id'].'_'));		
			}else {
				$names->set_tpl('{itemname}','<em>не создано</em>');
				$names->set_tpl('{vistext}','');		
			}
			
			//echo $this->name_multilang;
			
			$names->tpl_parse();
			$txt.=$names->template;
			unset($names);
		}
		
		//echo " <pre>".htmlspecialchars($txt)."</pre> ";
		return $txt;
	}
	
	
	
	
	//получение списка описаний на разных языках
	protected function GetDescrParams($f,$from,$to_page){
		$txt='';
		
		$names=new parse_class();
		$names->get_tpl('tpl/alldicts/its_behavior.html');
		$names->set_tpl('{itemno}',$f['id']);
		$mi=new AllDictItem();
		$res=$mi->GetBehaviorsOpt($f['kind_id']);
		$names->set_tpl('{opts}',$res);
		$names->tpl_parse();
		$txt.=$names->template;
		
		return $txt;
	}
	
	*/
	
	
	
	
	
	
	//!!!!!!!!!!!!!!клиентский вывоД
//***************************** ФУНКЦИИ ВЫВОДА ВСЕХ НАБОРОВ СВОЙСТВ ТОВАРА *******************************************
	
	//вывод привязанных или унаслед товаром опций заказа
	//вывод привязанных или унаслед товаром опций заказа
	//!!!!!!!!!!!!!!!!!!!!! через Смарти!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function GetOrderOptsByGoodId($id, $lang_id=LANG_CODE, $template1='', $template2='',$chosen_array=NULL,$option_name_prefix='',$flag_to_change=''){
		$txt='';
		
		
		//проверим привязки к родительским разделам по коду 2
		$mi=new MmenuItem();
		$gd=new PriceItem();
		$good=$gd->GetItemById($id);			
		$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
		$cter=1; $q='';
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				$q.=' '.$kk;
				if($cter<count($arr)) $q.=',';
				$cter++;
			}
		}
		
		$query1='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl, dict_lang as dlang where dl.dict_id=d.id and dlang.lang_id="'.$lang_id.'" and dlang.dict_id=d.id and d.kind_id="2" and dlang.is_shown=1 and ((dl.attach_code=2 and dl.key_value in ('.$q.'))  or (dl.attach_code="3" and dl.key_value="'.$id.'") or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) order by dl.attach_code, dl.ord desc'; //order by dl.ord desc
		//$txt=$query1;
		
		$dset=new mysqlSet($query1);
		$drs=$dset->GetResult();
		$drc=$dset->GetResultNumRows();
		//echo $drc;
		//получили набор прикрепленных словарей свойств, дальше каждый перебираем по именам, и для каждого имени - значение по коду товара!
		for($k=0; $k<$drc; $k++){
			$df=mysqli_fetch_array($drs);
			//echo " $df[0] ";
			$query='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$df[0].'" and pl.is_shown=1 order by pn.ord desc';
			//echo " $query ";
			$nset=new mysqlSet($query);
			$nrs=$nset->GetResult();
			$nrc=$nset->GetResultNumRows();
			//echo '<h1>zq</h1>';
			//перебираем имена опций
			
			
			for($j=0; $j<$nrc; $j++){
				$nf=mysqli_fetch_array($nrs);
				
				
				$smarty = new SmartyAdm;
				$smarty->debugging = DEBUG_INFO;
				
				$smarty->assign('title',stripslashes($nf[1]));
				$smarty->assign('prename',$option_name_prefix);
				$smarty->assign('set_name','name_id_'.$nf[0]);
				if($flag_to_change!='') {
					//echo "<h1>$flag_to_change</h1>";
					$smarty->assign('number',$flag_to_change);
				}
				
				$row='';
				//перебираем все значения свойства
				$query2='select pv.id, pl.name from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
				//echo " $query2 ";
				$vset=new mysqlSet($query2);
				$vrs=$vset->GetResult();
				$vrc=$vset->GetResultNumRows();
				//глобальный флаг, указывает, есть ли совпадение хотя бы у одного из значений опций с переданным массивом выбранных опций
				//если есть - значит НЕ ставим активным пункт "-не выбрано-"
				//если ни одного совпадения - значит данная опция не выбрана, активен пункт  "-не выбрано-"
				$global_flag_has=false;
				
				
				$ids=Array(); $names=Array(); $smarty_select=0;
				for($i=0; $i<$vrc; $i++){
					$vf=mysqli_fetch_array($vrs);
					
					if($chosen_array!==NULL) {
						//флаг, выбрано ли текущее значение опции
						//если текущее выбранное из базы значение совпадает хотя бы с одним из массива - помечаем его как выбранное
						$flag_has=false;
						foreach($chosen_array as $ck=>$cv){
							if($cv['value_id']==$vf[0]) { $flag_has=true; $global_flag_has=true; break; }
						}
						if($flag_has) $smarty_select=$vf[0]; //$tpl2->set_tpl('{selectedzone}','selected');
						else {} //$tpl2->set_tpl('{selectedzone}','');
					}else  {} //$tpl2->set_tpl('{selectedzone}','');
					
					
					$ids[$i+1]=$vf[0];
					$names[$i+1]=stripslashes($vf[1]);
					
					if($i==($vrc-1)){
						//на последнем шаге
						//впишем  опцию "не выбрано" в начало списка опций
						
						$trf=new ResFile(ABSPATH.'cnf/resources.txt');
						$ids[0]=0;
						$names[0]=$trf->GetValue('good.php', 'notchoosed',$lang_id);
						
						if($chosen_array===NULL) /*$tpl2->set_tpl('{selectedzone}','selected');*/ $smarty_select=0;
						else if($global_flag_has) /*$tpl2->set_tpl('{selectedzone}','');*/ {}
						else /*$tpl2->set_tpl('{selectedzone}','selected');*/ $smarty_select=0;
						
						unset($trf);
						
					}
				}
				
				$smarty->assign('ids',$ids);
				
				$smarty->assign('names',$names);
				$smarty->assign('itsel',$smarty_select);
				
				if(!isset($vrc)||($vrc==0)) $txt.='';
				else $txt.=$smarty->fetch($template1);
				unset($smarty);
			}
			
		}
		//return "<pre>".htmlspecialchars($txt)."</pre>";
		return $txt;
	}
	
	
	
	
	
	
	//вывод привязанных или унаслед товаром доп фото
	public function GetAddPhotosByGoodId($id, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt='';
		
		
		$alls=Array();
		//проверим привязки к родительским разделам по коду 2
		$mi=new MmenuItem();
		$gd=new PriceItem();
		$good=$gd->GetItemById($id);			
		$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
		$cter=1; $q='';
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				$q.=' '.$kk;
				if($cter<count($arr)) $q.=',';
				$cter++;
			}
		}
		
		$query1='select distinct d.id, dl.id, dl.ord, dl.attach_code from dict as d, dict_attach_d as dl, dict_lang as dlang where dl.dict_id=d.id and dlang.lang_id="'.$lang_id.'" and dlang.dict_id=d.id and d.kind_id="3" and dlang.is_shown=1 and ((dl.attach_code=2 and dl.key_value in ('.$q.'))  or (dl.attach_code="3" and dl.key_value="'.$id.'") or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) order by dl.attach_code, dl.ord desc'; //order by dl.ord desc
		//$txt=$query1;
		
		$dset=new mysqlSet($query1);
		$drs=$dset->GetResult();
		$drc=$dset->GetResultNumRows();
		//echo $drc;
		//получили набор прикрепленных словарей свойств, дальше каждый перебираем по именам, и для каждого имени - значение по коду товара!
		for($k=0; $k<$drc; $k++){
			$df=mysqli_fetch_array($drs);
			//echo " $df[0] ";
			$query='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$df[0].'" and pl.is_shown=1 order by pn.ord desc';
			//echo " $query ";
			$nset=new mysqlSet($query);
			$nrs=$nset->GetResult();
			$nrc=$nset->GetResultNumRows();
			
			$dic=new AllDictItem();
			$dict=$dic->GetItemById($df[0],$lang_id, 1);
			
			
			$dictrow=Array();
			
			$per_row=4; $cter=1;
			$row=Array();
			
			//перебираем имена опций
			for($j=0; $j<$nrc; $j++){
				$nf=mysqli_fetch_array($nrs);
				
				if($cter==1){
					//соберем ряд
					$row=Array();
					//echo 'Ряд начат!!!<br>';
				}
				
				$query2='select pv.id, pl.name, pv.photo_small, pv.photo_big from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
				//echo " $query2 ";
				$vset=new mysqlSet($query2);
				$vrs=$vset->GetResult();
				$vrc=$vset->GetResultNumRows();
				if($vrc>0){
					$vf=mysqli_fetch_array($vrs);
					
					$im=GetImageSize(ABSPATH.stripslashes($vf[2]));
					if($im!=false){
						$image_s_w=$im[0];
						$image_s_h=$im[1];
					}else{
						$image_s_w=70;
						$image_s_h=70;
					}
					$row[]=Array(
						'photo_exists'=>true,
						'item_image_s'=>stripslashes($vf[2]),
						'image_s_w'=>$image_s_w,
						'image_s_h'=>$image_s_h,
						'photo_id'=>$vf[0],
						'itemname'=>stripslashes($nf[1])
					);
				}else{
					//написать какой-нибудь обработчик для отстустсв фото
					$row[]=Array(
						'photo_exists'=>false,
						'itemname'=>stripslashes($nf[1])
					);
				}
				
				if(($j==($nrc-1))||($cter>=$per_row)){
					$cter=1;
					$dictrow[]=Array('cells'=>$row);
					//echo 'Ряд ended!!!<br>';
				}else $cter++;
			}
			
			$alls[]=Array(
				'addcaption'=>stripslashes($dict['name']),
				'addrows'=>$dictrow
			);
		}
		
		return $alls;
	}
	
	
	//вывод привязанных или унаслед товаром таблиц свойств
	public function GetPropTablesByGoodId($id, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt='';
		
		
		//проверим привязки к родительским разделам по коду 2
		$mi=new MmenuItem();
		$gd=new PriceItem();
		$good=$gd->GetItemById($id);			
		$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
		$cter=1; $q='';
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				$q.=' '.$kk;
				if($cter<count($arr)) $q.=',';
				$cter++;
			}
		}
		
		$query1='select distinct d.id, dl.id, dl.ord, dl.attach_code, dlang.name from dict as d, dict_attach_d as dl, dict_lang as dlang where dl.dict_id=d.id and dlang.lang_id="'.$lang_id.'" and dlang.dict_id=d.id and d.kind_id="1" and dlang.is_shown=1 and ((dl.attach_code=2 and dl.key_value in ('.$q.'))  or (dl.attach_code="3" and dl.key_value="'.$id.'") or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) order by dl.attach_code, dl.ord desc'; //order by dl.ord desc
		//$txt=$query1;
		
		$dset=new mysqlSet($query1);
		$drs=$dset->GetResult();
		$drc=$dset->GetResultNumRows();
		//echo $drc;
		//получили набор прикрепленных словарей свойств, дальше каждый перебираем по именам, и для каждого имени - значение по коду товара!

		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		$alls=Array();
		for($k=0; $k<$drc; $k++){
			$df=mysqli_fetch_array($drs);
			//echo " $df[0] ";
			$query='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$df[0].'" and pl.is_shown=1 order by pn.ord desc';
			//echo " $query ";
			$nset=new mysqlSet($query);
			$nrs=$nset->GetResult();
			$nrc=$nset->GetResultNumRows();
			
			//$dic=new AllDictItem();
			//$dict=$dic->GetItemById($df[0],$lang_id, 1);
			
			
			$props=Array();
			//перебираем имена опций
			for($j=0; $j<$nrc; $j++){
				$nf=mysqli_fetch_array($nrs);
				
				
				$query2='select pv.id, pl.name from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
				//echo " $query2 ";
				$vset=new mysqlSet($query2);
				$vrs=$vset->GetResult();
				$vrc=$vset->GetResultNumRows();
				if($vrc>0){
					$vf=mysqli_fetch_array($vrs);
					$value=stripslashes($vf[1]);
				}else $value='';
				
				$props[]=Array(
					'name'=>stripslashes($nf[1]),
					'value'=>$value
				);
			}
			
			$alls[]=Array(
				'caption'=>stripslashes($df[4]),
				'props'=>$props
			);
		}
		
		$smarty->assign('dicts',$alls);
		if($drc==0) return '';
		else return $smarty->fetch($template1);
	}
	
	
	//множественный вывод таблиц свойств для набора товаров 
	//(используется в сравнении товаров)
	public function GetPropTablesForGoods($goods, &$dicts_arr, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt=''; $mi=new MmenuItem();
		$gd=new PriceItem();
		$dicts_array=Array(); 
		$dicts_arr=Array();
		
		//формируем массив всех словарей по всем товарам
		foreach($goods as $id=>$some){
			//проверим привязки к родительским разделам по коду 2
			//$mi=new MmenuItem();
			//$gd=new PriceItem();
			$good=$gd->GetItemById($id);			
			$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					$q.=' '.$kk;
					if($cter<count($arr)) $q.=',';
					$cter++;
				}
			}
			$query1='select distinct d.id, dl.id, dl.ord, dl.attach_code, dlang.name from dict as d, dict_attach_d as dl, dict_lang as dlang where dl.dict_id=d.id and dlang.lang_id="'.$lang_id.'" and dlang.dict_id=d.id and d.kind_id="1" and dlang.is_shown=1 and ((dl.attach_code=2 and dl.key_value in ('.$q.'))  or (dl.attach_code="3" and dl.key_value="'.$id.'") or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) order by dl.attach_code, dl.ord desc'; //order by dl.ord desc
			
			$dset=new mysqlSet($query1);
			$drs=$dset->GetResult();
			$drc=$dset->GetResultNumRows();
			
			//формируем массив словарей, разбитый по товарам, словарям
			for($i=0; $i<$drc; $i++){
				$df=mysqli_fetch_array($drs);
				$dicts_array[$id][$i]=Array(
					'id'=>$df[0],
					'name'=>$df[4]
				);
				
			}
			
		}
		
		//имеем список словарей для каждого из товаров
		//оставить в списке только те словари, которые есть для всех товаров
		foreach($dicts_array as $id=>$set){
			//echo "<p> товар: $id<br>";
			foreach($set as $k=>$v){
				//прокручиваем словари по текущему товару
				//echo " проверим словарь $v[id] = $v[name]: <br>";
				//проверяем наличие текущего словаря у всех прочих товаров
				$found_arr=Array(); $cter=0;
				for($j=0;$j<count($dicts_array);$j++) $found_arr[$j]=false;
				foreach($dicts_array as $sub_id=>$sub_set){
					if($sub_id!=$id){
						foreach($sub_set as $kk=>$vv){
							//echo " словарь: $vv[id] = $vv[name] ";
							if($vv==$v){
								//echo " поиск для товара $id по товару $sub_id: словарь: $vv[id] = $vv[name] найден <br>";
								$found_arr[$cter]=true;
							}
						}
						//echo $sub_set[$k];
						//if(isset($dicts_array[$sub_id][
					}else $found_arr[$cter]=true;
					$cter++;
				}
				
				$found=true;
				for($j=0;$j<count($dicts_array);$j++){
					$found=$found&&$found_arr[$j];
				}
				if(!$found){
					//echo " поиск для товара $id словарь: $v[id] = $v[name] НЕ найден <br>";
					unset($dicts_array[$id][$k]);
				}//else echo " поиск для товара $id  словарь: $v[id] = $v[name] найден <br>";
				
			}
		}
		
		
		//echo "<p>Что осталось:</p>";
		//оставшийся массив содержит словари, одинаковые для всех товаров
		$cter=0;
		foreach($dicts_array as $id=>$set){
			//echo "<p> товар: $id<br>";
			if($cter>=1) break;
			foreach($set as $k=>$v){
				//echo "  словарь $v[id] = $v[name] <br>";
				//занесем в выходной массив наш словарь:
				$dicts_arr[]=$v;
			}
			$cter++;
		}
		
		$totalrows=Array();
		//выводим наши общие словари
		foreach($dicts_arr as $k=>$v){
			$totalrows[]=Array(
				'is_common_row'=>false,
				'row_title'=>stripslashes($v['name'])
			);
			
			//выводим из словарей свойства и значения
			$query='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$v['id'].'" and pl.is_shown=1 order by pn.ord desc';
			//echo " $query ";
			$nset=new mysqlSet($query);
			$nrs=$nset->GetResult();
			$nrc=$nset->GetResultNumRows();
			//перебираем имена опций
			for($j=0; $j<$nrc; $j++){
				$nf=mysqli_fetch_array($nrs);
				
				//перебираем значения свойств - работаем по товарам
				$cells=Array();
				$values=Array();
				foreach($goods as $gk=>$gv){
					$query2='select pv.id, pl.name from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$gk.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
					//echo " $query2 ";
					$vset=new mysqlSet($query2);
					$vrs=$vset->GetResult();
					$vrc=$vset->GetResultNumRows();
					
					//формируем массив значений 
					if($vrc>0){
						$vf=mysqli_fetch_array($vrs);
						$values[$gk]=$vf[1]; //$tpl3->set_tpl('{contents}',stripslashes($vf[1]));
					}else $values[$gk]='-';//$tpl3->set_tpl('{contents}','');
				}
				
				//выводим полученные значения свойств
				foreach($values as $id=>$value){
					if($this->CompareValues($id, $values)) $classname='propvalue';	
					else  $classname='propvalue_non_ident';	
					
					$cells[]=Array(
						'is_common_cell'=>true,
						'width'=>floor(100/count($goods)).'%',
						'classname'=>$classname,
						'contents'=>stripslashes($value)
					);
				}
				
				$totalrows[]=Array(
					'is_common_row'=>true,
					'row_title'=>stripslashes($nf[1]),
					'align'=>'left',
					'cells'=>$cells
				);
				
			}
		}
		return $totalrows;
	}
	
	
	//вывод привязанных или унаслед товаром таблиц свойств КРОМЕ указанных
	//используется в сравнении товаров
	public function GetPropTablesByGoodIdEXCEPT($id, $dicts_arr, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt='';
		
		//проверим привязки к родительским разделам по коду 2
		$mi=new MmenuItem();
		$gd=new PriceItem();
		$good=$gd->GetItemById($id);			
		$arr=$mi->RetrievePath($good['mid'], $flaglost, $vloj);
		$cter=1; $q='';
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				$q.=' '.$kk;
				if($cter<count($arr)) $q.=',';
				$cter++;
			}
		}
		
		//формируем строку - перечисление номеров словарей, которые НЕ включать в список
		$tmp_=Array();
		if(count($dicts_arr)>0){
			foreach($dicts_arr as $k=>$v) $tmp_[]='"'.$v['id'].'"';
			$not_string='and d.id NOT IN('.join(', ',$tmp_).')';
		//and d.id NOT IN('.$not_string.') 
		}else $not_string='';
		
		$query1='select distinct d.id, dl.id, dl.ord, dl.attach_code, dlang.name from dict as d, dict_attach_d as dl, dict_lang as dlang where dl.dict_id=d.id and dlang.lang_id="'.$lang_id.'" and dlang.dict_id=d.id and d.kind_id="1" and dlang.is_shown=1 and ((dl.attach_code=2 and dl.key_value in ('.$q.'))  or (dl.attach_code="3" and dl.key_value="'.$id.'") or (dl.attach_code=1 and dl.key_value="'.$good['mid'].'")) '.$not_string.' order by dl.attach_code, dl.ord desc'; //order by dl.ord desc
		//$txt=$query1;
		//echo $query1;
		$dset=new mysqlSet($query1);
		$drs=$dset->GetResult();
		$drc=$dset->GetResultNumRows();
		//получили набор прикрепленных словарей свойств, дальше каждый перебираем по именам, и для каждого имени - значение по коду товара!
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		$alls=Array();
		for($k=0; $k<$drc; $k++){
			$df=mysqli_fetch_array($drs);
			$query='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$df[0].'" and pl.is_shown=1 order by pn.ord desc';
			$nset=new mysqlSet($query);
			$nrs=$nset->GetResult();
			$nrc=$nset->GetResultNumRows();
			
			
			$props=Array();
			//перебираем имена опций
			for($j=0; $j<$nrc; $j++){
				$nf=mysqli_fetch_array($nrs);
				
				$query2='select pv.id, pl.name from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
				//echo " $query2 ";
				$vset=new mysqlSet($query2);
				$vrs=$vset->GetResult();
				$vrc=$vset->GetResultNumRows();
				if($vrc>0){
					$vf=mysqli_fetch_array($vrs);
					$value=stripslashes($vf[1]);
				}else $value='';
				$props[]=Array(
					'name'=>stripslashes($nf[1]),
					'value'=>$value
				);
				
			}
			$alls[]=Array(
				'caption'=>stripslashes($df[4]),
				'props'=>$props
			);
		}
		
		$smarty->assign('dicts',$alls);
		if($drc==0) return '';
		else return $smarty->fetch($template1);
	}
	
	
	
	
	
	//сравненние значений
	//currernt_id = айди нек товара, tmp_arr = массив значений одного свойства
	protected function CompareValues($current_id, $tmp_arr){
		$cter=0; $is_equal=true;
		//перебираем массив значений
		foreach($tmp_arr as $k=>$v){
			if($current_id==$k){
				//это нужное нам значение
				$cter1=0;
				//переберем все предыдущие от него значения и сравним
				foreach($tmp_arr as $kk=>$vv){
					if($cter1>=$cter){
						//достигли текущего значения, дальше не проверяем
						break;
					}
					//если хотя бы одно отличие - значит фолс и выход
					if($v!=$vv){
						$is_equal=false;
						break;
					}
					$cter1++;
				}
			}
			$cter++;
		}
		return $is_equal;
	}
	
	
	
//*********************КОНЕЦ  ФУНКЦИЙ ВЫВОДА ВСЕХ НАБОРОВ СВОЙСТВ ТОВАРА *******************************************
	
	
	
	
	//установка имен шаблонов
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang='tpl/alldicts/subitem_name.html', $name_vis_check='tpl/dicts_comp/subitem_lang_vis_check.html'){
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