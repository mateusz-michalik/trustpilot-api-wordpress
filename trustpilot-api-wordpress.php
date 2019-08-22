<?php
/*
Plugin Name: Trustpilot API for WordPress
Plugin URI: -
Description: A custom plugin to integrate Trustpilot API for WordPress
Version: 0.1
Author: Mateusz Michalik
Author URI: https://www.milchek.com
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

define('TRUSTPILOT_API_WP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TRUSTPILOT_API_WP_PLUGIN_URL', plugin_dir_url(__FILE__));

require TRUSTPILOT_API_WP_PLUGIN_DIR . 'vendor/autoload.php';

use Milchek\TrustpilotApiWordPress\Admin;
use Milchek\TrustpilotApiWordPress\CustomPostTypes;
use Milchek\TrustpilotApiWordPress\CustomTaxonomies;
use Milchek\TrustpilotApiWordPress\Importer;

$trustpilot_custom_post_types = new CustomPostTypes();
$trustpilot_custom_taxonomies = new CustomTaxonomies();
$trustpilot_importer = new Importer();

if (is_admin()) {
    $trustpilot_api_admin = new Admin();
}
