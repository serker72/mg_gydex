<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/calendar.php');
require_once('../classes/newsgroup.php');
require_once('../classes/v2/newsgroup_new.php');

if(!HAS_NEWS){
	header("Location: index.php");
	die();
}

//���������������� �����������
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 18)) {}
else{
	header('Location: no_rights.php');
	die();
}


$razd=new MmenuItem();
$og=new NewsGroupNew;

if(!isset($_GET['id'])){
	if(!isset($_POST['id'])){
		header("Location: index.php");
		die();
	}else $id = $_POST['id'];	
}else $id = $_GET['id'];	
$id=abs((int)$id);	

 

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


if(!isset($_GET['sortmode'])){
	if(!isset($_POST['sortmode'])){
		$sortmode=0;
	}else $sortmode = $_POST['sortmode'];	
}else $sortmode = $_GET['sortmode'];	
$sortmode=abs((int)$sortmode);	
if($sortmode>1) $sortmode=0;


if($sortmode==1){
	//������� ����, ���� ��� - �� ���� �������
	if(!isset($_GET['pdate']))
		if(!isset($_POST['pdate'])) $pdate=date("Y-m-d");
		else $pdate = $_POST['pdate'];		
	else $pdate = $_GET['pdate'];	
	$pdate=SecStr($pdate);
}



if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	if($kind==1){
		//��������� ����
		//������� ������ ���� ������
		$langs=Array();
		$langgr=new LangGroup();
		$langs=$langgr->GetLangsIdList();
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
												
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 18)) {

				  $r=new NewsItem();
				  $lparams=Array();
				  //�������� ��������� �������
				  foreach($langs as $lk=>$lv){
					  if(isset($_POST[$lid.'_'.$lv.'_is_shown'])) $r->ToggleVisibleLang($lid, $lv, 1);
					  else $r->ToggleVisibleLang($lid, $lv, 0);
				  }
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
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 18)) {
					$r=new NewsItem();
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
				    die();	
				}
			}
		}
	}
	
	
	
	
	if(($kind==4)||($kind==5)){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 18)) {
				
					$r=new NewsItem();
					if($kind==4) $r->ToggleVisible($lid,1);
					if($kind==5) $r->ToggleVisible($lid,0);
				}else{
					header('Location: no_rights.php');
				    die();		
				}
			}
		}
	}
	
	// die();
	if($sortmode==1) header('Location: viewnews.php?&from='.$from.'&to_page='.$to_page.'&sortmode=1&pdate='.$pdate.'&id='.$id);
	else header('Location: viewnews.php?&from='.$from.'&to_page='.$to_page.'&sortmode='.$sortmode.'&id='.$id);
	die();
}
	
require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'������ �������� - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=21;
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



$_context->AddContext(new ContextItem( 18, 'a', "", "������� �������", "ed_news.php?action=0&mid=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 18, 'r', "", "��� �������", "viewnews.php?sortmode=0&id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'w', "", "������������� ������", "ed_razd.php?action=1&id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "������ �����������", "razds.php?id=".$id, false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "������� �������� �������", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( "", '',  "", "�������", "viewnews.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);






//������� ������
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
$_razd_bc=$_r2->DrawNavigArr($id, LANG_CODE);
$_bc=new Bc();
$_bc->AddContext(new BcItem('�����', 'index.php'));
$_bc->AddContext(new BcItem('������� � �������', 'razds.php'));

foreach($_razd_bc as $item) $_bc->AddContext(new BcItem($item['name'], $item['url']));

$_bc->AddContext(new BcItem('������ ��������', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);



$smarty->display('page_top.html');
	
?>

 


	 
		
		
        
        <?
        $c= new Calendar();
			if(!isset($pdate)) echo $c->Draw(date('Y-m-d'),'viewnews.php','pdate','&sortmode=1&from='.$from.'&id='.$id,date('Y-m-d'),0);	
			else echo $c->Draw($pdate,'viewnews.php','pdate','&sortmode=1&from='.$from.'&id='.$id,$pdate,0);	
		?>
        
        
		<h3><?
			//��������� ���������
			switch($sortmode){
				case 0:
					echo '��� �������';
					$params=NULL;
				break;
				case 1:
					echo '������� �� ����: '.DateFromYmd($pdate);
					$params=Array(); $params['pdate']=$pdate;
				break;
				
				
			};
		?></h3>
        
       
        
		<?
		
		 
		echo $og->GetItemsById($id,$sortmode,$params,$from,$to_page, 'news/items.html');
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