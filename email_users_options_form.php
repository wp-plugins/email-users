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
	<p>
		Set the various options related to the email-users plugin. The various variables you can 
		include in the subject or body templates are:<br/>
		<ul>
			<li><strong>%MAILUSERS_BLOG_URL%</strong>: the link to the blog</li>
			<li><strong>%MAILUSERS_BLOG_NAME%</strong>: the blog's name</li>
			<li><strong>%MAILUSERS_FROM_NAME%</strong>: the wordpress user name of the person sending the mail</li>
			<li><strong>%MAILUSERS_POST_TITLE%</strong>: the title of the post you want to highlight</li>
			<li><strong>%MAILUSERS_POST_EXCERPT%</strong>: the excerpt of the post you want to highlight</li>
			<li><strong>%MAILUSERS_POST_URL%</strong>: the link to the post you want to highlight</li>
		</ul>
	</p>
	
	<? 	if ($err_msg!='') { ?>
			<p class="error"><?=$err_msg?></p>
			<p>Please correct the errors displayed above and try again.</p>
	<?	} ?>
		
	<p>
		Installed version: <?php echo mailusers_get_installed_version(); ?>
	</p>
	<p>
		Current version: <?php echo mailusers_get_current_version(); ?>
	</p>
	
	<form name="SendEmail" action="options-general.php?page=email-users/email_users_set_options.php" method="post">		
		<input type="hidden" name="send" value="true" />
		<fieldset id="titlediv">
			<legend>Default notification subject</legend>
			<div><input type="text" name="default_subject" value="<?php echo mailusers_get_default_subject(); ?>" size="30" /></div>
		</fieldset>
		<fieldset id="titlediv">
			<legend>Default notification body</legend>
			<div><textarea rows="10" cols="80" name="default_body" id="default_body"><?php echo mailusers_get_default_body(); ?></textarea></div>
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="Set options &raquo;" />
		</p>
	</form>	
</div>