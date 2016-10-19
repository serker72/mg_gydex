<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/smarty/SmartyAdm.class.php');

//���������������� �����������
require_once('inc/adm_header.php');

if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=ITEMS_PER_PAGE;
	}else $to_page = $_POST['to_page'];	
}else $to_page = $_GET['to_page'];	
$to_page=abs((int)$to_page);	


if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	if($kind==1){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				$r=new LangItem();
				$params=Array();
				if(isset($_POST[$lid.'_is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 9)) {
					$r->Edit($lid,$params);
				}else{
		header('Location: no_rights.php');
		  die();	
	}
				
			}
		}
	}
	
	if($kind==2){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//������� 
				
				$lid=(int)$val;
				
				$r=new LangItem();
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 9)){ 
					$r->Del($lid);
				}else{
		header('Location: no_rights.php');
		  die();	
	}
				
				
			}
		}
	}
	
	if(($kind==3)||($kind==4)){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				$r=new LangItem();
				$params=Array();
				if($kind==3) $params['is_shown']=1;
				else if($kind==4) $params['is_shown']=0;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 9)){ 
					$r->Edit($lid,$params);
				}else{
		header('Location: no_rights.php');
		  die();	
	}
			}
		}
	}
	
	header('Location: viewlangs.php?from='.$from.'&to_page='.$to_page);
	die();
}


//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'���������� ������� ����� - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//���� � ����������� �� ������� �������
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//�����-���
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);



//����������� �������
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;
 
 

$_context->AddContext(new ContextItem( 9, 'a', "", "�������� ����", "ed_lang.php?action=0", false , $global_profile  ));

 


$_context->AddContext(new ContextItem( "", '',  "", "�������", "viewlangs.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);


//������� ������
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('�����', 'index.php'));
$_bc->AddContext(new BcItem('���������� ������� �����', basename(__FILE__)));

 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);


$smarty->display('page_top.html');
?>




	<?
	
	$l=new LangGroup();
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 9)) 
	echo $l->GetItems(0,$from,$to_page);
	else echo NO_RIGHTS;
	
	?>
	
	
	
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>