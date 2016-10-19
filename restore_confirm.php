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
//определим какой язык
require_once('inc/lang_define.php');

 

$au=new AuthUser();
//проверим авторизацию
$profile=$au->Auth();

if($profile!==NULL){
	//незачем восстанавливать пароль, если авторизованы
	header("Location: /index.php");
	die();
}

$rf=new ResFile(ABSPATH.'cnf/resources.txt');
 

//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));

$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

$current_mid=-1;

//работа с хедером
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//работа с гориз меню
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//левая колонка
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');



//навигация
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);


 




$content='';
if(!isset($_GET['confirm'])||(strlen($_GET['confirm'])==0)){
	$content='
	<h1>Ошибка смены пароля</h1>
	Не задан код подтверждения для смены пароля.
	
	';
}elseif(!isset($_GET['program_id'])||(strlen($_GET['program_id'])==0)){
	$content='
	<h1>Ошибка смены пароля</h1>
	Неверные параметры смены пароля.
	
	';
}else{
	$program_id=abs((int)$_GET['program_id']);
	
	$_pi=new ProgramItem;
	$program=$_pi->GetItemById($program_id);
	
	if($program===false){
		$content='
	<h1>Ошибка смены пароля</h1>
	Неверные параметры смены пароля.
	
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
		<h1>Ошибка смены пароля</h1>
		Неверный код подтверждения для смены пароля.
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
			
			$sql1='insert into action_log (user_subj_id, pdate, description, value, ip) values("'.$user['id'].'", "'.time().'", "смена пароля", "сменил пароль с помощью формы смены/восстановления пароля интернет-портала", "'.getenv('HTTP_X_REAL_IP').'" )';
			//echo $sql1;
			
			$connection->query($sql1);
			
			
			$content='
		<h1>Cмена пароля</h1>
		Пароль успешно изменен.<br />
		Для продолжения работы, пожалуйста, авторизуйтесь на сайте заново.
		';	
			
			/*$_ui->Edit($user['id'], array('change_wait'=>0, 'password'=>$user['new_password']));
		
		$log=new ActionLog;
		$log->PutEntry($user['id'], 'смена пароля',NULL,NULL,NULL,'сменил пароль с помощью формы смены/восстановления пароля',NULL);*/
		}
		
	}
}
		
		

$sm1=new SmartyAdm;

 
// echo $content;
 $sm1->assign('mm', array('name'=>'Восстановление забытого пароля'));
 $sm1->assign('content', $content.'<br><br>
<br>
<br>
<br>
<br>
<br>
');
 $sm1->display('razd/page_simple.html');
 
 
//нижний код
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//работа с футером
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//работа с правой колонкой
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');

//работа с гориз меню
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);

?>
