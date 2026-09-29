var mousex = 0;
var mousey = 0;
var grabx = 0;
var graby = 0;
var orix = 0;
var oriy = 0;
var elex = 0;
var eley = 0;
var algor = 0;

var dragobj = null;

function falsefunc() { return false; } // used to block cascading events

function init()
{
  document.onmousemove = update; // update(event) implied on NS, update(null) implied on IE
  update();
}

function getMouseXY(e) // works on IE6,FF,Moz,Opera7
{ 
  if (!e) e = window.event; // works on IE, but not NS (we rely on NS passing us the event)

  if (e)
  { 
    if (e.pageX || e.pageY)
    { // this doesn't work on IE6!! (works on FF,Moz,Opera7)
      mousex = e.pageX;
      mousey = e.pageY;
      algor = '[e.pageX]';
      if (e.clientX || e.clientY) algor += ' [e.clientX] '
    }
    else if (e.clientX || e.clientY)
    { // works on IE6,FF,Moz,Opera7
      mousex = e.clientX + document.body.scrollLeft;
      mousey = e.clientY + document.body.scrollTop;
      algor = '[e.clientX]';
      if (e.pageX || e.pageY) algor += ' [e.pageX] '
    }  
  }
}

function update(e)
{
  getMouseXY(e); // NS is passing (event), while IE is passing (null)

  var span_browser = document.getElementById('span_browser');
  var span_winevent = document.getElementById('span_winevent');
  var span_autevent = document.getElementById('span_autevent');
  var span_mousex = document.getElementById('span_mousex');
  var span_mousey = document.getElementById('span_mousey');
  var span_grabx = document.getElementById('span_grabx');
  var span_graby = document.getElementById('span_graby');
  var span_orix = document.getElementById('span_orix');
  var span_oriy = document.getElementById('span_oriy');
  var span_elex = document.getElementById('span_elex');
  var span_eley = document.getElementById('span_eley');
  var span_algor = document.getElementById('span_algor');
  var span_dragobj = document.getElementById('span_dragobj');

  if (span_browser) span_browser.innerHTML = navigator.appName;
  if (span_winevent) span_winevent.innerHTML = window.event ? window.event.type : '(na)';
  if (span_autevent) span_autevent.innerHTML = e ? e.type : '(na)';
  if (span_mousex) span_mousex.innerHTML = mousex;
  if (span_mousey) span_mousey.innerHTML = mousey;
  if (span_grabx) span_grabx.innerHTML = grabx;
  if (span_graby) span_graby.innerHTML = graby;
  if (span_orix) span_orix.innerHTML = orix;
  if (span_oriy) span_oriy.innerHTML = oriy;
  if (span_elex) span_elex.innerHTML = elex;
  if (span_eley) span_eley.innerHTML = eley;
  if (span_algor) span_algor.innerHTML = algor;
  if (span_dragobj) span_dragobj.innerHTML = dragobj ? (dragobj.id ? dragobj.id : 'unnamed object') : '(null)';
}

function grab(context)
{
  document.onmousedown = falsefunc; // in NS this prevents cascading of events, thus disabling text selection
  dragobj = context;
  dragobj.style.zIndex = 5; // move it to the top
  document.onmousemove = drag;
  document.onmouseup = drop;
  grabx = mousex;
  graby = mousey;
  elex = orix = dragobj.offsetLeft;
  eley = oriy = dragobj.offsetTop;
  update();
}

function drag(e) // parameter passing is important for NS family 
{
  if (dragobj)
  {
    elex = orix + (mousex-grabx);
    eley = oriy + (mousey-graby);
    dragobj.style.position = "absolute";
    dragobj.style.left = (elex).toString(10) + 'px';
    dragobj.style.top  = (eley).toString(10) + 'px';
	dragobj.style.zIndex=5;
  }
  update(e);
  return false; // in IE this prevents cascading of events, thus text selection is disabled
}

function drop()
{
  if (dragobj)
  {
    dragobj.style.zIndex = 5;
    dragobj = null;
  }
  update();
  document.onmousemove = update;
  document.onmouseup = null;
  document.onmousedown = null;   // re-enables text selection on NS
}