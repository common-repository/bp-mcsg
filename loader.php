<?php
/*
Plugin Name: Minecraft Server Control Group
Plugin URI: http://linktart.co.uk/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A9NEGJEZR23H4
Description: Enables control of minecraft server whitelist in one group
Version: 1.1
Revision Date: December 13, 2011
Requires at least: WP 3.3.0, BuddyPress 1.5.1
Tested up to: WP 3.3.0, BuddyPress 1.5.1
License: AGPL http://www.fsf.org/licensing/licenses/agpl-3.0.html
Author: David Cartwright
Contributors: Aekeron
Author URI: http://linktart.co.uk
Network: true
*/

/* Only load the component if BuddyPress is loaded and initialized. */
function bp_mscg_init() {

	require( dirname( __FILE__ ) . '/includes/bp-mscg-core.php' );

}
add_action( 'bp_init', 'bp_mscg_init' );

// create the tables
function server_livechat_activate() {
	global $wpdb;

	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_server_livechat_online (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		timestamp int(11) NOT NULL
		 	   ) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );

	dbDelta($sql);

	update_site_option( 'server-livechat-db-version', '1.0' );
}
register_activation_hook( __FILE__, 'server_livechat_activate' );
?>