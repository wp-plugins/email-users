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
	if (isset($_POST['is_notification']) && $_POST['is_notification']=='true') {
		// We are in the case of somebody wanting to notify users of a post.
		// --
		$subject = mailusers_get_default_subject();
		$mail_content = mailusers_get_default_body();
		
		if ( !isset( $_POST['post_id'] ) ) {
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
		
	// Replace the template variables concerning the post details
	// --
	if ( isset( $_POST['post_id'] ) ) {
		$post_id = $_POST['post_id'];
		$post = get_post( $post_id );
		$post_title = $post->post_title;
		$post_url = get_permalink( $post_id );			
		$post_content = explode( '<!--more-->', $post->post_content, 2 );
		$post_excerpt = $post_content[0];
		
		$subject = mailusers_replace_post_templates($subject, $post_title, $post_excerpt, $post_url);
		$mail_content = mailusers_replace_post_templates($mail_content, $post_title, $post_excerpt, $post_url);
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
		
	<? 	if ($err_msg!='') { ?>
			<p class="error"><?=$err_msg?></p>
			<p><?php _e('Please correct the errors displayed above and try again.', MAILUSERS_I18N_DOMAIN); ?></p>
	<?	} ?>
		
	<form name="SendEmail" action="post.php?page=email-users/email_users_sendmail.php" method="post">		
		<input type="hidden" name="send" value="true" />
		<fieldset id="titlediv">
			<legend><?php _e('Mail format', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><select name="mail_format">
				<option value="html" <?php if (mailusers_get_default_mail_format()=='html') echo 'selected="true"'; ?>><?php _e('HTML', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="plaintext" <?php if (mailusers_get_default_mail_format()=='plaintext') echo 'selected="true"'; ?>><?php _e('Plain text', MAILUSERS_I18N_DOMAIN); ?></option>
			</select></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend><?php _e('Send From (name)', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><input type="text" name="fromName" value="<?=$from_name?>" size="30" /></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend><?php _e('Send From (email)', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><input type="text" name="fromAddress" value="<?=$from_address?>" size="30" /></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend><?php _e('Send To', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><select name="send_role"><option value="0"<?=($to==0?" selected":"")?>><?php _e('Subscribers', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="1"<?=($to==1?" selected":"")?>><?php _e('Contributors', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="2"<?=($to==2?" selected":"")?>><?php _e('Authors', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="3"<?=($to==3?" selected":"")?>><?php _e('Editors', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="4"<?=($to==4?" selected":"")?>><?php _e('Administrators', MAILUSERS_I18N_DOMAIN); ?></option>
				<option value="5"<?=($to==5?" selected":"")?>><?php _e('All the users', MAILUSERS_I18N_DOMAIN); ?></option></select></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend><?php _e('Subject', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><input type="text" name="subject" value="<?=$subject?>" size="30" /></div>
		</fieldset>
		<fieldset id="postdivrich">
			<legend><?php _e('Body', MAILUSERS_I18N_DOMAIN); ?></legend>
			<div><textarea rows="10" cols="80" name="mailContent" id="mailContent"><?=$mail_content?></textarea></div>
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Send Email', MAILUSERS_I18N_DOMAIN); ?> &raquo;" />
		</p>
	</form>	
</div>
