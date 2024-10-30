<?php
global $bp;

$mc_db_ip = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_ip' );
$mc_db_username = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_user' );
$mc_db_password = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_pass' );
$mc_db_database = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_wl_db_name' );
mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
@mysql_select_db( $mc_db_database );
$query = "SELECT name FROM whitelist_users ORDER BY name ASC";
$result = mysql_query( $query );
mysql_close();
$count = 1;
$minecraft_ids = array();
while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
	$minecraft_ids[] = $row['name'];
}
?>
<div class="bp-widget">

	<?php if ( bp_group_has_members( 'exclude_admins_mods=0&per_page=999&exclude_banned=false' ) ) : ?>

			<div class="pagination no-ajax">

				<div id="member-count" class="pag-count">
				
				</div>

			</div>

		<?php if ( groups_is_user_mod( $bp->loggedin_user->id, $bp->groups->current_group->id ) ||
				   groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) : ?>
		
			<ul id="members-list" class="item-list single-line">
				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

					<li class="<?php bp_group_member_css_class(); ?>">
						<?php bp_group_member_avatar_mini() ?>

						<h5>
							<?php bp_group_member_link() ?>
							
							<?php echo ' [ ' . get_user_meta( bp_get_group_member_id(), 'minecraft_username', true ) . ' ] '; ?>
							
							<?php if ( bp_get_group_member_is_banned() ) echo ' (group banned) '; ?>
							
								<span class="small">
									<form name="mc_whitelist_form" method="post">
									<input type="hidden" name="wp_id" id="wp_id" value="<?php echo bp_get_group_member_id(); ?>"/>
									<input type="hidden" name="g_id" id="g_id" value="<?php echo $bp->groups->current_group->id; ?>" />
									<?php wp_nonce_field( 'mc_whitelist', 'mc_whitelist_form', true, true ); ?>
									<?php
									if ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) == 'whitelisted' ) {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;WHITELISTED&nbsp;&nbsp;&nbsp;&nbsp;';
									} elseif ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) != 'banned' ) {
										echo '<input  id="mc_wl_add" name="mc_wl_add" type="submit" value="Add to Whitelist"/>';
									}
									?>
									
									<?php
									if ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) == 'whitelisted' ) {
										echo '<input id="mc_wl_remove" name="mc_wl_remove" type="submit" value="Remove from Whitelist"/>';
									} elseif ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) != 'banned' ) {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;NOT ON WHITELIST&nbsp;&nbsp;&nbsp;&nbsp;';
									} else {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;BANNED&nbsp;&nbsp;&nbsp;&nbsp;';							
									}
									?>
									<?php
									if ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) != 'banned' ) {
										echo '<input id="mc_wl_reset_user" name="mc_wl_reset_user" type="submit" value="Reset MC Username"/>';
									}
									?>
									<?php
									if ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) != 'banned' ) {
										echo '<input id="mc_wl_ban" name="mc_wl_ban" type="submit" value="Ban User"/>';
									} elseif ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) == 'banned' ) {
										echo '<input id="mc_wl_unban" name="mc_wl_unban" type="submit" value="Unban"/>';
									}
									?>
									</form>
								</span>
													
						</h5>
					</li>
					
				<?php endwhile; ?>
			</ul>

		<?php else: ?>
		
			<ul id="members-list" class="item-list single-line">
				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>
					<?php if ( get_user_meta( bp_get_group_member_id(), 'whitelist_status', true ) == 'whitelisted' ) { ?>
						<li class="<?php bp_group_member_css_class(); ?>">
							<?php bp_group_member_avatar_mini() ?>

							<h5>
								<?php bp_group_member_link() ?>
								
								<?php echo ' [ ' . get_user_meta( bp_get_group_member_id(), 'minecraft_username', true ) . ' ] '; ?>
								
								<?php if ( bp_get_group_member_is_banned() ) echo ' (group banned) '; ?>
																					
							</h5>
						</li>
					<?php 
					} 
					?>
					
				<?php endwhile; ?>
			</ul>
			
		<?php endif; ?>
	
	<?php else: ?>

		<div id="message" class="info">
			<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
		</div>

	<?php endif; ?>

</div>