		//построение массива пользователей в и вне группы для проверки отмеченных
		
		window.in_marked_%{$group_id}%=new Array();
		window.not_in_marked_%{$group_id}%=new Array();
		%{section name=usersec loop=$users_array}%
			window.in_marked_%{$group_id}%[%{$smarty.section.usersec.index}%]='%{$group_id}%_%{$users_array[usersec].id}%';
		%{/section}%
		
		 %{section name=notusersec loop=$not_users_array}%
			 window.not_in_marked_%{$group_id}%[%{$smarty.section.notusersec.index}%]='%{$group_id}%_not_%{$not_users_array[notusersec].id}%';
 		 %{/section}%
		 
		 