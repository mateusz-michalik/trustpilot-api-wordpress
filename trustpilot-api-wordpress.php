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

define('TRUSTPILOT_API_WP_VERSION', '1.0');
define('TRUSTPILOT_API_WP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TRUSTPILOT_API_WP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TRUSTPILOT_API_WP_ASSETS_URL', TRUSTPILOT_API_WP_PLUGIN_URL . 'assets/');
define('TRUSTPILOT_API_WP_LOG_DIR', TRUSTPILOT_API_WP_PLUGIN_DIR . 'logs/');
define('TRUSTPILOT_API_WP_API_NAMESPACE', 'trustpilot-api-wordpress/v1');

require TRUSTPILOT_API_WP_PLUGIN_DIR . 'vendor/autoload.php';

use Milchek\TrustpilotApiWordPress\Admin;

if (is_admin()) {
    $trustpilo_api_admin = new Admin();
    add_action('init', array($trustpilo_api_admin, 'init'));
}
