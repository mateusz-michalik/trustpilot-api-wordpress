<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class CustomPostTypes
{
    public function __construct()
    {
        add_action('init', array($this, 'register_custom_post_types'));
    }

    public function register_custom_post_types()
    {
        $types = array(
            array(
                'the_type' => 'review',
                'single' => 'Review',
                'plural' => 'Reviews',
                'icon' => 'dashicons-awards',
                'public' => true,
                'supports' => array('title', 'excerpt', 'thumbnail'),
                'has_archive' => true,
            ),
        );

        foreach ($types as $type) {
            $labels = array(
                'name' => $type['plural'],
                'add_new' => 'Add New ' . $type['single'],
                'add_new_item' => 'Add New ' . $type['single'],
                'edit_item' => 'Edit ' . $type['single'],
                'new_item' => 'New ' . $type['single'],
                'view_item' => 'View ' . $type['single'],
                'search_items' => 'Search ' . $type['plural'],
                'not_found' => 'No ' . $type['plural'] . ' found',
                'not_found_in_trash' => 'No ' . $type['plural'] . ' found in Trash',
            );

            $args = array(
                'labels' => $labels,
                'public' => $type['public'],
                'has_archive' => $type['has_archive'],
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => true,
                'capability_type' => 'post',
                'hierarchical' => true,
                'menu_position' => 20,
                'menu_icon' => $type['icon'],
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'supports' => $type['supports'],
            );

            register_post_type($type['the_type'], $args);
        }
    }
}
