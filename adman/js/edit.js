function img_resize(){var d=document.getElementsByTagName('img');var r=0,w=0,h=0;for(var i=0;i<d.length;i++){if(d[i].getAttribute('nw')!=null && d[i].getAttribute('nh')!=null){w=Number(d[i].getAttribute('nw')); h=Number(d[i].getAttribute('nw'));r=Number(d[i].width)/Number(d[i].height);if ((w/h)>r) d[i].width=Math.floor(h*r);else d[i].height=Math.floor(w/r);if((w-d[i].width)>4) d[i].style.padding='2px '+Math.floor((w-d[i].width)/2)+'px';else if((h-d[i].height)>4) d[i].style.padding=Math.floor((h-d[i].height)/2)+'px 2px';}}}
function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}
function gE(o){return document.getElementById(o);}
function isEmpty(value){return (value == null || value.length === 0);}
function hide_el(id){$(id).hide();}
function show_help(id){$('.bar').fadeOut('fast');$(id).fadeIn('slow');}
function check_supplier_name(el){var v = gE('f1').value;}
function extractDomain(url) {
    var domain;
	url = url.replace("www.", "");
    //find & remove protocol (http, ftp, etc.) and get domain
    if (url.indexOf("://") > -1) {
        domain = url.split('/')[2];
    }
    else {
        domain = url.split('/')[0];
    }

    //find & remove port number
    domain = domain.split(':')[0];

    return domain;
}
function CountCharacters() {
var body = tinymce.get("f9").getBody();
var content = tinymce.trim(body.innerText || body.textContent);
content = content.replace(/\s+/g, ' ');
	
if(isEmpty(content)){
content = 0;
}else content = content.split(' ').length;
$('#display_count').text(content);
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fff';
//var editor = tinymce.getInstanceById('f9');
if(content < 200){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
$('#word_left').text(200-content);
$('.desc_msg').text('Моля добавете още описание ! Не ви достигат '+ (200-content)+ ' думи за да продължите напред.');
//$('#next').attr("disabled", true); 
}else{
//$('#next').removeAttr("disabled");
$('#word_left').text('0');
$('.desc_msg').text('Супер! Можете да продължите с другите полета !');
}
return content;
};


$('document').ready(function(){
$(".change_image").click(function() {
	if($(this).is(':checked')){
	$('.change_image').removeAttr('Checked'); 
	$('#logoimage').hide();
	$('.logodiv').show();
	}else{
	$('.change_image').attr('Checked','Checked');
	$('#logoimage').show();
	$('.logodiv').hide();
	}

    });
	

if($("#prd_description").length){
var cell_string = $.trim($('#prd_description').val());
var prd_category = $.trim($('#prd_category').val());
$('#prd_description').val(cell_string.replace(/<br *\/?>/gi, '\n'));
if(isEmpty(prd_category)){
$('#prd_category').css('background',"#ffeaee");
//
}else $('#addoffer').show();
}

$('.accordion').each(function () {
	    $(this).find('.accordion-head').bind('click', function () {
            $(this).removeClass('open').addClass('close');
			var acbody = $(this).find('.accordion-body');
	        $(this).find('.accordion-body').slideUp();
	        if(!$(this).next().is(':visible')){
                $(this).removeClass('close').addClass('open');
	            $(this).next().slideDown();
	        }else{
			$(this).removeClass('open').addClass('close');
	            $(this).next().slideUp();
			}
	    });
	});
	
$('#f50SelectBoxIt,.selectboxit-option').hover(
        function () {
		$('.bar').hide();
            $('#help_business_type').show();
        }, 
        function () {
$('#help_business_type').hide();
        }
    );
	
	$('#f0SelectBoxIt,.selectboxit-option').hover(
        function () {
		$('.bar').hide();
            $('#help_city').show();
        }, 
        function () {
			$('#help_city').hide();
        }
    );

$('#f1').bind("change keyup input",function() {
//Alphabets, numbers and space(' ') no special characters min 3 and max 20 characters.
var ck_name = /^[A-Za-z0-9а-яА-Я& \'-]{6,70}$/;
var field = $.trim($(this).val());
if (!ck_name.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_supplier_name').text('Кратко име или непозволени символи !');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_supplier_name').html('Тук можете да въведете името на фирма, уебсайт, основна ключова фраза за бизнеса ви или търговска марка. Маx 70 символа');
  $('#next').show();
 }
 if(field.length < 1) $('#help_supplier_name').text('Тук можете да въведете името на фирма, уебсайт, основна ключова фраза за бизнеса ви или търговска марка. Маx 70 символа');
});

$('#f2').bind("change keyup input",function() {
var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

var field = $.trim($(this).val());
if (!ck_email.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_email').text('Невалиден Е-мейл адрес !');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_email').html('Готово !<br/>Можете да продължите !');
  $('#next').show();
 }
 if(field.length < 1) $('#help_email').text('Ще бъде използван за вход в сайта ако желаете да обновите профила');
});

$('#f3').bind("change keyup input",function() {
var ck_pass = /^[A-Za-z0-9 #!_\-]{6,20}$/;
var field = $.trim($(this).val());
if (!ck_pass.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_password').html('Невалидна парола!<br />Мин. дължина 6 символа. Позволени знаци са: - # ! _');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_password').html('Готово !<br/>Можете да продължите !');
  $('#next').show();
 }
 if(field.length < 1) $('#help_password').text('минимум 6 знака, букви, цифри или някой от следните знаци( - # ! _ )');
});

$('#f11').bind("change keyup input",function() {
var field = $.trim($(this).val());
 if (!/^(f|ht)tps?:\/\//i.test(field) || field.length < 13) {
  	$(this).css('background',"#ffeaee");
	$('#help_website').html('сайта трябва да започва с http:// или https://<br />напр. http://www.testing.espravki.com');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_website').html('Готово !<br/>Можете да продължите !');
  $('#next').show();
 }
 if(field.length < 1) $('#help_website').html('Ако имате уебсайт, сайта трябва да започва с http:// или https://<br />напр. http://www.testing.espravki.com');
});

$('#f7').bind("change keyup input",function() {
var ck_address = /^[A-Za-z0-9а-яА-Я& \/.,'-]{13,100}$/;
var field = $.trim($(this).val());
 if (!ck_address.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_address').html('Кратък адрес или непозволени символи!');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_address').html('Готово !<br/>Можете да продължите !');
  $('#next').show();
 }
 if(field.length < 1) $('#help_address').html('За онлайн бизнеси въведете адрес на централен офис или по съдебна регистрация.Фирми без реален адрес ще бъдат премахвани !');
});

$('#f8').bind("change keyup input",function() {
var ck_phone = /^[0-9 \;-\\/]{8,100}$/;
var field = $.trim($(this).val());
 if (!ck_phone.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_phone').html('Кратък телефон или непозволени символи!<br/>Ползвайте само цифри и ; за повече от 1 телефон');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_phone').html('Готово !<br/>Можете да продължите !');
  $('#next').show();
 }
 if(field.length < 1) $('#help_phone').html('Разделяйте телефоните с ; ако са повече от 1 номер');
});

$('#fsearch').bind("change keyup input",function() {
var ck_cat = /^[а-яА-Я -]{3,100}$/;
var field = $.trim($(this).val());
 if (!ck_cat.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_fsearch').html('Моля търсете на кирилица! Не са позволени други символи !');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_fsearch').html('Можете да разгледате всички налични категории тук или да добавите желаната категория в полето и ние ще я създадем');
  $('#next').show();
 }
 if(field.length < 1) $('#help_fsearch').html('Можете да разгледате всички налични категории тук или да добавите желаната категория в полето и ние ще я създадем ');
});

$('#f10').bind("change keyup input",function() {
var ck_cat = /^[A-Za-z0-9а-яА-Я ,-]{3,200}$/;
var field = $.trim($(this).val());
 if (!ck_cat.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_keywords').html('Кратко съдържание или използвани непозволени символи!');
	$('#next').hide();
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_keywords').html('Разделяйте всяка ключова дума или фраза със запетая (,). Не въвеждайте думи или фрази които не пресъстват в описанието!');
  $('#next').show();
 }
 if(field.length < 1) $('#help_keywords').html('Разделяйте всяка ключова дума или фраза със запетая (,). Не въвеждайте думи или фрази които не пресъстват в описанието!');
});

$('#prd_title').bind("change keyup input",function() {
var prd_title = /^[A-Za-z0-9а-я.,А-Я& \\/'-]{6,70}$/;
var field = $.trim($(this).val());
 if (!prd_title.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_prd_title').html('Кратко име на продукта или непозволени символи!');
	
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_prd_title').html('Готово !<br/>Можете да продължите !');
  $('#addoffer').show();
 }
 if(field.length < 1 || field.length > 70) $('#help_prd_title').html('Име на продукта или услугата. Маx 70 символа !');
});


$('#prd_category').bind("change keyup input",function() {
var prd_category = /^[A-Za-z0-9а-яА-Я& \'-]{6,150}$/;
var field = $.trim($(this).val());
 if (!prd_category.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_prd_category').html('Кратко име на категория или непозволени символи!');
	
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_prd_category').html('Готово !<br/>Можете да продължите !');
  $('#addoffer').show();
 }
 if(field.length < 1 || field.length > 150) $('#help_prd_category').html('Име на продукта или услугата. Маx 70 символа !');
});

$('#prd_description').bind("change keyup input",function() {
var help_prd_description = /^[A-Za-z0-9а-яА-Я& \-'.,:;-\\/]{6,250}$/;
var field = $.trim($(this).val());
 if (!help_prd_description.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_prd_description').html('Кратко описание на продукта или непозволени символи!');
	
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_prd_description').html('Готово !<br/>Можете да продължите !');
  $('#addoffer').show();
 }
 if(field.length < 6 || field.length > 250) $('#help_prd_description').html('Описание на продукта или услугата. Маx 250 символа');
});


$('#addoptions').bind("change keyup input",function(){
var selected_option = $('#prd_options option:selected');
var addoptions = /^[A-Za-z0-9а-яА-Я ;]{2,150}$/;
var field = $.trim($(this).val());

 if (!addoptions.test(field)) {
  	$(this).css('background',"#ffeaee");
	$('#help_add_options').html('Кратко описание на опциите или непозволени символи!');
	
 }else{
  $(this).css('background',"#f9ffee");
  $('#help_add_options').html('Готово !<br/>Можете да продължите !');
  $('#addoffer').show();
 }
 if(field.length < 1 || field.length > 150) $('#help_add_options').html('Въвеждайте опциите като ги разделяте една от друга с ;<br /> напр.: Бял;Черен;Син;');
if(selected_option.val() == 0){
$('#prd_options').css('background',"#ffeaee");

}else $('#prd_options').css('background',"#fff");
 });

$('#prd_options').bind("change keyup input",function(){
var selected_option = $('#prd_options option:selected');

if(selected_option.val() == 0){
$('#addoffer').show();
$('#addoptions').css('background',"#fff");
$('#prd_options').css('background',"#fff");

}else{
var optsfield = $.trim($('#addoptions').val());
if(isEmpty(optsfield)){
$('#addoptions').css('background',"#ffeaee");
$('#prd_options').css('background',"#fff");


		}else{
		$('#prd_options').css('background',"#fff");
		$('#addoffer').show();
		}
	}
 });

$('#loaddata').click(function(){
var url = $.trim($('#prd_link').val());
var cid = $.trim($('#categoryid').val());
var siteurl = $.trim($('#prd_link').attr('data'));
var rootdomainname = extractDomain(siteurl);
var rooturlname = extractDomain(url);
//alert((rooturlname === rootdomainname) + rooturlname + rootdomainname);
var pid = $.trim($('#productid').val());
//alert((rootdomainname.indexOf(siteurl) > -1));
if(isEmpty(url)){
$('#prd_link').css('background',"#ffeaee");
$('#prd_link_help').show().html('Моля въведете пълен път до страницата на продукта във вашият сайт!');

}else{
if (!/^(f|ht)tps?:\/\//i.test(url) || url.length < 13 || (rooturlname !==rootdomainname)) {
//alert((rooturlname === rootdomainname) + rooturlname + rootdomainname);
$('#prd_link').css('background',"#ffeaee");
$('#prd_link_help').show().html('УРЛ адреса трябва да започва с http:// или https:// и трябва да води до продукт във вашата страница!');

 }else{
	$('#prd_link').css('background',"#fff");
	$('#addoffer').show();
	$('#spinner').show();
	$.ajax({
	type: 'post',
    url: "../../ajax/getproductdata.php",
    async: true,
	dataType: "json",
    data: { "url": encodeURIComponent(url),"cid": cid,"pid": pid }, 
	success: function(data){
	//alert(data[0]+'-0'+data[1]+'-1'+data[2]+'-2'+data[3]+'-3'+data[4]+'-4');
	if(!isEmpty(data[0]) && !isEmpty(data[1]) && !isEmpty(data[2]) && !isEmpty(data[3])){
	$('#prd_title').val(data[0]);
	$('#prd_description').val(data[1]);
	$('#prd_price').val(data[2]);
	$('#prd_category').val(data[4]);
	$('#productid').val(data[5]);
	if(data[6]){
	$('.msg').html(data[6]);
	$('#spinner').hide();
	return false;
	}
	$('#addform').submit();
	$('#spinner').hide();
	var cell_string = $('#prd_description').val();
	$('#prd_description').val(cell_string.replace(/<br *\/?>/gi, '\n'));
    return false;
	}else alert('aa');$('#spinner').hide();
}}
);
	}
}

 });
 
});

$(function() {
if($("#prd_category").length){
function split( val ) {
return val.split( /,\s*/ 
);    
}function extractLast( term ) {
return split( term ).pop();    
}
var $j = jQuery.noConflict();
$j( "#prd_category" ).bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&            
$j( this ).data( "ui-autocomplete" ).menu.active ) {
event.preventDefault();      
}     
})      
.autocomplete({
source: function( request, response ) {
$.getJSON( "../../ajax/productscategory.php", {
term: extractLast( request.term )}, response );
},
search: function() {
// custom minLength          
var term = extractLast( this.value );
if ( term.length < 4 ){            
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
$(this).css('background',"#f9ffee");
$('#addoffer').show();
return false;        
}      
});
}
});