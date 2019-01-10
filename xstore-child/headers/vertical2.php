<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');
    $banner_pos = etheme_get_option('header_banner_pos');
?>

<div class="header-wrapper header-vertical header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <header class="header main-header header-bg-block">
        <div class="container-wrapper">
            <div class="menu-wrapper"> 
			    <p class="hamburger-icon with-anim">
                    <span></span>
			    </p>
			    <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
			</div>
        </div>
    </header>
</div>
<div class="header-wrapper header-bg-block header-center3 vertical-mod header-color-<?php echo esc_attr( $color ); ?>">
<?php if ( $banner_pos == 'top' ) {
    if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
    <?php endif; ?>
<?php } ?>
<header class="header main-header">
<div class="container">
    <div class="container-wrapper">
        <div class="header-left-wrap">
            <div class="languages-area">
                <?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('languages-sidebar'))): ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-logo"><?php etheme_logo(); ?></div>

        <div class="header-right-wrap">
            <?php if ( is_active_sidebar( 'top-bar-right' ) ): ?>
                <div class="top-links">
                    <?php dynamic_sidebar( 'top-bar-right') ?>
                </div>
            <?php endif;?>
            <?php etheme_shop_navbar( 'header' ); ?>
        </div>
        <div class="navbar-toggle">
            <span class="sr-only"><?php esc_html_e( 'Menu', 'xstore' ); ?></span>
            <span class="et-icon et-burger"></span>
        </div>
    </div>
    </div>
</header>
 <?php if ( $banner_pos == 'bottom' ) {
        if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-banner'))): ?>
        <?php endif; ?>
    <?php } ?>
    <?php if ( $banner_pos != 'bottom') : ?>
        <hr class="et-hr">
    <?php endif; ?>
</div>