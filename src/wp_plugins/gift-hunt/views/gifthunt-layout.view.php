<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

/**
 * Display extension subpage
 */
$gifthunt_subpage = "sessions";

if ( isset ( $_GET["p"] ) ) {
  $gifthunt_subpage = sanitize_title( $_GET["p"] );
}

$gifthunt = new Gifthuntfree();

$path = "gifthunt-" . $gifthunt_subpage . ".view.php";

// get default Mailchimp integration settings
$mailchimp_default_settings = $gifthunt->get_mailchimp_default_settings();

switch ( $gifthunt_subpage ) {
  /**
   * List gifthunt sessions
   */
  case "sessions":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      /**
       * Get gifthunt sessions from the database
       */
      $gifthunt_sessions = $gifthunt->get_gifthunt_list();
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  /**
   * Create new session
   */
  case "session-create":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  /**
   * Edit session
   */
  case "session-edit":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      $gifthunData = $gifthunt->get_gifthunt_data( intval( $_GET["id"] ) );
      if ( !isset( $gifthunData->id ) ) {
        // display 404
        include "gifthunt-404.view.php";
        break;
      }
      
      $gifthunt = $gifthunData;
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  /**
   * List hunters
   */
  case "hunters":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      $gifthunData = $gifthunt->get_gifthunt_data( intval( $_GET["id"] ) );
      if ( ! isset( $gifthunData->id ) ) {
        // display 404
        include "gifthunt-404.view.php";
        break;
      }

      $gifthuntHunters = $gifthunt->get_hunters( intval( $_GET["id"] ) );

      $gifthunt = $gifthunData;
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  /**
   * Mailchimp Integration settings
   */
  case "mailchimp-integration":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  /**
   * Mailchimp log list - for development purposes
   */
  case "mailchimp-log":
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      $gifthunt_mailchimp_log = $gifthunt->get_mailchimp_log();
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;

  default:
    if ( file_exists( dirname( __FILE__ ) . "/" . $path ) ) {
      include $path;
    } else {
      // display 404
      include "gifthunt-404.view.php";
    }
    break;
}