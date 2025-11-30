<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>

<div class="gifthunt-card">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">
      Session basic settings
    </h3>
    <p>A <strong>session</strong> is where you define <em>When</em>, <em>How</em> and <em>Who</em> can find gifts on your site.</p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label class="gifthunt-form-group__label" for="name">Session name</label>
      <input type="text" class="gifthunt-input-field" name="name" id="name" value="<?php echo esc_html( $gifthunt->name ); ?>" maxlength="250" required data-gifthunt-error="Session name is required" />
      <p class="gifthunt-form-group__help-text">This is just an information for you that you can use to identify your session. It won't be visible for your visitors.</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label class="gifthunt-form-group__label" for="icon">Session icon</label>

      <div class="gifthunt-accordion">
        <div class="gifthunt-accordion__section <?php
          if ( $gifthunt->icon != 99 ) {
            echo "gifthunt-accordion__section--active";
          }
          ?>">
          <h3 class="gifthunt-accordion__title">
            <span>
              <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 10V5L13 10L8 15V10Z" fill="#C4C4C4"/>
              </svg>
            </span>
            Default icons
          </h3>
          <div class="gifthunt-accordion__body">
            <p class="gifthunt-accordion__info">Choose one from the default icons.</p>
            <div id="gifthunt__icon-list">
              <?php
              for( $i = 1; $i < 12; $i++ ) {
                ?>
                <label>
                  <input type="radio" name="icon" value="<?php echo $i; ?>" <?php
                  if ( $gifthunt->icon == $i ) {
                    echo "checked";
                  }
                  ?> required />
                  <span class="gifthunt-icon-preview">
                    <img src="<?php echo plugins_url( "assets/images/" . $i . ".svg", dirname( __DIR__ ) ); ?>" alt="Gift Hunt Icon <?php echo $i; ?>">
                  </span>
                </label>
                <?php
              }
              ?>
            </div>
          </div>
        </div>

        <div class="gifthunt-accordion__section <?php
          if ( $gifthunt->icon == 99 ) {
            echo "gifthunt-accordion__section--active";
          }
          ?>">
          <h3 class="gifthunt-accordion__title">
            <span>
              <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 10V5L13 10L8 15V10Z" fill="#C4C4C4"/>
              </svg>
            </span>
            Custom icon
          </h3>
          <div class="gifthunt-accordion__body">
            <p class="gifthunt-accordion__info">Upload your custom icon. Recommended size: 80x80px. Recommended format: transparent PNG</p>

            <div id="gifthunt__icon-upload" class="gifthunt-card__columns">
              <div id="gifthunt__icon-upload-preview" class="gifthunt-card__column">
                <label <?php
                  if ( $gifthunt->icon == 99 ) {
                    echo "style='display: block;'";
                  }
                ?>>
                  <input type="radio" name="icon" value="99" <?php
                  if ( $gifthunt->icon == 99 ) {
                    echo "checked";
                  }
                  ?> required /> Use this custom icon<br />
                  <input type="hidden" name="custom_icon" id="custom_icon" value="<?php echo urldecode( $gifthunt->custom_icon ); ?>">
                  <span class="gifthunt-icon-preview">
                    <img src="<?php echo urldecode( $gifthunt->custom_icon ); ?>" />
                  </span>
                </label>
              </div>

              <div class="gifthunt-card__column gifthunt__icon-upload-button-column">
                <button type="button" class="button" id="gifthunt__icon-upload-button">Upload custom icon</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="gifthunt-error-message hidden" id="icon_error-message">
        Session icon is required. Please, choose one from the available options or upload a custom icon.
      </div>
      <p class="gifthunt-form-group__help-text">This is the icon that will "pop up" after the visitor spent enough time on your site, or visited the defined amount of pages.</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label for="icon_placement" class="gifthunt-form-group__label">Icon placement</label>

      <div class="gifthunt-form-group__body">
        <label><input type="radio" name="icon_placement" value="random" <?php
        if ( $gifthunt->icon_placement == "random" || ! $gifthunt->icon_placement ) {
          echo "checked";
        }
        ?> required /> Display the icon at a <strong>random</strong> position</label>

        <label><input type="radio" name="icon_placement" value="center" <?php
        if ( $gifthunt->icon_placement == "center" ) {
          echo "checked";
        }
        ?> required /> Display the icon at the <strong>center</strong> of the page (vertically and horizontally centered)</label>

        <label><input type="radio" name="icon_placement" value="bottom_left" <?php
        if ( $gifthunt->icon_placement == "bottom_left" ) {
          echo "checked";
        }
        ?> required /> Display the icon at the <strong>bottom left</strong> corner of the page</label>

        <label><input type="radio" name="icon_placement" value="bottom_right" <?php
        if ( $gifthunt->icon_placement == "bottom_right" ) {
          echo "checked";
        }
        ?> required /> Display the icon at the <strong>bottom right</strong> corner of the page</label>

        <label><input type="radio" name="icon_placement" value="shortcode" <?php
        if ( $gifthunt->icon_placement == "shortcode" ) {
          echo "checked";
        }
        ?> required /> Use a <strong>shortcode</strong> to display the icon at a <strong>defined position</strong>.</label>

        <p id="gifthunt-placement-shortcode-info" class="<?php echo ($gifthunt->icon_placement != "shortcode") ? 'hidden' : ''; ?>">
          Use the following code to display the session icon in your content:
          <input id="gifthunt-placement-shortcode" type="text" value="[ff_gifthunt_icon]" />
          <span aria-label="" aria-role="img">⚠️</span> Important<br />
          The shortcode will <strong>display the active Gift Hunt sessions icon</strong> wherever you paste it (post or page content, product page, etc). So make sure you remove it after you closed your session.<br />
          <strong>Don't add the shortcode to your content more than 1 time.</strong><br />This means, that if you'd like to display the session icon on a specific product page (eg.: https://mysite.com/product) add the shortcode only one time to that page.<br />You are still free to add the shortcode to another product page (eg.: https://mysite.com/other-product)
        </p>

        <div class="gifthunt-error-message hidden" id="icon_placement_error-message">
          Icon placement is required. Please, choose one from the available options.
        </div>
        <p class="gifthunt-form-group__help-text">This defines the position where your selected session icon will show up (eg. at a random position or at the bottom left corner of your website, etc)</p>
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section gifthunt-card__section--closed <?php
    if( $gifthunt->icon_placement == "shortcode" ){
      echo "hidden";
    }
    ?>" id="icon_animation_section">
    <div class="gifthunt-form-group">
      <label for="icon_animation" class="gifthunt-form-group__label gifthunt-form-group__label--accordion">
        <span>
          <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 10V5L13 10L8 15V10Z" fill="#C4C4C4"/>
          </svg>
        </span>
        Icon animation
      </label>

      <div class="gifthunt-form-group__body">
        <p class="gifthunt-form-group__help-text"><strong>Move your mouse over the items to preview animation.</strong></p>

        <div id="gifthunt__icon-animation-list">
          <label>
            <input type="radio" name="icon_animation" value="pop" <?php
            if ( $gifthunt->icon_animation == "pop" || ! $gifthunt->icon_animation ) {
              echo "checked";
            }
            ?> required /> Pop
            <span class="gifthunt-icon-animation-preview" data-animation="pop">
              <span></span>
            </span>
          </label>

          <label>
            <input type="radio" name="icon_animation" value="bounce" <?php
            if ( $gifthunt->icon_animation == "bounce" ) {
              echo "checked";
            }
            ?> required /> Bounce
            <span class="gifthunt-icon-animation-preview" data-animation="bounce">
              <span></span>
            </span>
          </label>

          <label>
            <input type="radio" name="icon_animation" value="shake" <?php
            if ( $gifthunt->icon_animation == "shake" ) {
              echo "checked";
            }
            ?> required /> Shake
            <span class="gifthunt-icon-animation-preview" data-animation="shake">
              <span></span>
            </span>
          </label>

          <label>
            <input type="radio" name="icon_animation" value="fade_left" <?php
            if ( $gifthunt->icon_animation == "fade_left" ) {
              echo "checked";
            }
            ?> required /> Fade in left
            <span class="gifthunt-icon-animation-preview" data-animation="fade_left">
              <span></span>
            </span>
          </label>

          <label>
            <input type="radio" name="icon_animation" value="fade_right" <?php
            if ( $gifthunt->icon_animation == "fade_right" ) {
              echo "checked";
            }
            ?> required /> Fade in right
            <span class="gifthunt-icon-animation-preview" data-animation="fade_right">
              <span></span>
            </span>
          </label>
        </div>
        
        <div class="gifthunt-error-message hidden" id="icon_animation_error-message">
          Icon animation is required. Please, choose one from the available options.
        </div>
        <p class="gifthunt-form-group__help-text">This is how the selected icon will show up on your site.</p>
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if( $gifthunt->icon_placement == "shortcode" ){
      echo "hidden";
    }
    ?>" id="display_type_section">
    <div class="gifthunt-form-group">
      <label class="gifthunt-form-group__label" for="display_type">When should the visitor see the session icon on your site?</label>

      <label><input type="radio" name="display_type" value="time" <?php
      if ( $gifthunt->display_type == "time" ) {
        echo "checked";
      }
      ?> required /> After they spent some time on the site</label>

      <label><input type="radio" name="display_type" value="pageview" <?php
      if ( $gifthunt->display_type == "pageview" ) {
        echo "checked";
      }
      ?> /> After they visited some pages</label>

      <div class="gifthunt-error-message hidden" id="display_type-error-message">
        Please, choose one from the available options.
      </div>
    </div>
  </div>

  <div class="gifthunt-card__section <?php 
    if ( $gifthunt->display_type == "pageview" || $gifthunt->icon_placement == "shortcode" ) {
      echo "hidden";
    }
    ?>" id="time_to_display_section">
    <div class="gifthunt-form-group">
      <label for="time_to_display" class="gifthunt-form-group__label">Seconds, after the session icon becomes visible for the visitor</label>
      <input type="number" min="1" name="time_to_display" id="time_to_display" value="<?php echo esc_html( $gifthunt->time_to_display ); ?>" data-gifthunt-error="Seconds, after the session icon becomes visible is required" />
      <p class="gifthunt-form-group__help-text">The icon will become visible for the visitors after they spent the defined amount of time on your website. Eg. 60 seconds (1 minute), or 300 seconds (5 minutes).</p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( $gifthunt->display_type == "time" || $gifthunt->icon_placement == "shortcode" ) {
      echo "hidden";
    }
    ?>" id="pageview_to_display_section">
    <div class="gifthunt-form-group">
      <label for="pageview_to_display" class="gifthunt-form-group__label">Number of visited pages, after the session icon becomes visible for the visitor</label>
      <input type="number" min="1" name="pageview_to_display" id="pageview_to_display" value="<?php echo esc_html( $gifthunt->pageview_to_display ); ?>" data-gifthunt-error="Number of visited pages, after the session icon becomes visible is required" />
      <p class="gifthunt-form-group__help-text">The icon will become visible for the visitors after they viewed the defined amount of unique pages on your website. Eg, if the number is 2, the icon will become visible when they visited 2 unique pages.</p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="radio" name="visible_to_visitors" value="every" <?php if( !$gifthunt->visible_to_visitors || $gifthunt->visible_to_visitors == "every" ){
          echo 'checked="checked"';
        } ?> /> <strong>Every visitor</strong> can find the gifts
      </label>

      <label>
        <input type="radio" name="visible_to_visitors" value="loggedin" <?php if( $gifthunt->visible_to_visitors == "loggedin" ){
          echo 'checked="checked"';
        } ?> /> <strong>Only logged in visitors</strong> can find the gifts
      </label>

      <label>
        <input type="radio" name="visible_to_visitors" value="anonym" <?php if( $gifthunt->visible_to_visitors == "anonym" ){
          echo 'checked="checked"';
        } ?> /> <strong>Only anonym visitors</strong> can find the gifts (visitors, who are not logged in)
      </label>

      <div class="gifthunt-error-message hidden" id="visible_to_visitors_error-message">
        Please, choose one from the available options.
      </div>

      <p class="gifthunt-form-group__help-text">
        When you choose the first option, the gifts will become visible to every user of your website (after they visited the defined number of pages, or spent enough time on your site). Use this option if you'd like to reach every visitor of your site with your Gift Hunt Session.
      </p>
      <p class="gifthunt-form-group__help-text">
        With the second option, you can target only logged in visitors with your Gift Hunt Session.
      </p>
      <p class="gifthunt-form-group__help-text">
        The third option can be used to show the gifts only to visitors who are not logged in. This is a great way to convert anonym visitors to customers.
      </p>
    </div>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="checkbox" name="allow_multiple_collect" id="allow_multiple_collect" value="1" <?php
        if ( $gifthunt->allow_multiple_collect ) {
          echo "checked";
        }
        ?> />
        Allow one user to collect multiple gifts during this session
      </label>

      <p class="gifthunt-form-group__help-text">Choose this option, if you'd like to let users collect multiple gifts during this session. This is just a cookie based protection, so users who visit your site from different browsers or clear their cookies will be able to collect gifts multiple times.</p>
    </div>
  </div>
</div>