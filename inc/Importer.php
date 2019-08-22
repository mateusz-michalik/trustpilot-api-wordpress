<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Importer
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('trustpilot-api-wordpress/v1/', 'import-reviews', array(
                'methods' => 'GET',
                'callback' => array($this, 'import_reviews'),
            ));

            register_rest_route('trustpilot-api-wordpress/v1/', 'import-review-categories', array(
                'methods' => 'GET',
                'callback' => array($this, 'import_review_categories'),
            ));
        });
    }

    public function import_reviews()
    {
        $trustpilot_business_unit = new TrustpilotBusinessUnit();
        $business_unit = $trustpilot_business_unit->get_stored_business_unit();

        if (!$business_unit) {
            wp_send_json(array(
                'result' => 'error',
                'message' => 'No business unit data found',
            ));
        }

        //request all the business units reviews
        $trustpilot_api = new TrustpilotAPI();
        $resource = 'business-units/' . $business_unit->id . '/reviews?language=en';
        $reviews = $trustpilot_api->request($resource);

        if ($reviews['response']['code'] == 200) {
           $reviews = json_decode($reviews['body']);

           wp_send_json($reviews);
        } else {
            wp_send_json(array(
                'result' => 'error',
                'message' => $reviews['response']['code'] . ': ' . $reviews['response']['message']
            ));
        }
    }

    public function import_review_categories()
    {
        wp_send_json(true);
    }
}
