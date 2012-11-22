<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
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
	if (!(current_user_can(MAILUSERS_EMAIL_SINGLE_USER_CAP) 
		|| 	current_user_can(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP)
		||	current_user_can(MAILUSERS_EMAIL_USER_GROUPS_CAP))) {		
        wp_die(printf('<div class="error fade"><p>%s</p></div>',
            __('You are not allowed to send emails.', MAILUSERS_I18N_DOMAIN)));
	} 
?>

<div class="wrap">

	<div id="icon-users" class="icon32"><br/></div>
	<h2><?php _e('Send an Email', MAILUSERS_I18N_DOMAIN); ?></h2>
	<br/>

	<?php if (current_user_can(MAILUSERS_EMAIL_SINGLE_USER_CAP)
		|| 	current_user_can(MAILUSERS_EMAIL_MULTIPLE_USERS_CAP)) { ?>
	<div style="float:left"><a href="admin.php?page=mailusers-send-to-user-page">
		<img src="<?php echo WP_CONTENT_URL . '/plugins/email-users/images/user.png'; ?>" alt="<?php _e('Send an email to one or more individual users', MAILUSERS_I18N_DOMAIN); ?>" title="<?php _e('Send an email to one or more individual users', MAILUSERS_I18N_DOMAIN); ?>" /></a>
	</div>
	<p><?php _e('Send an email to one or more individual users', MAILUSERS_I18N_DOMAIN); ?></p>
	<div class="clear"></div>
	<br/>
	<?php } ?>

	<?php if (current_user_can(MAILUSERS_EMAIL_USER_GROUPS_CAP)) { ?>
	<div style="float:left"><a href="admin.php?page=mailusers-send-to-group-page">
		<img src="<?php echo WP_CONTENT_URL . '/plugins/email-users/images/group.png'; ?>" alt="<?php _e('Send an email to one or more user groups', MAILUSERS_I18N_DOMAIN); ?>" title="<?php _e('Send an email to one or more user groups', MAILUSERS_I18N_DOMAIN); ?>" /></a>
	</div>
	<p><?php _e('Send an email to one or more user groups', MAILUSERS_I18N_DOMAIN); ?></p>
	<div class="clear"></div>
	<?php } ?>
	
	<div style="background: #FFFBCC; border: 1px solid #E6DB55; margin: 2em; padding: 1em;">
		<h2><?php _e('Discover other plugins by MarvinLabs:', MAILUSERS_I18N_DOMAIN); ?></h2>
		<ul>
			<li><?php _e('If email users is not enough, if you want to allow your users to communicate between each other, try: ', MAILUSERS_I18N_DOMAIN); ?><a href="http://user-messages.marvinlabs.com">User Messages</a></li>
			<li><?php _e('If you loose time copy/pasting the same post structure every time, try: ', MAILUSERS_I18N_DOMAIN); ?><a href="http://post-templates.marvinlabs.com">Post Templates</a></li>
		</ul>
	</div>
</div>
		
	
