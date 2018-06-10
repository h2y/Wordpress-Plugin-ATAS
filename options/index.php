<?php
// add opinions page

add_action('admin_menu', 'atas_options_page');
function atas_options_page() {
    add_options_page(
        'ATAS 自动提取文章标签摘要',
        'ATAS',
        'manage_options',
        'atas',
        'atas_options_page_html'
    );
}
function atas_options_page_html() {
    require dirname(__FILE__).'/options.php';
}

// add opinion items
require_once dirname(__FILE__).'/options_items.php';

