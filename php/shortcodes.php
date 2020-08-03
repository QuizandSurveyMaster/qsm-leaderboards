<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Converts shortcode to HTML
 *
 * @since 1.0.0
 * @param $atts Array of shortcode parameters
 * @return string The HTML for the shortcode
 */
function qsm_addon_leaderboards_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'quiz' => 0,
		'mlw_quiz' => 0,
		'top_users' => 5,
	), $atts));
	$quiz_id = $mlw_quiz !== 0 ? intval( $mlw_quiz ) : intval( $quiz );

	return qsm_addon_leaderboards_generate( $quiz_id, $top_users );
}
?>
