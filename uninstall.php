<?php

// security check so this file doesn't execute outside of wordpress context:
if(!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}

// delete all plugin settings ONLY if the user requested it:
global $wpdb;
$delete_settings = $wpdb->get_var("SELECT option_value FROM $wpdb->options WHERE option_name = 'wpoa_delete_settings_on_uninstall'");
if ($delete_settings) {
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wpoa_%';");
	$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'wpoa_%';");
}

?>