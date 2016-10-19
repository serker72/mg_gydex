<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/authuser.php');
require_once('classes/program_group.php');
require_once('classes/program_item.php');

$lg=new LangGroup();
//��������� ����� ����
require_once('inc/lang_define.php');

 

$au=new AuthUser();
//�������� �����������
$profile=$au->Auth();

if($profile!==NULL){
	//������� ��������������� ������, ���� ������������
	header("Location: /index.php");
	die();
}

$rf=new ResFile(ABSPATH.'cnf/resources.txt');
 

//����� �� �������
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

//�������� �����
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//�������� �����
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

$current_mid=-1;

//������ � �������
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//������ � ����� ����
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//����� �������
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');



//���������
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);


 




$content='';
if(!isset($_POST['doFind'])&&!isset($_POST['doRestore'])){
	//����� �������������� ������
	//���� ������ ��� ������	
	
	
	$sm1=new SmartyAdm;
	
	
	$content=$sm1->fetch('chp/restore1.html');
}elseif(isset($_POST['doFind'])&&isset($_POST['email'])){
	
	if((strlen($_POST['email'])==0)){
		$content='<h1>��� �������������� ������ ������� �����!</h1>';	
	}else{
		 
		
		$_pg=new ProgramGroup;
		
		$content=$_pg->FindAccessPrograms(SecStr($_POST['email']),   'chp/restore2.html', $debug=DEBUG_REDIRECT);
		
		//$content=$sm1->fetch('chp/restore2.html');
	}
	
}elseif(isset($_POST['doRestore'])){
	if((strlen($_POST['email'])==0)){
		$content='<h1>��� �������������� ������ ������� �����!</h1>';	
	}elseif(strlen($_POST['new_password'])<6){
		$content='<h1>��������� ���� ������!</h1>';	

	}else{
		
		$email=SecStr($_POST['email']);
		$new_password=$_POST['new_password'];
		$program_id=abs((int)$_POST['program_id']);
		
		$program_ids=array(); 
		foreach($_POST as $k=>$v) if(eregi("program_id_", $k)) {
			$program_ids[]=abs((int)$v);
		}
		
		
		
		
		$_pi=new ProgramItem;
		$success_changes=0; $mail_txt='';
		
		foreach($program_ids as $program_id){
			$program=$_pi->GetItemById($program_id);
			
			if($program===false){
				//echo 'zzzzzzzzzzz';
				$sm1=new SmartyAdm;
				$content=$sm1->fetch('chp/restore1_error.html');
				break;
			}else{
				if(DEBUG_REDIRECT) $debug_prefix='debug_';
				
				$connection=new MySQLi(ProgramHostName, ProgramUserName, ProgramPassword, $program[$debug_prefix.'database_name']);
				$connection->query('set names cp1251');
				
				$query='select * from user where is_active=1 and email_s="'.$email.'" and email_s<>""  ';
				
				$result=$connection->query($query);
				$rec_no=$result->num_rows;
				
			 
				if($rec_no==0){
					$sm1=new SmartyAdm;
					$content=$sm1->fetch('chp/restore1_error.html');
					break;
						
				}else{
					$f=$result->fetch_array();
					
					$new_password_md5=md5($new_password);
					$change_password_confirm=md5( time().$f['login'].$new_password.time() );
					
					
					$params=array();
					$params['new_password']=$new_password_md5;
					$params['change_password_confirm']=$change_password_confirm;
					$params['change_wait']=1;
					
					 
					$_params=array(); foreach($params as $k=>$v) $_params[]=' '.$k.'="'.$v.'" ';
					
					$sql='update user set  ';
					$sql.=implode(', ', $_params);
					$sql.=' where id="'.$f['id'].'"';
					
					$connection->query($sql);
					
					//echo $sql;
					
					$mail_txt.="
						<p>�� �������� ����� ������ ������ � ��������� ".$program['name']."</p>
						
						<p>��� ����� ������: ".$new_password."</p>
						
						<p>��� ������������ ����� ������ �������� �� ������: <a href=\"".SITEURL.'/restore_confirm.php?confirm='.$change_password_confirm."&program_id=".$program_id."\">".SITEURL.'/restore_confirm.php?confirm='.$change_password_confirm."&program_id=".$program_id."</a></p>
						<p></p>
						";
						
						//var_dump( $rf);
					
					//var_dump($f);
						$success_changes++;
					}
				}
		}
		if($success_changes>0){
			//��������� ������
			$mail_txt="<html><body>
			".$mail_txt."
			<p>���� �� �� ������ ������ ������, �� �������������� ������ ���������.</p>
						<p>�������.</p>
						</body></html>
			</body></html>";
			
			//echo $mail_txt;
						
			
			@mail($email,'������������� ����� ������',$mail_txt,"From: \"no-reply@gydex.ru\" <no-reply@gydex.ru>\n"."Reply-To: no-reply@gydex.ru\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
						
			 @usleep(50);
				
			  $sm1=new SmartyAdm;
			  
			  $content=$sm1->fetch('chp/restore3.html');
		}
	}
	
}
		
		

$sm1=new SmartyAdm;

 
// echo $content;
 $sm1->assign('mm', array('name'=>'�������������� �������� ������'));
 $sm1->assign('content', $content.'<br><br>
<br>
<br>
<br>
<br>
<br>
');
 $sm1->display('razd/page_simple.html');
 
//������ ���
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//������ � �������
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//������ � ������ ��������
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');

//������ � ����� ����
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);

?>
