<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* The class contains all of the functions for the leaderboard widget.
*
* @return void
* @since 1.0.0
*/
class QSM_Leaderboards_Widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'qsm_leaderboard_widget', 'QSM Leaderboard Widget', array( 'description' => 'A widget to display your leaderboard' ) );
	}

	/**
	 * Widget settings form
	 *
	 * @see WP_Widget::form()
	 *
	 * @since 1.0.0
	 * @param array $instance
	 */
	function form( $instance ) {

		// Prepare title and ID
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
			$quiz = esc_attr( $instance['quiz_id'] );
		} else {
			$title = '';
			$quiz = '';
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

	/**
	 * Sanitize widget settings form data
	 *
	 * @see WP_Widget::update()
	 *
	 * @since 1.0.0
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['quiz_id'] = ( ! empty( $new_instance['quiz_id'] ) ) ? strip_tags( $new_instance['quiz_id'] ) : '';

		return $instance;
	}

	/**
	 * Displays the widget
	 *
	 * @see WP_Widget::widget()
	 * @since 1.0.0
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		echo $args["before_widget"];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo qsm_addon_leaderboards_generate( $instance['quiz_id'] );
		echo $args["after_widget"];
	}
}
?>
