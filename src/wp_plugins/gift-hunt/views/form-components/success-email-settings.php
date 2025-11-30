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
      <div class="gifthunt-card__title">
        <h3>Success Email</h3>
        <div class="gifthunt-form-group">
          <label>
            <input type="checkbox" name="success_email_send" id="success_email_send" value="1" <?php
            if ( $gifthunt->success_email_send ) {
              echo "checked";
            }
            ?> /> Send an email to users after they filled the data collection form.
          </label>
          <p class="gifthunt-form-group__help-text">Choose this option, if you'd like to send an email to the visitors who found the gift and filled the data collection form. You can use this email to send additional information to the users.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-card__title">
      <h3>Success email options</h3>
      <p>After the lucky visitors filled the form, they'll also get an email with the gift they found (the gift will also be visible below the "Popup content after form submission" content). You can use custom email subject and custom email content for this message.<br />You can also personalize the subject and the body of the email with the help of the following shortcodes:</p>
      <ul id="gifthunt-success-email-list">
        <li>
          <span>%firstname%</span>
          <br>use it to display the first name (eg. Hi %firstname%)
        </li>
        
        <li>
          <span>%lastname%</span>
          <br>use it to display the last name
        </li>
        
        <li>
          <span>%email%</span>
          <br>use it to display the email address
        </li>	

        <li>
          <span>%gift%</span>
          <br>use it to display the gift the user found
        </li>	
      </ul>
    </div>
  </div>

  <div class="gifthunt-card__section success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-card__columns">
      <div class="gifthunt-card__column">
        <div class="gifthunt-form-group">
          <label for="success_email_sender_name" class="gifthunt-form-group__label">Success email sender name</label>
          <input type="text" class="gifthunt-input-field" name="success_email_sender_name" id="success_email_sender_name" value="<?php echo esc_html( $gifthunt->success_email_sender_name ); ?>" required maxlength="100" data-gifthunt-error="Success email sender name is required" />
          <p class="gifthunt-form-group__help-text">This is going to be the sender name of the success email (it will be visible in the users inbox, you can use the name of your website)</p>
        </div>
      </div>

      <div class="gifthunt-card__column">
        <div class="gifthunt-form-group">
          <label for="success_email_sender_email" class="gifthunt-form-group__label">Success email sender email address</label>
          <input type="text" class="gifthunt-input-field" name="success_email_sender_email" id="success_email_sender_email" value="<?php echo sanitize_email( $gifthunt->success_email_sender_email ); ?>" required maxlength="100" data-gifthunt-error="Success email sender email address is required" />
          <p class="gifthunt-form-group__help-text">This is going to be the sender email address of the success email (it could be your contact email or a noreply address)</p>
        </div>
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-form-group">
      <label for="success_email_subject" class="gifthunt-form-group__label">Success email subject</label>
      <input type="text" class="gifthunt-input-field" name="success_email_subject" id="success_email_subject" value="<?php echo esc_html( $gifthunt->success_email_subject ); ?>" required maxlength="100" data-gifthunt-error="Success email subject is required" />
      <p class="gifthunt-form-group__help-text">This is going to be the subject of the email</p>
    </div>
  </div>

  <div class="gifthunt-card__section gifthunt-card__section--closed success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-form-group">
      <label class="gifthunt-form-group__label gifthunt-form-group__label--accordion">
        <span>
          <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 10V5L13 10L8 15V10Z" fill="#C4C4C4"/>
          </svg>
        </span>
        Success email template
      </label>

      <div class="gifthunt-form-group__body">
        <p class="gifthunt-form-group__help-text"><strong>Click on the images to preview or use a template</strong></p>
        <div id="gifthunt__success-email-template-list">
          <label data-template="unstyled">
            Unstyled
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/unstyled.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Unstyled Email Template">
            </span>
          </label>

          <label data-template="default-light">
            Default - Light
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/default-light.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Default Light Email Template">
            </span>
          </label>

          <label data-template="default-dark">
            Default - Dark
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/default-dark.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Default Dark Email Template">
            </span>
          </label>

          <label data-template="halloween">
            Halloween
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/halloween.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Halloween Email Template">
            </span>
          </label>

          <label data-template="christmas">
            Christmas
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/christmas.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Christmas Email Template">
            </span>
          </label>

          <label data-template="easter">
            Easter
            <span class="gifthunt-success-email-template-preview">
              <img src="<?php echo plugins_url( "assets/images/success-email-templates/easter.png", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Easter Email Template">
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-form-group">
      <label for="success_email_body" class="gifthunt-form-group__label">Success email body</label>
      <?php
      wp_editor( $gifthunt->success_email_body, "success_email_body", [ "teeny" => true, "textarea_rows" => 10 ] )
      ?>
      <div class="gifthunt-error-message hidden" id="success_email_body-error-message">
        Success email body is required
      </div>
      <p class="gifthunt-form-group__help-text">This is going to be the body of the email</p>
    </div>
  </div>

  <div class="gifthunt-card__section success-email-section <?php 
  if( ! $gifthunt->success_email_send ){
    echo "hidden";
  }
  ?>">
    <div class="gifthunt-form-group">
      <button type="button" class="button" id="gifthunt-send-test-success-email">Send a test email</button>
    </div>
  </div>
</div>