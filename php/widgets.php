<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* The class contains all of the functions for the leaderboard widget.
*
* @return void
* @since 1.0.0
*/
class QSM_Leaderboards_Widget extends WP_Widget {

   	// constructor
    function __construct() {
        parent::__construct(false, $name = __('Quiz And Survey Master Leaderboard Widget', 'quiz-master-next'));
    }

    // widget form creation
    function form($instance) {
	    // Check values
		if( $instance) {
	     	$title = esc_attr($instance['title']);
	     	$quiz_id = esc_attr($instance['quiz_id']);
		} else {
			$title = '';
			$quiz_id = '';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'quiz-master-next'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('quiz_id'); ?>"><?php _e('Quiz ID', 'quiz-master-next'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('quiz_id'); ?>" name="<?php echo $this->get_field_name('quiz_id'); ?>" type="number" step="1" min="1" value="<?php echo $quiz_id; ?>" />
		</p>
		<?php
	}

    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
      	// Fields
      	$instance['title'] = strip_tags($new_instance['title']);
      	$instance['quiz_id'] = strip_tags($new_instance['quiz_id']);
     	return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );
   		// these are the widget options
   		$title = apply_filters('widget_title', $instance['title']);
   		$quiz_id = $instance['quiz_id'];
    	echo $before_widget;
   		// Display the widget
   		echo '<div class="widget-text wp_widget_plugin_box">';
   		// Check if title is set
   		if ( $title ) {
      		echo $before_title . $title . $after_title;
   		}
   		$quiz_id = intval( $quiz_id );
			echo qsm_addon_leaderboards_generate( $quiz_id );
   		echo '</div>';
   		echo $after_widget;
    }
}
?>
