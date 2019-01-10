<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $menu_class = 'menu-align-' . etheme_get_option('menu_align');
    $banner_pos = etheme_get_option('header_banner_pos');
?>

<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <?php if ( $banner_pos == 'top' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
    <?php get_template_part('headers/parts/top-bar'); ?>
    <div class="header-bg-block">
        <header class="header main-header">
            <div class="container">
                <div class="container-wrapper">
                    <div class="header-logo"><?php etheme_logo(); ?></div>
                    <div class="header-widgets">
                        <?php etheme_option('header_custom_block'); ?>
                    </div>
                    <?php etheme_shop_navbar( 'header' ); ?>
                    <div class="navbar-toggle">
                        <span class="sr-only"><?php esc_html_e('Menu', 'xstore'); ?></span>
                        <span class="et-icon et-burger"></span>
                    </div>
                </div>
            </div>
        </header>
        <div class="navigation-wrapper">
            <div class="container">
                <div class="menu-inner">
                    <div class="menu-wrapper <?php echo esc_attr($menu_class); ?>">
                        <?php et_show_secondary_menu(); ?>
                        <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
                    </div>
                    <?php if( etheme_get_option( 'search_form' ) == 'menu' ): ?>
                        <?php etheme_search_form( array(
                            'action' => 'default'
                        )); ?>
                    <?php endif; ?>
                </div>
                 <?php if ( $banner_pos != 'bottom') : ?>
                    <hr class="et-hr">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ( $banner_pos == 'bottom' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
</div>