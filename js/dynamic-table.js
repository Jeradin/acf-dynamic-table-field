(function($){


	function initialize_field( $el ) {

		//$el.doStuff();

	}

					function reNumber(){
							$('.dynaimic-table tbody').children().each(function(i){

								$(this).children('td.order').html( i+1 );

							});

				}

					function OnSorting($item, $placeholder){
					// if $item is a tr, apply some css to the elements
						if( $item.is('tr') )
						{
							// temp set as relative to find widths
							$item.css('position', 'relative');

							// set widths for td children
							$item.children().each(function(){

								$(this).width($(this).width());

							});

							// revert position css
							$item.css('position', 'absolute');

							// add markup to the placeholder
							$placeholder.html('<td style="height:' + $item.height() + 'px; padding:0;" colspan="' + $item.children('td').length + '"></td>');
						}
					};

					function OffSorting($item, $placeholder){
					// if $item is a tr, apply some css to the elements
						if( $item.is('tr') )
						{


							// set widths for td children
							$item.children().each(function(){

								$(this).css('width', 'auto');

							});

						}
					};



	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		acf.add_action('ready append', function( $el ){




			// search $el for fields of type 'table_field'
			acf.get_fields({ type : 'dynamic_table'}, $el).each(function(e){

							var $input	= $(this),
								$field	= acf.get_field_wrap( $input ),
								//$parent = $field.parent(),
								key		= acf.get_field_key( $field ),
								$textarea = $field.find('textarea');


					firstrow = true;
					var defaultheaders = $textarea.data( "defaultheaders" );
					if( defaultheaders ){
						defaultheaders = $textarea.data( "defaultheaders" ).split(',');
						firstrow = false;
						}

					if(!defaultheaders)defaultheaders=false;
					var maxrows = $textarea.data( "maxrows" );
					if(!maxrows)maxrows=999;

					disablesort = true;

					var disablesort = $textarea.data( "disablesort" );
					if(disablesort) disablesort=false;


				var theTable = $textarea.editTable({
					    //data: [[]],           // Fill the table with a js array (this is overridden by the textarea content if not empty)
					    tableClass: 'acf-table acf-input-table table-layout dynaimic-table '+ key,   // Table class, for styling
						//jsonData: false,        // Fill the table with json data (this will override data property)
					    headerCols: defaultheaders,      // Fix columns number and names (array of column names)
					    maxRows: maxrows,           // Max number of rows which can be added
					    first_row: firstrow,        // First row should be highlighted?
					    sortable: disablesort,           // Allow rows to be sorted
					    //row_template: false,    // An array of column types set in field_templates
					    //field_templates: false, // An array of custom field type objects

					    // Validate fields
					    /*validate_field: function (col_id, value, col_type, $element) {
					        return true;
					    }*/
					});


				//console.log(editTable.s));



				reNumber($('tbody',this));

				function reNumber(){
							//console.log(elem);
							$('.dynaimic-table tbody').children().each(function(i){
								$(this).children('td.order').html( i+1 );
							});
				}

				        // Add row
		        $('table.'+key).on('click', '.addrow,.delrow', function () {

		            reNumber();

		        });



				$('.dynaimic-table').one('mouseenter', 'td.order', function( e ){

					$('.dynaimic-table tbody').unbind('sortable').sortable({

						items					: '> tr',
						handle					: '> td.order',
						forceHelperSize			: true,
						forcePlaceholderSize	: true,
						scroll					: true,

						start : function (event, ui) {

							OnSorting(ui.item, ui.placeholder);

			   			},

			   			stop : function (event, ui) {

							OffSorting(ui.item, ui.placeholder);


							// render
							reNumber($(this));

			   			}

					});

				});

				initialize_field( $(this) );


			});


		});



	} else {


		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).live('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="dynamic_table"]').each(function(){


							var $input	= $(this),
								//$field	= acf.get_field_wrap( $input ),
								key = $(this).data( "key" ),
								$textarea = $(this).find('textarea');


					firstrow = true;
					var defaultheaders = $textarea.data( "defaultheaders" );
					if( defaultheaders ){
						defaultheaders = $textarea.data( "defaultheaders" ).split(',');
						firstrow = false;
						}

					if(!defaultheaders)defaultheaders=false;
					var maxrows = $textarea.data( "maxrows" );
					if(!maxrows)maxrows=999;

					disablesort = true;

					var disablesort = $textarea.data( "disablesort" );
					if(disablesort) disablesort=false;


				var theTable = $textarea.editTable({
					    //data: [[]],           // Fill the table with a js array (this is overridden by the textarea content if not empty)
					    tableClass: 'widefat acf-input-table v4 dynaimic-table '+ key,   // Table class, for styling
						//jsonData: false,        // Fill the table with json data (this will override data property)
					    headerCols: defaultheaders,      // Fix columns number and names (array of column names)
					    maxRows: maxrows,           // Max number of rows which can be added
					    sortable: disablesort,           // Allow rows to be sorted
					    first_row: firstrow,        // First row should be highlighted?
					    //row_template: false,    // An array of column types set in field_templates
					    //field_templates: false, // An array of custom field type objects

					    // Validate fields
					    /*validate_field: function (col_id, value, col_type, $element) {
					        return true;
					    }*/
					});


				reNumber($('tbody',this));



				 // Add row
		        $('table.'+key).on('click', '.addrow,.delrow', function () {

		            reNumber();

		        });



				$('.dynaimic-table').one('mouseenter', 'td.order', function( e ){

					$('.dynaimic-table tbody').unbind('sortable').sortable({

						items					: '> tr',
						handle					: '> td.order',
						forceHelperSize			: true,
						forcePlaceholderSize	: true,
						scroll					: true,

						start : function (event, ui) {

								OnSorting(ui.item, ui.placeholder);

			   			},

			   			stop : function (event, ui) {

							OffSorting(ui.item, ui.placeholder);

							// render
							reNumber($(this));

			   			}

					});

				});



				initialize_field( $(this) );

			});

		});


	}


})(jQuery);
