<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $header_hr = etheme_get_option('header_full_width') && !is_active_sidebar('header-banner');
    $banner_pos = etheme_get_option('header_banner_pos');

?>

<div class="fullscreen-menu">
    <p class="hamburger-icon open">
        <i class="et-icon et-delete"></i>
    </p>
    <div class="container fullscreen-menu-container">
        <div class="fullscreen-menu-collapse navbar-collapse">
            <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
        </div><!-- /.fullscreen-menu-collapse -->
    </div> 
</div>

<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <?php if ( $banner_pos == 'top' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
    <header class="header main-header header-bg-block">
        <div class="container">
            <div class="container-wrapper">
                <div class="header-logo"><?php etheme_logo(); ?></div>


                <div class="navbar-header">
                    <div class="header-widget-area">
                        <div class="languages-area">
                            <?php if( ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'languages-sidebar' ) ) ): ?>
                            <?php endif; ?>
                        </div>

                        <div class="top-links">
                            <?php if( ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'top-bar-right' ) ) ): ?>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <?php etheme_top_links(); ?>
                    <?php if ( etheme_get_option( 'top_links' ) == 'header' ) : ?>
                        <?php echo etheme_sign_link('','', true); ?>
                    <?php endif; ?>
                    <?php if( etheme_get_option( 'search_form' ) == 'header'): ?>
                        <?php etheme_search_form(); ?>
                    <?php endif; ?>

                    <?php if( etheme_woocommerce_installed() && etheme_get_option( 'top_wishlist_widget' ) == 'header' ) etheme_wishlist_widget(); ?>

                    <?php if( etheme_woocommerce_installed() && current_theme_supports( 'woocommerce' ) && ! etheme_get_option( 'just_catalog' ) && etheme_get_option( 'cart_widget' ) == 'header' ): ?>
                        <?php etheme_top_cart(); ?>
                    <?php endif ;?>
                    
                    <div class="hamburger-icon">
                      <i class="et-icon et-burger"></i>
                    </div>
            </div>
            <div class="navbar-toggle">
                <span class="sr-only"><?php esc_html_e( 'Menu', 'xstore' ) ?></span>
                <span class="et-icon et-burger"></span>
            </div>
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