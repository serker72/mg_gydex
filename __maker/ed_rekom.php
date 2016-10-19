<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/mmenulist.php');


require_once('../classes/priceitem.php');
require_once('../classes/rekom_group.php');
require_once('../classes/rekom_item.php');

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();		
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();		
}



$mi=new MmenuItem();
$ml=new MmenuList();

if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;

if($action==2){
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	$razd=new RekomItem();
	$razdel=$razd->GetItemById($id);
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
	
	
	$razd->Del($id);
	
	unset($razd);
	header('location: viewrekoms.php?good_id='.$razdel['pri_lid']);
	die();	
	
}

//вставка товаров
if($action==0){
	if(!isset($_GET['good_id']))
		if(!isset($_POST['good_id'])) {
			header('Location: index.php');
			die();
		}
		else $good_id = $_POST['good_id'];		
	else $good_id = $_GET['good_id'];		
	$good_id=abs((int)$good_id);
	$gd = new PriceItem();
	$good=$gd->GetItemById($good_id);
	
	
	
	
	
	//проверим шаг
	if(!isset($_GET['step']))
		if(!isset($_POST['step'])) {
			$step=0;
		}
		else $step = $_POST['step'];		
	else $step = $_GET['step'];		
	$step=abs($step);
	
	
	if($step==0.5){
		$step=1;
		
		
		if(!isset($_POST['kindSel'])) {
			$kindSel=0;
		}
		else $kindSel = $_POST['kindSel'];
		
		//$_SESSION['razdSel']=$razdSel;
		if($kindSel==0){
			$mid=abs((int)$_POST['mid']);
		}
		
	}
	
	if($step==1.5){
		$step=2;
	}
	
	
	if($step==2.5){
		$step=3;
		
			$pr=new PriceItem();
		   foreach($_POST as $k=>$v){
				if(eregi("goodr",$k)){
					//echo "$v<br>";
					$v=abs((int)$v);
					$prr=$pr->GetItemById($v);
					if($prr!=false){
						//добавим в рекомендуемый товар
						$params=Array();
						$params['pri_lid']=$good_id;
						$params['sec_lid']=$v;
						
						$re=new RekomItem();
						$re->Add($params);
					}
				}
			}
		
	}
	
	
}

require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>


    <h2>Рекомендуемые товары к товару <?=stripslashes($good['name'])?></h2>
   
   
   <?
   if($step==0){
   $step=0.5;
   ?>
   <form action="ed_rekom.php" method="post">
   <input type="hidden" name="good_id" value="<?=$good_id?>">
    <input type="hidden" name="step" value="<?=$step?>">
	
	<input type="submit" name="doGo" value="Дальше &gt;&gt;">
	&nbsp;&nbsp;&nbsp;
	<input type="button" name="doCancel" value="Отмена" onclick="window.close();">
	
	
	<br>
	
   <table cellspacing="2" cellpadding="4" border="0">
	<tr>
	    <td valign="top" class="itemscell">
		<input class="unbord" type="radio" name="kindSel" value="0" checked><strong>Выберите раздел:</strong><hr>
		<select name="mid" id="mid" size="32" style="width: 500px;">
		<?
		//рисуем дерево разделов
		echo $ml->GetItemsOptByParentIdLangId(0,0,LANG_CODE,'name');
		?>	
		</select>
		
		
		</td>
	    <td valign="top" class="itemscell">
		<input class="unbord" type="radio" name="kindSel" value="1"><strong>Выбрать из списка всех товаров</strong><br>
		
		</td>
	</tr>
	</table>
   </form>
   <?}//of step==0?>
   
   
   <?if($step==1){
   	$step=1.5;
	?>
	<form action="ed_rekom.php" method="post">
   <input type="hidden" name="good_id" value="<?=$good_id?>">
	<input type="hidden" name="step" value="<?=$step?>">
	
	<input type="submit" name="doGo" value="Дальше &gt;&gt;">
	&nbsp;&nbsp;&nbsp;
	<input type="button" name="doCancel" value="Отмена" onclick="window.close();"><p>
	
	<?
	if($kindSel==0){
		//товары в разделе
		$sql='select p.id, pl.name, p.photo_small from price_item as p inner join price_lang as pl on p.id=pl.price_id and pl.lang_id="'.LANG_CODE.'" where p.id<>'.$good_id.' and p.mid='.$mid.' and p.id not in(select distinct sec_lid from goods_rekommend where pri_lid='.$good_id.') order by p.mid, pl.name';
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		?>
		<table border="0" cellspacing="4" cellpadding="4">
		<?
		$per_row=6; $cter=1;
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			if($cter==1) echo '<tr align="center" valign="top">';
			
			echo '<td class="itemscell">';
			?>
			<input class="unbord" type="checkbox" name="goodr<?=$f[0]?>" value="<?=$f[0]?>"><a href="ed_price.php?action=1&id=<?=$f[0]?>" target="_blank"><?=stripslashes($f[1])?><br>
			<img src="/<?=stripslashes($f[2])?>" alt="" border="0"></a>
			<?
			
			if($cter>=$per_row) $cter=1;
			else $cter++;
		}
		?>
		</table>
		<?
		
	}else if($kindSel==1){
		//все товары
		$sql='select p.id, pl.name, p.photo_small from price_item as p inner join price_lang as pl on p.id=pl.price_id and pl.lang_id="'.LANG_CODE.'" where p.id<>'.$good_id.' and p.id not in(select distinct sec_lid from goods_rekommend where pri_lid='.$good_id.')  order by p.mid, pl.name';
		//echo $sql;
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$total=$set->GetResultNumRows();
		
		for($i=0; $i<$total; $i++){
			$f=mysqli_fetch_array($rs);
			?>
			<input class="unbord" type="checkbox" name="goodr<?=$f[0]?>" value="<?=$f[0]?>"><a href="ed_price.php?action=1&id=<?=$f[0]?>" target="_blank"><?=stripslashes($f[1])?></a><br>
			<?
		}
	}
   	?>
   </form>
   <?}//of step==1?>
   
   
   
   <?
   if($step==2){
   $step=2.5;
   ?>
   <form action="ed_rekom.php" method="post">
   <input type="hidden" name="good_id" value="<?=$good_id?>">
    <input type="hidden" name="step" value="<?=$step?>">
	
	<input type="submit" name="doGo" value="Дальше &gt;&gt;">
	&nbsp;&nbsp;&nbsp;
	<input type="button" name="doCancel" value="Отмена" onclick="window.close();"><hr><p>
	
	<strong>Итак, вы выбрали товары:</strong><p>
	
	<?
	$pr=new PriceItem();
	foreach($_POST as $k=>$v){
		if(eregi("goodr",$k)){
			//echo "$v<br>";
			$v=abs((int)$v);
			$prr=$pr->GetItemById($v);
			if($prr!=false){
				?>
				<input class="unbord" type="checkbox" name="goodr<?=$v?>" value="<?=$v?>" checked><a href="ed_price.php?action=1&id=<?=$v?>" target="_blank"><?=stripslashes($prr['name'])?></a><br>
				<?
			}
		}
	}
	?>
	<?} //of step==2?>
   
   
   
   
   
   
    <?
   if($step==3){
   $step=3.5;
   ?>
   <input type="button" name="doCancel" value="Закрыть" onclick="window.close(); try{opener.location.reload(); }catch(e){};"><hr><p>
   <h3>Добавленные рекомендуемые товары:</h3>
   <?
   $pr=new PriceItem();
   foreach($_POST as $k=>$v){
		if(eregi("goodr",$k)){
			$v=abs((int)$v);
			$prr=$pr->GetItemById($v);
			if($prr!=false){
				?>
				<strong><?=stripslashes($prr['name'])?></strong><br>
				<?
			}
		}
	}
   
   ?>
   
   
   <?}?>

<?




//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_noleft_bottom.html');
?>
