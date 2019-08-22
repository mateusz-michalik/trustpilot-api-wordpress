<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class CustomMetaBoxes
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_review_metaboxes'));
    }

    public function add_review_metaboxes()
    {
        add_meta_box(
            'trustpilot_api_wp_review_meta',
            'Review Data',
            array($this, 'review_metabox'),
            'review',
            'side',
            'default'
        );
    }

    public function review_metabox()
    {
        include_once TRUSTPILOT_API_WP_PLUGIN_DIR . 'views/review_meta.php';
    }
}
