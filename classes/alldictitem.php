<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');

require_once('smarty/SmartyAdm.class.php'); // Подключаем файл с классом

//итем словарь
class AllDictItem extends AbstractLangItem{
	protected $subkeyname;
	protected $dict_kind_tablename='dict_kind';
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='dict';
		$this->lang_tablename='dict_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='dict_id';
		$this->lang_id_name='lang_id';
		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		$this->DelChilds($id);
		
		AbstractLangItem::Del($id);
	}	
	
	
	//удаление всех подчиненных таблиц данного словаря
	public function DelChilds($id){
	
		//прикрепления словаря
		$query = 'delete from dict_attach_d where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		//из опций заказа
		$query = 'delete from order_item_option where value_id in (select id prop_value where name_id in (select id from prop_name where '.$this->mid_name.'='.$id.'));';
		
		
		//значения свойств
		$query = 'delete from prop_value_lang where value_id in (select id prop_value where name_id in (select id from prop_name where '.$this->mid_name.'='.$id.'));';
		$it=new nonSet($query);
		$query = 'delete from prop_value where name_id in (select id from prop_name where '.$this->mid_name.'='.$id.');';
		$it=new nonSet($query);
		
		
		
		
		//имена свойств
		$query = 'delete from prop_name_lang where name_id in (select id from prop_name where '.$this->mid_name.'='.$id.');';
		$it=new nonSet($query);
		$query = 'delete from prop_name where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
	}
	
	//групповое удаление по перечисленным номерам разделов
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
		//из опций заказа
		$query = 'delete from order_item_option where value_id in (select id from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=2 or attach_code=3 and key_value in ('.$q_string.') ) ));';
		$it=new nonSet($query);
		
		
		//значения свойств
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=2 or attach_code=3 and key_value in ('.$q_string.') ) ));';
		$it=new nonSet($query);
		$query = 'delete from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=2 or attach_code=1 and key_value in ('.$q_string.')  ));';
		$it=new nonSet($query);
		
		
		//прикрепления словаря
		$query = 'delete from dict_attach_d where attach_code=2 or attach_code=1 and key_value in ('.$q_string.');';
		$it=new nonSet($query);
		
		
		//для кода 3
		//значения свойств
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.')) ) ));';
		$it=new nonSet($query);
		$query = 'delete from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.')  )));';
		$it=new nonSet($query);
		
		
		//прикрепления словаря
		$query = 'delete from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.'));';
		$it=new nonSet($query);
		
		//die();
	}
	
	
	//перечисление видов поведения словаря в тегах option
	public function GetBehaviorsOpt($current_id=0){
		$txt='';
		$sql='select * from '.$this->dict_kind_tablename;
		
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		for($i=0;$i<$set->GetResultNumRows();$i++){
			$f=mysqli_fetch_array($rs);
			if($f['id']==$current_id){
				$txt.='<option value="'.$f['id'].'" selected>'.stripslashes($f['name']).'</option>'."\n";
			}else{
				$txt.='<option value="'.$f['id'].'">'.stripslashes($f['name']).'</option>'."\n";
			}
		}
		return $txt;
	}
	
	
	//прикрепить словарь к итему
	public function AttachDict($dict_id, $id, $kind){
		//проверим прикрепления
		$att=$this->CheckBoundsByIdKind($dict_id,$id,$kind);
		if(isset($att['has_kind_'.$kind])){
			//do nothing
			return false;
		}else{
			$sql='insert into dict_attach_d (dict_id, key_value, attach_code) values("'.$dict_id.'", "'.$id.'", "'.$kind.'")';
			$ns=new NonSet($sql);
			return true;
		}
	}
	
	//открепить словарь от итема
	public function DetachDict($dict_id, $id, $kind){
		$sql='delete from dict_attach_d where dict_id="'.$dict_id.'" and key_value="'.$id.'" and attach_code="'.$kind.'"';
		$ns=new NonSet($sql);
		return true;
	}
	
	
	
	
	//проверка: привязан ли данный словарь к данному разделу
	public function CheckBoundsByIdKind($dict_id,$id,$kind){
		$result=Array(); 
		$result['flag']=0;
		
		if(($kind==2)||($kind==1)){
			//проверим привязки с кодом 1
			$sql='select count(*) from dict_attach_d where dict_id='.$dict_id.' and key_value='.$id.' and attach_code=1';
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
			if($f['count(*)']>0){
				$result['has_kind_1']=true;
				$result['flag']=1;
			}//else $result['has_kind_1']=false;
			
			//проверим привязки с кодом 2 для самого раздела
			$sql='select count(*) from dict_attach_d where dict_id='.$dict_id.' and key_value='.$id.' and attach_code=2';
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
			if($f['count(*)']>0){
				$result['has_kind_2']=true;
				$result['flag']=2;
			}//else $result['has_kind_2']=false;
			
			
			//проверим привязки к родительским разделам по коду 2
			$mi=new MmenuItem();
			$mmi=$mi->GetItemById($id);
			if($mmi['parent_id']==0) return $result;
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
			//echo $q;	 die();
			$sql='select count(*) from dict_attach_d where dict_id='.$dict_id.' and key_value in('.$q.') and attach_code=2';
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
			if($f['count(*)']>0){
				$result['has_inh']=true;
			}//else $result['has_inh']=false;
		}
		
		if($kind==3){
			//проверим привязки с кодом 3
			$sql='select count(*) from dict_attach_d where dict_id='.$dict_id.' and key_value='.$id.' and attach_code=3';
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
			if($f['count(*)']>0){
				$result['has_kind_3']=true;
				$result['flag']=3;
			}//else $result['has_kind_1']=false;
			
			
			//проверим привязки к родительским разделам по коду 2
			$pi=new PriceItem();
			$pri=$pi->GetItemById($id);
			
			$mi=new MmenuItem();
			$mmi=$mi->GetItemById($pri['mid']);
			$arr=$mi->RetrievePath($pri['mid'], $flaglost, $vloj);
			$cter=1; $q='';
			foreach($arr as $k=>$v){
				foreach($v as $kk=>$vv){
					$q.=' '.$kk;
					if($cter<count($arr)) $q.=',';
					$cter++;
				}
			}
			//echo $q;	 die();
			$sql='select count(*) from dict_attach_d where dict_id='.$dict_id.' and ((key_value in('.$q.') and attach_code=2) or (key_value="'.$pri['mid'].'" and attach_code=1))';
			$set=new mysqlSet($sql);
			$rs=$set->GetResult();
			$f=mysqli_fetch_array($rs);
			if($f['count(*)']>0){
				$result['has_inh']=true;
			}//else $result['has_inh']=false;
		}
		
		return $result;
	}
	
	
	//показ подитемов
	
	
	//клиентский показ доп. фото
	public function ShowAddPhotosByDictIdGoodId($dict_id, $good_id, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt='';
		
		$query='select pn.id, pl.name from prop_name as pn inner join prop_name_lang as pl on (pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pl.is_shown=1) where pn.dict_id="'.$dict_id.'" order by pn.ord desc';


		//echo " $query <p />";
		$nset=new mysqlSet($query);
		$nrs=$nset->GetResult();
		$nrc=$nset->GetResultNumRows();
		
		$dic=new AllDictItem();
		$dict=$dic->GetItemById($dict_id,$lang_id, 1);
		
		
		$smarty=new SmartyAdm;
		$smarty->debugging=DEBUG_INFO;
		$smarty->assign('caption',stripslashes($dict['name']));
		
		
		$alls=Array();
		$row='';
		//перебираем имена опций
		for($j=0; $j<$nrc; $j++){
			$nf=mysqli_fetch_array($nrs);
			
				
				$query2='select pv.id, pl.name, pv.photo_small, pv.photo_big from prop_value as pv inner join prop_value_lang as pl on (pl.lang_id="'.$lang_id.'" and pl.is_shown="1" and pl.value_id=pv.id) where pv.item_id="'.$good_id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';

				
				//echo " $query2  <br>";
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
					$alls[]=Array(
						'photo_exists'=>true,
						'itemname'=>stripslashes($nf[1]),
						'image_s_w'=>$image_s_w,
						'image_s_h'=>$image_s_h,
						'photo_id'=>$vf[0],
						'item_image_s'=>stripslashes($vf[2])
					);
				}else{
					//написать какой-нибудь обработчик для отстустсв фото
					$alls[]=Array(
						'photo_exists'=>false,
						'itemname'=>stripslashes($nf[1])
					);
				}
		}
		$smarty->assign('cells',$alls);
		$txt=$smarty->fetch($template1);
		return $txt;
	}
	
	
	//вывод миниатюр строкой со стрелками влево-вправо
	public function GetGalView($current_id, $dict_id, $good_id, $lang_id=LANG_CODE, $template1='', $template2='',$template3=''){
		$txt=''; $max_row_photo=5; $half_max=floor($max_row_photo/2);
		$has_next=false; $has_prev=false;
		$url_path='';
		
		$sql='select pn.id, pl.name from prop_name as pn, prop_name_lang as pl where pl.lang_id="'.$lang_id.'" and pl.name_id=pn.id and pn.dict_id="'.$dict_id.'" and pl.is_shown=1 order by pn.ord desc';
		
		$set=new mysqlSet($sql);
		$nrs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		if($rc==0) return '';
		
		unset($total);
		$total=Array();
		
		$pos=0; unset($positions); $positions=Array();
		for($i=0; $i<$rc; $i++){
			//$f=mysqli_fetch_array($rs);
			
			$nf=mysqli_fetch_array($nrs);
			
			$query2='select pv.id, pl.name, pv.photo_small, pv.photo_big from prop_value as pv, prop_value_lang as pl where pl.lang_id="'.$lang_id.'" and pl.is_shown=1 and pl.value_id=pv.id and pv.item_id="'.$good_id.'" and pv.name_id="'.$nf[0].'" order by pv.ord desc';
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
				$alls[]=Array(
					'photo_exists'=>true,
					'itemname'=>stripslashes($nf[1]),
					'image_s_w'=>$image_s_w,
					'image_s_h'=>$image_s_h,
					'photo_id'=>$vf[0],
					'item_image_s'=>stripslashes($vf[2])
				);
				
				$total[$i]=Array(
					'id'=>$vf[0],
					'photo_small'=>$vf[2],
					'name'=>stripslashes($nf[1]),
					'small_txt'=>stripslashes($nf[1])
				);
				
				if($vf[0]==$current_id) $pos=$i; //позиция текущего фото в массиве
			}else{
				
			}
			
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
		if($template2=='xajax')
			$smarty = new SmartyAj;
		else $smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		//выведем стрелку пред
		$smarty->assign('has_prev',$has_prev);
		if($has_prev){
			$path=$url_path.'/viewaddphoto.php?photo_id='.$prev['id'];
			
			$smarty->assign('prev_item_url',$path);
			$smarty->assign('prev_onclik',$prev['id']);
		}else{
		}
		
		//выведем стрелку next
		$smarty->assign('has_next',$has_next);
		if($has_next){
			$path=$url_path.'/viewaddphoto.php?photo_id='.$next['id'];
			
			$smarty->assign('next_item_url',$path);
			$smarty->assign('next_onclik',$next['id']);
		}else{}
		
		$alls=Array();
		foreach($positions as $k=>$v){
			$path=$url_path.'/viewaddphoto.php?photo_id='.$v['id'];
			
			if($v['id']==$current_id) $is_active=true;
			else $is_active=false;
			
			$alls[]=Array(
				'is_active'=>$is_active,
				'item_url'=>$path,
				'item_src'=>stripslashes($v['photo_small']),
				'item_name'=>stripslashes($v['name']),
				'item_txt'=>stripslashes($v['small_txt']),
				'item_onclick'=>stripslashes($v['id'])
			);
		}
		
		$smarty->assign('items',$alls);
		$smarty->assign('lang_id',$lang_id);
		$smarty->assign('good_id',$good_id);		
		$txt=$smarty->fetch($template1);
		return $txt;
	}
	
}
?>