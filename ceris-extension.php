<?php
/*
Plugin Name: Ceris Extension
Plugin URI: 
Description: The Next Mag extension (more functional, widgets, etc.)
Author: bkninja
Version: 5.0
Author URI: http://bk-ninja.com
*/
if (!defined('CERIS_FUNCTIONS_PLUGIN_DIR')) {
    define('CERIS_FUNCTIONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-posts-list.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-most-commented.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-review-list.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-social-counters.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-subscribe.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-category-tiles.php");
include(CERIS_FUNCTIONS_PLUGIN_DIR."/widgets/widget-posts-slider.php");

function check_activate_plugin() {
    if ( is_plugin_active( 'redux-framework/redux-framework.php' )) {
        deactivate_plugins('redux-framework/redux-framework.php');
    }
    if ( is_plugin_active( 'mb-term-meta/mb-term-meta.php' )) {
        deactivate_plugins('mb-term-meta/mb-term-meta.php');
    }
    if ( is_plugin_active( 'meta-box-group/meta-box-group.php' )) {
        deactivate_plugins('meta-box-group/meta-box-group.php');
    }
    if ( is_plugin_active( 'meta-box-conditional-logic/meta-box-conditional-logic.php' )) {
        deactivate_plugins('meta-box-conditional-logic/meta-box-conditional-logic.php');
    }
    if ( is_plugin_active( 'mb-settings-page/mb-settings-page.php' )) {
        deactivate_plugins('mb-settings-page/mb-settings-page.php');
    }
    if ( is_plugin_active( 'ceris-sidebar-generator/ceris-sidebar-generator.php' )) {
        deactivate_plugins('ceris-sidebar-generator/ceris-sidebar-generator.php');
    }
}
add_action( 'admin_init', 'check_activate_plugin' );

if ( !class_exists( 'ReduxFramework' ) && file_exists( plugin_dir_path( __FILE__ ) . '/redux-framework/redux-core/framework.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/redux-framework/redux-core/framework.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/mb-term-meta/mb-term-meta.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/mb-term-meta/mb-term-meta.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/meta-box-group/meta-box-group.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/meta-box-group/meta-box-group.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/meta-box-conditional-logic/meta-box-conditional-logic.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/meta-box-conditional-logic/meta-box-conditional-logic.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/mb-settings-page/mb-settings-page.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/mb-settings-page/mb-settings-page.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/ceris-sidebar-generator/ceris-sidebar-generator.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/ceris-sidebar-generator/ceris-sidebar-generator.php' );
}

if ( ! function_exists( 'bk_contact_data' ) ) {  
    function bk_contact_data($contactmethods) {
        unset($contactmethods['aim']);
        unset($contactmethods['yim']);
        unset($contactmethods['jabber']);
        $contactmethods['publicemail'] = 'Public Email';
        $contactmethods['twitter'] = 'Twitter URL';
        $contactmethods['facebook'] = 'Facebook URL';
        $contactmethods['youtube'] = 'Youtube Username';
        return $contactmethods;
    }
}
add_filter('user_contactmethods', 'bk_contact_data');

/**-------------------------------------------------------------------------------------------------------------------------
 * remove redux sample config & notice
 */
if ( ! function_exists( 'ceris_redux_remove_notice' ) ) {
	function ceris_redux_remove_notice() {
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
		}
	}
	add_action( 'redux/loaded', 'ceris_redux_remove_notice' );
}
if ( ! function_exists( 'bk_set__cookie' ) ) {
    function bk_set__cookie(){
        if (class_exists('ceris_core')) {
            $ceris_option = ceris_core::bk_get_global_var('ceris_option');
            $cookietime = $ceris_option['bk-post-view--cache-time'];
            //echo (preg_replace('/[^A-Za-z0-9]/', '', $_SERVER["REQUEST_URI"]));
            $bk_uri = explode('/', $_SERVER["REQUEST_URI"]);
            $bkcookied = 0;
            if($bk_uri[count($bk_uri) - 1] !== '') {
                $cookie_name = preg_replace('/[^A-Za-z0-9]/', '', $bk_uri[count($bk_uri) - 1]);
            }else {
                $cookie_name = preg_replace('/[^A-Za-z0-9]/', '', $bk_uri[count($bk_uri) - 2]);
            }
            if(!isset($_COOKIE[$cookie_name])) {
                setcookie($cookie_name, '1', time() + $cookietime);  /* expire in 1 hour */
                $bkcookied = 1;
            }else {
                $bkcookied = 0;
            }
            return $bkcookied;
        }
    }
}
/**-------------------------------------------------------------------------------------------------------------------------
 * ceris_extension_single_entry_interaction
 */
if ( ! function_exists( 'ceris_extension_single_entry_interaction' ) ) {
	function ceris_extension_single_entry_interaction($postID) {
	   ?>
        <div class="entry-interaction entry-interaction--horizontal">
        	<div class="entry-interaction__left">
        		<div class="post-sharing post-sharing--simple">
        			<ul>
        				<?php echo Ceris_Single::bk_entry_interaction_share($postID);?>
        			</ul>
        		</div>
        	</div>
        
        	<div class="entry-interaction__right">
        		<?php echo Ceris_Single::bk_entry_interaction_comments($postID);?>
        	</div>
        </div>
    <?php
    }
}

/**-------------------------------------------------------------------------------------------------------------------------
 * ceris_extension_single_share
 */
if ( ! function_exists( 'ceris_extension_single_share' ) ) {
	function ceris_extension_single_share($postID) {
	   ?>
		<ul class="list-unstyled list-horizontal">
			<?php echo Ceris_Single::bk_entry_share($postID);?>
		</ul>	
    <?php
    }
}

/**-------------------------------------------------------------------------------------------------------------------------
 * ceris_extension_single_entry_interaction__sticky_share_box
 */
if ( ! function_exists( 'ceris_extension_single_entry_interaction__sticky_share_box' ) ) {
	function ceris_extension_single_entry_interaction__sticky_share_box($postID, $class= '') {
    	echo Ceris_Single::bk_entry_interaction_share_svg($postID, $class);
    }
}

// disable srcset on frontend
function disable_wp_responsive_images() {
	
	return 1;
	
}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');

// disable 768px image generation
function disable_wp_responsive_image_sizes($sizes) {
	
	unset($sizes['medium_large']);
	
	return $sizes;
	
}
add_filter('intermediate_image_sizes_advanced', 'disable_wp_responsive_image_sizes');

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
?>
