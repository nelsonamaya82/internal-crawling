jQuery(document).ready(function ($) {
	$('#crawl-now-button').on('click', function(){
		$.ajax({
			type: 'post',
			url:  admin.ajaxurl,
			data: 'action=' + admin.ajaxaction + '&nonce=' + admin.ajaxnonce
		})
			.done(function( result ){
				$('.internal-crawling-results').html(result);
			})
			.fail(function() {
				$('.internal-crawling-results').html('We are sorry, there was an error trying to get the crawling results. Please try again.');
			});
	});
});
