<?php

class acf_field_dynamic_table_field extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/




	function __construct() {

		// vars
		$this->name = 'dynamic_table';
		$this->label = __("Dynamic Table",'acf-dynamic-table');
		$this->category = 'content';
		$this->defaults = array(
			'tableclass'	=> '',
			'maxrows'		=> '',
			'fixed_columns'	=> 0,
			'disable_sort'	=> 0,
			'default_header'=> '',
			'readonly'		=> 0,
			'disabled'		=> 0,
		);

	// settings
		$this->settings = array(
			'path' => plugin_dir_path( __FILE__ ),
			'dir' => plugin_dir_url( __FILE__ ),
			'version' => '1.0.4'
		);

		// do not delete!
    	parent::__construct();
	}



	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used

		// register edittable
		wp_register_script( 'acf-edittable', $this->settings['dir'] . 'js/jquery.edittable.js', array('acf-input'), $this->settings['version'] );
		//wp_register_style( 'acf-edittablecss', $this->settings['dir'] . 'css/jquery.edittable.css', array('acf-input'), $this->settings['version'] );

		// register ACF scripts
		wp_register_script( 'acf-dynamic-table', $this->settings['dir'] . 'js/dynamic-table.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-dynamic-tablecss', $this->settings['dir'] . 'css/dynamic-table.css', array('acf-input'), $this->settings['version'] );

		// scripts
		wp_enqueue_script(array(
			'acf-edittable',
			'acf-dynamic-table',
		));

		// styles
		wp_enqueue_style(array(
			//'acf-edittablecss',
			'acf-dynamic-tablecss'
		));


	}


	function field_group_admin_enqueue_scripts() {
		wp_register_script( 'acf-field-dynamic-table-group', $this->settings['dir'] . 'js/dynamic-table-group.js', array( 'acf-field-group' ), $this->settings['version'] );


		// scripts
		wp_enqueue_script(
			array(
				'acf-field-dynamic-table-group',
			)
		);

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field( $field ) {

		$maxrows = null;

		$disablesort = null;

		$defaultheaders = null;


		// vars
		$o = array( 'id', 'name' );
		$s = array( 'readonly', 'disabled' );
		$e = '';


		// maxlength
		if( $field['maxrows'] !== '' ) {

			$maxrows = 'data-maxrows="'.$field['maxrows'].'"';

		}

						// disable sort
		if( !empty($field['disable_sort']) ) $disablesort = ' data-disablesort="'.$field['disable_sort'].'"';

		// rows
		if( !empty($field['fixed_columns']) ) {

			$defaultheaders = preg_split( '/\r\n|\r|\n/',  $field['default_headers'] );

			$defaultheaders = 'data-defaultheaders="'.implode(",", $defaultheaders) .'"';
			if( empty($field['default_headers']) ) $defaultheaders = 'data-defaultheaders="No Default Headers Set"';

		}




		// populate atts
		$atts = array();
		foreach( $o as $k ) {

			$atts[ $k ] = $field[ $k ];

		}



		// special atts
		foreach( $s as $k ) {

			if( $field[ $k ] ) {

				$atts[ $k ] = $k;

			}

		}



		$e .= '<textarea style="display:none" '.$maxrows.' '.$disablesort.' '.$defaultheaders.' data-key="'.$field['key'].'" ' . acf_esc_attr( $atts ) . ' >';
		$e .= esc_textarea( $field['value'] );
		$e .= '</textarea>';


		// return
		echo $e;

	}



	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @param	$field	- an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field_settings( $field ) {

		// Table class
		acf_render_field_setting( $field, array(
			'label'			=> __('Table Class','acf-dynamic-table'),
			'instructions'	=> __('Custom Table Class for the Front End','acf-dynamic-table'),
			'type'			=> 'text',
			'name'			=> 'tableclass',
		));


		// max rows
		acf_render_field_setting( $field, array(
			'label'			=> __('Max Rows','acf-dynamic-table'),
			'instructions'	=> __('Sets the maximum rows for the table','acf-dynamic-table'),
			'type'			=> 'number',
			'name'			=> 'maxrows'
		));
						// disable sort
		acf_render_field_setting( $field, array(
			'label'			=> __('Disable Sort','acf-dynamic-table'),
			'instructions'	=> __('Disable Sort - this will disable drag and drop sorting of rows','acf-dynamic-table'),
			'type'		=>	'radio',
			'name'		=>	'disable_sort',
			'layout'  		=> 'horizontal',
			'choices'		=> array(
				1				=> __("Yes",'acf-dynamic-table'),
				0				=> __("No",'acf-dynamic-table'),
			),
		));

				// fixed columns
		acf_render_field_setting( $field, array(
			'label'			=> __('Fixed Columns','acf-dynamic-table'),
			'instructions'	=> __('Fixed columns - column count and set names','acf-dynamic-table'),
			'type'			=> 'radio',
			'name'			=> 'fixed_columns',
			'layout'  		=> 'horizontal',
			'choices'		=> array(
				1				=> __("Yes",'acf-dynamic-table'),
				0				=> __("No",'acf-dynamic-table'),
			),
		));

				// default_headers
		acf_render_field_setting( $field, array(
			'label'			=> __('Default Headers','acf-dynamic-table'),
			'instructions'	=> __('Set default headers, one per line.','acf-dynamic-table'),
			'type'			=> 'textarea',
			'name'			=> 'default_headers',
		));


	}


	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	function format_value( $value, $post_id, $field ) {

		// bail early if no value or not for template
		if( $value=='[[""]]' || !is_string($value) ) {

			return '';

		}


		$tabledata =  json_decode($value);

			if ( !$field['fixed_columns'] ) {
				$theheaders = array_shift($tabledata);
			}else{
				$theheaders = preg_split( '/\r\n|\r|\n/',  $field['default_headers'] );
			};

			$html = '<table class="acf-dynamic-table ' . $field['tableclass'] . '">';
			  $html .= '<thead>';
			    $html .= '<tr>';
			      $html .= '<th>'.implode('</th><th>', $theheaders ).'</th>';
			    $html .= '</tr>';
			  $html .= '</thead>';
			 $html .=  '<tbody class="list">';
			 foreach ($tabledata as $row): array_map('htmlentities', $row);
			    $html .= '<tr>';
			    $html .= implode('', array_map(function ($v, $k)  use ($theheaders) { return '<td data-title="'.$theheaders[$k].'"  class="'.$theheaders[$k].'">'. $v.'</td>'; }, $row, array_keys($row)));
			     // $html .= '<td  data-title="'.$theheaders[$key].'">'.implode('</td><td>', $row).'</td>';
			   $html .=  '</tr>';
			 endforeach;
			  $html .= '</tbody>';
			$html .= '</table>';
		// return
		return $html;


	}




	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/

	function load_field( $field ) {

		$field['sub_fields'] = acf_get_fields( $field );


		// return
		return $field;
	}




}


// create field
new acf_field_dynamic_table_field();

?>
