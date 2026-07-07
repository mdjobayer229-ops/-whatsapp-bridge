<?php
/**
 * ================================================================
 * 📄 functions.php — jobayergroup.com
 * Theme: Career Counseling (Custom Build)
 * Maintained By: Jobayer Group Dev Team
 * Last Optimized: 2025
 *
 * INDEX:
 *  1. THEME CONSTANTS & REQUIRED FILES
 *  2. THEME SETUP (Minimal — design unused)
 *  3. WIDGET / SIDEBAR REGISTRATION (Minimal)
 *  4. BREADCRUMB
 *  5. WOOCOMMERCE — FAST CHECKOUT & PRICE FILTER
 *  6. BANGLA FONT INJECTION
 *  7. NAVIGATION MENU
 *  8. AFFILIATE HELPER FUNCTIONS
 *  9. SHORTCODE — [my_affiliate_data]
 * 10. SHORTCODE — [my_downline_data]
 * 11. SHORTCODE — [user_data]
 * 12. MLM DASHBOARD ENGINE (Config + AJAX + Countdown)
 * 13. LOGIN SYSTEM (Email / Username / Mobile / WhatsApp)
 * 14. SECURE CONTACT UPDATE (Email / Phone)
 * 15. AFFILIATE DASHBOARD AJAX
 * 16. PAYMENT METHOD SAVE / GET (bKash, Nagad, Rocket, Bank)
 * 17. PASSWORD CHANGE (Frontend Form)
 * 18. THANK YOU PAGE — SET PASSWORD HANDLER
 * 19. COD AUTO-COMPLETE + USER + AFFILIATE CREATE
 * 20. ADMIN NOTICE HANDLERS
 * 21. LOGOUT SYSTEM
 * ================================================================
 */

defined( 'ABSPATH' ) || exit;


// ================================================================
// 1. THEME CONSTANTS & REQUIRED FILES
// ================================================================

function career_counseling_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

function career_counseling_enqueue_setting() {
	career_counseling_define_constant( 'CAREER_COUNSELING_FREE_THEME_DOC', 'https://preview.wpelemento.com/theme-documentation/career-counseling/' );
	career_counseling_define_constant( 'CAREER_COUNSELING_SUPPORT',        'https://wordpress.org/support/theme/career-counseling/' );
	career_counseling_define_constant( 'CAREER_COUNSELING_REVIEW',         'https://wordpress.org/support/theme/career-counseling/reviews/' );
	career_counseling_define_constant( 'CAREER_COUNSELING_BUY_NOW',        'https://www.wpelemento.com/products/counseling-wordpress-theme' );
	career_counseling_define_constant( 'CAREER_COUNSELING_LIVE_DEMO',      'https://preview.wpelemento.com/career-counseling/' );
	career_counseling_define_constant( 'CAREER_COUNSELING_THEME_BUNDLE',   'https://www.wpelemento.com/products/wordpress-theme-bundle' );

	$required_files = array(
		'/includes/tgm/tgm.php',
		'/includes/customizer.php',
		'/includes/getstart/plugin-activation.php',
		'/includes/getstart/getstart.php',
		'/includes/post-create.php',
	);

	foreach ( $required_files as $relative_file ) {
		$file = get_template_directory() . $relative_file;
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}

	$upgrade_file = trailingslashit( get_template_directory() ) . 'includes/go-pro/class-upgrade-pro.php';
	if ( file_exists( $upgrade_file ) ) {
		load_template( $upgrade_file, true );
	}

	if ( class_exists( 'Whizzie' ) ) {
		new Whizzie();
	}
}
add_action( 'after_setup_theme', 'career_counseling_enqueue_setting' );

// ================================================================
// ✅ END — THEME CONSTANTS & REQUIRED FILES
// ================================================================


// ================================================================
// 2. THEME SETUP (Minimal)
// ================================================================

add_action( 'after_setup_theme', function () {
	register_nav_menus( array(
		'primary' => 'Primary Menu',
	) );
} );

// ================================================================
// ✅ END — THEME SETUP
// ================================================================


// ================================================================
// 3. WIDGET / SIDEBAR REGISTRATION (Minimal)
// ================================================================

if ( ! function_exists( 'career_counseling_widgets_init' ) ) {
	function career_counseling_widgets_init() {

		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'career-counseling' ),
			'id'            => 'career-counseling-sidebar',
			'description'   => esc_html__( 'This sidebar will be shown next to the content.', 'career-counseling' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Page Sidebar', 'career-counseling' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'This sidebar will be shown next to the content.', 'career-counseling' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar three', 'career-counseling' ),
			'id'            => 'sidebar-3',
			'description'   => esc_html__( 'This sidebar will be shown on blog pages.', 'career-counseling' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="title">',
			'after_title'   => '</h4>',
		) );

		for ( $i = 1; $i <= 4; $i++ ) {
			register_sidebar( array(
				/* translators: %d: footer sidebar number */
				'name'          => sprintf( esc_html__( 'Footer sidebar %d', 'career-counseling' ), $i ),
				'id'            => 'footer' . $i . '-sidebar',
				/* translators: %d: footer sidebar number */
				'description'   => sprintf( esc_html__( 'It appears in the footer %d.', 'career-counseling' ), $i ),
				'before_widget' => '<aside id="%1$s" class="%2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="title">',
				'after_title'   => '</h4>',
			) );
		}
	}
	add_action( 'widgets_init', 'career_counseling_widgets_init' );
}

// ================================================================
// ✅ END — WIDGET / SIDEBAR REGISTRATION
// ================================================================


// ================================================================
// 4. BREADCRUMB
// ================================================================

function career_counseling_the_breadcrumb() {
	if ( is_home() || is_front_page() ) {
		return;
	}

	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a> &raquo; ';

	if ( is_category() || is_single() ) {
		the_category( ' , ' );
		if ( is_single() ) {
			echo ' &raquo; ' . esc_html( get_the_title() );
		}
	} elseif ( is_page() ) {
		echo esc_html( get_the_title() );
	}
}

// ================================================================
// ✅ END — BREADCRUMB
// ================================================================


// ================================================================
// 5. WOOCOMMERCE — FAST CHECKOUT & PRICE FILTER
// ================================================================

function career_counseling_fast_checkout_auto_add_product() {
	if ( is_admin() || ! function_exists( 'WC' ) || ! is_page( 'fast-checkout' ) ) {
		return;
	}

	if ( null === WC()->cart && function_exists( 'wc_load_cart' ) ) {
		wc_load_cart();
	}

	if ( WC()->cart && WC()->cart->is_empty() ) {
		WC()->cart->add_to_cart( 81, 1 );
	}
}
add_action( 'template_redirect', 'career_counseling_fast_checkout_auto_add_product' );

add_filter( 'woocommerce_order_button_text', function () { return 'নিরাপদে অর্ডার সম্পন্ন করুন'; }, 999 );
add_filter( 'woocommerce_pay_order_button_text', function () { return 'নিরাপদে পেমেন্ট করুন'; }, 999 );
add_filter( 'woocommerce_checkout_privacy_policy_text', function () { return 'আপনার তথ্য নিরাপদে সংরক্ষিত থাকবে।'; }, 999 );

add_filter( 'woocommerce_gateway_title', function ( $title ) {
	$plain = wp_strip_all_tags( (string) $title );
	if ( false !== stripos( $plain, 'ssl' ) || false !== stripos( $plain, 'bkash' ) || false !== stripos( $plain, 'pay online' ) || false !== stripos( $plain, 'card' ) || false !== stripos( $plain, 'banking' ) ) return 'অনলাইন পেমেন্ট';
	if ( false !== stripos( $plain, 'cod' ) || false !== stripos( $plain, 'cash' ) ) return 'নগদ';
	return $title;
}, 999 );

add_filter( 'woocommerce_gateway_description', function () {
	return 'অনলাইন পেমেন্ট (বিকাশ/নগদ/রকেট)';
}, 999 );

add_filter( 'woocommerce_available_payment_gateways', function ( $gateways ) {
	foreach ( $gateways as $gateway ) {
		if ( ! is_object( $gateway ) ) continue;
		$search = strtolower( wp_strip_all_tags( $gateway->id . ' ' . $gateway->title . ' ' . $gateway->description ) );
		if ( false !== strpos( $search, 'ssl' ) || false !== strpos( $search, 'bkash' ) || false !== strpos( $search, 'pay online' ) || false !== strpos( $search, 'card' ) || false !== strpos( $search, 'banking' ) ) {
			$gateway->title       = 'অনলাইন পেমেন্ট';
			$gateway->description = 'বিকাশ/নগদ/রকেট';
		}
		if ( false !== strpos( $search, 'cod' ) || false !== strpos( $search, 'cash' ) ) {
			$gateway->title       = 'নগদ';
			$gateway->description = 'হাতে পেমেন্ট';
		}
	}
	return $gateways;
}, 999 );

function career_counseling_keep_single_cart_item( $passed ) {
	if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
		$keep_product_id = 81;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = isset( $cart_item['product_id'] ) ? (int) $cart_item['product_id'] : 0;
			if ( $product_id !== $keep_product_id ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'career_counseling_keep_single_cart_item' );

add_filter( 'woocommerce_is_sold_individually', '__return_true' );

function career_counseling_clean_price_percent( $price ) {
	return str_replace( '%', '', $price );
}
add_filter( 'woocommerce_get_price_html',    'career_counseling_clean_price_percent' );
add_filter( 'woocommerce_cart_item_price',   'career_counseling_clean_price_percent' );

add_filter( 'loop_shop_columns', 'career_counseling_loop_columns', 999 );
if ( ! function_exists( 'career_counseling_loop_columns' ) ) {
	function career_counseling_loop_columns() {
		return max( 1, absint( get_theme_mod( 'career_counseling_products_per_row', 4 ) ) );
	}
}

add_filter( 'loop_shop_per_page', 'career_counseling_products_per_page' );
function career_counseling_products_per_page( $cols ) {
	unset( $cols );
	return max( 1, absint( get_theme_mod( 'career_counseling_products_per_page', 8 ) ) );
}

add_filter( 'woocommerce_single_product_image_thumbnail_html', function ( $html ) {
	return '' . $html . '';
} );

add_filter( 'redirect_canonical', function ( $redirect_url, $requested_url ) {
	if ( is_page( 'fast-checkout' ) && ! empty( $_GET['ref'] ) ) {
		return false;
	}
	return $redirect_url;
}, 10, 2 );

// ================================================================
// ✅ END — WOOCOMMERCE FAST CHECKOUT & PRICE FILTER
// ================================================================


// ================================================================
// 6. BANGLA FONT INJECTION
// ================================================================

add_action( 'wp_head', function () { ?>
<!-- Bengali Font — jobayergroup.com -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style id="jobayer-bangla-font-fix">
html, body, *, input, textarea, select, button,
.woocommerce, .woocommerce *,
.goog-te-banner-frame, .goog-te-menu-frame,
body.translated-ltr, body.translated-rtl {
	font-family: 'Hind Siliguri', sans-serif !important;
}
body {
	font-size: 16px;
	line-height: 1.7;
	letter-spacing: 0;
}
</style>
<?php }, 999 );

// ================================================================
// ✅ END — BANGLA FONT INJECTION
// ================================================================


// ================================================================
// 7. NAVIGATION MENU SHORTCODES
// ================================================================

add_shortcode( 'custom_menu', function () {
	return wp_nav_menu( array(
		'theme_location' => 'primary',
		'menu_class'     => 'luxury-menu',
		'container'      => false,
		'echo'           => false,
	) );
} );

add_shortcode( 'main_menu', function () {
	return wp_nav_menu( array(
		'menu'       => 'Main Menu',
		'menu_class' => 'jg-premium-menu',
		'container'  => false,
		'echo'       => false,
	) );
} );

// ================================================================
// ✅ END — NAVIGATION MENU SHORTCODES
// ================================================================


// ================================================================
// 8. AFFILIATE HELPER FUNCTIONS
// ================================================================

add_action( 'init', function () {
	if ( ! empty( $_GET['ref'] ) ) {
		$ref = preg_replace( '/[^a-zA-Z0-9_\-]/', '', wp_unslash( $_GET['ref'] ) );
		if ( $ref !== '' && ( ! isset( $_COOKIE['uap_ref'] ) || $_COOKIE['uap_ref'] !== $ref ) ) {
			$secure = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' );
			setcookie( 'uap_ref', $ref, time() + ( 30 * 24 * 60 * 60 ), '/', '', $secure, true );
			$_COOKIE['uap_ref'] = $ref;
			if ( function_exists( 'WC' ) && WC()->session ) {
				WC()->session->set( 'jg_ref', $ref );
				$uap_clean = array( 'uap_affiliate_id', 'uap_referral_id', 'uap_referral_username' );
				foreach ( $uap_clean as $key ) {
					WC()->session->__unset( $key );
				}
			}
		}
	}
}, 9 );

function career_counseling_get_current_affiliate_id() {
	if ( ! is_user_logged_in() ) {
		return 0;
	}

	global $wpdb;

	return absint(
		$wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$wpdb->prefix}uap_affiliates WHERE uid = %d",
				get_current_user_id()
			)
		)
	);
}

function career_counseling_get_child_affiliate_ids( $parent_affiliate_ids ) {
	global $wpdb;

	$parent_affiliate_ids = array_values( array_filter( array_map( 'absint', (array) $parent_affiliate_ids ) ) );

	if ( empty( $parent_affiliate_ids ) ) {
		return array();
	}

	$placeholders = implode( ',', array_fill( 0, count( $parent_affiliate_ids ), '%d' ) );
	$query        = "SELECT affiliate_id FROM {$wpdb->prefix}uap_mlm_relations WHERE parent_affiliate_id IN ($placeholders)";
	$prepared     = $wpdb->prepare( $query, $parent_affiliate_ids );
	$ids          = $wpdb->get_col( $prepared );

	return array_values( array_filter( array_map( 'absint', $ids ) ) );
}

function career_counseling_maybe_verify_ajax_nonce() {
	$nonce = '';

	if ( isset( $_REQUEST['nonce'] ) ) {
		$nonce = sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
	} elseif ( isset( $_REQUEST['_ajax_nonce'] ) ) {
		$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_ajax_nonce'] ) );
	}

	if ( $nonce && ! wp_verify_nonce( $nonce, 'career_counseling_ajax' ) ) {
		wp_send_json_error( 'Invalid request' );
	}
}

// ================================================================
// ✅ END — AFFILIATE HELPER FUNCTIONS
// ================================================================


// ================================================================
// 9. SHORTCODE — [my_affiliate_data]
// ================================================================

function career_counseling_my_affiliate_data_shortcode() {
	if ( ! is_user_logged_in() ) {
		return '0,0,0,0,0';
	}

	$affiliate_id = career_counseling_get_current_affiliate_id();

	if ( ! $affiliate_id ) {
		return '0,0,0,0,0';
	}

	$level_1 = career_counseling_get_child_affiliate_ids( array( $affiliate_id ) );
	$level_2 = career_counseling_get_child_affiliate_ids( $level_1 );
	$level_3 = career_counseling_get_child_affiliate_ids( $level_2 );
	$level_4 = career_counseling_get_child_affiliate_ids( $level_3 );

	return implode( ',', array(
		count( $level_1 ),
		count( $level_2 ),
		count( $level_3 ),
		count( $level_4 ),
		0,
	) );
}
add_shortcode( 'my_affiliate_data', 'career_counseling_my_affiliate_data_shortcode' );

// ================================================================
// ✅ END — SHORTCODE [my_affiliate_data]
// ================================================================


// ================================================================
// 10. SHORTCODE — [my_downline_data]
// ================================================================

function career_counseling_my_downline_data_shortcode() {
	if ( ! is_user_logged_in() ) {
		return wp_json_encode( array() );
	}

	global $wpdb;

	$affiliate_id = career_counseling_get_current_affiliate_id();

	if ( ! $affiliate_id ) {
		return wp_json_encode( array() );
	}

	$relations_table  = $wpdb->prefix . 'uap_mlm_relations';
	$affiliates_table = $wpdb->prefix . 'uap_affiliates';
	$referrals_table  = $wpdb->prefix . 'uap_referrals';

	$downlines = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT child_affiliates.id AS affiliate_id,
			        child_users.display_name AS display_name,
			        COUNT(referrals.id) AS referral_count
			 FROM {$relations_table} AS relations
			 INNER JOIN {$affiliates_table} AS child_affiliates
			    ON child_affiliates.id = relations.affiliate_id
			 LEFT JOIN {$wpdb->users} AS child_users
			    ON child_users.ID = child_affiliates.uid
			 LEFT JOIN {$referrals_table} AS referrals
			    ON referrals.affiliate_id = child_affiliates.id
			 WHERE relations.parent_affiliate_id = %d
			 GROUP BY child_affiliates.id, child_users.display_name
			 ORDER BY child_users.display_name ASC",
			$affiliate_id
		)
	);

	$result = array();
	foreach ( $downlines as $downline ) {
		$result[] = array(
			'name'  => $downline->display_name ? $downline->display_name : __( 'User', 'career-counseling' ),
			'count' => absint( $downline->referral_count ),
		);
	}

	return wp_json_encode( $result );
}
add_shortcode( 'my_downline_data', 'career_counseling_my_downline_data_shortcode' );

// ================================================================
// ✅ END — SHORTCODE [my_downline_data]
// ================================================================


// ================================================================
// 11. SHORTCODE — [user_data]
// ================================================================

add_shortcode( 'user_data', function () {
	if ( ! is_user_logged_in() ) {
		return '';
	}

	$u = wp_get_current_user();

	$name = trim( $u->first_name . ' ' . $u->last_name );
	if ( empty( $name ) ) {
		$name = trim( $u->display_name );
	}
	if ( empty( $name ) || $name === $u->user_email ) {
		$name = $u->user_login;
	}

	$phone = get_user_meta( $u->ID, 'ihc_phone', true );
	if ( empty( $phone ) ) { $phone = get_user_meta( $u->ID, 'uap_phone', true ); }
	if ( empty( $phone ) ) { $phone = get_user_meta( $u->ID, 'billing_phone', true ); }
	if ( empty( $phone ) ) { $phone = get_user_meta( $u->ID, 'phone', true ); }
	if ( empty( $phone ) ) { $phone = 'Not set'; }

	$data = array(
		'name'     => $name,
		'username' => $u->user_login,
		'email'    => $u->user_email,
		'phone'    => $phone,
	);

	return '<script type="application/json" id="user_json">' . wp_json_encode( $data ) . '</script>';
} );

// ================================================================
// ✅ END — SHORTCODE [user_data]
// ================================================================


// ================================================================
// 12. MLM DASHBOARD ENGINE
// ================================================================

function cc_get_mlm_config() {
	return array(
		'targets'      => array( 3, 9, 27, 81, 243, 729, 2187, 6561, 19683, 59049 ),
		'mlm_totals'   => array( 45, 117, 252, 495, 981, 2439, 6813, 13374, 33057, 92106 ),
		'level_income' => array( 45, 72, 135, 243, 486, 1458, 4374, 6561, 19683, 59049 ),
		'commissions'  => array( 15, 8, 5, 3, 2, 2, 2, 1, 1, 1 ),
		'salary'       => 11000,
		'commitment'   => 99,
		'countdown'    => array(
			'start_hour' => 16,
			'end_hour'   => 24,
		),
		'statuses' => array(
			0 => array( 'label' => 'Commitment 45.45%',              'type' => 'commitment' ),
			1 => array( 'label' => 'Commitment 100% ✅ + Salary Start', 'type' => 'salary_start' ),
			2 => array( 'label' => 'Salary 2.29%',                   'type' => 'salary' ),
			3 => array( 'label' => 'Salary 4.50%',                   'type' => 'salary' ),
			4 => array( 'label' => 'Salary 8.92%',                   'type' => 'salary' ),
			5 => array( 'label' => 'Salary 22.17%',                  'type' => 'salary' ),
			6 => array( 'label' => 'Salary 61.94%',                  'type' => 'salary' ),
			7 => array( 'label' => 'Salary 100% ✅ + Bonus 21.58%',   'type' => 'bonus' ),
			8 => array( 'label' => 'Bonus Salary 200.52%',           'type' => 'bonus' ),
			9 => array( 'label' => 'Bonus Salary 737.33% 🚀',         'type' => 'winner' ),
		),
	);
}

add_action( 'wp_ajax_cc_final_progress',        'cc_final_progress' );
add_action( 'wp_ajax_nopriv_cc_final_progress', 'cc_final_progress' );

function cc_final_progress() {
	global $wpdb;

	$cfg = cc_get_mlm_config();

	if ( ! function_exists( 'career_counseling_get_current_affiliate_id' ) ) {
		wp_send_json_success( array( 'error' => 'affiliate_function_missing', 'data' => array() ) );
	}

	$user_timing = array( 'start_hour' => 16, 'end_hour' => 24, 'duration' => 8 );
	if ( is_user_logged_in() ) {
		$settings = ag_get_user_work_settings_data( get_current_user_id() );
		if ( $settings['has_settings'] ) {
			$user_timing = array(
				'start_hour' => $settings['start_hour'],
				'end_hour'   => $settings['end_hour'],
				'duration'   => $settings['duration'],
				'pending'    => $settings['pending'],
			);
		}
	}

	$affiliate_id = career_counseling_get_current_affiliate_id();

	if ( ! $affiliate_id ) {
		$empty = array();
		foreach ( $cfg['targets'] as $i => $t ) {
			$empty[] = array(
				'index'          => $i,
				'target'         => $t,
				'people_count'   => 0,
				'percent'        => 0,
				'current_income' => 0,
				'level_income'   => $cfg['level_income'][ $i ],
				'total_income'   => $cfg['mlm_totals'][ $i ],
				'status_label'   => $cfg['statuses'][ $i ]['label'],
				'status_type'    => $cfg['statuses'][ $i ]['type'],
				'is_active'      => ( $i === 0 ),
				'is_complete'    => false,
			);
		}
		wp_send_json_success( array(
			'data'              => $empty,
			'countdown'         => cc_get_countdown_data( $cfg, $user_timing['start_hour'], $user_timing['end_hour'] ),
			'user_work_settings' => $user_timing,
			'segment_state'     => null,
		) );
	}

	$real_income = (float) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}uap_referrals WHERE affiliate_id = %d",
			$affiliate_id
		)
	);

	$levels    = array();
	$levels[0] = career_counseling_get_child_affiliate_ids( array( $affiliate_id ) );
	for ( $i = 1; $i < 10; $i++ ) {
		$levels[ $i ] = career_counseling_get_child_affiliate_ids( $levels[ $i - 1 ] );
	}

	$cumulative = array();
	$sum        = 0;
	foreach ( $levels as $l ) {
		$sum           += count( $l );
		$cumulative[]   = $sum;
	}

	$data          = array();
	$active_target = 0;

	$segment_state = null;
	if ( is_user_logged_in() ) {
		$segment_state = ag_get_segment_state(
			get_current_user_id(),
			$user_timing['start_hour'],
			$user_timing['end_hour'],
			$cumulative[0] ?? 0
		);
	}

	foreach ( $cfg['targets'] as $i => $t ) {
		$people_count = $cumulative[ $i ] ?? 0;
		$percent      = min( ( $people_count / $t ) * 100, 100 );
		$is_complete  = ( $people_count >= $t );

		$allowed_income = $cfg['mlm_totals'][ $i ];
		$current_income = min( $real_income, $allowed_income );

		if ( ! $is_complete && $active_target === $i ) {
			$is_active = true;
		} elseif ( $i > 0 && ( $cumulative[ $i - 1 ] ?? 0 ) >= $cfg['targets'][ $i - 1 ] && ! $is_complete ) {
			$is_active     = true;
			$active_target = $i;
		} else {
			$is_active = ( $i === 0 && ! $is_complete );
		}

		$data[] = array(
			'index'          => $i,
			'target'         => $t,
			'people_count'   => $people_count,
			'percent'        => round( $percent, 2 ),
			'current_income' => round( $current_income, 2 ),
			'level_income'   => $cfg['level_income'][ $i ],
			'total_income'   => $allowed_income,
			'status_label'   => $cfg['statuses'][ $i ]['label'],
			'status_type'    => $cfg['statuses'][ $i ]['type'],
			'is_active'      => $is_active,
			'is_complete'    => $is_complete,
		);
	}

	wp_send_json_success( array(
		'data'               => $data,
		'countdown'          => cc_get_countdown_data( $cfg, $user_timing['start_hour'], $user_timing['end_hour'] ),
		'summary'            => array(
			'total_income'  => round( $real_income, 2 ),
			'total_people'  => $cumulative[9] ?? 0,
		),
		'user_work_settings' => $user_timing,
		'segment_state'      => $segment_state,
	) );
}

function cc_get_countdown_data( $cfg, $start_hour = null, $end_hour = null ) {
	if ( $start_hour === null ) {
		$start_hour = isset( $cfg['countdown']['start_hour'] ) ? (int) $cfg['countdown']['start_hour'] : 16;
	}
	if ( $end_hour === null ) {
		$end_hour = isset( $cfg['countdown']['end_hour'] ) ? (int) $cfg['countdown']['end_hour'] : 24;
	}

	$tz    = new DateTimeZone( 'Asia/Dhaka' );
	$now   = new DateTime( 'now', $tz );
	$today = $now->format( 'Y-m-d' );

	$start = new DateTime( $today . ' ' . str_pad( max( 0, min( 23, $start_hour ) ), 2, '0', STR_PAD_LEFT ) . ':00:00', $tz );

	if ( $end_hour <= $start_hour ) {
		$end = new DateTime( $today . ' ' . str_pad( max( 0, min( 23, $end_hour ) ), 2, '0', STR_PAD_LEFT ) . ':00:00', $tz );
		$end->modify( '+1 day' );
	} else {
		$end = new DateTime( $today . ' ' . str_pad( max( 0, min( 23, $end_hour ) ), 2, '0', STR_PAD_LEFT ) . ':00:00', $tz );
	}

	$now_ts   = $now->getTimestamp();
	$start_ts = $start->getTimestamp();
	$end_ts   = $end->getTimestamp();

	$window_open   = ( $now_ts >= $start_ts && $now_ts <= $end_ts );
	$seconds_left  = max( 0, $end_ts - $now_ts );
	$total_window  = ( $end_hour > $start_hour )
		? ( $end_hour - $start_hour ) * 3600
		: ( 24 - $start_hour + $end_hour ) * 3600;
	$elapsed       = max( 0, $now_ts - $start_ts );
	$progress_pct  = $window_open ? min( 100, ( $elapsed / $total_window ) * 100 ) : 0;

	$segment_seconds = (int) ( $total_window / 3 );
	$current_segment = 0;
	if ( $window_open ) {
		$current_segment = min( 2, (int) floor( $elapsed / $segment_seconds ) );
	}

	$segments = array();
	for ( $s = 0; $s < 3; $s++ ) {
		$seg_start  = $start_ts + ( $s * $segment_seconds );
		$seg_end    = $seg_start + $segment_seconds;
		$seg_done   = ( $now_ts >= $seg_end );
		$seg_active = ( $now_ts >= $seg_start && $now_ts < $seg_end );
		$seg_left   = $seg_active ? max( 0, $seg_end - $now_ts ) : 0;

		$seg_start_dt = new DateTime( '@' . $seg_start );
		$seg_start_dt->setTimezone( $tz );
		$seg_end_dt = new DateTime( '@' . $seg_end );
		$seg_end_dt->setTimezone( $tz );

		$segments[] = array(
			'label'        => ( $s + 1 ) . 'ম ব্যক্তি',
			'time_range'   => $seg_start_dt->format( 'g:i A' ) . ' – ' . $seg_end_dt->format( 'g:i A' ),
			'is_done'      => $seg_done,
			'is_active'    => $seg_active,
			'seconds_left' => $seg_left,
		);
	}

	return array(
		'window_open'     => $window_open,
		'seconds_left'    => $seconds_left,
		'progress_pct'    => round( $progress_pct, 2 ),
		'current_segment' => $current_segment,
		'segments'        => $segments,
		'server_time'     => $now->format( 'H:i:s' ),
	);
}

// ================================================================
// ✅ END — MLM DASHBOARD ENGINE
// ================================================================


// ================================================================
// 13. LOGIN SYSTEM
// ================================================================

if ( ! defined( 'JG_MAIN_SITE_URL' ) ) {
	define( 'JG_MAIN_SITE_URL', 'https://jobayergroup.com' );
}

add_action( 'template_redirect', function () {
	if ( is_page( 'job-login' ) && is_user_logged_in() ) {
		wp_redirect( JG_MAIN_SITE_URL . '/my-progress/' );
		exit;
	}
} );

add_action( 'wp_ajax_nopriv_jg_custom_login', 'jg_custom_login' );
add_action( 'wp_ajax_jg_custom_login',        'jg_custom_login' );

function jg_custom_login() {
	$login_input = isset( $_POST['login'] )    ? trim( sanitize_text_field( wp_unslash( $_POST['login'] ) ) )    : '';
	$password    = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] )                       : '';

	if ( $login_input === '' || $password === '' ) {
		wp_send_json_error( 'Login and password are required' );
	}

	$user = false;

	if ( is_email( $login_input ) ) {
		$user = get_user_by( 'email', $login_input );
	}

	if ( ! $user && preg_match( '/^[0-9+()\-\s]+$/', $login_input ) ) {
		$raw        = preg_replace( '/\D+/', '', $login_input );
		$candidates = array();

		if ( $raw !== '' ) {
			$candidates[] = $raw;
		}

		if ( strpos( $raw, '01' ) === 0 ) {
			$candidates[] = '880' . substr( $raw, 1 );
			$candidates[] = '+880' . substr( $raw, 1 );
		}
		if ( strpos( $raw, '880' ) === 0 ) {
			$candidates[] = '0' . substr( $raw, 3 );
			$candidates[] = '+' . $raw;
		}

		$candidates = array_values( array_unique( array_filter( $candidates ) ) );

		$meta_keys = array(
			'billing_phone', 'phone', 'mobile',
			'whatsapp', 'whatsapp_number',
			'uap_phone', 'ihc_phone',
		);

		foreach ( $meta_keys as $meta_key ) {
			foreach ( $candidates as $candidate ) {
				$users = get_users( array(
					'number'     => 1,
					'fields'     => 'all',
					'meta_key'   => $meta_key,
					'meta_value' => $candidate,
				) );
				if ( ! empty( $users ) ) {
					$user = $users[0];
					break 2;
				}
			}
		}
	}

	if ( ! $user || empty( $user->ID ) ) {
		$user = get_user_by( 'login', $login_input );
	}

	if ( ! $user || empty( $user->ID ) ) {
		wp_send_json_error( 'User not found' );
	}

	$signon = wp_signon( array(
		'user_login'    => $user->user_login,
		'user_password' => $password,
		'remember'      => true,
	), is_ssl() );

	if ( is_wp_error( $signon ) ) {
		wp_send_json_error( $signon->get_error_message() );
	}

	wp_send_json_success( array(
		'redirect' => JG_MAIN_SITE_URL . '/my-progress/',
	) );
}

// ================================================================
// ✅ END — LOGIN SYSTEM
// ================================================================


// ================================================================
// 14. SECURE CONTACT UPDATE (Email / Phone / WhatsApp)
// ================================================================

add_action( 'wp_ajax_secure_update_contact', 'jg_secure_update_contact' );

function jg_secure_update_contact() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Login required' );
	}

	$user    = wp_get_current_user();
	$user_id = $user->ID;

	$type  = isset( $_POST['type'] )  ? sanitize_text_field( wp_unslash( $_POST['type'] ) )  : '';
	$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
	$pass1 = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] )               : '';
	$pass2 = isset( $_POST['pass2'] ) ? (string) wp_unslash( $_POST['pass2'] )               : '';

	if ( $type === '' || $value === '' || $pass1 === '' || $pass2 === '' ) {
		wp_send_json_error( 'Missing required data' );
	}

	if (
		! wp_check_password( $pass1, $user->user_pass, $user_id ) ||
		! wp_check_password( $pass2, $user->user_pass, $user_id )
	) {
		wp_send_json_error( 'Password incorrect' );
	}

	if ( $type === 'email' ) {
		if ( ! is_email( $value ) ) {
			wp_send_json_error( 'Invalid email' );
		}
		if ( email_exists( $value ) && $user->user_email !== $value ) {
			wp_send_json_error( 'Email already used' );
		}
		wp_update_user( array( 'ID' => $user_id, 'user_email' => $value ) );
		wp_send_json_success( 'Email updated successfully' );
	}

	if ( in_array( $type, array( 'phone', 'mobile', 'whatsapp' ), true ) ) {
		update_user_meta( $user_id, 'billing_phone',   $value );
		update_user_meta( $user_id, 'phone',            $value );
		update_user_meta( $user_id, 'mobile',           $value );
		update_user_meta( $user_id, 'whatsapp',         $value );
		update_user_meta( $user_id, 'whatsapp_number',  $value );
		update_user_meta( $user_id, 'uap_phone',        $value );
		update_user_meta( $user_id, 'ihc_phone',        $value );
		wp_send_json_success( 'Phone / WhatsApp updated successfully' );
	}

	wp_send_json_error( 'Invalid request' );
}

// ================================================================
// ✅ END — SECURE CONTACT UPDATE
// ================================================================


// ================================================================
// 15. AFFILIATE DASHBOARD AJAX
// ================================================================

add_action( 'wp_ajax_get_affiliate_dashboard', 'career_counseling_get_affiliate_dashboard' );

add_action( 'wp_ajax_get_affiliate_dashboard', 'career_counseling_get_affiliate_dashboard' );

function career_counseling_get_affiliate_dashboard() {
	career_counseling_maybe_verify_ajax_nonce();

	if ( ! is_user_logged_in() ) {
		wp_send_json( array( 'error' => 'User not logged in' ) );
	}

	global $wpdb;

	$affiliate_id = career_counseling_get_current_affiliate_id();

	if ( ! $affiliate_id ) {
		wp_send_json( array( 'error' => 'Not affiliate' ) );
	}

	$referrals_table      = $wpdb->prefix . 'uap_referrals';
	$payments_table       = $wpdb->prefix . 'uap_payments';
	$team_relations_table = $wpdb->prefix . 'uap_affiliate_referral_users_relations';

	// মোট আয়: সব referral amount-এর যোগফল
	$total = (float) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(amount), 0)
			 FROM {$referrals_table}
			 WHERE affiliate_id = %d",
			$affiliate_id
		)
	);

	// প্রসেসিং: যেগুলো এখনো pending/verify stage-এ আছে
	$processing = (float) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(amount), 0)
			 FROM {$referrals_table}
			 WHERE affiliate_id = %d
			   AND status = 2
			   AND payment = 0",
			$affiliate_id
		)
	);

	// সেন্ড: যেগুলো sent/verified state-এ আছে
	$sent = (float) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(amount), 0)
			 FROM {$referrals_table}
			 WHERE affiliate_id = %d
			   AND payment = 1",
			$affiliate_id
		)
	);

	// পেইড: সম্পূর্ণ payout হয়ে গেছে এমন amount
	$paid = (float) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COALESCE(SUM(amount), 0)
			 FROM {$payments_table}
			 WHERE affiliate_id = %d
			   AND status = 2",
			$affiliate_id
		)
	);

	// Available = Total - Paid
	$available = max( 0, $total - $paid );

	// Team count
	$team = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*)
			 FROM {$team_relations_table}
			 WHERE affiliate_id = %d",
			$affiliate_id
		)
	);

	wp_send_json( array(
		'total'      => round( $total, 2 ),
		'available'  => round( $available, 2 ),
		'processing' => round( $processing, 2 ),
		'sent'       => round( $sent, 2 ),
		'paid'       => round( $paid, 2 ),
		'team'       => $team,
	) );
}

// ✅ END — AFFILIATE DASHBOARD AJAX
// ================================================================


// ================================================================
// 17. GET TEAM MEMBERS AJAX
// ================================================================

function career_counseling_get_member_designation_index( $affiliate_id ) {
	$cfg       = cc_get_mlm_config();
	$current   = array( $affiliate_id );
	$cumulative = array();

	for ( $i = 0; $i < 10; $i++ ) {
		$current = career_counseling_get_child_affiliate_ids( $current );
		$prev    = $i > 0 ? ( $cumulative[ $i - 1 ] ?? 0 ) : 0;
		$cumulative[ $i ] = $prev + count( $current );
	}

	foreach ( $cfg['targets'] as $i => $target ) {
		if ( ( $cumulative[ $i ] ?? 0 ) < $target ) {
			return $i;
		}
	}

	return 9;
}

add_action( 'wp_ajax_get_team_members', 'career_counseling_get_team_members' );

function career_counseling_get_team_members() {
	career_counseling_maybe_verify_ajax_nonce();

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'User not logged in' );
	}

	global $wpdb;

	$affiliate_id = career_counseling_get_current_affiliate_id();
	if ( ! $affiliate_id ) {
		wp_send_json_error( 'Not affiliate' );
	}

	$level = isset( $_GET['level'] ) ? absint( $_GET['level'] ) : 1;
	if ( $level < 1 ) { $level = 1; }
	if ( $level > 10 ) { $level = 10; }

	$affiliates_table = $wpdb->prefix . 'uap_affiliates';

	// ——— Get affiliate IDs at the requested level via MLM tree ———
	$parent_ids = array( $affiliate_id );
	for ( $i = 1; $i <= $level; $i++ ) {
		$child_ids = career_counseling_get_child_affiliate_ids( $parent_ids );
		if ( empty( $child_ids ) ) {
			$parent_ids = array();
			break;
		}
		$parent_ids = $child_ids;
	}
	$member_ids = $parent_ids;

	// ——— Get member details ———
	$members = array();
	if ( ! empty( $member_ids ) ) {
		$placeholders = implode( ',', array_fill( 0, count( $member_ids ), '%d' ) );
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.id AS affiliate_id, u.ID AS user_id, u.display_name, u.user_login, u.user_email
				 FROM {$affiliates_table} a
				 INNER JOIN {$wpdb->users} u ON u.ID = a.uid
				 WHERE a.id IN ({$placeholders})",
				$member_ids
			)
		);

		foreach ( $results as $row ) {
			// Name fallback (matches [user_data] shortcode logic)
			$name = trim( get_user_meta( $row->user_id, 'first_name', true ) . ' ' . get_user_meta( $row->user_id, 'last_name', true ) );
			if ( empty( $name ) ) {
				$name = trim( $row->display_name );
			}
			if ( empty( $name ) || $name === $row->user_email ) {
				$name = $row->user_login;
			}

			// Phone cascade
			$phone = get_user_meta( $row->user_id, 'ihc_phone', true );
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'uap_phone', true ); }
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'billing_phone', true ); }
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'phone', true ); }
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'mobile', true ); }
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'whatsapp_number', true ); }
			if ( empty( $phone ) ) { $phone = get_user_meta( $row->user_id, 'whatsapp', true ); }

			$members[] = array(
				'name'              => $name,
				'phone'             => $phone ?: '',
				'designation_index' => career_counseling_get_member_designation_index( $row->affiliate_id ),
			);
		}
	}

	// ——— Get stats for all levels ———
	$stats    = array();
	$current_ids = array( $affiliate_id );
	for ( $l = 1; $l <= 10; $l++ ) {
		$current_ids = career_counseling_get_child_affiliate_ids( $current_ids );
		$stats[] = array(
			'level' => $l,
			'count' => count( $current_ids ),
		);
		if ( empty( $current_ids ) ) { break; }
	}

	wp_send_json( array(
		'level'   => $level,
		'members' => $members,
		'stats'   => $stats,
	) );
}

// ================================================================
// ✅ END — GET TEAM MEMBERS AJAX
// ================================================================


// ================================================================
// 18. PAYMENT METHOD SAVE / GET
// ================================================================

add_action( 'wp_ajax_save_uap_payment', 'career_counseling_save_uap_payment' );

function career_counseling_save_uap_payment() {
	career_counseling_maybe_verify_ajax_nonce();

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Login required' );
	}

	$user    = wp_get_current_user();
	$user_id = $user->ID;

	$method   = isset( $_POST['method'] )   ? sanitize_key( wp_unslash( $_POST['method'] ) )            : '';
	$account  = isset( $_POST['account'] )  ? sanitize_text_field( wp_unslash( $_POST['account'] ) )    : '';
	$password = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] )                 : '';

	$allowed_methods = array( 'bkash', 'nagad', 'rocket', 'bank' );

	if ( ! in_array( $method, $allowed_methods, true ) ) {
		wp_send_json_error( 'Invalid payment method' );
	}
	if ( '' === $account ) {
		wp_send_json_error( 'Account information required' );
	}
	if ( '' === $password ) {
		wp_send_json_error( 'Password required' );
	}
	if ( ! wp_check_password( $password, $user->user_pass, $user_id ) ) {
		wp_send_json_error( 'Wrong password' );
	}
	if ( in_array( $method, array( 'bkash', 'nagad', 'rocket' ), true ) && ! preg_match( '/^[0-9]{11}$/', $account ) ) {
		wp_send_json_error( 'মোবাইল নাম্বার অবশ্যই ১১ সংখ্যার হতে হবে' );
	}

	$final_data = strtoupper( $method ) . ' - ' . $account;

	if ( 'bank' === $method ) {
		$account_name = isset( $_POST['acc_name'] )    ? sanitize_text_field( wp_unslash( $_POST['acc_name'] ) )    : '';
		$branch_name  = isset( $_POST['branch_name'] ) ? sanitize_text_field( wp_unslash( $_POST['branch_name'] ) ) : '';
		$bank_name    = isset( $_POST['bank_name'] )   ? sanitize_text_field( wp_unslash( $_POST['bank_name'] ) )   : '';

		if ( '' === $account_name || '' === $branch_name || '' === $bank_name ) {
			wp_send_json_error( 'ব্যাংকের সব তথ্য দিন' );
		}

		$final_data = 'BANK - ' . $account . ' | ' . $account_name . ' | ' . $branch_name . ' | ' . $bank_name;
	}

	update_user_meta( $user_id, 'uap_affiliate_bank_transfer_data', $final_data );
	update_user_meta( $user_id, 'uap_payment_details', $account );

	wp_send_json_success( 'Saved Successfully' );
}

add_action( 'wp_ajax_get_uap_payment', 'career_counseling_get_uap_payment' );

function career_counseling_get_uap_payment() {
	career_counseling_maybe_verify_ajax_nonce();

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Login required' );
	}

	wp_send_json_success( array(
		'data' => get_user_meta( get_current_user_id(), 'uap_affiliate_bank_transfer_data', true ),
	) );
}

// ================================================================
// ✅ END — PAYMENT METHOD SAVE / GET
// ================================================================


// ================================================================
// 17. PASSWORD CHANGE (Frontend Custom Form)
// ================================================================

function career_counseling_print_password_flag() {
	if ( ! is_user_logged_in() ) {
		return;
	}
	$user         = wp_get_current_user();
	$has_password = ! empty( $user->user_pass );
	echo '<script>window.hasPassword = ' . ( $has_password ? 'true' : 'false' ) . ';</script>';
}
add_action( 'wp_head', 'career_counseling_print_password_flag' );

function career_counseling_handle_custom_password_change() {
	if ( ! isset( $_POST['custom_pass_change'] ) ) {
		return;
	}

	$redirect_url = wp_get_referer() ? wp_get_referer() : home_url( '/affiliate-login/' );

	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( add_query_arg( 'pass_msg', 'login_required', home_url( '/affiliate-login/' ) ) );
		exit;
	}

	$user    = wp_get_current_user();
	$user_id = $user->ID;

	$new_password     = isset( $_POST['new_password'] )     ? (string) wp_unslash( $_POST['new_password'] )     : '';
	$confirm_password = isset( $_POST['confirm_password'] ) ? (string) wp_unslash( $_POST['confirm_password'] ) : '';

	if ( '' === $new_password || '' === $confirm_password ) {
		wp_safe_redirect( add_query_arg( 'pass_msg', 'empty', $redirect_url ) );
		exit;
	}

	if ( $new_password !== $confirm_password ) {
		wp_safe_redirect( add_query_arg( 'pass_msg', 'not_match', $redirect_url ) );
		exit;
	}

	if ( strlen( $new_password ) < 6 ) {
		wp_safe_redirect( add_query_arg( 'pass_msg', 'too_short', $redirect_url ) );
		exit;
	}

	if ( ! empty( $user->user_pass ) ) {
		$current_password = isset( $_POST['current_password'] ) ? (string) wp_unslash( $_POST['current_password'] ) : '';
		if ( '' === $current_password || ! wp_check_password( $current_password, $user->user_pass, $user_id ) ) {
			wp_safe_redirect( add_query_arg( 'pass_msg', 'wrong_current', $redirect_url ) );
			exit;
		}
	}

	wp_set_password( $new_password, $user_id );

	wp_safe_redirect( add_query_arg( 'pass_msg', 'success', home_url( '/affiliate-login/' ) ) );
	exit;
}
add_action( 'init', 'career_counseling_handle_custom_password_change' );

// ================================================================
// ✅ END — PASSWORD CHANGE
// ================================================================


// ================================================================
// 18. THANK YOU PAGE — SET PHONE AND PASSWORD HANDLER
// ================================================================

add_action( 'template_redirect', 'jg_handle_password_submit', 1 );

function jg_handle_password_submit() {
	if ( ! isset( $_POST['jg_set_password'] ) || ! function_exists( 'wc_get_order' ) ) {
		return;
	}

	$nonce     = sanitize_text_field( wp_unslash( $_POST['jg_nonce']     ?? '' ) );
	$order_id  = absint( $_POST['order_id']  ?? 0 );
	$order_key = sanitize_text_field( wp_unslash( $_POST['order_key']    ?? '' ) );
	$pass1     = trim( wp_unslash( $_POST['pass1'] ?? '' ) );
	$pass2     = trim( wp_unslash( $_POST['pass2'] ?? '' ) );

	// ✅ Phone নম্বর form থেকে নেওয়া হচ্ছে
	$phone     = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );

	if ( ! wp_verify_nonce( $nonce, 'jg_set_password_action' ) ) {
		jg_ty_redirect( 'security_error', $order_id );
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		jg_ty_redirect( 'invalid_order', $order_id );
	}

	if ( ! hash_equals( $order->get_order_key(), $order_key ) ) {
		jg_ty_redirect( 'invalid_key', $order_id );
	}

	if ( $pass1 === '' || $pass2 === '' ) {
		jg_ty_redirect( 'empty_password', $order_id );
	}

	// ── Strong password check ────────────────────────────────────
	if ( ! preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $pass1 ) ) {
		jg_ty_redirect( 'weak_password', $order_id );
	}

	if ( $pass1 !== $pass2 ) {
		jg_ty_redirect( 'mismatch', $order_id );
	}

	// ── Find user ────────────────────────────────────────────────
	$user = null;
	if ( is_user_logged_in() ) {
		$current = wp_get_current_user();
		if ( $current && ! empty( $current->ID ) ) {
			$user = $current;
		}
	}
	if ( ! $user ) {
		$user = get_user_by( 'email', $order->get_billing_email() );
	}
	if ( ! $user || empty( $user->ID ) ) {
		jg_ty_redirect( 'no_user_found', $order_id );
	}

	// ── Set password ──────────────────────────────────────────────
	wp_set_password( $pass1, $user->ID );

	// ── ✅ Phone / WhatsApp সব meta key-তে save করা হচ্ছে ────────
	if ( ! empty( $phone ) ) {
		update_user_meta( $user->ID, 'billing_phone',   $phone );
		update_user_meta( $user->ID, 'phone',            $phone );
		update_user_meta( $user->ID, 'mobile',           $phone );
		update_user_meta( $user->ID, 'whatsapp',         $phone );
		update_user_meta( $user->ID, 'whatsapp_number',  $phone );
		update_user_meta( $user->ID, 'uap_phone',        $phone );
		update_user_meta( $user->ID, 'ihc_phone',        $phone );

		// ✅ WooCommerce অর্ডারেও billing phone update
		$order->set_billing_phone( $phone );
		$order->save();
	}

	// ── Auto-login ────────────────────────────────────────────────
	$signon = wp_signon( array(
		'user_login'    => $user->user_login,
		'user_password' => $pass1,
		'remember'      => true,
	), is_ssl() );

	if ( ! is_wp_error( $signon ) ) {
		wp_set_current_user( $signon->ID );
		wp_set_auth_cookie( $signon->ID, true );
	}

	wp_safe_redirect( 'https://jobayergroup.com/my-progress/' );
	exit;
}

function jg_ty_redirect( $status, $order_id ) {
	$order = $order_id ? wc_get_order( $order_id ) : false;
	$url   = $order ? $order->get_checkout_order_received_url() : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'jg_pass_status', $status, $url ) );
	exit;
}

// ================================================================
// ✅ END — THANK YOU PAGE SET PASSWORD HANDLER
// ================================================================


// ================================================================
// 19. COD AUTO-COMPLETE + USER + AFFILIATE AUTO-CREATE
// ================================================================

add_action( 'woocommerce_checkout_order_processed', 'jg_auto_complete_cod_order', 20, 3 );

function jg_auto_complete_cod_order( $order_id, $posted_data, $order ) {
	if ( ! $order_id ) {
		return;
	}

	if ( ! $order instanceof WC_Order ) {
		$order = wc_get_order( $order_id );
	}

	if ( ! $order ) {
		return;
	}

	if ( 'cod' !== $order->get_payment_method() ) {
		return;
	}

	if ( $order->has_status( 'completed' ) ) {
		return;
	}

	$order->update_status( 'completed', 'Auto completed COD order' );
}

add_action( 'woocommerce_order_status_completed', 'jg_auto_create_user_and_affiliate' );

function jg_auto_create_user_and_affiliate( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );

	if ( ! $order ) {
		return;
	}

	$email      = $order->get_billing_email();
	$first_name = $order->get_billing_first_name();
	$last_name  = $order->get_billing_last_name();

	if ( empty( $email ) ) {
		return;
	}

	if ( email_exists( $email ) ) {
		$user    = get_user_by( 'email', $email );
		$user_id = $user->ID;
	} else {
		$password = wp_generate_password();
		do {
			$username = (string) wp_rand( 10000000000, 99999999999 );
		} while ( username_exists( $username ) );
		$user_id  = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			return;
		}

		wp_update_user( array(
			'ID'         => $user_id,
			'first_name' => $first_name,
			'last_name'  => $last_name,
		) );
	}

	if ( function_exists( 'uap_register_affiliate' ) ) {
		global $wpdb;
		$table = $wpdb->prefix . 'uap_affiliates';

		$exists = $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM $table WHERE uid = %d", $user_id )
		);

		if ( ! $exists ) {
			uap_register_affiliate( array(
				'uid'    => $user_id,
				'status' => 1,
			) );
		}
	}
}

// ================================================================
// ✅ END — COD AUTO-COMPLETE + USER + AFFILIATE AUTO-CREATE
// ================================================================


// ================================================================
// 20. ADMIN NOTICE HANDLERS
// ================================================================

function career_counseling_dismissed_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( 'Permission denied' );
	}
	update_option( 'career_counseling_admin_notice', true );
	wp_send_json_success();
}
add_action( 'wp_ajax_career_counseling_dismissed_notice', 'career_counseling_dismissed_notice' );

function career_counseling_getstart_setup_options() {
	update_option( 'career_counseling_admin_notice', false );
}
add_action( 'after_switch_theme', 'career_counseling_getstart_setup_options' );

// ================================================================
// ✅ END — ADMIN NOTICE HANDLERS
// ================================================================


// ================================================================
// 21. LOGOUT SYSTEM
// ================================================================

add_action( 'init', function () {
	if ( isset( $_GET['custom_logout'] ) && '1' === $_GET['custom_logout'] ) {
		if ( is_user_logged_in() ) {
			wp_logout();
		}
		wp_safe_redirect( 'https://jobayergroup.com/job-login/' );
		exit;
	}
} );

// ================================================================
// ✅ END — LOGOUT SYSTEM
// ================================================================


// ================================================================
// 22. USER WORK TIMING SETTINGS
// ================================================================

/**
 * Auto-activate pending settings if effective date has arrived.
 */
function ag_activate_pending_if_needed( $user_id ) {
	$pending_duration = get_user_meta( $user_id, 'ag_pending_duration', true );
	if ( empty( $pending_duration ) ) {
		return;
	}

	$effective_date = get_user_meta( $user_id, 'ag_pending_effective_date', true );
	if ( empty( $effective_date ) ) {
		return;
	}

	$tz    = new DateTimeZone( 'Asia/Dhaka' );
	$now   = new DateTime( 'now', $tz );
	$today = $now->format( 'Y-m-d' );

	if ( $today >= $effective_date ) {
		$duration   = (int) get_user_meta( $user_id, 'ag_pending_duration', true );
		$start_hour = (int) get_user_meta( $user_id, 'ag_pending_start_hour', true );
		$end_hour   = (int) get_user_meta( $user_id, 'ag_pending_end_hour', true );

		update_user_meta( $user_id, 'ag_work_duration',    $duration );
		update_user_meta( $user_id, 'ag_work_start_hour',  $start_hour );
		update_user_meta( $user_id, 'ag_work_end_hour',    $end_hour );

		delete_user_meta( $user_id, 'ag_pending_duration' );
		delete_user_meta( $user_id, 'ag_pending_start_hour' );
		delete_user_meta( $user_id, 'ag_pending_end_hour' );
		delete_user_meta( $user_id, 'ag_pending_effective_date' );
	}
}

/**
 * Check if current BD time is within a given work window.
 */
function ag_is_within_window( $start_hour, $end_hour ) {
	$tz                = new DateTimeZone( 'Asia/Dhaka' );
	$now               = new DateTime( 'now', $tz );
	$current_hour      = (int) $now->format( 'G' );
	$current_min       = (int) $now->format( 'i' );
	$current_total_min = $current_hour * 60 + $current_min;

	$start_total_min = $start_hour * 60;
	$end_total_min   = $end_hour * 60;

	if ( $end_hour > $start_hour ) {
		return ( $current_total_min >= $start_total_min && $current_total_min < $end_total_min );
	} elseif ( $end_hour < $start_hour ) {
		return ( $current_total_min >= $start_total_min || $current_total_min < $end_total_min );
	}
	return false;
}

/**
 * Get user work timing meta with pending auto-activation.
 */
function ag_get_user_work_settings_data( $user_id ) {
	ag_activate_pending_if_needed( $user_id );

	$duration   = (int) get_user_meta( $user_id, 'ag_work_duration', true );
	$start_hour = (int) get_user_meta( $user_id, 'ag_work_start_hour', true );
	$end_hour   = (int) get_user_meta( $user_id, 'ag_work_end_hour', true );

	$pending_duration        = get_user_meta( $user_id, 'ag_pending_duration', true );
	$pending_start_hour      = get_user_meta( $user_id, 'ag_pending_start_hour', true );
	$pending_end_hour        = get_user_meta( $user_id, 'ag_pending_end_hour', true );
	$pending_effective_date  = get_user_meta( $user_id, 'ag_pending_effective_date', true );

	$has_settings = ( $duration >= 4 && $duration <= 8 );

	return array(
		'has_settings' => $has_settings,
		'duration'     => $has_settings ? $duration : 8,
		'start_hour'   => $has_settings ? $start_hour : 16,
		'end_hour'     => $has_settings ? $end_hour : 24,
		'pending'      => array(
			'has_pending'    => ! empty( $pending_duration ),
			'duration'       => $pending_duration ? (int) $pending_duration : null,
			'start_hour'     => $pending_start_hour ? (int) $pending_start_hour : null,
			'end_hour'       => $pending_end_hour ? (int) $pending_end_hour : null,
			'effective_date' => $pending_effective_date ?: null,
		),
	);
}

// AJAX: Get user work settings
add_action( 'wp_ajax_ag_get_user_work_settings', 'ag_get_user_work_settings' );

function ag_get_user_work_settings() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Login required' );
	}

	$user_id     = get_current_user_id();
	$data        = ag_get_user_work_settings_data( $user_id );
	$is_within   = false;

	if ( $data['has_settings'] ) {
		$is_within = ag_is_within_window( $data['start_hour'], $data['end_hour'] );
	}
	$data['is_within_window'] = $is_within;

	wp_send_json_success( $data );
}

// AJAX: Save user work settings
add_action( 'wp_ajax_ag_save_user_work_settings', 'ag_save_user_work_settings' );

function ag_save_user_work_settings() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Login required' );
	}

	$user_id    = get_current_user_id();
	$duration   = isset( $_POST['duration'] )   ? absint( $_POST['duration'] )   : 0;
	$start_hour = isset( $_POST['start_hour'] ) ? absint( $_POST['start_hour'] ) : 0;

	if ( $duration < 4 || $duration > 8 ) {
		wp_send_json_error( 'কাজের সময় কমপক্ষে ৪ ঘণ্টা এবং সর্বোচ্চ ৮ ঘণ্টা হতে হবে' );
	}

	if ( $start_hour < 0 || $start_hour > 23 ) {
		wp_send_json_error( 'শুরুর সময় ০-২৩ এর মধ্যে হতে হবে' );
	}

	$end_hour = ( $start_hour + $duration ) % 24;

	$current_duration = (int) get_user_meta( $user_id, 'ag_work_duration', true );
	$has_existing     = ( $current_duration >= 4 && $current_duration <= 8 );

	if ( $has_existing ) {
		$current_start = (int) get_user_meta( $user_id, 'ag_work_start_hour', true );
		$current_end   = (int) get_user_meta( $user_id, 'ag_work_end_hour', true );

		if ( ag_is_within_window( $current_start, $current_end ) ) {
			$tz   = new DateTimeZone( 'Asia/Dhaka' );
			$now  = new DateTime( 'now', $tz );
			$today = $now->format( 'Y-m-d' );

			$tomorrow = new DateTime( 'now', $tz );
			$tomorrow->modify( '+1 day' );
			$effective_date = $tomorrow->format( 'Y-m-d' );

			update_user_meta( $user_id, 'ag_pending_duration',       $duration );
			update_user_meta( $user_id, 'ag_pending_start_hour',     $start_hour );
			update_user_meta( $user_id, 'ag_pending_end_hour',       $end_hour );
			update_user_meta( $user_id, 'ag_pending_effective_date', $effective_date );

			wp_send_json_success( array(
				'applied'        => 'pending',
				'effective_date' => $effective_date,
				'message'        => 'বর্তমান কাজের সময় শেষ হওয়ার পর (আগামীকাল থেকে) নতুন সময় কার্যকর হবে',
			) );
			return;
		}
	}

	update_user_meta( $user_id, 'ag_work_duration',   $duration );
	update_user_meta( $user_id, 'ag_work_start_hour', $start_hour );
	update_user_meta( $user_id, 'ag_work_end_hour',   $end_hour );

	delete_user_meta( $user_id, 'ag_pending_duration' );
	delete_user_meta( $user_id, 'ag_pending_start_hour' );
	delete_user_meta( $user_id, 'ag_pending_end_hour' );
	delete_user_meta( $user_id, 'ag_pending_effective_date' );

	wp_send_json_success( array(
		'applied' => 'immediate',
		'message' => 'আপনার কাজের সময় সফলভাবে সেট করা হয়েছে',
	) );
}

// ================================================================
// ✅ END — USER WORK TIMING SETTINGS
// ================================================================


// ================================================================
// 23. FIRST TARGET SEGMENT TRACKING
// ================================================================

/**
 * Track segment state for target 0 (3 segments, 1 person each).
 */
function ag_get_segment_state( $user_id, $start_hour, $end_hour, $people_count ) {
	$tz       = new DateTimeZone( 'Asia/Dhaka' );
	$now      = new DateTime( 'now', $tz );
	$today    = $now->format( 'Y-m-d' );
	$meta_key = 'ag_seg_' . $today;

	$total_seconds = 0;
	if ( $end_hour > $start_hour ) {
		$total_seconds = ( $end_hour - $start_hour ) * 3600;
	} else {
		$total_seconds = ( 24 - $start_hour + $end_hour ) * 3600;
	}
	$seg_duration = (int) ( $total_seconds / 3 );
	if ( $seg_duration < 1 ) {
		$seg_duration = 1;
	}

	$state = get_user_meta( $user_id, $meta_key, true );
	if ( empty( $state ) || ! is_array( $state ) ) {
		$state = array(
			'current'          => 0,
			'people_snapshot'  => max( 0, (int) $people_count ),
			'completed'        => array( false, false, false ),
			'retries'          => array( 0, 0, 0 ),
			'saved_time'       => 0,
			'initialized'      => true,
		);
		update_user_meta( $user_id, $meta_key, $state );
	}

	$current_people = max( 0, (int) $people_count );
	$snapshot       = max( 0, (int) $state['people_snapshot'] );
	$recruited      = ( $current_people > $snapshot );

	if ( $recruited && ! $state['completed'][ $state['current'] ] ) {
		$state['completed'][ $state['current'] ] = true;
		$state['saved_time']                     += (int) $seg_duration;
		$state['people_snapshot']                 = $current_people;

		if ( $state['current'] < 2 ) {
			$state['current']++;
		}

		update_user_meta( $user_id, $meta_key, $state );
	}

	$all_done = $state['completed'][0] && $state['completed'][1] && $state['completed'][2];

	return array(
		'current'          => $state['current'],
		'seg_duration_sec' => $seg_duration,
		'people_snapshot'  => $state['people_snapshot'],
		'people_now'       => $current_people,
		'completed'        => $state['completed'],
		'retries'          => $state['retries'],
		'saved_time'       => (int) $state['saved_time'],
		'target0_done'     => $all_done,
		'is_initialized'   => ! empty( $state['initialized'] ),
	);
}

/**
 * Reset segment tracking for a user (called when target 0 completes fully).
 */
function ag_reset_segment_state( $user_id ) {
	$tz    = new DateTimeZone( 'Asia/Dhaka' );
	$now   = new DateTime( 'now', $tz );
	$today = $now->format( 'Y-m-d' );
	delete_user_meta( $user_id, 'ag_seg_' . $today );
}

// ================================================================
// ✅ END — FIRST TARGET SEGMENT TRACKING
// ================================================================

















// ================================================================
// ✅ START — REPAREL / SEARE
// ================================================================


add_action('wp_ajax_jt_vtoken_stats', 'jt_vtoken_stats');
add_action('wp_ajax_nopriv_jt_vtoken_stats', 'jt_vtoken_stats');

function jt_uap_ref_key($u, $uid, $aff_id) {
  $candidates = [
    $u ? $u->user_login : '',
    $u ? $u->user_nicename : '',
    $u ? $u->display_name : '',
    'u' . (int) $uid,
    'a' . (int) $aff_id,
  ];

  foreach ($candidates as $c) {
    $c = sanitize_title(trim(wp_strip_all_tags((string) $c)));
    if ($c !== '') return $c;
  }

  return 'u' . (int) $uid;
}

function jt_vtoken_stats() {
  if (!is_user_logged_in()) {
    wp_send_json_error(['msg' => 'login_required'], 401);
  }

  global $wpdb;
  $p   = $wpdb->prefix;
  $uid = get_current_user_id();
  $u   = get_userdata($uid);

  if (!$u) {
    wp_send_json_error(['msg' => 'user_not_found'], 404);
  }

  $aff_id = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM {$p}uap_affiliates WHERE uid=%d AND status=1 LIMIT 1",
    $uid
  ));

  if (!$aff_id) {
    wp_send_json_error(['msg' => 'affiliate_missing'], 404);
  }

  // 1st choice: uap native function (exact match with dashboard)
  $ref_key = '';
  if (function_exists('uap_get_affiliate_ref_by_affiliate_id')) {
    $ref_key = uap_get_affiliate_ref_by_affiliate_id($aff_id);
  }
  // 2nd choice: direct DB query
  if (!$ref_key) {
    $ref_key = $wpdb->get_var($wpdb->prepare(
      "SELECT `name` FROM {$p}uap_affiliates WHERE id=%d",
      $aff_id
    ));
  }
  // 3rd choice: custom fallback
  if (!$ref_key) {
    $ref_key = jt_uap_ref_key($u, $uid, $aff_id);
  }
  $ref_url  = trailingslashit(home_url('/fast-checkout')) . '?ref=' . rawurlencode($ref_key);

  $tz = wp_timezone();
  $now = new DateTimeImmutable('now', $tz);

  $today6 = $now->setTime(6, 0, 0);
  if ($now < $today6) {
    $today6 = $today6->modify('-1 day');
  }
  $tomorrow6 = $today6->modify('+1 day');

  // Weekly reset: Friday 6 AM
  $weekday = (int) $today6->format('N'); // 1=Mon ... 7=Sun
  $daysFromFriday = ($weekday - 5 + 7) % 7;
  $weekStart = $today6->modify("-{$daysFromFriday} days");
  $weekEnd   = $weekStart->modify('+7 days');

  // Monthly reset: 1st day of month 6 AM
  $monthStart = new DateTimeImmutable($today6->format('Y-m-01 06:00:00'), $tz);
  if ($today6 < $monthStart) {
    $monthStart = $monthStart->modify('-1 month');
  }
  $monthEnd = $monthStart->modify('+1 month');

  $vis_table = "{$p}uap_visits";

  $count_visits = function($from, $to) use ($wpdb, $vis_table, $aff_id) {
    return (int) $wpdb->get_var($wpdb->prepare(
      "SELECT COUNT(DISTINCT ref_hash)
       FROM {$vis_table}
       WHERE affiliate_id=%d
         AND visit_date >= %s
         AND visit_date < %s",
      $aff_id,
      $from->format('Y-m-d H:i:s'),
      $to->format('Y-m-d H:i:s')
    ));
  };

  $today_vis = $count_visits($today6, $tomorrow6);
  $week_vis  = $count_visits($weekStart, $weekEnd);
  $month_vis = $count_visits($monthStart, $monthEnd);

  $total_vis = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(DISTINCT ref_hash) FROM {$vis_table} WHERE affiliate_id=%d",
    $aff_id
  ));

  $first_vis_date = $wpdb->get_var($wpdb->prepare(
    "SELECT MIN(visit_date) FROM {$p}uap_visits WHERE affiliate_id=%d",
    $aff_id
  ));

  $cached = [
    'me' => [
      'name'        => $u->display_name ?: $u->user_login,
      'affiliate_id'=> $aff_id,
      'ref_key'     => $ref_key,
      'ref_url'     => $ref_url,
      'today_vis'   => $today_vis,
      'week_vis'    => $week_vis,
      'month_vis'   => $month_vis,
      'total_vis'   => $total_vis,
      'first_vis_date' => $first_vis_date ?: current_time('Y-m-d'),
    ],
    'cycle' => [
      'daily'   => $today6->format('Y-m-d'),
      'weekly'  => $weekStart->format('Y-m-d'),
      'monthly' => $monthStart->format('Y-m'),
    ],
    'rule' => '1 visitor = 1 reward number'
  ];

  wp_send_json_success($cached);
}
// ================================================================
// ✅ END — REPAREL / SEARE
// ================================================================