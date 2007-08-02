<?php 
	if (!post_templates_is_current_user_allowed_to_admin()) {
?>
<div class="wrap">
	<div id="message" class="error">
		<p><?php echo __("You are not allowed to change the options of this plugin."); ?></p>
	</div>
</div>
<?php	
	} else if (!isset($_POST['view_role']) || !isset($_POST['create_role']) || !isset($_POST['admin_role'])) {
?>
<div class="wrap">
	<div id="message" class="error">
		<p><strong><?php _e('Missing option values!') ?></strong></p>
	</div>
</div>
<?php
	} else {
		$view_role = post_templates_get_level_from_role($_POST['view_role']);
		$create_role = post_templates_get_level_from_role($_POST['create_role']);
		$admin_role = post_templates_get_level_from_role($_POST['admin_role']);
		
		post_templates_set_view_user_level($view_role);
		post_templates_set_create_user_level($create_role);
		post_templates_set_admin_user_level($admin_role);
		
		echo '<meta content="0; URL=options-general.php?page=post-template/post_templates_admin.php&msg=set" http-equiv="Refresh" />';
		exit;
	}
?>