<?php
function mdocs_restore_defaults() {
	mdocs_list_header();
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Restore Memphis Document Library\'s to Defaults','memphis-documents-library'); ?></h3>
		</div>
		<div class="panel-body">
			<p><?php _e('This will return Memphis Documents Library to its default install state.  This means that all you files, post, and categories will be remove and all setting will return to their default state. <b>Please backup your files before continuing.</b>','memphis-documents-library'); ?></p>
			<div class="mdocs-clear-both"></div>
			<br>
			<form enctype="multipart/form-data" method="post" action="#" class="mdocs-setting-form">
				<input type="hidden" name="mdocs-restore-default" value="clean-up" />
				<input type="button" class="button-primary" onclick="mdocs_restore_default()" value="<?php _e('Restore To Default','memphis-documents-library') ?>" />
			</form>
		</div>
	</div>
	<?php
}
?>