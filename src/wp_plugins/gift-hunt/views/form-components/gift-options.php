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
    <h3 class="gifthunt-card__title">
      Gift options
    </h3>
    <p>In this section, you can define what kind of gifts your visitors can collect.</p>
    <p>If you want to give the same gift to all the lucky visitors, choose the first option. If you have more gifts and you want to randomly give one of them to your visitors, choose the second option. The third option is for you if you would like to give a unique gift to every lucky visitor.</p>
    <p><strong>If you are giving away discount codes, don't forget to create them first.</strong></p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="gift_type" class="gifthunt-form-group__label">Gift type</label>
      <label>
        <input type="radio" name="gift_type" value="oneMany" required data-gifthunt-error="Gift type is required" <?php
        if ( $gifthunt->gift_type == "oneMany" ) {
          echo "checked";
        }
        ?> /> One gift - Give the same gift to every lucky visitor
      </label>

      <label>
        <input type="radio" name="gift_type" value="moreMany" required data-gifthunt-error="Gift type is required" <?php
        if ( $gifthunt->gift_type == "moreMany" ) {
          echo "checked";
        }
        ?> /> More gifts - Randomly display one for the lucky visitors
      </label>

      <label>
        <input type="radio" name="gift_type" value="moreOne" required data-gifthunt-error="Gift type is required" <?php
        if ( $gifthunt->gift_type == "moreOne" ) {
          echo "checked";
        }
        ?> /> More gifts - Give a unique gift for each lucky visitor
      </label>

      <div class="gifthunt-error-message hidden" id="gift_type-error-message">
        Please, choose one from the available options.
      </div>
    </div>
  </div>
</div>

<div class="gifthunt-card gifthunt-card--session-type-default-setting <?php
  if ( $gifthunt->gift_type != "oneMany" ) {
    echo " hidden";
  }
  
  if( isset( $gifthunt->session_type ) && $gifthunt->session_type != "default" && $gifthunt->session_type != "" ){
    echo " hidden";
  }
  ?>" id="gifthunt-card--one-gift">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">
      One gift
    </h3>
    <p>The gift your visitors can find on your site.<br />Please, use regular text in the fields below.</p>
    <p>If you'd like to give away multiple gifts, choose an other option above.</p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="gift" class="gifthunt-form-group__label">Gift</label>
      <input type="text" class="gifthunt-input-field" name="gift" id="gift" placeholder="Eg. Discount code: FreeShipping19" value="<?php
      if ( isset( $gifthunt->gifts ) && isset( $gifthunt->gifts[0] ) ) {
        echo esc_html( $gifthunt->gifts[0]->gift );
      }
      ?>" data-gifthunt-error="Please, define a gift" />
      <p class="gifthunt-form-group__help-text">Your visitors will see this after they filled the data collection form.</p>
    </div>
  </div>
</div>

<div class="gifthunt-card gifthunt-card--session-type-default-setting <?php
  if ( $gifthunt->gift_type == "oneMany" ) {
    echo " hidden";
  }

  if( isset( $gifthunt->session_type ) && $gifthunt->session_type != "default" && $gifthunt->session_type != "" ){
    echo " hidden";
  }
  ?>" id="gifthunt-card--more-gifts">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">
      More gifts
    </h3>
    <p>The gifts your visitors can find on your site.<br />Please, use regular text in the fields below.</p>
    <p>Click on the "Add another gift" button to add more gifts to your gifthunt session.</p>
    <p>If you'd like to give away only one gift, choose the <em>"One gift - Give the same gift to every lucky visitor"</em> option above.</p>
  </div>

  <div class="gifthunt-card__section">
    <div id="gifthunt-card__section--gift-list">
      <?php
      if ( isset( $gifthunt->gifts ) ) {
        foreach( $gifthunt->gifts as $gift ) {
          ?>
          <div class="gifthunt-form-group gift-item" id="gift-item-<?php echo esc_attr( $gift->id ); ?>" data-gift-id="<?php echo esc_attr( $gift->id ); ?>">
            <label for="gift_<?php echo esc_attr( $gift->id ); ?>" class="gifthunt-form-group__label">Gift</label>
            <div class="gifthunt-input-group">
              <input type="text" class="gifthunt-input-field" name="gift_<?php echo esc_attr( $gift->id ); ?>" id="gift_<?php echo esc_attr( $gift->id ); ?>" placeholder="Eg. Discount code: FreeShipping19" data-gift-db-id="<?php echo esc_attr( $gift->id ); ?>" value="<?php echo esc_html( $gift->gift ); ?>" />
              <button type="button" class="button gifthunt__gift-item-remove-button" data-gift-id="<?php echo esc_attr( $gift->id ); ?>">
                <strong>&times;</strong>
                remove
              </button>
            </div>
            <p class="gifthunt-form-group__help-text">Your visitors will see this after they filled the data collection form.</p>
          </div>
          <?php
        }
      } else {
        ?>
        <div class="gifthunt-form-group gift-item" id="gift-item-0" data-gift-id="0">
          <label for="gift_0" class="gifthunt-form-group__label">Gift</label>
          <input type="text" class="gifthunt-input-field" name="gift_0" id="gift_0" placeholder="Eg. Discount code: FreeShipping19" data-gift-db-id="0" value="" />
          <p class="gifthunt-form-group__help-text">Your visitors will see this after they filled the data collection form.</p>
        </div>
        <?php
      }
      ?>
    </div>
    <div class="gifthunt-error-message hidden" id="gifts-error-message">
      Please, add at least one gift.
    </div>
  </div>

  <script type="text/template" id="gift-field-template">
    <div class="gifthunt-form-group gift-item" id="gift-item-%GIFTID%" data-gift-id="%GIFTID%">
      <label for="gift-%GIFTID%" class="gifthunt-form-group__label">Gift</label>
      <div class="gifthunt-input-group">
        <input type="text" class="gifthunt-input-field" name="gift-%GIFTID%" id="gift-%GIFTID%" placeholder="Eg. Discount code: FreeShipping19" data-gift-db-id="%GIFTDBID%" value="%GIFTGIFT%" />
        <button type="button" class="button gifthunt__gift-item-remove-button" data-gift-id="%GIFTID%">
          <strong>&times;</strong>
          remove
        </button>
      </div>
      <p class="gifthunt-form-group__help-text">Your visitors will see this after they filled the data collection form.</p>
    </div>
  </script>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <button type="button" class="button" id="gifthunt-add-gift-button">
        <strong>+</strong> Add another gift
      </button>
    </div>
  </div>
</div>

<div class="gifthunt-card gifthunt-card--session-type-default-setting <?php
  if( isset( $gifthunt->session_type ) && $gifthunt->session_type != "default" && $gifthunt->session_type != "" ){
    echo "hidden";
  }
?>">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">
      Popup window options
    </h3>
    <p>After someone "found" the session icon on your site, to collect their reward they need to fill a form. This form will be visible inside a popup window. You can use the fields below to give some instructions to the lucky visitors (why they need to provide these information, what is going to happen after they filled the form, etc).</p>
    <p><strong>If the gifts your visitor can hunt have an expiration date, don't forget to add that information to the "Popup content after form submission" field below.</strong></p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="popup_title" class="gifthunt-form-group__label">Popup window title</label>
      <input type="text" class="gifthunt-input-field" name="popup_title" id="popup_title" value="<?php echo esc_html( $gifthunt->popup_title ); ?>" maxlength="250" required data-gifthunt-error="Popup window title is required" />
      <p class="gifthunt-form-group__help-text">This text will be visible at the top of the popup window which becomes visible when someone clicked on the session icon</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="popup_body" class="gifthunt-form-group__label">Popup window content</label>
      <?php
      wp_editor( $gifthunt->popup_body, "popup_body", [ "teeny" => true, "textarea_rows" => 5 ] );
      ?>
      <div class="gifthunt-error-message hidden" id="popup_body-error-message">
        Popup window content is required
      </div>
      <p class="gifthunt-form-group__help-text">This text will be visible inside the popup window (above the data collection form, below the popup window title)</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="popup_submit_body" class="gifthunt-form-group__label">Popup content after form submission</label>
      <?php
      wp_editor( $gifthunt->popup_submit_body, "popup_submit_body", [ "teeny" => true, "textarea_rows" => 5 ] );
      ?>
      <div class="gifthunt-error-message hidden" id="popup_submit_body-error-message">
        Popup content after form submission is required
      </div>
      <p class="gifthunt-form-group__help-text">This text will be visible inside the popup window (below the popup window title) after someone sucessfully filled and submitted the data collection form, the discount code will be visible below this text</p>
    </div>
  </div>

  <div class="gifthunt-card__section gifthunt-card__section--closed">
    <div class="gifthunt-form-group">
      <label for="popup_design" class="gifthunt-form-group__label gifthunt-form-group__label--accordion">
        <span>
          <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 10V5L13 10L8 15V10Z" fill="#C4C4C4"/>
          </svg>
        </span>
        Popup window design
      </label>

      <div class="gifthunt-form-group__body">
        <p class="gifthunt-form-group__help-text"><strong>Click on the images for larger preview</strong></p>
        <div id="gifthunt__popup-design-list">
          <label>
            <input type="radio" name="popup_design" value="default" <?php
            if ( $gifthunt->popup_design == "default" || ! $gifthunt->popup_design ) {
              echo "checked";
            }
            ?> required /> Default - Light
            <span class="gifthunt-popup-design-preview">
              <img src="<?php echo plugins_url( "assets/images/popup/default.png", dirname ( __DIR__ ) ); ?>" alt="Gift Hunt Default Popup Design">
            </span>
          </label>

          <label>
            <input type="radio" name="popup_design" value="default_dark" <?php
            if ( $gifthunt->popup_design == "default_dark") {
              echo "checked";
            }
            ?> required /> Default - Dark
            <span class="gifthunt-popup-design-preview">
              <img src="<?php echo plugins_url( "assets/images/popup/default_dark.png", dirname (__DIR__) ); ?>" alt="Gift Hunt Default Dark Popup Design">
            </span>
          </label>

          <label>
            <input type="radio" name="popup_design" value="halloween" <?php
            if ( $gifthunt->popup_design == "halloween" ) {
              echo "checked";
            }
            ?> required /> Halloween
            <span class="gifthunt-popup-design-preview">
              <img src="<?php echo plugins_url( "assets/images/popup/halloween.png", dirname (__DIR__) ); ?>" alt="Gift Hunt Halloween Popup Design">
            </span>
          </label>

          <label>
            <input type="radio" name="popup_design" value="christmas" <?php
            if ( $gifthunt->popup_design == "christmas" ) {
              echo "checked";
            }
            ?> required /> Christmas
            <span class="gifthunt-popup-design-preview">
              <img src="<?php echo plugins_url( "assets/images/popup/christmas.png", dirname (__DIR__) ); ?>" alt="Gift Hunt Christmas Popup Design">
            </span>
          </label>

          <label>
            <input type="radio" name="popup_design" value="easter" <?php
            if ( $gifthunt->popup_design == "easter" ) {
              echo "checked";
            }
            ?> required /> Easter
            <span class="gifthunt-popup-design-preview">
              <img src="<?php echo plugins_url( "assets/images/popup/easter.png", dirname (__DIR__) ); ?>" alt="Gift Hunt Easter Popup Design">
            </span>
          </label>
        </div>
        
        <div class="gifthunt-error-message hidden" id="popup_design_error-message">
          Popup window design is required. Please, choose one from the available options.
        </div>
        <p class="gifthunt-form-group__help-text">This is how the popup window will look like on your site.</p>
      </div>
    </div>
  </div>
</div>