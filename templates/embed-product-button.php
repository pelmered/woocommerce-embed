<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<button class="wce-embed-product-button">
	<?php _e("Embed product", "wc-embed"); ?>
</button>
<div class="wce-embed-product-code">
	<p class="wce-embed-instructions">
		<?php _e("Copy this code and paste it into your page/post", "wc-embed"); ?>
	</p>
	<textarea><iframe src="<?php echo site_url(); ?>?wce_embed_products=<?php echo get_the_ID(); ?>&wce_embed=1" width="" height="" frameborder="0"></iframe></textarea>
	<iframe class="wce-embed-iframe-to-play-with" src="<?php echo site_url(); ?>?wce_embed_products='<?php echo get_the_ID(); ?>'&wce_embed=1" width="" height="" frameborder="0"></iframe>

    <p class="wce-embed-advanced closed">
    	<?php _e("Customize embed options"); ?>
    </p>
    
	<div class="wce-embed-product-options">	
		<div class="wce-embed-product-options-size">
	        <span class="group-title"><?php _e("Embed size options"); ?></span>
			<input type="radio" name="size" value="small" data-width="150" data-height="450" id="size-small">
			<label for="size-small">
				<?php _e("Small", "wc-embed"); ?>
			</label><br />
			<input type="radio" name="size" value="medium" data-width="225" data-height="600" id="size-medium" checked>
			<label for="size-medium">
				<?php _e("Medium", "wc-embed"); ?>
			</label><br />
			<input type="radio" name="size" value="large" data-width="300" data-height="800" id="size-large">
			<label for="size-large">
				<?php _e("Large", "wc-embed"); ?>
			</label><br />
			<input type="radio" name="size" data-width="0" data-height="0" id="size-custom">
			<label for="size-custom">
				<?php _e("Custom size", "wc-embed"); ?>
			</label><br />            
            <input type="text" data-type="width" name="size-width" id="size-width" value="0" />
            <input type="text" data-type="height" name="size-height" id="size-height" value="0" /><br />
		</div>
        <div class="wce-embed-product-options-display">
	        <span class="group-title"><?php _e("Embed display options"); ?></span>        
            <input type="checkbox" name="display-title" id="display-title" checked />
            <label for="display-title">
            	<?php _e("Include title", "wc-embed"); ?>
            </label><br />
            <input type="checkbox" name="display-image" id="display-image" checked />
            <label for="display-image">
            	<?php _e("Include image", "wc-embed"); ?>
            </label><br />
            <input type="checkbox" name="display-desc" id="display-desc" checked />
            <label for="display-desc">
            	<?php _e("Include description", "wc-embed"); ?>
            </label>
			<br />
			<input type="checkbox" name="display-price" id="display-price" checked />
            <label for="display-price">
            	<?php _e("Include price", "wc-embed"); ?>
            </label>
			<br />
			<input type="checkbox" name="display-rating" id="display-rating" checked />
            <label for="display-rating">
            	<?php _e("Include rating", "wc-embed"); ?>
            </label>
			<br />
		</div>
	</div>
    
</div>
