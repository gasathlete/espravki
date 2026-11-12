function isEmpty(value){return (value == null || value.length === 0);}
function show_help(id){
$('.bar').fadeOut('fast');
$(id).show();
$(id).delay(3000).fadeOut(3000);
//$(id).fadeIn('slow');
}

function codeAddress() {
geocoder = new google.maps.Geocoder();
var map;
var marker = "";

    //In this case it gets the address from an element on the page, but obviously you  could just pass it to the method instead
    var town = $.trim($('#town').val());
	var company_address = $.trim($('#company_address').val());
	var address = company_address+','+town;
	//document.getElementById( 'address' ).value;
	
if(isEmpty(town)){
$('#town').css('background',"#ffeaee");
return false;
}else $('#town').css('background',"#fff");

if(isEmpty(company_address)){
$('#company_address').css('background',"#ffeaee");
return false;
}else $('#company_address').css('background',"#fff");


    geocoder.geocode( { 'address' : address }, function( results, status ) {
        if( status == google.maps.GeocoderStatus.OK ) {

		 map = new google.maps.Map(document.getElementById("map"),{
        zoom: 16,
        center: new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
            //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
           map.setCenter( results[0].geometry.location );
			//console.log(results);
			//console.log(results[0].geometry.location.lat());
			$('#latitude').val(results[0].geometry.location.lat());
			$('#longitude').val(results[0].geometry.location.lng());
            var marker = new google.maps.Marker( {
                map     : map,
                position: results[0].geometry.location
            } );
			
			$('.mapholder').fadeIn();
        } else {
            alert( 'Geocode was not successful for the following reason: ' + status );
        }
    } );
}
function set_pay(txt,v){$('#payment_txt').html(txt);$('#payment').val(v);$('#paymentdiv').show();}
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
if(content < 150){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
$('#word_left').text(150-content);
$('.desc_msg').text(messages.add+' '+ (150-content)+ ' '+messages.tocon);
//$('#next').attr("disabled", true); 
}else{
//$('#next').removeAttr("disabled");
$('#word_left').text('0');
$('.desc_msg').text(messages.supersi);
}
return content;
};

function aa(){
$("#advertForm").find("input[type=file]").each(function(index, field){
if(field.files.length < 1){
$(this).remove();
//console.log($(this).attr('class')+'-'+field.files.length);

}
  for(var i=0;i<field.files.length;i++) {
    const file = field.files[i];
	//console.log(file+'/'+$(this).attr('class')+'-'+field.files.length);
    if(file.size > 5242880 || file.fileSize > 5242880) {
      alert('Files must be less than 5MB.');
    }
  }
});
}
	
function previewImages(el,numfiles) {
	var maxfiles = numfiles;
	
	$('.imgmsg').html('');
	var countimgs = 0;
	if($('.imgdiv').length) countimgs = $('.imgdiv').length;
  //var $preview = $('#img_preview').empty();
  
  if (el.files && ((el.files.length + countimgs) <= numfiles)) $.each(el.files, readAndPreview);
  else{
  //console.log((el.files.length + countimgs)+' - '+numfiles);
  $('.imgmsg').html(messages.maxfiles+' '+numfiles);
  event.preventDefault();
  //$('.custom-file-input').val('');
  $(el).val('');
  $('#img_preview').html();
  return false;
  //
  }
  
	//alert((el.files.length + countimgs));
  function readAndPreview(i, file) {
    //$preview = $('#img_preview').append('<span/>');

	
	
    if (!/\.(jpe?g|png)$/i.test(file.name)){
      return $('.imgmsg').html(messages.wrong_image_file);//alert(file.name +" is not an image");
    } 
	
	if ((file.size / 1000) > 3000){
	
      return $('.imgmsg').html(messages.bigimage+' '+file.name +' - '+(file.size / 1000)+' KB');//alert(file.name +" big image");
    }
	
	var checkfiles = aa();
/**/

	// else...
	
	$('.custom-file-input').hide();
	//console.log((el.files.length + countimgs)+' - '+numfiles);
	if((el.files.length + countimgs) == numfiles) $('.custom-file').hide();//alert();
	if((el.files.length + countimgs) < numfiles){
	$(".custom-file").append('<input onchange="previewImages(this,'+numfiles+');" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input newinputs" data-id="" type="file" name="images[]" multiple />');
    }
	
	
	var output = document.getElementById("img_preview");
    var reader = new FileReader();
	var newel = '';
    $(reader).on("load", function() {
	var div = document.createElement('div');
	div.setAttribute("class", "imgdiv");
	//var div = $('#img_preview').append('<span/>');
div.innerHTML = '<img class="thumbnail" src="'+this.result+'" title="preview image"/><span class="fileinfo">'+file.name+' | '+(file.size / 1000)+'KB</span><button class="remove_file" value="" type="button" aria-label="Remove from list"></button>';
output.insertBefore(div,null);
	
    });

    reader.readAsDataURL(file);
    
  }
}

(function($) {
	"use strict";

var ed = tinymce.get('f9');
if (ed) ed.remove();

setTimeout(function(){
//$('#f9').tinymce({
tinymce.init({
oninit : "setPlainText",
 height : "400",
plugins: "paste", paste_as_text: true,language: "bg_BG",menubar: false,formats :false,toolbar: "undo redo | bold italic | removeformat | alignleft aligncenter alignright",
paste_remove_styles: true,
selector: "#f9",
setup: function(editor) {
editor.on('keyup', function (e) {
CountCharacters();
$('.bar').hide();$('#help_description').show();
});
editor.on('click', function (e) {
CountCharacters();
$('.bar').hide();$('#help_description').show();
});
editor.on('change', function (e) {  
CountCharacters();
$('.bar').hide();$('#help_description').show();
});
},
});
}, 1000);
	
$('.btnNext').click(function(){
  $('.nav-tabs > li > a.active').parent('li').next('li').find('a').trigger('click');
  $('html, body').animate({scrollTop: $('.tab-content').offset().top - 180}, 800);
});

  $('.btnPrevious').click(function(){
  $('.nav-tabs > li > a.active').parent('li').prev('li').find('a').trigger('click');
  $('html, body').animate({scrollTop: $('.tab-content').offset().top - 180}, 800);
});	

$('#sellbusiness').on('change',function(){
//alert($(this).is(':checked'));
if($(this).is(':checked')){
$('#business_price').prop('readonly',false);
}
else $('#business_price').prop('readonly',true).val('');
})


$('#helpmedesc').on('change',function(){
var body = tinymce.get("f9").getBody();
var content = tinymce.trim(body.innerText || body.textContent);
content = content.replace(/\s+/g, ' ');
if($(this).is(':checked')){
tinymce.activeEditor.execCommand('mceInsertContent', false, messages.textsample);//" <b>bolded text</b> "
}
else tinymce.activeEditor.setContent('');//tinymce.activeEditor.execCommand('mceInsertContent', false, " <b>empty</b> ");
})

//$("#but_upload").click(function(){
$('#but_upload').on('click',function(){
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file',files);

        $.ajax({
            url: 'upload.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 0){
                    $("#img").attr("src",response); 
                    $(".preview img").show(); // Display image element
                }else{
                    alert('file not uploaded');
                }
            },
        });
    });

$("#supplier_name").on("keyup change", function() {
$('#company_name').val($(this).val());
})

$("#inv_city").on("keyup change", function() {
$('#town').val($(this).val());
})


$("#inv_address").on("keyup change", function() {
$('#company_address').val($(this).val());
})



$("#business_type").on("change", function() {
if($(this).val() == 2) $('.addmsg').show();
})

//$('.custom-file-input').on("change", previewImages($(this).attr('data-num')));


$("body").on("click", '.remove_file', function() {

if($(this).val() > 0){
//console.log($(this).val());
var image_id = $.trim($(this).val());
var aid = $.trim($('#aid').val());

	if(image_id >= 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/adman/ajax/delete_image.php",
	dataType: "json",
    async: true,
    data: { "image_id": image_id, "aid": aid}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(1000);
	var countimgs = 0;
	if($('.imgdiv').length) countimgs = $('.imgdiv').length;
	var numfiles = $('#fnum').val();
	//var el = 0;
	//if($('.custom-file-input').length) el = $('.custom-file-input').files.length;
	console.log(countimgs +' - '+numfiles);
		if((countimgs) < numfiles){
		if(!$('.custom-file-input').length) $(".custom-file").append('<input onchange="previewImages(this,'+numfiles+');" accept=".jpg, .png, image/jpeg, image/png" class="custom-file-input newinputs" data-id="" type="file" name="images[]" multiple /><label class="custom-file-label">'+messages.sel_file+'</label>');
		}
	}, 1000);
	
	$('#img_preview').html(data[1]);
	
			}
		});
	}
return false;

}else{
 $('.custom-file-input').val('');
 $('.newinputs').remove();

 $('#img_preview').html('');
 $('.custom-file').show();
 $('#advert_images').show();
	}
})

$('#meta_keywords').on("click keyup",function() {
var ck_cat = /^[A-Za-z0-9а-яА-Я ,-]{3,200}$/;
var field = $.trim($(this).val());
var area = $.trim($('#area').val());
var city = $.trim($('#town').val());
var category = $.trim($('#add_listing_categories').val());
var keywords = $.trim($(this).val());

if(isEmpty(keywords) && !isEmpty(category) && category !='0'){
$('#meta_keywords').val(category+', '+category +' '+area + ', ' + category + ' в ' + city + ', ');
$('#meta_keywords').css('background',"#fff");
}

 if (!ck_cat.test(field)) {
  	$('#meta_keywords').css('background',"#ffeaee");
	$('#help_keywords').html(messages.meta1).show();
	//$('#next').hide();
 }else{
  $('#meta_keywords').css('background',"#fff");
  $('#help_keywords').html(messages.meta2).show();
  //$('#next').show();
 }
 if(field.length < 1) $('#help_keywords').html(messages.meta2).show();
});


$('.mand').on("change keyup",function(){
var v = $.trim($(this).val());
var eltype = $(this).prop('type');
var id = $(this).attr('id');
if(eltype == 'text' && v.length < 3){
$('#'+id).css('background','#ffecec');
return false;
}else $('#'+id).css('background','#fff');
})

$('#business_type').on("change",function() {
if($('#business_type').val() < 1){
$('#select2-business_type-container').css('background','#ffecec');
return false;
}else $('#select2-business_type-container').css('background','#fff');
})

$('#add_listing_categories').on("change",function() {
if($('#add_listing_categories').val().length < 2){
$('#select2-add_listing_categories-container').css('background','#ffecec');
return false;
}else $('#select2-add_listing_categories-container').css('background','#fff');
})

$('body').on('click','.add_doc',function(){
//alert($(this).attr('id'));
var cloneCount = 1;
    $("#mainclone").clone().removeAttr('id').removeClass('dn').insertAfter(".custom_docs_file:last");
  
})


$('body').on('change keyup','.dname',function(e){
var name = $(this).val();
if(isEmpty(name)){
$(this).css('background','#ffecec');
}else $(this).css('background','#fff');
})

$('body').on('change','.docs',function(e){
var fileName = e. target. files[0]. name;
var file_desc = $(this).parents('div.custom_docs_file').find('div.input-group').find('input').val();

//console.log($(this).parents('div.custom_docs_file').find('div.input-group').find('input').val());
$(this).next('.custom-file-label').html(fileName);
if(isEmpty(file_desc)){
$(this).parents('div.custom_docs_file').find('div.input-group').find('input').css('background','#ffecec');
}else $(this).parents('div.custom_docs_file').find('div.input-group').find('input').css('background','#fff');

//alert('The file "' + fileName + '" has been selected.' );

})


 $("form input").on("invalid", function() {
        //find tab id      
//alert($(this));		
        var element = $(this).closest('.ui-tabs-panel').index();
         //goto tab id
        $('#yourTabs').tabs('option', 'active', element)
 });
 
$('#advertForm').bind('submit',function(evt){
var errors = 0;
//alert(errors);

$('.mand').each(function(){
var tab = $(this).attr('data-tab');
var id = $(this).attr('id');
var eltype = $(this).prop('type');
var v = $.trim($(this).val());


if(eltype == 'text' && v.length < 3){
$('#'+id).css('background','#ffecec');
$('#'+tab).click();
setTimeout(function(){$('html, body').animate({scrollTop: $('#'+id).offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $('#'+id).css('background','#fff');

if(eltype == 'email' && !validateEmail(v)){
$('#'+id).css('background','#ffecec');
$('#'+tab).click();
setTimeout(function(){$('html, body').animate({scrollTop: $('#'+id).offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $('#'+id).css('background','#fff');

//alert(id+'-'+eltype+'-'+v);
})


if($('#business_type').val() < 1){
$('#select2-business_type-container').css('background','#ffecec');
$('#firsttab').click();
setTimeout(function(){$('html, body').animate({scrollTop: $('#select2-business_type-container').offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $('#select2-business_type-container').css('background','#fff');


if($('#add_listing_categories').length){
var container = $('#add_listing_categories').next('.select2').find('ul');
if($('#add_listing_categories').val().length < 1){
//$('#select2-add_listing_categories-container').css('background','#ffecec');
$(container).css('background','#ffecec');
$('#firsttab').click();
setTimeout(function(){$('html, body').animate({scrollTop: $('#add_listing_categories').offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $(container).css('background','#fff');//$('#select2-add_listing_categories-container').css('background','#fff');
}

if($('#payment').length){
if(!$('#payment').is(':checked')){
$('#payment_txt').css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#payment').offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $('#payment_txt').css('background','#fff');
}

/*if(!$('#terms').is(':checked')){
$('#termslbl').css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#terms').offset().top - 150}, 800)}, 1000);
errors++;
return false;
}else $('#termslbl').css('background','#fff');*/

if(errors > 0){
 evt.preventDefault();
	}else{
	$('#save_advert').hide();
		$('#global-loader span').html(messages.saving);
$('#global-loader').fadeIn();
	}

})

$('body').on('change keyup','.ordering',function(){
var image_id = $.trim($(this).attr('data-id'));
var ordernum = $.trim($(this).val());
var aid = $.trim($('#aid').val());
	if(ordernum >= 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/adman/ajax/reordering_images.php",
	dataType: "json",
    async: true,
    data: { "image_id": image_id, "ordernum": ordernum, "aid":aid}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(500)
	}, 1000);
	
	if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
	}
})

$('body').on('click','.del_product',function(){
$('#confirm_del').attr('data-id',$(this).attr('data-id'));
$('#deletediv').modal('show');
})

$('body').on('click','#confirm_del',function(){
var pid = $.trim($(this).attr('data-id'));
var aid = $.trim($('#aid').val());
if(pid > 0){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/adman/ajax/delete_prd.php",
	dataType: "json",
    async: true,
    data: { "pid": pid, "aid": aid}, 
	success: function(data){
	$('.text-orange').html(data[0]);
	$('#deletediv').modal('hide');
	$('#pid_'+pid).remove();
	setTimeout(function(){
	$('#global-loader').fadeOut(500)
	}, 500);
	
	if(data[1].length > 10) $('#img_preview').html(data[1]);
	//location.reload();
			}
		});
	}
})


	$('#global-loader span').html(messages.loading);

})(jQuery);