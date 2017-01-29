<?php
function mdocs_display_file_info($the_mdoc, $index=0, $current_cat) {
	$the_mdoc_permalink = mdocs_get_permalink($the_mdoc['parent'], true);
	$the_post = get_post($the_mdoc['parent']);
	if($the_post != null) $is_new = preg_match('/new=true/',$the_post->post_content);
	else $is_new = false;
	$date_format = get_option('mdocs-date-format');
	$the_date = mdocs_format_unix_epoch($the_mdoc['modified']);
	$mdocs_show_downloads = get_option( 'mdocs-show-downloads' );
	$mdocs_show_download_btn = get_option( 'mdocs-show-download-btn' );
	$mdocs_show_author = get_option( 'mdocs-show-author' );
	$mdocs_show_version = get_option( 'mdocs-show-version' );
	$mdocs_show_update = get_option( 'mdocs-show-update' );
	$mdocs_show_ratings = get_option( 'mdocs-show-ratings' );
	$mdocs_show_new_banners = get_option('mdocs-show-new-banners');
	$mdocs_time_to_display_banners = get_option('mdocs-time-to-display-banners');
	$new_or_updated = '';
	
	
	
	$the_rating = mdocs_get_rating($the_mdoc);
	if($mdocs_show_new_banners) {
		$modified = floor($the_mdoc['modified']/86400)*86400;
		$today = floor(time()/86400)*86400;
		$days = (($today-$modified)/86400);
		if($mdocs_time_to_display_banners > $days) {
			if($is_new == true) {
				$status_class = 'mdocs-success';
				$new_or_updated = '<small><small class="label label-success">'.__('New', 'memphis-documents-library').'</small></small>';
			} else {
				$status_class = 'mdocs-info';
				$new_or_updated = '<small><small class="label label-info">'.__('Updated', 'memphis-documents-library').'</small></small>';
			}
		} else  $status_class = '';
	} else $status_class = ''; 
	
	if(get_option('mdocs-hide-new-update-label')) $new_or_updated = '';
	
	if($the_date['gmdate'] > time()) $scheduled = '<small class="text-muted"><b>'.__('Scheduled').'</b></small>';
	else $scheduled = '';
	?>
		<tr class="<?php echo $status_class; ?>">
			<?php
			$title_colspan = 0;
			if(is_admin()) {
				if(mdocs_check_file_rights($the_mdoc)) {
					?>
					<td><input type="checkbox" name="mdocs-batch-checkbox" data-id="<?php echo $the_mdoc['id']; ?>"/></td>
					<?php
				} else $title_colspan = 2;
				$dropdown_class = 'mdocs-dropdown-menu';
			} else $dropdown_class = 'mdocs-dropdown-menu';
			if(get_option('mdocs-dropdown-toggle-fix')  && !is_admin() ) $data_toogle = '';
			else $data_toogle = 'dropdown';
			?>
			<td id="title" class="mdocs-tooltip" colspan="<?php echo $title_colspan; ?>">
					<div class="mdocs-btn-group btn-group">
						<?php
						if(get_option('mdocs-hide-name')) $name_string = $new_or_updated.mdocs_get_file_type_icon($the_mdoc).' '.$the_mdoc['filename'].'<br>'.$scheduled;
						elseif(get_option('mdocs-hide-filename')) $name_string = $new_or_updated.mdocs_get_file_type_icon($the_mdoc).' '.str_replace('\\','',$the_mdoc['name']).'<br>'.$scheduled;
						else $name_string = $new_or_updated.mdocs_get_file_type_icon($the_mdoc).' '.str_replace('\\','',$the_mdoc['name']).' - <small class="text-muted">'.$the_mdoc['filename'].'</small><br>'.$scheduled;
						
						
						?>
						<a class="mdocs-title-href" data-mdocs-id="<?php echo $index; ?>" data-toggle="<?php echo $data_toogle; ?>" href="#" ><?php echo $name_string; ?></a>
						
						<ul class="<?php echo $dropdown_class; ?>" role="menu" aria-labelledby="dropdownMenu1">
							<li role="presentation" class="dropdown-header"><i class="fa fa-medium"></i> &#187; <?php echo $the_mdoc['filename']; ?></li>
							<li role="presentation" class="divider"></li>
							<li role="presentation" class="dropdown-header"><?php _e('File Options'); ?></li>
							<?php
								mdocs_download_rights($the_mdoc);
								mdocs_desciption_rights($the_mdoc);
								mdocs_preview_rights($the_mdoc);
								mdocs_versions_rights($the_mdoc);
								mdocs_rating_rights($the_mdoc);
								mdocs_goto_post_rights($the_mdoc, $the_mdoc_permalink);
								mdocs_share_rights($index, $the_mdoc_permalink, get_site_url().'/?mdocs-file='.$the_mdoc['id'].'&mdocs-url=false');
								if(is_admin()) { ?>
							<li role="presentation" class="divider"></li>
							<li role="presentation" class="dropdown-header"><?php _e('Admin Options'); ?></li>
							<?php
								mdocs_add_update_rights($the_mdoc, $current_cat);
								mdocs_manage_versions_rights($the_mdoc, $index, $current_cat);
								mdocs_delete_file_rights($the_mdoc, $index, $current_cat);
								if(get_option('mdocs-preview-type') == 'box' && get_option('mdocs-box-view-key') != '') {
									mdocs_refresh_box_view($the_mdoc, $index);
								}
							?>
							<li role="presentation" class="divider"></li>
							<li role="presentation" class="dropdown-header"><i class="fa fa-laptop"></i> <?php _e('File Status:'.' '.ucfirst($the_mdoc['file_status'])); ?></li>
							<li role="presentation" class="dropdown-header"><i class="fa fa-bullhorn"></i> <?php _e('Post Status:'.' '.ucfirst($the_mdoc['post_status'])); ?></li>
							<?php } ?>
						  </ul>
					</div>
			</td>
			<?php
			if($mdocs_show_downloads) { ?><td id="downloads"><i class="fa fa-cloud-download"></i> <b class="mdocs-orange"><?php echo $the_mdoc['downloads'].' <small>'.__('downloads','memphis-documents-library'); ?></small></b></a></td><?php }
			if($mdocs_show_version) { ?><td id="version"><i class="fa fa-power-off"></i><b class="mdocs-blue"> <?php echo $the_mdoc['version']; ?></b></td><?php }
			if($mdocs_show_author) { ?><td id="owner"><i class="fa fa-pencil"></i> <i class="mdocs-green"><?php echo get_user_by('login', $the_mdoc['owner'])->display_name; ?></i></td><?php }
			if($mdocs_show_update) { ?><td id="update"><i class="fa fa-calendar"></i> <b class="mdocs-red"><?php echo $the_date['formated-date']; ?></b></td><?php }
			// RATINGS
			if($mdocs_show_ratings) {
				echo '<td id="rating">';
				for($i=1;$i<=5;$i++) {
					if($the_rating['average'] >= $i) echo '<i class="fa fa-star mdocs-gold" id="'.$i.'"></i>';
					elseif(ceil($the_rating['average']) == $i ) echo '<i class="fa fa-star-half-full mdocs-gold" id="'.$i.'"></i>';
					else echo '<i class="fa fa-star-o" id="'.$i.'"></i>';
				}
				echo '</td>';
			}
			// DOWNLOAD BUTTON
			if($mdocs_show_download_btn) {
				if($the_mdoc['non_members'] == 'on' || is_user_logged_in()) {
					?><td id="download-btn"><i class="fa fa-download"></i> <b><a href="<?php echo site_url().'/?mdocs-file='.$the_mdoc['id']; ?>"><?php echo __('Download','memphis-documents-library'); ?></b></a></td><?php
				} else {
					?><td id="download-btn"><i class="fa fa-download"></i> <b><a href="<?php echo wp_login_url(htmlspecialchars(get_permalink($the_mdoc['parent']))); ?>"><?php echo __('Login','memphis-documents-library'); ?></a></b></td><?php
				}
			}
			?>
		</tr>
		<tr>
<?php
}
?>