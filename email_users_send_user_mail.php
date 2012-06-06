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
	if (	!current_user_can(MAILUSERS_EMAIL_SINGLE_USER_CAP)
		|| 	!current_user_can(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP)) {	
		wp_die(__('You are not allowed to send emails to users.', MAILUSERS_I18N_DOMAIN));
	} 
?>

<?php
	global $user_identity, $user_email, $user_ID;

	$err_msg = '';
	
	// Send the email if it has been requested
	if (array_key_exists('send', $_POST) && $_POST['send']=='true') {
	get_currentuserinfo();
	$from_name = $user_identity;
	$from_address = $user_email;
	
	// Analyse form input, check for blank fields
	if ( !isset( $_POST['mail_format'] ) || trim($_POST['mail_format'])=='' ) {
		$err_msg = $err_msg . __('You must specify the mail format.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$mail_format = $_POST['mail_format'];
	}
	
	if ( !isset($_POST['send_users']) || !is_array($_POST['send_users']) || empty($_POST['send_users']) ) {
		$err_msg = $err_msg . __('You must enter at least a recipient.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$send_users = $_POST['send_users'];
	}
	
	if ( !isset( $_POST['subject'] ) || trim($_POST['subject'])=='' ) {
		$err_msg = $err_msg . __('You must enter a subject.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$subject = $_POST['subject'];
	}
	
	if ( !isset( $_POST['mailContent'] ) || trim($_POST['mailContent'])=='' ) {
		$err_msg = $err_msg . __('You must enter some content.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	} else {
		$mail_content = $_POST['mailContent'];
	}
	}
	if (!isset($send_users)) {
		$send_users = array();
	}

	if (!isset($mail_format)) {
		$mail_format = mailusers_get_default_mail_format();
	}

	if (!isset($subject)) {
		$subject = '';
	}

	if (!isset($mail_content)) {
		$mail_content = '';
	}	
	

	// If error, we simply show the form again
	if (array_key_exists('send', $_POST) && ($_POST['send']=='true') && ($err_msg == '')) {
		// No error, send the mail
		
		// Do some HTML homework if needed
		//--
		if ($mail_format=='html') {
			$mail_content = wpautop($mail_content);
		}		
	?>
	<div class="wrap">
	<?php 
		// Fetch users
		// --
		$recipients = mailusers_get_recipients_from_ids($send_users, $user_ID);

		if (empty($recipients)) {
	?>
			<p><strong><?php _e('No recipients were found.', MAILUSERS_I18N_DOMAIN); ?></strong></p>
	<?php
		} else {
			$num_sent = mailusers_send_mail($recipients, $subject, $mail_content, $mail_format, $from_name, $from_address);
			if (false === $num_sent) {
				echo '<div class="error fade"><p>There was a problem trying to send email to users.</p></div>';
			} else if (0 === $num_sent) {
				echo '<div class="error fade"><p>No email has been sent to other users. This may be because no valid email addresses were found.</p></div>';
			} else if ($num_sent > 0 && $num_sent == count($recipients)){
	?>
			<div class="updated fade">
				<p><?php echo sprintf(__('Email sent to %s user(s).', MAILUSERS_I18N_DOMAIN), $num_sent); ?></p>
			</div>
	<?php
			} else if ($num_sent > count($recipients)) {
				echo '<div class="error fade"><p>WARNING: More email has been sent than the number of recipients found.</p></div>';
			} else {
				echo '<div class="updated fade"><p>Email has been sent to $num_sent users, but '.count($recipients).' recipients were originally found. Perhaps some users don\'t have valid email addresses?</p></div>';
			}
			include('email_users_user_mail_form.php');
		}
	?>
	</div>
	
<?php
	} else {
		include('email_users_user_mail_form.php');
		// No error, send the mail
	}
?>
