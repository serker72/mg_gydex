<?
require_once('abstractitem.php');

//сессия пользователя
class ClaimCreationSession extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='claim_creation_session';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='mid';
		
	}
	//подсчет таких логинов во всех сессиях, кроме текущей 
	public function CountLogins($login,$sid){
		$this->DelSession($user_id);
		
		$set=new mysqlSet('select count(*) from '.$this->tablename.' where login="'.$login.'" and SID<>"'.$sid.'"');
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$f=mysqli_fetch_array($rs);	
		
		return $f[0];
	}
	
	
	public function AddSession(  $login){
		$this->Add(array(  'login'=>$login, 'pdate'=>time(), 'SID'=>session_id(), 'ip'=>getenv('REMOTE_ADDR')));
	}
	
	
	//удалим сессии
	public function DelSession($SID){
		new NonSet('delete from '.$this->tablename.' where SID="'.$SID.'"');
	}
	
	
	
	//очистка старых сессий
	public function ClearOldSessions($time_interval=900){
		//если никто не заходил более двух часов - очистить таблицу сессий!
		$etalon=time()-(2*60*60);
		$set=new mysqlSet('select count(*) from '.$this->tablename.' where pdate>='.$etalon);
		$rs=$set->getResult(); 
		$f=mysqli_fetch_array($rs);
		if($f[0]==0){
			new NonSet('truncate '.$this->tablename);	
		}
		
		
		//очистим сессии, не обновляемые 15 минут и более
		new NonSet('delete from '.$this->tablename.' where pdate<'.(time()-$time_interval).'');	
	}
	
}
?>