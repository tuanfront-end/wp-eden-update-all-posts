<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Eden Update Posts
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Eden Tuan
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */


//  

add_action('init', 'actionUpdateAllPostByPostID');

function actionUpdateAllPostByPostID()
{
	if (!current_user_can('edit_others_posts')) {
		return;
	}

	if (!isset($_POST['post_id'])) {
		return;
	}

	$oPost = get_post($_POST['post_id']);
	if (!$oPost) {
		return;
	}
	// 
	$aPosts = new WP_Query([
		'posts_per_page' => -1
	]);
	foreach ($aPosts->posts as $oItem) {
		wp_update_post([
			'ID'           => $oItem->ID,
			// Get content of postID and update to $oItem content
			'post_content' => $oPost->post_content,
		], true);
	}
}

function wporg_options_page_html()
{
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="" method="post">
			<label for="post_id">Post ID</label>
			<input id="post_id" name="post_id" type="number" min="1" placeholder="1">
			<?php
			// output security fields for the registered setting "wporg_options"
			settings_fields('wporg_options');
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections('wporg');
			// output save settings button
			submit_button(__('Save Settings', 'textdomain'));
			?>
		</form>
	</div>
<?php
}

add_action('admin_menu', 'wporg_options_page');
function wporg_options_page()
{
	add_menu_page(
		'WPOrg',
		'Eden Update Posts',
		'manage_options',
		'wporg',
		'wporg_options_page_html',
		'',
		20
	);
}
