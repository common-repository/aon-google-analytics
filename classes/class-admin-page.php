<?php
/*
 * Class to create the AON Google Analytics tools page within WordPress admin
 *
 */

if(!class_exists('AON_Admin_Page'))
{

  /**
   * Class AON_Admin_Page
   */
  class AON_Admin_Page
  {

    /**
     * @var bool|mixed|void
     */
    private $options;

    /**
     * AON_Admin_Page constructor.
     * @param $options
     */
    public function __construct($options)
    {
      $this->options = $options;
    }

    /**
     * Creates the admin page wrapper and loads the settings
     */
    public function create_admin_page()
    {
      ?>
      <div class="wrap">
        <h1>AON Google Analytics</h1>
        <form method="post" action="options.php">
          <?php
            // Print out all hidden setting fields
            settings_fields( 'aon_google_analytics_group' );
            do_settings_sections( 'aon-google-analytics' );
            submit_button();
          ?>
        </form>
      </div>
      <?php
    }

    /**
     * Settings creation
     */
    public function page_init()
    {
      register_setting(
        'aon_google_analytics_group', // Option group
        'aon_google_analytics', // Option name
        array( $this, 'sanitize' ) // Sanitize
      );

      add_settings_section(
        'aon_ga_section', // ID
        '', // Title
        array( $this, 'print_section_info' ), // Callback
        'aon-google-analytics' // Page
      );

      add_settings_field(
        'tracking_number',
        'Tracking ID:',
        array( $this, 'tracking_number_callback' ),
        'aon-google-analytics',
        'aon_ga_section'
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

      if( isset( $input['tracking_number'] ) ) {
        $new_input['tracking_number'] = sanitize_text_field($input['tracking_number']);
      }

      return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
      echo 'All you will need here is your Google Analytics tracking ID. Once you have that, copy and paste it into <br>
      the "Tracking ID" field below. Tracking will be enabled when you enter and save your tracking id. You may need <br>
      to flush your websites cache in order for the script to be added.';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function tracking_number_callback()
    {
      printf(
        '<input type="text" placeholder="UA-XXXXXXXX-XX" id="tracking_number" name="aon_google_analytics[tracking_number]" value="%s" />',
        isset( $this->options['tracking_number'] ) ? esc_attr( $this->options['tracking_number']) : ''
      );
    }

  }
}
