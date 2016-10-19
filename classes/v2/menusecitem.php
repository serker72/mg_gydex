<?
//require_once('authuser.php');

class MenuSecItem extends MenuItem{
	public function DeployItem($current_id=0){
		$arr=NULL;
		
		 
		
		$rights_man=new DistrRightsManager;
		
		$has=$rights_man->CheckAccess($this->_auth_result['login'],  $this->_auth_result['passw'], 'r', $this->object_id) ;
		 
		 if(($this->module_constant!="")) {
		 	  $has=$has&&(constant($this->module_constant)==1);
		 }
		
		 
		 
		if( $has ){
			 
			$arr=$this->CodeItem($current_id);	
		}
		
		return $arr;
	}

}
?>