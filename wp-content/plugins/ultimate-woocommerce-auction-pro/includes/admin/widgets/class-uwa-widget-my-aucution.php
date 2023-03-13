<?php

if (!defined('ABSPATH')) {
	exit;
}


/**
 * My Auctions Widget
 *
 * @class UWA_Widget_My_Auctions
 * @package Ultimate WooCommerce Auction PRO
 * @author Nitesh Singh 
 * @since 1.0 
 * @category Widgets 
 *
 */
class UWA_Widget_My_Auctions extends WP_Widget {
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_uwa_my_auctions';
		$this->widget_description = __( 'Display a list of auctions user had bid.', 'woo_ua' );
		$this->widget_id          = 'woocommerce_uwa_my_auctions';
		$this->widget_name        = __( 'UWA My Auctions', 'woo_ua' );
		
		/* Widget settings. */
		$uwa_widget_arg = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );
		parent::__construct('uwa_my_auctions', $this->widget_name, $uwa_widget_arg);		
		
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );		
	}

	/**
	 * Flush the widget cache
	 *
	 */
	function flush_widget_cache() {		
		wp_cache_delete('widget_uwa_my_auctions', 'widget');
	}	

	/**
	 * Output widget
	 *
	 * @see WP_Widget
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 *
	 */	
	function widget($args, $instance) {
		global $woocommerce,$wpdb;	
	    $table = $wpdb->prefix."woo_ua_auction_log";	 
		$cache = wp_cache_get('widget_uwa_my_auctions', 'widget');		

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('My Auctions', 'woo_ua' ) : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		if ( ! is_user_logged_in() ) return;
			
		$user_id  = get_current_user_id();
		$auctionsids = array();
		$userauction	 = $wpdb->get_results("SELECT  DISTINCT auction_id FROM ".$table." WHERE userid = $user_id ",ARRAY_N );
		if(isset($userauction) && !empty($userauction)){
			foreach ($userauction as $auction) {
				$auctionsids []= $auction[0];				
			}
        } else{
            return;
        }

		$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
		$query_args['post__in']	= $auctionsids ;
		$query_args['meta_query'] = $woocommerce->query->get_meta_query();
		$query_args['meta_query'][] = array(	'key'  => 'woo_ua_auction_closed',	'compare' => 'NOT EXISTS');
		$query_args['tax_query'] = array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')); 
		$query_args['auction_arhive'] = TRUE; 	
		$uwa_query = new WP_Query($query_args);
		
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;	
		echo '<ul class="product_list_widget">';
		if ( $uwa_query->have_posts() ) {		
		$uwa_hide_time = empty( $instance['uwa_hide_time'] ) ? 0 : 1;		
			while ( $uwa_query->have_posts()) {
				$uwa_query->the_post();
			global $product;
			$uwa_started = $product->is_uwa_live();
			$uwa_expired = $product->is_uwa_expired();
			$product_id = $product->get_id();
			$auction_image = ( has_post_thumbnail() ? get_the_post_thumbnail( $uwa_query->post->ID, 'shop_thumbnail' ) : wc_placeholder_img ( 'shop_thumbnail' ) );
			?>
			<li>
			<a href="<?php echo get_permalink();?>"><?php echo $auction_image;?><?php echo get_the_title();?></a>			
			<?php if($uwa_hide_time !=1) { ?>
			
			<?php if(($uwa_expired === FALSE ) and ($uwa_started  === TRUE )) {
			$remaning_time = $product->get_uwa_remaining_seconds();
			$remaning_time  =  wp_date('Y-m-d H:i:s',$product->get_uwa_remaining_seconds(),get_uwa_wp_timezone());
				$uwa_time_zone =  (array)wp_timezone();
				 
				
				$auc_end_date=get_post_meta( $product_id, 'woo_ua_auction_end_date', true );
				$rem_arr=get_remaining_time_by_timezone($auc_end_date); 
				?>
			 
			<span class="uwa_time_left"><?php _e('Time left', 'woo_ua');?></span>
		 
			
			<?php
					countdown_clock(
						$end_date=$auc_end_date,
						$item_id=$product_id,
						$item_class='uwa-main-auction-product-loop uwa_auction_product_countdown '   
					);					
					?>
					
			<?php } elseif (($uwa_expired === FALSE ) and ($uwa_started  === FALSE )){ 
			$starting_time = $product->get_uwa_seconds_to_start_auction();
			$starting_time  =  wp_date('Y-m-d H:i:s',$product->get_uwa_seconds_to_start_auction(),get_uwa_wp_timezone());
			$uwa_time_zone =  (array)wp_timezone();
			 
			
			$auc_end_date=get_post_meta( $product_id, 'woo_ua_auction_start_date', true );
				$rem_arr=get_remaining_time_by_timezone($auc_end_date);
			?>		
			 

			<span class="uwa_time_left"><?php _e('Starting Time Left:', 'woo_ua'); ?></span>
			 
			
			<?php
					countdown_clock(
						$end_date=$auc_end_date,
						$item_id=$product_id,
						$item_class='uwa-main-auction-product-loop uwa_auction_product_countdown '   
					);					
					?>
			<?php } ?>
			<?php } ?>
			<?php echo $product->get_price_html();

				
					/* display winner info in live auctions */
					if(get_option('uwa_winner_live_widget') == 'yes'){
							?>
							<div class="winner-name" data-auction_id="<?php echo esc_attr( $product_id ); ?>">
								<?php
						$winner_text = $product->get_uwa_winner_text();
						if($winner_text){ ?>
							<span style="color:green;font-size:20px;"><?php echo $winner_text; ?></span>
							<?php
						}
						?>
						</div>
						<?php
					}
				?>
			</li>
				<?php
			}
			
		}else {
			?>
			<li><?php _e('My Auction not found', 'woo_ua');?></li>
			
		<?php }
		echo '</ul>';
		echo $after_widget;		
		wp_reset_postdata();		
		$content = ob_get_clean();
		if ( isset( $args['widget_id'] ) ) $cache[$args['widget_id']] = $content;
		echo $content;
		wp_cache_set('widget_uwa_my_auctions', $cache, 'widget');
		
	}


	/**
	 * Update function
	 *
	 * @see WP_Widget->update	
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 * 
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['uwa_hide_time'] = empty( $new_instance['uwa_hide_time'] ) ? 0 : 1;		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_uwa_my_auctions']) ) delete_option('widget_uwa_my_auctions');

		return $instance;
	}


	/**
	 * Form function
	 *
	 * @see WP_Widget->form	
	 * @param array $instance	
	 * 
	 */
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;		
		$uwa_hide_time = empty( $instance['uwa_hide_time'] ) ? 0 : 1;		
        ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'woo_ua' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of auctions to show:', 'woo_ua' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('uwa_hide_time') ); ?>" name="<?php echo esc_attr( $this->get_field_name('uwa_hide_time') ); ?>"<?php checked( $uwa_hide_time ); ?> />
		<label for="<?php echo $this->get_field_id('uwa_hide_time'); ?>"><?php _e( 'Hide timer', 'woo_ua' ); ?></label></p>

        <?php
	}
}