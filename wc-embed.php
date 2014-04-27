<?php

/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   WC_Embed
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Embed
 * Plugin URI:        @TODO
 * Description:       Embed WooCommerce products
 * Version:           1.0.0
 * Author:            @TODO
 * Author URI:        @TODO
 * Text Domain:       wc-embed
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */
// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}

define('WP_EMBED_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WP_EMBED_PLUGIN_URL', plugins_url('', __FILE__) . '/');
define('WP_EMBED_PLUGIN_NAME', untrailingslashit(plugin_basename(__FILE__)));

if (!class_exists('WC_Pricefiles'))
{
    require_once( WP_EMBED_PLUGIN_PATH . 'includes/class-wc-embed.php' );

    global $wc_embed;

    $wc_embed = WC_Embed::get_instance();


    // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
    register_activation_hook(__FILE__, array($wc_embed, 'activate'));
    //Deletes all data if plugin deactivated
    register_deactivation_hook(__FILE__, array($wc_embed, 'deactivate'));

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($wc_embed, 'action_links'));
}

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace WC_Embed with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook(__FILE__, array('WC_Embed', 'activate'));
register_deactivation_hook(__FILE__, array('WC_Embed', 'deactivate'));

/*
 * @TODO:
 *
 * - replace WC_Embed with the name of the class defined in
 *   `class-plugin-name.php`
 */
add_action('plugins_loaded', array('WC_Embed', 'get_instance'));

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-name-admin.php` with the name of the plugin's admin file
 * - replace WC_Embed_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
/*
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wc-embed-admin.php' );
	//add_action( 'plugins_loaded', array( 'WC_Embed_Admin', 'get_instance' ) );

}
*/
