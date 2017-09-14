<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CCBPress_Settings_CCB extends CCBPress_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'initialize' ) );
		add_filter( 'ccbpress_settings_help_tabs', array( $this, 'help_tabs' ) );
    }

    public function initialize() {

        // First, we register a section. This is necessary since all future options must belong to one.
    	add_settings_section(
    		'ccbpress_settings_ccb_connection_settings_section',
    		__( 'Connection Settings', 'ccbpress-core' ),
    		array( $this, 'ccb_section_callback' ),
    		'ccbpress_settings_ccb'
    	);

        // If the option does not exist, then add it
    	if ( false == get_option( 'ccbpress_ccb' ) ) {
    		add_option( 'ccbpress_ccb' );
    	}

    	/**
    	 * The API URL field
    	 */

    	add_settings_field(
    		'api_prefix',
    		'<strong>' . __('Your CCB Website', 'ccbpress-core') . '</strong>',
    		array( $this, 'input_callback' ),
    		'ccbpress_settings_ccb',
    		'ccbpress_settings_ccb_connection_settings_section',
    		array(
    			'field_id'  => 'api_prefix',
    			'page_id'   => 'ccbpress_ccb',
                'size'      => 'medium',
    			'label'     => __('The URL you use to access your Church Community Builder site.', 'ccbpress-core'),
                'before'    => '<code>https://</code>',
                'after'    => '<code>.ccbchurch.com</code>'
    		)
    	);

		/**
		 * The API username field
		 */

		add_settings_field(
			'api_user',
			'<strong>' . __( 'API Username', 'ccbpress-core' ) . '</strong>',
			array( $this, 'input_callback' ),
			'ccbpress_settings_ccb',
			'ccbpress_settings_ccb_connection_settings_section',
			array(
				'field_id'  	=> 'api_user',
				'page_id'   	=> 'ccbpress_ccb',
				'size'      	=> 'medium',
				'autocomplete'	=> 'off',
				'label'			=> __( 'This is different from the login you use for Church Community Builder.', 'ccbpress-core' ),
			)
		);

		/**
		 * The API password field
		 */

		add_settings_field(
			'api_pass',
			'<strong>' . __( 'API Password', 'ccbpress-core' ) . '</strong>',
			array( $this, 'input_callback' ),
			'ccbpress_settings_ccb',
			'ccbpress_settings_ccb_connection_settings_section',
			array(
				'field_id'  	=> 'api_pass',
				'page_id'   	=> 'ccbpress_ccb',
				'type'      	=> 'password',
				'size'      	=> 'medium',
				'autocomplete'	=> 'off',
			)
		);

		if ( CCBPress()->ccb->is_connected() ) {

			// First, we register a section. This is necessary since all future options must belong to one.
	    	add_settings_section(
	    		'ccbpress_settings_ccb_api_services_section',
	    		__( 'API Services', 'ccbpress-core' ),
	    		array( $this, 'api_services_section_callback' ),
	    		'ccbpress_settings_ccb'
	    	);

			add_settings_field(
	            'check_services_form',
	            '<strong>' . __('Check Your Services', 'ccbpress-core') . '</strong>',
	            array( $this, 'text_callback' ),
	            'ccbpress_settings_ccb',
	            'ccbpress_settings_ccb_api_services_section',
	            array(
	                'header' => NULL,
	                'title' => NULL,
	                'content' => '<a class="button" id="ccbpress-ccb-service-check-button">Check Services Now</a><div id="ccbpress-ccb-service-check-results"></div>',
	            )
	        );

		}

        // Finally, we register the fields with WordPress
    	register_setting(
    		'ccbpress_settings_ccb',			// The group name of the settings being registered
    		'ccbpress_ccb',			// The name of the set of options being registered
    		array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields
    	);

    }

    public function ccb_section_callback() {
        echo '<p>' . __('These are the settings for the API connection to Church Community Builder.', 'ccbpress-core') . '</p>';
	}

	public function api_services_section_callback() {
        echo '<p>' . __('Use this tool to check if your API User has the appropriate API Services enabled in Church Community Builder.', 'ccbpress-core') . '</p>';
	}

    public function sanitize_callback( $input ) {
        //return $input;
        // Define all of the variables that we'll be using
    	$ccb_api_user = "";
    	$ccb_api_pass = "";
    	$ccb_api_prefix = "";
    	$output = array();

    	// Loop through each of the incoming options
    	foreach ( $input as $key => $value ) {

    		// Check to see if the current option has a value. If so, process it.
    		if ( isset( $input[$key] ) ) {

    			switch ( $key ) {

    				case 'api_user':
    					$ccb_api_user = $input[$key];
    					break;

    				case 'api_pass':
    					$ccb_api_pass = $input[$key];
    					break;

    				case 'api_prefix':
    					$ccb_api_prefix = $input[$key];
    					break;

    			}

    			// Strip all HTML and PHP tags and properly handle quoted strings
    			$output[$key] = strip_tags( stripslashes( $input[$key] ) );

    		}

    	}

    	// Let's test the connection with our newly saved settings
        $output['connection_test'] = (string) CCBPress()->ccb->test_connection( $output['api_prefix'], $output['api_user'], $output['api_pass'] );

    	// Return the array
    	return $output;

    }

	public function help_tabs( $help_tabs ) {

		$services = apply_filters( 'ccbpress_ccb_services', array() );
		sort( $services );

		ob_start();
		?>
		<p>Your API User must have permission to use the following services:
		<ul>
		<?php foreach ( $services as $service ) : ?>
		    <li><?php echo $service; ?></li>
		<?php endforeach; ?>
		<?php if ( count( $services ) === 0 ) : ?>
			<li><?php _e('There are no services registered with CCBPress.', 'ccbpress-core'); ?></li>
		<?php endif; ?>
		</ul></p>
		<?php
		$content = ob_get_clean();

		$help_tabs[] = array(
			'id'		=> 'ccbpress-ccb-services',
			'tab_id'	=> 'ccb',
			'title'		=> __('Required Services', 'ccbpress-core'),
			'content'	=> $content,
		);

		return $help_tabs;

	}

}
new CCBPress_Settings_CCB();
