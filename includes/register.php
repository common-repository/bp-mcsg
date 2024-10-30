<br/>
<strong>Username: </strong><?php echo get_user_meta( $bp->loggedin_user->id, 'minecraft_username', true ); ?>
<br/><br/>
<?php
if ( !get_user_meta( $bp->loggedin_user->id, 'minecraft_username', true ) || is_super_admin() ) {
	?>
	<form name="mc_username_form" method="post">
	<?php wp_nonce_field( 'mc_username', 'minecraft', true, true ) ?>
	<input type="hidden" name="wp_id" id="wp_id" value="<?php echo $bp->loggedin_user->id; ?>" />
	Add or update MC username: <input name="mc_username" id="mc_username" type="text"></input>  
	<input type="submit" value="Submit"></input>
	</form>
	<br/>
	<?php
}
?>
<p>You can only set your username <strong>once</strong>.  If you want to change it after this you must contact an admin.</p>
<p>Usernames are NOT case-sensitive and will be converted to lowercase before saving.</p>
