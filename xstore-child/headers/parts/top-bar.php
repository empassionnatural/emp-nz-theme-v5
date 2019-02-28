<?php if (etheme_get_option('top_bar')): ?>
	<div class="top-bar topbar-color-<?php echo get_query_var( 'et_top-bar-color', 'dark' ); ?>">
		<div class="container">
            <div class="row">
                <div class="cols col-sm-8 col-md-6 col-lg-4">
                    <div class="languages-area flex">
                        <?php etheme_shop_navbar( 'tb-left' ); ?>
                        <?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('languages-sidebar'))): ?>
                        <?php endif; ?>
                    </div>
                </div>
	            <?php if ( is_active_sidebar('top-panel') && etheme_get_option('top_panel') ): ?>
                    <div class="top-panel-open"><span><?php esc_html_e('Open panel', 'xstore'); ?></span></div>
	            <?php endif ?>
                <div class="cols col-sm-4 col-md-6 col-lg-4">
                    <div class="account-dropdown flex">
                        <!--<div class="top-links">

                            <?php /*etheme_shop_navbar( 'tb-right' ); */?>
                        </div>-->
                        <?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('top-bar-right'))): ?>
                        <?php endif; ?>

                        <?php global $current_user; ?>
                        <?php if( is_user_logged_in() ) : ?>
                            <div class="dropdown account-container">
                                <button type="button" class="btn btn-default btn-account dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account<span class="caret"></span>
                                </button>
                                <?php
                                    echo etheme_sign_link()['submenu'];
                                ?>
                            </div>

                        <?php else: ?>
                        <span class="account-links"><a class="link-login" href="/my-account"><span class="vc_icon_element-icon fa fa-user"></span><span class="log-text">Log In</span> </a> <a class="link-signup" href="/register"> Sign Up </a>
                        <?php endif; ?>
                    </div>
                </div>
			</div>
		</div>
	</div>
<?php endif; ?>