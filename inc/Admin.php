<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Admin
{
    public $settings_slug = 'trustpilot-api-settings';
    public $oauth_url = 'https://authenticate.trustpilot.com';
    public $access_token_url = 'https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/accesstoken';

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
    }

    public function get_plugin_settings_url()
    {
        return admin_url() . 'admin.php?page=' . $this->settings_slug;
    }

    public function get_oauth_url()
    {
        $url_params = [
            'client_id' => esc_attr(get_option('trustpilot_api_key')),
            'redirect_uri' => $this->get_plugin_settings_url(),
            'response_type' => 'code',
        ];

        return $this->oauth_url . '?' . http_build_query($url_params);
    }

    public function settings_page()
    {
        if (isset($_GET['code']) && $_GET['code'] != '') {
            $api_key = esc_attr(get_option('trustpilot_api_key'));
            $api_secret = esc_attr(get_option('trustpilot_api_secret'));
            $authorization = base64_encode($api_key . ':' . $api_secret);
            $code = esc_attr($_GET['code']);

            $access_token = wp_remote_post($this->access_token_url, [
                'headers' => [
                    'Authorization' => 'Basic [' . $authorization . ']',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => [
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $this->get_plugin_settings_url(),
                    'code' => $code,
                    'client_id' => $api_key,
                    'client_secret' => $api_secret,
                ],
            ]);

            if ($access_token['response']['code'] == 200) {
                update_option('trustpilot_api_access_token', $access_token['body']);
            }
        }

        include_once TRUSTPILOT_API_WP_PLUGIN_DIR . 'views/settings.php';
    }
}
