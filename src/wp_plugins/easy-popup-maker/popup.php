<?php
/*
 * Plugin Name: Easy Popup Window Maker
 * Description: create and manage powerful promotion modal popups for your WordPress blog or website. Powerful and easy to use.
 * Version: 1.3
 * Author: wp-buy
 * Text Domain: CPWPWM
 * Domain Path: /languages/
 * Author URI: https://wp-buy.com
 * License: GPL2
 */


define( 'CPWPWM_DIR', plugin_dir_path( __FILE__ ) );
define( 'CPWPWM_PLUGIN_URL', plugin_dir_url(__FILE__) );


function CPWPWM_replace_custom_css($prefix = '' ,$css ){

    $parts = explode('}', $css);
    foreach ($parts as &$part) {
        if (empty($part)) {
            continue;
       }

        $subParts = explode(',', $part);
        foreach ($subParts as &$subPart) {
            $subPart = $prefix . ' ' . trim($subPart);
        }
    
        $part = implode(', ', $subParts);
    }
    $prefixedCss = implode("}\n", $parts);
    return $prefixedCss;
}


// load translation file
add_action( 'init', 'CPWPWM_load_textdomain' );
function CPWPWM_load_textdomain() {
  load_plugin_textdomain( 'CPWPWM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

require_once( CPWPWM_DIR . '/admin/post_type.php' );
require_once( CPWPWM_DIR . '/admin/crud.php' );
require_once( CPWPWM_DIR . '/view.php' );





if (!function_exists('CPWPWM_filter_action_links')) {
    function CPWPWM_filter_action_links($links)
    {
        $links['settings'] = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=CPWPWM_DATA'), __('My Popups', 'CPWPWM'));
        return $links;
    }

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'CPWPWM_filter_action_links', 10, 1);
}
