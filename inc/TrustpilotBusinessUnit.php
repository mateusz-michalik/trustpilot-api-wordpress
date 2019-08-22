<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class TrustpilotBusinessUnit
{
    public $business_unit_option_name = 'trustpilot_api_business_unit';

    public function __construct()
    {
    }

    public function get_business_unit()
    {
        $trustpilot_api = new TrustpilotAPI();
        $business_unit_request = $trustpilot_api->request('business-units/find');

        if ($business_unit_request['response']['code'] == 200) {
            update_option($this->business_unit_option_name, $business_unit_request['body']);

            return json_decode($business_unit_request['body']);
        } else {
            return false;
        }
    }

    public function get_stored_business_unit()
    {
        if ($business_unit_json = get_option($this->business_unit_option_name)) {
            return json_decode($business_unit_json);
        } else {
            return false;
        }
    }
}
