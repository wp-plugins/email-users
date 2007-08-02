<?php 
	if (!isset( $_GET['post'] ) && !isset( $_POST['post'] )) {
?>
<div class="wrap">
	<p><strong><?php _e('No page to templatize has been supplied!') ?></strong></p>
</div>
<?php
	} else {
		// Get the page to templatize	
		$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
		$post = post_templates_get_page($id);
		
		// Copy the post and insert it as a template
		if (isset($post) && $post!=null) {
			post_templates_create_template_from_page($post);
		
			// Show the template management page
			echo '<meta content="0; URL=edit.php?page=post-template/manage_page_templates.php" http-equiv="Refresh" />';
			exit;
		} else {
?>
<div class="wrap">
	<p><strong><?php _e('Template creation failed, could not find page: ') . $id ?></strong></p>
</div>
<?php
		}
	}
?>