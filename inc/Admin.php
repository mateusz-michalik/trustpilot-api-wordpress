<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Admin
{
    public $settings_slug = 'trustpilot-api-settings';
    public $api_key_option_name = 'trustpilot_api_key';
    public $api_secret_option_name = 'trustpilot_api_secret';
    public $api_name_option_name = 'trustpilot_api_name';
    public $api_access_token_name = 'trustpilot_api_access_token';

    public function __construct()
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
            $this->settings_slug,
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
        register_setting('trustpilot-api-settings', $this->api_key_option_name);
        register_setting('trustpilot-api-settings', $this->api_secret_option_name);
        register_setting('trustpilot-api-settings', $this->api_name_option_name);
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
            $existing_access_token = json_decode(get_option($this->api_access_token_name));
        }

        $trustpilot_business_unit = new TrustpilotBusinessUnit();
        $business_unit = $trustpilot_business_unit->get_stored_business_unit();

        include_once TRUSTPILOT_API_WP_PLUGIN_DIR . 'views/settings.php';
    }
}
