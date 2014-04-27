<?php
/* 
 * 
 * 
 */
global $product, $post;

?>
<div class="woocommerce woocommerce-page woocommerce-embed embed-size-<?php echo (isset($view_settings['size']) ? $view_settings['size'] : 'medium'); ?>">
    <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

        <a href="<?php echo get_permalink($product->ID); ?>" title="<?php echo esc_attr(get_the_title()); ?>">