<?php


	add_shortcode( 'countdown', 'countdown_shortcode' );	
	
	/**
	* countdown shortcode  
	* [countdown id="%product_id%"]	 
	*
	* @param array $atts	 
	*
	*/
	function countdown_shortcode($atts, $content = null) { 
        
        ob_start();  
        $output  = get_by_the_countdown($atts, $content);
        $output  = ob_get_clean();
        
        return $output;  
    }

	function get_by_the_countdown($atts, $content) {
		$product_id =  $atts['id']; 
		$product = wc_get_product( $product_id );
		$pro_type = $product->get_type();
		if($pro_type == 'auction'){
		$uwa_expired = $product->is_uwa_expired();
		$uwa_started = $product->is_uwa_live();
		$woo_ua_auction_start_date = get_post_meta( $product_id, 'woo_ua_auction_start_date', true );
		$woo_ua_auction_end_date = get_post_meta( $product_id, 'woo_ua_auction_end_date', true );
		$second_count = strtotime($woo_ua_auction_end_date)  -  (get_option( 'gmt_offset' )*3600);
		$uwa_countdown_format = get_option( 'woo_ua_auctions_countdown_format' );
		
		$second_count  =  wp_date('Y-m-d H:i:s',$second_count,get_uwa_wp_timezone());
			$uwa_time_zone =  (array)wp_timezone();
			 
		
		if($uwa_started  === TRUE ) { 
			$auc_end_date=get_post_meta( $product_id, 'woo_ua_auction_end_date', true );
			$rem_arr=get_remaining_time_by_timezone($auc_end_date); 
		?>
		<div class="uwa_auction_time" id="uwa_auction_countdown" style="width:280px;">
			
			<?php
			countdown_clock(
				$end_date=$auc_end_date,
				$item_id=$product_id,
				$item_class='uwa-main-auction-product uwa_auction_product_countdown uwa_auction_product_countdown_2' 
			);
			
			?>
		</div>
		<?php } ?>
	
		<?php if ($product->get_uwa_auction_fail_reason() == '1'){ ?>
		
		<p class="expired" style="width:280px;"><?php  _e('Auction Expired because there were no bids', 'woo_ua');?>  </p>
			 
		 <?php } elseif($product->get_uwa_auction_fail_reason() == '2'){ ?>
			
		<p class="reserve_not_met" style="width:280px;"> <?php	_e('Auction expired without reaching reserve price', 'woo_ua'); ?> </p>
			
		<?php } ?>
			
	<?php } 
	}