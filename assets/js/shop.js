function isEmpty(value){return (value == null || value.length === 0);}
function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}

function update_amounts(){
var sum = 0.0;
var delivery_address = $.trim($('#delivery_address').val());

$('.cart > tbody  > .product').each(function(){
	var qty = parseInt($(this).find('.qty').val());
	var price = $(this).find('.pr_price').val();
	var minq = parseInt($(this).find('.qty').attr('min'));
	//var maxq = parseInt($(this).find('.qty').attr('max'));
	//if(qty <= maxq && qty >= minq && qty > 0){
	
	if(qty >= minq && qty > 0){
	var amount = (qty*price);
	sum+= amount;
	$(this).find('.subttl').text(''+ amount.toFixed(2));
	$(this).find('.qty').css('background-color', '#fff');
	$('#send_order').removeAttr('disabled');
	//$('.hint').fadeOut();
	}else{
	$(this).find('.qty').css('background-color', '#fff4fa');
	//$(this).find('.hint').fadeIn();
	$('#send_order').attr('disabled','disabled');
	$('#send_order').attr('title',messages.add_min_qty);
	//alert(qty+" "+minq+' '+maxq);
	return false;
	}
	//alert(sum);
});
if(isEmpty(delivery_address)){
$('#send_order').attr('disabled','disabled');
$('#send_order').attr('title',messages.enterdel_adr);
}
else{
$('#send_order').removeAttr('disabled');
$('#send_order').attr('title','');
}
$('#total').text(sum.toFixed(2));


if(sum < 1){
$('#total').css('background','#ffecec');
$('#send_order').attr('disabled','disabled');	
}else{
$('#total').css('background','#fff');
$('#send_order').removeAttr('disabled');
	}
}

;

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
(function($, window, document, undefined) {

    'use strict';

    var pluginName = 'showmore',
        defaults = {
            closedHeight: 100,
            buttonTextMore: 'show more',
            buttonTextLess: 'show less',
            buttonCssClass: 'showmore-button',
            animationSpeed: 0.5,
            openHeightOffset: 0,
            onlyWithWindowMaxWidth: 0
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.btn;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function() {
            if (this.settings.onlyWithWindowMaxWidth > 0) {
                this.bindResize();
                this.responsive();                
            } else {
                this.showmore();
            }
        },
        bindResize: function() {
            var self = this;
            var resizeTimer;
            $(window).on('resize', function() {
                if (resizeTimer) {
                    clearTimeout(resizeTimer);
                }
                resizeTimer = setTimeout(function() {
                    self.responsive();
                }, 250);
            });
        },
        responsive: function() {
            if ($(window).innerWidth() <= this.settings.onlyWithWindowMaxWidth) {
                this.showmore();
            } else {
                this.remove();
            }
        },
        showmore: function() {

            if (this.btn) {
                return;
            }

            var self = this;
            var element = $(this.element);
            var settings = this.settings;

            if (settings.animationSpeed > 10) {
                settings.animationSpeed = settings.animationSpeed / 1000;
            }

            var showMoreInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner more',
                text: settings.buttonTextMore
            });
            var showLessInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner less',
                text: settings.buttonTextLess
            });

            element.addClass('closed').css({
                'height': settings.closedHeight,
                'overflow': 'hidden'
            });

            var resizeTimer;
            $(window).on('resize', function() {
                if (!element.hasClass('closed')) {
                    if (resizeTimer) {
                        clearTimeout(resizeTimer);
                    }
                    resizeTimer = setTimeout(function() {
                        // resizing has "stopped"
                        self.setOpenHeight(true);
                    }, 150); // this must be less than bindResize timeout!
                }
            });

            var showMoreButton = $('<div />', {
                'class': settings.buttonCssClass,
                html: showMoreInner
            });

            showMoreButton.on('click', function(event) {
                event.preventDefault();
                if (element.hasClass('closed')) {
                    self.setOpenHeight();
                    element.removeClass('closed');
                    showMoreButton.html(showLessInner);
                } else {
                    element.css({
                        'height': settings.closedHeight,
                        'transition': 'all ' + settings.animationSpeed + 's ease'
                    }).addClass('closed');
                    showMoreButton.html(showMoreInner);
                }
            });
            element.after(showMoreButton);
            this.btn = showMoreButton;
        },

        setOpenHeight: function(noAnimation) {
            $(this.element).css({
                'height': this.getOpenHeight()
            });
            if (noAnimation) {
                $(this.element).css({
                    'transition': 'none'
                });    
            } else {
                $(this.element).css({
                    'transition': 'all ' + this.settings.animationSpeed + 's ease'
                });    
            }
        },

        getOpenHeight: function() {
            $(this.element).css({'height': 'auto', 'transition': 'none'});
            var targetHeight = $(this.element).innerHeight();
            $(this.element).css({'height': this.settings.closedHeight});
            // we must call innerHeight() otherwhise there will be no css animation
            $(this.element).innerHeight();
            return targetHeight;
        },

        remove: function() {
            // var element = $(this.element);
            if ($(this.element).hasClass('closed')) {
                this.setOpenHeight();
            }
            if (this.btn) {
                this.btn.off('click').empty().remove();
                this.btn = undefined;
            }
        }
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);


(function($) {
    "use strict";
	//alert($('#container').height());
	if($('#container').height() > 250){
	$('#container').showmore({
		closedHeight: 250,
		buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
		buttonCssClass: 'showmore-button',
		animationSpeed: 0.5
	});
	}
	/*
	if (document.documentElement.clientWidth < 900) {
		$('#container').showmore({
			closedHeight: 450,
			buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
			buttonCssClass: 'showmore-button',
			animationSpeed: 0.5
		});
	}*/
	
})(jQuery);
$('document').ready(function(){

$("body").on("click", '#send_fast_order', function() {
var pname = $.trim($('#pname').val());
var names = $.trim($('#names').val());
var cphone = $.trim($('#cphone').val());
var da = $.trim($('#delivery_address').val());
var aid = $.trim($('#aid').val());
var pid = $.trim($('#pid').val());
var errors = 0;

$('.man').each(function(){
var v = $.trim($(this).val());
var id = $.trim($(this).attr('id'));
if(isEmpty(v) || v.length < 5){
errors++;
 $('html, body').animate({scrollTop: $('#'+id).offset().top - 150}, 800);
$(this).css('background','#ffecec');
return false;
	}else $(this).css('background','#fff');
})

if(errors < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/fast_order.php",
	dataType: "json",
    async: true,
    data: { "pname": pname, "names": names, "cphone":cphone, "da":da, "aid":aid, "pid":pid}, 
	success: function(data){
	$('#global-loader img').remove();
	$('#global-loader span').html('<p><i class="fa fa-shopping-cart" aria-hidden="true"></i></p>Sending..');
	setTimeout(function(){
	$('#global-loader').fadeOut(1500);
	}, 2500);
	$('.msgaaa').text(data[0]);
	$('html, body').animate({scrollTop: $('.msgaaa').offset().top - 150}, 800);

	
			}
		});

	}
})


$("body").on("click", '.add_to_basket', function() {
var pid = $.trim($('#pid').val()); 
var qty = $.trim($('.qty').val());
var act = $.trim($(this).attr('data-act'));
var url = $.trim($(this).attr('data-url'));
var counter = $('#prd_counter').text();
var selected_option= [];
var errors = 0;


if($('.select_option')){
$('.select_option').each(function(){
var val = $.trim($(this).val());
var id = $.trim($(this).attr('id'));
var elem = $(this).next('span').find('span.select2-selection');
if(isEmpty(val) || val == "0"){

elem.css('background','#ffecec');
setTimeout(function(){
$('html, body').animate({scrollTop: elem.offset().top - 150}, 800);
}, 500);
errors++;
return false;
	}else{
	elem.css('background','#fff');
		selected_option.push({
            id: id, 
            value:  $(this).val()
        })
	}
})
}

if(qty > 0) $('.qty').css('background','#fff');
else $('.qty').css('background','#ffecec');
if(pid > 0 && qty > 0 && !isEmpty(act) && errors < 1){
$('.qty').css('background','#fff');
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/add_to_basket.php",
	dataType: "json",
    async: true,
    data: { "act": act, "url": url, "pid":pid, "qty":qty, "so":selected_option}, 
	success: function(data){
	$('#global-loader img').remove();
	$('#global-loader span').html('<p><i class="fa fa-shopping-cart" aria-hidden="true"></i></p>'+data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(1500);
	$('#atbholder').html('<a href="../../cart/" class="btn btn-secondary fs1" >'+messages.f_order+'</a>');
	}, 2500);
	$('#prd_counter').text(data[2]);
	//if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
	}
	})
	


	$('#contact_trader').on('click', function(e){
	$('.msg').html('');
	var names = $.trim($('#cnames').val());
	var email = $.trim($('#email').val());
	var phone = $.trim($('#phone').val());
	var message = $.trim($('#message').val());
	var aid = $.trim($('#aid').val());
	var pid = $.trim($('#pid').val());
	var pname = $.trim($('#pname').val());
	var errors = 0;

	//console.log(aid+'aaa');

	if(isEmpty(names) || names.length < 3){
	$('#cnames').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#cnames').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#cnames').css('background','#fff');
	

	if(isEmpty(aid) || aid < 1){
	errors++;
	return false;
	}
	

	if(!validateEmail(email)){
	  $('#email').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#cnames').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#email').css('background','#fff');

	if(isEmpty(phone) || phone.length < 8){
	$('#phone').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#cnames').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#phone').css('background','#fff');

	
	if(isEmpty(message) || message.length < 10){
	$('#message').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#cnames').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#message').css('background','#fff');
	
	 //console.log(errors);
	
	if(errors < 1){
	$('#global-loader').show();

	$.ajax({
	type: 'post',
    url: "/ajax/contact_trader.php",
	dataType: "json",
    async: true,
    data: { "names": names, "email": email, "phone":phone, "aid":aid, "pid":pid, "pname":pname, "message":message}, 
	success: function(data){
	$('.nmsg').html(data[0]);
	$('html, body').animate({scrollTop: $('.nmsg').offset().top - 150}, 800);
	$('#contact_trader').remove();
	$('#global-loader').hide();

	//if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
		return false;
	}
	})
	
	$("body").on("change", '.catradio', function() {
	$('#categoryfield').val($(this).val());
	})
	
	$("body").on("change", '.otradio', function() {
	$('#offer_type').val($(this).val());
	})
	
	$("body").on("click", '#filter_category', function() {
	//var category = $('.catradio:checked').val();
	var category = $('#category').val();
	var oftype = $('.otradio:checked').val();
	var sort = $('.sort_ch:checked').val();
	
	var marka = $('.marka:checked').val();
	var model = $('.model:checked').val();
	var modfication = $('.modification:checked').val();
	
	if(!isEmpty(category) || !isEmpty(oftype)){
	$('#categoryfield').val(category);
	$('#sort_ch').val(sort);
	//window.location.replace(messages.site_url+'/products/'+category);
	$('#search_product_form').submit();
		}
	})
	
	$("body").on("click", '#search_p', function() {
	var stext = $.trim($('#search_text').val());
	var errors = 0;
	
	if(isEmpty(stext) || stext.length < 3){
	$('#search_text').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#search_text').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#search_text').css('background','#fff');
	
	$('#search_product').click();
	$('#search_product_form').submit();
	})
	
	$("body").on("click", '#search_product', function() {
	var stext = $.trim($('#search_text').val());
	var errors = 0;
	
	if(isEmpty(stext) || stext.length < 3){
	$('#search_text').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#search_text').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#search_text').css('background','#fff');
	
	})
	
	$("body").on("click", '.tags', function() {
	var tag = $.trim($(this).text());
	
	if(isEmpty(tag) || tag.length < 3){
	  return false;
	}else{
	$('#search_text').val(tag);
	$('#search_product').click();
	$('#search_product_form').submit();
	}
	
	})
	
	$('body').on('click','.rem_from_bask',function(){
var pid = $.trim(parseInt($(this).attr('data-id')));
//if(pid > 0) alert(pid);
if(pid > 0){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/remove_from_basket.php",
	dataType: "json",
    async: true,
    data: { "pid":pid}, 
	success: function(data){
	$('#global-loader img').remove();
	$('#global-loader span').html('<p><i class="fa fa-shopping-cart" aria-hidden="true"></i></p>'+data[0]);
	setTimeout(function(){
	$('#global-loader').fadeOut(1500);
	//$('#atbholder').html('<a href="../../cart/" class="btn btn-secondary fs1" >'+messages.f_order+'</a>');
	//location.reload();
	$('#pr_'+pid).remove();
	update_amounts();
	}, 2500);
	if(data[2] < 1) $('#send_order').remove();
	$('#prd_counter,.pr_count').text(data[2]);
	
	//if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
	}
})

$('.qty').keyup(function(){
update_amounts();
});

$('body').on('change','.qty',function(){
var qty = $.trim($(this).val());
	if(qty > 0){
	//alert($(".qty").val());
   $('#send_order').removeAttr('disabled');
   $('#send_order').removeAttr('title');
   $(this).css('background-color', '#fff');
	 var pid = parseInt($(this).attr('data-id'));
	 var qty = $.trim($(this).val());
	if(qty > 0){
	$('#global-loader').fadeIn();

	$.ajax({
	type: 'post',
    url: "/ajax/set_qty.php",
	dataType: "json",
    async: true,
    data: { "qty": qty, "pid": pid}, 
	success: function(data){
	$('#global-loader').hide();
			}
		});
		}
	}else {
	$('#send_order').attr('disabled','disabled');	 
	$('#send_order').attr('title',messages.add_qty);
	$(this).css('background-color', '#ffecec');
	}
	update_amounts();
 });

 $('body').on('change','.select_option',function(){
 var so = $.trim($(this).val());
 var el = $(this).next('span').find('span.select2-selection');
 var pid = parseInt($(this).attr('data-id'));
 var optname = $(this).attr("data-name");
// console.log(optname);
 if(!isEmpty(so) && so != "0" && !isEmpty(optname)){
	el.css('background','#fff');
	
	$('#global-loader').fadeIn();

	$.ajax({
	type: 'post',
    url: "/ajax/set_product_options.php",
	dataType: "json",
    async: true,
    data: { "so": so, "pid": pid, "optname":optname}, 
	success: function(data){
	$('#global-loader').hide();
			}
		});
		
	}else el.css('background','#ffecec');
 })
 
$('body').on('click','#send_order',function(){
var delivery_address = $.trim($('#delivery_address').val());
var phone = $.trim($('#phone').val());
var total = $.trim(parseFloat($('#total').text()));
var terms = $('.terms:checked').length;

var selected_option= [];
var qty= [];
var comments = '';
if($('#comments').length) comments = $.trim($('#comments').val());

var errors = 0;


if(total < 1){
$('#total').css('background','#ffecec');
}else $('#total').css('background','#fff');


$('.qty').each(function(){
var val = $.trim(parseInt($(this).val()));
var id = $.trim($(this).attr('data-id'));
//var qty= [id];
if(val < 1){
$(this).css('background','#ffecec');
$('html, body').animate({scrollTop: $('.qty').offset().top - 150}, 800);
errors++;
return false;
	}else{
	$(this).css('background','#fff');

	/*qty.push({
	id: id,
	value:  $(this).val()
        })*/
	}
})
//console.log(qty);

$('.select_option').each(function(){
var val = $.trim($(this).val());
var id = $.trim($(this).attr('data-id'));
if(isEmpty(val) || val == "0"){
//console.log(val);
$(this).next('span').find('span.select2-selection').css('background','#ffecec');
setTimeout(function(){
$('html, body').animate({scrollTop: $('.scroller').offset().top - 150}, 800);
$(".table-responsive").animate(
//{scrollLeft: "+=300px"},
{scrollLeft: $('.select_option').offset().left - 150},
    "slow"
  );
}, 500);
errors++;
return false;
	}else{
	$('span.select2-selection').css('background','#fff');
	/*selected_option.push({
            id: id, 
            value:  $(this).val()
        })*/
	}
})
//console.log(selected_option);
if(isEmpty(phone) || phone.length < 9){
$('#phone').css('background','#ffecec');
$('html, body').animate({scrollTop: $('#phone').offset().top - 150}, 800);
errors++;
return false;
}
else $('#phone').css('background','#fff');


if(isEmpty(delivery_address) || delivery_address.length < 10){
$('#delivery_address').css('background','#ffecec');
$('html, body').animate({scrollTop: $('#delivery_address').offset().top - 150}, 800);
errors++;
return false;
}
else $('#delivery_address').css('background','#fff');

if(terms < 1){
$('.custom-checkbox').css('background','#ffecec');
$('html, body').animate({scrollTop: $('.custom-checkbox').offset().top - 150}, 800);
errors++;
return false;
}else $('.custom-checkbox').css('background','#fff');


//console.log(errors);
if(errors < 1){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/send_order.php",
	dataType: "json",
    async: true,
    data: { "act": "save", "address":delivery_address, "phone":phone, "comments":comments}, //, "qty": qty, "so":selected_option
	success: function(data){
	if(data[1] > 0){
	$('.orange').html(data[0]);
	$('html, body').animate({scrollTop: $('.orange').offset().top - 150}, 800);
	$('#send_order').remove();
	}
			}
		});
	$('#global-loader').fadeOut(1500);

}
return false;
})

if(isMobile.any()){
if($('.accordion-toggle').length) $('.accordion-toggle').click();

}


if($('.products_tab').length){
var running = false;


$(window).bind('scroll', function() {
if(!running){

if($(window).scrollTop() >= $('.products_tab').offset().top + $('.products_tab').outerHeight() - window.innerHeight) {
 
running = true;
var value = $('.singleoffer').length;
var t = 'products';
var viewtype = 'tab-12';
var srctxt = $.trim($('#search_text').val());
var cat = $.trim($('#categoryfield').val());
var offer_type = $.trim($('#offer_type').val());
var sort = $('#sort_ch').val();
var nomore = $('.nomoreproducts').length;
var category_list = '';
var make = '';
var model = '';
var modification = '';
if($('#category_list').length){
 //category_list = JSON.parse($('#category_list').val());
 //category_list = $("#category_list").val().serialize(); 
 }

if($('#make').length){
make = $("#make").val();
}
if($('#model').length){
model = $("#model").val();
}
if($('#modification').length){
modification = $("#modification").val();
}
//var value = parseInt($('#'+viewtype).find('div.singleoffer:last').attr('data-id'));
 
 if(value > 11 && nomore < 1){
  //console.log('end ' + value+" "+ viewtype);
  $.ajax({
	type: 'post',
    url: "/ajax/get_more.php",
    async: true,
	dataType: 'json',
    data: { "t": t,"v": value, "viewtype":viewtype,"cat":cat,"search":srctxt,"offer_type":offer_type,"sort":sort, "catlist":category_list, "make":make, "model":model, "modification":modification}, 
	success: function(data){
	$('#global-loader').hide();
	//$('.adverts_tab').html(data[0]);
	//$('.adverts_tab').find('div.singleoffer:last')
	$( data[0] ).insertAfter( $('#tab-12').find('div.singleoffer:last') );
	//$( data[2] ).insertAfter( $('#tab-12').find('div.singleoffer:last') );
	$('#cr').html($('.singleoffer').length);
	if(data[1] > 0){
	running = false;
	}else{
//	running = false;
$('#cr').html($('.singleoffer').length);
//$( data[0] ).insertAfter( $('#'+viewtype).find('div.singleoffer:last') );
}
			}
		});
		return false;
 }
			}
			}
		});
}

$('body').on('change','.select2-show-search-prdcat',function(){
$('#categoryfield').val($(this).val());
var url = $('#category option:selected').attr("data-url");
//alert($('#category option:selected').attr("data-url"));
if(!isEmpty(url)) $('#search_product_form').attr('action',url);
$('#search_text').val('');
$('#filter_category').click();
})

$('body').on('change','.marka',function(){
$('#make').val($('.marka:checked').val());
$('#filter_category').click();
})
$('body').on('change','.model',function(){
$('#model').val($('.model:checked').val());
$('#filter_category').click();
})
$('body').on('change','.modification',function(){
$('#modification').val($('.modification:checked').val());
$('#filter_category').click();
})
})