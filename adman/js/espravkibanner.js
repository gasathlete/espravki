if (!window.jQuery) {
  var jq = document.createElement('script'); jq.type = 'text/javascript';
  jq.src = 'http://shoppingbulgaria.com/js/jalerts/jquery.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}

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
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
if(!isMobile.any() ){
var jq1 = document.createElement('script'); jq1.type = 'text/javascript';
  jq1.src = 'http://shoppingbulgaria.com/js/jquery.cycle.all.js';
  document.getElementsByTagName('head')[0].appendChild(jq1);
  

  var cssId = 'espravkibanner';  // you could encode the css path itself to generate id..
if (!document.getElementById(cssId)){
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = cssId;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = 'http://www.espravki.com/css/espravkibanner.css';
    link.media = 'all';
    head.appendChild(link);
}

function aa(){
$('body').append("<div class='eslideshowcover3'></div>");

$('.eslideshowcover3').load('http://www.espravki.com/modules/showbanners.php',function(){

function SetCookie(c_name,value,expiredays){var exdate=new Date();exdate.setDate(exdate.getDate()+expiredays);document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());}

var countdivs = $('.eslideshow div').length;
if(countdivs > 1){
jQuery(".eslideshow").cycle({
timeout:8000, // no autoplay is 0
fx: 'fade', //nalichni effecti: zoom, fade, turnDown, curtainX, scrollRight,
next: '#next',
prev: '#prev'
		});
	}
	
$('.min').click(function(){
if( document.cookie.indexOf("minimize2") ===-1 )	SetCookie('minimize2','minimize2',1);
	$('.eslideshowcover3').animate({bottom:'-265'}, 1000);
	setTimeout(function(){
	$('.offercount').fadeOut(2000)
	}, 2000);
	$('.min').hide();
});

if($(".eslideshowcover3").length){
if( document.cookie.indexOf("minimize2") ===-1 ){
setTimeout(function(){$(".eslideshowcover3").animate({bottom:0}, 1000)}, 3000);
$('.offercount').fadeOut(3000);
}else{
$('.offercount').show();
setTimeout(function(){
	$('.offercount').fadeOut(2000)
	}, 2000);
//setTimeout(function(){$(".eslideshowcover3").animate({bottom:-265}, 1000)}, 3000);
$('.eslideshowcover3 > .slideshow h3').fadeIn(2000);
$('.min').hide();
}
$('.eslideshowcover3 > .slideshow h3').click(function(){
	$('.offercount').fadeIn(2000);
	$('.eslideshowcover3').animate({bottom:'0'}, 1000);
	$('.min').fadeIn(2000);
});

$('h3').click(function(){
$('.eslideshowcover3').animate({bottom:'0'}, 1000);
$('.min').fadeIn(2000);
});
$('.offercount').click(function(){
	$('.eslideshowcover3 > .slideshow h3').fadeIn(2000);
	$('.eslideshowcover3').animate({bottom:'0'}, 1000);
	$('.min').fadeIn(2000);
});
}
});
	}
}