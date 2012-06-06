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
	if (!current_user_can(MAILUSERS_NOTIFY_USERS_CAP)) {		
		wp_die(__("You are not allowed to notify users about posts and pages.", MAILUSERS_I18N_DOMAIN));
	} 
?>

<?php
	global $user_identity, $user_email, $user_ID;

	$err_msg = '';
	
	get_currentuserinfo();
	$from_name = $user_identity;
	$from_address = $user_email;
	$mail_format = mailusers_get_default_mail_format();

	// Send the email if it has been requested
	if (array_key_exists('send', $_POST) && $_POST['send']=='true') {	
	// Analyse form input, check for blank fields
	if ( isset( $_POST['post_id'] ) ) {
		$post_id = $_POST['post_id'];
	}
	
	if ( !isset( $_POST['send_roles'] ) && !isset( $_POST['send_users'] ) ) {
		$err_msg = $err_msg . __('You must select at least a recipient.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$send_roles = isset($_POST['send_roles']) ? $_POST['send_roles'] : array();
		$send_users = isset($_POST['send_users']) ? $_POST['send_users'] : array();
	}
	
	if ( !isset( $_POST['subject'] ) || trim($_POST['subject'])=='' ) {
		$err_msg = $err_msg . __('You must enter a subject.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$original_subject = $_POST['subject'];
	}
	
	if ( !isset( $_POST['mailContent'] ) || trim($_POST['mailContent'])=='' ) {
		$err_msg = $err_msg . __('You must enter a content.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$original_mail_content = $_POST['mailContent'];
	}
	}

	if (!isset($send_roles)) {
		$send_roles = array();
	}

	if (!isset($mail_format)) {
		$mail_format = mailusers_get_default_mail_format();
	}

	if (!isset($original_subject)) {
		$original_subject = '';
	}

	if (!isset($original_mail_content)) {
		$original_mail_content = '';
	}	
	
	// If error, we simply show the form again
	if (array_key_exists('send', $_POST) && ($_POST['send']=='true') && ($err_msg == '')) {
		// No error, send the mail
?>

	<div class="wrap">
	<?php 
		// Fetch users
		// --
		$users_from_roles = mailusers_get_recipients_from_roles($send_roles, $user_ID, MAILUSERS_ACCEPT_NOTIFICATION_USER_META);
		$users_from_ids = mailusers_get_recipients_from_ids($send_users, $user_ID, MAILUSERS_ACCEPT_NOTIFICATION_USER_META);
		$recipients = array_merge($users_from_roles, $users_from_ids);

		if (empty($recipients)) {
	?>
			<p><strong><?php _e('No recipients were found.', MAILUSERS_I18N_DOMAIN); ?></strong></p>
	<?php
		} else {	
			mailusers_send_mail($recipients, format_to_post($original_subject), $original_mail_content, $mail_format, $from_name, $from_address);
	?>
			<div class="updated fade">
				<p><?php echo sprintf(__("Notification sent to %s user(s).", MAILUSERS_I18N_DOMAIN), count($recipients)); ?></p>
			</div>
	<?php
			include 'email_users_notify_form.php';
		}
	?>
	</div>
	
<?php
	} else {
		// Redirect to the form page
		include 'email_users_notify_form.php';
	}
?>
