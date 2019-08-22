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
                if ($this->import_review($review)) {
                    $review_import_count++;
                }
            }
        }

        //check if there is another page of data and store this for the next time this endpoint is hit
        if ($reviews_data->links) {
            foreach ($reviews_data->links as $link) {
                if ($link->rel == 'next-page') {
                    $next_page = $link->href;
                    break;
                } else {
                    $next_page = false;
                }
            }

            //if a 'next-page' link was found then write it
            if ($next_page) {
                update_option(
                    $this->import_page_option_name,
                    str_replace($trustpilot_api->api_url, '', $next_page)
                );
            } else {
                //otherwise we're at the end so delete the option so the import can start from page 1
                delete_option($this->import_page_option_name);
            }
        }

        wp_send_json(array(
            'result' => 'success',
            'reviews_imported' => $review_import_count,
            'message' => 'Import finished from: ' . $resource,
            'next_page' => $next_page
        ));
    }

    public function import_review($review)
    {
        //query WP to see if review CPT already exists
        $review_post_query = new \WP_Query(
            array(
                'post_type' => 'review',
                'meta_query' => array(
                    array(
                        'key' => 'review_id',
                        'value' => $review->id,
                        'compare' => '=',
                    ),
                ),
            )
        );

        //if review CPT exists
        if ($review_post_query->have_posts()) {
            $review_post_id = $review_post_query->posts[0]->ID;
        } else {
            //else create a new review CPT
            $review_post_id = wp_insert_post(array(
                'post_title' => $review->title,
                'post_content' => $review->text,
                'post_excerpt' => wp_trim_excerpt($review->text),
                'post_date' => $review->createdAt,
                'post_type' => 'review',
                'post_author' => 1,
                'post_status' => 'publish',
            ));
        }

        //if CPT created, update post meta with some review data and increment counter
        if ($review_post_id) {
            //update the review CPT meta
            update_post_meta($review_post_id, 'review_id', $review->id);
            update_post_meta($review_post_id, 'review_stars', $review->stars);
            update_post_meta($review_post_id, 'review_consumer_id', $review->consumer->id);
            update_post_meta($review_post_id, 'review_consumer_name', $review->consumer->displayName);
            update_post_meta($review_post_id, 'review_consumer_location', $review->consumer->displayLocation);
        }

        return $review_post_id;
    }

    public function import_review_categories()
    {
        wp_send_json(true);
    }
}
