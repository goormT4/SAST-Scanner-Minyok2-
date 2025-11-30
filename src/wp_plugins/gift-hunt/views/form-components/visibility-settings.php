<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>
<div class="gifthunt-card">
  <div class="gifthunt-card__section">
    <h3 class="gifthunt-card__title">Session visibility</h3>
    <p>At a given time you can have only 1 active session running in your site. Use the options below to turn on or off the session and define the start and end dates.</p>
  </div>

  <div class="gifthunt-card__section">
    <div class="gifthunt-form-group">
      <label>
        <input type="checkbox" name="active" id="active" <?php
        if ( $gifthunt->active ) {
          echo "checked";
        }
        ?> />
        Active
      </label>
      <p class="gifthunt-form-group__help-text">
        Choose this option if you want to <strong>turn ON</strong> this Gift Hunt Session
      </p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( ! $gifthunt->active ) {
      echo "hidden";
    }
    ?>" id="visible-from-section">
    <div class="gifthunt-form-group">
      <label for="visible_from" class="gifthunt-form-group__label">Session start date</label>
      <input type="text" class="gifthunt-input-field" id="visible_from" name="visible_from" value="<?php echo esc_html( $gifthunt->visible_from ); ?>" data-gifthunt-error="Session start date is required" />
      <p class="gifthunt-form-group__help-text">If the Hunt Session is Active, your session will be visible for your visitors from this date</p>
    </div>
  </div>

  <div class="gifthunt-card__section <?php
    if ( ! $gifthunt->active ) {
      echo "hidden";
    }
    ?>" id="visible-to-section">
    <div class="gifthunt-form-group">
      <label for="visible_to" class="gifthunt-form-group__label">Session end date</label>
      <input type="text" class="gifthunt-input-field" id="visible_to" name="visible_to" value="<?php echo esc_html( $gifthunt->visible_to ); ?>" data-gifthunt-error="Session end date is required" />
      <p class="gifthunt-form-group__help-text">This is the last day your session will be active</p>
    </div>
  </div>

</div>

<?php
if ( $gifthunt->id ) {
  ?>
  <div class="gifthunt-card">
    <div class="gifthunt-card__section gifthunt-card__section--secondary">
      <h3 class="gifthunt-card__title">Danger zone</h3>
      <p>If you'd like to delete this session and all the collected user information, click the button below.<br />If you'd like to delete only one hunter or all the hunters of this session, visit the following page: <a href="admin.php?page=gifthuntfree-sessions&p=hunters&id=<?php echo esc_attr( $gifthunt->id ); ?>">Hunters list</a></p>
    </div>

    <div class="gifthunt-card__section gifthunt-card__section--secondary">
      <div class="gifthunt-form-group">
        <button type="button" class="button button-link-delete" id="button--session-delete">
          Delete session
        </button>
      </div>
    </div>
  </div>
  <?php
}
?>