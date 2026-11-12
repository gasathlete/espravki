function gE(o){return document.getElementById(o);}
function priceFormat(e){
var c=0;
if(e.keyCode) c=e.keyCode; else c=e.which;
switch(c){case 48:case 46:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 8:case 0:return true;break;default:return false;break;}
}
function del(q){
	var o=gE('form1').getElementsByTagName('input');var f=false;
	for(var i=0;i<o.length;i++){if(o[i].className='radio' && o[i].checked) f=true;}
	if(f && confirm(q)){gE('btn').value='del';document.form1.submit();}
}