<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

require_once('../classes/discr_authuser.php');
$au=new DiscrAuthUser();
	//�������� �����������
	$profile=$au->Auth();
	if($profile===NULL){
		
		//Header('HTTP/1.1 403 Forbidden');
		//Header('Location: login.php');
		//die();		
	}
	else{
		Header('Location: index.php');
		die();		
	}


//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",SITETITLE);
$smarty->assign("NAVIMENU",'');


$smarty->display('page_mini_top.html');
unset($smarty);

?>
<div class="pole_login">
  <h1>����������, �������������!</h1>
  
  
  <form action="usereffects.php" method="post" name="auther" id="auther">
   
    
    
    <label for="login">�����</label><br />
    <input type="text" name="login" id="login" size="15" maxlength="40" value="" placeholder="�����">
    <p />
    
    <label for="password">������</label><br />
    
    
    <input type="password" name="passw" id="passw" size="15" maxlength="40" value="" placeholder="****" >
    
    <br />
    <input type="checkbox" name="rem_me" id="rem_me" class="unbord" value=""><label for="rem_me">��������� ����</label>
    
     <?
	$code=0;
	if(isset($_GET['code'])) $code=abs((int)$_GET['code']);
	if($code>0) {
		?>
		<div class="wrong"><? echo $au->ShowError($code);?></div>
        <?
	}
	?>
    
    <p />
    <input type="submit" name="doLog" id="doLog" value="�����"> 

    <a href="forgot.php">������ ������?</a><br>
  </form>
  
</div>
<?
//������ ������

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
echo $smarty->fetch('page_mini_bottom.html');
unset($smarty);
?>