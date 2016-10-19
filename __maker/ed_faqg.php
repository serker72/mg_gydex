<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/faq_g_item.php');


 

//административная авторизация
require_once('inc/adm_header.php');


 

$ph=new FaqGItem;

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


if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;



 
	
if(!isset($_GET['nonvisual']))
	if(!isset($_POST['nonvisual'])) $nonvisual = 0;
	else $nonvisual = $_POST['nonvisual'];		
else $nonvisual = $_GET['nonvisual'];		
$nonvisual=abs((int)$nonvisual);	



if($action==0){
	 

}


	
if(($action==1)||($action==2)){
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	
	 
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 20)) {
	
	  $photo=$ph->GetItemById($id,$lang_id);
	  if($photo==false){
		  header('Location: index.php');
		  die();	
	  }
	}else{
		header('Location: no_rights.php');
			 die();	
	}
	  
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=Array(); $lparams=Array();
	$params['name']=SecStr($_POST['name']);
	 
	 
	
	$params['ord']=abs((int)$_POST['ord']);	
	
	 
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 20)) {
		$r_code=$ph->Add($params);
		unset($razd);
	}else{
		header('Location: no_rights.php');
		 die();		
	}
	
	if(isset($_POST['doInp']))
		header('Location: viewfaqg.php?from='.$from.'&to_page='.$to_page);
	else if(isset($_POST['doApply']))
		header('Location: ed_faqg.php?action=1&id='.$r_code.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
}


//смена порядка показа фото
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$photo['ord']<255)	$params['ord']=(int)$photo['ord']+1;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$photo['ord']>0) $params['ord']=(int)$photo['ord']-1;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 20)) {
		$ph->Edit($id, $params );
	}else{
		header('Location: no_rights.php');
		 die();	
	}
	
	header('Location: viewfaqg.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
}


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=Array(); $lparams=Array();
	$params['name']=SecStr($_POST['name']);
	 
	 
	
	$params['ord']=abs((int)$_POST['ord']);	
	 
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 20)) {
		
			$id=$ph->Add($params );
		}else{
			header('Location: no_rights.php');
		 die();	
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 20)) {
			$ph->Edit($id, $params);
		}else{
			header('Location: no_rights.php');
		 die();	
		}
	}
	if(isset($_POST['doInp']))
		header('Location: viewfaqg.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_faqg.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
}


if($action==2){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 20)) {
		$ph->Del($id);
	}else{
		header('Location: no_rights.php');
	 	die();	
	}
	
	header('Location: viewfaqg.php?from='.$from.'&to_page='.$to_page);
	die();
}




require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование группы вопросов - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');

?>

	
	<?
	//навигация
	//echo $razd->DrawNavig($mid, LANG_CODE,0,'правка статьи');
	?>
	
	
    
	<form action="ed_faqg.php" method="post" id="inpp" name="inpp">
	<h1>Редактирование группы вопросов</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
    <input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
    
    
    
     
    
    
    
    
    
    
    
	
	<label for="name">Название:</label><br />
 <input name="name" type="text" size="60" maxlength="255" value="<? if($action==1) echo $photo['name']; else echo ''; ?>" /><p />

		
	   
		
		<label for="ord">Порядок показа:</label> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $photo['ord']; else echo '1'; ?>" /><p />
        
        
       
        
        <input name="doInp" type="submit" value="Внести изменения" onclick="" />
        <input name="doApply" type="submit" value="Применить изменения" onclick="" />
		   
	<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	</form>
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
 // frmvalidator.addValidation("name","req","Заполните  поле Название статьи!");    
 
</script>  
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>