<?
require_once('global.php');
require_once('priceitem.php');
require_once('csvlowlevel.php');

//����� ��� csv-�������������
class CsvFormer{
	public $low_level; 
	
	//���� � ����� � �������
	protected $path;
	//��� ������
	protected $sessionname='csvmaster';
	//���-�� �������, ����� ������� ��������� ��������� �������
	protected $records_per_head=5;
	
	public function __construct($path){
		$this->path=$path;
		$this->low_level=new CsvLowLevel($path);
	}
	
	//�������� ���������� �����
	public function CreateTempFile(){
		$this->low_level->CreateTempFile();
	}
	
	//��������� ������� ���������
	public function DrawTable1($template,$from=0){
		$txt='';
		if(($_SESSION[$this->sessionname]['filename']===NULL)||
			($_SESSION[$this->sessionname]['process_filename']===NULL)||
			!file_exists($this->path.$_SESSION[$this->sessionname]['filename'])
				||
				!file_exists($_SESSION[$this->sessionname]['process_filename'])
			){
			//return '<h3>������ ��������� ������!</h3>';
			$smarty = new SmartyAdm;
			$smarty->debugging = DEBUG_INFO;
			
			$smarty->assign('glob_error',true);
		
			$txt=$smarty->fetch($template);
			return $txt;
		}
		
		$pi=new PriceItem;
		$ml=new MmenuList;
		$menus=$ml->GetItemsOptShopByLangId($m_vals,$m_names);
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$alls=Array(); $rules=Array();
		//��������� �������� ���� � ��������� �� ���� �������
		if($f=@fopen($this->path.$_SESSION[$this->sessionname]['filename'], "r")){
			
			//������� ��� ����, ���������� �� ���� �������
			/*���� �� �������� �������� - ��:
			 ��������� ������� ��������� ������
			 �������� ������ ������� � ��������� ������ �� ���� 
			
			���� ���������� ������� - �������� � ��� ������:
			 ��������� ������ ������:
			  ������ ������� ������� - ������� �������, ��������
			   ����� - ������������ �������� �� ��� �����
			   ������� - ���������� ��� �� ���� � ������� ������ � ��� ����
			  ������ ��� -
			   ���������� ��� �� ���� � ������� ������ � ��� ����*/
			
			$priznak=$this->low_level->CheckFromPositionRez($from,$last_position);
			if($priznak<0){
				//���������� ��������
				$this->low_level->InsertBlanksRez($last_position,$from);
				//echo '<strong>�� ���������� �� ����!</strong> ';
			}else{
				//�������� ��������
				//echo '<strong>���������� �� ����!</strong> ';
			}
			
			$counter=0; $subcounter=0; $head_cter=0; $bflag=false;
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				
				$counter++;
				if($counter<=$from) continue;
				
				
				if($subcounter>=$_SESSION[$this->sessionname]['records_per_page']) continue;
				$subcounter++;
				
				
				if($head_cter==$this->records_per_head){
					$time_to_strike=true;
					$head_cter=0;
				}else {
					
					$time_to_strike=false;
				}
				$head_cter++;
				
				if(isset($data[0])) $csv_art=$data[0];
				else $csv_art='';
				
				if(isset($data[1])) $csv_name=$data[1];
				else $csv_name='';

				if(isset($data[2])) $csv_ost=$data[2];
				else $csv_ost='';								
				
				$bflag=!$bflag;
				
				
				
				//��������� ������ �� �����
				$rec=$this->low_level->GetData($from+$subcounter, $flag);
				//��������� ������ ������:
				if($flag<0){
					//������ ��� 
					//echo '<strong>������ �� �������!</strong><br> ';
					// ���������� ��� �� ���� � ������� ������ � ��� ����
					$good=$pi->GetItemByNameArt($csv_name,$csv_art);
					if($good!=false){
						//����� ���� � ����
						$good_=$pi->GetItemIdByNameArt($csv_name,$csv_art);
						$good_id=$good_['id'];
						$base_ost=$good['ostatok'];
						$base_name=stripslashes($good['name']);
						$base_art=stripslashes($good['articul']);
						$base_green=true;
						$action=2;
						$selected_mid=$good['mid'];
						
						$input_data=Array();
						$input_data[0]=$action;
						$input_data[1]=$base_art;
						$input_data[2]=$base_name;
						$input_data[3]=$csv_ost;
						$input_data[4]=$selected_mid;
						
					}else{
						//������ ��� � ����, ��������� �������� �����
						$base_ost='-';
						$good_id='';
						$base_name='-';
						$base_art='-';
						$base_green=false;
						$action=1;
						$selected_mid=0;
						
						$input_data=Array();
						$input_data[0]=$action;
						$input_data[1]=$csv_art;
						$input_data[2]=$csv_name;
						$input_data[3]=$csv_ost;
						$input_data[4]=$selected_mid;
					}
					
					$this->low_level->SetData($from+$subcounter,$input_data);
				}else{
					// ������ ������� ������� - ������� �������, ��������
					//echo '<strong>������ �������!</strong><br> ';
					$good=$pi->GetItemByNameArt($csv_name,$csv_art);
					if($good!=false){
						if((stripslashes($good['name'])==$rec[2])&&
						(stripslashes($good['articul'])==$rec[1])){
							$good_=$pi->GetItemIdByNameArt($csv_name,$csv_art);
							$good_id=$good_['id'];
							//����� - ������������ �������� �� ��� �����
							$base_ost=$good['ostatok'];
							$base_name=$rec[2];
							$base_art=$rec[1];
							$base_green=true;
							$csv_ost=$rec[3];
							$action=2;
							$selected_mid=$rec[4];
							
						}else{
							// ������� - ���������� ��� �� ���� � ������� ������ � ��� ����
							$base_ost=$good['ostatok'];
							$good_=$pi->GetItemIdByNameArt($csv_name,$csv_art);
							$good_id=$good_['id'];
							
							$base_name=stripslashes($good['name']);
							$base_art=stripslashes($good['articul']);
							$base_green=true;	
							$action=2;
							$selected_mid=$good['mid'];
							
						}
					}else{
						//������ ��� � ����, ��������� �������� �����
						$csv_ost=$rec[3];
						$good_id='';
						$base_ost='-';
						$base_name='-';
						$base_art='-';
						$base_green=false;
						$action=$rec[0];
						$selected_mid=$rec[4];
						
					}
				}
				
				
				
				
				$alls[]=Array(
					'time_to_strike'=>$time_to_strike,
					'bflag'=>$bflag,
					'csv_art'=>$csv_art,
					'csv_hash'=>md5($csv_art),
					'csv_name'=>$csv_name,
					'csv_ost'=>$csv_ost,
					'base_ost'=>$base_ost,							
					'base_name'=>$base_name,
					'base_art'=>$base_art,
					'base_green'=>$base_green,
					'good_id'=>$good_id,
					'action'=>$action,
					'mids'=>$m_vals,
					'mid_names'=>$m_names,
					'selected_mid'=>$selected_mid
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_ost',
					'kind'=>'num',
					'caption'=>'� ���� ������� ��������� ������ ������������� �����!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_ost',
					'kind'=>'gt=0',
					'caption'=>'� ���� ������� ��������� ������ ������������� �����!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_mid',
					'kind'=>'gt=0',
					'caption'=>'�������� �������� ������!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_action',
					'kind'=>'gt=0',
					'caption'=>'�������� �������� ��� �������!'
				);
				
			}
			$smarty->assign('per_this_page',$subcounter);
			
			fclose($f);
		}
		
		$smarty->assign('totalreccount',$this->CountAllRecords());
		$smarty->assign('records',$alls);
		$smarty->assign('rules',$rules);
		
		$smarty->assign('filename',$_SESSION[$this->sessionname]['filename']);
		$smarty->assign('from',$from);
		$smarty->assign('per_page',$_SESSION[$this->sessionname]['records_per_page']);
		$smarty->assign('step',2);
		$smarty->assign('glob_error',false);
		
		$txt=$smarty->fetch($template);
		
		return $txt;
	}
	
	//��������� ������� �������������
	//��������� ������� ���������
	public function DrawTable2($template,$from=0){
		$txt='';
		if(($_SESSION[$this->sessionname]['filename']===NULL)||
			($_SESSION[$this->sessionname]['process_filename']===NULL)||
			!file_exists($this->path.$_SESSION[$this->sessionname]['filename'])
				||
				!file_exists($_SESSION[$this->sessionname]['process_filename'])
			){
			//return '<h3>������ ��������� ������!</h3>';
			$smarty = new SmartyAdm;
			$smarty->debugging = DEBUG_INFO;
			
			$smarty->assign('glob_error',true);
		
			$txt=$smarty->fetch($template);
			return $txt;
		}
		
		$pi=new PriceItem;
		$ml=new MmenuList;
		$menus=$ml->GetItemsOptShopByLangId($m_vals,$m_names);
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$alls=Array(); $rules=Array();
		//��������� ��� ���� � ��������� �� ���� �������
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r")){
			
			
			
			$counter=0; $subcounter=0; $head_cter=0; $bflag=false;
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				
				
				
				
				$counter++;
				if($counter<=$from) continue;
				
				if($subcounter>=$_SESSION[$this->sessionname]['records_per_page']) continue;
				$subcounter++;
				
				
				if($head_cter==$this->records_per_head){
					$time_to_strike=true;
					$head_cter=0;
				}else {
					
					$time_to_strike=false;
				}
				$head_cter++;
				
				
				if(isset($data[1])) $csv_art=$data[1];
				else $csv_art='';
				
				if(isset($data[2])) $csv_name=$data[2];
				else $csv_name='';

				if(isset($data[3])) $csv_ost=$data[3];
				else $csv_ost='';								
				
				if(isset($data[0])) $action=$data[0];
				else $action=0;								
				
				if(isset($data[4])) $selected_mid=$data[4];
				else $selected_mid=0;								
				
				$bflag=!$bflag;
				
				//��������� ����� � ��������� � ������ � ��
				$good=$pi->GetItemByNameArt($csv_name,$csv_art);
				if($good!=false){
					//����� ���� � ����
					$base_green=true;
					$base_ost=$good['ostatok'];
				}else{
					//�����a net � ����
					$base_green=false;
					$base_ost='-';
				}
				
				
				
				$alls[]=Array(
					'time_to_strike'=>$time_to_strike,
					'bflag'=>$bflag,
					'csv_art'=>$csv_art,
					'csv_hash'=>md5($csv_art),
					'csv_name'=>$csv_name,
					'csv_ost'=>$csv_ost,
					'base_ost'=>$base_ost,		
					'base_green'=>$base_green,
					'action'=>$action,
					'mids'=>$m_vals,
					'mid_names'=>$m_names,
					'selected_mid'=>$selected_mid
					
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_ost',
					'kind'=>'num',
					'caption'=>'� ���� ������� ��������� ������ ������������� �����!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_ost',
					'kind'=>'gt=0',
					'caption'=>'� ���� ������� ��������� ������ ������������� �����!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_mid',
					'kind'=>'gt=0',
					'caption'=>'�������� �������� ������!'
				);
				
				$rules[]=Array(
					'fname'=>md5($csv_art).'_action',
					'kind'=>'gt=0',
					'caption'=>'�������� �������� ��� �������!'
				);
				
			}
			
			$smarty->assign('per_this_page',$subcounter);
			
			fclose($f);
		}
		
		
		
		$smarty->assign('totalreccount',$this->CountAllRecords());
		$smarty->assign('records',$alls);
		$smarty->assign('rules',$rules);
		
		//$smarty->assign('filename',$_SESSION[$this->sessionname]['filename']);
		$smarty->assign('from',$from);
		$smarty->assign('per_page',$_SESSION[$this->sessionname]['records_per_page']);
		$smarty->assign('step',3);
		$smarty->assign('glob_error',false);
		
		$txt=$smarty->fetch($template);
		
		return $txt;
	}
	
	//�������� ��������� � ���� ������
	public function UpdateBase($template){
		
		$txt='';
		if(
			($_SESSION[$this->sessionname]['process_filename']===NULL)||
				!file_exists($_SESSION[$this->sessionname]['process_filename'])
			){
			$smarty = new SmartyAdm;
			$smarty->debugging = DEBUG_INFO;
			$smarty->assign('glob_error',true);
			$txt=$smarty->fetch($template);
			return $txt;
		}
		
		$pi=new PriceItem;
		$mi=new MmenuItem;
		//$ml=new MmenuList;
		//$menus=$ml->GetItemsOptShopByLangId($m_vals,$m_names);
		
		$smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		$alls=Array(); $rules=Array();
		//��������� ��� ���� � ��������� �� ���� �������
		if($f=@fopen($_SESSION[$this->sessionname]['process_filename'], "r")){
			/*
		��������� ��� ����:
		����������� ��� ��������:
			1 - ������� ������, ��������� ��� ������
			2 - ������ ������, ���������, ���� �� ����� �����, 
				-���� - ���������, �������� ��� ������
				-��� - ����������
			3 - ������ �� ������, ����������
		*/
			 $head_cter=0; $bflag=false;
			while($data=fgetcsv($f,2500,$_SESSION[$this->sessionname]['separator'])){
				
				if($head_cter==$this->records_per_head){
					$time_to_strike=true;
					$head_cter=0;
				}else {
					
					$time_to_strike=false;
				}
				$head_cter++;
				
				if(isset($data[1])) $csv_art=$data[1];
				else $csv_art='';
				
				if(isset($data[2])) $csv_name=$data[2];
				else $csv_name='';

				if(isset($data[3])) $csv_ost=$data[3];
				else $csv_ost=0;								
				
				if(isset($data[0])) $action=$data[0];
				else $action=0;								
				
				if(isset($data[4])) $selected_mid=abs((int)$data[4]);
				else $selected_mid=0;								
				
				$bflag=!$bflag;
				
				$group='';
				$result=false;
				if($action==1){
					$mm=$mi->GetItemById($selected_mid);
					if($mm==false){
						//������
						//echo 'no mid';
						$result=false;
					}else{
						//��������� �����
						//echo 'good add';
						$mi=new MmenuItem();
						$pth=$mi->RetrievePath($selected_mid, $flaglost, $vloj, LANG_CODE);
						$value='-������- ';
						if($flaglost) $value.=' ����������� ����� - ';
						foreach($pth as $k=>$v){
							foreach($v as $kk=>$vv){
								$value.=' &gt; '.(stripslashes($vv['name']));
							}
						}
						$group=$value;
						
						
						$params=Array(); $lparams=Array();
						$params['mid']=$selected_mid;
						$params['articul']=$csv_art;
						$lparams['name']=$csv_name;
						$lparams['lang_id']=LANG_CODE;	
						
						$pi->Add($params, $lparams, $csv_ost);
						
						$result=true;
					}
				}else if($action==2){
					//��������� ����� � ��������� � ������ � ��
					$good=$pi->GetItemIdByNameArt($csv_name,$csv_art);
					if($good!=false){
						//����� ���� � ����
						$mm=$mi->GetItemById($selected_mid);
						if($mm==false){
							//������
							//echo 'no mid';
							$result=false;
						}else{
							//��������� �����
							//echo 'edit good';
							$params=Array(); $lparams=Array();
							$params['mid']=$selected_mid;
							$lparams['lang_id']=LANG_CODE;	
							
							$pth=$mi->RetrievePath($selected_mid, $flaglost, $vloj, LANG_CODE);
							$value='-������- ';
							if($flaglost) $value.=' ����������� ����� - ';
							foreach($pth as $k=>$v){
								foreach($v as $kk=>$vv){
									$value.=' &gt; '.(stripslashes($vv['name']));
								}
							}
							$group=$value;
							
							/*echo " ������: $good[id] $csv_ost";
							foreach($good as $k=>$v){
								echo "$k = $v<br>";
							}
							echo "<hr>";*/
							$pi->Edit($good['id'], $params, $lparams, $csv_ost);
							$result=true;
						}
					}else{
						//������
						//echo 'no good';
						$result=false;
					}
					
				}else{
					//������ �� ������
					$result=false;
				}
			
				$alls[]=Array(
					'time_to_strike'=>$time_to_strike,
					'bflag'=>$bflag,
					'csv_art'=>$csv_art,
					'csv_name'=>$csv_name,
					'csv_ost'=>$csv_ost,
					'action'=>$action,
					'selected_mid'=>$selected_mid,
					'group'=>$group,
					'result'=>$result
				);
			
			
			}
		}
		$smarty->assign('records',$alls);
		
		$smarty->assign('step',4);
		$smarty->assign('glob_error',false);
		
		$txt=$smarty->fetch($template);
		
		if($_SESSION[$this->sessionname]['do_delete_file']==1){
			@unlink($this->path.$_SESSION[$this->sessionname]['filename'] );
			@unlink($_SESSION[$this->sessionname]['process_filename']);
			
		}
		$_SESSION[$this->sessionname]['filename']=NULL;
		$_SESSION[$this->sessionname]['process_filename']=NULL;
		
		$_SESSION[$this->sessionname]['records_per_page']=ITEMS_PER_PAGE;
		$_SESSION[$this->sessionname]['do_delete_file']=1;
		
		return $txt;
		
	}
	
	//�������������� ������ � ��� �����
	public function EditRecord($from, $articul, $params){
		return $this->low_level->EditRecord($from, $articul, $params);
	}
	
	
	//�������� ������� ���� � �������� �����
	public function CheckFromPosition($from){
		return $this->low_level->CheckFromPosition($from);
	}
	
	//�������� ������� ���� � ��� �����
	public function CheckFromPositionRez($from,&$counter){
		return $this->low_level->CheckFromPositionRez($from,$counter);
	}
	
	//������� ������ ����� ������� ishodnogo �����
	public function CountAllRecords(){
		return $this->low_level->CountAllRecords();
	}
	
	//������� ���������� �����
	public function ClearTempFile(){
		return $this->low_level->ClearTempFile();
	}
}
?>
