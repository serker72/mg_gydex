<?
//session_name("addphotos");
session_start();
require_once('../classes/global.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/mmenulist.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/photoitem.php');
require_once('photosettings.php');
$image_path='..'.$rootpath;

include('../editor/fckeditor.php');
if(!HAS_GALLERY){
	header("Location: index.php");
	die();
}

//���������������� �����������
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 21)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 21)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}


$razd=new MmenuItem();

$ph=new PhotoItem();



//if(!isset($step)) $step=0;
if(!isset($_GET['step'])){
	if(!isset($_POST['step'])){
		$step=0;
	}else $step = $_POST['step'];	
}else $step = $_GET['step'];	
$step=abs((float)$step);	

//die();
if($step==0){
	/*foreach($_SESSION as $k=>$v){
		unset($_SESSION[$k]);
	}*/

	if(!isset($_GET['from'])){
		if(!isset($_POST['from'])){
			$from=0;
		}else $from = $_POST['from'];	
	}else $from = $_GET['from'];	
	$from=abs((int)$from);	
	$_SESSION['from']=$from;


	if(!isset($_GET['to_page'])){
		if(!isset($_POST['to_page'])){
			$to_page=ITEMS_PER_PAGE;
		}else $to_page = $_POST['to_page'];	
	}else $to_page = $_GET['to_page'];	
	$to_page=abs((int)$to_page);	
	$_SESSION['to_page']=$to_page;
	

	if(!isset($_GET['action']))
		if(!isset($_POST['action'])) $action = 0;
		else $action = $_POST['action'];		
	else $action = $_GET['action'];		
	$action=abs((int)$action);
	if(($action!=0)&&($action!=1)/*&&($action!=2)*/) $action=0;
	//0 => ��������� ���������� ����
	//1 => �� ���� �� �����

	$_SESSION['action']=$action;

	if(!isset($_GET['lang_id']))
		if(!isset($_POST['lang_id'])) {
			//header('Location: index.php');
			//die();
			$lang_id=LANG_CODE;
		}
		else $lang_id = $_POST['lang_id'];		
	else $lang_id = $_GET['lang_id'];		
	$lang_id=abs((int)$lang_id);
		
	if(!isset($_GET['nonvisual']))
		if(!isset($_POST['nonvisual'])) $nonvisual = 0;
		else $nonvisual = $_POST['nonvisual'];		
	else $nonvisual = $_GET['nonvisual'];		
	$nonvisual=abs((int)$nonvisual);	
	$_SESSION['nonvisual']=$nonvisual;

	if(!isset($_GET['mid']))
		if(!isset($_POST['mid'])) {
			header('Location: index.php');
			die();
			//$mid=0;
		}
		else $mid = $_POST['mid'];		
	else $mid = $_GET['mid'];		
	$mid=abs((int)$mid);
	$_SESSION['mid']=$mid;
}


//die();



if(isset($_POST['doBack'])){
	$step-=1.5;
	//header('Location: add_photos.php?id='.$_SESSION['mid'].'&from='.$_SESSION['from'].'&to_page='.$_SESSION['to_page']);
}


//���������
if($_SESSION['action']==0){
	
	if($step==0.5){
		//die('qq');
		
		//������� ��� ������ �������
		//� ���-��
		if(!isset($_GET['cou']))
			if(!isset($_POST['cou'])) {
				$cou=0;
			}
			else $cou = $_POST['cou'];		
		else $cou = $_GET['cou'];		
		$cou=abs((int)$cou);
		
		
		//������� ����� ������
		$params=Array(); $lparams=Array();
		$lparams['name']=SecStr($_POST['name']);
		$lparams['small_txt']=SecStr($_POST['small_txt']);	
		$lparams['big_txt']=SecStr($_POST['big_txt']);	
		$params['photo_small']=SecStr($_POST['photo_small']);	
		$params['photo_big']=SecStr($_POST['photo_big']);	
		$lparams['lang_id']=LANG_CODE;	
		
		$params['mid']=abs((int)$_POST['mid']);
		
		$params['ord']=abs((int)$_POST['ord']);
		
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
		
		
		for($i=0; $i<$cou; $i++) $ph->Add($params,$lparams);
		unset($razd);
		
		header('location: viewphotos.php?id='.$params['mid'].'&from='.$_SESSION['from'].'&to_page='.$_SESSION['to_page']);
		die();
		
		
		
	}
}

if($_SESSION['action']==1){
	
	if($step==0.5){
		
		$_SESSION['name']=SecStr($_POST['name']);
		$_SESSION['small_txt']=SecStr($_POST['small_txt']);	
		$_SESSION['big_txt']=SecStr($_POST['big_txt']);	
		
		$_SESSION['lang_id']=LANG_CODE;	
		
		$_SESSION['mid']=abs((int)$_POST['mid']);
		
		$_SESSION['ord']=abs((int)$_POST['ord']);
		
		if(isset($_POST['is_shown'])) $_SESSION['is_shown']=1; else $_SESSION['is_shown']=0;
		
		$step=1;
	}
	
	if($step==1.5){	
		
		if(isset($_POST['folder']))
			$_SESSION['folder']=SecStr($_POST['folder']);
		else 
			$_SESSION['folder']='';
		
		$step=2;
		
	}
	
	if($step==2.5){
			
		$thnames=$HTTP_POST_VARS['tns'];
		$pics = $HTTP_POST_VARS['files'];
	
		$photos=array();
		while(strpos($pics,"\n")!=false){
			$pic=substr($pics,0,strpos($pics,"\n"));
			$pics=substr($pics,strpos($pics,"\n")+1,strlen($pics));
			//echo $pic."<p>";
			//���������� ������ ���������
			if(strlen($thnames)>=2){
			//����� ���������
				if(strpos($thnames,"\n")!=false){
					$th=substr($thnames,0,strpos($thnames,"\n"));
					$thnames=substr($thnames,strpos($thnames,"\n")+1,strlen($thnames));
				}else $th="img/no.gif";
			}else{
			//������ no.gif
				$th = "img/no.gif";
			}
			//echo $th."<p>";
			$photos[$pic]=array(
					'th' => $th,
					'pic' => $pic/*,
					'pic_med' => substr($pic, 0, (strpos($pic,basename($pic)))).'a'.basename($pic),
					'pic_ssmall' => substr($pic, 0, (strpos($pic,basename($pic)))).'ts'.basename($pic)*/
			);
			if(strlen($pics)<2) break;
		}
		
		$counter=count($photos);
			
		foreach($photos as $n=>$item){
			$params=Array(); $lparams=Array();
			$lparams['name']=$_SESSION['name'];
			$lparams['small_txt']=$_SESSION['small_txt'];
			$lparams['big_txt']=$_SESSION['big_txt'];
			$params['photo_small']=trim($item['th']);
			$params['photo_big']=trim($item['pic']);
			$lparams['lang_id']=$_SESSION['lang_id'];
			
			$params['mid']=$_SESSION['mid'];
			
			$params['ord']=$_SESSION['ord'];
			
			$lparams['is_shown']=$_SESSION['is_shown'];
			$ph->Add($params,$lparams);
		}	
		
		
		header('Location: viewphotos.php?id='.$_SESSION['mid'].'&from='.$_SESSION['from'].'&to_page='.$_SESSION['to_page']);
		die();
	}
}






	function get_listing($listpath, $pre="", $num=0){
		$hand_list = opendir($listpath);
		while(($name=readdir($hand_list))!==false){
			if($name==".") continue;
			if($name=="..") continue;
			if(is_dir($listpath."/".$name)) {
				if($pre!="") $suff=$pre."@1"; else $suff="";
				echo "<option value=\"".($suff.$name)."\">".($listpath."/".$name)."</option>\n";
				get_listing($listpath."/".$name, $suff.$name, $num+1);
			}
		}	
		closedir($hand_list);
	}

	
	function get_pics($fullpath,&$th,&$pic){
		$hand = opendir($fullpath);
		while(($name=readdir($hand))!==false){
			if(($name==".")||($name==".."))  continue;
			if(is_file($fullpath."/".$name)){
			//������� ��������...
				if(eregi("^(.*)\\.(jpg|jpeg)$",$name,$P)) {
					//���� ���-�� �� tn - ������
					if(eregi("^tn",$name,$P)) 
						//$th=$th."\n".str_replace("../","",$fullpath)."/".$name;
						$th[]=str_replace("../","",$fullpath)."/".$name."\n";
					else{
						if((!ereg("^a",$name,$P))&&(!ereg("^ts",$name,$P))) $pic[]=str_replace("../","",$fullpath)."/".$name."\n";
					}
				}
			}
		}
		closedir($hand);	
	}

require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.'');
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


$smarty->display('page_top.html');

?>

	
	<?if($_SESSION['action']==0){?>
	<h1>���������� ���������� ���������� ����</h1>
	<?}
	if($_SESSION['action']==1){?>
	<h1>���������� ���� �� �����</h1>
	<?}?>
	
	
	<form action="add_photos.php" method="post" id="inpp" name="inpp">
	
	
	
	<?
	if($_SESSION['action']==0){
		$step=0.5;
	?>
	
	<input type="hidden" name="step" value="<?=$step?>">
	<strong>���������� ����������� ����:</strong><br>
	<input type="text" name="cou" id="cou" value="1" size="3" maxlength="3"><p>
	
	
		<h2>������ ����</h2>
	
		���� �����<br />
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="img/no.gif" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">������� ����</a>
		<p />		

		���� �������<br />
		<input name="photo_big" id="photo_big" type="text" size="100" maxlength="255" value="img/no.gif" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=3','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_big.value=''; document.forms.inpp.photo_big.focus();">������� ����</a>
		<p />	
	
		�������� ����:<br />
		<input name="name" type="text" size="100" maxlength="255" value="" /><p />	

			��������������� ����:<br>
			<select name="mid" id="mid" style="width: 300px;">
				<?
				$parent_id=$mid; 
				$curr=0;
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<?
			//echo htmlspecialchars($ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name'));
			
			?>
			<p>

			����� ����� � ����:<br />
		        <?php
				if($nonvisual==1){
				?>
				<textarea cols="80" rows="10" name="small_txt"></textarea>
				<?
				}else{
					$sBasePath = '../editor/';
					
					$oFCKeditor = new FCKeditor('small_txt') ;
					$oFCKeditor->Config['CustomConfigurationsPath'] = '/editor/myconfig.js' ;
					$oFCKeditor->ToolbarSet = 'mybar';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = 200;
					
					$oFCKeditor->BasePath	= $sBasePath ;
					$oFCKeditor->Value = '';
					
					$oFCKeditor->Create() ;
				}
				?>
		        <p />

				����� � ����:<br />
		        <?php
				if($nonvisual==1){
				?>
				<textarea cols="80" rows="20" name="big_txt"></textarea>
				<?
				}else{
					$sBasePath = '../editor/';
					
					$oFCKeditor = new FCKeditor('big_txt') ;
					$oFCKeditor->Config['CustomConfigurationsPath'] = '/editor/myconfig.js' ;
					$oFCKeditor->ToolbarSet = 'mybar';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = 350;
					
					$oFCKeditor->BasePath	= $sBasePath ;
					$oFCKeditor->Value = '';
					
					$oFCKeditor->Create() ;
				}
				?>
		        <p />
				
				���������� �� �����: <input class="unbord" name="is_shown" type="checkbox" /><p />

				������� ������: <input name="ord" type="text" size="3" maxlength="3" value="1" /><p />
	<?}?>	
	
	<?if($_SESSION['action']==1){?>
		<?if($step==0){
			$step=0.5;?>
			<input type="hidden" name="step" value="<?=$step?>">
			
			<h2>������ ����</h2>
	
		
	
		�������� ����:<br />
		<input name="name" type="text" size="100" maxlength="255" value="<?if(isset($_SESSION['name'])) echo htmlspecialchars(stripslashes($_SESSION['name']));?>" /><p />	

			��������������� ����:<br>
			<select name="mid" id="mid" style="width: 300px;">
				<?
				$parent_id=$_SESSION['mid']; 
				$curr=0;
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<?
			//echo htmlspecialchars($ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name'));
			
			?>
			<p>

			����� ����� � ����:<br />
		        <?php
				if($_SESSION['nonvisual']==1){
				?>
				<textarea cols="80" rows="10" name="small_txt"><?if(isset($_SESSION['small_txt'])) echo htmlspecialchars(stripslashes($_SESSION['small_txt']));?></textarea>
				<?
				}else{
					$sBasePath = '../editor/';
					
					$oFCKeditor = new FCKeditor('small_txt') ;
					$oFCKeditor->Config['CustomConfigurationsPath'] = '/editor/myconfig.js' ;
					$oFCKeditor->ToolbarSet = 'mybar';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = 200;
					
					$oFCKeditor->BasePath	= $sBasePath ;
					if(isset($_SESSION['small_txt'])) $oFCKeditor->Value= stripslashes($_SESSION['small_txt']); else $oFCKeditor->Value = '';
					
					$oFCKeditor->Create() ;
				}
				?>
		        <p />

				����� � ����:<br />
		        <?php
				if($_SESSION['nonvisual']==1){
				?>
				<textarea cols="80" rows="20" name="big_txt"><?if(isset($_SESSION['big_txt'])) echo htmlspecialchars(stripslashes($_SESSION['big_txt']));?></textarea>
				<?
				}else{
					$sBasePath = '../editor/';
					
					$oFCKeditor = new FCKeditor('big_txt') ;
					$oFCKeditor->Config['CustomConfigurationsPath'] = '/editor/myconfig.js' ;
					$oFCKeditor->ToolbarSet = 'mybar';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = 350;
					
					$oFCKeditor->BasePath	= $sBasePath ;
					if(isset($_SESSION['big_txt'])) $oFCKeditor->Value= stripslashes($_SESSION['big_txt']); else $oFCKeditor->Value = '';
					
					$oFCKeditor->Create() ;
				}
				?>
		        <p />
				
				���������� �� �����: <input class="unbord" name="is_shown" type="checkbox" <?if(isset($_SESSION['is_shown'])) echo 'checked';?>/><p />

				������� ������: <input name="ord" type="text" size="3" maxlength="3" value="1" <?if(isset($_SESSION['ord'])) echo htmlspecialchars(stripslashes($_SESSION['ord']));?>/><p />
			
	
		<?}?>
		<?if($step==1){
			$step=1.5;
			
			
			?>
			<input type="hidden" name="step" value="<?=$step?>">
			
			
			<table cellspacing="2" cellpadding="2" border="0">
			<tr>
			    <td width="50%" valign="top">
					<h2>�������� �����:</h2>
					<select name="folder" size="32"><?get_listing($image_path,"",0);?></select>
					
				</td>
			    <td width="50%" valign="top">
				<h2>��������� ������:</h2>
				<strong>�������� ����: </strong><?=stripslashes($_SESSION['name'])?><p>
				<strong>����� ����� � ����: </strong><?=stripslashes($_SESSION['small_txt'])?><p>
				<strong>����� � ����: </strong><?=stripslashes($_SESSION['big_txt'])?><p>
				<strong>������� ������ ����: </strong><?=stripslashes($_SESSION['ord'])?><p>
				<strong>���������� �� �����: </strong><?if($_SESSION['is_shown']==1) echo '��'; else echo '���';?>
				</td>
			</tr>
			</table>
			<p>		
		
		
		<?}?>
		
		<?if($step==2){
			$step=2.5;
			
			
			?>
			<input type="hidden" name="step" value="<?=$step?>">
			<table cellspacing="2" cellpadding="2" border="0">
			<tr>
			    <td width="50%" valign="top">
					<h2>����������:</h2>
		<?
			
	$th=array();	$pic=array();
	get_pics($image_path.'/'.eregi_replace("@1","/",$_SESSION['folder']),$th,$pic);
	@sort($th, SORT_STRING);
	@sort($pic, SORT_STRING);	
	
	?>
	
	
	<strong>������ ������:</strong><br>	
	<table width="500" border="0" cellspacing="0" cellpadding="0">
	<tr align="left" valign="top">
		<td width="50%">
		������:<br>
		<textarea cols="53" rows="25" name="tns"><?/*=$th*/
		for($k=0; $k<count($th); $k++) echo $th[$k];
		?></textarea>
		</td>
		<td width="50%">
		������ ����:<br>
		<textarea cols="53" rows="25" name="files"><?/*=$pic*/
		for($k=0; $k<count($pic); $k++) echo $pic[$k];?></textarea>		
		</td>		
	</tr>
	</table>
	
	</td>
			    <td width="50%" valign="top">
				<h2>��������� ������:</h2>
				<strong>����� � ������������: </strong><?=stripslashes(eregi_replace("@1","/",$_SESSION['folder']))?><p>
				<strong>�������� ����: </strong><?=stripslashes($_SESSION['name'])?><p>
				<strong>����� ����� � ����: </strong><?=stripslashes($_SESSION['small_txt'])?><p>
				<strong>����� � ����: </strong><?=stripslashes($_SESSION['big_txt'])?><p>
				<strong>������� ������ ����: </strong><?=stripslashes($_SESSION['ord'])?><p>
				<strong>���������� �� �����: </strong><?if($_SESSION['is_shown']==1) echo '��'; else echo '���';?>
				</td>
			</tr>
			</table>
		
		<p>
		
		<?}
		?>
		
	<?}?>
	
	
	<?if($step>=1){?><input name="doBack" type="submit" value="&lt;&lt;�����"><?}?>
	 <input name="doInp" type="submit" value="����������&gt;&gt;">
	</form>
	
	<script  language="JavaScript">  
	  var  frmvalidator    =  new  Validator("inpp");  
	 <?
	if($_SESSION['action']==0){?>
	  frmvalidator.addValidation("cou","req","��������� ���� ���������� ����������� ����!"); 
	  frmvalidator.addValidation("cou","num","� ���� ���������� ����������� ���� ��������� ������ �����!"); 
	  frmvalidator.addValidation("ord","req","��������� ���� ������� ������!"); 
	  frmvalidator.addValidation("ord","num","� ���� ������� ������ ��������� ������ �����!"); 
	  frmvalidator.addValidation("name","req","��������� ���� �������� ����!"); 
	 <?}?>	  
	 <?
	if(($_SESSION['action']==1)&&($step==0.5)){?>
	  frmvalidator.addValidation("name","req","��������� ���� �������� ����!"); 
	    frmvalidator.addValidation("ord","req","��������� ���� ������� ������!"); 
	  frmvalidator.addValidation("ord","num","� ���� ������� ������ ��������� ������ �����!"); 
	 <?}?>	  
	  
	</script>  
	
	
	
	
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>