<?php

require_once 'onsave.php';


add_action('add_meta_boxes', 'atas_add_custom_box');
function atas_add_custom_box() {
    add_meta_box(
        'atas_box_id',
        'ATAS 自动标签',
        'atas_box_html',
        'post'
    );
    add_meta_box(
        'atas_box_summary_id',
        'ATAS 自动摘要（开发中）',
        'atas_box_summary_html',
        'post'
    );
}


require_once ATAS_DIR.'/metabox/api.php';


function atas_metabox_loadjs($post) {
    wp_register_script( 'atas_metabox', ATAS_URL.'/metabox/tagsbox.js' );

    wp_localize_script( 'atas_metabox', 'atas_metabox', [
        'nonce' => wp_create_nonce( "atas_metabox_".$post->ID ),
        //'atasOpt' => get_option('atas_options'),
        'postId' => $post->ID
    ] );

    wp_enqueue_script( 'atas_metabox' );
}

function atas_box_html($post) {
    // load js into page
    atas_metabox_loadjs($post);


    $meta = get_post_meta($post->ID);
    $opt = get_option('atas_options');

    $tagmode = $opt['atas_autotag'];
    if(isset( $meta['atas_tag_mode'] ))
        $tagmode = $meta['atas_tag_mode'][0];

    ?>
    <label for="atas_tag_mode">保存该文章时自动提取标签：</label>
    <select name="atas_tag_mode" id="atas_tag_mode" >
        <option value="0" <?php selected(0, $tagmode)?> >关闭</option>
        <option value="1" <?php selected(1, $tagmode)?> >仅在未手动设定标签时</option>
        <option value="2" <?php selected(2, $tagmode)?> >保留现有的标签 并自动提取至
            <?=$opt['atas_autotag_num']?>
            个标签</option>
    </select>

    <p><b>手动提取标签：</b>
        <a href="javascript:" class="get-local">本地标签库</a>；
        <a href="javascript:" class="get-boson">Bosonnlp</a>；
    </p>

    <div id="tagcloud-post_tag" class="the-tagcloud" style="display:none"></div>

    <input type="hidden" name="atas_autotag_contents" id="atas_autotag_contents">
    <?php
}


function atas_box_summary_html() {
    $opt = get_option('atas_options');
    $key = 'atas_summary';

    ?>
    <label for="<?=$key?>">
        <input id="<?=$key?>" name="<?=$key?>" value="1" type="checkbox" <?php checked('1', $opt[$key])?> >
        保存该文章时自动提取摘要
    </label>

    <p><b>手动提取摘要：</b>
        <a href="#">Bosonnlp</a>；
    </p>

    <textarea rows="1" cols="40" name="atas_excerpt" id="excerpt"></textarea>

    <?php
}
