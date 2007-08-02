<?php 
	if (!isset( $_GET['post'] ) && !isset( $_POST['post'] )) {
?>
<div class="wrap">
	<p><strong><?php _e('No post to templatize has been supplied!') ?></strong></p>
</div>
<?php
	} else {
		// Get the post to templatize	
		$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
		$post = post_templates_get_post($id);
		
		// Copy the post and insert it as a template
		if (isset($post) && $post!=null) {
			post_templates_create_template_from_post($post);
		
			// Show the template management page
			echo '<meta content="0; URL=edit.php?page=post-template/manage_post_templates.php" http-equiv="Refresh" />';
			exit;
		} else {
?>
<div class="wrap">
	<p><strong><?php _e('Template creation failed, could not find post: ') . $id ?></strong></p>
</div>
<?php
		}
	}
?>