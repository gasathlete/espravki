var xmlHttptest=GetXmlHttpObject();
var hajbusy=0;
function GetXmlHttpObject(){var xml=null;try{xml=new XMLHttpRequest();}catch(e){try{xml=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){xml=new ActiveXObject("Microsoft.XMLHTTP");}}if(xml==null) alert('browser does not support ajax...');return xml;}
function product_vote(aid,vote_value){
	if (xmlHttptest == null) return;
	if (hajbusy == 1) return;
	hajbusy = 1;
	var url = "../../ajax/product_votes.php";
	var params ="aid="+ encodeURIComponent(aid) + "&vote_value=" + encodeURIComponent(vote_value);
	xmlHttptest.open("POST",url,true);
	xmlHttptest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttptest.setRequestHeader("Content-length", params.length);
	xmlHttptest.setRequestHeader("Connection", "close");
	xmlHttptest.onreadystatechange=return_product_vote;
	xmlHttptest.send(params);
}
function change_stars(to){
$('img.is').each(function() {
var aa = $(this).attr('id');
var bb = document.getElementById(aa);
bb.src='../../images/interface/star3.png';
});
	for(i=1;i<=to;i++){
	//console.log(i);
	var obj=document.getElementById('star_'+i);
	if(obj)	obj.src='../../images/interface/star1.png';
	}
	$('#selectedstars').val((to));
}
function restore_stars(to){
	var to=10;
	for(var i=1;i<=to;i++){
	var obj=document.getElementById('star_'+i);
	if(obj)	obj.src='../../images/interface/star3.png';
	}
		$('#voted_stars').mouseleave(function(){
	var rating=$('#voted_stars').attr('data');
	var floatrating=Math.floor(rating);

	for(i=1;i<=floatrating;i++){
	var obj ='';
	var tochange = (Math.floor(rating) + 1);
	var obj=document.getElementById('star_'+(floatrating + 1));
	if(rating % 1 != 0)	obj.src='../../images/interface/star2.png';
	//console.log(floatrating);
	var obj=document.getElementById('star_'+i);
	if(obj)	obj.src='../../images/interface/star1.png';
	}
});
}

function return_product_vote(){
	xmlDoc = xmlHttptest.responseXML;
	var html='';
	if (xmlHttptest.readyState==4 || xmlHttptest.readyState=="complete"){
		if((xmlDoc.getElementsByTagName("htmls").length == 1) && (xmlDoc.getElementsByTagName("html").length > 0)){
			var obj=document.getElementById('stars');
			for (i = 0; i < xmlDoc.getElementsByTagName("html").length; i++){
				html += xmlDoc.getElementsByTagName("html")[i].childNodes[0].nodeValue;
			}
			if(obj)
			obj.innerHTML=html;
		}
		hajbusy = 0;
	}
}
$(function () {
$("#savecomment").click(function(){
var name = $.trim($('#anames').val());
var comment = $.trim($('#authorcomments').val());
var star = $.trim($('#selectedstars').val());

if (name === '') {
	$('#anames').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top
    }, 1000);
	$('span.msg').text('Моля въведете Вашите имена !');
	return false;
    }else $('#anames').css('background','#fff');

	if (comment === '' ||  comment.length > 500 ||  comment.length < 10) {
	$('#authorcomments').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top
    }, 1000);
	if(comment.length < 10){
	$('span.msg').text('Моля въведете вашият коментар ! Минимална дължина 10 символа !');
	}else $('span.msg').text('Максимална дължина на коментара е 500 символа !');
	return false;
    }else $('#authorcomments').css('background','#fff');
	
	if (star === '' || parseInt(star) < 1 ) {
	$('span.msg').text('Моля оценете колко звезди давате на този бизнес!');
	return false;
	}else $('span.msg').text('');
	
	//document.getElementById("subcom").submit();
});
$(".count_reviews").click(function(){
$('html, body').animate({
	scrollTop: $("span.msg").offset().top
    }, 1000);

});
});