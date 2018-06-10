<?php

abstract class NlpServer {
    function getKw($content, $title, $num=10) {}
    function getSum($text, $title='', $percentage=300) {}

    // 去除 HTML 标签，转换 <br>、&nbsp; 等
    static function strip_contents($cont) {
        $cont = html_entity_decode($cont);
        $cont = preg_replace('/<.+?>/s', ' ', $cont);
        return $cont;
    }
}
