<?php

function qsm_addon_leaderboards_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'mlw_quiz' => 0
	), $atts));
	$mlw_quiz_id = intval( $mlw_quiz );
	$mlw_quiz_leaderboard_display = "";

	// Do leaderboard function
}
?>
