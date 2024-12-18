<?php
/**
 * Plugin Name: [CERIS] Widget Review List
 * Plugin URI: http://bk-ninja.com/
 * Description: This widget displays the review posts with thumbnails in the tabs.
 * Version: 1.0
 * Author: BK-Ninja
 * Author URI: http://bk-ninja.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_review_list' );

function bk_register_widget_review_list() {
	register_widget( 'bk_widget_review_list' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_review_list extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-ceris-widget', 'description' => esc_html__('Displays Review Posts List.', 'ceris') );

		/* Create the widget. */
		parent::__construct( 'bk_widget_review_list', esc_html__('[CERIS] Widget Review List', 'ceris'), $widget_ops);
	}
    
	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
        
        $widget_opts = array();
        $title = $instance['title'];
        $headingStyle = $instance['heading_style'];
        $widgetModule = $instance['widget_module'];
        
        if($headingStyle) {
            $headingClass = Ceris_Core::bk_get_widget_heading_class($headingStyle);
        }else {
            $headingClass = '';
        }
        
        $widget_opts['offset']      = !empty( $instance['offset'] )     ? $instance['offset'] : 0;
        $widget_opts['category_id'] = !empty( $instance['category_id'] )? $instance['category_id'] : 0;
        $widget_opts['category_ids'] = !empty( $instance['category_ids'] )? $instance['category_ids'] : '';
        $widget_opts['tags']        = !empty( $instance['tags'] )       ? $instance['tags'] : '';
        $widget_opts['entries']     = !empty( $instance['entries'] )    ? $instance['entries'] : 4;	
        $widget_opts['orderby']     = !empty( $instance['orderby'] )    ? $instance['orderby'] : 'date';
        $widget_opts['widget_type'] = 'review';	
        
        $widgetClass = 'atbs-ceris-widget-reviews-list';
        
        $the_query =  Ceris_Widget::bk_widget_query($widget_opts);
        
        echo ($before_widget);
        
        echo '<div class="'.$widgetClass.'">';
        
		if ( $title ) { echo Ceris_Widget::bk_get_widget_heading($title, $headingClass); }
        
		if ( $the_query -> have_posts() ) :
            switch ( $widgetModule ) {
                case 'review-posts-a' :
                    echo '<ul class="posts-list list-space-sm list-unstyled">';
                    echo Ceris_Widget::bk_review_posts_a($the_query);
                    echo '</ul>';
                    break;
                
                case 'review-posts-b' :
                    echo '<ol class="posts-list list-space-sm list-unstyled">';
                    echo Ceris_Widget::bk_review_posts_b($the_query);
                    echo '</ol>';
                    break;
                    
                case 'review-posts-c' :
                    echo '<ul class="posts-list list-space-sm list-unstyled">';
                    echo Ceris_Widget::bk_review_posts_c($the_query);
                    echo '</ul>';
                    break;
                    
                case 'review-posts-d' :
                    echo '<ol class="posts-list list-space-sm list-unstyled">';
                    echo Ceris_Widget::bk_review_posts_d($the_query);
                    echo '</ol>';
                    break;
                                        
                default :
                    echo '<ul class="posts-list list-space-sm list-unstyled">';
                    echo Ceris_Widget::bk_review_posts_a($the_query);
                    echo '</ul>';
                    break;
            }
		endif;
        ?>
    <?php
        echo '</div><!-- End Widget Module-->';
        /* After widget (defined by themes). */
		echo ($after_widget);
        wp_reset_postdata();
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title']      = $new_instance['title'];
        $instance['heading_style'] = strip_tags($new_instance['heading_style']);
        $instance['widget_module'] = strip_tags($new_instance['widget_module']);
		$instance['entries']    = intval(strip_tags($new_instance['entries']));
        $instance['offset']     = intval(strip_tags($new_instance['offset']));
        $instance['category_id']= strip_tags($new_instance['category_id']);
        $instance['category_ids']= strip_tags($new_instance['category_ids']);
        $instance['tags']       = strip_tags($new_instance['tags']);
        $instance['orderby']    = strip_tags($new_instance['orderby']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Posts List', 'heading_style' => 'default', 'widget_module' => 'indexed-posts-a', 'entries' => 5, 'offset' => 0, 'category_id' => 'all', 'category_ids' => '', 'tags' => '', 'orderby' => 'date');
		$instance = wp_parse_args((array) $instance, $defaults);
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
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'widget_module' )); ?>"><?php esc_attr_e('Widget Module:', 'ceris'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'widget_module' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'widget_module' )); ?>" >
			    <option value="review-posts-a" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'review-posts-a' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Review Posts A', 'ceris'); ?></option>
			    <option value="review-posts-b" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'review-posts-b' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Review Posts B', 'ceris'); ?></option>
			    <option value="review-posts-c" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'review-posts-c' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Review Posts C', 'ceris'); ?></option>
			    <option value="review-posts-d" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'review-posts-d' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Review Posts D', 'ceris'); ?></option>
		    </select>
	    </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'entries' )); ?>"><strong><?php esc_html_e('[Optional] Number of entries to display: ', 'ceris'); ?></strong></label>
		<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('entries')); ?>" name="<?php echo esc_attr($this->get_field_name('entries')); ?>" value="<?php echo esc_attr($instance['entries']); ?>"/></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'offset' )); ?>"><strong><?php esc_html_e('[Optional] Offet Posts number: ', 'ceris'); ?></strong></label>
		<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('offset')); ?>" name="<?php echo esc_attr($this->get_field_name('offset')); ?>" value="<?php echo esc_attr($instance['offset']); ?>" /></p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id('category_id')); ?>"><strong><?php esc_html_e('Filter by Category: ','ceris');?></strong></label> 
			<select id="<?php echo esc_attr($this->get_field_id('category_id')); ?>" name="<?php echo esc_attr($this->get_field_name('category_id')); ?>" class="widefat categories">
				<option value='all' <?php if ('all' == $instance['category_id']) echo 'selected="selected"'; ?>><?php esc_html_e( 'All Categories', 'ceris' ); ?></option>
				<?php $categories = get_categories('hide_empty=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo esc_attr($category->term_id); ?>' <?php if ($category->term_id == $instance['category_id']) echo 'selected="selected"'; ?>><?php echo esc_attr($category->cat_name); ?></option>
				<?php } ?>
			</select>
		</p> 
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>"><?php esc_attr_e('[Optional] Multiple Category: (Separate category ids by the comma. e.g. 1,2):','ceris') ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'category_ids' )); ?>" value="<?php if( !empty($instance['category_ids']) ) echo esc_attr($instance['category_ids']); ?>" />
	    </p>
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>"><?php esc_attr_e('[Optional] Tags(Separate tags by the comma. e.g. tag1,tag2):','ceris') ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tags' )); ?>" value="<?php if( !empty($instance['tags']) ) echo esc_attr($instance['tags']); ?>" />
	    </p>
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>"><?php esc_attr_e('Order By:', 'ceris'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'orderby' )); ?>" >
			    <option value="date" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'date' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Latest Review', 'ceris'); ?></option>
			    <option value="top_review" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'top_review' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Best Review', 'ceris'); ?></option>
			    <option value="rand" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'rand' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Random', 'ceris'); ?></option>
			    <option value="speed_reads" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'speed_reads' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Speed Reads', 'ceris'); ?></option>
			    <option value="alphabetical_asc" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'alphabetical_asc' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Alphabetical A->Z', 'ceris'); ?></option>
			    <option value="alphabetical_decs" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'alphabetical_decs' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Alphabetical Z->A', 'ceris'); ?></option>
		    </select>
	    </p>       
<?php
	}
}
?>
