$(document).ready(function() {
	$('.autosuggest2').keyup(function() {
		var search_term = $(this).attr('value');
		$.post('ajax/search2.php', {search_term:search_term}, function(data) {
			$('.result2').html(data);
			$('.result2 li').click(function() {
			var result_value = $(this).text();
			$('.autosuggest2').attr('value', result_value);
			$('.result2').html('');
				});
			});
		});
	});