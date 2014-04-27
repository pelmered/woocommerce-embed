<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $product;

include 'global/embed-view-header.php';

do_action( 'woocommerce_embed_before_single_product', arraY( 'view_settings' => $view_settings ) );

if( !isset($view_settings['image']) || $view_settings['image'] == 1)
{
    woocommerce_show_product_sale_flash();
    woocommerce_show_product_images();
}

do_action( 'woocommerce_embed_before_single_product_summary' );

if( !isset($view_settings['title']) || $view_settings['title'] == 1)
{
    woocommerce_template_single_title();
}
if( !isset($view_settings['rating']) || $view_settings['rating'] == 1)
{
    woocommerce_template_single_rating();
}
if( !isset($view_settings['price']) || $view_settings['price'] == 1)
{
    woocommerce_template_single_price();
}
if( !isset($view_settings['desc']) || $view_settings['desc'] == 1)
{
    //woocommerce_template_single_excerpt();
}
if ( $product->is_in_stock() )
{
    do_action( 'woocommerce_embed_button' );
}

do_action( 'woocommerce_embed_after_single_product_summary' );

do_action( 'woocommerce_embed_after_single_product' );

include 'global/embed-view-footer.php';
