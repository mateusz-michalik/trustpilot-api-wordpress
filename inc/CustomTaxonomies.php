<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class CustomTaxonomies
{
    public function __construct()
    {
        add_action('init', array($this, 'register_custom_taxonomies'));
    }

    public function register_custom_taxonomies()
    {
        $custom_taxonomies = array(
            array(
                'key' => 'review_category',
                'singular' => 'Review Category',
                'plural' => 'Review Categories',
                'hierarchical' => true,
                'post_type_for' => array('review'),
            ),
        );

        foreach ($custom_taxonomies as $custom_taxonomy) {

            $labels = array(
                'name' => $custom_taxonomy['plural'],
                'singular_name' => $custom_taxonomy['singular'],
                'menu_name' => $custom_taxonomy['plural'],
                'all_items' => 'All ' . $custom_taxonomy['plural'],
                'parent_item' => 'Parent ' . $custom_taxonomy['singular'],
                'parent_item_colon' => 'Parent ' . $custom_taxonomy['singular'] . ':',
                'new_item_name' => 'New ' . $custom_taxonomy['singular'] . ' Name',
                'add_new_item' => 'Add New ' . $custom_taxonomy['singular'],
                'edit_item' => 'Edit ' . $custom_taxonomy['singular'],
                'update_item' => 'Update ' . $custom_taxonomy['singular'],
                'separate_items_with_commas' => 'Separate ' . $custom_taxonomy['singular'] . ' with commas',
                'search_items' => 'Search ' . $custom_taxonomy['plural'],
                'add_or_remove_items' => 'Add or remove ' . $custom_taxonomy['plural'],
                'choose_from_most_used' => 'Choose from the most used ' . $custom_taxonomy['plural'],
            );

            $args = array(
                'labels' => $labels,
                'hierarchical' => $custom_taxonomy['hierarchical'],
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => false,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
            );

            register_taxonomy($custom_taxonomy['key'], $custom_taxonomy['post_type_for'], $args);

            if (is_array($custom_taxonomy['post_type_for'])) {
                foreach ($custom_taxonomy['post_type_for'] as $post_type) {
                    register_taxonomy_for_object_type($custom_taxonomy['key'], $post_type);
                }
            } else {
                register_taxonomy_for_object_type($custom_taxonomy['key'], $custom_taxonomy['post_type_for']);
            }

        }
    }
}
