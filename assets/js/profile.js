function isEmpty(value){return (value == null || value.length === 0);}
function priceFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 46:case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}
function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}

$('document').ready(function(){
$('body').on('click', '.fa-eye', function(){
var ids = $(this).attr('data-id');
var types = $('#'+ids).attr('type');
if(types === 'password') $('#'+ids).prop('type','text');
else $('#'+ids).prop('type','password');
})


$('body').on('click','.del_advert',function(){
$('#confirm_del').attr('data-id',$(this).attr('data-id'));
$('#deletediv').modal('show');
})

$('body').on('click','#confirm_del',function(){
var aid = $.trim($(this).attr('data-id'));
if(aid > 0){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/delete_advert.php",
	dataType: "json",
    async: true,
    data: { "aid": aid}, 
	success: function(data){
	$('.text-orange').html(data[0]);
	$('#deletediv').modal('hide');
	$('#aid_'+aid).remove();
	setTimeout(function(){
	$('#global-loader').fadeOut(500)
	}, 500);
	

	//location.reload();
			}
		});
	}
})


$('body').on('click','.del_product',function(){
$('#confirm_del2').attr('data-id',$(this).attr('data-id'));
$('#confirm_del2').attr('data-aid',$(this).attr('data-aid'));
$('#deletediv').modal('show');
})

$('body').on('click','#confirm_del2',function(){
var pid = $.trim($(this).attr('data-id'));
var aid = $.trim($(this).attr('data-aid'));
if(pid > 0 && aid > 0){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/delete_prd.php",
	dataType: "json",
    async: true,
    data: { "pid": pid,"aid": aid}, 
	success: function(data){
	$('.text-orange').html(data[0]);
	$('#deletediv').modal('hide');
	$('#pid_'+pid).remove();
	setTimeout(function(){
	$('#global-loader').fadeOut(500)
	}, 500);
	
	if(data[1].length > 10) $('#img_preview').html(data[1]);
	location.reload();
			}
		});
	}
})


if($('#all_b').length) $('#all_b span').html(($("div[data-status='active']").length + $("div[data-status='pendings']").length));
if($('#active').length) $('#active span').html($("div[data-status='active']").length);
if($('#pendings').length) $('#pendings span').html($("div[data-status='pendings']").length);

$('body').on('click','a#all_b',function(){
$('.advert').show();
})

$('body').on('click','a#active',function(){
$('.advert').hide();
$("div[data-status='active']").show();
})

$('body').on('click','a#pendings',function(){
$('.advert').hide();
$("div[data-status='pendings']").show();
})

if($('#all_ords').length){
if($(".not_active").length) $('#not_active span').html($(".not_active").length);
else $('#not_active').closest("li").hide();

if($('.pending').length) $('#pending span').html($(".pending").length);
else $('#pending').closest("li").hide();

if($('.canceled').length) $('#canceled span').html($(".canceled").length);
else $('#canceled').closest("li").hide();

if($('.finished').length) $('#finished span').html($(".finished").length);
else $('#finished').closest("li").hide();

$('body').on('click','a#all_ords',function(){
$('.orders').show();
var sum = 0;
$('.all_p').each(function(){
    sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
});
$('#total').text(sum.toFixed(2));
})

$('body').on('click','a#not_active',function(){
$('.orders').hide();
$(".not_active").show();
var sum = 0;
$('.not_active_p').each(function(){
    sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
});
$('#total').text(sum.toFixed(2));
})

$('body').on('click','a#pending',function(){
$('.orders').hide();
$(".pending").show();
var sum = 0;
$('.pending_p').each(function(){
    sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
});
$('#total').text(sum.toFixed(2));
})

$('body').on('click','a#canceled',function(){
$('.orders').hide();
$(".canceled").show();
var sum = 0;
$('.canceled_p').each(function(){
    sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
});
$('#total').text(sum.toFixed(2));
})

$('body').on('click','a#finished',function(){
$('.orders').hide();
$(".finished").show();
var sum = 0;
$('.finished_p').each(function(){
    sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
});
$('#total').text(sum.toFixed(2));
})
$('body').on('click','.all_a',function(){
$('.all_a').removeClass('active');
$(this).addClass('active');
	})
}

$('body').on('change','#change_status',function(){
$('#change_status_btn').attr('data-status',$(this).val());
$('#statusdiv').modal('show');
})

$('body').on('click','#change_status_btn',function(){
var status = $.trim($(this).attr('data-status'));
var id = $.trim($(this).attr('data-id'));

if(!isEmpty(status) && id.length == 32){
$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/change_ord_status.php",
	dataType: "json",
    async: true,
    data: { "status": status,"id": id}, 
	success: function(data){
	$('.orange').html(data[0]);
	$('#statusdiv').modal('hide');
	if(data[2]) $('#change_status').prop('disabled',true);
	setTimeout(function(){
	$('#global-loader').fadeOut(500)
	}, 500);
	
			}
		});
	}
//console.log(status);
})


if($('.card-header').length) $('html, body').animate({scrollTop: $('.card-header').offset().top - 180}, 800);
})