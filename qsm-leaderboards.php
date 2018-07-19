<?php
/**
 * Plugin Name: QSM - Leaderboards
 * Plugin URI: https://quizandsurveymaster.com
 * Description: Adds some basic leaderboards to Quiz And Survey Master
 * Author: Frank Corso
 * Author URI: https://quizandsurveymaster.com
 * Version: 1.0.1
 *
 * @author Frank Corso
 * @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * This class is the main class of the plugin
 *
 * When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
 *
 * @since 1.0.0
*/
class QSM_Leaderboards {

	/**
	 * Version Number
	 *
	 * @var string
	 * @since 1.0.0
	*/
	public $version = '1.0.1';

	/**
	 * Main Construct Function
	 *
	 * Call functions within class
	 *
	 * @since 1.0.0
	 * @uses QSM_Leaderboards::load_dependencies() Loads required filed
	 * @uses QSM_Leaderboards::add_hooks() Adds actions to hooks and filters
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
	 * @since 1.0.0
	 * @return void
	*/
	public function load_dependencies() {
		include( "php/addon-settings-tab-content.php" );
		include( "php/quiz-settings-tab-content.php" );
		include( "php/leaderboard-function.php" );
		include( "php/shortcodes.php" );
		include( "php/widgets.php" );
	}

	/**
	 * Add Hooks
	 *
	 * Adds functions to relavent hooks and filters
	 *
	 * @since 1.0.0
	 * @return void
	*/
	public function add_hooks() {
		add_action( 'init',  array( $this, 'register_fields' ) );
		add_action( 'init',  array( $this, 'backwards_compatibility' ) );
		add_action( 'admin_init', 'qsm_addon_leaderboards_register_quiz_settings_tabs' );
		add_action( 'admin_init', 'qsm_addon_leaderboards_register_addon_settings_tabs' );
		add_shortcode( 'qsm_leaderboard', 'qsm_addon_leaderboards_shortcode' );
		add_action('widgets_init', create_function('', 'return register_widget("QSM_Leaderboards_Widget");') );
	}

	/**
	 * Small fixes for using with older versions of QSM
	 *
	 * @since 1.0.0
	 */
	public function backwards_compatibility() {
		remove_shortcode( 'mlw_quizmaster_leaderboard' );
		add_shortcode( 'mlw_quizmaster_leaderboard', 'qsm_addon_leaderboards_shortcode' );
	}

	/**
	 * Sets up quiz settings for the addon
	 *
	 * @since 1.0.0
	 */
	public function register_fields() {
		global $mlwQuizMasterNext;

		$field_array = array(
			'id'        => 'template',
			'label'     => 'Leaderboard Template',
			'type'      => 'editor',
			'variables' => array(
				'%QUIZ_NAME%',
				'%FIRST_PLACE_NAME%',
				'%FIRST_PLACE_SCORE%',
				'%SECOND_PLACE_NAME%',
				'%SECOND_PLACE_SCORE%',
				'%THIRD_PLACE_NAME%',
				'%THIRD_PLACE_SCORE%',
				'%FOURTH_PLACE_NAME%',
				'%FOURTH_PLACE_SCORE%',
				'%FIFTH_PLACE_NAME%',
				'%FIFTH_PLACE_SCORE%',
			),
			'default' => 0,
		);
		$mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_leaderboards' );
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
	$leaderboard_data = get_option( 'qsm_addon_leaderboard_settings', '' );
	if ( isset( $leaderboard_data["license_key"] ) ) {
		$license_key = trim( $leaderboard_data["license_key"] );
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
 * @since 1.0.0
 * @return void
 */
function qsm_addon_leaderboard_load() {
	// Make sure QSM is active
	if ( class_exists( 'MLWQuizMasterNext' ) ) {
		$qsm_leaderboards = new QSM_Leaderboards();
	} else {
		add_action( 'admin_notices', 'qsm_addon_leaderboard_missing_qsm' );
	}
}
add_action( 'plugins_loaded', 'qsm_addon_leaderboard_load' );

/**
 * Display notice if Quiz And Survey Master isn't installed
 *
 * @since 1.0.0
 */
function qsm_addon_leaderboard_missing_qsm() {
	echo '<div class="error"><p>QSM - Leaderboards requires Quiz And Survey Master. Please install and activate the Quiz And Survey Master plugin.</p></div>';
}
?>
