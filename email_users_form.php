<?php
/*  Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

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
	if ( isset( $_POST['is_notification'] ) && $_POST['is_notification']=='true' ) {
		// We are in the case of somebody wanting to notify users of a post.
		//--
		get_currentuserinfo();
		$fromName = $user_identity;
		$fromAddress = $user_email;
		$subject = mailusers_get_default_subject();
		$mailContent = mailusers_get_default_body();
		
		if ( !isset( $_POST['post_id'] ) ) {
			$err_msg .= 'Trying to notify of a post without passing the post id !';
		}
		else {
			$post_id = $_POST['post_id'];
			$post = get_post( $post_id );
			$blog_url = get_option( 'siteurl' );
			$blog_name = get_option( 'blogname' );
			$post_title = $post->post_title;
			$post_url = get_permalink( $post_id );
			
			$content = explode( '<!--more-->', $post->post_content, 2 );
			$post_excerpt = $content[0];
			
			$subject = preg_replace( '/%MAILUSERS_FROM_NAME%/', $fromName, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_FROM_NAME%/', $fromName, $mailContent );
						
			$subject = preg_replace( '/%MAILUSERS_BLOG_URL%/', $blog_url, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_BLOG_URL%/', $blog_url, $mailContent );
			
			$subject = preg_replace( '/%MAILUSERS_BLOG_NAME%/', $blog_name, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_BLOG_NAME%/', $blog_name, $mailContent );
						
			$subject = preg_replace( '/%MAILUSERS_POST_TITLE%/', $post_title, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_POST_TITLE%/', $post_title, $mailContent );
			
			$subject = preg_replace( '/%MAILUSERS_POST_EXCERPT%/', $post_excerpt, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_POST_EXCERPT%/', $post_excerpt, $mailContent );
			
			$subject = preg_replace( '/%MAILUSERS_POST_URL%/', $post_url, $subject );
			$mailContent = preg_replace( '/%MAILUSERS_POST_URL%/', $post_url, $mailContent );
		}
	}	
	else {
		// We are in the case of somebody wanting to send a mail to users
		//--
		get_currentuserinfo();
		
		if ( !isset( $_POST['fromName'] ) ) {
			$fromName = $user_identity;
		}	
		
		if ( !isset( $_POST['fromAddress'] ) ) {
			$fromAddress = $user_email;
		}
	}
?>

<?php	
	if ( mailusers_get_installed_version() != mailusers_get_current_version() ) {
?>
<div class="wrap">
	<p style="text-color:red;">
		It looks like you have an old version of the plugin activated. Please deactivate the plugin
		and activate it again to complete the installation of the new version.
	</p>
</div>
<?php
	}
?>

<div class="wrap">
	<h2>Email users</h2>	
<?php	
	if ( isset( $_POST['is_notification'] ) && $_POST['is_notification']=='true' ) {
?>
		<p>
			Notify your registered users of a particular post based on their access levels.  
			Only administrators are able to use this functionality.
		</p>
<?php 
	}	
	else {
?>
		<p>
			Send an email to your registered users based on their access levels. 
			Only administrators are able to use this functionality.
		</p>
<?php 
	}
?>
	
	<? 	if ($err_msg!='') { ?>
			<p class="error"><?=$err_msg?></p>
			<p>Please correct the errors displayed above and try again.</p>
	<?	} ?>
		
	<form name="SendEmail" action="post.php?page=email-users/email_users_sendmail.php" method="post">		
		<input type="hidden" name="send" value="true" />
		<fieldset id="titlediv">
			<legend>Send From (name)</legend>
			<div><input type="text" name="fromName" value="<?=$fromName?>" size="30" /></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend>Send From (email)</legend>
			<div><input type="text" name="fromAddress" value="<?=$fromAddress?>" size="30" /></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend>Send To</legend>
			<div><select name="send_role"><option value="0"<?=($to==0?" selected":"")?>>Subscribers</option>
				<option value="1"<?=($to==1?" selected":"")?>>Contributors</option>
				<option value="2"<?=($to==2?" selected":"")?>>Authors</option>
				<option value="3"<?=($to==3?" selected":"")?>>Editors</option>
				<option value="4"<?=($to==4?" selected":"")?>>Administrators</option>
				<option value="5"<?=($to==5?" selected":"")?>>ALL</option></select></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend>Subject</legend>
			<div><input type="text" name="subject" value="<?=$subject?>" size="30" /></div>
		</fieldset>
		<fieldset id="postdivrich">
			<legend>Email Contents</legend>
			<div><textarea rows="10" cols="80" name="mailContent" id="mailContent"><?=$mailContent?></textarea></div>
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="Send Email &raquo;" />
		</p>
	</form>	
</div>