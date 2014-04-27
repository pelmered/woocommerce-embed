jQuery(document).ready(function($){
	var wcEmbed = {};

	wcEmbed.app = {
		init: function() {
			wcEmbed.app.routes();
			wcEmbed.changeOptions.sync();
		},
		routes: function() {
			wcEmbed.selector.embedToggleTrigger.on('click', wcEmbed.trigger.toggleEmbedCode);
			wcEmbed.selector.optionsToggleTrigger.on('click', wcEmbed.trigger.toggleOptions);
			wcEmbed.selector.changeOptionsSize.on('click', wcEmbed.changeOptions.setIframeSize);
			wcEmbed.selector.changeShowTitle.on('click', wcEmbed.changeOptions.setShowTitle);
			wcEmbed.selector.changeShowImage.on('click', wcEmbed.changeOptions.setShowImage);
			wcEmbed.selector.changeShowPrice.on('click', wcEmbed.changeOptions.setShowPrice);
			wcEmbed.selector.changeShowRating.on('click', wcEmbed.changeOptions.setShowRating);
			wcEmbed.selector.embedWidthInput.on('change', wcEmbed.changeOptions.setUserIframeSize);
			wcEmbed.selector.embedHeightInput.on('change', wcEmbed.changeOptions.setUserIframeSize);
		}
	}

	wcEmbed.selector = {
		embedToggleTrigger: $('button.wce-embed-product-button'),
		optionsToggleTrigger: $('p.wce-embed-advanced'),
		embedOptions: $('div.wce-embed-product-options'),
		changeOptionsSize: $('div.wce-embed-product-options-size input[type="radio"]'),
		customSizeRadio: $('input#size-custom'),
		changeShowTitle: $('div.wce-embed-product-options-display input#display-title'),
		changeShowImage: $('div.wce-embed-product-options-display input#display-image'),
		changeShowPrice: $('div.wce-embed-product-options-display input#display-price'),
		changeShowRating: $('div.wce-embed-product-options-display input#display-rating'),
		embedWidthInput: $('div.wce-embed-product-options-size input#size-width'),
		embedHeightInput: $('div.wce-embed-product-options-size input#size-height'),
		selectedOptionsSize: $('div.wce-embed-product-options-size input[type="radio"]:checked'),
		embedTextarea: $('div.wce-embed-product-code textarea'),
	}

	wcEmbed.trigger = {
		toggleEmbedCode: function(e) {
                        e.preventDefault();
			$(this).siblings('div.wce-embed-product-code').toggleClass('visible');
		},
		toggleOptions: function() {
			$(this).toggleClass('closed');

			wcEmbed.selector.embedOptions.toggleClass('visible');
		}
	}

	wcEmbed.changeOptions = {
		setIframeSize: function() {
			wcEmbed.changeOptions.sync();

			var width = $(this).data('width');
			var height = $(this).data('height');

			wcEmbed.embed._width = width;
			wcEmbed.selector.embedWidthInput.val(width);
			wcEmbed.embed._height = height;
			wcEmbed.selector.embedHeightInput.val(height);

			wcEmbed.embed._size = $(this).val();

			wcEmbed.embed.setTextareaValue();
		},

		setUserIframeSize: function() {
			
			// If the field was the width - change the width otherwise change the height
			if($(this).data('type') == 'width') {
				// Check whheater the value is numeric or not
				if( $.isNumeric($(this).val()) ) {
					wcEmbed.embed._width = $(this).val();
				} else {
					$(this).val(0);
					wcEmbed.embed._width = 0;
				}
			} else {
				// Check whheater the value is numeric or not
				if( $.isNumeric($(this).val()) ) {
					wcEmbed.embed._height = $(this).val();
				} else {
					$(this).val(0);
					wcEmbed.embed._height = 0;
				}
			}

			wcEmbed.selector.customSizeRadio.prop('checked', true);

			wcEmbed.embed.setTextareaValue();
		},

		setShowTitle: function() {
			wcEmbed.embed._title = $(this).prop('checked') == true ? 1 : 0;
			
			wcEmbed.embed.setTextareaValue();
		},

		setShowImage: function() {
			wcEmbed.embed._image = $(this).prop('checked') == true ? 1 : 0;
			
			wcEmbed.embed.setTextareaValue();
		},

		setShowPrice: function() {
			wcEmbed.embed._price = $(this).prop('checked') == true ? 1 : 0;
			
			wcEmbed.embed.setTextareaValue();
		},

		setShowRating: function() {
			wcEmbed.embed._rating = $(this).prop('checked') == true ? 1 : 0;
			
			wcEmbed.embed.setTextareaValue();
		},

		sync: function() {
			wcEmbed.embed.baseUrl = wce_embed_iframe_data.wce_embed_site_url;
			wcEmbed.embed.products = wce_embed_iframe_data.wce_embed_products;
			wcEmbed.embed.embed = wce_embed_iframe_data.wce_embed;

			wcEmbed.embed._width = wcEmbed.selector.selectedOptionsSize.data('width');
			wcEmbed.selector.embedWidthInput.val(wcEmbed.embed._width);
			wcEmbed.embed._height = wcEmbed.selector.selectedOptionsSize.data('height');
			wcEmbed.selector.embedHeightInput.val(wcEmbed.embed._height);

			wcEmbed.embed.setTextareaValue();
		}
	}

	wcEmbed.embed = {
		baseUrl: null,
		products: null,
		embed: null,
		_width: null,
		_height: null,
		_title: 1,
		_image: 1,
		_price: 1,
		_rating: 1,
		_size: 'medium',


		setTextareaValue: function() {
			var html = '<iframe src="';
					html += wcEmbed.embed.baseUrl;
					html += '?wce_embed_products='+wcEmbed.embed.products;
					html += '&wce_embed='+wcEmbed.embed.embed;
					html += '&wce_show_title='+wcEmbed.embed._title;
					html += '&wce_show_image='+wcEmbed.embed._image;
					html += '&wce_show_price='+wcEmbed.embed._price;
					html += '&wce_show_rating='+wcEmbed.embed._rating;
					html += '&wce_embed_size='+wcEmbed.embed._size;
				html += '" ';
				html += 'width="';
					html += wcEmbed.embed._width;
				html += '" ';
				html += 'height="';
					html += wcEmbed.embed._height;
				html += '" '
				html += 'frameborder="0">';
				html += '</iframe>';

			wcEmbed.selector.embedTextarea.val(html);
		}
	}






	// Initiate the app
	wcEmbed.app.init();


});

