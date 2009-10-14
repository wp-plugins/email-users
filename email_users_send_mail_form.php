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
	// Fetch the default subject and body in case we are notifying of a post
	// --
	if (isset($_GET['is_notification']) && $_GET['is_notification']=='true') {
		// We are in the case of somebody wanting to notify users of a post.
		// --
		$subject = mailusers_get_default_subject();
		$mail_content = mailusers_get_default_body();
		
		if ( !isset( $_GET['post_id'] ) ) {
			$err_msg .= 
				__('Trying to notify of a post without passing the post id !', 
					MAILUSERS_I18N_DOMAIN);
		}
	} else {
		$subject = '';
		$mail_content = '';		
	}

	// Replace the template variables concerning the blog details
	// --
	$subject = mailusers_replace_blog_templates($subject);
	$mail_content = mailusers_replace_blog_templates($mail_content);
		
	// Replace the template variables concerning the sender details
	// --	
	get_currentuserinfo();
	$from_name = isset($_POST['fromName']) ? $_POST['fromName'] : $user_identity;
	$from_address = isset($_POST['fromAddress']) ? $_POST['fromAddress'] : $user_email;
	$subject = mailusers_replace_sender_templates($subject, $from_name);
	$mail_content = mailusers_replace_sender_templates($mail_content, $from_name);
		
	if (!isset($send_roles)) {
		$send_roles = array();
	}	
	if (!isset($send_users)) {
		$send_users = array();
	}
		
	// Replace the template variables concerning the post details
	// --
	if ( isset( $_GET['post_id'] ) ) {
		$post_id = $_GET['post_id'];
		$post = get_post( $post_id );
		$post_title = $post->post_title;
		$post_url = get_permalink( $post_id );			
		$post_content = explode( '<!--more-->', $post->post_content, 2 );
		$post_excerpt = $post_content[0];
		
		$subject = mailusers_replace_post_templates($subject, $post_title, $post_excerpt, $post_url);
		$mail_content = mailusers_replace_post_templates($mail_content, $post_title, $post_excerpt, $post_url);
	}
	
	//Fetch a list of user roles
	if ( ! isset($wp_roles) ) {
		$wp_roles = new WP_Roles();			
	}
?>

<?php	
	if ( mailusers_get_installed_version() != mailusers_get_current_version() ) {
?>
<div class="wrap">
	<p style="text-color:red;">
		<?php _e('It looks like you have an old version of the plugin activated. Please deactivate the plugin and activate it again to complete the installation of the new version.', MAILUSERS_I18N_DOMAIN); ?>
	</p>
</div>
<?php
	}
?>

<div class="wrap">
	<h2><?php _e('Email users', MAILUSERS_I18N_DOMAIN); ?></h2>
<?php	
	if ( isset( $_POST['is_notification'] ) && $_POST['is_notification']=='true' ) {
?>
		<p>
			<?php _e('Notify your registered users of a particular post based on their access levels.', MAILUSERS_I18N_DOMAIN); ?>
		</p>
<?php 
	}	
	else {
?>
		<p>
			<?php _e('Send an email to your registered users based on their access levels.', MAILUSERS_I18N_DOMAIN); ?>
		</p>
<?php 
	}
?>
		
	<?php 	if (isset($err_msg) && $err_msg!='') { ?>
			<p class="error"><?php echo $err_msg; ?></p>
			<p><?php _e('Please correct the errors displayed above and try again.', MAILUSERS_I18N_DOMAIN); ?></p>
	<?php	} ?>
		
	<form name="SendEmail" action="post-new.php?page=email-users/email_users_send_mail.php" method="post">		
		<input type="hidden" name="send" value="true" />
		
		<table class="editform" width="100%" cellspacing="2" cellpadding="5">
		<tr>
			<th scope="row" valign="top"><label for="mail_format"><?php _e('Mail format', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td><select name="mail_format">
					<option value="html" <?php if (mailusers_get_default_mail_format()=='html') echo 'selected="true"'; ?>>
						<?php _e('HTML', MAILUSERS_I18N_DOMAIN); ?>
					</option>
					<option value="plaintext" <?php if (mailusers_get_default_mail_format()=='plaintext') echo 'selected="true"'; ?>>
						<?php _e('Plain text', MAILUSERS_I18N_DOMAIN); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label for="fromName"><?php _e('Send From (name)', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td><input type="text" name="fromName" value="<?php echo $from_name;?>" size="30" /></td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label for="fromAddress"><?php _e('Send From (email)', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td><input type="text" name="fromAddress" value="<?php echo $from_address;?>" size="30" /></td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label for="send_roles"><?php _e('Send To (use CTRL key to select/deselect multiple items)', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td>
			<table>
			<tr>
				<td>
					<select name="send_roles[]" multiple="yes" size="8" style="width: 250px;">
					<?php 
						foreach ($wp_roles->get_names() as $key => $value) { 
					?>
						<option value="<?php echo $key; ?>"	<?php 
							echo (in_array($key, $send_roles) ? ' selected="yes"' : '');?>>
							<?php echo __('Role', MAILUSERS_I18N_DOMAIN) . ' - ' . $value; ?>
						</option>
					<?php 
						}
					?>
					</select>
				</td>
				<td>
					<select name="send_users[]" multiple="yes" size="8" style="width: 400px;">
					<?php 
						$users = get_users_of_blog();
						foreach ($users as $user) { 
					?>
						<option value="<?php echo $user->user_id; ?>" <?php 
							echo (in_array($user->user_id, $send_users) ? ' selected="yes"' : '');?>>
							<?php echo __('User', MAILUSERS_I18N_DOMAIN) . ' - ' . $user->display_name . '  (' . $user->user_email . ')'; ?>
						</option>
					<?php 
						}
					?>
					</select>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label for="subject"><?php _e('Subject', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td><input type="text" name="subject" value="<?php echo $subject;?>" size="30" /></td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label for="mailContent"><?php _e('Body', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td><textarea rows="10" cols="80" name="mailContent" id="mailContent"><?php echo $mail_content;?></textarea></td>
		</tr>
		</table>
		
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Send Email', MAILUSERS_I18N_DOMAIN); ?> &raquo;" />
		</p>	
	</form>	
</div>
