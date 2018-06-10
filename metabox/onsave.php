<?php
require_once ATAS_DIR.'/class/Bosonnlp.php';
require_once ATAS_DIR.'/class/AtasLocal.php';


add_action('save_post', 'atas_onsave', 10, 3);
function atas_onsave($post_id, $post, $isUpdate) {
    // is new post
    if(!isset($_REQUEST['atas_tag_mode'])) {
        return;
    }

    // tag: auto mode
    $opt = get_option('atas_options');  // default mode
    $tag_mode = $_REQUEST['atas_tag_mode'];

    if($tag_mode != $opt['atas_autotag'])
        update_post_meta( $post_id, 'atas_tag_mode', $tag_mode );
    else
        delete_post_meta( $post_id, 'atas_tag_mode' );

    // apply manual tags
    $tags = $_REQUEST['atas_autotag_contents'];
    if($tags!='')
        wp_set_post_tags( $post_id, $tags, true );


    // check if ignored term_id

    $terms  = get_the_terms($post, 'category');
    $terms2 = get_the_terms($post, 'post_tag');
    if(!$terms)  $terms  = [];
    if(!$terms2) $terms2 = [];
    $terms = $terms + $terms2;

    foreach ($terms as &$term)
        $term = $term->term_id;

    $ignoreTerms = explode(' ', $opt['atas_autopass']);
    foreach ($ignoreTerms as $termId) {
        $termId = intval($termId);
        if( in_array($termId, $terms) )
            return;
    }


    // auto tags
    switch( intval($tag_mode) ) {
        // TODO: 修改文章时更新之前的自动标签
        case 1:  // 仅未手动设定时
            $nowTags = wp_get_post_tags($post_id);
            if(sizeof($nowTags)!=0) break;

            //开始提取标签
            $needNum = intval( $opt['atas_autotag_num'] );

            if($opt['atas_autotag_engine'] == '1')
                $nlp = new Bosonnlp( $opt['atas_bosonnlp_token'] );
            else
                $nlp = new AtasLocal();

            $newTags = $nlp->getKw(esc_html($post->post_content), $post->post_title, $needNum);
            foreach ($newTags as &$tag)
                $tag = $tag[1];

            wp_set_post_tags( $post_id, $newTags, false );

            break;

        case 2:  // 扩展到 x 个
            $oldTags = wp_get_post_tags($post_id);
            foreach ($oldTags as &$tag)
                $tag = mb_strtolower( $tag->name );

            $needNum = intval( $opt['atas_autotag_num'] ) - sizeof($oldTags);
            if($needNum <= 0) break;

            if($opt['atas_autotag_engine'] == '1')
                $nlp = new Bosonnlp( $opt['atas_bosonnlp_token'] );
            else
                $nlp = new AtasLocal();

            $newTags = $nlp->getKw(esc_html($post->post_content), $post->post_title, $needNum*2);
            $newTagStr = '';
BugFu::log($needNum);
            foreach ($newTags as $tag) {
                $name = mb_strtolower( $tag[1] );
                if(! in_array($name, $oldTags) ) {
                    $newTagStr .= $name.',';
                    if( --$needNum == 0 ) break;
                }
            }

            wp_set_post_tags( $post_id, $newTagStr, true );

            break;

        case 0: default:
            break;
    }

}
