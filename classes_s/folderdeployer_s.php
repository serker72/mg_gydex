<?
require_once('global_resolver.php');
require_once(ABSPATH.'classes_s/onefolder.php');
require_once(ABSPATH.'classes_s/folderdeployer.php');
require_once(ABSPATH.'classes/smarty/SmartyAdm.class.php');
require_once(ABSPATH.'classes/smarty/SmartyAj.class.php');
require_once(ABSPATH.'classes/resoursefile.php');

//����� ��������� �� ������
class FolderDeployer_S extends FolderDeployer{

	protected $init_path; //������ ���� � �����
	protected $base_path; //�������� ���� (��� ��������� ��� ��������� ������)
	protected $fragments=Array(); //������, ���������� ��������� ���� � �����
	
	protected $delim='/'; //������-������� ���������
	
	//������� ��� ����� � ������
	protected $dir_arr=Array();
	protected $file_arr=Array();
	
	protected $result='';
	
	protected $folder_class_name='fldr';
	protected $active_folder_name='afldr';
	protected $folder_plus_img='/img/plus.gif';
	protected $folder_minus_img='/img/minus.gif';
	
	//��� �����-��������
	protected $prog_name='index.php';
	
	//������ ��� ������ ������ �����
	protected $template='photofolder/folders_tree.html';
	
	//����-������� ������������� ���������
	protected $has_xajax=false;
	
	protected $has_root=true;
	
	public function  __construct($init_path,$base_path,$folder){
		$this->init($init_path,$base_path,$folder);
	}
	
	protected function init($path, $base_path,$folder){
		$this->init_path=$path;
		$this->base_path=$base_path;
		$this->folder=$folder;
		$this->MakeFragments();
	}
	
	//�������� ��� ��������
	public function SetProgName($name){
		$this->prog_name=$name;
		return true;
	}
	
	public function SetTemplate($template){
		$this->template=$template;
		return true;
	}
	public function SetXajax($xajax){
		$this->has_xajax=$xajax;
		return true;
	}
	public function SetRoot($root){
		$this->has_root=$root;
		return true;
	}
	
	
	//��������� �������� ������ �� ����� �� ������� /
	public function MakeFragments(){
		$some=substr($this->init_path, strlen($this->base_path), strlen($this->init_path));
		
		$this->fragments=explode( $this->delim, $some);
		
		
	}

	//��������� ������� ����������
	public function GetFragments(){
		return $this->fragments;
	}
	
	//��������� �� ������� ���������� � ������ ������ ���������
	public function CreateTree($has_files=false,$params=Array()){
		$txt='';
		
		$alls=Array();
		
		if($this->has_xajax) $sm=new SmartyAj;
		else $sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		//������ ����� ������
		//$txt.='<div class="dirtree">';
		
		$offs=0; $path=$this->base_path;
		for($i=0;$i<count($this->fragments);$i++){
			
			if($i!=0) $path.='/'.$this->fragments[$i];
			else $path.=$this->fragments[$i];
			$d=new OneFolder($path,$this->base_path);
			$d->SetOffs($offs);
			$txt.=$d->ReadDirect($has_files);
			
			$this->dir_arr[]=$d->GetDirArr();
			$this->file_arr[]=$d->GetFileArr();
			$offs+=20;
		}
		
		$this->result='';
		
		$str=DeParams($params);
		
		
		
		/*$this->result.="<div class=\"folder_level\" style=\"margin-left: 0px; \"><a class=\"fldr\" href=\"/$this->prog_name?folder=$str\">/������</a></div>
		</div>";
		*/
		$subs=$this->RekursArrays($this->base_path,0, $has_files, $params);
		
		$files_s=Array();
		if($has_files){
			foreach($this->file_arr[0] as $k=>$v){
				//$this->result.=$this->DrawFile($k,$v,$params);
				$files_s[]=Array(
					'name'=>basename($k)
				);
			}
		}
		
		
		
		if($this->has_root){
			$alls[]=Array(
				'url'=>"$this->prog_name?folder=$str",
				'pict'=>'/img/01.gif',
				'name'=>'/&nbsp;������� �����',
				'has_files'=>$has_files,
				'files'=>$files_s,
				'has_controls'=>false,
				'rel_path'=>'/',
				'subs'=>$subs
			);
		}else{
			$alls[]=Array(
				'subs'=>$subs
			);
		}
		
		
		//$txt.=$this->result;
		//����� ����� ������
		//$txt.='</div>';
		//return $txt;
		
		$sm->assign('items',$alls);
		return $sm->fetch($this->template);
		
	}
	
	
	//����������� ����� �������� ������
	protected function RekursArrays($currpath,$ct, $has_files=false, $params=Array()){
			
		$alls=Array();
		
		//$sm=new SmartyAdm;
		if($this->has_xajax) $sm=new SmartyAj;
		else $sm=new SmartyAdm;
		$sm->debugging=DEBUG_INFO;
		
		
		if($ct<count($this->fragments)){
			if($ct!=0) $currpath.='/'.$this->fragments[$ct];
			else $currpath.=$this->fragments[$ct];
			
			$sm->assign('rel_path', str_replace($this->base_path, '', $currpath));
			
			foreach($this->dir_arr[$ct] as $k=>$v){
				//echo $currpath;
				//echo count($this->fragments);
				$checkpoint =(isset($this->fragments[$ct+1])&&(($currpath.'/'.$this->fragments[$ct+1])==$k));
				
				//echo @$this->fragments[$ct+1].'<br>';
				//echo "$k<br>";
				
				//$params=Array();
				//$params['folder']=$v['rel_path'];
				$str=DeParams($params);
				
				//echo $v['rel_path'];
				//$this->result.=$currpath;
				
				$subs=''; $files_s=Array();
				//$this->result.=$this->DrawDir($k, $v, $checkpoint, $params);
				if($checkpoint){
					$ct++;
					
					$subs=$this->RekursArrays($currpath,$ct,$has_files,$params);
					
					if($has_files)
						foreach($this->file_arr[$ct] as $kk=>$vv){
	//						$this->result.=$this->DrawFile($kk,$vv,$params);
							$files_s[]=Array(
								'name'=>basename($kk)
							);
						}
					$pict=$this->folder_minus_img;
				}else $pict=$this->folder_plus_img;
				
				$alls[]=Array(
					'url'=>"$this->prog_name?from=0&folder=$v[rel_path]$str",
					'pict'=>$pict,
					'name'=>$v['name'],
					'has_files'=>$has_files,
					'files'=>$files_s,
					'has_controls'=>true,
					'rel_path'=>$v['rel_path'],
					'subs'=>$subs
				);
			}
		}
		
		$sm->assign('items',$alls);
		return $sm->fetch($this->template);
	}
	
	
	//������� ������ ��������
	protected function DrawDir($name,$props,$is_opened,$params){
		$txt='';
		/*
		$str=DeParams($params);
		
		$txt.="<div class=\"folder_level\" style=\"margin-left: $props[offs]px;\" id=\"$name\">";
		$txt.="<a name=\"$props[rel_path]\"></a>";
		if($is_opened){
			$txt.="<nobr><a class=\"$this->active_folder_name\" href=\"$this->prog_name?from=0&folder=$props[rel_path]$str\"><img src=\"$this->folder_minus_img\" alt=\"\" border=\"0\" align=\"absmiddle\">$props[name]</a></nobr>";

			//$txt.="$name";
		}else{
			$txt.="<nobr><a class=\"$this->folder_class_name\" href=\"$this->prog_name?from=0&folder=$props[rel_path]$str\"><img src=\"$this->folder_plus_img\" alt=\"\" border=\"0\" align=\"absmiddle\">$props[name]</a></nobr>";
		}
		
		$txt.="<span style=\"font-size: 8pt;\">&nbsp;&nbsp; <a class=\"photolink\" href=\"#$props[rel_path]\" onmousedown=\"".COORDFUNC." SetCoords('folderer');\" onClick=\"pt=document.getElementById('folderpath'); pt.value='".SecStr($props['path'])."'; pt=document.getElementById('oldname'); pt.value='".SecStr($props['name'])."'; pt=document.getElementById('foldername'); pt.value='".SecStr($props['name'])."'; pt=document.getElementById('action'); pt.value='1'; r=document.getElementById('folderer'); r.className='renvis'; \">�������������</a> &nbsp;&nbsp;&nbsp;&nbsp; 

<a class=\"photolink\" href=\"#\" onClick=\"ct=window.confirm('��������!!! �� ������������� ������ ������� ������ �������?'); if(ct) {".COORDFUNC." RemoveDirFunc('".SecStr($props['rel_path'])."');}\">�������</a></span>
			</div>";*/
		return $txt;
		
	}
	
	//������� ������ �����
	protected function DrawFile($name,$offset,$params){
		$txt='';		
		/*$txt.="<div style=\"color: red; margin-left: ".$offset['offs']."px;\">$name</div>";
		*/
		return $txt;
	}
	
	
	
	
}

?>