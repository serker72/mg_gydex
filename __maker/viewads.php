<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/bansgroup.php');
require_once('../classes/banitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/v2/bansgroup_new.php');

//���������������� �����������
require_once('inc/adm_header.php');

$razd=new MmenuItem();

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
		//������� ������ ���� ������
		$langs=Array();
		$langgr=new LangGroup();
		$langs=$langgr->GetLangsIdList();
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
												
				
				

				
				$r=new BanItem();
				
				//echo $_POST["kind_id_".$lid];
				//$params=Array();
				//$params["kind_id"]=abs((int)$_POST["kind_id_".$lid]);
				//$r->Edit($lid,$params);
				
		//		$lparams=Array();
				//�������� ��������� �������
				foreach($langs as $lk=>$lv){
					if(isset($_POST[$lid.'_'.$lv.'_is_shown'])) $r->ToggleVisibleLang($lid, $lv, 1);
					else $r->ToggleVisibleLang($lid, $lv, 0);
				}
				
			}
		}
	}
	
	//die();
	
	
	if($kind==2){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//������� 
				
				$lid=(int)$val;
				
				$r=new BanItem();
				
				$r->Del($lid);
				
				
			}
		}
	}
	
	
	
	
	if(($kind==4)||($kind==5)){
		//��������� ����
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				
				$r=new BanItem();
				
				if($kind==4) $r->ToggleVisible($lid,1);
				if($kind==5) $r->ToggleVisible($lid,0);
				
			}
		}
	}
	
	header('Location: viewads.php?&from='.$from.'&to_page='.$to_page);
	die();

}
	
	
	
	
	


require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'������ �������� ����� - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);


$_menu_id=23;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//���� � ����������� �� ������� �������
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);



//����������� �������
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;



$_context->AddContext(new ContextItem( 11, 'a', "", "������� ������", "ed_ban.php?action=0", false , $global_profile  ));
 
$_context->AddContext(new ContextItem( "", '',  "", "�������", "viewads.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//������� ������
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('�����', 'index.php'));
 

$_bc->AddContext(new BcItem('������ ��������', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);




$smarty->display('page_top.html');
?>

	
	<?
	//���������
	//echo $razd->DrawNavig($id, $lang_id,0,' ������ ������� ');
	?>
	
	<?
	
	$pl=new BansGroupNew();

	echo $pl->GetItems(0,$from,$to_page, 'banners/items.html');
	
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