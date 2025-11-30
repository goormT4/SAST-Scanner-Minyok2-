<?php
if ( !function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>
<div class="wrap">
  <h1 class="wp-heading-inline">Mailchimp Log</h1>
  <hr class="wp-header-end" />

  <ul class="subsubsub">
    <li>
      <?php
      if ( count( $gifthunt_mailchimp_log ) > 1 ) {
        echo count( $gifthunt_mailchimp_log ) . " items";
      } elseif ( count( $gifthunt_mailchimp_log ) == 1 ) {
        echo "1 item";
      } elseif ( count( $gifthunt_mailchimp_log ) == 0 ) {
        echo "The log is empty";
      }
      ?>
    </li>
  </ul>

  <div class="tablenav top">
  </div>

	<table class="wp-list-table widefat fixed striped posts">
    <thead>
      <tr>
        <th scope="col" class="column-title manage-column column-primary">
          ID
        </th>

        <th scope="col" class="column-title manage-column">
          Hunter ID
        </th>

        <th scope="col" class="column-title manage-column">
          Log data
        </th>
      </tr>
    </thead>
    
    <tbody>
      <?php
      if( count( $gifthunt_mailchimp_log ) ){
        foreach( $gifthunt_mailchimp_log as $log_item ){
          ?>
          <tr>
            <td><?php echo intval( $log_item->id ); ?></td>
            <td><?php echo intval( $log_item->hunter_id ); ?></td>
            <td class="gifthunt-log-data">
              <?php
              echo stripslashes_deep( $log_item->log );
              ?>
            </td>
          </tr>
          <?php
        }
      } else {
        ?>
        <tr>
          <td colspan="3">The log is empty</td>
        </tr>
        <?php
      }
      ?>
    </tbody>
    
    <tfoot>
      <tr>
        <th scope="col" class="column-title manage-column column-primary">
          ID
        </th>

        <th scope="col" class="column-title manage-column">
          Hunter ID
        </th>

        <th scope="col" class="column-title manage-column">
          Log data
        </th>
      </tr>
    </tfoot>
  </table>
</div>