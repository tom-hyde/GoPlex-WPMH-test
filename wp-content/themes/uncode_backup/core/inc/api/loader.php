<?php
/**
 * Load API classes and functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once 'api-functions.php';

if ( is_admin() ) {
	require_once 'class-api-license.php';
	require_once 'class-theme-registration.php';
	require_once 'class-updater.php';
}
