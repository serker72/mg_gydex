<?

require_once('langitem.php');
require_once('resoursefile.php');
require_once('smarty/SmartyAdm.class.php');
require_once('smarty/SmartyAj.class.php');

//������ � ��������� ����� ������
class ResFileAj extends ResFile{
	protected $filename;
	protected $resfiles;
	protected $resstrings;
	protected $reslangs;
	
	//�������
	protected $table='reseditor/res-aj.html';
	protected $filerow='';
	protected $resrow='reseditor/res_resrow_aj.html';	
	protected $valuerow='reseditor/res_valrow_aj.html';
	
	
	
//********************** ������ �� �������� ********************************************************************	
//��������� �� �������!!!!!!!!!!!!!!!!!!	
	
	//������ ����� ������
	public function DrawTree($is_aj=false){
		$txt='';
		
		if($is_aj) $smarty = new SmartyAj;
		else $smarty = new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$alls=Array();
		foreach($this->resfiles as $k=>$file){
			$ress=Array();
			//����� �������� � �������� �������� ��������, ����� ������
			
			$alls[]=Array(
				'fileno'=>stripslashes($file['file_id']),
				'filename'=>stripslashes($file['file_id']),
				'filedescr'=>stripslashes($file['descr']),
				//��� ���������� ������:
				'filename_'=>htmlspecialchars(stripslashes($file['file_id'])),
				'filedescr_'=>htmlspecialchars(stripslashes($file['descr'])),
				'ress'=>$ress
			);
		}
		$smarty->assign('files',$alls);
		$smarty->assign('COORDFUNC',COORDFUNC);
		//$smarty->assign('full_tree',$full_tree);
		$txt=$smarty->fetch($this->table);
		
		return $txt;
	}
	
	
	//������� ������ ������ �������� � �� ��������
	public function DrawRes($file_id){
		$txt='';
		
		$smarty = new SmartyAj;
		$smarty->debugging = DEBUG_INFO;
		
		$alls=Array();
		foreach($this->resfiles as $k=>$file){
			//������� ������ ���� �  �� ���� ������!
			if($file['file_id']==$file_id){
				foreach($file['res'] as $kk=>$res){
						$langs=$this->DrawVals($file_id, $res['res_id'], $res['reslang']);
						
						$alls[]=Array(
							'resno'=>stripslashes($res['res_id']),
							'resno_'=>htmlspecialchars(stripslashes($res['res_id'])),
							'resname'=>stripslashes($res['res_id']),
							'resdescr'=>stripslashes($res['descr']),
							'resdescr_'=>htmlspecialchars(stripslashes($res['descr'])),
							'langs'=>$langs
						);
				}
			}
		
		}
		$smarty->assign('ress',$alls);
		$smarty->assign('fileno',$file_id);
		$smarty->assign('fileno_',htmlspecialchars(stripslashes($file_id)));
		$smarty->assign('COORDFUNC',COORDFUNC);
		$txt=$smarty->fetch($this->resrow);
		return $txt;
	}
	
	
	//����� �������� ��������
	public function DrawVals($file_id,$res_id){
		$txt='';
		$smarty = new SmartyAj;
		$smarty->debugging = DEBUG_INFO;
		
		$alls=Array();
		foreach($this->resfiles as $k=>$file){
			//������� ������ ���� �  �� ���� ������!
			if($file['file_id']==$file_id){
				foreach($file['res'] as $kk=>$res){
					
					//������� ������ ������ � �� ���� ������!
					if($res['res_id']==$res_id){
						
						foreach($res['reslang'] as $kkk=>$reslang){
							$li=new LangItem();
							$lang=$li->GetItemById($reslang['lang_id']);
							if($lang!=false){
								$alls[]=Array(
									'langno'=>stripslashes($reslang['lang_id']),
									'value'=>htmlspecialchars(stripslashes($reslang['value'])),
									'value_'=>eregi_replace("\n","",eregi_replace("\r\n","",htmlspecialchars(stripslashes($reslang['value'])))),
									'lang_flag'=>stripslashes($lang['lang_flag'])
								);
							}
						}
					}
				}
			}
		}
		
		$smarty->assign('fileno',$file_id);
		$smarty->assign('fileno_',htmlspecialchars(stripslashes($file_id)));
		$smarty->assign('langs',$alls);
		$smarty->assign('resno',$res_id);
		$smarty->assign('resno_',htmlspecialchars(stripslashes($res_id)));
		$smarty->assign('COORDFUNC',COORDFUNC);
		$txt=$smarty->fetch($this->valuerow);
		
		return $txt;
	}
	
}
?>