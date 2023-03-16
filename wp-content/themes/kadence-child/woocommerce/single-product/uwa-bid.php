<?php

/* NOTE -- don't change class name and structure as it used in ajax place bid */

/**
 * Auction Product Bid Area
 * 
 * @package Ultimate WooCommerce Auction PRO
 * @author Nitesh Singh
 * @since 1.0
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce, $product, $post;

if(is_object( $product ) && !(method_exists( $product, 'get_type') && $product->get_type() == 'auction')){
	return;
}

$uwa_placebid_ajax_enable = get_option('woo_ua_auctions_placebid_ajax_enable', "no");

if($uwa_placebid_ajax_enable == "yes"){
	$button = "button";
}elseif($uwa_placebid_ajax_enable == "no"){
	$button = "submit";
}

/* -- aelia -- */
$product_base_currency = $product->uwa_aelia_get_base_currency();	
$currency_symbol = $product->uwa_aelia_get_base_currency_symbol();
$args = array("currency" => $product_base_currency);
$uwa_auction_type = $product->get_uwa_auction_type();
$uwa_expired = $product->is_uwa_expired();
$uwa_started = $product->is_uwa_live();
$uwa_reserved = $product->is_uwa_reserved();
$uwa_proxy  = $product->get_uwa_auction_proxy();
$uwa_reserve_met = $product->is_uwa_reserve_met();
$uwa_silent = $product->get_uwa_auction_silent();
$uwa_countdown_format = get_option( 'woo_ua_auctions_countdown_format' );
$uwa_remaining_seconds = $product->get_uwa_remaining_seconds();
$uwa_ending_time = $product->get_uwa_auctions_end_time();
$uwa_starting_time = $product->get_uwa_auction_start_time();
$uwa_start_price = $product ->get_uwa_auction_start_price();
$uwa_bid_value = $product->uwa_bid_value();
$curent_bid = $product->get_uwa_auction_current_bid();
$current_user = wp_get_current_user();
$product_id =  $product->get_id();
$user_max_bid = $product->get_uwa_user_max_bid($product_id ,$current_user->ID );
$reverse_bid_text = $uwa_auction_type == 'reverse' ? __( 'Your Minimum Bid is', 'woo_ua' ) : __( 'Your Maximum Bid is', 'woo_ua' );
$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );
$gmt_offset = get_option('gmt_offset') > 0 ? '+'.get_option('gmt_offset') : get_option('gmt_offset');
$timezone_string = get_option('timezone_string') ? get_option('timezone_string') : __('UTC ','woo_ua').$gmt_offset;
/*$silent_bid_text = $uwa_auction_type == 'reverse' ? sprintf(__( "Minimum bid for this auction is %s.", 'woo_ua' ), wc_price($uwa_start_price)) : sprintf(__( "Maximum bid for this auction is %s.", 'woo_ua' ), wc_price($uwa_start_price));*/
$silent_bid_text = sprintf(__( "Opening price is %s.", 'woo_ua' ), 
	wc_price($uwa_start_price, $args));

$uwa_enable_bid_place_warning = get_option('uwa_enable_bid_place_warning');
$auction_selling_type = $product->get_uwa_auction_selling_type();

$bid_inc = $product->get_uwa_auction_bid_increment();
$uwa_variable_inc_enable = get_post_meta($product->get_uwa_wpml_default_product_id(), 
			'uwa_auction_variable_bid_increment', true);
if($uwa_proxy == 'yes'){
	$winner_name = $product->get_uwa_proxy_winner_name();
} else {
	$winner_name = $product->get_uwa_winner_name();
}
$sticky_classes = '';
if(($uwa_expired === FALSE ) and ($uwa_started  === TRUE )){
	$sticky_classes = 'live-auction justify-content-between gap-10';
}
else if(($uwa_expired === FALSE ) and ($uwa_started  === FALSE )){
	$sticky_classes = 'schedule-auction';
}
else if(($uwa_expired === TRUE ) && empty($product->get_uwa_auction_fail_reason())){
	$sticky_classes = 'ended-auction';
}
else{
	$sticky_classes = 'expired-auction';
}
?>

<div id="auction-product-type"  
	data-auction-id="<?php echo esc_attr( $product_id ); ?>">

	<div class="aucion-sticky-bar d-flex align-items-center <?php echo $sticky_classes;?>">
		<?php if(($uwa_expired === FALSE ) and ($uwa_started  === TRUE )) : ?>
		
			<?php if(get_option('uwa_hide_timer_field', 'no') == 'no'){ 
					$uwa_remaining_seconds  =  wp_date('Y-m-d H:i:s',$product->get_uwa_remaining_seconds(),get_uwa_wp_timezone());
					$uwa_time_zone =  (array)wp_timezone();
					$sinceday  =  wp_date('M j, Y H:i:s O',time(),get_uwa_wp_timezone());
					
					$auc_end_date = get_post_meta( $product_id, 'woo_ua_auction_end_date', true );
					$rem_arr=get_remaining_time_by_timezone($auc_end_date); ?>
					<script>var servertime='<?php echo $sinceday;?>';</script>

					<div class="uwa_auction_time" id="uwa_auction_countdown">
						<strong>
							<?php _e('Time Left:', 'woo_ua'); ?>
						</strong>
						<?php
							countdown_clock(
								$end_date=$auc_end_date,
								$item_id=$product_id,
								$item_class='uwa-main-auction-product uwa_auction_product_countdown'   
							);
						?>
					</div>		
			<?php } ?>
			
			<div class="uwa-timezone">
				<?php if(get_option('uwa_hide_ending_on_field', 'no') == 'no'){ ?>
				<h5 class="uwa_auction_end_time">
					<strong><?php _e('Ending On:', 'woo_ua'); ?></strong>
					<span class="text">	
						<?php echo  date_i18n( get_option( 'date_format' ),  strtotime( $uwa_ending_time ));  ?>  
						<?php echo  date_i18n( get_option( 'time_format' ),  strtotime( $uwa_ending_time ));  ?>		
					</span>
				</h5>
				<?php } ?>
				<?php if(get_option('uwa_hide_timezone_field', 'no') == 'no'){ ?>
				<h5 class="uwa_auction_product_timezone">
					<strong><?php _e('Timezone:', 'woo_ua'); ?></strong>
					<span class="text">
						<?php echo $timezone_string; ?>
					</span>
				</h5>
				<?php } ?>	
			</div>
			<?php 
			if($auction_selling_type == "auction" || $auction_selling_type == "both" || $auction_selling_type == ""){?>
			
				<?php if ($uwa_silent != 'yes'){ ?>	
						<?php if(get_option('uwa_show_reserve_price', 'no') == 'yes'){ ?>
							<div class="checkreserve">
							<?php $start_price = wc_price($uwa_start_price); ?>
							
							<?php if(($uwa_reserved === TRUE) &&( $uwa_reserve_met === FALSE )  ) { ?>
										<p class="m-0 uwa_auction_reserve_not_met">
										<strong><?php _e('Opening Bid:', 'woo_ua'); ?></strong>
										<span class="text"><?php printf(__('%s','woo_ua') , $start_price);?></span>
										</p>
							<?php } ?>   
							
							<?php if(($uwa_reserved === TRUE) &&( $uwa_reserve_met === TRUE )  ) { ?>
								<p class="uwa_auction_reserve_met">
									<strong><?php _e('Opening Bid:', 'woo_ua'); ?></strong>
									<span class="text"><?php printf(__('%s','woo_ua') , $start_price);?></span>
								</p>
							<?php } ?>
							</div>
						<?php } ?>
						
							<?php if(get_option('uwa_hide_reserve_field', 'no') == 'no' && get_option('uwa_show_reserve_price', 'no') == 'no'){ ?>
								<div class="checkreserve">
									<?php if(($uwa_reserved === TRUE) &&( $uwa_reserve_met === FALSE )  ) { ?>
										<?php $reserve_text = __( "price has not been met.", 'woo_ua' ); ?>
										<p class="m-0 uwa_auction_reserve_not_met">
											<strong><?php _e('Opening Bid:', 'woo_ua'); ?></strong>
											<span class="text"><?php printf(__('%s','woo_ua') , $reserve_text);?></span>
										</p>	
									<?php } ?>

									<?php if(($uwa_reserved === TRUE) &&( $uwa_reserve_met === TRUE )  ) { ?>
										<?php $reserve_text = __( "price has been met.", 'woo_ua' ); ?>
										<p class="m-0 uwa_auction_reserve_met">
											<strong><?php _e('Opening Bid:', 'woo_ua'); ?></strong>
											<span class="text"><?php printf(__('%s','woo_ua') , $reserve_text);?></span>
										</p>
									<?php } ?>
								</div>
							<?php } ?>		

				<?php } elseif($uwa_silent == 'yes'){
						if (!empty($uwa_start_price)) {?>
						<p class="uwa_silent_bid_text"><?php echo $silent_bid_text; ?></p>	
						<?php } 
					} ?>
				<div class="total-bid hide-mobile">
					<p class="uwa_auction_reserve_met">
						<strong><?php _e('No. of Bids:', 'woo_ua'); ?></strong>
						<span class="text"><?php echo count($product->uwa_auction_log_history()); ?></span>
					</p>
				</div>
				<div class="highest-bid">
					<p class="uwa_auction_reserve_met">
						<strong><?php _e('Current Bid:', 'woo_ua'); ?></strong>
						<span class="text highest"><?php printf(__('%s','woo_ua') , wc_price($product->get_uwa_auction_current_bid(), $args));?></span>
					</p>
				</div>
					
			<?php } ?>
			<!-- <div class='uwa_auction_product_ajax_change d-flex align-items-center gap-40 <?php echo (get_the_author_meta('email') === $current_user->user_email) ? "justify-content-around" : "justify-content-between" ?>'>
			</div> -->
			<?php 
			/* ------  selling type - place bid start  ------ */

			if(get_the_author_meta('email') !== $current_user->user_email &&  ($auction_selling_type == "auction" || $auction_selling_type == "both" || $auction_selling_type == "")){
				if(get_option('uwa_show_custom_bid') == 'yes' || get_option('uwa_show_custom_bid') == false){ 
					$get_custombid_label = get_option('uwa_label_custom_bid', "");
					if($get_custombid_label){										
						$custombid_label = 	__($get_custombid_label, 'woo_ua');
					}
					else{										
						$custombid_label = 	__("Custom Bid", 'woo_ua');
					}?>

					<form id ="uwa_auction_form" class="uwa_auction_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $product_id; ?>">
						<?php do_action('ultimate_woocommerce_auction_before_bid_button'); 
						global $wp;
						$current_url = home_url( $wp->request );
						?>
						<?php if($uwa_auction_type == 'reverse' ) {?>
						
								<div class="quantity buttons_added">
									<span class="uwa_currency"><?php echo $currency_symbol;?></span>		
									<input type="number" name="uwa_bid_value" 
									id="uwa_bid_value" 
										data-auction-id="<?php echo esc_attr( $product_id ); ?>"value="" 
									<?php if ($uwa_silent != 'yes'){ ?> min="1" max="<?php echo $uwa_bid_value  ?>" <?php } ?>   
									<?php if ($uwa_silent == 'yes'){ ?> min="1" 
									<?php } ?>   
									step="any" size="<?php echo strlen($product->get_uwa_current_bid())+2 ?>" title="bid"  class="input-text  qty bid text left">					
								</div>	
								<?php if(is_user_logged_in()):?>
								<button type="<?php echo $button; ?>" id="placebidbutton" class="place-bid-button bid_button button alt d-flex justify-content-center align-items-center">
									<?php echo apply_filters('ultimate_woocommerce_auction_bid_button_text', $custombid_label, $product); ?>
								</button>
								<?php else: ?>
									<a href="<?php echo site_url()?>/sign-in?uwa-new-redirect=<?php echo $current_url?>" class="place-bid-button bid_button button alt d-flex justify-content-center align-items-center">Login to Place Bid</a>
								<?php endif;?>

								<?php $loader_imgurl = UW_AUCTION_PRO_URL . "assets/images/ajax_loader.gif"; ?>
							
								<span class="ajax-loader-placebid" style="display:none;">
									<img class='loaderimg' src="<?php echo $loader_imgurl; ?>" 
										style="visibility: hidden;margin-left:5px;" />
								</span>
								
						<?php } else { ?>
							<div class="quantity buttons_added">
								<span class="uwa_currency"><?php echo $currency_symbol;?></span>	
								<input type="number" name="uwa_bid_value"  id="uwa_bid_value" data-auction-id="<?php echo esc_attr( $product_id ); ?>"
								<?php if(isset($bidval)) { ?>value="<?php echo $bidval; ?>" <?php } ?>
								<?php if ($uwa_silent != 'yes'){ ?>  min="<?php echo $uwa_bid_value  ?>"  <?php } ?> 
								<?php if ($uwa_silent == 'yes'){ ?>  min="1"  
								<?php } ?> 
								step="any" size="<?php echo strlen($product->get_uwa_current_bid())+2 ?>" title="bid"  class="input-text qty bid text left">
							</div>
							<?php if(is_user_logged_in()):?>
								<button type="<?php echo $button; ?>" id="placebidbutton" class="place-bid-button bid_button button alt d-flex justify-content-center align-items-center">
									<?php echo apply_filters('ultimate_woocommerce_auction_bid_button_text', $custombid_label, 
										$product); ?>
								</button>
							<?php else: ?>
								<a href="<?php echo site_url()?>/sign-in?uwa-new-redirect=<?php echo $current_url?>" class="place-bid-button bid_button button alt d-flex justify-content-center align-items-center">Login to Place Bid</a>
							<?php endif;?>

							<?php $loader_imgurl = UW_AUCTION_PRO_URL . "assets/images/ajax_loader.gif"; ?>
							
							<span class="ajax-loader-placebid" style="display:none;">
								<img class='loaderimg' src="<?php echo $loader_imgurl; ?>" 
									style="visibility: hidden;margin-left:5px;" />
							</span>
						
						<?php } ?>
						
						<input type="hidden" name="bid" value="<?php echo esc_attr( $product_id ); ?>" />
						<input type="hidden" id="uwa_place_bid" name="uwa-place-bid" value="<?php echo $product_id; ?>" />
						<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>" />
						
						<?php if ( is_user_logged_in() ) { ?>
							<input type="hidden" name="user_id" value="<?php echo  get_current_user_id(); ?>" />
						<?php  } ?> 
						
						<?php do_action('ultimate_woocommerce_auction_after_bid_button'); ?>
					</form>
				<?php }
			}
		
		?>

		<?php elseif (($uwa_expired === FALSE ) and ($uwa_started  === FALSE )):?>

			<p class="text auction-status">Starting Soon</p>
			
			<div class="featured-sep"></div>
			<div class="d-flex align-items-center gap-40">
				<p class="uwa_auction_product_schedule_time m-0">
					<?php _e('Auction has not been started yet.', 'woo_ua'); ?>
				</p>
	
				<?php if(get_option('uwa_hide_start_on_field', 'no') == 'no'){ ?>
					<p class="uwa_auction_product_schedule_time hide-mobile m-0">
						<strong><?php _e('Start On:', 'woo_ua'); ?></strong>
						<span class="text">		
							<?php echo  date_i18n( get_option( 'date_format' ),  strtotime( $uwa_starting_time ));  ?>
							<?php echo  date_i18n( get_option( 'time_format' ),  strtotime( $uwa_starting_time ));  ?>
						</span>		
					</p>
				<?php } ?>	
	
				<?php if(get_option('uwa_hide_timer_field', 'no') == 'no'){ 
					$starting_time  =  wp_date('Y-m-d H:i:s',$product->get_uwa_seconds_to_start_auction(),get_uwa_wp_timezone());
					$uwa_time_zone =  (array)wp_timezone();
				
					$auc_end_date=get_post_meta( $product_id, 'woo_ua_auction_start_date', true );
					$rem_arr=get_remaining_time_by_timezone($auc_end_date); 
					?>
					
					<div class="uwa_auction_product_time hide-mobile scheduled" id="uwa_auction_countdown">
						<strong>
							<?php _e('Starting Time Left:', 'woo_ua'); ?>
						</strong>
						
						<?php
						countdown_clock(
							$end_date=$auc_end_date,
							$item_id=$product_id,
							$item_class='uwa-main-auction-product  scheduled'   
						);
						
						?>
					</div>
				<?php } ?>
			</div>

		<?php elseif (($uwa_expired === TRUE ) && empty($product->get_uwa_auction_fail_reason())):?>
			<p class="text auction-status">Ended</p>
			<div class="featured-sep"></div>
			<div class="d-flex gap-40 ended-detail">
				<div class="highest-bid">
					<strong>Winning Bid:</strong>
					<span class="text"><?php echo wc_price($product->get_uwa_auction_current_bid());?></span>
				</div>
				<div class="highest-bid">
					<strong>Sold to:</strong>
					<span class="text profile-name"><?php echo $winner_name; ?></span>
				</div>
			</div>
		<?php else:?>
			<p class="text auction-status">Expired</p>
			<div class="featured-sep"></div>
			<p class="text m-0"><strong>Auction expired because there were no bids.</strong></p>
		<?php endif;?>

	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">

<script type="text/javascript">
var curentpageenddate='<?php  echo strtotime( $uwa_ending_time );?>';

	jQuery("document").ready(function($){

		$("#placebidbutton_direct").on('click', function(event){

			var formname = "directbid";			
			retval = bid_check(formname);
			
			if(retval == true || retval == false){				
				return retval;
			}
		});
		
		$("#placebidbutton").on('click', function(event){
			
			var formname = "custombid";
			retval = bid_check(formname);
			
			if(retval == true || retval == false){				
				return retval;
			}
		});

		function bid_check(formname){			

			var id_Bid;

			if(formname == "custombid"){
				id_Bid = "#uwa_bid_value";
			}
			else if(formname == "directbid"){
				id_Bid = "#uwa_bid_value_direct";
			}

				var bidval = parseFloat($(id_Bid).val());

			  	if(bidval){		  		

			  		if(formname == "custombid"){

				  			var minval = parseFloat($(id_Bid).attr("min"));
							var maxval = parseFloat($(id_Bid).attr("max"));
					

							if(minval <= bidval){
								bid_process(formname, id_Bid);
							}
							else{
								
								Swal.fire({
									title: 'Wrong Bid',
									text: "<?php _e('Please enter bid value greater than suggested bid', 'woo_ua');?>",
									showCloseButton: true,
									focusConfirm: false,
									focusCancel: false,
									focusClose: false,
								});

								// alert("<?php // _e('Please enter bid value greater than suggested bid', 'woo_ua');?>");
								return false;
							}
					}
					else if(formname == "directbid"){
						bid_process(formname, id_Bid);						
					}

				} /* end of if - bidval */												
				else{
					// alert("<?php _e('Please enter bid value', 'woo_ua');?>");
					Swal.fire({
						title: 'Empty Bid',
						text: "<?php _e('Please enter bid value', 'woo_ua');?>",
						showCloseButton: true,
						focusConfirm: false,
						focusCancel: false,
						focusClose: false,
					});
					return false;
				}

		} /* end of function */


		function bid_process(formname, id_Bid){
				
				<?php 

				$uwa_enable_bid_place_warning = 
					get_option('uwa_enable_bid_place_warning');
				$uwa_placebid_ajax_enable = 
					get_option('woo_ua_auctions_placebid_ajax_enable');

				if($uwa_placebid_ajax_enable == "no" || 
					$uwa_placebid_ajax_enable == ""){ /* using page load */
					if($uwa_enable_bid_place_warning  == "yes"){ ?>
						confirm_bid(formname, id_Bid);
					<?php
					}		
				}elseif($uwa_placebid_ajax_enable == "yes"){ /* using ajax */
					if($uwa_enable_bid_place_warning  == "yes"){ ?>
						var retval = confirm_bid(formname, id_Bid);
						if(retval == true){ 
							/* bid using ajax if confirm yes */
							placebid_ajax_process(formname);
						}
					<?php
					}
					else{?>
						/* bid using ajax */
						placebid_ajax_process(formname);
					<?php
					}
				}
				?>

		} /* end of function */


		function placebid_ajax_process(formname){

			if(formname == "custombid"){
				id_Bid = "#uwa_bid_value";
				id_h_Product = "#uwa_place_bid";
				id_Bid_Button = "#placebidbutton";
				class_ajax_Span = ".ajax-loader-placebid";
				class_ajax_Img	= ".loaderimg";
			}
			else if(formname == "directbid"){
				id_Bid = "#uwa_bid_value_direct";
				id_h_Product = "#uwa_place_bid_direct";
				id_Bid_Button = "#placebidbutton_direct";
				class_ajax_Span = ".ajax-loader-placebid_direct";
				class_ajax_Img	= ".loaderimg_direct";
			}

			var uwa_place_bid = $(id_h_Product).val();
			var uwa_bid_value = $(id_Bid).val();
			var uwa_url = "<?php echo admin_url('admin-ajax.php'); ?>";		
			
			$.ajax({
      			method : "post",  /* don't use 'type' */
				url : "<?php echo admin_url('admin-ajax.php'); ?>",				
				data : 	{action: "uwa_ajax_placed_bid",
						uwa_place_bid : uwa_place_bid,
						uwa_bid_value : uwa_bid_value
						},
				beforeSend: function(){	
					$(class_ajax_Span).css("display", "inline");
    				$(class_ajax_Img).css("visibility", "visible");
    				$('.product-type-auction').css("opacity", "0.7");
    				$("#placebidbutton").attr("disabled", "disabled");
    				$("#placebidbutton_direct").attr("disabled", "disabled");
    				$('.single_add_to_cart_button').attr("disabled", 
    					"disabled");
  				},
  				
				success: function(response) {
					
					var data = $.parseJSON( response );
					
					if(typeof data.allmsg != "undefined"){
						//$(".woocommerce-notices-wrapper").hide();
						//$(".woocommerce-notices-wrapper").html(data.allmsg).fadeIn(1000);
						
						$(".woocommerce-notices-wrapper").html(data.allmsg);
					}

					/* display fields data in detail page */
					//if(data.allstatus == 1){

						var auctionid = uwa_place_bid;
						var newprice = data.alldata_display.uwa_curent_bid;
						var newenterval = data.alldata_display.entervalue;
						var newwinusername = data.alldata_display.winusername;
						var newreservetext = data.alldata_display.reservetext;
						var newmaxmintext = data.alldata_display.maxmintext;
						var newuwabidsalldata = data.alldata_display.uwa_bids_alldata;
						var newbidminval = data.alldata_display.uwa_bid_minval;
						var newbidmaxval = data.alldata_display.uwa_bid_maxval;
						var newtimerval = data.alldata_display.remaining_secs;
						var auctiontype = data.alldata_display.auction_type;

						var newnextbids = data.alldata_display.next_bids;

						var newuwa_imgtext = data.alldata_display.uwa_imgtext;
						var newuwa_detailtext = data.alldata_display.uwa_detailtext;
						var newuwa_buynow = data.alldata_display.uwa_buynow;

						if(typeof newprice != "undefined"){
							$("p.price").html(newprice); /* + "--done"); */
						}
						
						if(typeof newenterval != 'undefined'){
							newenterval = newenterval + " )";
							/* uwa_inc_latest_price or uwa_inc_price_ajax_492 */
							$("small.uwa_inc_latest_price").html(newenterval);
						}

						if(typeof newreservetext != 'undefined'){
							//strong.uwa_auction_reserve_price							
							$("div.checkreserve").html(newreservetext);
						}

						if(typeof newwinusername != 'undefined'){

							$("div.winner-name").html(newwinusername);
						}

						if(typeof newmaxmintext != 'undefined'){
							$("p.max-bid").html(newmaxmintext);
						}

						if(typeof newtimerval != 'undefined'){
							
							$("div.uwa_auction_product_countdown").attr(
								'data-time', newtimerval);

							time1 = newtimerval;
														
						}

						if(typeof newuwabidsalldata != 'undefined'){
								

							$("div.uwa_bids_history_data").html(newuwabidsalldata); 
						}

						if(typeof newbidminval != 'undefined'){

							/* note : change min value for both direct and custom bid  **** */

							$("#uwa_bid_value").attr("min", newbidminval);
							

							/* set default value for direct bid */
							
						}

						if(typeof newnextbids != 'undefined'){

							if(auctiontype == "normal"){

								/* set options for direct bid */
								$("#uwa_bid_value_direct").html(newnextbids);

							}
							
						}

						if(typeof newbidmaxval != 'undefined'){

							/* note : change max value for both direct and custom bid  **** */

							$("#uwa_bid_value").attr("max", newbidmaxval);
							$("#uwa_bid_value_direct").attr("max", 
								newbidmaxval);
								
							/* set default value for direct bid */
							if(auctiontype == "reverse"){
								$("#uwa_bid_value_direct").val(newbidmaxval);
							}

						}
						
						if(typeof newuwa_imgtext != "undefined"){
							$("span.uwa_imgtext").html(newuwa_imgtext); 
						}
						else{							
							$("span.uwa_imgtext").html("");
						}

						if(typeof newuwa_detailtext != "undefined"){							
							$("p.uwa_detailtext").html(newuwa_detailtext);
						}
						else{							
							$("p.uwa_detailtext").html(""); 
						}

						if(typeof newuwa_buynow != "undefined"){
							
							if(newuwa_buynow != "yes"){
								
								$("form.buy-now .single_add_to_cart_button").css("display", 
									"none"); 								
							}
							else{
								
								$("form.buy-now .single_add_to_cart_button").css("display", 
									"inline-block"); 
							}							
						}

						$("#uwa_bid_value").val("");
						

					//}
					
				},
				error: function(){},

				complete: function(){
					$(class_ajax_Span).css("display", "none");
					$(class_ajax_Img).css("visibility", "hidden");
    				$('.product-type-auction').css("opacity", "1");	
    				$("#placebidbutton").removeAttr("disabled");
    				$("#placebidbutton_direct").removeAttr("disabled");
    				$('.single_add_to_cart_button').removeAttr("disabled");
    				

    				/* -------- slider ---------- */

    				var custom_add = 100;

    				<?php  if(get_template() == "Divi"){ ?> 					

    					custom_add = 500;

    				<?php } ?>


						$("html").animate({scrollTop: ($(".woocommerce-notices-wrapper").offset().top)-custom_add}, 1500);
						
						$(".woocommerce-notices-wrapper").hide(); 
						setTimeout(function(){
					       $(".woocommerce-notices-wrapper").fadeIn(2000);
					    }, 1000); 

    				/* -------- slider ---------- */				    


				},
			});

		} /* end of function */


			/* Extra confirmation message on place bid */
		function  confirm_bid(formname, id_Bid) {

			/* Get bid value, format value and then add to confirm message */
			var bidval = jQuery(id_Bid).val();
			var bidval = parseFloat(bidval);

			if (bidval > 0){
				
				var floatbidval = bidval.toFixed(2); /* 2 numbers after decimal point */
				/*var currencyval = "<?php echo html_entity_decode(get_woocommerce_currency_symbol()); ?>";*/

				/* bloginfo( 'charset' ); */

				var currencyval = "<?php echo html_entity_decode(get_woocommerce_currency_symbol($product_base_currency), ENT_COMPAT | ENT_HTML401, 
					'UTF-8'); ?>";

				var finalval = currencyval + floatbidval;


				if(formname == "custombid"){
					var confirm1 = '<?php echo addslashes(__( "Do you really want to bid", "woo_ua" )); ?>';
				}
				else if(formname == "directbid"){
					var confirm1 = '<?php echo addslashes(__( "Do you really want to directly place this bid", "woo_ua" )); ?>';
				}

				
				var confirm_message = confirm1 + ' ' + finalval + ' ?';

				// var result_conf = confirm(confirm_message);
				// var result_conf = false;
				event.preventDefault();

				let confirm_html = `<div class="confirm-bid featured-list"><p>Are you sure you want to place this bid?</p>
				<div class="center-tab d-flex align-items-center justify-content-center mb-2 product">
						<div class="tag-row d-flex flex-direction-column">
							<span class="tag">Opening Bid</span>
							<span class="text">$122</span>
						</div>
						<div class="featured-sep"></div>
						<div class="tag-row d-flex flex-direction-column">
							<span class="tag">Your placing bid</span>
							<span class="text" style="color: #EC1B34;">$12323</span>
						</div>
					</div></div>				
				`;
				Swal.fire({
					title: 'Confirm Bid',
					text: confirm_message,
					html: confirm_html,
					confirmButtonText: 'Yes, place bid',
					showCancelButton: true,
					showCloseButton: true,
					focusConfirm: false,
					focusCancel: false,
					focusClose: false,
				}).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.isConfirmed) {
						console.log("bid success full");
						result_conf = true;
						$('#uwa_auction_form').submit();
					} else{
						result_conf = false;
					}
				});

				// event.preventDefault();

				// if(result_conf == false){
				// 	event.preventDefault(); /* don't use return it reloads page */
				// }
				// else{
				// 	return true;
				// }
			}
			
		} /* end of function - confirm_bid() */


	}); /* end of document ready */

</script>