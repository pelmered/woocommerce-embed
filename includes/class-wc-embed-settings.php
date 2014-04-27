<?php

//require_once( WP_EMBED_PLUGIN_PATH . 'includes/class-ewp-plugin-settings.php' );

class WC_Embed_Settings { //extends EWP_Plugin_Settings {

public $plugin_slug;
public $plugin_options = array();

function __construct($plugin_slug) {

    //parent::__construct($plugin_slug);

    $this->plugin_slug = $plugin_slug;

    $this->plugin_options = get_option($this->plugin_slug.'_options');

    add_action('admin_menu', array($this, 'add_plugin_menu'));    

    add_filter( $this->plugin_slug.'_option_tabs', array($this, 'add_options_tab' ) );

    add_action('admin_init', array($this, 'initialize_embed_options'));

    add_action('admin_enqueue_scripts', array($this, 'admin_options_styles'));
}


    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    function display_settings_page() {

        $tabs = array();
        $tabs = apply_filters($this->plugin_slug . '_option_tabs', $tabs);
        
        //Get key of first tab (the default) 
        $first_key = key($tabs);
        
        if( !empty($_GET['tab']) && in_array($_GET['tab'], array_keys($tabs)))
        {
            $active_tab = $_GET['tab'];
        }
        else
        {
            $active_tab = $first_key;
        }
        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap woocommerce">

            <div id="icon-themes" class="icon32"></div>
            <h2><?php echo $tabs[$first_key]['name'] ?></h2>
            <?php settings_errors(); ?>

            <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs AS $slug => $name) : ?>
                    <a href="?page=<?php echo $this->plugin_slug; ?>&tab=<?php echo $slug; ?>" class="nav-tab <?php echo $active_tab == $slug ? 'nav-tab-active' : ''; ?>"><?php echo $name['name']; ?></a>
            <?php endforeach; ?>
            </h2>

            <form method="post" action="options.php">
                <?php

                if( is_callable($tabs[$active_tab]['callback']) )
                {
                    call_user_func($tabs[$active_tab]['callback']);
                }
                else
                {

                }

                ?>
            </form>

        </div><!-- /.wrap -->
        <?php
    }

function admin_options_styles() {

    wp_enqueue_style('pricefiles-admin-options-styles', WP_EMBED_PLUGIN_URL . 'assets/css/admin-options.css', '', '');
}

/**
 * 
 */
function add_plugin_menu() {

    add_submenu_page(
        'woocommerce', __('Embed', $this->plugin_slug), __('Embed', $this->plugin_slug), 'manage_woocommerce', 
        $this->plugin_slug, array($this, 'display_settings_page')
    );
}

// end sandbox_example_theme_menu


function add_options_tab($tabs) {

    $tabs = array(
        'embed_settings' => array(
            'name' => __('Embed options', $this->plugin_slug),
            'callback' => array($this, 'embed_options_page_settings')
        ),
        'affiliate_settings' => array(
            'name' => __('Affiliate options', $this->plugin_slug),
            'callback' => array($this, 'embed_affiliate_options_page_settings')
        )
    ) + $tabs;

    return $tabs;
}
    


function embed_options_page_settings() {
    settings_fields($this->plugin_slug.'_options');
    do_settings_sections($this->plugin_slug.'_options_section');
    
    submit_button();
}
function embed_affiliate_options_page_settings() {
    settings_fields('pricefile_urls_section');
    do_settings_sections($this->plugin_slug.'_pricefile_urls_section');

    settings_fields($this->plugin_slug.'_options');
    do_settings_sections($this->plugin_slug.'_options_section');
    
    submit_button();
}

// end sandbox_theme_display

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

/**
 * Provides default values for the Display Options.
 */

/*
function default_embed_options() {

    $defaults = array(
//        'output_prices' => 'shop',
    );

    return apply_filters('sandbox_theme_default_pricelist_options', $defaults);
}
*/

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
function initialize_embed_options() {

    register_setting(
            $this->plugin_slug.'_options', $this->plugin_slug.'_options', array($this, 'validate_input')
    );

    // First, we register a section. This is necessary since all future options must belong to a 
    add_settings_section(
            'embed_display_button_section', // ID used to identify this section and with which to register options
            __('Display buttons', $this->plugin_slug), // Title to be displayed on the administration page
            array($this, 'embed_display_callback'), // Callback used to render the description of the section
            $this->plugin_slug.'_embed_display_button_section' // Page on which to add this section of options
    );

    add_settings_section(
        $this->plugin_slug.'_options', 
        __('Embed options', $this->plugin_slug), 
        array($this, 'embed_settings_callback'), 
        $this->plugin_slug.'_options_section'
    );

    // Next, we'll introduce the fields for toggling the visibility of content elements.
    add_settings_field(
        'cart_button_display', 
        __('Show cart button', $this->plugin_slug), 
        array($this, 'cart_button_display_callback'), 
        $this->plugin_slug.'_options_section', 
        $this->plugin_slug.'_options', 
        array(
            'description' => __('These products will not show up in the pricefile.', $this->plugin_slug),
        )
    );
    
    add_settings_field(
        'product_button_display', 
        __('Show product button', $this->plugin_slug), 
        array($this, 'product_button_display_callback'), 
        $this->plugin_slug.'_options_section', 
        $this->plugin_slug.'_options', 
        array(
            'description' => __('These products will not show up in the pricefile.', $this->plugin_slug),
        )
    );
    add_settings_field(
        'product_button_visible_for', 
        __('Show product button for', $this->plugin_slug), 
        array($this, 'product_button_visible_for'), 
        $this->plugin_slug.'_options_section', 
        $this->plugin_slug.'_options', 
        array(
            'description' => __('These products will not show up in the pricefile.', $this->plugin_slug),
        )
    );
    
    
    add_settings_section(
        'embed_display_button_section', // ID used to identify this section and with which to register options
        __('Display buttons', $this->plugin_slug), // Title to be displayed on the administration page
        array($this, 'embed_display_callback'), // Callback used to render the description of the section
        $this->plugin_slug.'_embed_display_button_section' // Page on which to add this section of options
    );    
    add_settings_field(
        'product_button_display', 
        __('Show product button', $this->plugin_slug), 
        array($this, 'product_button_display_callback'), 
        $this->plugin_slug.'_options_section', 
        $this->plugin_slug.'_options', 
        array(
            'description' => __('These products will not show up in the pricefile.', $this->plugin_slug),
        )
    );
    
}

function validate_input($input) {
    if (!is_array($input))
        return false;

    $output = $input;

    //Apply filter_input on all values
    array_walk_recursive($output, array($this, 'filter_input'));

    // Return the array processing any additional functions filtered by this action
    return apply_filters($this->plugin_slug . '_validate_input', $output, $input);
}

// end sandbox_theme_validate_input_examples

function filter_input(&$input) {

    $input = strip_tags(stripslashes($input));
}
/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the General Options page. 
 *
 * It's called from the 'sandbox_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function embed_settings_callback() {

    
    
}

function cart_button_display_callback()
{
    //$options = get_option( $this->plugin_slug.'_options', array('output_prices' => '') );
    $options = $this->plugin_options;

    echo '<select id="cart_button_display" name="'.$this->plugin_slug.'_options[cart_button_display]">';
    echo '<option value=""' . selected($options['cart_button_display'], 'none', false) . '>' . __('None', $this->plugin_slug) . '</option>';
    echo '<option value="woocommerce_after_cart_contents"' . selected($options['cart_button_display'], 'woocommerce_after_cart_contents', false) . '>' . 'woocommerce_after_cart_contents' . '</option>';
    echo '</select>';
}

function product_button_display_callback()
{
    //$options = get_option( $this->plugin_slug.'_options', array('output_prices' => '') );
    $options = $this->plugin_options;

    echo '<select id="product_button_display" name="'.$this->plugin_slug.'_options[product_button_display]">';
    echo '<option value=""' . selected($options['product_button_display'], 'none', false) . '>' . __('None', $this->plugin_slug) . '</option>';
    echo '<option value="woocommerce_share"' . selected($options['product_button_display'], 'woocommerce_share', false) . '>' . 'woocommerce_share' . '</option>';
    echo '</select>';
}

function product_button_visible_for() {
    global $woocommerce;

    //$shipping_methods_ids = get_option($this->plugin_slug.'_options', FALSE);
    //$shipping_methods_ids = $shipping_methods_ids['shipping_methods'];

    $roles = get_editable_roles();
    
    $selected_roles = (empty($this->plugin_options['product_button_display_for']) ?  array() : $this->plugin_options['product_button_display_for'] );
/*
    print_r($selected_roles);
    
    print_r($roles);
  */  
    
    if ($roles) {
        foreach ($roles as $key => $data) {
            echo '<label class="shipping-method"> ';
            echo '<span>'.esc_html($data['name']).'</span>';
            echo '<input type="checkbox" name="'.$this->plugin_slug.'_options[product_button_display_for][]" value="'.$key.'"'.(in_array($key, $selected_roles) ? 'checked="checked"' : '').'/>';
            echo '</label>';
        }
    }
}


/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

function output_prices_callback($args) {

    //$options = get_option( $this->plugin_slug.'_options', array('output_prices' => '') );
    $options = $this->plugin_options;

    echo '<select id="output_prices" name="'.$this->plugin_slug.'_options[output_prices]">';
    echo '<option value="shop"' . selected($options['output_prices'], 'shop', false) . '>' . __('Same as shop', $this->plugin_slug) . '</option>';
    echo '<option value="including"' . selected($options['output_prices'], 'including', false) . '>' . __('Including VAT', $this->plugin_slug) . '</option>';
    echo '<option value="excluding"' . selected($options['output_prices'], 'excluding', false) . '>' . __('Excluding VAT', $this->plugin_slug) . '</option>';
    echo '</select>';
}

function exclude_products_callback($args) {
    global $woocommerce;

    $product_ids = (empty($this->plugin_options['exclude_ids']) ?  array() : $this->plugin_options['exclude_ids'] );
    
    echo '<select id="woocommerce_pricefiles_exclude_ids" name="'.$this->plugin_slug.'_options[exclude_ids][]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="' . __('Search for a product&hellip;', 'woocommerce') . '">';

    if ($product_ids) {
        foreach ($product_ids as $product_id) {

            $product = get_product($product_id);
            $product_name = woocommerce_get_formatted_product_name($product);

            echo '<option value="' . esc_attr($product_id) . '" selected="selected">' . esc_html($product_name) . '</option>';
        }
    }

    echo '</select>';
    echo '<img class="help_tip" data-tip="' . __('Add any products you want to exlude from the price list here.', 'woocommerce') . '" src="' . $woocommerce->plugin_url() . '/assets/images/help.png" height="16" width="16" />';

    echo '<p>' . $args['description'] . '</p>';
}


function shipping_destination_callback($args) {
    global $wc_embed, $woocommerce;
    
    echo '<p>' . $args['description'] . '</p>';

    echo '<div id="shipping-destination">';

    $shipping_destination_values = $this->plugin_options['shipping_destination'];

    if (!$shipping_destination_values) {
        global $wc_pricefiles_globals;
        $shipping_destination_values = $wc_pricefiles_globals['default_shipping_destination'];
    }

    $shipping_fields = $wc_embed->get_shipping_destination_fields();

    foreach ($shipping_fields as $key => $field) {
        $field['required'] = 0; 
        woocommerce_form_field($this->plugin_slug.'_options[shipping_destination][' . $key . ']', $field, $shipping_destination_values[$key]);
    }

    echo '</div>';
}


}
?>