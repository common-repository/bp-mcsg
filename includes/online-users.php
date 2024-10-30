<?php
$mc_db_ip = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_ip' );
$mc_db_username = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_user' );
$mc_db_password = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_pass' );
$mc_db_database = groups_get_groupmeta( $bp->groups->current_group->id, 'mc_uo_db_name' );
mysql_connect( $mc_db_ip, $mc_db_username, $mc_db_password );
@mysql_select_db( $mc_db_database );
$query = "SELECT name FROM users_online WHERE online = 1 ORDER BY name ASC";
$result = mysql_query( $query );
mysql_close();
$total_players_online = mysql_num_rows( $result );
$minecraft_ids = array();
while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
	$minecraft_ids[] = strtolower( $row['name'] );
}
?>
<?php if ( bp_group_has_members( 'exclude_admins_mods=0&per_page=999' ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">
		
			<?php echo 'Total Players Online: ' . $total_players_online?>

		</div>


	</div>

	<ul id="member-list" class="item-list" role="main">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<?php foreach ( $minecraft_ids as $minecraft_id ) { ?>
			
				<?php if ( strtolower( get_user_meta( bp_get_group_member_id(), 'minecraft_username', true ) ) == $minecraft_id ) : ?>
									
					<li>
						<a href="<?php bp_group_member_domain(); ?>">

							<?php bp_group_member_avatar_thumb(); ?>

						</a>

						<h5><?php bp_group_member_link(); ?><?php echo '  [ ' . $minecraft_id . ' ]'; ?></h5>
						<span class="activity"><?php bp_group_member_joined_since(); ?></span>

						<?php do_action( 'bp_group_members_list_item' ); ?>

						<?php if ( bp_is_active( 'friends' ) ) : ?>

							<div class="action">

								<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

								<?php do_action( 'bp_group_members_list_item_action' ); ?>

							</div>

						<?php endif; ?>
					</li>
					
				<?php endif; ?>
			
			<?php } ?>
			
		<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php echo 'Total Players Online: ' . $total_players_online?>

		</div>

	</div>

	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>