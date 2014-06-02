<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$wc_embed = WC_Embed::get_instance();

if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

        wc_embed_delete_plugin_data();

        //delete_site_option( $option_name ); 
        
	if ($blogs) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
                        
                        wc_embed_delete_plugin_data();
                        
			restore_current_blog();
		}
	}
}
else
{
    wc_embed_delete_plugin_data();
}

function wc_embed_delete_plugin_data()
{
    global $wc_embed;
    
    
    unregister_setting($wc_embed->plugin_slug.'_options');
    
    delete_option('embed_settings');
    delete_option('affiliate_settings');
    
    /* @TODO: delete all transient, options and files you may have added 
    delete_transient( 'TRANSIENT_NAME' );
    delete_option('OPTION_NAME');
    //info: remove custom file directory for main site 
    $upload_dir = wp_upload_dir();
    $directory = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . "CUSTOM_DIRECTORY_NAME" . DIRECTORY_SEPARATOR;
    if (is_dir($directory)) {
            foreach(glob($directory.'*.*') as $v){
                    unlink($v);
            }
            rmdir($directory);
    }
    */
}
