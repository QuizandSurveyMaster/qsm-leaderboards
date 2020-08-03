<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers your tab in the quiz settings page
 *
 * @since 1.0.0
 * @return void
 */
function qsm_addon_leaderboards_register_quiz_settings_tabs() {
  global $mlwQuizMasterNext;
  if ( ! is_null( $mlwQuizMasterNext ) && ! is_null( $mlwQuizMasterNext->pluginHelper ) && method_exists( $mlwQuizMasterNext->pluginHelper, 'register_quiz_settings_tabs' ) ) {
    $mlwQuizMasterNext->pluginHelper->register_quiz_settings_tabs( "Leaderboards", 'qsm_addon_leaderboards_quiz_settings_tabs_content' );
  }
}

/**
 * Generates the content for your quiz settings tab
 *
 * @since 1.0.0
 * @return void
 */
function qsm_addon_leaderboards_quiz_settings_tabs_content() {

	global $wpdb;
	global $mlwQuizMasterNext;
	?>
	<h3><?php _e( 'Template Variables', 'quiz-master-next' ); ?></h3>
	<p>Use this shortcode to generate the leaderboard for this quiz: [qsm_leaderboard quiz=<?php echo intval( $_GET["quiz_id"] ); ?> top_users=5]</p>
	<table class="form-table">
		<tr>
			<td><strong>%QUIZ_USER_NAME%</strong> - <?php _e("The name of the user", 'quiz-master-next'); ?></td>
			<td><strong>%QUIZ_USER_SCORE%</strong> - <?php _e("The user score from quiz", 'quiz-master-next'); ?></td>
		</tr>

		<tr>
			<td><strong>%QUIZ_NAME%</strong> - <?php _e("The name of the quiz", 'quiz-master-next'); ?></td>
		</tr>
	</table> 
	<?php
	$mlwQuizMasterNext->pluginHelper->generate_settings_section( 'quiz_leaderboards' );
}
?>
