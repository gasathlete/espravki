function gE(o){return document.getElementById(o);}
function isVisible(obj){
	if (obj == document) return true
	if (!obj) return false
	if (!obj.parentNode) return false
	if (obj.style) {
		if (obj.style.display == 'none') return false
		if (obj.style.visibility == 'hidden') return false
	}
	//Try the computed style in a standard way
	if (window.getComputedStyle) {
		var style = window.getComputedStyle(obj, "")
		if (style.display == 'none') return false
		if (style.visibility == 'hidden') return false
	}
	//Or get the computed style using IE's silly proprietary way
	var style = obj.currentStyle
	if (style) {
		if (style['display'] == 'none') return false
		if (style['visibility'] == 'hidden') return false
	}
	return isVisible(obj.parentNode);
}

function init(){document.onclick=getCursorXY;}
function getCursorXY(e){
	if(e) position_x = e.pageX;
	else position_x = event.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
	if(e) position_y = e.pageY;
	else position_y = event.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
}

function check_symbols(element){
	if(element.value!='')
		if (!(/[\u0410-\u044E]/i.test(element.value))){
			show_alert(js_lang[34],js_lang[3],'confirmation');
			return false;
		}
}
function maximize_window(){window.moveTo(0,0);window.resizeTo(screen.width,screen.height);}
function change_icons_menu_background(my_color,my_element,my_action){
	var color='';
	var obj=gE(my_element);
	if(my_color=='white') color='#fff';
	if(my_color=='shampain') color='#fbfbd0';
	if(my_color=='blue') color='#178dbf';
	if(my_color=='yellow') color='#ffcc00';
	if(my_action=='colorize') obj.style.backgroundColor=color;
	else obj.style.backgroundColor='';
}
function set_as_top(el){var obj=gE(el);if(obj) obj.style.zIndex='1000';}
function show_hide_element(element){
	var my_element='list_guest_mail_' + element.value;
	var my_checkbox_name='checkbox_option_' + element.value;
	var obj=gE(my_element);
	var real_checkbox=gE(my_checkbox_name);
	if(obj){
		if(element.checked){
			obj.innerHTML='<input type="text" class="custom_field" name="guest_mail[]" id="guest_mail_'+ element.value +'">';
		}
		else obj.innerHTML=js_lang[2];
	}
	if(real_checkbox) real_checkbox.value='1';
}
function show_hide_guest_element(element){
	var my_element='checkbox_mail_' + element.value;
	var my_checkbox_name='checkbox_option_' + element.value;
	var obj=gE(my_element);
	var real_checkbox=gE(my_checkbox_name);
	if(obj){
		var to_change=obj.getAttribute('change').toString();
		if(to_change=='1'){
			if(element.checked){
				obj.style.border='solid 1px #666';
				obj.value='';
			}
			else{
				obj.style.border='none';
				obj.value=js_lang[2];
			}
		}
	}
	if(real_checkbox){
		if(element.checked) real_checkbox.value='1';
		else real_checkbox.value='0';
	}
}
function change_background_image(el){el.style.backgroundColor='#f1f1f1';}
function is_int_value(my_value){
	var my_flag=0;
	var zero_count=0;
	var values=new Array('0','1','2','3','4','5','6','7','8','9');
	if(my_value.length==0) return 0;
	else{
		for (var i=0;i<my_value.length;i++){
			my_flag=0;
			for(var j=0;j<values.length;j++){
				if(values[j]==my_value[i]) my_flag=1;
			}
			if(my_value[i]=='0') zero_count++;
			if(my_flag==0) return 0;
		}
	}
	if(zero_count==my_value.length) return 0;
	else return 1;
}

function limit_symbols(el){
	var str_obj=el.value;
	if(str_obj.length==2){
		el.value=el.value.substr(0,2);
	}
}

function show_guest_status_fields(){
	var disabled_submit=1;
	if(persons_count){
		for(var i=1;i<=persons_count;i++){
			var status_obj=gE('guest_status_'+i);
			if(status_obj){
				var my_value=status_obj.value;
				//var attr=status_obj.getAttribute('requested_guest');
				if(my_value=='no'){
					var obj_to_show=gE('guest_'+i);
					var obj_to_change=gE('description_'+i);
					var obj_to_hide=gE('calendar_field_'+i);
					disabled_submit=0;
				}
				else if(my_value=='maybe'){
					var obj_to_show=gE('calendar_field_'+i);
					var obj_to_change=gE('calendar_'+i);
					var obj_to_hide=gE('guest_'+i);
					disabled_submit=0;
				}
				else if(my_value=='yes'){
					disabled_submit=0;
				}
				else{
					var obj1=gE('calendar_field_'+i);
					var obj2=gE('guest_'+i);
					if(obj1 && obj2){
						obj1.style.display='none';
						obj2.style.display='none';
						obj1.style.visibility='hidden';
						obj2.style.visibility='hidden';
					}
				}
				if(obj_to_show){
					obj_to_show.style.display='block';
					obj_to_show.style.visibility='visible';
				}
				if(obj_to_hide){
					obj_to_hide.style.display='none';
					obj_to_hide.style.visibility='hidden';
				}
				if(obj_to_change){
					if(my_value=='no'){
						if(obj_to_change.value=='') obj_to_change.style.border='solid 1px green';
					}
					if(my_value=='maybe'){
						if((obj_to_change.value=='')) obj_to_change.style.border='solid 1px green';
						else{
							var my_date_value=obj_to_change.value;
							var my_date=my_date_value.substr(6,4) +'-'+my_date_value.substr(3,2)+'-'+ my_date_value.substr(0,2);
							if(my_date<=today_date) obj_to_change.style.border='solid 1px green';
						}
					}
				}
				if(my_value=='yes'){
					var obj_to_hide1=gE('guest_'+i);
					var obj_to_hide2=gE('calendar_field_'+i);
					if(obj_to_hide1 && obj_to_hide2){
						obj_to_hide1.style.display='none';
						obj_to_hide1.style.visibility='hidden';
						obj_to_hide2.style.display='none';
						obj_to_hide2.style.visibility='hidden';
					}
				}
				/*if((attr==1) && (my_value=='yes')){
					var hidden_guests=gE('guests_in_group');
					if(hidden_guests){
						hidden_guests.style.display='block';
						hidden_guests.style.visibility='visible';
					}
				}
				else if((attr==1) && (my_value!='yes')){
					var hidden_guests=gE('guests_in_group');
					if(hidden_guests){
						hidden_guests.style.display='none';
						hidden_guests.style.visibility='hidden';
					}
				}*/
			}
		}
	}
}
function steps_visibility(object){
	if(object=='purchase'){
		if(purchase_1_max==0) purchase_1_max = Math.ceil(count_objects_group(object, '1') / records_count_on_group);
		if(purchase_2_max==0) purchase_2_max = Math.ceil(count_objects_group(object, '2') / records_count_on_group);
		if(purchase_3_max==0) purchase_3_max = Math.ceil(count_objects_group(object, '3') / records_count_on_group);
		if(purchase_4_max==0) purchase_4_max = Math.ceil(count_objects_group(object, '4') / records_count_on_group);
		if(purchase_5_max==0) purchase_5_max = Math.ceil(count_objects_group(object, '5') / records_count_on_group);
		if(purchase_6_max==0) purchase_6_max = Math.ceil(count_objects_group(object, '6') / records_count_on_group);
	}

	if(object=='schedule'){
		if(schedule_1_max==0) schedule_1_max = Math.ceil(count_objects_group(object, '1') / records_count_on_group);
		if(schedule_2_max==0) schedule_2_max = Math.ceil(count_objects_group(object, '2') / records_count_on_group);
		if(schedule_3_max==0) schedule_3_max = Math.ceil(count_objects_group(object, '3') / records_count_on_group);
		if(schedule_4_max==0) schedule_4_max = Math.ceil(count_objects_group(object, '4') / records_count_on_group);
		if(schedule_5_max==0) schedule_5_max = Math.ceil(count_objects_group(object, '5') / records_count_on_group);
		if(schedule_6_max==0) schedule_6_max = Math.ceil(count_objects_group(object, '6') / records_count_on_group);
	}
	if(purchase_1_max<=1){
		var varname='next_'+object+'_1';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
	if(purchase_2_max<=1){
		var varname='next_'+object+'_2';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
	if(purchase_3_max<=1){
		var varname='next_'+object+'_3';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
	if(purchase_4_max<=1){
		var varname='next_'+object+'_4';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
	if(purchase_5_max<=1){
		var varname='next_'+object+'_5';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
	if(purchase_6_max<=1){
		var varname='next_'+object+'_6';
		var obj2=gE(varname);
		if(obj2){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
		}
	}
}
function clear_content(el){
	var obj=gE(el);
	if(obj) obj.innerHTML='';
}
function check_purchases_schedules_step1(element){
	var element_name=element + '_name';
	var obj=gE(element_name);
	var message_div=gE('message_div_step1');
	if(obj){
		var str = obj.value;
		var re = /[a-zA-Z0-9а-яА-Я,. -!:;?]{1,100}/g;
		if ((str != '') && (str.match(re) == str)){
			if(message_div) message_div.innerHTML='';
			obj.style.border='solid 1px #b5b5b5';
			set_purchase_wizard_step('2','next');
		}
		else{
			if(message_div) message_div.innerHTML=js_lang[14];
			obj.style.border='solid 1px green';
		}
	}
}
function check_purchases_schedules_step2(element){
	var element_date=element+'_date';
	var element_hour=element+'_date_hour';
	var element_minutes=element+'_date_minutes';
	var element_shop=element+'_shop';
	var element_description=element+'_description';
	var date_obj=document.getElementById(element_date);
	var hour_obj=document.getElementById(element_hour);
	var minutes_obj=document.getElementById(element_minutes);
	var shop_obj=document.getElementById(element_shop);
	var description_obj=document.getElementById(element_description);
	var message_obj=document.getElementById('message_div_step2');
	var message_num;


	if(date_obj && hour_obj && minutes_obj && shop_obj && description_obj)
	{

		var date_obj_str=date_obj.value;
		var hour_obj_str= hour_obj.value;
		var minutes_obj_str=minutes_obj.value;
		var shop_obj_str=shop_obj.value;
		var description_obj_str=description_obj.value;
		var show_next=1;
		var shop_re = /[a-zA-Z0-9а-яА-Я,. -!:;?]{1,100}/g;
		var desc_re= /[a-zA-Z0-9а-яА-Я,. -!:;?]{1,100}/g;

		if(date_obj_str!=''){
			if((hour_obj_str=='1000')){
				show_next=0;
				message_num=17;
				hour_obj.style.backgroundColor='green';
			}
			else{
				hour_obj.style.backgroundColor='#fff';
			}
			if( minutes_obj_str=='1000'){
				show_next=0;
				message_num=17;
				minutes_obj.style.backgroundColor='green';
			}
			else{
				hour_obj.style.backgroundColor='#fff';
			}
		}
		if((shop_obj_str=='') || (shop_obj_str.match(shop_re) != shop_obj_str)){
			shop_obj.style.border='solid 1px green';
			message_num=18;
			show_next=0;
		}
		else{
			shop_obj.style.border='solid 1px #b5b5b5';
		}
		
		if((description_obj_str!='') && (shop_obj_str.match(desc_re) != shop_obj_str)){
			description_obj.style.border='solid 1px green';
			message_num=19;
			show_next=0;
		}
		else{
			description_obj.style.border='solid 1px #b5b5b5';
		}
		if(show_next){
			message_obj.innerHTML='';
			if(hour_obj)
				hour_obj.style.backgroundColor='#fff';
			if(minutes_obj)
				minutes_obj.style.backgroundColor='#fff';
			if(shop_obj)
				shop_obj.style.border='solid 1px #b5b5b5';
			if(description_obj)
				description_obj.style.border='solid 1px #b5b5b5';
			set_purchase_wizard_step('3','next');
		}
		else{
			if(message_obj)
				message_obj.innerHTML=js_lang[message_num];
		}
	}
}
function print_purchases_schedules(element){
	var filter_period='';
	var from_date='';
	var to_date='';

	filter_period=document.getElementById('filter_period').value;
	from_date=document.getElementById('from_filter_date').value;
	to_date=document.getElementById('to_filter_date').value;

	url='/index.php?print='+ encodeURIComponent(element)+'&filter_period='+ encodeURIComponent(filter_period);

	if(from_date!='')
		url+='&from_date=' + encodeURIComponent(from_date);

	if(to_date!='')
		url+='&to_date=' + encodeURIComponent(to_date);
	
	printscreen(url);
	// window.open(url,'mywindow','menubar=1,toolbar=1,location=1,resizable=1,width=1000,height=500');
	//document.open("text/html");
	/*fprint.document.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><link href="'+ prev_path +'mbd.css" rel="stylesheet" type="text/css" /></link><link href="'+ prev_path +'forms_style.css" rel="stylesheet" type="text/css" /></link></head><body><div id="purchases_table_print"></div>');

	print_purchases_schedules_table(element);*/

	
}
function printscreen(url)
{
	 window.open(url,'mywindow','menubar=1,toolbar=1,location=1,resizable=1,width=1000,height=500,scrollbars=yes');
}
function prev_objects_group(object,group)
{
	var my_var=0;
	var arrange_by='';
	var start_from=0;
	var edn_to=0;
	var r_1=0;
	var r_2=0;	
	var r_3=0;	
	var r_4=0;
	var r_5=0;
	var r_6=0;


	

	if(object=='purchase')
	{

		
		arrange_by=purchases_filter;
		
		if(group=='1')
		{
			
			//purchase_1_count--;
			if(purchase_1_count<1)
			{
				purchase_1_count=1;
				
			}
		

			
			if(purchase_1_count==1)
			{
				var obj=document.getElementById('prev_purchase_1');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_purchase_1');
				if(obj2 && (purchase_1_count<purchase_1_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}
	
			
			my_var=purchase_1_count;

			
			
		}
		if(group=='2')
		{
			//purchase_2_count--;
			if(purchase_2_count<1)
			{
				purchase_2_count=1;
				
			}
			
			if(purchase_2_count==1)
			{
				var obj=document.getElementById('prev_purchase_2');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}

		
				var obj2=document.getElementById('next_purchase_2');

				if(obj2 && (purchase_2_count<purchase_2_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=purchase_2_count;

			
		}
		if(group=='3')
		{
			//purchase_3_count--;
			if(purchase_3_count<1)
				purchase_3_count=1;

			
			if(purchase_3_count==1)
			{
				var obj=document.getElementById('prev_purchase_3');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_purchase_3');

				
				if(obj2 && (purchase_3_count<purchase_3_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=purchase_3_count;

			
		}
		if(group=='4')
		{
			//purchase_4_count--;
			if(purchase_4_count<1)
				purchase_4_count=1;

			if(purchase_4_count==1)
			{
				var obj=document.getElementById('prev_purchase_4');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_purchase_4');
				if(obj2 && (purchase_4_count<purchase_4_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=purchase_4_count;

			
		}
		if(group=='5')
		{
			//purchase_5_count--;
			if(purchase_5_count<1)
				purchase_5_count=1;

			if(purchase_5_count==1)
			{
				var obj=document.getElementById('prev_purchase_5');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_purchase_5');
				if(obj2 && (purchase_5_count<purchase_5_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=purchase_5_count;

			
		}

		if(group=='6')
		{
			//purchase_6_count--;
			if(purchase_6_count<1)
				purchase_6_count=1;

			if(purchase_6_count==1)
			{
				var obj=document.getElementById('prev_purchase_6');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_purchase_6');
				if(obj2 && (purchase_6_count<purchase_6_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=purchase_6_count;

			
		}
	}

/*--------------------------------------*/


	
	
	if(object=='schedule')
	{
		arrange_by=schedules_filter;
		
		if(group=='1')
		{
			
			//purchase_1_count--;
			if(schedule_1_count<1)
			{
				schedule_1_count=1;
				
			}
		
			if(schedule_1_count==1)
			{
				var obj=document.getElementById('prev_schedule_1');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_1');
				if(obj2 && (schedule_1_count<schedule_1_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}
	
			
			my_var=schedule_1_count;
			
			
		}
		if(group=='2')
		{
			//schedule_2_count--;
			if(schedule_2_count<1)
			{
				schedule_2_count=1;
				
			}

			if(schedule_2_count==1)
			{
				var obj=document.getElementById('prev_schedule_2');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_2');
				if(obj2 && (schedule_2_count<schedule_2_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=schedule_2_count;

			
		}
		if(group=='3')
		{
			//schedule_3_count--;
			if(schedule_3_count<1)
				schedule_3_count=1;
			
			if(schedule_3_count==1)
			{
				var obj=document.getElementById('prev_schedule_3');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_3');


				
				
				if(obj2 && (schedule_3_count<schedule_3_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=schedule_3_count;

			
			

		}
		if(group=='4')
		{
			//schedule_4_count--;
			if(schedule_4_count<1)
				schedule_4_count=1;

			if(schedule_4_count==1)
			{
				var obj=document.getElementById('prev_schedule_4');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_4');
				if(obj2 && (schedule_4_count<schedule_4_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=schedule_4_count;

			
		}
		if(group=='5')
		{
			//schedule_5_count--;
			if(schedule_5_count<1)
				schedule_5_count=1;

			if(schedule_5_count==1)
			{
				var obj=document.getElementById('prev_schedule_5');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_5');
				if(obj2 && (schedule_5_count<schedule_5_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=schedule_5_count;

			
		}

		if(group=='6')
		{
			//schedule_6_count--;
			if(schedule_6_count<1)
				schedule_6_count=1;
			
			if(schedule_6_count==1)
			{
				var obj=document.getElementById('prev_schedule_6');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('next_schedule_6');
				if(obj2 && (schedule_6_count<schedule_6_max))
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
					
				}
			}

			my_var=schedule_6_count;

			
		}
	}
	//console.log(purchase_1_max,purchase_2_max,purchase_3_max,purchase_4_max,purchase_5_max,purchase_6_max);
/*--------------------------------------*/

	start_from=(my_var-1)*records_count_on_group+1;
	//end_to=my_var*records_count_on_group;

	

	var divname='div_'+object+'_'+group;
	var ob=document.getElementById(divname);

	//console.log(unarranged);
	var j=0;//arrange_by
		
	if (ob)
	{
		var nodes_num = 0;
		var records_num=0;
		for (var i = 0; i < ob.childNodes.length; i++)
		{
			if (typeof ob.childNodes[i].id != 'undefined')
			{
				records_num++;
				if (ob.childNodes[i].getAttribute('id') != null)
				{
					var idd = ob.childNodes[i].getAttribute('id').toString();
					if (idd.substr(0, 8) == object)
					{

						var arrid=ob.childNodes[i].getAttribute('arranged').toString();
						var org=ob.childNodes[i].getAttribute('organized').toString();

						if((arrange_by=='none') || ((arrange_by=='arranged') && (arrid=='1')) || ((arrange_by=='unarranged') && (arrid=='0')) || ((arrange_by=='unorganized') && (org=='0')) || ((arrange_by=='organized') && (org=='1')))
						{
							nodes_num++;
						}

						if ((nodes_num >= start_from) && (nodes_num < (start_from + records_count_on_group)))
						{
							if(arrange_by=='none')
							{
								
								ob.childNodes[i].style.visibility = 'visible';
								ob.childNodes[i].style.display = 'block';

								if(group=='1')
									r_1=1;
								if(group=='2')
									r_2=1;
								if(group=='3')
									r_3=1;
								if(group=='4')
									r_4=1;
								if(group=='5')
									r_5=1;
								if(group=='6')
									r_6=1;
							}
							else
							{
								
								if(arrange_by=='arranged')
								{
									if(arrid=='1')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
										if(group=='1')
											r_1=1;
										if(group=='2')
											r_2=1;
										if(group=='3')
											r_3=1;
										if(group=='4')
											r_4=1;
										if(group=='5')
											r_5=1;
										if(group=='6')
											r_6=1;
										
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								if(arrange_by=='unarranged')
								{
									if(arrid=='0')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
										if(group=='1')
											r_1=1;
										if(group=='2')
											r_2=1;
										if(group=='3')
											r_3=1;
										if(group=='4')
											r_4=1;
										if(group=='5')
											r_5=1;
										if(group=='6')
											r_6=1;
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								if(arrange_by=='unorganized')
								{
									if(org=='0')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
										if(group=='1')
											r_1=1;
										if(group=='2')
											r_2=1;
										if(group=='3')
											r_3=1;
										if(group=='4')
											r_4=1;
										if(group=='5')
											r_5=1;
										if(group=='6')
											r_6=1;
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								
								if(arrange_by=='organized')
								{
									if(org=='1')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
										if(group=='1')
											r_1=1;
										if(group=='2')
											r_2=1;
										if(group=='3')
											r_3=1;
										if(group=='4')
											r_4=1;
										if(group=='5')
											r_5=1;
										if(group=='6')
											r_6=1;
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

							}
						}
						else
						{
							ob.childNodes[i].style.visibility = 'hidden';
							ob.childNodes[i].style.display = 'none';
						}
					}
				}
			}
		}

		//console.log(r_1,r_2,r_3,r_4,r_5,r_6);
		if(group=='1')
		{
			//console.log(r_1);
			var no_records_div='no_records_1';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_1==0)
				{
					var status_div=document.getElementById('status_1');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_1==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}

		if(group=='2')
		{
			//console.log(r_2);
			var no_records_div='no_records_2';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_2==0)
				{
					var status_div=document.getElementById('status_2');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_2==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}

		if(group=='3')
		{
			//console.log(r_3);
			var no_records_div='no_records_3';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_3==0)
				{
					var status_div=document.getElementById('status_3');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_3==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}

		if(group=='4')
		{
			//console.log(r_4);
			var no_records_div='no_records_4';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_4==0)
				{
					var status_div=document.getElementById('status_4');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_4==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}


		if(group=='5')
		{
			//console.log(r_5);
			var no_records_div='no_records_5';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_5==0)
				{
					var status_div=document.getElementById('status_5');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_5==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}

		if(group=='6')
		{
			//console.log(r_6);
			var no_records_div='no_records_6';
			var obj_no_records_div=document.getElementById(no_records_div);
			if(obj_no_records_div)
			{
				if(r_6==0)
				{
					var status_div=document.getElementById('status_6');
					if(status_div)
						status_div.innerHTML=js_lang[filter_by_label_id];
					obj_no_records_div.style.display='block';
					obj_no_records_div.style.visibility='visible';
				}
				else if(r_6==1)
				{
					
					obj_no_records_div.style.display='none';
					obj_no_records_div.style.visibility='hidden';
				}
			}
		}
		

	}

	
	
	
}
function next_objects_group(object,group)
{
	
	//arrange_by - filtracia po
	//my_var - stranica
	//start_from - 
	//
	//
	//
	var my_var=0;
	var arrange_by='';
	var start_from=0;
	var edn_to=0;

	if(object=='purchase')
	{

		
		arrange_by=purchases_filter;
		if(group=='1')
		{
			
			//purchase_1_count++;
			if(purchase_1_count>purchase_1_max)
				purchase_1_count=purchase_1_max;

			if(purchase_1_count==purchase_1_max)
			{
				var obj=document.getElementById('next_purchase_1');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('prev_purchase_1');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			my_var=purchase_1_count;
		}
		if(group=='2')
		{
			
			if(purchase_2_count>purchase_2_max)
				purchase_2_count=purchase_2_max;

			if(purchase_2_count==purchase_2_max)
			{
				var obj=document.getElementById('next_purchase_2');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				
				var obj2=document.getElementById('prev_purchase_2');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			my_var=purchase_2_count;
			
		}
		if(group=='3')
		{
			
			if(purchase_3_count>purchase_3_max)
				purchase_3_count=purchase_3_max;

			if(purchase_3_count==purchase_3_max)
			{
				var obj=document.getElementById('next_purchase_3');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}

				var obj2=document.getElementById('prev_purchase_3');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			my_var=purchase_3_count;

			
		}
		if(group=='4')
		{
			
			if(purchase_4_count>purchase_4_max)
				purchase_4_count=purchase_4_max;

			if(purchase_4_count==purchase_4_max)
			{
				var obj=document.getElementById('next_purchase_4');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}

				var obj2=document.getElementById('prev_purchase_4');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}

			}	
			

			my_var=purchase_4_count;

			
		}
		if(group=='5')
		{

			
			if(purchase_5_count>purchase_5_max)
				purchase_5_count=purchase_5_max;

			if(purchase_5_count==purchase_5_max)
			{
				var obj=document.getElementById('next_purchase_5');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				
				var obj2=document.getElementById('prev_purchase_5');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}

			}	

			my_var=purchase_5_count;

			
		}
		if(group=='6')
		{

			
			if(purchase_6_count>purchase_6_max)
				purchase_6_count=purchase_6_max;

			if(purchase_6_count==purchase_6_max)
			{
				var obj=document.getElementById('next_purchase_6');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
			
				var obj2=document.getElementById('prev_purchase_6');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
				

			}

			my_var=purchase_6_count;	
		}
	}


		
/*--------------------------------------------------------------------------------------------------*/
	
	
	if(object=='schedule')
	{
		arrange_by=schedules_filter;

		
		if(group=='1')
		{
			
			if(schedule_1_count>schedule_1_max)
				schedule_1_count=schedule_1_max;

			if(schedule_1_count==schedule_1_max)
			{
				var obj=document.getElementById('next_schedule_1');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				var obj2=document.getElementById('prev_schedule_1');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			
			my_var=schedule_1_count;
		}
		if(group=='2')
		{
			
			if(schedule_2_count>schedule_2_max)
				schedule_2_count=schedule_2_max;

			if(schedule_2_count==schedule_2_max)
			{
				var obj=document.getElementById('next_schedule_2');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				
				var obj2=document.getElementById('prev_schedule_2');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			my_var=schedule_2_count;
			
		}
		if(group=='3')
		{

			
			if(schedule_3_count>schedule_3_max)
				schedule_3_count=schedule_3_max;

			if(schedule_3_count==schedule_3_max)
			{
				var obj=document.getElementById('next_schedule_3');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}

				var obj2=document.getElementById('prev_schedule_3');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}

			
			my_var=schedule_3_count;

			
		}
		if(group=='4')
		{
			
			if(schedule_4_count>schedule_4_max)
				schedule_4_count=schedule_4_max;

			if(schedule_4_count==schedule_4_max)
			{
				var obj=document.getElementById('next_schedule_4');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}

				var obj2=document.getElementById('prev_schedule_4');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}

			}	
			

			my_var=schedule_4_count;

			
		}
		if(group=='5')
		{

			
			if(schedule_5_count>schedule_5_max)
				schedule_5_count=schedule_5_max;

			if(schedule_5_count==schedule_5_max)
			{
				var obj=document.getElementById('next_schedule_5');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
				
				var obj2=document.getElementById('prev_schedule_5');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}

			}	

			my_var=schedule_5_count;

			
		}
		if(group=='6')
		{

			
			if(schedule_6_count>schedule_6_max)
				schedule_6_count=schedule_6_max;

			if(schedule_6_count==schedule_6_max)
			{
				var obj=document.getElementById('next_schedule_6');
				if(obj)
				{
					obj.style.display='none';
					obj.style.visibility='hidden';
				}
			
				var obj2=document.getElementById('prev_schedule_6');
				if(obj2)
				{
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
				

			}

			my_var=schedule_6_count;	
		}
	}
	
/*--------------------------------------------------------------------------------------------------*/
	start_from=(my_var-1)*records_count_on_group +1;
	//end_to=my_var*records_count_on_group;
	start_from=(my_var-1)*records_count_on_group;


	//console.log(my_var,start_from);
	
	var divname='div_'+object+'_'+group;
	var ob=document.getElementById(divname);

	//console.log(unarranged);
	var j=0;//arrange_by
		
	if (ob)
	{
		var nodes_num = 0;
		var records_num=0;
		for (var i = 0; i < ob.childNodes.length; i++)
		{
			if (typeof ob.childNodes[i].id != 'undefined')
			{
				records_num++; //broi zapisi
				if (ob.childNodes[i].getAttribute('id') != null)
				{
					var idd = ob.childNodes[i].getAttribute('id').toString();
					if (idd.substr(0, 8) == object)
					{
						var arrid=ob.childNodes[i].getAttribute('arranged').toString();
						var org=ob.childNodes[i].getAttribute('organized').toString();

						//console.log(arrid,org);

						if((arrange_by=='none') || ((arrange_by=='arranged') && (arrid=='1')) || ((arrange_by=='unarranged') && (arrid=='0')) || ((arrange_by=='unorganized') && (org=='0')) ||((arrange_by=='organized') && (org=='1')))
						{
							nodes_num++;
						}


						//console.log(nodes_num,start_from,start_from +records_count_on_group);
						//console.log((nodes_num >= start_from) && (nodes_num < (start_from +records_count_on_group)));
					
						
	

						if ((nodes_num > start_from) && (nodes_num <= (start_from +records_count_on_group))) //nodes_num
						{
							if(arrange_by=='none')
							{
								
								ob.childNodes[i].style.visibility = 'visible';
								ob.childNodes[i].style.display = 'block';
							}
							else
							{
								
								if(arrange_by=='arranged')
								{
									if(arrid=='1')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								if(arrange_by=='unarranged')
								{
									if(arrid=='0')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								if(arrange_by=='unorganized')
								{
									if(org=='0')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}

								
								if(arrange_by=='organized')
								{
									if(org=='1')
									{
										
										ob.childNodes[i].style.visibility = 'visible';
										ob.childNodes[i].style.display = 'block';
									}
									else
									{
										ob.childNodes[i].style.visibility = 'hidden';
										ob.childNodes[i].style.display = 'none';
									}
								}


							}
						}
						else
						{
							ob.childNodes[i].style.visibility = 'hidden';
							ob.childNodes[i].style.display = 'none';
						}
					}
				}
			}
		}
	}
	
}
function count_objects_group(object, group)
{
	var j = 0;
	var divname='div_'+object+'_'+group;
	var ob = document.getElementById(divname);
	var filter_by='';

	if(object=='purchase')
		filter_by=purchases_filter;
	
	if(object=='schedule')
		filter_by=schedules_filter;


	if (ob)
	{
		
		var nodes_num = 0;
		for (var i = 0; i < ob.childNodes.length; i++)
		{
			if (typeof ob.childNodes[i].id != 'undefined')
			{
				nodes_num++;
				if (ob.childNodes[i].getAttribute('id') != null)
				{
					var idd = ob.childNodes[i].getAttribute('id').toString();
					if (idd.substr(0, 8) == object)
					{
						if(filter_by=='none')
						{
							j++;
						}
						else
						{
							
							var arrid=ob.childNodes[i].getAttribute('arranged').toString();
							var org=ob.childNodes[i].getAttribute('organized').toString();

							if((filter_by=='arranged') && ( arrid=='1'))
							{
								j++;
							}
							if((filter_by=='unarranged') && ( arrid=='0'))
							{
								j++;
							}
							if((filter_by=='unorganized') && (org=='0'))
							{
								j++;
							}
							if((filter_by=='organized') && (org=='1'))
							{
								j++;
							}
							
						}
				
					}
				}
			}
		}
	}

	if(j==0)
		j=1;
	
	return j;
}

function clear_field(element, flag)
{
	var myelement = document.getElementById(element);
	if (flag == true)
	{
		if (myelement)
			myelement.value = '';
	}
}
function show_hide(my_id)
{	
	
	var element=document.getElementById(my_id);
	if(element)
	{
		if (element.style.visibility=='visible')
		{
			element.style.display='none';
			element.style.visibility='hidden';
		}
		else
		{
			element.style.display='block';
			element.style.visibility='visible';
		}
	}
}
function add_remove_guest_on_request(element)
{
	var my_element='list_guest_mail_' + element.value;
	var obj=document.getElementById(my_element);
	var my_num=0;
	var flag=0;


	if(obj)
	{
		if(element.checked)
		{
			obj.innerHTML='<input type="text" id="guest_mail_request_' + element.value + '" class="custom_field" name="guest_mail_'+ element.value +'">';
			guests_on_request.push(element.value);
		}
		else
		{
			obj.innerHTML=js_lang[2];

			
	
			for(var i=0;i<guests_on_request.length;i++)
			{
				if(guests_on_request[i]==element.value)
				{
					flag=1;
					my_num=i;
					break;
				}
			}
		
			if(flag)
				guests_on_request.splice(my_num,1);
		}
	}
	else
	{
		if(element.checked)
			guests_on_request_with_m.push(element.value);
		else
		{
			for(var i=0;i<guests_on_request.length;i++)
			{
				if(guests_on_request_with_m[i]==element.value)
				{
					flag=1;
					my_num=i;
					break;
				}
			}

			if(flag)
				guests_on_request_with_m.splice(my_num,1);
		}
			
	}
}
function show_element(my_id){
	//alert('aaa'+my_id);
	var element=document.getElementById(my_id);
	if(element){
		element.style.display='block';
		element.style.visibility='visible';
	}
}
function show_popup_form(my_id){
	var my_num=-1;
	var obj;
	for(var i=0;i<fopen.length;i++){
		if(fopen[i]==my_id){
			my_num=i;
		}
		else{
			obj=document.getElementById(fopen[i]);
			if(obj){
				obj.style.display='none';
				obj.style.visibility='hidden';
			}
		}
	}
	if(my_num==-1) fopen.push(my_id);
	show_element(my_id);
}
function set_loader(my_element,my_value){
	var obj=document.getElementById(my_element);
	if(obj){
		obj.value=my_value;
	}
}
function hide_element(my_id)
{
	var element=document.getElementById(my_id);
	
	if(element)
	{
		element.style.display='none';
		element.style.visibility='hidden';
	}
}
function hide_form(my_id)
{
	var my_num=0;
	var flag=0;
	for(var i=0;i<fopen.length;i++)
	{
		if(fopen[i]==my_id)
		{
			flag=1;
			my_num=i;
			break;
		}
	}

	if(flag)
		fopen.splice(my_num,1);
	hide_element(my_id);
	//console.log(fopen);
}
function columns_autoresize()
{
	var obj1=document.getElementById('left_column');
	var obj2=document.getElementById('central_column');
	var obj3=document.getElementById('right_column');
	var obj4=document.getElementById('top_offers');
	var ht=0;

	if(obj1 && obj2 && obj3)
	{
		if(obj1.offsetHeight + 10 > obj2.offsetHeight + 20)
		{
			ht=obj1.offsetHeight + 10;
		}
		else
		{
			ht=obj2.offsetHeight + 20;
		}

		if(ht<obj3.offsetHeight + 152)
		{
			ht=obj3.offsetHeight + 152;
		}


		obj1.style.height = (ht - 10 - 20)  + 'px';
		obj2.style.height = (ht - 20) + 'px';
		obj3.style.height = (ht - 152 - 20) + 'px';
		obj4.style.height = (ht - 130 -10 -20 -12) + 'px';

	}

	
	//obj4.style.height=ht -100 + 'px';
}
function autoresize(){
	var obj=document.getElementById('article_content');
	if(obj){
		if((obj.offsetHeight+280)<screen.height)
			obj.style.height=(screen.height-(obj.offsetHeight+280)) + 'px';	
	}
}

function wedding_screen_autoresize()
{
	autoresize();

	var obj=document.getElementById('content');
	if(obj)
	{
		alert(obj.offsetHeight);
	}

	
}
function show_tab(tab_name)
{
	var tabs=new Array('official','party','church');
	var div_name='';
	var my_tab_name='';
	var obj;
	for(var i=0;i<tabs.length;i++)
	{

		
		div_name=tabs[i] + '_div';
		my_tab_name=tabs[i] + '_tab';
		//console.log(tabs[i]==tab_name);
		if(tabs[i]==tab_name)
		{
			
			show_element(div_name);
			obj=document.getElementById(my_tab_name);
			obj.setAttribute('class','l_tab_a');
			obj.className='l_tab_a';
		}
		else
		{
				
			hide_element(div_name);
			obj=document.getElementById(my_tab_name);
			obj.setAttribute('class','l_tab');
			obj.className='l_tab';
		}
	}
}
function ef(my_field){
	var obj=document.getElementById(my_field);
	if(obj){
		obj.removeAttribute('readonly',0);
		obj.setAttribute('class','custom_textarea');
		obj.className='custom_textarea';
	}
}
function set_colors(element)
{
	var obj1=document.getElementById(element);
	var obj21=document.getElementById('party_div');
	var obj22=document.getElementById('church_div');
	var obj23=document.getElementById('official_div');
	var obj26=document.getElementById('checkbox_img');

	if(obj1)
	{
		if(obj1.value=='0')
		{
			obj21.style.backgroundColor='#fff';
			obj22.style.backgroundColor='#fff';
			obj23.style.backgroundColor='#fff';
			obj26.src=prev_path + 'images/interface/icons/0_status.png';
		}
		else
		{
			obj21.style.backgroundColor='#f2f2f2';
			obj22.style.backgroundColor='#f2f2f2';
			obj23.style.backgroundColor='#f2f2f2';
			obj26.src=prev_path + 'images/interface/icons/1_status.png';	
		}
	}
	
}
function check_uncheck(element){
	var obj1=document.getElementById(element);
	var obj2=document.getElementById('wedding_date');
	var obj3=document.getElementById('wedding_date_hour');
	var obj4=document.getElementById('wedding_date_minutes');
	var obj5=document.getElementById('wedding_party_date');
	var obj6=document.getElementById('wedding_party_hour');
	var obj7=document.getElementById('wedding_party_minutes');
	var obj8=document.getElementById('wedding_city');
	var obj9=document.getElementById('restaurant');
	var obj11=document.getElementById('wedding_church_date');
	var obj12=document.getElementById('wedding_church_hour');
	var obj13=document.getElementById('wedding_church_minutes');
	var obj14=document.getElementById('church');
	var obj15=document.getElementById('register_office');
	var obj16=document.getElementById('church_name');
	var obj17=document.getElementById('restaurant_name');
	var obj18=document.getElementById('party_city');
	var obj19=document.getElementById('church_city');
	var obj20=document.getElementById('register_office_name');
	var obj21=document.getElementById('party_div');
	var obj22=document.getElementById('church_div');
	var obj23=document.getElementById('official_div');
	var obj24=document.getElementById('step2_active');
	var obj25=document.getElementById('step2_inactive');
	var obj26=document.getElementById('checkbox_img');
	var obj27=document.getElementById('display_calendar_button1');
	var obj28=document.getElementById('display_calendar_button2');
	var obj29=document.getElementById('display_calendar_button3');

	if(obj1){
		if(obj1.value=='0'){
			obj2.setAttribute('readonly',true);
			obj3.disabled=true;
			obj4.disabled=true;
			obj5.setAttribute('readonly',true);
			obj6.disabled=true;
			obj7.disabled=true;
			obj11.setAttribute('readonly',true);
			obj12.disabled=true;
			obj13.disabled=true;
			obj8.setAttribute('readonly',true);
			obj18.setAttribute('readonly',true);
			obj19.setAttribute('readonly',true);
			obj9.setAttribute('readonly',true);
			obj14.setAttribute('readonly',true);
			obj15.setAttribute('readonly',true);
			obj16.setAttribute('readonly',true);
			obj17.setAttribute('readonly',true);
			obj20.setAttribute('readonly',true);
			obj8.readOnly = true;
			obj18.readOnly = true;
			obj19.readOnly = true;
			obj9.readOnly = true;
			obj14.readOnly = true;
			obj15.readOnly = true;
			obj16.readOnly = true;
			obj17.readOnly = true;
			obj20.readOnly = true;
			obj2.setAttribute('class','custom_field_ro');
			obj3.setAttribute('class','custom_field_ro');
			obj4.setAttribute('class','custom_field_ro');
			obj5.setAttribute('class','custom_field_ro');
			obj6.setAttribute('class','custom_field_ro');
			obj7.setAttribute('class','custom_field_ro');
			obj8.setAttribute('class','custom_field_ro');
			obj9.setAttribute('class','custom_field_ro');
			obj11.setAttribute('class','custom_field_ro');
			obj12.setAttribute('class','custom_field_ro');
			obj13.setAttribute('class','custom_field_ro');
			obj14.setAttribute('class','custom_field_ro');
			obj15.setAttribute('class','custom_field_ro');
			obj16.setAttribute('class','custom_field_ro');
			obj17.setAttribute('class','custom_field_ro');
			obj18.setAttribute('class','custom_field_ro');
			obj19.setAttribute('class','custom_field_ro');
			obj20.setAttribute('class','custom_field_ro');
			obj2.className='custom_field_ro';
			obj3.className='custom_field_ro';
			obj4.className='custom_field_ro';
			obj5.className='custom_field_ro';
			obj6.className='custom_field_ro';
			obj7.className='custom_field_ro';
			obj8.className='custom_field_ro';
			obj9.className='custom_field_ro';
			obj11.className='custom_field_ro';
			obj12.className='custom_field_ro';
			obj13.className='custom_field_ro';
			obj14.className='custom_field_ro';
			obj15.className='custom_field_ro';
			obj16.className='custom_field_ro';
			obj17.className='custom_field_ro';
			obj18.className='custom_field_ro';
			obj19.className='custom_field_ro';
			obj20.className='custom_field_ro';
			obj21.style.backgroundColor='#f2f2f2';
			obj22.style.backgroundColor='#f2f2f2';
			obj23.style.backgroundColor='#f2f2f2';
			if(obj24){
				obj24.style.display='none';
				obj24.style.visibility='hidden';
			}

			if(obj25){
				obj25.style.display='block';
				obj25.style.visibility='visible';
			}

			obj1.value='1';
			obj26.src=prev_path + 'images/interface/icons/1_status.png';
			
			obj27.style.display='none';
			obj27.style.visibility='hidden';

			obj28.style.display='none';
			obj28.style.visibility='hidden';

			obj29.style.display='none';
			obj29.style.visibility='hidden';
		}
		else
			if(obj1.value='1'){
			obj2.removeAttribute('readonly',0);
			obj3.disabled=false;
			obj4.disabled=false;
			obj5.removeAttribute('readonly',0);
			obj6.disabled=false;
			obj7.disabled=false;
			obj11.removeAttribute('readonly',0);
			obj12.disabled=false;
			obj13.disabled=false;
			obj8.removeAttribute('readonly',0);
			obj9.removeAttribute('readonly',0);
			obj14.removeAttribute('readonly',0);
			obj15.removeAttribute('readonly',0);
			obj16.removeAttribute('readonly',0);
			obj17.removeAttribute('readonly',0);
			obj18.removeAttribute('readonly',0);
			obj19.removeAttribute('readonly',0);
			obj20.removeAttribute('readonly',0);
			obj8.readOnly = false;
			obj18.readOnly = false;
			obj19.readOnly = false;
			obj9.readOnly = false;
			obj14.readOnly = false;
			obj15.readOnly = false;
			obj16.readOnly = false;
			obj17.readOnly = false;
			obj20.readOnly = false;
			obj2.setAttribute('class','custom_field');
			obj3.setAttribute('class','custom_field');
			obj4.setAttribute('class','custom_field');
			obj5.setAttribute('class','custom_field');
			obj6.setAttribute('class','custom_field');
			obj7.setAttribute('class','custom_field');
			obj8.setAttribute('class','custom_field');
			obj9.setAttribute('class','custom_field');
			obj11.setAttribute('class','custom_field');
			obj12.setAttribute('class','custom_field');
			obj13.setAttribute('class','custom_field');
			obj14.setAttribute('class','custom_field');
			obj15.setAttribute('class','custom_field');
			obj16.setAttribute('class','custom_field');
			obj17.setAttribute('class','custom_field');
			obj18.setAttribute('class','custom_field');
			obj19.setAttribute('class','custom_field');
			obj20.setAttribute('class','custom_field');
			obj2.className='custom_field';
			obj3.className='custom_field';
			obj4.className='custom_field';
			obj5.className='custom_field';
			obj6.className='custom_field';
			obj7.className='custom_field';
			obj8.className='custom_field';
			obj9.className='custom_field';
			obj11.className='custom_field';
			obj12.className='custom_field';
			obj13.className='custom_field';
			obj14.className='custom_field';
			obj15.className='custom_field';
			obj16.className='custom_field';
			obj17.className='custom_field';
			obj18.className='custom_field';
			obj19.className='custom_field';
			obj20.className='custom_field';
			obj21.style.backgroundColor='#fff';
			obj22.style.backgroundColor='#fff';
			obj23.style.backgroundColor='#fff';
			if(obj24){
				obj24.style.display='block';
				obj24.style.visibility='visible';
			}
			if(obj25){
				obj25.style.display='none';
				obj25.style.visibility='hidden';
			}
			obj1.value='0';
			obj26.src=prev_path + 'images/interface/icons/0_status.png';
			obj27.style.display='block';
			obj27.style.visibility='visible';
			obj28.style.display='block';
			obj28.style.visibility='visible';
			obj29.style.display='block';
			obj29.style.visibility='visible';
		}
	}
	
}
function guest_fields_ro(element)
{
	var obj=document.getElementById(element);

	if(obj.name=='no_best_man')
	{
		var obj1=document.getElementById('best_man_fname');
		var obj2=document.getElementById('best_man_lname');
		var obj3=document.getElementById('best_man_city');
		var obj4=document.getElementById('best_man_mail');
	}

	if(obj.name=='no_sponsor')
	{
		var obj1=document.getElementById('sponsor_fname');
		var obj2=document.getElementById('sponsor_lname');
		var obj3=document.getElementById('sponsor_city');
		var obj4=document.getElementById('sponsor_mail');
	}

	if(obj)
	{
		if(obj.checked)
		{
			if(obj1 && obj2 && obj3 && obj4)
			{
				obj1.readOnly=true;
				obj2.readOnly=true;
				obj3.readOnly=true;
				obj4.readOnly=true;
				obj1.setAttribute('class','custom_field_ro');
				obj1.className='custom_field_ro';
				obj2.setAttribute('class','custom_field_ro');
				obj2.className='custom_field_ro';
				obj3.setAttribute('class','custom_field_ro');
				obj3.className='custom_field_ro';
				obj4.setAttribute('class','custom_field_ro');
				obj4.className='custom_field_ro';
				
			}
		}
		else
		{
			if(obj1 && obj2 && obj3 && obj4)
			{
				obj1.readOnly=false;
				obj2.readOnly=false;
				obj3.readOnly=false;
				obj4.readOnly=false;
				obj1.setAttribute('class','custom_field');
				obj1.className='custom_field';
				obj2.setAttribute('class','custom_field');
				obj2.className='custom_field';
				obj3.setAttribute('class','custom_field');
				obj3.className='custom_field';
				obj4.setAttribute('class','custom_field');
				obj4.className='custom_field';
			}
		}
	}
	
}
/*function add_new_element(sub1,hidden2,db_count,myparent)
{
	
	var obj1=document.getElementById(sub1);
	var obj2=document.getElementById(hidden2);
	elements_count++;

	var my_number=parseInt(db_count)+parseInt(elements_count)+1000;
	
	
	if(obj2)
	{
		obj2.innerHTML="<div style='float:left;width:100%;'><span style='float:left;width:150px;'><input type='text' name='new_element[]' class='custom_field' id='my_element"+ elements_count +"'><input type=submit value=\"Запази\" onClick=\"save_new_element('" + hidden2 + "','" + sub1 + "','my_element" + elements_count + "','" + my_number + "','"+ myparent +"');return false;\"></span></div><br>";
	}
}*/

function show_preview(form_id,lang)
{
	var obj=document.getElementById('invitation_preview');
	if(obj)
	{
		var obj1=document.getElementById('back');
		obj.style.height=obj1.offsetHeight + 'px';
		show_hide('invitation_preview');
		if (obj.style.visibility=='visible')
			get_bestman_invitation_form(form_id,lang);
	}
}

var timeoutID = 0;

function refresh_after_tsec()
{
	if (timeoutID != 0)
	{
		clearTimeout(timeoutID);
		timeoutID = 0;
	}
	refresh_invitation_image_timer();
}

function calculate(param1,param2,param3)
{
	var obj1=document.getElementById(param1);
	var obj2=document.getElementById(param2);
	var obj3=document.getElementById(param3);

	var str1=obj1.value;
	var str2=obj2.value;
	
	str1=str1.replace(",",".");
	str2=str2.replace(",",".");

	if ((str1 == '') || (str2 == ''))
	{
		obj1.value = 0;
		obj2.value = 0;
		str1 = 0;
		str2 = 0;
	}
	var my_var=parseFloat(str1)*parseFloat(str2);
	obj3.value=my_var.toFixed(2);
}

function show_hide_calculator(field,element)
{
	var obj=document.getElementById(element);
	var selected_option=document.getElementById(field);
	
	if((selected_option) && (obj))
	{
		if((selected_option.value=='1') || (selected_option.value=='2') || (selected_option.value=='4'))
		{
			obj.style.display='block';
			obj.style.visibility='visible';
		}

		if((selected_option.value=='0') || (selected_option.value=='3'))
		{
			obj.style.display='none';
			obj.style.visibility='hidden';
		}
	}

}

function validate_purchase_fields()
{
	var obj1=document.getElementById('type');
	//var obj2=document.getElementById('arranged');
	var obj3=document.getElementById('total');
	var obj4=document.getElementById('submit_step3');
	var obj5=document.getElementById('quantity');
	var obj6=document.getElementById('price');
	var obj7=document.getElementById('red_notes');
	var submit_disabled = 1;
	if(obj1 && obj3 && obj4){
		if ((((obj1.value=='1') || (obj1.value=='2') || (obj1.value=='4')) && (parseFloat(obj3.value)>0)) || (obj1.value=='3'))
			submit_disabled = 0;
	}
	if(submit_disabled==1){
		obj3.setAttribute('class','custom_field_wrong');
		obj4.setAttribute('disabled',true);
		obj5.setAttribute('class','custom_field_wrong');
		obj6.setAttribute('class','custom_field_wrong');
		obj7.style.visibility = 'visible';
		obj7.style.display = 'inline';	
	}
	else{
		obj3.setAttribute('class','custom_field');
		obj4.removeAttribute('disabled');
		obj5.setAttribute('class','custom_field');
		obj6.setAttribute('class','custom_field');
		obj7.style.visibility = 'hidden';
		obj7.style.display = 'none';
	}
}

function set_target_option(element,lang)
{
	/*var obj1=document.getElementById(option);
	var obj2=document.getElementById(element);

	
	if(obj1 && obj2)
	{
		
		if(obj1.value=='0')
		{
			obj2.innerHTML='';
		}
		
		if(obj1.value=='1')
		{
			
			get_wedding_schedules(element,lang);
		}

		if(obj1.value=='2')
		{
			
			obj2.innerHTML='<span style="float:left;width:250px;"><input type=text name="elementname" class=custom_field value="Задача"></span><span style="float:left;width:180px;"><input type=text name="shop" class=custom_field value="Място:"></span>';
		}
	}*/

	var obj2=document.getElementById(element);
	obj2.innerHTML='<span style="float:left;width:250px;"><input type=text name="elementname" class=custom_field value="Задача"></span><span style="float:left;width:180px;"><input type=text name="shop" class=custom_field value="Място:"></span>';
}
function togle_checkbox(ctrl)
{
	var ob = document.getElementById('element_'+ctrl);
	var ob1 = document.getElementById('element_'+ctrl+'_0');
	var ob2 = document.getElementById('element_'+ctrl+'_1');
	var ob3=document.getElementById('field_'+ctrl);

	if ((ob) && (ob1) && (ob2))
	{
		if (ob.value=='0')
		{
			ob.value='1';
			ob1.style.visibility='hidden';
			ob1.style.display='none';
			ob2.style.visibility='visible';
			ob2.style.display='inline';
			ob3.style.display='inline';
			ob3.style.visibility='visible';
		}
		else
		{
			ob.value='0';
			ob1.style.visibility='visible';
			ob1.style.display='inline';
			ob2.style.visibility='hidden';
			ob2.style.display='none';
			ob3.style.visibility='hidden';
			ob3.style.display='none';
		}
	}
}

function enable_field(my_date,field_id)
{
	var obj=document.getElementById(field_id);
	var obj2=document.getElementById('schedule_date');
	var obj3=document.getElementById('schedule_date_hour');
	var obj4=document.getElementById('schedule_date_minutes');
	var obj6=document.getElementById('target_option');

	if(obj)
	{
		obj.disabled=false;
		obj.setAttribute('class','custom_select');

	}


	if(obj2)
	{
		
		var obj5=document.getElementById('reminder_div');
		var my_str=obj2.value;
		var hour = obj3.selectedIndex;
		var minutes= obj4.selectedIndex;
		var my_new_str=my_str.substr(6,4) + '-' + my_str.substr(3,2) + '-' + my_str.substr(0,2) + ' ' + obj3.options[hour].text + ':' + obj4.options[minutes].text;


		if(my_new_str<my_date)
		{
			obj5.style.display='none';
			obj5.style.visibility='hidden';

			if(field_id!='reminder')
				compare_time('2');
		}
		else
		{
			obj5.style.display='block';
			obj5.style.visibility='visible';

			if(field_id!='reminder')
				compare_time('1');
		}
	}

	/*if((obj6) && (field_id=='schedule_date_hour'))
	{
		obj6.style.display='none';
		obj6.style.visibility='hidden';
	}*/
}

/*function show_message(element,record,targ)
{
	var obj=document.getElementById(element);
	var obj2=document.getElementById(element+'_content');
	obj2.innerHTML='<span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 20px; font-size: 13px;">Сигурни ли сте, че искате да изтриете записа?</span><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 10px; font-size: 12px;"><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 10px; font-size: 12px;"><a href="" onClick="show_hide(\'' + element + '\'); return false;">Затвори<\a></span><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 10px; font-size: 12px;"><a href="index.php?schedule='+ record +'&target=' + targ + '">OK</a></span></span>';
	
	obj.style.display='block';
	obj.style.visibility='visible';

	position_box(element);
}*/

function show_confirmation (question, cancel_label, cancel_js, confirm_label, confirm_js)
{
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');

	obj2.innerHTML='<div style="float:left;background-image:url('+ prev_path +'/images/interface/message_bg.png);background-repeat:repeat-x;background-position:bottom;background-color:#333;color:#fff;height:150px;text-align:center;width:400px;border:solid 4px #666;"><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 20px; font-size: 14px;">' + question + '</span><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 10px; "><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="#" onClick="' + cancel_js + ' return false;" style="color:#a22c2c;">' + cancel_label + '<\a></span><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="#" onClick="' + confirm_js + ' return false;" style="color:#a22c2c;">' + confirm_label + '</a></span></span></div>';

	obj.style.display='block';
	obj.style.visibility='visible';
	position_box_def_size('confirmation',400,150);
}
function show_dialog(question, cancel_label, cancel_js, field_type, field_name ,confirm_label, confirm_js)
{
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');
	var fields_html='';

	if(field_type=='text')
	{
		fields_html+='<input type="text"  style="width:300px;border:solid 2px #666;" name="' + field_name + '" id="' + field_name + '">';	
	}

	obj2.innerHTML='<div style="float:left;background-image:url('+ prev_path +'/images/interface/message_bg.png);background-repeat:repeat-x;background-position:bottom;background-color:#333;color:#fff;height:150px;text-align:center;width:400px;border:solid 4px #666;"><span id="confirmation_message_div" style="float:left;width:398px;font-size:11px;color:#a22c2c;"></span><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 20px; font-size: 14px;">' + question + '</span>' + fields_html + '<span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 10px; "><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="#" onClick="' + cancel_js + ' return false;" style="color:#a22c2c;">' + cancel_label + '<\a></span><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="#" onClick="' + confirm_js + ' return false;" style="color:#a22c2c;">' + confirm_label + '</a></span></span></div>';

	obj.style.display='block';
	obj.style.visibility='visible';
	position_box('confirmation');
}
function show_alert(message,label,cancel_js){
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');
	obj2.innerHTML='<div style="float:left;background:#333 url(../../images/interface/message_bg.png) bottom repeat-x;color:#fff;height:160px;text-align:center;width:400px;border:solid 4px #666;"><span style="float: left;margin:0;padding:20px 0 0 0;width:398px;font-size:14px;">'+message+'</span><span style="float:left; margin:0;padding:10px 0 0 0;width:398px;"><span style="float:left;margin:0;padding:15px 0 0 0;width:398px;font-size:13px;text-align:center;"><a href="javascript:hide_element(\''+cancel_js+'\');" style="color:#a22c2c;">' + label + '<\a></span></span></div>';
	obj.style.display='block';
	obj.style.visibility='visible';
	position_box_def_size('confirmation',400+40,160+30);
}

function show_alert2(message,label,cancel_js){
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');
	obj2.innerHTML='<div style="float:left;background:#333 url('+ prev_path +'/images/interface/message_bg.png) bottom repeat-x;color:#fff;height:160px;text-align:center;width:400px;border:solid 4px #666;"><span style="float: left;margin:0;padding:20px 0 0 0;width:398px;font-size: 14px;">' + message + '</span><span style="float:left; margin:0;padding:10px 0 0 0;width:398px;"><span style="float:left;margin:0;padding:10px 0 0 0;width:398px; font-size:13px;text-align:center;"><a href="#" onClick="' + cancel_js + ' return false;" style="color:#a22c2c;">' + label + '<\a></span></span></div>';
	obj.style.display='block';
	obj.style.visibility='visible';
	position_box('confirmation');
}

function show_big_alert(message,label,cancel_js){
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');

	obj2.innerHTML='<div style="float:left;background-image:url('+ prev_path +'/images/interface/message_bg.png);background-repeat:repeat-x;background-position:bottom;background-color:#333;color:#fff;height:230px;text-align:center;width:500px;border:solid 4px #666;"><span style="float: left; margin: 0; padding: 0; width: 498px; padding-top: 20px; font-size: 14px;">' + message + '</span><span style="float: left; margin: 0; padding: 0; width: 498px; padding-top: 10px;"><span style="float: left; margin: 0; padding: 0; width: 498px; padding-top: 10px; font-size: 13px;text-align:center;"><a href="#" onClick="' + cancel_js + ' return false;" style="color:#a22c2c;">' + label + '<\a></span></span></div>';

	obj.style.display='block';
	obj.style.visibility='visible';
	position_box('confirmation');
}
function show_confirmation_gallery (question, cancel_label, cancel_js, confirm_label, confirm_js){
	var obj=document.getElementById('confirmation');
	var obj2=document.getElementById('confirmation_content');
	obj2.innerHTML='<div style="float:left;background-image:url('+ prev_path +'/images/interface/message_bg.png);background-repeat:repeat-x;background-position:bottom;background-color:#333;color:#fff;height:150px;text-align:center;width:400px;border:solid 4px #666;"><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 20px; font-size: 14px;">' + question + '</span><span style="float: left; margin: 0; padding: 0; width: 398px; padding-top: 10px; "><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="#" onClick="' + cancel_js + ' return false;" style="color:#a22c2c;">' + cancel_label + '<\a></span><span style="float: left; margin: 0; padding: 0; width: 199px; padding-top: 20px; font-size: 13px;"><a href="" onClick="' + confirm_js + ' return false;" style="color:#a22c2c;">' + confirm_label + '</a></span></span></div>';
	obj.style.display='block';
	obj.style.visibility='visible';
	//position_box('confirmation');
}
function show_hide_subjects(element){
	//var obj=document.getElementById(element);
	//var obj2=document.getElementById("arrow_"+element);
	var my_length=subobjects.length;
	for(var i=0;i<my_length;i++){
		var obj=document.getElementById('subobjects'+subobjects[i]);
		var obj2=document.getElementById('arrow_'+subobjects[i]);
		if(obj){
			if(element!=subobjects[i]){
				obj.style.display='none';
				obj.style.visibility='hidden';
				obj2.src=prev_path + 'images/interface/icons/arrow_open.png';
			}
			else{
				if(obj.style.display=='none'){
					obj.style.display='block';
					obj.style.visibility='visible';
					obj2.src=prev_path + 'images/interface/icons/arrow_closed.png';
				}
				else{
					obj.style.display='none';
					obj.style.visibility='hidden';
					obj2.src=prev_path + 'images/interface/icons/arrow_open.png';
				}
			}
		}
	}
}

function position_box(el){
	var ScrollTop = document.body.scrollTop;
	if (ScrollTop == 0){
		if (window.pageYOffset) ScrollTop = window.pageYOffset;
		else ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
	}

	var ScrollLeft = document.body.scrollLeft;
	if (ScrollLeft == 0){
		if (window.pageXOffset) ScrollLeft = window.pageXOffset;
		else ScrollLeft = (document.body.parentElement) ? document.body.parentElement.scrollLeft : 0;
	}

	if (navigator.appName=="Netscape"){
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
	}
	else if (navigator.appName.indexOf("Microsoft")!=-1){
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else if (navigator.appName=="Konqueror"){
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else{
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
		if (((typeof winW) == undefined) || ((typeof winH) == undefined)){
			winW = document.body.parentElement.clientWidth;
			winH = document.body.parentElement.clientHeight;
		}
	}

	var theDiv = document.getElementById(el);
	if (theDiv){
		var theDivwidth = theDiv.offsetWidth;
		var theDivheight = theDiv.offsetHeight;
		
		var ntop = parseInt(ScrollTop + ((winH - theDivheight) / 2));
		var nleft = parseInt(ScrollLeft + ((winW - theDivwidth) / 2));

		theDiv.style.top = ntop + 'px';
		theDiv.style.left = nleft + 'px';
	}
}

function position_box_def_size(el, theDivwidth, theDivheight){
	//alert('ok');
	var ScrollTop = document.body.scrollTop;
	if (ScrollTop == 0)
	{
		if (window.pageYOffset) ScrollTop = window.pageYOffset;
		else ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
	}

	var ScrollLeft = document.body.scrollLeft;
	if (ScrollLeft == 0)
	{
		if (window.pageXOffset) ScrollLeft = window.pageXOffset;
		else ScrollLeft = (document.body.parentElement) ? document.body.parentElement.scrollLeft : 0;
	}

	if (navigator.appName=="Netscape")
	{
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
	}
	else if (navigator.appName.indexOf("Microsoft")!=-1)
	{
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else if (navigator.appName=="Konqueror")
	{
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else
	{
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
		if (((typeof winW) == undefined) || ((typeof winH) == undefined))
		{
			winW = document.body.parentElement.clientWidth;
			winH = document.body.parentElement.clientHeight;
		}
	}

	var ntop = 0;
	var nleft = 0;

	ntop = parseInt(ScrollTop + ((winH - theDivheight) / 2));
	nleft = parseInt(ScrollLeft + ((winW - theDivwidth) / 2));

	var theDiv = document.getElementById(el);
	if (theDiv)
	{
		theDiv.style.top = ntop + 'px';
		theDiv.style.left = nleft + 'px';
	}
}
function position_box_coords(theDivwidth, theDivheight)
{
	var positions=new Array();

	var ScrollTop = document.body.scrollTop;
	if (ScrollTop == 0)
	{
		if (window.pageYOffset) ScrollTop = window.pageYOffset;
		else ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
	}

	var ScrollLeft = document.body.scrollLeft;
	if (ScrollLeft == 0)
	{
		if (window.pageXOffset) ScrollLeft = window.pageXOffset;
		else ScrollLeft = (document.body.parentElement) ? document.body.parentElement.scrollLeft : 0;
	}

	if (navigator.appName=="Netscape")
	{
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
	}
	else if (navigator.appName.indexOf("Microsoft")!=-1)
	{
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else if (navigator.appName=="Konqueror")
	{
		winW = document.body.parentElement.clientWidth;
		winH = document.body.parentElement.clientHeight;
	}
	else
	{
		winW = window.innerWidth-16;
		winH = window.innerHeight-16;
		if (((typeof winW) == undefined) || ((typeof winH) == undefined))
		{
			winW = document.body.parentElement.clientWidth;
			winH = document.body.parentElement.clientHeight;
		}
	}

	var ntop = 0;
	var nleft = 0;

	ntop = parseInt(ScrollTop + ((winH - theDivheight) / 2));
	nleft = parseInt(ScrollLeft + ((winW - theDivwidth) / 2));

	positions[0]=ntop;
	positions[1]=nleft+100;

	return positions;
}
function load_visible_guests_fields(){
	var obj_acc=document.getElementById('accompanied');
	var obj3=document.getElementById('guests_tabs');
	if(obj_acc){
		var first_name=document.getElementById('guest_name_0');
		var subf_ttl=document.getElementById('subforms_title');
		var name_filed=document.getElementById('guest_titular_name');
		if(name_filed) name_filed.innerHTML=first_name.value;
		if(obj_acc.value >0){
			subf_ttl.style.display='block';
			subf_ttl.style.visibility='visible';
		}
	}
	for(var i=0;i<6;i++){
		var element_name='guest_group_'+i;
		var obj=document.getElementById(element_name);
		if(obj){
			var obj1_name='relative_type_div_'+i;
			var obj2_name='relation_side_div_'+i;
			var obj1=document.getElementById(obj1_name);
			var obj2=document.getElementById(obj2_name);

			if(obj.value=='1'){
				if(obj1 && obj2){
					obj1.style.display='block';
					obj1.style.visibility='visible';
					obj2.style.display='block';
					obj2.style.visibility='visible';
				}
			}
			else if((obj.value=='2') || (obj.value=='3')){
				obj2.style.display='block';
				obj2.style.visibility='visible';
			}
			else if((obj.value=='6') || (obj.value=='7')){
				obj2.style.display='none';
				obj2.style.visibility='hidden';
			}
		}
	}
	wedding_guest_form_generator();
	if(obj3){
		obj3.style.display='block';
		obj3.style.visibility='visible';
	}
}
/*function wedding_guest_fields_old(number,element)
{
	var obj1=document.getElementById('guest_group_'+number);
	var obj=document.getElementById('relative_type_'+number);

			
	if(obj1.value=='1')
	{
		
		var obj2=document.getElementById(element+'_'+number);
		var obj3=document.getElementById('relation_side_'+number);
		if(obj2)
		{
			obj2.style.display='block';
			obj2.style.visibility='visible';
		}
		if(obj3)
		{
			//alert(obj3);
			obj3.remove(2);
		}
	}
	else if((obj1.value=='2') ||  (obj1.value=='3') || (obj1.value=='5'))
	{
		
		var obj2=document.getElementById('relative_type_div_'+number);
		var obj3=document.getElementById('relation_side_div_'+number);
		var obj4=document.getElementById('relation_side_'+number);
		if(obj2 && obj3)
		{
			obj2.style.display='none';
			obj2.style.visibility='hidden';
			obj3.style.display='block';
			obj3.style.visibility='visible';
		}
		if(obj4)
		{
			obj4.remove(2);
			try
			{
				obj4.add(new Option(js_lang[0], "mutual"),  null) //add new option to end of "sample"
			}
			catch(e)
			{ //in IE, try the below version instead of add()
				obj4.add(new Option(js_lang[0], "mutual")) //add new option to end of "sample"
			}
		}
		
	}
	else
	{
		var obj2=document.getElementById('relative_type_div_'+number);
		var obj3=document.getElementById('relation_side_div_'+number);
		var obj4=document.getElementById('relation_side_'+number);
		if(obj2 && obj3)
		{
			obj2.style.display='none';
			obj2.style.visibility='hidden';
			obj3.style.display='none';
			obj3.style.visibility='hidden';
		}
		
		if(obj4)
		{
			obj4.remove(2);
			try
			{
 				obj4.add(new Option(js_lang[0], "mutual"),  null) //add new option to end of "sample"
			}
 			catch(e)
			{ //in IE, try the below version instead of add()
  				obj4.add(new Option(js_lang[0], "mutual")) //add new option to end of "sample"
			}
		}
	}

	
	if(obj && (element=='relation_side_div'))
	{
		if(obj.value=='0')
		{
			var obj2=document.getElementById('relation_side_div_'+number);
			if(obj2)
			{
				obj2.style.display='none';
				obj2.style.visibility='hidden';
			}
		}
	}

}*/
function wedding_guest_fields(number,element)
{
	var obj1=document.getElementById('guest_group_'+number);
	var obj2=document.getElementById('relative_type_'+number);
	var obj3=document.getElementById('relation_side_'+number);
	var obj4=document.getElementById('relative_type_div_'+number);
	var obj5=document.getElementById('relation_side_div_'+number);
		

	
	if(obj1.value=='1')
	{
		obj4.style.display='block';
		obj4.style.visibility='visible';
		if(obj2.value=='0')
		{
			obj5.style.display='none';
			obj5.style.visibility='hidden';
		}
		else
		{
			obj5.style.display='block';
			obj5.style.visibility='visible';
		}
		if(obj3)
		{
			obj3.remove(2);

			//kodut dobaven za son i daughter
			if(obj2)
			{
				if((obj2.value=='son') || (obj2.value=='daughter'))
				{	
					try
					{
						obj3.add(new Option(js_lang[0], "mutual"),  null) //add new option to end of "sample"
					}
					catch(e)
					{ //in IE, try the below version instead of add()
						obj3.add(new Option(js_lang[0], "mutual")) //add new option to end of "sample"
					}
				}
				
			}
			//kodut dobaven za son i daughter - end
			
				

		}
		
	}
	else if((obj1.value=='2') ||  (obj1.value=='3'))
	{
		
		obj4.style.display='none';
		obj4.style.visibility='hidden';
		if(obj2.value=='0')
		{
			if(element!='relative_type_div')
			{
				obj5.style.display='none';
				obj5.style.visibility='hidden';
			}
			else
			{
				obj5.style.display='block';
				obj5.style.visibility='visible';
			}
		}
		else
		{
			obj5.style.display='block';
			obj5.style.visibility='visible';
		}

		if(obj3)
		{
			
			obj3.remove(2);
			try
			{
				obj3.add(new Option(js_lang[0], "mutual"),  null) //add new option to end of "sample"
			}
			catch(e)
			{ //in IE, try the below version instead of add()
				obj3.add(new Option(js_lang[0], "mutual")) //add new option to end of "sample"
			}
		}
		
	}
	else if(obj1.value=='4')
	{
		obj4.style.display='none';
		obj4.style.visibility='hidden';
		obj5.style.display='none';
		obj5.style.visibility='hidden';
	}
	else
	{
		obj4.style.display='none';
		obj4.style.visibility='hidden';
		obj5.style.display='none';
		obj5.style.visibility='hidden';

		if(obj3)
		{
			
			obj3.remove(2);
			try
			{
				obj3.add(new Option(js_lang[0], "mutual"),  null) //add new option to end of "sample"
			}
			catch(e)
			{ 
				obj3.add(new Option(js_lang[0], "mutual")) //add new option to end of "sample"
			}
		}
	}

}
function guest_status_reason(element_number){
	var obj1=document.getElementById('guest_status_' + element_number);
	var obj2=document.getElementById('guest_' + element_number);
	var obj3=document.getElementById('calendar_field_' + element_number);
	
	if(obj1 && obj2 && obj3){
		//var attr=obj1.getAttribute('requested_guest');
		if(obj1.value=='no'){
			obj2.style.display='block';
			obj2.style.visibility='visible';
			obj3.style.display='none';
			obj3.style.visibility='hidden';
		}
		else if(obj1.value=='maybe'){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
			obj3.style.display='block';
			obj3.style.visibility='visible';
		}
		else if(obj1.value=='yes'){
			obj2.style.display='none';
			obj2.style.visibility='hidden';
			obj3.style.display='none';
			obj3.style.visibility='hidden';
		}
		else{
			obj2.style.display='none';
			obj2.style.visibility='hidden';
			obj3.style.display='none';
			obj3.style.visibility='hidden';
		}

		/*if((attr=='1') && (obj1.value!='yes')){
			var obj=document.getElementById('guests_in_group');
			if(obj){
				obj.style.display='none';
				obj.style.visibility='hidden';
			}
		}
		else if((attr=='1') && (obj1.value=='yes')){
			var obj=document.getElementById('guests_in_group');
			if(obj && (persons_count>1)){
				obj.style.display='block';
				obj.style.visibility='visible';
			}
		}*/
	}
}
function wedding_guest_form_old(max_person)
{
	
	var obj1=document.getElementById('accompanied');
	var obj3=document.getElementById('subforms_title');
	
	if(obj1)
	{
		
		if(obj1.value!='0')
		{

			
			var count=parseInt(obj1.value);
			
			if (max_person < count) max_person = count;
			
			/*for (var i=1; i <= count; i++)
			{
				var obj2=document.getElementById('form_'+i);
				if (obj2)
				{
					
					show_element('form_'+i);
				}
			}
			
			for(i = count+1; i <= max_person; i++)
			{
				var obj2=document.getElementById('form_'+i);
				if (obj2)
				{
					obj2.style.visibility = 'hidden';
					obj2.style.display = 'none';
				}
			}*/

			for(i=0;i<=max_person;i++)
			{
				if(i==count)
					show_element('form_'+i);
				else
					hide_element('form_'+i);
			}
			

			/*for (i = count; i <= max_person; i++)
			{
				var obj2=document.getElementById('form_'+i);
				if (obj2)
				{
					obj2.style.visibility = 'hidden';
					obj2.style.display = 'none';
				}
			}*/
			if(obj3)
			{
				
				obj3.style.display = 'block';
				obj3.style.visibility = 'visible';
				
			}
		}
		else
		{
			for (var i=2; i <= max_person; i++)
			{
				var obj2=document.getElementById('form_'+i);
				if (obj2)
				{
					obj2.style.visibility = 'hidden';
					obj2.style.display = 'none';
				}
			}

			if(obj3)
			{
				obj3.style.visibility = 'hidden';
				obj3.style.display = 'none';
			}
		}
	}
}
function wedding_guest_form_generator()
{
	var obj1=document.getElementById('accompanied');
	var obj2=document.getElementById('guests_tabs');
	var guest_name='';
	var html='';
	var style='';
	if(obj1)
	{
		for(i=0;i<=obj1.value;i++)
		{
			guest_name='';
			var guest_name_object=document.getElementById('guest_name_'+i);
			if(guest_name_object)
				guest_name= guest_name_object.value;

			style='font-size:12px;';
			if(i==0){
				style='font-size:16px;color:#000;';
			}
			if(guest_name!='')
				html+='<span id="guest_tab_'+ i +'"><a href="" onClick="wedding_guest_form(5,' + i +');return false;" style="'+ style +'">'+ guest_name +'</a></span> | ';
			else
			{
				if(i!=0)
					html+='<span id="guest_tab_'+ i +'"><a href="" onClick="wedding_guest_form(5,' + i +');return false;" style="'+ style +'">'+ js_lang[30] + ' '+i+'</a></span> | ';
				else
					html+='<span id="guest_tab_'+ i +'"><a href="" onClick="wedding_guest_form(5,' + i +');return false;" style="'+ style +'">'+ js_lang[21] + ' </a></span> | ';
			}
		}
	}


	if(obj1)
		if(obj1.value=='0')
			html='';
	/*if(obj1)
	{
		if(obj1.value=='0')
		{
			html='';
			var submit_obj=document.getElementById('form_submit');
			if(submit_obj)
			{
				submit_obj.style.display='block';
				submit_obj.style.visibility='visible';
			}
		}
		else
		{
			var submit_obj=document.getElementById('form_submit');
			if(submit_obj)
			{
				submit_obj.style.display='none';
				submit_obj.style.visibility='hidden';
			}	
		}
	}*/

	if(obj2)
	 obj2.innerHTML=html;
	
}
function wedding_guest_form(max_person,visible_form)
{
	var obj1=document.getElementById('accompanied');
	var obj3=document.getElementById('subforms_title');
	//var submit_button=document.getElementById('form_submit');
	var count=0;

	if(obj1)
	{
		
		if(obj1.value!='0')
			var count=parseInt(obj1.value);
	}


	for(i=0;i<=max_person;i++)
	{
		if(i==visible_form)
		{
			show_element('form_'+i);
			var guest_tab=document.getElementById('guest_tab_'+i);
			if(guest_tab){
				guest_tab.childNodes[0].style.fontSize='16px';
				guest_tab.childNodes[0].style.color='#000';
			}
		}
		else
		{
			
			hide_element('form_'+i);
			var guest_tab=document.getElementById('guest_tab_'+i);
			if(guest_tab){
				guest_tab.childNodes[0].style.fontSize='12px';
				guest_tab.childNodes[0].style.color='#666';
			}
		}

	}

	/*if(visible_form==count)
	{
		var submit_obj=document.getElementById('form_submit');
		if(submit_obj)
		{
			submit_obj.style.display='block';
			submit_obj.style.visibility='visible';
		}
	}
	else
	{
		var submit_obj=document.getElementById('form_submit');
		if(submit_obj)
		{
			submit_obj.style.display='none';
			submit_obj.style.visibility='hidden';
		}
	}*/
	
}
function alter_name(element)
{
	
	/*var obj=document.getElementById('guest_titular_name');
	obj.innerHTML=element.value;*/
	
	var my_str=element.id;
	var my_element_num=my_str.substr(11,my_str.length);
	
	var tab_name='guest_tab_'+my_element_num;
	var obj=document.getElementById(tab_name);
	if(obj)
	{
		obj.innerHTML='<a href="" onClick="wedding_guest_form(5,'+ my_element_num +');return false;">'+ element.value +'</a>';
	}

}

function rotate_multi_news()
{
	var obj=document.getElementById('column_1_x');
	if(obj)
	{
		var left_pos_page = parseInt(column_1_x_pos / 5);

		var ob=document.getElementById('label_1_x_'+(column_1_x_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='normal';

		if(column_1_x_pos < advertisement_column_1_x_titles.length - 1)
			column_1_x_pos++;
		else
			column_1_x_pos=0;

		var left_pos_page = parseInt(column_1_x_pos / 5);

		var j = 0;
		for (var i = left_pos_page * 5; i < (left_pos_page + 1) * 5; i++)
		{
			if (i < advertisement_column_1_x_titles.length)
			{
				var ob=document.getElementById('label_1_x_' + j);
				if(ob) ob.innerHTML = advertisement_column_1_x_titles[i];
			}
			else
			{
				var ob=document.getElementById('label_1_x_' + j);
				if(ob) ob.innerHTML = '&nbsp;';
			}
			j++;
		}
		var ob=document.getElementById('image_1_x');
		if(ob)
		{
			if (advertisement_column_1_x_images[column_1_x_pos] != '')
			{
				ob.src=advertisement_column_1_x_images[column_1_x_pos];
				ob.style.visibility = 'visible';
				ob.style.display = 'block';
			}
			else
			{
				ob.style.visibility = 'hidden';
				ob.style.display = 'none';
			}
		}
		var ob=document.getElementById('label_1_x_'+(column_1_x_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='bold';
	}

	var obj=document.getElementById('column_1_y');
	if(obj)
	{
		var left_pos_page = parseInt(column_1_y_pos / 5);

		var ob=document.getElementById('label_1_y_'+(column_1_y_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='normal';

		if(column_1_y_pos < advertisement_column_1_y_titles.length - 1)
			column_1_y_pos++;
		else
			column_1_y_pos=0;

		var left_pos_page = parseInt(column_1_y_pos / 5);

		var j = 0;
		for (var i = left_pos_page * 5; i < (left_pos_page + 1) * 5; i++)
		{
			if (i < advertisement_column_1_y_titles.length)
			{
				var ob=document.getElementById('label_1_y_' + j);
				if(ob) ob.innerHTML = advertisement_column_1_y_titles[i];
			}
			else
			{
				var ob=document.getElementById('label_1_y_' + j);
				if(ob) ob.innerHTML = '&nbsp;';
			}
			j++;
		}
		var ob=document.getElementById('image_1_y');
		if(ob)
		{
			if (advertisement_column_1_y_images[column_1_y_pos] != '')
			{
				ob.src=advertisement_column_1_y_images[column_1_y_pos];
				ob.style.visibility = 'visible';
				ob.style.display = 'block';
			}
			else
			{
				ob.style.visibility = 'hidden';
				ob.style.display = 'none';
			}
		}
		var ob=document.getElementById('label_1_y_'+(column_1_y_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='bold';
	}

	var obj=document.getElementById('column_2_y');
	if(obj)
	{
		var left_pos_page = parseInt(column_2_y_pos / 5);

		var ob=document.getElementById('label_2_y_'+(column_2_y_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='normal';

		if(column_2_y_pos < advertisement_column_2_y_titles.length - 1)
			column_2_y_pos++;
		else
			column_2_y_pos=0;

		var left_pos_page = parseInt(column_2_y_pos / 5);

		var j = 0;
		for (var i = left_pos_page * 5; i < (left_pos_page + 1) * 5; i++)
		{
			if (i < advertisement_column_2_y_titles.length)
			{
				var ob=document.getElementById('label_2_y_' + j);
				if(ob) ob.innerHTML = advertisement_column_2_y_titles[i];
			}
			else
			{
				var ob=document.getElementById('label_2_y_' + j);
				if(ob) ob.innerHTML = '&nbsp;';
			}
			j++;
		}
		var ob=document.getElementById('image_2_y');
		if(ob)
		{
			if (advertisement_column_2_y_images[column_2_y_pos] != '')
			{
				ob.src=advertisement_column_2_y_images[column_2_y_pos];
				ob.style.visibility = 'visible';
				ob.style.display = 'block';
			}
			else
			{
				ob.style.visibility = 'hidden';
				ob.style.display = 'none';
			}
		}
		var ob=document.getElementById('label_2_y_'+(column_2_y_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='bold';
	}

	var obj=document.getElementById('column_2_x');
	if(obj)
	{
		var left_pos_page = parseInt(column_2_x_pos / 5);

		var ob=document.getElementById('label_2_x_'+(column_2_x_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='normal';

		if(column_2_x_pos < advertisement_column_2_x_titles.length - 1)
			column_2_x_pos++;
		else
			column_2_x_pos=0;

		var left_pos_page = parseInt(column_2_x_pos / 5);

		var j = 0;
		for (var i = left_pos_page * 5; i < (left_pos_page + 1) * 5; i++)
		{
			if (i < advertisement_column_2_x_titles.length)
			{
				var ob=document.getElementById('label_2_x_' + j);
				if(ob) ob.innerHTML = advertisement_column_2_x_titles[i];
			}
			else
			{
				var ob=document.getElementById('label_2_x_' + j);
				if(ob) ob.innerHTML = '&nbsp;';
			}
			j++;
		}
		var ob=document.getElementById('image_2_x');
		if(ob)
		{
			if (advertisement_column_2_x_images[column_2_x_pos] != '')
			{
				ob.src=advertisement_column_2_x_images[column_2_x_pos];
				ob.style.visibility = 'visible';
				ob.style.display = 'block';
			}
			else
			{
				ob.style.visibility = 'hidden';
				ob.style.display = 'none';
			}
		}
		var ob=document.getElementById('label_2_x_'+(column_2_x_pos - (left_pos_page * 5)));
		if(ob) ob.style.fontWeight='bold';
	}
}

function rotate_single_news()
{
	var obj=document.getElementById('column_1_center');
	if(obj)
	{
		if(column_1_center_pos < advertisement_column_1_center_titles.length - 1)
			column_1_center_pos++;
		else
			column_1_center_pos=0;

		var img_element='image_1_center';
		var ob=document.getElementById(img_element);
		if(ob) ob.src=advertisement_column_1_center_images[column_1_center_pos];

		var label_element='label_1_center';
		var ob=document.getElementById(label_element);
		if(ob) ob.innerHTML=advertisement_column_1_center_titles[column_1_center_pos] + '<br>' + advertisement_column_1_center_texts[column_1_center_pos];
	}

	var obj=document.getElementById('column_2_center');
	if(obj)
	{
		if(column_2_center_pos < advertisement_column_2_center_titles.length - 1)
			column_2_center_pos++;
		else
			column_2_center_pos=0;

		var img_element='image_2_center';
		var ob=document.getElementById(img_element);
		if(ob) ob.src=advertisement_column_2_center_images[column_2_center_pos];

		var label_element='label_2_center';
		var ob=document.getElementById(label_element);
		if(ob) ob.innerHTML=advertisement_column_2_center_titles[column_2_center_pos] + '<br>' + advertisement_column_2_center_texts[column_2_center_pos];
	}
}

function rotate_advertisment()
{
	if (adv_stop == true) return;

	var adv_old_position = adv_position;

	if (adv_direction == 1)
	{
		adv_position++;
		if (adv_position >= adv_prefix.length)
		{
			adv_direction = 2;
			adv_position = adv_prefix.length - 2;
		}
	}
	else
	{
		adv_position--;
		if (adv_position < 0)
		{
			adv_direction = 1;
			adv_position = 1;
		}
	}

	var ob_b = document.getElementById(adv_prefix[adv_position] + '_button');
	var ob_i = document.getElementById(adv_prefix[adv_position] + '_info');
	ob_b.style.visibility = 'hidden';
	ob_b.style.display = 'none';
	ob_i.style.visibility = 'visible';
	ob_i.style.display = 'block';

	var ob_b = document.getElementById(adv_prefix[adv_old_position] + '_button');
	var ob_i = document.getElementById(adv_prefix[adv_old_position] + '_info');
	ob_b.style.visibility = 'visible';
	ob_b.style.display = 'block';
	ob_i.style.visibility = 'hidden';
	ob_i.style.display = 'none';
}

function select_advertisment(adv_no)
{

	if (adv_stop == false)
		clearInterval(adv_interval);
	adv_stop = true;

	if (adv_no >= adv_prefix.length) adv_no = 0;

	/*if(adv_position!=adv_no)
	{
		var ob_b = document.getElementById(adv_prefix[adv_no] + '_button');
		var ob_i = document.getElementById(adv_prefix[adv_no] + '_info');
		ob_b.style.visibility = 'hidden';
		ob_b.style.display = 'none';
		ob_i.style.visibility = 'visible';
		ob_i.style.display = 'block';
	
		var ob_b = document.getElementById(adv_prefix[adv_position] + '_button');
		var ob_i = document.getElementById(adv_prefix[adv_position] + '_info');
		ob_b.style.visibility = 'visible';
		ob_b.style.display = 'block';
		ob_i.style.visibility = 'hidden';
		ob_i.style.display = 'none';
	}
	
	adv_position = adv_no;*/
	
}

function change_gallery_image(number)
{
	var obj1=document.getElementById('image_title');
	var obj2=document.getElementById('image_preview');
	if(obj1 && obj2)
	{
		obj1.innserHTML=gallery_images_titles[number];
		obj2.src=gallery_images[number];
	}
}

function change_gallery_line(direction)
{
	
	if(direction=='prev' && gallery_images_pos>0)
		gallery_images_pos--;
	
	if(direction=='next' && gallery_images_pos<pages_count)
		gallery_images_pos++;

	
	var first_image_to_show=gallery_images_pos*images_line_count;
	var last_image_to_show=first_image_to_show+images_line_count-1;

	var first_image_to_hide=0;
	var last_image_to_hide=0;

	//alert(first_image_to_show + ' ' + last_image_to_show);

	for(var i=first_image_to_show;i<=last_image_to_show;i++)
	{
		var element='gallery_image_'+i;
		var obj=document.getElementById(element);
		if(obj)
		{
			obj.style.display='block';
			obj.style.visibility='visible';
		}
		
	}

	if(direction=='prev')
	{
		first_image_to_hide=(gallery_images_pos+1)*images_line_count;
		
	}
	
	if(direction=='next')
	{
		first_image_to_hide=(gallery_images_pos-1)*images_line_count;
	}
	last_image_to_hide=first_image_to_hide+images_line_count-1;

	//alert(first_image_to_hide + ' ' + last_image_to_hide);
	for(var i=first_image_to_hide;i<=last_image_to_hide;i++)
	{
		var element='gallery_image_'+i;
		var obj=document.getElementById(element);
		if (obj)
		{
			obj.style.display='none';
			obj.style.visibility='hidden';
		}
		
	}
}
function change_cell_style(cell,my_style)
{
	var my_span='cell_'+ cell;
	var obj=document.getElementById(my_span);
	var my_date='cell_'+ cell + '_date';
	var obj2=document.getElementById(my_date);

	//cell_class=obj.getAttribute('class');
	cell_class=obj.className;
	if(obj && obj2)
	{
		if(my_style=='darck')
		{
			obj.style.backgroundImage="url('../images/interface/cell_active.jpg')";
		}
		else
		{
			var image_name='../images/interface/' + cell_class  + '.jpg';
			obj.style.backgroundImage="url('"+ image_name +"')";
		}
	}
}
function uncheck(element)
{
	var ob=document.getElementById(element);
	if(ob)
	{
		ob.checked=checked_flag=false;
	}
		
}
function filter(element,option)
{
	filter_purchases_schedules(option,element.value);
}
function filter_purchases_schedules(element,by_type)
{

	
	purchase_1_count=1;
	purchase_2_count=1;
	purchase_3_count=1;
	purchase_4_count=1;
	purchase_5_count=1;
	purchase_6_count=1;
	schedule_1_count=1;
	schedule_2_count=1;
	schedule_3_count=1;
	schedule_4_count=1;
	schedule_5_count=1;
	schedule_6_count=1;

	
	if(by_type=='unarranged')
	{
		
		filter_by_label_id=10;
		if(element=='purchase')
			purchases_filter='unarranged';
		
		else
			schedules_filter='unarranged';
		

		
	}
	else if(by_type=='arranged')
	{
		filter_by_label_id=11;
		if(element=='purchase')
			purchases_filter='arranged';
		
		else
			schedules_filter='arranged';
		
	}
	else if(by_type=='unorganized')
	{
		filter_by_label_id=12;
		if(element=='purchase')
			purchases_filter='unorganized';
		
		else
			schedules_filter='unorganized';
	}
	else if(by_type=='organized')
	{
		filter_by_label_id=13;
		if(element=='purchase')
			purchases_filter='organized';
		
		else
			schedules_filter='organized';
	}

	
	
	if(element=='purchase')
	{
		purchase_1_max = Math.ceil(count_objects_group(element, 1) / records_count_on_group);
		purchase_2_max = Math.ceil(count_objects_group(element, 2) / records_count_on_group);
		purchase_3_max = Math.ceil(count_objects_group(element, 3) / records_count_on_group);
		purchase_4_max = Math.ceil(count_objects_group(element, 4) / records_count_on_group);
		purchase_5_max = Math.ceil(count_objects_group(element, 5) / records_count_on_group);
		purchase_6_max = Math.ceil(count_objects_group(element, 6) / records_count_on_group);
	}
	else if(element=='schedule')
	{
		schedule_1_max = Math.ceil(count_objects_group(element, 1) / records_count_on_group);
		schedule_2_max = Math.ceil(count_objects_group(element, 2) / records_count_on_group);
		schedule_3_max = Math.ceil(count_objects_group(element, 3) / records_count_on_group);
		schedule_4_max = Math.ceil(count_objects_group(element, 4) / records_count_on_group);
		schedule_5_max = Math.ceil(count_objects_group(element, 5) / records_count_on_group);
		schedule_6_max = Math.ceil(count_objects_group(element, 6) / records_count_on_group);
	}
	
	
	prev_objects_group(element,1);
	prev_objects_group(element,2);
	prev_objects_group(element,3);
	prev_objects_group(element,4);
	prev_objects_group(element,5);
	prev_objects_group(element,6);
	steps_visibility(element,1);
	steps_visibility(element,2);
	steps_visibility(element,3);
	steps_visibility(element,4);
	steps_visibility(element,5);
	steps_visibility(element,6);

}

function hide_other_in_sub_group(id, column_pool, object)
{
	for (var i = 0; i < column_pool.length; i++)
	{
		if (column_pool[i] == id)
		{
			show_element('div_' + object + '_' + column_pool[i]);
			// pokazva [-] na sushtia index img.src
		}
		else
		{
			hide_element('div_' + object + '_' + column_pool[i]);
			// pokazva [+] na sushtia index
		}
	}
}

function show_hint(element,hint_class_name)
{

	
	element.childNodes[1].style.display='block';
	element.childNodes[1].style.visibility='visible';
	
	
}
function hide_hint(element)
{
	
	
	element.style.display='none';
	element.style.visibility='hidden';
	
}
function change_gift_fields(element)
{

	
	var ob=document.getElementById('confirmed_guests_count');
	var ob2=document.getElementById('price_per_guest');
	
	var missing=document.getElementById('missing_accepted_guests');
	var accepted=document.getElementById('accepted_guests');

	//if (!ob) return false;
	//if (!ob2) return false;
	
	//persons = gift_guests_count;
	persons=0;


	for (var i = 0; i < persons_count; i++)
	{
		var obs = document.getElementById('guest_status_' + (i+1));
		var obj= document.getElementById('customer_field_'+ (i+1));
		var obj_no=document.getElementById('customer_field_no_'+ (i+1));
		if (obs.options[obs.selectedIndex].value == "yes")
		{
			persons++;
			

			if(missing && accepted)
			{
				missing.style.display='none';
				missing.style.visibility='hidden';
				accepted.style.display='block';
				accepted.style.visibility='visible';
			}

			obj.style.display='block';
			obj.style.visibility='visible';
			obj_no.style.display='none';
			obj_no.style.visibility='hidden';	
			
		}
		else
		{
			obj.style.display='none';
			obj.style.visibility='hidden';
			obj_no.style.display='block';
			obj_no.style.visibility='visible';

			if(missing && accepted && (persons==0))
			{
				missing.style.display='block';
				missing.style.visibility='visible';
				accepted.style.display='none';
				accepted.style.visibility='hidden';
				
			}
		}
	}

	
	/*if (obs.options[obs.selectedIndex].value == "yes")
	{
		if(new_obj)
			{
				new_obj.style.display='block';
				new_obj.style.visibility='visible';
				
			}

			if(new_obj_no)
			{
				new_obj_no.style.display='none';
				new_obj_no.style.visibility='hidden';	
			}
		
			if(missing)
			{
				missing.style.display='none';
				missing.style.visibility='hidden';
			}
	}
	else
	{
		if(new_obj)
			{
				new_obj.style.display='none';
				new_obj.style.visibility='hidden';
				
			}

			if(new_obj_no)
			{
				new_obj_no.style.display='block';
				new_obj_no.style.visibility='visible';
			}

			if(missing)
			{
				missing.style.display='block';
				missing.style.visibility='visible';
			}
	}*/

	
	var new_price = gift_price / persons;

	ob.innerHTML = persons;
	/*ob2.innerHTML = (Math.round(new_price * 100)) / 100;*/
}
function show_prev_object(element)
{
	var number=1;
	var obj;

	//console.log(current_bridesmaid_object,current_bridesman_object);

	if(element=='bridesmaid')
	{
		if(current_bridesmaid_object>1)
			number=current_bridesmaid_object-1;
		
	}
	
	if(element=='bridesman')
	{
		if(current_bridesman_object>1)
			number=current_bridesman_object-1;
	}
	
	

	for(var j=1;j<11;j++)
	{
		if(j!=number)
			hide_element(element + '_' +j);
		else
			show_element(element + '_' +number);
	}

	if(element=='bridesmaid')
		current_bridesmaid_object=number;

	if(element=='bridesman')
		current_bridesman_object=number;

	//console.log(current_bridesmaid_object,current_bridesman_object);
	
}
function show_next_object(element){
	var number=10;
	var obj;
	if(element=='bridesmaid'){
		if(current_bridesmaid_object<10) number=current_bridesmaid_object+1;
	}
	if(element=='bridesman'){
		if(current_bridesman_object<10) number=current_bridesman_object+1;
	}
	if(element=='friend'){
		if(current_friend_object<10) number=current_friend_object+1;
	}
	for(var j=1;j<11;j++){
		if(j<=number) show_element(element + '_' +number);
	}
	if(element=='bridesmaid') current_bridesmaid_object=number;
	if(element=='bridesman') current_bridesman_object=number;
	if(element=='friend') current_friend_object=number;
}
function more_than_one_same_value(my_arr1,my_arr2){
	var my_plain_arr=new Array();
	var wrong_fields=new Array();
	for(var i=0;i<my_arr1.length;i++){
		my_plain_arr.push(my_arr1[i]);
	}
	for(var i=0;i<my_arr2.length;i++){
		my_plain_arr.push(my_arr2[i]);
	}
	for(var i=0;i<my_plain_arr.length;i++){
		for(var j=i+1;j<my_plain_arr.length;j++){
			if(my_plain_arr[i]==my_plain_arr[j]){
				wrong_fields.push(i);
				wrong_fields.push(j);
			}
		}
	}
	return wrong_fields;
}

function wedding_organizer_steps(my_step){
	location.href = "index.php?page=wedding_organizer&step=" + my_step;
}
function filter_guests(){
	var filter_by_group=document.getElementById('filter_by_group');
	var filter_by_side=document.getElementById('filter_by_side');
	var fdiv=document.getElementById('list_guests_unacc');
	if(filter_by_group && filter_by_side && fdiv){
		var by_group=filter_by_group.value;
		var by_side=filter_by_side.value;
		for (var i = 0; i < fdiv.childNodes[1].childNodes.length; i++){
			var relgr = fdiv.childNodes[1].childNodes[i].getAttribute('filterrelationgroup');
			var relsd = fdiv.childNodes[1].childNodes[i].getAttribute('relationside');
			if((relgr!=null) && (relsd!=null)){
				relgr=relgr.toString();
				relsd=relsd.toString();
				if((by_group!='0') && (by_side!='0')){
					if((relgr==by_group) && (relsd==by_side)){
						fdiv.childNodes[1].childNodes[i].style.display='block';
						fdiv.childNodes[1].childNodes[i].style.visibility='visible';
					}
					else{
						fdiv.childNodes[1].childNodes[i].style.display='none';
						fdiv.childNodes[1].childNodes[i].style.visibility='hidden';
					}
				}
				else if((by_group=='0') && (by_side=='0')){
					fdiv.childNodes[1].childNodes[i].style.display='block';
					fdiv.childNodes[1].childNodes[i].style.visibility='visible';
				}
				else if((by_group!='0') && (by_side=='0')){
					if(relgr==by_group){
						fdiv.childNodes[1].childNodes[i].style.display='block';
						fdiv.childNodes[1].childNodes[i].style.visibility='visible';
					}
					else{
						fdiv.childNodes[1].childNodes[i].style.display='none';
						fdiv.childNodes[1].childNodes[i].style.visibility='hidden';
					}
				}
				else if((by_group=='0') && (by_side!='0')){
					if(relsd==by_side){
						fdiv.childNodes[1].childNodes[i].style.display='block';
						fdiv.childNodes[1].childNodes[i].style.visibility='visible';
					}
					else{
						fdiv.childNodes[1].childNodes[i].style.display='none';
						fdiv.childNodes[1].childNodes[i].style.visibility='hidden';
					}
				}
			}
		}
	}
}
function show_next_image(my_num){
	hide_element('image_' + my_num);
	my_num++;
	if(my_num>elements_count) my_num=1;
	show_element('image_' + my_num);
}
function show_image(my_num){
	for(var i=1;i<=elements_count;i++){
		var obj=document.getElementById('image_' + i);
		if(obj){
			if(i==my_num) show_element('image_' + i);
			else hide_element('image_' + i);
		}
	}
}

function priceFormat(e){
var c=0;
if(e.keyCode) c=e.keyCode; else c=e.which;
switch(c){case 48:case 46:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}
}
function dateFormat(e){
var c=0;
if(e.keyCode) c=e.keyCode; else c=e.which;
switch(c){case 48:case 45:case 46:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}
}
function guest_input(t,n){
	var o=gE(n);
	if(t.checked){
		if(o.value==''){
			o.value=gE('enter_mail').value;
			o.style.color='red';
			o.className='gm_rw';
			o.readOnly=false;
			o.style.display='block';
		}
	}
	else{
		if(!o.readOnly){
			o.className='gm_ro';
			o.readOnly=true;
			o.value='';
			o.style.display='block';
		}
	}
}
function add_city(){
	var o=gE('cities');var v=gE('add_city_txt').value;var f=true;
	var a=Array();
	if(v!=''){
		for(var i=0;i<o.getElementsByTagName("label").length; i++){
			if(o.getElementsByTagName("label")[i].innerHTML.toLowerCase()==v.toLowerCase()) f=false;
			a[i]=o.getElementsByTagName("input")[i].checked;
		}
		if(f){
			var t=new Date();
			var m=t.getTime();
			o.innerHTML+='<div><input type="checkbox" name="cities[]" id="cty'+m+'" value="'+v+'" /><label for="cty'+m+'">'+v+'</label></div>';
			for(var i=0;i<o.getElementsByTagName("input").length; i++){
				if(a[i]) o.getElementsByTagName("input")[i].checked=true;
			}
		}
		else alert('Този град вече съществува в списъка с градове!!!');
		o.scrollTop=o.scrollHeight;
		o.scrollIntoView(0,o.scrollHeight);
	}
}
function clear_addf(){
	var o=gE('addf');
	for(var i=0;i<o.getElementsByTagName("input").length; i++){
		if(o.getElementsByTagName("input")[i].name=='cname' || o.getElementsByTagName("input")[i].name=='cid') o.getElementsByTagName("input")[i].value='';
	}
	for(var i=0;i<o.getElementsByTagName("textarea").length; i++){
		o.getElementsByTagName("textarea")[i].value='';
	}
}
function delete_ask(q){
	var o=gE('form1').getElementsByTagName('input');var f=false;
	for(var i=0;i<o.length;i++){if(o[i].className='radio' && o[i].checked) f=true;}
	if(f && confirm(q)){gE('btn').value='del';document.form1.submit();}
}
function delete_ask2(id){
if (confirm('Сигурни ли сте че искате да изтриете този листинг?')) {
var formid = 'delform'+id;
	gE(formid).submit();
}
}
function delete_ask1(o, q){if(confirm(q)) return true; else return false;}
function prevPage(){var o=gE('pn');if(o.value>1){o.value--;document.form1.submit();}}
function nextPage(t){var o=gE('pn');if(o.value<t){o.value++;document.form1.submit();}}
function pageN(t){var o=gE('pn');o.value=t;document.form1.submit();}
var Drag={
	obj:null,
	init:function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper){
		p=o.getElementsByTagName('div');
		for(var i=0;i<p.length;i++){
			if(p[i].className=='left_top_corner') p[i].onmousedown=Drag.start;
			else if(p[i].className=='top_border') p[i].onmousedown=Drag.start;
			else if(p[i].className=='right_top_corner') p[i].onmousedown=Drag.start;
		}
		o.hmode=bSwapHorzRef?false:true;
		o.vmode=bSwapVertRef?false:true;
		o.root=o;
		if(o.hmode && isNaN(parseInt(o.root.style.left))) o.root.style.left="0px";
		if(o.vmode && isNaN(parseInt(o.root.style.top))) o.root.style.top="0px";
		if(!o.hmode && isNaN(parseInt(o.root.style.right))) o.root.style.right="0px";
		if(!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom="0px";
		o.minX=typeof minX!='undefined'?minX:null;
		o.minY=typeof minY!='undefined'?minY:null;
		o.maxX=typeof maxX!='undefined'?maxX:null;
		o.maxY=typeof maxY!='undefined'?maxY:null;
		o.xMapper=fXMapper?fXMapper:null;
		o.yMapper=fYMapper?fYMapper:null;
		o.root.onDragStart=new Function();
		o.root.onDragEnd=new Function();
		o.root.onDrag=new Function();
	},
	start:function(e){
		var o=Drag.obj=this.parentNode;
		e=Drag.fixE(e);
		var y=parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
		var x=parseInt(o.hmode ? o.root.style.left : o.root.style.right );
		o.root.onDragStart(x,y);
		o.lastMouseX=e.clientX;
		o.lastMouseY=e.clientY;
		if(o.hmode){
			if(o.minX!=null) o.minMouseX=e.clientX-x+o.minX;
			if(o.maxX!=null) o.maxMouseX=o.minMouseX+o.maxX-o.minX;
		}else{
			if(o.minX!=null) o.maxMouseX=-o.minX+e.clientX+x;
			if(o.maxX!=null) o.minMouseX=-o.maxX+e.clientX+x;
		}
		if(o.vmode){
			if(o.minY!=null) o.minMouseY=e.clientY-y+o.minY;
			if(o.maxY!=null) o.maxMouseY=o.minMouseY+o.maxY-o.minY;
		}else{
			if(o.minY!=null) o.maxMouseY=-o.minY+e.clientY+y;
			if(o.maxY!=null) o.minMouseY=-o.maxY+e.clientY+y;
		}
		document.onmousemove=Drag.drag;
		document.onmouseup=Drag.end;
		return false;
	},
	drag:function(e){
		e=Drag.fixE(e);
		var o=Drag.obj;
		var ey=e.clientY;
		var ex=e.clientX;
		var y=parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
		var x=parseInt(o.hmode ? o.root.style.left : o.root.style.right );
		var nx, ny;
		if(o.minX!=null) ex=o.hmode ? Math.max(ex,o.minMouseX):Math.min(ex,o.maxMouseX);
		if(o.maxX!=null) ex=o.hmode ? Math.min(ex,o.maxMouseX):Math.max(ex,o.minMouseX);
		if(o.minY!=null) ey=o.vmode ? Math.max(ey,o.minMouseY):Math.min(ey,o.maxMouseY);
		if(o.maxY!=null) ey=o.vmode ? Math.min(ey,o.maxMouseY):Math.max(ey,o.minMouseY);
		nx=x+((ex-o.lastMouseX)*(o.hmode?1:-1));
		ny=y+((ey-o.lastMouseY)*(o.vmode?1:-1));
		if(o.xMapper) nx=o.xMapper(y);
		else if(o.yMapper) ny=o.yMapper(x);
		Drag.obj.root.style[o.hmode?"left":"right"]=nx+"px";
		Drag.obj.root.style[o.vmode?"top":"bottom"]=ny+"px";
		Drag.obj.lastMouseX=ex;
		Drag.obj.lastMouseY=ey;
		Drag.obj.root.onDrag(nx,ny);
		return false;
	},
	end:function(){
		document.onmousemove=null;
		document.onmouseup=null;
		Drag.obj.root.onDragEnd(parseInt(Drag.obj.root.style[Drag.obj.hmode?"left":"right"]),parseInt(Drag.obj.root.style[Drag.obj.vmode?"top":"bottom"]));
		Drag.obj = null;
	},
	fixE:function(e){
		if(typeof e=='undefined') e=window.event;
		if(typeof e.layerX=='undefined') e.layerX=e.offsetX;
		if(typeof e.layerY=='undefined') e.layerY=e.offsetY;
		return e;
	}
};
function resize_Iframe(frameid){
	var fr=gE(frameid);
	if (fr && !window.opera){
		fr.style.display="block"
		if (fr.contentDocument && fr.contentDocument.body.offsetHeight){
			fr.height = fr.contentDocument.body.offsetHeight+10;
		}
		else if (fr.document && fr.document.body.scrollHeight){
			fr.height=0;
			fr.height = fr.contentWindow.document.body.scrollHeight;
		}
		if(fr.height<180) fr.height=180;
	}
}
function showPWF(){
	var isIE = (window.ActiveXObject) ? true : false;
	var o=gE('login').getElementsByTagName('tr');
	for(var i=0;i<o.length;i++) o[i].style.display='none';
	var o=gE('pwf').getElementsByTagName('tr');
	for(var i=0;i<o.length;i++) if(isIE) o[i].style.display='block'; else o[i].style.display='table-row';
}
function hidePWF(){
	var isIE = (window.ActiveXObject) ? true : false;
	var o=gE('login').getElementsByTagName('tr');
	for(var i=0;i<o.length;i++) if(isIE) o[i].style.display='block'; else o[i].style.display='table-row';
	var o=gE('pwf').getElementsByTagName('tr');
	for(var i=0;i<o.length;i++) o[i].style.display='none';
}
function order_status(o,q){
	if(o.value!=0){
		if(confirm(q)) window.location.href=window.location.href+'&uid='+o.value;
		else o.firstChild.selected=true;
	}
}
function hide_element_confirmation(id){
	var element=document.getElementById(id);
	if(element){
		element.style.display='none';
		$('body').removeClass("fades");
	}
}
function img_resize(){
	var d=document.getElementsByTagName('img');
	var r=0,w=0,h=0;
	for(var i=0;i<d.length;i++){
		if(d[i].getAttribute('nw')!=null && d[i].getAttribute('nh')!=null){
			w=Number(d[i].getAttribute('nw')); h=Number(d[i].getAttribute('nw'));
			r=Number(d[i].width)/Number(d[i].height);
			if ((w/h)>r) d[i].width=Math.floor(h*r);
			else d[i].height=Math.floor(w/r);
			if((w-d[i].width)>4) d[i].style.padding='2px '+Math.floor((w-d[i].width)/2)+'px';
			else if((h-d[i].height)>4) d[i].style.padding=Math.floor((h-d[i].height)/2)+'px 2px';
		}
	}
}


/**
 * @fileoverview dragscroll - scroll area by dragging
 * @version 0.0.8
 * 
 * @license MIT, see http://github.com/asvd/dragscroll
 * @copyright 2015 asvd <heliosframework@gmail.com> 
 */
//просто добавяш клас dragscroll към елемента който обвива таблицата която искаш да се скролва хоризонтално
//za da se aktiwirat elementi dobawqsh atribut disabledrag na dadeniq element i shte moze naprimer da pishesh w input-a
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['exports'], factory);
    } else if (typeof exports !== 'undefined') {
        factory(exports);
    } else {
        factory((root.dragscroll = {}));
    }
}(this, function (exports) {
    var _window = window;
    var _document = document;
    var mousemove = 'mousemove';
    var mouseup = 'mouseup';
    var mousedown = 'mousedown';
    var EventListener = 'EventListener';
    var addEventListener = 'add'+EventListener;
    var removeEventListener = 'remove'+EventListener;
    var newScrollX, newScrollY;

    var dragged = [];
    var reset = function(i, el) {
        for (i = 0; i < dragged.length;) {
            el = dragged[i++];
            el = el.container || el;
            el[removeEventListener](mousedown, el.md, 0);
            _window[removeEventListener](mouseup, el.mu, 0);
            _window[removeEventListener](mousemove, el.mm, 0);
        }

        // cloning into array since HTMLCollection is updated dynamically
        dragged = [].slice.call(_document.getElementsByClassName('dragscroll'));
        for (i = 0; i < dragged.length;) {
            (function(el, lastClientX, lastClientY, pushed, scroller, cont){
                (cont = el.container || el)[addEventListener](
                    mousedown,
                    cont.md = function(e) {
					    if (e.target.hasAttribute('disabledrag') 
                            || e.target.closest('[disabledrag]')
                        ) {                      
                            return true
                        }
                        if (!el.hasAttribute('nochilddrag') ||
                            _document.elementFromPoint(
                                e.pageX, e.pageY
                            ) == cont
                        ) {
                            pushed = 1;
                            lastClientX = e.clientX;
                            lastClientY = e.clientY;

                            e.preventDefault();
                        }
                    }, 0
                );

                _window[addEventListener](
                    mouseup, cont.mu = function() {pushed = 0;}, 0
                );

                _window[addEventListener](
                    mousemove,
                    cont.mm = function(e) {
                        if (pushed) {
                            (scroller = el.scroller||el).scrollLeft -=
                                newScrollX = (- lastClientX + (lastClientX=e.clientX));
                            scroller.scrollTop -=
                                newScrollY = (- lastClientY + (lastClientY=e.clientY));
                            if (el == _document.body) {
                                (scroller = _document.documentElement).scrollLeft -= newScrollX;
                                scroller.scrollTop -= newScrollY;
                            }
                        }
                    }, 0
                );
             })(dragged[i++]);
        }
    }

      
    if (_document.readyState == 'complete') {
        reset();
    } else {
        _window[addEventListener]('load', reset, 0);
    }

    exports.reset = reset;
}));

function check(){
//var data = $( 'textarea#bg_short_description' ).val();
//datas = data.replace(/<p>(\s*&nbsp;\s*)+<\/p>/ig,'<p>&nbsp;</p>');
//alert(datas);
//alert($("#longitude").val());
if(($("#longitude").val() === '') || ($("#latitude").val() === '')){
alert('Моля добавете координати !');
gE('btn').value='save';document.form1.submit();
return false;
}else{
gE('btn').value='save';document.form1.submit();
} 
}

$('document').ready(function(){
$('.pagen').click(function(){
var pn=$(this).attr('data');
$('#pn').val(pn);
document.getElementById('advertform').submit();
})

/*window.onload = function(){
var roxyFileman = '../adman/ckeditor/fileman/index.html';
$(function(){
if($("#bg_short_description").length){
   CKEDITOR.replace( 'bg_short_description',{filebrowserBrowseUrl:roxyFileman,
	filebrowserImageBrowseUrl:roxyFileman+'?type=image',
	removeDialogTabs: 'link:Upload;image:Upload'});
	}
if($("#mail_description").length){
   CKEDITOR.replace( 'mail_description',{filebrowserBrowseUrl:roxyFileman,
	filebrowserImageBrowseUrl:roxyFileman+'?type=image',
	removeDialogTabs: 'link:Upload;image:Upload'});
	}
});
}*/


	$(".hideme").hide();
	$("#fragment1").show();
    $(".fragment").click(function() {
	var id = $(this).attr("id");
	var sectionId = id.replace("f", "#fragment");
	$(".hideme").hide();
	$(sectionId).show();
    });
	
	$('.test').click(function(){
	var url = $.trim($('#product_link').val());
	var advertid = $.trim($('#aid').val());
	var intitle = $.trim($('#intitle').val());
	var indesc = $.trim($('#indesc').val());
	var inprice = $.trim($('#inprice').val());
	var promo_price = $.trim($('#promo_price').val());
	var inimg = $.trim($('#inimg').val());
	var incatname = $.trim($('#incatname').val());
	var error = 0;
	
	/*if(url.length < 30 ){
	$('#product_link').css('background',"#ffeaee");
	//alert(error+url.length);

	error = 1;
	}
	if(intitle.length < 10 ){
	$('#intitle').css('background',"#ffeaee");
	error = 1;
	}
	if(indesc.length < 10 ){
	$('#indesc').css('background',"#ffeaee");
	error = 1;
	}
	if(inprice.length < 10 ){
	$('#inprice').css('background',"#ffeaee");
	error = 1;
	}
	if(inimg.length < 10 ){
	$('#inimg').css('background',"#ffeaee");
	error = 1;
	}*/

	if(error > 0){
	
		return false;
	}else{
	$.ajax({
	type: 'post',
    url: "./getproductdata.php",
    async: false,
	dataType: "json",
    data: { "url": encodeURIComponent(url),"advertid": advertid },
	async: true,
	success: function(data){
	//alert(data[2]);

	//if(!isEmpty(data[0]) && !isEmpty(data[1]) && !isEmpty(data[2]) && !isEmpty(data[3]) && !isEmpty(data[4])){
	if(data[0].length) $('#intitle1').html(data[0]);
	if(data[1].length) $('#indesc1').html(data[1]);
	if(data[2].length) $('#inprice1').html(data[2]);
	if(data[6].length) $('#promo_price1').html(data[6]);

	if(data[3].length){
	//var images = JSON.parse(data[3]);
	$.each(data[3], function(index, el) { 
        $('#inimg1').prepend('<span style="margin:5px;"><img width="150" src="'+data[3][index]+'"/></span>');
    });
	
	}
	if(data[4].length) $('#incatname1').html(data[4]);
	$('#msg').html(data[8]);
	if(data[8].length){
	$('#msg').html(data[8]);
	return false;
	}
	$('#addform').submit();
	$('#spinner').hide();
    return false;
	//}
}}
);
}	
	});
	
if($('.imagediv').length){
$('.imagediv').hide();
$('.imagediv:first').show();
 $("input:file").change(function (){
   var nextf = $(this).attr('data');
   $('#input'+(nextf)+'').next().show();
 });
//$.noConflict();
}
if($('#datepicker').length){
var dateToday = new Date();   
$('#datepicker').datepicker({
	dateFormat: 'yy-mm-dd',
	minDate: dateToday
    });
	}
if($('.date').length){
var dateToday = new Date();   
$('.date').datepicker({
	dateFormat: 'yy-mm-dd'
	//minDate: dateToday
    });
	}
	
	$('body').on('change','.set_cat',function(){
var cat_id = $.trim($(this).val());
var pid = $.trim(parseInt($(this).attr('id')));
	if(cat_id > 0 && pid > 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/adman/ajax/set_category.php",
	dataType: "json",
    async: true,
    data: { "cat_id": cat_id, "pid":pid}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	$('#global-loader').fadeOut()
	//setTimeout(function(){
	
	//}, 200);
	
	if(data[1].length > 2) $('#cat_'+pid).html(data[1]);
	
			}
		});
	}
})

function select2CopyClasses(data, container) {
    if (data.element) {
        $(container).addClass($(data.element).attr("class"));
    }
    return data.text;
}

$('.select2-show-search-prdcat').select2({
	minimumResultsForSearch: '',
	tags: true,
	maximumSelectionLength: 2,
	insertTag: function (data, tag) {
    // Insert the tag at the end of the results
    data.push(tag);
    },
	placeholder: messages.search,
	templateResult: select2CopyClasses,
    templateSelection: select2CopyClasses
	});
	
$(".editaff").click(function(){
var id = $(this).attr('data');
window.location = "../adman/index.php?page=affiliates&edit=" + id;
});

$('.commsg').click(function(){
jAlert('inform', 'Commission is payable only for completed orders !', 'Commission payment information');
})

if($("#fragment1").length){
	$(".hideme").hide();
	$("#fragment1").show();
	var pid = $('#aid').val();
    $(".fragment").click(function() {
	var id = $(this).attr("id");
	var sectionId = id.replace("f", "#fragment");
	$(".hideme").hide();
	$(sectionId).show();
    });
	}
	

if($("#bg_short_description").length){
var ed = tinymce.get('bg_short_description');
if (ed) ed.remove();
setTimeout(function(){
//$('#bg_short_description').tinymce({
tinymce.init({
oninit : "setPlainText",
height : "400",
//plugins: "paste", paste_as_text: true,language: "bg_BG",menubar: false,formats :false,toolbar: "undo redo | bold italic | removeformat | alignleft aligncenter alignright | link image | code",paste_remove_styles: true,
 menubar: true,
plugins: "link image code",
toolbar: 'undo redo | styleselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code',
automatic_uploads: true,
images_upload_url: '/adman/ajax/editor_image_upload.php',
images_reuse_filename: true,
selector: "#bg_short_description",
setup: function(editor) {
},
});
}, 1000);
}

if($("#mail_description").length){
var ed = tinymce.get('mail_description');
if (ed) ed.remove();
setTimeout(function(){
//$('#mail_description').tinymce({
tinymce.init({
oninit : "setPlainText",
height : "400",
//plugins: "paste", paste_as_text: true,language: "bg_BG",menubar: false,formats :false,toolbar: "undo redo | bold italic | removeformat | alignleft aligncenter alignright | link image | code",paste_remove_styles: true,
 menubar: true,
plugins: "link image code",
toolbar: 'undo redo | styleselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code',
automatic_uploads: true,
images_upload_url: '/adman/ajax/editor_image_upload.php',
images_reuse_filename: true,
selector: "#mail_description",
setup: function(editor) {
},
});
}, 1000);

$('body').on('change','.inv',function(){
var receiver = $('#receiver').val();
if($(this).is(':checked')){
$('#receiver').val($('#receiver').val() + ',' + $(this).val());
//console.log($(this).val());
	}else{
	$('#receiver').val($('#receiver').val().replace($(this).val() + ',',""));
	$('#receiver').val($('#receiver').val().replace($(this).val(),""));
	}
	
	$('#receiver').val($('#receiver').val().replace(/^,|,$/g,''));
})
}

$('body').on('click','#csearch',function(){
  if (!isEmpty($('#customers').val())) {
    $('#customers').css('background', '#fff');
    document.getElementById('customersearch').value = $('#customers').val();
    document.getElementById('alabala').submit();
  } else $('#customers').css('background', '#ffecec');
})
	$('#global-loader').fadeOut(100)
})