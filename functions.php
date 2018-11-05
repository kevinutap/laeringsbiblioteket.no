<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function oceanwp_child_enqueue_parent_style() {
	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );
	// Load the stylesheet
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
	
}
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );


add_action('wp_head', 'dc_implmetation',1);
function dc_implmetation(){
	if ( is_product() ){
	echo '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/">';
	$cleantitle = get_the_title( $post->ID );
	echo '<meta name="DC.Title" content="'. wp_strip_all_tags($cleantitle) .'">';
	echo '<meta name="DC.Creator.PersonalName" content="' . get_field('dc.contributor') . '">';
	echo '<meta name="DC.date" content="' . get_field('dc.year') . '">';
	$cleanabstract = get_field('dc.description');
	echo '<meta name="DC.Description" content="' . wp_strip_all_tags($cleanabstract) . '">';
	echo '<meta name="DC.Identifier" content="' . get_field('dc.identifier') . '">';
	echo '<meta name="DC.Source" content="' . get_field('dc.source') . '">';
	echo '<meta name="DC.Type" content="Text.Serial.Journal">';
	echo '<meta name="DC.Type.articleType" content="Articles">';
	}
}



add_action( 'woocommerce_after_single_product_summary', 'displayfields');
function displayfields() {
	//display dc custom fields
	$specifications_fields = get_specifications_fields();
    
	foreach ( $specifications_fields as $name => $field ){
		$dclabel = $field['label'];
		$dcname = $field['name'];
		$dcvalue = $field['value'];
		$dctype = $field['type'];
		if($dctype=='url'){
			echo '<div id="'.$dcname.'"><strong>' . $dclabel . ': </strong> <a href="' . $dcvalue . '"  target="_blank" >' . $dcvalue . ' </a></div>';
		}
		else
		{
			echo '<div id="'.$dcname.'"><strong>' . $dclabel . ': </strong>'.$dcvalue.'</div>';}
		}
	
	//display stikkord and custom taxonomy
	echo '<div class="cat" id="dc.subject">' . get_the_term_list( $post->ID, 'product_tag', '<strong>Stikkord:</strong> ', ' ', '' ) . '</div>';
	echo '<div class="cat">' . str_replace ( '<a href="'. get_site_url() . '/fag/fag/" rel="tag">Fag</a>' , "" , get_the_term_list( $post->ID, 'fag', "<strong>Utdanning:</strong> ", " ")). '</div>';
	echo '<div class="cat">' . str_replace ( '<a href="'. get_site_url() . '/teknologi/teknologi/" rel="tag">Digitale verktøy</a>' , "" , get_the_term_list( $post->ID, 'teknologi', "<strong>Digitale verktøy:</strong> ", " ")). '</div>';	
	echo '<div class="cat">' . str_replace ( '<a href="'. get_site_url() . '/utdanningsniva/utdanningsniva/" rel="tag">Utdanningsnivå</a>' , "" , get_the_term_list( $post->ID, 'utdanningsniva', "<strong>Utdanningsnivå:</strong> ", " ")). '</div>';
	echo '<div class="cat">' . str_replace ( '<a href="'. get_site_url() . '/pedagogik/pedagogikk/" rel="tag">Didaktikk</a>' , "" , get_the_term_list( $post->ID, 'pedagogik', "<strong>Didaktikk:</strong> ", " ")). '</div>';	
}

/*published date and last modified date*/
add_action('woocommerce_single_product_summary','wc_single_page_product_date',25);
function wc_single_page_product_date() {
		echo '<div id="publisert">Publikasjonsår : ' . get_field('dc.year') . ' | Innleggsdato: ' . get_the_date(). '</div>';
}


//display taxonomy on catalogue
add_action( 'ocean_after_archive_product_categories', 'displayfields2');
function displayfields2() {
	echo '<div id="fag" class="cat_result_list">' . str_replace ( '<a href="'. get_site_url() . '/fag/fag/" rel="tag">Fag</a>' , "" , get_the_term_list( $post->ID, 'fag', " ", " ")). '</div>';
}


add_action( 'woocommerce_after_shop_loop_item', 'display_date_result', 15 );
function display_date_result() {
	echo '<div id="publisert">Publikasjonsår : ' . get_field('dc.year') . ' | Innleggsdato: ' . get_the_date(). '</div>';
}


// CHANGE RELATED PRODUCTS TEXT
add_filter( 'gettext', 'my_text_strings', 20, 3 );
function my_text_strings( $translated_text, $text, $domain ) {

	switch ( $translated_text ) {
        case 'No products were found matching your selection.' :
            $translated_text = __( 'Ingen treff.', 'woocommerce' );
            break;
		case 'Related products' :
            $translated_text = __( 'Relaterte artikler...', 'woocommerce' );
            break;
		case 'Description' :
            $translated_text = __( 'Beskrivelse', 'woocommerce' );
            break;
		case 'Product Name' :
            $translated_text = __( 'Artikkel', 'woocommerce' );
            break;
		case 'Read more' :
            $translated_text = __( 'Les mer', 'woocommerce' );
            break;
		case 'Search' :
            $translated_text = __( 'Søk', 'woocommerce' );
            break;
		case 'Date Added' :
            $translated_text = __( 'Innlagt dato', '' );
            break;
		case 'Product name' :
            $translated_text = __( 'Title', '' );
            break;
		case 'Products' :
            $translated_text = __( 'Articles', '' );
            break;
		case 'Product' :
            $translated_text = __( 'Article', '' );
            break;
		case 'Actions' :
            $translated_text = __( 'Valg', '' );
            break;
		case 'Remove' :
            $translated_text = __( 'Fjern', '' );
            break;
		case 'Relevance' :
            $translated_text = __( 'Sorter etter relevant', '' );
            break;
    }
    return $translated_text;
}




//Adding ris extension
add_filter('upload_mimes', 'my_myme_types', 1, 1);
function my_myme_types($mime_types){
    $mime_types['ris'] = 'text/ris'; //Adding ris extension
    return $mime_types;
}


/**
 * Adds WooCommerce catalog sorting options using postmeta, such as custom fields
 * Tutorial: http://www.skyverge.com/blog/sort-woocommerce-products-custom-fields/
**/
function skyverge_add_postmeta_ordering_args( $sort_args ) {
		
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	switch( $orderby_value ) {
	
		// Name your sortby key whatever you'd like; must correspond to the $sortby in the next function
			
		case 'Publikasjonsår-nyeste-først':
			$sort_args['orderby'] = 'meta_value_num';
			$sort_args['order'] = 'desc';
			$sort_args['meta_key'] = 'dc.year';
			break;
			
		case 'Publikasjonsår-eldste-først':
			$sort_args['orderby'] = 'meta_value_num';
			$sort_args['order'] = 'asc';
			$sort_args['meta_key'] = 'dc.year';
			break;
		
		case 'Innleggsdato-eldste-først':
			$sort_args['orderby'] = 'date';
			$sort_args['order'] = 'asc';
			break;
			
		case 'Innleggsdato-nyeste-først':
			$sort_args['orderby'] = 'date';
			$sort_args['order'] = 'desc';
			break;
	}
	
	return $sort_args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'skyverge_add_postmeta_ordering_args' );
// Add these new sorting arguments to the sortby options on the frontend
function skyverge_add_new_postmeta_orderby( $sortby ) {
	
	// Adjust the text as desired
	$sortby['Publikasjonsår-nyeste-først'] = __( 'Sorter etter publikasjonsår-nyeste først', 'woocommerce' );
    $sortby['Publikasjonsår-eldste-først'] = __( 'Sorter etter publikasjonsår-eldste først', 'woocommerce' );
	$sortby['Innleggsdato-eldste-først'] = __( 'Sorter etter innleggsdato-eldste først', 'woocommerce' );
	$sortby['Innleggsdato-nyeste-først'] = __( 'Sorter etter innleggsdato-nyeste først', 'woocommerce' );
	return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'skyverge_add_new_postmeta_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'skyverge_add_new_postmeta_orderby' );


/* Change Tag to Stikkord  -  Customize Product Categories Labels https://gist.github.com/agusmu/eaedc6f63edbdaf237f2 */
add_filter( 'woocommerce_taxonomy_args_product_tag', 'custom_wc_taxonomy_args_product_tag' );
function custom_wc_taxonomy_args_product_tag( $args ) {
	$args['label'] = __( 'Product Tags', 'woocommerce' );
	$args['labels'] = array(
        'name' 				=> __( 'Stikkord', 'woocommerce' ),
        'singular_name' 	=> __( 'Stikkord', 'woocommerce' ),
        'menu_name'			=> _x( 'Stikkord', 'Admin menu name', 'woocommerce' ),
        'search_items' 		=> __( 'Search Stikkord', 'woocommerce' ),
        'all_items' 		=> __( 'All Stikkord', 'woocommerce' ),
        'parent_item' 		=> __( 'Parent Stikkord', 'woocommerce' ),
        'parent_item_colon' => __( 'Parent Stikkord:', 'woocommerce' ),
        'edit_item' 		=> __( 'Edit Stikkord', 'woocommerce' ),
        'update_item' 		=> __( 'Update Stikkord', 'woocommerce' ),
        'add_new_item' 		=> __( 'Add New Stikkord', 'woocommerce' ),
        'new_item_name' 	=> __( 'New Stikkord Name', 'woocommerce' )
	);

	return $args;
}


/*Change Post-Type Products to Articles.  Source : https://revelationconcept.com/wordpress-rename-default-posts-news-something-else/*/
function revcon_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['product']->labels;
    $labels->name = 'Articles';
    $labels->singular_name = 'Article';
    $labels->add_new = 'Add Article';
    $labels->add_new_item = 'Add Article';
    $labels->edit_item = 'Edit Article';
    $labels->new_item = 'Article';
    $labels->view_item = 'View Article';
    $labels->search_items = 'Search Article';
    $labels->not_found = 'No Article found';
    $labels->not_found_in_trash = 'No Article found in Trash';
    $labels->all_items = 'All Articles';
    $labels->menu_name = 'Articles';
    $labels->name_admin_bar = 'Articles';
}
 
add_action( 'admin_menu', 'revcon_change_post_label' );
add_action( 'init', 'revcon_change_post_object' );


/** Disable Ajax Call from WooCommerce on front page and posts https://www.webnots.com/fix-slow-page-loading-with-woocommerce-wc-ajaxget_refreshed_fragments/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page() || is_single() ) wp_dequeue_script('wc-cart-fragments');
}*/

/** Disable Ajax Call from WooCommerce  https://www.webnots.com/fix-slow-page-loading-with-woocommerce-wc-ajaxget_refreshed_fragments/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments2', 11); 
function dequeue_woocommerce_cart_fragments2() { if (is_front_page()) wp_dequeue_script('wc-cart-fragments'); }*/

//Tag / Stikkord - to accept semicolon as delimiter
add_filter( 'gettext_with_context', 't5_semicolon_tag_delimiter', 10, 4 );
function t5_semicolon_tag_delimiter( $translated, $text, $context, $domain )
{
    if ( 'default' !== $domain or 'tag delimiter' !== $context or ',' !== $text )
        return $translated;

    return ';';
}

add_action( 'woocommerce_before_single_product', 'tilbake_lenke', 10 );
function tilbake_lenke() {
  global $product;
	echo '<div id="tilbaketiltreffliste" style="padding-bottom:20px;"><a href="javascript: history.go(-1)"> Tilbake til treffliste </a></div>'; 
}

//https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
//Insert metadata into post excerpt for searching purpose
add_action( 'save_post', 'set_private_categories');
function set_private_categories($post_id) {
	// If this is a revision, get real post ID
	if ( $parent_id = wp_is_post_revision( $post_id ) ) 
		$post_id = $parent_id;

	if ( get_post_type($post_id) == 'product') {
	//show_message(get_post_type($post_id));
	$excerpttitle = get_the_title( $post_id );
	$excerptdesc = get_post($post_id)->post_content;
	$excerptstikkord = get_the_term_list( $post_id, 'product_tag','',' ');
	$cat1 = get_the_term_list($post_id, 'fag','',' ');
	$cat2 = get_the_term_list($post_id, 'pedagogik','',' ');
	$cat3 = get_the_term_list($post_id, 'teknologi','',' ');
	$cat4 = get_the_term_list($post_id, 'utdanningsniva','',' ');
	$fullcat = $cat1.' '.$cat2.' '.$cat3.' '.$cat4;
	$fullcat = strip_tags($fullcat);
		
 	$specifications_fields = get_specifications_fields();
	foreach ( $specifications_fields as $name => $field ){
        $dcvalue = $field['value'];
		$dcvalue1 = $dcvalue1.' '.$dcvalue;
	}
	
	$fullexcerpt = wp_strip_all_tags($excerpttitle).' '.wp_strip_all_tags($excerptdesc).' '.wp_strip_all_tags($dcvalue1).' '.$fullcat.' '.wp_strip_all_tags($excerptstikkord);

	// unhook this function so it doesn't loop infinitely
	remove_action( 'save_post', 'set_private_categories' );

	// update the post, which calls save_post again
	wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => $fullexcerpt ) );

	// re-hook this function
	add_action( 'save_post', 'set_private_categories' );
	}
}


//https://dream-encode.com/acf-get-all-fields-in-a-field-group/
function get_specifications_fields() {

	global $post;
	
	$specifications_group_id = 1003; // Post ID of the specifications field group - dc.
	$specifications_fields = array();
	
	$fields = acf_get_fields( $specifications_group_id );
	
	foreach ( $fields as $field ) {
		$field_value = get_field( $field['name'] );
		
		if ( $field_value && !empty( $field_value ) ) {
			$specifications_fields[$field['name']] = $field;
			$specifications_fields[$field['name']]['value'] = $field_value;
		}
	}
	return $specifications_fields;
}

add_action('woocommerce_archive_description', 'mobilefilter', 5);
function mobilefilter() {
    echo do_shortcode( '[oceanwp_library id="1965"]' );
}


//Article count
add_filter('woof_print_content_before_search_form', function($content) {
        global $WOOF;
        if ($WOOF->is_isset_in_request_data($WOOF->get_swoof_search_slug()))
        {
            return $content . 'Treff: ' . do_shortcode('[woof_found_count]') . '<br /><br />';
        }
 
        return '';
    });
