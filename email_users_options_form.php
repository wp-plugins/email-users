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
	if ( mailusers_get_installed_version() != mailusers_get_current_version() ) {
?>
<div class="wrap">
	<p style="text-color:red;">
		<?php _e('It looks like you have an old version of the plugin activated. Please deactivate the plugin and activate it again to complete the installation of the new version.', MAILUSERS_I18N_DOMAIN); ?>
	</p>		
	<p>
		<?php _e('Installed version:', MAILUSERS_I18N_DOMAIN); ?> <?php echo mailusers_get_installed_version(); ?> <br/>
		<?php _e('Current version:', MAILUSERS_I18N_DOMAIN); ?> <?php echo mailusers_get_current_version(); ?>
	</p>
</div>
<?php
	}
?>

<div class="wrap">
	<h2><?php _e('Email users', MAILUSERS_I18N_DOMAIN); ?> <?php echo mailusers_get_installed_version(); ?></h2>

<div align="center">
<a href="http://email-users.vincentprat.info" target="_blank"><?php _e('Plugin\'s home page', MAILUSERS_I18N_DOMAIN); ?></a>
<br/><br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="vpratfr@yahoo.fr">
<input type="hidden" name="item_name" value="Email Users - Wordpress Plugin">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="<?php _e('EN', MAILUSERS_I18N_DOMAIN); ?>">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="PayPal">
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>

	<p>
		<?php _e('Set the various options related to the email-users plugin. The various variables you can include in the subject or body templates are:', MAILUSERS_I18N_DOMAIN); ?><br/>
		<ul>
			<li><strong>%BLOG_URL%</strong>: <?php _e('the link to the blog', MAILUSERS_I18N_DOMAIN); ?></li>
			<li><strong>%BLOG_NAME%</strong>: <?php _e('the blog\'s name', MAILUSERS_I18N_DOMAIN); ?></li>
			<li><strong>%FROM_NAME%</strong>: <?php _e('the wordpress user name of the person sending the mail', MAILUSERS_I18N_DOMAIN); ?></li>
			<li><strong>%POST_TITLE%</strong>: <?php _e('the title of the post you want to highlight', MAILUSERS_I18N_DOMAIN); ?></li>
			<li><strong>%POST_EXCERPT%</strong>: <?php _e('the excerpt of the post you want to highlight', MAILUSERS_I18N_DOMAIN); ?></li>
			<li><strong>%POST_URL%</strong>: <?php _e('the link to the post you want to highlight', MAILUSERS_I18N_DOMAIN); ?></li>
		</ul>
	</p>
	
	<?php 	
		if (isset($err_msg) && $err_msg!='') { ?>
			<p class="error"><?php echo $err_msg; ?></p>
			<p><?php _e('Please correct the errors displayed above and try again.', MAILUSERS_I18N_DOMAIN); ?></p>
	<?php	
		} ?>
	
	<form name="SendEmail" action="options-general.php?page=email-users/email_users_set_options.php" method="post">		
		<input type="hidden" name="send" value="true" />
		<table class="editform" width="100%" cellspacing="2" cellpadding="5">
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('Allow users having at least following role to use the plugin', MAILUSERS_I18N_DOMAIN); ?></label></th>
			<td>
				<select name="mail_user_level">
					<option value="8" <?php if (mailusers_get_mail_user_level()=='8') echo 'selected="true"'; ?>>
						<?php _e('Administrators', MAILUSERS_I18N_DOMAIN); ?></option>
					<option value="5" <?php if (mailusers_get_mail_user_level()=='5') echo 'selected="true"'; ?>>
						<?php _e('Editors', MAILUSERS_I18N_DOMAIN); ?></option>
					<option value="2" <?php if (mailusers_get_mail_user_level()=='2') echo 'selected="true"'; ?>>
						<?php _e('Authors', MAILUSERS_I18N_DOMAIN); ?></option>
					<option value="1" <?php if (mailusers_get_mail_user_level()=='1') echo 'selected="true"'; ?>>
						<?php _e('Contributors', MAILUSERS_I18N_DOMAIN); ?></option>
					<option value="0" <?php if (mailusers_get_mail_user_level()=='0') echo 'selected="true"'; ?>>
						<?php _e('Subscribers', MAILUSERS_I18N_DOMAIN); ?></option>
			</select></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('Send mails as plain text or HTML by default?', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<select name="default_mail_format">
					<option value="html" <?php if (mailusers_get_default_mail_format()=='html') echo 'selected="true"'; ?>><?php _e('HTML', MAILUSERS_I18N_DOMAIN); ?></option>
					<option value="plaintext" <?php if (mailusers_get_default_mail_format()=='plaintext') echo 'selected="true"'; ?>><?php _e('Plain text', MAILUSERS_I18N_DOMAIN); ?></option>
				</select></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('Send mails using SMTP server or built-in PHP mail functions?', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<select name="mail_method">
					<option value="smtp" <?php if (mailusers_get_mail_method()=='smtp') echo 'selected="true"'; ?>>SMTP</option>
					<option value="mail" <?php if (mailusers_get_mail_method()=='mail') echo 'selected="true"'; ?>>PHP mail</option>
				</select></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('SMTP server (only for SMTP method)', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<input type="text" name="smtp_server" 
					value="<?php echo mailusers_get_smtp_server(); ?>"/></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('SMTP port (only for SMTP method) - Usually 25 by default on most servers.', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<input type="text" name="smtp_port" 
					value="<?php echo mailusers_get_smtp_port(); ?>"/></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('SMTP user (only for SMTP method) - Leave blank if your server does not require authentication.', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<input type="text" name="smtp_user" 
					value="<?php echo mailusers_get_smtp_user(); ?>"/></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('SMTP password (only for SMTP method)', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<input type="password" name="smtp_password" 
					value="<?php echo mailusers_get_smtp_password(); ?>"/></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('Default notification subject', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<input type="text" name="default_subject" 
					value="<?php echo mailusers_get_default_subject(); ?>" 
					size="80" /></td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="mail_format"><?php _e('Default notification body', MAILUSERS_I18N_DOMAIN); ?></th>
			<td>
				<textarea rows="10" cols="80" name="default_body" id="default_body"><?php echo mailusers_get_default_body(); ?></textarea>
			</td>
		</tr>
		</table>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Set options', MAILUSERS_I18N_DOMAIN); ?> &raquo;" />
		</p>
	</form>	
</div>
