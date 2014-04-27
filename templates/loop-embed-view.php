<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $product;

include 'global/embed-view-header.php';


echo do_shortcode('[products ids="'.implode(', ', $product_ids).'"]');


include 'global/embed-view-footer.php';
