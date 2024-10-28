<?php
class AccessibilityStatementSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'A11y statement settings', 
            'A11y statement settings', 
            'manage_options', 
            'a11y-statement-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'a11y_statement_options' );
        ?>
        <div class="wrap">
            <h1>Accessibility statement settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'a11y_statement_option_group' );
                do_settings_sections( 'a11y-statement-settings' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'a11y_statement_option_group', // Option group
            'a11y_statement_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'a11y-statement-settings' // Page
        );     

        add_settings_field(
            'content_config', 
            'Content configurator', 
            array( $this, 'content_config_callback' ), 
            'a11y-statement-settings', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['content_config'] ) )
            $new_input['content_config'] = stripslashes( $input['content_config'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:<br>';
        print 'Available shortcodes: [blog_name] [blog_url]';

    }

    public function content_config_callback()
    {
  
        echo  '<textarea id="content_config" name="a11y_statement_options[content_config]" rows="20" cols="150">';
        echo isset( $this->options['content_config'] ) ? esc_attr( $this->options['content_config']) : '';
        echo '</textarea>';

    }

}