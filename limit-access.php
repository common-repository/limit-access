<?php
/* 
Plugin Name: Limit User Access 
Description: Limits the number of simultaneous sessions a user can host, via IP.
Version: 1.0 
Author: Owen Conti
*/ 

$allowedIPs = 3;
$redirectURL = get_bloginfo('url') . '/limited-access';

global $wpdb;
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

$table_name = $wpdb->prefix . 'limit_access';
$timeOut = 60;

function limit_access_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'limit_access';
	$sql = "CREATE TABLE " . $table_name . " (
				id mediumint NOT NULL AUTO_INCREMENT,
				timestamp timestamp NOT NULL default CURRENT_TIMESTAMP,
				user_id bigint( 20 ) NOT NULL default 0,
				user_ip varchar( 20 ) NOT NULL default '',
				PRIMARY KEY (id)		
	);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
}

register_activation_hook(__FILE__, 'limit_access_install');

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$user_login = $current_user->user_login;

if ($user_login == 'UNLIMITED_ACCESS') {
	exit;
}

if ($user_login == 'special_doc') {
	$allowedIPs = 5;
}



if ($user_id != 0) {

	if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) )
		$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else
		$ip_address = $_SERVER["REMOTE_ADDR"];
	
	list( $ip_address ) = explode( ',', $ip_address );
	
	$user_ip = $ip_address;
	
	$rowCount = $wpdb->query( $wpdb->prepare('SELECT * FROM ' . $table_name . ' WHERE user_id = %d', $user_id), ARRAY_N );
	
	if ($rowCount > $allowedIPs) {
		
		$wpdb->query( $wpdb->prepare( "
		DELETE FROM $table_name
		WHERE (user_ip = %s AND user_id = %d)
		OR timestamp < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL %d SECOND)
	", $user_ip, $user_id, $timeOut ) );
		
		wp_logout();
		wp_redirect( $redirectURL );
		exit;
	}
	
	// Purge table
	$wpdb->query( $wpdb->prepare( "
		DELETE FROM $table_name
		WHERE (user_ip = %s AND user_id = %d)
		OR timestamp < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL %d SECOND)
	", $user_ip, $user_id, $timeOut ) );
	
	$data = compact( 'user_id', 'user_ip' );
	$data = stripslashes_deep( $data );
	$insert_user = $wpdb->insert( $table_name, $data );
}