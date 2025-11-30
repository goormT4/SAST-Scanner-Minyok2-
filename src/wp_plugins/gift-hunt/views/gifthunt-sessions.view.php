<?php
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>

<div class="wrap">

  <h1 class="wp-heading-inline">Gift Hunt</h1>
  <a href="admin.php?page=gifthuntfree-sessions&p=session-create" class="page-title-action">Add New Gift Hunt Session</a>

  <hr class="wp-header-end" />

  <?php
  if ( isset( $_GET["msg"] ) && $_GET["msg"] == "deleted" ) {
    ?>
    <div id="message" class="updated notice is-dismissible"><p>Session <strong>deleted</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
    <?php
  }
  ?>

  <ul class="subsubsub">
    <li>
      <?php
      if ( count( $gifthunt_sessions ) > 1 ) {
        echo count( $gifthunt_sessions ) . " Gift Hunt sessions";
      } elseif ( count( $gifthunt_sessions ) == 1 ) {
        echo "1 Gift Hunt session";
      } elseif ( count( $gifthunt_sessions ) == 0 ) {
        echo "You haven't created any sessions yet";
      }
      ?>
    </li>
  </ul>

  <div class="tablenav top">
  </div>

  <?php
  /**
   * Display Mailchimp integration notice if necessary
   */
  if( isset( $mailchimp_default_settings->active ) && $mailchimp_default_settings->active == 1 ){
    ?>
    <div id="mailchimp-notice" class="notice notice-info"><p><strong>Mailchimp integration is active.</strong> The collected user information will be sent to your Mailchimp lists. <a href="admin.php?page=gifthuntfree-sessions&p=mailchimp-integration">You can update your Mailchimp settings here</a>.</p></div>
    <?php
  }
  ?>

  <?php
  if ( $gifthunt_sessions ) {
    ?>
    <table class="wp-list-table widefat fixed striped posts">
      <thead>
        <tr>
          <th scope="col" class="column-title manage-column column-primary">
            Session name
          </th>

          <th scope="col" class="column-title manage-column">
            Session status
          </th>

          <th scope="col" class="column-title manage-column">
            Session visible from
          </th>

          <th scope="col" class="column-title manage-column">
            Session visible to
          </th>

          <th scope="col" class="column-title manage-column">
          </th>

          <th scope="col" class="column-title manage-column">
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach( $gifthunt_sessions as $gifthunt_session ) {
          ?>
          <tr>
            <td>
              <strong>
                <a href="admin.php?page=gifthuntfree-sessions&p=session-edit&id=<?php echo esc_attr( $gifthunt_session->id ); ?>" class="row-title">
                  <?php
                  echo $gifthunt_session->name;
                  ?>
                </a>
              </strong>
            </td>

            <td>
              <?php
              if ( $gifthunt_session->active == true ) {
                ?>
                <span class="gifthunt-badge gifthunt-badge--active">active</span>
                <?php
              } else {
                ?>
                <span class="gifthunt-badge gifthunt-badge--inactive">inactive</span>
                <?php
              }
              ?>              
            </td>

            <td>
              <?php
              // session visible from
              echo $gifthunt_session->visible_from;
              ?>
            </td>

            <td>
              <?php
              // session visible to
              echo $gifthunt_session->visible_to;
              ?>
            </td>

            <td>
              <?php
              if( $gifthunt_session->session_type == "custom_popup" ){
                ?>
                <span class="gifthunt-session-list-info">
                  Not collecting hunters (session with custom popup content)
                </span>
                <?php
              } else {
                ?>
                <a href="admin.php?page=gifthuntfree-sessions&p=hunters&id=<?php echo intval($gifthunt_session->id); ?>">View Hunters</a>
                <?php
              }
              ?>
            </td>

            <td>
              <a href="admin.php?page=gifthuntfree-sessions&p=session-edit&id=<?php echo intval( $gifthunt_session->id ); ?>">Edit session</a>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>

      <tfoot>
        <tr>
          <th scope="col" class="column-title manage-column column-primary">
            Session name
          </th>

          <th scope="col" class="column-title manage-column">
            Session status
          </th>

          <th scope="col" class="column-title manage-column">
            Session visible from
          </th>

          <th scope="col" class="column-title manage-column">
            Session visible to
          </th>

          <th scope="col" class="column-title manage-column">
          </th>

          <th scope="col" class="column-title manage-column">
          </th>
        </tr>
      </tfoot>
    </table>

    <?php
  } else {
    ?>
    <div class="gifthunt-welcome-panel">
      <div class="gifthunt-welcome-panel-content">
        <h2>Welcome to Gift Hunt!</h2>
        <p class="about-description">
          You haven't created any sessions yet. Click the button below to get started.
        </p>

        <p>&nbsp;</p>

        <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/yo3NSaCqY2w" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

        <h3>Get started</h3>
        <a class="button button-primary button-hero" href="admin.php?page=gifthuntfree-sessions&p=session-create">Add New Gift Hunt Session</a>
        <p>
          or <a href="https://gifthuntplugin.com/create-your-first-gift-hunt-session.html" target="_blank">learn how to create your first Gift Hunt Session</a>
        </p>
      </div>
    </div>
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