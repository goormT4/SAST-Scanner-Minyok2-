<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>
<div class="gifthunt-card">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">Gift Hunt Session Type</h3>
    <p>You can create two types of gift hunt sessions on your site. You can collect user information and give a gift as a reward or you can display some kind of content when someone finds the session icon.</p>
  </div>

  <div class="gifthunt-card__section">
    <label>
      <input type="radio" name="session_type" value="default" <?php
      if( !$gifthunt->session_type || ( isset( $gifthunt->session_type ) && ( $gifthunt->session_type == "default" || $gifthunt->session_type == "" ) ) ) {
        echo "checked";
      }
      ?> />
      Collect user information and give gifts to your visitors as a reward
    </label>
    <p class="gifthunt-form-group__help-text">Choose this option if you would like to collect user information (name, email address, additional information) and give your visitors a gift after you collected those information.<br />
      <a href="https://youtu.be/LsXOy1rcgyI" target="_blank">View demo...</a>
    </p>
  </div>

  <div class="gifthunt-card__section">
    <label>
      <input type="radio" name="session_type" value="custom_popup" <?php
      if( isset( $gifthunt->session_type ) && $gifthunt->session_type == "custom_popup" ) {
        echo "checked";
      }
      ?> />
      Display a popup window with custom content
    </label>
    <p class="gifthunt-form-group__help-text">Choose this option if you would like to display some content (video, custom form, link to a pdf, etc) to the visitors who found the gift on your site.<br />
      <a href="https://youtu.be/ZxI948lWVQY" target="_blank">View demo...</a>
    </p>
  </div>
</div>

<div class="gifthunt-card gifthunt-card--session-type-custom-popup-setting <?php
  if( isset( $gifthunt->session_type ) && $gifthunt->session_type != "custom_popup" ){
    echo "hidden";
  }
?>">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">Custom popup window content</h3>
    <p>You can add all kinds of custom HTML (YouTube video, contact form, Mailchimp form, a link that points to a PDF, etc) to your popup window but make sure that your visitors will be able to view it properly both on desktop and mobile.</p>
    
    <?php
    wp_editor( stripslashes( base64_decode( $gifthunt->custom_popup_content ) ), "custom_popup_content", [ "teeny" => true, "textarea_rows" => 5 ] );
    ?>
    
    <div class="gifthunt-error-message hidden" id="custom_popup_content-error-message">
    Custom popup window content is required
    </div>
    
    <p class="gifthunt-form-group__help-text">This is the content that will be visible in a popup window to your visitors once they found the session icon and clicked on it.</p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      
      <label for="custom_popup_close_button_text" class="gifthunt-form-group__label">Popup window close button label</label>
      <input type="text" class="gifthunt-input-field" name="custom_popup_close_button_text" id="custom_popup_close_button_text" value="<?php echo esc_html( $gifthunt->custom_popup_close_button_text ); ?>" maxlength="70" data-gifthunt-error="Popup window close button label is required" />
      
      <p class="gifthunt-form-group__help-text">This is the text that will be visible on the close button at the bottom of your popup window. You can use something like "Close" or "Ok" </p>
    </div>
  </div>

</div>