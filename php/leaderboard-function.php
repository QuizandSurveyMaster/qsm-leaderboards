<?php
/**
 * Handles the actual generation of the leaderboards.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates the leaderboads
 *
 * @since 1.0.0
 * @param int $quiz_id int The ID of the quiz.
 * @return string The HTML of the leaderboard
 */
function qsm_addon_leaderboards_generate( $quiz_id, $top_users ) {

	global $wpdb;
	global $mlwQuizMasterNext;
	$quiz_id = intval( $quiz_id );
        
	// Retrieves template, grading system, and name of quiz.
	$mlwQuizMasterNext->pluginHelper->prepare_quiz( $quiz_id );
	$template = $mlwQuizMasterNext->pluginHelper->get_section_setting( 'quiz_leaderboards', 'template' );
	$grade_system = $mlwQuizMasterNext->pluginHelper->get_section_setting( 'quiz_options', 'system' );
	$form_type = $mlwQuizMasterNext->pluginHelper->get_section_setting( 'quiz_options', 'form_type' );
	$quiz_name = $wpdb->get_var( $wpdb->prepare( "SELECT quiz_name FROM {$wpdb->prefix}mlw_quizzes WHERE deleted='0' AND quiz_id=%d", $quiz_id ) );
                
	// Prepares SQL for results, then retrieve results.
	$sql = "SELECT * FROM {$wpdb->prefix}mlw_results WHERE quiz_id=%d AND deleted='0'";
        if( !empty( $form_type ) && ($form_type == 1 || $form_type == 2) ){            
            //Do nothing
        }else{
            if ( 0 == $grade_system ) {
                    $sql .= ' ORDER BY correct_score DESC';
            }
            if ( 1 == $grade_system ) {
                    $sql .= ' ORDER BY point_score DESC';
            }
            if( 3 == $grade_system ){
                $sql .= ' ORDER BY correct_score, point_score DESC';
            }
        }	
	$sql .= ' LIMIT '.$top_users;        
	$results = $wpdb->get_results( $wpdb->prepare( $sql, $quiz_id ) );

	// Changes variable to quiz name.
	$template = str_replace( '%QUIZ_NAME%' , $quiz_name, $template );

	// Cycles through each result and use name/points for entry in leaderboard.
	$leader_count = 0;
	$users_names_score = '';
	$name_pos = strpos($template,'%QUIZ_USER_NAME%');
	$score_post = strpos($template,'%QUIZ_USER_SCORE%');
	if($name_pos < $score_post){
		$inner_text = get_string_between($template,'%QUIZ_USER_NAME%','%QUIZ_USER_SCORE%');
	}else{
		$inner_text = get_string_between($template,'%QUIZ_USER_SCORE%','%QUIZ_USER_NAME%');
	}
	foreach ( $results as $result ) {
		$leader_count++;

		// Changes name to quiz taker's name.
		$users_names_score .= $result->name.$inner_text;

		// Depending on grading system, use either score or points.
                if( !empty( $form_type ) && ($form_type == 1 || $form_type == 2) ){                                	
										$users_names_score .= "Not graded";
                }else{
                    if ( $grade_system == 0 ) {
											$users_names_score .= $result->correct_score . "%";
                    }
                    if ( $grade_system == 1 ) {
												$users_names_score .= $result->point_score . " Points";
                    }
                    if ( $grade_system == 3 ) {
												$users_names_score .= $result->correct_score . "% OR " . $result->point_score . " Points";
                    }
                }	
								$users_names_score .= '<br>';	
	}
	if($name_pos < $score_post){
		$template = str_replace( "%QUIZ_USER_NAME%".$inner_text.'%QUIZ_USER_SCORE%', $users_names_score, $template );
	}else{
		str_replace( "%QUIZ_USER_SCORE%".$inner_text.'%QUIZ_USER_NAME%', $users_names_score, $template );
	}
	$template = str_replace( "%QUIZ_USER_NAME%", $users_names_score, $template );
	// Removes all variables in case any were missed.
	$template = str_replace( "%QUIZ_NAME%", " ", $template );
	$template = str_replace( "%QUIZ_USER_NAME%", " ", $template );
	$template = str_replace( "%QUIZ_USER_SCORE%", " ", $template );

	// Return template
	return wpautop( $template );
}

/**
 * Get all text between QUIZ_USER_NAME and QUIZ_USER_SCORE from template
 */
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

?>
