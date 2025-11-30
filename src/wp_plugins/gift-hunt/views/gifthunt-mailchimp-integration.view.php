<?php
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>
<div class="wrap">
	<div class="gifthunt-page-actions gifthunt-page-actions--header edit-post-header">
		<h1 class="wp-heading-inline">Mailchimp Integration Settings</h1>

		<button type="submit" class="gifthunt-button button button-primary gifthunt-save-mailchimp-settings <?php echo ( !isset( $mailchimp_default_settings->api_key ) ) ? 'hidden' : ''; ?>">
			Save settings
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
			<div id="mailchimp-notice" class="subpage-notice notice-info"><p><strong>Mailchimp integration is active.</strong> You can turn it off by changing the status at the bottom of this page.</p></div>
			<?php
		}
		?>

			<div class="gifthunt-card">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">How does the integration works?</h3>
					<p>You can connect Gift Hunt to your Mailchimp account so the collected user data will be sent there automatically.</p>
					<p>To do that, you'll need an <a href="https://mailchimp.com/help/about-api-keys/#find+or+generate+your+api+key" target="_blank">API key</a>, an <a href="https://mailchimp.com/help/create-audience/" target="_blank">audience list</a> with at least 4 fields (email, first name, last name, additional information). Luckily, most of these things have already been created for you when you registered to Mailchimp.</p>
					<p>To connect Gift Hunt to Mailchimp, you need to follow these steps:</p>
					<ol>
						<li>Create an <a href="https://mailchimp.com/help/about-api-keys/#find+or+generate+your+api+key" target="_blank">API key</a>, add it to the API Key field below and click on the "Next" button next to the field.<br />When you click "Next", Gift Hunt will connect to Mailchimp to get the audience lists available in your account.</li>
						<li>The lists will become visible in the "List settings" block. Select the one where you'd like to send the collected hunter information and click on the button next to the lists field. Gift Hunt will read the available fields from the selected list.</li>
						<li>In the "Field settings" section you can connect the data fields of Gift Hunt to the fields in Mailchimp. Select one Mailchimp field for all Gift Hunt fields (first name, last name, additional information).</li>
						<li>When your API Key, List and Fields have been set, you can save your settings. You can also turn on the Mailchimp integration by selecting the "Active" option in the "Status" section of this page.<br /><br />
						<strong>⚠️ Important</strong><br />Previously collected user information won't be sent to your list only those that have been collected after you activated your Mailchimp integration. If you collected some user information before that and would like to keep your lists in sync, export your hunters and import the collected data to Mailchimp.
						</li>
					</ol>
					<p>When your Mailchimp integration is active all the collected information will be stored by Gift Hunt and will be sent to the selected Mailchimp list as well.</p>
				</div>
			</div>

			<div class="gifthunt-card">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">Integration Settings</h3>
					<p>To integrate Gift Hunt with your Mailchimp account, <a href="https://mailchimp.com/help/about-api-keys/#find+or+generate+your+api+key" target="_blank">get an API key from your account</a>, then copy and paste it below to get started.</p>

					<div class="gifthunt-card__columns">
						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label" for="api_key">API Key</label>
								<input type="text" class="gifthunt-input-field" name="api_key" id="api_key" value="<?php echo ( isset( $mailchimp_default_settings->api_key ) ) ? $mailchimp_default_settings->api_key : ""; ?>" placeholder="12345678901234567890123456789012-xx1" />
							</div>
						</div>

						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label">&nbsp;</label>
								<button class="gifthunt-button button button-primary button-hero" id="api_key_submit">
									Next
									<i class="hidden">
										<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
											</path>
										</svg>
									</i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="gifthunt-card <?php echo ( !isset( $mailchimp_default_settings->list_id ) ) ? 'hidden' : ''; ?>" id="list_settings">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">List settings</h3>
					<p>Select the list where you'd like to send the collected hunter information and click on the "Next" button.</p>
					<div class="gifthunt-card__columns">
						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label for="list_id" class="gifthunt-form-group__label">Select your list</label>
								<select class="gifthunt-select-field" name="list_id" id="list_id">
									<option value="0">loading...</option>
								</select>
								<input type="hidden" name="list_name" id="list_name" value="<?php echo ( isset( $mailchimp_default_settings->list_name ) ) ? $mailchimp_default_settings->list_name : ''; ?>" />
							</div>
						</div>

						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label">&nbsp;</label>
								<button class="gifthunt-button button button-primary button-hero" id="list_submit">
									Next
									<i class="hidden">
										<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
											</path>
										</svg>
									</i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="gifthunt-card <?php echo ( !isset( $mailchimp_default_settings->fname ) ) ? 'hidden' : ''; ?>" id="merge_fields">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">Field settings</h3>
					<p>In this section you can connect the data fields of Gift Hunt to the fields in Mailchimp. Select one Mailchimp field for all Gift Hunt fields (first name, last name, additional information).</p>
					<p>If you select FNAME for the First Name field that means that the First Name that was collected by Gift Hunt will be sent to the FNAME field in Mailchimp.</p>
					
					<div class="gifthunt-form-group">
						<label for="field_email" class="gifthunt-form-group__label">First Name field merge tag</label>
						<select class="gifthunt-select-field" name="fname" id="fname">
							<option value="0">loading...</option>
						</select>
					</div>

					<div class="gifthunt-form-group">
						<label for="field_email" class="gifthunt-form-group__label">Last Name field merge tag</label>
						<select class="gifthunt-select-field" name="lname" id="lname">
							<option value="0">loading...</option>
						</select>
					</div>
				
					<div class="gifthunt-form-group">
						<label for="field_email" class="gifthunt-form-group__label">Additional Information field merge tag</label>
						<select class="gifthunt-select-field" name="additional" id="additional">
							<option value="0">loading...</option>
						</select>
					</div>
					
				</div>
			</div>
			
			<div class="gifthunt-card <?php
			if( 
				!isset( $mailchimp_default_settings->api_key ) || 
				( isset( $mailchimp_default_settings->api_key ) && strlen( $mailchimp_default_settings->api_key ) < 10 ) ||
				!isset( $mailchimp_default_settings->list_id ) || 
				( isset( $mailchimp_default_settings->list_id ) && strlen( $mailchimp_default_settings->list_id ) < 5 ) ||
				!isset( $mailchimp_default_settings->fname ) ||
				( isset( $mailchimp_default_settings->fname ) && strlen( $mailchimp_default_settings->fname ) < 2 ) ||
				!isset( $mailchimp_default_settings->lname ) || 
				( isset( $mailchimp_default_settings->lname ) && strlen( $mailchimp_default_settings->lname ) < 2 )|| 
				!isset( $mailchimp_default_settings->additional ) ||
				( isset( $mailchimp_default_settings->additional ) && strlen( $mailchimp_default_settings->additional ) < 2 )
			){
				echo "hidden";
			}
			?>" id="integration_status">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">Status</h3>
					<p></p>
					<div class="gifthunt-form-group">
					  <label for="active">
							<input type="checkbox" name="active" id="active" <?php
							if( isset( $mailchimp_default_settings->active) && $mailchimp_default_settings->active == 1 ){
								echo 'checked="checked"';
							}
							?> /> Active
						</label>
						<p class="gifthunt-form-group__help-text">
						  You can turn On or Off your Mailchimp integration by changing the setting above. When your integration is active, all the collected user information will be sent to the Mailchimp list you selected above.
						</p>
					</div>
				</div>
			</div>

			<div id="integration_test_block" class="gifthunt-card">
				<div class="gifthunt-card__section">
					<h3 class="gifthunt-card__title">Test your integration settings</h3>
				</div>

				<div class="gifthunt-card__section">
					<p>To test your integration settings, please add some test value to the fields below.</p>
					<p><strong>Make sure that the email address you use is not already added to the Mailchimp audience list you selected above (<em id="selected_list_name"><?php echo ( isset( $mailchimp_default_settings->list_name ) ) ? $mailchimp_default_settings->list_name : ""; ?></em>)</strong></p>

					<div class="gifthunt-card__columns">
						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label" for="test_email_address">Test email address:</label>
								<input type="email" id="test_email_address" />
							</div>
						</div>

						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label" for="test_first_name">Test first name:</label>
								<input type="text" id="test_first_name" />
							</div>
						</div>
					</div>

					<div class="gifthunt-card__columns">
						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label" for="test_last_name">Test last name:</label>
								<input type="text" id="test_last_name" />
							</div>
						</div>

						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label" for="test_additional_information">Test additional information:</label>
								<input type="text" id="test_additional_information" />
							</div>
						</div>
					</div>

					<div class="gifthunt-card__columns">
						<div class="gifthunt-card__column">
							<p>When you press the "Test integration" button, Gift Hunt will try to add a contact to the selected list with the data you added to the test fields above.</p>
						</div>

						<div class="gifthunt-card__column">
							<div class="gifthunt-form-group">
								<label class="gifthunt-form-group__label">&nbsp;</label>
								<button class="gifthunt-button button button-secondary button-hero" id="test_integration_submit">
									Test integration
									<i class="hidden">
										<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
											</path>
										</svg>
									</i>
								</button>
							</div>
						</div>
					</div>
				</div>


			</div>


		</div>

		<div class="gifthunt-form__sidebar">
		</div>
	</form>
</div>

<div class="gifthunt-page-actions">
	<a href="admin.php?page=gifthuntfree-sessions" class="gifthunt-button--link">Back to session list</a>

	<button type="submit" class="gifthunt-button button button-primary button-hero gifthunt-save-mailchimp-settings <?php echo ( !isset( $mailchimp_default_settings->api_key ) ) ? 'hidden' : ''; ?>">
		Save settings
		<i class="hidden">
			<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
				</path>
			</svg>
		</i>
	</button>
</div>


<div class="gifthunt-form-result__modal hidden">
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

		<div class="gifthunt-form-result gifthunt-form-result--success hidden">
			<div class="gifthunt-form-result__content">
				Success
			</div>
			<button type="button" class="gifthunt-form-result__close-button">
				<strong>
					&times;
				</strong>
			</button>
		</div>
	</div>
</div>

<script>
const gifthuntNonce = '<?php echo wp_create_nonce( "gifthuntfree-nonce-string" ); ?>';
const defaultApiKey = '<?php echo ( isset( $mailchimp_default_settings->api_key ) ) ? $mailchimp_default_settings->api_key : ""; ?>';
const defaultListId = '<?php echo ( isset( $mailchimp_default_settings->list_id ) ) ? $mailchimp_default_settings->list_id : ""; ?>';
const defaultListName = '<?php echo ( isset( $mailchimp_default_settings->list_name ) ) ? $mailchimp_default_settings->list_name : ""; ?>';
const defaultFname = '<?php echo ( isset( $mailchimp_default_settings->fname ) ) ? $mailchimp_default_settings->fname : ""; ?>';
const defaultLname = '<?php echo ( isset( $mailchimp_default_settings->lname ) ) ? $mailchimp_default_settings->lname : ""; ?>';
const defaultAdditional = '<?php echo ( isset( $mailchimp_default_settings->additional ) ) ? $mailchimp_default_settings->additional : ""; ?>';
</script>
<script src="<?php echo plugins_url( "assets/scripts/gifthunt-mailchimp.js", __DIR__ ); ?>"></script>