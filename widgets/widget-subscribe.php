<?php
/**
 * Plugin Name: [CERIS] Widget Most Commented
 * Plugin URI: http://bk-ninja.com/
 * Description: This widget displays the most commented posts with comment count on the left.
 * Version: 1.0
 * Author: BK-Ninja
 * Author URI: http://bk-ninja.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_subscribe' );

function bk_register_widget_subscribe() {
	register_widget( 'bk_widget_subscribe' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_subscribe extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-ceris-widget', 'description' => esc_html__('Displays Subscribe Form.', 'ceris') );

		/* Create the widget. */
		parent::__construct( 'bk_widget_subscribe', esc_html__('[CERIS] Widget Subscribe', 'ceris'), $widget_ops);
	}
    
	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
        
        $widget_opts = array();
        $title       = $instance['title'];
        $shortcode   = $instance['mailchim_shortcode'];
        echo ($before_widget);
        echo '<div class="widget widget-subscribe widget-subscribe--stack-bottom">';
        echo '<div class="widget-subscribe__inner">';
        echo '<div class="subscribe-form">';
        if($shortcode != ''):
        echo do_shortcode($shortcode);
        endif;
        echo '</div><!-- .subscribe-form -->';
        echo '</div><!-- .widget-subscribe__inner -->';
        echo '</div><!-- .widget -->';
        
        /* After widget (defined by themes). */
		echo ($after_widget);
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title']              = $new_instance['title'];
        $instance['mailchim_shortcode'] = $new_instance['mailchim_shortcode'];
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Subscribe Us', 'mailchim_shortcode' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
    <p>
		<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><strong><?php esc_html_e('[Optional] Title:', 'ceris'); ?></strong></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
	</p>
    
    <p>
		<label for="<?php echo esc_attr($this->get_field_id( 'mailchim_shortcode' )); ?>"><strong><?php esc_html_e('Mailchimp Shortcode:', 'ceris'); ?></strong></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('mailchim_shortcode')); ?>" name="<?php echo esc_attr($this->get_field_name('mailchim_shortcode')); ?>" value="<?php if( !empty($instance['mailchim_shortcode']) ) echo esc_attr($instance['mailchim_shortcode']); ?>" />
    </p>
<?php
	}
}
?>
