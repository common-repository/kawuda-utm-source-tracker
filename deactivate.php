<?php
/**
 * Exit if accessed directly!
 *
 * @package           Kawuda
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Delete database table
 **/
function kawuda_deactivate() {
	global $wpdb;
	$table = $wpdb->prefix . 'kawuda_link';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
	 

	$table = $wpdb->prefix . 'kawuda_user';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}
