<?php
/*
Plugin Name: ATAS 自动提取文章标签摘要
Version:     1.0.0
Description: 在发布文章时自动提取文章标签及摘要，让你的文章管理更简单。
Plugin URI:  https://github.com/h2y/Wordpress-Plugin-ATAS
Author:      Moshel
Author URI:  https://hzy.pw
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define ( 'ATAS_URL', plugins_url( '', __FILE__ ) );
define ( 'ATAS_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );


// load opinion page
require_once ATAS_DIR.'/options/index.php';

// metabox in edit page
require_once ATAS_DIR.'/metabox/index.php';


// auto tags and summary

add_action('save_post', 'atas', 10, 3);
function atas($post_id, $post, $update) {
    if ( wp_is_post_revision( $post_id ) )
        return;

    $a = print_r($post, true);
}



?>

