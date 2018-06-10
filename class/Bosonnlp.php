<?php
//http://docs.bosonnlp.com/keywords.html
require_once 'NlpServer.php';
require_once 'AtasLocal.php';


class Bosonnlp extends NlpServer {
    private $token = '';
    function __construct($token) {
        $this->token = $token;
    }


    function getKw($content, $title='', $num = 10) {
        $opt = get_option('atas_options');
        $existmore = floatval( $opt['atas_autotag_existmore'] );

        $content = parent::strip_contents($content);
        $body = utf8_encode( json_encode($title.$content.$title) );

        $args = array(
            'body' => $body,
            'headers' => [
                'Content-Type'=>'application/json',
                'Accept'=>'application/json',
                'X-Token'=> $this->token
            ]
        );

        $needNum = round($existmore * $num);
        $ret = wp_remote_post('http://api.bosonnlp.com/keywords/analysis?top_k='.$needNum, $args);
        $ret = json_decode( $ret['body'] );

        if( $existmore==1 )
            return $ret;


        // 对本地已有的标签加权

        global $wpdb;
        $tags = $wpdb->get_results("
            SELECT name FROM $wpdb->terms, $wpdb->term_taxonomy
            WHERE taxonomy='post_tag' AND count>0
            AND $wpdb->term_taxonomy.term_id=$wpdb->terms.term_id ");

        foreach ( $tags as &$tag )
            $tag = mb_strtolower( $tag->name );

        foreach ( $ret as &$retItem ) {
            $name = mb_strtolower( $retItem[1] );
            if( in_array($name, $tags) )
                $retItem[0] *= $existmore;
        }

        rsort($ret);
        $ret = array_slice($ret, 0, $num);

        return $ret;
    }


    function getSum($text, $title='', $percentage=300) {
        // build request body
        $reqBody = array(
            'content' => $text,
            'percentage' => $percentage,
            'title' => $title
        );
        $reqBody = json_encode($reqBody);

        $args = array(
            'body' => utf8_encode( json_encode($reqBody) ),
            'headers' => [
                'X-Token'=> $this->token
            ]
        );

        $ret = wp_remote_post('http://api.bosonnlp.com/summary/analysis', $args);
        $ret = $ret['body'];

        $ret = str_replace('\n', ' ', $ret);

        return $ret;
    }
}
