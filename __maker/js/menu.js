$(document).ready(function(){
// скрываем все подуровни
$('.gydex_menu li ul').hide ();
// во всех пунктах с подпунктами ищем и добавляем к нему класс arrow (стрелочку)
$('li li:has(li)').find('a:first').addClass ('arrow');
// описываем событие li:hover
 
$('.gydex_menu li').hover (
// over
function () {
// для красоты li добавим класс с другим фоном
$(this).addClass('active');
// отображаем скрытый список
$('ul:first', this).show();
},
// out
function () {
// нужно убрать добавлений класс
$(this).removeClass('active');
// скрываем список
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