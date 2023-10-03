jQuery(function($)
{
	$('input').on('change', function() {
		modis_get_posts();
	});

	$('.orderby').on('change', function(e) {
		e.preventDefault();
		modis_get_posts();
	});

	$(document).on("click", ".page-numbers", function( e ) {
		e.preventDefault();

		var url = $(this).attr('href');
		var paged = url.split('&paged=');

		if(~url.indexOf('&paged=')) {
			paged = url.split('&paged=');
		} else {
			paged = url.split('/page/');
		}
		modis_get_posts(paged[1]);
	});
	function getCats() {
		var cats = [];

		$( ".modis_filter_check input:checked" ).each( function() {
			var val = $( this ).val();
			cats.push( val );
		} );
		return cats;
	}
	function getPricesMin() {
		return $('#priceMin').val();
	}
	function getPriceMax() {
		return $('#priceMax').val();
	}
	function modis_order() {
		return $('.orderby option:selected').val();
	}

	function modis_get_posts(paged)
	{
		var paged_value = paged; //Store the paged value if it's being sent through when the function is called
		var ajax_url = woocommerce_params.ajax_url; //Get ajax url (added through wp_localize_script)

		/*console.log(getCats());*/

		$.ajax({
			type: 'GET',
			url: ajax_url,
			data: {
				action: 'modis_filter',
				category: getCats(),
				min: getPricesMin(),
				max: getPriceMax(),
				order: modis_order(),
				paged: paged_value //If paged value is being sent through with function call, store here
			},
			beforeSend: function() {
				$('#main').html('Loading...');
			},
			success: function(data) {
				// Hide loader here
				$('#main').html(data);
			},
			error: function() {
				// If an ajax error has occurred, do something here.
				$('#main').html('<p>There has been an error</p>');
			}
		});
	}
});
