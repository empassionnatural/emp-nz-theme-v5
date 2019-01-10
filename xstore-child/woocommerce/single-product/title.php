<?php
/**
 * Single Product title
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(etheme_get_option('product_name_signle')) {
    return;
}
global $product, $post;
$average = $product->get_average_rating();
$product_settings = etheme_get_option('quick_view_switcher');
$product_settings = $product_settings['enabled'];
?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
<?php if (is_product() == 'true') {?>

    <?php if (array_key_exists('quick_categories', $product_settings)): ?>
        <?php
        etheme_product_cats();
        ?>
    <?php endif ?>
    <div class="single-product-rating">
        <div class="star-rating"><span style="width:<?php echo ( ( $average / 5 ) * 100 );?>%"><strong itemprop="ratingValue" class="rating"><?php echo $average; ?></strong> <?php echo __( 'out of 5', 'woocommerce' )?></span></div>
        <div class="rating-link">
            <?php
            if($average > 0) {
            } else {
                echo "<p>No Reviews Yet.</p>";
            }
            ?>
        </div>
    </div>
<?php }?>