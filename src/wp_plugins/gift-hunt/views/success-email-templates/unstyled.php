<?php
if ( ! function_exists( "add_action" ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
?>

<div id="gifthunt-success-email-template-unstyled" class="hidden">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" bgcolor="#eeeeee">
    <tr align="center" bgcolor="#eeeeee">
      <td align="center" valign="top" bgcolor="#eeeeee">
        
        <table width="500" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
          <tr align="center" bgcolor="#ffffff">
            <td align="center" height="20" width="500" colspan="3" bgcolor="#ffffff"></td>
          </tr>

          <tr align="center" bgcolor="#ffffff">
            <td align="center" width="20" bgcolor="#ffffff"></td>
            <td align="left" width="460" bgcolor="#ffffff">
              <p>Hi %firstname%,</p>
              <p>the gift you just found on our website is the following:<br />%gift%</p>
              <p>Make sure you keep this email in a good place so you'll find the gift when you need it. If you have any questions, don't hesitate to get in touch. For contact information, visit our website.</p>
              <p>Have a great day.</p>
            </td>
            <td align="center" width="20" bgcolor="#ffffff"></td>
          </tr>

          <tr align="center" bgcolor="#ffffff">
            <td align="center" height="20" width="500" colspan="3" bgcolor="#ffffff"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>