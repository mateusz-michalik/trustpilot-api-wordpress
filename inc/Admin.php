<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Admin
{
    public $settings_slug = 'trustpilot-api-settings';

    public function __construct()
    {
    }

    public function init()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'register_plugin_settings'));
    }

    public function admin_menu()
    {
        add_menu_page(
            'Trustpilot API',
            'Trustpilot API',
            'administrator',
            $this->settings_slug,
            array($this, 'settings_page'),
            'dashicons-cloud'
        );

        add_submenu_page(
            $this->$settings_slug,
            'Trustpilot API',
            'Settings',
            'administrator',
            $this->settings_slug,
            array($this, 'settings_page'),
            'dashicons-cloud'
        );
    }

    public function register_plugin_settings()
    {
        register_setting('trustpilot-api-settings', 'trustpilot_api_key');
        register_setting('trustpilot-api-settings', 'trustpilot_api_secret');
        register_setting('trustpilot-api-settings', 'trustpilot_api_name');
    }

    public function get_plugin_settings_url()
    {
        return admin_url() . 'admin.php?page=' . $this->settings_slug;
    }

    public function settings_page()
    {
        $authentication = new Authentication();

        if (isset($_GET['code']) && $_GET['code'] != '') {
            $get_access_token = $authentication->request_access_token(esc_attr($_GET['code']));
        } else {
            $existing_access_token = json_decode(get_option('trustpilot_api_access_token'));
        }

        //test business unit API call
        if($existing_access_token) {
            $business_unit = wp_remote_get('https://api.trustpilot.com/v1/business-units/find',[
                'headers' => [
                    'Authorization' => 'Bearer ' . $existing_access_token->access,
                ],
                'body' => [
                    'apikey' => get_option('trustpilot_api_key'),
                    'name' => get_option('trustpilot_api_name'),
                ],
            ]);

            if($business_unit['response']['code'] == 200) {
                update_option('trustpilot_api_business_unit', $business_unit['body']);
            }
        }

        include_once TRUSTPILOT_API_WP_PLUGIN_DIR . 'views/settings.php';
    }
}
