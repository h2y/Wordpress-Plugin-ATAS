<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('atas');

        do_settings_sections('atas');

        submit_button('保存设置');
        ?>
    </form>
</div>
