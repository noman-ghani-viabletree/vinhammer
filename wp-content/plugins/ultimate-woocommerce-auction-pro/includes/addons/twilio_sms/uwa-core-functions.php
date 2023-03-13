<?php

/**
 * Extra Functions file
 *
 * @package Ultimate WooCommerce Auction Pro - business - addon twilio sms
 * @author Nitesh Singh 
 * @since 1.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Testing Twilio SMS 
 * 
 */  
 
 add_filter('ultimate_woocommerce_auction_before_place_bid_filter',
	'uwa_sms_phone_country_checking', 5, 3);

function uwa_sms_phone_country_checking($product_data, $bid){
    global $woocommerce, $product_data;

	$uwa_twilio_sms_mobile_reqiured = get_option('uwa_twilio_sms_mobile_reqiured');
 	if($uwa_twilio_sms_mobile_reqiured != "yes"){

		$customer_id = get_current_user_id();
		$ctm_phone = get_user_meta( $customer_id, 'billing_phone', true );
		$billing_country = get_user_meta( $customer_id, 'billing_country', true );
		$payment_method_url = wc_get_account_endpoint_url('edit-address')."billing";
		
		if(empty($ctm_phone) || empty($billing_country)){
			
			wc_add_notice(sprintf(__('Please enter Phone Number and Country details before placing the bid. <a href="%s" class="button" target="_blank">Submit here &rarr;</a>', 'woo_ua'), $payment_method_url), 'error');
			return false;
		}
	}

	return true;
}
 
 
 
 
add_action("wp_ajax_uwa_twilio_send_test_sms", "uwa_twilio_send_test_sms_ajxa_callback");

function uwa_twilio_send_test_sms_ajxa_callback() {
	global $wpdb;
	$mobile_number = trim( $_POST['uwa_test_phone'] );
	$uwa_test_message = sanitize_text_field( $_POST['uwa_test_message'] );
	$uwa_twilio_sms_sid = get_option('uwa_twilio_sms_sid');
	$uwa_twilio_sms_token = get_option('uwa_twilio_sms_token');
	$uwa_twilio_sms_from_number = get_option('uwa_twilio_sms_from_number');
	
	if(!empty($uwa_twilio_sms_sid)  && !empty($uwa_twilio_sms_sid) && !empty(
		$uwa_twilio_sms_sid)){
	
		require_once ( UW_AUCTION_PRO_ADDONS .'twilio_sms/lib/Twilio/autoload.php' );
    	$client = new Twilio\Rest\Client( $uwa_twilio_sms_sid, $uwa_twilio_sms_token);
		try {
			$message = $client->messages->create( $mobile_number, array( 
				'from' => $uwa_twilio_sms_from_number, 
				'body' => $uwa_test_message ) );			
			$response['message'] = __( 'Test message sent successfully', 'woo_ua' );
		} 
		catch( \Exception $e ) {
			$response['message'] =  $e->getMessage();
		    uwa_create_log("Error While Sending Test SMS : " .$e->getMessage());
		}
	}
	else {
		$response['message'] = __( 'Credentials are required', 'woo_ua' );
	}
		
	echo json_encode( $response );
	exit;
}

/**
 * Send Placed Bid SMS
 * 
 */
$uwa_twilio_sms_placed_bid_enabled = get_option('uwa_twilio_sms_placed_bid_enabled');
if($uwa_twilio_sms_placed_bid_enabled == "yes"){	
	add_action( 'ultimate_woocommerce_auction_place_bid', 'uwa_twilio_send_sms_place_bid'); 
} 
function uwa_twilio_send_sms_place_bid( $auction_id ) {
	global $wpdb, $woocommerce, $post;
	$customer_id = get_current_user_id();
	$uwa_sms_placebid_user_enabled = get_user_meta($customer_id,'uwa_sms_placebid_user_enabled', true);
	if($uwa_sms_placebid_user_enabled == "no" )
	{
		return false;
	}
	$message = "";
	$billing_country = "";
	$uwa_twilio_sms_sid = get_option('uwa_twilio_sms_sid');
	$uwa_twilio_sms_token = get_option('uwa_twilio_sms_token');
	$uwa_twilio_sms_from_number = get_option('uwa_twilio_sms_from_number');
	
	$product = wc_get_product($auction_id['product_id']);
	/* OLD */ 
	
	/*$currency_symbol = get_woocommerce_currency(); */ 
	
	/* --aelia-- */
	$product_base_currency = $product->uwa_aelia_get_base_currency();
	$currency_symbol = $product_base_currency;
	
	$auction_bid_value = $currency_symbol." ".$product->get_uwa_current_bid();
	$product_id =  $product->get_id();
	$auction_title = $product->get_title();
    $link = get_permalink($product->get_id());
	
	
	$ctm_phone = get_user_meta( $customer_id, 'billing_phone', true );
	$billing_country = get_user_meta( $customer_id, 'billing_country', true );	
	$to = uwa_twilio_sm_format_e164( $ctm_phone, $billing_country );

	$uwa_message_pp = get_option('uwa_twilio_sms_placed_bid_template',
		"New bid of amount {bid_value} has been placed for product id {product_id}.");
	
	$uwa_message_pp = str_replace('{bid_value}', $auction_bid_value, $uwa_message_pp);
	$uwa_message_pp = str_replace('{product_id}', $product_id, $uwa_message_pp);
	$uwa_message_pp = str_replace('{product_name}',$auction_title, $uwa_message_pp);	
	$uwa_message_pp = str_replace('{link}',  $link, $uwa_message_pp);	
	$message .= $uwa_message_pp;
	
	require_once ( UW_AUCTION_PRO_ADDONS .'twilio_sms/lib/Twilio/autoload.php' );
	$client = new Twilio\Rest\Client( $uwa_twilio_sms_sid, $uwa_twilio_sms_token);
	try {
		$fmessage = $client->messages->create( $to, array( 
			'from' => $uwa_twilio_sms_from_number, 
			'body' => $message ));
	} 
	catch( \Exception $e ) {
		$response['message'] =  $e->getMessage();			
	}	
}


$uwa_twilio_sms_won_enabled = get_option('uwa_twilio_sms_won_enabled');	
if($uwa_twilio_sms_won_enabled == "yes"){	
	/*add_action( 'ultimate_woocommerce_auction_close', 'uwa_twilio_send_sms_to_winner'); */
	add_action( 'ultimate_woocommerce_auction_winner_sms', 'uwa_twilio_send_sms_to_winner');  
} 
function uwa_twilio_send_sms_to_winner( $auction_id ) {
	
	global $wpdb, $woocommerce, $post;

	$product = wc_get_product($auction_id);
	$customer_id = $product->get_uwa_auction_current_bider();
	$uwa_sms_won_user_enabled = get_user_meta($customer_id,'uwa_sms_won_user_enabled', true);
	if($uwa_sms_won_user_enabled == "no" )
	{
		return false;
	}
	$sms_sent_status = get_post_meta( $auction_id, '_uwa_won_sms_sent_status', true );
	$auto_one_time_sms = get_post_meta( $auction_id, '_done_one_time_sms', true );
	
	if($auto_one_time_sms!='done_one_time_sms_1'){ 
			add_post_meta($auction_id, '_done_one_time_sms','done_one_time_sms_0');
	}
	
	if($sms_sent_status !="sent"){ 
		if($auto_one_time_sms !="done_one_time_sms_1" ){ 
			update_post_meta($auction_id, '_done_one_time_sms' ,"done_one_time_sms_1");
			
			$message = "";
			$pay_link = "";
			$billing_country = "";
			$uwa_twilio_sms_sid = get_option('uwa_twilio_sms_sid');
			$uwa_twilio_sms_token = get_option('uwa_twilio_sms_token');
			$uwa_twilio_sms_from_number = get_option('uwa_twilio_sms_from_number');	
			
			
			
			if ( (!empty($product->get_uwa_auction_current_bider()) && 
				$product->get_uwa_auction_expired() == '2' ) ) {
				$product_id =  $product->get_id();
				$auction_title = $product->get_title();
				$link = get_permalink($product->get_id());
				$customer_id = $product->get_uwa_auction_current_bider();
				
				$ctm_phone = get_user_meta( $customer_id, 'billing_phone', true );
				$billing_country = get_user_meta( $customer_id, 'billing_country', true );
				$to = uwa_twilio_sm_format_e164( $ctm_phone, $billing_country );
				
				$checkout_url = esc_attr(add_query_arg("pay-uwa-auction", $auction_id, uwa_auction_get_checkout_url()));

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
				
				$uwa_message_pp = get_option('uwa_twilio_sms_won_template',
					"You have won auction product id {product_id}, title {product_name}. Click {this_pay_link} to pay.");
				
				$uwa_message_pp = str_replace('{product_id}', $product_id, $uwa_message_pp);
				$uwa_message_pp = str_replace('{product_name}', $auction_title, $uwa_message_pp);	
				$uwa_message_pp = str_replace('{this_pay_link}', $checkout_url, $uwa_message_pp);	

				$message .= $uwa_message_pp;
				require_once ( UW_AUCTION_PRO_ADDONS .'twilio_sms/lib/Twilio/autoload.php' );
				$client = new Twilio\Rest\Client( $uwa_twilio_sms_sid, $uwa_twilio_sms_token);
				try {
					$fmessage = $client->messages->create( $to, array( 
						'from' => $uwa_twilio_sms_from_number, 
						'body' => $message ));				
					$sms_sent_status_metakey = "_uwa_won_sms_sent_status";
							update_post_meta($product_id, $sms_sent_status_metakey,"sent");

						
				}
				catch( \Exception $e ) {
					$response['message'] =  $e->getMessage();
					uwa_create_log("SMS Sent Won Error: " . $e->getMessage()." Auction ID=".$product_id);
				}		



			} /* end of if - empty */
		
		}
	}
	
}


$uwa_twilio_sms_outbid_enabled = get_option('uwa_twilio_sms_outbid_enabled');	
if($uwa_twilio_sms_outbid_enabled == "yes"){	
	add_action( 'ultimate_woocommerce_auction_outbid_bid', 
		'uwa_twilio_send_sms_to_outbid_bidder', 10, 2); 
} 
function uwa_twilio_send_sms_to_outbid_bidder( $product_id, $outbiddeduser ) {	
	global $wpdb, $woocommerce, $post;
	if ($outbiddeduser) {
		
		$message = "";	
		$billing_country = "";
		$uwa_twilio_sms_sid = get_option('uwa_twilio_sms_sid');
		$uwa_twilio_sms_token = get_option('uwa_twilio_sms_token');
		$uwa_twilio_sms_from_number = get_option('uwa_twilio_sms_from_number');	
			
		$product = wc_get_product($product_id);	
		$uwa_sms_outbid_user_enabled = get_user_meta($outbiddeduser,'uwa_sms_outbid_user_enabled', true);
		if($uwa_sms_outbid_user_enabled == "no" )
		{
			return false;
		}
		$currency_symbol = get_woocommerce_currency();
		$auction_bid_value = $currency_symbol." ".$product->get_uwa_current_bid();
		$product_id =  $product->get_id();
		$auction_title = $product->get_title();
	    $link = get_permalink($product->get_id());
		$customer_id = $outbiddeduser;
		
		$ctm_phone = get_user_meta( $customer_id, 'billing_phone', true );
		$billing_country = get_user_meta( $customer_id, 'billing_country', true );	
		$to = uwa_twilio_sm_format_e164( $ctm_phone, $billing_country );
		
		$uwa_message_pp = get_option('uwa_twilio_sms_outbid_template',"You have been outbid on product id {product_id}, title {product_name}. The current highest bid is {bid_value}. Open {link} and place your bid.");
		
		$uwa_message_pp = str_replace('{bid_value}', $auction_bid_value, $uwa_message_pp);
		$uwa_message_pp = str_replace('{product_id}', $product_id, $uwa_message_pp);
		$uwa_message_pp = str_replace('{product_name}',$auction_title, $uwa_message_pp);	
		$uwa_message_pp = str_replace('{link}', $link, $uwa_message_pp);	
		$message .= $uwa_message_pp;

		require_once ( UW_AUCTION_PRO_ADDONS .'twilio_sms/lib/Twilio/autoload.php' );
		$client = new Twilio\Rest\Client( $uwa_twilio_sms_sid, $uwa_twilio_sms_token);
		try {
			$fmessage = $client->messages->create( $to, array( 
				'from' => $uwa_twilio_sms_from_number, 
				'body' => $message ) );
		} 
		catch( \Exception $e ) {
			$response['message'] =  $e->getMessage();				
		}
	  
	} /* end of if */	
}


$uwa_twilio_sms_ending_soon_enabled = get_option('uwa_twilio_sms_ending_soon_enabled');	
if($uwa_twilio_sms_ending_soon_enabled == "yes"){
	uwa_twilio_get_ending_soon_auctions();
} 

function uwa_twilio_get_ending_soon_auctions() {
	 
		global $woocommerce, $wpdb;
		$uwa_interval =  get_option('uwa_twilio_sms_ending_soon_time', 1);
		$uwa_interval_time = date( 'Y-m-d H:i', current_time( 'timestamp' ) + ( $uwa_interval * HOUR_IN_SECONDS ) );
		// get auction which are live, and then matched interval with end date
	$args = array(
				'post_type'          => 'product',
				'posts_per_page'     => '100',                        
				//'posts_per_page'     => '-1',                        
				'tax_query'          => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'auction',
					),
				),
				'meta_query'         => array(
					'relation' => 'AND',        
					array(
						'key'     => 'woo_ua_auction_has_started',
						'value' => '1',
					),                            
					array(
						'key'     => 'woo_ua_auction_closed',
						'compare' => 'NOT EXISTS',
					),
					array(
							'key'     => 'uwa_auction_sent_ending_soon_sms',									
							'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'woo_ua_auction_end_date',
						'compare' => '<',
						'value'   => $uwa_interval_time,
						'type '   => 'DATETIME',
					),
					
				),                        
			);

	$the_query = new WP_Query( $args );           
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();	
			$product_data = wc_get_product( $the_query->post->ID );
			$now_timestamp = current_time( "timestamp");			
			add_post_meta( $the_query->post->ID, 'uwa_auction_sent_ending_soon_sms', $now_timestamp, true );
			uwa_twilio_send_sms_to_ending_soon( $the_query->post->ID );
			
		endwhile;
		wp_reset_postdata();
	}
		
}

function uwa_twilio_send_sms_to_ending_soon( $product_id ) {
	global $wpdb, $woocommerce, $post;

	if ($product_id) {
		$message = "";
		 //Get all participates 
		$final_userlist = array();	
		$ending_auction_users = $wpdb->get_results("SELECT DISTINCT userid  FROM ". 
			$wpdb->prefix ."woo_ua_auction_log WHERE auction_id = ". $product_id, OBJECT_K); //ARRAY_A

		if(count($ending_auction_users) > 0){
			$arr_ending_auction_users = array_keys($ending_auction_users);
			$final_auction_users[$product_id] =  $arr_ending_auction_users;
			$final_userlist = $arr_ending_auction_users;
		}
		
		$total_users = count($final_userlist);
		if ( $total_users > 0 ) {
			$billing_country = "";
			$uwa_twilio_sms_sid = get_option('uwa_twilio_sms_sid');
			$uwa_twilio_sms_token = get_option('uwa_twilio_sms_token');
			$uwa_twilio_sms_from_number = get_option('uwa_twilio_sms_from_number');	
				
			$product = wc_get_product($product_id);
			$product_id =  $product->get_id();
			$auction_title = $product->get_title();
			$link = get_permalink($product->get_id()); 
			
			$uwa_message_pp = get_option('uwa_twilio_sms_ending_soon_template',"Auction id {product_id}, title {product_name} will be expiring soon. Place your highest bid to win it.");
			
			$uwa_message_pp = str_replace('{product_id}', $product_id, $uwa_message_pp);
			$uwa_message_pp = str_replace('{product_name}', $auction_title, $uwa_message_pp);
			$uwa_message_pp = str_replace('{link}', $link, $uwa_message_pp);	
			$message .= $uwa_message_pp; 

			require_once ( UW_AUCTION_PRO_ADDONS .
						'twilio_sms/lib/Twilio/autoload.php' );
			$client = new Twilio\Rest\Client( $uwa_twilio_sms_sid, 
						$uwa_twilio_sms_token);
			 
			foreach ( $final_userlist as $key => $value) {
				$customer_id = $value;
				$uwa_sms_ending_soon_user_enabled = get_user_meta($customer_id,'uwa_sms_ending_soon_user_enabled', true);
				if($uwa_sms_ending_soon_user_enabled == "no" )
				{
					continue;
				}
				$ctm_phone = get_user_meta( $customer_id, 'billing_phone', true );
				$billing_country = get_user_meta( $customer_id, 'billing_country', true );	
				$to = uwa_twilio_sm_format_e164( $ctm_phone, $billing_country );

					try {
						$fmessage = $client->messages->create( $to, array( 
							'from' => $uwa_twilio_sms_from_number, 
							'body' => $message ) );
							
					} 
					catch( \Exception $e ) {
						$response['message'] =  $e->getMessage();
						
					uwa_create_log("SMS Sent Ending Soon Error: " . $e->getMessage()." Auction ID=".$product_id);	
					}
				
			} /* end of foreach */
		} /* end of if total users */		
	}
	
}

/**
 * Formating Phone Number 
 * 
 */
function uwa_twilio_sm_format_e164( $number, $country_code = null ) {

		/* if customer has allrady full phone number */
		if ( ! strncmp( $number, '+', 1 ) ) {
			return '+' . preg_replace( '[\D]', '', $number );
		}

		/* remove any non-number characters */
		$number = preg_replace( '[\D]', '', $number );
		$country_calling_code = null;	

		/* number has international call prefix (00) */
		if ( 0 === strpos( $number, '00' ) ) {

			/* remove international dialing code */
			$number = substr( $number, 2 );

			/* determine if the number has a country calling code entered */
			foreach ( uwa_twilio_sms_get_country_codes() as $code => $prefix ) {
				if ( 0 === strpos( $number, $prefix ) ) {
					$country_calling_code = $prefix;
					break;
				}
			} /* end of foreach */
		}

		/* getting full number with country code. */
		if ( ! $country_calling_code && $country_code ) {			
			$country_calling_code = uwa_twilio_sms_get_country_calling_code( $country_code );
			$number = $country_calling_code . $number;
		}

		/* if no country found  */
		if ( ! $country_calling_code ) {
			return $number;
		}

		/* remove 0 from  country code */
		if ( '0' === substr( $number, strlen( $country_calling_code ), 1 ) ) {
			$number = preg_replace( "/{$country_calling_code}0/", $country_calling_code, 
				$number, 1 );
		}

		/* prepend + */
		$number = '+' . $number;
		return $number;
}
	
/**
 * Get country calling code
 * 
 */
function uwa_twilio_sms_get_country_calling_code( $country ) {

	$country = strtoupper( $country );
	$country_codes = uwa_twilio_sms_get_country_codes();
	return ( isset( $country_codes[ $country ] ) ) ? $country_codes[ $country ] : '';
}
	
/**
 * Get country calling code
 * 
 */
function get_uwa_twilio_sms_asid_country_codes() {

		$country_codes = array_keys( uwa_twilio_sms_get_country_codes() );

		// The countries that don't allow an ASID
		$non_asid_country_codes = array(
			'AF',
			'AR',
			'AZ',
			'BD',
			'BE',
			'BR',
			'CA',
			'CD',
			'CG',
			'CL',
			'CN',
			'CO',
			'CR',
			'DO',
			'DZ',
			'EC',
			'GF',
			'GH',
			'GT',
			'GU',
			'HN',
			'HR',
			'HU',
			'IQ',
			'IR',
			'KE',
			'KG',
			'KR',
			'KW',
			'KY',
			'KZ',
			'LA',
			'LK',
			'MA',
			'MC',
			'ML',
			'MM',
			'MX',
			'MY',
			'MZ',
			'NA',
			'NI',
			'NP',
			'NR',
			'NZ',
			'PA',
			'PK',
			'PR',
			'QA',
			'RO',
			'SV',
			'SY',
			'TR',
			'US',
			'UY',
			'VE',
			'VN',
			'ZA',
		);

		$country_codes = array_diff( $country_codes, $non_asid_country_codes );

		return $country_codes;
}


function uwa_twilio_sms_get_country_codes() {

		$country_codes = array(
			'AC' => '247',
			'AD' => '376',
			'AE' => '971',
			'AF' => '93',
			'AG' => '1268',
			'AI' => '1264',
			'AL' => '355',
			'AM' => '374',
			'AO' => '244',
			'AQ' => '672',
			'AR' => '54',
			'AS' => '1684',
			'AT' => '43',
			'AU' => '61',
			'AW' => '297',
			'AX' => '358',
			'AZ' => '994',
			'BA' => '387',
			'BB' => '1246',
			'BD' => '880',
			'BE' => '32',
			'BF' => '226',
			'BG' => '359',
			'BH' => '973',
			'BI' => '257',
			'BJ' => '229',
			'BL' => '590',
			'BM' => '1441',
			'BN' => '673',
			'BO' => '591',
			'BQ' => '599',
			'BR' => '55',
			'BS' => '1242',
			'BT' => '975',
			'BW' => '267',
			'BY' => '375',
			'BZ' => '501',
			'CA' => '1',
			'CC' => '61',
			'CD' => '243',
			'CF' => '236',
			'CG' => '242',
			'CH' => '41',
			'CI' => '225',
			'CK' => '682',
			'CL' => '56',
			'CM' => '237',
			'CN' => '86',
			'CO' => '57',
			'CR' => '506',
			'CU' => '53',
			'CV' => '238',
			'CW' => '599',
			'CX' => '61',
			'CY' => '357',
			'CZ' => '420',
			'DE' => '49',
			'DJ' => '253',
			'DK' => '45',
			'DM' => '1767',
			'DO' => '1809',
			'DZ' => '213',
			'EC' => '593',
			'EE' => '372',
			'EG' => '20',
			'EH' => '212',
			'ER' => '291',
			'ES' => '34',
			'ET' => '251',
			'EU' => '388',
			'FI' => '358',
			'FJ' => '679',
			'FK' => '500',
			'FM' => '691',
			'FO' => '298',
			'FR' => '33',
			'GA' => '241',
			'GB' => '44',
			'GD' => '1473',
			'GE' => '995',
			'GF' => '594',
			'GG' => '44',
			'GH' => '233',
			'GI' => '350',
			'GL' => '299',
			'GM' => '220',
			'GN' => '224',
			'GP' => '590',
			'GQ' => '240',
			'GR' => '30',
			'GT' => '502',
			'GU' => '1671',
			'GW' => '245',
			'GY' => '592',
			'HK' => '852',
			'HN' => '504',
			'HR' => '385',
			'HT' => '509',
			'HU' => '36',
			'ID' => '62',
			'IE' => '353',
			'IL' => '972',
			'IM' => '44',
			'IN' => '91',
			'IO' => '246',
			'IQ' => '964',
			'IR' => '98',
			'IS' => '354',
			'IT' => '39',
			'JE' => '44',
			'JM' => '1',
			'JO' => '962',
			'JP' => '81',
			'KE' => '254',
			'KG' => '996',
			'KH' => '855',
			'KI' => '686',
			'KM' => '269',
			'KN' => '1869',
			'KP' => '850',
			'KR' => '82',
			'KW' => '965',
			'KY' => '1345',
			'KZ' => '7',
			'LA' => '856',
			'LB' => '961',
			'LC' => '1758',
			'LI' => '423',
			'LK' => '94',
			'LR' => '231',
			'LS' => '266',
			'LT' => '370',
			'LU' => '352',
			'LV' => '371',
			'LY' => '218',
			'MA' => '212',
			'MC' => '377',
			'MD' => '373',
			'ME' => '382',
			'MF' => '590',
			'MG' => '261',
			'MH' => '692',
			'MK' => '389',
			'ML' => '223',
			'MM' => '95',
			'MN' => '976',
			'MO' => '853',
			'MP' => '1670',
			'MQ' => '596',
			'MR' => '222',
			'MS' => '1664',
			'MT' => '356',
			'MU' => '230',
			'MV' => '960',
			'MW' => '265',
			'MX' => '52',
			'MY' => '60',
			'MZ' => '258',
			'NA' => '264',
			'NC' => '687',
			'NE' => '227',
			'NF' => '672',
			'NG' => '234',
			'NI' => '505',
			'NL' => '31',
			'NO' => '47',
			'NP' => '977',
			'NR' => '674',
			'NU' => '683',
			'NZ' => '64',
			'OM' => '968',
			'PA' => '507',
			'PE' => '51',
			'PF' => '689',
			'PG' => '675',
			'PH' => '63',
			'PK' => '92',
			'PL' => '48',
			'PM' => '508',
			'PR' => '1787',
			'PS' => '970',
			'PT' => '351',
			'PW' => '680',
			'PY' => '595',
			'QA' => '974',
			'QN' => '374',
			'QS' => '252',
			'QY' => '90',
			'RE' => '262',
			'RO' => '40',
			'RS' => '381',
			'RU' => '7',
			'RW' => '250',
			'SA' => '966',
			'SB' => '677',
			'SC' => '248',
			'SD' => '249',
			'SE' => '46',
			'SG' => '65',
			'SH' => '290',
			'SI' => '386',
			'SJ' => '47',
			'SK' => '421',
			'SL' => '232',
			'SM' => '378',
			'SN' => '221',
			'SO' => '252',
			'SR' => '597',
			'SS' => '211',
			'ST' => '239',
			'SV' => '503',
			'SX' => '1721',
			'SY' => '963',
			'SZ' => '268',
			'TA' => '290',
			'TC' => '1649',
			'TD' => '235',
			'TG' => '228',
			'TH' => '66',
			'TJ' => '992',
			'TK' => '690',
			'TL' => '670',
			'TM' => '993',
			'TN' => '216',
			'TO' => '676',
			'TR' => '90',
			'TT' => '1868',
			'TV' => '688',
			'TW' => '886',
			'TZ' => '255',
			'UA' => '380',
			'UG' => '256',
			'UK' => '44',
			'US' => '1',
			'UY' => '598',
			'UZ' => '998',
			'VA' => '39',
			'VC' => '1784',
			'VE' => '58',
			'VG' => '1284',
			'VI' => '1340',
			'VN' => '84',
			'VU' => '678',
			'WF' => '681',
			'WS' => '685',
			'XC' => '991',
			'XD' => '888',
			'XG' => '881',
			'XL' => '883',
			'XN' => '857',
			'XP' => '878',
			'XR' => '979',
			'XS' => '808',
			'XT' => '800',
			'XV' => '882',
			'YE' => '967',
			'YT' => '262',
			'ZA' => '27',
			'ZM' => '260',
			'ZW' => '263',
		);

		return $country_codes;

} /* end of function */
