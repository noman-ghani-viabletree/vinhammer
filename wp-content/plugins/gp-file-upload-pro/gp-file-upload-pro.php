<?php
/**
 * Plugin Name: GP File Upload Pro
 * Description: A professional file and image uploader that feels like magic.
 * Plugin URI: https://gravitywiz.com/documentation/gravity-forms-file-upload-pro/
 * Version: 1.3.6
 * Author: Gravity Wiz
 * Author URI: https://gravitywiz.com/
 * License: GPL2
 * Perk: True
 * Update URI: https://gravitywiz.com/updates/gp-file-upload-pro
 * Text Domain: gp-file-upload-pro
 * Domain Path: /languages
 */

define( 'GPFUP_VERSION', '1.3.6' );

require plugin_dir_path( __FILE__ ) . 'includes/class-gp-bootstrap.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-compatibility-gravitypdf.php';

$gp_file_upload_pro_bootstrap = new GP_Bootstrap( 'class-gp-file-upload-pro.php', __FILE__ );
