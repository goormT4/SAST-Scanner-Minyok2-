<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>
<div class="gifthunt-card gifthunt-card--session-type-default-setting <?php
  if( isset( $gifthunt->session_type ) && $gifthunt->session_type != "default" && $gifthunt->session_type != "" ){
    echo "hidden";
  }
?>">
  <div class="gifthunt-card__section">
    <div class="gifthunt-card__title">
      <h3>Data collection options</h3>
      <p>The data collection form will be visible inside the popup window, below the popup window content.<br />You can choose to not just collect the first name, last name and email of the lucky visitor but also some additional information. You can use that to collect messages, addresses, phone numbers, etc.</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="form_label_email" class="gifthunt-form-group__label">Label for email field</label>
      <input type="text" class="gifthunt-input-field" name="form_label_email" id="form_label_email" value="<?php echo esc_html( $gifthunt->form_label_email ); ?>" maxlength="50" required data-gifthunt-error="Label for email field is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible above the email field in the data collection form</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="checkbox" name="form_collect_first_name" id="form_collect_first_name" value="1" <?php
        if ( $gifthunt->form_collect_first_name ) {
          echo "checked";
        }
        ?> /> Collect first names in the form
      </label>
      <p class="gifthunt-form-group__help-text">Choose this option, if you'd like to display a field in your data collection form to collect first names</p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( ! $gifthunt->form_collect_first_name ) {
      echo "hidden";
    }
    ?>" id="form_label_first_name_section">
    <div class="gifthunt-form-group">
      <label for="form_label_first_name" class="gifthunt-form-group__label">Label for first name field</label>
      <input type="text" class="gifthunt-input-field" name="form_label_first_name" id="form_label_first_name" value="<?php echo esc_html( $gifthunt->form_label_first_name ); ?>" maxlength="50" data-gifthunt-error="Label for first name field is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible above the First Name field in the data collection form</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="checkbox" name="form_collect_last_name" id="form_collect_last_name" value="1" <?php
        if ( $gifthunt->form_collect_last_name ) {
          echo "checked";
        }
        ?> /> Collect last names in the form
      </label>
      <p class="gifthunt-form-group__help-text">Choose this option, if you'd like to display a field in your data collection form to collect last names</p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( ! $gifthunt->form_collect_last_name ) {
      echo "hidden";
    }
    ?>" id="form_label_last_name_section">
    <div class="gifthunt-form-group">
      <label for="form_label_last_name" class="gifthunt-form-group__label">Label for last name field</label>
      <input type="text" class="gifthunt-input-field" name="form_label_last_name" id="form_label_last_name" value="<?php echo esc_html( $gifthunt->form_label_last_name ); ?>" maxlength="50" data-gifthunt-error="Label for last name field is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible above the Last Name field in the data collection form</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="checkbox" name="form_collect_additional_information" id="form_collect_additional_information" value="1" <?php
        if ( $gifthunt->form_collect_additional_information ) {
          echo "checked";
        }
        ?> /> Collect additional information in the form
      </label>
      <p class="gifthunt-form-group__help-text">Choose this option, if you'd like to display an extra field in your data collection form (for example, this could be useful if you'd like to collect the address of your lucky visitors)</p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( ! $gifthunt->form_collect_additional_information ) {
      echo "hidden";
    }
    ?>" id="form_label_additional_information_section">
    <div class="gifthunt-form-group">
      <label for="form_label_additional_information" class="gifthunt-form-group__label">Label for additional information field</label>
      <input type="text" class="gifthunt-input-field" name="form_label_additional_information" id="form_label_additional_information" value="<?php echo esc_html( $gifthunt->form_label_additional_information ); ?>" maxlength="50" data-gifthunt-error="Label for additional information field is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible above the additional information field in the data collection form</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <p><strong>As you are going to collect personal information during the process, you should inform users of how you are going to use the collected data. To claim their gifts, users have to accept these terms.<br />You can use the privacy policy of your website here if that's appropriate.</strong></p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="form_label_legal" class="gifthunt-form-group__label">Label for Privacy Policy, Terms of Service field</label>
      <input type="text" class="gifthunt-input-field" name="form_label_legal" id="form_label_legal" value="<?php echo esc_html( $gifthunt->form_label_legal ); ?>" required maxlength="250" data-gifthunt-error="Label for Privacy Policy is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible next to the legal checkbox in the data collection form</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="form_legal_url" class="gifthunt-form-group__label">URL for Privacy Policy, Terms of Service</label>
      <input type="url" name="form_legal_url" id="form_legal_url" value="<?php echo esc_url( $gifthunt->form_legal_url ); ?>" required maxlength="250" data-gifthunt-error="URL for Privacy Policy is required" />
      <p class="gifthunt-form-group__help-text">If your Privacy Policy, Terms of Service are available on different URLs, please, create a new page where both of those URLs are available and use the link of that page here</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="popup_button" class="gifthunt-form-group__label">Form submit button text</label>
      <input type="text" class="gifthunt-input-field" name="popup_button" id="popup_button" value="<?php echo esc_html( $gifthunt->popup_button ); ?>" maxlength="50" required data-gifthunt-error="Form submit button text is required" />
      <p class="gifthunt-form-group__help-text">This is the text that will be visible on the submit button at the end of the data collection form</p>
    </div>
  </div>
</div>