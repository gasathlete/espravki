$("document").ready(function(){
$('#lo').click(function(){
var mail=$.trim($("#admin_mail").val());
var pass=$.trim($("#admin_password").val());
if(mail.length < 1 || pass.length < 1 || mail.length > 30 || pass.length > 30){
return false;
		}
	})
})
function show_square_style(element){
	var obj=document.getElementById('square_style');
	if(element.value==0){
		obj.style.display='none';
		obj.style.visibility='hidden';
	}
	if(element.value==1){
		obj.style.display='block';
		obj.style.visibility='visible';
	}
	if(element.value==2){
		obj.style.display='block';
		obj.style.visibility='visible';
	}
	if(element.value==3){
		obj.style.display='none';
		obj.style.visibility='hidden';
	}
}
function change_tab(tab1,tab2){
	var obj1=document.getElementById(tab1);
	var obj2=document.getElementById(tab2);
	if(obj1 && obj2){
		obj1.style.display='none';
		obj1.style.visibility='hidden';
		obj2.style.display='block';
		obj2.style.visibility='visible';
	}
}
function show_element(my_id){
	var element=document.getElementById(my_id);
	if(element){
		element.style.display='block';
		element.style.visibility='visible';
	}
}
function hide_element(my_id){
	var element=document.getElementById(my_id);
	if(element){
		element.style.display='none';
		element.style.visibility='hidden';
	}
}
function activate_tab(tab_num,tab_count){
	var activate_tab_name='tab'+tab_num;
	var tab_obj_name='ntab'+tab_num;
	var obj=document.getElementById(activate_tab_name);
	var tab_obj=document.getElementById(tab_obj_name);
	if(obj && tab_obj){
		show_element(activate_tab_name);
		tab_obj.style.backgroundColor='#f2f2f2';
	}
	for(var i=1;i<=tab_count;i++){
		if(i!=tab_num){
			var tab_obj_name='ntab'+i;
			var nactivate_tab_name='tab'+i;
			var obj=document.getElementById(nactivate_tab_name);
			var tab_obj=document.getElementById(tab_obj_name);
			if(obj && tab_obj_name){
				hide_element(nactivate_tab_name);
				tab_obj.style.backgroundColor='#999';
			}
		}
	}
}