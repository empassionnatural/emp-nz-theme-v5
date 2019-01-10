<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $menu_class = 'menu-align-' . etheme_get_option('menu_align');
    $header_hr = etheme_get_option('header_full_width');
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
                <div class="header-logo"><?php etheme_logo(); ?></div>
                <?php et_show_secondary_menu(); ?>
                <div class="menu-wrapper test-1 <?php echo esc_attr($menu_class); ?>">
                    <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
                </div>
                <div class="navbar-toggle">
                    <span class="sr-only"><?php esc_html_e('Menu', 'xstore'); ?></span>
                   <span class="et-icon et-burger"></span>
                </div>

                <?php etheme_search_form( array(
                    'action' => 'default'
                )); ?>

                <?php etheme_shop_navbar( 'header' ); ?>
            </div>
            <?php if ( !$header_hr && $banner_pos != 'bottom') : ?>
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