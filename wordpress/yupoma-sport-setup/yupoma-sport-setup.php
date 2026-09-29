<?php
/**
 * Plugin Name: YUPOMA SPORT Setup
 * Description: One-time store setup for YUPOMA SPORT (pages, menu, categories, WooCommerce settings for Spain) plus footer legal bar and checkout tweaks.
 * Version:     1.0.0
 * Author:      YUPOMA
 * Text Domain: yupoma-sport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YUPOMA_SETUP_DIR', plugin_dir_path( __FILE__ ) );
define( 'YUPOMA_SETUP_URL', plugin_dir_url( __FILE__ ) );
define( 'YUPOMA_SETUP_DONE', 'yupoma_sport_setup_done' );
define( 'YUPOMA_SETUP_LOG', 'yupoma_sport_setup_log' );

require_once YUPOMA_SETUP_DIR . 'includes/pages.php';

register_activation_hook( __FILE__, 'yupoma_setup_activate' );

/** On activation: run now if WordPress is fully loaded, otherwise on the next request. */
function yupoma_setup_activate() {
	if ( get_option( YUPOMA_SETUP_DONE ) ) {
		return;
	}
	if ( did_action( 'wp_loaded' ) ) {
		yupoma_setup_run();
	} else {
		update_option( 'yupoma_sport_setup_pending', 1 );
	}
}

add_action( 'wp_loaded', function () {
	if ( get_option( 'yupoma_sport_setup_pending' ) && ! get_option( YUPOMA_SETUP_DONE ) ) {
		delete_option( 'yupoma_sport_setup_pending' );
		yupoma_setup_run();
	}
} );

function yupoma_log( $msg ) {
	$log   = (array) get_option( YUPOMA_SETUP_LOG, array() );
	$log[] = $msg;
	update_option( YUPOMA_SETUP_LOG, $log, false );
}

function yupoma_setup_run() {
	update_option( YUPOMA_SETUP_LOG, array(), false );

	// Site identity.
	update_option( 'blogname', 'YUPOMA SPORT' );
	update_option( 'blogdescription', '100% original sportswear, footwear and accessories' );
	update_option( 'timezone_string', 'Europe/Madrid' );
	update_option( 'WPLANG', '' ); // English.

	yupoma_setup_logo();
	$ids = yupoma_setup_pages();
	yupoma_setup_categories();
	yupoma_setup_woocommerce( $ids );
	yupoma_setup_menus( $ids );

	update_option( YUPOMA_SETUP_DONE, gmdate( 'c' ) );
	yupoma_log( 'Setup finished.' );
}

/* ---------------------------------------------------------------- Logo */

function yupoma_sideload( $file, $title ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$existing = get_posts( array( 'post_type' => 'attachment', 'title' => $title, 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( $existing ) {
		return (int) $existing[0];
	}
	$tmp = wp_tempnam( $file );
	copy( YUPOMA_SETUP_DIR . 'assets/' . $file, $tmp );
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, $title );
	if ( is_wp_error( $id ) ) {
		yupoma_log( 'Logo upload failed: ' . $id->get_error_message() );
		return 0;
	}
	return (int) $id;
}

function yupoma_setup_logo() {
	$logo = yupoma_sideload( 'yupoma-sport-logo.png', 'YUPOMA SPORT logo' );
	$icon = yupoma_sideload( 'yupoma-sport-favicon.png', 'YUPOMA SPORT icon' );
	if ( $logo ) {
		set_theme_mod( 'custom_logo', $logo );
		yupoma_log( 'Logo set.' );
	}
	if ( $icon ) {
		update_option( 'site_icon', $icon );
		yupoma_log( 'Favicon set.' );
	}
}

/* --------------------------------------------------------------- Pages */

/** Creates pages that do not exist yet (by slug). Never overwrites existing content. */
function yupoma_setup_pages() {
	$ids = array();
	foreach ( yupoma_pages() as $slug => $page ) {
		$found = get_page_by_path( $slug );
		if ( $found ) {
			$ids[ $slug ] = $found->ID;
			yupoma_log( "Page '{$page['title']}' already exists, left unchanged." );
			continue;
		}
		$ids[ $slug ] = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'draft', // Reviewed by the owner before publishing.
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_content' => $page['content'],
		) );
		yupoma_log( "Page '{$page['title']}' created (draft)." );
	}
	return $ids;
}

/* ---------------------------------------------------------- Categories */

function yupoma_term( $name, $parent = 0 ) {
	$slug = sanitize_title( ( $parent ? get_term( $parent )->slug . '-' : '' ) . $name );
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term ) {
		return (int) $term->term_id;
	}
	$res = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug, 'parent' => $parent ) );
	return is_wp_error( $res ) ? 0 : (int) $res['term_id'];
}

function yupoma_setup_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		yupoma_log( 'WooCommerce not active: categories skipped.' );
		return;
	}
	$sports = array( 'Running', 'Basketball', 'Tennis', 'Training & Gym', 'Football', 'Lifestyle' );
	foreach ( array( 'Men', 'Women', 'Kids' ) as $gender ) {
		$g = yupoma_term( $gender );
		foreach ( array( 'Footwear', 'Clothing', 'Accessories' ) as $type ) {
			$t = yupoma_term( $type, $g );
			if ( 'Footwear' === $type ) {
				foreach ( $sports as $s ) {
					yupoma_term( $s, $t );
				}
			}
		}
	}
	yupoma_term( 'New In' );
	if ( taxonomy_exists( 'product_brand' ) ) { // WooCommerce 9.6+ brands.
		foreach ( array( 'Nike', 'Adidas', 'ASICS' ) as $b ) {
			if ( ! term_exists( $b, 'product_brand' ) ) {
				wp_insert_term( $b, 'product_brand' );
			}
		}
	}
	yupoma_log( 'Product categories created: Men / Women / Kids > Footwear (by sport), Clothing, Accessories; New In; brands.' );
}

/* --------------------------------------------------------- WooCommerce */

function yupoma_setup_woocommerce( $ids ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		yupoma_log( 'WooCommerce not active: store settings skipped.' );
		return;
	}
	update_option( 'woocommerce_default_country', 'ES' );
	update_option( 'woocommerce_currency', 'EUR' );
	update_option( 'woocommerce_currency_pos', 'right_space' );
	update_option( 'woocommerce_price_thousand_sep', '.' );
	update_option( 'woocommerce_price_decimal_sep', ',' );
	update_option( 'woocommerce_price_num_decimals', '2' );
	update_option( 'woocommerce_weight_unit', 'kg' );
	update_option( 'woocommerce_dimension_unit', 'cm' );
	update_option( 'woocommerce_allowed_countries', 'specific' );
	update_option( 'woocommerce_specific_allowed_countries', yupoma_eu_countries() );
	update_option( 'woocommerce_ship_to_countries', '' );

	// Prices entered and shown with IVA included.
	update_option( 'woocommerce_calc_taxes', 'yes' );
	update_option( 'woocommerce_prices_include_tax', 'yes' );
	update_option( 'woocommerce_tax_display_shop', 'incl' );
	update_option( 'woocommerce_tax_display_cart', 'incl' );
	update_option( 'woocommerce_tax_based_on', 'shipping' );
	yupoma_tax_rates();

	// Guest checkout on, no forced account creation.
	update_option( 'woocommerce_enable_guest_checkout', 'yes' );
	update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );
	update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );

	// Legal pages.
	if ( ! empty( $ids['terms-and-conditions'] ) ) {
		update_option( 'woocommerce_terms_page_id', $ids['terms-and-conditions'] );
	}
	if ( ! empty( $ids['privacy-policy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['privacy-policy'] );
	}
	update_option( 'woocommerce_checkout_terms_and_conditions_checkbox_text', 'I have read and agree to the [terms] and the right of withdrawal conditions.' );
	update_option( 'woocommerce_checkout_privacy_policy_text', 'Your personal data will be used to process your order and for the other purposes described in our [privacy_policy].' );

	// Front page.
	if ( ! empty( $ids['home'] ) && 'draft' === get_post_status( $ids['home'] ) ) {
		yupoma_log( 'Home page is a draft: after you publish it, set it as front page in Settings > Reading.' );
	}
	yupoma_log( 'WooCommerce: Spain, EUR, prices incl. IVA 21%, EU-only selling, guest checkout.' );
}

function yupoma_eu_countries() {
	return array( 'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE' );
}

/** Spain 21% standard rate; Canary Islands, Ceuta and Melilla (postcodes 35/38/51/52) at 0% (outside the EU VAT area). */
function yupoma_tax_rates() {
	if ( ! class_exists( 'WC_Tax' ) ) {
		return;
	}
	global $wpdb;
	$exists = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate_country = 'ES'" );
	if ( $exists ) {
		yupoma_log( 'Spanish tax rates already exist, left unchanged.' );
		return;
	}
	$zero = WC_Tax::_insert_tax_rate( array(
		'tax_rate_country' => 'ES', 'tax_rate' => '0.0000', 'tax_rate_name' => 'Canarias, Ceuta y Melilla',
		'tax_rate_priority' => 1, 'tax_rate_shipping' => 1, 'tax_rate_order' => 0, 'tax_rate_class' => '',
	) );
	WC_Tax::_update_tax_rate_postcodes( $zero, '35*;38*;51*;52*' );
	WC_Tax::_insert_tax_rate( array(
		'tax_rate_country' => 'ES', 'tax_rate' => '21.0000', 'tax_rate_name' => 'IVA',
		'tax_rate_priority' => 1, 'tax_rate_shipping' => 1, 'tax_rate_order' => 1, 'tax_rate_class' => '',
	) );
	yupoma_log( 'Tax rates: IVA 21% (Spain), 0% for Canarias / Ceuta / Melilla. Check OSS rates for other EU countries with your gestor.' );
}

/* --------------------------------------------------------------- Menus */

function yupoma_setup_menus( $ids ) {
	$menu_id = yupoma_menu( 'YUPOMA Main', function ( $menu_id ) use ( $ids ) {
		$shop = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
		yupoma_menu_cat( $menu_id, 'New In' );
		foreach ( array( 'Men', 'Women', 'Kids' ) as $g ) {
			yupoma_menu_cat( $menu_id, $g );
		}
		if ( $shop > 0 ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Shop', 'menu-item-object-id' => $shop, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		foreach ( array( 'brands', 'sale' ) as $slug ) {
			yupoma_menu_page( $menu_id, $ids, $slug );
		}
	} );

	$footer_id = yupoma_menu( 'YUPOMA Footer', function ( $menu_id ) use ( $ids ) {
		foreach ( array( 'shipping-returns', 'size-guide', 'about', 'contact', 'legal-notice', 'terms-and-conditions', 'right-of-withdrawal', 'privacy-policy', 'cookie-policy' ) as $slug ) {
			yupoma_menu_page( $menu_id, $ids, $slug );
		}
	} );

	// Assign to theme locations when the theme uses classic menus.
	$locations = get_registered_nav_menus();
	if ( $locations ) {
		$set  = get_theme_mod( 'nav_menu_locations', array() );
		$keys = array_keys( $locations );
		$set[ $keys[0] ] = $menu_id;
		foreach ( $keys as $k ) {
			if ( false !== strpos( $k, 'footer' ) ) {
				$set[ $k ] = $footer_id;
			}
		}
		set_theme_mod( 'nav_menu_locations', $set );
		yupoma_log( 'Menus assigned to theme locations: ' . implode( ', ', $keys ) . '.' );
	} else {
		yupoma_log( 'Block theme detected: menus "YUPOMA Main" and "YUPOMA Footer" created; select them in the Site Editor navigation block.' );
	}
}

function yupoma_menu( $name, $fill ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		return $menu->term_id;
	}
	$menu_id = wp_create_nav_menu( $name );
	$fill( $menu_id );
	yupoma_log( "Menu '{$name}' created." );
	return $menu_id;
}

function yupoma_menu_cat( $menu_id, $name ) {
	$term = get_term_by( 'slug', sanitize_title( $name ), 'product_cat' );
	if ( $term ) {
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $name, 'menu-item-object-id' => $term->term_id, 'menu-item-object' => 'product_cat', 'menu-item-type' => 'taxonomy', 'menu-item-status' => 'publish' ) );
	}
}

function yupoma_menu_page( $menu_id, $ids, $slug ) {
	if ( ! empty( $ids[ $slug ] ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => $ids[ $slug ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
	}
}

/* ------------------------------------------------- Permanent front-end */

/** Legal bar in the footer on every page: trademark disclaimer + withdrawal link (Directive (EU) 2023/2673). */
function yupoma_published_page( $slug ) {
	$page = get_page_by_path( $slug );
	return ( $page && 'publish' === $page->post_status ) ? $page : null;
}

add_action( 'wp_footer', function () {
	$withdraw = yupoma_published_page( 'right-of-withdrawal' );
	$legal    = yupoma_published_page( 'legal-notice' );
	$cookies  = yupoma_published_page( 'cookie-policy' );
	?>
	<div class="yupoma-legal-bar" style="background:#F7F4F1;color:#34302D;font:13px/1.6 Montserrat,Arial,sans-serif;padding:18px 16px;text-align:center;border-top:1px solid #e7e1dc">
		<p style="margin:0 0 6px">YUPOMA SPORT is an independent retailer and is not affiliated with, authorised or endorsed by Nike, Adidas or ASICS. All trademarks belong to their respective owners. All products are 100% original.</p>
		<p style="margin:0">
			<?php if ( $legal ) : ?><a href="<?php echo esc_url( get_permalink( $legal ) ); ?>">Legal Notice</a> · <?php endif; ?>
			<?php if ( $cookies ) : ?><a href="<?php echo esc_url( get_permalink( $cookies ) ); ?>">Cookie Policy</a> · <?php endif; ?>
			<?php if ( $withdraw ) : ?><a href="<?php echo esc_url( get_permalink( $withdraw ) ); ?>" style="font-weight:600;color:#E8622C">Withdraw from contract</a> · <?php endif; ?>
			© <?php echo esc_html( gmdate( 'Y' ) ); ?> YUPOMA SPORT
		</p>
	</div>
	<?php
}, 5 );

/** Unambiguous payment button (TRLGDCU art. 98 / Consumer Rights Directive). */
add_filter( 'woocommerce_order_button_text', function () {
	return 'Place order and pay';
} );

/** Brand colours for WooCommerce buttons. */
add_action( 'wp_head', function () {
	echo '<style>:root{--yupoma-ink:#34302D;--yupoma-accent:#E8622C;--yupoma-soft:#F7F4F1}'
		. '.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce #place_order{background:var(--yupoma-ink);color:#fff}'
		. '.woocommerce span.onsale{background:var(--yupoma-accent)}</style>';
} );

/* ------------------------------------------------------- Admin report */

add_action( 'admin_notices', function () {
	$log = get_option( YUPOMA_SETUP_LOG );
	if ( ! $log || ! current_user_can( 'manage_options' ) || get_user_meta( get_current_user_id(), 'yupoma_setup_notice_seen', true ) ) {
		return;
	}
	echo '<div class="notice notice-success"><p><strong>YUPOMA SPORT setup</strong></p><ul style="list-style:disc;padding-left:20px">';
	foreach ( $log as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}
	echo '</ul><p>New pages are saved as <strong>drafts</strong>. Fill in the [PLACEHOLDERS] in the legal pages, then publish.</p></div>';
	update_user_meta( get_current_user_id(), 'yupoma_setup_notice_seen', 1 );
} );
