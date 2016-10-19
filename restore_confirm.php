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
if(!isset($_GET['confirm'])||(strlen($_GET['confirm'])==0)){
	$content='
	<h1>������ ����� ������</h1>
	�� ����� ��� ������������� ��� ����� ������.
	
	';
}elseif(!isset($_GET['program_id'])||(strlen($_GET['program_id'])==0)){
	$content='
	<h1>������ ����� ������</h1>
	�������� ��������� ����� ������.
	
	';
}else{
	$program_id=abs((int)$_GET['program_id']);
	
	$_pi=new ProgramItem;
	$program=$_pi->GetItemById($program_id);
	
	if($program===false){
		$content='
	<h1>������ ����� ������</h1>
	�������� ��������� ����� ������.
	
	';
	}else{
		if($debug) $debug_prefix='debug_';
		
		$connection=new MySQLi(ProgramHostName, ProgramUserName, ProgramPassword, $program[$debug_prefix.'database_name']);
		$connection->query('set names cp1251');
		
		$query='select * from user where is_active=1 and change_wait=1 and change_password_confirm="'.SecStr($_GET['confirm']).'" and email_s<>""  ';
		
		$result=$connection->query($query);
		$rec_no=$result->num_rows;
		
	 
		if($rec_no==0){
			$content='
		<h1>������ ����� ������</h1>
		�������� ��� ������������� ��� ����� ������.
		';
		}else{
			
			$user=$result->fetch_array();
			$params=array('change_wait'=>0, 'password'=>$user['new_password']);
			
			$_params=array(); foreach($params as $k=>$v) $_params[]=' '.$k.'="'.$v.'" ';
				
			$sql='update user set  ';
			$sql.=implode(', ', $_params);
			$sql.=' where id="'.$user['id'].'"';
			
			//echo $sql;
			
			$connection->query($sql);
			
			$sql1='insert into action_log (user_subj_id, pdate, description, value, ip) values("'.$user['id'].'", "'.time().'", "����� ������", "������ ������ � ������� ����� �����/�������������� ������ ��������-�������", "'.getenv('HTTP_X_REAL_IP').'" )';
			//echo $sql1;
			
			$connection->query($sql1);
			
			
			$content='
		<h1>C���� ������</h1>
		������ ������� �������.<br />
		��� ����������� ������, ����������, ������������� �� ����� ������.
		';	
			
			/*$_ui->Edit($user['id'], array('change_wait'=>0, 'password'=>$user['new_password']));
		
		$log=new ActionLog;
		$log->PutEntry($user['id'], '����� ������',NULL,NULL,NULL,'������ ������ � ������� ����� �����/�������������� ������',NULL);*/
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
