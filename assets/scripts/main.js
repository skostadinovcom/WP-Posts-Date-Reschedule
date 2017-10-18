jQuery(document).ready(function($){
	$('#pdr_function').change(function(){
		var selected = $(this).val();
		$('.pdr_function_selector').hide();

		if ( selected == 1 ) {
			$('.pdr_date_selector').show();
		}

		if ( selected == 2 ) {
			$('.pdr_plus_selector').show();
		}

		if ( selected == 3 ) {
			$('.pdr_minus_selector').show();
		}
	});
});