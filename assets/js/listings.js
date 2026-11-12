//votes
var xmlHttptest=GetXmlHttpObject();
var hajbusy=0;
function GetXmlHttpObject(){var xml=null;try{xml=new XMLHttpRequest();}catch(e){try{xml=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){xml=new ActiveXObject("Microsoft.XMLHTTP");}}if(xml==null) alert('browser does not support ajax...');return xml;}

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

(function($, window, document, undefined) {

    'use strict';

    var pluginName = 'showmore',
        defaults = {
            closedHeight: 100,
            buttonTextMore: 'show more',
            buttonTextLess: 'show less',
            buttonCssClass: 'showmore-button',
            animationSpeed: 0.5,
            openHeightOffset: 0,
            onlyWithWindowMaxWidth: 0
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.btn;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function() {
            if (this.settings.onlyWithWindowMaxWidth > 0) {
                this.bindResize();
                this.responsive();                
            } else {
                this.showmore();
            }
        },
        bindResize: function() {
            var self = this;
            var resizeTimer;
            $(window).on('resize', function() {
                if (resizeTimer) {
                    clearTimeout(resizeTimer);
                }
                resizeTimer = setTimeout(function() {
                    self.responsive();
                }, 250);
            });
        },
        responsive: function() {
            if ($(window).innerWidth() <= this.settings.onlyWithWindowMaxWidth) {
                this.showmore();
            } else {
                this.remove();
            }
        },
        showmore: function() {

            if (this.btn) {
                return;
            }

            var self = this;
            var element = $(this.element);
            var settings = this.settings;

            if (settings.animationSpeed > 10) {
                settings.animationSpeed = settings.animationSpeed / 1000;
            }

            var showMoreInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner more',
                text: settings.buttonTextMore
            });
            var showLessInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner less',
                text: settings.buttonTextLess
            });

            element.addClass('closed').css({
                'height': settings.closedHeight,
                'overflow': 'hidden'
            });

            var resizeTimer;
            $(window).on('resize', function() {
                if (!element.hasClass('closed')) {
                    if (resizeTimer) {
                        clearTimeout(resizeTimer);
                    }
                    resizeTimer = setTimeout(function() {
                        // resizing has "stopped"
                        self.setOpenHeight(true);
                    }, 150); // this must be less than bindResize timeout!
                }
            });

            var showMoreButton = $('<div />', {
                'class': settings.buttonCssClass,
                html: showMoreInner
            });

            showMoreButton.on('click', function(event) {
                event.preventDefault();
                if (element.hasClass('closed')) {
                    self.setOpenHeight();
                    element.removeClass('closed');
                    showMoreButton.html(showLessInner);
                } else {
                    element.css({
                        'height': settings.closedHeight,
                        'transition': 'all ' + settings.animationSpeed + 's ease'
                    }).addClass('closed');
                    showMoreButton.html(showMoreInner);
                }
            });
            element.after(showMoreButton);
            this.btn = showMoreButton;
        },

        setOpenHeight: function(noAnimation) {
            $(this.element).css({
                'height': this.getOpenHeight()
            });
            if (noAnimation) {
                $(this.element).css({
                    'transition': 'none'
                });    
            } else {
                $(this.element).css({
                    'transition': 'all ' + this.settings.animationSpeed + 's ease'
                });    
            }
        },

        getOpenHeight: function() {
            $(this.element).css({'height': 'auto', 'transition': 'none'});
            var targetHeight = $(this.element).innerHeight();
            $(this.element).css({'height': this.settings.closedHeight});
            // we must call innerHeight() otherwhise there will be no css animation
            $(this.element).innerHeight();
            return targetHeight;
        },

        remove: function() {
            // var element = $(this.element);
            if ($(this.element).hasClass('closed')) {
                this.setOpenHeight();
            }
            if (this.btn) {
                this.btn.off('click').empty().remove();
                this.btn = undefined;
            }
        }
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

$(function () {
$("#savecomment").click(function(){
var name = $.trim($('#anames').val());
var comment = $.trim($('#authorcomments').val());
var star = $.trim($('#selectedstars').val());
 var aid = $("#savecomment").attr('data-id');

var errors = 0;
if (name === '') {
	$('#anames').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top - 150 
    }, 1000);
	errors++;
	$('span.msg').text('Моля въведете Вашите имена !');
	return false;
    }else $('#anames').css('background','#fff');

	if (comment === '' ||  comment.length > 500 ||  comment.length < 10) {
	$('#authorcomments').css('background','#fff6f6');
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top - 150 
    }, 1000);
	if(comment.length < 10){
	$('span.msg').text('Моля въведете вашият коментар ! Минимална дължина 10 символа !');
	}else $('span.msg').text('Максимална дължина на коментара е 500 символа !');
	errors++;
	return false;
    }else $('#authorcomments').css('background','#fff');
	
	if (star === '' || parseInt(star) < 1 ) {
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top - 150 
    }, 1000);
	$('span.msg').text('Моля оценете колко звезди давате на този бизнес!');
	errors++;
	return false;
	}else $('span.msg').text('');
	
	if(errors < 1){
	
	$.ajax({
	type: 'post',
    url: "/ajax/votes.php",
    async: true,
	dataType: "json",
    data: { "name": name,"comment": comment,"star":star, "aid":aid}, 
	success: function(data){
	if(data[1] > 0) $('.msg').html(data[0]);
	$('html, body').animate({
	scrollTop: $("span.msg").offset().top - 150 
    }, 1000);
	}
		});
		return false;
	}
});
$(".count_reviews").click(function(){
$('html, body').animate({
	scrollTop: $("span.msg").offset().top
    }, 1000);

});
});
//votes
function creategooglemap(){
jQuery(function($) {
	 var script2 = document.createElement('script');
    script2.src = "https://www.espravki.com/assets/js/markerwithlabel.js";
    //document.getElementsByTagName('head')[0].appendChild(script2);
	
});
}
function move(id){
if($("#a" + id).length){
$('html, body').animate({scrollTop: $("#a" + id).offset().top}, 1500);
	}
}

//map
;(function($, window, document, undefined) {

	var AxGmap = function (element){
		this.element = $(element);
		this.gmap;
		this.markers = [];
		this.infoWindows = [];
		this.init();
	}

	AxGmap.prototype = {
		init: function (){
			this.createGmap();
			this.fin();
		},
		createGmap: function(){
			var options = this.createGmapOption();
			var children = this.element.children();
			this.gmap = new google.maps.Map(this.element[0], options);
			this.createMarker(children);
			this.showMapStatus();
		},
		createMarker: function(elements){
			var self = this;
			var openWindowSet = null;
			elements.each(function(index){
				var element = $(this);
				var windowOpen = false;
				var options = self.createMarkerOption(element);
				if (options.windowOpen) {
					windowOpen = true;
					delete options.windowOpen;
				}
				var marker = new google.maps.Marker(options);
				self.markers.push(marker);
				var content = element.html().trim();
				if (content.length) {
					var infoWindowSet = self.createInfoWindowSet(content, marker);
					self.infoWindows.push(infoWindowSet);
					if (windowOpen) {
						openWindowSet = infoWindowSet;
					};
				}
			});
			if (openWindowSet) {
				self.openInfoWindow(openWindowSet);
			};
		},
		createInfoWindowSet: function(content, marker){
			var self = this;
			var infoWindow = new google.maps.InfoWindow({content: content});
			google.maps.event.addListener(marker, 'click', function(){
				self.openInfoWindow({'infoWindow':infoWindow, 'marker':marker});
			});
			return {'infoWindow':infoWindow, 'marker':marker};
		},
		createLatLng: function(element){
			var lat = 0;
			var lng = 0;
			var latlng = element.data('latlng') ? element.data('latlng') : this.element.data('latlng');
			if (latlng) {
				var split = latlng.split(',');
				lat = this.parseNum(split[0].trim());
				lng = this.parseNum(split[1].trim());
			};
			//var pos = new google.maps.LatLng(lat, lng);

			return new google.maps.LatLng(lat, lng);
			//return pos;
		},
		createGmapOption: function(){
			var self = this;
			var options = {
				center: this.createLatLng(this.element),
				position: new google.maps.LatLng(this.element),
				zoom: 9
			};
			if (this.element.data("mapType")) {
				var mapType = this.element.data("mapType").toUpperCase();
				$.extend(options, {'mapTypeId': google.maps.MapTypeId[mapType]});
			};
			if (this.element.data("mapWidth") != null) {
				this.element.width(this.element.data("mapWidth"));
			};
			if (this.element.data("mapHeight") != null) {
				this.element.height(this.element.data("mapHeight"));
			};
			var properties = ["zoom", "draggable", "scrollwheel", "maxZoom", "minZoom", "mapTypeControl", "overviewMapControl", "panControl", "rotateControl", "scaleControl", "streetViewControl", "zoomControl"];
			$.each(properties, function(index, property){
				if (self.element.data(property) != null) {
					options[property] = self.element.data(property);
				}
			});
			return options;
		},
		createMarkerOption: function(element){
			var options = {
				map: this.gmap,
				labelClass: "labelsss",
				position: this.createLatLng(element)
			};
			if (element.data('title') != null) {
				options['title'] = element.data('title');
			}
			if (element.data('label') != null) {
			options['label'] = {
			  text: element.data('label'),
			  color: "red",
			  fontSize: "15px",
			  width: "100px"
			}
			}
			if (element.data('markerImage') != null) {
				options['icon'] = element.data('markerImage');
			}
			if (element.data('windowOpen')) {
				options['windowOpen'] = true;
			}
			return options;
		},
		openInfoWindow: function(infoWindowSet){
			var self = this;
			infoWindowSet.infoWindow.open(self.gmap, infoWindowSet.marker);
			$.each(self.infoWindows, function(index, val){
				if(val.infoWindow != infoWindowSet.infoWindow){
					val.infoWindow.close();
				}
			});
		},
		parseNum: function(val){
			return (typeof val == null) ? 0.0 : parseFloat(val);
		},
		showMapStatus: function(){
			var self = this;
			if (!this.element.data("mapStatus")) {
				return;
			};
			var status = $('<div style="color:#000; background-color:#000; border:solid 1px #ccc; width:' + self.element.width() + 'px"><dl style="margin:1em;"><dt>Center LatLng</dt><dd class="axgmap-status-latlng"></dd><dt>Zoom</dt><dd class="axgmap-status-zoom"></dd><dt>Right Click LatLng</dt><dd class="axgmap-status-rightclick">none</dd></dl></div>');
			status.insertAfter(this.element);
			google.maps.event.addListener(this.gmap, 'idle', function(){
				$('.axgmap-status-latlng', status).empty().append(self.gmap.getCenter().lat().toFixed(6) + ', ' + self.gmap.getCenter().lng().toFixed(6));
				//$('.axgmap-status-zoom', status).empty().append(self.gmap.getZoom());
			});
			google.maps.event.addListener(this.gmap, 'rightclick', function(event){
				$('.axgmap-status-rightclick', status).empty().append(event.latLng.lat().toFixed(6) + ', ' + event.latLng.lng().toFixed(6));
			});
		},
		fin: function(){
			if (!AxGmap.didCreated) {
				AxGmap.didCreated = true;
				$('<style>.gm-style img{max-width:inherit;}</style>').appendTo('head');
			};
		}
	};

	$.fn.axgmap = function(){
		return this.each(function(){
			if(!$.data(this, 'AxGmap')){
				$.data(this, 'AxGmap', new AxGmap(this));
			}
		});
	};

	$(function() {
		//if($('.axgmap').length) $('.axgmap').axgmap();
	});

})(jQuery, window, document);


$('document').ready(function(){

if($('.smcontainer').length){
if($('.smcontainer').height() > 300){
	$('.smcontainer').showmore({
		closedHeight: 250,
		buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
		buttonCssClass: 'showmore-button',
		animationSpeed: 0.5
	});
		}
	}

$('.cities').on('change', function(e) {
var v = $(this).val();
$('#search_city').val(v);
$('#search_ar').click();
})

$('.cats').on('change', function(e) {
var v = $(this).val();
$('#category').val(v).trigger("change");
$('#search_ar').click();
})
	
	$("body").on("click", '#filter_city', function() {
	var city = $('.custom-control-input:checked').val();
	
	if(!isEmpty(city)){
	$('#search_city').val(city);
	$('#search_advert_form').submit();
	}
	})
	$("body").on("click", '#filter_city2', function() {
	var catsradio = $('.catsradio:checked').val();
	
	if(!isEmpty(catsradio)){
	$('#search_advert_form').submit();
	}
	})

$("body").on("change", '.catsradio', function() {
$('#category').val($(this).val()).select2();

})


$('.city_ch').on('change', function(e) {
$('#search_city').val($(this).val());
$('#city_match').val($(this).val());
$('#search_advert_form').submit();
})

if($('.adverts_tab').length){
var running = false;


$(window).bind('scroll', function() {
if(!running){

if($(window).scrollTop() >= $('.adverts_tab').offset().top + $('.adverts_tab').outerHeight() - window.innerHeight) {
 
running = true;
var value = $('.snglad').length;
var t = 'adverts';
var viewtype = 'tab-11';
var srctxt = $.trim($('#srctxt').val());
var cat = $.trim($('#category').val());
var city = $.trim($('#search_city').val());
var sort = $('#sort_ch2').val();

var for_sale = 0;
if($('#for_sale').length) for_sale = 1;


var ex_city = '';
if($('#ex_city').length) ex_city = $.trim($('#ex_city').val());
/*$('.tab-pane').each(function(){
if ($(this).hasClass("active")) {
viewtype = $(this).attr( "id" );
}
})*/

//var value = parseInt($('#'+viewtype).find('div.snglad:last').attr('data-id'));
 
 if(value > 11){
  //console.log('end ' + value+" "+ viewtype);
  $.ajax({
	type: 'post',
    url: "/ajax/get_more.php",
    async: true,
	dataType: "json",
    data: { "t": t,"v": value, "viewtype":viewtype,"cat":cat,"search":srctxt,"search_city":city,"sort":sort, "ex_city":ex_city, "for_sale":for_sale}, 
	success: function(data){
	$('#global-loader').hide();
	//$('.adverts_tab').html(data[0]);
	//$('.adverts_tab').find('div.snglad:last')
	$( data[0] ).insertAfter( $('#tab-11').find('div.snglad:last') );
	//$( data[2] ).insertAfter( $('#tab-11').find('div.snglad:last') );
	$('#cr').html($('.snglad').length);
	if(data[1] > 0){
	running = false;
	}else{
//	running = false;
$('#cr').html($('.snglad').length);
//$( data[0] ).insertAfter( $('#'+viewtype).find('div.snglad:last') );
}
			}
		});
		return false;
 }
			}
			}
		});
}


$('body').on('click','#prddiv',function(){
setTimeout(function(){$('html, body').animate({scrollTop: $('#prs_div').offset().top - 150}, 800)}, 300);
 })

$('body').on('click','#docsh',function(){
setTimeout(function(){$('html, body').animate({scrollTop: $('#docs').offset().top - 150}, 800)}, 300);
 })
 
$('#contact_the_trader').on('click', function(e){
	$('.msg').html('');
	var names = $.trim($('#names').val());
	var email = $.trim($('#email').val());
	var phone = $.trim($('#phone').val());
	var message = $.trim($('#message').val());
	var aid = $.trim($('#aid').val());
	var pname = $.trim($('#pname').val());
	var errors = 0;

	
	if(isEmpty(names) || names.length < 3){
	$('#names').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#names').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#names').css('background','#fff');
	
	if(isEmpty(aid) || aid < 1){
	errors++;
	return false;
	}
	
	
	if(!validateEmail(email)){
	  $('#email').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#names').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#email').css('background','#fff');
	
	if(isEmpty(phone) || phone.length < 8){
	$('#phone').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#names').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#phone').css('background','#fff');
	
	
	if(isEmpty(message) || message.length < 10){
	$('#message').css('background','#ffecec');
	  $('html, body').animate({scrollTop: $('#names').offset().top - 150}, 800);
	  errors++;
	  return false;
	}else $('#message').css('background','#fff');
	
	 
	
	if(errors < 1){
	$('#global-loader').show();

	$.ajax({
	type: 'post',
    url: "/ajax/contact_trader.php",
	dataType: "json",
    async: true,
    data: { "names": names, "email": email, "phone":phone, "aid":aid,"message":message}, 
	success: function(data){
	$('.msgaaa').html(data[0]);
	$('html, body').animate({scrollTop: $('.msgaaa').offset().top - 150}, 800);
	$('#contact_the_trader').remove();
	$('#global-loader').hide();

	//if(data[1].length > 10) $('#img_preview').html(data[1]);
	
			}
		});
		return false;
	}
	})

	;
	(function($, window, document, undefined) {

    'use strict';

    var pluginName = 'showmore',
        defaults = {
            closedHeight: 100,
            buttonTextMore: 'show more',
            buttonTextLess: 'show less',
            buttonCssClass: 'showmore-button',
            animationSpeed: 0.5,
            openHeightOffset: 0,
            onlyWithWindowMaxWidth: 0
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.btn;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function() {
            if (this.settings.onlyWithWindowMaxWidth > 0) {
                this.bindResize();
                this.responsive();                
            } else {
                this.showmore();
            }
        },
        bindResize: function() {
            var self = this;
            var resizeTimer;
            $(window).on('resize', function() {
                if (resizeTimer) {
                    clearTimeout(resizeTimer);
                }
                resizeTimer = setTimeout(function() {
                    self.responsive();
                }, 250);
            });
        },
        responsive: function() {
            if ($(window).innerWidth() <= this.settings.onlyWithWindowMaxWidth) {
                this.showmore();
            } else {
                this.remove();
            }
        },
        showmore: function() {

            if (this.btn) {
                return;
            }

            var self = this;
            var element = $(this.element);
            var settings = this.settings;

            if (settings.animationSpeed > 10) {
                settings.animationSpeed = settings.animationSpeed / 1000;
            }

            var showMoreInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner more',
                text: settings.buttonTextMore
            });
            var showLessInner = $('<div />', {
                'class': settings.buttonCssClass + '-inner less',
                text: settings.buttonTextLess
            });

            element.addClass('closed').css({
                'height': settings.closedHeight,
                'overflow': 'hidden'
            });

            var resizeTimer;
            $(window).on('resize', function() {
                if (!element.hasClass('closed')) {
                    if (resizeTimer) {
                        clearTimeout(resizeTimer);
                    }
                    resizeTimer = setTimeout(function() {
                        // resizing has "stopped"
                        self.setOpenHeight(true);
                    }, 150); // this must be less than bindResize timeout!
                }
            });

            var showMoreButton = $('<div />', {
                'class': settings.buttonCssClass,
                html: showMoreInner
            });

            showMoreButton.on('click', function(event) {
                event.preventDefault();
                if (element.hasClass('closed')) {
                    self.setOpenHeight();
                    element.removeClass('closed');
                    showMoreButton.html(showLessInner);
                } else {
                    element.css({
                        'height': settings.closedHeight,
                        'transition': 'all ' + settings.animationSpeed + 's ease'
                    }).addClass('closed');
                    showMoreButton.html(showMoreInner);
                }
            });
            element.after(showMoreButton);
            this.btn = showMoreButton;
        },

        setOpenHeight: function(noAnimation) {
            $(this.element).css({
                'height': this.getOpenHeight()
            });
            if (noAnimation) {
                $(this.element).css({
                    'transition': 'none'
                });    
            } else {
                $(this.element).css({
                    'transition': 'all ' + this.settings.animationSpeed + 's ease'
                });    
            }
        },

        getOpenHeight: function() {
            $(this.element).css({'height': 'auto', 'transition': 'none'});
            var targetHeight = $(this.element).innerHeight();
            $(this.element).css({'height': this.settings.closedHeight});
            // we must call innerHeight() otherwhise there will be no css animation
            $(this.element).innerHeight();
            return targetHeight;
        },

        remove: function() {
            // var element = $(this.element);
            if ($(this.element).hasClass('closed')) {
                this.setOpenHeight();
            }
            if (this.btn) {
                this.btn.off('click').empty().remove();
                this.btn = undefined;
            }
        }
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
(function($) {
    "use strict";
	//alert($('#container').height());
	if($('#container').height() > 250){
	$('#container').showmore({
		closedHeight: 250,
		buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
		buttonCssClass: 'showmore-button',
		animationSpeed: 0.5
	});
	}
	/*
	if (document.documentElement.clientWidth < 900) {
		$('#container').showmore({
			closedHeight: 450,
			buttonTextMore: messages.show_more,
		buttonTextLess: messages.close,
			buttonCssClass: 'showmore-button',
			animationSpeed: 0.5
		});
	}*/
	
})(jQuery);



if(isMobile.any()){
if($('.accordion-toggle').length){
$('.accordion-toggle').click();
}
}

/*$('.chev').on('click', function(e){
$('.collapse').on('shown.bs.collapse', function () {
  if($('.show').length){
$('.filter_city_f').show();
	}else $('.filter_city_f').hide();
})

$('.collapse').on('hidden.bs.collapse', function () {
  if($('.show').length){
$('.filter_city_f').show();
	}else $('.filter_city_f').hide();
})
})*/
if($('#googlemap').length) initialize();
})