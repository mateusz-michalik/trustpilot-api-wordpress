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
            register_rest_route(TRUSTPILOT_API_WP_API_NAMESPACE, 'import-reviews', array(
                'methods' => 'GET',
                'callback' => array($this, 'import_reviews'),
            ));

            register_rest_route(TRUSTPILOT_API_WP_API_NAMESPACE, 'import-review-categories', array(
                'methods' => 'GET',
                'callback' => array($this, 'import_review_categories'),
            ));
        });
    }

    public function import_reviews()
    {
        wp_send_json(true);
    }

    public function import_review_categories()
    {
        wp_send_json(true);
    }
}
