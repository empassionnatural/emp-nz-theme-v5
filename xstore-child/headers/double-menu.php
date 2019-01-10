<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $menu_class = 'menu-align-' . etheme_get_option('menu_align');
    $custom_html = etheme_get_option( 'header_custom_block' );
    $header_hr = etheme_get_option('header_full_width') && !is_active_sidebar('header-banner');
    $banner_pos = etheme_get_option('header_banner_pos');
?>

<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <?php if ( $banner_pos == 'top' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
    <?php get_template_part('headers/parts/top-bar'); ?>
    <header class="header main-header header-bg-block">
        <div class="container">
            <div class="container-wrapper">
                <div class="header-left-wrap">
                    <?php if ( ! empty( $custom_html ) ): ?>
                        <div class="header-custom">
                        <div class="custom-content">
                            <?php do_shortcode( etheme_option( 'header_custom_block' ) ) ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="menu-wrapper <?php echo esc_attr($menu_class); ?>">
                        <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
                    </div>
                </div>
                <div class="header-logo"><?php etheme_logo(); ?></div>
                <div class="header-right-wrap">
                    <div class="menu-wrapper menu-wrapper-right <?php echo esc_attr($menu_class); ?>">
                        <?php etheme_menu( 'main-menu-right', 'custom_nav_right' ); ?>
                    </div>
                    <?php etheme_shop_navbar( 'header' ); ?>
                </div>
                <div class="navbar-toggle">
                    <span class="sr-only"><?php esc_html_e('Menu', 'xstore'); ?></span>
                    <span class="et-icon et-burger"></span>
                </div>
            </div>
            <?php if ( !$header_hr && $banner_pos == 'top') : ?>
                <hr class="et-hr">
            <?php endif; ?>
        </div>
    </header>
    <?php if ( $header_hr && $banner_pos != 'bottom') : ?>
        <hr class="et-hr">
    <?php endif; ?>
    <?php if ( $banner_pos == 'bottom' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
</div>