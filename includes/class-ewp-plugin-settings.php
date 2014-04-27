<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Description of class-plugin-settings
 *
 * @author peter
 */
class EWP_Plugin_Settings {
    
    
    public $plugin_slug;
    
    private $licence_options = array();
    
    public $EWP_Licence_Key_Handler;
    
    function __construct( $plugin_slug ) {
        
        $this->plugin_slug = $plugin_slug;
        
        $this->licence_options = get_option($this->plugin_slug.'_licence_data', $this->licence_options_defaults());
    
        add_action('admin_init', array($this, 'intialize_licence_data'));
        
        //$this->intialize_licence_data();
        
        add_action('wp_ajax_ewp_get_latest_news', array($this, 'ewp_get_latest_news'));
        

        
        $this->EWP_Licence_Key_Handler = new EWP_Licence_Key_Handler($this->plugin_slug);
    }

    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    function display_settings_page() {

        $tabs = array(
            'licence_options' => array(
                'name' => __('Licence options', $this->plugin_slug),
                'callback' => array($this, 'licence_options_page_settings')
            )
        );

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
    
    
    function licence_options_page_settings() {
        
        settings_fields($this->plugin_slug.'_licence_data');
        do_settings_sections($this->plugin_slug.'_licence_data_section');
        
        if( $this->licence_options['activated'] == 1 )
        {
            echo '<input type="hidden" name="'.$this->plugin_slug.'_licence_data[action]" value="deactivate" />';
            submit_button( __('Deactivate licence', $this->plugin_slug));
        }
        else
        {
            echo '<input type="hidden" name="'.$this->plugin_slug.'_licence_data[action]" value="activate" />';
            submit_button( __('Activate licence', $this->plugin_slug));
        }
        
    }
    
    
    function intialize_licence_data() {
        
        register_setting( 
            $this->plugin_slug.'_licence_data', 
            $this->plugin_slug.'_licence_data', 
            array($this, 'validate_licence_data')
        );

        // API Key
        add_settings_section(
            $this->plugin_slug.'_licence_data', 
            __('License Information', $this->plugin_slug), 
            array($this, 'ewp_licence_key_text'), 
            $this->plugin_slug.'_licence_data_section'
        );
        add_settings_field(
            'licence_key', 
            __('License Key', $this->plugin_slug), 
            array($this, 'ewp_licence_key_field'), 
            $this->plugin_slug.'_licence_data_section', 
            $this->plugin_slug.'_licence_data'
        );
        add_settings_field(
            'api_email', 
            __('License email', $this->plugin_slug), 
            array($this, 'ewp_api_email_field'), 
            $this->plugin_slug.'_licence_data_section', 
            $this->plugin_slug.'_licence_data'
        );

        // Activation settings
        register_setting('am_deactivate_example_checkbox', 'am_deactivate_example_checkbox', array($this, 'ewp_license_key_deactivation'));
        add_settings_section('deactivate_button', 'Plugin License Deactivation', array($this, 'ewp_deactivate_text'), 'api_manager_example_deactivation');
        add_settings_field('deactivate_button', 'Deactivate Plugin License', array($this, 'ewp_deactivate_textarea'), 'api_manager_example_deactivation', 'deactivate_button');
        
    }
    
    function licence_options_defaults() {
        
        return array(
            'licence_key'   => '',
            'licence_email' => '',
            'activated'     => 0,
            'product_id'    => $this->plugin_slug,
        );
        
    }

    // Provides text for api key section
    public function ewp_licence_key_text() {
        
        if( $this->licence_options['activated'] == 1 )
        {
            echo '<p style="color: green;">Licence Active</p>';
        }
        else
        {
            echo '<p style="color: red;">Licence Inactive</p>';
        }
        

        echo '<input type="hidden" name="'.$this->plugin_slug.'_licence_data[activated]" value="'.$this->licence_options['activated'].'" />';
        echo '<input type="hidden" name="'.$this->plugin_slug.'_licence_data[product_id]" value="'.$this->plugin_slug.'" />';

    }

    // Outputs API License text field
    public function ewp_licence_key_field() {

        //$options = get_option($this->plugin_slug.'_licence_data');

        echo '<input id="licence_key" name="'.$this->plugin_slug.'_licence_data[licence_key]" size="25" type="text" value="'.$this->licence_options['licence_key'].'" />';
        if (!empty($this->licence_options['licence_key'])) 
        {
            echo '<span class="icon-pos"><img src="'.plugins_url('', __FILE__).'/assets/images/complete.png" title="" style="padding-bottom: 4px; vertical-align: middle; margin-right:3px;" /></span>';
        } else 
        {
            echo '<span class="icon-pos"><img src="'.plugins_url('', __FILE__).'/assets/images/warn.png" title="" style="padding-bottom: 4px; vertical-align: middle; margin-right:3px;" /></span>';
        }
    }

        // Outputs API License email text field
        public function ewp_api_email_field() {

            //$options = get_option($this->plugin_slug.'_licence_data');
                
            echo '<input id="licence_email" name="'.$this->plugin_slug.'_licence_data[licence_email]" size="25" type="text" value="'.$this->licence_options['licence_email'].'" />';
            if (!empty($this->licence_options['licence_email'])) {
                echo "<span class='icon-pos'><img src='" . plugins_url('', __FILE__) . "/assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
            } else {
                echo "<span class='icon-pos'><img src='" . plugins_url('', __FILE__) . "/assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
            }
        }

        // Sanitizes and validates all input and output for Dashboard
        public function validate_licence_data($input) {

            // Load existing options, validate, and update with changes from input before returning
            $options = get_option($this->plugin_slug.'_licence_data');

            $options['licence_key'] = trim($input['licence_key']);
            $options['licence_email'] = trim($input['licence_email']);

            $licence_email = trim($input['licence_email']);
            $licence_key = trim($input['licence_key']);

            $args = array(
                'email' => $licence_email,
                'licence_key' => $licence_key,
            );
            
            if( $options['activated'] == 1 && $input['action'] == 'deactivate')
            {
                $activate_results = $this->EWP_Licence_Key_Handler->deactivate($args);
                $options['activated'] = 0;
                
                add_settings_error('activate_text', 'activate_msg', "Licence deactivated.", 'updated');
            }
            //die();
            //if ($activation_status == 'Deactivated' || $activation_status == '' || $licence_key == '' || $licence_email == '' || $checkbox_status == 'on' || $current_licence_key != $licence_key) 
            else
            {

                /**
                 * If this is a new key, and an existing key already exists in the database,
                 * deactivate the existing key before activating the new key.
                 */
                $current_licence_key = $this->get_key();
                
                if ($current_licence_key != $licence_key)
                    $this->replace_license_key($current_licence_key);


                $activate_results = $this->EWP_Licence_Key_Handler->activate($args);
                
                $activate_results = json_decode($activate_results, true);
                
                if ($activate_results['activated'] == 1) {
                    
                    if($activate_results['code'] == 201)
                    {
                        add_settings_error('activate_text', 'activate_msg', "Plugin already activated. {$activate_results['message']}.", 'updated');
                    }
                    else
                    {
                        add_settings_error('activate_text', 'activate_msg', "Plugin activated. {$activate_results['message']}.", 'updated');
                    }
                    $options['activated'] = 1;
                    
                    
                    //update_option('api_example_manager_activated', 'Activated');
                    //update_option('am_deactivate_example_checkbox', 'off');
                }

                if ($activate_results == false) {
                    add_settings_error('licence_key_check_text', 'licence_key_check_error', "Connection failed to the License Key API server. Try again later.", 'error');
                    $options['licence_key'] = '';
                    $options['licence_email'] = '';
                    $options['activated'] = 0;
                    //update_option('api_example_manager_activated', 'Deactivated');
                }

                if (isset($activate_results['code'])) {

                    switch ($activate_results['code']) {
                        case '100':
                            add_settings_error('api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_email'] = '';
                            $options['licence_key'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '101':
                            add_settings_error('licence_key_text', 'licence_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '102':
                            add_settings_error('licence_key_purchase_incomplete_text', 'licence_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '103':
                            add_settings_error('licence_key_exceeded_text', 'licence_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '104':
                            add_settings_error('licence_key_not_activated_text', 'licence_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '105':
                            add_settings_error('licence_key_invalid_text', 'licence_key_invalid_error', "{$activate_results['error']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                        case '106':
                            add_settings_error('sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                            $options['licence_key'] = '';
                            $options['licence_email'] = '';
                            $options['activated'] = 0;
                            //update_option('api_example_manager_activated', 'Deactivated');
                            break;
                    }
                }
            } // End Plugin Activation
            
            return $options;
        }

        public function get_key() {
            $licence_options = get_option($this->plugin_slug.'_licence_data');
            $licence_key = $licence_options['licence_key'];

            return $licence_key;
        }

        // Deactivate the current license key before activating the new license key
        public function replace_license_key($current_licence_key) {

            $default_options = get_option($this->plugin_slug.'_licence_data');

            $api_email = $default_options['licence_email'];

            $args = array(
                'email' => $api_email,
                'licence_key' => $current_licence_key,
            );

            $reset = $this->EWP_Licence_Key_Handler->deactivate($args); // reset license key activation

            if ($reset == true)
                return true;

            return add_settings_error('not_deactivated_text', 'not_deactivated_error', "The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.", 'updated');
            ;
        }

        // Deactivates the license key to allow key to be used on another blog
        public function wc_am_license_key_deactivation($input) {

            $activation_status = get_option('api_example_manager_activated');

            $default_options = get_option($this->plugin_slug.'_licence_data');

            $api_email = $default_options['licence_email'];
            $licence_key = $default_options['licence_key'];

            $args = array(
                'email' => $api_email,
                'licence_key' => $licence_key,
            );

            $options = ( $input == 'on' ? 'on' : 'off' );

            echo $options . '_' . $activation_status . '_' . $licence_key . '|' . $api_email;

            if ($options == 'on' && $activation_status == 'Activated' && $licence_key != '' && $api_email != '') {
                $reset = $this->EWP_Licence_Key_Handler->deactivate($args); // reset license key activation

                if ($reset == true) {
                    $update = array(
                        'licence_key' => '',
                        'licence_email' => ''
                    );
                    $merge_options = array_merge($default_options, $update);

                    update_option($this->plugin_slug.'_licence_data', $merge_options);

                    add_settings_error('wc_am_deactivate_text', 'deactivate_msg', "Plugin license deactivated.", 'updated');

                    return $options;
                }
            } else {

                return $options;
            }
        }

        public function ewp_deactivate_text() {
            
        }

        public function ewp_deactivate_textarea() {

            $activation_status = get_option('am_deactivate_example_checkbox');
            ?>
        <input type="checkbox" id="am_deactivate_example_checkbox" name="am_deactivate_example_checkbox" value="on" <?php checked($activation_status, 'on'); ?> />
        <span class="description"><?php _e('Deactivates plugin license so it can be used on another blog.', 'api-example_manager'); ?></span>
        <?php
    }
    
    
    public function ewp_sidebar() {
        ?>    
        <div id="poststuff">
            <div class="postbox">
                <h3 class="hndle"><?php _e('More plugins from ExtendWP', 'extendwp-updater'); ?></h3>
                <div class="inside" id="ewp-sidebar-content">
                    <ul class="celist">
                        <li><a href="" target="_blank"><?php _e('', 'extendwp-updater'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <script>
        
jQuery(function( $ ){
    
    var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    var data = {
        action: 'ewp_get_latest_news'
    };


    //if (login_check_xhr) login_check_xhr.abort();

    get_latest_news_xhr = $.ajax({
        type: 'POST',
        url: ajax_url,
        data: data,
        //dataType: 'json',
        success: function( response ) {
            
            $('#ewp-sidebar-content').html(response);
            
        }
    });
    
});
        </script>
        <?php
    }
    
    public function ewp_get_latest_news() {
        
        $args = array(
            'timeout'     => 15,
            'redirection' => 5,
            'httpversion' => '1.1',
            'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => true,
            'stream'      => false,
            'filename'    => null
        );
        
        $response = wp_remote_get( 'http://extendwp.com/?get=lastest-news-feed', $args );
        
        echo $response;
        die();
    }
  
    /* ------------------------------------------------------------------------ *
     * Setting Callbacks
     * ------------------------------------------------------------------------ */

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
}
?>
