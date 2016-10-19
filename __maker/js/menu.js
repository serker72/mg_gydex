$(document).ready(function(){
// �������� ��� ���������
$('.gydex_menu li ul').hide ();
// �� ���� ������� � ����������� ���� � ��������� � ���� ����� arrow (���������)
$('li li:has(li)').find('a:first').addClass ('arrow');
// ��������� ������� li:hover
 
$('.gydex_menu li').hover (
// over
function () {
// ��� ������� li ������� ����� � ������ �����
$(this).addClass('active');
// ���������� ������� ������
$('ul:first', this).show();
},
// out
function () {
// ����� ������ ���������� �����
$(this).removeClass('active');
// �������� ������
$('ul:first', this).hide ();
}
);




//left menu
$("#gydex_left_col > ul > li > a").bind("click", function(){
	
	if($(this).hasClass("opened")) {
		 $(this).removeClass("opened").addClass("closed");
	}
	else{
		 $(this).removeClass("closed").addClass("opened");
		 
	}
	
	return false;
});


//"check all" tasks
$("#check_all").bind("change", function(){
	$("input[type=checkbox][id^=do_process_]").prop("checked", $(this).prop("checked"));
});
$("input[type=checkbox][id^=do_process_]").bind("change", function(){
	cou=$("input[type=checkbox][id^=do_process_]").length;
	ccou=$("input[type=checkbox][id^=do_process_]:checked").length;
	if(cou==ccou) $("#check_all").prop("checked", true);
	else $("#check_all").prop("checked", false); 
});
$("input[class=gydex_control]").bind("change", function(){
	id=$(this).attr("id");

	id=id.replace(/_([0-9])+([a-z_])+$/, '');
	
	id=id.replace(/_([a-z_])+$/, '');
	
	
	$("#do_process_"+id).prop("checked", true).trigger("change");
});
$("select[class=gydex_control]").bind("change", function(){
	id=$(this).attr("id");

	id=id.replace(/_([0-9])+([a-z_])+$/, '');
	
	id=id.replace(/_([a-z_])+$/, '');
	
	 
	$("#do_process_"+id).prop("checked", true).trigger("change");
});

});