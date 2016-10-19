<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/resoursefile_aj.php');

require_once('../classes/xajax/xajax_core/xajax.inc.php');
require_once('js/reseditor_xajax_common.php');

//административная авторизация
require_once('inc/adm_header.php');


/*
if(!isset($_GET['viewmode']))
	if(!isset($_POST['viewmode'])) {
		//header('Location: index.php');
		//die();
		$viewmode=0;
	}
	else $viewmode = $_POST['viewmode'];		
else $viewmode = $_GET['viewmode'];		
$viewmode=abs((int)$viewmode);
*/

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Библиотека языковых ресурсов - '.SITETITLE.'');
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

$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
if(isset($_GET['doSort'])&&($_GET['doSort']==1)){
	//сортируем библиотеку по именам файлов
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {		
		$rf->SortRF();
	}else{
		header('Location: no_rights.php');
		  die();		
	}
}

$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);




//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;
 

$_context->AddContext(new ContextItem( "", '',  "", "Справка", "viewlangs.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);


//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Библиотека языковых ресурсов', basename(__FILE__)));

 


$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);
//var_dump($bc);





$smarty->assign('xajax_scripts',$xajax_read->getJavascript('../classes/xajax'));

$smarty->display('page_top_xajax.html');



//подключим дивы редактирования


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 13)) {

require_once('viewres_divs.php');
?>
	<h1>Библиотека языковых ресурсов сайта</h1>
	

	<a href="#" onclick="<?=COORDFUNC?> n=document.getElementById('doEdFile'); n.style.display='none'; n=document.getElementById('doInpFile'); n.style.display='inline'; m=document.getElementById('file_add_win'); m.style.top=coord[1]; m.style.left=coord[0];  aa=document.getElementById('file_id'); aa.value='';  bb=document.getElementById('descr'); bb.value='';  m.className='work_window_vis';"><img src="/img/plus.gif" alt="" width="7" height="7" border="0">добавить файл</a><br>
	
	
	<a href="viewres.php?doSort=1">сортировать библиотеку</a><p>
	

	<table width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr align="left" valign="top">
		<td width="*">
		<?
		
		
		echo $rf->DrawTree();
		//любой код
		?>
		</td>
	</tr>
	</table>

	
<?
}else{
	echo NO_RIGHTS;	
}


//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);


$smarty->display('page_bottom.html');
?>