<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $product, $post;
    $average = $product->get_average_rating();

?>
<div class="star-rating"><span style="width:<?php echo ( ( $average / 5 ) * 100 );?>%"><strong itemprop="ratingValue" class="rating"><?php echo $average; ?></strong> <?php echo __( 'out of 5', 'woocommerce' )?></span></div>
<div class="rating-link">
    <?php
    if($average > 0) {
        echo "<p><a href=".get_permalink($post->ID)." target='_self' rel='noopener'>Write a Review</a></p>";
    } else {
        echo "<p>No Reviews Yet. Be the first! <a class='double-rev' href=".get_permalink($post->ID)." target='_self' rel='noopener'>Write a Review</a></p>";
    }
    ?>
</div>