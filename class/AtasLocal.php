<?php
require_once 'NlpServer.php';

class AtasLocal extends NlpServer {
    function getKw($content, $title, $num = 10) {
        $content = parent::strip_contents($content);
        $content .= str_repeat('\n'.$title, 3);
        $content = mb_strtolower($content);

        $ret = [];
        $tags = get_tags();

        foreach ( $tags as $tag ) {
            $tag = mb_strtolower($tag->name);
            $n = substr_count($content, $tag);
            if($n>0)
                array_push($ret, [$n, $tag]);
        }

        rsort($ret);

        if(sizeof($ret) > $num)
            return array_slice($ret, 0, $num);
        else return $ret;
    }

    function getSum($text, $title = '', $percentage = 300) {
        return 'Local 引擎不支持摘要提取！';
    }
}
