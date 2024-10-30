<?php
require ( 'functions.php' );

class BP_MCSG extends BP_Group_Extension {	

	function bp_mcsg() {
		global $bp;
		
		$this->name = 'Minecraft Server';
		$this->slug = 'minecraft-server';

		$this->create_step_position = 16;
		$this->nav_item_position = 41;
		
		if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_enabled' ) == '1' ) {
			$this->enable_nav_item = true;
		} else {
			$this->enable_nav_item = false;
		}		
				
	}	
	
	function create_screen() {
		global $bp;
		
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false;

		echo 'For now, do this config after you have created the group';	
			
		wp_nonce_field( 'groups_create_save_' . $this->slug );
	}

	function create_screen_save() {
		check_admin_referer( 'groups_create_save_' . $this->slug );	
	}

	function edit_screen() {
		global $bp;
		
		if ( !groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) {
			return false;
		}
		
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false;
			
		require( dirname( __FILE__ ) . '/mc-admin-page.php' );
	}

	function edit_screen_save() {
		global $bp;

		if ( !isset( $_POST['save'] ) )
			return false;

		check_admin_referer( 'groups_edit_save_' . $this->slug );
		
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_enabled', $_POST['mc_wl_enabled'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_ip', $_POST['mc_wl_ip'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_user', $_POST['mc_wl_user'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_pass', $_POST['mc_wl_pass'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_db_name', $_POST['mc_wl_db_name'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_wl_table_name', $_POST['mc_wl_table_name'] );

		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_enabled', $_POST['mc_uo_enabled'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_ip', $_POST['mc_uo_ip'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_user', $_POST['mc_uo_user'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_pass', $_POST['mc_uo_pass'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_db_name', $_POST['mc_uo_db_name'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_uo_table_name', $_POST['mc_uo_table_name'] );
		
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_telnet_ip', $_POST['mc_telnet_ip'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_telnet_port', $_POST['mc_telnet_port'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_telnet_user', $_POST['mc_telnet_user'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_telnet_pass', $_POST['mc_telnet_pass'] );
		
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_enabled', $_POST['mc_sc_enabled'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_ip', $_POST['mc_sc_ip'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_user', $_POST['mc_sc_user'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_pass', $_POST['mc_sc_pass'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_db_name', $_POST['mc_sc_db_name'] );
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_sc_table_name', $_POST['mc_sc_table_name'] );
		
		groups_update_groupmeta( $bp->groups->current_group->id, 'mc_map_enabled', $_POST['mc_map_enabled'] );
		
		bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
		
		bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . 'admin/' . $this->slug );
	}

	function display() {
		global $bp;
		
		if ( groups_is_user_member( $bp->loggedin_user->id, $bp->groups->current_group->id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $bp->groups->current_group->id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id )
			 || is_super_admin() ) {
			
			if ( !$current_action = $bp->action_variables[0] ) {
				$current_action = 'server-chat';
			}
			?>
			<div id="subnav" class="item-list-tabs no-ajax" role="navigation">
				<ul>
					<?php
					if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_enabled' ) == '1' ) {
						echo '<li';
						if ( $current_action == 'server-chat' ) echo ' class="current"';
						echo '>
							<a href="server-chat">Server Chat</a>
						</li>';
					}
					if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_enabled' ) == '1' ) {
						echo '<li';
						if ( $current_action == 'whitelist' ) echo ' class="current"';
						echo '>
							<a href="whitelist">Whitelist</a>
						</li>';
					}
					if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_map_enabled' ) == '1' ) {
						echo '<li';
						if ( $current_action == 'map' ) echo ' class="current"';
						echo '>
							<a href="map">Map</a>
						</li>';
					}
					if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_enabled' ) == '1' ) {
						echo '<li';
						if ( $current_action == 'register' ) echo ' class="current"';
						echo '>
							<a href="register">Register</a>
						</li>';
					}
					?>
				</ul>
			</div>
			<?php
			
			switch ( $current_action ) {
				case 'server-chat':
					$this_is_server_chat = true;
					require( dirname( __FILE__ ) . '/server-chat.php' );
					break;
				case 'whitelist':
					require( dirname( __FILE__ ) . '/whitelist.php' );
					break;
				case 'map':
					require( dirname( __FILE__ ) . '/map.php' );
					break;
				case 'register':
					require( dirname( __FILE__ ) . '/register.php' );
					break;
				default:
					echo '<p>Select an option from above.</p>';
			}
		} else {
			echo '<div id="message" class="error"><p>This content is only available to group members.</p></div>';
		}
		
	}

	function widget_display() { 
		// Not used
	}
}

bp_register_group_extension( 'BP_MCSG' );
?>