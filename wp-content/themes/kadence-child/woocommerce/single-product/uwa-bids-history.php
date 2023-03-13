<?php

/**
 * Auction history tab
 * 
 * @package Ultimate WooCommerce Auction PRO
 * @author Nitesh Singh 
 * @since 1.0  
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

global $woocommerce, $post, $product;
$datetimeformat = get_option('date_format').' '.get_option('time_format');
$heading = apply_filters('ultimate_woocommerce_auction_total_bids_heading', __( 'Bid Logs', 'woo_ua' ) );
$current_bidder = $product->get_uwa_auction_current_bider();
?>

<h4 class="bid-log-heading"> <?php echo $heading; ?></h4>
<div class="uwa_bids_history_data" data-auction-id="<?php echo $product->get_id(); ?>">  <!-- main container -->	

	<div id="auction-history-table-<?php echo $product->get_id(); ?>" class="auction-history-table">
		<?php 
			
		$uwa_auction_log_history = $product->uwa_auction_log_history();
		
		if ( !empty($uwa_auction_log_history) ): ?>

			<div class="d-flex flex-direction-column gap-10 mt-1 mb-2">
				<?php 
				$aelia_addon = "";
				$addons = uwa_enabled_addons();
				if(is_array($addons) && in_array('uwa_currency_switcher', $addons)){
					if($product->uwa_aelia_is_configure() == TRUE){
						$aelia_addon = true;
					}
				}
				
				foreach ($uwa_auction_log_history as $history_value) { 

					if($aelia_addon == true){
						$history_value->bid = $product->uwa_aelia_base_to_active($history_value->bid);
					}

					?>
					<?php 
					$user_name = uwa_user_display_name($history_value->userid);
					if ($product->get_uwa_auction_proxy()=="yes"){ 
						$user_name = uwa_proxy_mask_user_display_name($history_value->userid);
					}elseif($product->get_uwa_auction_silent()=="yes"){
						$user_name = uwa_silent_mask_user_display_name($history_value->userid);
					}
					$bid_price = 0;
					if ($product->get_uwa_auction_proxy()=="yes"){ 
							$bid_price = uwa_proxy_mask_bid_amt($history_value->bid);			
					}elseif($product->get_uwa_auction_silent()=="yes"){
						$bid_price = uwa_silent_mask_bid_amt($history_value->bid);
					} else { 
						$bid_price = wc_price($history_value->bid);
					} 		
					?>
					<div class="bid_username bid_detail d-flex gap-10 flex-wrap">
						<i class="fa fa-dollar"></i> 
						<div style="flex: 1;">
							<div><strong><?php echo $bid_price;?></strong> bid places by <strong><?php echo $user_name;?></strong></div>
							<span class="bid-date"><?php echo mysql2date($datetimeformat ,$history_value->date)?></span>
						</div>
					</div>
					
				<?php } ?> 
			</div>

		<?php else: ?> 
		<div class="no-bids">
			<img src="<?php echo get_stylesheet_directory_uri()?>/images/no-active-bids.png" alt="no-active-bids">
		</div>	
		<?php endif;?>
			
		<div class="start text-center ">
			<?php 
			$start_date = $product->get_uwa_auction_start_time(); ?>									
			<div  class="bid-date"><?php echo ($product->is_uwa_live() === TRUE ? 'Auction started at' : 'Auction starting on') ." ". mysql2date($datetimeformat,$start_date) ?></div>
		</div>
	</div>

</div>