function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}

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


$("document").ready(function(){
$('#editprofile').click(function(){
	var your_names = $.trim($('#your_names').val());
	var your_phone = $.trim($('#your_phone').val());
	var city = $.trim($('#f0').val());
	var delivery_address = $.trim($('#delivery_address').val());
	
	if(your_names === '' || your_names.length < 5){
	$("#your_names").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $(".rws > h2").offset().top
    }, 1000);
	return false;
	}else $("#your_names").css('background','#fff');
	
	if(your_phone === '' || your_phone.length < 5){
	$("#your_phone").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#your_names").offset().top
    }, 1000);
	return false;
	}else $("#your_phone").css('background','#fff');
	
	if(city === '' || city < 1){
	$("#f0SelectBoxItContainer").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#your_phone").offset().top
    }, 1000);
	return false;
	}else $("#f0SelectBoxItContainer").css('background','#fff');
	
	if(delivery_address === '' || delivery_address.length < 15){
	$("#delivery_address").css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("#f0SelectBoxItContainer").offset().top
    }, 1000);
	return false;
	}else $("#delivery_address").css('background','#fff');

})

if($("select.transform").length){$("select.transform").selectBoxIt();}

$.get("../../modules/menu.php", function(data, status){
  $('#nav').html(data);
//alert("Data: " + data + "\nStatus: " + status);
});
$('#showhide').toggle(
	  function(){
	  $('#menu').show();
	  if(isMobile.any()){
	  //alert(window.screen.availWidth);
	  $("#menu, #nav, ul.subul").css('max-width','none');
	  $("#menu").animate({left: "0px"});
	  }else $("#menu").animate({left: "0px"});
		  
		   $("#search").animate({left: "200px"});
	  },
	  function(){
	  $("#search").animate({left: "0px"});
	  if(isMobile.any()){
	  $("#menu").animate({left: "-100%"});
	  }else $("#menu").animate({left: "-320px"});
	  //$('#menu').hide();
	  }
  );
  
  $('#cancel').click(function(){
  var url = $(this).attr('data');
  jAlert('confirm', 'Сигурни ли сте, че желаете да откажете поръчката? Ако потвърдите с ОК повече няма да можете да променяте статуса й!', 'Внимание !');
	$('#popup_ok').click(function(){
	window.location.href = "./index.php?orders=show&delete="+url;
	//document.products_filter.submit();
	});
	});
});