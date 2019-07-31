<?php

namespace Milchek\TrustpilotApiWordPress;

if (!defined('ABSPATH')) {
    exit;
}

class Admin
{
    public $settings_slug = 'trustpilot-api-settings';

    public function __construct()
    {
    }

    public function init()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'register_plugin_settings'));
    }

    public function admin_menu()
    {
        add_menu_page(
            'Trustpilot API',
            'Trustpilot API',
            'administrator',
            $this->settings_slug,
            array($this, 'settings_page'),
            'dashicons-cloud'
        );

        add_submenu_page(
            $this->$settings_slug,
            'Trustpilot API',
            'Settings',
            'administrator',
            $this->settings_slug,
            array($this, 'settings_page'),
            'dashicons-cloud'
        );
    }

    public function register_plugin_settings()
    {
    }

    public function settings_page()
    {
        include_once TRUSTPILOT_API_WP_PLUGIN_DIR . 'views/settings.php';
    }
}
