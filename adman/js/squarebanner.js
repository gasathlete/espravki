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

$('document').ready(function(){
var countdivs = $('.eslideshow div').length;
if(countdivs >= 1){
//$('#preva').hide();
jQuery(".eslideshow").cycle({
timeout:8000, // no autoplay is 0
fx: 'fade', //nalichni effecti: zoom, fade, turnDown, curtainX, scrollRight,
next: '#nexta',
prev: '#preva'
	});
	}
});