<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       Hlc-software.eu
 * @since      1.0.0
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/public
 * @author     HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 */
class Hlc_Sql_Window_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hlc-sql-window-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hlc-sql-window-public.js', array( 'jquery' ), $this->version, false );

	}

  /**
  * Returns sql data results
  * @since 1.0.0
  */
  public function getsqldata($sql){
    if(isset($sql)){
      if(! isset($pwdb)){
        global $wpdb; 
      }
      $results = $wpdb->get_results( $sql, OBJECT );
      if(isset($results)){
        $myTable = new hlc_list();
        $myTable->set_data($results, $wpdb);
        echo '<div class="wrap">'; 
        $myTable->prepare_items(); 
        $myTable->display(); 
        echo '</div>';          
      } 
      return $results; 
    }
    return NULL;
  }

  public function hlc_getSaved($content){
    if(! isset($pwdb)){
      global $wpdb; 
    }
    $sql = 'select id, descr from hlc_Queries order by descr';
    $results = $wpdb->get_results( $sql, OBJECT );      
    if(isset($results)){      
      foreach($results as $res){
        echo '<option value="'.$res->id.'">'.$res->descr.'</option>';
      }
      //$newRes = ob_get_contents();
      unset($results);
    }
  }

  public function hlc_data($content){
    $hlcsql = $_POST["sql"];
    echo '<div class="wrap" style="overflow: scroll;">'; 
    if(isset($hlcsql) && current_user_can('manage_options')){
      $usr = wp_get_current_user();
      $hlcsql = str_replace('\\"','"',$hlcsql);
      $hlcsql = str_replace("\\'",'\'',$hlcsql);
      echo("SQL <br /><hr>" . $hlcsql . '<hr><br />'); 
      if(! isset($pwdb)){
        global $wpdb; 
      }
      if($_POST["run"]){
        $results = $wpdb->get_results( $hlcsql, OBJECT );
        if(isset($results)){
          $mycolumns = $wpdb->get_col_info('name',-1);
          if($_POST["run"]){
            $this->hlc_run($results, $mycolumns);
          }
        }
      }
      else {
        do_action('hlc_genpro_sql', $hlcsql);
      }
    }   
    echo '</div>';
  }

  public function hlc_run($results, $mycolumns){
    //$mycolumns = $wpdb->get_col_info('name',-1);
    if(isset($mycolumns)){
      echo '<table class="widefat">';
      echo '<tr>';
      foreach($mycolumns as $col){
        echo '<th>' . $col . '</th>';
      }
      echo '</tr>';
      foreach($results as $res){
        echo '<tr>';
        foreach($res as $fld){
          echo '<td><pre>';//<![CDATA[
          echo $fld;
          echo '</pre></td>'; //]]>
        }
        echo '</tr>';
      }
      echo '</table>';            
    }
    else {
      echo '<br/>No Results.';
    }
  }

  public function hlc_getSQLText($content){
    $id = $_POST['savedsql'];
    if(isset($id)){
      if(! isset($pwdb)){
        global $wpdb; 
      }
      if($id != 0){
        $sql = 'Select myquery from hlc_Queries where id='.$id.' ';
        $results = $wpdb->get_results( $sql, OBJECT );
        if(isset($results)){      
          foreach($results as $res){
            echo $res->myquery;
          }
          unset($results);
        }
        echo '';
      }
    }
  }

  /**
  * Hlc Commands 
  * Define commands here
  */
  public function hlc_content($content){
    if(strpos($content,'[hlc_data/]') >= 0 ){
      ob_start();
      $this->hlc_data($content);
      $newRes = ob_get_contents();
		  ob_clean();
		  ob_end_flush();
      $content = str_replace('[hlc_data/]',$newRes, $content);
    }
    if(strpos($content,'[hlc_saved/]') >= 0){
      ob_start();
      $this->hlc_getSaved($content);
      $newRes = ob_get_contents();
		  ob_clean();
		  ob_end_flush();
      $content = str_replace('[hlc_saved/]',$newRes, $content);
    }
    if(strpos($content,'[hlc_text/]') >= 0){
      ob_start();
      $this->hlc_getSQLText($content);
      $newRes = ob_get_contents();
		  ob_clean();
		  ob_end_flush();
      $content = str_replace('[hlc_text/]',$newRes, $content);
    }

    return $content;
  }

}
