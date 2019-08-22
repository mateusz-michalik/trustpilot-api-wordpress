<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Authentication
{
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

        return 'https://authenticate.trustpilot.com' . '?' . http_build_query($url_params);
    }

    public function request_access_token($code)
    {
        $api_key = get_option($this->admin->api_key_option_name);
        $api_secret = get_option($this->admin->api_secret_option_name);
        $authorization = base64_encode($api_key . ':' . $api_secret);
        $trustpilot_api = new TrustpilotAPI();

        $access_token = wp_remote_post($trustpilot_api->api_url .
            'oauth/oauth-business-users-for-applications/accesstoken', [
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
            update_option($this->admin->api_access_token_name, $access_token['body']);

            //get stored business unit data or retrieve if we don't have it yet
            $trustpilot_business_unit = new TrustpilotBusinessUnit();
            if (!$trustpilot_business_unit->get_stored_business_unit()) {
                $trustpilot_business_unit->get_business_unit();
            }
        }

        return $access_token;
    }
}
