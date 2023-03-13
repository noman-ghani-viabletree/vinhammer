<?php
/**
 * Plugin Name: All In One Migration Unlimited Extension
 * Plugin URI: https://google.com/
 * Description: Extension for All-in-One WP Migration that enables unlimited size exports and imports
 * Author: Noman
 * Author URI: https://google.com/
 * Version: 2.52
 * Text Domain: all-in-one-wp-migration-unlimited-extension
 * Domain Path: /languages
 * Network: True
 *
 *
 * Copyright (C) 2023-2024 Noman Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}
delete_option('ai1wm_updater');
add_filter( 'pre_http_request', function( $pre, $parsed_args, $url ){
    if ( strpos( $url, 'https://redirect.wp-migration.com/v1/check/unlimited-extension/' ) !== false ) {
        return new WP_Error();
    } else {
        return $pre;
    }
}, 10, 3 );
if ( is_multisite() ) {
	// Multisite Extension shall be used instead
	return;
}

// Check SSL Mode
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && ( $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ) {
	$_SERVER['HTTPS'] = 'on';
}

// Plugin Basename
define( 'AI1WMUE_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

// Plugin Path
define( 'AI1WMUE_PATH', dirname( __FILE__ ) );

// Plugin URL
define( 'AI1WMUE_URL', plugins_url( '', AI1WMUE_PLUGIN_BASENAME ) );

// Include constants
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'constants.php';

// Include functions
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions.php';

// Include loader
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'loader.php';

// ===========================================================================
// = All app initialization is done in Ai1wmue_Main_Controller __constructor =
// ===========================================================================
$main_controller = new Ai1wmue_Main_Controller();
