function isEmpty(value){return (value == null || value.length === 0);}
function priceFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 46:case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}

function show_help(id){
$('.bar').fadeOut('fast');
$(id).show();
$(id).delay(3000).fadeOut(3000);
//$(id).fadeIn('slow');
}
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

function CountCharacters(){
var body = tinymce.get("description").getBody();
var contents = tinymce.trim(body.innerText || body.textContent);
contents = contents.replace(/\s+/g, ' ');
	
if(isEmpty(contents)){
content = 0;
}else content = contents.length;
$('#display_count').text(content);
//console.log(content);
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fff';
//var editor = tinymce.getInstanceById('description');
if(content < 100){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
$('#word_left').text(100-content);
$('.desc_msg').text(messages.add+' '+ (100-content)+ ' '+messages.tocon2);
//$('#next').attr("disabled", true); 
}else{
//$('#next').removeAttr("disabled");
$('#word_left').text('0');
$('.desc_msg').text(messages.supersi);
}
return content;
};

function aa(){
$("#productform").find("input[type=file]").each(function(index, field){
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

var ed = tinymce.get('description');
if (ed) ed.remove();

setTimeout(function(){
//$('#description').tinymce({
tinymce.init({
oninit : "setPlainText",
 height : "400",
plugins: "paste", paste_as_text: true,language: "bg_BG",menubar: false,formats :false,toolbar: "undo redo | bold italic | removeformat | alignleft aligncenter alignright",
paste_remove_styles: true,
selector: "#description",
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


tinymce.init({
oninit : "setPlainText",
 height : "400",
plugins: "paste", paste_as_text: true,language: "bg_BG",menubar: false,formats :false,toolbar: "undo redo | bold italic | removeformat | alignleft aligncenter alignright",
paste_remove_styles: true,
selector: "#special_terms"
});

}, 1000);

/*
tinymce.on('addeditor', function( event ) {
    var editor = event.editor;
    var $textarea = $('#' + editor.id);
	var content = $textarea.val().length;
	$('#display_count').text(content);
	if(content < 100){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
$('#word_left').text(100-content);
$('.desc_msg').text(messages.add+' '+ (100-content)+ ' '+messages.tocon2);
}else{
$('#word_left').text('0');
$('.desc_msg').text(messages.supersi);
}
  }, true );*/

$("body").on("change", '#product_options', function() {
var po = $(this).val();
$('#pohldr').html('');
if(po.length){
$('.optshlpr').fadeIn();
for (var i = 0; i < po.length; i++) {
    //console.log(po[i]);
	//	<input placeholder="'+messages.opt_sample+'" class="form-control mand" type="text" id="'+po[i]+'" name="options[]" value="" />

	if(!$('#'+po[i]).length) $("#pohldr").append('<label class="form-label text-dark mt-4">'+messages.ent_opts+' '+po[i]+'</label><small class="ib w100">'+messages.product_opts_sm+'</small><select multiple class="form-control select2-show-search-option mandselect border-bottom-0 w100" id="'+po[i]+'" name="options['+po[i]+'][]"></select>');
	$('#'+po[i]).select2({
	minimumResultsForSearch: '',
	tags: true,
	maximumSelectionLength: 15,
	insertTag: function (data, tag) {
    // Insert the tag at the end of the results
    data.push(tag);
    },
	placeholder: messages.searchopt
	});
		}
	}else $('.optshlpr').fadeOut();
})

$("body").on("click", '.remove_img', function(){
var id = $.trim($(this).attr('data-id'));
$('#img_'+id).remove();

if(!isEmpty(id)){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/delete_image.php",
	dataType: "json",
    async: true,
    data: { "id":id, "ri":1}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	
	
	setTimeout(function(){
	$('#global-loader').fadeOut(1000);
	var countimgs = 0;
	if($('.imgdiv').length) countimgs = $('.imgdiv').length;
	var numfiles = 4;
	//var el = 0;
	//if($('.custom-file-input').length) el = $('.custom-file-input').files.length;
	//console.log(countimgs +' - '+numfiles);
	
	}, 1000);
	
	$('#img_preview').html(data[1]);
	
			}
		});
	}
})

$("body").on("click", '.remove_file', function() {

if($(this).val() > 0){
var pid = $.trim($(this).attr('data-pid'));
//console.log($(this).val());
var image_id = $.trim($(this).val());
	if(image_id >= 0 && pid > 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/delete_image.php",
	dataType: "json",
    async: true,
    data: { "image_id": image_id, "pid":pid}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(1000);
	var countimgs = 0;
	if($('.imgdiv').length) countimgs = $('.imgdiv').length;
	var numfiles = 4;
	//var el = 0;
	//if($('.custom-file-input').length) el = $('.custom-file-input').files.length;
	//console.log(countimgs +' - '+numfiles);
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
 $('#product_images').show();
	}
})

$('body').on('change keyup','.ordering',function(){
var image_id = $.trim($(this).attr('data-id'));
var ordernum = $.trim($(this).val());
var pid = $.trim($(this).attr('data-pid'));
	if(ordernum >= 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/reordering_images.php",
	dataType: "json",
    async: true,
    data: { "image_id": image_id, "ordernum": ordernum, "pid":pid}, 
	success: function(data){
	$('#global-loader span').html(data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(1000)
	}, 1000);
	
	if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
	}
})

$("#title").on("keyup change", function() {
var title = $.trim($(this).val());
//console.log(title.length);
//$('#category_name').val(0).trigger('change');	
if(title.length >= 5){
	$.ajax({
	type: 'post',
    url: "/ajax/category_suggest.php",
	dataType: "json",
    async: true,
    data: { "title": title}, 
	success: function(data){
	if(data[0] > 0){

$('#category_name').val(data[0]).trigger('change');	
//$('#category_name').select2('data', {id: data[0], text: data[1]});
//.append(newOption).trigger('change');
//	$('#category_name').val(data[1]);
	
	}
	
			}
		});

	}
})


$('body').on('click','#loaddata',function(){
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
$('#prd_link_help').show().html(messages.site_msg1);

}else{
if (!/^(f|ht)tps?:\/\//i.test(url) || url.length < 13 || (rooturlname !==rootdomainname)) {
//alert((rooturlname === rootdomainname) + rooturlname + rootdomainname);
$('#prd_link').css('background',"#ffeaee");
$('#prd_link_help').show().html(messages.site_msg);

 }else{
$('#global-loader').fadeIn();
	$('#prd_link').css('background',"#fff");
	$.ajax({
	type: 'post',
    url: "/ajax/getproductdata.php",
    async: true,
	dataType: "json",
    data: { "url": encodeURIComponent(url)}, 
	success: function(data){

	if(data[0].length) $('#title').val(data[0]);
	if(data[1].length) tinymce.activeEditor.execCommand('mceInsertContent', false, data[1]);
	if(data[2].length) $('#prd_price').val(data[2]);
	//if(data[3].length) $('#inimg1').val(data[3]);
	
	if(data[3].length){
	var output = document.getElementById("img_preview");
	if(data[3].length === 4) $('.custom-file').remove();
	
	$.each(data[3], function(index, el) {
	var div = document.createElement('div');
	div.setAttribute("class", "imgdiv");
	div.setAttribute("id", "img_"+index);
	div.innerHTML ='<img class="thumbnail" src="'+data[3][index]+'" title="preview image"/><span class="fileinfo"></span><button class="remove_img" data-id="'+index+'" value="" type="button" aria-label="Remove from list"></button>';
     //   $('#inimg1').prepend('<span style="margin:5px;"><img width="150" src="'+data[3][index]+'"/></span>');
    output.insertBefore(div,null);
	});
	
	}
	
	if(data[4].length){
	var newdata = {
    id: data[4],
    text: data[4]
};
$("#category_name").empty().trigger('change')
var newOption = new Option(newdata.text, newdata.id, false, false);
$('#category_name').append(newOption).attr("selected", "selected").trigger('change').click();
	}

	//console.log(data[6].length+' aaaaa'+data[6]);
	var promo_price = '0.00';
	//if(data[6].length) $('#promo_price').val(data[6]);
	if(data[6] > 0){
	promo_price = data[6];
	}
	$('#promo_price').val(promo_price);	
	
	if(data[8].length){
	$('.orange').html(data[8]);
	$('#spinner').hide();
	}
	  $('html, body').animate({scrollTop: $('.orange').offset().top - 180}, 800);
	$('.db').fadeOut();
	$('#global-loader').fadeOut();
}
});
	}
}

 });

 
$("body").on("change", '.offer_type', function() {
var sel = $("input[name='offer_type']:checked").val();
//console.log(sel);
if(sel == 1){
$('.deldiv').show();
}else{
$('.deldiv').hide();
$(".not_del").prop( "checked", true );
}

})


 
$("body").on("change", '#category_name', function() {
var categorytxt = $('#category_name option:selected').text();
//console.log(categorytxt.length);
$("#product_tags").trigger('change')
var newOption = '';
if(categorytxt.length > 3) newOption = new Option(categorytxt, categorytxt, false, true);
//console.log(newOption);
$('#product_tags').append(newOption).trigger('change');
})


$('#productform').bind('submit',function(evt){
var errors = 0;
$('.mand').each(function(){
var id = $(this).attr('id');
var eltype = $(this).prop('type');
var v = $.trim($(this).val());
var of_type = $("input[name='offer_type']:checked").length;
var delivery = $("input[name='deliver_product']:checked").length;

//var desc = tinymce.get("description").getContent();
//console.log(of_type);
if(of_type < 1){
$('.otlbl').css('background','#ffecec');
$('html, body').animate({scrollTop: $('.otlbl').offset().top - 150}, 800)
errors++;
return false;
}else $('.otlbl').css('background','#fff');


if(delivery < 1){
$('.deldiv').show();
$('.dlbl').css('background','#ffecec');
$('html, body').animate({scrollTop: $('.dlbl').offset().top - 150}, 800)
errors++;
return false;
}else $('.dlbl').css('background','#fff');

if(eltype == 'text' && v.length < 1){
$('#'+id).css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#'+id).offset().top - 150}, 800)}, 500);
errors++;
return false;
}else $('#'+id).css('background','#fff');
})

if($('.mandselect').length){
$('.mandselect').each(function(){
var val = $.trim($(this).val());
if(isEmpty(val) || val.length < 1){
//alert($(this).attr('id'));
$(this).next('span').find('ul.select2-selection__rendered').css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#product_options').offset().top - 150}, 800)}, 500);
errors++;
return false;
		}else $('ul.select2-selection__rendered').css('background','#fff');
	})
}


if($('#category_name').length){
if($('#category_name').val() < 1){
$('#select2-category_name-container').css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#select2-category_name-container').offset().top - 150}, 800)}, 500);
errors++;
return false;
}else $('#select2-category_name-container').css('background','#fff');
}

if(tinymce.get("description").getContent().length < 100){
tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fdcaca';
setTimeout(function(){$('html, body').animate({scrollTop: $('#prd_price').offset().top - 150}, 800)}, 500);
errors++;
return false;
}else tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#fff';


if(isEmpty($('#product_tags').val())){
$('#product_tags').next('span').find('ul.select2-selection__rendered').css('background','#ffecec');
setTimeout(function(){$('html, body').animate({scrollTop: $('#product_tags').offset().top - 150}, 800)}, 500);
errors++;
return false;
}//else $('ul.select2-selection__rendered').css('background','#fff');

if(errors > 0){
 evt.preventDefault();
	}else{
$('#save_prd').hide();
$('#global-loader span').html(messages.saving);
$('#global-loader').fadeIn();
	}
	
})



$('#global-loader span').html(messages.loading);

})(jQuery);