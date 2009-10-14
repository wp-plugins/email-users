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
	require(ABSPATH.'wp-content/plugins/email-users/class-phpmailer.php');
	$err_msg = '';
	
	// Analyse form input, check for blank fields
	if ( !isset( $_POST['fromName'] ) || trim($_POST['fromName'])=='' ) {
		$err_msg = $err_msg . 'You must enter a sender name.<br/>';
	}
	else {
		$fromName = $_POST['fromName'];
	}
	
	if ( !isset( $_POST['fromAddress'] ) || trim($_POST['fromAddress'])=='' ) {
		$err_msg = $err_msg . 'You must enter a sender mail address.<br/>';
	}
	else {
		$fromAddress = $_POST['fromAddress'];
	}
	
	if ( !isset( $_POST['send_role'] ) || trim($_POST['send_role'])=='' ) {
		$err_msg = $err_msg . 'You must enter a recipient category.<br/>';
	}
	else {
		$send_role = $_POST['send_role'];
		$to = $send_role;
	}
	
	if ( !isset( $_POST['subject'] ) || trim($_POST['subject'])=='' ) {
		$err_msg = $err_msg . 'You must enter a subject.<br/>';
	}
	else {
		$subject = $_POST['subject'];
	}
	
	if ( !isset( $_POST['mailContent'] ) || trim($_POST['mailContent'])=='' ) {
		$err_msg = $err_msg . 'You must enter a content.<br/>';
	}
	else {
		$mailContent = $_POST['mailContent'];
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
		$users = mailusers_get_users_from_role( $send_role );

		if ( 0==count( $users ) ) {
			echo '<p><strong>No users in this group</strong></p>';
		}
		else {		
			// --------
			// Bug correction from Cyril Crua, prevents savage backslashes to be inserted and retrieved from database before special characters.
			if (get_magic_quotes_gpc()) {
			    $fromAddress = stripslashes($fromAddress);
			    $fromName = stripslashes($fromName);
			    $subject = stripslashes($subject);
			    $mailContent = stripslashes($mailContent);
			}
			// End of bug correction
			//-------

			// Prepare the mail
			$mail = new PHPMailer();
			$mail->From     	= $fromAddress;
			$mail->FromName 	= $fromName;
			$mail->Mailer   	= 'mail';
			$mail->ContentType 	= 'text/html';
			$mail->Subject 		= $subject;
			$mail->Body    		= $mailContent;
			
			// Our list of users to show as result
			$user_list_as_string = '';
			
			// Add author of the mail as main recipient
			$mail->AddAddress( $fromAddress, $fromName );
			
			// Add users as BCC
			foreach ( $users as $user ) {
				$mail->AddBCC( $user->user_email, $user->display_name );
				$user_list_as_string .= '<li>'.$user->display_name.' ('.$user->user_email.')</li>';
			}
			
			// Send mail and show result
			if ( !$mail->send() ) {
				echo '<p style="text-color:red;">Failure: ' . $mail->ErrorInfo . '</p>';
			} else {		
				echo '<p><strong>Result of the mailing</strong></p>';
				echo '<p style="text-color:green;"><ul>';
				echo $user_list_as_string;
				echo '</ul></p>';
			}
		}
	}
?>
		</ul></p>
	</div>
