jQuery(document).ready(function($) {




	if( typeof acf.add_action !== 'undefined' ) {

			function acf_dynamic_table_defaults_field( $el ){


			$el = $el.closest('.field');
				// vars
				var fixed = $el.find('[data-name="toggle-fixed_columns"]:checked').val();
				//console.log(fixed);
				if( fixed == '1' ) {

					$el.find('.acf-field[data-name="default_headers"]').show();

				} else {

					$el.find('.acf-field[data-name="default_headers"]').hide();

				}

			}


		acf.add_action('open_field change_field_type', function( $el ){

		// bail early if not select
		if( $el.attr('data-type') != 'dynamic_table' ) {

			return;

		}


		// add class to input
		$el.find('.acf-field[data-name="fixed_columns"] input[type="radio"]').attr('data-name', 'toggle-fixed_columns');


		// render
		acf_dynamic_table_defaults_field( $el );

	});

	$(document).on('change', '[data-name="toggle-fixed_columns"]', function(){

			acf_dynamic_table_defaults_field(  $(this).closest('.field') );

	});


	}else{
		//Version 4



		$(document).live('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="table_field"]').each(function(){

				initialize_field( $(this) );

			});

		});



	}




})


