<?php


function process_minecraft_whitelist_form() {
	global $bp;
	
	if ( isset( $_POST['mc_whitelist_form'] ) ) {
		// reset username
		if ( isset( $_POST['mc_wl_reset_user'] ) ) {
		
			if ( groups_is_user_mod( $bp->loggedin_user->id, $_POST['g_id'] )
				 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['g_id'] )
				 || is_super_admin() ) {
			
				remove_from_wl_db( $_POST['g_id'], get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) );
				update_user_meta( $_POST['wp_id'], 'minecraft_username', '' );
				update_user_meta( $_POST['wp_id'], 'whitelist_status', '' );
				$success = true;
			}
			
		}
		// add to whitelist
		if ( isset( $_POST['mc_wl_add'] ) ) {
			if ( groups_is_user_mod( $bp->loggedin_user->id, $_POST['g_id'] )
				 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['g_id'] )
				 || is_super_admin() ) {
				 
				if ( get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) ) {
					
					add_to_wl_db( $_POST['g_id'], get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) );
					update_user_meta( $_POST['wp_id'], 'whitelist_status', 'whitelisted' );
					$success = true;
				} else {
					$error = '- no MC username set';
				}
			}
		}
		// remove from whitelist
		if ( isset( $_POST['mc_wl_remove'] ) ) {
			if ( groups_is_user_mod( $bp->loggedin_user->id, $_POST['g_id'] )
				 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['g_id'] )
				 || is_super_admin() ) {
				remove_from_wl_db( $_POST['g_id'], get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) );
				update_user_meta( $_POST['wp_id'], 'whitelist_status', '' );
				$success = true;
			}
		}
		// ban user
		if ( isset( $_POST['mc_wl_ban'] ) ) {
			if ( groups_is_user_mod( $bp->loggedin_user->id, $_POST['g_id'] )
				 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['g_id'] )
				 || is_super_admin() ) {
				remove_from_wl_db( $_POST['g_id'], get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) );
				update_user_meta( $_POST['wp_id'], 'whitelist_status', 'banned' );
				$success = true;
			}
		}
		// unban user
		if ( isset( $_POST['mc_wl_unban'] ) ) {
			if ( groups_is_user_mod( $bp->loggedin_user->id, $_POST['g_id'] )
				 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['g_id'] )
				 || is_super_admin() ) {
				update_user_meta( $_POST['wp_id'], 'whitelist_status', '' );
				$success = true;
			}
		}
		
		if ( $success ) {
			bp_core_add_message( 'Saved');
		} else {
			bp_core_add_message( 'Error saving ' . $error , 'warning' );
		}
		
		bp_core_redirect( $_POST['_wp_http_referer'] );
	}
}
add_action( 'get_header', 'process_minecraft_whitelist_form' );

function remove_from_wl_db( $g_id, $username ) {
	$mc_db_ip = groups_get_groupmeta( $g_id, 'mc_wl_ip' );
	$mc_db_username = groups_get_groupmeta( $g_id, 'mc_wl_user' );
	$mc_db_password = groups_get_groupmeta( $g_id, 'mc_wl_pass' );
	$mc_db_database = groups_get_groupmeta( $g_id, 'mc_wl_db_name' );
	mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
	@mysql_select_db( $mc_db_database );
	$remove_query = "DELETE FROM whitelist_users WHERE name='$username'";
	mysql_query( $remove_query );
	
	return true;
}

function add_to_wl_db( $g_id, $username ) {
	$mc_db_ip = groups_get_groupmeta( $g_id, 'mc_wl_ip' );
	$mc_db_username = groups_get_groupmeta( $g_id, 'mc_wl_user' );
	$mc_db_password = groups_get_groupmeta( $g_id, 'mc_wl_pass' );
	$mc_db_database = groups_get_groupmeta( $g_id, 'mc_wl_db_name' );
	mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
	@mysql_select_db( $mc_db_database );
	$insert_query = "INSERT INTO whitelist_users (name) VALUES ('" . mysql_real_escape_string( $username ) . "')";
	mysql_query( $insert_query );
	
	return true;
}

function process_minecraft_username_form() {
	global $bp;
	
	if ( isset( $_POST['minecraft'] ) ) {
	
		if ( ( $_POST['wp_id'] == $bp->loggedin_user->id && !get_user_meta( $_POST['wp_id'], 'minecraft_username', true ) ) 
			   || is_super_admin() ) {
		
			update_user_meta( $_POST['wp_id'], 'minecraft_username', strtolower( mysql_real_escape_string( $_POST['mc_username'] ) ) );
			$success = true;
		}
		
		if ( $success ) {
			bp_core_add_message( 'Username saved.');
		} else {
			bp_core_add_message( 'Error saving username.', 'warning' );
		}
		
		bp_core_redirect( $_POST['_wp_http_referer'] );
	}
}
add_action( 'get_header', 'process_minecraft_username_form' );


// server chat

function server_livechat_who_is_online() {
	global $bp, $wpdb;
	
	if ( $_POST['server_livechat_online_query'] == 1 ) {	
		//die if nonce fail
		check_ajax_referer( 'server_livechat_heartbeat_' . $_POST['server_livechat_group_id'] );
		// only do this is member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || groups_is_user_mod( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || is_super_admin() ) {
			
			// get the users on the server first
			$mc_db_ip = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_uo_ip' );
			$mc_db_username = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_uo_user' );
			$mc_db_password = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_uo_pass' );
			$mc_db_database = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_uo_db_name' );
			mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
			@mysql_select_db( $mc_db_database );
			$query_uo = "SELECT name FROM users_online WHERE online = 1 ORDER BY name ASC";
			$result_uo = mysql_query( $query_uo );
			mysql_close();
			$total_players_online = mysql_num_rows( $result_uo );
			$user_names = array();
			while ( $row = mysql_fetch_array( $result_uo, MYSQL_ASSOC ) ) {
				$user_names[] = strtolower( $row['name'] );
			}
			$user_ids = array();
			$usermeta = $wpdb->prefix . 'usermeta';
			foreach ( $user_names as $mc_user ) {
				$select_user = "SELECT user_id FROM $usermeta WHERE meta_key = 'minecraft_username' AND meta_value = '" . $mc_user . "'";
				$users_on_server[] = $wpdb->get_var( $select_user );
			}
			echo '<h5>Users on the Server</h5>';
			// we have results - anyone that has checked in in last 15 seconds is shown as online
			foreach( $users_on_server as $server_livechat_user_id ) {
				echo '<li class="item-list">';
				echo '<div class="bp-livechat-user-online-avatar">' . bp_core_fetch_avatar( 'item_id=' . $server_livechat_user_id . '&object=user&type=thumb&width=30&height=30' ) . '</div>';
				echo '' . bp_core_get_userlink( $server_livechat_user_id ) . '&nbsp;&nbsp;[ ' . get_user_meta( $server_livechat_user_id, 'minecraft_username', true ) . ' ]';
				echo bp_add_friend_button( $server_livechat_user_id );
				echo '</li>';
			}
			//delete old
			$sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_server_livechat_online WHERE ".
								   "group_id=%d AND user_id=%d", 
								   $_POST['server_livechat_group_id'], $bp->loggedin_user->id  );
			$wpdb->query($sql);
			//add new
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_server_livechat_online".
								   "( group_id, user_id, timestamp ) ".
								   "VALUES ( %d, %d, %s )", 
								   $_POST['server_livechat_group_id'], $bp->loggedin_user->id, time() );
			$wpdb->query($sql);
			//get users viewing this page
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_server_livechat_online ".
								   "WHERE group_id=%d", 
								   $_POST['server_livechat_group_id'] ) );
			if( empty( $rows ) ) {
				echo 'nobody online - how are you even viewing this?';
				die;
			}
			echo '<h5>Users on the Website</h5>';
			// we have results - anyone that has checked in in last 15 seconds is shown as online
			foreach( $rows as $server_livechat_user ) {
				if ( time() - $server_livechat_user->timestamp < 15 ) {
					echo '<li id="' . $server_livechat_user->timestamp . '" class="item-list">';
					echo '<div class="bp-livechat-user-online-avatar">' . bp_core_fetch_avatar( 'item_id=' . $server_livechat_user->user_id . '&object=user&type=thumb&width=30&height=30' ) . '</div>';
					echo '' . bp_core_get_userlink( $server_livechat_user->user_id ) . '&nbsp;&nbsp;[ ' . get_user_meta( $server_livechat_user->user_id, 'minecraft_username', true ) . ' ]';
					echo bp_add_friend_button( $server_livechat_user->user_id );
					echo '</li>';
				}
			}
			die;
		}
	}
}
add_action( 'get_header', 'server_livechat_who_is_online' );

function server_livechat_new_message() {
	global $bp, $wpdb;
	
	if ( $_POST['server_livechat_new_message'] == 1 ) {
		//die if nonce fail
		check_ajax_referer( 'server_livechat_new_message_' . $_POST['server_livechat_group_id'] );
		// only do this is member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || groups_is_user_mod( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || is_super_admin() ) {

			$minecraft_username = get_user_meta( $bp->loggedin_user->id, 'minecraft_username', true );
			if ( !$minecraft_username ) $minecraft_username = $bp->loggedin_user->fullname;
			
			$mc_db_ip = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_ip' );
			$mc_db_username = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_user' );
			$mc_db_password = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_pass' );
			$mc_db_database = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_db_name' );
			mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
			@mysql_select_db( $mc_db_database );
			$datetime = gmdate("Y-m-d\TH:i:s\Z");
			$insert_query = "INSERT INTO `lb-chat` (date, playerid, message) VALUES ('" . $datetime . "','9999','<strong>" . $minecraft_username . ":</strong> " . mysql_real_escape_string( $_POST['server_livechat_textbox'] ) . "')"; 
			mysql_query( $insert_query );
			mysql_close();
			
			$host_mctel = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_telnet_ip' ); 
			$port_mctel = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_telnet_port' ); 
			$user_mctel = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_telnet_user' );
			$pass_mctel = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_telnet_pass' );
			$fp = fsockopen( $host_mctel, $port_mctel, $errno, $errstr, 30 );
			$out = $user_mctel . "\r\n" . $pass_mctel . "\r\n";
			fputs($fp, $out);
			usleep( 1700 );
			$out =  "say " . $minecraft_username . "-" . $_POST['server_livechat_textbox'] . "\r\n";
			fputs($fp, $out);
			fclose($fp);
			die;
		}
	}
}
add_action( 'get_header', 'server_livechat_new_message' );

function server_livechat_load_messages() {
	global $bp, $wpdb;
	
	if ( $_POST['server_livechat_load_messages'] == 1 ) {
		//die if nonce fail
		check_ajax_referer( 'server_livechat_load_messages_' . $_POST['server_livechat_group_id'] );
		// only do this is member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || groups_is_user_mod( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $_POST['server_livechat_group_id'] )
			 || is_super_admin() ) {

			$mc_db_ip = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_ip' );
			$mc_db_username = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_user' );
			$mc_db_password = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_pass' );
			$mc_db_database = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_db_name' );
			$mc_db_table_name = groups_get_groupmeta( $_POST['server_livechat_group_id'], 'mc_sc_table_name' );
			mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
			@mysql_select_db( $mc_db_database );
			$query_sc = "SELECT * FROM `$mc_db_table_name` ORDER BY id DESC LIMIT 150";
			$result_sc = mysql_query( $query_sc );
			$messages = array();
			while ( $row = mysql_fetch_array( $result_sc, MYSQL_ASSOC ) ) {
				$message = new stdClass;
				$message->id = $row['id'];
				$message->date = $row['date'];
				$message->lb_user = $row['playerid'];
				$message->mc_user = '';
				$message->message_content = $row['message'];
				if ( substr( $message->message_content, 0, 4 ) == '/say' ) {
					$message->message_content = substr( $message->message_content, 5 );
				} elseif ( substr( $message->message_content, 0, 1 ) == '/' ) {
					continue;
				}
				$messages[] = $message;
			}
			$query_pl = "SELECT * FROM `lb-players` ORDER BY playerid";
			$result_pl = mysql_query( $query_pl );
			$players = array();
			while ( $row = mysql_fetch_array( $result_pl, MYSQL_ASSOC ) ) {
				$player = new stdClass;
				$player->player_id = $row['playerid'];
				$player->player_name = $row['playername'];
				$players[] = $player;
			}
			mysql_close();
			foreach( $messages as &$message ) {
				if ( $message->lb_user == 9999 ) {
					$message->mc_user = 'webchat9999';
				} else {
					$message->mc_user = $players[$message->lb_user - 1]->player_name;
				}
			}
			foreach( $messages as $message ) {
				if ( $message->mc_user == 'webchat9999' ) {
					echo '<strong>[' . $message->date . '] </strong>' . $message->message_content . '<br/>';
				} else {
					echo '<strong>[' . $message->date . '] ' . $message->mc_user . '</strong>: ' . stripslashes( $message->message_content ). '<br/>';
				}
			}
			die;
		}
	}
}
add_action( 'get_header', 'server_livechat_load_messages' );
?>