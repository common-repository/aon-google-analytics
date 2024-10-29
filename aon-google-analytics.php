<?php
/*
	Plugin Name: AON Google Analytics
	Plugin URI: https://www.aarononeill.com/how-to-add-google-analytics-to-wordpress/
	Description: Simply put, this is the easiest way to implement Google Analytics tracking on your website. No hassle, no fuss.
	Version: 1.0
	Author: Aaron O'Neill
	Author URI: https://www.aarononeill.com/
*/

if(!class_exists('AON_Google_Analytics'))
{

  require_once 'classes/class-admin-page.php';

  /**
   * Class AON_Google_Analytics
   */
  class AON_Google_Analytics
  {

    /**
     * @var bool|mixed|void
     */
    public $options;

    /**
     * @var AON_Admin_Page
     */
    private $admin_page;

    /**
     * AON_Google_Analytics constructor.
     */
    public function __construct()
    {

      # Load Options
      $this->options = get_option( 'aon_google_analytics' );

      # Instantiate Classes:
      $this->admin_page = new AON_Admin_Page($this->options);

      # Actions:
      add_action( 'wp_head', array( $this, 'add_google_analytics' ), 20 );
      add_action( 'admin_menu', array( $this, 'add_admin_menu_item' ), 20 );
      add_action( 'admin_init', array( $this, 'add_admin_settings_page' ), 20 );

      # Deactivation
      register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

    }

    /**
     * Adds Google Analytics script
     */
    public function add_google_analytics()
    {

      if( isset($this->options['tracking_number']) && !empty($this->options['tracking_number']) ) {
      ?>
        <!-- Added with AON Google Analytics plugin -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $this->options['tracking_number']) ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '<?php echo esc_attr( $this->options['tracking_number']) ?>');
        </script>
        <!-- Added with AON Google Analytics plugin -->
        <?php
      }
    }

    /**
     * Adds admin menu item under tools menu item
     */
    public function add_admin_menu_item()
    {

      add_submenu_page(
        'tools.php',
        'AON Google Analytics',
        'AON Google Analytics',
        'manage_options',
        'aon-google-analytics',
        array( $this->admin_page, 'create_admin_page' )
      );

    }

    /**
     * Calls the admin page class - creates settings
     */
    public function add_admin_settings_page()
    {

      $this->admin_page->page_init();

    }

    /**
     * On deactivation delete the created options
     */
    public function deactivate()
    {

      delete_option('aon_google_analytics');

    }


  }

  $aon_google_analytics = new AON_Google_Analytics();

}
