<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Hlc-software.eu
 * @since      1.0.0
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/admin
 * @author     HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 */
class Hlc_Sql_Window_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hlc_Sql_Window_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hlc_Sql_Window_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hlc-sql-window-admin.css', array(), $this->version, 'all' );

	}


	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hlc_Sql_Window_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hlc_Sql_Window_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hlc-sql-window-admin.js', array( 'jquery' ), $this->version, false );

	}

  public function hlc_adminInit(){
    add_settings_section('hlc_settings_section','HLC settings',array($this,'hlc_settings_section'),'general');
  }

  public function hlc_settings_section(){
    $surl = site_url();
    $content = '';
    echo 'Site name :'.$surl.'<br/>';
    do_action('hlc_genpro_settings', array('content' => $content));
    echo $content;
    if( strlen($content)==0){
      echo 'You are using standard version. Check Pro version <a href="http://hlc-software.eu/sql-window-pro/" target="_blank">here</a>.';
    }
  }

  //public function hlc_settings(){
  //  add_options_page('HLC settings','HLC Software','manage_options','hlc_settings','');
  //}
}
