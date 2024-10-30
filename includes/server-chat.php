<?php
global $bp;
if ( !$this_is_server_chat ) die;
?>
<script type="text/javascript"><?php require( dirname( __FILE__ ) . '/jquery-timers-1.2.js' );?></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	serverLivechatHeartbeat();
	serverLivechatLoadMessages();
	jQuery(document).everyTime(5000, function() {
		serverLivechatLoadMessages();
		serverLivechatHeartbeat();
	}, 0);
});

function serverLivechatHeartbeat(i) {
	jQuery.post("<?php echo $bp->root_domain . '/' . $bp->current_component . '/' . $bp->current_item; ?>", { _ajax_nonce: "<?php echo wp_create_nonce( 'server_livechat_heartbeat_' . $bp->groups->current_group->id ); ?>", server_livechat_online_query: "1", server_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>" }, function(data) {
		jQuery('#server-livechat-users-online').html(data);
	});
}

function serverLivechatsubmitNewMessage(){
	var message_content = jQuery('#server_livechat_textbox').val();
	jQuery.post("<?php echo $bp->root_domain . '/' . $bp->current_component . '/' . $bp->current_item; ?>", { _ajax_nonce: "<?php echo wp_create_nonce( 'server_livechat_new_message_' . $bp->groups->current_group->id ); ?>", server_livechat_new_message: "1", server_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>", server_livechat_textbox: message_content }, function() {
		jQuery('#server_livechat_textbox').val('');
		serverLivechatLoadMessages();
	});
}

function serverLivechatLoadMessages() {	
	jQuery.post("<?php echo $bp->root_domain . '/' . $bp->current_component . '/' . $bp->current_item; ?>", { _ajax_nonce: "<?php echo wp_create_nonce( 'server_livechat_load_messages_' . $bp->groups->current_group->id ); ?>", server_livechat_load_messages: "1", server_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>" }, function(data) {
		jQuery('#server-livechat-container').html(data);
	});
}
</script>

<div id="livechat-chat-box" style="width:70%;float:left;">
	<form>
	<input id="server_livechat_textbox" name="server_livechat_textbox" type="text" size="45"/>
	<input type="submit" value="Say" onClick="serverLivechatsubmitNewMessage();return false;"/>
	</form>
	<div id="server-livechat-container" style="border:1px solid silver;height:400px;margin-top:5px;margin-right:5px;padding-left:3px;overflow:scroll;">
	</div>
</div>

<div id="server-livechat-users-online-container" style="width:30%;float:right;">
	<ul id="server-livechat-users-online" class="item-list" role="main">
	</ul>
</div>