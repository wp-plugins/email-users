<div class="wrap">
<h2><?php echo __("Post templates options"); ?></h2>
<h3><?php echo __("Current version: ") . post_templates_get_installed_version(); ?></h3>

<?php 
	if (!post_templates_is_current_user_allowed_to_admin()) {
?>
	<div id="message" class="error">
		<p><?php echo __("You are not allowed to change the options of this plugin."); ?></p>
	</div>
<?php	
	} else {	
		$view_role = post_templates_get_role_from_level(post_templates_get_view_user_level());
		$create_role = post_templates_get_role_from_level(post_templates_get_create_user_level());
		$admin_role = post_templates_get_role_from_level(post_templates_get_admin_user_level());

		if (isset($_GET['msg']) && $_GET['msg']=='set') {
?>
	<div id="message" class="updated fade">
		<p><?php _e('Options set successfully.'); ?></p>
	</div>

<?php 
		}
?>
<p>
<ul>
<li><?php echo __("View level sets the minimal role to have to be able to view the templates and create posts from them."); ?></li>
<li><?php echo __("Create level sets the minimal role to have to be able to create templates."); ?></li>
<li><?php echo __("Admin level sets the minimal role to change the plugin options and to delete templates."); ?></li>
</ul>
</p>

<form name="SetOptions" action="edit.php?page=post-template/set_options.php" method="post">
	<fieldset id="titlediv">
		<legend><?php echo __("View level"); ?></legend>
		<div><select name="view_role">
			<option value="0"<?=($view_role==0?" selected":"")?>><?php echo __("Subscribers"); ?></option>
			<option value="1"<?=($view_role==1?" selected":"")?>><?php echo __("Contributors"); ?></option>
			<option value="2"<?=($view_role==2?" selected":"")?>><?php echo __("Authors"); ?></option>
			<option value="3"<?=($view_role==3?" selected":"")?>><?php echo __("Editors"); ?></option>
			<option value="4"<?=($view_role==4?" selected":"")?>><?php echo __("Administrators"); ?></option>
		</select></div>
	</fieldset>
	<fieldset id="titlediv">
		<legend><?php echo __("Create level"); ?></legend>
		<div><select name="create_role">
			<option value="0"<?=($create_role==0?" selected":"")?>><?php echo __("Subscribers"); ?></option>
			<option value="1"<?=($create_role==1?" selected":"")?>><?php echo __("Contributors"); ?></option>
			<option value="2"<?=($create_role==2?" selected":"")?>><?php echo __("Authors"); ?></option>
			<option value="3"<?=($create_role==3?" selected":"")?>><?php echo __("Editors"); ?></option>
			<option value="4"<?=($create_role==4?" selected":"")?>><?php echo __("Administrators"); ?></option>
		</select></div>
	</fieldset>
	<fieldset id="titlediv">
		<legend><?php echo __("Admin level"); ?></legend>
		<div><select name="admin_role">
			<option value="0"<?=($admin_role==0?" selected":"")?>><?php echo __("Subscribers"); ?></option>
			<option value="1"<?=($admin_role==1?" selected":"")?>><?php echo __("Contributors"); ?></option>
			<option value="2"<?=($admin_role==2?" selected":"")?>><?php echo __("Authors"); ?></option>
			<option value="3"<?=($admin_role==3?" selected":"")?>><?php echo __("Editors"); ?></option>
			<option value="4"<?=($admin_role==4?" selected":"")?>><?php echo __("Administrators"); ?></option>
		</select></div>
	</fieldset>

	<p class="submit">
		<input type="submit" name="Submit" value="<?php echo __('Set options &raquo;'); ?>" />
	</p>
</form>
<?php
} // End if (post_templates_is_current_user_allowed_to_admin())
?>
</div>
	