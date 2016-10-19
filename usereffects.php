<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/authuser.php');

if((!HAS_PRICE)&&(!HAS_BASKET)){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//��������� ����� ����
require_once('inc/lang_define.php');

$au=new AuthUser(); $backurl=getenv('HTTP_REFERER');

/*foreach($_POST as $k=>$v){
		echo "$k=>$v<br>";
	}
foreach($_SESSION['abyr'] as $k=>$v){
		echo "$k=><br>";
		foreach($v as $kk=>$vv) echo "&nbsp;&nbsp;$kk=>$vv<br>";
	}*/

//������������
if(isset($_GET['doOut'])){
		$au->DeAuthorize();
		header('Location: '.$backurl);
		die();
}

//�����������
if(isset($_POST['doLog'])){
	$login=$_POST['login'];
	$passw=md5(($_POST['passw']));
	if(isset($_POST['rem_me'])) $rem=true; else $rem=false;
	//echo htmlspecialchars($login);
	
	//���������� ����� �� �����
	$au->Authorize($login,$passw,$rem);
	
	header('Location: '.$backurl);
	die();
}


//����������� ������ �����
if(isset($_POST['doNew'])){
	
	if(!isset($_SESSION['abyr'])){
		header('Location: /index.php');
		die();
	}
	
	
	$err_code=0; $tryagain=0;
	
	$params=Array();
	if(isset($_POST[$_SESSION['abyr']['login']['name']])) $params['login']=SecStr($_POST[$_SESSION['abyr']['login']['name']],10);
	else $params['login']='';
	$_SESSION['abyr']['login']['value']=$params['login'];
	
	if(isset($_POST[$_SESSION['abyr']['passw']['name']])) $params['passw']=$_POST[$_SESSION['abyr']['passw']['name']];//SecStr($_POST[$_SESSION['abyr']['passw']['name']],10);
	else $params['passw']='';
	$_SESSION['abyr']['passw']['value']=$params['passw'];
	
	if(isset($_POST[$_SESSION['abyr']['username']['name']])) $params['username']=SecStr($_POST[$_SESSION['abyr']['username']['name']],10);
	else $params['username']='';
	$_SESSION['abyr']['username']['value']=$params['username'];
	
	if(isset($_POST[$_SESSION['abyr']['address']['name']])) $params['address']=SecStr($_POST[$_SESSION['abyr']['address']['name']],10);
	else $params['address']='';
	$_SESSION['abyr']['address']['value']=$params['address'];
	
	if(isset($_POST[$_SESSION['abyr']['email']['name']])) $params['email']=SecStr($_POST[$_SESSION['abyr']['email']['name']],10);
	else $params['email']='';
	$_SESSION['abyr']['email']['value']=$params['email'];
	
	if(isset($_POST[$_SESSION['abyr']['phone']['name']])) $params['phone']=SecStr($_POST[$_SESSION['abyr']['phone']['name']],10);
	else $params['phone']='';
	$_SESSION['abyr']['phone']['value']=$params['phone'];
	
	if(isset($_POST[$_SESSION['abyr']['is_mailed']['name']])) $params['is_mailed']=1;
	else $params['is_mailed']=0;
	$_SESSION['abyr']['is_mailed']['value']=$params['is_mailed'];
	
	$params['lang_id']=$lang;
	
	//��������� ��� ��������
	//�� ������: �����, ������������
	if(strlen($params['login'])>=1){
		$cou=$au->user_item->CheckLogin($params['login'],NULL);
		
		//echo "<strong>$cou</strong>";
		if($cou>0){
			$err_code=9;
			$tryagain=1;
		}else{
			//$err_code=0;
			//$tryagain=0;
		}
	}else{
		$err_code=8;
		$tryagain=1;
	}
	
	//�� ������: ����� 4 �������
	if(strlen($params['passw'])>=4){
		//$err_code=0;
		//$tryagain=0;
	}else{
		$err_code=10;
		$tryagain=1;
	}
	
	//�� ������: �����, ���������
	if(strlen($params['email'])>=6){
		if(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,5})$', $params['email'])){
			//$err_code=0;
			//$tryagain=0;
		}else{
			$err_code=11;
			$tryagain=1;
		}
	}else{
		$err_code=11;
		$tryagain=1;
	}
	
	if(($err_code==0)&&($tryagain==0)){
		//��� �������� ��������, �������� ����� � ����
		$pro=$au->AddProfile($params);
		if($pro!==false){
			//���������� ����� �� �����
			$au->Authorize($_POST[$_SESSION['abyr']['login']['name']],md5($_POST[$_SESSION['abyr']['passw']['name']]));
			//echo htmlspecialchars($_POST[$_SESSION['abyr']['login']['name']]); die();
			unset($_SESSION['abyr']);
			header('Location: /index.php');
			die();
		}
	}else{
		//������, ���������� ����� �� ��������� ����
		
		/*foreach($_SESSION['abyr'] as $k=>$v){
		echo "$k=><br>";
		foreach($v as $kk=>$vv) echo "&nbsp;&nbsp;$kk=>$vv<br>";
	}*/
		
		header('Location: /profile.php?tryagain=1&err_code='.$err_code);
		die();
	}
}


//������ �����
if(isset($_POST['doEdit'])){
	
	if(!isset($_SESSION['abyr'])){
		header('Location: /index.php');
		die();
	}
	$err_code=0; $tryagain=0; $params=Array();
	
	//�������� ��������������
	//���� ������� - �� ������ �����=������ �� �������, ������ ������=������ �� �����
	//���� ���������� - �� ������ ������������ �� �������!
	//echo 'in aut ';
	$profile=$au->Auth();
	if($profile===NULL){
		//�������� �� �������
		header('Location: /index.php');
		die();
	}
	$old_login=htmlspecialchars_decode($profile['login']); //echo htmlspecialchars(" $old_login ").'<p>';
	if(isset($_POST[$_SESSION['abyr']['passw']['name']])) $old_passw=$_POST[$_SESSION['abyr']['passw']['name']];//SecStr($_POST[$_SESSION['abyr']['passw']['name']],10); 
	else $old_passw=''; //$params['passw']='';
	$_SESSION['abyr']['passw']['value']='';//$params['passw'];
	
	
	
	//smena logina
	if(isset($_POST['doChl'])){
		if(isset($_POST[$_SESSION['abyr']['login']['name']])) $new_login=SecStr($_POST[$_SESSION['abyr']['login']['name']],10);
		else $new_login='';
		$_SESSION['abyr']['login']['value']=$new_login;
	}
	
	
	//smena parolya
	if(isset($_POST['doChp'])){
		if(isset($_POST[$_SESSION['abyr']['newpassw']['name']])) $newpassw1=$_POST[$_SESSION['abyr']['newpassw']['name']];//SecStr($_POST[$_SESSION['abyr']['newpassw']['name']],10);
		else $newpassw1='';
		$_SESSION['abyr']['newpassw']['value']=$newpassw1;	
		
		if(isset($_POST[$_SESSION['abyr']['newpassw_c']['name']])) $newpassw2=SecStr($_POST[$_SESSION['abyr']['newpassw_c']['name']],10);
		else $newpassw2='';
		$_SESSION['abyr']['newpassw_c']['value']=$newpassw2;	
		
	}
	
	
	if(isset($_POST[$_SESSION['abyr']['username']['name']])) $params['username']=SecStr($_POST[$_SESSION['abyr']['username']['name']],10);
	else $params['username']='';
	$_SESSION['abyr']['username']['value']=$params['username'];
	
	if(isset($_POST[$_SESSION['abyr']['address']['name']])) $params['address']=SecStr($_POST[$_SESSION['abyr']['address']['name']],10);
	else $params['address']='';
	$_SESSION['abyr']['address']['value']=$params['address'];
	
	if(isset($_POST[$_SESSION['abyr']['email']['name']])) $params['email']=SecStr($_POST[$_SESSION['abyr']['email']['name']],10);
	else $params['email']='';
	$_SESSION['abyr']['email']['value']=$params['email'];
	
	if(isset($_POST[$_SESSION['abyr']['phone']['name']])) $params['phone']=SecStr($_POST[$_SESSION['abyr']['phone']['name']],10);
	else $params['phone']='';
	$_SESSION['abyr']['phone']['value']=$params['phone'];
	
	if(isset($_POST[$_SESSION['abyr']['is_mailed']['name']])) $params['is_mailed']=1;
	else $params['is_mailed']=0;
	$_SESSION['abyr']['is_mailed']['value']=$params['is_mailed'];
	
	if(isset($_POST[$_SESSION['abyr']['doChl']['name']])) $_SESSION['abyr']['doChl']['value']='1';
	else  $_SESSION['abyr']['doChl']['value']='0';
	
	if(isset($_POST[$_SESSION['abyr']['doChp']['name']])) $_SESSION['abyr']['doChp']['value']='1';
	else  $_SESSION['abyr']['doChp']['value']='0';
	
	$params['lang_id']=$lang;
	
	//�������� ��������
	
	//���� ����� ������ - �� �������� �� ������ ������
	if(isset($_POST['doChl'])){
		//�����, ������������
		if(strlen($new_login)>=1){
			$cou=$au->user_item->CheckLogin($new_login,$profile['id']);
			
			//echo "<strong>$cou</strong>";
			if($cou>0){
				$err_code=9;
				$tryagain=1;
			}else{
				//$err_code=0;
				//$tryagain=0;
				$params['login']=$new_login;
			}
		}else{
			$err_code=8;
			$tryagain=1;
		}
	}
	
	//�� ������� ������: ����� 4 �������
	if(strlen($old_passw)>=4){
		//$err_code=0;
		//$tryagain=0;
	}else{
		$err_code=10;
		$tryagain=1;
	}
	
	//���� ����� ������, �� �������� ����� �������
	//����� (>=4), ���������� ������ � ��������������
	if(isset($_POST['doChp'])){
		if(strlen($newpassw1)>=4){
			//$err_code=0;
			//$tryagain=0;
		}else{
			$err_code=10;
			$tryagain=1;
		}
		
		if($newpassw1==$newpassw2){
		
		}else{
			$err_code=13;
			$tryagain=1;
		}
		
		if(($err_code==0)&&($tryagain==0)) $params['passw']=$newpassw1;
	}
	
	//�� ������: �����, ���������
	if(strlen($params['email'])>=6){
		if(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,5})$', $params['email'])){
			//$err_code=0;
			//$tryagain=0;
		}else{
			$err_code=11;
			$tryagain=1;
		}
	}else{
		$err_code=11;
		$tryagain=1;
	}
	
	
	if(($err_code==0)&&($tryagain==0)){
		//��� �������� ��������, �������� ����� � ����
		/*echo "$old_login $old_passw<br>"; 
		foreach($params as $k=>$v){
			echo "$k = $v<br>";
		}*/
		
		$pro=$au->EditProfile($old_login,$old_passw,$params);
		if($pro!==false){
			//���������� ����� �� �����
			if(isset($_POST['doChl'])) $log=$new_login;
			else $log=$old_login;
			
			if(isset($_POST['doChp'])) $pass=$newpassw1;
			else $pass=$old_passw;
			
			$au->Authorize($log,md5($pass));
			unset($_SESSION['abyr']);
			header('Location: /index.php');
			die();
		}else{
			//�� ���������� �������������� ��� �������������� �������
			//echo  htmlspecialchars(" $old_login $old_passw ").'<p>';
			header('Location: /profile.php?tryagain=1&err_code=14');
			die();
		}
	}else{
		//������, ���������� ����� �� ��������� ����
		/*foreach($_SESSION['abyr'] as $k=>$v){
		echo "$k=><br>";
		foreach($v as $kk=>$vv) echo "&nbsp;&nbsp;$kk=>$vv<br>";
	}*/
		header('Location: /profile.php?tryagain=1&err_code='.$err_code);
		die();
	}
	
}

//�������� ������� �����
if(isset($_POST['doDel'])){
	
	if(!isset($_SESSION['abyr'])){
		header('Location: /index.php');
		die();
	}
	$err_code=0; $tryagain=0; $params=Array();
	//�������� ��������������
	//���� ������� - �� ������ �����=������ �� �������, ������ ������=������ �� �����
	//���� ���������� - �� ������ ������������ �� �������!
	$profile=$au->Auth();
	if($profile===NULL){
		//�������� �� �������
		header('Location: /index.php');
		die();
	}
	$old_login=htmlspecialchars_decode($profile['login']); 
	if(isset($_POST[$_SESSION['abyr']['passw']['name']])) $old_passw=$_POST[$_SESSION['abyr']['passw']['name']];//SecStr($_POST[$_SESSION['abyr']['passw']['name']],10); 
	else $old_passw=''; 
	
	
	$_SESSION['abyr']['passw']['value']='';//$params['passw'];
	
	//smena logina
	if(isset($_POST['doChl'])){
		if(isset($_POST[$_SESSION['abyr']['login']['name']])) $new_login=SecStr($_POST[$_SESSION['abyr']['login']['name']],10);
		else $new_login='';
		$_SESSION['abyr']['login']['value']=$new_login;
	}
	
	
	//smena parolya
	if(isset($_POST['doChp'])){
		if(isset($_POST[$_SESSION['abyr']['newpassw']['name']])) $newpassw1=$_POST[$_SESSION['abyr']['newpassw']['name']];//SecStr($_POST[$_SESSION['abyr']['newpassw']['name']],10);
		else $newpassw1='';
		$_SESSION['abyr']['newpassw']['value']=$newpassw1;	
		
		if(isset($_POST[$_SESSION['abyr']['newpassw_c']['name']])) $newpassw2=SecStr($_POST[$_SESSION['abyr']['newpassw_c']['name']],10);
		else $newpassw2='';
		$_SESSION['abyr']['newpassw_c']['value']=$newpassw2;	
		
	}
	
	
	if(isset($_POST[$_SESSION['abyr']['username']['name']])) $params['username']=SecStr($_POST[$_SESSION['abyr']['username']['name']],10);
	else $params['username']='';
	$_SESSION['abyr']['username']['value']=$params['username'];
	
	if(isset($_POST[$_SESSION['abyr']['address']['name']])) $params['address']=SecStr($_POST[$_SESSION['abyr']['address']['name']],10);
	else $params['address']='';
	$_SESSION['abyr']['address']['value']=$params['address'];
	
	if(isset($_POST[$_SESSION['abyr']['email']['name']])) $params['email']=SecStr($_POST[$_SESSION['abyr']['email']['name']],10);
	else $params['email']='';
	$_SESSION['abyr']['email']['value']=$params['email'];
	
	if(isset($_POST[$_SESSION['abyr']['phone']['name']])) $params['phone']=SecStr($_POST[$_SESSION['abyr']['phone']['name']],10);
	else $params['phone']='';
	$_SESSION['abyr']['phone']['value']=$params['phone'];
	
	if(isset($_POST[$_SESSION['abyr']['is_mailed']['name']])) $params['is_mailed']=1;
	else $params['is_mailed']=0;
	$_SESSION['abyr']['is_mailed']['value']=$params['is_mailed'];
	
	if(isset($_POST[$_SESSION['abyr']['doChl']['name']])) $_SESSION['abyr']['doChl']['value']='1';
	else  $_SESSION['abyr']['doChl']['value']='0';
	
	if(isset($_POST[$_SESSION['abyr']['doChp']['name']])) $_SESSION['abyr']['doChp']['value']='1';
	else  $_SESSION['abyr']['doChp']['value']='0';
	
	$params['lang_id']=$lang;
	
	$res=$au->DelProfile($old_login,$old_passw);
	if($res==true) {
		//������� ������������, ��������� �� �������
		unset($_SESSION['abyr']);
		header('Location: /index.php');
		die();
	}else{
		//�������� ����������� ��� ��������
		header('Location: /profile.php?tryagain=1&err_code=15');
		die();
	}
}


header('Location: /index.php');
die();

?>