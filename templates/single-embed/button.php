<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $product;
?>
<a href="<?php echo get_permalink($product->ID); ?>" class="single_add_to_cart_button button alt"><?php _e('Go to store', 'wc-embed'); ?></a>

