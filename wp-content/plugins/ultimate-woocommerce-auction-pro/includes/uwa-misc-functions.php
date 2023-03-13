<?php

/**
 * Extra Functions file
 *
 * @package Ultimate WooCommerce Auction PRO
 * @author Nitesh Singh 
 * @since 1.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get Auction List By User Id
 *
 */
 
function get_uwa_wp_timezone() {	
	$uwa_time_zone = wp_timezone();
	return $uwa_time_zone;
	
} 

function get_uwa_now_date() {	
	$uwa_now_date = wp_date('Y-m-d H:i:s',time(),get_uwa_wp_timezone());
	return $uwa_now_date;
	
}  
  
function get_uwa_auction_by_user( $user_id  ) {
	
	global $wpdb;
	$table = $wpdb->prefix."woo_ua_auction_log";	 
	$query   = $wpdb->prepare("SELECT auction_id,MAX(bid) FROM $table  WHERE userid = %d GROUP by auction_id ORDER by date DESC", $user_id);
	$results = $wpdb->get_results( $query ); 
	foreach ($results as &$var) {			
	    $query   = $wpdb->prepare( "SELECT bid FROM $table WHERE auction_id = %d AND userid = %d ORDER by CAST(bid AS decimal(50,5)) DESC, date ASC LIMIT 1", $var->auction_id, $user_id );
	    $result = $wpdb->get_var( $query );
	    $var->max_bid = $result;
	}

	return $results;
}
 

/**
 * Get Auction WatchList By User Id
 *
 */
function get_uwa_auction_watchlist_by_user( $user_id  ) {
	
	global $wpdb, $woocommerce;
	global $product;
	global $sitepress;

	$results = get_user_meta( $user_id, "woo_ua_auction_watch"); 

	$new_watchlist = array();
	if(count($results) > 0){
		foreach($results as $key => $value) {
			$product = wc_get_product( $value );
			if (is_object($product) && method_exists( $product, 'get_type') && $product->get_type() == 'auction'){
				$product_status = $product->status;
				if ($product_status == 'publish') {
					$new_watchlist[] = $value;
				}
			}
			
		}

	}

	return $new_watchlist;
} 

/**
 * Get Url For checkout
 *
 */
function uwa_auction_get_checkout_url() {
	
	$checkout_page_id = wc_get_page_id('checkout');	
	$checkout_url     = '';
	
	if ( $checkout_page_id ) {

		if ( is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes' )			
			$checkout_url = str_replace( 'http:', 'https:', get_permalink( $checkout_page_id ) );
			
		else
			$checkout_url = get_permalink( $checkout_page_id );
	}
	return apply_filters( 'woocommerce_get_checkout_url', $checkout_url );
}

/**
 * Bid Placed Message
 *
 */
function uwa_bid_place_message( $product_id ) {
	
	global $woocommerce; 
	$product_data = wc_get_product($product_id);
	
		/* --aelia-- */
	$product_base_currency = $product_data->uwa_aelia_get_base_currency();
  	$args = array("currency" => $product_base_currency);

	$current_user = wp_get_current_user();
	$is_slient_auction = $product_data->get_uwa_auction_silent();
	
	if($is_slient_auction == "yes"){	
	
		$display_bid_value = wc_price($product_data->get_uwa_last_bid(), $args);
	}
	else{
	
		$display_bid_value = wc_price($product_data->get_uwa_current_bid(), $args);
	}
	

	/* if($current_user->ID == $product_data->get_uwa_auction_current_bider()){
		
		if(!$product_data->is_uwa_reserve_met()){			
			$message = sprintf(__('Your bid of %s has been placed successfully.', 'woo_ua'),wc_price($product_data ->get_uwa_current_bid())); 
		} 
		else {
			if($product_data->get_uwa_auction_max_bid()){
				$message = sprintf( 
					__('Your bid of %s has been placed successfully! Your max bid is %s.', 'woo_ua'),
					wc_price($product_data->get_uwa_current_bid()), 
					wc_price($product_data->get_uwa_auction_max_bid()));
				
			}
			else{				
				$message = sprintf(__('Your bid of %s has been placed successfully.', 'woo_ua'), wc_price($product_data->get_uwa_current_bid()));
			}
		}	
		
	} 
	else {
		
		if($product_data->get_uwa_auction_proxy() =="yes"){
			$message = sprintf(__( "Your bid has been placed successfully.", 'woo_ua'));
		}
		else {
			
			$message = sprintf( 
			__( "Your bid of %s has been placed successfully.", 'woo_ua'),
			wc_price($product_data ->get_uwa_current_bid()) );	
		}
			
	} */

	if($current_user->ID == $product_data->get_uwa_auction_current_bider()){
		
		if(!$product_data->is_uwa_reserve_met()){			
			$message = sprintf(
				__('Your bid of %s has been placed successfully.', 'woo_ua'),
				$display_bid_value);
		} 
		else {
			if($product_data->get_uwa_auction_max_bid()){
				$message = sprintf( 
					__('Your bid of %s has been placed successfully! Your max bid is %s.', 
						'woo_ua'),
					$display_bid_value, 
					wc_price($product_data->get_uwa_auction_max_bid(), $args));
				
			}
			else{				
				$message = sprintf(__('Your bid of %s has been placed successfully.', 
					'woo_ua'), $display_bid_value);
			}
		}	
		
	} 
	else {
		
		if($product_data->get_uwa_auction_proxy() =="yes"){
			$message = sprintf(__( "Your bid has been placed successfully.", 'woo_ua'));
		}
		else {
			
			$message = sprintf( 
			__( "Your bid of %s has been placed successfully.", 'woo_ua'),
				$display_bid_value);
		}
			
	} 


	wc_add_notice ( apply_filters('ultimate_woocommerce_auction_bid_place_message', $message, $product_id ) );
}


if (!function_exists('wc_get_price_decimals')) {
	function wc_get_price_decimals() {
		return absint( get_option( 'wc_price_num_decimals', 2 ) );
	}
}

if (!function_exists('uwa_get_expired_auctions_id')) {

    /**
     * Return Expired auctions ids
     *     
     */
    function uwa_get_expired_auctions_id() {
		$args = array(
				'post_type' => 'product',
				'posts_per_page' => '-1',
				'show_expired_auctions' => TRUE,
				'tax_query' => array(array('taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'auction')),
				'meta_query' => array(
					array(
						'key' => 'woo_ua_auction_closed',
						//'compare' => 'NOT EXISTS',
						'compare' => 'IN',
						'value' => array('1','2','3','4'),
					)
				),
				'auction_arhive' => TRUE,
				'show_expired_auctions' => TRUE,
				'fields' => 'ids',
			);
    	$query = new WP_Query( $args );
    	$uwa_get_expired_auctions_id = $query->posts;

		return $uwa_get_expired_auctions_id;
	}
}

if (!function_exists( 'uwa_get_scheduled_auctions_id')) {

    /**
     * Return scheduled auctions ids
     *    
     */
    function uwa_get_scheduled_auctions_id() {
		$args = array(
				'post_type' => 'product',
				'posts_per_page' => '-1',
				'show_expired_auctions' => TRUE,
				'tax_query' => array(array('taxonomy' => 'product_type', 'field' => 'slug', 
					'terms' => 'auction')),
				'meta_query' => array(
					array(
						'key'     => 'woo_ua_auction_closed',
						'compare' => 'NOT EXISTS',
				),
				array(
						'key'     => 'woo_ua_auction_started',
						'value' => '0',
				)
				),
				'auction_arhive' => TRUE,
				'show_schedule_auctions' => TRUE,
				'fields' => 'ids',
			);
    	$query = new WP_Query( $args );
    	$uwa_get_scheduled_auctions_id = $query->posts;

		return $uwa_get_scheduled_auctions_id;
	}
}

function uwa_woocommerce_auctions_ordering() {
    	
        global $wp_query;

        if ( 1 === $wp_query->found_posts ) {
                return;
        }

        $orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : get_option( 'uwa_default_orderby');
        $show_default_orderby    = 'menu_order' === get_option( 'uwa_default_orderby' );
        $catalog_orderby_options = array(
                                'menu_order'       => __( 'Default sorting', 'woocommerce' ),
                                'date'             => __( 'Sort by latest', 'woocommerce' ),
                                'price'            => __( 'Sort by buynow price: low to high', 'woo_ua' ),
                                'price-desc'       => __( 'Sort by buynow price: high to low', 'woo_ua' ),
                               /* 'uwa_bid_asc'          => __( 'Sort by current bid: Low to high', 'woo_ua' ),
                                'uwa_bid_desc'         => __( 'Sort by current bid: High to low', 'woo_ua' ),*/
                                'uwa_ending'      => __( 'Sort auction by Ending Soon', 'woo_ua' ),
                                'uwa_started'  => __( 'Sort auction by Just started', 'woo_ua' ),
                                'uwa_active' => __( 'Sort auction by Most Active', 'woo_ua' ),
        );

        if ( ! $show_default_orderby ) {
                unset( $catalog_orderby_options['menu_order'] );
        }
        
        wc_get_template( 'loop/orderby.php', array( 'catalog_orderby_options' => $catalog_orderby_options, 'orderby' => $orderby, 'show_default_orderby' => $show_default_orderby ) );
}

//user display name
function uwa_user_display_name($user_id) {
		
	global $wpdb;	
	$uwa_simple_maskusername_enable = get_option('uwa_simple_maskusername_enable');
	$c_user_id = get_current_user_id();
	$user_name = "";	
	
	if(current_user_can('administrator') || current_user_can('manage_options') ||  
		current_user_can('manage_woocommerce') || $c_user_id == $user_id){
		$user_name = get_userdata($user_id)->display_name;	
	}
	elseif($uwa_simple_maskusername_enable == "yes"){
		
		$no_user_name = get_userdata($user_id)->display_name;			
			
		$user_strlen = strlen($no_user_name);
		$user_firstchar = strtolower($no_user_name[0]);
		$user_lastchar = strtolower($no_user_name[$user_strlen-1]);
		$user_middlechars = str_repeat("*", $user_strlen - 2);
				
		//$user_name = str_repeat("*", strlen($no_user_name)); /* prev */
		$user_name = $user_firstchar. $user_middlechars . $user_lastchar;
		
		
	/* }elseif($uwa_simple_maskusername_enable == "no"){	*/
	}else{
		$user_name = get_userdata($user_id)->display_name;
	}
	
	return $user_name;

}
	
function uwa_proxy_mask_user_display_name($user_id) {
	
	global $wpdb;
	
	$uwa_proxy_maskusername_enable = get_option('uwa_proxy_maskusername_enable');	
	//$uwa_disable_display_user_name = get_user_meta($user_id, 'uwa_disable_display_user_name', true);
	
	$c_user_id = get_current_user_id();	
	$user_name = "";
	
	if(current_user_can('administrator') || current_user_can('manage_options') ||  
		current_user_can('manage_woocommerce') || $c_user_id == $user_id){
		$user_name = get_userdata($user_id)->display_name;	
	}
	elseif($uwa_proxy_maskusername_enable == "yes"){
		
		$no_user_name = get_userdata($user_id)->display_name;			
			
		$user_strlen = strlen($no_user_name);
		$user_firstchar = strtolower($no_user_name[0]);
		$user_lastchar = strtolower($no_user_name[$user_strlen-1]);
		$user_middlechars = str_repeat("*", $user_strlen - 2);
				
		//$user_name = str_repeat("*", strlen($no_user_name)); /* prev */
		$user_name = $user_firstchar. $user_middlechars . $user_lastchar;
		
		
	/* }elseif($uwa_proxy_maskusername_enable == "no"){ */
	}else{
		$user_name = get_userdata($user_id)->display_name;
	}
	
	return $user_name;	
}
	
function uwa_silent_mask_user_display_name($user_id) {	
		
	global $wpdb;
	
	$uwa_silent_maskusername_enable = get_option('uwa_silent_maskusername_enable');	
	//$uwa_disable_display_user_name = get_user_meta($user_id, 'uwa_disable_display_user_name', true);
	
	$c_user_id = get_current_user_id();
	$user_name = "";	
	
	if(current_user_can('administrator') || current_user_can('manage_options') ||  
		current_user_can('manage_woocommerce') || $c_user_id == $user_id){
		$user_name = get_userdata($user_id)->display_name;	
	}
	elseif($uwa_silent_maskusername_enable == "yes"){
		
		$no_user_name = get_userdata($user_id)->display_name;			
			
		$user_strlen = strlen($no_user_name);
		$user_firstchar = strtolower($no_user_name[0]);
		$user_lastchar = strtolower($no_user_name[$user_strlen-1]);
		$user_middlechars = str_repeat("*", $user_strlen - 2);
				
		//$user_name = str_repeat("*", strlen($no_user_name)); /* prev */
		$user_name = $user_firstchar. $user_middlechars . $user_lastchar;
		
		
	/* }elseif($uwa_silent_maskusername_enable == "no"){	*/
	}else{
		$user_name = get_userdata($user_id)->display_name;
	}
	
	return $user_name;	
}
	
function uwa_proxy_mask_bid_amt($bid_value) {
		global $wpdb;
		$uwa_proxy_maskbid_enable = get_option('uwa_proxy_maskbid_enable');			
		if($uwa_proxy_maskbid_enable == "yes"){						
			$bid_value_amt = str_repeat("*", strlen($bid_value));
			
		} else {
			$bid_value_amt = wc_price($bid_value);
		}
		if (current_user_can('administrator') || current_user_can('manage_options') ) {		 
			$bid_value_amt = wc_price($bid_value);	
		}	

		return $bid_value_amt;
}
	
function uwa_silent_mask_bid_amt($bid_value) {
		global $wpdb;
		$uwa_silent_bid_enable = get_option('uwa_silent_bid_enable');			
		if($uwa_silent_bid_enable == "yes"){						
			$bid_value_amt = str_repeat("*", strlen($bid_value));
			
		} else {
			$bid_value_amt = wc_price($bid_value);
		}
		if (current_user_can('administrator') || current_user_can('manage_options') ) {		 
			$bid_value_amt = wc_price($bid_value);	
		}

		return $bid_value_amt;
}

/**
 * list bidders Ajax callback
 *
 * @see 'See More' link on 'Your Auctions/User Auctions' pages
 */
function uwa_see_more_bids_ajax_callback(){
    global $wpdb;
    $datetimeformat = get_option('date_format').' '.get_option('time_format');
	
	$auction_status = $_POST['auction_status'];

	if ($_POST['show_rows'] == -1) {
    	$query_bidders = 'SELECT * FROM '.$wpdb->prefix.'woo_ua_auction_log WHERE auction_id ='.$_POST['auction_id'].' ORDER BY date DESC';
	   
		$response['uwa_label_text'] = __('See less.','woo_ua');	   
	   
    } else {
    	$query_bidders = 'SELECT * FROM '.$wpdb->prefix.'woo_ua_auction_log WHERE auction_id ='.$_POST['auction_id'].' ORDER BY date DESC LIMIT 2';
		$response['uwa_label_text'] = __('See more','woo_ua');
    }

	$results = $wpdb->get_results($query_bidders);
	$row_bidders = '';
	if (!empty($results)) {
		
        foreach ($results as $result) {
            
				$userid	= $result->userid;
				$userdata = get_userdata( $userid );
				$bidder_name = $userdata->user_nicename;
                if ($userdata){				
					
					$bidder_name = "<a href='".get_edit_user_link( $userid )."' target='_blank'>".$bidder_name.'</a>';
					
				} else {
					
				  $bidder_name = 'User id:'.$userid;
                } 
				
				
				$maxbid_metakey = "woo_ua_auction_user_max_bid_".$_POST['auction_id'];
				$max_bid =  wc_price(get_user_meta($userid, $maxbid_metakey, true));

				$bid_amt = wc_price($result->bid);
				$bid_time = mysql2date($datetimeformat, $result->date);
				$row_bidders .= "<tr>";
				$row_bidders .= "<td>".$bidder_name." </td>";
				$row_bidders .= "<td>".$bid_amt."</td>";
				$row_bidders .= "<td>".$max_bid."</td>";
				$row_bidders .= "<td>".$bid_time."</td>";
				if ($auction_status == 'live') {
						$bid_ID = $result->id;
						$bid_user_ID = $result->userid;
						$bid_amount = $result->bid;
						$row_bidders .= "<td><a href='#' class='button uwa_force_choose_winner' 
							data-bid_id=".$bid_ID." 
							data-bid_user_id=".$bid_user_ID." 
							data-bid_amount=".$bid_amount." 
							data-auction_id=".$_POST['auction_id']." >".__('Choose Winner', 'woo_ua')."</a></td>";
				}				
				$row_bidders .= "</tr>";
				
	    } /* end of foreach */
			
		$row_bidders_final= $row_bidders;
		$response['bids_list'] =$row_bidders_final;		
		  	
	} /* end of if */

	echo json_encode( $response );
	exit;   
}

add_action('wp_ajax_uwa_see_more_bids_ajax', 'uwa_see_more_bids_ajax_callback');
add_action('wp_ajax_nopriv_uwa_see_more_bids_ajax', 
	'uwa_see_more_bids_ajax_callback');


function uwa_auction_ajax_add_bid_callback(){

	if (empty($_REQUEST['bid_value']) || !is_numeric($_REQUEST['bid_value'])) {
		$response['status'] = 0;
	}
	
	global $wpdb,$woocommerce, $product, $post;

	$auction_id = absint($_POST['product_id']);	
	$bid = abs(round(str_replace(',', '.', $_REQUEST['bid_value']), wc_get_price_decimals()));
	$proxy_engine = false;		
	$history_bid_id = false;
	$product_data = wc_get_product( $auction_id );
	$response['status'] = 1;

	if (!is_user_logged_in()) {		
		$response['msg_error'] = __('Please sign in to place your bid or buy the product','woo_ua');
		$response['status'] = 0;	
	}

	if ($bid <= 0) {
		$response['msg_error'] = __('Please enter a value greater than 0!','woo_ua');
		$response['status'] = 0;		
	}
		
	/* Check if auction product expired */
	if ($product_data -> is_uwa_expired()) {		
		$response['msg_error'] = __('This auction  has expired', 'woo_ua' );
		$response['status'] = 0;		
	}

	/* Check if auction product Live or schedule */
	if (!$product_data -> is_uwa_live()) {		
		$response['msg_error'] = __('Sorry, the auction has not started yet', 'woo_ua' );
		$response['status'] = 0;	
	}
	
	/* Check Stock */
	if (!$product_data -> is_in_stock()) {
		$response['msg_error'] = __('You cannot place a bid because the product is out of stock.', 
			'woo_ua');
		$response['status'] = 0;		
	}
	
	if ('auction' === $product_type) {
		
		$current_user = wp_get_current_user();
		$auction_type = $product_data->get_uwa_auction_type();
		$auction_bid_value = $product_data->uwa_bid_value();
		$auction_bid_increment = $product_data->get_uwa_auction_bid_increment();
		$auction_current_bid = $product_data->get_uwa_auction_current_bid();
		$auction_current_bider = $product_data->get_uwa_auction_current_bider();
		$auction_high_bid = $product_data->get_uwa_auction_max_bid();
		$auction_high_current_bider = $product_data->get_uwa_auction_max_current_bider();
		$auction_reserved_price = $product_data->get_uwa_auction_reserved_price();
		$auction_bid_count = $product_data->get_uwa_auction_bid_count();
		
		if ($auction_type == 'normal') {
			if ( $product_data->uwa_bid_value() <= ($bid )) {
				    
					$curent_bid = $product_data -> get_uwa_current_bid();
					update_post_meta($product_id, 'woo_ua_auction_current_bid', $bid);
					update_post_meta($product_id, 'woo_ua_auction_current_bider', $current_user->ID);
					update_post_meta($product_id, 'woo_ua_auction_bid_count', absint($product_data->get_uwa_auction_bid_count() + 1));					
					$history_bid_id = true;					
					
			} else {
				
				$response['msg_error'] = __('Please enter a bid value  greater than the current bid', 
					'woo_ua');
				$response['status'] = 0;
			}
		}  
		
		/* do_action('ultimate_woocommerce_auction_place_bid', array( 'product_id' => $product_id ,'log_id' => $history_bid_id )); */
		
		
		if ($history_bid_id){
			
			$woo_ua_auction_log = $wpdb->prefix."woo_ua_auction_log";
			$sql = "INSERT INTO $woo_ua_auction_log (userid, auction_id, bid, proxy, date) VALUES (".$current_user->ID.",".$product_id.",".$bid.",".$proxy.",".current_time('mysql').")";
			
			if($wpdb->query($sql)) {
				$response['msg_success'] = __('Your Bid Placed Successfully', 'woo_ua');				 
			}
		}
		   
	}

	/* exit;	 */
   	echo json_encode( $response );
	die();

}
add_action('wp_ajax_uwa_auction_ajax_add_bid', 'uwa_auction_ajax_add_bid_callback');
add_action('wp_ajax_nopriv_uwa_auction_ajax_add_bid', 'uwa_auction_ajax_add_bid_callback');	

	
function count_user_posts_by_type( $userid, $post_type = 'product' ) {
    global $wpdb;
    $where = get_posts_by_author_sql( $post_type, true, $userid );
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

    return apply_filters( 'get_usernumposts', $count, $userid );
}



/**
 * Anti Snipping
 *	
 */ 
function uwa_extend_auction_time_antisnipping($aucid){	

	global $product_data, $wpdb;	
	global $sitepress;

	if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 
		'get_default_language')) {
			
	    $aucid = icl_object_id($aucid, 'product', false, 
    	$sitepress->get_default_language());

	}		
	
    $ext_tm = get_option('uwa_auto_extend_time');
	$ext_tm = (double)$ext_tm;
    
    $ext_tmm = get_option('uwa_auto_extend_time_m');
    $ext_tmm = (double)$ext_tmm;
	
	$ext_tmm_s = get_option('uwa_auto_extend_time_s');
    $ext_tmm_s = (double)$ext_tmm_s;
	
	if($ext_tm > 0 || $ext_tmm > 0 || $ext_tmm_s > 0 ){	/* any of them must be > 0 */
	
			$ext_whn = get_option('uwa_auto_extend_when');
			$ext_whn = (double)$ext_whn;
				
			$ext_whnm = get_option('uwa_auto_extend_when_m');
   		 	$ext_whnm = (double)$ext_whnm;
								   
			$ext_whnm_s = get_option('uwa_auto_extend_when_s');
   		 	$ext_whnm_s = (double)$ext_whnm_s;
			/* $eng = get_post_meta($aucid, 'wdm_bidding_engine', true); */
			
			if(($ext_whn > 0 || $ext_whnm > 0 || $ext_whnm_s > 0) && ($ext_tm > 0 || $ext_tmm > 0 || $ext_tmm_s > 0) ){
				$le = get_post_meta($aucid, 'woo_ua_auction_end_date', true);	

				if(strtotime(get_uwa_now_date()) >= (strtotime($le) - (($ext_whn*3600) + ($ext_whnm*60)  + ($ext_whnm_s)))){
					
					$uwa_aviod_snipping_type_main  = get_option('uwa_aviod_snipping_type_main');
					if($uwa_aviod_snipping_type_main == "sniping_type_extend_checked"){
						$dt = strtotime($le)+(($ext_tm*3600) + ($ext_tmm*60) + ($ext_tmm_s));
					}else if($uwa_aviod_snipping_type_main == "sniping_type_reset_checked"){
						$dt = strtotime(get_uwa_now_date())+(($ext_tm*3600) + ($ext_tmm*60) + ($ext_tmm_s));
					}else{
						$dt = strtotime($le)+(($ext_tm*3600) + ($ext_tmm*60) + ($ext_tmm_s));
					}

					//$new_end_date = wp_date('Y-m-d H:i:s', $dt,get_uwa_wp_timezone());
					update_post_meta($aucid, 'woo_ua_from_anti_snipping', 'yes');
					$type_of_antisnipping  = get_option('uwa_aviod_snipping_type');
					
						$new_date = date("Y-m-d H:i:s", $dt);
						$cookie_time = strtotime($new_date)  -  (get_option( 'gmt_offset' )*3600);
					if($type_of_antisnipping == "snipping_recursive"){
						
						
						
						
						$is_done = update_post_meta($aucid, 'woo_ua_auction_end_date', date("Y-m-d H:i:s", $dt));
					
						/* Set that recursive antisipping is done */
						if($is_done != false){
						
								 
							setcookie('acution_end_time_php_'.$aucid, $cookie_time, time() + (86400 * 30 * 7), "/");
							update_post_meta($aucid, 'woo_ua_auction_extend_time_antisnipping_recursive', 'yes');
							
							/* Send Ending Soon Auction Mail again */
							//do_action( 'woo_ua_auctions_ending_soon_email_bidders', $aucid);
						}
					}					
					else if($type_of_antisnipping == "snipping_only_once" || $type_of_antisnipping == "" ){ 
					
					
					
					
						
						$is_updated = update_post_meta($aucid, 'woo_ua_auction_end_date', date("Y-m-d H:i:s", $dt));
					
						/* Set that only once antisipping is done */
						if($is_updated != false){
							
							setcookie('acution_end_time_php_'.$aucid, $cookie_time, time() + (86400 * 30 * 7), "/");
							update_post_meta($aucid, 'woo_ua_auction_extend_time_antisnipping', 'yes');
							
							/* Send Ending Soon Auction Mail again */
							//do_action( 'woo_ua_auctions_ending_soon_email_bidders', $aucid);
						}
					}
				}
				
			} /* end of if */
			
	} /* end of if  -- extend minutes and hours  > 0  */
}
add_action('uwa_extend_auction_time', 'uwa_extend_auction_time_antisnipping', 10, 1);


if (!function_exists( 'get_auction_product_search_form')) {

    /**
     * Display Auction product search form.
     *
     * Will first attempt to locate the uwa-auction-searchform.php file in either the child or.
     * the parent, then load it. If it doesn't exist, then the default search form.
     * will be displayed.
     *
     * The default searchform uses html5.
     *
     * @param bool $echo (default: true).
     * @return string
     *
     */
    function uwa_get_auction_product_search_form( $echo = true ) {
        global $product_search_form_index;

        ob_start();

        if ( empty( $product_search_form_index ) ) {
        	$product_search_form_index = 0;
        }

        do_action( 'pre_get_auction_product_search_form' );

        wc_get_template( 'uwa-auction-searchform.php' );

        $form = apply_filters( 'get_uwa_auction_search_form', ob_get_clean() );

        if ( $echo ) {
            echo $form; // WPCS: XSS ok.
        } else {
            return $form;
        }
    } /* end of fuction */
}
	

function uwa_get_auctions_count($auction_type) {
		global $wpdb; 
		global $sitepress;		
		if ($auction_type == 'live') {
			$meta_query = array(
						'relation' => 'AND',
							array(			     
								'key'  => 'woo_ua_auction_closed',
								'compare' => 'NOT EXISTS',
							),
							array(
							'key'     => 'woo_ua_auction_has_started',
							'value' => '1',
							)							
						);
		} elseif ($auction_type == 'expired') {						
			$meta_query= array(
						'relation' => 'AND',
							array(			     
								'key' => 'woo_ua_auction_closed',
								'value' => array('1','2','3','4'),
								'compare' => 'IN',
							),							
						);
		} elseif ($auction_type == 'scheduled') {		
			$meta_query= array(						
							array(			     
								'key'  => 'woo_ua_auction_closed',
								'compare' => 'NOT EXISTS',
								),	
							array(
							'key'     => 'woo_ua_auction_started',
							'value' => '0',
							)	
						);						
		}

		$curr_user_id = get_current_user_id();		
		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',			
			'posts_per_page' => -1,	
			'author' => $curr_user_id,
			'meta_query' => array($meta_query),
			'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')),
			'auction_arhive' => TRUE
		);		
		if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_current_language')) {
		   
			$args['suppress_filters']=0;	
		}
	    $auction_item_array = get_posts($args);		
		$total_items = count($auction_item_array); 

		return $total_items;		
}
	
function uwa_get_users_auctions_count($auction_type, $user_id) {      
		global $wpdb; 
		global $sitepress;					
		if ($auction_type == 'live') {
			$meta_query = array(
						'relation' => 'AND',
							array(			     
								'key'  => 'woo_ua_auction_closed',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => 'woo_ua_auction_has_started',
								'value' => '1',
							)							
						);
		} elseif ($auction_type == 'expired') {						
			$meta_query= array(
						'relation' => 'AND',
							array(			     
								'key' => 'woo_ua_auction_closed',
								'value' => array('1','2','3','4'),
								'compare' => 'IN',
							),							
						);
		} elseif ($auction_type == 'scheduled') {			
			$meta_query= array(						
							array(			     
								'key'  => 'woo_ua_auction_closed',
								'compare' => 'NOT EXISTS',
								),	
							array(
								'key'     => 'woo_ua_auction_started',
								'value' => '0',
							)	
						);						
		}

		$curr_user_id = get_current_user_id();

		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',			
			'posts_per_page' => -1,				
			'meta_query' => array($meta_query),
			'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')),
			'auction_arhive' => TRUE
		);	

		if (!empty($user_id)) {          
        	$args['author__in'] = $user_id;
        } else {
            $args[ 'author__not_in'] = $curr_user_id;
        }
		if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_current_language')) {
		   
			$args['suppress_filters']=0;	
		}
	    $auction_item_array = get_posts($args);		
		$total_items = count($auction_item_array); 

		return $total_items;		
}
	
function uwa_front_user_bid_list( $user_id , $bid_status ) {

	global $wpdb, $woocommerce;
	global $product;
	global $sitepress;


	   $table = $wpdb->prefix."woo_ua_auction_log";	 
	   $query   = $wpdb->prepare("SELECT auction_id, MAX(bid) as max_userbid FROM $table  WHERE userid = %d GROUP by auction_id ORDER by date DESC", $user_id);	  
	   $my_auctions = $wpdb->get_results( $query ); 

    $active_bids_count = 0;
    $lost_bids_count = 0;
    $won_bids_count = 0;
    $won_bids_products_ids = array();

	if ( count($my_auctions ) > 0 ) {
			$aelia_addon = "";
			$addons = uwa_enabled_addons();
			if(is_array($addons) && in_array('uwa_currency_switcher', $addons)){			
					$aelia_addon = true;			
			}

		?> 
		<table class="shop_table shop_table_responsive tbl_bidauc_list">
			<tr class="bidauc_heading">
			    <th class="toptable"><?php echo __( 'Image', 'woo_ua' ); ?></th>
			    <th class="toptable"><?php echo __( 'Product', 'woo_ua' ); ?></th>
			    <th class="toptable"><?php echo __( 'Your bid', 'woo_ua' ); ?></th>
			    <th class="toptable"><?php echo __( 'Current bid', 'woo_ua' ); ?></th>
			    <th class="toptable"><?php echo __( 'End date', 'woo_ua' ); ?></th>
			    <th class="toptable"><?php echo __( 'Status', 'woo_ua' ); ?></th>
			</tr>
			<?php	
			foreach ( $my_auctions as $my_auction ) {		  
			   
			   $product_id =  $my_auction->auction_id;	
				
				if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_current_language')) {
				
					$product_id = icl_object_id($my_auction->auction_id	,'product', false, $sitepress->get_current_language());
				}
			   
			   
			   $product = wc_get_product( $product_id );

			   if(is_object($product)){

				  
				   
					if ( method_exists( $product, 'get_type') && $product->get_type() == 'auction' ) {

						if($aelia_addon == true){
							if($product->uwa_aelia_is_configure() == TRUE){							
								$my_auction->max_userbid = $product->uwa_aelia_base_to_active($my_auction->max_userbid);
							}						
						}	

				        $product_name = get_the_title( $product_id );
				        $product_url  = get_the_permalink( $product_id );
				        $a            = $product->get_image( 'thumbnail' );

				    	if ($bid_status == "won" && $user_id == $product->get_uwa_auction_current_bider() && $product->get_uwa_auction_expired() == '2' ){ 	        			
									$won_bids_count++;
				    		?>			
							<tr class="bidauc_won">            
				            	<td class="bidauc_img"><?php echo $a;?></td>
				            	<td class="bidauc_name"><a href="<?php echo $product_url; ?>"><?php echo $product_name ?></a></td>
				            	<td class="bidauc_bid"><?php echo wc_price($my_auction->max_userbid); ?></td>
				            	<td class="bidauc_curbid"><?php echo $product->get_price_html(); ?></td>
				            	<td class="bidauc_enddate"><?php echo $product->get_uwa_auction_end_dates(); ?></td>	

								<?php

				            	/* -----  Pay now button for winner ----- */
								if (($user_id == $product->get_uwa_auction_current_bider() && $product->get_uwa_auction_expired() == '2' && !$product->get_uwa_auction_payed() )) { 

									$won_bids_products_ids[]= $product->get_id();
									$checkout_url = esc_attr(add_query_arg("pay-uwa-auction",$product->get_id(), uwa_auction_get_checkout_url()));

									/* change payment url for auto order */
										$uwa_auto_order_enable = get_option('uwa_auto_order_enable');
										if($uwa_auto_order_enable == "yes"){								

											$productid = $product->get_id();
										    $uwa_order_id = get_post_meta( $productid, 'woo_ua_order_id', true);
										    if ($uwa_order_id > 0){
										    	$order = wc_get_order($uwa_order_id);
												$checkout_url = $order->get_checkout_payment_url();
											}				
										}

					            	?>
									<td class="bidauc_status"> 

										<?php 
										
										/* --- when offline_addon is active --- */

										$addons = uwa_enabled_addons();
										if(is_array($addons) && in_array(
											'uwa_offline_dealing_addon', $addons)){
										
												// buyers and stripe both deactive
											if(!in_array('uwa_buyers_premium_addon', 
												$addons) && 
												!in_array('uwa_stripe_auto_debit_addon', $addons)){
												//echo "in 1";

											}	// buyers active only
											elseif(in_array('uwa_buyers_premium_addon', 
												$addons) && 
												!in_array('uwa_stripe_auto_debit_addon', $addons)){
												//echo "in 2";	

												?>
													<a href="<?php echo $checkout_url; ?>" class="button alt">
													<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', __( "Pay Buyer's Premium", 
														'woo_ua' ), 
													$product); ?>
													</a>	
												<?php 			

											}  // buyers and stripe both active
											elseif(in_array('uwa_buyers_premium_addon', 
												$addons) && 
												in_array('uwa_stripe_auto_debit_addon', $addons)){
												//echo "in 3";				
											}

										}
										else{
											
											?>
											<a href="<?php echo $checkout_url; ?>" 
												class="button alt">
											<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', __( 'Pay Now', 'woo_ua' ), $product); ?>
											</a>	
											<?php 

										} /* end of else */

										?>									
										
									</td>
								
				            		<?php 
				            	} 
				            	else { ?>			            		
				            		<td class="bidauc_status"><?php echo __( 'Closed', 'woo_ua' ); ?></td>
				            		<?php
				        		}  ?>

								</tr> 	
				     	<?php } /* end of if of won  */
				    	
				    	/* ------------------------ For Lost bids  ---------------------- */


				    	elseif ($bid_status == "lost" && $user_id != $product->get_uwa_auction_current_bider() && $product->get_uwa_auction_expired() == '2' ){
									$lost_bids_count++;
				    	 ?>			
							<tr class="bidauc_lost">            
				            	<td class="bidauc_img"><?php echo $a ;?></td>
				            	<td class="bidauc_name"><a href="<?php echo $product_url; ?>"><?php echo $product_name ?></a></td>
				            	<td class="bidauc_bid"><?php echo wc_price($my_auction->max_userbid); ?></td>
				            	<td class="bidauc_curbid"><?php echo $product->get_price_html(); ?></td>
				            	<td class="bidauc_enddate"><?php echo $product->get_uwa_auction_end_dates(); ?></td>
				            	<td class="bidauc_status"><?php echo __( 'Closed', 'woo_ua' ); ?></td>	                	
								</tr> 	
				     	<?php } /* end of if of lost */

				     	/* ------------------------ For active bids  ---------------------- */

				     	elseif($bid_status == "active" && $product->get_uwa_auction_expired() == false){ 
				     			$active_bids_count++;
				     		?>
				     		<tr class="bidauc_active">            
				            	<td class="bidauc_img"><?php echo $a ;?></td>
				            	<td class="bidauc_name"><a href="<?php echo $product_url; ?>"><?php echo $product_name ?></a></td>
				            	<td class="bidauc_bid"><?php echo wc_price($my_auction->max_userbid); ?></td>
				            	<td class="bidauc_curbid"><?php echo $product->get_price_html(); ?></td>
				            	<td class="bidauc_enddate"><?php echo $product->get_uwa_auction_end_dates(); ?></td>
				            	<td class="bidauc_status"><?php echo __( 'Started', 'woo_ua' ); ?></td>	                	
								</tr> 	
								<?php
				     	}

					}  /* end of if method exists  */

				}
				
			} /* end of foreach */ 

			if($bid_status == "won" && count($won_bids_products_ids) > 1){ ?>

				<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>		
				  <?php 
								echo '<a href="'.apply_filters( 'ultimate_woocommerce_auction_all_pay_now_button_text',esc_attr(add_query_arg("pay-uwa-auction",implode(",", $won_bids_products_ids), uwa_auction_get_checkout_url()))).'" class="button">'.__( 'Check Out All', 'woo_ua' ).'</a>';  ?>
				</td>
				</tr>
			 <?php	
			}
			elseif($bid_status == "won" && $won_bids_count == 0){ ?>

				<tr class="bidauc_msg"><td colspan="6"><div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">		
				  <?php _e( 'No bids available yet.' , 'woo_ua' ) ?>
				</div></td></tr>
			 <?php	
			}elseif($bid_status == "lost" && $lost_bids_count == 0){ ?>

				<tr class="bidauc_msg"><td colspan="6"><div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">		
				  <?php _e( 'No bids available yet.' , 'woo_ua' ) ?>
				</div></td></tr>
				
			 <?php
			}elseif($bid_status == "active" && $active_bids_count == 0){ ?>

				<tr class="bidauc_msg"><td colspan="6"><div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">		
				  <?php _e( 'No bids available yet.' , 'woo_ua' ) ?>
				</div></td></tr>

				 <?php	
			}
			?>
		</table> 

	<?php
	} /* end of if - count */
	else {
		$shop_page_id = wc_get_page_id( 'shop' );   
		$shop_page_url = $shop_page_id ? get_permalink( $shop_page_id ) : '';
		?>  
		<div class="woocommerce-message woocommerce-message--info woocommerce-Message 	
			woocommerce-Message--info woocommerce-info">		
			  <a class="woocommerce-Button button" href="<?php echo $shop_page_url;?>">
				<?php _e( 'Go shop' , 'woocommerce' ) ?>		</a> <?php _e( 'No bids available yet.' , 'woo_ua' ) ?>
		</div>
	                 
	<?php } /* end of else */

}


function uwa_front_user_bids_count( $user_id , $bid_status ) {
	 
	global $wpdb, $woocommerce;
	   $table = $wpdb->prefix."woo_ua_auction_log";	 
       $query   = $wpdb->prepare("SELECT auction_id, MAX(bid) as max_userbid FROM $table  WHERE userid = %d GROUP by auction_id ORDER by date DESC", $user_id);	  
       $my_auctions = $wpdb->get_results( $query );


    $active_bids_count = 0;
    $lost_bids_count = 0;
    $won_bids_count = 0;

	if ( count($my_auctions ) > 0 ) {
	   foreach ( $my_auctions as $my_auction ) {
		   global $product;
	      // $product = wc_get_product( $my_auction->auction_id );
			
			 global $sitepress;
			   
			   $product_id =  $my_auction->auction_id;	
				
				if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_current_language')) {
				
					$product_id = icl_object_id($my_auction->auction_id	,'product',false, $sitepress->get_current_language());
				}
			   
			   
			   $product = wc_get_product( $product_id );


			if(is_object($product)){

			   
			
				if ( method_exists( $product, 'get_type') && $product->get_type() == 'auction' ) {
					
		        	if ($bid_status == "won" && $user_id == $product->get_uwa_auction_current_bider() && $product->get_uwa_auction_expired() == '2' ){ 
		        		/* echo "in won bids"; */
		        		$won_bids_count++;
						
		         	} /* end of if */
		        	
		        	/* ------------------------ For Lost bids  ---------------------- */


		        	elseif ($bid_status == "lost" && $user_id != $product->get_uwa_auction_current_bider() && $product->get_uwa_auction_expired() == '2' ){
		        		/* echo "in lost bids"; */ 	
		        		$lost_bids_count++;
		         	} /* end of if of lost */

		         	elseif($bid_status == "active" && $product->get_uwa_auction_expired() == false){ 
		         		/* echo "in active bids"; */
		         		$active_bids_count++;
		         	}

				}  /* end of if method exists  */
			}
			
	    } /* end of foreach  */

	} /* end of if - count */

	if($bid_status == "won"){
		return  $won_bids_count;
	}elseif($bid_status == "lost"){
		return  $lost_bids_count;
	}elseif($bid_status == "active"){
		return  $active_bids_count;
	}else{
		return "null";
	}
}


function uwa_front_user_watchlist( $user_id ){

	global $wpdb, $woocommerce;
	global $product;
	global $sitepress;

	$my_auctions_watchlist = get_uwa_auction_watchlist_by_user($user_id);
	$my_auctions_watchlist_count = uwa_front_user_watchlist_count( $user_id );

	if ( $my_auctions_watchlist_count > 0 ) {
	?>
	<table class="shop_table shop_table_responsive tbl_watchauc_list">
	    <tr class="watchauc_heading">
	        <th class="toptable"><?php echo __( 'Image', 'woo_ua' ); ?></td>
	        <th class="toptable"><?php echo __( 'Product', 'woo_ua' ); ?></td>       
	        <th class="toptable"><?php echo __( 'Current bid', 'woo_ua' ); ?></td>
	        <th class="toptable"><?php echo __( 'Status', 'woo_ua' ); ?></td>
	        <th class="toptable"></td>
	    </tr>
	    <?php
	    foreach($my_auctions_watchlist as $key => $value) {
			
	        $product      = wc_get_product( $value );
	        if ( !$product )
	            continue;
	        
	        if ( is_object($product) && method_exists( $product, 'get_type') && $product->get_type() == 'auction' ) {

		        $product_name = get_the_title( $value );
		        $product_url  = get_the_permalink( $value );
		        $a            = $product->get_image( 'thumbnail' );
				$checkout_url = esc_attr(add_query_arg("pay-uwa-auction", $product->get_id(), uwa_auction_get_checkout_url()));

				/* change payment url for auto order */
					$uwa_auto_order_enable = get_option('uwa_auto_order_enable');
					if($uwa_auto_order_enable == "yes"){								

						$productid = $product->get_id();
					    $uwa_order_id = get_post_meta( $productid, 'woo_ua_order_id', true);
					    if ($uwa_order_id > 0){
					    	$order = wc_get_order($uwa_order_id);
							$checkout_url = $order->get_checkout_payment_url();
						}				
					}	
					
		        ?>
		        <tr class="watchauc_list">
		            <td class="watchauc_img"><?php echo $a ?></td>
		            <td class="watchauc_name"><a href="<?php echo $product_url; ?>"><?php echo $product_name ?></a></td>           
		            <td class="watchauc_curbid"><?php echo $product->get_price_html(); ?></td>
		            <?php

		            /* -----  Pay now button for winner ----- */

					if (($user_id == $product->get_uwa_auction_current_bider() && 
						$product->get_uwa_auction_expired() == '2' && !$product->get_uwa_auction_payed() )) { 

		              	?>
						<td class="watchauc_status"> 
								<?php 
								
								/* --- when offline_addon is active --- */

								$addons = uwa_enabled_addons();
								if(is_array($addons) && in_array(
									'uwa_offline_dealing_addon', $addons)){
								
										// buyers and stripe both deactive
									if(!in_array('uwa_buyers_premium_addon', 
										$addons) && 
										!in_array('uwa_stripe_auto_debit_addon', $addons)){
										//echo "in 1";

									}	// buyers active only
									elseif(in_array('uwa_buyers_premium_addon', 
										$addons) && 
										!in_array('uwa_stripe_auto_debit_addon', $addons)){
										//echo "in 2";
										?>
											<a href="<?php echo $checkout_url; ?>" 
												class="button alt">
												<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', __( 
													"Pay Buyer's Premium", 
													'woo_ua' ), $product); ?>
											</a>

									<?php 				

									}  // buyers and stripe both active
									elseif(in_array('uwa_buyers_premium_addon', 
										$addons) && 
										in_array('uwa_stripe_auto_debit_addon', $addons)){
										//echo "in 3";				
									}

								}
								else{
									?>
									<a href="<?php echo $checkout_url; ?>" 
										class="button alt">
										<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', __( 'Pay Now', 
											'woo_ua' ), $product); ?>
									</a>

									<?php 

								} /* end of else */

								?>
										
						</td>
		            	<?php  
		        	} elseif ( $product->is_uwa_expired() ){ ?> 
					
						<td class="watchauc_status"><?php echo __( 'Closed', 'woo_ua' ); ?></td>
		   
						<?php } else { ?>
		                <td class="watchauc_status"><?php echo __( 'Started', 'woo_ua' ); ?></td>
		                <?php
		            }
		            ?>
					<td class="product-remove">
						<a href="javascript:void(0)" data-auction-id="<?php echo esc_attr( $product->get_id() ); ?>" 
						class="remove-uwa uwa-watchlist-action remove" aria-label="Remove this item">×</a>
					</td>

		        </tr>
	        <?php
	    	}
		} ?>

	</table>

	  <?php
	} 
	else { ?>
	   
	   	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info watchauc_msg">
			
			   <?php _e( 'No auctions in watchlist' , 'woo_ua' ) ?>
		</div>
	                 
	<?php }
}


function uwa_front_user_watchlist_count( $user_id ){
	global $wpdb, $woocommerce;
	global $product;

	$my_auctions_watchlist = get_uwa_auction_watchlist_by_user($user_id);
	$watchlist_count = count($my_auctions_watchlist);

   	return $watchlist_count;
}

function uwa_front_user_auction_settings( $user_id ){
	do_action( 'woocommerce_before_edit_account_form' );
	/* $user_id = get_current_user_id(); */
 ?>

	<form class="woocommerce-EditAccountForm edit-account" action="" method="post">
		
		 <?php
		 	$uwa_disable_display_user_name = get_user_meta($user_id, 'uwa_disable_display_user_name', true) !== '0' ? '1' : '0';
		 	woocommerce_form_field( 'uwa_disable_display_user_name', array(
	        'type'          => 'checkbox',
	        'class'         => array('input-checkbox'),
	        'label'         => __('Display your name publicly', 'woo_ua'),
	        'required'  => false,
	        'default' => 1
	        ), $uwa_disable_display_user_name );

	       ?>

		<div class="clear"></div>

		<p>
			<?php wp_nonce_field( 'save_uwa_auctions_settings' ); ?>
			<input type="submit" class="woocommerce-Button button" name="save_uwa_auctions_settings" 
				value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
			<input type="hidden" name="action" value="save_uwa_auctions_settings" />
		</p>
		
	</form>

 <?php

}

/**
 *  Ajax Bid Placed Message
 *
 */
function ajax_uwa_bid_place_message( $product_id ) {
	
	global $woocommerce; 
	$product_data = wc_get_product($product_id);
	
		/* --aelia-- */
	$product_base_currency = $product_data->uwa_aelia_get_base_currency();
  	$args = array("currency" => $product_base_currency);

	$current_user = wp_get_current_user();
	$is_slient_auction = $product_data->get_uwa_auction_silent();
	
	if($is_slient_auction == "yes"){	
	
		$display_bid_value = wc_price($product_data->get_uwa_last_bid(), $args);
	}
	else{
	
		$display_bid_value = wc_price($product_data->get_uwa_current_bid(), $args);
	}
	
	if($current_user->ID == $product_data->get_uwa_auction_current_bider()){
		
		if(!$product_data->is_uwa_reserve_met()){			
			$message = sprintf(
				__('Your bid of %s has been placed successfully.', 'woo_ua'),
				$display_bid_value);
		} 
		else {
			if($product_data->get_uwa_auction_max_bid()){
				$message = sprintf( 
					__('Your bid of %s has been placed successfully! Your max bid is %s.', 
						'woo_ua'),
					$display_bid_value, 
					wc_price($product_data->get_uwa_auction_max_bid(), $args));
				
			}
			else{				
				$message = sprintf(__('Your bid of %s has been placed successfully.', 
					'woo_ua'), $display_bid_value);
			}
		}	
		
	} 
	else {
		
		if($product_data->get_uwa_auction_proxy() =="yes"){
			$message = sprintf(__( "Your bid has been placed successfully.", 'woo_ua'));
		}
		else {
			
			$message = sprintf( 
			__( "Your bid of %s has been placed successfully.", 'woo_ua'),
				$display_bid_value);
		}
			
	} 

	
		$newmessage = '<div class="woocommerce-message" role="alert">'.$message.'</div>';
		return $newmessage;
}


function uwa_get_plugin_info_version() {
	?> 
	 <span class="uwa_info_text"> - Ultimate WooCommerce Auction PRO</span>
	 <span class="uwa_version_text">(Version:<?php echo UW_AUCTION_PRO_VERSION; ?>)</span>
	<?php 
}

if(!function_exists('uwa_create_log')) {
	function uwa_create_log( $message, $level = 'debug', $source = 'uwa_auction' ) {
		$logger  = wc_get_logger();
		$context = array( 'source' => $source );

		return $logger->log( $level, $message, $context );
	}
}

/**
 * Get Auction WatchList By Auction Id
 *
 */
function get_uwa_auction_watchlist_by_auctionid( $auction_id  ) {
	
	global $wpdb;	
	$results = get_post_meta( $auction_id, "woo_ua_auction_watch"); 

	return $results;
} 
