<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>

<div class="gifthunt-page-actions gifthunt-page-actions--header edit-post-header">
	<h1>
		<?php
		if ( $gifthunt->id ) {
			?>
			Editing: <?php echo esc_html( $gifthunt->name ); ?>
			<?php
		} else {
			?>
			Create New Gift Hunt Session
			<?php
		}
		?>
	</h1>

	<?php
	if ( $gifthunt->id ) {
		?>
		<a href="<?php echo get_site_url() . "?gfthntprvw=" . $gifthunt->id; ?>" target="_blank" class="button" id="gifthunt-session-preview-button">Preview session</a>
		<?php
	}
	?>

	<button type="submit" class="gifthunt-button button button-primary gifthunt-save-session">
		Save session
		<i class="hidden">
			<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
				</path>
			</svg>
		</i>
	</button>
</div>

<form action="" class="gifthunt-form">
	<div class="gifthunt-form__form">

	<?php
	/**
	 * Display Mailchimp integration notice if necessary
	 */
	if( isset( $mailchimp_default_settings->active ) && $mailchimp_default_settings->active == 1 ){
		?>
		<div id="mailchimp-notice" class="subpage-notice notice-info"><p><strong>Mailchimp integration is active.</strong> The collected user information will be sent to your Mailchimp lists. <a href="admin.php?page=gifthuntfree-sessions&p=mailchimp-integration">You can update your Mailchimp settings here</a>.</p></div>
		<?php
	}
	?>

		<?php
		include __DIR__ . "/form-components/get-started.php";
		include __DIR__ . "/form-components/basic-settings.php";
		include __DIR__ . "/form-components/session-type.php";
		include __DIR__ . "/form-components/gift-options.php";
		include __DIR__ . "/form-components/data-collection-options.php";
		include __DIR__ . "/form-components/success-email-settings.php";
		?>

	</div>

	<div class="gifthunt-form__sidebar">
		<?php
		include __DIR__ . "/form-components/visibility-settings.php";
		?>
	</div>
</form>

<div class="gifthunt-page-actions">
	<a href="admin.php?page=gifthuntfree-sessions" class="gifthunt-button--link">Back to session list</a>

	<button type="submit" class="gifthunt-button button button-primary button-hero gifthunt-save-session">
		Save session
		<i class="hidden">
			<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
				</path>
			</svg>
		</i>
	</button>
</div>

<div class="gifthunt-form-result__modal <?php
	if ( ! isset( $_GET["msg"] ) ) {
		echo "hidden";
	}
	?>">
	<div class="gifthunt-form-result__modal-content">
		<div class="gifthunt-form-result gifthunt-form-result--error hidden">
			<div class="gifthunt-form-result__content">
				Error
			</div>
			<button type="button" class="gifthunt-form-result__close-button">
				<strong>
					&times;
				</strong>
			</button>
		</div>

		<div class="gifthunt-form-result gifthunt-form-result--success <?php
			if ( ! isset( $_GET["msg"] ) || $_GET["msg"] != "created" ) {
				echo "hidden";
			}
			?>">
			<div class="gifthunt-form-result__content">
				<?php
				if ( isset( $_GET["msg"] ) ) {
					$currentMsg = sanitize_title( $_GET["msg"] );

					switch( $currentMsg ) {
						case "created":
							echo "Session created successfully";
							break;
						
						default:
							echo "Success";
							break;
					}
				} else {
					echo "Success";
				}
				?>
			</div>
			<button type="button" class="gifthunt-form-result__close-button">
				<strong>
					&times;
				</strong>
			</button>
		</div>
	</div>
</div>

<div class="gifthunt-form__delete-confirm-modal hidden">
	<div class="gifthunt-form__delete-confirm-modal-content">
		<h3>Are you sure?</h3>
		<p>If you delete the session, all the session data and collected user information will be deleted as well.</p>
		<div class="gifthunt-form__delete-confirm-actions">
			<button type="button" class="button" id="button--cancel-delete">Cancel</button>
			<button type="button" class="button gifthunt-button--danger" id="button--confirm-delete">
				Delete session and all collected data
				<i class="hidden">
					<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
						</path>
					</svg>
				</i>
			</button>
		</div>

		<button type="button" class="gifthunt-form__delete-confirm-modal__close-button">
			<strong>
				&times;
			</strong>
		</button>
	</div>
</div>

<div class="gifthunt-form__test-mail-modal hidden">
	<div class="gifthunt-form__test-mail-modal-content">
		<h3>Send success email preview</h3>
		<div class="gifthunt-form-group">
			<label for="to" class="gifthunt-form-group__label">Send test mail to</label>
			<input type="email" name="to" id="to" />
			<p class="gifthunt-form-group__help-text">Add the email address where you'd like to send the test message to</p>
		</div>

		<div class="gifthunt-form__test-mail-actions">
			<button type="button" class="button" id="button--cancel-send-testmail">Cancel</button>
			<button type="button" class="button button-primary" id="button--confirm-send-testmail">
				Send test email
				<i class="hidden">
					<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
						</path>
					</svg>
				</i>
			</button>
		</div>

		<button type="button" class="gifthunt-form__test-mail-modal__close-button">
			<strong>
				&times;
			</strong>
		</button>
	</div>
</div>

<div class="gifthunt-form__popup-design-preview-modal hidden">
</div>

<div class="gifthunt-form__success-email-template-preview-modal hidden">
	<div class="gifthunt-form__success-email-template-preview-modal-content">
		<h3>Template: <span id="success-email-template-preview-name"></span></h3>

		<div class="gifthunt-form__success-email-template-preview-modal-body">
		</div>

		<div class="gifthunt-form__test-mail-actions">
			<button type="button" class="button" id="button--cancel-success-email-template-preview">Cancel</button>
			<button type="button" class="button button-primary" id="button--confirm-success-email-template-preview">Use this template</button>
		</div>

		<button type="button" class="gifthunt-form__success-email-template-preview-modal__close-button">
			<strong>
				&times;
			</strong>
		</button>
	</div>
</div>

<div class="gifthunt-form__shortcode-preview-alert-modal hidden">
	<div class="gifthunt-form__shortcode-preview-alert-modal-content">
		<h3>Preview your session with shortcode icon placement</h3>

		<p>When you use the shortcode option as your icon placement, you can preview your session by following the steps below.</p>
		<ol>
			<li>Save the settings of your session.</li>
			<li>Make sure the session is active. (<span id="shortcode-preview-current-status"></span>)</li>
			<li>The start date of your session is today (or an earlier date) and the end date is a future date. (<span id="shortcode-preview-date-settings"></span>)</li>
			<li>Create a test page/post/product page where you can test your session.</li>
			<li>After you created your test page/post/product, copy the following shortcode to your clipboard: <strong>[ff_gifthunt_icon]</strong></li>
			<li>Paste the shortcode into the content of your page/post/product.</li>
			<li>Save the changes of your page/post/product and then preview it.</li>
			<li>The session icon should become visible at the location where you placed your shortcode.</li>
		</ol>

		<p>As the session icon is an image, the appearance of it can be a bit different depending on the settings of your theme.</p>

		<button class="button button-primary" id="shortcode-preview-alert-close">Ok</button>

		<button type="button" class="gifthunt-form__shortcode-preview-alert-modal__close-button">
			<strong>
				&times;
			</strong>
		</button>
	</div>
</div>

<?php
// success email templates
include "success-email-templates/christmas.php";
include "success-email-templates/default-dark.php";
include "success-email-templates/default-light.php";
include "success-email-templates/easter.php";
include "success-email-templates/halloween.php";
include "success-email-templates/unstyled.php";
?>

<script>
const giftSessionId = <?php echo $gifthunt->id; ?>;
const gifthuntNonce = '<?php echo wp_create_nonce( "gifthuntfree-nonce-string" ); ?>';
</script>