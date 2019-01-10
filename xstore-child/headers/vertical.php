<?php 
    $ht = get_query_var('et_ht', 'xstore');
    $color = get_query_var('et_header-color', 'dark');

?>

<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <header class="header main-header header-bg-block">
        <div class="container-wrapper">

            <div class="header-logo"><?php etheme_logo(); ?></div>

            <div class="menu-wrapper"> 
			    <p class="hamburger-icon with-anim">
		            <span></span>
			    </p>
			    <?php etheme_menu( 'main-menu', 'custom_nav' ); ?>
			</div>

			 <div class="navbar-toggle">
                    <span class="sr-only"><?php esc_html_e( 'Menu', 'xstore' ); ?></span>
                    <span class="et-icon et-burger"></span>
                </div>
            <?php etheme_shop_navbar( 'header', array( 'search' ) ); ?>
        </div>
    </header>
</div>