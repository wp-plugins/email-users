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
	if (!mailusers_is_current_user_allowed_to_mail()) {
?>
	<div id="message" class="error">
		<p><?php _e("You are not allowed to send mails with this plugin.", MAILUSERS_I18N_DOMAIN); ?></p>
	</div>
<?php	
		exit();
	} 
?>

<?php
	require(ABSPATH.'wp-content/plugins/email-users/class.phpmailer.php');
	$err_msg = '';
	
	// Analyse form input, check for blank fields
	if ( !isset( $_POST['fromName'] ) || trim($_POST['fromName'])=='' ) {
		$err_msg = $err_msg . __('You must enter a sender name.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$from_name = $_POST['fromName'];
	}
	
	if ( !isset( $_POST['mail_format'] ) || ($_POST['mail_format']!='html' && $_POST['mail_format']!='plaintext') ) {
		$err_msg = $err_msg . __('You must specify a mail format (HTML or plain text).', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$mail_format = $_POST['mail_format'];
	}
	
	if ( !isset( $_POST['fromAddress'] ) || trim($_POST['fromAddress'])=='' ) {
		$err_msg = $err_msg . __('You must enter a sender mail address.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$from_address = $_POST['fromAddress'];
	}
	
	if ( !isset( $_POST['send_role'] ) || trim($_POST['send_role'])=='' ) {
		$err_msg = $err_msg . __('You must enter a recipient category.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$send_role = $_POST['send_role'];
		$to = $send_role;
	}
	
	if ( !isset( $_POST['subject'] ) || trim($_POST['subject'])=='' ) {
		$err_msg = $err_msg . __('You must enter a subject.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$original_subject = $_POST['subject'];
	}
	
	if ( !isset( $_POST['mailContent'] ) || trim($_POST['mailContent'])=='' ) {
		$err_msg = $err_msg . __('You must enter a content.', MAILUSERS_I18N_DOMAIN) . '<br/>';
	}
	else {
		$original_mail_content = $_POST['mailContent'];
	}
	
	// If error, we simply show the form again
	if ( $err_msg!='' ) {
		// Redirect to the form page
		include 'email_users_form.php';
	}
	else {
		// No error, send the mail
?>
	<div class="wrap">
	
		
<?php 
		// Fetch users
		// --
		$users = mailusers_get_users_from_role( $send_role );

		if ( 0==count( $users ) ) {
			echo '<p><strong>' 
				. __('No users in this group', MAILUSERS_I18N_DOMAIN) 
				. '</strong></p>';
		}
		else {		
			// --------
			// Bug correction from Cyril Crua, prevents savage backslashes to be
			// inserted and retrieved from database before special characters.
			if (get_magic_quotes_gpc()) {
			    $from_address = stripslashes($from_address);
			    $from_name = stripslashes($from_name);
			    $original_subject = stripslashes($original_subject);
			    $original_mail_content = stripslashes($original_mail_content);
			}
			// End of bug correction
			//-------

			// Prepare the mail
			// --
			$mail = new PHPMailer();
			
			// Mailer used
			// --
			if (mailusers_get_mail_method()=='smtp') {
				$mail->Mailer  	= 'smtp';
				$mail->Host     = mailusers_get_smtp_server();
				$mail->Port     = mailusers_get_smtp_port();
				if (mailusers_get_smtp_user()!='') {
         			$mail->SMTPAuth  = true;
					$mail->Username  = mailusers_get_smtp_user();
					$mail->Password  = mailusers_get_smtp_password();
				} else {
         			$mail->SMTPAuth  = false;
         		}	
			} else {
				$mail->Mailer   	= 'mail';
			}
			
			// Mail format
			// --
			if ($mail_format=='html') {
				$mail->IsHTML(true);
			} else {
				$mail->IsHTML(false);
			}
			
			// Mail details
			// --
			$mail->From     	= $from_address;
			$mail->FromName 	= $from_name;
			$mail->Subject 		= $original_subject;
			$mail->Body    		= $original_mail_content;
			
			// Our list of users to show as result
			// --
			$user_list_as_string = '';
			
			// Add author of the mail as main recipient
			// --
			$mail->AddAddress( $from_address, $from_name );
			
			// Add users as BCC
			// --
			foreach ( $users as $user ) {
				$mail->AddBCC( $user->user_email, $user->display_name );
				$user_list_as_string .= '<li>'.$user->display_name.' ('.$user->user_email.')</li>';
			}
			
			// Send mail and show result
			// --
			if ( !$mail->send() ) {
				echo '<p style="color:red;">' 
					. __('Failure:', MAILUSERS_I18N_DOMAIN) 
					. ' ' . $mail->ErrorInfo 
					. '</p>';
			} else {		
				echo '<p><strong>' 
					. __('Result of the mailing', MAILUSERS_I18N_DOMAIN) 
					. '</strong></p>';
				echo '<p style="color:green;"><ul>';
				echo $user_list_as_string;
				echo '</ul></p>';
			}
		}
	}
?>
		</ul></p>
	</div>
