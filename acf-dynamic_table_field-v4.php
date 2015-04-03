<?php

class acf_field_table_field extends acf_field {

	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
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
			'version' => '1.0.0'
		);

		// do not delete!
    	parent::__construct();
	}



	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options( $field )
	{


		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// key is needed in the field names to correctly save the data
		$key = $field['name'];


		// Create Field Options HTML
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Table Class','acf-dynamic-table'); ?></label>
				<p class="description"><?php _e('Custom Table Class for the Front End','acf-dynamic-table'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'		=>	'text',
					'name'		=>	'fields['.$key.'][tableclass]',
					'value'		=>	$field['preview_size'],
				));

				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Max Rows','acf-dynamic-table'); ?></label>
				<p class="description"><?php _e('Sets the maximum rows for the table','acf-dynamic-table'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'		=>	'number',
					'name'		=>	'fields['.$key.'][maxrows]',
					'value'		=>	$field['maxrows'],
				));

				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Disable Sort','acf-dynamic-table'); ?></label>
				<p class="description"><?php _e('Disable Sort - this will disable drag and drop sorting of rows','acf-dynamic-table'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'		=>	'radio',
					'name'		=>	'fields['.$key.'][disable_sort]',
					'layout'  		=> 'horizontal',
					'value' => $field['disable_sort'],
					'choices'		=> array(
						1				=> __("Yes",'acf-dynamic-table'),
						0				=> __("No",'acf-dynamic-table'),
					),
				));

				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Fixed Columns','acf-dynamic-table'); ?></label>
				<p class="description"><?php _e('Fixed columns - column count and set names','acf-dynamic-table'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'		=>	'radio',
					'name'		=>	'fields['.$key.'][fixed_columns]',
					'layout'  		=> 'horizontal',
					'value' => $field['fixed_columns'],
					'choices'		=> array(
						1				=> __("Yes",'acf-dynamic-table'),
						0				=> __("No",'acf-dynamic-table'),
					),
				));

				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Default Headers','acf-dynamic-table'); ?></label>
				<p class="description"><?php _e('Set default headers, one per line.','acf-dynamic-table'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'		=>	'textarea',
					'name'		=>	'fields['.$key.'][default_headers]',
					'value'		=>	$field['default_headers'],
				));

				?>
			</td>
		</tr>

		<?php

	}


	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{


		// vars
		$o = array( 'id',  'name' );
		$e = '';


		$e .= '<textarea style="display:none" ';

		foreach( $o as $k )
		{
			$e .= ' ' . $k . '="' . esc_attr( $field[ $k ] ) . '"';
		}
		//field key

		$e .= ' data-key="'.$field['key'].'"';

				// maxlength
		if( $field['maxrows'] !== '' ) $e .= ' data-maxrows="'.$field['maxrows'].'"';

				// disable sort
		if( !empty($field['disable_sort']) ) $e .= ' data-disablesort="'.$field['disable_sort'].'"';

				// fixed rows
		if( !empty($field['fixed_columns']) ) {

			$defaultheaders = preg_split( '/\r\n|\r|\n/',  $field['default_headers'] );

			$e .= 'data-defaultheaders="'.implode(",", $defaultheaders) .'"';
			if( empty($field['default_headers']) ) $e .= 'data-defaultheaders="No Default Headers Set"';

		}

		$e .= '>';
		$e .= esc_textarea($field['value']);
		$e .= '</textarea>';

		// return
		echo $e;


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


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used

		wp_register_script( 'acf-field-dynamic-table-group', $this->settings['dir'] . 'js/dynamic-table-group.js', array( 'acf-field-group' ), $this->settings['version'] );


		// scripts
		wp_enqueue_script(
			array(
				'acf-field-dynamic-table-group',
			)
		);

	}



	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value_for_api( $value, $post_id, $field )
	{
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
			 $html .=  '<tbody>';
			 foreach ($tabledata as $row): array_map('htmlentities', $row);
			    $html .= '<tr>';
			      $html .= '<td>'.implode('</td><td>', $row).'</td>';
			   $html .=  '</tr>';
			 endforeach;
			  $html .= '</tbody>';
			$html .= '</table>';

		// return
		return $html;



		// Note: This function can be removed if not used
		return $value;
	}



}


// create field
new acf_field_table_field();

?>