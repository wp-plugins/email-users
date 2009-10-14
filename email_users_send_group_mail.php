<?php
/*  Copyright 2006 Vincent Prat

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
?>

<?php 
	if (!current_user_can(MAILUSERS_EMAIL_USER_GROUPS_CAP)) {		
		wp_die(__("You are not allowed to send emails to user groups.", MAILUSERS_I18N_DOMAIN));
	} 
?>

<?php
	$err_msg = '';
	
	get_currentuserinfo();
	$from_name = $user_identity;
	$from_address = $user_email;
	
	// Analyse form input, check for blank fields
	if ( !isset( $_POST['mail_format'] ) || trim($_POST['mail_format'])=='' ) {
		$err_msg = $err_msg . __('You must specify the mail format.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$mail_format = $_POST['mail_format'];
	}
	
	if ( !isset($_POST['send_roles']) || !is_array($_POST['send_roles']) || empty($_POST['send_roles']) ) {
		$err_msg = $err_msg . __('You must select at least a role.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$send_roles = $_POST['send_roles'];
	}
	
	if ( !isset( $_POST['subject'] ) || trim($_POST['subject'])=='' ) {
		$err_msg = $err_msg . __('You must enter a subject.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$subject = $_POST['subject'];
	}
	
	if ( !isset( $_POST['mailContent'] ) || trim($_POST['mailContent'])=='' ) {
		$err_msg = $err_msg . __('You must enter a content.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$mail_content = $_POST['mailContent'];
	}
	
	// If error, we simply show the form again
	if ( $err_msg!='' ) {
		// Redirect to the form page
		include 'email_users_group_mail_form.php';
	} else {
		// No error, send the mail
?>

	<div class="wrap">
	<?php 
		// Fetch users
		// --
		$recipients = mailusers_get_recipients_from_roles($send_roles, $user_ID, MAILUSERS_ACCEPT_MASS_EMAIL_USER_META);

		if (empty($recipients)) {
	?>
			<p><strong><?php _e('No recipients were found.', MAILUSERS_I18N_DOMAIN); ?></strong></p>
	<?php
		} else {	
			mailusers_send_mail($recipients, $subject, $mail_content, $mail_format, $from_name, $from_address);
	?>
			<div class="updated fade">
				<p><?php echo sprintf(__("Notification sent to %s user(s).", MAILUSERS_I18N_DOMAIN), count($recipients)); ?></p>
			</div>
	<?php
			include 'email_users_group_mail_form.php';
		}
	?>
	</div>
	
<?php
	}
?>
