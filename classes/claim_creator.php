<?
require_once('claim_crsession.php');

class ClaimCreator{
	public $ses;
	protected $tablename;
	protected $digits=8;
	protected $begin;
	
	function __construct(){
		$this->ses=new ClaimCreationSession;	
		$this->tablename='claim';
		$this->begin='T';
	}
	
	
	//функция возвращает гаранитрованно ближайший свободный логин и бронирует его в сессии
	public function GenLogin(){
		//login - code
		
		$set=new mysqlset('select max(code) from '.$this->tablename.' where code REGEXP "^'.$this->begin.'[0-9]+"');
		
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		
		if($rc>0){
			$f=mysqli_fetch_array($rs);	
			
			//$f1=mysqli_fetch_array($rs1);	
			
			//echo $f[0];
			eregi($this->begin."([[:digit:]]{".$this->digits."})",$f[0],$regs);
			//print_r($regs);
			
			
			$number=(int)$regs[1];
			//print_r($regs); die();
			$number++;
			
			$test_login=$this->begin.sprintf("%0".$this->digits."d",$number);
			
			while($this->ses->CountLogins($test_login,  session_id())>0){
				$number++;
				$test_login=$this->begin.sprintf("%0".$this->digits."d",$number);
			}
			
			$this->ses->AddSession($test_login);
			$login=$test_login;
		}else{
			
			//$f=mysqli_fetch_array($rs);	
			$login=$this->begin.sprintf("%0".$this->digits."d",1);
			
			$this->ses->AddSession( $login);
		}
		
		
		return $login;
	}
	
	
}
?>