$(function(){
	 $("#solution_id").bind("change", function(){
		$("div.faq_hot").hide();
		$("div.faq_hot#hot_"+$(this).val()).show();
	 });
	 $("#faq_read_all").bind("click", function(){
		location.href='?solution_id='+ $("#solution_id").val()+'&group_id=0#faq_title';
	 });
	 
	 $("#solution_id1").bind("change", function(){
		location.href='?solution_id='+ $("#solution_id1").val()+'&group_id=0#faq';
	 });
	 
	 $("a[id^=faq_grtab_]").bind("click", function(){
		id=$(this).attr("id").replace(/^faq_grtab_/,'');
		$("div.faq_tab").hide();
		
		
		$("div[id^=faq_gr_"+id+"]").show();
		
		
		return false; 
	 });
	 
	 
	 $("li[id^=faq_ref_]").bind("click", function(){
		 id=$(this).attr("id").replace(/^faq_ref_/,'');
		 
		 $("div[id^=faq_qa_]").hide();
		 $("div[id=faq_qa_"+id+"]").show();
	 });
	 
});