<?php

if (!defined('ABSPATH')) {
	exit;
}


/**
 * Scheduled Auctions Widget
 *
 * @class UWA_Widget_Scheduled_Auctions
 * @package Ultimate WooCommerce Auction PRO
 * @author Nitesh Singh 
 * @since 1.0 
 * @category Widgets 
 *
 */
class UWA_Widget_Scheduled_Auctions extends WP_Widget {
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_uwa_scheduled_auctions';
		$this->widget_description = __( 'Display a list of Future Auctions from your store.', 'woo_ua' );
		$this->widget_id          = 'woocommerce_uwa_scheduled_auctions';
		$this->widget_name        = __( 'UWA Future Auctions', 'woo_ua' );
		
		/* Widget settings. */
		$uwa_widget_arg = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );
		parent::__construct('uwa_scheduled_auctions', $this->widget_name, $uwa_widget_arg);		
		
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );		
	}
	
	/**
	 * Flush the widget cache
	 *
	 */
	function flush_widget_cache() {		
		wp_cache_delete('widget_uwa_scheduled_auctions', 'widget');
	}

	/**
	 * Output widget
	 *
	 * @see WP_Widget
	 * @param array $args     Arguments
	 * @param array $instance Widget instance
	 *
	 */	
	function widget($args, $instance) {
		global $woocommerce;
		global $wpdb;		
		$cache = wp_cache_get('widget_uwa_scheduled_auctions', 'widget');		

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Future Auctions', 'woo_ua' ) : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 10 )
			$number = 10;

	    $query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product');
	    $query_args['meta_query'] = array();
	    $query_args['meta_query'][]    = $woocommerce->query->stock_status_meta_query();
        $query_args['meta_query'][]= array(	'key'  => 'woo_ua_auction_closed',	'compare' => 'NOT EXISTS');
        $query_args['meta_query'][]= array('key' => 'woo_ua_auction_started', 'value' => 0);	
	    $query_args['meta_query']      = array_filter( $query_args['meta_query'] );		
		$query_args['tax_query']       = array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')); 
		$query_args['auction_arhive']  = TRUE;
		$query_args['meta_key'] = 'woo_ua_auction_start_date';
		$query_args['orderby']  = 'meta_value';
		$query_args['order']    = 'ASC';
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
			$starting_time = $product->get_uwa_seconds_to_start_auction();
			$product_id = $product->get_id();
			$auction_image = ( has_post_thumbnail() ? get_the_post_thumbnail( $uwa_query->post->ID, 'shop_thumbnail' ) : wc_placeholder_img ( 'shop_thumbnail' ) );
			$starting_time  =  wp_date('Y-m-d H:i:s',$product->get_uwa_seconds_to_start_auction(),get_uwa_wp_timezone());
			$uwa_time_zone =  (array)wp_timezone();
			$sinceday  =  wp_date('M j, Y H:i:s O',time(),get_uwa_wp_timezone());
			?>
			<li>
			<script>
			var servertime='<?php echo $sinceday;?>';
			</script>
			<a href="<?php echo get_permalink();?>"><?php echo $auction_image;?><?php echo get_the_title();?></a>			
			<?php if($uwa_hide_time !=1) { 
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
			<?php echo $product->get_price_html();?>
			</li>
			<?php
			}
		} else { ?>
			<li><?php _e('Future auction not found', 'woo_ua');?></li>
			
		<?php }
		echo '</ul>';
		echo $after_widget;		
		wp_reset_postdata();		
		$content = ob_get_clean();
		if ( isset( $args['widget_id'] ) ) $cache[$args['widget_id']] = $content;
		echo $content;
		wp_cache_set('widget_uwa_scheduled_auctions', $cache, 'widget');
		
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
		if ( isset($alloptions['widget_uwa_scheduled_auctions']) ) delete_option('widget_uwa_scheduled_auctions');

		return $instance;
	}

	/**
	 * Form function
	 *
	 * @see WP_Widget->form
	 * @param array $instance
	 * @return void
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
		<label for="<?php echo $this->get_field_id('uwa_hide_time'); ?>"><?php _e( 'Hide time left', 'woo_ua' ); ?></label></p>

        <?php
	}
}