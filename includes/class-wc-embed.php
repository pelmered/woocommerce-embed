<?php

/**
 * Plugin Name.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
add_action('all', 'print_the_filter');

function print_the_filter()
{
    if (current_filter() != 'gettext')
    {

        //    echo '<p>'.current_filter().'</p>';
    }
}

class WC_Embed
{

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0.0';

    /**
     * @TODO - Rename "plugin-name" to the name your your plugin
     *
     * Unique identifier for your plugin.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'wc-embed';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
    public $template_url;

    protected $plugin_options;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct()
    {
        $this->plugin_options = get_option($this->plugin_slug.'_options');

        
        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));
        add_action('init', array($this, 'plugin_init'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));
    }

    function plugin_init()
    {
        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        $this->template_url = apply_filters('woocommerce_embed_template_url', 'wc-embed/');

        
        if (is_admin())
        {
            require_once( WP_EMBED_PLUGIN_PATH . 'includes/class-wc-embed-settings.php' );

            new WC_Embed_Settings($this->plugin_slug);
        }
        

        if (filter_input(INPUT_GET, 'wce_embed') == 1)
        {
            add_action('get_header', array($this, 'display_embed_view'));
        }

        //if( is_product() )
        //{

        /*
        $current_user = wp_get_current_user();
        //$current_user->roles;
        $this->plugin_options['product_button_display_for'];
        */
        
        //if( !empty($this->plugin_options['cart_button_display']))
        //{
            add_action('woocommerce_share', array($this, 'display_product_embed_button'));        
        //}
        
        //}

        //if( !empty($this->plugin_options['cart_button_display']) )
        //{
            add_action('woocommerce_after_cart_contents', array($this, 'display_cart_embed_button'));
        //}
        
        
        $this->template_hooks();
    }

    function template_hooks()
    {
        
        
        
        add_action('woocommerce_embed_button', function($params = array()) {
            $this->get_plugin_template('single-embed/button.php', $params);
        });
        add_action('woocommerce_embed_before_single_product', function($params = array()) {
            $this->get_plugin_template('single-embed/before.php', $params);
        });
        add_action('woocommerce_embed_before_single_product_summary', function($params = array()) {
            $this->get_plugin_template('single-embed/before-summary.php', $params);
        });
        add_action('woocommerce_embed_after_single_product_summary', function($params = array()) {
            $this->get_plugin_template('single-embed/after-summary.php', $params);
        });
        add_action('woocommerce_embed_after_single_product', function($params = array()) {
            $this->get_plugin_template('single-embed/after.php', $params);
        });

        
        add_action('woocommerce_embed_before_loop', function($params = array()) {
            $this->get_plugin_template('loop-embed/before-loop.php', $params);
        });
        add_action('woocommerce_embed_after_loop', function($params = array()) {
            $this->get_plugin_template('loop-embed/after-loop.php', $params);
        });

        
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite())
        {

            if ($network_wide)
            {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id)
                {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            }
            else
            {
                self::single_activate();
            }
        }
        else
        {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite())
        {

            if ($network_wide)
            {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id)
                {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            }
            else
            {
                self::single_deactivate();
            }
        }
        else
        {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog'))
        {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate()
    {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', WP_EMBED_PLUGIN_URL . 'assets/css/public.css', array(), self::VERSION);
        wp_enqueue_style($this->plugin_slug . '-embed-styles', WP_EMBED_PLUGIN_URL . 'assets/css/embed.css', array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script( $this->plugin_slug . '-plugin-script', WP_EMBED_PLUGIN_URL.'assets/js/public.js', array( 'jquery' ), self::VERSION );

		$iframe_data = [
			'wce_embed_products' => (is_cart() ? $this->get_cart_ids() : get_the_ID()),
			'wce_embed' => '1',
			'wce_embed_site_url' => site_url(),
		];
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'wce_embed_iframe_data', $iframe_data );

		wp_enqueue_script( $this->plugin_slug . '-plugin-script');
    }
    
    function get_cart_ids()
    {
        global $woocommerce;
        //print_r($woocommerce->cart->get_cart() );

        $cart = $woocommerce->cart->get_cart();
        $ids = array();

        foreach ( $cart as $cart_item_key => $values )
        {
            if ($values['variation_id'] > 0 && $values['data']->variation_has_stock)
            {
                // Variation has stock levels defined so its handled individually
                $ids[] =$values['variation_id'];
            }
            else
            {
                $ids[] =$values['product_id'];
            }
        }
        
        return implode(',', $ids);
    }
    
    function get_plugin_template($template, $params = array())
    {
        woocommerce_get_template($template, $params, $this->template_url, WP_EMBED_PLUGIN_PATH . '/templates/');
    }

    /**
     * NOTE:  Actions are points in the execution of a page or process
     *        lifecycle that WordPress fires.
     *
     *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0
     */
    public function display_product_embed_button()
    {
        $this->get_plugin_template('embed-product-button.php');
    }
    public function display_cart_embed_button()
    {
        $this->get_plugin_template('embed-cart-button.php');
    }

    function display_embed_view()
    {

        //if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

        global $woocommerce, $woocommerce_loop, $product, $post;
        
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
        remove_action('wp_head', '_admin_bar_bump_cb');

        $product_ids = explode(',', filter_input(INPUT_GET, 'wce_embed_products'));
        //print_r($product_ids);
        $product_count = count($product_ids);

        if ($product_count > 1)
        {
            $this->get_plugin_template('loop-embed-view.php', array(
                'post' => $post,
                'product_ids' => $product_ids,
                'view_settings' => array(
                    'title'     => filter_input(INPUT_GET, 'wce_show_title'),
                    'image'     => filter_input(INPUT_GET, 'wce_show_image'),
                    'price'     => filter_input(INPUT_GET, 'wce_show_price'),
                    'rating'    => filter_input(INPUT_GET, 'wce_show_rating'),
                    'desc'      => filter_input(INPUT_GET, 'wce_show_desc'),
                    'size'      => filter_input(INPUT_GET, 'wce_embed_size'),
                )
            ));
            /*
            $woocommerce_loop['columns'] = 5;

            woocommerce_product_loop_start();
            
            foreach($product_ids AS $pid)
            {
                $product = get_product($pid);
                wc_get_template_part( 'content', 'product' );
                
            }
            
            woocommerce_product_loop_end();
            */
        }
        elseif ($product_count == 1)
        {
            $product = get_product($product_ids[0]);
            $post = get_post($product_ids[0]);
            //print_r($product);
            setup_postdata($post);
            //wc_get_template_part( 'content', 'single-product' );
            //woocommerce_reset_loop();

            $this->get_plugin_template('single-embed-view.php', array(
                'post' => $post,
                'view_settings' => array(
                    'title'     => filter_input(INPUT_GET, 'wce_show_title'),
                    'image'     => filter_input(INPUT_GET, 'wce_show_image'),
                    'price'     => filter_input(INPUT_GET, 'wce_show_price'),
                    'rating'    => filter_input(INPUT_GET, 'wce_show_rating'),
                    'desc'      => filter_input(INPUT_GET, 'wce_show_desc'),
                    'size'      => filter_input(INPUT_GET, 'wce_embed_size'),
                )
            ));

            //woocommerce_reset_loop();
            //require_once( WP_EMBED_PLUGIN_PATH . 'template/single-embed-view.php' );
        }
        else
        {
            die('Error: no product');
        }
        die();
    }
    
    
    
    /**
     * action_links function.
     *
     * @access public
     * @param mixed $links
     * @return void
     */
    public function action_links($links)
    {

        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=wc-embed') . '">' . __('Settings', $this->plugin_slug) . '</a>',
            '<a href="https://wordpress.org/plugins/woocommerce-embed/">' . __('Info & Support', $this->plugin_slug) . '</a>',
        );

        return array_merge($plugin_links, $links);
    }

}
