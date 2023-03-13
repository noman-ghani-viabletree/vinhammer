<?php

/**
 * Ultimate WooCommerce Auction Pro General Setting Tab
 *
 * @package Ultimate WooCommerce Auction Pro
 * @author Nitesh Singh 
 * @since 1.0 
 *
 */

if(isset($_POST['uwa-settings-submit']) == 'Save Changes'){	
		
		if(isset($_POST['uwa_placebid_ajax_enable'])){
			update_option('woo_ua_auctions_placebid_ajax_enable', "yes");
		}else{
			update_option('woo_ua_auctions_placebid_ajax_enable', "no");
		}
		
		if(isset($_POST['uwa_bid_ajax_enable'])){	
			update_option('woo_ua_auctions_bid_ajax_enable', "yes");
		}else{
			update_option('woo_ua_auctions_bid_ajax_enable', "no");
		}
		if (isset($_POST['uwa_bid_ajax_interval'])) {
			update_option('woo_ua_auctions_bid_ajax_interval', $_POST['uwa_bid_ajax_interval']);
		}

		if(isset($_POST['uwa_simple_maskusername_enable'])){
			update_option('uwa_simple_maskusername_enable', "yes");
		} else{
			update_option('uwa_simple_maskusername_enable', "no");
		}
		
		if(isset($_POST['uwa_listpage_sync_clock_enable'])){
			update_option('uwa_listpage_sync_clock_enable', "yes");
		} else{
			update_option('uwa_listpage_sync_clock_enable', "no");
		}
		
		
		

		if(isset($_POST['uwa_auto_order_enable'])){
			update_option('uwa_auto_order_enable', "yes");
		} else{
			update_option('uwa_auto_order_enable', "no");
		}

		if(isset($_POST['uwa_shipping_address'])){
			$ship_address = $_POST['uwa_shipping_address'];
			if($ship_address == "yes"){
				update_option('uwa_shipping_address', "yes");
			}
			else if($ship_address == "no"){
				update_option('uwa_shipping_address', "no");
			}			
		} else{
			update_option('uwa_shipping_address', "no");
		}


		/**
		 * Proxy Bidding Setting
		 */		
		if(isset($_POST['uwa_proxy_bid_enable'])){	
			update_option('uwa_proxy_bid_enable', "yes");			
		}else{
			update_option('uwa_proxy_bid_enable', "no");			
		}
		
		if(isset($_POST['uwa_proxy_maskusername_enable'])){
			update_option('uwa_proxy_maskusername_enable', "yes");
		} else{
			update_option('uwa_proxy_maskusername_enable', "no");
		}		
		if(isset($_POST['uwa_proxy_maskbid_enable'])){
			update_option('uwa_proxy_maskbid_enable', "yes");
		} else{
			update_option('uwa_proxy_maskbid_enable', "no");
		}

		if(isset($_POST['uwa_proxy_same_maxbid'])){
			update_option('uwa_proxy_same_maxbid', "yes");
		} else{
			update_option('uwa_proxy_same_maxbid', "no");
		}

		/*if(isset($_POST['uwa_proxy_disable_reserve_price'])){
			update_option('uwa_proxy_disable_reserve_price', "yes");
		} else{
			update_option('uwa_proxy_disable_reserve_price', "no");
		}*/

		/**
		 * Silent Bidding Setting
		 */
		if(isset($_POST['uwa_silent_bid_enable'])){
			update_option('uwa_silent_bid_enable', "yes");			
		} else{
			update_option('uwa_silent_bid_enable', "no");				
		}
		if(isset($_POST['uwa_silent_maskusername_enable'])){	
			update_option('uwa_silent_maskusername_enable', "yes");
		} else{
			update_option('uwa_silent_maskusername_enable', "no");
		}		
		if(isset($_POST['uwa_silent_maskbid_enable'])){	
			update_option('uwa_silent_maskbid_enable', "yes");
		} else{
			update_option('uwa_silent_maskbid_enable', "no");
		}
		if(isset($_POST['uwa_restrict_bidder_enable'])){	
			update_option('uwa_restrict_bidder_enable', "yes");
		} else{
			update_option('uwa_restrict_bidder_enable', "no");
		}
		if(isset($_POST['uwa_disable_buy_it_now'])){	
			update_option('uwa_disable_buy_it_now', "yes");
		} else{
			update_option('uwa_disable_buy_it_now', "no");
		}	
		if(isset($_POST['uwa_enable_bid_place_warning'])){	
			update_option('uwa_enable_bid_place_warning', "yes");
		} else{
			update_option('uwa_enable_bid_place_warning', "no");
		}	
		
		
		/**
		 * Extra Setting
		 */		
		
		if(isset($_POST['uwa_aviod_snipping_type_main'])){
			update_option('uwa_aviod_snipping_type_main', sanitize_text_field($_POST['uwa_aviod_snipping_type_main']));
		} else {
			delete_option('uwa_aviod_snipping_type_main');
		} 
		
		if(isset($_POST['anti_sniping_timer_update_notification'])){
			update_option('anti_sniping_timer_update_notification', sanitize_text_field($_POST['anti_sniping_timer_update_notification']));
		} else {
			delete_option('anti_sniping_timer_update_notification');
		}
		
		if(isset($_POST['timer_type'])){
			update_option('timer_type', sanitize_text_field($_POST['timer_type']));
		} else {
			delete_option('timer_type');
		}
		
		




		if(isset($_POST['uwa_aviod_snipping_type'])){
			update_option('uwa_aviod_snipping_type', sanitize_text_field($_POST['uwa_aviod_snipping_type']));
		} else {
			delete_option('uwa_aviod_snipping_type');
		}
		
		if($_POST['uwa_auto_extend_when']){
			update_option('uwa_auto_extend_when', absint($_POST['uwa_auto_extend_when']));
		} else {
			delete_option('uwa_auto_extend_when');
		}
		
		if($_POST['uwa_auto_extend_when_m']){
			update_option('uwa_auto_extend_when_m', absint($_POST['uwa_auto_extend_when_m']));
		} else {
			delete_option('uwa_auto_extend_when_m');
		}
		if($_POST['uwa_auto_extend_when_s']){
			update_option('uwa_auto_extend_when_s', absint($_POST['uwa_auto_extend_when_s']));
		} else {
			delete_option('uwa_auto_extend_when_s');
		}
		if($_POST['uwa_auto_extend_time']){
			update_option('uwa_auto_extend_time', absint($_POST['uwa_auto_extend_time']));
		} else {
			delete_option('uwa_auto_extend_time');
		}	
		if($_POST['uwa_auto_extend_time_m']){
			update_option('uwa_auto_extend_time_m', absint($_POST['uwa_auto_extend_time_m']));
		}else {
			delete_option('uwa_auto_extend_time_m');
		}
		if($_POST['uwa_auto_extend_time_s']){
			update_option('uwa_auto_extend_time_s', absint($_POST['uwa_auto_extend_time_s']));
		}else {
			delete_option('uwa_auto_extend_time_s');
		}			
		if (isset($_POST['uwa_can_maximum_bid_amt'])) {
			update_option('uwa_can_maximum_bid_amt', ($_POST['uwa_can_maximum_bid_amt']));
		}
		
		if (isset($_POST['uwa_can_maximum_bid_amt'])) {
			update_option('uwa_can_maximum_bid_amt', ($_POST['uwa_can_maximum_bid_amt']));
		}
		if(isset($_POST['uwa_relist_options'])){
			update_option('uwa_relist_options', sanitize_text_field($_POST['uwa_relist_options']));
		}
		
		
		if(isset($_POST['uwa_silent_outbid_email'])){	
			update_option('uwa_silent_outbid_email', "yes");
		} else{
			update_option('uwa_silent_outbid_email', "no");
		}
		
		if(isset($_POST['uwa_silent_outbid_email_cprice'])){	
			update_option('uwa_silent_outbid_email_cprice', "yes");
		} else{
			update_option('uwa_silent_outbid_email_cprice', "no");
		}
		
		if(isset($_POST['uwa_allow_owner_to_bid'])){	
			update_option('uwa_allow_owner_to_bid', "yes");
		} else{
			update_option('uwa_allow_owner_to_bid', "no");
		}
		
		if(isset($_POST['uwa_allow_admin_to_bid'])){	
			update_option('uwa_allow_admin_to_bid', "yes");
		} else{
			update_option('uwa_allow_admin_to_bid', "no");
		}

		if(isset($_POST['uwa_disable_buy_it_now__bid_check'])){	
			update_option('uwa_disable_buy_it_now__bid_check', "yes");
		} else{
			update_option('uwa_disable_buy_it_now__bid_check', "no");
		}


		if(isset($_POST['uwa_block_reg_user'])){
			$is_updated = update_option('uwa_block_reg_user', $_POST['uwa_block_reg_user']);
			
			if($_POST['uwa_block_reg_user'] == "yes" && $is_updated == true){				

				$blogusers = get_users(array('fields' => array('id')));
				if(count($blogusers) > 0){
					foreach($blogusers as $key => $value ){

						if($value->id){
							$userid = $value->id;
							update_user_meta($userid, "uwa_block_user_status", "uwa_block_user_to_bid");
						}

					} /* end of foreach */
				}
			} /* end of if */

		} else{
			delete_option('uwa_block_reg_user');
		}

		if(isset($_POST['uwa_block_user_text'])){
			update_option('uwa_block_user_text', trim($_POST['uwa_block_user_text']));
		}
		if(isset($_POST['anti_sniping_clock_msg'])){
			update_option('anti_sniping_clock_msg', trim($_POST['anti_sniping_clock_msg']));
		}
		
		
		if (isset($_POST['uwa_global_bid_inc'])) {
			update_option('uwa_global_bid_inc', absint($_POST['uwa_global_bid_inc']));
		}

		
		if(isset($_POST['uwa_cron_type'])){	
			update_option('uwa_cron_type', $_POST['uwa_cron_type']);
		} 
		
		
}

	/**
	 * Getting All Cron Setting Field from DB
	 */	
		
	$uwa_bid_ajax_interval = get_option('woo_ua_auctions_bid_ajax_interval', '25');	
	$uwa_ajax_enable = get_option('woo_ua_auctions_bid_ajax_enable');
	$uwa_placebid_ajax_enable = get_option('woo_ua_auctions_placebid_ajax_enable', "no");  /* set default value to "no" */
	$checked_enable="";
	   	if($uwa_ajax_enable == "yes"){
			$checked_enable = "checked";
	   	}
	$placebid_checked_enable="";
		if($uwa_placebid_ajax_enable == "yes"){
			$placebid_checked_enable = "checked";
	   	}

		
	/**
	 * Getting All Simple bid Setting Field from DB
	 */ 	
	 
	$uwa_simple_maskusername_enable = get_option('uwa_simple_maskusername_enable');
	$uwa_simple_maskusername_checked_enable="";
		if($uwa_simple_maskusername_enable =="yes"){
			$uwa_simple_maskusername_checked_enable = "checked";
		}
		
		
		$uwa_listpage_sync_clock_enable = get_option('uwa_listpage_sync_clock_enable');
	$uwa_listpage_sync_clock_enable_chk="";
		if($uwa_listpage_sync_clock_enable =="yes"){
			$uwa_listpage_sync_clock_enable_chk = "checked";
		}
	 



	

	/**
	 * Getting All Proxy Setting Field from DB
	 */ 	
	$uwa_proxy_bid_enable = get_option('uwa_proxy_bid_enable');
	$proxy_bid_checked_enable="";
		if($uwa_proxy_bid_enable =="yes"){
			$proxy_bid_checked_enable = "checked";
		}
	$uwa_proxy_maskusername_enable = get_option('uwa_proxy_maskusername_enable');
	$uwa_proxy_maskusername_checked_enable="";
		if($uwa_proxy_maskusername_enable =="yes"){
			$uwa_proxy_maskusername_checked_enable = "checked";
		}
	$uwa_proxy_maskbid_enable = get_option('uwa_proxy_maskbid_enable');
	$uwa_proxy_maskbid_checked_enable="";
		if($uwa_proxy_maskbid_enable =="yes"){
			$uwa_proxy_maskbid_checked_enable = "checked";
		}

	$uwa_proxy_same_maxbid = get_option('uwa_proxy_same_maxbid');
	$uwa_proxy_same_checked_maxbid = "";
		if($uwa_proxy_same_maxbid == "yes"){
			$uwa_proxy_same_checked_maxbid = "checked";
		}
		
	/*
	* Getting All Silent Setting Field from DB
	*/ 	
	$uwa_silent_bid_enable = get_option('uwa_silent_bid_enable');
	$silent_bid_checked_enable="";
		if($uwa_silent_bid_enable =="yes"){
			$silent_bid_checked_enable = "checked";
		}
	$uwa_silent_maskusername_enable = get_option('uwa_silent_maskusername_enable');
	$uwa_silent_maskusername_checked_enable="";
		if($uwa_silent_maskusername_enable =="yes"){
			$uwa_silent_maskusername_checked_enable = "checked";
		}
	$uwa_silent_maskbid_enable = get_option('uwa_silent_maskbid_enable');
	$uwa_silent_maskbid_checked_enable="";
		if($uwa_silent_maskbid_enable =="yes"){
			$uwa_silent_maskbid_checked_enable = "checked";
		}
	$uwa_restrict_bidder_enable = get_option('uwa_restrict_bidder_enable');
	$uwa_silent_restrict_checked_enable="";
		if($uwa_restrict_bidder_enable =="yes"){
			$uwa_silent_restrict_checked_enable = "checked";
		}	
		
		
	$uwa_disable_buy_it_now = get_option('uwa_disable_buy_it_now');
	$uwa_disable_buy_it_now_checked_enable="";
		if($uwa_disable_buy_it_now =="yes"){
			$uwa_disable_buy_it_now_checked_enable = "checked";
		}	
	$uwa_enable_bid_place_warning = get_option('uwa_enable_bid_place_warning');
	$uwa_enable_bid_place_warning_checked_enable="";
		if($uwa_enable_bid_place_warning =="yes"){
			$uwa_enable_bid_place_warning_checked_enable = "checked";
		}		
	
	$uwa_silent_outbid_email = get_option('uwa_silent_outbid_email',"no");
	$uwa_silent_outbid_email_checked_enable ="";
		if($uwa_silent_outbid_email =="yes"){
			$uwa_silent_outbid_email_checked_enable  = "checked";
		}	
	$uwa_silent_outbid_email_cprice = get_option('uwa_silent_outbid_email_cprice',"no");
	$uwa_silent_outbid_email_cprice_checked_enable ="";
		if($uwa_silent_outbid_email_cprice =="yes"){
			$uwa_silent_outbid_email_cprice_checked_enable  = "checked";
		}		
	 	
	/*
	* Getting All Extra Setting Setting Field from DB
	*/


	/* Automatic order */

	$uwa_auto_order_enable = get_option('uwa_auto_order_enable');
	$uwa_auto_order_checked_enable="";
		if($uwa_auto_order_enable =="yes"){
			$uwa_auto_order_checked_enable = "checked";
		}

	$address_yes_checked = "";
	$address_no_checked = "";
	$uwa_shipping_address = get_option("uwa_shipping_address");
	
	if($uwa_shipping_address == "yes"){
		$address_yes_checked = "checked";
	}
	else if($uwa_shipping_address == "no" || $uwa_shipping_address == "" ){ 
		$address_no_checked = "checked";
	}


	$uwa_auto_extend_when = get_option('uwa_auto_extend_when');
	$uwa_auto_extend_when_m = get_option('uwa_auto_extend_when_m');
	$uwa_auto_extend_when_s = get_option('uwa_auto_extend_when_s');
	$uwa_auto_extend_time = get_option('uwa_auto_extend_time');
	$uwa_auto_extend_time_m = get_option('uwa_auto_extend_time_m');
	$uwa_auto_extend_time_s = get_option('uwa_auto_extend_time_s');	
	$uwa_can_maximum_bid_amt = get_option('uwa_can_maximum_bid_amt');
	$uwa_relist_options = get_option('uwa_relist_options');
 
	$uwa_aviod_snipping_type_main = get_option('uwa_aviod_snipping_type_main');
	$sniping_type_extend_checked = "";
	$sniping_type_reset_checked = "";

	if($uwa_aviod_snipping_type_main == "sniping_type_extend_checked" || $uwa_aviod_snipping_type_main == ""){
		$sniping_type_extend_checked = "checked";
	}
	else if($uwa_aviod_snipping_type_main == "sniping_type_reset_checked"  ){
		$sniping_type_reset_checked = "checked";
	}
	
	
	$anti_sniping_timer_update_notification = get_option('anti_sniping_timer_update_notification');
	$anti_sniping_timer_update_notification_chk = "";
	$anti_sniping_timer_update_notification_chk2 = "";
	

	if($anti_sniping_timer_update_notification == "auto_page_refresh" || $anti_sniping_timer_update_notification == ""){
		$anti_sniping_timer_update_notification_chk = "checked";
	}
	else if($anti_sniping_timer_update_notification == "manual_page_refresh"  ){
		$anti_sniping_timer_update_notification_chk2 = "checked";
	}
	
	
	$timer_type = get_option('timer_type',"timer_jquery");
	$timer_type_chk = "";
	$timer_type_chk2 = "";
	

	if($timer_type == "timer_jquery" || $timer_type == ""){
		$timer_type_chk = "checked";
	}
	else if($timer_type == "timer_react"  ){
		$timer_type_chk2 = "checked";
	}
	
	
	
	

	$uwa_aviod_snipping_type = get_option('uwa_aviod_snipping_type');
	$recursive_checked = "";
	$only_once_checked = "";
	
	if($uwa_aviod_snipping_type == "snipping_recursive"){
		$recursive_checked = "checked";
	}
	else if($uwa_aviod_snipping_type == "snipping_only_once" || $uwa_aviod_snipping_type == "" ){ 
		$only_once_checked = "checked";
		/*if($uwa_aviod_snipping_type == false){  /* set option in db if not exist /*
			update_option('uwa_aviod_snipping_type', "snipping_only_once");
		}*/
	}
 
	$uwa_allow_owner_to_bid = get_option('uwa_allow_owner_to_bid',"no");
	$uwa_restrict_owner_checked_enable="";
		if($uwa_allow_owner_to_bid =="yes"){
			$uwa_restrict_owner_checked_enable = "checked";
		}
 
	$uwa_allow_admin_to_bid = get_option('uwa_allow_admin_to_bid',"no");
	$uwa_allow_admin_to_bid_checked="";
		if($uwa_allow_admin_to_bid =="yes"){
			$uwa_allow_admin_to_bid_checked = "checked";
		}

	$uwa_disable_buy_it_now__bid_check = get_option('uwa_disable_buy_it_now__bid_check');
	$uwa_disable_buy_it_now__bid_check_enable="";
		if($uwa_disable_buy_it_now__bid_check =="yes"){
			$uwa_disable_buy_it_now__bid_check_enable = "checked";
		}


	
	$block_yes_checked = "";
	$block_no_checked = "";
	$uwa_block_reg_user = get_option("uwa_block_reg_user");
	
	if($uwa_block_reg_user == "yes"){
		$block_yes_checked = "checked";
	}
	else if($uwa_block_reg_user == "no" || $uwa_block_reg_user == "" ){ 
		$block_no_checked = "checked";
	}
	
	$get_block_user_text = get_option('uwa_block_user_text');
	$anti_sniping_clock_msg = get_option('anti_sniping_clock_msg');
	/* var_dump($get_block_user_text); */
	if ($get_block_user_text === false){
		//$block_user_text = __("test test", "woo_ua");
		$block_user_text = __("You cannot place a bid on the product yet. Please contact the administrator of the website to get it unblocked.", "woo_ua");
	}
	elseif(empty($get_block_user_text)){
		$block_user_text = "";
	}
	else{
		//$block_user_text = __($get_block_user_text, "woo_ua");
		$block_user_text = $get_block_user_text;
	}

	$uwa_global_bid_inc = get_option("uwa_global_bid_inc");
	
 ?>
<div class="uwa_main_setting_content">
	<table class="form-table">
		<tbody>							
			<tr class="uwa_heading">
				<th colspan="2"><?php _e('Cron Job Setting','woo_ua'); ?></th>
			</tr>
			<tr>
				<th scope="row" colspan="2"><?php 
					echo '<p>' . sprintf( __( '<b>We recommend you to set up cron jobs for your auction products so that their status and associated emails are triggered properly. You can go through <a href="https://docs.auctionplugin.net/article/123-set-your-auction-cron-job" target="_blank">this article</a> to know how to set these cron jobs</b>', 'woo_ua' )) . '</p>'; 
					
				?></th>							 
				
			</tr> 
			<tr>
				<th scope="row"><?php _e('Auction Status:','woo_ua'); ?></th>
				<td class="uwaforminp">
				<?php 
					echo '<p>' . sprintf( __( '<b>%1$s/?ua-auction-cron=process-auction</b>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'uwa_process_auction_cron_ignore_notice', '0' ) ) ) . '</p>'; 
					echo '<p style="font-style: italic;">' . sprintf( __( 'This cron job will check auction for expiration and then send winning emails | We recommend to set it to every minute', 'woo_ua' ) ) . '</p>'; 
				?>					
				</td>
			</tr> 
			
			<tr>
				<th scope="row"><?php _e('Ending soon emails:','woo_ua'); ?></th>							 
				<td class="uwaforminp">
				<?php 
					echo '<p>' . sprintf( __( '<b>%1$s/?ua-auction-cron=ending-soon-email</b>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'uwa_process_auction_cron_ignore_notice', '0' ) ) ) . '</p>'; 
					echo '<p style="font-style: italic;">' . sprintf( __( 'This cron job will check which auction products are going to end soon (hours setting is inside email). We recommend to set it to one hour', 'woo_ua' ) ) . '</p>'; 
				?>
				</td>
			</tr> 
			
			<tr>
				<th scope="row"><?php _e('Payment Reminder:','woo_ua'); ?></th>							 
				<td class="uwaforminp">
				<?php 
					echo '<p>' . sprintf( __( '<b>%1$s/?ua-auction-cron=payment-reminder-email</b>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'uwa_process_auction_cron_ignore_notice', '0' ) ) ) . '</p>'; 
					echo '<p style="font-style: italic;">' . sprintf( __( 'This cron job will send payment reminder email to winner.  We recommend to set it to one hour.', 'woo_ua' ) ) . '</p>'; 
				?>
				</td>
			</tr> 
			
			<tr>
				<th scope="row"><?php _e('Automatic Relisting:','woo_ua'); ?></th>							 
				<td class="uwaforminp">
				<?php 
					echo '<p>' . sprintf( __( '<b>%1$s/?ua-auction-cron=auto-relist</b>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'uwa_process_auction_cron_ignore_notice', '0' ) ) ) . '</p>'; 
					echo '<p style="font-style: italic;">' . sprintf( __( 'This cron job will check which auction product is due for automatic relist. We recommend to set it to one hour.', 'woo_ua' ) ) . '</p>'; 
				?>
				</td>
			</tr> 

			<tr>
				<th scope="row"><?php _e('Ending soon SMS:','woo_ua'); ?></th>
				<td class="uwaforminp">
				<?php 
					echo '<p>' . sprintf( __( '<b>%1$s/?ua-auction-cron=ending-soon-sms</b>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'uwa_process_auction_cron_ignore_notice', '0' ) ) ) . '</p>'; 
					echo '<p style="font-style: italic;">' . sprintf( __( 'This cron job will check which auction products are going to end soon (hours setting is inside SMS). We recommend to set it to one hour', 'woo_ua' ) ) . '</p>'; 
				?>
				</td>
			</tr> 
			
		</tbody>						
	</table> 
</div>	
<div class="uwa_main_setting_content">		  
	<form  method='post' class='uwa_auction_setting_style'>

		<table class="form-table">
			<tbody>
			<tr class="uwa_heading">
			  		<th colspan="2"><?php _e( 'AJAX BIDDING', 'woo_ua' ); ?></th>
				</tr>	
				<tr>
					<th scope="row">
						<label for="uwa_placebid_ajax_enable"><?php _e( 'Instant Bidding', 'woo_ua' ); ?>
						</label>
					</th>
					<td class="uwaforminp">	<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?
							</strong><span>
							<?php _e("This option will simulate instant bidding for users. You can keep it enable.", 'woo_ua');
							?>	
						</span></a>									
						<input type="checkbox" <?php echo $placebid_checked_enable;?> 
							name="uwa_placebid_ajax_enable" class="regular-number" 
							id="uwa_placebid_ajax_enable" value="1"><?php _e( 'By enabling this setting, bids will be placed without page refresh.', 'woo_ua' ); ?>
						
														 
					</td>
				</tr>
				
				
				
				
				<tr>
					<th scope="row">
						<label for="uwa_bid_ajax_enable"><?php _e( '', 'woo_ua' ); ?>
						</label>
					</th>
					<td class="uwaforminp">	<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?
							</strong><span>
							<?php _e("By enabling this setting, bid information will be polled every X second mentioned in below setting and bid information will be displayed without page refresh. This can be performance heavy operation on your server.", 'woo_ua');
							?>	
						</span></a>									
						<input type="checkbox" <?php echo $checked_enable;?> name="uwa_bid_ajax_enable" class="regular-number" id="uwa_bid_ajax_enable" value="1"><?php _e( 'Get Bid amount information instantly without page refresh.', 'woo_ua' ); ?>
						
														 
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="uwa_bid_ajax_interval"><?php _e( 'Check Bidding Information', 'woo_ua' ); ?></label>
					</th>

					<td class="uwaforminp"> <a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?
							</strong><span>
							<?php _e('Time interval between two ajax requests in seconds (bigger intervals means less load on server)', 'woo_ua');
							?>									
						</span></a>
						<?php _e( 'In every', 'woo_ua' ); ?>
						<input type="number" name="uwa_bid_ajax_interval" class="regular-number"  min="1" 
							id="uwa_bid_ajax_interval" value="<?php echo $uwa_bid_ajax_interval; ?>"><?php _e( 'Second.', 'woo_ua' ); ?>
														
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Sync Timer on Auction List Page', 'woo_ua' ); ?></th>
					<td class="uwaforminp">	
							<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?
							</strong><span>
							<?php _e('When this checkbox is enabled then once the page loads, after five seconds an AJAX request will be sent to server to get server time and calculate the time left for expiration and update the timer of the product. We have given this provision if site takes too long to load and due to this the timer might have a lag and to overcome this lag, we have provided this setting. Please note that enabling this setting will increase number of AJAX request calls to the server which might lead to stress on the server.', 'woo_ua');
							?>									
						</span></a>					
						<input type="checkbox" <?php echo $uwa_listpage_sync_clock_enable_chk;?> name="uwa_listpage_sync_clock_enable"  id="uwa_listpage_sync_clock_enable" value="1">
						<span class="description"></span>
						
					</td>
				</tr>
				
				
				<tr class="uwa_heading">
					<th colspan="2"><?php _e( 'Simple Auction Settings', 'woo_ua' ); ?></th>
				</tr>
				
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp"><a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span>
							<?php _e('if you enable Mask Username for simple bidding username will add ****** and not display full Username public ', 'woo_ua');  ?>
					   	</span></a>									
						<input type="checkbox" <?php echo $uwa_simple_maskusername_checked_enable;?> name="uwa_simple_maskusername_enable"  id="uwa_simple_maskusername_enable" value="1">
						<span class="description"><?php _e('Mask Username', 'woo_ua');  ?>.</span>
						
					</td>
				</tr>

				
				<tr class="uwa_heading">
					<th colspan="2"><?php _e( 'Proxy Auction Settings', 'woo_ua' ); ?></th>
				</tr>
				
				<tr>
					<th scope="row">
						<label for="uwa_proxy_bid_enable">
						<?php _e( 'Enable Proxy Bidding:', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span>
							<strong><?php _e('Proxy Bidding', 'woo_ua');  ?></strong>:<br>							
							<?php _e("Proxy Bidding (also known as Automatic Bidding) - Our automatic bidding system makes bidding convenient so you don't have to keep coming back to re-bid every time someone places another bid. When you place a bid, you enter the maximum amount you're willing to pay for the item. The seller and other bidders don't know your maximum bid. We'll place bids on your behalf using the automatic bid increment amount, which is based on the current high bid. We'll bid only as much as necessary to make sure that you remain the high bidder, or to meet the reserve price, up to your maximum amount.", 'woo_ua' ); ?>
							</span></a>					
						<input type="checkbox" <?php echo $proxy_bid_checked_enable;?> name="uwa_proxy_bid_enable"  id="uwa_proxy_bid_enable" value="1">
						<span class="description"><?php _e('Enable Proxy Bidding', 'woo_ua');  ?>.</span>	
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td class="uwaforminp"><a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span>
							<?php _e('if you enable Mask Username for proxy bidding username will add ****** and not display full Username public ', 'woo_ua');  ?>
					   	</span></a>									
						<input type="checkbox" <?php echo $uwa_proxy_maskusername_checked_enable;?> name="uwa_proxy_maskusername_enable"  id="uwa_proxy_maskusername_enable" value="1">
						<span class="description"><?php _e('Mask Username', 'woo_ua');  ?>.</span>
						
					</td>
				</tr>
				
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp"><a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span>
							<?php _e('if you enable Mask Bid for proxy bidding Bid value will add ****** and not display full Bid Value public ', 'woo_ua');  ?>
					   </span></a>									
						<input type="checkbox" <?php echo $uwa_proxy_maskbid_checked_enable;?> name="uwa_proxy_maskbid_enable"  id="uwa_proxy_maskbid_enable" value="1">
						<span class="description"><?php _e('Mask Bid Amount', 'woo_ua');  ?></span>
						
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td class="uwaforminp"><a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('If User 1 has set a specific maximum bid and if User 2 comes and places same maximum bid, then plugin will show alert message to user 2 and will place bid on behalf of User 1 of same amount as his maximum bid.', 'woo_ua');  ?>
					    </span></a>
						<input type="checkbox" <?php echo $uwa_proxy_same_checked_maxbid;?> name="uwa_proxy_same_maxbid"  
							id="uwa_proxy_same_maxbid" value="1">
						<span class="description"><?php _e("Place bid on behalf of first user if maximum bid of second user matches with first user.", 'woo_ua');  ?></span>						
					</td>
				</tr>

					
				<tr class="uwa_heading">
					<th colspan="2"><?php _e( 'Silent Auction Settings', 'woo_ua' ); ?></th>
				</tr>
				
				<tr>
					<th scope="row">
						<label for="uwa_silent_bid_enable">
							<?php _e( 'Enable Silent Bidding', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">	
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span>
							<strong><?php _e('Silent bidding', 'woo_ua');  ?>:</strong><br>							
							<?php _e('A Silent-bid auction is a type of auction process in which all bidders simultaneously submit Silent bids to the auctioneer, so that no bidder knows how much the other auction participants have bid. The highest bidder is usually declared the winner of the bidding process.', 'woo_ua');  ?>
							</span></a>
						<input type="checkbox" <?php echo $silent_bid_checked_enable;?> name="uwa_silent_bid_enable" id="uwa_silent_bid_enable" value="1">					
						<span class="description"><?php _e('Enable Silent Bidding', 'woo_ua');  ?>
						</span>	
							
							 
					</td>
				</tr>	
				
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp">
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('if you enable Mask Username for silent bidding username will add ****** and not display full Username public ', 'woo_ua');  ?>
					    </span></a>
						<input type="checkbox" <?php echo $uwa_silent_maskusername_checked_enable;?> name="uwa_silent_maskusername_enable"  id="uwa_silent_maskusername_enable" value="1">
						<span class="description"><?php _e('Mask Username', 'woo_ua');  ?></span>
									
					</td>
				</tr>
				
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp">
					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('if you enable Mask Bid for silent bidding Bid value will add ****** and not display full Bid Value public ', 'woo_ua');  ?>
					    </span></a>						
						<input type="checkbox" <?php echo $uwa_silent_maskbid_checked_enable;?> name="uwa_silent_maskbid_enable"  id="uwa_silent_maskbid_enable" value="1">
						<span class="description"><?php _e('Mask Bid Amount', 'woo_ua');  ?></span>
								
					</td>
				</tr>
				
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp">
					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('In reality, Silent auctions accept single bid from each user. You can check this field to allow single bid for each user.', 'woo_ua');  ?>
					    </span></a>						
						<input type="checkbox" <?php echo $uwa_silent_restrict_checked_enable;?> name="uwa_restrict_bidder_enable"  id="uwa_restrict_bidder_enable" value="1">
						<span class="description"><?php _e('Restrict users to bid only one time.', 'woo_ua');  ?></span>
								
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td class="uwaforminp">										
						<input type="checkbox" <?php echo $uwa_silent_outbid_email_checked_enable;?> name="uwa_silent_outbid_email"  id="uwa_silent_outbid_email" value="1">
						<span class="description"><?php _e('Do you want to send outbid notification.', 'woo_ua');  ?></span>
								
					</td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td class="uwaforminp">										
						<input type="checkbox" <?php echo $uwa_silent_outbid_email_cprice_checked_enable;?> name="uwa_silent_outbid_email_cprice"  id="uwa_silent_outbid_email_cprice" value="1">
						<span class="description"><?php _e('Show Current Bid Value In outbid mail.', 'woo_ua');  ?></span>
					</td>
				</tr>
				
			 
				
				<tr class="uwa_heading">
					<th colspan="2"><?php _e( 'Timer and Soft Close / Avoid Sniping', 'woo_ua' ); ?></th>
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping">
				   		<?php _e( 'Choose from where Countdown Timer would get time:', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">	

<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span><?php _e("Timer is essential for auctions and they should run in a way so that each user sees approximate same time left on their browser. Though this depends on various factors other than our software but we have provided 2 ways for timer to get time. First way is recommended when bidders are in one timezone or country. We recommend using this setting as the calculation of time logic is fast. The second way should be chosen when your bidders are at different locations around the globe.", 'woo_ua' ); ?> </span></a>					

						<input type="radio" name="timer_type" value="timer_jquery"  <?php echo $timer_type_chk; ?>>
							<span class="description"><?php _e('Local - Ideal when Bidders are in single timezone (Recommended)', 'woo_ua');  ?></span>
							<span style="margin-right:20px;"></span>
						<input type="radio" name="timer_type"  value="timer_react" <?php echo $timer_type_chk2; ?>>
							<span class="description"><?php _e('Global - Ideal when Bidders are all over the World.', 'woo_ua');  ?></span>					

						
					</td> 
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping">
				   		<?php _e( 'Anti-sniping: How should users see an updated time left (Timer value)?', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">	

<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
							<span><?php _e("When a user places a bid which invokes anti-sniping (soft-close) then the time left will change and thus the timer shown on the page has to be updated to show correct present value. Since other users who were already seeing the product detail page, it is imperative for them to see the updated time left and the best way to update timer value on their product detail page would be to
Automatic Page Refresh
Manual Page Refresh
As admin you can choose among these two values.", 'woo_ua' ); ?> </span></a>					

<input type="radio" name="anti_sniping_timer_update_notification" value="auto_page_refresh"  <?php echo $anti_sniping_timer_update_notification_chk; ?>>
							<span class="description"><?php _e('Auto Page Refresh', 'woo_ua');  ?></span>
							<span style="margin-right:20px;"></span>
						<input type="radio" name="anti_sniping_timer_update_notification"  value="manual_page_refresh" <?php echo $anti_sniping_timer_update_notification_chk2; ?>>
							<span class="description"><?php _e('Manual Page Refresh', 'woo_ua');  ?></span>					

						
					</td> 
				</tr> 
				
				
				
				<?php
					$setmsg=$anti_sniping_clock_msg;
					if($anti_sniping_clock_msg==""){
						$setmsg="Time left has changed due to soft-close";	
					}
				?>
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping">
				   		<?php _e( 'What message to show when timer has changed?', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">
					<textarea  name="anti_sniping_clock_msg" id="anti_sniping_clock_msg" rows="2" cols="50"><?php echo strip_tags(trim($setmsg)); ?></textarea>
					</td> 
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping">
				   		<?php _e( 'Anti-sniping: What do you want to do?', 'woo_ua' ); ?></label>
						</th>
					<td class="uwaforminp">	

<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("We provide two options. First option extending auction if bid placed meet below timing. Second option will reset auction and will send email to
							all bidders intimating latest bid", 'woo_ua' ); ?> </span></a>

						<input type="radio" name="uwa_aviod_snipping_type_main" id="uwa_aviod_snipping_type_extend" value="sniping_type_extend_checked"  <?php echo $sniping_type_extend_checked; ?>>
							<span class="description"><?php _e('Extend Auction', 'woo_ua');  ?></span>
							<span style="margin-right:20px;"></span>
						<input type="radio" name="uwa_aviod_snipping_type_main" id="uwa_aviod_snipping_type_reset" value="sniping_type_reset_checked" <?php echo $sniping_type_reset_checked; ?>>
							<span class="description"><?php _e('Reset Auction', 'woo_ua');  ?></span>					

						 
					</td> 
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping"><?php _e( 'Anti-sniping: Extend Auction options', 'woo_ua' ); ?></label>
						</th>
					<td class="uwaforminp">	


						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("We provide two options. First option will keep extending auction if bid placed meet below timing. Second option will extend auction only once and will send email to 
							all bidders intimating latest bid", 'woo_ua' ); ?> </span></a>						
						
						<input type="radio" name="uwa_aviod_snipping_type" id="uwa_aviod_snipping_type_recursive" value="snipping_recursive"  <?php echo $recursive_checked; ?>> 
							<span class="description"><?php _e('Extend Auction in recursive manner', 'woo_ua');  ?></span>
							<span style="margin-right:20px;"></span> 
						<input type="radio" name="uwa_aviod_snipping_type" id="uwa_aviod_snipping_type_once" value="snipping_only_once" <?php echo $only_once_checked; ?>> 
							<span class="description"><?php _e('Extend Auction only once', 'woo_ua');  ?></span>				

						 
					</td> 
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping"><?php _e( 'Anti-sniping: At what time should it kick-in?', 'woo_ua' ); ?></label>
						</th>
					<td class="uwaforminp">	


						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("Mention time left for auction to close:", 'woo_ua' ); ?> </span></a>
						<input type="number" placeholder="<?php _e( 'Hours', 'woo_ua' ); ?>" name="uwa_auto_extend_when" class="small-text regular-number" id="uwa_auto_extend_when" value="<?php echo $uwa_auto_extend_when; ?>"  min="0">
						<input type="number" placeholder="<?php _e( 'Minutes', 'woo_ua' ); ?>" name="uwa_auto_extend_when_m" class="small-text regular-number" id="uwa_auto_extend_when_m" value="<?php echo $uwa_auto_extend_when_m; ?>"  min="0">
						<input type="number" placeholder="<?php _e( 'Seconds', 'woo_ua' ); ?>" name="uwa_auto_extend_when_s" class="small-text regular-number" id="uwa_auto_extend_when_s" value="<?php echo $uwa_auto_extend_when_s; ?>"  min="0" max="59">
						
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("Note: You should use hours and minutes field and we do not recommend to use seconds field for the simple reason that loading of page after refresh depends on hosting server capacity and the page content and that varies from customer to customer and thus the timer wont be real time as you would expect.", 'woo_ua' ); ?> </span></a>
											
 
					</td> 
				</tr>
				
				<tr>
					<th scope="row">
				   		<label for="uwa_avoid_snipping"><?php _e( 'Anti-sniping: What time should it extend or reset to?', 'woo_ua' ); ?></label>
						</th>
					<td class="uwaforminp">	


					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("Extend auction by following time:", 'woo_ua' ); ?> </span></a>
						
						<input type="number" placeholder="<?php _e( 'Hours', 'woo_ua' ); ?>" name="uwa_auto_extend_time" class="small-text regular-number" id="uwa_auto_extend_time" value="<?php echo $uwa_auto_extend_time; ?>"  min="0">
						<input type="number" placeholder="<?php _e( 'Minutes', 'woo_ua' ); ?>" name="uwa_auto_extend_time_m" class="small-text regular-number" id="uwa_auto_extend_time_m" value="<?php echo $uwa_auto_extend_time_m; ?>"  min="0">
						<input type="number" placeholder="<?php _e( 'Seconds', 'woo_ua' ); ?>" name="uwa_auto_extend_time_s" class="small-text regular-number" id="uwa_auto_extend_time_s" value="<?php echo $uwa_auto_extend_time_s; ?>"  min="0"  max="59">
											
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("Note: You should use hours and minutes field and we do not recommend to use seconds field for the simple reason that loading of page after refresh depends on hosting server capacity and the page content and that varies from customer to customer and thus the timer wont be real time as you would expect.", 'woo_ua' ); ?> </span></a>
						 
						 
					</td> 
				</tr>

				<tr class="uwa_heading">
					<th colspan="2"><?php _e( 'Extra Settings', 'woo_ua' ); ?></th>
				</tr>
				
				<tr>
					<th scope="row"><label for="uwa_avoid_snipping">
						<?php _e('Auto create order after Auction expire', 'woo_ua'); ?></label></th>
					<td class="uwaforminp">
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e('When an auction product will expire then WC order will be created with "pending payment" status and will have payment details (except shipping charge).', 'woo_ua');  ?>
					   	</span></a>


						<label class="switch">
						  <!-- <input type="checkbox"> --> 
						  <input class="coupon_question" type="checkbox" <?php echo $uwa_auto_order_checked_enable;?> name="uwa_auto_order_enable"  id="uwa_auto_order_enable" value="1">
						  <span class="slider"></span>
						</label>

						
						<span class="description"><?php _e('Do you want to automatically generate an order for an auction product?', 'woo_ua'); ?></span>
					</td>
				</tr>

				<tr class="answer">
					<th scope="row"></th>
					<td class="uwaforminp">
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("User will have to update their shipping address inside their My Account > Addresses. This will then let admin add shipping cost to the order and taking shipping fee also.", 'woo_ua' ); ?>
						</span></a>

						<span><?php _e("Do you want users to fill their shipping address before they place their bids?", 'woo_ua'); ?></span>
						<p style="padding-left: 20px;"><?php _e("User will have to update their shipping address inside their My Account > Addresses. This will then let admin add shipping cost to the order and taking shipping fee also.", 'woo_ua' ); ?></p>
						<br/>
						<a  style="margin-top: 22px;" href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("Note: User will have to update their shipping address inside their My Account > Addresses. This will then let admin add shipping cost to the order and taking shipping fee also.", 'woo_ua' ); ?> </span></a>
					
						<input type="radio" name="uwa_shipping_address" id="uwa_shipping_address_yes" 
						value="yes"  <?php echo $address_yes_checked; ?>> 
						<span class="description"><?php _e('Yes', 'woo_ua');  ?></span>
						<span style="margin-right:20px;"></span> 

						
						<span class="uwaforminp"><a href="" class="uwa_fields_tooltip" onclick="return false">
							<strong>?</strong>
							<span><?php _e("Note: Since user address is not available then there will be difficulty for admin to add shipping cost for the product inside its associated order.", 'woo_ua' ); ?> </span></a>
								<input type="radio" name="uwa_shipping_address" id="uwa_shipping_address_no" value="no" <?php echo $address_no_checked; ?>> 
								<span class="description"><?php _e('No', 'woo_ua');  ?></span>
						</span>
					
					</td>
				</tr>

				
				 
			 
				

				<tr>
					<th>
						<label for="uwa_can_maximum_bid_amt"><?php _e( 'Bidding Restriction:', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">	<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e('You can set maximum bidding amount here.', 'woo_ua');  ?></span></a>					
						<input type="number" name="uwa_can_maximum_bid_amt" style="width: 157px;" class="regular-number" min="1" id="uwa_can_maximum_bid_amt" value="<?php echo $uwa_can_maximum_bid_amt; ?>">						
						<?php _e('Default is', 'woo_ua');  ?>  <?php echo wc_price(999999999999.99);  ?>									 
					</td>
				</tr>
				
				<tr>
					<th></th>
					<td class="uwaforminp">						
						<input type="checkbox" <?php echo $uwa_allow_admin_to_bid_checked;?> name="uwa_allow_admin_to_bid"  id="uwa_allow_admin_to_bid" value="1">
						<span class="description"><?php _e('Allow Administrator to bid on their own auction.', 'woo_ua');  ?></span>
					</td>
				</tr>
				
				<tr>
					<th></th>
					<td class="uwaforminp">						
						<input type="checkbox" <?php echo $uwa_restrict_owner_checked_enable;?> name="uwa_allow_owner_to_bid"  id="uwa_allow_owner_to_bid" value="1">
						<span class="description"><?php _e('Allow Auction Owner (Seller/Vendor) to bid on their own auction.', 'woo_ua');  ?></span>								 
					</td>
				</tr>
								<tr>
					<th scope="row">
				   		<label for="uwa_block_reg_user">
				   		<?php _e("Do you want to block registered user from bidding", "woo_ua"); ?></label>
					</th>
					<td>
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span><?php _e("If yes, it will block all existing and new users to place bid, defaults is no", 
							"woo_ua"); ?> </span></a>
						
						<input type="radio" name="uwa_block_reg_user" id="uwa_block_reg_user_yes" value="yes"  
							<?php echo $block_yes_checked; ?>> 
							<span class="description"><?php _e("Yes", "woo_ua");  ?></span>
							<span style="margin-right:20px;"></span> 
						<input type="radio" name="uwa_block_reg_user" id="uwa_block_reg_user_no" value="no" 
							<?php echo $block_no_checked; ?>> 
							<span class="description"><?php _e("No", "woo_ua");  ?></span>
						<br /><br />
					</td> 
				</tr> 
				
				<tr>
					<th scope="row">
						<label for="uwa_block_user_text">
							<?php _e("What notification message do you want to show to blocked users", "woo_ua"); ?>
						</label>
					</th>
					<td>
						<textarea  name="uwa_block_user_text" id="uwa_block_user_text" rows="4" cols="50"><?php echo strip_tags(trim($block_user_text)); ?></textarea>
					</td>				
				</tr>

				<tr>
					<th>
						<label for="uwa_global_bid_inc"><?php _e( 'Set Bid Increment Globally:', 'woo_ua' ); ?></label>
					</th>
					<td class="uwa_global_bid_inc">	<a href="" class="uwa_fields_tooltip" onclick="return false">
						<strong>?</strong>
						<span><?php _e('You can set bid increment for every auction', 'woo_ua');  ?></span></a>					
						<input type="number" name="uwa_global_bid_inc" style="width: 157px;" class="regular-number" min="1" id="uwa_global_bid_inc" value="<?php echo $uwa_global_bid_inc; ?>">															 
					</td>
				</tr>
				
				
				
				
				
				<tr>
					<th>
						<label for="uwa_can_maximum_bid_amt"><?php _e( 'Relist Options', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">	
						<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>							
							<span>
							<strong><?php _e('Start auction from beginning', 'woo_ua');  ?> :</strong><br>
							<?php _e('When you select this option then all bids are deleted and auction starts from beginning.', 'woo_ua');  ?><br>	<br>
							<strong><?php _e('Start auction from where it ended.', 'woo_ua');  ?></strong>
							<br>						
							<?php _e('When you select this option then auction starts from where it had ended.', 'woo_ua');  ?><br>
							</span>
						</a>					
						<select class="uwa_relist_options"  name="uwa_relist_options">
						<option value="uwa_relist_start_from_beg" <?php selected( $uwa_relist_options, 'uwa_relist_start_from_beg' ); ?>><?php _e('Start auction from beginning', 'woo_ua');  ?></option>
						<option value="uwa_relist_start_from_end" <?php selected( $uwa_relist_options, 'uwa_relist_start_from_end' ); ?>><?php _e('Start auction from where it ended.', 'woo_ua');  ?>
						</option>
						</select>
					</td>
				</tr>
			
			<tr>
					<th>
						<label for="uwa_disable_buy_it_now"><?php _e( 'Disable the Buy It Now', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">
					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('Disable the Buy It Now option once bidding has reached the reserve price.', 'woo_ua');  ?>
					    </span></a>						
						<input type="checkbox" <?php echo $uwa_disable_buy_it_now_checked_enable;?> name="uwa_disable_buy_it_now"  id="uwa_disable_buy_it_now" value="1">
						<span class="description"><?php _e('Disable the Buy It Now option once bidding has reached the reserve price.', 'woo_ua');  ?></span>
								
					</td>
				</tr>

				<tr>
					<th>
					</th>
					<td class="uwaforminp">
					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('Disable the Buy It Now option once bidding has reached the Buy Now price.', 'woo_ua');  ?>
					    </span></a>						
						<input type="checkbox" <?php echo $uwa_disable_buy_it_now__bid_check_enable;?> name="uwa_disable_buy_it_now__bid_check"  id="uwa_disable_buy_it_now__bid_check" value="1">
						<span class="description"><?php _e('Disable the Buy It Now option once bidding has reached the Buy Now price.', 'woo_ua');  ?></span>
								
					</td>
				</tr>
			
			<tr>
					<th>
						<label for="uwa_enable_bid_place_warning"><?php _e( 'Enable an alert box.', 'woo_ua' ); ?></label>
					</th>
					<td class="uwaforminp">
					<a href="" class="uwa_fields_tooltip" onclick="return false"><strong>?</strong>
						<span>
							<?php _e('This setting lets you enable an alert confirmation which is shown to user when they place a bid.', 'woo_ua');  ?>
					    </span></a>						
						<input type="checkbox" <?php echo $uwa_enable_bid_place_warning_checked_enable;?> name="uwa_enable_bid_place_warning"  id="uwa_enable_bid_place_warning" value="1">
						<span class="description"><?php _e('Enable an alert box for confirmation when user places a bid.', 'woo_ua');  ?></span>
								
					</td>
			</tr>
				
				<tr class="submit">
                	<th colspan="2">
						<input type="submit" id="uwa-settings-submit" name="uwa-settings-submit" class="button-primary" value="<?php _e('Save Changes','woo_ua');?>" />
			    	</th>
             	</tr>
             	
			</tbody>						
		</table>
		
	</form>
</div>	   


<style>		 

.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 21px;
}

.switch input {
  display: none;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #dedede;
  border-radius: 40px;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  background: #fff;
  border-radius: 50%;
  left: 4px;
  bottom: 3px;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background: #3582c4;
}

input:checked + .slider:before {
  -webkit-transform: translateX(30px);
  -moz-transform: translateX(30px);
  transform: translateX(17px);
}

input:focus + .slider {
}

</style>


<script>

(function($) {
  $(document).ready(function() {
    var slider = $("#range"),
      output = $("#output");

    output.text(slider.val());
    slider.on("input", function() {
      output.text(slider.val());
    });
	
	if ($(".coupon_question").is(':checked')){
		$(".answer").show();
	}else{
		$(".answer").hide();
	}
	$(".coupon_question").click(function() {
		if($(this).is(":checked")) {
			$(".answer").show();
		} else {
			$(".answer").hide();
		}
	});
	
  });
})(jQuery);

</script>