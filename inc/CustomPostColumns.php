<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class CustomPostColumns
{
    public function __construct()
    {
        add_filter('manage_edit-review_columns', array($this, 'custom_post_columns'));
        add_filter('manage_edit-review_sortable_columns', array($this, 'custom_post_columns_sorting'));
        add_action('manage_review_posts_custom_column', array($this, 'custom_post_columns_data'), 10, 2);
        add_action('pre_get_posts', array($this, 'custom_posts_orderby'));
    }

    public function custom_post_columns($columns)
    {
        $updated_columns = array();

        foreach ($columns as $column_slug => $column_title) {
            //add review meta columns
            if ($column_slug == 'date') {
                $updated_columns['reviewer'] = 'Reviewer';
                $updated_columns['stars'] = 'Stars';
            }

            $updated_columns[$column_slug] = $column_title;
        }

        return $updated_columns;
    }

    public function custom_post_columns_sorting($columns)
    {
        $columns['reviewer'] = 'review_consumer_name';
        $columns['stars'] = 'review_stars';

        return $columns;
    }

    public function custom_posts_orderby($query)
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        switch ($query->get('orderby')) {
            case 'review_consumer_name':
                $query->set('orderby', 'meta_value');
                $query->set('meta_key', 'review_consumer_name');
                break;
            case 'review_stars':
                $query->set('orderby', 'meta_value');
                $query->set('meta_key', 'review_stars');
                $query->set('meta_type', 'numeric');
                break;
        }
    }

    public function custom_post_columns_data($column, $post_id)
    {
        switch ($column) {
            case 'stars':
                echo get_post_meta($post_id, 'review_stars', true);
                break;
            case 'reviewer':
                echo get_post_meta($post_id, 'review_consumer_name', true);
                break;
        }
    }
}
