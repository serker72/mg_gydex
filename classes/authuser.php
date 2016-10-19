<?
require_once('global.php');
require_once('useritem.php');
require_once('resoursefile.php');

//����� ��� ���������� ������ � ������
class AuthUser{
	public $user_item; //���-� ������ UserItem
	protected $profile=NULL; //���������-������� �����
	
	protected $sess_name; //��� ������, ��� ����
	protected $show_errors=true;
	protected $err_mess=Array();
	protected $templates=Array();
	protected $lang;
	protected $max_cookie_time=2419200; //����� ����� �������
	
	protected $err_code;
	
	
	public function __construct($sess_name='blackcat_user'){
		$this->init($sess_name);
	}
	
	//��������� ���� ����
	protected function init($sess_name){
		$this->sess_name=$sess_name;
		if(isset($_SESSION['lang'])){
			$this->lang=$_SESSION['lang'];
		}else $this->lang=LANG_CODE;
		if(!isset($_SESSION[$this->sess_name])) $_SESSION[$this->sess_name]=Array();
		
		$this->err_code=0;
		
		$this->user_item=new UserItem();
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		$this->err_mess[1]=$rf->GetValue('profile.php','auth_error',$this->lang);//�������� �����/������';
		$this->err_mess[2]=$rf->GetValue('profile.php','auth_error',$this->lang);
		$this->err_mess[3]=$rf->GetValue('profile.php','auth_error',$this->lang);
		$this->err_mess[4]=$rf->GetValue('profile.php','auth_error',$this->lang);
		$this->err_mess[5]=$rf->GetValue('profile.php','add_user_error',$this->lang);
		$this->err_mess[6]=$rf->GetValue('profile.php','ed_user_error',$this->lang);
		$this->err_mess[7]=$rf->GetValue('profile.php','ed_user_auth_error',$this->lang);
		
		$this->err_mess[8]=$rf->GetValue('profile.php','log_error',$this->lang);
		$this->err_mess[9]=$rf->GetValue('profile.php','log_exists_error',$this->lang);
		
		$this->err_mess[10]=$rf->GetValue('profile.php','pass_error',$this->lang);
		$this->err_mess[11]=$rf->GetValue('profile.php','email_error',$this->lang);
		 
		$this->err_mess[12]=$rf->GetValue('profile.php','unknown_error',$this->lang); //������ ����, �� ��� ���������
		
		$this->err_mess[13]=$rf->GetValue('profile.php','pass_not_confirm_error',$this->lang); //
		
		$this->err_mess[14]=$rf->GetValue('profile.php','incorrect_pass_error',$this->lang); //�������� ������� ������ ��� ������ �������
		$this->err_mess[15]=$rf->GetValue('profile.php','incorrect_pass_error',$this->lang); //�������� ������� ������ ��� ������ �������
	}
	
	//������� ����������� (���������� �� ������, ������)
	public function Authorize($login,$password,$rem_me=false){
		if($this->AuthUser($login, $password)){
			$_SESSION[$this->sess_name]['username']=$login;
			$_SESSION[$this->sess_name]['password']=$password;
			if($rem_me){
				@setcookie("user[0]", $login,time()+$this->max_cookie_time,'/');				
				@setcookie("user[1]", $password,time()+$this->max_cookie_time,'/');	
			}
		}
	}
	
	//������� ������
	public function DeAuthorize(){
		unset($_SESSION[$this->sess_name]['username']);
		unset($_SESSION[$this->sess_name]['password']);
		@setcookie("user[0]", '',time()-84000,'/');
		@setcookie("user[1]", '',time()-84000,'/');
	}
	
	//������� �������� ����������� (���������� ������, ������ � ��.)
	public function Auth(){
		$this->err_code=1;//����������� �� ���������	
		if(isset($_SESSION[$this->sess_name]['username'])&&isset($_SESSION[$this->sess_name]['password'])){
			//��������� ���-��
			//��������� �����-������
			//���� �������, ������ ����������
			if(@$this->AuthUser(($_SESSION[$this->sess_name]['username']), $_SESSION[$this->sess_name]['password']))	{
				//�������������� �����!
				//�������� ��� 
				$value['username'] = $_SESSION[$this->sess_name]['username'];
				$value['password'] = $_SESSION[$this->sess_name]['password'];
				
				//��� ��� ��������� � ������� AuthUser
				//$this->username=$value['username'];			
				//$this->password=$value['password'];	
				$this->err_code=0;//��� ������			
			}else{
				$this->err_code=4;//����� �� ������ ��� � ��			
			}	
		}else{
			//� ������ ��� ���-��, �������� ������
			if(isset($_COOKIE['user'])){
				
				$value['username'] = $_COOKIE['user']['0'];
				$value['password'] = $_COOKIE['user']['1'];			
				if(@$this->AuthUser(($value['username']), $value['password']))	{
					//�������������� �����!
				    $_SESSION[$this->sess_name]['username']=$value['username'];
				    $_SESSION[$this->sess_name]['password']=$value['password'];	
					
					//��� ��� ��������� � ������� AuthUser
					//$this->username=$value['username'];			
					//$this->password=$value['password'];								
					
					@setcookie("user[0]", $value['username'],time()+$this->max_cookie_time,'/');				
					@setcookie("user[1]", $value['password'],time()+$this->max_cookie_time,'/');	
					$this->err_code=0;//��� ������
				}else{
					$this->err_code=2;//����� �� ������� ��� � ��
				}			
			}else		
				$this->err_code=3;//����� �� ���������, ������� ���		
		}	
		
		if($this->err_code!=0){
			//��� ��� ��������� � ������� AuthUser
			//$this->username=NULL;
			//$this->pass=NULL;
			//$this->profile=NULL;
			
			//echo $this->ShowError($this->err_code);
		}
		return $this->profile;
	}
	
	//������� �������� ������-������
	//���� ��� �������� ��������, �� ���������� ���, � ��������� �������
	//���� ��� �������� �� ��������, �����-� ����, ������ ������� ����� NULL
	public function AuthUser($login,$password){
		$us=new mysqlSet("select * from clients where login=\"".SecStr($login,10)."\" and passw=\"".SecStr($password,10)."\" and is_blocked=0;");
		//echo htmlspecialchars("select * from clients where login=\"".SecStr($login,10)."\" and passw=\"".SecStr($password,10)."\" and is_blocked=0;");
		$nr=$us->getResult();
		$rc=$us->GetResultNumRows();
		if($rc>=1){
			$f =  mysqli_fetch_array($nr);
			$this->SetProfile($f); //echo 'qqq ';die();
			$this->err_code=0;
			return true;
		}else{
			$this->SetProfile(NULL);
			$this->err_code=1;
		    return false;
		}
	}
	
	//������������� ������� �� ������ ���� ������
	protected function SetProfile($record){
		unset($this->profile);
		$this->profile=$record;
	}
	
	//���������� ������� �������
	public function GetProfile(){
		return $this->profile;
	}
	
	
	//�������� ������ �����
	public function AddProfile($params){
		//���� �������� - �� �������� ������� � ������� ���
		$params['passw']=md5($params['passw']);
		$code=$this->user_item->Add($params);
		if($code===false){
			$this->SetProfile(NULL);
			$this->err_code=5;
			return false;
		}else{
			$profile=$this->user_item->GetItemById($code);
			$this->SetProfile($profile);
			return $this->profile;
		}
		return false;
	}
	
	//������� ������� �����
	public function EditProfile($login,$password,$params){
		$password=md5($password);
		//������� ���������� �������
		if($this->AuthUser($login,$password)){
			if(isset($params['passw'])) $params['passw']=md5($params['passw']);
			$res=$this->user_item->Edit($this->profile['id'],$params);
			if($res==true){
				//�������� ����� ������� �� ����
				$profile=$this->user_item->GetItemById($this->profile['id']);
				$this->SetProfile($profile);
				return true;
			}else{
				$this->err_code=6;
				return false;
			}
		}else $this->err_code=7;
		return false;
	}
	
	//������� ������� �����
	public function DelProfile($login,$password){
		//�������� �������
		
		$password=md5($password);
		if($this->AuthUser($login,$password)){
			$this->user_item->Del($this->profile['id']);
			$this->SetProfile(NULL);
			$this->DeAuthorize();
			return true;
		}else $this->err_code=15;
		return false;
	}
	
	//����� ������ �� ��������� ������
	public function ChangePassLogin($params){
		//���� ������� ����� �� ������
		$profile=$this->user_item->GetUserByLogin($params);
		if($profile!==NULL){
			//������������ �������
			$this->ChangePassProfile($profile);
		}
	}
	
	
	//����� ������ �� ��������� ������
	public function ChangePassEmail($params){
		$profiles=$this->user_item->GetUsersByEmail($params,$cou);
		if($profiles!==NULL){
			//������������ �������
			for($i=0;$i<$cou;$i++){
				$f=mysqli_fetch_array($profiles);
				$this->ChangePassProfile($f);
			}
		}
	}
	
	
	//��������� ������ ������ ��� ������� ����� � �������� ��� �� ������
	protected function ChangePassProfile($params){
		if($params!==NULL){
			$new_pass=substr(md5(time().$params['login'].time()),0,6);
			//�������� ������ � �������
			$upd=Array(); $upd['passw']=md5($new_pass);
			$this->user_item->Edit($params['id'],$upd);
			
			$rf=new ResFile(ABSPATH.'cnf/resources.txt');
			//�������� ������
			$addr=stripslashes($params['email']);
			$topic=$rf->GetValue('restore.php','mail_topic',$params['lang_id']);
			$text=$rf->GetValue('restore.php','mail_text',$params['lang_id']).' '.$new_pass;
			
			//echo "<pre>".htmlspecialchars($topic.' '.$text)."</pre>";
			//���������� ������ �� �����
			@mail($addr,$topic,$text);
		}
	}
	
	
	public function GetErrorCode(){
		return $this->err_code;
	}
	
	//����� ��������� �� ������ �� ���� ������
	public function ShowError($err_code){
		if($this->show_errors) return $this->err_mess[$err_code];
		else return '';
	}
	
	//��������� ����� ������ ������
	public function SetShowErrors($flag){
		$this->show_errors=$flag;
	}
}
?>