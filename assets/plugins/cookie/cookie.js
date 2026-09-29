$(document).ready(function() {
	$('body').ihavecookies({
		title: 'Защита на Личните данни GDPR',
		message: 'Този уебсайт използва бисквитки (Cookies)! Преди да продължите, моля запознайте се с условията за ползване и Защита на личните данни!. Кликнете върху <strong>Приемам</strong> за да приемете GDPR политиката или прочетете пълните ',
		delay: 600,
		expires: 1,
		link: 'https://www.espravki.com/news/terms-and-conditions',
		onAccept: function(){
			var myPreferences = $.fn.ihavecookies.cookie();
			//console.log('Yay! The following preferences were saved...');
			//console.log(myPreferences);
		},
		uncheckBoxes: true,
		acceptBtnLabel: 'Приемам',
		moreInfoLabel: 'Условия за ползване'
	});

	if ($.fn.ihavecookies.preference('marketing') === true) {
		//console.log('This should run because marketing is accepted.');
	}
});