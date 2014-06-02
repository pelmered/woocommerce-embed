<?php

/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   WC_Embed
 * @author    Peter Elmered <peter@elmered.com>, Hans Järvman, Christoffer Rydberg
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/woocommerce-embed/
 * @copyright 2014 Peter Elmered, Hans Järvman, Christoffer Rydberg
 *
 * Plugin Name:       WooCommerce Embed
 * Plugin URI:        http://wordpress.org/plugins/woocommerce-embed/
 * Description:       Embed WooCommerce products
 * Version:           1.0.0
 * Author:            pekz0r, studiogulo, eukaryoter
 * Text Domain:       wc-embed
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/pelmered/woocommerce-embed
 */

// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}

define('WP_EMBED_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WP_EMBED_PLUGIN_URL', plugins_url('', __FILE__) . '/');
define('WP_EMBED_PLUGIN_NAME', untrailingslashit(plugin_basename(__FILE__)));

if (!class_exists('WC_Embed'))
{
    require_once( WP_EMBED_PLUGIN_PATH . 'includes/class-wc-embed.php' );
}

global $wc_embed;

$wc_embed = WC_Embed::get_instance();

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array($wc_embed, 'activate'));
register_deactivation_hook(__FILE__, array($wc_embed, 'deactivate'));

//Loads plugin class (singleton)
add_action('plugins_loaded', array($wc_embed, 'get_instance'));

//Adds action links in the plugins list in WP-Admin
add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($wc_embed, 'action_links'));

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
/*
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wc-embed-admin.php' );
	//add_action( 'plugins_loaded', array( 'WC_Embed_Admin', 'get_instance' ) );

}
*/

