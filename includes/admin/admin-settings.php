<?php
class CCBPress_Settings {

    /**
     * Text Input Field
     *
     * @package    CCBPress_Core
     * @since      1.0.0
     *
     * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
     *
     * @return	string	HTML to display the field.
     */
    public function input_callback( $args ) {

        // Set the defaults.
		$defaults = array(
			'field_id'		=> null,
			'page_id'		=> null,
			'label'      	=> null,
            'type'          => 'text',
			'size'          => 'regular',
            'before'        => null,
            'after'         => null,
			'autocomplete'	=> null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

        // Get the saved values from WordPress.
    	$options = get_option( $args['page_id'] );


        // Start the output buffer.
        ob_start();
        ?>
        <?php echo $args['before']; ?>
        <input type="<?php echo esc_attr( $args['type'] ); ?>" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo ( isset( $options[ $args['field_id'] ] ) ? $options[ $args['field_id'] ] : '' ); ?>" class="<?php esc_attr_e( $args['size'] ); ?>-text"<?php echo ( 'off' === $args['autocomplete'] ) ? ' autocomplete="off"' : ''; ?> />
        <?php echo $args['after']; ?>
        <?php if ( $args['label'] != '' ) : ?>
            <p class="description"><?php echo $args['label']; ?></p>
        <?php endif; ?>

        <?php
    	// Print the output
        echo ob_get_clean();

    } // input_callback()

	/**
     * License Key Field
     *
     * @package    CCBPress_Core
     * @since      1.0.0
     *
     * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
     *
     * @return	string	HTML to display the field.
     */
    public function license_key_callback( $args ) {

        // Set the defaults
		$defaults = array(
			'field_id'		=> NULL,
			'page_id'		=> NULL,
			'label'      	=> NULL,
		);

		// Parse the arguments
		$args = wp_parse_args( $args, $defaults );

        // Get the saved values from WordPress
    	$options = get_option( $args['page_id'] );

		$license_data = get_option( $args['field_id'] . '_active', '' );
		$license_data = json_decode( $license_data );

        // Start the output buffer
        ob_start();
        ?>
		<?php wp_nonce_field( $args['field_id'] . '-nonce', $args['field_id'] . '-nonce' ); ?>
		<input type="text" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo ( isset( $options[ $args['field_id'] ] ) ? $options[ $args['field_id'] ] : '' ); ?>" class="regular-text" />
		<?php if ( isset( $license_data->license ) && 'valid' === $license_data->license ) : ?>
			<input type="submit" class="button-secondary" name="<?php echo esc_attr( $args['field_id'] . '_deactivate' ); ?>" value="<?php echo esc_attr( __('Deactivate License', 'ccbpress-core') ); ?>">
			<?php if ( isset( $license_data->expires ) && 'lifetime' === $license_data->expires ) : ?>
				<p class="description"><?php esc_html_e( 'Your license key never expires.', 'ccbpress-core' ); ?></p>
			<?php else : ?>
				<p class="description"><?php echo esc_html( sprintf( '%1$s %2$s.', __( 'Your license key expires on', 'ccbpress-core' ), (string) date( 'F jS, Y', strtotime( $license_data->expires ) ) ) ); ?></p>
			<?php endif; ?>
		<?php endif; ?>
        <?php if ( '' !== $args['label'] ) : ?>
            <p class="description"><?php echo $args['label']; ?></p>
        <?php endif; ?>

        <?php
    	// Print the output
        echo ob_get_clean();

    } // license_key_callback()

	/**
     * Checkbox Input Field
     *
     * @package    CCBPress_Core
     * @since      1.0.0
     *
     * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
     *
     * @return	string	HTML to display the field.
     */
    public function checkbox_callback( $args ) {

        // Set the defaults
		$defaults = array(
			'field_id'		=> null,
			'page_id'		=> null,
			'value'			=> '1',
			'label'      	=> null,
            'before'        => null,
            'after'         => null,
		);

		// Parse the arguments
		$args = wp_parse_args( $args, $defaults );

        // Get the saved values from WordPress
    	$options = get_option( $args['page_id'] );


        // Start the output buffer
        ob_start();
        ?>
        <?php echo $args['before']; ?>
        <input type="checkbox" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $args['value'] ); ?>" <?php isset( $options[ $args['field_id'] ] ) ? checked( $options[ $args['field_id'] ] ) : '' ?>/>
		<?php if ( $args['label'] != '' ) : ?>
            <label for="<?php echo esc_attr( $args['field_id'] ); ?>" class="description"><?php echo $args['label']; ?></label>
        <?php endif; ?>
		<?php echo $args['after']; ?>

        <?php
    	// Print the output
        echo ob_get_clean();

    } // input_callback()


	/**
	 * Select Input Field
	 *
	 * @package    CCBPress
	 * @since      1.0
	 *
	 * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
	 * array	$args[ 'options' ]
	 *
	 * @return	string	HTML to display the field.
	 */

	public function select_callback( $args ) {

		// Set the defaults
		$defaults = array(
			'field_id'      => NULL,
			'page_id'       => NULL,
			'label'         => NULL,
            'options'       => array(),
		);

        // Parse the arguments
		$args = wp_parse_args( $args, $defaults );

		// Pull the variables from the array
		$field_id		= $args['field_id'];
	    $page_id		= $args['page_id'];
	    $label_text		= $args['label'];
	    $select_options	= $args['options'];

	    // Get the saved values from WordPress
		$options = get_option( $args['page_id'] );

		ob_start(); ?>
		<select id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]">
	    <?php
		// Loop through all of the available options
		foreach ( $args['options'] as $key => $value ) : ?>
			<option <?php echo selected( ( empty( $options[ $args['field_id'] ] ) ? '' : $options[ $args['field_id'] ] ), $key, false ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo $value; ?></option>
		<?php endforeach; ?>
		</select>
	    <span class="description"><?php echo $args['label']; ?></span>
		<?php
	    // Print the output
		echo ob_get_clean();

	} // select_callback()

    /**
     * List Select Input Field
     *
     * @package    CCBPress
     * @since      1.0
     *
     * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
	 * array	$args[ 'options' ]
	 * string	$args[ 'input_title' ]
	 * string	$args[ 'select_title' ]
	 * string	$args[ 'right_title' ]
     *
     * @return	string	HTML to display the field.
     */

    public function list_select_callback( $args ) {

		wp_enqueue_script( 'ccbpress-core-fields', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/fields.js', array('jquery'), '1.0.0' );
		wp_enqueue_style( 'ccbpress-core-fields', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/admin/fields.css' );

		// Set the defaults
		$defaults = array(
			'field_id'      => NULL,
			'page_id'       => NULL,
			'label'         => NULL,
            'options'       => array(),
			'input_title'   => NULL,
            'select_title'  => NULL,
            'right_title'   => NULL,
		);

        // Parse the arguments
		$args = wp_parse_args( $args, $defaults );

        // Get the saved values from WordPress
    	$options = get_option( $args['page_id'] );
        $selected_values_original = ( empty( $options) ? '' : $options[ $args['field_id'] ] );
        $selected_values = explode('&&', $selected_values_original);

        ob_start(); ?>
        <div class="description"><?php echo $args['label']; ?></div><br />
        <fieldset class="ccbpress_list_select">
    		<label for="<?php echo esc_attr( $args['field_id'] ); ?>_input"><strong><?php echo $args['input_title']; ?></strong></label>
    		<input type="text" id="<?php echo esc_attr( $args['field_id'] ); ?>_input" />
        	<label for="<?php echo esc_attr( $args['field_id'] ); ?>_select"><strong><?php echo $args['select_title']; ?></strong></label>
        	<select id="<?php echo esc_attr( $args['field_id'] ); ?>_select">
    		<?php foreach ( $args['options'] as $select_option ) : ?>
				<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo $select_option['name']; ?></option>
			<?php endforeach; ?>
			</select>
			<button class="button ccbpress_list_select_add" id="<?php echo esc_attr( $args['field_id'] ); ?>_add"><?php _e('Add', 'ccbpress-core'); ?></button>
        </fieldset>
        <fieldset class="ccbpress_list_select_right">
        	<label for="<?php echo esc_attr( $args['field_id'] ); ?>_selected"><strong><?php echo $args['right_title']; ?></strong></label>
        	<select size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_selected">
			<?php
			// Find all of the values that have been selected
    		foreach ( $args['options'] as $select_option ) :
				// Check that the value is in the array of selected values
				foreach ( $selected_values as $value ) :
					$value_array = explode( '==', $value );
					if ( isset( $value_array[1] ) && $select_option['value'] == $value_array[1] ) : ?>
    					<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo $value_array[0]; ?></option>
    				<?php endif; ?>
    			<?php endforeach; ?>
        	<?php endforeach; ?>
        	</select>
        	<button class="button ccbpress_list_select_edit" id="<?php echo esc_attr( $args['field_id'] ); ?>_edit"><?php _e('Edit', 'ccbpress-core'); ?></button>&nbsp;
    		<button class="button ccbpress_list_select_remove" id="<?php echo esc_attr( $args['field_id'] ); ?>_remove"><?php _e('Remove', 'ccbpress-core'); ?></button>
        </fieldset>
        <input type="hidden" id="<?php echo esc_attr( $args['field_id'] ); ?>_values" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $selected_values_original ); ?>" />
		<?php
        // Print the output
    	echo ob_get_clean();

    } // list_select_callback()

	/**
	 * Multi Select Input Field
	 *
	 * @package    CCBPress
	 * @since      1.0
	 *
	 * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'label' ]
	 * array	$args[ 'options' ]
	 *
	 * @return	string	HTML to display the field.
	 */

	public function multi_select_callback( $args ) {

		wp_enqueue_script( 'ccbpress-core-fields', CCBPRESS_CORE_PLUGIN_URL . 'assets/js/admin/fields.js', array('jquery'), '1.0.0' );
		wp_enqueue_style( 'ccbpress-core-fields', CCBPRESS_CORE_PLUGIN_URL . 'assets/css/admin/fields.css' );

		// Set the defaults
		$defaults = array(
			'field_id'      => NULL,
			'page_id'       => NULL,
			'label'         => NULL,
			'options'       => array(),
		);

		// Parse the arguments
		$args = wp_parse_args( $args, $defaults );

	    // Get the saved values from WordPress
		$options = get_option( $args['page_id'] );
	    $selected_values_original = ( empty( $options ) ? '' : ( isset( $options[ $args['field_id'] ] ) ? $options[ $args['field_id'] ] : '' ) );
	    $selected_values = explode(',', $selected_values_original);

		ob_start(); ?>
		<?php if ( ! is_null( $args['label'] ) ) : ?>
			<div class="description"><?php echo $args['label']; ?></div><br />
		<?php endif; ?>
	    <fieldset class="ccbpress_multi_select">
	    	<legend><?php _e('Unselected:', 'ccbpress-core'); ?></legend>
	    	<select multiple="multiple" size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_all">
			<?php
			if ( $args['options'] != '' ) :
				// Find all of the values that have not been selected
				foreach ( $args['options'] as $select_option ) :
					// Check that the value is not in the array of selected values
					if ( ! in_array( $select_option['value'], $selected_values ) ) : ?>
						<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo $select_option['name']; ?></option>
			    	<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
	    	<button class="button ccbpress_multi_select_add" id="<?php echo esc_attr( $args['field_id'] ); ?>_add"><?php _e('Add To Selected', 'ccbpress-core'); ?></button>
	    </fieldset>
	    <fieldset class="ccbpress_multi_select">
	    	<legend><?php _e('Selected:', 'ccbpress-core'); ?></legend>
	    	<select multiple="multiple" size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_selected">
			<?php
			if ( $args['options'] != '' ) :
				// Find all of the values that have been selected
				foreach ( $args['options'] as $select_option ) :
					// Check that the value is in the array of selected values
					if ( in_array( $select_option['value'], $selected_values ) ) : ?>
						<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo $select_option['name']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
			<button class="button ccbpress_multi_select_remove" id="<?php echo esc_attr( $args['field_id'] ); ?>_remove"><?php _e('Remove From Selected', 'ccbpress-core'); ?></button>
	    </fieldset>
	    <input type="hidden" id="<?php echo esc_attr( $args['field_id'] ); ?>_values" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $selected_values_original ); ?>" />
		<?php
	    // Print the output
		echo ob_get_clean();

	} // multi_select_callback()

	/**
	 * Textarea Input Field
	 *
	 * @package    CCBPress
	 * @since      1.0
	 *
	 * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'field_id' ]
	 * string	$args[ 'page_id' ]
	 * string	$args[ 'textarea_id' ]
	 * bool		$args[ 'media_upload' ]
	 * int		$args[ 'rows' ]
	 * bool		$args[ 'minimal' ]
	 * bool		$args[ 'wysiwyg' ]
	 *
	 * @return	string	HTML to display the field.
	 */

	public function textarea_callback( $args ) {

		// Set the defaults
		$defaults = array(
			'field_id'      => NULL,
			'page_id'       => NULL,
			'textarea_id'   => NULL,
			'media_upload'	=> TRUE,
			'rows'			=> get_option( 'default_post_edit_rows', 10 ),
			'cols'			=> 40,
			'minimal'		=> FALSE,
			'wysiwyg'		=> FALSE,
			'wpautop'		=> FALSE,
		);

		// Parse the arguments
		$args = wp_parse_args( $args, $defaults );

		// Get the saved values from WordPress
		$options = get_option( $args['page_id'] );
		$editor_value = $options[ $args['field_id'] ];

	    // Checks if it should display the WYSIWYG editor
		if ( TRUE === $args['wysiwyg'] ) {

			wp_editor( $editor_value, $args['textarea_id'], array(
			    'textarea_name'	=> $args['page_id'] . '[' . $args['field_id'] . ']',
			    'media_buttons'	=> $args['media_upload'],
			    'textarea_rows'	=> $args['rows'],
			    'wpautop'		=> $args['wpautop'],
			    'teeny'			=> $args['minimal'],
		    ) );

	    } else {

		   // Display the plain textarea field
		   echo '<textarea rows="' . $args['rows'] . '" cols="' . $args['cols'] . '" name="' . $args['page_id'] . '[' . $args['field_id'] . ']" id="' . $args['textarea_id'] . '" class="ccbpress code">' . $editor_value . '</textarea>';

	    }

	} // textarea_callback()

	/**
	 * Text
	 *
	 * @package    CCBPress_Core
	 * @since      1.0.0
	 *
	 * @param	array	$args	Arguments to pass to the function. (See below).
	 *
	 * string	$args[ 'header_type' ]
	 * string	$args[ 'title' ]
	 * string	$args[ 'content' ]
	 *
	 * @return	string	HTML to display the field.
	 */

	public function text_callback( $args ) {

		// Set the defaults
		$defaults = array(
			'header'	=> 'h2',
			'title'		=> NULL,
			'content'	=> NULL,
		);

		// Parse the arguments
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		// Check that the title and header_type are not blank
		if ( ! is_null( $args['title'] ) ) {
			echo '<' . $args['header'] . '>' . $args['title'] . '</' . $args['header'] . '>';
	    }

	    // Check that the content is not blank
		if ( ! is_null ( $args['content'] ) ) {
			echo $args['content'];
	    }

		// Print the output
	    echo ob_get_clean();

	} // text_callback()

}
