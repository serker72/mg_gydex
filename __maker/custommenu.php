<?
$custommenu='';
if(HAS_PRICE){
	if(HAS_BASKET){
		require_once('../classes/ordersgroup.php');
		$t_og=new OrdersGroup();
		$custommenu.='
		
		
		<a href="vieworders.php" class="mmenulink">Заказы ('.$t_og->CalcItemsByMode(0).')</a><p>
		
		<a href="viewclients.php" class="mmenulink">Покупатели</a><p>
		
		<hr />
		';
		unset($t_og);
	}
	
	if(HAS_OST){
		$custommenu.='
		
		<a href="csvmaster.php" class="mmenulink" target="_blank">CSV-импорт товарных остатков</a><p>';
	}
	
	$custommenu.='
	
	<a href="viewfirms.php"  class="mmenulink">Фирмы-производители</a><p>
	
	<a href="viewprices.php"  class="mmenulink">Виды цен магазина</a><p>

	<a href="viewcurrs.php"  class="mmenulink">Валюты магазина</a><p>


	<a href="viewdicts.php"  class="mmenulink">Словари свойств</a><p>
	
	<hr />
	';
}

	$custommenu.='	<a href="discr_matrix_group.php"  class="mmenulink">Права групп</a><p>';
	
	$custommenu.='	<a href="discr_matrix_user.php"  class="mmenulink">Права пользователей</a><p>
	
	<hr />
	
	';

//работа с языковыми ресурсами
	$custommenu.='	<a href="viewres.php"  class="mmenulink">Библиотека языковых ресурсов сайта</a><p>';
?>