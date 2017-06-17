<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers your tab in the addon  settings page
 *
 * @since 1.0.0
 * @return void
 */
function qsm_addon_leaderboards_register_addon_settings_tabs() {
	global $mlwQuizMasterNext;
	if ( ! is_null( $mlwQuizMasterNext ) && ! is_null( $mlwQuizMasterNext->pluginHelper ) && method_exists( $mlwQuizMasterNext->pluginHelper, 'register_quiz_settings_tabs' ) ) {
		$mlwQuizMasterNext->pluginHelper->register_addon_settings_tab( "Leaderboards", 'qsm_addon_leaderboard_addon_settings_tabs_content' );
	}
}

/**
 * Generates the content for your addon settings tab
 *
 * @since 1.0.0
 * @return void
 */
function qsm_addon_leaderboard_addon_settings_tabs_content() {

	global $mlwQuizMasterNext;

	//If nonce is correct, update settings from passed input
	if ( isset( $_POST["leaderboards_nonce"] ) && wp_verify_nonce( $_POST['leaderboards_nonce'], 'leaderboards') ) {

		// Load previous license key
    $leaderboard_data = get_option( 'qsm_addon_leaderboard_settings', '' );
    if ( isset( $leaderboard_data["license_key"] ) ) {
      $license = trim( $leaderboard_data["license_key"] );
    } else {
      $license = '';
    }

    // Save settings
    $saved_license = sanitize_text_field( $_POST["license_key"] );
    $leaderboard_data = array(
      'license_key' => $saved_license
    );
    update_option( 'qsm_addon_leaderboard_settings', $leaderboard_data );

    // Checks to see if the license key has changed
    if ( $license != $saved_license ) {

      // Prepares data to activate the license
      $api_params = array(
        'edd_action'=> 'activate_license',
        'license' 	=> $saved_license,
        'item_name' => urlencode( 'Leaderboards' ), // the name of our product in EDD
        'url'       => home_url()
      );

      // Call the custom API.
      $response = wp_remote_post( 'https://quizandsurveymaster.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

      // If previous license key was entered
      if ( ! empty( $license ) ) {

        // Prepares data to deactivate changed license
        $api_params = array(
          'edd_action'=> 'deactivate_license',
          'license' 	=> $license,
          'item_name' => urlencode( 'Leaderboards' ), // the name of our product in EDD
          'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( 'http://quizandsurveymaster.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
      }
    }
    $mlwQuizMasterNext->alertManager->newAlert( 'Your settings has been saved successfully! You can now set up your leaderboards using the Leaderboards tab when editing your quiz or survey.', 'success' );
  }

  // Load settings
  $leaderboard_data = get_option( 'qsm_addon_leaderboard_settings', '' );
  $leaderboard_defaults = array(
    'license_key' => ''
  );
  $leaderboard_data = wp_parse_args( $leaderboard_data, $leaderboard_defaults );

  // Show any alerts from saving
  $mlwQuizMasterNext->alertManager->showAlerts();

  ?>
  <form action="" method="post">
    <table class="form-table" style="width: 100%;">
      <tr valign="top">
        <th scope="row"><label for="license_key">Addon License Key</label></th>
        <td><input type="text" name="license_key" id="license_key" value="<?php echo $leaderboard_data["license_key"]; ?>"></td>
      </tr>
    </table>
    <?php wp_nonce_field('leaderboards','leaderboards_nonce'); ?>
    <button class="button-primary">Save Changes</button>
  </form>
  <?php
}
?>
