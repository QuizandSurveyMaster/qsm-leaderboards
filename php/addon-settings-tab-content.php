<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers your tab in the addon  settings page
 *
 * @since 0.1.0
 * @return void
 */
function qsm_addon_xxxxx_register_addon_settings_tabs() {
  global $mlwQuizMasterNext;
  if ( ! is_null( $mlwQuizMasterNext ) && ! is_null( $mlwQuizMasterNext->pluginHelper ) && method_exists( $mlwQuizMasterNext->pluginHelper, 'register_quiz_settings_tabs' ) ) {
    $mlwQuizMasterNext->pluginHelper->register_addon_settings_tab( "Plugin Name", 'qsm_addon_xxxxx_addon_settings_tabs_content' );
  }
}

/**
 * Generates the content for your addon settings tab
 *
 * @since 0.1.0
 * @return void
 * @todo Replace the xxxxx with your addon's name
 */
function qsm_addon_xxxxx_addon_settings_tabs_content() {

  //Enqueue your scripts and styles
  wp_enqueue_script( 'plugin_name_admin_script', plugins_url( '../js/plugin-name-admin.js' , __FILE__ ), array( 'jquery' ) );
  wp_enqueue_style( 'plugin_name_admin_style', plugins_url( '../css/plugin-name-admin.css' , __FILE__ ) );

	global $mlwQuizMasterNext;

  //If nonce is correct, update settings from passed input
  if ( isset( $_POST["analysis_nonce"] ) && wp_verify_nonce( $_POST['analysis_nonce'], 'analysis') ) {

    // Load previous license key
    $analysis_data = get_option( 'qsm_addon_analysis_settings', '' );
    if ( isset( $logic_data["license_key"] ) ) {
      $license = trim( $analysis_data["license_key"] );
    } else {
      $license = '';
    }

    // Save settings
    $saved_license = sanitize_text_field( $_POST["license_key"] );
    $analysis_data = array(
      'license_key' => $saved_license
    );
    update_option( 'qsm_addon_analysis_settings', $analysis_data );

    // Checks to see if the license key has changed
    if ( $license != $saved_license ) {

      // Prepares data to activate the license
      $api_params = array(
        'edd_action'=> 'activate_license',
        'license' 	=> $saved_license,
        'item_name' => urlencode( 'Reporting & Analysis' ), // the name of our product in EDD
        'url'       => home_url()
      );

      // Call the custom API.
      $response = wp_remote_post( 'http://quizandsurveymaster.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

      // If previous license key was entered
      if ( ! empty( $license ) ) {

        // Prepares data to deactivate changed license
        $api_params = array(
          'edd_action'=> 'deactivate_license',
          'license' 	=> $license,
          'item_name' => urlencode( 'Reporting & Analysis' ), // the name of our product in EDD
          'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( 'http://quizandsurveymaster.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
      }
    }
    $mlwQuizMasterNext->alertManager->newAlert( 'Your settings has been saved successfully! You can now analyze your results on the Results page.', 'success' );
  }

  // Load settings
  $analysis_data = get_option( 'qsm_addon_analysis_settings', '' );
  $analysis_defaults = array(
    'license_key' => ''
  );
  $analysis_data = wp_parse_args( $analysis_data, $analysis_defaults );

  // Show any alerts from saving
  $mlwQuizMasterNext->alertManager->showAlerts();

  ?>
  <form action="" method="post">
    <table class="form-table" style="width: 100%;">
      <tr valign="top">
        <th scope="row"><label for="license_key">Addon License Key</label></th>
        <td><input type="text" name="license_key" id="license_key" value="<?php echo $analysis_data["license_key"]; ?>"></td>
      </tr>
    </table>
    <?php wp_nonce_field('analysis','analysis_nonce'); ?>
    <button class="button-primary">Save Changes</button>
  </form>
  <?php
}
?>
