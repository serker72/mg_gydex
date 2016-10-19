<?
require_once('global.php');
require_once('distr_useritem.php');
require_once('resoursefile.php');
require_once('authuser.php');

//класс для клиентской работы с юзером
class DiscrAuthUser extends AuthUser{
	public $user_item; //экз-р класса UserItem
	protected $profile=NULL; //структура-профиль юзера
	
	protected $sess_name; //имя сессии, где юзер
	protected $show_errors=true;
	protected $err_mess=Array();
	protected $templates=Array();
	protected $lang;
	protected $max_cookie_time=2419200; //время жизни кукисов
	
	
	public function __construct($sess_name='gydex_panel'){
		$this->init($sess_name);
	}
	
	//установка всех имен
	protected function init($sess_name){
		$this->sess_name=$sess_name;
		if(isset($_SESSION['lang'])){
			$this->lang=$_SESSION['lang'];
		}else $this->lang=LANG_CODE;
		if(!isset($_SESSION[$this->sess_name])) $_SESSION[$this->sess_name]=Array();
		
		$this->err_code=0;
		$this->user_item=new DistrUserItem();
		
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		$this->err_mess[1]=$rf->GetValue('profile.php','auth_error',$this->lang);//неверный логин/пароль';
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
		 
		$this->err_mess[12]=$rf->GetValue('profile.php','unknown_error',$this->lang); //ошибка есть, но код потерялиы
		
		$this->err_mess[13]=$rf->GetValue('profile.php','pass_not_confirm_error',$this->lang); //
		
		$this->err_mess[14]=$rf->GetValue('profile.php','incorrect_pass_error',$this->lang); //неверный текущий пароль при правке профиля
		$this->err_mess[15]=$rf->GetValue('profile.php','incorrect_pass_error',$this->lang); //неверный текущий пароль при правке профиля
	}
	
	//функция авторизации (авторизуем по логину, паролю)
	public function Authorize($login,$password,$rem_me=false){
		if($this->AuthUser($login, $password)){
			$_SESSION[$this->sess_name]['username']=$login;
			$_SESSION[$this->sess_name]['password']=$password;
			if($rem_me){
				@setcookie("user[0]", $login,time()+$this->max_cookie_time,'/');				
				@setcookie("user[1]", $password,time()+$this->max_cookie_time,'/');	
			}
			return true;
		}else return false;
	}
	
	//функция проверки логина-пароля
	//если все проверки пройдены, то возвращает тру, и обновляет профиль
	//если все проверки НЕ пройдены, возвр-т фолс, вместо профиля будет NULL
	public function AuthUser($login,$password){
		$us=new mysqlSet("select * from discr_users where login=\"".SecStr($login,10)."\" and passw=\"".SecStr($password,10)."\" and is_blocked=0;");
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
	
	
	//вывод сообщения об ошибке по коду ошибки
	public function ShowError($err_code){
		return $this->err_mess[$err_code];
		
	}
	
	
}
?>