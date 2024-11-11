<?php
if(!function_exists('sbgen_add_support_custom_sidebar')) {
    function sbgen_add_support_custom_sidebar() {
        add_theme_support('sbgenerator');
        if (get_theme_support('sbgenerator')) new sbgenerator();
    }

    add_action('after_setup_theme', 'sbgen_add_support_custom_sidebar');
}