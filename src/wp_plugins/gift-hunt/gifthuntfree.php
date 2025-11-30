<?php
/*
 * Plugin Name:       Gift Hunt
 * Plugin URI:        https://gifthuntplugin.com
 * Description:       Online treasure hunt on your WordPress site
 * Version:           2.0.2
 * Author:            Ecommerce Platforms
 * Author URI:        https://ecommerce-platforms.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gifthuntfree
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

define( "GIFTHUNTFREE_FRONTEND_ENDPOINT", admin_url( "admin-ajax.php" ) );
define( "GIFTHUNTFREE_FRONTEND_ASSETS_FOLDER", plugins_url( "assets/", __FILE__ ) );
define( "GIFTHUNTFREE_VERSION", "2.0.2" );
define( "GIFTHUNTFREE_ICON_SHORTCODE", "ff_gifthunt_icon" );

include "classes/class-gifthuntfree.php";

/**
 * Plugin has been activated
 */
function gifthuntfree_activate() {
  $gifthuntfree = new Gifthuntfree();
  $gifthuntfree->install_gifthunt();
}

/**
 * Plugin has been deactivated
 */
function gifthuntfree_deactivate() {
}

/**
 * Plugin has been uninstalled
 */
function gifthuntfree_uninstall() {
  $gifthuntfree = new Gifthuntfree();
  $gifthuntfree->uninstall_gifthunt();
}

// on activation
register_activation_hook(__FILE__ , "gifthuntfree_activate");

// on deactivation
register_deactivation_hook(__FILE__ , "gifthuntfree_deactivate");

// on uninstall
register_uninstall_hook(__FILE__ , "gifthuntfree_uninstall");

/**
 * Display top level menu
 */
function gifthuntfree_top_level_menu() {
  // Top level menu
  add_menu_page(
    "🎁 Gift Hunt",
    "Gift Hunt",
    "manage_options",
    'gifthuntfree-sessions',
    "gifthuntfree_top_level_menu_display",
    "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik0yLjQ3MzAxIDEzLjU1MzFINDcuNDA1OFYyMC40OTFIMi40NzMwMVYxMy41NTMxWiIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIi8+CjxwYXRoIGQ9Ik03LjA5NzA4IDIwLjVINDIuNzgwOFY0Ny44NTkzSDcuMDk3MDhWMjAuNVoiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMyIvPgo8cGF0aCBkPSJNMzYuNzUwNiAzLjY3MzE2QzM4LjE1OTkgNS4wODI1IDM4LjE1OTkgNy4zNjc1IDM2Ljc1MDYgOC43NzY4NEMzNS45MzIxIDkuNTk1MjkgMzMuODE0NSAxMC45OTUgMzEuNjE3MyAxMS45NDM2QzMwLjUzMDYgMTIuNDEyOCAyOS41MjcxIDEyLjcyNTcgMjguNzM3OCAxMi44MjQxQzI4LjM0NjEgMTIuODczIDI4LjA2NDQgMTIuODYxNCAyNy44Nzg4IDEyLjgyNDNDMjcuNzkwMSAxMi44MDY1IDI3LjczNTggMTIuNzg1NCAyNy43MDY0IDEyLjc3MUMyNy42OTIgMTIuNzYzOSAyNy42ODM0IDEyLjc1ODQgMjcuNjc5MSAxMi43NTUzQzI3LjY3NDkgMTIuNzUyMyAyNy42NzMzIDEyLjc1MDggMjcuNjczMiAxMi43NTA2TDI3LjY3MzIgMTIuNzUwNkMyNy42NzMgMTIuNzUwNSAyNy42NzE1IDEyLjc0ODkgMjcuNjY4NSAxMi43NDQ3QzI3LjY2NTQgMTIuNzQwMyAyNy42NTk5IDEyLjczMTcgMjcuNjUyOCAxMi43MTczQzI3LjYzODMgMTIuNjg3OSAyNy42MTcyIDEyLjYzMzcgMjcuNTk5NSAxMi41NDQ5QzI3LjU2MjQgMTIuMzU5MyAyNy41NTA4IDEyLjA3NzYgMjcuNTk5NiAxMS42ODU5QzI3LjY5OCAxMC44OTY3IDI4LjAxMDkgOS44OTMxOSAyOC40ODAyIDguODA2NDZDMjkuNDI4OCA2LjYwOTI5IDMwLjgyODUgNC40OTE2MSAzMS42NDY5IDMuNjczMTZDMzMuMDU2MyAyLjI2MzgyIDM1LjM0MTIgMi4yNjM4MiAzNi43NTA2IDMuNjczMTZaIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjMiLz4KPHBhdGggZD0iTTEzLjY3MzcgMy42NzM3NUMxNS4wODM0IDIuMjY0MDggMTcuMzY4OSAyLjI2NDA4IDE4Ljc3ODYgMy42NzM3NUMxOS41OTcyIDQuNDkyMzYgMjAuOTk3MSA2LjYxMDQyIDIxLjk0NTkgOC44MDc5NkMyMi40MTUyIDkuODk0ODggMjIuNzI4MiAxMC44OTg2IDIyLjgyNjYgMTEuNjg4QzIyLjg3NTUgMTIuMDc5NyAyMi44NjM5IDEyLjM2MTUgMjIuODI2OCAxMi41NDcyQzIyLjgwOSAxMi42MzYgMjIuNzg3OSAxMi42OTAyIDIyLjc3MzQgMTIuNzE5NkMyMi43NjYzIDEyLjczNDEgMjIuNzYwOCAxMi43NDI3IDIyLjc1NzcgMTIuNzQ3MUMyMi43NTQ4IDEyLjc1MTIgMjIuNzUzMiAxMi43NTI4IDIyLjc1MyAxMi43NTNMMjIuNzUzIDEyLjc1M0MyMi43NTI5IDEyLjc1MzEgMjIuNzUxMyAxMi43NTQ3IDIyLjc0NzEgMTIuNzU3N0MyMi43NDI3IDEyLjc2MDggMjIuNzM0MSAxMi43NjYzIDIyLjcxOTYgMTIuNzczNEMyMi42OTAyIDEyLjc4NzkgMjIuNjM2IDEyLjgwOSAyMi41NDcyIDEyLjgyNjhDMjIuMzYxNSAxMi44NjM5IDIyLjA3OTcgMTIuODc1NSAyMS42ODggMTIuODI2NkMyMC44OTg2IDEyLjcyODIgMTkuODk0OSAxMi40MTUyIDE4LjgwOCAxMS45NDU5QzE2LjYxMDQgMTAuOTk3MSAxNC40OTI0IDkuNTk3MjIgMTMuNjczNyA4Ljc3ODZDMTIuMjY0MSA3LjM2ODk0IDEyLjI2NDEgNS4wODM0MiAxMy42NzM3IDMuNjczNzVaIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjMiLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMCI+CjxyZWN0IHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4K",
    100
  );

  add_submenu_page(
    "gifthuntfree-sessions",
    "Crete New Gift Hunt Session",
    "Add New",
    "manage_options",
    "admin.php?page=gifthuntfree-sessions&p=session-create"
  );

  add_submenu_page(
    "gifthuntfree-sessions",
    "Connect Gift Hunt to Mailchimp",
    "Mailchimp Integration",
    "manage_options",
    "admin.php?page=gifthuntfree-sessions&p=mailchimp-integration"
  );
}

function gifthuntfree_top_level_menu_display() {
  include "views/gifthunt-layout.view.php";
}

function gifthuntfree_settings_menu($links) {
  $gifthuntfree_sessions_link = '<a href="admin.php?page=gifthuntfree-sessions&tab=sessions">Gift Hunt Sessions</a>';
  array_push( $links, $gifthuntfree_sessions_link );

  return $links;
}

/**
 * Display plugin settings menu in the WP sidebar
 */
add_action( "admin_menu", "gifthuntfree_top_level_menu" );
add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), "gifthuntfree_settings_menu" );

/**
 * Load custom CSS for plugin
 */
function gifthuntfree_load_settings_style( $hook ) {
  if ( $hook == "toplevel_page_gifthuntfree-sessions" ) {
    wp_enqueue_style( "gifthuntfree_backend_css", plugins_url( "assets/css/gifthunt.css", __FILE__ ) );
  }

  if ( isset( $_GET["p"] ) && ( $_GET["p"] == "session-edit" || $_GET["p"] == "session-create" ) ) {
    wp_enqueue_style( "gifthuntfree_datepicker_css", plugins_url( "assets/css/datepicker.min.css", __FILE__ ) );
    wp_enqueue_script( "jquery-ui-datepicker" );
    wp_enqueue_script( "gifthuntfree_form__js", plugins_url( "assets/scripts/gifthunt.js", __FILE__ ), "", GIFTHUNTFREE_VERSION, true );
  } else if ( isset( $_GET["p"] ) && $_GET["p"] == "hunters" ) {
    wp_enqueue_script( "gifthuntfree_hunters__js", plugins_url( "assets/scripts/gifthunt-hunters.js", __FILE__ ), "", GIFTHUNTFREE_VERSION, true );
  }
}

add_action( "admin_enqueue_scripts", "gifthuntfree_load_settings_style" );

/**
 * Process backend ajax requests
 */
function gifthuntfree_ajax_action() {
  check_ajax_referer( "gifthuntfree-nonce-string", "nonce", true );
  if (!current_user_can( "manage_options" ) ) {
    wp_die();
  }

  $gifthuntfree = new Gifthuntfree();

  $gifthuntfree_action = $_POST["gifthuntAction"];

  switch ( $gifthuntfree_action ) {
    case "create":
      // create new gifthunt session
      $result = $gifthuntfree->create_gifthunt();
      wp_send_json( $result );
      break;

    case "update":
      // update gifthunt session
      $result = $gifthuntfree->update_gifthunt();
      wp_send_json( $result );
      break;

    case "delete":
      // delete gifthunt session
      $result = $gifthuntfree->delete_gifthunt();
      wp_send_json( $result );
      break;

    case "send_test_mail":
      // send test result mail
      $result = $gifthuntfree->send_test_result_mail();
      wp_send_json( $result );
      break;

    case "delete_hunter":
      // delete selected hunter
      $result = $gifthuntfree->delete_hunter();
      wp_send_json( $result );
      break;

    case "delete_all_hunters":
      // delete all hunters in a selected session
      $result = $gifthuntfree->delete_hunters_all();
      wp_send_json( $result );
      break;

    /**
     * Mailchimp functions
     */
    case "mailchimp_lists":
      // get the list of managed Mailchimp lists
      $result = $gifthuntfree->mailchimp_lists();
      wp_send_json( $result );
      break;

    case "mailchimp_merge_fields":
      // get the merge fields for the selected list
      $result = $gifthuntfree->mailchimp_merge_fields();
      wp_send_json( $result );
      break;

    case "mailchimp_save_settings":
      // save mailchimp integration settings
      $result = $gifthuntfree->mailchimp_save_settings();
      wp_send_json( $result );
      break;

    case "mailchimp_test_integration":
      // test mailchimp integration settings
      $result = $gifthuntfree->mailchimp_integration_test();
      wp_send_json( $result );
      break;

    default:
      break;
  }

  wp_die();
}

add_action( "wp_ajax_gifthuntfree_action", "gifthuntfree_ajax_action" );

/**
 * Process export hunters request
 */
function gifthuntfree_export_hunters() {
  if ( !current_user_can( "manage_options" ) ) {
    wp_die();
  }

  $gifthuntfree = new Gifthuntfree();
  $gifthuntfree->export_hunters();

  die();
}

add_action( "admin_post_export_hunters_free", "gifthuntfree_export_hunters" );

/**
 * Frontend script
 */
function gifthuntfree_frontend_script() {
  $gifthuntfree = new Gifthuntfree();
  $gifthuntfree->display_frontend_script();
  return;
}
add_action( "wp_footer", "gifthuntfree_frontend_script" );

/**
 * Process frontend ajax requests
 */
function gifthuntfree_ajax_action_frontend() {
  check_ajax_referer( "gifthuntfree-nonce-frontend", "nonce", true );
  $gifthuntfree = new Gifthuntfree();

  $gifthuntfree_action = $_POST["gifthuntfreeAction"];

  switch ( $gifthuntfree_action ) {
    case "collect":
      // user filled the data collection form after found a gift
      $result = $gifthuntfree->collect_gift();
      wp_send_json( $result );
      break;

    default:
      break;
  }
  
  wp_die();
}

add_action( "wp_ajax_nopriv_gifthuntfree_ajax_frontend_action", "gifthuntfree_ajax_action_frontend" );
add_action( "wp_ajax_gifthuntfree_ajax_frontend_action", "gifthuntfree_ajax_action_frontend" );

/**
 * Display the icon with shortcode
 */
function gifthuntfree_shortcode_display(){
  $gifthuntfree = new Gifthuntfree();
  $gifthunt_icon = $gifthuntfree->get_gifthunt_icon();

  return $gifthunt_icon;
}

add_shortcode( GIFTHUNTFREE_ICON_SHORTCODE, "gifthuntfree_shortcode_display" );