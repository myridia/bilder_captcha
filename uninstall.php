<?php

/**
 * Remove options and table when plugin is uninstalled
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

delete_option('cf7ic_plugin_do_activation_redirect');
delete_option('wpforms_status');
delete_option('cf7ic_timestamp');

global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'ai1ic');