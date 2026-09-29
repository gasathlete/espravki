function set_cat(v){
$('#newscats').val(v);
document.getElementById("searchform").submit(); 
}
$('document').ready(function(){
$('#contactusbtn').on('click', function(e) {
	$('.msg').html('');
	var name = $.trim($('#name').val());
	var fname = $.trim($('#fname').val());
	var mail = $.trim($('#mail').val());
	var phone = $.trim($('#phone').val());
	var subject = $.trim($('#subject').val());
	var message = $.trim($('#message').val());
	var errors = 0;
	
	var form_data = new FormData();
	var files = '';
	var filetoup = '';
	if(document.getElementById("up_doc").value != "") files = document.getElementById("up_doc").files[0];
	
	
	//console.log(files.name+' - '+files.size);
	if(files.size > 0){
	//alert();
    var ext = files.name.split('.').pop().toLowerCase();
	//alert(ext);
	if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf','doc','ppt']) == -1){
     errors++;
	$('.msg').html(messages.invalidfile +' '+files.name);
	$('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	return false;
    }
	
	var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("up_doc").files[0]);
    //var f = document.getElementById("up_doc").files[0];
	if(files.size > 3000000){
	errors++;
	$('.msg').html(messages.bigimage + '<br />'+ files.name +'<br />'+ messages.file_size + ' / '+(files.size/1000)+' KB');
	$('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	return false;
		}else{
		filetoup = document.getElementById('up_doc').files[0];
		}
	}
	
	
	
	if(isEmpty(name) || name.length < 3){
	$('#name').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#name').css('background','#fff');
	
	if(isEmpty(fname) || fname.length < 3){
	$('#fname').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#fname').css('background','#fff');
	
	if(!validateEmail(mail)){
	  $('#mail').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#mail').css('background','#fff');
	
	if(isEmpty(phone) || phone.length < 8){
	$('#phone').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#phone').css('background','#fff');
	
	if(isEmpty(subject) || subject < 1){
	$('#subject').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#subject').css('background','#fff');
	
	if(isEmpty(message) || message.length < 20){
	$('#message').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#message').css('background','#fff');
	
	 
	
	if(errors < 1){
	$('#global-loader').show();
	form_data.append('fname', fname);
	form_data.append('name', name);
	form_data.append('mail', mail);
	form_data.append('phone', phone);
	form_data.append('subject', subject);
	form_data.append('message', message);
	form_data.append("filetoup", filetoup);

	
	$.ajax({
	method: 'post',
    url: "/ajax/contactus.php",
    async: false,
	dataType: "json",
	contentType: false,
    cache: false,
	async: false,
    processData: false,
	data: form_data,
	beforeSend:function(){
     $('#global-loader span').html('<br /><label class="text-primary">' + messages.sending + '</label>');
    },
	success: function(data){
	$('.msg').html(data[0]);
	 $('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	if(data[1] > 0){
	$('#contactusbtn').remove();
	
	}
	$('#global-loader').hide();
			}
		});
		return false;
	}
	})
$('body').on('click', '.fa-eye', function(){
var ids = $(this).attr('data-id');
var types = $('#'+ids).attr('type');
if(types === 'password') $('#'+ids).prop('type','text');
else $('#'+ids).prop('type','password');
})

$(".text-secondary").click(function(){
        $("#pass_forgotten,#regs,#pass_forgotten,#hlink,#loginbtn,#llink").removeClass('active');
    });

$('body').on('click', '#loginbtn', function(){
	
var email = $.trim($('#login_mail').val());
var pass = $.trim($('#login_password').val());
var error = 0;
if(isEmpty(email) || email.length < 6 || !validateEmail(email)){
error++;
	$('#login_mail').css('background',"#ffecec");
	return false;
	}else $('#login_mail').css('background','#fff');

if(isEmpty(pass) || pass.length < 6){
error++;
	$('#login_password').css('background',"#ffecec");
	return false;
	}else $('#login_password').css('background','#fff');
	
	if(error < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/check_login.php",
    async: true,
	dataType: "json",
    data: { "email": email,"pass": pass}, 
	success: function(data){
	//$('#typepsdiv').html(data);
	$('#global-loader').hide();
	$('.msg').html(data[0]);
	if(data[1] > 0){
	//$('.loginf').hide();
	location.reload();
	}else{
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 60
    }, 1000);
	}
			}
		});
		return false;
	}
	})
	
	$('body').on('click', '#pass_forgotten', function(){
	$('#forg_mail').val($.trim($('#login_mail').val()));
	})
	
	$('body').on('click', '#n_p', function(){
	var email = $.trim($('#forg_mail').val());
	var errors = 0;
	if(isEmpty(email) || email.length < 6 || !validateEmail(email)){
	errors++;
	$('#forg_mail').css('background',"#ffecec");
	return false;
	}else $('#forg_mail').css('background','#fff');
	
	
	if(errors < 1){
	
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/passforgotten.php",
    async: true,
	dataType: "json",
    data: { "email": email}, 
	success: function(data){
	$('.msg').html(data[0]);
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 60
    }, 1000);
	$('#global-loader').hide();
	}
		});
		return false;
	}
	})

	$('#regs').on('click', function() {
	$('#profile-tab').click();
	//alert();
	})
	
//$('#reg_name').on('keyup', function() {
$('body').on('keyup', '#reg_name', function(){
var regex = /^[a-zA-Zа-яА-Я\s]+$/;
if (!regex.test($("#reg_name").val())) {
//alert($("#reg_name").val());
console.log("caller is " + arguments.callee.caller.toString());

$('.msg').html(messages.er_let);
$('#reg_name').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_name').css('background','#fff');
});


$('body').on('click', '#registerme', function(){
var reg_name = $.trim($('#reg_name').val());
var reg_email = $.trim($('#reg_email').val());
var reg_password = $.trim($('#reg_password').val());
var code = $.trim($('#code').val());
var terms = $('#terms:checked').length;
var error = 0;
var regex = /^[a-zA-Zа-яА-Я\s]+$/;

//alert(terms);


if(isEmpty(reg_name) || reg_name.length < 6 || !regex.test(reg_name)){
error++;
$('.msg').html(messages.er_let);
	$('#reg_name').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_name').css('background','#fff');
	
if(isEmpty(reg_email) || reg_email.length < 6 || !validateEmail(reg_email)){
error++;
	$('#reg_email').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#reg_email").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_email').css('background','#fff');

if(isEmpty(reg_password) || reg_password.length < 6){
error++;
	$('#reg_password').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#reg_password").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_password').css('background','#fff');
	
	
	if(terms < 1){
	$('.custom-checkbox').css('background',"#ffecec");
	error++;
	return false;
	}else $('.custom-checkbox').css('background',"#fff");
	
	if(error < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/register.php",
    async: true,
	dataType: "json",
    data: { "reg_name": reg_name,"reg_email": reg_email,"reg_password": reg_password, "code":code}, 
	success: function(data){
	$('#global-loader').hide();
	$('.msg').html(data[0]);
	if(data[2] > 0){
	$('#validationdiv').modal('show')
	}else{
		if(data[1] > 0){
		//$('.loginf').hide();
		location.reload();
		}else{
		$('html, body').animate({
		scrollTop: $(".msg").offset().top  - 60
		}, 1000);
		}
	}
			}
		});
		return false;
	}
	})
	
$('body').on('click', '#confirmcode', function(){
var reg_name = $.trim($('#reg_name').val());
var reg_email = $.trim($('#reg_email').val());
var reg_password = $.trim($('#reg_password').val());
var code = $.trim($('#code').val());
var error = 0;
var regex = /^[a-zA-Zа-яА-Я\s]+$/;


if(isEmpty(code) || code.length < 6){
error++;
$('.msg').html(messages.er_let);
	$('#code').css('background',"#ffecec");
	return false;
	}else $('#code').css('background','#fff');
	
	
if(isEmpty(reg_name) || reg_name.length < 6 || !regex.test(reg_name)){
error++;
$('.msg').html(messages.er_let);
	$('#reg_name').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_name').css('background','#fff');
	
if(isEmpty(reg_email) || reg_email.length < 6 || !validateEmail(reg_email)){
error++;
	$('#reg_email').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#reg_email").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_email').css('background','#fff');

if(isEmpty(reg_password) || reg_password.length < 6){
error++;
	$('#reg_password').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#reg_password").offset().top  - 160
    }, 1000);
	return false;
	}else $('#reg_password').css('background','#fff');
	
	if(error < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/register.php",
    async: true,
	dataType: "json",
    data: { "reg_name": reg_name,"reg_email": reg_email,"reg_password": reg_password, "code":code}, 
	success: function(data){
	$('#global-loader').hide();
	$('.msg').html(data[0]);
	if(data[2] > 0){
	$('.formsg').html(data[0]);
	$('#validationdiv').modal('show');
	}else{
		if(data[1] > 0){
		//$('.loginf').hide();
		location.reload();
		}else{
		$('html, body').animate({
		scrollTop: $(".msg").offset().top  - 160
		}, 1000);
		}
		$('#validationdiv').modal('hide');
	}
			}
		});
		return false;
	}
	})

	 
$('body').on('click', '.likes', function(){
$('#like').val($(this).attr('data-id'));
$('#votes').css('background','#fff');
})


	
	$('body').on('click', '#savecomment', function(){
var names = $.trim($('#names').val());
var email = $.trim($('#email').val());
var review = $.trim($('#review').val());
var id = $.trim($('#aid').val());
var atitle = $.trim($('#atitle').val());
var like = $.trim($('#like').val());
var error = 0;
var regex = /^[a-zA-Zа-яА-Я\s]+$/;

if(isEmpty(id) || id < 1){
error++;
return false;
}


if(isEmpty(like) || like < 2){
error++;
$('.msg').html(messages.mustvote);
	$('#votes').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	return false;
}else $('#votes').css('background','#fff');

if(isEmpty(names) || names.length < 6 || !regex.test(names)){
error++;
$('.msg').html(messages.er_let);
	$('#names').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	return false;
	}else $('#names').css('background','#fff');
	
if(isEmpty(email) || email.length < 6 || !validateEmail(email)){
error++;
	$('#email').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#email").offset().top  - 160
    }, 1000);
	return false;
	}else $('#email').css('background','#fff');

if(isEmpty(review) || review.length < 10){
error++;
	$('#review').css('background',"#ffecec");
	$('html, body').animate({
	scrollTop: $("#review").offset().top  - 160
    }, 1000);
	return false;
	}else $('#review').css('background','#fff');
	
	if(error < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/save_comment.php",
    async: true,
	dataType: "json",
    data: { "names": names,"email": email,"review": review, "id": id, "atitle": atitle, "rating": like}, 
	success: function(data){
	$('#global-loader').hide();
	$('.msg').html(data[0]);
	if(data[1] > 0){
	//$('.loginf').hide();
	//location.reload();
	}else{
	$('html, body').animate({
	scrollTop: $(".msg").offset().top  - 160
    }, 1000);
	}
			}
		});
		return false;
	}
	})
	
$('body').on('click', 'a.tagsclicker', function() {
$('#searchnews').val($(this).attr('data-tag'));
document.getElementById("searchform").submit();
})

if($('.newstab').length){
var running = false;

$(window).bind('scroll', function() {
if(!running){

if($(window).scrollTop() >= $('.newstab').offset().top + $('.newstab').outerHeight() - window.innerHeight) {
 
running = true;
var t = 'articles';
var viewtype = '';
var searchnews = $.trim($('#searchnews').val());
var cat = $.trim($('#newscats').val());
$('.tab-pane').each(function(){
if ($(this).hasClass("active")) {
viewtype = $(this).attr( "id" );
}
})

var value = parseInt($('#'+viewtype).find('div.snglart:last').attr('data-id'));

 
 if(value > 0){
  //console.log('end ' + value+" "+ viewtype);
  $.ajax({
	type: 'post',
    url: "/ajax/get_more.php",
    async: true,
	dataType: "json",
    data: { "t": t,"v": value, "viewtype":viewtype,"cat":cat,"searchnews":searchnews}, 
	success: function(data){
	$('#global-loader').hide();
	//$('.newstab').html(data[0]);
	//$('.newstab').find('div.snglart:last')
	$( data[11] ).insertAfter( $('#tab-11').find('div.snglart:last') );
	$( data[12] ).insertAfter( $('#tab-12').find('div.snglart:last') );
	if(data[1] > 0){
	running = false;
	}else{
//	running = false;
$( data[0] ).insertAfter( $('#'+viewtype).find('div.snglart:last') );
}
			}
		});
		return false;
 }
			}
			}
		});
	
}

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

if($('#sh_more').length){
$('#show_more').showmore({
		closedHeight: 5,
		buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
		buttonCssClass: 'showmore-button',
		animationSpeed: 0.5
	});
	
	
}

if($('#video').length){
document.addEventListener('click', musicPlay);
function musicPlay() {
if( document.cookie.indexOf("videoplayed") ===-1 ){
    document.getElementById('video').play();
    document.removeEventListener('click', musicPlay);
	$('html, body').animate({scrollTop: $('#video').offset().top - 150}, 800);
	SetCookie('videoplayed','1',1);
		}
	}
}

document.fonts.ready.then(function () {
  //alert('All fonts in use by visible text have loaded.');
  // alert('Roboto loaded? ' + document.fonts.check('1em Roboto'));  // true
});

//if($('#profile-tab').length) $('#profile-tab').click();
})