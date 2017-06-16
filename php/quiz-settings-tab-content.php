<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers your tab in the quiz settings page
 *
 * @since 0.1.0
 * @return void
 */
function qsm_addon_leaderboards_register_quiz_settings_tabs() {
  global $mlwQuizMasterNext;
  if ( ! is_null( $mlwQuizMasterNext ) && ! is_null( $mlwQuizMasterNext->pluginHelper ) && method_exists( $mlwQuizMasterNext->pluginHelper, 'register_quiz_settings_tabs' ) ) {
    $mlwQuizMasterNext->pluginHelper->register_quiz_settings_tabs( "Plugin Name", 'qsm_addon_xxxxx_quiz_settings_tabs_content' );
  }
}

/**
 * Generates the content for your quiz settings tab
 *
 * @since 0.1.0
 * @return void
 * @todo Replace the xxxxx with your addon's name
 */
function qsm_addon_xxxxx_quiz_settings_tabs_content() {

  //Enqueue your scripts and styles
  wp_enqueue_script( 'plugin_name_admin_script', plugins_url( '../js/plugin-name-admin.js' , __FILE__ ), array( 'jquery' ) );
  wp_enqueue_style( 'plugin_name_admin_style', plugins_url( '../css/plugin-name-admin.css' , __FILE__ ) );

	global $wpdb;
	global $mlwQuizMasterNext;
	?>
	<h3><?php _e( 'Template Variables', 'quiz-master-next' ); ?></h3>
	<table class="form-table">
		<tr>
			<td><strong>%FIRST_PLACE_NAME%</strong> - <?php _e("The name of the user who is in first place", 'quiz-master-next'); ?></td>
			<td><strong>%FIRST_PLACE_SCORE%</strong> - <?php _e("The score from the first place's quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%SECOND_PLACE_NAME%</strong> - <?php _e("The name of the user who is in second place", 'quiz-master-next'); ?></td>
			<td><strong>%SECOND_PLACE_SCORE%</strong> - <?php _e("The score from the second place's quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%THIRD_PLACE_NAME%</strong> - <?php _e('The name of the user who is in third place', 'quiz-master-next'); ?></td>
			<td><strong>%THIRD_PLACE_SCORE%</strong> - <?php _e("The score from the third place's quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%FOURTH_PLACE_NAME%</strong> - <?php _e('The name of the user who is in fourth place', 'quiz-master-next'); ?></td>
			<td><strong>%FOURTH_PLACE_SCORE%</strong> - <?php _e("The score from the fourth place's quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%FIFTH_PLACE_NAME%</strong> - <?php _e('The name of the user who is in fifth place', 'quiz-master-next'); ?></td>
			<td><strong>%FIFTH_PLACE_SCORE%</strong> - <?php _e("The score from the fifth place's quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%QUIZ_NAME%</strong> - <?php _e("The name of the quiz", 'quiz-master-next'); ?></td>
		</tr>
	</table>
	<?php
	$mlwQuizMasterNext->pluginHelper->generate_settings_section( 'quiz_leaderboards' );
}
?>
