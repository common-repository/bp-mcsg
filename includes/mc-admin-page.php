<?php
global $bp;
wp_nonce_field( 'groups_edit_save_' . $this->slug );
?>
<input type="checkbox" name="mc_wl_enabled" id="mc_wl_enabled" value="1"  
	<?php 
	if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_enabled' ) == '1' ) {
		echo 'checked=1';
	}
	?>
/>
Enable MineCraft server Whitelist integration
<br/>
Whitelist DB Server IP:<br/>
<input name="mc_wl_ip" id="mc_wl_ip" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_ip' ); ?>"></input><br/>
Whitelist DB Username:<br/>
<input name="mc_wl_user" id="mc_wl_user" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_user' ); ?>"></input><br/>
Whitelist DB Password:<br/>
<input name="mc_wl_pass" id="mc_wl_pass" type="password" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_pass' ); ?>"></input><br/>
Whitelist DB Name:<br/>
<input name="mc_wl_db_name" id="mc_wl_db_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_db_name' ); ?>"></input><br/>
Whitelist DB Table Name:<br/>
<input name="mc_wl_table_name" id="mc_wl_table_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_table_name' ); ?>"></input><br/>

<hr>
<input type="checkbox" name="mc_uo_enabled" id="mc_uo_enabled" value="1"  
	<?php 
	if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_enabled' ) == '1' ) {
		echo 'checked=1';
	}
	?>
/>
Enable MineCraft server Users Online integration
<br/>
Users Online DB Server IP:<br/>
<input name="mc_uo_ip" id="mc_uo_ip" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_ip' ); ?>"></input><br/>
Users Online DB Username:<br/>
<input name="mc_uo_user" id="mc_uo_user" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_user' ); ?>"></input><br/>
Users Online DB Password:<br/>
<input name="mc_uo_pass" id="mc_uo_pass" type="password" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_pass' ); ?>"></input><br/>
Users Online DB Name:<br/>
<input name="mc_uo_db_name" id="mc_uo_db_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_db_name' ); ?>"></input><br/>
Users Online DB Table Name:<br/>
<input name="mc_uo_table_name" id="mc_uo_table_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_table_name' ); ?>"></input><br/>

<hr>
<input type="checkbox" name="mc_sc_enabled" id="mc_sc_enabled" value="1"  
	<?php 
	if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_enabled' ) == '1' ) {
		echo 'checked=1';
	}
	?>
/>
Enable MineCraft server Server Chat integration
<br/>
Server Chat DB Server IP:<br/>
<input name="mc_sc_ip" id="mc_sc_ip" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_ip' ); ?>"></input><br/>
Server Chat DB Username:<br/>
<input name="mc_sc_user" id="mc_sc_user" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_user' ); ?>"></input><br/>
Server Chat DB Password:<br/>
<input name="mc_sc_pass" id="mc_sc_pass" type="password" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_pass' ); ?>"></input><br/>
Server Chat DB Name:<br/>
<input name="mc_sc_db_name" id="mc_sc_db_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_db_name' ); ?>"></input><br/>
Server Chat DB Table Name:<br/>
<input name="mc_sc_table_name" id="mc_sc_table_name" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_sc_table_name' ); ?>"></input><br/>
Telnet Details (needed for sending chat to the server)
<br/>
Minecraft Server Telnet IP:<br/>
<input name="mc_telnet_ip" id="mc_telnet_ip" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_telnet_ip' ); ?>"></input><br/>
Minecraft Server Telnet Port:<br/>
<input name="mc_telnet_port" id="mc_telnet_port" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_telnet_port' ); ?>"></input><br/>
Minecraft Server Telnet Username:<br/>
<input name="mc_telnet_user" id="mc_telnet_user" type="text" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_telnet_user' ); ?>"></input><br/>
Minecraft Server Telnet Password:<br/>
<input name="mc_telnet_pass" id="mc_telnet_pass" type="password" value="<?php echo groups_get_groupmeta( $bp->groups->current_group->id, 'mc_telnet_pass' ); ?>"></input><br/>

<hr>
<!--
<input type="checkbox" name="mc_map_enabled" id="mc_map_enabled" value="1"  
	<?php 
	if ( groups_get_groupmeta( $bp->groups->current_group->id, 'mc_map_enabled' ) == '1' ) {
		echo 'checked=1';
	}
	?>
/>
Enable MineCraft server Map integration
<br/>
-->
<br/>
<input type="submit" name="save" value="Save" />