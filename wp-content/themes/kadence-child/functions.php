<?php
/**
 * Enqueue child styles.
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'product-cat-theme', get_stylesheet_directory_uri() . '/css/product_cat.css');
	wp_enqueue_style( 'header-theme', get_stylesheet_directory_uri() . '/css/header.css');
	wp_enqueue_style( 'auction-detail-theme', get_stylesheet_directory_uri() . '/auction-detail.css');
	wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri().'/js/custom.js', array('jquery'), time(), true);
    wp_localize_script(
        'custom-script',
        'opt',
        array('ajaxUrl' => admin_url('admin-ajax.php'),
        'noResults' => esc_html__('No data found', 'textdomain'),
        'home_url' => home_url(),
                )
    );
}	

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' ); // Remove the // from the beginning of this line if you want the child theme style.css file to load on the front end of your site.

// custom css and js
add_action('admin_enqueue_scripts', 'enqueue_script_in_admin');

function enqueue_script_in_admin($hook) {
	global $parent_file;
    if ( 'edit.php?post_type=product' == $parent_file ) {
		wp_enqueue_script('product-admin-custom', get_stylesheet_directory_uri() . '/js/product-admin.js');
        return;
    }else{
		return;
	}
}

/**
 * Add custom functions here
 */

 // Repeater - Youtube or Vimeo Video Link Field
add_filter( 'gform_form_post_get_meta_12', 'sl_add_video_link_field' );
function sl_add_video_link_field( $form ) {
    $link = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => 1001,
        'formId' => $form['id'],
        'label'  => 'Add link to YouTube/Vimeo video',
        'pageNumber'  => 4,
    ) );
 
    $repeater = GF_Fields::create( array(
        'type'             => 'repeater',
        'id'               => 1000,
        'formId'           => $form['id'],
        // 'label'            => 'Add link to YouTube/Vimeo video',
        //'addButtonText'    => 'Add', // Optional
        //'removeButtonText' => 'Remove', // Optional
        //'maxItems'         => 3, // Optional
        'pageNumber'       => 4, // Ensure this is correct
        'fields'           => array( $link ), // Add the fields here.
    ) );
 
    //$form['fields'][] = $repeater;
	
	array_splice( $form['fields'], 78, 0, array( $repeater ) );
 
    return $form;
}

add_filter( 'gform_form_update_meta_12', 'sl_remove_video_link_field', 10, 3 );
function sl_remove_video_link_field( $form_meta, $form_id, $meta_name ) {
    if ( $meta_name == 'display_meta' ) {
        $form_meta['fields'] = wp_list_filter( $form_meta['fields'], array( 'id' => 1000 ), 'NOT' );
    }
 
    return $form_meta;
}

add_filter( 'gform_required_legend', '__return_empty_string' );

add_filter("wpdiscuz_comment_author", function ($authorName, $comment) {
	$user_data = get_userdata( $comment->user_id );
	$authorName = $user_data->user_login;
	
	return $authorName;
}, 10, 2);

add_action( 'gform_after_submission_12', 'submit_listing_submission', 10, 2 );
function submit_listing_submission( $entry, $form ) {
	$func_arr = array(
		"function"			=> "after_submission",
		"entry"				=> $entry,
		"form"				=> $form
	);
	
	submit_listing( $func_arr );
}

function submit_listing( $func_arr ) {
    
    $form 	= $func_arr['form'];
	$entry 	= $func_arr['entry'];
	
	$entry_id = $entry['id'];
	
	global $wpdb;
	
	$user_email = rgar( $entry, '35' );

	$wp_user = get_user_by( 'email', $user_email );
	$auther_id = $wp_user->ID;

    $submitListing = [];

	
	$submitListing['private_party_or_dealer'] = rgar( $entry, '10' );
    $submitListing['is_the_title_in_your_name'] = rgar( $entry, '11' );

    $submitListing['street_address'] = rgar( $entry, '51' );
    $submitListing['suite_apartment_number'] = rgar( $entry, '52' );
    $submitListing['city'] = rgar( $entry, '53' );
    $submitListing['state'] = rgar( $entry, '54' );
    $submitListing['zip_code'] = rgar( $entry, '56' );
    $submitListing['contact_number'] = rgar( $entry, '57' );

    $submitListing['referred_status'] = rgar( $entry, '13' );
    
	$submitListing['referred_firstname'] = rgar( $entry, '17' );
    $submitListing['referred_lastname'] = rgar( $entry, '18' );
    $submitListing['referred_email'] = rgar( $entry, '20' );
    $submitListing['referred_phone'] = rgar( $entry, '21' );

    $submitListing['how_you_hear_about_vinhammer'] = rgar( $entry, '22' );
    $submitListing['how_you_hear_about_vinhammer_others'] = rgar( $entry, '23' );

    $submitListing['pricing'] = rgar( $entry, '42' );
    $submitListing['like_to_add_a_reserve'] = rgar( $entry, '25' );
    $submitListing['reserve_price'] = rgar( $entry, '43' );
	
	$submitListing['vin'] = strtoupper( rgar( $entry, '45' ) );
    $submitListing['make'] = rgar( $entry, '46' );
	$submitListing['model'] = rgar( $entry, '47' );
	$submitListing['year'] = rgar( $entry, '49' );
    $submitListing['indicated_mileage'] = rgar( $entry, '50' );
    $submitListing['true_mileage'] = rgar( $entry, '59' );
    $submitListing['actual_mileage'] = rgar( $entry, '60' );
    $submitListing['car_transmission'] = rgar( $entry, '61' );
    $submitListing['body_style'] = rgar( $entry, '62' );
    $submitListing['driveline'] = rgar( $entry, '63' );
    $submitListing['fuel_type'] = rgar( $entry, '64' );
    $submitListing['engine_size'] = rgar( $entry, '65' );
    $submitListing['exterior_color'] = rgar( $entry, '66' );
    $submitListing['interior_color'] = rgar( $entry, '67' );

	$features_field = GFFormsModel::get_field( $form, 69 );
	$features_field_value = is_object( $features_field ) ? $features_field->get_value_export( $entry ) : '';
	$submitListing['additional_features'] = preg_split("/\r\n|\n|\r|, |,/", $features_field_value);
	$submitListing['additional_unique_features'] = rgar( $entry, '70' );

    $submitListing['vehicle_history'] = rgar( $entry, '75' );

    $submitListing['sale_status'] = rgar( $entry, '76' );
    $submitListing['sale_status_description'] = rgar( $entry, '77' );

    $submitListing['services_status'] = rgar( $entry, '79' );
    $submitListing['services_status_description'] = rgar( $entry, '80' );
    
	$submitListing['modifications_status'] = rgar( $entry, '81' );
	$submitListing['modifications_status_description'] = rgar( $entry, '82' );
    
    $submitListing['pain_body_status'] = rgar( $entry, '83' );
    $submitListing['pain_body_status_description'] = rgar( $entry, '84' );
	
    $submitListing['known_issue'] = rgar( $entry, '86' );
    $submitListing['known_issue_description'] = rgar( $entry, '87' );
	
    $submitListing['accident_status'] = rgar( $entry, '88' );
    $submitListing['accident_status_description'] = rgar( $entry, '89' );
	
    $submitListing['rusts_status'] = rgar( $entry, '90' );
    $submitListing['rusts_description'] = rgar( $entry, '91' );

    $submitListing['addition_info'] = rgar( $entry, '93' );

    $photography_field = GFFormsModel::get_field( $form, 94 );
	$photography_field_value = is_object( $photography_field ) ? $photography_field->get_value_export( $entry ) : '';
	$submitListing['need_professional_photography'] = preg_split("/\r\n|\n|\r|, |,/", $photography_field_value);
    

	$photo_fields_data_arr = array(
		array(
			"id"		=> '98',
			"tag"		=> 'featured_videos'
		),
        array(
            "id"		=> '102',
            "tag"		=> 'interior_photos'
        ),
		array(
			"id"		=> '104',
			"tag"		=> 'exterior_photos'
		),
		array(
			"id"		=> '106',
			"tag"		=> 'engine_photos'
		),
		array(
			"id"		=> '108',
			"tag"		=> 'undercarriage_photos'
		),
		array(
			"id"		=> '110',
			"tag"		=> 'other_photos'
		)
	);
	
	$photos_data_arr = [
        'featured_videos' 		=> [],
        'interior_photos'		=> [],
        'exterior_photos' 		=> [],
        'engine_photos' 		=> [],
        'undercarriage_photos' 	=> [],
        'other_photos' 			=> [],
    ];
	
	foreach ($photo_fields_data_arr as $photo_field_data) {
		$photo_id 	= $photo_field_data["id"];
		$photo_tag	= $photo_field_data["tag"];
		
		$photo_field = GFAPI::get_field( $form, $photo_id );
		$photo_files = json_decode( rgar( $entry, $photo_id ) );
		foreach ($photo_files as $photo_file) {
			$attachment_id = upload_file_by_url($photo_file);
			if($photo_tag == 'featured_videos'){
				$attachment_url = wp_get_attachment_url($attachment_id);
				array_push( $photos_data_arr[$photo_tag],  ["url" => $attachment_url]);
			}else{
				array_push( $photos_data_arr[$photo_tag],  $attachment_id);
			}
		
		}
	}

    $submitListing['photos_data'] = $photos_data_arr;

    //  Videos - Youtube/Vimeo Links
	$field_rep_video_links = rgar( $entry, '1000' );
	$links_arr_video_links = array();
	foreach ($field_rep_video_links as $item) {
		if ($item['1001']) {
			preg_match('~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)~', $item['1001'], $match_youtube);
			preg_match('~(?:https?://)?(?:www.)?(?:vimeo.com)/?([^\s]+)~', $item['1001'], $match_vimeo);
			
			if ($match_youtube) {
				array_push($links_arr_video_links, array( "url_type" => "youtube", "url" => "https://youtube.com/watch?v=" . $match_youtube[1] ));
			}
			else if ($match_vimeo) {
				array_push($links_arr_video_links, array( "url_type" => "vimeo", "url" => "https://vimeo.com/" . $match_vimeo[1] ));
			}
			else {
				// Invalid Youtube or Vimeo Video Link
			}
		}
	}

    $submitListing['youtube_vimeo_links'] = $links_arr_video_links;

    $auction_title = $submitListing['year'] . ' ' . $submitListing['make'] . ' ' . $submitListing['model'];
    
    $auction_data = array(
        'post_author'  => $auther_id,
        'post_status'  => "draft",
        'post_title'   => $auction_title,
        'post_parent'  => '',
        'post_type'    => "product",
    );

	// Remove Gravity Folders Media Start
		$table_gf_entry_meta = $wpdb->prefix . 'gf_entry_meta';

		foreach ($photo_fields_data_arr as $tmp_pht) {
			$fu_id = $tmp_pht['id'];
			$sql_uploaded_files = $wpdb->prepare(
				"
				SELECT meta_value
				FROM $table_gf_entry_meta
				WHERE meta_key = %d AND entry_id = %d
				",
				$fu_id,
				$entry_id
			);

			$uploaded_files = $wpdb->get_var( $sql_uploaded_files );
			$uploaded_files = json_decode($uploaded_files);

			if (count($uploaded_files) > 0) {
				foreach ($uploaded_files as $uploaded_file) {
					$uploaded_file_url = explode('uploads', $uploaded_file);
					$uploaded_file_name = end($uploaded_file_url);

					$upload_dir = wp_upload_dir();

					$uploaded_file_full_path = $upload_dir['basedir'] . $uploaded_file_name;

					wp_delete_file( $uploaded_file_full_path );
				}
			}
		}
	// Remove Gravity Folders Media End
    
    $auction_id = wp_insert_post($auction_data, $wp_error);
    
    if ( $auction_id ) {
        $product_type = 'auction'; // <== Here define your product type slug
        $class_name   = WC_Product_Factory::get_product_classname( $auction_id, $product_type );
        
        // If the product class exist for the defined product type
        if( !empty($class_name) && class_exists( $class_name ) ) {
            $product = new $class_name($auction_id); // Get an empty instance of a grouped product Object
        }
        // For a custom product class (you may have to define the custom class name)
        else {
            $class_name = 'WC_Product_custom'; // <== Here define the Class name of your custom product type
        
            if( class_exists( $class_name ) ) {
                $product = new $class_name($auction_id); // Get an empty instance of a custom class product Object
            } else {
                wp_send_json_error( array( 'message' =>__('Wrong product class') ), 409 );
                return; // or exit;
            }
        }
        $product->set_description($submitListing['description']);
        $product->set_short_description($submitListing['highlights']);
        $auction_id = $product->save(); // Save to database 
        
        foreach($submitListing as $key => $val){
            if($key == 'photos_data'){
				// set_post_thumbnail($auction_id, $val['photos_data'][0]); 
                foreach ($val as $key1 => $res) {
                    update_field( $key1, $res, $auction_id );
                }
            }else{
                update_field($key, $val, $auction_id);
            }
        }

        $referred_values = array(
            'referred_firstname'    =>   $submitListing['referred_firstname'], //THE 1st PART MATCHES YOUR FIELD NAMES, THE 2nd IS THE VALUE YOU WANT
            'referred_lastname'     =>   $submitListing['referred_lastname'],
            'referred_email'     =>   $submitListing['referred_email'],
            'referred_phone'     =>   $submitListing['referred_phone'],
        );
		update_field( 'referred_by', $referred_values, $auction_id );

		$address_values = array(
            'street_address'			=>   $submitListing['street_address'], //THE 1st PART MATCHES YOUR FIELD NAMES, THE 2nd IS THE VALUE YOU WANT
            'suite_apartment_number'    =>   $submitListing['suite_apartment_number'],
            'city'						=>   $submitListing['city'],
            'state'     				=>   $submitListing['state'],
            'zip_code'     				=>   $submitListing['zip_code'],
            'contact_number'     		=>   $submitListing['contact_number'],
        );
        update_field( 'city_state', $address_values, $auction_id );
    } 
}

function upload_file_by_url( $file_url ) {

	// it allows us to use download_url() and wp_handle_sideload() functions
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// download to temp dir
	$temp_file = download_url( $file_url );

	if( is_wp_error( $temp_file ) ) {
		return false;
	}

	// move the temp file into the uploads directory
	$file = array(
		'name'     => basename( $file_url ),
		'type'     => mime_content_type( $temp_file ),
		'tmp_name' => $temp_file,
		'size'     => filesize( $temp_file ),
	);
	$sideload = wp_handle_sideload(
		$file,
		array(
			'test_form'   => false // no needs to check 'action' parameter
		)
	);

	if( ! empty( $sideload[ 'error' ] ) ) {
		// you may return error message if you want
		return false;
	}

	// it is time to add our uploaded image into WordPress media library
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => $sideload[ 'url' ],
			'post_mime_type' => $sideload[ 'type' ],
			'post_title'     => basename( $sideload[ 'file' ] ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$sideload[ 'file' ]
	);

	if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return false;
	}

	if($sideload[ 'type'] == 'video/mp4' || $sideload[ 'type'] == 'video/webm'){
		return $attachment_id;
	}
	// update medatata, regenerate image sizes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
	);

	return $attachment_id;

}

function getCategoryThumbnail($images, $category) {
	$thumbnail = get_site_url() . "/wp-content/uploads/2023/02/listing-default-img.png";
	// var_dump($images, $category);
	foreach($images as $key => $image) {
		if($key == $category){
			if ($category == "video") {
				foreach ($image as $img) {
					preg_match('~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)~', $img, $match_youtube);
					preg_match('~(?:https?://)?(?:www.)?(?:vimeo.com)/?([^\s]+)~', $img, $match_vimeo);
					if ($match_youtube) {
						$thumbnail = "https://img.youtube.com/vi/" . $match_youtube[1] . "/hqdefault.jpg";
					}
					else if ($match_vimeo) {
						$thumbnail = getVimeoThumbnail($match_vimeo[1]);
					}
				}
			}
			else {
				$thumbnail = $image[0];
			}
		}
	}
	
	return $thumbnail;
}

function getListingThumbnail($listing_id) {
	$thumbnail = get_site_url() . "/wp-content/uploads/2023/02/listing-default-img.png";
	
	$interior_photos 		= get_field('interior_photos', $listing_id);
	$exterior_photos 		= get_field('exterior_photos', $listing_id);
	$engine_photos 			= get_field('engine_photos', $listing_id);
	$undercarriage_photos 	= get_field('undercarriage_photos', $listing_id);
	$other_photos 			= get_field('other_photos', $listing_id);

	if(!empty($interior_photos) && count($interior_photos) > 0){
		return $thumbnail = $interior_photos[0];
	}
	if(!empty($exterior_photos) && count($exterior_photos) > 0){
		return $thumbnail = $exterior_photos[0];
	}
	if(!empty($engine_photos) && count($engine_photos) > 0){
		return $thumbnail = $engine_photos[0];
	}
	if(!empty($$undercarriage_photos) && count($$undercarriage_photos) > 0){
		return $thumbnail = $$undercarriage_photos[0];
	}
	if(!empty($other_photos) && count($other_photos) > 0){
		return $thumbnail = $other_photos[0];
	}
	
	return $thumbnail;
}

function getVimeoThumbnail($videoID) {
	$curl = curl_init();
	
	$api_url = 'http://vimeo.com/api/v2/video/' . $videoID . '.json';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $api_url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	
	return $response[0]->thumbnail_large;
}

function woocommerce_product_loop_start() { echo '<ul class="products all_products products-ul">';  }


function admin_default_page() {
	$redirect_path = $_GET['redirect_to'];
	if(!empty($redirect_path)){
		return $redirect_path;
	}else{
		return home_url();
	}
}
  
add_filter('login_redirect', 'admin_default_page');


function auction_filter_func($atts = []){
	extract(shortcode_atts(array(
		'expired' => false,
		'hidescheduled' => 'no',
		), $atts));

	if(!$expired){
		$meta_query[] = array(
			'key'     => 'woo_ua_auction_closed',
			'compare' => 'NOT EXISTS',
		);

		if($hidescheduled == 'yes'){
			$meta_query[] = array(
				'key'     => 'woo_ua_auction_started',
				'compare' => 'NOT EXISTS',
			);
		}
	}else{
		$meta_query[] = array(
			'key'     => 'woo_ua_auction_closed',
			'compare' => 'EXISTS',
		);
	}

	$args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'orderby' => 'id',
		'order' => 'desc',
		'meta_query' => $meta_query,
		'posts_per_page' => -1,   // -1 is default for all results to display
		'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 
			'terms' => 'auction')),
		'auction_arhive' => TRUE,
	);
	$filters = [
		'body_style' => [],
		'car_transmission' => [],
		'year' => [],
		'mileage' => [],
		'pricing' => [],
	];

	$products = new WP_Query( $args );
	while ( $products->have_posts() ) : $products->the_post();
		global $product;

		array_push($filters['body_style'], get_field('body_style'));
		array_push($filters['car_transmission'], get_field('car_transmission')['label']);
		array_push($filters['year'], get_field('year'));
		array_push($filters['mileage'], get_field('indicated_mileage'));
		array_push($filters['pricing'], $product->get_uwa_auction_start_price());

	endwhile;
	
	$filters['body_style'] = array_count_values($filters['body_style']);
	$filters['car_transmission'] = array_count_values($filters['car_transmission']);

	sort($filters['mileage']);
	sort($filters['year']);
	sort($filters['pricing']);

	$start_mileage = (int)current($filters['mileage']); 
	$end_mileage = (int)end($filters['mileage']);
	$new_mileage = [[$start_mileage, $start_mileage + 3]];
	
	for ($i = $start_mileage; $i <= $end_mileage ; $i++) {
		if($i == $end_mileage){
			continue;
		}
		$tmp_last_val = (($i+3) > $end_mileage) ? $end_mileage : $i + 3;
		if(end($new_mileage)[1] == $i){
			array_push($new_mileage, [$i, $tmp_last_val]);
		}
	}

	ob_start();
	?>
	<div class="filter_head d-flex justify-content-between align-items-center mb-1">
		<h5 class="f_heading top-heading d-flex gap-10 align-items-center"><img src="<?php echo get_stylesheet_directory_uri()?>/images/filter-icon.png"/> Filter By</h5>
		<a href="javascript:void(0)" class="clear_filter f_heading top-heading">Clear Filter</a>
	</div>
	<div class="main_filters gform_wrapper gravity-theme filter-row">
		<div class="filter-item faq-drawer body_style_container">
			<input class="faq-drawer__trigger" id="faq-drawer" type="checkbox" />
			<label class="faq-drawer__title f_heading" for="faq-drawer">Body Style</label>
			<div class="faq-drawer__content-wrapper">
				<?php
					$body_styles = acf_get_field('body_style');
					foreach($body_styles["choices"] as $key => $body_style){
						$b_checked = (isset($_GET['body_style']) && $_GET['body_style'] == $body_style) ? 'checked' : '';
						echo '
						<div class="d-flex justify-content-between align-items-center mb-1">
							<div class="gchoice">
								<input type="checkbox" id="body_style'.$key.'" name="body_style" class="filter_change gfield-choice-input" '.$b_checked.' value="'.$body_style.'">
								<label for="body_style'.$key.'">
									<span>'.$body_style.'</span>
								</label>
							</div>
							<span class="count">'.($filters['body_style'][$body_style] ? $filters['body_style'][$body_style] : 0).'</span>
						</div>
						';
					}
				?>
			</div>
		</div>
		<div class="filter-item faq-drawer transmission_container">
			<input class="faq-drawer__trigger" id="faq-drawer-2" type="checkbox" />
			<label class="faq-drawer__title f_heading" for="faq-drawer-2">Transmission</label>
			<div class="faq-drawer__content-wrapper">
				<?php
					$car_transmission = acf_get_field('car_transmission');
					foreach($car_transmission["choices"] as $key => $transmission){
						$c_checked = (isset($_GET['car_transmission']) && $_GET['car_transmission'] == $transmission) ? 'checked' : '';
						echo '
						<div class="d-flex justify-content-between align-items-center mb-1">
							<div class="gchoice">
								<input type="checkbox" id="transmission'.$key.'" name="body_style" class="filter_change gfield-choice-input" '.$c_checked.' value="'.$transmission.'">
								<label for="transmission'.$key.'">
									<span>'.$transmission.'</span>
								</label>
							</div>		
							<span class="count">'.($filters['car_transmission'][$transmission] ? $filters['car_transmission'][$transmission] : 0) .'</span>
						</div>
						';
					}
				?>
			</div>
		</div>
		<div class="filter-item faq-drawer range_container">
			<?php
				$min_year = current($filters['year']);
				$max_year = end($filters['year']);
			?>
			<input class="faq-drawer__trigger" id="faq-drawer-3" type="checkbox" />
			<label class="faq-drawer__title f_heading" for="faq-drawer-3">Year</label>
			<div class="faq-drawer__content-wrapper">
				<section class="range-slider d-flex justify-content-between">
					<span class="full-range"></span>
					<span class="incl-range"></span>
					<span class="output outputOne"></span>
					<input name="min_year" value="<?php echo isset($_GET['min_year']) ? $_GET['min_year'] : $min_year; ?>" min="<?php echo $min_year; ?>" max="<?php echo $max_year; ?>" step="1" type="range" class="min-range filter_change">
					<input name="max_year" value="<?php echo $max_year; ?>" min="<?php echo $min_year; ?>" max="<?php echo $max_year; ?>" step="1" type="range" class="max-range filter_change">
					<span class="output outputTwo"></span>
				</section>
			</div>
		</div>
		<div class="filter-item faq-drawer mileage_container">
			<input class="faq-drawer__trigger" id="faq-drawer-4" type="checkbox" />
			<label class="faq-drawer__title f_heading" for="faq-drawer-4">Mileage</label>
			<div class="faq-drawer__content-wrapper">
				<?php
					foreach($new_mileage as $key => $mileage){
						$m_checked =  (isset($_GET['mileage']) && $_GET['mileage'] == ($mileage[0].'-'.$mileage[1])) ? 'checked' : '';
						echo '
						<div class="d-flex justify-content-between align-items-center mb-1">
							<div class="gchoice">
								<input type="checkbox" id="transmission'.$key.'" name="body_style" class="filter_change gfield-choice-input" '.$m_checked.' value="'.$mileage[0].'-'.$mileage[1].'">
								<label for="transmission'.$key.'">
									<span>'.$mileage[0].' miles - '.$mileage[1].' miles</span>
								</label>
							</div>
						</div>
						';
					}
				?>
			</div>
		</div>
		<div class="filter-item faq-drawer range_container">
			<?php
				$auction_min_price = current($filters['pricing']);
				$auction_max_price = end($filters['pricing']);
			?>
			<input class="faq-drawer__trigger" id="faq-drawer-5" type="checkbox" />
			<label class="faq-drawer__title f_heading" for="faq-drawer-5">Price</label>
			<div class="faq-drawer__content-wrapper">
				<section class="range-slider d-flex justify-content-between">
					<span class="full-range"></span>
					<span class="incl-range"></span>
					<span class="output outputOne"></span>
					<input name="min_price" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : $auction_min_price; ?>" min="<?php echo $auction_min_price; ?>" max="<?php echo $auction_max_price; ?>" step="1" type="range" class="min-range filter_change">
					<input name="max_price" value="<?php echo $auction_max_price; ?>" min="<?php echo $auction_min_price; ?>" max="<?php echo $auction_max_price; ?>" step="1" type="range" class="max-range filter_change">
					<span class="output outputTwo"></span>
				</section>
			</div>
		</div>
		<?php if($expired){ ?>
			<input type="hidden" name="expired" value="1">
		<?php } ?>

	</div>
<?php
	return '<form id="filter-form" class="search_filter">' . ob_get_clean() . '</form>';
}
add_shortcode('auction_filter', 'auction_filter_func');


function auction_search_field_func(){
ob_start();
?>
<div class="search-wrap">
	<i class="fa fa-search"></i>
	<input type="text" name="auction_search" placeholder="Search auctions..."/>
</div>
<div class="search-wrap sort-wrapper">
	<select name="sort" id="sort" class="sort">
		<option value="">Sort by</option>
		<option value="newly_listed">Newly Listed</option>
		<option value="ending_soon">Ending Soon</option>
		<option value="no_reserve">No Reserve</option>
	</select>
</div>
<?php
	return '<form id="auction-search-form" class="search_auction mt-2 mb-0">' . ob_get_clean() . '</form>';
}
add_shortcode('auction_search_field', 'auction_search_field_func');

function auction_list_func($atts = []){
	global $woocommerce_loop, $woocommerce;
	
	$filter_request 				= isset($_POST) ? json_decode(stripslashes($_POST['filter_request'])) : null;
	$filter['body_style'] 			= isset($_POST) ? json_decode(stripslashes($_POST['body_style'])) : null;
    $filter['car_transmission'] 	= isset($_POST) ? json_decode(stripslashes($_POST['car_transmission'])) : null;
    $filter['mileage'] 				= isset($_POST) ? json_decode(stripslashes($_POST['mileage'])) : null;
    $filter['min_year'] 			= isset($_POST) ? json_decode(stripslashes($_POST['min_year'])) : null;
    $filter['max_year']				= isset($_POST) ? json_decode(stripslashes($_POST['max_year'])) : null;
    $filter['min_price'] 			= isset($_POST) ? json_decode(stripslashes($_POST['min_price'])) : null;
    $filter['max_price']			= isset($_POST) ? json_decode(stripslashes($_POST['max_price'])) : null;
    $filter['expired']				= isset($_POST) ? json_decode(stripslashes($_POST['expired'])) : null;
    $auction_search					= isset($_POST) ? json_decode(stripslashes($_POST['auction_search'])) : null;

	extract(shortcode_atts(array(
		'category'  => '',
		'columns' 	=> '4',
		'orderby'   => 'id',
		'order'     => 'desc',		  	
		'hidescheduled' => 'no',	
		'paginate' => 'false',
		'limit' => 10,
		'expired' => false
		), $atts));

	$limit = (int)$limit;  // don't remove

	if(!$expired && !$filter['expired']){
		$meta_query[] = array(
			'key'     => 'woo_ua_auction_closed',
			'compare' => 'NOT EXISTS',
		);

		if($hidescheduled == 'yes'){
			$meta_query[] = array(
				'key'     => 'woo_ua_auction_started',
				'compare' => 'NOT EXISTS',
			);
		}
	}else{
		$meta_query[] = array(
			'key'     => 'woo_ua_auction_closed',
			'compare' => 'EXISTS',
		);
	}

	if(isset($_POST)){
		if($filter['min_year'] && $filter['max_year']){
			$meta_query[] = array(
				'key'       => 'year',
				'value'     => array( (int)$filter['min_year'], (int)$filter['max_year'] ),
				'compare'   => 'BETWEEN',
			);
		}
		if($filter['min_price'] && $filter['max_price']){
			$meta_query[] = array(
				'key'       => 'woo_ua_opening_price',
				'value'     => array( (int)$filter['min_price'], (int)$filter['max_price'] ),
				'compare'   => 'BETWEEN',
				'type'    	=> 'numeric',
			);
		}
		if($filter['body_style']){
			$meta_query[] = array(
				'key'       => 'body_style',
				'value'     => $filter['body_style'],
				'compare'   => 'IN',
			);
		}
		if($filter['car_transmission']){
			$meta_query[] = array(
				'key'       => 'car_transmission',
				'value'     => $filter['car_transmission'],
				'compare'   => 'IN',
			);
		}
		if($filter['mileage']){
			$meta_query[] = array(
				'key'       => 'indicated_mileage',
				'value'     => array(current($filter['mileage']), end($filter['mileage'])),
				'compare'   => 'BETWEEN',
				'type'    	=> 'numeric',
			);
		}
	}
	
	$args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'orderby' => $orderby,
		'order' => $order,
		'meta_query' => $meta_query,
		//'posts_per_page' => -1,   // -1 is default for all results to display
		'posts_per_page' => $limit,
		'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 
			'terms' => 'auction')),
		'auction_arhive' => TRUE,			
	);

	if($auction_search && !empty($auction_search)){
		$args['s'] = $auction_search;
	}

	if(!empty($category)){	
		$args['product_cat']  =  $atts['category'];
	}
	
	$product_visibility_terms  = wc_get_product_visibility_term_ids();
	$product_visibility_not_in = $product_visibility_terms['exclude-from-catalog'];
	if ( ! empty( $product_visibility_not_in ) ) {
				$tax_query[] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_not_in,
					'operator' => 'NOT IN',
				);
	}

	/* skus */
	if(isset($atts['skus'])){
		$skus = explode(',', $atts['skus']);
		$skus = array_map('trim', $skus);
		$args['meta_query'][] = array(
			'key' 		=> '_sku',
			'value' 	=> $skus,
			'compare' 	=> 'IN'
		);
	}

	/* ids */
	if(isset($atts['ids'])){
		$ids = explode(',', $atts['ids']);
		$ids = array_map('trim', $ids);
		$args['post__in'] = $ids;
	}
	
	/* Set Pagination Variable */
	if($paginate === "true"){
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$args['paged'] = $paged;
		//$woocommerce_loop['paged'] = $paged;
	}
	ob_start();
	$products = new WP_Query( $args );
	
	// $woocommerce_loop['columns'] = $columns;

	if ( $products->have_posts() ) : ?>

		<?php
		
			/* Pagination Top Text */				
			if($paginate === "true" && ($limit >= 1 || $limit === -1 ))  {				
				$args_toptext = array(
					'total'    => $products->found_posts,
					//'per_page' => $products->get( 'posts_per_page' ),
					'per_page' => $limit,
					'current'  => max(1, get_query_var('paged')),
				);
				wc_get_template( 'loop/result-count.php', $args_toptext );
			}
		?>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>
	<?php else : ?>

		<?php wc_get_template( 'loop/no-products-found.php' ); ?>

	<?php endif;

	wp_reset_postdata();


	/* ---  Display Pagination ---  */

	if ( $paginate === "true" && $limit >= 1  && $limit < $products->found_posts ) { // don't change condition else design conflicts
	
		$big = 999999999;
		$current = max(1, get_query_var('paged'));
		$total   = $products->max_num_pages;
		$base    = esc_url_raw( str_replace( $big, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( $big, false ))));
		$format  = '?paged=%#%';			

		if ( $total <= 1 ) {
			return;
		}
		
		$display_data = '<nav class="woocommerce-pagination">';
		$display_data .= paginate_links( 
			apply_filters( 'woocommerce_pagination_args', 
				array( 
					'base'         => $base,
					'format'       => $format,						
					'add_args'     => false,						
					'current'      => $current,
					'total'        => $total,
					'prev_text'    => '&larr;',
					'next_text'    => '&rarr;',
					//'type'         => 'list',
					'end_size'     => 3,
					'mid_size'     => 3,
				) 
			));			
		$display_data .= '</nav>';
		echo $display_data;
		
	} /* end of if - paginate */
	// $auction_html = '<div class="product_listing">' . ob_get_clean() . '</div>';
	if($filter_request){
		echo json_encode(['html'=> ob_get_clean(), 'args'=> $args ]);
    	die();
	}else{
		return '<div class="product_listing">' . ob_get_clean() . '</div>';
	}
}
add_shortcode('auction_list', 'auction_list_func');
add_action( 'wp_ajax_product_filters_action', 'auction_list_func' );
add_action( 'wp_ajax_nopriv_product_filters_action', 'auction_list_func' );

function home_featured_action_func(){
	global $woocommerce_loop, $woocommerce;

	$args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'orderby' => 'id',
		'order' => 'desc',
		'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')),
		'auction_arhive' => TRUE,
	);

	$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
	);


	ob_start();
	$products = new WP_Query( $args );
	$woocommerce_loop['columns'] = $columns;
	if ( $products->have_posts() ) : ?>

		<div class="featured-list">

		<?php while ( $products->have_posts() ) : $products->the_post(); 
		global $product;
		$address = get_field('city_state');
		?>

			<div class="featured-product d-flex product justify-content-between align-items-center">
				<div class="auction-details">
					<h3>Featured Auction</h3>
					<div class="timer-wrapper">
						<?php echo do_shortcode('[countdown id="'. get_the_ID() .'"]') ?>
					</div>
					<h3 class="woocommerce-loop-product__title m-0"><?php the_title() ?></h3>
					<div class="tag-row d-flex align-items-center gap-10 mb-2">
						<?php if(!empty($address['city']) && !empty($address['state'])):?>
							<span class="tag"><?php echo $address['city'].', '.$address['state'] ?></span>
						<?php endif;?>
					</div>
					<div class="center-tab d-flex align-items-center mb-2">
						<div class="tag-row d-flex flex-direction-column">
							<span class="text"><?php printf(__('%s','woo_ua') , wc_price($product->get_uwa_auction_start_price()));?></span>
							<span class="tag">Opening Bid</span>
						</div>
						<div class="featured-sep"></div>
						<div class="tag-row d-flex flex-direction-column">
							<span class="text" style="color: #EC1B34;"><?php printf(__('%s','woo_ua') , wc_price($product->get_uwa_auction_current_bid()));?></span>
							<span class="tag"><?php echo count($product->uwa_auction_log_history()); ?> Bid<?php echo count($product->uwa_auction_log_history()) > 1 ? 's' : ''; ?></span>
						</div>
					</div>
					<a href="<?php echo get_the_permalink()?>" class="wp-element-button place-bid">Bid Now</a>
				</div>
				<div class="featured-img">
					<img src="<?php echo getListingThumbnail(get_the_ID()); ?>" class="auction-img" />
				</div>
			</div>

		<?php endwhile; // end of the loop. ?>

	</div>
		
	<?php else : ?>
	
		<?php wc_get_template( 'loop/no-products-found.php' ); ?>

	<?php endif;		

	wp_reset_postdata();


	return '<div class="product_listing">' . ob_get_clean() . '</div>';
}
add_shortcode('home_featured_action', 'home_featured_action_func');


function auction_home_filter_func(){
	$meta_query[] = array('key' => 'woo_ua_auction_closed', 'compare' => 'NOT EXISTS');

	$args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'orderby' => 'id',
		'order' => 'desc',
		'meta_query' => $meta_query,
		'posts_per_page' => -1,   // -1 is default for all results to display
		'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 
			'terms' => 'auction')),
		'auction_arhive' => TRUE,
	);
	$filters = [
		'body_style' => [],
		'car_transmission' => [],
		'year' => [],
		'mileage' => [],
		'pricing' => [],
	];

	$products = new WP_Query( $args );
	while ( $products->have_posts() ) : $products->the_post();
		global $product;

		array_push($filters['body_style'], get_field('body_style'));
		array_push($filters['car_transmission'], get_field('car_transmission')['label']);
		array_push($filters['year'], get_field('year'));
		array_push($filters['mileage'], get_field('indicated_mileage'));
		array_push($filters['pricing'], $product->get_uwa_auction_start_price());

	endwhile;
	
	$filters['body_style'] = array_count_values($filters['body_style']);
	$filters['car_transmission'] = array_count_values($filters['car_transmission']);

	sort($filters['mileage']);
	sort($filters['year']);
	sort($filters['pricing']);

	$start_mileage = (int)current($filters['mileage']); 
	$end_mileage = (int)end($filters['mileage']);
	$new_mileage = [[$start_mileage, $start_mileage + 3]];
	
	for ($i = $start_mileage; $i <= $end_mileage ; $i++) {
		if($i == $end_mileage){
			continue;
		}
		$tmp_last_val = (($i+3) > $end_mileage) ? $end_mileage : $i + 3;
		if(end($new_mileage)[1] == $i){
			array_push($new_mileage, [$i, $tmp_last_val]);
		}
	}
	ob_start();
?>
	<div class="select-box">
		<div class="select-rect d-flex flex-wrap justify-content-between align-items-center">
		<div class="select-wrapper first-select">
			<h5>Body Style</h5>
			<select name="body_style" value="body type">
				<option value="" disabled selected>Select</option>
				<?php
					$body_styles = acf_get_field('body_style');
					foreach($body_styles["choices"] as $body_style){
						echo '<option value="'.$body_style.'">'.$body_style.'</option>';
					}
				?>
			</select>
		</div>
		<div class="select-wrapper third-select">
			<h5>Transmission</h5>
			<select name="car_transmission" value="body type">
				<option value="" disabled selected>Select</option>
				<?php
					$car_transmission = acf_get_field('car_transmission');
					foreach($car_transmission["choices"] as $transmission){
						echo '<option value="'.$transmission.'">'.$transmission.'</option>';
					}
				?>
			</select>
		</div>
		<div class="select-wrapper fourth-select">
			<select name="min_year">
				<option value="" disabled selected>Year</option>
				<?php
					foreach($filters['year'] as $year){
						echo '<option value="'.$year.'">'.$year.'</option>';
					}
				?>
			</select>
		</div>
		<div class="select-wrapper fifth-select">
			<select name="mileage" value="body type">
				<option value="" disabled selected>Mileage</option>
				<?php
					foreach($new_mileage as $mileage){
						echo '<option value="'.$mileage[0].'-'.$mileage[1].'">'.$mileage[0].' miles - '.$mileage[1].' miles</option>';
					}
				?>
			</select>
		</div>
		<div class="select-wrapper fifth-select">
			<select name="min_price" value="body type">
				<option value="" disabled selected>Price</option>
				<?php
					foreach($filters['pricing'] as $pricing){
						echo '<option value="'.$pricing.'">'.$pricing.'</option>';
					}
				?>
			</select>
		</div>
		<div class="submit-wrapper">
			<button>
				<img src="<?php echo get_stylesheet_directory_uri() ?>/images/search.png" class="search-icon"> 
				<span>Search</span>
			</button>
		</div>
		</div>
	</div>
<?php
	return '<form id="home-filter-form" class="search_filter" action="'.site_url().'/live-auctions">' . ob_get_clean() . '</form>';
}
add_shortcode('auction_home_filter', 'auction_home_filter_func');


add_filter( 'woocommerce_account_menu_items', 'QuadLayers_remove_acc_address', 9999 );
function QuadLayers_remove_acc_address( $items ) {
	$items['uwa-auctions'] = 'Your Bids & Watchlist';
	$items['orders'] = 'Your Auctions';
	$items['messages'] = 'Messages';
	var_dump($items);die;
 	unset( $items['downloads'] );
	// $items = array(
	// 	["dashboard"]=>
	// 	string(9) "Dashboard"
	// 	["orders"]=>
	// 	string(13) "Your Auctions"
	// 	["subscriptions"]=>
	// 	string(13) "Subscriptions"
	// 	["downloads"]=>
	// 	string(9) "Downloads"
	// 	["edit-address"]=>
	// 	string(9) "Addresses"
	// 	["edit-account"]=>
	// 	string(15) "Account details"
	// 	["uwa-auctions"]=>
	// 	string(21) "Your Bids & Watchlist"
	// 	["customer-logout"]=>
	// 	string(6) "Logout"
	// 	["messages"]=>
	// 	string(8) "Messages"
	//   }
	// return $items;
}

// 1. Register new endpoint
// Note: Resave Permalinks or it will give 404 error  
function QuadLayers_add_messages_endpoint() {
    add_rewrite_endpoint( 'messages', EP_ROOT | EP_PAGES );
}  
add_action( 'init', 'QuadLayers_add_messages_endpoint' );  
// ------------------
// 2. Add new query
function QuadLayers_messages_query_vars( $vars ) {
    $vars[] = 'messages';
    return $vars;
}  
add_filter( 'query_vars', 'QuadLayers_messages_query_vars', 0 );  
// ------------------
// 3. Insert the new endpoint 

// ------------------
// 4. Add content to the new endpoint  
function QuadLayers_messages_content() {
echo '<h3>Messages</h3><p>Welcome to the messages area. As a premium customer, manage your messages tickets from here, you can submit a ticket if you have any issues with your website. We\'ll put our best to provide you with a fast and efficient solution</p>';
// echo do_shortcode( '[tickets-shortcode]' );
// echo do_shortcode( '[wpforms id="1082"]' );
}  
add_action( 'woocommerce_account_messages_endpoint', 'QuadLayers_messages_content' );