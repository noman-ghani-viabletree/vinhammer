<?php

/**
 * Auction product add to cart
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

if ( ! $product->is_purchasable() ) {
	return;
}
	
if (  $product->is_uwa_expired() ) {
	return;
}

if (  $product->is_uwa_live() === FALSE )  {
	return;
}

if ( !$product->is_sold_individually() ) {
	return;
}
	
if ( $product->is_in_stock() ) : ?>
    <?php do_action('woocommerce_before_add_to_cart_form'); ?>
    <form class="buy-now cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>">
	<div class="uwa_buttons">
        <?php 
            do_action('woocommerce_before_add_to_cart_button');
                if ( ! $product->is_sold_individually() )
                                woocommerce_quantity_input( array(
                                        'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                                        'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
                                ) );
         ?>
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
    	
<?php 
    $auction_selling_type = $product->get_uwa_auction_selling_type();

    if($auction_selling_type == "buyitnow" || $auction_selling_type == "both" || 
        $auction_selling_type == ""){


            /* --------  when offline_dealing_addon is active ------- */
            $addons = array();
            $addons = uwa_enabled_addons();

         
            if($addons == false || (is_array($addons) && !in_array('uwa_offline_dealing_addon', 
                $addons))){
                            
                $uwa_disable_buy_it_now = get_option('uwa_disable_buy_it_now', "no");
                $uwa_disable_buy_it_now__bid_check = get_option('uwa_disable_buy_it_now__bid_check', "no");
                $current_bid_value = $product->get_uwa_auction_current_bid();
                $buy_now_price = $product->get_regular_price();
            
                if($uwa_disable_buy_it_now == "no" && $uwa_disable_buy_it_now__bid_check == "no" ){
                    
                    $buy_now_price = $product->get_regular_price();
                     if(!empty($buy_now_price) && ($buy_now_price > 0)){                
                            ?>
                            <button type="submit" class="single_add_to_cart_button button alt">
                                <?php echo apply_filters('single_add_to_cart_text',
                                    sprintf(__( 'Buy Now %s', 'woo_ua' ), wc_price($product->get_regular_price())), $product); ?>
                            </button>
                          <?php
                        }
                }
                elseif($uwa_disable_buy_it_now == "yes" && $uwa_disable_buy_it_now__bid_check == "no" ){
                    
                     if($product->is_uwa_reserve_met() == FALSE){

                        $buy_now_price = $product->get_regular_price();
                        if(!empty($buy_now_price) && ($buy_now_price > 0)){                
                            ?>

                            <button type="submit" class="single_add_to_cart_button button alt">
                                <?php echo apply_filters('single_add_to_cart_text',
                                    sprintf(__( 'Buy Now %s', 'woo_ua' ), wc_price($product->get_regular_price())), $product); ?>
                            </button>
                            
                         <?php
                        }

                    }
                }elseif($uwa_disable_buy_it_now == "yes" && $uwa_disable_buy_it_now__bid_check == "yes" ){
                    
                    if($product->is_uwa_reserve_met() == FALSE){
                        
                        if ($current_bid_value < $buy_now_price) {
                            $buy_now_price = $product->get_regular_price();
                                if(!empty($buy_now_price) && ($buy_now_price > 0)){ ?>
                                    <button type="submit" class="single_add_to_cart_button button alt">
                                        <?php echo apply_filters('single_add_to_cart_text',
                                            sprintf(__( 'Buy Now %s', 'woo_ua' ), wc_price($product->get_regular_price())), $product); ?>
                                    </button>
                                 <?php
                                }
                            }
                        
                    }
                }elseif($uwa_disable_buy_it_now == "no" && $uwa_disable_buy_it_now__bid_check == "yes" ){
                    
                    if ($current_bid_value < $buy_now_price) {
                        $buy_now_price = $product->get_regular_price();
                            if(!empty($buy_now_price) && ($buy_now_price > 0)){ ?>
                                <button type="submit" class="single_add_to_cart_button button alt">
                                    <?php echo apply_filters('single_add_to_cart_text',
                                        sprintf(__( 'Buy Now %s', 'woo_ua' ), wc_price($product->get_regular_price())), $product); ?>
                                </button>
                             <?php
                            }
                        }
                        
                }

            } /* end of if - addon */
            
    } /* end of auction selling type */
?>

        
		
				<input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>" />
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			
			<?php do_action('woocommerce_after_add_to_cart_button'); ?>
			
		</div>
    </form>
    <?php do_action('ultimate_woocommerce_auction_after_bid_form'); ?>
    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
<?php endif; ?>