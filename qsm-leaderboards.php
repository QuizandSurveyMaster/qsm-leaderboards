<?php
/**
 * Plugin Name: QSM -
 * Plugin URI:
 * Description:
 * Author:
 * Author URI:
 * Version: 0.1.0
 *
 * @author
 * @version 0.1.0
 */

 /**
  * @todo Follow this list to setup your addon:
  *
  * - Fill in information in the comments at the top of this file
  * - Replace the Plugin_Name class throughout the addon with your addon's main class
  * - Change the xxxxx in the various settings functions to your addon's name
  * - Replace all instances of the plugin name with your addon's name including the folder and the main file
  * - Find all @todo's and fill in the relevant information
  */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
  * This class is the main class of the plugin
  *
  * When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
  *
  * @since 0.1.0
  */
class Plugin_Name {

    /**
  	 * Version Number
  	 *
  	 * @var string
  	 * @since 0.1.0
  	 */
  	public $version = '0.1.0';

    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.1.0
  	  * @uses Plugin_Name::load_dependencies() Loads required filed
  	  * @uses Plugin_Name::add_hooks() Adds actions to hooks and filters
  	  * @return void
  	  */
    function __construct() {
      $this->load_dependencies();
      $this->add_hooks();
			$this->check_license();
    }

    /**
  	  * Load File Dependencies
  	  *
  	  * @since 0.1.0
  	  * @return void
      * @todo If you are not setting up the addon settings tab, the quiz settings tab, or variables, simply remove the include file below
  	  */
    public function load_dependencies() {
      include( "php/addon-settings-tab-content.php" );
      include( "php/quiz-settings-tab-content.php" );
      include( "php/variables.php" );
    }

    /**
  	  * Add Hooks
  	  *
  	  * Adds functions to relavent hooks and filters
  	  *
  	  * @since 0.1.0
  	  * @return void
      * @todo If you are not setting up the addon settings tab, the quiz settings tab, or variables, simply remove the relevant add_action below
  	  */
    public function add_hooks() {
      add_action( 'admin_init', 'qsm_addon_xxxxx_register_quiz_settings_tabs' );
      add_action( 'admin_init', 'qsm_addon_xxxxx_register_addon_settings_tabs' );
      add_filter( 'mlw_qmn_template_variable_results_page', 'qsm_addon_xxxxxx_my_variable', 10, 2 );
    }

	/**
	 * Checks license
	 *
	 * Checks to see if license is active and, if so, checks for updates
	 *
	 * @since 1.0.0
	 * @return void
	 */
	 public function check_license() {

		if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// load our custom updater
			include( 'php/EDD_SL_Plugin_Updater.php' );
		}

	  // retrieve our license key from the DB
	  $analysis_data = get_option( 'qsm_addon_analysis_settings', '' );
	  if ( isset( $logic_data["license_key"] ) ) {
	    $license_key = trim( $analysis_data["license_key"] );
	  } else {
	    $license_key = '';
	  }

	 	// setup the updater
	 	$edd_updater = new EDD_SL_Plugin_Updater( 'https://quizandsurveymaster.com', __FILE__, array(
	 			'version' 	=> $this->version, 				// current version number
	 			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
	 			'item_name' => 'Leaderboards', 	// name of this plugin
	 			'author' 	=> 'Frank Corso'  // author of this plugin
	 		)
	 	);
	 }
}

/**
 * Loads the addon if QSM is installed and activated
 *
 * @since 0.1.0
 * @return void
 */
function qsm_addon_xxxxxx_load() {
	// Make sure QSM is active
	if ( class_exists( 'MLWQuizMasterNext' ) ) {
		$plugin_name = new Plugin_Name();
	} else {
		add_action( 'admin_notices', 'qsm_addon_xxxxxx_missing_qsm' );
	}
}
add_action( 'plugins_loaded', 'qsm_addon_xxxxxx_load' );

/**
 * Display notice if Quiz And Survey Master isn't installed
 *
 * @since       0.1.0
 * @return      string The notice to display
 */
function qsm_addon_xxxxxx_missing_qsm() {
  echo '<div class="error"><p>Plugin Name requires Quiz And Survey Master. Please install and activate the Quiz And Survey Master plugin.</p></div>';
}
?>
