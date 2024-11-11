<?php
/**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init', 'ceris_register_instagram_widget');
function ceris_register_instagram_widget(){
	register_widget('ceris_instagram');
}
class ceris_instagram extends WP_Widget {
    
/**
 * Widget setup.
 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-ceris-widget widget--instagram', 'description' => esc_html__('Displays Instagram Gallery.', 'ceris') );

		/* Create the widget. */
		parent::__construct( 'ceris_instagram', esc_html__('[CERIS]: Instagram', 'ceris'), $widget_ops);
	}
    function widget( $args, $instance ) {
		extract($args);
        $title = $instance['title'];
        $headingStyle = $instance['heading_style'];
        $userid = apply_filters('userid', $instance['userid']);
    	$amount = apply_filters('instagram_image_amount', $instance['image_amount']);
        if($headingStyle) {
            $headingClass = Ceris_Core::bk_get_widget_heading_class($headingStyle);
        }else {
            $headingClass = '';
        }	
        echo $before_widget; 
        
        if ( $title ) { echo Ceris_Widget::bk_get_widget_heading($title, $headingClass); }
        
		// Pulls and parses data.
        
        $photos_arr = array();
            
		$search_for['username'] = $userid;
    	$photos_arr = Ceris_Widget::ceris_get_instagram( $search_for, $amount, $amount, false );
        
		?>
        <div class="widget__content ">
        	<ul class="list-unstyled clearfix instagram-list">
        		<?php
        			foreach($photos_arr as $photo)
        			{
        		?>
        			<li class="instagram-item"><a target="_blank" href="<?php echo esc_url($photo['link']); ?>"><img src="<?php echo esc_url($photo['large']); ?>" alt="<?php echo esc_attr($photo['description']); ?>" /></a></li>
        		<?php
        			}
        		?>
        	</ul>	
        </div>
        								
        <?php echo $after_widget; ?>
        			 
        <?php }	

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {	
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
      /* Set up some default widget settings. */
      $defaults = array( 'title' => '', 'heading_style' => 'default', 'userid' => '', 'image_amount' => '');
      $instance = wp_parse_args( (array) $instance, $defaults );	

      $title = esc_attr($instance['title']);
			$userid = esc_attr($instance['userid']);
			$amount = esc_attr($instance['image_amount']);	
    ?>
    <p>
		<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><strong><?php esc_html_e('[Optional] Title:', 'ceris'); ?></strong></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
	</p>
    <p>
	    <label for="<?php echo esc_attr($this->get_field_id( 'heading_style' )); ?>"><?php esc_attr_e('Heading Style:', 'ceris'); ?></label>
	    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'heading_style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'heading_style' )); ?>" >
		    <option value="default" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'default' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Default - From Theme Option', 'ceris'); ?></option>
            <option value="line" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'line' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Line', 'ceris'); ?></option>
		    <option value="no-line" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'no-line' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading No Line', 'ceris'); ?></option>
		    <option value="line-under" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'line-under' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Line Under', 'ceris'); ?></option>
		    <option value="center" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'center' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Center', 'ceris'); ?></option>
		    <option value="line-around" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'line-around' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Line Around', 'ceris'); ?></option>
		</select>
    </p>
    <p><label for="<?php echo $this->get_field_id('userid'); ?>"><?php esc_html_e( 'Instagram user ID:', 'ceris'); ?> <input class="widefat" id="<?php echo $this->get_field_id('userid'); ?>" name="<?php echo $this->get_field_name('userid'); ?>" type="text" value="<?php echo $userid; ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('image_amount'); ?>"><?php esc_html_e( 'Images count:', 'ceris'); ?> <input class="widefat" id="<?php echo $this->get_field_id('image_amount'); ?>" name="<?php echo $this->get_field_name('image_amount'); ?>" type="text" value="<?php echo $amount; ?>" /></label></p>	

<?php }

}
?>