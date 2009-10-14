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
	if (!mailusers_is_current_user_allowed_to_configure()) {
?>
	<div id="message" class="error">
		<p><?php _e("You are not allowed to change the options of this plugin.", MAILUSERS_I18N_DOMAIN); ?></p>
	</div>
<?php	
		exit();
	} 
	
?>

<?php
	$mail_user_level = '8';
	$default_subject = '';
	$default_body = '';
	$default_mail_format = 'html';
	$mail_method = 'mail';
	$smtp_server = '';
	$smtp_port = '';
	$smtp_user = '';
	$smtp_password = '';
	
	if ( isset( $_POST['mail_user_level'] ) ) {
		$mail_user_level = $_POST['mail_user_level'];
	}
	
	if ( isset( $_POST['default_subject'] ) ) {
		$default_subject = $_POST['default_subject'];
	}
	
	if ( isset( $_POST['default_body'] ) ) {
		$default_body = $_POST['default_body'];
	}
	
	if ( isset( $_POST['mail_method'] ) ) {
		$mail_method = $_POST['mail_method'];
	}
	
	if (isset( $_POST['default_mail_format'])) {
		$default_mail_format = $_POST['default_mail_format'];
	}

	if (isset( $_POST['smtp_server'])) {
		$smtp_server = $_POST['smtp_server'];
	}
	
	if (isset($_POST['smtp_user'])) {
		$smtp_user = $_POST['smtp_user'];
	}
	
	if (isset($_POST['smtp_password'])) {
		$smtp_password = $_POST['smtp_password'];
	}
	
	if (isset($_POST['smtp_port'])) {
		$smtp_port = $_POST['smtp_port'];
	}
	
	mailusers_update_default_subject( $default_subject );
	mailusers_update_default_body( $default_body );
	mailusers_update_default_mail_format( $default_mail_format );
	mailusers_update_mail_method( $mail_method );
	mailusers_update_smtp_server( $smtp_server );
	mailusers_update_smtp_port( $smtp_port );
	mailusers_update_smtp_user( $smtp_user );
	mailusers_update_smtp_password( $smtp_password );
	mailusers_update_mail_user_level( $mail_user_level );
?>

<div class="wrap">
	<p><?php _e('Options set successfully', MAILUSERS_I18N_DOMAIN); ?></p>
</div>

<?php include 'email_users_options_form.php'; ?>
