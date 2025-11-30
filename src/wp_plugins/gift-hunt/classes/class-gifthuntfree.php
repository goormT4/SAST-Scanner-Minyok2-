<?php
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}


class Gifthuntfree {
  private $gifthunt_sessions_table;
  private $gifthunt_gifts_table;
  private $gifthunt_hunters_table;
  private $gifthunt_mailchimp_settings_table;
  private $gifthunt_mailchimp_logs_table;

  private $db;
  private $prefix;

  public $id = 0;
  public $name;
  public $active = false;
  public $visible_from;
  public $visible_to;
  public $icon = 1;
  public $custom_icon = "";
  public $icon_placement = "random";
  public $icon_animation = "pop";
  public $display_type = "time";
  public $time_to_display = 90;
  public $pageview_to_display = 5;
  public $allow_multiple_collect = false;
  public $visible_to_visitors = "every"; // every: visible to every visitor, loggedin: visible to logged in visitors, anonym: visible to anonym - not logged in - visitors
  public $session_type = "default";
  public $gift_type = "oneMany";
  public $gifts;
  public $popup_title = "You just found a gift";
  public $popup_body = "This is your lucky day because you just found a hidden gift on our website. Fill the form below to claim your prize.";
  public $popup_button = "Submit";
  public $popup_submit_body = "Thank you for filling the form. We've sent you an email with your gift and you can also find it below.";
  public $popup_design = "default";
  public $custom_popup_content = "You just found a gift";
  public $custom_popup_close_button_text = "Close";
  public $form_collect_first_name = true;
  public $form_collect_last_name = true;
  public $form_collect_additional_information = false;
  public $form_label_first_name = "First name";
  public $form_label_last_name = "Last name";
  public $form_label_email = "Email address";
  public $form_label_additional_information = "Additional information";
  public $form_label_legal = "I have read and agree to the Privacy Policy and Terms of Service";
  public $form_legal_url;
  public $success_email_send = 1;
  public $success_email_sender_name;
  public $success_email_sender_email;
  public $success_email_subject = "🎁 Here comes your gift";
  public $success_email_body = "Hi %firstname%,\n\nthe gift you just found on our website is the following:\n%gift%\n\nMake sure you keep this email in a good place so you'll find the gift when you need it. If you have any questions, don't hesitate to get in touch. For contact information, visit our website.\n\nHave a great day.";
  public $created;
  public $updated;

  public function __construct() {
    global $wpdb;

    $this->db = $wpdb;
    $this->prefix = $wpdb->prefix;

    $this->gifthunt_sessions_table = $wpdb->prefix . "ff_gifthuntfree_sessions";
    $this->gifthunt_gifts_table = $wpdb->prefix . "ff_gifthuntfree_gifts";
    $this->gifthunt_hunters_table = $wpdb->prefix . "ff_gifthuntfree_hunters";
    $this->gifthunt_mailchimp_settings_table = $wpdb->prefix . "ff_mailchimp_settings";
    $this->gifthunt_mailchimp_logs_table = $wpdb->prefix . "ff_mailchimp_logs";

    $date = new DateTime("now");
    $this->name = "Gift Hunt Session " . $date->format( "Y-m-d" );

    $this->visible_from = $date->format( "Y-m-d" );

    $date->add( new DateInterval("P30D") );
    $this->visible_to = $date->format( "Y-m-d" );

    $this->success_email_sender_name = get_bloginfo( "name" );
    $this->success_email_sender_email = get_option( "admin_email" );

    $this->form_legal_url = get_option( "url" );
  }

  /**
   * Create necessary data tables after Gifthunt installed
   */
  public function install_gifthunt() {
    if ( !current_user_can( "manage_options" ) ) {
      wp_die( "Unauthorized user" );
    }

    $sessions_table_name = $this->gifthunt_sessions_table;
    $gifts_table_name = $this->gifthunt_gifts_table;
    $hunters_table_name = $this->gifthunt_hunters_table;
    $mailchimp_settings_table_name = $this->gifthunt_mailchimp_settings_table;
    $mailchimp_logs_table_name = $this->gifthunt_mailchimp_logs_table;

    if ( !function_exists( "dbDelta" ) ) { 
      require_once ABSPATH . "/wp-admin/includes/upgrade.php"; 
    }

    /**
     * Create sessions table
     */
    $sessions_table_create_query = "CREATE TABLE " . $sessions_table_name . " ( 
      id int(11) NOT NULL auto_increment ,
      name varchar(250) NOT NULL ,
      active boolean NOT NULL ,
      visible_from date NOT NULL ,
      visible_to date NOT NULL ,
      icon varchar(250) NOT NULL ,
      custom_icon text ,
      icon_placement varchar(20) NOT NULL,
      icon_animation varchar(20) NOT NULL,
      display_type varchar(10) NOT NULL ,
      time_to_display int(5) NOT NULL ,
      pageview_to_display int(5) NOT NULL ,
      allow_multiple_collect boolean NOT NULL ,
      visible_to_visitors varchar(10) ,
      session_type varchar(20) NOT NULL ,
      custom_popup_content text NOT NULL ,
      custom_popup_close_button_text varchar(70) ,
      gift_type varchar(10) NOT NULL ,
      popup_title varchar(250) NOT NULL ,
      popup_body text NOT NULL ,
      popup_button varchar(100) NOT NULL ,
      popup_submit_body text NOT NULL ,
      popup_design varchar(20) NOT NULL,
      form_collect_first_name boolean NOT NULL ,
      form_collect_last_name boolean NOT NULL ,
      form_collect_additional_information boolean NOT NULL ,
      form_label_first_name varchar(100) NOT NULL ,
      form_label_last_name varchar(100) NOT NULL ,
      form_label_email varchar(100) NOT NULL ,
      form_label_additional_information varchar(100) NOT NULL ,
      form_label_legal varchar(250) NOT NULL ,
      form_legal_url varchar(250) NOT NULL ,
      success_email_send boolean NOT NULL DEFAULT 1 ,
      success_email_sender_name varchar(100) NOT NULL ,
      success_email_sender_email varchar(100) NOT NULL ,
      success_email_subject varchar(150) NOT NULL ,
      success_email_body text NOT NULL ,
      created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
      updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
      PRIMARY KEY  (id)
      )
      CHARSET=utf8mb4
      COLLATE utf8mb4_unicode_ci";

    dbDelta( $sessions_table_create_query );

    /**
     * Create gifts table
     */
    $gifts_table_create_query = "CREATE TABLE " . $gifts_table_name . " (
      id int(11) NOT NULL auto_increment,
      session_id int(11) NOT NULL,
      gift text NOT NULL,
      status varchar(10) NOT NULL,
      created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
      )
      CHARSET=utf8mb4
      COLLATE utf8mb4_unicode_ci";

    dbDelta( $gifts_table_create_query );

    /**
     * Create hunters table
     */
    $hunters_table_create_query = "CREATE TABLE " . $hunters_table_name . " (
      id int(11) NOT NULL auto_increment , 
      session_id int(11) NOT NULL , 
      first_name varchar(100) NOT NULL , 
      last_name varchar(100) NOT NULL , 
      email varchar(100) NOT NULL , 
      additional_information text NOT NULL , 
      gift text NOT NULL , 
      gift_id int(11) NOT NULL , 
      stored_in_mailchimp int(1) , 
      created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , 
      PRIMARY KEY  (id)
      )
      CHARSET=utf8mb4
      COLLATE utf8mb4_unicode_ci";

    dbDelta( $hunters_table_create_query );

    /**
     * Create Mailchimp settings table
     */
    $mailchimp_settings_create_query = "CREATE TABLE " . $mailchimp_settings_table_name . " (
      id int(11) NOT NULL auto_increment ,
      session_id int(11) NOT NULL ,
      list_id varchar(70) NOT NULL ,
      list_name varchar(250) NOT NULL ,
      active tinyint(1) NOT NULL ,
      type varchar(10) NOT NULL ,
      api_key varchar(70) NOT NULL ,
      fname varchar(20) NOT NULL ,
      lname varchar(20) NOT NULL ,
      additional varchar(20) NOT NULL ,
      PRIMARY KEY  (id)
      )
      CHARSET=utf8mb4
      COLLATE utf8mb4_unicode_ci";

    dbDelta( $mailchimp_settings_create_query );

    /**
     * Create Mailchimp logs table
     */
    $mailchimp_logs_create_query = "CREATE TABLE " . $mailchimp_logs_table_name . " (
      id int(11) NOT NULL auto_increment ,
      hunter_id int(11) NOT NULL ,
      log text NOT NULL ,
      PRIMARY KEY  (id)
      )
      CHARSET=utf8mb4
      COLLATE utf8mb4_unicode_ci";

    dbDelta( $mailchimp_logs_create_query );
  }

  /**
   * Remove created database tables on uninstall
   */
  public function uninstall_gifthunt() {
    if (!current_user_can("manage_options")) {
      wp_die("Unauthorized user");
    }

    // remove gifts table
    $this->db->query( "DROP TABLE IF EXISTS `" . $this->gifthunt_gifts_table . "`" );

    // remove hunters table
    $this->db->query( "DROP TABLE IF EXISTS `" . $this->gifthunt_hunters_table . "`" );

    // remove sessions table
    $this->db->query( "DROP TABLE IF EXISTS `" . $this->gifthunt_sessions_table . "`" );

    // remove mailchimp settings table
    $this->db->query( "DROP TABLE IF EXISTS `" . $this->gifthunt_mailchimp_settings_table . "`" );

    // remove mailchimp logs table
    $this->db->query( "DROP TABLE IF EXISTS `" . $this->gifthunt_mailchimp_logs_table . "`" );

    return;
  }

  /**
   * Get all gifthunt sessions from the database
   */
  public function get_gifthunt_list() {
    $gifthunt_sessions = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_sessions_table . "
      ORDER BY id DESC
      "
    );

    return $gifthunt_sessions;
  }
  
  /**
   * Get the data of a selected gifthunt session from the database
   */
  public function get_gifthunt_data( $gifthunt_id ) {
    $current_gifthunt_id = intval( $gifthunt_id );
    $gifthunt_data = $this->db->get_row(
      "
      SELECT *
      FROM " . $this->gifthunt_sessions_table . "
      WHERE id = " . $current_gifthunt_id . "
      "
    );

    if ( $gifthunt_data == null ) {
      return false;
    }

    // get the gifts from the database for selected session
    $gifts = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_gifts_table . "
      WHERE session_id = " . $current_gifthunt_id . "
      AND status != 'deleted'
      ORDER BY id ASC
      "
    );

    $gifthunt_data->{ "gifts" } = $gifts;

    return stripslashes_deep( $gifthunt_data );
  }

  /**
   * Process and check posted gifthunt session data
   */
  public function process_gifthunt_session_data() {
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $error_count = 0;
    // check required fields
    $required_basic_fields = [ "name", "icon", "icon_placement", "display_type", "session_type" ];

    // check session type
    $session_type = $_POST["gifthuntSessionData"]["session_type"];
    if( $session_type == "default" ) {
      array_push( $required_basic_fields, "popup_title", "form_label_email", "form_label_legal", "form_legal_url", "popup_button", "success_email_sender_name", "success_email_sender_email", "success_email_subject", "popup_body", "popup_submit_body", "popup_design", "success_email_body", "gift_type", "icon_animation" );
    } else {
      array_push( $required_basic_fields, "custom_popup_content", "custom_popup_close_button_text" );
    }

    // check display type
    if ( $_POST["gifthuntSessionData"]["display_type"] == "time" && $session_type == "default" ) {
      array_push( $required_basic_fields, "time_to_display" );
    } else if($session_type == "default") {
      array_push( $required_basic_fields, "pageview_to_display" );
    }

    // check gift type
    if ( $_POST["gifthuntSessionData"]["gift_type"] == "oneMany" && $session_type == "default" ) {
      array_push( $required_basic_fields, "gift" );
    } else {
      if ( ! $_POST["gifthuntSessionData"]["gifts"][0]["value"] && $session_type == "default" ) {
        $error_count++;
      }
    }

    // check custom icon
    if( $_POST["gifthuntSessionData"]["icon"] == 99 && empty( $_POST["gifthuntSessionData"]["custom_icon"] )){
      $error_count++;
    }

    foreach ( $required_basic_fields as $required_basic_field ) {
      if ( ! $_POST["gifthuntSessionData"][ $required_basic_field ] || strlen( $_POST["gifthuntSessionData"][ $required_basic_field ] ) < 1 ) {
        $error_count++;
      }
    }

    /**
     * Check first name label
     */
    if ( $_POST["gifthuntSessionData"]["form_collect_first_name"] == "true" && ( ! $_POST["gifthuntSessionData"]["form_label_first_name"] || strlen( $_POST["gifthuntSessionData"]["form_label_first_name"] ) < 1 ) ) {
      $error_count++;
    }

    /**
     * Check last name label
     */
    if ( $_POST["gifthuntSessionData"]["form_collect_last_name"] == "true" && ( ! $_POST["gifthuntSessionData"]["form_label_last_name"] || strlen( $_POST["gifthuntSessionData"]["form_label_last_name"] ) < 1 ) ) {
      $error_count++;
    }

    /**
     * Check additional information label
     */
    if ( $_POST["gifthuntSessionData"]["form_collect_additional_information"] == "true" && ( ! $_POST["gifthuntSessionData"]["form_label_additional_information"] || strlen( $_POST["gifthuntSessionData"]["form_label_additional_information"] ) < 1 ) ) {
      $error_count++;
    }

    /**
     * Check visible from and visible to dates if session is active
     */
    if ( $_POST["gifthuntSessionData"]["active"] == "true" && ( ! $_POST["gifthuntSessionData"]["visible_from"] || !$_POST["gifthuntSessionData"]["visible_to"] ) ) {
      $error_count++;
    }

    if ($error_count) {
      $result["data"] = "Please, fill all required fields to create a new Gift Hunt session";
      return $result;
    }

    // process logical session data
    $gifthunt_session_visible_from = isset( $_POST["gifthuntSessionData"]["visible_from"] ) ? sanitize_text_field( $_POST["gifthuntSessionData"]["visible_from"] ) : $this->visible_from;
    $gifthunt_session_visible_to = isset( $_POST["gifthuntSessionData"]["visible_to"] ) ? sanitize_text_field( $_POST["gifthuntSessionData"]["visible_to"] ) : $this->visible_to;
    $gifthunt_time_to_display = isset( $_POST["gifthuntSessionData"]["time_to_display"] ) ? intval( $_POST["gifthuntSessionData"]["time_to_display"] ) : $this->time_to_display;
    $gifthunt_pageview_to_display = isset( $_POST["gifthuntSessionData"]["pageview_to_display"] ) ? intval( $_POST["gifthuntSessionData"]["pageview_to_display"] ) : $this->pageview_to_display;
    $gifthunt_form_label_first_name = isset( $_POST["gifthuntSessionData"]["form_label_first_name"] ) ? $_POST["gifthuntSessionData"]["form_label_first_name"] : $this->form_label_first_name;
    $gifthunt_form_label_last_name = isset( $_POST["gifthuntSessionData"]["form_label_last_name"] ) ? $_POST["gifthuntSessionData"]["form_label_last_name"] : $this->form_label_last_name;
    $gifthunt_form_label_additional_information = isset( $_POST["gifthuntSessionData"]["form_label_additional_information"] ) ? sanitize_text_field( $_POST["gifthuntSessionData"]["form_label_additional_information"] ) : $this->form_label_additional_information;

    // add gifts to the gifthunt session object
    $gifthunt_session_gifts = array();
    if ( $_POST["gifthuntSessionData"]["gift_type"] == "oneMany" && $session_type == "default" ) {
      array_push( $gifthunt_session_gifts, [
        "dbid" => 0, 
        "value" => sanitize_text_field( $_POST["gifthuntSessionData"]["gift"] )
        ]);
    } else {
      if ( is_array( $_POST["gifthuntSessionData"]["gifts"] ) && $session_type == "default" ) {
        foreach( $_POST["gifthuntSessionData"]["gifts"] as $gift){
          array_push( $gifthunt_session_gifts, [
            "dbid" => intval( $gift["dbid"] ), 
            "value" => sanitize_text_field( $gift["value"] )
            ]);
        }
      }
    }

    /**
     * Legal URL cannot be empty
     */
    $form_legal_url = "";
    if($session_type != "default"){
      $form_legal_url = "https://";
    } else {
      $form_legal_url = esc_url( $_POST["gifthuntSessionData"]["form_legal_url"] );
    }

    $gifthunt_session_db_data = [
      "name"                                => sanitize_text_field( $_POST["gifthuntSessionData"]["name"] ),
      "active"                              => intval( $_POST["gifthuntSessionData"]["active"] ),
      "visible_from"                        => $gifthunt_session_visible_from,
      "visible_to"                          => $gifthunt_session_visible_to,
      "icon"                                => intval( $_POST["gifthuntSessionData"]["icon"] ),
      "custom_icon"                         => urlencode( $_POST["gifthuntSessionData"]["custom_icon"] ),
      "icon_placement"                      => sanitize_text_field( $_POST["gifthuntSessionData"]["icon_placement"] ),
      "icon_animation"                      => sanitize_text_field( $_POST["gifthuntSessionData"]["icon_animation"] ),
      "display_type"                        => sanitize_text_field( $_POST["gifthuntSessionData"]["display_type"] ),
      "time_to_display"                     => $gifthunt_time_to_display,
      "pageview_to_display"                 => $gifthunt_pageview_to_display,
      "allow_multiple_collect"              => intval( $_POST["gifthuntSessionData"]["allow_multiple_collect"] ),
      "visible_to_visitors"                 => sanitize_text_field( $_POST["gifthuntSessionData"]["visible_to_visitors"] ),
      "session_type"                        => sanitize_text_field( $session_type ),
      "custom_popup_content"                => base64_encode( $_POST["gifthuntSessionData"]["custom_popup_content"] ),
      "custom_popup_close_button_text"      => sanitize_text_field( $_POST["gifthuntSessionData"]["custom_popup_close_button_text"] ),
      "gift_type"                           => sanitize_text_field( $_POST["gifthuntSessionData"]["gift_type"] ),
      "popup_title"                         => sanitize_text_field( $_POST["gifthuntSessionData"]["popup_title"] ),
      "popup_body"                          => wp_kses_post( $_POST["gifthuntSessionData"]["popup_body"] ),
      "popup_button"                        => sanitize_text_field( $_POST["gifthuntSessionData"]["popup_button"] ),
      "popup_submit_body"                   => wp_kses_post( $_POST["gifthuntSessionData"]["popup_submit_body"] ),
      "popup_design"                        => sanitize_text_field( $_POST["gifthuntSessionData"]["popup_design"] ),
      "form_collect_first_name"             => intval( $_POST["gifthuntSessionData"]["form_collect_first_name"] ),
      "form_collect_last_name"              => intval( $_POST["gifthuntSessionData"]["form_collect_last_name"] ),
      "form_collect_additional_information" => intval( $_POST["gifthuntSessionData"]["form_collect_additional_information"] ),
      "form_label_first_name"               => $gifthunt_form_label_first_name,
      "form_label_last_name"                => $gifthunt_form_label_last_name,
      "form_label_email"                    => sanitize_text_field( $_POST["gifthuntSessionData"]["form_label_email"] ),
      "form_label_additional_information"   => $gifthunt_form_label_additional_information,
      "form_label_legal"                    => sanitize_text_field( $_POST["gifthuntSessionData"]["form_label_legal"] ),
      "form_legal_url"                      => $form_legal_url,
      "success_email_send"                  => intval( $_POST["gifthuntSessionData"]["success_email_send"] ),
      "success_email_sender_name"           => sanitize_text_field( $_POST["gifthuntSessionData"]["success_email_sender_name"] ),
      "success_email_sender_email"          => sanitize_email( $_POST["gifthuntSessionData"]["success_email_sender_email"] ),
      "success_email_subject"               => sanitize_text_field( $_POST["gifthuntSessionData"]["success_email_subject"] ),
      "success_email_body"                  => wp_kses_post( $_POST["gifthuntSessionData"]["success_email_body"] ),
      "created"                             => date("Y-m-d H:i:s"),
      "updated"                             => date("Y-m-d H:i:s"),
      "gifts"                               => $gifthunt_session_gifts
    ];

    if ( isset( $_POST["gifthuntSessionData"]["id"] ) && $_POST["gifthuntSessionData"]["id"] > 0 ) {
      $gifthunt_session_db_data["id"] = intval( $_POST["gifthuntSessionData"]["id"] );
    }

    return $gifthunt_session_db_data;
  }

  /**
   * Save data of a new Gifthunt session
   */
  public function create_gifthunt() {
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $gifthunt_session_db_data = $this->process_gifthunt_session_data();

    if ( isset( $gifthunt_session_db_data["status"] ) ) {
      return $gifthunt_session_db_data;
    }

    $gifthunt_session_gifts = $gifthunt_session_db_data["gifts"];

    unset( $gifthunt_session_db_data["gifts"] );

    // check if current gifthunt session is active
    // turn off other active session
    if ( $gifthunt_session_db_data["active"] ) {
      $this->db->update( $this->gifthunt_sessions_table, array( "active" => 0 ), array( "active" => 1 ) );
    }

    $insert_result = $this->db->insert(
      $this->gifthunt_sessions_table,
      $gifthunt_session_db_data
    );

    $new_session_id = $this->db->insert_id;

    if ( ! $new_session_id ) {
      $result["data"] = "Database error. Please, turn off and on the plugin and try again. If you keep seeing this error, feel free to get in touch at hello@gifthuntplugin.com";
      return $result;
    }

    /**
     * If session type is default, save gifts to the database
     */
    if( $gifthunt_session_db_data["session_type"] == "default" ) {
      $gifts = array();

      foreach ( $gifthunt_session_gifts as $gift ) {
        $gifts[] = $this->db->prepare( "( %d, %s, %s)" , $new_session_id, $gift["value"], "new");
      }

      $gifts_query = "INSERT INTO `" . $this->gifthunt_gifts_table . "` (`session_id`, `gift`, `status`) VALUES ";
      $gifts_query .= implode( ", ", $gifts );

      $gifts_query_result = $this->db->query( $gifts_query );
      
      if ( ! $gifts_query_result ) {
        $result["data"] = "Database error. Please, turn off and on the plugin and try again. If you keep seeing this error, feel free to get in touch at hello@gifthuntplugin.com";
        return $result;
      }
    }

    // return new session id
    $result["status"] = "success";
    $result["data"] = $new_session_id;

    return $result;
  }

  /**
   * Update data of a selected Gifthunt session
   */
  public function update_gifthunt() {
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $gifthunt_session_db_data = $this->process_gifthunt_session_data();

    if ( isset( $gifthunt_session_db_data["status"] ) ) {
      return $gifthunt_session_db_data;
    }

    $gifthunt_session_gifts = $gifthunt_session_db_data["gifts"];
    $gifthunt_session_id = $gifthunt_session_db_data["id"];

    unset( $gifthunt_session_db_data["gifts"] );
    unset( $gifthunt_session_db_data["id"] );

    // check if current gifthunt session is active
    // turn off other active session
    if ( $gifthunt_session_db_data["active"] ) {
      $this->db->update( $this->gifthunt_sessions_table, array( "active" => 0 ), array( "active" => 1 ) );
    }

    // update session default data
    $update_result = $this->db->update(
      $this->gifthunt_sessions_table,
      $gifthunt_session_db_data,
      array( "id" => $gifthunt_session_id )
    );

    if ( $update_result === false ) {
      $result["data"] = "Database error. Please, turn off and on the plugin and try again. If you keep seeing this error, feel free to get in touch at hello@gifthuntplugin.com";
      return $result;
    }

    /**
     * If session type is default, update current gifts and insert new ones
     */
    $result["gifts"] = [];
    if( $gifthunt_session_db_data["session_type"] == "default" ) {
      $gifts = array();
      $gift_id_array = array();
  
      foreach ( $gifthunt_session_gifts as $gift ) {
        $gifts[] = $this->db->prepare( "( %d, %d, %s, %s)" , $gift["dbid"], $gifthunt_session_id, $gift["value"], "new" );
        if ( $gift["dbid"] != 0 ) {
          $gift_id_array[] = $gift["dbid"];
        }
      }
  
      if ( ! count( $gift_id_array ) ) {
        $gift_id_array[0] = 0;
      }
  
      // set gifts to deleted that are not in the gift ids array
      $gifts_delete_query = "UPDATE " . $this->gifthunt_gifts_table . " SET status = 'deleted' WHERE session_id = " . $gifthunt_session_id . " AND id NOT IN ( ";
      $gifts_delete_query .= implode( ", ", $gift_id_array );
      $gifts_delete_query .= " );";
      $this->db->query( $gifts_delete_query );
  
      // insert new gifts to the database and update old ones
      $gifts_query = "INSERT INTO `" . $this->gifthunt_gifts_table . "` (`id`, `session_id`, `gift`, `status`) VALUES ";
      $gifts_query .= implode( ", ", $gifts );
      $gifts_query .= " ON DUPLICATE KEY UPDATE gift = VALUES(gift);";
  
      $gifts_query_result = $this->db->query( $gifts_query );
  
      if ( $gifts_query_result === false ) {
        $result["data"] = $this->db->print_error() . " Database error. Please, wait a few minutes and try again";
        return $result;
      }
  
      // get valid gifts from the database
      $session_gifts = $this->db->get_results(
        "
        SELECT *
        FROM " . $this->gifthunt_gifts_table . "
        WHERE session_id = " . $gifthunt_session_id . "
        AND status != 'deleted'
        ORDER BY id ASC
        "
      );

      $result["gifts"] = stripslashes_deep( $session_gifts );
    }

    // return success message
    $result["status"] = "success";
    $result["data"] = "Session updated successfully";

    return $result;
  }

  /**
   * Delete a Gifthunt session
   */
  public function delete_gifthunt() {
    $gifhunt_id = intval( $_POST["id"] );
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    // delete hunters
    $hunters_delete_result = $this->db->delete( $this->gifthunt_hunters_table, array( "session_id" => $gifhunt_id ), array( '%d' ) );

    // delete gifts
    $gifts_delete_result = $this->db->delete( $this->gifthunt_gifts_table, array( "session_id" => $gifhunt_id ), array( '%d' ) );

    // delete session
    $session_delete_result = $this->db->delete( $this->gifthunt_sessions_table, array( "id" => $gifhunt_id ), array( '%d' ) );

    if ( $session_delete_result === FALSE || $gifts_delete_result === FALSE ) {
      $result["data"] = "There was an error during the process. Please, reload the page and try again";
    } else {
      $result["status"] = "success";
    }

    return $result;
  }

  /**
   * Delete hunter data
   */
  public function delete_hunter() {
    $current_hunter_id = intval( $_POST["id"] );
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    // get hunter data from the database
    $hunter_data = $this->db->get_row(
      "
      SELECT *
      FROM " . $this->gifthunt_hunters_table . "
      WHERE id = " . $current_hunter_id . "
      "
    );

    if (!$hunter_data) {
      $result["data"] = "There was an error during the process. Please, try again";
      return $result;
    }

    $hunter_delete_result = $this->db->delete( $this->gifthunt_hunters_table, array( "id" => $current_hunter_id ), array( '%d' ) );

    if (!$hunter_delete_result) {
      $result["data"] = "There was an error during the process. Please, try again";
      return $result;
    }

    // change gift status to new
    $gift_update_result = $this->db->update(
      $this->gifthunt_gifts_table,
      array(
        "status"  => "new"
      ),
      array(
        "id"      => $hunter_data->gift_id,
        "status"  => "found"
      ),
      array( '%s' ),
      array( '%d' )
    );

    $result["status"] = "success";
    $result["data"] = "Hunter has been removed";

    return $result;
  }

  /**
   * Get all hunters by gifthunt id
   */
  public function get_hunters( $gifthunt_id ) {
    $current_gifthunt_id = intval( $gifthunt_id );

    $session_hunters = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_hunters_table . "
      WHERE session_id = " . $current_gifthunt_id . "
      ORDER BY created DESC
      "
    );

    return $session_hunters;
  }

  /**
   * Delete all hunters
   */
  public function delete_hunters_all() {
    $current_gifthunt_id = intval( $_POST['id'] );
    $result = [
      "status"  => "error",
      "data"    => ""
    ];
    // delete hunters
    $hunters_delete_result = $this->db->delete( $this->gifthunt_hunters_table, array( "session_id" => $current_gifthunt_id ), array( '%d' ) );

    if ( ! $hunters_delete_result ) {
      $result["data"] = "There was an error during the process. Please, try again";
      return $result;
    }
    
    // update gifts status
    $gift_update_result = $this->db->update(
      $this->gifthunt_gifts_table,
      array(
        "status"      => "new"
      ),
      array(
        "session_id"  => $current_gifthunt_id,
        "status"      => "found"
      ),
      array( '%s' ),
      array( '%d' )
    );

    $result["status"] = "success";

    return $result;
  }

  /**
   * Get gifts for a specific gifthunt session
   */
  public function get_gifts( $gifthunt_id ) {
    $current_gifthunt_id = intval( $gifthunt_id );

    $gifts = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_gifts_table . "
      WHERE session_id = " . $current_gifthunt_id . "
      ORDER BY id DESC
      "
    );

    return $gifts;
  }

  /**
   * Send test result mail
   */
  public function send_test_result_mail() {
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $to = $_POST["to"];
    $from_name = stripslashes_deep( $_POST["success_email_sender_name"] );
    $from_email = stripslashes_deep( $_POST["success_email_sender_email"] );
    $subject = stripslashes_deep( "(TEST) " . $_POST["success_email_subject"] );
    $message = stripslashes_deep( $_POST["success_email_body"] );

    $mail_result = wp_mail( $to, $subject, $message, "From: " . $from_name . " <" . $from_email . ">\r\nContent-Type: text/html; charset=UTF-8" );

    if ( ! $mail_result) {
      $result["data"] = "There was an error during the process, please wait a few minutes and try again";
    } else {
      $result["status"] = "success";
      $result["data"] = "A test mail has been sent to " . $to . "<br /> <strong>If you like it, don't forget to save your changes</strong>";
    }

    return $result;
  }

  /**
   * Export hunters
   */
  public function export_hunters() {
    $current_gifthunt_id = intval( $_POST["id"] );

    // get hunters from the database
    $hunters = $this->get_hunters( $current_gifthunt_id );

    if ( !class_exists( "XLSXWriter" ) ) {
      include "class-xlsxwriter.php";
    }
    
    $writer = new XLSXWriter();

    $filename = "gifhunt_hunters_" . date("YmdHis") . ".xlsx";
    
    header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename( $filename ).'"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    
    $writer->setAuthor( "Gifhunt for WordPress" );

    // sheet header
    $writer->writeSheetRow( "Hunters", array(
      "First name",
      "Last name",
      "Email",
      "Additional information",
      "Gift",
      "Date"
    ) );

    // sheet rows
    foreach ( $hunters as $hunter ) {
      $writer->writeSheetRow( "Hunters", array(
        stripslashes_deep( $hunter->first_name ),
        stripslashes_deep( $hunter->last_name ),
        stripslashes_deep( $hunter->email ),
        stripslashes_deep( $hunter->additional_information ),
        stripslashes_deep( $hunter->gift ),
        $hunter->created
      ) );
    }

    // display sheet in browser
    $writer->writeToStdOut();

    die();
  }

  /**
   * ====================
   * Mailchimp integration functions
   * ====================
   */
  /**
   * Get managed Mailchimp lists
   */
  public function mailchimp_lists(){
    if ( !class_exists( "Mailchimp" ) ) {
      include "class-mailchimp.php";
    }

    $api_key = sanitize_text_field( $_POST["apiKey"] );
    
    $mailchimp = new MailChimp( $api_key );
    $result = $mailchimp->get( "lists" );

    return $result;
  }

  /**
   * Get merge fields for the selected list
   */
  public function mailchimp_merge_fields(){
    if ( !class_exists( "Mailchimp" ) ) {
      include "class-mailchimp.php";
    }

    $api_key = sanitize_text_field( $_POST["apiKey"] );
    $list_id = sanitize_text_field( $_POST["listId"] );
    
    $mailchimp = new MailChimp( $api_key );
    $result = $mailchimp->get( "lists/" . $list_id . "/merge-fields" );

    return $result;
  }

  /**
   * Get default mailchimp settings
   */
  public function get_mailchimp_default_settings(){
    $mailchimp_default_settings = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_mailchimp_settings_table . "
      WHERE type = 'default'
      ORDER BY id DESC
      "
    );

    if( $mailchimp_default_settings ){
      $mailchimp_default_settings = $mailchimp_default_settings[0];
    }

    return $mailchimp_default_settings;
  }

  /**
   * Get last 100 items from Mailchimp log table
   */
  public function get_mailchimp_log(){
    $mailchimp_log = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_mailchimp_logs_table . "
      ORDER BY id DESC
      "
    );

    return $mailchimp_log;
  }

  /**
   * Save mailchimp integration settings
   */
  public function mailchimp_save_settings(){
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $active = ( $_POST["active"] == "true" ) ? 1 : 0;

    $mailchimp_settings = [
      "type"        => "default",
      "api_key"     => sanitize_text_field( $_POST["apiKey"] ),
      "fname"       => sanitize_text_field( $_POST["fname"] ),
      "lname"       => sanitize_text_field( $_POST["lname"] ),
      "additional"  => sanitize_text_field( $_POST["additional"] ),
      "active"      => $active,
      "list_id"     => sanitize_text_field( $_POST["listId"] ),
      "list_name"   => sanitize_text_field( $_POST["listName"] )
    ];

    $mailchimp_default_settings = $this->get_mailchimp_default_settings();

    $db_result = "";
    if( $mailchimp_default_settings ){
      /**
       * Update default settings if they exists
       */
      $db_result = $this->db->update(
        $this->gifthunt_mailchimp_settings_table,
        $mailchimp_settings,
        array( "type" => "default" )
      );
    } else {
      /**
       * Insert default settings if they don't exists
       */
      $db_result = $this->db->insert(
        $this->gifthunt_mailchimp_settings_table,
        $mailchimp_settings
      );
    }

    if( $db_result !== false ){
      $result["status"] = "success";
    } else {
      $result["data"] = "There was a database error during the process. Please, try again.";
    }

    return $result;
    
  }

  /**
   * Send hunters to Mailchimp
   */
  public function hunter_add_to_mailchimp( $hunter ){
    if( !$hunter ){
      return;
    }

    /**
     * Get Mailchimp integration data
     */
    $mailchimp_settings = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_mailchimp_settings_table . "
      WHERE type = 'default'
      ORDER BY id DESC
      "
    );

    /**
     * Check if Mailchimp integration settings exists and it's active
     */
    if( !$mailchimp_settings || $mailchimp_settings[0]->active != 1 ){
      return;
    }

    $mailchimp_settings = $mailchimp_settings[0];

    /**
     * Add hunter to the list
     */
    if ( !class_exists( "Mailchimp" ) ) {
      include "class-mailchimp.php";
    }

    $api_key = $mailchimp_settings->api_key;
    
    $mailchimp = new MailChimp( $api_key );

    $result = $mailchimp->post( "lists/" . $mailchimp_settings->list_id . "/members", [
                "email_address" => $hunter["email"],
                "status"        => "subscribed",
                "merge_fields"  => [
                  $mailchimp_settings->fname      => $hunter["firstName"],
                  $mailchimp_settings->lname      => $hunter["lastName"],
                  $mailchimp_settings->additional => $hunter["additionalInformation"]
                ]
              ]);
    
    return $result;
  }

  /**
   * Test mailchimp integration settings
   */
  public function mailchimp_integration_test(){
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $mailchimp_settings = [
      "api_key"     => sanitize_text_field( $_POST["apiKey"] ),
      "fname"       => sanitize_text_field( $_POST["fname"] ),
      "lname"       => sanitize_text_field( $_POST["lname"] ),
      "additional"  => sanitize_text_field( $_POST["additional"] ),
      "list_id"     => sanitize_text_field( $_POST["listId"] ),
      "list_name"   => sanitize_text_field( $_POST["listName"] )
    ];

    $test_data = [
      "email"       => sanitize_text_field( $_POST["testEmailAddress"] ),
      "fname"       => sanitize_text_field( $_POST["testFirstName"] ),
      "lname"       => sanitize_text_field( $_POST["testLastName"] ),
      "additional"  => sanitize_text_field( $_POST["testAdditionalInformation"] )
    ];

    if ( !class_exists( "Mailchimp" ) ) {
      include "class-mailchimp.php";
    }

    $api_key = $mailchimp_settings["api_key"];
    
    $mailchimp = new MailChimp( $api_key );

    $mailchimp_result = $mailchimp->post( "lists/" . $mailchimp_settings["list_id"] . "/members", [
                "email_address" => $test_data["email"],
                "status"        => "subscribed",
                "merge_fields"  => [
                  $mailchimp_settings["fname"]      => $test_data["fname"],
                  $mailchimp_settings["lname"]      => $test_data["lname"],
                  $mailchimp_settings["additional"] => $test_data["additional"]
                ]
              ]);

    if( $mailchimp_result["status"] == "subscribed" ){
      $result["status"] = "success";
    } else {
      $result["data"] = $mailchimp_result["title"] . " " . $mailchimp_result["detail"];
    }
    
    return $result;

  }

  /**
   * ====================
   * Frontend functions
   * ====================
   */
  private function get_active_gifthunt_data( $id = 0 ) {
    if ( $id != 0 ) {
      // get session data for preview
      $gifthunt_data = $this->db->get_row(
        "
        SELECT *
        FROM " . $this->gifthunt_sessions_table . "
        WHERE id = " . $id . "
        "
      );
    } else {
      $date = new DateTime("now");
      $today = $date->format( "Y-m-d" );
  
      $gifthunt_data = $this->db->get_row(
        "
        SELECT *
        FROM " . $this->gifthunt_sessions_table . "
        WHERE active = 1
        AND visible_from <= '" . $today . "'
        AND visible_to >= '" . $today . "'
        "
      );

      // check available gifts
      if ( $gifthunt_data && $gifthunt_data->gift_type == "moreOne" ) {
        $gifts_count = $this->db->get_var(
          "
          SELECT COUNT(*) 
          FROM " . $this->gifthunt_gifts_table . "
          WHERE status = 'new'
          AND session_id = " . $gifthunt_data->id . "
          "
        );

        if ( $gifts_count < 1 ) {
          return false;
        }
      }
    }

    if ( $gifthunt_data == null ) {
      return false;
    }

    return stripslashes_deep( $gifthunt_data );
  }

  /**
   * Display frontend script
   */
  public function display_frontend_script() {
    $main_css = file_get_contents( dirname(__DIR__) . "/assets/css/frontend/main.min.css" );
    $icon_css = file_get_contents( dirname(__DIR__) . "/assets/css/frontend/icon.min.css" );
    $main_script = file_get_contents( dirname(__DIR__) . "/assets/scripts/frontend/main.min.js" );
    $popup_window = file_get_contents( dirname(__DIR__) . "/assets/views/frontend/popup.html" );

    // get active session, or preview session data from the database
    // dont display anything if active session doesn't exists
    $session_preview = "false";
    if ( isset( $_GET["gfthntprvw"] ) ) {
      $session_preview = "true";
      $active_hunt_session_data = $this->get_active_gifthunt_data( intval( $_GET["gfthntprvw"] ) );
    } else {
      $active_hunt_session_data = $this->get_active_gifthunt_data();
    }

    // check visitor type restrictions
    // every: visible to every visitor, loggedin: visible to logged in visitors, anonym: visible to anonym - not logged in - visitors
    if( $active_hunt_session_data->visible_to_visitors && $active_hunt_session_data->visible_to_visitors != "every" ){
      if( $active_hunt_session_data->visible_to_visitors == "loggedin" && !is_user_logged_in() ){
        // visible only to logged in visitors and current user is not logged in
        echo "<!-- Gift Hunt Debug: visible only to logged in users and current user is not logged in -->";
        return;
      } else if( $active_hunt_session_data->visible_to_visitors == "anonym" && is_user_logged_in() ){
        // visible only to anonym visitors
        echo "<!-- Gift Hunt Debug: visible only to anonym users and current user is logged in -->";
        return;
      }
    }

    if ( ! $active_hunt_session_data ) {
      echo "<!-- Gift Hunt Debug: no active gifthunt session or out of codes -->";
      return;
    }

    $gifthunt_frontend_script = "const ffDiscounthuntSessionPreview = " . $session_preview . ";";
    $gifthunt_frontend_script .= "const ffDiscounthuntNonce = '" . wp_create_nonce( "gifthuntfree-nonce-frontend" ) . "';";
    $gifthunt_frontend_script .= 'const ffDiscounthuntCSS = "' . $main_css . '";';
    $gifthunt_frontend_script .= 'const ffDiscounthuntIconCSS = "' . $icon_css . '";';
    $gifthunt_frontend_script .= "const ffDiscounthuntSessionData = " . json_encode( $active_hunt_session_data ) . ";";
    $gifthunt_frontend_script .= "const ffDiscounthuntApiEndpoint = '" . GIFTHUNTFREE_FRONTEND_ENDPOINT . "';";
    $gifthunt_frontend_script .= "const ffDiscounthuntFrontendAssets = '" . GIFTHUNTFREE_FRONTEND_ASSETS_FOLDER . "';";
    $gifthunt_frontend_script .= "const ffDiscounthuntPopupWindow = " . $popup_window . ";";
    $gifthunt_frontend_script .= $main_script;

    echo "<script>" . $gifthunt_frontend_script . "</script>";

    if( isset( $active_hunt_session_data->session_type ) && $active_hunt_session_data->session_type == "custom_popup" ){
      /**
       * Handle custom popup window
       */
      $custom_popup_window = file_get_contents( dirname(__DIR__) . "/assets/views/frontend/popup_custom.html" );
      $custom_popup_window_content = do_shortcode( stripslashes_deep( base64_decode( $active_hunt_session_data->custom_popup_content ) ) );
      $custom_popup_window_close_button_label = stripslashes_deep( $active_hunt_session_data->custom_popup_close_button_text );

      $custom_popup_window = str_replace( array( "[[CUSTOM_POPUP_WINDOW_CONTENT]]", "[[CUSTOM_POPUP_WINDOW_CLOSE_BUTTON_LABEL]]" ), array( $custom_popup_window_content, $custom_popup_window_close_button_label ), $custom_popup_window );

      echo $custom_popup_window;
    }
    
    return;
  }

  /**
   * Save hunter data, collect gift
   */
  public function collect_gift() {
    $result = [
      "status"  => "error",
      "data"    => ""
    ];

    $site_url = get_site_url();
    $referer = $_SERVER["HTTP_REFERER"];

    if ( strpos( $referer, $site_url ) === false ) {
      $result["status"] = "closed";
      return $result;
    }

    // check current session data
    $current_gifthunt_id = intval( $_POST["huntSession"] );
    $gifthunt_data = $this->db->get_row(
      "
      SELECT *
      FROM " . $this->gifthunt_sessions_table . "
      WHERE id = " . $current_gifthunt_id . "
      AND active = 1
      "
    );

    if ( $gifthunt_data == null ) {
      $result["status"] = "closed";
      return $result;
    }

    // get random gift from the dabatase
    $gift_status = "";
    if( $gifthunt_data->gift_type == "moreOne" ){
      // if codes can be used only one time, get only codes with status "new"
      $gift_status = "AND status = 'new'";
    } else {
      // if codes can be used many times, get all codes that are not deleted
      $gift_status = "AND status != 'deleted'";
    }

    $gifts = $this->db->get_results(
      "
      SELECT *
      FROM " . $this->gifthunt_gifts_table . "
      WHERE session_id = " . $current_gifthunt_id . "
      " . $gift_status . "
      ORDER BY id DESC
      ",
      ARRAY_A
    );

    // out of gifts
    if ( count( $gifts ) < 1 ) {
      $result["status"] = "closed";
      return $result;
    }

    $collected_gift = $gifts[ mt_rand(0, count( $gifts ) - 1 ) ];

    // update gifts status if necessary
    if ( $gifthunt_data->gift_type == "moreOne" ) {
      $this->db->update(
        $this->gifthunt_gifts_table,
        array( "status" => "found" ),
        array( "id" => $collected_gift["id"] )
      );
    }

    $hunter_data = ["firstName", "lastName", "email", "additionalInformation"];
    foreach ( $hunter_data as $data ) {
      $hunter_data[ $data ] = sanitize_text_field( $_POST[ $data ] );
    }

    // save hunter data to the database
    $hunter_query_result = $this->db->query(
      $this->db->prepare(
        "
        INSERT INTO " . $this->gifthunt_hunters_table . "
        ( session_id, first_name, last_name, email, additional_information, gift, gift_id , created)
        VALUES ( %d, %s, %s, %s, %s, %s, %d, %s)
        ",
        $current_gifthunt_id,
        $hunter_data["firstName"],
        $hunter_data["lastName"],
        $hunter_data["email"],
        $hunter_data["additionalInformation"],
        $collected_gift["gift"],
        $collected_gift["id"],
        date("Y-m-d H:i:s")
      )
    );

    if ( ! $hunter_query_result ) {
      $result["data"] = "There was an error during the process. Please, wait a few minutes and try again";
      return $result;
    }

    $new_hunter_id = $this->db->insert_id;

    /**
     * Send hunter data to Mailchimp
     */
    $mailchimp_result = $this->hunter_add_to_mailchimp( $hunter_data );

    /**
     * Save mailchimp result to mailchimp log
     */
    $this->db->query(
      $this->db->prepare(
        "
        INSERT INTO " . $this->gifthunt_mailchimp_logs_table . "
        ( hunter_id, log )
        VALUES ( %d, %s )
        ",
        $new_hunter_id,
        json_encode( $mailchimp_result )
      )
    );

    /**
     * Update hunter data with mailchimp info
     */
    if( isset( $mailchimp_result["status"] ) && $mailchimp_result["status"] == "subscribed" ){
      $this->db->update( $this->gifthunt_hunters_table, array( "stored_in_mailchimp" => 1 ), array( "id" => $new_hunter_id ) );
    }

    if( $gifthunt_data->success_email_send != 0 ){
      // send mail to hunter
      $to = sanitize_email( $_POST["email"] );
      $from_name = stripslashes_deep( $gifthunt_data->success_email_sender_name );
      $from_email = stripslashes_deep( $gifthunt_data->success_email_sender_email );

      // replace placeholders with collected information
      $subject = str_replace( 
                  array( "%firstname%", "%lastname%", "%email%", "%gift%" ) , 
                  array( sanitize_text_field( $_POST["firstName"] ), sanitize_text_field( $_POST["lastName"] ), sanitize_email( $_POST["email"] ), stripslashes_deep( $collected_gift["gift"] ) ), 
                  stripslashes_deep( $gifthunt_data->success_email_subject )
                );

      $message = str_replace( 
                  array( "%firstname%", "%lastname%", "%email%", "%gift%" ) , 
                  array( $hunter_data["firstName"], $hunter_data["lastName"], $hunter_data["email"], stripslashes_deep( $collected_gift["gift"] ) ), 
                  stripslashes_deep( $gifthunt_data->success_email_body )
                );

      // send the final mail to hunter
      $mail_result = wp_mail( $to, $subject, $message, "From: " . $from_name . " <" . $from_email . ">\r\nContent-Type: text/html; charset=UTF-8" );
    }

    // send back gift to frontend
    $result["status"] = "success";
    $result["data"] = stripslashes_deep( $collected_gift["gift"] );

    return $result;
  }

  /**
   * Get gifthunt session icon - for shortcode display
   */
  public function get_gifthunt_icon(){
    $gifthunt_icon = "";

    if ( isset( $_GET["gfthntprvw"] ) ) {
      $gifthunt_session_data = $this->get_active_gifthunt_data( intval( $_GET["gfthntprvw"] ) );
    } else {
      $gifthunt_session_data = $this->get_active_gifthunt_data();
    }

    if( $gifthunt_session_data && isset( $gifthunt_session_data->icon )){
      if( $gifthunt_session_data->icon == 99 ){
        // custom icon
        $gifthunt_icon = '<a id="ff-discounthunt-icon-container" class="gifthunt-icon--inline"><img src="' . urldecode( $gifthunt_session_data->custom_icon ) . '" /></a>';
      } else {
        // built in icon
        $gifthunt_icon = '<a id="ff-discounthunt-icon-container" class="gifthunt-icon--inline"><img src="' . GIFTHUNT_FRONTEND_ASSETS_FOLDER . 'images/' . $gifthunt_session_data->icon . '.svg" /></a>';
      }
    }

    return $gifthunt_icon;
  }
}