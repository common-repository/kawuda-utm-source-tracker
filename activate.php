<?php
/**
 * Exit if accessed directly!
 *
 * @package           Kawuda
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Create database table
 **/
function kawuda_activate() {
	global $wpdb;
	$charset_coallate = $wpdb->get_charset_collate();

	$table_link = $wpdb->prefix . 'kawuda_link';
	$sql1       = "CREATE TABLE $table_link (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          kawuda_id int(11) NOT NULL,
                          session_id text NOT NULL,
                          action_datetime datetime NOT NULL DEFAULT current_timestamp(),
                          from_url text NOT NULL,
                          to_url text NOT NULL,
                          post_id int(11) NOT NULL,
                          tracking_item_type varchar(200) NOT NULL,
                          tracking_item varchar(200) NOT NULL,
                          PRIMARY KEY (id)
                        )  $charset_coallate";

	$table_name_user = $wpdb->prefix . 'kawuda_user';
	$sql2            = "CREATE TABLE $table_name_user (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          wp_user_id int(11) NOT NULL,
                          session_id varchar(255) NOT NULL,
                          utm_source varchar(255) NOT NULL,
                          utm_medium varchar(255) NOT NULL,
                          utm_campaign varchar(255) NOT NULL,
                          utm_term varchar(255) NOT NULL,
                          utm_content varchar(255) NOT NULL,
                          visit_datetime datetime NOT NULL DEFAULT current_timestamp(),
                          google_id varchar(255) NOT NULL,
                          fb_id varchar(255) NOT NULL,
                          from_site text NOT NULL,
                          user_platform varchar(200) NOT NULL,
                          user_mobile varchar(200) NOT NULL,
                          user_browser varchar(200) NOT NULL,
                          user_ip varchar(200) NOT NULL,
                          PRIMARY KEY (id)
                        )  $charset_coallate";
	// require WordPress dbDelta() function.
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql1 );
	dbDelta( $sql2 );
}
