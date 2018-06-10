<?php

// add opinion items

add_action('admin_init', 'atas_admin_init');
function atas_admin_init() {
    //delete_option( 'atas_options' );
    register_setting( 'atas', 'atas_options', ['default'=>[
        'atas_bosonnlp_token' => 'V4yf-1NC.18616.hjpfcHGZTVSj',
        'atas_autotag_num' => '5',
        'atas_autotag_engine' => '1',
        'atas_autotag' => '1',
        'atas_autotag_existmore' => '1.5',
        'atas_summary_len' => '300',
        'atas_summary' => '0',
        'atas_autopass' => ' ',
    ]] );

    // add items

    add_settings_section('atas_optsection_main', null, null, 'atas');
    add_settings_field('atas_bosonnlp_token', 'Bosonnlp API 密钥', 'atas_bosonnlp_token_show', 'atas', 'atas_optsection_main');
    add_settings_field('atas_autopass', '自动模式忽略拥有这些 term_id 的文章', 'atas_autopass_show', 'atas', 'atas_optsection_main');

    add_settings_section('atas_optsection_tag', 'ATAS 标签', null, 'atas');
    add_settings_field('atas_autotag_num', '标签个数', 'atas_autotag_num_show', 'atas', 'atas_optsection_tag');
    add_settings_field('atas_autotag_existmore', '优先本地已有的标签', 'atas_autotag_existmore_show', 'atas', 'atas_optsection_tag');
    add_settings_field('atas_autotag', '保存时自动提取', 'atas_autotag_show', 'atas', 'atas_optsection_tag');
    add_settings_field('atas_autotag_engine', '自动提取时的引擎', 'atas_autotag_engine_show', 'atas', 'atas_optsection_tag');

    add_settings_section('atas_optsection_summary', 'ATAS 摘要（开发中）', 'atas_optsection_summary_show', 'atas');
    add_settings_field('atas_summary_len', '摘要长度', 'atas_summary_len_show', 'atas', 'atas_optsection_summary');
    add_settings_field('atas_summary', '保存时自动提取', 'atas_summary_show', 'atas', 'atas_optsection_summary');

    add_settings_section('atas_about', '关于本插件', 'atas_about_show', 'atas');
}


function atas_about_show() {
    ?>
    <p>这个 WP 插件由 Moshel 开发，感谢你的选用。欢迎来访我的个人博客：<a href="https://hzy.pw" target="_blank">https://hzy.pw/</a></p>
    <p>本插件在 Github 上开源，喜欢请给个 Star，也欢迎各种建议和 PR：<a href="https://github.com/h2y/Wordpress-Plugin-ATAS" target="_blank">https://github.com/h2y/Wordpress-Plugin-ATAS</a></p>
    <p>如果该插件对你有帮助，可以考虑请我喝一杯咖啡，让本插件变得更好。捐助连接：<a href="https://hzy.pw/payme" target="_blank">https://hzy.pw/payme</a></p>
    <?php
}

function atas_bosonnlp_token_show() {
    $opt = get_option('atas_options');
    $key = 'atas_bosonnlp_token';

    ?>
    <input name='atas_options[<?=$key?>]' type='text' value='<?=$opt[$key]?>' />
    <p>请访问 <a href="https://bosonnlp.com/" target="_blank">https://bosonnlp.com/</a>
      免费注册并获取 API 密钥
    </p>
    <?php
}

function atas_autopass_show() {
    $opt = get_option('atas_options');
    $key = 'atas_autopass';

    ?>
    <input name='atas_options[<?=$key?>]' type='text' value='<?=$opt[$key]?>' />
    <p><b>用空格隔开</b>。如果你想在某些分类、标签中屏蔽 [保存时自动提取] 功能，可以将这些分类或标签的 ID 在这里输入</p>
    <p>《<a href="https://note.hzy.pw/3814.html" target="_blank">WP 中如何获取分类或标签的 term_id</a>》</p>
    <?php
}

function atas_autotag_show() {
    $options = get_option('atas_options');
    $key = 'atas_autotag';
    $name = 'atas_options[atas_autotag]';

    ?>
    <label for="<?=$key?>0">
        <input id="<?=$key?>0" name="<?=$name?>" value="0" type="radio" <?php checked('0', $options[$key])?> >
        关闭自动提取标签功能（仍可以在编辑文章时手动提取标签）
    </label><br>
    <label for="<?=$key?>1">
        <input id="<?=$key?>1" name="<?=$name?>" value="1" type="radio" <?php checked('1', $options[$key])?> >
        仅未手动设定标签的文章
    </label><br>
    <label for="<?=$key?>2">
        <input id="<?=$key?>2" name="<?=$name?>" value="2" type="radio" <?php checked('2', $options[$key])?> >
        保留手动设定的标签，并自动提取标签，使标签数量达到设定的个数
    </label>

    <?php
}

function atas_summary_show() {
    $options = get_option('atas_options');
    $key = 'atas_summary';
    $name = 'atas_options[atas_summary]';

    ?>
    <label for="<?=$key?>0">
        <input id="<?=$key?>0" name="<?=$name?>" value="0" type="radio" <?php checked('0', $options[$key])?> >
        关闭自动提取摘要功能（你仍然可以在编辑文章时点击 ATAS 插件来提取摘要）
    </label><br>
    <label for="<?=$key?>1">
        <input id="<?=$key?>1" name="<?=$name?>" value="1" type="radio" <?php checked('1', $options[$key])?> >
        仅在没有设定摘要的文章中自动提取摘要
    </label>

    <?php
}

function atas_autotag_num_show() {
    $opt = get_option('atas_options');
    $key = 'atas_autotag_num';
    ?>
    <input name='atas_options[<?=$key?>]' type='number' value="<?=$opt[$key]?>" />
    <?php
}

function atas_summary_len_show() {
    $key = 'atas_summary_len';
    $options = get_option('atas_options');
    ?>
    <input name='atas_options[<?=$key?>]' type='number' value="<?=$options[$key]?>" />
    <?php
}

function atas_optsection_summary_show() {
    echo '<p>文章拥有摘要后，在主页会显示摘要的内容，而不是显示文章的前 x 个字符（存在部分主题不会显示摘要）</p>';
}

function atas_autotag_existmore_show() {
    $options = get_option('atas_options');
    $key = 'atas_autotag_existmore';
    ?>
    无优先
    <input name='atas_options[<?=$key?>]' type="range" min="1" max="2" step="0.25"
           value="<?=$options[$key]?>" />
    权重 x2
    <?php
}

function atas_autotag_engine_show() {
    $options = get_option('atas_options');
    $key = 'atas_autotag_engine';

    ?>
    <select name="atas_options[<?=$key?>]">
        <option value="0" <?php selected('0', $options[$key])?> >本地标签库</option>
        <option value="1" <?php selected('1', $options[$key])?> >Bosonnlp</option>
    </select>
    <?php
}
