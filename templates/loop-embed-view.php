<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $product;

include 'global/embed-view-header.php';

do_action( 'woocommerce_embed_before_loop', arraY( 'view_settings' => $view_settings ) );

echo do_shortcode('[products ids="'.implode(', ', $product_ids).'"]');

do_action( 'woocommerce_embed_after_loop', arraY( 'view_settings' => $view_settings ) );

include 'global/embed-view-footer.php';
