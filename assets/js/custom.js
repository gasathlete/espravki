function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}
function validateEmail(email) {var re = /\S+@\S+\.\S+/;return re.test(email);}
function isEmpty(value){return (value == null || value.length === 0);}
function SetCookie(c_name,value,expiredays){var exdate=new Date();exdate.setDate(exdate.getDate()+expiredays);document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());}
function setCookies(name, value, days) {
	    var d = new Date;
	    d.setTime(d.getTime() + 24*60*60*1000*days);
	    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
	}
function showPosition(position) {
 // console.log( position +" Latitude: " + position.coords.latitude +  "<br>Longitude: " + position.coords.longitude);
  
  if(position.coords.latitude && position.coords.longitude){
	$.ajax({
	type: 'post',
    url: "/ajax/latlng.php",
    async: true,
	dataType: "json",
    data: {
	"lat": position.coords.latitude,
	"lng": position.coords.longitude
	}, 
	success: function(data){
	//$('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	$('#global-loader span').html(data[0]);
	if(data[2]){
	//alert(data[2]);
	$('#search_city').val(data[2]);
	//SetCookie('subscribed','subscribed',365*10);
	}
	//$('#global-loader span').html(messages.ready);
	$('#global-loader').delay(1000).fadeOut(1000);
			}
		});
		return false;
	
	}else{
	$('#global-loader span').html(messages.ready);
  $('#global-loader').fadeOut();
	}
  
}
function getCookie(name) {
	    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	    return v ? v[2] : null;
	}
function errorCallback(error) {
    if (error.code == error.PERMISSION_DENIED) {
	$('#global-loader span').html(messages.not_share_loc);
	//setTimeout($('#global-loader').fadeOut(), 3000);
	$('#global-loader').delay(3000).fadeOut(1000);
	//$('#global-loader').fadeOut();
    alert(error.code);
    }else $('#global-loader span').html(error.code);
	//console.log(error);
	//alert(error.code);
}

function getLocation(){
//alert(document.cookie);
  if (navigator.geolocation) {
  $('#global-loader span').html(messages.share_loc);
  $('#global-loader').show();
  /*navigator.geolocation.getCurrentPosition(function (showPosition){
	//console.log("Got position: ",pos,"-",pos.coords.latitude, pos.coords.longitude);
}, function error(err){
	setCookies('geolocations','1',1);
	alert(err.message);
});*/
	navigator.geolocation.getCurrentPosition(showPosition,errorCallback,{
        maximumAge: Infinity,
        timeout:200000
    });
  } else {
   // x.innerHTML = "Geolocation is not supported by this browser.";
	$('#msg').html(messages.error_allow_geolocation);
  }
  
}

(function($) {
	"use strict";
	
	// When the user scrolls the page, execute myFunction 
	window.onscroll = function() {myFunction()};
	function myFunction() {
		var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
		var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
		var scrolled = (winScroll / height) * 100;
		document.getElementById("myBar").style.width = scrolled + "%";
	}
	
	// ______________Active Class
	$(document).ready(function() {
		$(".horizontalMenu-list li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
		$(".horizontal-megamenu li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().parent().parent().parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
		$(".horizontalMenu-list .sub-menu .sub-menu li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
	});
	
	// ______________ Back to Top
	$(window).on("scroll", function(e) {
		if ($(this).scrollTop() > 0) {
			$('#back-to-top').fadeIn('slow');
		} else {
			$('#back-to-top').fadeOut('slow');
		}
	});
	$("#back-to-top").on("click", function(e) {
		$("html, body").animate({
			scrollTop: 0
		}, 600);
		return false;
	});
	
	
	
	// ______________Quantity-right-plus
	var quantitiy = 0;
	$('.quantity-right-plus').on('click', function(e) {
		e.preventDefault();
		var quantity = parseInt($('#quantity').val()); 
		$('#quantity').val(quantity + 1); 
	});
	$('.quantity-left-minus').on('click', function(e) {
		e.preventDefault();
		var quantity = parseInt($('#quantity').val());
		if (quantity > 0) {
			$('#quantity').val(quantity - 1);
		}
	});		
	
	// ______________Chart-circle
	if ($('.chart-circle').length) {
		$('.chart-circle').each(function() {
			let $this = $(this);
			$this.circleProgress({
				fill: {
					color: $this.attr('data-color')
				},
				size: $this.height(),
				startAngle: -Math.PI / 4 * 2,
				emptyFill: '#f9faff',
				lineCap: ''
			});
		});
	}
	
	const DIV_CARD = 'div.card';	
	
	// ______________Tooltip
	$('[data-toggle="tooltip"]').tooltip();
	
	// ______________Popover
	$('[data-toggle="popover"]').popover({
		html: true
	});
	
	// ______________Card Remove
	$('[data-toggle="card-remove"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.remove();
		e.preventDefault();
		return false;
	});
	
	// ______________Card Collapse
	$('[data-toggle="card-collapse"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-collapsed');
		e.preventDefault();
		return false;
	});
	
	// ______________Card Full Screen
	$('[data-toggle="card-fullscreen"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-fullscreen').removeClass('card-collapsed');
		e.preventDefault();
		return false;
	});
	
	$('#subscribe').on('click', function(e) {
	if( document.cookie.indexOf("subscribed") ===-1 ){
	var email = $.trim($('#email').val());
	var errors = 0;
	if(!validateEmail(email)){
	  $('#email').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#email').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else{
	$('#email').css('background','#fff');
	if(errors < 1){
	$('#global-loader').fadeIn();
	$.ajax({
	type: 'post',
    url: "/ajax/subscribe.php",
    async: true,
	dataType: "json",
    data: {
	"email": email
	}, 
	success: function(data){
	//$('html, body').animate({scrollTop: $('.msg').offset().top - 150}, 800);
	$('.msg').html(data[0]);
	if(data[1] > 0){
	$('#sform').remove();
	SetCookie('subscribed','subscribed',365*10);
	}
	$('#global-loader').hide();
			}
		});
		return false;
	
	}
	
	}
	}
	})
})(jQuery);
