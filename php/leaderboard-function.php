<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generates the leaderboads
 *
 * @since 1.0.0
 * @param $quiz_id int The ID of the quiz
 * @return string The HTML of the leaderboard
 */
function qsm_addon_leaderboards_generate( $quiz_id ) {

	// Globals
	global $wpdb;
	global $mlwQuizMasterNext;
	$quiz_id = intval( $quiz_id );

	// Retrieve template, grading system, and name of quiz
	$mlwQuizMasterNext->pluginHelper->prepare_quiz( $quiz_id );
	$template = $mlwQuizMasterNext->pluginHelper->get_section_setting( 'quiz_leaderboards', 'template' );
	$grade_system = $mlwQuizMasterNext->pluginHelper->get_section_setting( 'quiz_options', 'system' );
	$quiz_name = $wpdb->get_var( $wpdb->prepare( "SELECT quiz_name FROM {$wpdb->prefix}mlw_quizzes WHERE deleted='0' AND quiz_id=%d", $quiz_id ) );

	// Prepare SQL for results, then retrieve results
	$sql = "SELECT * FROM {$wpdb->prefix}mlw_results WHERE quiz_id=%d AND deleted='0'";
	if ( $grade_system == 0 ) {
		$sql .= " ORDER BY correct_score DESC";
	}
	if ( $grade_system == 1 ) {
		$sql .= " ORDER BY point_score DESC";
	}
	$sql .= " LIMIT 10";
	$results = $wpdb->get_results( $wpdb->prepare( $sql, $quiz_id ) );

	// Change variable to quiz name
	$template = str_replace( "%QUIZ_NAME%" , $quiz_name, $template);

	// Cycle through each result and use name/points for entry in leaderboard
	$leader_count = 0;
	foreach( $results as $result ) {
		$leader_count++;

		// Change name to quiz taker's name
		if ($leader_count == 1) {$template = str_replace( "%FIRST_PLACE_NAME%" , $result->name, $template);}
		if ($leader_count == 2) {$template = str_replace( "%SECOND_PLACE_NAME%" , $result->name, $template);}
		if ($leader_count == 3) {$template = str_replace( "%THIRD_PLACE_NAME%" , $result->name, $template);}
		if ($leader_count == 4) {$template = str_replace( "%FOURTH_PLACE_NAME%" , $result->name, $template);}
		if ($leader_count == 5) {$template = str_replace( "%FIFTH_PLACE_NAME%" , $result->name, $template);}

		// Depending on grading system, use either score or points
		if ( $grade_system == 0 ) {
			if ($leader_count == 1) {$template = str_replace( "%FIRST_PLACE_SCORE%" , $result->correct_score . "%", $template);}
			if ($leader_count == 2) {$template = str_replace( "%SECOND_PLACE_SCORE%" , $result->correct_score . "%", $template);}
			if ($leader_count == 3) {$template = str_replace( "%THIRD_PLACE_SCORE%" , $result->correct_score . "%", $template);}
			if ($leader_count == 4) {$template = str_replace( "%FOURTH_PLACE_SCORE%" , $result->correct_score . "%", $template);}
			if ($leader_count == 5) {$template = str_replace( "%FIFTH_PLACE_SCORE%" , $result->correct_score . "%", $template);}
		}
		if ( $grade_system == 1 ) {
			if ($leader_count == 1) {$template = str_replace( "%FIRST_PLACE_SCORE%" , $result->point_score . " Points", $template);}
			if ($leader_count == 2) {$template = str_replace( "%SECOND_PLACE_SCORE%" , $result->point_score . " Points", $template);}
			if ($leader_count == 3) {$template = str_replace( "%THIRD_PLACE_SCORE%" , $result->point_score . " Points", $template);}
			if ($leader_count == 4) {$template = str_replace( "%FOURTH_PLACE_SCORE%" , $result->point_score . " Points", $template);}
			if ($leader_count == 5) {$template = str_replace( "%FIFTH_PLACE_SCORE%" , $result->point_score . " Points", $template);}
		}
	}

	// Remove all variables in case any were missed
	$template = str_replace( "%QUIZ_NAME%", " ", $template );
	$template = str_replace( "%FIRST_PLACE_NAME%", " ", $template );
	$template = str_replace( "%SECOND_PLACE_NAME%", " ", $template );
	$template = str_replace( "%THIRD_PLACE_NAME%", " ", $template );
	$template = str_replace( "%FOURTH_PLACE_NAME%", " ", $template );
	$template = str_replace( "%FIFTH_PLACE_NAME%", " ", $template );
	$template = str_replace( "%FIRST_PLACE_SCORE%", " ", $template );
	$template = str_replace( "%SECOND_PLACE_SCORE%", " ", $template );
	$template = str_replace( "%THIRD_PLACE_SCORE%", " ", $template );
	$template = str_replace( "%FOURTH_PLACE_SCORE%", " ", $template );
	$template = str_replace( "%FIFTH_PLACE_SCORE%", " ", $template );

	// Return template
	return $template;
}
?>
