<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class TrustpilotAPI
{
    public $api_url = 'https://api.trustpilot.com/v1/';
    private $admin;

    public function __construct()
    {
        $this->admin = new Admin();
    }

    public function request($resource)
    {
        if (!$resource) {
            return array(
                'response' => false,
                'message' => 'You must supply a resource param',
            );
        }

        $access_token = json_decode(get_option($this->admin->api_access_token_name));
        if (!$access_token) {
            return array(
                'response' => false,
                'message' => 'Access token not found',
            );
        }

        $api_key = get_option($this->admin->api_key_option_name);
        if (!$api_key) {
            return array(
                'response' => false,
                'message' => 'API Key not found',
            );
        }

        $api_name = get_option($this->admin->api_name_option_name);
        if (!$api_name) {
            return array(
                'response' => false,
                'message' => 'API Name not found',
            );
        }

        return wp_remote_get($this->api_url . $resource, [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token->access,
            ],
            'body' => [
                'apikey' => $api_key,
                'name' => $api_name,
            ],
        ]);
    }
}
