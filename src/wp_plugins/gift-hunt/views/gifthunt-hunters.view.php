<?php
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>

<div class="wrap gifthunt-hunters-list">
	<div class="gifthunt-page-actions gifthunt-page-actions--header">
		<h1>
			<?php echo esc_html( $gifthunt->name ); ?> - Hunters
			<small><br /><a href="admin.php?page=gifthuntfree-sessions&p=session-edit&id=<?php echo intval( $gifthunt->id ); ?>" class="gifthunt-button--link">View session settings</a></small>
		</h1>

		<form action="admin-post.php" method="POST" target="_blank">
			<input type="hidden" name="action" value="export_hunters_free">
			<input type="hidden" name="id" value="<?php echo esc_attr( $gifthunt->id ); ?>">
			<?php
			if ( count( $gifthuntHunters ) >= 1 ) {
				?>
				<button type="submit" class="gifthunt-button button button-primary gifthunt-export-hunters">
					Export hunters
					<i class="hidden">
						<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
							</path>
						</svg>
					</i>
				</button>
				<?php
			}
			?>
		</form>
	</div>

	<?php
	/**
	 * Display Mailchimp integration notice if necessary
	 */
	if( isset( $mailchimp_default_settings->active ) && $mailchimp_default_settings->active == 1 ){
		?>
		<div id="mailchimp-notice" class="subpage-notice notice-info"><p><strong>Mailchimp integration is active.</strong> The collected user information will be sent to your Mailchimp lists. <a href="admin.php?page=gifthuntfree-sessions&p=mailchimp-integration">You can update your Mailchimp settings here</a>.</p></div>
		<?php
	}

	/**
	 * Display custom popup notice if necessary
	 */
	if( isset( $gifthunt->session_type ) && $gifthunt->session_type == "custom_popup" ){
		?>
		<div class="subpage-notice notice-info"><p>This is a session with a custom popup content and not collecting user information.</p></div>
		<?php
	}
	?>

	<ul class="subsubsub">
    <li>
      <?php
      if ( count( $gifthuntHunters ) > 1 ) {
        echo "<span class='hunter-count' data-count='" . count( $gifthuntHunters ) . "'>" . count( $gifthuntHunters ) . "</span> hunters";
      } elseif ( count( $gifthuntHunters ) == 1 ) {
        echo "<span class='hunter-count' data-count='1'>1</span> hunter";
      } elseif ( count( $gifthuntHunters ) == 0 ) {
        echo "No hunters";
      }
      ?>
    </li>
	</ul>
	
	<?php
	if ( count( $gifthuntHunters ) >= 1 ) {
		?>
		<table class="wp-list-table widefat fixed striped posts">
      <thead>
        <tr>
          <th scope="col" class="column-title manage-column">
            First Name
          </th>

          <th scope="col" class="column-title manage-column">
						Last Name
          </th>

          <th scope="col" class="column-title manage-column">
						Email address
					</th>
					
					<th scope="col" class="column-title manage-column">
						Additional information
					</th>
					
					<th scope="col" class="column-title manage-column">
						Gift
					</th>
					
					<th scope="col" class="column-title manage-column">
						Date
					</th>
					
					<th scope="col" class="column-title manage-column">
          </th>
        </tr>
      </thead>
      <tbody>
				<?php
        foreach ( $gifthuntHunters as $hunter ) {
          ?>
          <tr class="hunter" data-hunter-id="<?php echo esc_attr( $hunter->id ); ?>" id="hunter-<?php echo esc_attr( $hunter->id ); ?>">
					
						<td class="hunter__first-name">
							<strong>
								<?php
								echo esc_html( stripslashes_deep( $hunter->first_name ) );
								?>
							</strong>
						</td>

						<td class="hunter__last_name">
							<strong>
								<?php
								echo esc_html( stripslashes_deep( $hunter->last_name ) );
								?>
							</strong>
						</td>

						<td class="hunter__email">
							<a href="mailto:<?php echo esc_html( stripslashes_deep( $hunter->email ) ); ?>" target="_blank" title="Send mail to <?php echo esc_html( stripslashes_deep( $hunter->email ) ); ?>"><?php echo esc_html( stripslashes_deep( $hunter->email ) ); ?></a>
						</td>

						<td class="hunter__additional-information">
							<?php
							echo esc_html( stripslashes_deep( $hunter->additional_information ) );
							?>
						</td>

						<td class="hunter__gift">
							<?php
							echo esc_html( stripslashes_deep( $hunter->gift ) );
							?>
						</td>

						<td class="hunter__created">
							<?php
							echo esc_html( stripslashes_deep( $hunter->created ) );
							?>
						</td>

						<td align="right">
							<button 
								type="button"
								title="Delete hunter"
								class="button gifthunt-button--danger button-delete-hunter"
								data-hunter-id="<?php echo esc_attr( $hunter->id ); ?>"
								data-hunter-first-name="<?php echo esc_html( stripslashes_deep( $hunter->first_name ) ); ?>"
								data-hunter-last-name="<?php echo esc_html( stripslashes_deep( $hunter->last_name ) ); ?>"
								data-hunter-email="<?php echo esc_html( stripslashes_deep( $hunter->email ) ); ?>"
								data-hunter-gift="<?php echo esc_html( stripslashes_deep( $hunter->gift ) ); ?>">
									&times;
							</button>
						</td>
          </tr>
					<?php
        }
        ?>
      </tbody>

      <tfoot>
        <tr>
          <th scope="col" class="column-title manage-column">
            First Name
          </th>

          <th scope="col" class="column-title manage-column">
						Last Name
          </th>

          <th scope="col" class="column-title manage-column">
						Email address
					</th>
					
					<th scope="col" class="column-title manage-column">
						Additional information
					</th>
					
					<th scope="col" class="column-title manage-column">
						Gift
					</th>
					
					<th scope="col" class="column-title manage-column">
						Date
					</th>
					
					<th scope="col" class="column-title manage-column">
          </th>
        </tr>
      </tfoot>
		</table>
		
		<div class="gifthunt-page-actions">
			<div class="gifthunt-page-actions--secondary">
				<a href="admin.php?page=gifthuntfree-sessions" class="gifthunt-button--link">Back to session list</a>
				<button type="button" class="button gifthunt-button--danger" id="button-delete-all-hunters">Delete all hunters</button>
			</div>
			
			<form action="admin-post.php" method="POST" target="_blank">
				<input type="hidden" name="action" value="export_hunters_free">
				<input type="hidden" name="id" value="<?php echo esc_attr( $gifthunt->id ); ?>">
				<button type="submit" class="gifthunt-button button button-primary button-hero gifthunt-export-hunters">
					Export hunters
					<i class="hidden">
						<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
							</path>
						</svg>
					</i>
				</button>
			</form>
		</div>
		<?php
	} else {
		?>
		<p class="gifthunt-hunters-list__empty-state">There are no hunters in this gifthunt session</p>
		<p>
			<a href="admin.php?page=gifthuntfree-sessions" class="gifthunt-button--link">Back to session list</a>
		</p>
		<?php
	}
	?>

	<div class="gifthunt-helptext">
		<p>
			<span class="gifthunt-helptext__icon">
				<svg viewBox="0 0 20 20" focusable="false" aria-hidden="true"><circle cx="10" cy="10" r="9" fill="#ffffff"></circle><path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m0-4a1 1 0 1 0 0 2 1 1 0 1 0 0-2m0-10C8.346 4 7 5.346 7 7a1 1 0 1 0 2 0 1.001 1.001 0 1 1 1.591.808C9.58 8.548 9 9.616 9 10.737V11a1 1 0 1 0 2 0v-.263c0-.653.484-1.105.773-1.317A3.013 3.013 0 0 0 13 7c0-1.654-1.346-3-3-3"></path></svg>
			</span>
			Learn more about
			<a href="https://gifthuntplugin.com/gift-hunt-best-practices.html" target="_blank" class="gifthunt-link--external">how to get the most out of your Gift Hunt sessions</a>
			<span class="gifthunt-external-link-icon">
				<svg viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M13 12a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H6c-.575 0-1-.484-1-1V7a1 1 0 0 1 1-1h1a1 1 0 0 1 0 2v5h5a1 1 0 0 1 1-1zm-2-7h4v4a1 1 0 1 1-2 0v-.586l-2.293 2.293a.999.999 0 1 1-1.414-1.414L11.586 7H11a1 1 0 0 1 0-2z"></path></svg>
			</span>
		</p>
	</div>
</div>

<div class="gifthunt-form-result__modal <?php echo !isset( $_GET["msg"] ) ? 'hidden' : ''; ?>">
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

		<div class="gifthunt-form-result gifthunt-form-result--success <?php echo ( isset( $_GET["msg"] ) && $_GET["msg"] == "deleted" ) ? '' : 'hidden'; ?>">
			<div class="gifthunt-form-result__content">
				<?php
				if ( isset( $_GET["msg"] ) ) {
					$currentMsg = sanitize_title( $_GET["msg"] );

					switch( $currentMsg ) {
						case "deleted":
							echo "All hunters have been removed";
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

<div class="gifthunt-form__delete-hunter-confirm-modal hidden">
	<div class="gifthunt-form__delete-hunter-confirm-modal-content">
		<h3>Are you sure you want to delete the selected hunter?</h3>
		<p>Once you've deleted the hunter data, you can't undo this action. Make sure you only delete the data if it's necessary.</p>
		<p>The gift (<span class="hunter-collected-gift"></span>) collected by the selected hunter will become available for other visitors to collect.</p>
		<p>
			<strong>Selected hunter:</strong>
			<span id="hunter-first-name"></span>
			<span id="hunter-last-name"></span>
			<span id="hunter-email"></span>,
			Collected gift: 
			<span class="hunter-collected-gift"></span>
		</p>
		<div class="gifthunt-form__delete-confirm-actions">
			<button type="button" class="button" id="button--cancel-delete-hunter">Cancel</button>
			<button type="button" class="button gifthunt-button--danger" id="button--confirm-delete-hunter">
				Delete hunter
				<i class="hidden">
					<svg class="gifthunt-spin" width="25" height="15" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M33 20.5C33 22.9723 32.2669 25.389 30.8934 27.4446C29.5199 29.5002 27.5676 31.1024 25.2835 32.0485C22.9995 32.9946 20.4861 33.2421 18.0614 32.7598C15.6366 32.2775 13.4093 31.087 11.6612 29.3388C9.91301 27.5907 8.7225 25.3634 8.24018 22.9386C7.75787 20.5139 8.00541 18.0005 8.95151 15.7165C9.8976 13.4324 11.4998 11.4801 13.5554 10.1066C15.611 8.73311 18.0277 8 20.5 8L20.5 13C19.0166 13 17.5666 13.4399 16.3332 14.264C15.0999 15.0881 14.1386 16.2594 13.5709 17.6299C13.0032 19.0003 12.8547 20.5083 13.1441 21.9632C13.4335 23.418 14.1478 24.7544 15.1967 25.8033C16.2456 26.8522 17.582 27.5665 19.0368 27.8559C20.4917 28.1453 21.9997 27.9968 23.3701 27.4291C24.7406 26.8614 25.9119 25.9001 26.736 24.6668C27.5601 23.4334 28 21.9834 28 20.5H33Z" fill="white">
						</path>
					</svg>
				</i>
			</button>
		</div>

		<button type="button" class="gifthunt-form__delete-hunter-confirm-modal__close-button">
			<strong>
				&times;
			</strong>
		</button>
	</div>
</div>

<div class="gifthunt-form__delete-confirm-modal hidden">
	<div class="gifthunt-form__delete-confirm-modal-content">
		<h3>Are you sure you want to delete all hunters in this session?</h3>
		<p>Once you've deleted all the hunters, you can't undo this action. Make sure you only delete the data if it's necessary.</p>
		<p>The gifts collected by the hunters will become available for other visitors to collect.</p>
		<div class="gifthunt-form__delete-confirm-actions">
			<button type="button" class="button" id="button--cancel-delete">Cancel</button>
			<button type="button" class="button gifthunt-button--danger" id="button--confirm-delete">
				Delete all hunters
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

<script>
const giftSessionId = <?php echo intval( $gifthunt->id ); ?>;
const gifthuntfreeNonce = '<?php echo wp_create_nonce( "gifthuntfree-nonce-string" ); ?>';
const huntersCount = <?php echo count( $gifthuntHunters ); ?>;
</script>
<script src="<?php echo plugins_url( "assets/scripts/gifthunt-hunters.js", __DIR__ ); ?>"></script>