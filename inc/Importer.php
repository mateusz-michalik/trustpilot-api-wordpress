<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Importer
{
    public $import_page_option_name = 'trustpilot_review_import_page';

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

        $trustpilot_api = new TrustpilotAPI();
        $resource = 'business-units/' . $business_unit->id . '/reviews?language=en';

        //since calls are paginated, check if we're up to a certain page (stored in WP options)
        $import_page = get_option($this->import_page_option_name);
        if ($import_page) {
            $resource = $import_page;
        }

        //request the business units reviews
        $reviews = $trustpilot_api->request($resource);
        $review_import_count = 0;

        //if request caused a WP error
        if (is_wp_error($reviews)) {
            wp_send_json(array(
                'result' => 'error',
                'reviews_imported' => $review_import_count,
                'message' => $reviews->get_error_message(),
            ));
        }

        //if API call failed
        if ($reviews['response']['code'] != 200) {
            wp_send_json(array(
                'result' => 'error',
                'reviews_imported' => $review_import_count,
                'message' => $reviews['response']['code'] . ': ' . $reviews['response']['message'],
            ));
        }

        //otherwise, we're good - json decode the returned data
        $reviews_data = json_decode($reviews['body']);

        //iterate through reviews and store as CPTs
        if ($reviews_data->reviews) {
            foreach ($reviews_data->reviews as $review) {
                $review_import_count++;
            }
        }

        //check if there is another page of data and store this for the next time this endpoint is hit
        if ($reviews_data->links) {
            foreach ($reviews_data->links as $link) {
                if ($link->rel == 'next-page') {
                    update_option(
                        $this->import_page_option_name,
                        str_replace($trustpilot_api->api_url, '', $link->href)
                    );
                }
            }
        }

        wp_send_json(array(
            'result' => 'success',
            'reviews_imported' => $review_import_count,
            'message' => 'Import finished from: ' . $resource,
        ));
    }

    public function import_review_categories()
    {
        wp_send_json(true);
    }
}
