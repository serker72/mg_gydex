<?
$custommenu='';
if(HAS_PRICE){
	if(HAS_BASKET){
		require_once('../classes/ordersgroup.php');
		$t_og=new OrdersGroup();
		$custommenu.='
		
		
		<a href="vieworders.php" class="mmenulink">������ ('.$t_og->CalcItemsByMode(0).')</a><p>
		
		<a href="viewclients.php" class="mmenulink">����������</a><p>
		
		<hr />
		';
		unset($t_og);
	}
	
	if(HAS_OST){
		$custommenu.='
		
		<a href="csvmaster.php" class="mmenulink" target="_blank">CSV-������ �������� ��������</a><p>';
	}
	
	$custommenu.='
	
	<a href="viewfirms.php"  class="mmenulink">�����-�������������</a><p>
	
	<a href="viewprices.php"  class="mmenulink">���� ��� ��������</a><p>

	<a href="viewcurrs.php"  class="mmenulink">������ ��������</a><p>


	<a href="viewdicts.php"  class="mmenulink">������� �������</a><p>
	
	<hr />
	';
}

	$custommenu.='	<a href="discr_matrix_group.php"  class="mmenulink">����� �����</a><p>';
	
	$custommenu.='	<a href="discr_matrix_user.php"  class="mmenulink">����� �������������</a><p>
	
	<hr />
	
	';

//������ � ��������� ���������
	$custommenu.='	<a href="viewres.php"  class="mmenulink">���������� �������� �������� �����</a><p>';
?>