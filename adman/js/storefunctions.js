function setadverts(id) {$('#adverts').val(id);document.products_filter.submit();}
function hide_menu(){$('#catmenu').hide('slow');}
$(document).ready(function(){$("#qtotal").text(" 0 ");if($("select.transform").length){$("select.transform").selectBoxIt();}
$('h2.cats > img').show();$('h2.cats > img').toggle(function(){$('#catmenu').hide('slow');},function(){$('#catmenu').show('slow');});
if($(".filters").length){$(".filters").appendTo('#fcont');}
$('li.li0:has(ul)').addClass('arrow');$('li.li1:has(ul)').addClass('rw');
$(".subul").mouseleave(function(){$("#left_column").css('zIndex','0');$(this).fadeOut('slow');});
$("#nav").mouseleave(function(){$("#left_column").css('zIndex','0');$(this).find('ul.subul').fadeOut('slow');});
$('.tick').click(function(){$('#bcontent').show();});
$('.pagen').click(function(){var pn=$(this).attr('data');$('#pn').val(pn);document.products_filter.submit();});
$('li.main').click(function(){$("#left_column").css('zIndex','200');$("#left_column").css('width','100%');$(this).find('ul.subul').toggle('show');});
$('li.main').mouseleave(function(){$(this).find('ul.subul').hide();});
$('.category_select').change(function(){var selectedcategory='';var values = [];$('.category_select').each(function(){if(this.checked){selectedcategory=$(this).attr('data');values.push(selectedcategory);}else{$('#makes').val('');}});
$('#category_select').val(values);document.products_filter.submit();});
$('.o').click(function(){var ord=$(this).attr('data');var ordtype=$(this).attr('ordtype');$('#ord').val(ord);$('#ord_type').val(ordtype);document.products_filter.submit();});if($('#right_column').length){var element = document.getElementById('left_column');$('#right_column').css('min-height',element.offsetHeight);$('#conttable').css('min-height',element.offsetHeight + 150);}});
function gE(o){return document.getElementById(o);}
function img_resize(){var d=document.getElementsByTagName('img');var r=0,w=0,h=0;for(var i=0;i<d.length;i++){if(d[i].getAttribute('nw')!=null && d[i].getAttribute('nh')!=null){w=Number(d[i].getAttribute('nw')); h=Number(d[i].getAttribute('nw'));r=Number(d[i].width)/Number(d[i].height);if ((w/h)>r) d[i].width=Math.floor(h*r);else d[i].height=Math.floor(w/r);if((w-d[i].width)>4) d[i].style.padding='2px '+Math.floor((w-d[i].width)/2)+'px';else if((h-d[i].height)>4) d[i].style.padding=Math.floor((h-d[i].height)/2)+'px 2px';}}}
function tellafriend() {window.open('../../../modules/tell-a-friend.php','sendfriend','width=600,height=550,toolbar=0,menubar=0,scrollbars=0,status=0,resizable=0,screenx=225,screeny=230');}
$("document").ready(function(){
var countdivs = $('.slideshow div').length;
if(countdivs > 1){
jQuery(".slideshow").cycle({timeout:8000, fx: 'fade',next: '#next',prev: '#prev'});}});