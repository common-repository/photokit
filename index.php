<?php
/*
Plugin Name:  photokit 
Description:   photokit wordpress plugin , to edit image. 
Version:1.0
Author: Jack Zhu
Author URI : https://github.com/ikardi420
License : GPL v or later
Text Domain:  photokit
Domain Path : /languages/
*/



if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly




function wttlmscode_enqueue_scripts()
{

    wp_register_style('lmscode-stylesheet',  plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_style('lmscode-stylesheet');


    wp_enqueue_script('codescript-main', plugin_dir_url(__FILE__) . 'assets/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'wttlmscode_enqueue_scripts');

function add_custom_image_editor_button($actions, $post)
{
    $actions['custom_edit'] = '<a href="#" data-id="' . $post->ID . '">Custom Edit</a>';
    return $actions;
}
add_filter('media_row_actions', 'add_custom_image_editor_button', 10, 2);

function enqueue_custom_image_editor_scripts()
{
    wp_enqueue_script('custom-image-editor', plugin_dir_url(__FILE__) . 'assets/js/index.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-image-editor', 'WPURLS', array('siteurl' => get_option('siteurl')));

    wp_register_style('image-editor-stylesheet',  plugin_dir_url(__FILE__) . 'assets/css/editor.css');
    wp_enqueue_style('image-editor-stylesheet');
}
add_action('admin_enqueue_scripts', 'enqueue_custom_image_editor_scripts');





require_once('photokit-shortcode.php');
add_action('admin_enqueue_scripts', function () {
    if (is_admin())
        wp_enqueue_media();
});

function fn_upload_file()
{
    if (isset($_POST['upload_file'])) {
        $upload_dir = wp_upload_dir();

        if (!empty($upload_dir['basedir'])) {
            $user_dirname = $upload_dir['basedir'] . '/product-images';
            if (!file_exists($user_dirname)) {
                wp_mkdir_p($user_dirname);
            }

            $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $user_dirname . '/' . $filename);
            // save into database $upload_dir['baseurl'].'/product-images/'.$filename;
        }
    }
}
add_action('init', 'fn_upload_file');

add_shortcode('poiktimg_uploader', 'poiktimg_uploader_callback');

function poiktimg_uploader_callback()
{
    return '<form class="upload-img-form" action="' . plugin_dir_url(__FILE__) . 'process_upload.php" method="post" enctype="multipart/form-data">
	Upload here your downloaded image: <input type="file" name="profile_picture" />
	<input class="upload-img-btn" type="submit" name="submit" value="Upload to wp gallery" />
	</form>';
}


define('PLUGIN_FILE_PATH', __FILE__);

register_activation_hook(PLUGIN_FILE_PATH, 'insert_page_on_activation');

function insert_page_on_activation()
{
    if (!current_user_can('activate_plugins')) return;

    $page_slug = 'photokit'; // Slug of the Post
    $new_page = array(
        'post_type'     => 'page',                 // Post Type Slug eg: 'page', 'post'
        'post_title'    => 'photokit',    // Title of the Content
        'post_content'  => '[pkeditor] [poiktimg_uploader]',    // Content
        'post_status'   => 'publish',            // Post Status
        'post_author'   => 1,                    // Post Author ID
        'post_name'     => $page_slug            // Slug of the Post
    );
    if (!get_page_by_path($page_slug, OBJECT, 'page')) { // Check If Page Not Exits
        $new_page_id = wp_insert_post($new_page);
    }
}
