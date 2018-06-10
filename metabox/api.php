<?php
require_once ATAS_DIR.'/class/Bosonnlp.php';
require_once ATAS_DIR.'/class/AtasLocal.php';


add_action( 'wp_ajax_atas_getBosonTags', 'atas_getBosonTags' );
function atas_getBosonTags() {
    check_ajax_referer( "atas_metabox_".$_REQUEST['postId'] );

    $opt = get_option('atas_options');
    $needNum = round( intval( $opt['atas_autotag_num'] ) * 1.7 );
    $nlp = new Bosonnlp( $opt['atas_bosonnlp_token'] );

    $ret = $nlp->getKw( $_REQUEST['content'], $_REQUEST['title'], $needNum );

    //echo json_encode($opt);
    echo json_encode($ret);

    wp_die();
}

add_action( 'wp_ajax_atas_getLocalTags', 'atas_getLocalTags' );
function atas_getLocalTags() {
    check_ajax_referer( "atas_metabox_".$_REQUEST['postId'] );

    $opt = get_option('atas_options');
    $needNum = round( intval( $opt['atas_autotag_num'] ) * 1.7 );
    $nlp = new AtasLocal();

    $ret = $nlp->getKw( $_REQUEST['content'], $_REQUEST['title'], $needNum );

    echo json_encode($ret);

    wp_die();
}
