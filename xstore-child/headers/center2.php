<?php
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $menu_class = 'menu-align-' . etheme_get_option('menu_align');
    $banner_pos = etheme_get_option('header_banner_pos');
    $tb_color = get_query_var('et_top-bar-color', 'dark');
?>
<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <?php if ( $banner_pos == 'top' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
    <?php if (etheme_get_option('top_bar')): ?>
        <div class="top-bar topbar-color-<?php echo esc_attr($tb_color); ?>">
            <div class="container">
                <div class="et_centered-type">
                    <?php etheme_shop_navbar( 'tb-left' ); ?>
                    <div class="languages-area">
                        <?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('languages-sidebar'))): ?>
                        <?php endif; ?>
                    </div>

                    <div class="header-logo"><?php etheme_logo(); ?></div>

                    <div class="top-links">
                        <?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('top-bar-right'))): ?>
                        <?php endif; ?>
                    </div>
                    <?php etheme_shop_navbar( 'tb-right' ); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <header class="header main-header header-bg-block">
        <div class="container">
            <div class="container-wrapper">
                <div class="header-logo"><?php etheme_logo(); ?></div>
                <div class="menu-wrapper <?php echo esc_attr($menu_class); ?>">
                    <?php et_show_secondary_menu(); ?>
                    <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
                </div>
                <div class="navbar-toggle">
                    <span class="sr-only"><?php esc_html_e('Menu', 'xstore'); ?></span>
                   <span class="et-icon et-burger"></span>
                </div>
                <?php etheme_shop_navbar( 'header' ); ?>
            </div>
        </div>
    </header>
    <?php if ( $banner_pos != 'bottom') : ?>
        <hr class="et-hr">
    <?php endif;
    if ( $banner_pos == 'bottom' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
</div>