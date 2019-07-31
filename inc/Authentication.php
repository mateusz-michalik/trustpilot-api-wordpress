<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Authentication
{
    public $oauth_url = 'https://authenticate.trustpilot.com';
    public $access_token_url = 'https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/accesstoken';
    private $admin;

    public function __construct()
    {
        $this->admin = new Admin();
    }

    public function get_oauth_url()
    {
        $url_params = [
            'client_id' => esc_attr(get_option('trustpilot_api_key')),
            'redirect_uri' => $this->admin->get_plugin_settings_url(),
            'response_type' => 'code',
        ];

        return $this->oauth_url . '?' . http_build_query($url_params);
    }

    public function request_access_token($code)
    {
        $api_key = get_option('trustpilot_api_key');
        $api_secret = get_option('trustpilot_api_secret');
        $authorization = base64_encode($api_key . ':' . $api_secret);

        $access_token = wp_remote_post($this->access_token_url, [
            'headers' => [
                'Authorization' => 'Basic [' . $authorization . ']',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->admin->get_plugin_settings_url(),
                'code' => $code,
                'client_id' => $api_key,
                'client_secret' => $api_secret,
            ],
        ]);

        if ($access_token['response']['code'] == 200) {
            update_option('trustpilot_api_access_token', $access_token['body']);
        }

        return $access_token;
    }
}
