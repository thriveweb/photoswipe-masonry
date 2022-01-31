<?php
/*
Plugin Name: Photoswipe Masonry
Plugin URI: http://thriveweb.com.au/the-lab/photoswipe/
Description: This is a image gallery plugin for WordPress built using PhotoSwipe from  Dmitry Semenov.
<a href="http://photoswipe.com/">PhotoSwipe</a>
Author: Web Design Gold Coast
Author URI: http://thriveweb.com.au/
Version: 1.2.15
Text Domain: photoswipe-masonry
*/

/*  Copyright 2010  Dean Oakley  (email : dean@thriveweb.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Illegal Entry');
}

//============================== PhotoSwipe options ========================//
class photoswipe_plugin_options
{

	public $plugin_name;

	public function __construct()
	{
		$this->plugin_name = 'photoswipe-masonry';

		$options = get_option('photoswipe_options');

		// set defaults
		if (!is_array($options)) {

			$options['show_controls'] = false;

			$options['show_captions'] = true;

			$options['use_masonry'] = false;

			$options['thumbnail_width'] = 150;
			$options['thumbnail_height'] = 150;

			$options['max_image_height'] = '2400';
			$options['max_image_width'] = '1800';

			$options['white_theme'] = false;
			update_option('photoswipe_options', $options);
		}
	}

	// initialize the required hooks & fiters
	public function init()
	{
		add_action('init', array($this, 'photoswipe_kses_allow_attributes'));
		add_action('init', array($this, 'photoswipe_add_image_resize'));
		add_action('admin_menu', array($this, 'photoswipe_add_submenu'));
		add_action('admin_head', array($this, 'photoswipe_register_head'));
		add_action('wp_enqueue_scripts', array($this, 'photoswipe_scripts_method'));
		add_action('wp_footer',  array($this, 'photoswipe_footer'));
		add_action('admin_post_update_settings', array($this, 'photoswipe_update_settings'));
		add_filter('wp_get_attachment_link', array($this, 'photoswipe_get_attachment_link'), 10, 6);
		add_shortcode('gallery',  array($this, 'photoswipe_shortcode'));
		add_shortcode('photoswipe',  array($this, 'photoswipe_shortcode'));
		add_action('save_post', array($this, 'photoswipe_save_post'), 10, 3);
		add_filter('plugin_action_links_photoswipe-masonry' . '/photoswipe-masonry.php', array($this, 'photoswipe_settings_link'), 10, 1);
		add_action('post_updated', array($this, 'photoswipe_update_post'), 10, 3);
	}

	// add allowed attributes
	public function photoswipe_kses_allow_attributes()
	{

		global $allowedposttags;
		$allowedposttags['a']['data-size'] = array();
	}


	// add photoswipe submenu page 
	public function photoswipe_add_submenu()
	{
		add_submenu_page('options-general.php', 'PhotoSwipe options', 'PhotoSwipe', 'edit_theme_options', basename(__FILE__), array($this, 'display'), 99);
	}

	// admin settings form of the plugin
	public static function display()
	{
		$options = get_option('photoswipe_options');
		$text_domain = 'photoswipe-masonry';
?>
		<div id="photoswipe_admin" class="wrap">

			<h2>PhotoSwipe Options</h2>

			<p>PhotoSwipe is a image gallery plugin for WordPress built using PhotoSwipe from Dmitry Semenov. <a href="http://photoswipe.com/">PhotoSwipe</a></p>
			<?php if (isset($_GET["update-status"]) && $_GET["update-status"] == "true") : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e('Settings save successfully!'); ?>.</p>
				</div>
			<?php elseif (isset($_GET["update-status"]) && $_GET["update-status"] == "false") : ?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('These is some trouble in saving the data, please check later!'); ?>.</p>
				</div>
			<?php endif; ?>
			<form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="update_settings" />
				<?php wp_nonce_field(-1, 'photoswipe_admin_options_nonce_field'); ?>

				<div class="ps_border"></div>

				<p style="font-style:italic; font-weight:normal; color:grey ">Please note: Images that are already on the server will not change size until you regenerate the thumbnails. Use <a title="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/" href="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/">AJAX thumbnail rebuild</a> </p>

				<div class="fl_box">
					<p>Thumbnail Width</p>
					<p><input type="text" name="thumbnail_width" value="<?php esc_attr_e($options['thumbnail_width'], $text_domain); ?>" /></p>
				</div>

				<div class="fl_box">
					<p>Thumbnail Height</p>
					<p><input type="text" name="thumbnail_height" value="<?php esc_attr_e($options['thumbnail_height'], $text_domain); ?>" /></p>
				</div>

				<div class="fl_box">
					<p>Max image width</p>
					<p><input type="text" name="max_image_width" value="<?php esc_attr_e($options['max_image_width'], $text_domain); ?>" /></p>
				</div>

				<div class="fl_box">
					<p>Max image height</p>
					<p><input type="text" name="max_image_height" value="<?php esc_attr_e($options['max_image_height'], $text_domain); ?>" /></p>
				</div>

				<div class="ps_border"></div>

				<p><label><input name="white_theme" type="checkbox" value="checkbox" <?php if ($options['white_theme']) esc_attr_e("checked='checked'", $text_domain); ?> /><?php esc_attr_e("Use white theme?", $text_domain); ?></label></p>

				<p><label><input name="show_captions" type="checkbox" value="checkbox" <?php if ($options['show_captions']) esc_attr_e("checked='checked'", $text_domain); ?> /><?php esc_attr_e("Show captions on thumbnails?", $text_domain); ?></label></p>

				<p><label><input name="use_masonry" type="checkbox" value="checkbox" <?php if ($options['use_masonry']) esc_attr_e("checked='checked'", $text_domain); ?> /><?php esc_attr_e("Don't use Masonry?", $text_domain); ?></label></p>

				<p><input class="button-primary" type="submit" name="photoswipe_save" value="Save Changes" /></p>

			</form>

		</div>

	<?php
	}

	// admin CSS
	public function photoswipe_register_head()
	{
		$current_screen_obj = get_current_screen();
		if ($current_screen_obj->base == "settings_page_photoswipe-masonry") :
			$url = plugins_url('admin.css', __FILE__);
			wp_enqueue_style('style', $url);
		endif;
	}

	// enqueue all CSS & JS
	public function photoswipe_scripts_method()
	{
		$options = get_option('photoswipe_options');
		$photoswipe_wp_plugin_path =  plugins_url() . '/photoswipe-masonry';

		wp_enqueue_style('photoswipe-core-css',	$photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe.css');


		// Skin CSS file (optional)
		// In folder of skin CSS file there are also:
		// - .png and .svg icons sprite,
		// - preloader.gif (for browsers that do not support CSS animations)
		if ($options['white_theme']) wp_enqueue_style('white_theme', $photoswipe_wp_plugin_path . '/photoswipe-dist/white-skin/skin.css');
		else wp_enqueue_style('pswp-skin', $photoswipe_wp_plugin_path . '/photoswipe-dist/default-skin/default-skin.css');

		// register inline css for shortcode
		wp_register_style('photoswipe-masonry-inline', $photoswipe_wp_plugin_path . '/photoswipe-masonry-inline.css');
		// wp_enqueue_style('photoswipe-masonry-inline', $photoswipe_wp_plugin_path . '/photoswipe-masonry-inline.css');

		wp_enqueue_script('jquery');

		//Core JS file
		wp_enqueue_script('photoswipe', 			$photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe.min.js');

		wp_enqueue_script('photoswipe-masonry-js', $photoswipe_wp_plugin_path . '/photoswipe-masonry.js');

		//UI JS file
		wp_enqueue_script('photoswipe-ui-default', $photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe-ui-default.min.js');

		//Masonry - re-named to move to header
		wp_enqueue_script('photoswipe-masonry', 	$photoswipe_wp_plugin_path . '/masonry.pkgd.min.js', '', '', false);
		//imagesloaded
		wp_enqueue_script('photoswipe-imagesloaded', 			$photoswipe_wp_plugin_path . '/imagesloaded.pkgd.min.js');

		// register inline js for the shortcode only	
		wp_register_script('photoswipe-masonry-js-inline', $photoswipe_wp_plugin_path . '/photoswipe-masonry-inline.js');
	}

	// update & save the admin form settings
	public function photoswipe_update_settings()
	{
		$status = 'false';
		if (
			isset($_POST['photoswipe_save']) &&
			(isset($_POST['action']) == "update_settings") &&
			(isset($_POST['photoswipe_admin_options_nonce_field']) &&
				wp_verify_nonce($_POST['photoswipe_admin_options_nonce_field']) &&
				current_user_can('manage_options')
			)
		) {
			$options = get_option('photoswipe_options');

			$options['thumbnail_width'] = (int)stripslashes($_POST['thumbnail_width']);
			$options['thumbnail_height'] = (int)stripslashes($_POST['thumbnail_height']);

			$options['max_image_width'] = (int)stripslashes($_POST['max_image_width']);
			$options['max_image_height'] = (int)stripslashes($_POST['max_image_height']);

			if (isset($_POST['white_theme'])) {
				$options['white_theme'] = (bool)true;
			} else {
				$options['white_theme'] = (bool)false;
			}

			if (isset($_POST['show_controls'])) {
				$options['show_controls'] = (bool)true;
			} else {
				$options['show_controls'] = (bool)false;
			}

			if (isset($_POST['show_captions'])) {
				$options['show_captions'] = (bool)true;
			} else {
				$options['show_captions'] = (bool)false;
			}

			if (isset($_POST['use_masonry'])) {
				$options['use_masonry'] = (bool)true;
			} else {
				$options['use_masonry'] = (bool)false;
			}

			$response = update_option('photoswipe_options', $options);
			if ($response) :
				$status = 'true';
			endif;
		} else {
			$status = 'false';
		}

		wp_redirect(admin_url('options-general.php?page=photoswipe-masonry.php&update-status=' . $status));
	}


	// link attachments
	public function photoswipe_get_attachment_link($link, $id, $size, $permalink, $icon, $text)
	{
		if ($permalink === false && !$text && 'none' != $size) {
			$_post = get_post($id);

			$image_attributes = wp_get_attachment_image_src($_post->ID, 'original');

			if ($image_attributes) {
				$link = str_replace('<a ', '<a data-size="' . $image_attributes[1] . 'x' . $image_attributes[2] . '" ', $link);
			}
		}

		return $link;
	}

	// definition of  photoswipe shortcode
	public function photoswipe_shortcode($attr)
	{
		global $post;
		global $photoswipe_count;

		// enqueing inline css 
		wp_enqueue_style('photoswipe-masonry-inline');


		$options = get_option('photoswipe_options');

		if (!empty($attr['ids'])) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if (empty($attr['orderby'])) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$args = shortcode_atts(array(
			'id' 				=> intval($post->ID),
			'show_controls' 	=> $options['show_controls'],
			'columns'    => 3,
			'size'       => 'thumbnail',
			'order'      => 'DESC',
			'orderby'    => 'menu_order ID',
			'include'    => '',
			'exclude'    => ''
		), $attr);

		$photoswipe_count += 1;
		$post_id = intval($post->ID) . '_' . $photoswipe_count;
		if (!empty($args['include'])) {

			//"ids" == "inc"

			$include = preg_replace('/[^0-9,]+/', '', $args['include']);
			$_attachments = get_posts(array('include' => $args['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']));

			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif (!empty($args['exclude'])) {
			$exclude = preg_replace('/[^0-9,]+/', '', $args['exclude']);
			$attachments = get_children(array('post_parent' => $args['id'], 'exclude' => $args['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']));
		} else {

			$attachments = get_children(array('post_parent' => $args['id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']));
		}

		$columns = intval($args['columns']);
		$itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
		$size_class = sanitize_html_class($args['size']);
		ob_start();
	?>
		<div style="clear:both"></div>
		<div class="psgal_wrap">
			<div id="<?php esc_attr_e('psgal_' . $post_id); ?>" data-psgal_id="<?php esc_attr_e($post_id); ?>" data-psgal_container_id="<?php esc_attr_e('container_' . $post_id); ?>" data-psgal_thumbnail_width="<?php esc_attr_e($options['thumbnail_width']); ?>" data-psgal_use_masonary="<?php echo (($options['use_masonry']) ? $options['use_masonry'] : 0); ?>" class="<?php esc_attr_e('psgal-inline psgal gallery-columns-' . $columns . ' gallery-size-' . $size_class . ' use_masonry_' . $options['use_masonry'] . ' show_captions_' . $options['show_captions']); ?>" itemscope itemtype="http://schema.org/ImageGallery">
				<?php

				if (!empty($attachments)) {
					foreach ($attachments as $aid => $attachment) {

						$thumb = wp_get_attachment_image_src($aid, 'photoswipe_thumbnails');

						$full = wp_get_attachment_image_src($aid, 'photoswipe_full');

						$_post = get_post($aid);

						$image_title = esc_attr($_post->post_title);
						$image_alttext = get_post_meta($aid, '_wp_attachment_image_alt', true);
						$image_caption = $_post->post_excerpt;
						$image_description = $_post->post_content;
						$calculated_width = ($options['thumbnail_width'] - 10) / $thumb[1] * $thumb[2];

				?>
						<figure class="msnry_items" itemscope itemtype="http://schema.org/ImageObject" style="<?php esc_attr_e('width:' . $options['thumbnail_width'] . 'px;'); ?>">
							<a href="<?php esc_attr_e($full[0]); ?>" itemprop="contentUrl" data-size="<?php esc_attr_e($full[1] . 'x' . $full[2]); ?>" data-caption="<?php esc_attr_e($image_caption); ?>" style="<?php esc_attr_e("height:" . (($options['thumbnail_width'] - 10) / $thumb[1] * $thumb[2]) . "px;"); ?>">
								<img class="msnry_thumb" src="<?php esc_attr_e($thumb[0]); ?>" itemprop="thumbnail" alt="<?php esc_attr_e($image_alttext); ?>" />
							</a>
							<?php
							if (empty($options['show_captions'])) :
								$caption_style = "display:none;";
							else :
								$caption_style = " ";
							endif;
							?>
							<figcaption class="photoswipe-gallery-caption" style="<?php esc_attr_e($caption_style); ?>"><?php esc_attr_e($image_caption); ?></figcaption>

						</figure>
				<?php

					}
				}

				?>
			</div>
		</div>
		<div style='clear:both'></div>
<?php
		// enqueing inline js 
		wp_enqueue_script('photoswipe-masonry-js-inline');
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}

	// insert the pswp block in the footer
	public function photoswipe_footer()
	{
		ob_start();
		include dirname(__FILE__) . '/photoswipe-masonry-footer-html.php';
		return ob_end_flush();
	}

	// define image sizes to resize the image
	public function photoswipe_add_image_resize()
	{
		$options = get_option('photoswipe_options');

		//image sizes - No cropping for a nice zoom effect
		add_image_size('photoswipe_thumbnails', (int) $options['thumbnail_width'] * 2, (int) $options['thumbnail_height'] * 2, false);
		add_image_size('photoswipe_full', (int) $options['max_image_width'], (int) $options['max_image_height'], false);
	}

	// update embeds on save
	public function photoswipe_save_post($post_id, $post, $update)
	{

		$post_content = $post->post_content;

		// Check against the classic editor
		$new_content = preg_replace_callback('/(<a((?!data\-size)[^>])+href=["\'])([^"\']*)(["\']((?!data\-size)[^>])*><img)/i', array($this, 'photoswipe_save_post_callback'), $post_content);

		if (!!$new_content && $new_content !== $post_content) :
			remove_action('save_post', 'photoswipe_save_post', 10, 3);

			wp_update_post(array('ID' => $post_id, 'post_content' => $new_content));

			add_action('save_post', $this->photoswipe_save_post($post_id, $post, $update), 10, 3);
		endif;
	}

	// add update method
	public function photoswipe_update_post($post_id, $post_after, $post_before)
	{
		$this->photoswipe_save_post($post_id, $post_after, (bool)true);
	}

	// preg_replace_callback function from photoswipe_save_post
	public function photoswipe_save_post_callback($matches)
	{
		$before = $matches[1];
		$image_url = $matches[3];
		$after = $matches[4];

		$id = photoswipe_plugin_options::fjarrett_get_attachment_id_by_url($image_url);

		if ($id) {
			$image_attributes = wp_get_attachment_image_src($id, 'original');
			if ($image_attributes) {
				$before = str_replace('<a ', '<a class="single_photoswipe" data-size="' . $image_attributes[1] . 'x' . $image_attributes[2] . '" ', $before);
			}
		}

		return $before . $image_url . $after;
	}

	/**
	 * Return an ID of an attachment by searching the database with the file URL.
	 *
	 * First checks to see if the $url is pointing to a file that exists in
	 * the wp-content directory. If so, then we search the database for a
	 * partial match consisting of the remaining path AFTER the wp-content
	 * directory. Finally, if a match is found the attachment ID will be
	 * returned.
	 *
	 * @param string $url The URL of the image (ex: http://mysite.com/wp-content/uploads/2013/05/test-image.jpg)
	 *
	 * @return int|null $attachment Returns an attachment ID, or null if no attachment is found
	 */
	public static function fjarrett_get_attachment_id_by_url($url)
	{
		// Split the $url into two parts with the wp-content directory as the separator
		$parsed_url  = explode(parse_url(WP_CONTENT_URL, PHP_URL_PATH), $url);

		// Get the host of the current site and the host of the $url, ignoring www
		$this_host = str_ireplace('www.', '', parse_url(home_url(), PHP_URL_HOST));
		$file_host = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));

		// Return nothing if there aren't any $url parts or if the current host and $url host do not match
		if (!isset($parsed_url[1]) || empty($parsed_url[1]) || ($this_host != $file_host)) {
			return;
		}

		// Now we're going to quickly search the DB for any attachment GUID with a partial path match
		// Example: /uploads/2013/05/test-image.jpg
		global $wpdb;
		$prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;

		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM {$prefix}posts WHERE guid RLIKE %s;", $parsed_url[1]));

		// Returns null if no attachment is found
		return $attachment[0];
	}

	/**
	 * Plugin settings link
	 * 
	 * @since    1.0.0
	 */
	public function photoswipe_settings_link(array $links)
	{
		$url = get_admin_url() . "options-general.php?page=photoswipe-masonry.php";
		$settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
		$links[] = $settings_link;
		return $links;
	}
}

// object creation of the photoswipe_plugin_options class and call to the init() method of the constructor
function run_photoswipe()
{
	$plugin = new photoswipe_plugin_options();
	$plugin->init();
}

// function call of the run_photoswipe
run_photoswipe();
