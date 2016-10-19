<?

require_once('abstractgroup.php');
 

//  
class ProgramGroup extends AbstractGroup {
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='program';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	
	public function FindAccess($login, $password, $template, $debug=DEBUG_REDIRECT){
		$sm=new SmartyAj;
		
		$debug_prefix=''; if($debug) $debug_prefix='debug_';
		
		
		$progs=$this->GetItemsArr();
		
		
		$matched=array();
		if(strlen(trim($login))>0) foreach($progs as $k=>$program){
			$connection=new MySQLi(ProgramHostName, ProgramUserName, ProgramPassword, $program[$debug_prefix.'database_name']);
			$connection->query('set names cp1251');
			
			//
			//echo 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz';
			//echo $connection->error;
			
			$query='select id from user where is_active=1 and email_s="'.SecStr($login).'" and email_s<>"" and password="'.md5($password).'" ';
			//echo ProgramUserName;
			//echo $query.'<br>';
			
			//echo ProgramHostName. ProgramUserName. ProgramPassword. $program[$debug_prefix.'database_name'];
			
			$result=$connection->query($query);
			$rec_no=$result->num_rows;
			
			if($program['has_orgs']==0) { 
				
				if($rec_no>0){
					
						
					 	$program['org_id']=0;
						$matched[]=$program;
					 } 
			}else{
				//проверять организации	
				 
				
				$f=mysqli_fetch_array($result);
				 
				$query1='select s.id as org_id, s.full_name, so.name as opf_name
					from supplier as s 
					left join opf as so on so.id=s.opf_id
					inner join supplier_to_user as su on su.org_id=s.id
				where
					s.is_org=1 and s.is_active=1 and su.user_id="'.$f['id'].'" ';
					
				//echo $query1;	
				$result2=$connection->query($query1);
				$rec_no2=$result2->num_rows;	
				
				for($j=0; $j<$rec_no2; $j++){
					$g=mysqli_fetch_array($result2);
					
					$program['org_id']=$g['org_id'];
					$program['opf_name']=$g['opf_name'];
					$program['full_name']=$g['full_name'];
					
					$matched[]=$program;	
				}
				
			}
		
		}
		
		
		$sm->assign('matched', $matched);
		
		//print_r($matched);
		
		return $sm->fetch($template);	
		
	}
	
	
	//список программ, куда есть доступ пользователю с таким логином (для восстановления пароля)
	public function FindAccessPrograms($login,   $template, $debug=DEBUG_REDIRECT){
		$sm=new SmartyAdm;
		
		$debug_prefix=''; if($debug) $debug_prefix='debug_';
		
		
		$progs=$this->GetItemsArr();
		
		
		$matched=array();
		if(strlen(trim($login))>0) foreach($progs as $k=>$program){
			$connection=new MySQLi(ProgramHostName, ProgramUserName, ProgramPassword, $program[$debug_prefix.'database_name']);
			$connection->query('set names cp1251');
			
			$query='select id from user where is_active=1 and email_s="'.SecStr($login).'" and email_s<>""  ';
			//echo 'zzzzzzzzzzzzzzzzzzzzzzzzzz';
			
			//echo $connection->error;
			
			//echo ProgramHostName.', '.ProgramUserName.', '.ProgramPassword.', '.$program[$debug_prefix.'database_name'];
			
			$result=$connection->query($query);
			$rec_no=$result->num_rows;
			
				
				if($rec_no>0){
					
						$recs=$result->fetch_assoc();
			
		 				//var_dump($recs);
					
					 	$program['org_id']=0;
						
						//условие активности галочки в списке
						$program_is_active=true;
						
						//неактивна, если флаг активности программы в БД!=1 (==2,3)
						//И айди пользователя !=1,2
						if(($program['is_active']!=1)&&(!in_array($recs['id'], array(1,2)))) $program_is_active=$program_is_active&&false;
						 
						
						//причина неактивности
						$inactive_reason='';
						if($program['is_active']==2){
							$inactive_reason='Восстановление пароля в данной программе невозможно. Причина: Истек период оплаты программы. Пожалуйста, обратитесь к администраторам программы.';	
						}
						elseif($program['is_active']==3){
							$inactive_reason='Восстановление пароля в данной программе невозможно. Причина: Прекращено обслуживание данной программы. Пожалуйста, обратитесь к администраторам программы.';	
						}
						
						//получить список организаций, если они предусмотрены
						$orgs='';
						if($program['has_orgs']==1){
							$_orgs=array();
							$query1='select opf.name as opf_name, org.* from supplier as org left join opf  on opf.id=org.opf_id where org.is_org=1 and org.is_active=1  ';
							
							$result1=$connection->query($query1);
							$rec_no1=$result1->num_rows;
							for($i=0; $i<$rec_no1; $i++){
								$recs1=$result1->fetch_assoc();
								$_orgs[]=$recs1['opf_name'].' '.$recs1['full_name'];
							}
							
							$orgs=': '.implode(', ',$_orgs);
						}
						
						
						$program['program_is_active']=$program_is_active;
						$program['inactive_reason']=$inactive_reason;
						$program['orgs']=$orgs;
						
						
						$matched[]=$program;
					 } 
			 
		
		}
		
		$sm->assign('email', $login);
		$sm->assign('matched', $matched);
		
		//var_dump($matched);
		
		return $sm->fetch($template);	
		
	}
	
	
		//итемы в тегах option
	public function GetItemsArr(){
		$arr=array();
		$sql='select * from '.$this->tablename.' where is_active!=0 order by id asc';
		$set=new mysqlSet($sql);
		$tc=$set->GetResultNumRows();
		if($tc>0){
			$rs=$set->GetResult();
			for($i=0;$i<$tc;$i++){
				$f=mysqli_fetch_array($rs);
				
				$arr[]=$f; 
			}
		}
		return $arr;
	}
	
}
?>