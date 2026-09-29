var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
function img_resize(){var d=document.getElementsByTagName('img');var r=0,w=0,h=0;for(var i=0;i<d.length;i++){if(d[i].getAttribute('nw')!=null && d[i].getAttribute('nh')!=null){w=Number(d[i].getAttribute('nw')); h=Number(d[i].getAttribute('nw'));r=Number(d[i].width)/Number(d[i].height);if ((w/h)>r) d[i].width=Math.floor(h*r);else d[i].height=Math.floor(w/r);if((w-d[i].width)>4) d[i].style.padding='2px '+Math.floor((w-d[i].width)/2)+'px';else if((h-d[i].height)>4) d[i].style.padding=Math.floor((h-d[i].height)/2)+'px 2px';}}}

$(function () {
/*$.get("../../modules/menu.php", function(data, status){
$('#nav').html(data);
});*/

$('#show_user_menu').click(function(){
$('#cmenu').toggle();
})

$('#catmenu').hide();
$('#showhide').toggle(
          function(){
		  //alert();
		  $('#menu,#catmenu').show();
		  if(isMobile.any()){
		  //alert(window.screen.availWidth);
		  $("#menu, #nav, ul.subul").css('max-width','none');
		  $("#menu").animate({left: "0px"});
		  }else $("#menu").animate({left: "0px"});$("#catmenu").appendTo("#nav");
             $("#catmenu").removeClass("intro"); 
			$("#search").animate({left: "200px"});
          },
          function(){
		  $("#search").animate({left: "0px"});
		  if(isMobile.any()){
		  $("#menu").animate({left: "-100%"});
		  }else $("#menu").animate({left: "-100%"});
		  //$('#menu').hide();
          }
      );
	  
$('li.with > span').click(function () {
	
    var text = $.trim($(this).text());
	var plustext = '+ '+text.replace("+", "");
	var newValue = text.replace('+', '');

    if (text.charAt(0) === "+") {
	var all = $('li.with > span').each(function(){
	var aa = $(this).text();
	var res = aa.replace("-", "+");
	$(this).text(res);
	$(this).next('ul.subul').slideUp("fast", function () {});
	});
	$(this).next('ul.subul').slideDown("fast", function () {});
	$(this).text('- '+newValue);
    } else {
        $(this).next('ul.subul').slideUp("fast", function () {});
		$(this).text('+ '+newValue.replace('-', ''));
    }
});

});
$('document').ready(function(){
$('#wrapper').click(function(){$("#menu").animate({left: "-320px"});$("#wrapper").css({'opacity': "1.0"});});

$('#nav_up').hide();if(window.pageYOffset > 300){$('#nav_up').show();}
$(window).scroll(function(){if ($(window).scrollTop() > 300) {$('#nav_up').fadeIn('slow');}else{$('#nav_up').fadeOut('slow');}});
$("#nav_up").click(function(){$('html, body').animate({scrollTop: 0},500);$('#nav_up').show();});

$('#menu').hide();

if($("img.aa").length){
$("img.aa").click(function(){
     $(this).find(".info").toggle();
	 if($(".challenge").length){
	 $(".challenge,#overlay").hide();
	 }
 });
}
$('#offer_added').click(function(){
    	$('html, body').animate({
	scrollTop: $("#update").offset().top
    }, 1000);
 });

$('.updprd').click(function(){
var prid = $(this).attr('data-id');
$('#productid').val(prid);
document.getElementById("addform").submit();
});

if($("select.transform").length){
$("select.transform").selectBoxIt();
}
if($("#cityfilter").length){$("#cityfilter").appendTo('#cityspan');}

$('form.p_form').bind('submit',function (e){

	$('#f1,#f2,#f3,#f0').css('background','#fff');
	var businesstype = $.trim($('#f50').val());
    var name = $.trim($('#f1').val());
	var mail = $.trim($('#f2').val());
	var pass = $.trim($('#f3').val());
	var city = $.trim($('#f0').val());
	var area = $.trim($('#f5').val());
	var postcode = $.trim($('#f6').val());
	var address = $.trim($('#f7').val());
	var body = tinymce.get("f9").getBody();
	var description = tinymce.trim(body.innerText || body.textContent);
	description = description.replace(/\s+/g, ' ');
	var category = $.trim($('#fsearch').val());

	//var words = document.getElementById("f9").value.replace(/[^а-яa-zA-Z0-9]/i, "");
	//words = $.trim(words);
	//words = words.split(' ').length;
	
	if(isEmpty(description)){
	words = 0;
	}else words = description.split(' ').length;
	$('#display_count').text(words);

	
	if (businesstype === '' || businesstype < 1) {
	$('#f50SelectBoxIt').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("h1").offset().top
    }, 1000);
	return false;
    }else $('#f50SelectBoxIt').css('background','#fff');
	
    if (name === '') {
	$('#f1').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("h1").offset().top
    }, 1000);
	return false;
    }else $('#f1').css('background','#fff');
	if (mail === '') {
	$('#f2').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f1").offset().top
    }, 1000);
	return false;
    }else $('#f2').css('background','#fff');
	
	if(!$("#update").length){
	if (pass === '') {
	$('#f3').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f2").offset().top
    }, 1000);
	return false;
    }
	}

	if (city === '' || city === '0') {
	$('#f0SelectBoxIt').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f11").offset().top
    }, 1000);
	return false;
    }else $('#f0SelectBoxIt').css('background','#fff');
	
	
	if (postcode === '') {
	$('#f6').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f5").offset().top
    }, 1000);
	return false;
    }else $('#f6').css('background','#fff');
	
	if (address === '') {
	$('#f7').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f6").offset().top
    }, 1000);
	return false;
    }else $('#f7').css('background','#fff');
	
	if (description === '' ||  words < 200) {
	$('#f9').css('background','#fff6f6');
	$('.label > label.short').css('color','red');
	$('#word_left').text(200-words);
	$('#display_count').text(words);
	$('html, body').animate({
	scrollTop: $("#f8").offset().top
    }, 1000);
	$('.desc_msg').text('Моля добавете още описание ! Не ви достигат '+ (200-words)+ ' думи за да продължите напред.');
	return false;
    }else $('#word_left').text(0);
	
	var text = description;
	var frequency = text.split(' ').reduce(function(previous, current) {
		if (!previous.hasOwnProperty(current)) {
			previous[current] = 0;
		}
		if(current.length > 5){
		previous[current] += 1;
		}
		return previous;
	
	}, {});

	var repeatedWords = Object.keys(frequency).filter(function(element) {
	//alert(frequency[element] > 10);
		return frequency[element] > 10;
	});

	//console.log(repeatedWords);
	$('.desc_msg').text('');
	if(repeatedWords.length > 0){
	//alert(repeatedWords.length);
	$('#f9').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f8").offset().top
    }, 1000);
	$('.desc_msg').text('Описанието не изглежда полезно!Копирахте ли един и същ текст няколко пъти? Открихме '+ repeatedWords.length +' думи да се повтарят повече 10 пъти. Думи: '+repeatedWords);
	return false;
	}
	

var aa = ''; //the result
 $.ajax({
	type: 'post',
    url: "../free/checkdescription.php",
    async: false,
    data: { "description": description }, 
	success: function(data, status){
    aa = data;
	if(aa !== '0'){
	$('.desc_msg').html(aa);
	$('html, body').animate({
	scrollTop: $("#f8").offset().top
    }, 1000);
    return false;
 }else $('.desc_msg').html('');
}}
);
	
 
	if (category === '') {
	$('#fsearch').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f9").offset().top
    }, 1000);
	return false;
    }else $('#fsearch').css('background','#fff');
	

	if($("#sellbusinessform").length){
	if($('#sellbusiness').is(":checked")){
	var sell_description = $.trim($('#f17').val());
	$("#sellbusinessform").show();
	if (sell_description === '' ||  sell_description.length < 50) {
	$('#f17').css('background','#fff6f6');
	$('.sell_desc_msg').text('Ако желаете да продадете бизнеса си, моля добавете по-подробно описание по-долу!')
	$('html, body').animate({
	scrollTop: $("#f16").offset().top
    }, 1000);
	return false;
		}else $('#f17').css('background','#fff');

}

	if(($(".upload").length) > 5){
	$('html, body').animate({
	scrollTop: $("#sellbusiness").offset().top
    }, 1000);
	$('.sell_desc_msg').text('Позволено ви е да публикувате само 4 снимки !')
	return false;
	}
}
	
	if($("#f33").length){
	if(!$("#f33").is(':checked')){
	alert('Моля прочетете и приемете условията за ползване !');
	return false;
	} 
	}
});


	if($("img.arrow").length){
	$("img.arrow").click(function(){
	//alert();
	$('.rws').find(".arrow-up, .arrow-down").toggle();
	if($('.premiumtohide').length){
	$('.premiumtohide').toggle();
	}
	if($('.premiumtoshow').length){
	$('.premiumtoshow').toggle();
	//$('.imagediv').css('display','none');
	}
	});
	
	
	
$('.imagediv').hide();
$('.imagediv:first').show();
$("input:file").change(function (){
var nextf = $(this).attr('data');
$('#input'+(nextf)+'').next().show();
});
 
}

if($("#sellbusinessform").length){
if($('#sellbusiness').is(":checked")) $("#sellbusinessform").show();

$("#sellbusiness").click(function(){
$("#sellbusinessform").toggle();
});
}

if($("#become_premium").length){
	$("#become_premium").click(function(){
	$("#overlay,#paymentform").show();
	$("body").css('overflow','hidden');
	//document.getElementById("paypal").submit();
	});
}

if($("img.payclose").length){
$("img.payclose").click(function(){
	$("#overlay,#paymentform").hide();
	$("body").css('overflow-y','auto');
	});
};

if($("#gplus").length){
	$("#gplus").click(function(){
	$("#overlay,.steps").show();
	$("body").css('overflow','hidden');
	});
	$('#closebtn').click(function(){
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	$("#gplus").attr('checked', false);
	});
}

if($(".offerbtn").length){
	$("#add_offer").click(function(){
	$("#overlay,.productform").show();
	//$("body").css('overflow','hidden');
	});
	$('#closebtn').click(function(){
	$( ".toclear" ).each(function() {
	$(this).attr('value', '');
	});
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
		window.location.href = "../../addbusiness/edit/";

	});
}

if($("#addoffer").length){
$("#addoffer").click(function(){
document.getElementById("addform").submit();
});
}
/*
//tuk pokazwa popup s ukazanie za spodelqne na mestopolozenieto
if($("#showMe").length){
	$("#showMe").click(function(){
	$("#overlay,.steps").show();
	$("body").css('overflow','hidden');
	});
	$('#closebtn').click(function(){
	$("#overlay,.steps").hide();
	$("body").css('overflow-y','auto');
	});
}*/
if($("#gplusussubmit").length){
	$("#gplusussubmit").click(function(){
	var unames = $.trim($('#gplususer').val());
	if(unames === '' || unames.length < 3){
	alert('Моля добавете ID или Google+ потребителско име с което гласувахте !Ако не го направите няма да получите обратен вот ! ');
	return false;
	}
	});

	}

if($("#contactsubmit").length){
	//$('#cform').hide();
	$("#contactsubmit").click(function(){
	//$('#cform').fadeIn('slow');
	var your_names = $.trim($('#your_names').val());
	var your_email = $.trim($('#your_email').val());
	var your_message = $.trim($('#your_message').val());
	var antispam = $.trim($('#antispam').val());
	if(your_names === '' || your_names.length < 3){
	$("#your_names").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#contact_listing").offset().top
    }, 1000);
	return false;
	}else $("#your_names").css('background','#fff');
	if(your_email === '' || your_email.length < 7){
	$("#your_email").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#your_names").offset().top
    }, 1000);
	return false;
	}else $("#your_email").css('background','#fff');
	if(your_message === '' || your_message.length < 7){
	$("#your_message").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#your_email").offset().top
    }, 1000);
	return false;
	}else $("#your_message").css('background','#fff');
	if(antispam === '' || antispam.length < 5){
	$("#antispam").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#your_message").offset().top
    }, 1000);
	return false;
	}else $("#antispam").css('background','#fff');
	});	
}
$("ul.atoz li").click(function(){
$(this).children("ul.atoz li ul").toggle();
//alert();

});


$("#contbtn").click(function(){
if($('input.c:radio:checked').length > 0){
document.getElementById("callengeform").submit();
 }else{
   $('#cmsg').text('Моля изберете една от двете опции !');
 }
});

if($("#googlemapsmall").length){
console.log();
if(!isMobile.any()) $("#googlemap").appendTo("#googlemapsmall");

	}
	
		var x = false;
		$('#list').on('click', function(){
		 if (!x){
		  $(".row").removeClass('square');
		  $('#list').attr('src','../../images/interface/grid.png');
		  x = true;
		 }
		 else {
		  $(".row").addClass('square');
		  $('#list').attr('src','../../images/interface/list.png');
		  x = false;
		 }
		});
	
});

function move(id){$('html, body').animate({scrollTop: $("#" + id).offset().top}, 1500);}
	
$(function() {

if($("#fsearch").length){
function split( val ) {
return val.split( /,\s*/ 
);    
}function extractLast( term ) {
return split( term ).pop();    
}
var $j = jQuery.noConflict();
$j( "#fsearch" ).bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&            
$j( this ).data( "ui-autocomplete" ).menu.active ) {
event.preventDefault();      
}      
})      
.autocomplete({
source: function( request, response ) {
$.getJSON( "../../ajax/data.php", {
term: extractLast( request.term )}, response );
},
search: function() {
// custom minLength          
var term = extractLast( this.value );
if ( term.length < 2 ){            
return false;          
}
},        
focus: function() {
// prevent value inserted on focus  
return false;        
},        
select: function( event, ui ) {          
var terms = split( this.value );          
// remove the current input          
terms.pop();
//window.location.href=ui.item.value;
       
// add the selected item          
terms.push( ui.item.value );
var keywords = $.trim($('#f10').val());
var area = $.trim($('#f5').val());
if(area != ''){
area = ', ' + ui.item.value + ' в ' + area;
}else area = '';
var city = $("#f0 option:selected").text();
if($('#f10').val() !=''){
$('#f10').val(keywords + ui.item.value + area + ', ' + ui.item.value + ' в ' + city + ', ');
}else $('#f10').val(ui.item.value + area + ', ' + ui.item.value + ' в ' + city + ', ');        
// add placeholder to get the comma-and-space at the end          
terms.push( "" );          
this.value = terms.join( "" );
return false;        
}      
});
}

$('#loader').fadeOut();
});
function delete_ask(q,id){
var form = "todelete"+id;
	if(confirm(q)){document.getElementById(form).submit();}
}
function chat(){
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?2i1TeU1t5wWEhWOacv4jaoLnkEvVUCNa';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
}


function creategooglemap(){
jQuery(function($) {
	 var script2 = document.createElement('script');
    script2.src = "http://www.espravki.com/js/markerwithlabel.js";
    //document.getElementsByTagName('head')[0].appendChild(script2);
	
});
}

