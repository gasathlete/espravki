function img_resize(){var d=document.getElementsByTagName('img');var r=0,w=0,h=0;for(var i=0;i<d.length;i++){if(d[i].getAttribute('nw')!=null && d[i].getAttribute('nh')!=null){w=Number(d[i].getAttribute('nw')); h=Number(d[i].getAttribute('nw'));r=Number(d[i].width)/Number(d[i].height);if ((w/h)>r) d[i].width=Math.floor(h*r);else d[i].height=Math.floor(w/r);if((w-d[i].width)>4) d[i].style.padding='2px '+Math.floor((w-d[i].width)/2)+'px';else if((h-d[i].height)>4) d[i].style.padding=Math.floor((h-d[i].height)/2)+'px 2px';}}}
function numberFormat(e){var c=0;if(e.keyCode) c=e.keyCode; else c=e.which;switch(c){case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}}
$("document").ready(function(){
$('#nav_up').hide();if(window.pageYOffset > 300){$('#nav_up').show();}
$(window).scroll(function(){if ($(window).scrollTop() > 300) {$('#nav_up').fadeIn('slow');}else{$('#nav_up').fadeOut('slow');}});
$("#nav_up").click(function(){$('html, body').animate({scrollTop: 0},500);$('#nav_up').show();});
if($("select.transform").length){$("select.transform").selectBoxIt();}

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
var userscreenheight=$(window).height();
if(!isMobile.any()) $('#search').css('height',(userscreenheight - 100));
$('.s').hide();
	  $(function () {
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
    });

$('#closebtn').click(function(){$("#overlay,.steps").hide();$("body").css('overflow-y','auto');$("#gplus").attr('checked', false);});
$.get("http://www.testing.espravki.com/modules/menu.php", function(data, status){
  $('#nav').html(data);
//alert("Data: " + data + "\nStatus: " + status);
});

var select = $("select.transform"); 
$("p.clear").click(function(){

select.find('option').removeAttr("selected");
select.find("option[value='0']").attr('selected','selected');
select.data("selectBox-selectBoxIt").refresh();
//selectBox.selectOption('0');
//document.getElementById("sc").selectedIndex = 0;
//$('select.transform option[value="0"]').attr("selected",true);
//$("select.transform").find('option[value=0]').attr('selected', 'selected')
	});
});
$(function() {
if($("#srctxt").length){
function split( val ) {
return val.split( /,\s*/ 
);    
}function extractLast( term ) {
return split( term ).pop();    
}
var $j = jQuery.noConflict();
$j( "#srctxt" ).bind( "keydown", function( event ) {
$('#exact_match').val('0');
if ( event.keyCode === $j.ui.keyCode.TAB &&            
$j( this ).data( "ui-autocomplete" ).menu.active ) {
event.preventDefault();      
}      
})      
.autocomplete({
source: function( request, response ) {
$.getJSON( "http://www.testing.espravki.com/ajax/search.php", {
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
$('#exact_match').val('1');
var keywords = $('#f10').val();
var country = $("#f0 option:selected").text();
var area = $('#f5').val();
var city = $('#f4').val();
if($('#f10').val() !=''){
$('#f10').val(keywords + ui.item.value + ', ' + ui.item.value + ' in ' + country + ', ' + ui.item.value + ' in ' + area + ', ' + ui.item.value + ' in ' + city + ', ');
}else $('#f10').val(ui.item.value +', ' + ui.item.value + ' in ' + country + ', ' + ui.item.value + ' in ' + area + ', ' + ui.item.value + ' in ' + city + ', ');        
// add placeholder to get the comma-and-space at the end          
terms.push( "" );          
this.value = terms.join( "" );
return false;        
}      
});
}
});
