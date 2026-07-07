<?php
/**
 * Template Name: Jobayer Group Landing + Checkout (All-in-One)
 * ল্যান্ডিং পেজ + WooCommerce চেকআউট — এক পেজে
 */

// ==================== AFFILIATE REF TRACKING ====================
$ref = '';

if (!empty($_GET['ref'])) {
    $ref = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['ref']);
}

if (empty($ref) && !empty($_COOKIE['uap_ref'])) {
    $ref = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_COOKIE['uap_ref']);
}

if (!empty($ref)) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie('uap_ref', $ref, time() + (30 * 24 * 60 * 60), '/', '', $secure, true);
}

$site_base = 'https://jobayergroup.com';
// ================================================================

// ==================== CHECKOUT PRODUCT CONFIG ====================
$light_checkout_product_id      = 81;  /* ⚠️ আপনার আসল WooCommerce Product ID দিন */
$light_checkout_quantity        = 1;
$light_checkout_product_bn_name = 'জোবায়ের গ্রুপ পেশা - স্টার্টিং রিসোর্স এক্সেস (৯৯ টাকা)';
// ================================================================

// ==================== PAGE CONFIG ====================
$cfg = array(
    'headline'     => '⏳ শেষবারের মতো অফার: ১০ লক্ষ টাকার ২৩০+ কোর্স আজ মাত্র ৯৯ টাকায়!',
    'subheadline'  => 'আগামী ২৪ ঘণ্টা পর দাম বেড়ে হবে ১,৪৯৯ টাকা। এই মুহূর্তে যুক্ত হলে পাচ্ছেন দেশের সেরা ১২ জন প্রশিক্ষকের ২৩০টির বেশি কোর্স — আজীবনের জন্য। পছন্দ না হলে ২৪ ঘণ্টায় টাকা ফেরত।',
    'timer_show'   => true,
    'price_anchor' => '১০,০০,০০০+',
    'cta_text'     => '🔥 হ্যাঁ, দাম বাড়ার আগে মাত্র ৯৯ টাকায় আজীবন অ্যাক্সেস নিন →',
);

// A/B Testing Variant — define before use in body_class
$ab_variant = 'a'; // default variant, change for A/B tests

// Helper: Bengali digit conversion
if (!function_exists('lcc_bn_to_en_number')) {
    function lcc_bn_to_en_number($str) {
        $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
        $en = array('0','1','2','3','4','5','6','7','8','9');
        return str_replace($bn, $en, $str);
    }
}

// Calculate savings for price anchor display
$cfg['price_anchor_raw'] = (int) preg_replace('/[^0-9]/', '', lcc_bn_to_en_number($cfg['price_anchor']));
$cfg['savings'] = max(0, $cfg['price_anchor_raw'] - 99);

// Bengali number formatter for consistent output
if (!function_exists('lcc_bn_number_format')) {
    function lcc_bn_number_format($num) {
        $en = array('0','1','2','3','4','5','6','7','8','9',',');
        $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯',',');
        return str_replace($en, $bn, number_format($num));
    }
}
// ================================================================

// ==================== BANGLA TEXT HELPER ====================
if (!function_exists('light_conversion_checkout_bn_text')) {
    function light_conversion_checkout_bn_text($text) {
        $map = array(
            'Billing details'        => 'আপনার তথ্য',
            'Your order'             => 'আপনার অর্ডার',
            'Product'                => 'পণ্য',
            'Subtotal'               => 'সাবটোটাল',
            'Total'                  => 'মোট',
            'Payment'                => 'পেমেন্ট',
            'Place order'            => 'নিরাপদে অর্ডার সম্পন্ন করুন',
            'Complete Secure Order Now' => 'নিরাপদে অর্ডার সম্পন্ন করুন',
            'First name'             => 'আপনার নামের প্রথম অংশ',
            'Last name'              => 'নামের শেষ অংশ',
            'Country / Region'       => 'দেশ / অঞ্চল',
            'Email address'          => 'ইমেইল ঠিকানা',
            'Phone'                  => 'মোবাইল নম্বর',
            'optional'               => 'ঐচ্ছিক',
            'Required'               => 'আবশ্যক',
            'Returning customer?'    => '',
            'Click here to login'    => '',
            'Have a coupon?'         => '',
            'Click here to enter your code' => '',
            'You must be logged in to checkout.' => '',
            'Invalid email address.' => 'সঠিক ইমেইল ঠিকানা লিখুন।',
            'Please enter a valid phone number.' => 'সঠিক মোবাইল নম্বর লিখুন।',
            '%s is a required field.' => '%s তথ্যটি অবশ্যই পূরণ করুন।',
            'Pay Online (Credit/Debit Card/MobileBanking/NetBanking/bKash)' => 'অনলাইনে পেমেন্ট করুন',
            'Pay securely by Credit/Debit card, Internet banking or Mobile banking through SSLCommerz.' => 'বিকাশ, নগদ বা রকেট — ১ ক্লিকে পেমেন্ট করুন!',
            'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.' => 'আপনার অর্ডার সম্পন্ন করা, পেমেন্ট যাচাই করা এবং প্রয়োজনীয় সহায়তা দেওয়ার জন্য আপনার তথ্য ব্যবহার করা হবে।',
            'privacy policy'         => 'গোপনীয়তা নীতি',
            'Bangladesh'             => 'বাংলাদেশ',
        );
        $plain = trim(wp_strip_all_tags((string) $text));
        if (isset($map[$plain])) return $map[$plain];
        if (isset($map[$text]))  return $map[$text];
        return $text;
    }
}
// ================================================================

// ==================== DUPLICATE PHONE / EMAIL CHECK ====================
if (!function_exists('lcc_has_existing_order_by_phone')) {
    function lcc_has_existing_order_by_phone($phone) {
        if (empty($phone)) return false;
        $orders = wc_get_orders(array('limit' => 1, 'billing_phone' => sanitize_text_field($phone), 'status' => array('wc-processing', 'wc-completed', 'wc-on-hold')));
        if (!empty($orders)) return true;
        $users = get_users(array('meta_key' => 'billing_phone', 'meta_value' => sanitize_text_field($phone), 'number' => 1, 'fields' => 'ids'));
        return !empty($users);
    }
}

if (!function_exists('lcc_has_existing_order_by_email')) {
    function lcc_has_existing_order_by_email($email) {
        if (empty($email) || !is_email($email)) return false;
        $orders = wc_get_orders(array('limit' => 1, 'billing_email' => sanitize_email($email), 'status' => array('wc-processing', 'wc-completed', 'wc-on-hold')));
        if (!empty($orders)) return true;
        $user = get_user_by('email', sanitize_email($email));
        if ($user) {
            $user_orders = wc_get_orders(array('limit' => 1, 'customer_id' => $user->ID, 'status' => array('wc-processing', 'wc-completed', 'wc-on-hold')));
            if (!empty($user_orders)) return true;
        }
        return false;
    }
}
// ================================================================

// ==================== WOOCOMMERCE HOOKS ====================

// Checkout validation — duplicate check
add_action('woocommerce_checkout_process', function () {
    $phone = isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
    $email = isset($_POST['billing_email']) ? sanitize_email(wp_unslash($_POST['billing_email'])) : '';
    if (!empty($phone) && lcc_has_existing_order_by_phone($phone)) {
        wc_add_notice('❌ এই মোবাইল নম্বর (' . esc_html($phone) . ') দিয়ে ইতোমধ্যে একটি অর্ডার সম্পন্ন হয়েছে।', 'error');
        return;
    }
    if (!empty($email) && lcc_has_existing_order_by_email($email)) {
        wc_add_notice('❌ এই ইমেইল ঠিকানা (' . esc_html($email) . ') দিয়ে ইতোমধ্যে একটি অর্ডার সম্পন্ন হয়েছে।', 'error');
        return;
    }
});

// Guest checkout settings
add_filter('pre_option_woocommerce_enable_guest_checkout', function () { return 'yes'; }, 999);
add_filter('pre_option_woocommerce_enable_checkout_login_reminder', function () { return 'no'; }, 999);
add_filter('woocommerce_checkout_registration_required', '__return_false', 999);
add_filter('woocommerce_checkout_registration_enabled', '__return_true', 999);
add_filter('woocommerce_enable_order_notes_field', '__return_false', 999);
add_filter('woocommerce_cart_needs_shipping', '__return_false', 999);
add_filter('woocommerce_checkout_must_be_logged_in_message', function () { return ''; }, 999);

add_filter('woocommerce_get_terms_and_conditions_checkbox_text', function () { return 'আমি ওয়েবসাইটের শর্তাবলি পড়ে সম্মতি দিচ্ছি।'; }, 999);
add_filter('woocommerce_checkout_required_field_notice', function ($message, $field_label) { return '<strong>' . wp_kses_post($field_label) . '</strong> তথ্যটি অবশ্যই পূরণ করুন।'; }, 999, 2);

// Billing fields — only 5 fields
add_filter('woocommerce_checkout_fields', function ($fields) {
    $keep = array('billing_first_name', 'billing_phone', 'billing_email');
    foreach (array_keys($fields['billing']) as $key) {
        if (!in_array($key, $keep, true) && strpos($key, 'uap_') !== 0) unset($fields['billing'][$key]);
    }
    $billing_settings = array(
        'billing_first_name' => array('label' => 'আপনার সম্পূর্ণ নাম', 'placeholder' => 'যেমন: সাকিব আহমেদ', 'required' => true, 'priority' => 10),
        'billing_phone'      => array('label' => 'বিকাশ/নগদ নম্বর', 'placeholder' => 'যেমন: 01XXXXXXXXX', 'required' => true, 'priority' => 20, 'custom_attributes' => array('maxlength' => '11', 'pattern' => '[0-9]{11}', 'inputmode' => 'numeric')),
        'billing_email'      => array('label' => 'সঠিক ইমেইল ঠিকানা', 'placeholder' => 'যেমন: sakib@gmail.com', 'required' => true, 'priority' => 30),
    );
    foreach ($billing_settings as $key => $settings) {
        if (isset($fields['billing'][$key])) {
            foreach ($settings as $sk => $sv) $fields['billing'][$key][$sk] = $sv;
        }
    }
    if (isset($fields['order']['order_comments'])) unset($fields['order']['order_comments']);
    return $fields;
}, 999);

add_filter('woocommerce_default_address_fields', function ($fields) {
    $remove = array('address_1', 'address_2', 'city', 'state', 'postcode', 'company');
    foreach ($remove as $key) { if (isset($fields[$key])) unset($fields[$key]); }
    return $fields;
}, 999);

add_filter('default_checkout_billing_country', function () { return 'BD'; }, 999);

add_filter('woocommerce_countries', function ($countries) {
    if (isset($countries['BD'])) $countries['BD'] = 'বাংলাদেশ';
    return $countries;
}, 999);

// Product name in Bangla
add_filter('woocommerce_cart_item_name', function ($name, $cart_item) use ($light_checkout_product_id, $light_checkout_product_bn_name) {
    $pid = isset($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
    $vid = isset($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;
    return ($pid === (int) $light_checkout_product_id || $vid === (int) $light_checkout_product_id) ? esc_html($light_checkout_product_bn_name) : $name;
}, 999, 2);

add_filter('woocommerce_order_item_name', function ($name, $item) use ($light_checkout_product_id, $light_checkout_product_bn_name) {
    if (is_a($item, 'WC_Order_Item_Product')) {
        $pid = (int) $item->get_product_id();
        $vid = method_exists($item, 'get_variation_id') ? (int) $item->get_variation_id() : 0;
        return ($pid === (int) $light_checkout_product_id || $vid === (int) $light_checkout_product_id) ? esc_html($light_checkout_product_bn_name) : $name;
    }
    return $name;
}, 999, 2);



// General translation
add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain !== 'woocommerce') return $translated;
    $new_text = light_conversion_checkout_bn_text($translated);
    return ($new_text !== $translated) ? $new_text : light_conversion_checkout_bn_text($text);
}, 999, 3);

add_filter('ngettext', function ($translation, $single, $plural, $number, $domain) {
    if ($domain !== 'woocommerce') return $translation;
    return light_conversion_checkout_bn_text($translation);
}, 999, 5);

// Save affiliate ref to order meta
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['jg_affiliate_ref'])) {
        update_post_meta($order_id, '_affiliate_ref', sanitize_text_field($_POST['jg_affiliate_ref']));
    }
});

// ================================================================

// ==================== RESULT PAGE CHECK ====================
if (!function_exists('light_conversion_checkout_is_result_page')) {
    function light_conversion_checkout_is_result_page() {
        if (function_exists('is_order_received_page') && is_order_received_page()) return true;
        if (function_exists('is_wc_endpoint_url')) {
            if (is_wc_endpoint_url('order-received') || is_wc_endpoint_url('order-pay')) return true;
        }
        $uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        return (false !== strpos($uri, 'order-received') || false !== strpos($uri, 'order-pay'));
    }
}
// ================================================================

// ==================== PRODUCT CART ADD HELPER ====================
if (!function_exists('light_conversion_checkout_add_product')) {
    function light_conversion_checkout_add_product($product_id, $quantity) {
        if (!function_exists('WC') || !function_exists('wc_get_product') || !WC()->cart) return false;
        $product = wc_get_product($product_id);
        if (!$product) return false;
        if ($product->is_type('variation')) return WC()->cart->add_to_cart($product->get_parent_id(), $quantity, $product->get_id(), $product->get_variation_attributes());
        if ($product->is_type('variable')) {
            $available_variations = $product->get_available_variations();
            if (!empty($available_variations[0]['variation_id'])) {
                $vp = wc_get_product($available_variations[0]['variation_id']);
                if ($vp) return WC()->cart->add_to_cart($product->get_id(), $quantity, $vp->get_id(), $vp->get_variation_attributes());
            }
            return false;
        }
        return WC()->cart->add_to_cart($product_id, $quantity);
    }
}
// ================================================================

// ==================== CART LOCK ====================
if (function_exists('is_checkout') && is_checkout() && function_exists('WC') && function_exists('wc_get_product') && !is_admin() && !(function_exists('wp_doing_ajax') && wp_doing_ajax()) && !light_conversion_checkout_is_result_page() && $light_checkout_product_id > 0) {
    if (null === WC()->cart) WC()->initialize();
    if (WC()->session && method_exists(WC()->session, 'set_customer_session_cookie')) {
        WC()->session->set_customer_session_cookie(true);
    }
    if (WC()->cart && wc_get_product($light_checkout_product_id)) {
        $already_correct = false;
        $cart_items = WC()->cart->get_cart();
        if (1 === count($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $cpid = isset($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
                $cvid = isset($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;
                $cqty = isset($cart_item['quantity']) ? (int) $cart_item['quantity'] : 0;
                if ($cqty === (int) $light_checkout_quantity && ($cpid === (int) $light_checkout_product_id || $cvid === (int) $light_checkout_product_id)) {
                    $already_correct = true;
                }
            }
        }
        if (!$already_correct) {
            foreach ($cart_items as $cart_item_key => $cart_item) {
                WC()->cart->remove_cart_item($cart_item_key);
            }
            $added = light_conversion_checkout_add_product($light_checkout_product_id, $light_checkout_quantity);
            if ($added) {
                WC()->cart->calculate_totals();
            } else if (function_exists('wc_add_notice')) {
                wc_add_notice('পণ্যটি বর্তমানে যুক্ত করা সম্ভব হচ্ছে না। আবার চেষ্টা করুন।', 'error');
            }
        }
    }
}
// ================================================================

// ==================== AJAX DUPLICATE CHECK HANDLER ====================
add_action('wp_ajax_lcc_check_duplicate', 'lcc_ajax_check_duplicate');
add_action('wp_ajax_nopriv_lcc_check_duplicate', 'lcc_ajax_check_duplicate');

if (!function_exists('lcc_ajax_check_duplicate')) {
  function lcc_ajax_check_duplicate() {
    check_ajax_referer('lcc_duplicate_check', 'nonce', true);
    $type  = isset($_POST['type'])  ? sanitize_text_field(wp_unslash($_POST['type']))  : '';
    $value = isset($_POST['value']) ? sanitize_text_field(wp_unslash($_POST['value'])) : '';
    if (empty($type) || empty($value)) { wp_send_json(array('exists' => false)); }
    $exists = false;
    if ('phone' === $type) $exists = lcc_has_existing_order_by_phone($value);
    elseif ('email' === $type) $exists = lcc_has_existing_order_by_email($value);
    wp_send_json(array('exists' => $exists));
  }
}
// ================================================================

// ==================== THANKYOU — VIRAL SHARE LOOP ====================
add_action('woocommerce_thankyou', function ($order_id) {
    if (!$order_id) return;
    $order = wc_get_order($order_id);
    if (!$order) return;
    $ref = $order->get_meta('_affiliate_ref');
    if (empty($ref)) {
        $ref = wp_generate_password(16, false);
        $order->update_meta_data('_partner_ref', $ref);
        $order->save();
    }
    $share_url = 'https://jobayergroup.com/dashboard/?ref=' . urlencode($ref);
    ?>
    <div style="max-width:600px;margin:30px auto;padding:24px;border-radius:16px;background:#fff;border:1px solid #E2E8F0;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,.08);">
        <h2 style="color:#1E293B;font-size:24px;margin:0 0 8px;">🎉 অভিনন্দন! আপনার অ্যাকাউন্ট সফলভাবে খুলে গেছে!</h2>
        <p style="color:#64748B;font-size:16px;margin:0 0 16px;">আপনার ব্যক্তিগত শেয়ার লিংক তৈরি হয়েছে। আপনি এখনই এই লিংকটি কপি করে আপনার ৩ জন বন্ধু বা সহপাঠীর সাথে শেয়ার করুন। তারা যুক্ত হওয়ামাত্রই আপনার অ্যাকাউন্টে আয় জমা হতে শুরু করবে এবং আপনার আজীবনের জন্য মাসিক বোনাস পাওয়ার যাত্রা শুরু হবে।</p>
        <div style="background:#F8F9FA;border-radius:8px;padding:12px 16px;margin:0 auto 16px;word-break:break-all;font-size:14px;font-weight:700;color:#1E293B;border:1px solid #E2E8F0;"><?php echo esc_html($share_url); ?></div>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="https://wa.me/?text=<?php echo urlencode('এই সুযোগটি দেখুন! ' . $share_url); ?>" target="_blank" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#25D366;color:#fff;font-weight:900;text-decoration:none;font-size:15px;">📱 হোয়াটসঅ্যাপে শেয়ার করুন</a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#1877F2;color:#fff;font-weight:900;text-decoration:none;font-size:15px;">📘 ফেসবুকে শেয়ার করুন</a>
        </div>
        <p style="color:#64748B;font-size:13px;margin-top:16px;">🔥 মাত্র ৩ জন বন্ধু যুক্ত করলেই আপনার মাসিক আয় পাওয়ার যাত্রা শুরু!</p>
    </div>
    <?php
}, 10, 1);

add_filter('woocommerce_thankyou_order_received_text', function ($text, $order) {
    return 'আপনার পেমেন্ট সফল হয়েছে! নিচে আপনার ব্যক্তিগত শেয়ার লিংক ও শেয়ার করার অপশন দেওয়া আছে।';
}, 10, 2);

// ================================================================

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Jobayer Group Career — ৯৯ টাকায় পেশা শুরু করুন</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.youtube.com">
    <link rel="preconnect" href="https://i.ytimg.com">
    <link rel="dns-prefetch" href="https://jobayergroup.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700;800;900&display=swap&subset=bengali" rel="stylesheet" media="print" onload="this.media='all'">

    <?php wp_head(); ?>

    <style>
        :root{
            --bg:#FFFDF5;
            --bg2:#FFFFFF;
            --card:#FFFFFF;
            --text:#1E293B;
            --muted:#64748B;
            --border:#E2E8F0;
            --shadow:0 18px 50px rgba(0,0,0,.08);
            --accent:#1D4ED8;
            --accent2:#FF6B35;
            --cta:#FF6B35;
            --cta-dark:#E85D2C;
            --accent-blue:#1D4ED8;
            --cta-orange:var(--cta);
            --cta-orange-dark:var(--cta-dark);
            --urgency:#DC2626;
            --trust:#10B981;
            --trust-blue:#1D4ED8;
            --trust-blue-dark:#1E3A8A;
            --secure:#0284C7;
            --price:#16A34A;
            --price-dark:#15803D;
            --gold:#FFBF00;
            --amber:#FFB300;
            --glow:rgba(29,78,216,.15);
        }

        .glass-card{
            background:#FFFFFF;
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            border:1px solid #E2E8F0;
            border-radius:20px;
            box-shadow:0 4px 12px rgba(0,0,0,.04);
        }
        .glass-card:hover{
            background:#FFFFFF;
            border-color:#E2E8F0;
        }
        .glass-card-highlight{
            background:linear-gradient(135deg,rgba(29,78,216,.04),rgba(234,88,12,.03));
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            border:1px solid rgba(29,78,216,.15);
            border-radius:20px;
        }

        *{
            box-sizing:border-box;
            font-family:'Hind Siliguri',Arial,Noto Sans Bengali,sans-serif;
        }
        @media(prefers-reduced-motion:reduce){
            *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important;}
        }

        #checkoutSection input, #checkoutSection select, #checkoutSection textarea{font-size:16px;}

        html{
            scroll-behavior:smooth;
            overflow-y:auto;
            width:100%;
        }

        body{
            margin:0;
            color:var(--text);
            width:100%;
            overflow-x:hidden;
            -webkit-tap-highlight-color:transparent;
            font-size:16px;
            line-height:1.6;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(29,78,216,.08) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 100%, rgba(234,88,12,.05) 0%, transparent 60%),
                var(--bg);
        }

        img{
            max-width:100%;
            display:block;
        }

        .landing-shell{
            max-width:1120px;
            margin:auto;
            padding:18px 12px 80px;
        }

        .logo{
            margin-top:10px;
            text-align:center;
            font-size:28px;
            font-weight:900;
            line-height:1.15;
        }

        .logo .logo-main{color:#1E3A8A;}
        .logo .logo-sub{display:block;font-size:18px;opacity:.7;font-weight:700;}

        .hero-card{
            max-width:980px;
            margin:12px auto 0;
            padding:16px 16px 14px;
            border-radius:20px;
            background:var(--card);
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            border:1px solid var(--border);
            box-shadow:0 18px 50px rgba(0,0,0,.08);
            text-align:center;
        }

        .headline-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:10px 16px;
            margin-bottom:14px;
            border-radius:999px;
            background:rgba(29,78,216,.08);
            border:1px solid rgba(29,78,216,.15);
            color:var(--text);
            font-size:14px;
            font-weight:800;
            word-break:break-word;
            overflow-wrap:break-word;
        }

        .hero-card h1{
            margin:0 0 8px;
            font-size:23px;
            line-height:1.35;
            font-weight:900;
            color:#1E3A8A;
        }
        .hero-card h1 span{background:linear-gradient(135deg,var(--accent-blue),var(--cta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}

        .hero-card h3{
            margin:0 0 12px;
            font-size:17px;
            line-height:1.45;
            font-weight:900;
            color:#1E3A8A;
        }

        .info-box{
            max-width:820px;
            margin:0 auto;
            padding:18px 18px 14px;
            border-radius:20px;
            background:linear-gradient(135deg, rgba(29,78,216,.06), rgba(234,88,12,.06), rgba(29,78,216,.04));
            border:1px solid var(--border);
            text-align:left;
        }

        .video-text{
            margin:0 0 12px;
            text-align:center;
            color:var(--muted);
            font-size:15px;
            line-height:1.75;
            font-weight:700;
        }

        .feature{
            display:flex;
            align-items:flex-start;
            gap:10px;
            margin:10px 0 0;
            padding:12px 14px;
            border-radius:14px;
            background:var(--card);
            border:1px solid var(--border);
            color:var(--text);
            font-weight:800;
            font-size:14px;
            line-height:1.6;
            box-shadow:0 8px 18px rgba(0,0,0,.04);
        }

        .checkoutBtn{
            display:flex;
            justify-content:center;
            align-items:center;
            width:100%;
            max-width:500px;
            min-height:56px;
            padding:16px 24px;
            margin:20px auto 0;
            border:none;
            border-radius:16px;
            background:linear-gradient(135deg,var(--cta),var(--cta-dark));
            color:#fff;
            text-decoration:none;
            font-size:19px;
            font-weight:900;
            line-height:1.4;
            cursor:pointer;
            touch-action:manipulation;
            box-shadow:0 16px 32px rgba(234,88,12,.35);
            text-align:center;
            word-break:break-word;
            transition:transform .2s ease,box-shadow .2s ease,filter .2s ease, background .2s ease;
            letter-spacing:.3px;
        }
        .checkoutBtn:hover{
            transform:translateY(-2px);
            filter:saturate(1.08);
            box-shadow:0 20px 40px rgba(234,88,12,0.4);
        }

        .pulse-cta{
            animation:pulse-urgency 1.8s infinite !important;
        }

        .mid-cta{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            max-width:100%;
            width:fit-content;
            min-width:200px;
            margin:20px auto;
            padding:14px 24px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,var(--cta),var(--cta-dark));
            color:#fff;
            font-weight:900;
            font-size:15px;
            cursor:pointer;
            box-shadow:0 12px 28px var(--glow);
            transition:transform .2s,box-shadow .2s;
            text-decoration:none;
            text-align:center;
            line-height:1.4;
            touch-action:manipulation;
        }
        .mid-cta:hover{transform:translateY(-2px);box-shadow:0 16px 32px var(--glow);}
        .mid-cta:active{transform:translateY(0);}

        #videoWrapper{
            position:relative;
            margin-top:24px;
            border-radius:18px;
            overflow:hidden;
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:var(--shadow);
        }

        #videoWrapper:fullscreen,
        #videoWrapper:-webkit-full-screen{
            width:100vw;
            height:100vh;
            margin:0;
            border-radius:0;
            background:#000;
        }

        #videoFrame{
            display:block;
            width:100%;
            height:56.25vw;
            min-height:225px;
            max-height:430px;
            background:#000;
        }

        #videoFrame iframe{
            width:100% !important;
            height:100% !important;
            display:block;
            border:0;
        }

        #videoWrapper:fullscreen #videoFrame,
        #videoWrapper:-webkit-full-screen #videoFrame{
            width:100%;
            height:100%;
        }

        .videoCover{
            position:absolute;
            inset:0;
            z-index:9;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
            background:
                linear-gradient(180deg,rgba(255,255,255,.08),rgba(0,0,0,.12)),
                url("https://img.youtube.com/vi/nRmNR13u0-g/maxresdefault.jpg") center/cover no-repeat;
            text-align:center;
        }

        .videoCover.is-hidden{
            display:none;
        }

        .coverPlay{
            width:72px;height:72px;border-radius:50%;
            background:linear-gradient(135deg,var(--accent-blue),var(--cta));
            color:#fff;font-size:32px;font-weight:900;
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;box-shadow:0 8px 24px var(--glow);
            transition:transform .2s,box-shadow .2s;
        }
        .coverPlay:hover{transform:scale(1.08);box-shadow:0 12px 32px var(--glow);}

        .speed-controls{
            position:absolute;top:12px;right:12px;z-index:12;
            display:flex;gap:4px;
            opacity:0;transition:opacity .3s;
            background:rgba(0,0,0,.65);
            border-radius:8px;padding:4px;
        }
        #videoWrapper:hover .speed-controls,
        #videoWrapper.is-playing .speed-controls{opacity:1;}
        .speed-btn{
            background:transparent;border:0;
            color:rgba(255,255,255,.6);font-size:12px;font-weight:700;
            padding:4px 10px;border-radius:5px;cursor:pointer;
            font-family:inherit;transition:all .15s;
        }
        .speed-btn:hover{color:#fff;background:rgba(255,255,255,.12);}
        .speed-btn.active{color:#fff;background:rgba(29,78,216,.75);}

        .youtubeMask{
            position:absolute;
            left:0;
            right:0;
            z-index:3;
            pointer-events:none;
        }

        .youtubeMaskTop{
            top:0;
            height:56px;
            background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,0));
        }

        .youtubeMaskBottom{
            bottom:0;
            height:66px;
            background:linear-gradient(0deg,rgba(255,255,255,.96),rgba(255,255,255,0));
        }

        #videoWrapper:fullscreen .youtubeMask,
        #videoWrapper:-webkit-full-screen .youtubeMask{
            display:none;
        }

        .videoLine{
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            z-index:11;
            height:7px;
            background:rgba(0,0,0,.08);
        }

        #videoLineFill{
            display:block;
            width:0%;
            height:100%;
            background:linear-gradient(90deg,var(--accent),var(--accent2));
            transition:width .25s ease;
        }

        .section-wrap{
            max-width:1120px;
            margin:24px auto 0;
        }

        .section-divider{
            width:80px;
            height:3px;
            margin:32px auto;
            border-radius:2px;
            background:linear-gradient(90deg,var(--accent-blue),var(--cta));
            opacity:.3;
        }

        .info-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:14px;
        }

        .info-card{
            background:var(--card);
            backdrop-filter:blur(16px);
            -webkit-backdrop-filter:blur(16px);
            border:1px solid var(--border);
            border-radius:20px;
            box-shadow:0 8px 24px rgba(0,0,0,.06);
            padding:18px 16px 16px;
            position:relative;
            overflow:hidden;
            transition:box-shadow .2s ease;
        }
        .info-card:hover{
            box-shadow:0 12px 36px rgba(0,0,0,.1);
        }

        .info-card::before{
            content:"";
            position:absolute;
            left:0;
            top:0;
            width:100%;
            height:4px;
            background:linear-gradient(90deg,var(--accent),var(--accent2),var(--accent));
        }

        .info-card h3{
            margin:0 0 10px;
            font-size:18px;
            line-height:1.4;
            font-weight:900;
            color:var(--text);
        }

        .info-card .subline{
            margin:0 0 14px;
            color:var(--muted);
            font-size:15px;
            line-height:1.75;
            font-weight:600;
        }

        .group-list{
            display:grid;
            gap:10px;
            margin:0;
            padding:0;
            list-style:none;
        }

        .group-list li{
            display:flex;
            gap:10px;
            align-items:flex-start;
            color:var(--text);
            font-weight:700;
            line-height:1.65;
            font-size:15px;
        }

        .overview-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:10px;
            margin:0;
            padding:0;
        }
        .overview-item{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:14px;
            padding:16px 15px;
            cursor:pointer;
            touch-action:manipulation;
            transition:all 0.2s;
            display:flex;
            flex-direction:column;
            gap:5px;
        }
        .overview-item:hover{
            background:rgba(255,255,255,.08);
            border-color:var(--accent-blue);
            transform:translateY(-2px);
            box-shadow:0 6px 20px var(--glow);
        }
        .overview-icon{
            font-size:26px;
            line-height:1;
        }
        .overview-title{
            font-weight:700;
            font-size:14px;
            color:var(--text);
            line-height:1.3;
        }
        .overview-desc{
            font-size:12.5px;
            color:var(--muted);
            line-height:1.5;
        }
        @media(min-width:640px){
            .overview-grid{grid-template-columns:1fr 1fr;gap:12px;}
        }

        .group-list li span{
            flex:0 0 auto;
            width:24px;
            height:24px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:rgba(29,78,216,.12);
            color:var(--accent-blue);
            font-weight:900;
            margin-top:1px;
        }

        .chip-row{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .chip{
            padding:10px 14px;
            border-radius:999px;
            background:var(--card);
            border:1px solid var(--border);
            color:var(--text);
            font-weight:800;
            font-size:14px;
            line-height:1.25;
        }

        .value-shock{
            grid-column:1 / -1;
            background:linear-gradient(135deg,rgba(29,78,216,.08),rgba(234,88,12,.08),rgba(29,78,216,.06));
        }

        .value-shock .big-value{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            align-items:center;
            justify-content:center;
            margin-top:10px;
        }

        .value-badge{
            padding:10px 14px;
            border-radius:999px;
            background:var(--card);
            border:1px solid var(--border);
            font-weight:900;
            color:var(--text);
            box-shadow:0 8px 18px rgba(0,0,0,.15);
        }

        .floatingTimer{
            position:fixed;
            left:50%;
            bottom:100px;
            transform:translateX(-50%);
            z-index:9999;
            min-width:min(94vw,560px);
            max-width:560px;
            padding:14px 18px;
            border-radius:18px;
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:0 16px 40px rgba(0,0,0,.08);
            text-align:center;
            color:var(--text);
            font-weight:900;
            font-size:14px;
            line-height:1.6;
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
            display:none;
        }

        .floatingTimer strong{
            display:block;
            margin-top:4px;
            font-size:16px;
        }

        @media(min-width:769px){
            .landing-shell{padding:24px 20px 100px;}
            .logo{font-size:38px;}
            .hero-card{padding:28px 28px 24px;border-radius:24px;}
            .hero-card h1{font-size:36px;line-height:1.3;letter-spacing:-.3px;}
            .hero-card h3{font-size:22px;}
            .video-text{font-size:16px;}
            .info-card{padding:20px 20px 18px;}
            .info-card h3{font-size:20px;}
            #videoFrame{height:550px;min-height:0;max-height:none;}
            .salary-dashboard{padding:18px;border-radius:16px;}
            .salary-wrapper th,.salary-wrapper td{padding:12px;font-size:14px;}
            .gallery-slider-inner img{height:320px;}
            .gallery-amount{font-size:14px;padding:4px 10px;bottom:8px;left:8px;border-radius:8px;}
            .checkoutCta h2{font-size:26px;}
            .floatingTimer{min-width:min(92vw,560px);font-size:16px;bottom:90px;}
            .floatingTimer strong{font-size:18px;}
            .mentor-table-wrap table{font-size:14px;}
            .mentor-table-wrap th,.mentor-table-wrap td{padding:10px 8px;}
            .feature{font-size:15px;}
        }

        #checkoutCtaSection{
            display:block;
        }

        .salary-section,
        .content-section{
            max-width:1120px;
            margin:44px auto 0;
            padding:15px 0;
            content-visibility:auto;
            contain-intrinsic-size:400px;
        }

        .salary-wrapper{
            max-width:1120px;
            margin:0 auto;
            padding:16px;
            background:var(--card);
            border:1px solid var(--border);
            border-radius:18px;
            box-shadow:var(--shadow);
            backdrop-filter:blur(10px);
            -webkit-backdrop-filter:blur(10px);
        }

        .salary-dashboard{
            width:100%;
            background:rgba(255,255,255,.04);
            border-radius:14px;
            padding:14px;
            color:var(--text);
            overflow:hidden;
            border:1px solid var(--border);
            backdrop-filter:blur(10px);
            -webkit-backdrop-filter:blur(10px);
        }

        .salary-header{
            display:flex;
            flex-direction:column;
            align-items:center;
            text-align:center;
            gap:8px;
            padding:16px;
            border-radius:16px;
            background:linear-gradient(90deg,rgba(29,78,216,.1),rgba(234,88,12,.1),rgba(29,78,216,.1));
            border:1px solid var(--border);
            margin-bottom:18px;
        }

        .salary-header-top{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            font-size:22px;
            font-weight:900;
        }

        .live-circle{
            width:13px;
            height:13px;
            border-radius:50%;
            background:var(--accent2);
            animation:liveGlow 1s infinite;
        }

        @keyframes liveGlow{
            0%{box-shadow:0 0 5px rgba(234,88,12,.8)}
            50%{box-shadow:0 0 20px rgba(234,88,12,1)}
            100%{box-shadow:0 0 5px rgba(234,88,12,.8)}
        }

        .salary-header-subtitle{
            font-size:15px;
            font-weight:700;
            color:var(--muted);
        }

        .tableWrap{
            max-height:600px;
            overflow-y:auto;
            overflow-x:auto;
                scroll-behavior:smooth;
            border-radius:16px;
            border:1px solid var(--border);
            background:var(--card);
        }

        .tableWrap::-webkit-scrollbar{
            width:6px;
        }

        .tableWrap::-webkit-scrollbar-thumb{
            background:var(--accent2);
            border-radius:8px;
        }

        .salary-wrapper table{
            width:100%;
            border-collapse:collapse;
        }

        .salary-wrapper thead{
            position:sticky;
            top:0;
            z-index:2;
            background:rgba(255,255,255,.04);
        }

        .salary-wrapper th,
        .salary-wrapper td{
            padding:10px 8px;
            font-size:13px;
            text-align:left;
            border-bottom:1px solid var(--border);
        }

        .salary-wrapper th{
            color:var(--text);
            font-weight:900;
        }

        .salary-wrapper tbody tr:nth-child(odd){
            background:rgba(255,255,255,.04);
        }

        .salary-wrapper tbody tr:nth-child(even){
            background:rgba(255,255,255,.02);
        }

        .success-row{
            background:rgba(255,191,0,.12)!important;
            color:#B8860B;
            font-weight:800;
        }

        .section-title{
            text-align:center;
            font-size:24px;
            font-weight:900;
            margin-bottom:8px;
            color:var(--text);
        }

        .section-subtitle{
            max-width:820px;
            margin:0 auto 20px;
            color:var(--muted);
            text-align:center;
            line-height:1.75;
            font-weight:600;
        }

        .gallery-slider-wrap{
            position:relative;
            overflow:hidden;
            border-radius:16px;
            max-width:100%;
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:0 10px 22px rgba(0,0,0,.08);
            margin:0 auto;
            max-width:500px;
        }

        .gallery-slider-inner{
            display:flex;
            transition:transform .5s ease;
        }

        .gallery-slider-inner .img-box{
            flex:0 0 100%;
            position:relative;
            background:var(--card);
            max-width:100%;
        }

        .gallery-slider-inner img{
            width:100%;
            height:220px;
            object-fit:cover;
            display:block;
            pointer-events:none;
            user-select:none;
        }

        .gallery-amount{
            position:absolute;
            bottom:5px;
            left:5px;
            background:rgba(220,38,38,.88);
            color:#fff;
            padding:3px 7px;
            border-radius:6px;
            font-size:11px;
            font-weight:700;
            z-index:2;
        }

        .checkoutCta{
            max-width:820px;
            margin:52px auto 0;
            padding:28px 20px;
            border-radius:20px;
            text-align:center;
            background:linear-gradient(135deg,rgba(29,78,216,.06),rgba(234,88,12,.10),rgba(29,78,216,.06));
            border:1px solid var(--border);
            box-shadow:0 18px 50px rgba(0,0,0,.08);
        }

        .checkoutCta h2{
            margin:0 0 10px;
            font-size:24px;
            line-height:1.3;
            color:var(--text);
            font-weight:900;
        }

        .checkoutCta p{
            max-width:650px;
            margin:0 auto 18px;
            color:var(--muted);
            font-size:16px;
            line-height:1.75;
            font-weight:600;
        }

        .mentor-table-wrap{
            overflow-x:auto;
                border-radius:14px;
            border:1px solid var(--border);
            margin-top:4px;
        }
        .mentor-table-wrap table{
            width:100%;
            border-collapse:collapse;
            font-size:12px;
        }
        .mentor-table-wrap thead{
            background:rgba(255,255,255,.06);
        }
        .mentor-table-wrap th,
        .mentor-table-wrap td{
            padding:8px 6px;
            text-align:left;
            border-bottom:1px solid var(--border);
            vertical-align:top;
            line-height:1.5;
        }
        .mentor-table-wrap th{
            font-weight:900;
            color:var(--text);
            white-space:nowrap;
        }
        .mentor-table-wrap tbody tr:nth-child(odd){
            background:rgba(255,255,255,.04);
        }
        .mentor-table-wrap tbody tr:nth-child(even){
            background:rgba(255,255,255,.02);
        }
        .mentor-table-wrap td:first-child{
            font-weight:900;
            color:var(--accent);
            text-align:center;
            width:32px;
        }
        .mentor-table-wrap td:nth-child(2){
            font-weight:800;
            white-space:nowrap;
        }

        .part2-tab-bar{
            display:flex;
            flex-wrap:nowrap;
            gap:6px;
            margin-bottom:14px;
            padding:6px;
            border-radius:16px;
            background:rgba(255,255,255,.88);
            border:1px solid var(--border);
            box-shadow:0 8px 24px rgba(0,0,0,.06);
            justify-content:flex-start;
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
            scrollbar-width:none;
        }
        .part2-tab-bar::-webkit-scrollbar{display:none;}
        .part2-tab-btn{
            border:none;
            border-radius:12px;
            padding:10px 14px;
            font-weight:800;
            cursor:pointer;
            font-size:13px;
            transition:all .18s ease;
            background:transparent;
            color:#64748b;
            font-family:'Hind Siliguri',sans-serif;
            white-space:nowrap;
        }
        .part2-tab-btn:hover{
            background:rgba(29,78,216,.1);
            color:var(--text);
        }
        .part2-tab-btn.active{
            background:linear-gradient(135deg,var(--accent-blue),var(--cta));
            color:#fff;
            box-shadow:0 6px 20px var(--glow);
        }
        .part2-tab-content{
            display:none;
        }
        .part2-tab-content.active{
            display:block;
        }
        .part2-tab-content .info-card{
            margin:0;
        }

        .mentor-list{
            display:grid;
            gap:8px;
        }
        .mentor-item{
            display:flex;
            flex-direction:column;
            gap:2px;
            padding:12px 14px;
            border-radius:12px;
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:0 6px 14px rgba(0,0,0,.2);
        }
        .mentor-item .mentor-name{
            font-weight:900;
            font-size:15px;
            color:var(--text);
        }
        .mentor-item .mentor-platform{
            font-weight:600;
            font-size:13px;
            color:var(--muted);
            display:-webkit-box;
            -webkit-line-clamp:2;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }
        .mentor-item:nth-child(odd){
            background:rgba(255,255,255,.02);
        }

        .review-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:12px;
            margin-top:16px;
        }
        .review-card.hidden-review{
            display:none;
        }
        .review-card{
            background:var(--card);
            border-radius:16px;
            padding:18px 20px;
            border:1px solid var(--border);
            box-shadow:0 4px 16px rgba(0,0,0,.2);
            transition:transform .18s ease,box-shadow .18s ease;
        }
        .review-card:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 24px rgba(0,0,0,.3);
        }
        .review-stars{
            color:var(--accent-blue);
            font-size:15px;
            letter-spacing:1px;
            margin-bottom:6px;
        }
        .review-stars span{
            color:var(--muted);
            font-size:13px;
            font-weight:700;
            letter-spacing:0;
        }
        .review-name{
            font-weight:800;
            font-size:16px;
            color:var(--text);
            margin-bottom:6px;
            padding-left:10px;
            border-left:3px solid var(--accent-blue);
        }
        .review-text{
            font-size:14px;
            line-height:1.7;
            color:var(--text);
        }
        @media(min-width:768px){
            .review-grid{grid-template-columns:1fr 1fr;gap:14px;}
        }

        /* ===== MOBILE-FIRST: Toggle / Collapse ===== */
        .section-toggle{
            width:100%;
            max-width:900px;
            margin:24px auto;
        }
        .section-toggle-btn{
            display:flex;
            align-items:center;
            justify-content:center;
            width:100%;
            gap:10px;
            padding:16px 20px;
            border-radius:14px;
            border:2px dashed var(--border);
            background:var(--card);
            color:var(--accent-blue);
            font-size:15px;
            font-weight:800;
            cursor:pointer;
            transition:all .25s;
            -webkit-tap-highlight-color:transparent;
            touch-action:manipulation;
            user-select:none;
            line-height:1.4;
            text-align:center;
        }
        .section-toggle-btn:hover,
        .section-toggle-btn:active{
            border-color:var(--accent-blue);
            background:rgba(29,78,216,.04);
            transform:translateY(-1px);
        }
        .section-toggle-btn .toggle-arrow{
            transition:transform .35s;
            font-size:12px;
        }
        .section-toggle-btn.open .toggle-arrow{
            transform:rotate(180deg);
        }
        .section-toggle-content{
            max-height:0;
            overflow:hidden;
            transition:max-height .5s ease;
        }
        .section-toggle-content.open{
            max-height:none;
        }
        .section-toggle-content > *:first-child{
            margin-top:20px;
        }

        /* ===== IMAGE LAYOUT SHIFT PREVENTION ===== */
        .platform-logo-item img{
            display:block;
            width:100%;
            height:auto;
            aspect-ratio:3/2;
            object-fit:contain;
            border-radius:8px;
        }
        .trainer-img-wrap{
            position:relative;
            width:100%;
            aspect-ratio:1/1;
            overflow:hidden;
            border-radius:50%;
        }
        .trainer-img-wrap img{
            display:block;
            width:100%;
            height:100%;
            object-fit:cover;
        }
        .gallery-item{
            position:relative;
            aspect-ratio:4/3;
            overflow:hidden;
            border-radius:12px;
        }
        .gallery-item img{
            display:block;
            width:100%;
            height:100%;
            object-fit:cover;
        }

        /* ===== CHECKOUT LOADING SPINNER ===== */
        .lcc-checkout-loading{
            position:relative;
            pointer-events:none;
        }
        .lcc-checkout-loading::after{
            content:'';
            position:absolute;
            inset:0;
            background:rgba(255,255,255,.7);
            backdrop-filter:blur(4px);
            z-index:100;
            border-radius:14px;
        }
        .lcc-checkout-loading .lcc-spinner{
            display:flex !important;
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            z-index:101;
            flex-direction:column;
            align-items:center;
            gap:12px;
        }
        .lcc-spinner-ring{
            width:44px;
            height:44px;
            border:4px solid var(--border);
            border-top-color:var(--cta);
            border-radius:50%;
            animation:lcc-spin .7s linear infinite;
        }
        @keyframes lcc-spin{
            to{transform:rotate(360deg);}
        }
        .lcc-spinner-text{
            font-size:14px;
            font-weight:800;
            color:var(--text);
        }

        /* ===== SCROLL PROGRESS BAR ===== */
        .scroll-progress{
            position:fixed;
            top:0;
            left:0;
            width:0%;
            height:3px;
            background:linear-gradient(90deg,var(--cta),var(--gold));
            z-index:100000;
            transition:width .15s ease-out;
            will-change:width;
        }

        /* ===== FADE-IN ON SCROLL ===== */
        .fade-in{
            opacity:0;
            transform:translateY(24px);
            transition:opacity .6s ease-out, transform .6s ease-out;
        }
        .fade-in.visible{
            opacity:1;
            transform:translateY(0);
        }

        /* ===== MOBILE SPACING ===== */
        @media(max-width:599px){
            .hero-card,
            .section-wrap,
            .work-process,
            .price-anchor,
            .drive-preview-wrap,
            .faq-section,
            .checkoutCta,
            .section-toggle{
                margin-top:28px !important;
                margin-bottom:0;
            }
            .proof-strip{
                margin-top:20px;
            }
            .hero-card{
                padding:18px 10px !important;
            }
            .info-grid{
                gap:10px;
            }
        }

        @media(max-width:399px){
            .hero-card .info-box > div[style*="grid-template-columns"]{
                grid-template-columns:1fr !important;
            }
        }
        @media(min-width:768px){
            .info-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;}
        }

        .live-notif-bar{
            position:fixed;
            left:12px;
            bottom:80px;
            z-index:10001;
            transform:translateY(120%);
            width:92vw;
            max-width:380px;
            padding:12px 16px;
            border-radius:14px;
            background:var(--card);
            border:1.5px solid rgba(29,78,216,.25);
            box-shadow:0 10px 40px rgba(0,0,0,.06);
            font-weight:700;
            font-size:13px;
            line-height:1.55;
            pointer-events:none;
            transition:transform .45s cubic-bezier(.34,1.56,.64,1),opacity .45s ease;
            box-sizing:border-box;
            opacity:0;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .live-notif-bar.show{transform:translateY(0);opacity:1;}
        .live-notif-bar .notif-icon{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1D4ED8,#FF6B35);color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
        .live-notif-bar .notif-text{flex:1;color:var(--text);}
        @media(min-width:501px){
            .live-notif-bar{left:18px;bottom:100px;transform:translateY(120%);width:360px;max-width:90vw;border-radius:16px;padding:14px 18px;font-size:13.5px;}
            .live-notif-bar.show{transform:translateY(0);}
        }

        .live-counter{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:999px;
            background:rgba(29,78,216,.08);
            border:1px solid rgba(29,78,216,.15);
            font-weight:800;
            font-size:13px;
            color:var(--accent-blue);
        }
        .live-counter .pulse-dot{
            width:8px;height:8px;
            border-radius:50%;
            background:var(--accent-blue);
            animation:liveGlow 1.2s infinite;
        }

        .countdown-box{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:999px;
            background:rgba(29,78,216,.1);
            border:1px solid rgba(29,78,216,.2);
            font-weight:800;
            font-size:13px;
            color:var(--accent-blue);
        }

        .super-review{
            border:2px solid var(--accent-blue) !important;
            background:linear-gradient(135deg,rgba(29,78,216,.06),rgba(234,88,12,.04)) !important;
            position:relative;
        }
        .super-review::after{
            content:"⭐ সেরা পর্যালোচনা";
            position:absolute;
            top:-10px;
            right:12px;
            padding:3px 10px;
            border-radius:999px;
            background:linear-gradient(90deg,var(--accent-blue),var(--cta));
            color:#fff;
            font-size:11px;
            font-weight:800;
            line-height:1.4;
        }

        .trust-row{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            justify-content:center;
            margin-top:14px;
        }

        .value-tag{
            display:inline-block;
            padding:2px 8px;
            border-radius:6px;
            background:rgba(29,78,216,.15);
            font-size:11px;
            font-weight:800;
            color:var(--accent-blue);
            margin-left:6px;
            white-space:nowrap;
        }
        .mentor-price{
            display:inline-block;
            padding:3px 8px;
            border-radius:6px;
            background:rgba(29,78,216,.12);
            color:var(--accent-blue);
            font-size:10.5px;
            font-weight:800;
            margin-left:5px;
            white-space:nowrap;
            line-height:1.5;
        }
        .mentor-price s{
            color:#64748B;
            margin-right:3px;
        }
        .free-badge-sm{
            padding:1px 6px;
            border-radius:4px;
            background:linear-gradient(135deg,var(--accent-blue),var(--cta));
            color:#fff;
            font-size:9px;
            font-weight:800;
            margin-left:3px;
            white-space:nowrap;
        }

        @keyframes pulse-urgency{
            0%{box-shadow:0 0 0 0 rgba(29,78,216,.5)}
            70%{box-shadow:0 0 0 12px rgba(29,78,216,0)}
            100%{box-shadow:0 0 0 0 rgba(29,78,216,0)}
        }


        .gallery-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:8px;
            margin-top:14px;
        }
        .gallery-item{
            border-radius:6px;
            overflow:hidden;
        }
        .gallery-item img{
            width:100%;
            height:200px;
            object-fit:cover;
            display:block;
            border-radius:6px;
        }
        @media(max-width:374px){
            .gallery-item img{height:160px;}
        }
        @media(min-width:400px){
            .gallery-item img{height:240px;}
        }
        @media(min-width:480px){
            .gallery-grid{grid-template-columns:repeat(2,1fr);gap:10px;}
            .gallery-item img{height:260px;}
        }
        @media(min-width:640px){
            .gallery-grid{grid-template-columns:repeat(3,1fr);gap:10px;}
            .gallery-item img{height:280px;}
        }
        @media(min-width:768px){
            .gallery-grid{grid-template-columns:repeat(3,1fr);gap:12px;}
            .gallery-item{border-radius:8px;}
            .gallery-item img{height:320px;}
        }
        @media(min-width:1024px){
            .gallery-grid{grid-template-columns:repeat(3,1fr);gap:14px;}
            .gallery-item{border-radius:8px;}
            .gallery-item img{height:360px; border-radius:6px;}
        }

        .salary-wrapper th,
        .salary-wrapper td{
            font-size:12px;
        }
        /* ---- Phase 5: Value Shock ---- */
        .price-anchor{
            max-width:1100px;
            margin:32px auto;
            padding:24px 20px 22px;
            border-radius:20px;
            background:linear-gradient(135deg, rgba(29,78,216,.1), rgba(29,78,216,.06));
            border:2px solid rgba(29,78,216,.25);
            text-align:center;
            box-shadow:0 8px 24px var(--glow);
            width:92%;
            display:block;
        }
        .price-anchor .anchor-label{
            font-size:13px;
            color:var(--muted);
            font-weight:600;
            margin-bottom:4px;
            letter-spacing:.4px;
        }
        .price-anchor .anchor-original{
            font-size:16px;
            color:var(--muted);
            font-weight:600;
            margin-bottom:4px;
        }
        .price-anchor .anchor-original s{
            color:#64748B;
            font-size:24px;
            font-weight:900;
        }
        .price-anchor .anchor-offer{
            font-size:28px;
            font-weight:900;
            color:var(--text);
            margin:6px 0;
        }
        .price-anchor .anchor-offer .offer-highlight{
            color:var(--price);
            font-size:36px;
        }
        @media(max-width:480px){
            .price-anchor .anchor-offer{font-size:22px;}
            .price-anchor .anchor-offer .offer-highlight{font-size:28px;}
            .price-anchor .anchor-save{font-size:15px;padding:10px 16px;}
            .price-anchor .anchor-save strong{font-size:18px;}
            .price-anchor{padding:18px 14px 16px;}
        }
        .price-anchor .anchor-save{
            display:inline-flex;
            align-items:center;
            gap:8px;
            margin-top:10px;
            padding:12px 24px;
            border-radius:12px;
            background:rgba(255,191,0,.12);
            border:2px solid rgba(255,191,0,.28);
            color:var(--gold);
            font-weight:900;
            font-size:18px;
            box-shadow:0 4px 12px var(--glow);
        }
        .price-anchor .anchor-save strong{
            color:var(--gold);
            font-size:24px;
        }
        .offer-stack-wrap{
            margin-top:10px;
            background:linear-gradient(135deg,rgba(29,78,216,.06),rgba(29,78,216,.02)) !important;
            border:1.5px solid rgba(29,78,216,.14) !important;
        }
        .offer-stack-wrap .offer-stack-divider{
            width:60px;height:3px;
            margin:28px auto;
            border-radius:2px;
            background:linear-gradient(90deg,var(--accent-blue),var(--cta));
            opacity:.15;
        }
        .offer-stack-wrap .section-subhead{
            text-align:center;
            font-size:15px;
            font-weight:700;
            color:var(--muted);
            letter-spacing:.5px;
            margin-bottom:18px;
        }
        .offer-stack-wrap .stack-header{
            font-size:15px;
            font-weight:800;
            color:var(--muted);
            text-align:center;
            margin-bottom:12px;
        }
        .offer-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:10px;
        }
        .offer-card{
            background:var(--card);
            border:1.5px solid var(--border);
            border-radius:16px;
            padding:16px 14px;
            display:flex;
            flex-direction:column;
            gap:3px;
            box-shadow:0 4px 14px rgba(0,0,0,.05);
            transition:transform .18s ease,box-shadow .18s ease;
            position:relative;
            overflow:hidden;
        }
        .offer-card::before{
            content:"";
            position:absolute;
            top:0;left:0;right:0;
            height:3px;
            background:linear-gradient(90deg,var(--accent-blue),var(--cta));
        }
        .offer-card:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 24px var(--glow);
        }
        .offer-card .offer-icon{
            font-size:26px;
            line-height:1;
        }
        .offer-card .offer-title{
            font-weight:900;
            font-size:13px;
            color:var(--text);
            line-height:1.3;
        }
        .offer-card .offer-mentor{
            font-size:11.5px;
            color:var(--muted);
            font-weight:600;
        }
        .offer-card .offer-price-row{
            display:flex;
            align-items:center;
            gap:6px;
            margin-top:auto;
            padding-top:6px;
        }
        .offer-card .offer-price-row s{
            color:#64748B;
            font-size:13px;
            font-weight:700;
        }
        .offer-card .offer-free-badge{
            padding:2px 10px;
            border-radius:999px;
            background:linear-gradient(135deg,var(--accent-blue),var(--cta));
            color:#fff;
            font-size:10px;
            font-weight:800;
            white-space:nowrap;
        }
        .offer-bundle-row{
            grid-column:1/-1;
            background:linear-gradient(135deg,rgba(29,78,216,.1),rgba(234,88,12,.06));
            border:2px solid rgba(29,78,216,.18);
            border-radius:16px;
            padding:16px 18px;
            text-align:center;
        }
        .offer-bundle-row .bundle-price{
            font-size:20px;
            font-weight:900;
            color:var(--text);
        }
        .offer-bundle-row .bundle-price span{
            color:var(--price);
            font-size:28px;
        }
        .offer-bundle-row .bundle-save{
            display:block;
            margin-top:4px;
            color:var(--gold);
            font-weight:700;
            font-size:14px;
        }
        .platform-logos-section{
            margin-top:20px;
        }
        .platform-logo-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:8px;
            margin-top:14px;
        }
        .platform-logo-item{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:10px;
            padding:14px 12px 10px;
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:8px;
            transition:box-shadow .2s;
        }
        .platform-logo-item:hover{
            box-shadow:0 4px 14px rgba(0,0,0,.06);
        }
        .platform-logo-item img{
            width:100%;
            height:72px;
            object-fit:contain;
            display:block;
            border-radius:6px;
        }
        .platform-logo-item span{
            font-size:11px;
            font-weight:700;
            color:var(--text);
            text-align:center;
            line-height:1.2;
        }
        .trainer-photo-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:8px;
            margin-top:14px;
        }
        .trainer-photo-item{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:12px;
            padding:16px 10px 12px;
            display:flex;
            flex-direction:column;
            align-items:center;
            text-align:center;
            gap:8px;
            transition:background .2s;
        }
        .trainer-photo-item:hover{
            background:rgba(255,255,255,.08);
        }
        .trainer-photo-item .trainer-img-wrap{
            width:90px;
            height:90px;
            border-radius:50%;
            overflow:hidden;
            background:rgba(255,255,255,.08);
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }
        .trainer-photo-item .trainer-img-wrap img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }
        .trainer-photo-item .trainer-name{
            font-size:12px;
            font-weight:700;
            color:var(--text);
            line-height:1.2;
        }
        @media(max-width:479px){
        }
        @media(min-width:600px){
            .offer-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;}
            .platform-logo-grid{grid-template-columns:repeat(3,minmax(0,1fr));}
            .trainer-photo-grid{grid-template-columns:repeat(3,minmax(0,1fr));}
            .platform-logo-item img{max-height:60px;}
            .proof-strip-v2 .proof-item-new{font-size:12px;padding:6px 14px;}
            .drive-sub-items{padding-left:28px;}
            .drive-item{font-size:12px;padding:6px 10px;}
            .drive-folder{font-size:13px;padding:8px 10px;}
            .payment-brand-badge{font-size:13px;padding:8px 16px;}
            .proof-strip{padding:18px 12px;}
            .proof-num{font-size:20px;}
            .proof-label{font-size:11px;}
            .proof-divider{height:32px;}
            .proof-strip-inner{gap:16px 24px;}
        }
        @media(min-width:900px){
            .offer-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;}
            .platform-logo-grid{grid-template-columns:repeat(4,minmax(0,1fr));}
            .trainer-photo-grid{grid-template-columns:repeat(4,minmax(0,1fr));}
        }

        /* ---- Phase 6: Trust & Social Proof ---- */
        .proof-strip-v2 .proof-item-new{
            display:flex;
            align-items:center;
            gap:6px;
            padding:4px 10px;
            border-radius:999px;
            background:rgba(255,255,255,.1);
            border:1px solid rgba(255,255,255,.12);
            font-size:10px;
            font-weight:600;
            color:rgba(255,255,255,.92);
        }

        /* Chat-style testimonial cards */
        .chat-testimonial-grid{
            display:grid;
            grid-template-columns:1fr;
            gap:12px;
            margin:20px 0 14px;
        }
        .chat-testi-card{
            background:var(--card);
            border-radius:16px;
            padding:18px;
            border:1px solid var(--border);
            box-shadow:0 4px 16px rgba(0,0,0,.2);
            display:flex;
            flex-direction:column;
            gap:8px;
            position:relative;
        }
        .chat-testi-card::before{
            content:"";
            position:absolute;
            left:0;top:0;bottom:0;
            width:4px;
            border-radius:4px 0 0 4px;
            background:linear-gradient(180deg,var(--accent-blue),var(--cta));
        }
        .chat-testi-header{
            display:flex;
            align-items:center;
            gap:10px;
        }
        .chat-testi-avatar{
            width:38px;height:38px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:900;
            font-size:16px;
            color:#fff;
            flex-shrink:0;
        }
        .chat-testi-name{
            font-weight:800;
            font-size:14px;
            color:var(--text);
        }
        .chat-testi-platform{
            font-size:11px;
            color:var(--muted);
            font-weight:600;
        }
        .chat-testi-msg{
            background:rgba(255,255,255,.06);
            border-radius:12px;
            padding:12px 14px;
            font-size:13.5px;
            line-height:1.7;
            color:var(--text);
            position:relative;
            margin-left:8px;
        }
        .chat-testi-msg::before{
            content:"";
            position:absolute;
            left:-6px;top:10px;
            width:12px;height:12px;
            background:rgba(255,255,255,.06);
            transform:rotate(45deg);
            border-radius:2px;
        }
        .chat-testi-stars{
            color:var(--accent-blue);
            font-size:13px;
            letter-spacing:1px;
        }
        .chat-testi-time{
            font-size:11px;
            color:#64748B;
            font-weight:600;
            text-align:right;
        }
        @media(min-width:768px){
            .chat-testimonial-grid{grid-template-columns:1fr 1fr;gap:14px;}
        }

        /* Google Drive Preview */
        .drive-preview-wrap{
            max-width:820px;
            margin:40px auto;
            padding:0 14px;
            content-visibility:auto;
            contain-intrinsic-size:500px;
        }
        .drive-preview-card{
            background:var(--card);
            border-radius:18px;
            border:1px solid var(--border);
            box-shadow:0 12px 32px rgba(0,0,0,.3);
            overflow:hidden;
        }
        .drive-preview-header{
            display:flex;
            align-items:center;
            gap:10px;
            padding:14px 18px;
            background:var(--card);
            border-bottom:1px solid var(--border);
        }
        .drive-preview-header .drive-logo{
            font-size:22px;
        }
        .drive-preview-header .drive-title{
            font-weight:800;
            font-size:15px;
            color:var(--text);
        }
        .drive-preview-header .drive-badge{
            margin-left:auto;
            padding:4px 12px;
            border-radius:999px;
            background:rgba(29,78,216,.12);
            color:var(--accent-blue);
            font-size:11px;
            font-weight:800;
            white-space:nowrap;
        }
        .drive-preview-body{
            padding:14px 18px 18px;
        }
        .drive-folder{
            display:flex;
            align-items:center;
            gap:8px;
            padding:7px 8px;
            border-radius:10px;
            background:rgba(29,78,216,.06);
            border:1px solid rgba(29,78,216,.12);
            margin-bottom:6px;
            font-weight:700;
            font-size:12px;
            color:var(--text);
        }
        .drive-folder .folder-icon{font-size:18px;}
        .drive-folder .folder-count{
            margin-left:auto;
            font-size:11px;
            color:#64748B;
            font-weight:600;
        }
        .drive-sub-items{
            padding-left:16px;
            display:flex;
            flex-direction:column;
            gap:4px;
            margin-bottom:8px;
        }
        .drive-item{
            display:flex;
            align-items:center;
            gap:6px;
            padding:5px 8px;
            border-radius:8px;
            background:var(--card);
            font-size:11px;
            color:var(--muted);
            font-weight:600;
        }
        .drive-item .item-icon{font-size:14px;}
        .drive-item .item-badge{
            margin-left:auto;
            padding:2px 8px;
            border-radius:6px;
            font-size:10px;
            font-weight:700;
        }
        .drive-item .item-badge.hd{background:rgba(29,78,216,.12);color:var(--accent-blue);}
        .drive-item .item-badge.new{background:rgba(29,78,216,.12);color:var(--accent-blue);}
        .drive-preview-footer{
            padding:12px 18px;
            border-top:1px solid var(--border);
            text-align:center;
            background:linear-gradient(135deg,rgba(29,78,216,.06),rgba(234,88,12,.04));
        }
        .drive-preview-footer span{
            font-size:13px;
            font-weight:700;
            color:var(--muted);
        }
        .drive-preview-footer strong{
            color:var(--accent-blue);
        }
        .drive-pulse{
            animation:drivePulse 2s infinite;
        }
        @keyframes drivePulse{
            0%{opacity:1}
            50%{opacity:.6}
            100%{opacity:1}
        }
        /* ---- Phase 7: Checkout ---- */
        .payment-brands{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            justify-content:center;
            margin:14px 0;
        }
        .payment-brand-badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:10px;
            font-weight:800;
            font-size:12px;
            border:1.5px solid;
        }
        .payment-brand-badge.bkash{
            background:rgba(209,32,83,.08);
            border-color:rgba(209,32,83,.20);
            color:#d12053;
        }
        .payment-brand-badge.nagad{
            background:rgba(246,146,30,.1);
            border-color:rgba(246,146,30,.22);
            color:#e8731a;
        }
        .payment-brand-badge.rocket{
            background:rgba(29,78,216,.08);
            border-color:rgba(29,78,216,.2);
            color:#e2136e;
        }
        .payment-brand-badge.ssl{
            background:rgba(29,78,216,.08);
            border-color:rgba(29,78,216,.18);
            color:var(--accent-blue);
        }
        .delivery-guarantee{
            text-align:center;
            margin:8px 0;
            padding:8px 14px;
            border-radius:10px;
            background:rgba(29,78,216,.08);
            border:1px solid rgba(29,78,216,.16);
            color:var(--accent-blue);
            font-weight:700;
            font-size:14px;
            line-height:1.6;
        }
        .loss-aversion{
            text-align:center;
            margin:10px 0 4px;
            padding:10px 14px;
            border-radius:12px;
            background:rgba(29,78,216,.08);
            border:1px solid rgba(29,78,216,.16);
            color:var(--accent-blue);
            font-size:14px;
            font-weight:700;
            line-height:1.6;
        }
        .loss-aversion-hero{
            text-align:center;
            margin:12px 0 6px;
            padding:12px 16px;
            border-radius:12px;
            background:rgba(29,78,216,.08);
            border:1px solid rgba(29,78,216,.2);
            color:var(--text);
            font-size:13px;
            font-weight:700;
            line-height:1.65;
        }
        /* ---- Mini Tab Bar Nav ---- */
        .section-nav.section-nav-merged{
            display:flex;
            position:fixed;
            left:0;right:0;bottom:0;
            z-index:9999;
            gap:4px;
            padding:4px 8px calc(4px + env(safe-area-inset-bottom,0px));
            border-radius:0;
            background:rgba(255,255,255,.96);
            backdrop-filter:blur(16px);
            -webkit-backdrop-filter:blur(16px);
            border-top:1px solid var(--border);
            box-shadow:0 -4px 24px rgba(0,0,0,.06);
            align-items:center;
            justify-content:center;
            touch-action:manipulation;
        }
        .section-nav-merged .tab-group{
            display:flex;
            flex:1;
            gap:0;
            justify-content:space-around;
            min-width:0;
        }
        .section-nav-merged .section-nav-btn{
            border:none;
            border-radius:8px;
            padding:4px 2px 2px;
            font-weight:700;
            cursor:pointer;
            font-size:10px;
            touch-action:manipulation;
            background:transparent;
            color:#64748b;
            white-space:nowrap;
            font-family:'Hind Siliguri',sans-serif;
            min-height:44px;
            flex:1;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:0;
            transition:color .15s,background .15s;
            position:relative;
            min-width:0;
        }
        .section-nav-merged .section-nav-btn:hover{
            background:rgba(29,78,216,.06);
        }
        .section-nav-merged .section-nav-btn .nav-icon{
            font-size:17px;
            line-height:1.2;
        }
        .section-nav-merged .section-nav-btn .nav-label{
            font-size:10px;
            line-height:1.3;
            margin-top:1px;
        }
        .section-nav-merged .section-nav-btn.active{
            color:var(--accent-blue);
            background:rgba(29,78,216,.08);
        }
        .section-nav-merged .section-nav-btn.active::after{
            content:'';
            position:absolute;
            top:0;
            left:50%;
            transform:translateX(-50%);
            width:16px;
            height:2px;
            border-radius:0 0 2px 2px;
            background:var(--accent-blue);
        }
        .section-nav-merged .nav-cta-btn{
            border:none;
            border-radius:10px;
            padding:8px 12px;
            font-weight:900;
            cursor:pointer;
            font-size:12px;
            touch-action:manipulation;
            background:linear-gradient(135deg,var(--cta),var(--cta-dark));
            color:#fff;
            text-decoration:none;
            box-shadow:0 6px 20px rgba(234,88,12,.35);
            font-family:'Hind Siliguri',sans-serif;
            display:flex;
            align-items:center;
            justify-content:center;
            white-space:nowrap;
            line-height:1.2;
            flex-shrink:0;
            min-height:44px;
            min-width:64px;
            transition:transform .15s,box-shadow .15s;
        }
        .section-nav-merged .nav-cta-btn:active{
            transform:scale(.95);
        }
        .section-nav-merged .nav-cta-btn:hover{
            box-shadow:0 8px 28px rgba(234,88,12,.45);
        }
        @media(min-width:421px){
            .section-nav-merged .section-nav-btn{
                font-size:11px;
                padding:8px 4px 4px;
                min-height:48px;
            }
            .section-nav-merged .section-nav-btn .nav-icon{
                font-size:20px;
            }
            .section-nav-merged .section-nav-btn .nav-label{
                font-size:11px;
            }
            .section-nav-merged .nav-cta-btn{
                padding:10px 16px;
                font-size:14px;
                min-height:44px;
                border-radius:12px;
            }
        }
        @media(min-width:769px){
            .section-nav.section-nav-merged{
                padding:8px 16px calc(8px + env(safe-area-inset-bottom,0px));
                gap:8px;
            }
            .section-nav-merged .section-nav-btn{
                font-size:12px;
                padding:6px 8px 4px;
                min-height:48px;
                border-radius:10px;
            }
            .section-nav-merged .section-nav-btn .nav-icon{
                font-size:20px;
            }
            .section-nav-merged .section-nav-btn .nav-label{
                font-size:11px;
            }
            .section-nav-merged .section-nav-btn.active::after{
                width:20px;
                height:3px;
            }
            .section-nav-merged .nav-cta-btn{
                padding:10px 18px;
                font-size:14px;
                border-radius:12px;
                min-height:44px;
                min-width:80px;
            }
        }
        @media(min-width:1024px){
            .section-nav-merged .nav-cta-btn{
                padding:14px 28px;
                font-size:18px;
                border-radius:14px;
                min-height:48px;
                width:auto;
                white-space:nowrap;
                line-height:normal;
            }
        }

        /* ===== Phase 2 Sections ===== */

        /* Social Proof Strip */
        .proof-strip{
            background:var(--card);
            border-top:1px solid var(--border);
            border-bottom:1px solid var(--border);
            padding:14px 10px;
            width:100%;
        }
        .proof-strip-inner{
            max-width:1100px;
            margin:0 auto;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-wrap:wrap;
            gap:10px 14px;
        }
        .proof-item{
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:2px;
        }
        .proof-num{
            color:var(--text);
            font-size:16px;
            font-weight:900;
            line-height:1.1;
        }
        .proof-label{
            color:var(--muted);
            font-size:10px;
            font-weight:600;
            letter-spacing:.3px;
        }
        .proof-divider{
            width:1px;
            height:24px;
            background:var(--border);
        }

        /* Bonus Panel — Glassmorphism Course Cards */
        /* Bonus Footer (shared with Work Process) */
        .bonus-footer{
            max-width:820px;
            margin:16px auto 0;
            padding:14px 16px;
            border-radius:14px;
            background:rgba(29,78,216,.06);
            border:1px solid rgba(29,78,216,.12);
            text-align:center;
            font-size:13px;
            color:var(--text);
            font-weight:700;
            line-height:1.7;
        }

        /* Work Process — 3-Step Metalic Cards */
        .work-process{
            max-width:1100px;
            margin:40px auto 0;
            padding:0 14px;
        }
        .work-process .section-title{
            max-width:820px;
            margin-left:auto;
            margin-right:auto;
        }
        .work-steps{
            display:grid;
            grid-template-columns:1fr;
            gap:14px;
            max-width:820px;
            margin:0 auto;
        }
        .work-step{
            background:var(--card);
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
            border:1px solid var(--border);
            border-radius:16px;
            padding:18px 16px;
            display:flex;
            align-items:flex-start;
            gap:14px;
            transition:transform .2s;
        }
        .work-step:hover{
            transform:translateY(-2px);
        }
        .work-step-num{
            flex-shrink:0;
            width:44px;
            height:44px;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
            font-weight:900;
            color:#fff;
        }
        .work-step:nth-child(1) .work-step-num{background:linear-gradient(135deg,var(--accent-blue),var(--cta));}
        .work-step:nth-child(2) .work-step-num{background:linear-gradient(135deg,var(--cta),var(--accent-blue));}
        .work-step:nth-child(3) .work-step-num{background:linear-gradient(135deg,var(--accent-blue),var(--cta));}
        .work-step-body{
            flex:1;
            min-width:0;
        }
        .work-step-body h3{
            margin:0 0 4px;
            font-size:15px;
            font-weight:900;
            color:var(--text);
        }
        .work-step-body p{
            margin:0;
            font-size:13px;
            color:var(--muted);
            line-height:1.6;
            font-weight:600;
        }
        .work-step-body .step-highlight{
            display:inline-block;
            margin-top:6px;
            padding:3px 10px;
            border-radius:999px;
            background:rgba(29,78,216,.1);
            color:var(--accent-blue);
            font-size:11px;
            font-weight:800;
        }
        @media(min-width:640px){
            .work-steps{gap:16px;}
            .work-step{padding:20px 18px;}
            .work-step-num{width:50px;height:50px;font-size:20px;border-radius:14px;}
            .work-step-body h3{font-size:16px;}
            .work-step-body p{font-size:14px;}
        }
        @media(min-width:768px){
            .work-steps{gap:18px;}
        }

        /* FAQ Section */
        .faq-section{
            max-width:800px;
            margin:40px auto;
            padding:0 14px;
            content-visibility:auto;
            contain-intrinsic-size:400px;
        }
        .faq-section h2{
            text-align:center;
            font-size:20px;
            color:var(--accent-blue);
            margin-bottom:8px;
        }
        .faq-section > p{
            text-align:center;
            color:var(--muted);
            font-size:14px;
            margin-bottom:28px;
        }
        .faq-item{
            background:var(--card);
            border-radius:14px;
            margin-bottom:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
            overflow:hidden;
        }
        .faq-q{
            padding:16px 20px;
            font-size:14px;
            font-weight:700;
            color:var(--text);
            cursor:pointer;
            touch-action:manipulation;
            display:flex;
            justify-content:space-between;
            align-items:center;
            user-select:none;
            transition:background .2s;
        }
        .faq-q:hover{background:rgba(29,78,216,.04);}
        .faq-q .faq-arrow{
            font-size:12px;
            color:var(--muted);
            transition:transform .25s;
        }
        .faq-q.open .faq-arrow{transform:rotate(180deg);}
        .faq-a{
            max-height:0;
            overflow:hidden;
            transition:max-height .3s ease,padding .3s ease;
            padding:0 20px;
            font-size:13px;
            color:var(--muted);
            line-height:1.7;
        }
        .faq-a.open{
            max-height:300px;
            padding:0 20px 16px;
        }
        @media(min-width:500px){
            .faq-section h2{font-size:24px;}
        }

        /* Reviews Slider */
        .reviews-slider-wrap{
            overflow:hidden;
            position:relative;
            margin-top:20px;
        }
        .reviews-track{
            display:flex;
            transition:transform .5s ease;
        }
        .review-slide{
            min-width:100%;
            padding:0 10px;
            box-sizing:border-box;
        }
        .review-slide-inner{
            background:var(--card);
            border-radius:16px;
            padding:28px 24px;
            text-align:center;
            box-shadow:0 4px 16px rgba(0,0,0,.06);
            border:1px solid var(--border);
        }
        .review-slide-inner .review-stars{
            color:var(--accent-blue);
            font-size:20px;
            margin-bottom:10px;
        }
        .review-slide-inner .review-quote{
            font-size:14px;
            color:var(--text);
            line-height:1.7;
            margin-bottom:14px;
            font-style:italic;
        }
        .review-slide-inner .review-author{
            font-weight:700;
            color:var(--accent-blue);
            font-size:14px;
        }
        .review-slide-inner .review-label{
            font-size:12px;
            color:var(--muted);
        }
        .reviews-dots{
            display:flex;
            justify-content:center;
            gap:8px;
            margin-top:14px;
        }
        .review-dot{
            width:10px;height:10px;
            border-radius:50%;
            background:#E2E8F0;
            transition:background .3s,transform .3s;
            cursor:pointer;
            border:none;
            padding:0;
        }
        .review-dot.active{
            background:var(--accent-blue);
            transform:scale(1.3);
        }
    </style>

    <!-- ==================== CHECKOUT CSS (ORIGINAL) ==================== -->
    <style>
    #checkoutSection{
      --green:#10B981;
      --green-dark:#059669;
      --red:#DC2626;
      --red-dark:#dc2626;
      --ink:#1E293B;
      --muted:#64748B;
      --line:#E2E8F0;
      --soft:#FFFDF5;
      --white:#ffffff;
      --cyan:#0284C7;
      --cta:#FF6B35;
    }

    #checkoutSection .wrap{
      max-width:1160px;
      margin:auto;
      padding:24px 16px 44px;
    }

    #checkoutSection .brand-panel{
      background:var(--soft);
      border:1px solid var(--line);
      border-radius:8px;
      padding:18px 16px 17px;
      margin-bottom:16px;
      text-align:center;
      box-shadow:0 14px 30px rgba(0,0,0,.25);
    }

    #checkoutSection .jg-logo{
      text-align:center;
      font-size:37px;
      font-weight:900;
      line-height:1.16;
    }
    #checkoutSection .jg-logo span:nth-child(1){
      background:linear-gradient(90deg,var(--accent-blue),var(--cta));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }
    #checkoutSection .jg-logo span:nth-child(2){
      background:linear-gradient(90deg,var(--cta),var(--accent-blue));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }
    #checkoutSection .jg-logo span:nth-child(3){
      background:linear-gradient(90deg,var(--accent-blue),var(--cta));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }

    #checkoutSection .header{
      text-align:center;
      margin:0 0 15px;
      padding:16px 14px;
      background:var(--soft);
      border:1px solid var(--line);
      border-radius:8px;
      box-shadow:0 12px 26px rgba(0,0,0,.25);
    }
    #checkoutSection .header h1{
      margin:0 0 7px;
      font-size:34px;
      line-height:1.18;
      font-weight:900;
      color:var(--accent-blue);
    }
    #checkoutSection .header p{
      margin:0;
      font-size:16px;
      line-height:1.7;
      color:var(--muted);
      font-weight:800;
    }

      #checkoutSection .urgency{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,var(--urgency),var(--cta));
        color:#fff;
        padding:16px 18px;
        border-radius:12px;
        text-align:center;
        margin:0 auto 16px;
        font-size:18px;
        font-weight:900;
        box-shadow:0 12px 28px rgba(234,88,12,.25);
      }
    #checkoutSection .urgency:after{
      content:"";
      position:absolute;
      top:0;
      left:-90px;
      width:78px;
      height:100%;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.44),transparent);
      animation:shine 2.4s infinite;
    }
    #checkoutSection #timer{
      display:inline-block;
      min-width:68px;
      padding:4px 10px;
      margin-left:6px;
      background:rgba(0,0,0,.3);
      color:#fff;
      border-radius:6px;
      font-variant-numeric:tabular-nums;
    }
    #checkoutSection .timer-progress{
      height:6px;
      background:rgba(255,255,255,.28);
      border-radius:99px;
      overflow:hidden;
      margin-top:10px;
    }
    #checkoutSection #timerBar{
      display:block;
      width:100%;
      height:100%;
      background:rgba(255,255,255,.5);
      border-radius:99px;
      transition:width .35s ease;
    }

    #checkoutSection .trust,
    #checkoutSection .checkout-note{
      display:grid;
      grid-template-columns:1fr;
      gap:8px;
    }
    #checkoutSection .trust{ margin-bottom:10px; }
    #checkoutSection .checkout-note{ margin-bottom:24px; }

    #checkoutSection .trust span,
    #checkoutSection .checkout-note span{
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:45px;
      padding:10px 12px;
      border-radius:8px;
      background:var(--soft);
      text-align:center;
      border:1px solid var(--line);
      box-shadow:0 10px 22px rgba(0,0,0,.2);
    }
    #checkoutSection .trust span{
      font-size:15px;
      font-weight:900;
      color:var(--ink);
    }
    #checkoutSection .checkout-note span{
      color:var(--ink);
      font-size:14px;
      font-weight:900;
      background:var(--soft);
      border-color:var(--line);
    }

    #checkoutSection .woocommerce form.checkout{
      display:grid;
      grid-template-columns:1fr;
      column-gap:0;
      row-gap:0;
      align-items:start;
      background:#fff;
      border-radius:6px;
      border:1px solid var(--line);
      box-shadow:0 4px 12px rgba(0,0,0,.08);
      padding:18px;
    }
    #checkoutSection #customer_details{ grid-column:1; grid-row:auto; }
    #checkoutSection #order_review_heading{ grid-column:1; grid-row:auto; }
    #checkoutSection #order_review{ grid-column:1; grid-row:auto; }

    #checkoutSection #customer_details,
    #checkoutSection #order_review{
      background:transparent;
      padding:0;
      border-radius:0;
      margin:0;
      border:none;
      box-shadow:none;
      width:100%;
    }

    #checkoutSection #customer_details{
      padding-bottom:16px;
      margin-bottom:16px;
      border-bottom:1px solid var(--line);
    }

    #checkoutSection #order_review_heading{
      margin:0 0 10px;
      padding:0;
      font-size:18px;
      line-height:1.3;
      font-weight:800;
      color:var(--ink);
    }

    #checkoutSection .woocommerce .col2-set .col-1,
    #checkoutSection .woocommerce-page .col2-set .col-1{ float:none !important; width:100% !important; max-width:100% !important; }
    #checkoutSection .woocommerce .col2-set .col-2,
    #checkoutSection .woocommerce-page .col2-set .col-2{ display:none !important; }

    #checkoutSection .woocommerce-billing-fields h3,
    #checkoutSection .woocommerce-checkout h3{
      margin:0 0 10px;
      font-size:18px;
      font-weight:800;
      color:var(--ink);
    }

    #checkoutSection #customer_details .woocommerce-billing-fields__field-wrapper{
      display:grid;
      grid-template-columns:1fr;
      gap:12px;
    }

    #checkoutSection #customer_details .form-row-first,
    #checkoutSection #customer_details .form-row-last{
      float:none !important;
      width:100% !important;
      margin:0 !important;
      padding:0 !important;
    }

    #checkoutSection #billing_phone_field,
    #checkoutSection #billing_email_field,
    #checkoutSection #billing_country_field{
      grid-column:1 / -1;
    }

    #checkoutSection #customer_details .form-row{
      float:none !important;
      width:100% !important;
      margin:0 !important;
      padding:0 !important;
    }

    #checkoutSection #billing_address_1_field,
    #checkoutSection #billing_address_2_field,
    #checkoutSection #billing_city_field,
    #checkoutSection #billing_state_field,
    #checkoutSection #billing_postcode_field,
    #checkoutSection #billing_company_field,
    #checkoutSection #order_comments_field{
      display:none !important;
    }

    #checkoutSection input,
    #checkoutSection select,
    #checkoutSection textarea{
      width:100%;
      min-height:50px;
      padding:12px 14px;
      border-radius:8px;
      border:1px solid var(--line);
      margin:0;
      font-size:16px;
      background:rgba(255,255,255,.06);
      color:var(--ink);
      transition:border-color .18s ease, box-shadow .18s ease;
    }
    #checkoutSection input:focus,
    #checkoutSection select:focus,
    #checkoutSection textarea:focus{
      border-color:var(--accent-blue);
      box-shadow:0 0 0 4px rgba(29,78,216,.14);
      outline:none;
    }

    #checkoutSection label{
      display:block;
      margin-bottom:7px;
      font-size:15px;
      font-weight:900;
      color:var(--ink);
      word-break:normal;
      overflow-wrap:break-word;
    }

    #checkoutSection .required{ color:var(--accent-blue) !important; text-decoration:none !important; }
    #checkoutSection .optional{ color:var(--muted); font-size:13px; font-weight:700; }

    #checkoutSection .select2-container{ width:100% !important; }
    #checkoutSection .select2-container .select2-selection--single{ height:50px; border-radius:8px; border-color:var(--line); background:rgba(255,255,255,.06); }
    #checkoutSection .select2-container--default .select2-selection--single .select2-selection__rendered{ line-height:50px; color:var(--ink); padding-left:14px; font-size:16px; }
    #checkoutSection .select2-container--default .select2-selection--single .select2-selection__arrow{ height:50px; }

    #checkoutSection .shop_table{
      width:100%;
      border-collapse:collapse;
      table-layout:auto;
      overflow:hidden;
      border:1px solid var(--line);
      border-radius:8px;
    }
    #checkoutSection .shop_table th,
    #checkoutSection .shop_table td{
      padding:13px 14px;
      border-bottom:1px solid var(--line);
      font-size:16px;
      line-height:1.5;
      word-break:normal;
    }
    #checkoutSection .shop_table tr:last-child th,
    #checkoutSection .shop_table tr:last-child td{ border-bottom:0; }
    #checkoutSection .shop_table th{ font-weight:900; color:var(--ink); background:var(--soft); }
    #checkoutSection .shop_table .product-name{ padding-right:12px; overflow-wrap:anywhere; }
    #checkoutSection .shop_table .product-total,
    #checkoutSection .shop_table td:last-child,
    #checkoutSection .shop_table th:last-child{
      width:122px;
      min-width:122px;
      text-align:right;
      white-space:nowrap;
    }
    #checkoutSection .cart-subtotal th,
    #checkoutSection .order-total th{ white-space:nowrap; }
    #checkoutSection .order-total th,
    #checkoutSection .order-total td{
      color:var(--price);
      font-weight:900;
      font-size:20px;
      background:rgba(22,163,74,.06);
    }

    #checkoutSection .woocommerce-checkout-payment{
      background:var(--soft);
      padding:16px;
      border-radius:8px;
      border:1px solid var(--line);
      margin-top:16px;
    }
    #checkoutSection .woocommerce-checkout #payment ul.payment_methods{ padding:0 !important; border-bottom:1px solid var(--line) !important; }
    #checkoutSection .woocommerce-checkout #payment div.form-row{ padding:0 !important; margin:16px 0 0 !important; }

    #checkoutSection .wc_payment_method{
      background:var(--soft);
      padding:14px;
      border-radius:8px;
      border:1px solid var(--line);
      margin-bottom:10px;
    }
    #checkoutSection .wc_payment_method label{ font-size:16px; color:var(--ink); }

    #checkoutSection .payment_box{
      background:var(--soft) !important;
      border-radius:8px !important;
      color:var(--muted) !important;
      font-size:15px !important;
      line-height:1.7 !important;
      border:1px solid var(--line);
    }
    #checkoutSection .payment_box:before{ border-bottom-color:var(--soft) !important; }

    #checkoutSection #place_order{
      width:100%;
      min-height:58px;
      padding:15px 18px;
      border:none;
      border-radius:8px;
      background:linear-gradient(135deg,var(--cta),var(--cta-dark));
      color:#fff;
      font-weight:900;
      font-size:18px;
      margin-top:10px;
      cursor:pointer;
      box-shadow:0 17px 32px rgba(234,88,12,.31);
      transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
      text-transform:none;
      font-variant-numeric:tabular-nums;
    }
    #checkoutSection #place_order:hover{
      background:linear-gradient(135deg,var(--cta-dark),var(--cta));
      transform:translateY(-1px);
      box-shadow:0 21px 40px rgba(234,88,12,.39);
    }
    #checkoutSection #place_order:active{ transform:translateY(1px); }

    #checkoutSection .secure-checkout-badge{
      display:flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      margin:12px auto 0;
      padding:8px 16px;
      border-radius:999px;
      background:rgba(29,78,216,.08);
      border:1px solid rgba(29,78,216,.18);
      color:var(--accent-blue);
      font-size:13px;
      font-weight:800;
      text-align:center;
    }
    #checkoutSection .secure-checkout-badge .lock-icon{font-size:15px;}

    #checkoutSection .woocommerce-privacy-policy-text{ font-size:14px; line-height:1.8; color:var(--muted); }
    #checkoutSection .woocommerce-privacy-policy-text a{ color:var(--accent-blue); font-weight:900; }

    #checkoutSection .product-thumbnail,
    #checkoutSection .product-remove,
    #checkoutSection .product-quantity,
    #checkoutSection .woocommerce-form-coupon-toggle,
    #checkoutSection .checkout_coupon,
    #checkoutSection .woocommerce-form-login-toggle,
    #checkoutSection .woocommerce-form-login,
    #checkoutSection .woocommerce-additional-fields,
    #checkoutSection .woocommerce-shipping-fields{ display:none !important; }

    #checkoutSection .woocommerce-error,
    #checkoutSection .woocommerce-message,
    #checkoutSection .woocommerce-info{
      border-radius:8px;
      border-top:0;
      background:var(--soft);
      box-shadow:0 10px 24px rgba(0,0,0,.2);
      font-size:16px;
      line-height:1.7;
    }

    #checkoutSection .woocommerce-error li{
      color:var(--accent-blue);
      font-weight:700;
      padding:6px 0;
    }

    #checkoutSection .admin-product-warning{
      background:rgba(29,78,216,.08);
      border:1px solid rgba(29,78,216,.2);
      color:var(--accent-blue);
      padding:13px 14px;
      border-radius:8px;
      margin-bottom:16px;
      font-size:15px;
      font-weight:900;
    }

    @keyframes shine{
      0%{ left:-90px }
      55%,100%{ left:110% }
    }
    @media(min-width:768px){
      #checkoutSection .woocommerce form.checkout{
        grid-template-columns:1fr minmax(340px,.65fr);
        column-gap:24px;
        row-gap:0;
      }
      #checkoutSection #customer_details{ grid-column:1; grid-row:1 / span 2; padding-bottom:0; margin-bottom:0; border-bottom:none; }
      #checkoutSection #order_review_heading{ grid-column:2; grid-row:1; }
      #checkoutSection #order_review{ grid-column:2; grid-row:2; position:sticky; top:18px; align-self:start; }
      #checkoutSection #customer_details .woocommerce-billing-fields__field-wrapper{
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:14px 16px;
      }
      #checkoutSection .trust,
      #checkoutSection .checkout-note{
        grid-template-columns:repeat(3,1fr);
        gap:10px;
      }
      #checkoutSection .wrap{ padding:18px 12px 36px; }
    }

    .create-account { display: none !important; }
    </style>
    <!-- ================================================================ -->

</head>
<body <?php body_class( 'light-conversion-checkout variant-' . $ab_variant ); ?>>
<main class="landing-shell">
    <div class="logo">
        <span class="logo-main">Jobayer Group</span>
        <span class="logo-sub">Career</span>
    </div>

    <!-- PART 1 -->
    <section id="part1Section" class="hero-card">
        <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:12px;">
            <div class="live-counter" style="background:rgba(29,78,216,.12);color:var(--accent-blue);"><span class="pulse-dot" style="background:var(--accent-blue);"></span> <span id="liveStudentCount">৮৬৬+</span> সক্রিয় শিক্ষার্থী</div>
            <div class="countdown-box">⏰ শেষ হতে <span id="headerTimer">০৩:৪২:১৫</span></div>
        </div>
        <div class="headline-badge" style="display:flex;width:fit-content;margin:0 auto 14px;color:#1E3A8A;border-color:rgba(30,58,138,.2);background:rgba(30,58,138,.08);">
            💰 সরাসরি কাজ শিখে প্রথম মাসেই <span style="color:var(--gold);font-weight:900;">১১,০০০</span> থেকে <span style="color:var(--gold);font-weight:900;">৯২,০০০</span> টাকা পর্যন্ত উপার্জনের বাস্তবমুখী সুযোগ!
        </div>

        <h1>
            <?php echo esc_html($cfg['headline']); ?>
        </h1>

        <h3>
            <?php echo esc_html($cfg['subheadline']); ?>
        </h3>

        <div class="info-box" style="padding:14px 14px 12px;">
            <p style="margin:0 0 10px;font-size:15px;font-weight:800;text-align:center;color:var(--text);">
                ▶️ <strong>২ মিনিটের ভিডিও দেখুন</strong> — নতুন ডিজিটাল পেশা শুরু করুন
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div style="display:flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:var(--card);border:1px solid var(--border);font-size:12px;font-weight:700;color:var(--text);line-height:1.3;">
                    🏆 ২৩০+ প্রিমিয়াম কোর্স (উপহার)
                </div>
                <div style="display:flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:var(--card);border:1px solid var(--border);font-size:12px;font-weight:700;color:var(--text);line-height:1.3;">
                    💰 লাইভ আয়ের প্রমাণ
                </div>
                <div style="display:flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:var(--card);border:1px solid var(--border);font-size:12px;font-weight:700;color:var(--text);line-height:1.3;">
                    💳 বাস্তব পেমেন্ট প্রমাণ
                </div>
                <div style="display:flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:var(--card);border:1px solid var(--border);font-size:12px;font-weight:700;color:var(--text);line-height:1.3;">
                    🛡️ ২৪ ঘণ্টা টাকা ফেরত গ্যারান্টি
                </div>
            </div>
            <p style="margin:12px 0 0;text-align:center;font-size:13px;font-weight:700;color:var(--muted);line-height:1.7;">
                👇 নিচে দেখুন: কে কত টাকা আয় করছে, পেমেন্টের ছবি, প্রশিক্ষকদের তালিকা, কোর্সের বিবরণ, আর যারা কাজ করছেন তাদের মতামত
            </p>
        </div>

        <div class="loss-aversion-hero">📊 <strong>১০ লক্ষ টাকার কোর্স মাত্র ৯৯ টাকায়!</strong> কোর্স পছন্দ না হলে ২৪ ঘণ্টার মধ্যে টাকা ফেরত — আপনার কোনো ঝুঁকি নেই, শুধু লাভ!</div>

        <div id="videoWrapper">
            <div id="videoFrame"></div>

            <div class="youtubeMask youtubeMaskTop" aria-hidden="true"></div>
            <div class="youtubeMask youtubeMaskBottom" aria-hidden="true"></div>

            <div id="speedControls" class="speed-controls">
                <button class="speed-btn active" data-speed="1">1×</button>
                <button class="speed-btn" data-speed="1.5">1.5×</button>
                <button class="speed-btn" data-speed="2">2×</button>
            </div>

            <div id="videoCover" class="videoCover" aria-hidden="true">
                <div class="coverPlay">▶</div>
            </div>

            <div class="videoLine"><span id="videoLineFill"></span></div>
        </div>

        <a class="checkoutBtn" href="#checkoutSection" onclick="var s=document.getElementById('checkoutSection');if(s){s.scrollIntoView({behavior:'smooth',block:'start'});return false;}"><?php echo esc_html($cfg['cta_text']); ?></a>
        <p style="text-align:center;margin:12px auto 0;font-size:13px;font-weight:700;color:#64748B;max-width:500px;line-height:1.6;">কোর্স ও আয়ের প্রজেক্ট পছন্দ না হলে ৭ দিনের মধ্যে কোনো প্রশ্ন ছাড়াই ৯৯ টাকা ১০০% ফেরত পাবেন।</p>
    </section>

    <!-- PART 2: Problem & Solution -->
    <section class="section-wrap" style="margin-top:16px;">
        <div class="headline-badge" style="display:flex;width:fit-content;margin:0 auto 14px;">🤔 আপনি কি এই সমস্যাগুলোর মুখোমুখি?</div>
        <div class="info-grid" style="grid-template-columns:1fr 1fr;">
            <div class="info-card" style="background:linear-gradient(135deg,rgba(220,38,38,.04),rgba(220,38,38,.02));border-color:rgba(220,38,38,.12);">
                <h3 style="color:#DC2626;">❌ আজকের অনলাইন লার্নিং এর বড় বড় সমস্যা</h3>
                <ul class="group-list">
                    <li><span>⚠️</span> প্রিমিয়াম কোর্সের দাম ১০,০০০-৮৫,০০০+ টাকা — সবার জন্য affordable না</li>
                    <li><span>⚠️</span> ইউটিউবে ভালো কন্টেন্ট আছে কিন্তু স্ট্রাকচারড গাইডেন্স ও রোডম্যাপ নেই</li>
                    <li><span>⚠️</span> বিভিন্ন প্ল্যাটফর্মে ছড়িয়ে ছিটিয়ে থাকা কোর্স — এক জায়গায় পাবেন না</li>
                    <li><span>⚠️</span> কোন স্কিল প্রথমে শিখবেন, কীভাবে শুরু করবেন — সঠিক দিকনির্দেশনা নেই</li>
                    <li><span>⚠️</span> শেখার পর কীভাবে ইনকাম শুরু করবেন — সেই পথ দেখায় না অধিকাংশ কোর্স</li>
                </ul>
            </div>
            <div class="info-card" style="background:linear-gradient(135deg,rgba(22,163,74,.04),rgba(22,163,74,.02));border-color:rgba(22,163,74,.12);">
                <h3 style="color:#16A34A;">✅ জোবায়ের গ্রুপ পেশা — সম্পূর্ণ সমাধান</h3>
                <ul class="group-list">
                    <li><span>✅</span> মাত্র ৯৯ টাকায় ২৩০+ প্রিমিয়াম কোর্স — ১০ লক্ষ টাকার কন্টেন্ট!</li>
                    <li><span>✅</span> ১০টি বিভাগে A-Z স্ট্রাকচারড কোর্স — এক জায়গায় সম্পূর্ণ সিলেবাস</li>
                    <li><span>✅</span> বিগিনার থেকে পেশাদার — প্রতিটি ধাপে স্টেপ-বাই-স্টেপ গাইডেন্স</li>
                    <li><span>✅</span> ক্লায়েন্ট খোঁজার গাইড — শেখার পরপরই আয় শুরু করুন</li>
                    <li><span>✅</span> লাইফটাইম অ্যাক্সেস + ফ্রি আপডেট — আজীবনের জন্য আপনার সম্পদ</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Social Proof Strip -->
    <div class="proof-strip">
        <div class="proof-strip-inner">
            <div class="proof-item">
                <span class="proof-num">৮৬৬+</span>
                <span class="proof-label">সক্রিয় শিক্ষার্থী</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item">
                <span class="proof-num">৪.৯★</span>
                <span class="proof-label">ফেসবুক মূল্যায়ন</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item">
                <span class="proof-num">৮+ বছর</span>
                <span class="proof-label">পেশাদার অভিজ্ঞতা</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item">
                <span class="proof-num">৫০,০০০+</span>
                <span class="proof-label">সর্বোচ্চ মাসিক আয়</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item proof-strip-v2">
                <span class="proof-item-new">⚡ সাথে সাথে অ্যাক্সেস</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item proof-strip-v2">
                <span class="proof-item-new">📚 লাইফটাইম আপডেট</span>
            </div>
            <div class="proof-divider"></div>
            <div class="proof-item proof-strip-v2">
                <span class="proof-item-new">✅ ২৪ ঘণ্টা ফেরত</span>
            </div>
        </div>
    </div>

    <!-- WORK PROCESS — 3-Step System -->
    <section class="work-process">
        <div class="headline-badge" style="display:flex;width:fit-content;margin:0 auto 14px;">⚙️ আয়ের সহজ ৩টি ধাপ</div>
        <div class="section-title">আপনার আয়ের সহজ ৩টি উপায়:</div>
        <div class="work-steps">
            <div class="work-step">
                <div class="work-step-num">১</div>
                <div class="work-step-body">
                    <h3>📝 অ্যাকাউন্ট খুলুন</h3>
                    <p>মাত্র ৯৯ টাকা দিন। সাথে সাথেই সব কোর্স ও টুলস খুলে যাবে! <span class="step-highlight">⏱ ৩০ সেকেন্ড</span></p>
                </div>
            </div>
            <div class="work-step">
                <div class="work-step-num">২</div>
                <div class="work-step-body">
                    <h3>📢 লিংক শেয়ার করুন</h3>
                    <p>আপনার লিংক ফেসবুক ও হোয়াটসঅ্যাপে শেয়ার করুন। কোনো অভিজ্ঞতা লাগে না — সবকিছু রেডিমেড দেওয়া আছে! <span class="step-highlight">🎯 শুরু করুন আজই</span></p>
                </div>
            </div>
            <div class="work-step">
                <div class="work-step-num">৩</div>
                <div class="work-step-body">
                    <h3>💰 টাকা তুলুন</h3>
                    <p>আপনার লিংকে যতজন যুক্ত হবে, তত আয় সরাসরি বিকাশ/নগদে চলে আসবে! <span class="step-highlight">🟢 সরাসরি পেমেন্ট</span></p>
                </div>
            </div>
        </div>
        <div class="bonus-footer">💡 আমাদের ৮৬৬+ শিক্ষার্থীর ৭২% ই প্রথম মাসেই আয় শুরু করেছেন! 🚀 আপনার পালা এখনই!</div>
    </section>

    <!-- Phase 5: Price Anchor Banner -->
    <div class="price-anchor">
        <div class="anchor-label">🔥 ভ্যালু শক — নিজেই তুলনা করে দেখুন</div>
        <div class="anchor-original">২৩০+ কোর্সের বাজারমূল্য: <s><?php echo esc_html($cfg['price_anchor']); ?> টাকা</s></div>
        <div class="anchor-offer">আজকের অফার মূল্য: <span class="offer-highlight">মাত্র ৯৯ টাকা</span></div>
        <div class="anchor-save">🟢 আপনি বাঁচাচ্ছেন: <strong><?php echo lcc_bn_number_format($cfg['savings']); ?>+ টাকা!</strong></div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:14px;">
            <span style="padding:4px 12px;border-radius:6px;background:rgba(255,191,0,.08);font-size:11px;font-weight:700;color:var(--gold);">টেন মিনিট স্কুল: ৮৫,০০০+ টাকা</span>
            <span style="padding:4px 12px;border-radius:6px;background:rgba(255,191,0,.08);font-size:11px;font-weight:700;color:var(--gold);">ঘুড়ি লার্নিং: ৫৫,০০০+ টাকা</span>
            <span style="padding:4px 12px;border-radius:6px;background:rgba(255,191,0,.1);font-size:11px;font-weight:700;color:var(--gold);">আমাদের অফার: মাত্র ৯৯ টাকা</span>
        </div>
    </div>

    <!-- PART 2: Combined Category Index + Pricing Comparison -->
    <section id="offerCards" class="info-card offer-stack-wrap">
        <h3>🎯 দেখুন — ২৩০+ কোর্সে আপনি কত টাকা বাঁচাচ্ছেন</h3>
        <p class="subline">প্রতিটি বিভাগে ২০-৫০টি কোর্স — নিচে দেখুন আপনি কত টাকা বাঁচাচ্ছেন!</p>

        <div class="headline-badge" style="display:flex; width:fit-content; margin:0 auto 14px;">
            🚀 ৯৯ টাকায় — ৩০ দিনে শিখে মাসে ৫০,০০০+ টাকা আয় করুন!
        </div>

        <div class="part2-tab-bar" id="part2TabBar">
            <button class="part2-tab-btn active" data-tab="0">🎓 জ্ঞান</button>
            <button class="part2-tab-btn" data-tab="1">🏛️ প্রতিষ্ঠানসমূহ</button>
            <button class="part2-tab-btn" data-tab="2">👨‍🏫 প্রশিক্ষকবৃন্দ</button>
            <button class="part2-tab-btn" data-tab="3">💼 ফ্রিল্যান্সিং</button>
            <button class="part2-tab-btn" data-tab="4">🌍 ই-কমার্স</button>
            <button class="part2-tab-btn" data-tab="5">👨‍💻 ডেভেলপমেন্ট</button>
            <button class="part2-tab-btn" data-tab="6">📚 ভাষা ও চাকরি</button>
            <button class="part2-tab-btn" data-tab="7">🎨 UI/UX ও মাল্টিমিডিয়া</button>
            <button class="part2-tab-btn" data-tab="8">🛠️ সফটওয়্যার টুলস</button>
            <button class="part2-tab-btn" data-tab="9">🔐 নোটস</button>
        </div>

            <div class="part2-tab-content active" data-tab-content="0">
            <section class="info-card">
                    <h3>🎓 এই প্যাকেজে যা যা থাকছে — এক নজরে সম্পূর্ণ সূচিপত্র</h3>
                    <p class="subline">নিচের প্রতিটি বিষয়ের ওপর ক্লিক করলেই বিস্তারিত দেখতে পাবেন</p>
                    <div class="overview-grid">
                        <div class="overview-item" onclick="switchTab(3)">
                            <span class="overview-icon">💼</span>
                            <span class="overview-title">ফ্রিল্যান্সিং ও অনলাইন আর্নিং <span class="mentor-price"><s>৳১০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">ঘরে বসেই বিশ্বের যেকোনো প্রান্ত থেকে ফ্রিল্যান্সিং ও ডিজিটাল মার্কেটিং করে আয় করার পূর্ণাঙ্গ গাইড</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(5)">
                            <span class="overview-icon">💻</span>
                            <span class="overview-title">প্রোগ্রামিং ও আইটি ডেভেলপমেন্ট <span class="mentor-price"><s>৳১৮,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">ওয়েবসাইট, মোবাইল অ্যাপ ও সফটওয়্যার তৈরির কোর্স — কোডিং শিখে পেশা গড়ুন</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(3)">
                            <span class="overview-icon">📢</span>
                            <span class="overview-title">ডিজিটাল মার্কেটিং ও এসইও <span class="mentor-price"><s>৳১২,৫০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">ফেসবুক, গুগল, ইউটিউব ও লিংকডইনে বিজ্ঞাপন ও মার্কেটিংয়ের আধুনিক কৌশল</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(4)">
                            <span class="overview-icon">🌍</span>
                            <span class="overview-title">ই-কমার্স ও অনলাইন ব্যবসা <span class="mentor-price"><s>৳১৪,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">শপিফাই, ড্রপশিপিং, অ্যামাজন ও সোশ্যাল কমার্স — অনলাইনে পণ্য বিক্রির A-Z</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(7)">
                            <span class="overview-icon">🎨</span>
                            <span class="overview-title">UI/UX, মোশন গ্রাফিক্স ও থ্রিডি <span class="mentor-price"><s>৳১৬,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">ফিগমা, আফটার ইফেক্টস ও ব্লেন্ডার দিয়ে ডিজাইন ও অ্যানিমেশনের পেশাদার কোর্স</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(1)">
                            <span class="overview-icon">🏛️</span>
                            <span class="overview-title">প্রতিষ্ঠানসমূহ <span class="mentor-price"><s>৳২০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">টেন মিনিট স্কুল, ঘুড়ি লার্নিং, ক্রিয়েটিভ আইটি সহ ৮টি শীর্ষ প্রতিষ্ঠানের কোর্স</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(6)">
                            <span class="overview-icon">📚</span>
                            <span class="overview-title">ভাষা শিক্ষা ও চাকরি প্রস্তুতি <span class="mentor-price"><s>৳৮,৫০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">IELTS, স্পোকেন ইংলিশ, বিসিএস, ব্যাংক জবস ও সরকারি চাকরির সম্পূর্ণ প্রস্তুতি</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(2)">
                            <span class="overview-icon">👑</span>
                            <span class="overview-title">শীর্ষ প্রশিক্ষকবৃন্দ <span class="mentor-price"><s>৳২৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">আয়মান সাদিক, ঝংকার মাহবুব, মুনজারিন শহীদ সহ ১২ জন তারকা প্রশিক্ষকবৃন্দের কোর্স</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(8)">
                            <span class="overview-icon">🛠️</span>
                            <span class="overview-title">সফটওয়্যার টুলস <span class="mentor-price"><s>৳৬,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">এমএস অফিস, ফাইভার, আপওয়ার্ক, ওয়ার্ডপ্রেস, ইউটিউব — প্রিমিয়াম ভার্সন ফ্রিতে</span>
                        </div>
                        <div class="overview-item" onclick="switchTab(9)">
                            <span class="overview-icon">🔐</span>
                            <span class="overview-title">নোটস ও ডিজিটাল সুরক্ষা <span class="mentor-price"><s>৳৩,৫০০</s><span class="free-badge-sm">ফ্রি</span></span></span>
                            <span class="overview-desc">আরিফ নোটস, কপিরাইট কোর্স ও ডিজিটাল নিরাপত্তা — শেখার পাশাপাশি সুরক্ষিত থাকুন</span>
                        </div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="1">
            <section class="info-card">
                    <h3>🏛️ প্রতিষ্ঠানসমূহ</h3>
                    <p class="subline">প্রধান প্রতিষ্ঠানসমূহের তালিকা।</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">📘 টেন মিনিট স্কুল (10MS) <span class="mentor-price"><s>৳৮৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📗 ঘুড়ি লার্নিং (Ghoori Learning) <span class="mentor-price"><s>৳৫৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📙 স্কিল আপ (Skill Up) <span class="mentor-price"><s>৳৩৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📕 ইশিখন (eShikhon.com) <span class="mentor-price"><s>৳৬৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 মায়াজাল (Mayajal) <span class="mentor-price"><s>৳৪০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🖥️ MSB Academy (মাসুক সরকারের MSB) <span class="mentor-price"><s>৳৭৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">⚙️ ক্রিয়েটিভ আইটি (Creative IT) <span class="mentor-price"><s>৳৯০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🧩 প্রব্লেম কেআই (Problem KI) <span class="mentor-price"><s>৳৩০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📖 রেপটো (REPTO) <span class="mentor-price"><s>৳১২,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="9">
                <section class="info-card">
                    <h3>🔐 নোটস ও সুরক্ষা</h3>
                    <p class="subline">শেখার পাশাপাশি প্রয়োজনীয় নিরাপত্তা বিষয়ক রিসোর্স।</p>
                    <div class="chip-row">
                        <span class="chip">📒 Arif Notes | সকল নোটস</span>
                        <span class="chip">🔐 Copyright Content Course</span>
                        <span class="chip">🛡️ ডিজিটাল সুরক্ষা</span>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="2">
                <section class="info-card">
                    <h3>🏆 যে সকল প্রশিক্ষকবৃন্দের কোর্স আপনি ফ্রিতে পাবেন</h3>
                    <p class="subline">তালিকায় থাকা সকল জনপ্রিয় প্রশিক্ষকবৃন্দের কোর্স একেবারে ফ্রিতেই পাবেন</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">👑 আয়মান সাদিক (Ayman Sadiq) <span class="mentor-price"><s>৳৪৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 মুনজারিন শহীদ (Munzarin Shahid) <span class="mentor-price"><s>৳২৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">💻 ঝংকার মাহবুব — Jhankar Mahbub <span class="mentor-price"><s>৳৫৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🚀 খালিদ ফারহান (Khalid Farhan) <span class="mentor-price"><s>৳৩০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🎨 সাদমান সাদিক — Sadman Sadik <span class="mentor-price"><s>৳২০,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🌍 ফ্রিল্যান্সার নাসিম — Freelancer Nasim <span class="mentor-price"><s>৳২৮,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🎤 তাহসান খান — Tahsan Khan <span class="mentor-price"><s>৳৩৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 জুবায়ের হোসাইন — Jubayer Hossain <span class="mentor-price"><s>৳২৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 আবতাহি ইপ্তেসাম — Abtahi Iptesam <span class="mentor-price"><s>৳১৮,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🕌 মাহাদে হাসান — Mahade Hasan <span class="mentor-price"><s>৳১৫,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">💼 ভৈভব সিসিনিটি — Vaibhav Sisinity <span class="mentor-price"><s>৳৩২,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                        <div class="mentor-item"><span class="mentor-name">🔍 সোবান তারিক — Soban Tariq <span class="mentor-price"><s>৳২২,০০০</s><span class="free-badge-sm">ফ্রি</span></span></span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="6">
                <section class="info-card">
                    <h3>📚 সরকারি বেসরকারি চাকরি এবং ভাষা শিক্ষা কোর্সসমূহ</h3>
                    <p class="subline">তালিকায় থাকা প্রত্যেকটি কোর্স পাবেন একেবারে ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">🌍 আইইএলটিএস — IELTS</span></div>
                        <div class="mentor-item"><span class="mentor-name">🗣️ স্পোকেন ইংলিশ — Spoken English</span></div>
                        <div class="mentor-item"><span class="mentor-name">📋 বিসিএস প্রিলিমিনারি — BCS Preliminary</span></div>
                        <div class="mentor-item"><span class="mentor-name">✍️ ইংলিশ গ্রামার ক্র্যাশ কোর্স — English Grammar Crash Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">📝 ইংলিশ গ্রামার ক্র্যাশ কোর্স — English Grammar Crash Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏛️ সরকারি চাকরি প্রস্তুতি — Government Job Preparation</span></div>
                        <div class="mentor-item"><span class="mentor-name">👩‍🏫 প্রাথমিক সহকারী শিক্ষক নিয়োগ — Primary Assistant Teacher</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏦 ব্যাংক জবস ফুল কোর্স — Bank Jobs Full Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧒 স্পোকেন ইংলিশ ফর কিডস — Spoken English For Kids</span></div>
                        <div class="mentor-item"><span class="mentor-name">💼 ইংলিশ ফর পেশাদার — English For Professional</span></div>
                        <div class="mentor-item"><span class="mentor-name">✏️ ইংলিশ রাইটিং ফর শিক্ষার্থী — English Writing For Student</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏠 ইংলিশ ফর ডেইলি লাইফ — English For Daily Life</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔤 ইংলিশ গ্রামার ১০১ — English Grammar 101</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔠 ইংলিশ গ্রামার ১০২ — English Grammar 102</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌏 আইইএলটিএস জেনারেল — IELTS General Preparation</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎙️ অ্যাডভান্স ইংলিশ স্পিকিং — Advanced English Speaking</span></div>
                        <div class="mentor-item"><span class="mentor-name">📕 ভোকাবুলারি ফর অল — Vocabulary For All</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧠 স্টাডি স্মার্ট — Study Smart</span></div>
                        <div class="mentor-item"><span class="mentor-name">💻 মাইক্রোসফট অফিস ফুল কোর্স — MS Office Full Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 মাইক্রোসফট এক্সেল — Microsoft Excel</span></div>
                        <div class="mentor-item"><span class="mentor-name">📄 মাইক্রোসফট ওয়ার্ড — Microsoft Word</span></div>
                        <div class="mentor-item"><span class="mentor-name">📽️ মাইক্রোসফট পাওয়ারপয়েন্ট — Microsoft PowerPoint</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖥️ কম্পিউটার বেসিক কোর্স — Computer Basic Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖱️ কম্পিউটার বেসিক কোর্স — Computer Basic Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">📈 ই-শিখন এক্সেল কোর্স — E-Shikhon Excel Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 অ্যাডভান্স এক্সেল — Advanced Excel</span></div>
                        <div class="mentor-item"><span class="mentor-name">📚 এইচএসসি ইংলিশ কোর্স — HSC English Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">📖 এইচএসসি টেস্ট পেপার সলভ — HSC Test Paper Solve</span></div>
                        <div class="mentor-item"><span class="mentor-name">⏳ এইচএসসি শেষ মুহূর্তের প্রস্তুতি — HSC Last Minute</span></div>
                        <div class="mentor-item"><span class="mentor-name">📉 এইচএসসি শর্ট সিলেবাস — HSC Short Syllabus</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 এসএসসি প্রস্তুতি কোর্স — SSC Preparation</span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="3">
                <section class="info-card">
                    <h3>💼 মূলধারার ফ্রিল্যান্সিং, ডিজিটাল মার্কেটিং, এসইও এবং ডেটা বিশ্লেষণ</h3>
                    <p class="subline">তালিকায় থাকা প্রত্যেকটি কোর্স পাবেন একেবারে ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">🏠 ঘরে বসে ফ্রিল্যান্সিং — Freelancing From Home</span></div>
                        <div class="mentor-item"><span class="mentor-name">📝 ডাটা এন্ট্রি ফ্রিল্যান্সিং — Data Entry Freelancing</span></div>
                        <div class="mentor-item"><span class="mentor-name">📘 ফেসবুক মার্কেটিং — Facebook Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎨 গ্রাফিক্স ডিজাইন — Graphics Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">📢 ডিজিটাল মার্কেটিং — Digital Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔍 এসইও ফর বিগিনার্স — SEO For Beginners</span></div>
                        <div class="mentor-item"><span class="mentor-name">📈 এসইও বেসিক — SEO Basic</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏡 আইটি বাড়ি SEO পার্ট ১ ও ২ — IT Bari SEO</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 গুগল অ্যাডস মাস্টারি — Google Ads Mastery</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 ফেসবুক অ্যাডস মাস্টারি — Facebook Ads Mastery</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔧 ফেসবুক পিক্সেল ও কনভার্সন API — Facebook Pixel &amp; API</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 গুগল অ্যানালিটিক্স ৪ — Google Analytics 4</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏷️ গুগল ট্যাগ ম্যানেজার ফর শপিফাই — GTM For Shopify</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ওয়েব অ্যানালিটিক্স মাস্টারি — Web Analytics Mastery</span></div>
                        <div class="mentor-item"><span class="mentor-name">⚙️ অ্যাডভান্সড গুগল ট্যাগ ম্যানেজার — Advanced GTM</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖥️ GA4 সার্ভার-সাইড ট্র্যাকিং — GA4 Server Side</span></div>
                        <div class="mentor-item"><span class="mentor-name">📋 গুগল অ্যাডস ম্যানেজমেন্ট — Google Ads Management</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛒 গুগল অ্যানালিটিক্স ফর ই-কমার্স — GA For E-Commerce</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛍️ গুগল শপিং অ্যাডস — Google Shopping Ads</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔁 ফেসবুক অ্যাডস ফানেল — Facebook Ads Funnel</span></div>
                        <div class="mentor-item"><span class="mentor-name">📹 অ্যাডভান্স ইউটিউব বুস্টিং — Advanced YouTube Boosting</span></div>
                        <div class="mentor-item"><span class="mentor-name">💼 লিঙ্কডইন মার্কেটিং — LinkedIn Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">📸 ইনস্টাগ্রাম মার্কেটিং — Instagram Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌟 ইনস্টাগ্রাম মার্কেটিং মাস্টারক্লাস — Instagram Masterclass</span></div>
                        <div class="mentor-item"><span class="mentor-name">💰 সিপিএ মার্কেটিং — CPA Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">📦 ডিজিটাল মার্কেটিং অল-ইন-ওয়ান — Digital Marketing All-In-One</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔰 বেসিক ডিজিটাল মার্কেটিং — Basic Digital Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖼️ ফেসবুক কনটেন্ট ডিজাইন — Facebook Content Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">▶️ ইউটিউব মার্কেটিং — YouTube Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 রয় ডিজিটাল মার্কেটিং — RoY Digital Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ওয়েবকোডার আইটি ডিজিটাল মার্কেটিং — Webcoder IT Digital Marketing</span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="5">
                <section class="info-card">
                    <h3>👨‍💻 কোডিং, ওয়েব ও সফটওয়্যার অ্যাপ্লিকেশন ডেভেলপমেন্ট</h3>
                    <p class="subline">তালিকায় থাকা প্রত্যেকটি কোর্স পাবেন একেবারে ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">🎨 ওয়েব ডিজাইন — Web Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔧 ওয়ার্ডপ্রেস — WordPress</span></div>
                        <div class="mentor-item"><span class="mentor-name">⚡ ফুল স্ট্যাক ওয়েব ডেভেলপমেন্ট — Full Stack Web Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔄 MERN স্ট্যাক ওয়েব ডেভেলপমেন্ট — MERN Stack</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 অ্যাপ ডেভেলপমেন্ট — App Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🤖 অ্যান্ড্রয়েড অ্যাপ ডেভেলপমেন্ট — Android App Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">☕ জাভা — Java</span></div>
                        <div class="mentor-item"><span class="mentor-name">🐍 পাইথন বেসিক — Python Basic</span></div>
                        <div class="mentor-item"><span class="mentor-name">💻 সি প্রোগ্রাম — C Program</span></div>
                        <div class="mentor-item"><span class="mentor-name">🐘 পিএইচপি ও মাইএসকিউএল — PHP &amp; MySQL</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 ডার্ট অ্যান্ড ফ্লাটার — Dart And Flutter</span></div>
                        <div class="mentor-item"><span class="mentor-name">📚 কমপ্লিট জাভা কোর্স — Complete Java Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">🟣 অ্যান্ড্রয়েড বাই কটলিন — Android By Kotlin</span></div>
                        <div class="mentor-item"><span class="mentor-name">🚀 জিরো টু হিরো ইন অ্যান্ড্রয়েড — Zero To Hero In Android</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ডেভেলপ পেশাদার ওয়েবসাইট — Professional Websites</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔰 বেসিক ওয়ার্ডপ্রেস — Basic WordPress</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎨 ওয়ার্ডপ্রেস থিম ডেভেলপমেন্ট — WordPress Theme Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖌️ ওয়ার্ডপ্রেস থিম কাস্টমাইজেশন — WordPress Theme Customization</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖥️ ওয়েব থিম ডেভেলপমেন্ট — Web Theme Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏢 ASP.NET</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛒 ফুল স্ট্যাক শপ — Full Stack Shop</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎮 গেম ডেভেলপমেন্ট — Game Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧩 গেম ডেভেলপমেন্ট উইদাউট কোডিং — Game Dev Without Coding</span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="7">
                <section class="info-card">
                    <h3>🎨 ইউআই/ইউএক্স, ভিজ্যুয়াল মাল্টিমিডিয়া ও থ্রিডি অ্যানিমেশন আর্টস</h3>
                    <p class="subline">তালিকায় থাকা প্রত্যেকটি কোর্স পাবেন একেবারে ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">🎨 বেসিক UI/UX ডিজাইন — Basic UI/UX Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">📐 লার্ন UI/UX ফ্রম স্ক্র্যাচ — Learn UI/UX From Scratch</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖌️ UI/UX ডিজাইন (Interactive Care)</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎬 মোশন গ্রাফিক্স ইন আফটার ইফেক্টস — Motion Graphics In AE</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏫 ক্রিয়েটিভ আইটি মোশন গ্রাফিক্স — Creative IT Motion Graphics</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎞️ মোশন গ্রাফিক্স 2D ও 3D — Motion Graphics 2D &amp; 3D</span></div>
                        <div class="mentor-item"><span class="mentor-name">📽️ ২ডি/৩ডি মোশন — 2D/3D Motion</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧸 কার্টুন অ্যানিমেশন — Cartoon Animation</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧊 থ্রিডি অ্যানিমেশন বেসিক — 3D Animation Basic</span></div>
                        <div class="mentor-item"><span class="mentor-name">✏️ অ্যাডোবি ইলাস্ট্রেটর — Adobe Illustrator</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖼️ গ্রাফিক ডিজাইনিং উইথ ফটোশপ — Graphic Designing With Photoshop</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 অ্যাডোবি এক্সডি এসেনশিয়াল — Adobe XD Essential</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔤 লোগো ডিজাইন করে ফ্রিল্যান্সিং — Logo Design Freelancing</span></div>
                        <div class="mentor-item"><span class="mentor-name">👕 টি-শার্ট ডিজাইন করে ফ্রিল্যান্সিং — T-Shirt Design Freelancing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎽 টি-শার্ট ডিজাইন মাস্টারক্লাস — T-Shirt Design Masterclass</span></div>
                        <div class="mentor-item"><span class="mentor-name">📄 ফ্লায়ার ডিজাইন মাস্টারক্লাস — Flyer Design Masterclass</span></div>
                        <div class="mentor-item"><span class="mentor-name">💳 বিজনেস কার্ড ও ব্যানার ডিজাইন — Business Card &amp; Banner Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔄 গ্রাফিক্স ডিজাইন আপডেট টিউটোরিয়াল — Graphics Design Update</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌟 জিরো টু হিরো ইন ফটোশপ — Zero To Hero In Photoshop</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 গ্রাফিক্স ডিজাইন উইথ পাওয়ারপয়েন্ট — Graphics Design With PowerPoint</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏗️ অটোক্যাড কোর্স — AutoCAD Course</span></div>
                        <div class="mentor-item"><span class="mentor-name">✒️ বাংলা টাইপোগ্রাফি অ্যান্ড ক্যালিগ্রাফি — Bangla Typography &amp; Calligraphy</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎥 ভিডিও এডিটিং উইথ প্রিমিয়ার প্রো — Video Editing With Premiere Pro</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 মোবাইল দিয়ে গ্রাফিক ডিজাইন — Graphic Design Using Mobile</span></div>
                        <div class="mentor-item"><span class="mentor-name">📸 ফটো এডিটিং উইথ স্মার্টফোন — Photo Editing With Smartphone</span></div>
                        <div class="mentor-item"><span class="mentor-name">📷 মোবাইল ফটোগ্রাফি — Mobile Photography</span></div>
                        <div class="mentor-item"><span class="mentor-name">💒 ওয়েডিং ফটোগ্রাফি — Wedding Photography</span></div>
                        <div class="mentor-item"><span class="mentor-name">🍔 ফুড ফটোগ্রাফি — Food Photography</span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="4">
                <section class="info-card">
                    <h3>🌍 গ্লোবাল ই-কমার্স, ব্যবসা উদ্যোগ, পেশাদার পেশা ও অন্যান্য দক্ষতা</h3>
                    <p class="subline">তালিকায় থাকা প্রত্যেকটি কোর্স পাবেন একেবারে ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">🛒 ই-কমার্স স্টার্টআপ — E-Commerce Startup</span></div>
                        <div class="mentor-item"><span class="mentor-name">📦 ই-কমার্স স্টার্টআপ ২ — E-Commerce Startup 2</span></div>
                        <div class="mentor-item"><span class="mentor-name">🚢 শপিফাই ড্রপশিপিং — Shopify Dropshipping</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎨 শপিফাই থিম ডেভেলপমেন্ট — Shopify Theme Development</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔗 অ্যাফিলিয়েট মার্কেটিং ফর বিগিনার্স — Affiliate Marketing For Beginners</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 কমপ্লিট অ্যাফিলিয়েট মার্কেটিং — Complete Affiliate Marketing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔍 সোর্সিং এজেন্ট বিজনেস — Sourcing Agent Business</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌏 এক্সপোর্ট ফ্রম বাংলাদেশ — Export From Bangladesh</span></div>
                        <div class="mentor-item"><span class="mentor-name">👕 মার্চ বাই অ্যামাজন — Merch By Amazon</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎥 অ্যামাজন অ্যাফিলিয়েট উইথ ইউটিউব — Amazon Affiliate With YouTube</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏷️ অ্যালিএক্সপ্রেস অ্যাফিলিয়েট — AliExpress Affiliate</span></div>
                        <div class="mentor-item"><span class="mentor-name">⭐ ফাইভার মাস্টারক্লাস — Fiverr Masterclass</span></div>
                        <div class="mentor-item"><span class="mentor-name">📋 ফাইভার মার্কেটপ্লেস A-Z — Fiverr Marketplace A-Z</span></div>
                        <div class="mentor-item"><span class="mentor-name">✅ ফাইভার অ্যাকাউন্ট সাকসেস — Fiverr Account Success</span></div>
                        <div class="mentor-item"><span class="mentor-name">👩‍💼 ভার্চুয়াল অ্যাসিস্ট্যান্ট — Virtual Assistant</span></div>
                        <div class="mentor-item"><span class="mentor-name">📢 কমপ্লিট গুগল অ্যাডসেন্স — Complete Google AdSense</span></div>
                        <div class="mentor-item"><span class="mentor-name">✍️ কনটেন্ট রাইটিং — Content Writing</span></div>
                        <div class="mentor-item"><span class="mentor-name">📝 আর্টিকেল রাইটিং — Article Writing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏷️ প্রোডাক্ট ডিসক্রিপশন রাইটিং — Product Description Writing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ওয়েব কনটেন্ট ক্রিয়েশন — Web Content Creation</span></div>
                        <div class="mentor-item"><span class="mentor-name">📄 সিভি রাইটিং — CV Writing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 চাকরি জীবনের প্রস্তুতি — Career Preparation</span></div>
                        <div class="mentor-item"><span class="mentor-name">📅 প্রথম ৯০ দিনের প্ল্যান — First 90 Days Plan</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎤 কমুনিকেশন মাস্টারক্লাস — Communication Masterclass</span></div>
                        <div class="mentor-item"><span class="mentor-name">🧭 পেশা গাইডেন্স — Career Guidance</span></div>
                        <div class="mentor-item"><span class="mentor-name">🗣️ ইংলিশ ফর ফ্রিল্যান্সিং — English For Freelancing</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎬 ক্রিয়েটিভ কনটেন্ট ডিজাইন টেকনিকস — Creative Content Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">💡 ই-বিজনেস আইডিয়া — E-Business Idea</span></div>
                        <div class="mentor-item"><span class="mentor-name">📖 ২৪ ঘণ্টায় কোরআন শিক্ষা — Quran Learning in 24 Hours</span></div>
                        <div class="mentor-item"><span class="mentor-name">🕌 কোরআন লার্নিং — Quran Learning</span></div>
                        <div class="mentor-item"><span class="mentor-name">🗺️ সহজে স্পোকেন আরবি — Spoken Arabic Easily</span></div>
                        <div class="mentor-item"><span class="mentor-name">✒️ সুন্দর ও দ্রুত বাংলা হাতের লেখা — Bangla Handwriting</span></div>
                        <div class="mentor-item"><span class="mentor-name">✏️ দ্রুত ইংরেজি হাতের লেখা — Fast English Handwriting</span></div>
                        <div class="mentor-item"><span class="mentor-name">🤖 রোবোটিক্স ফর বিগিনার্স — Robotics For Beginners</span></div>
                        <div class="mentor-item"><span class="mentor-name">💪 পার্সোনাল ফিটনেস — Personal Fitness</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛡️ সেলফ ডিফেন্স — Self Defense</span></div>
                        <div class="mentor-item"><span class="mentor-name">🚗 বেসিক কার মেইনটেন্যান্স — Basic Car Maintenance</span></div>
                        <div class="mentor-item"><span class="mentor-name">👗 পেশাদার ব্লক প্রিন্ট ডিজাইন — Professional Block Print Design</span></div>
                        <div class="mentor-item"><span class="mentor-name">💰 ম্যাজিক অফ মিউচুয়াল ফান্ডস — Magic Of Mutual Funds</span></div>
                        <div class="mentor-item"><span class="mentor-name">🥗 Nutrition And Good Health</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔐 ইথিক্যাল হ্যাকিং — Ethical Hacking</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛡️ সার্টিফাইড এথিক্যাল হ্যাকিং — Certified Ethical Hacking</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 সাইবার ৭১ — Cyber 71</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 রুট ফোন — Root Phone</span></div>
                        <div class="mentor-item"><span class="mentor-name">💾 গুগল ড্রাইভ আনলিমিটেড স্টোরেজ — Google Drive Unlimited</span></div>
                        <div class="mentor-item"><span class="mentor-name">⚫ ব্ল্যাকহ্যাট মানি মেকিং — Blackhat Money Making</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎬 ভিডিওস্ক্রাইব সফটওয়্যার — VideoScribe Software</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏫 গ্রাফিক স্কুল — Graphic School</span></div>
                        <div class="mentor-item"><span class="mentor-name">💻 আইটি ফার্ম বিডি ক্লাস — IT Firm BD Class</span></div>
                    </div>
                </section>
            </div>

        <div class="part2-tab-content" data-tab-content="8">
                <section class="info-card">
                    <h3>🛠️ সফটওয়্যার টুলস এবং ডিজিটাল প্ল্যাটফর্মের প্রয়োজনীয়তার ক্রমবিন্যাস</h3>
                    <p class="subline">এই সফটওয়্যার গুলোর প্রিমিয়াম ভার্সন পাবেন ফ্রিতে</p>
                    <div class="mentor-list">
                        <div class="mentor-item"><span class="mentor-name">📁 মাইক্রোসফট অফিস — Microsoft Office</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 মাইক্রোসফট এক্সেল — Microsoft Excel</span></div>
                        <div class="mentor-item"><span class="mentor-name">📄 মাইক্রোসফট ওয়ার্ড — Microsoft Word</span></div>
                        <div class="mentor-item"><span class="mentor-name">📘 ফেসবুক অ্যাডস — Facebook Ads</span></div>
                        <div class="mentor-item"><span class="mentor-name">▶️ ইউটিউব — YouTube</span></div>
                        <div class="mentor-item"><span class="mentor-name">⭐ ফাইভার — Fiverr</span></div>
                        <div class="mentor-item"><span class="mentor-name">💼 আপওয়ার্ক — Upwork</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔧 ওয়ার্ডপ্রেস — WordPress</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎨 অ্যাডোবি ফটোশপ — Adobe Photoshop</span></div>
                        <div class="mentor-item"><span class="mentor-name">✏️ অ্যাডোবি ইলাস্ট্রেটর — Adobe Illustrator</span></div>
                        <div class="mentor-item"><span class="mentor-name">📽️ মাইক্রোসফট পাওয়ারপয়েন্ট — Microsoft PowerPoint</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 গুগল অ্যাডস — Google Ads</span></div>
                        <div class="mentor-item"><span class="mentor-name">🐍 পাইথন — Python</span></div>
                        <div class="mentor-item"><span class="mentor-name">☕ জাভা — Java</span></div>
                        <div class="mentor-item"><span class="mentor-name">🐘 পিএইচপি — PHP</span></div>
                        <div class="mentor-item"><span class="mentor-name">🗄️ মাইএসকিউএল — MySQL</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛍️ শপিফাই — Shopify</span></div>
                        <div class="mentor-item"><span class="mentor-name">📸 ইনস্টাগ্রাম — Instagram</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ফ্রিল্যান্সার ডটকম — Freelancer.com</span></div>
                        <div class="mentor-item"><span class="mentor-name">📈 গুগল অ্যানালিটিক্স — Google Analytics</span></div>
                        <div class="mentor-item"><span class="mentor-name">📊 GA4 — Google Analytics 4</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏷️ গুগল ট্যাগ ম্যানেজার — Google Tag Manager</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔵 ফেসবুক পিক্সেল — Facebook Pixel</span></div>
                        <div class="mentor-item"><span class="mentor-name">🔗 কনভার্সন API — Conversion API</span></div>
                        <div class="mentor-item"><span class="mentor-name">🟢 নোড.জেএস — Node.js</span></div>
                        <div class="mentor-item"><span class="mentor-name">⚡ এক্সপ্রেস.জেএস — Express.js</span></div>
                        <div class="mentor-item"><span class="mentor-name">🍃 মঙ্গোডিবি — MongoDB</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 ফ্লাটার — Flutter</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎯 ডার্ট — Dart</span></div>
                        <div class="mentor-item"><span class="mentor-name">🟣 কটলিন — Kotlin</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏢 ASP.NET</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎬 অ্যাডোবি প্রিমিয়ার প্রো — Adobe Premiere Pro</span></div>
                        <div class="mentor-item"><span class="mentor-name">✨ অ্যাডোবি আফটার ইফেক্টস — Adobe After Effects</span></div>
                        <div class="mentor-item"><span class="mentor-name">📐 অ্যাডোবি XD — Adobe XD</span></div>
                        <div class="mentor-item"><span class="mentor-name">📢 গুগল অ্যাডসেন্স — Google AdSense</span></div>
                        <div class="mentor-item"><span class="mentor-name">📦 অ্যামাজন — Amazon</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏷️ অ্যালিএক্সপ্রেস — AliExpress</span></div>
                        <div class="mentor-item"><span class="mentor-name">🛒 গুগল শপিং অ্যাডস — Google Shopping Ads</span></div>
                        <div class="mentor-item"><span class="mentor-name">📋 মার্চেন্ট সেন্টার — Merchant Center</span></div>
                        <div class="mentor-item"><span class="mentor-name">👥 পিপল পার আওয়ার — PeoplePerHour</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎓 গুরু — Guru</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏗️ অটোক্যাড — AutoCAD</span></div>
                        <div class="mentor-item"><span class="mentor-name">🏛️ অটোডেস্ক — Autodesk</span></div>
                        <div class="mentor-item"><span class="mentor-name">🖼️ মকআপ টুলস — Mockup Tools</span></div>
                        <div class="mentor-item"><span class="mentor-name">🎬 ভিডিওস্ক্রাইব — VideoScribe</span></div>
                        <div class="mentor-item"><span class="mentor-name">📱 টার্মাক্স — Termux</span></div>
                        <div class="mentor-item"><span class="mentor-name">🌐 ওয়েবিডো — Webydo</span></div>
                    </div>
                </section>
            </div>

            <div class="section-toggle fade-in">
                <button class="section-toggle-btn" onclick="toggleSection(this)">
                    📂 <span>সব প্ল্যাটফর্ম ও ট্রেইনার দেখুন (২১টি)</span>
                    <span class="toggle-arrow">▼</span>
                </button>
                <div class="section-toggle-content">
            <section class="info-card platform-logos-section">
                <h3>🏛️ আমাদের প্রতিষ্ঠানসমূহ</h3>
                <p class="subline">যেসব প্ল্যাটফর্ম ও প্রতিষ্ঠানের কোর্স আপনি ফ্রিতে পাচ্ছেন</p>
                <div class="platform-logo-grid">
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/10-Minute-School.jpg" alt="টেন মিনিট স্কুল (10MS)" loading="lazy">
                        <span>📘 টেন মিনিট স্কুল (10MS)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/Ghoori-Learning.jpeg" alt="ঘুড়ি লার্নিং" loading="lazy">
                        <span>📗 ঘুড়ি লার্নিং</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/Skill-Up.png" alt="স্কিল আপ" loading="lazy">
                        <span>📙 স্কিল আপ (Skill Up)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/eShikhon.com_.webp" alt="ইশিখন" loading="lazy">
                        <span>📕 ইশিখন (eShikhon.com)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/Mayajal.jpg" alt="মায়াজাল" loading="lazy">
                        <span>📊 মায়াজাল (Mayajal)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/MSB-Academy.png" alt="MSB Academy" loading="lazy">
                        <span>🖥️ MSB Academy</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/Creative-IT.jpg" alt="ক্রিয়েটিভ আইটি" loading="lazy">
                        <span>⚙️ ক্রিয়েটিভ আইটি (Creative IT)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/Problem-KI.png" alt="প্রব্লেম কেআই" loading="lazy">
                        <span>🧩 প্রব্লেম কেআই (Problem KI)</span>
                    </div>
                    <div class="platform-logo-item">
                        <img src="https://jobayergroup.com/wp-content/uploads/2026/06/REPTO.jpg" alt="রেপটো" loading="lazy">
                        <span>📖 রেপটো (REPTO)</span>
                    </div>
                </div>
            </section>

            <section id="trainerSection" class="info-card" style="margin-top:20px">
                <h3>👨‍🏫 শীর্ষ প্রশিক্ষকবৃন্দ</h3>
                <p class="subline">যেসব তারকা প্রশিক্ষকের কোর্স আপনি ফ্রিতে পাচ্ছেন</p>
                <div class="trainer-photo-grid">
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Ayman-Sadiq.jpg" alt="আয়মান সাদিক" loading="lazy"></div>
                        <span class="trainer-name">👑 আয়মান সাদিক (Ayman Sadiq)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Munzereen-Shahid.jpg" alt="মুনজারিন শহীদ" loading="lazy"></div>
                        <span class="trainer-name">🎯 মুনজারিন শহীদ (Munzarin Shahid)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Jhankar-Mahbub.jpg" alt="ঝংকার মাহবুব" loading="lazy"></div>
                        <span class="trainer-name">💻 ঝংকার মাহবুব (Jhankar Mahbub)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Khalid-Farhan.jpg" alt="খালিদ ফারহান" loading="lazy"></div>
                        <span class="trainer-name">🚀 খালিদ ফারহান (Khalid Farhan)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Sadman-Sadik.jpg" alt="সাদমান সাদিক" loading="lazy"></div>
                        <span class="trainer-name">🎨 সাদমান সাদিক (Sadman Sadik)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Freelancer-Nasim.jpg" alt="ফ্রিল্যান্সার নাসিম" loading="lazy"></div>
                        <span class="trainer-name">🌍 ফ্রিল্যান্সার নাসিম (Freelancer Nasim)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Tahsan-Khan.jpg" alt="তাহসান খান" loading="lazy"></div>
                        <span class="trainer-name">🎤 তাহসান খান (Tahsan Khan)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Jubayer-Hossain.jpg" alt="জুবায়ের হোসাইন" loading="lazy"></div>
                        <span class="trainer-name">📱 জুবায়ের হোসাইন (Jubayer Hossain)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Abtahi-Iptesam.jpg" alt="আবতাহি ইপ্তেসাম" loading="lazy"></div>
                        <span class="trainer-name">📊 আবতাহি ইপ্তেসাম (Abtahi Iptesam)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Mahade-Hasan.jpg" alt="মাহাদে হাসান" loading="lazy"></div>
                        <span class="trainer-name">🕌 মাহাদে হাসান (Mahade Hasan)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Vaibhav-Sisinity.jpg" alt="ভৈভব সিসিনিটি" loading="lazy"></div>
                        <span class="trainer-name">💼 ভৈভব সিসিনিটি (Vaibhav Sisinity)</span>
                    </div>
                    <div class="trainer-photo-item">
                        <div class="trainer-img-wrap"><img src="https://jobayergroup.com/wp-content/uploads/2026/06/Soban-Tariq.jpg" alt="সোবান তারিক" loading="lazy"></div>
                        <span class="trainer-name">🔍 সোবান তারিক (Soban Tariq)</span>
                    </div>
                </div>
            </section>
                </div>
            </div>
        <a class="mid-cta" href="#checkoutSection" onclick="var s=document.getElementById('checkoutSection');if(s){s.scrollIntoView({behavior:'smooth',block:'start'});return false;}">🔥 হ্যাঁ, আমি ৯৯ টাকায় পুরো বান্ডেল নিচ্ছি ➔</a>
    </section>

    <!-- Phase 6: Google Drive Folder Preview (Compact) -->
    <div class="section-toggle fade-in">
        <button class="section-toggle-btn" onclick="toggleSection(this)">
            📂 <span>প্রিভিউ দেখুন — গুগল ড্রাইভে কী আছে</span>
            <span class="toggle-arrow">▼</span>
        </button>
        <div class="section-toggle-content">
    <div class="drive-preview-wrap">
        <div class="headline-badge" style="display:flex;width:fit-content;margin:0 auto 14px;">
            📁 কেনার পর পাবেন — ২৩০+ কোর্সের গুগল ড্রাইভ!
        </div>
        <div class="drive-preview-card">
            <div class="drive-preview-header">
                <span class="drive-logo">📁</span>
                <span class="drive-title">জোবায়ের গ্রুপ পেশা — মাস্টার বান্ডেল (২৩০+ কোর্স)</span>
                <span class="drive-badge drive-pulse">🟢 লাইভ</span>
            </div>
            <div class="drive-preview-body" id="driveBody">
                <div class="drive-folder drive-folder-open" style="cursor:pointer;">
                    <span class="folder-icon">📂</span>
                    ✅ টাকা দিলেই সব খুলে যাবে — সাথে সাথে!
                    <span class="folder-count">⚡ ১ সেকেন্ড</span>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;padding:16px 12px;">
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">💼 ফ্রিল্যান্সিং ও আয়</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">🌐 ওয়েব ডেভেলপমেন্ট</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">🎨 গ্রাফিক্স ও ভিডিও</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">🛒 ই-কমার্স</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">🗣️ ভাষা ও চাকরি</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">📱 অ্যাপ ও গেম</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">🔐 সাইবার সিকিউরিটি</span>
                    <span style="background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;">📒 নোটস</span>
                </div>
            </div>
            <div class="drive-preview-footer">
                <span>⚡ এখনই কিনলে সঙ্গে সঙ্গে গুগল ড্রাইভে <strong>সব খুলে যাবে!</strong> 🚀</span>
            </div>
        </div>
    </div>
            </div>
        </div>

    

    <div class="section-toggle fade-in">
        <button class="section-toggle-btn" data-target="salary" onclick="toggleSection(this)">
            📊 <span>লাইভ আয় দেখুন — কে কত টাকা পাচ্ছে</span>
            <span class="toggle-arrow">▼</span>
        </button>
        <div class="section-toggle-content">

    <!-- PART 3 -->
    <section id="salarySection" class="salary-section">
        <div class="headline-badge" style="display:flex; width:fit-content; margin:0 auto 14px;">
            🟢 লাইভ: এই মুহূর্তে যারা যুক্ত — তারা কত টাকা পাচ্ছেন তা দেখুন!
        </div>

        <div class="salary-wrapper">
            <div class="salary-dashboard">
                <div class="salary-header">
                    <div class="salary-header-top">
                        <div class="live-circle"></div>
                        লাইভ — বোনাস বিতরণ করা হচ্ছে 🟢
                    </div>
                    <div class="salary-header-subtitle">এই মুহূর্তে কে কত টাকা আয় করছে তা নিচে দেখুন — কয়েক সেকেন্ড পরপর নতুন আয়ের খবর আসবে!</div>
                    <div style="margin-top:8px;padding:6px 12px;border-radius:999px;background:rgba(29,78,216,.08);border:1px solid rgba(29,78,216,.15);font-size:11px;font-weight:700;color:var(--text);">📊 নিচের তথ্যগুলো আমাদের সফল শিক্ষার্থীদের প্রকৃত আয়ের ভিত্তিতে তৈরি ডেমো</div>
                </div>

                <div class="tableWrap">
                    <table>
                        <thead>
                            <tr>
                                <th>নাম</th>
                                <th>মোট বোনাস</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody id="jgSalaryBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
        </div>
    </div>
    <div class="section-toggle fade-in">
        <button class="section-toggle-btn" data-target="gallery" onclick="toggleSection(this)">
            🧾 <span>পেমেন্ট স্ক্রিনশট দেখুন (১০টি)</span>
            <span class="toggle-arrow">▼</span>
        </button>
        <div class="section-toggle-content">

    <section id="gallerySection" class="content-section">
        <div class="headline-badge" style="display:flex; width:fit-content; margin:0 auto 14px;">
            💰 ব্যাংক, বিকাশ ও নগদে পেমেন্টের বাস্তব ছবি — দেখুন!
        </div>

        <div class="section-title">📸 রিয়েল পার্টনার — রিয়েল পেমেন্ট প্রমাণ</div>
        <div class="section-subtitle">
            নিচে আমাদের সফল শিক্ষার্থীদের ব্যাংক, বিকাশ ও নগদে টাকা পাওয়ার বাস্তব ছবি — আপনার চোখের সামনে প্রমাণ।
        </div>

        <div class="gallery-grid" id="galleryGrid">
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image.jpg" alt="Payment proof 1" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-1.jpg" alt="Payment proof 2" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-2.jpg" alt="Payment proof 3" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-3.jpg" alt="Payment proof 4" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-4.jpg" alt="Payment proof 5" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-5.jpg" alt="Payment proof 6" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-6.jpg" alt="Payment proof 7" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-7.jpg" alt="Payment proof 8" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-8.jpg" alt="Payment proof 9" loading="lazy" decoding="async"></div>
            <div class="gallery-item"><img src="https://jobayergroup.com/wp-content/uploads/2026/04/image-9.jpg" alt="Payment proof 10" loading="lazy" decoding="async"></div>
        </div>

    </section>
        </div>
    </div>

    <!-- PART 5 -->
    <section id="reviewSection" class="section-wrap">
        <div class="headline-badge" style="display:flex; width:fit-content; margin:0 auto 14px;">
            💬 আগে সন্দেহ ছিল, আজ মাসে ২৫,০০০-৫০,০০০+ টাকা আয় করছেন!
        </div>

        <div class="reviews-slider-wrap">
            <div class="reviews-track" id="reviewsTrack">
                <div class="review-slide">
                    <div class="review-slide-inner">
                        <div class="review-stars">★★★★★ <span>5.0/5</span></div>
                        <div class="review-quote">"আমি আগে কখনো অনলাইনে কাজ করিনি। জোবায়ের গ্রুপের নির্দেশিকা আর সহায়তার কারণে আজ আমি নিজের ল্যাপটপ থেকে মাসে ২৫,০০০+ টাকা ইনকাম করছি। শুরুটা ছিল ৯৯ টাকা, কিন্তু ভ্যালু পেয়েছি লক্ষ টাকার বেশি!"</div>
                        <div class="review-author">— মিতা ইসলাম</div>
                        <div class="review-label">ফ্রিল্যান্সার, সিলেট</div>
                    </div>
                </div>
                <div class="review-slide">
                    <div class="review-slide-inner">
                        <div class="review-stars">★★★★★ <span>4.9/5</span></div>
                        <div class="review-quote">"ইউটিউবে অনেক কিছু ফ্রিতে পাওয়া যায়, কিন্তু স্ট্রাকচার আর দিকনির্দেশনা ছাড়া শেখা অসম্পূর্ণ। এই কোর্সটা আমাকে রিয়েল মার্কেটের জন্য প্রস্তুত করেছে। এখন নিয়মিত ক্লায়েন্ট পাচ্ছি। সবার কাছে রেকমেন্ড করব!"</div>
                        <div class="review-author">— নীলা হোসেন</div>
                        <div class="review-label">ডিজিটাল মার্কেটার, ঢাকা</div>
                    </div>
                </div>
                <div class="review-slide">
                    <div class="review-slide-inner">
                        <div class="review-stars">★★★★★ <span>5.0/5</span></div>
                        <div class="review-quote">"শুরুতে ভেবেছিলাম এটা আর দশটা অনলাইন স্ক্যাম হবে। কিন্তু জোবায়ের গ্রুপের ট্রান্সপারেন্সি আর রিয়েল শিক্ষার্থী ফলাফল দেখে কনফিডেন্ট হলাম। ৭ মাসে এখন মাসিক আয় ৪০,০০০+। সবচেয়ে বড় কথা, একটা সহায়ক কমিউনিটি পেয়েছি।"</div>
                        <div class="review-author">— রাফসান জামান</div>
                        <div class="review-label">ই-কমার্স আর্নার, চট্টগ্রাম</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-dots" id="reviewsDots">
            <button class="review-dot active" data-slide="0"></button>
            <button class="review-dot" data-slide="1"></button>
            <button class="review-dot" data-slide="2"></button>
        </div>

        <!-- Phase 6: Chat-style testimonial cards -->
        <div class="chat-testimonial-grid">
            <div class="chat-testi-card">
                <div class="chat-testi-header">
                    <div class="chat-testi-avatar" style="background:linear-gradient(135deg,var(--accent-blue),var(--cta));">🌸</div>
                    <div>
                        <div class="chat-testi-name">রোজিনা আক্তার</div>
                        <div class="chat-testi-platform">ফেসবুক গ্রুপ · ২ সপ্তাহ আগে</div>
                    </div>
                    <div class="chat-testi-stars" style="margin-left:auto;">★★★★★</div>
                </div>
                <div class="chat-testi-msg">
                    প্রথম বোনাস পাওয়ার দিনটা এখনো মনে আছে। নিজের চেষ্টায় কিছু অর্জনের অনুভূতি অসাধারণ! মাত্র ৯৯ টাকা দিয়ে শুরু করে আজ মাসে ২৫,০০০+ টাকা আয় করছি। যারা দ্বিধায় আছেন, তাদের বলব—শুরু করে দেখুন!
                </div>
                <div class="chat-testi-time">গতকাল ৩:৪২ PM</div>
            </div>
            <div class="chat-testi-card">
                <div class="chat-testi-header">
                    <div class="chat-testi-avatar" style="background:linear-gradient(135deg,var(--accent-blue),var(--cta));">🌿</div>
                    <div>
                        <div class="chat-testi-name">পূর্ণিমা বেগম</div>
                        <div class="chat-testi-platform">হোয়াটসঅ্যাপ গ্রুপ · ১ মাস আগে</div>
                    </div>
                    <div class="chat-testi-stars" style="margin-left:auto;">★★★★★</div>
                </div>
                <div class="chat-testi-msg">
                    আমি গ্রামের মেয়ে। আগে ভাবতাম অনলাইনে কাজ করা শুধু শহরের ছেলেমেয়েদের জন্য। কিন্তু এই প্ল্যাটফর্মে যুক্ত হওয়ার পর আমার চোখ খুলে গেছে। এখন ঘরে বসে কাজ করি আর পরিবারকে সাহায্য করি।
                </div>
                <div class="chat-testi-time">গত সপ্তাহে ১১:১৫ AM</div>
            </div>
            <div class="chat-testi-card">
                <div class="chat-testi-header">
                    <div class="chat-testi-avatar" style="background:linear-gradient(135deg,var(--accent-blue),var(--cta));">💪</div>
                    <div>
                        <div class="chat-testi-name">ফারিয়া ইসলাম</div>
                        <div class="chat-testi-platform">ফেসবুক মেসেঞ্জার · ৩ সপ্তাহ আগে</div>
                    </div>
                    <div class="chat-testi-stars" style="margin-left:auto;">★★★★★</div>
                </div>
                <div class="chat-testi-msg">
                    শেখার সুযোগ আর আয়—দুটোই পাচ্ছি এখান থেকে। সঠিক নির্দেশিকা আর সহায়তা পেলে যে কেউ সফল হতে পারে। আমি এখন ৬ মাস ধরে যুক্ত আর প্রতিদিন নতুন কিছু শিখছি। ধন্যবাদ জোবায়ের গ্রুপ টিমকে!
                </div>
                <div class="chat-testi-time">গতকাল ৭:০৮ PM</div>
            </div>
            <div class="chat-testi-card">
                <div class="chat-testi-header">
                    <div class="chat-testi-avatar" style="background:linear-gradient(135deg,var(--accent-blue),var(--cta));">🔥</div>
                    <div>
                        <div class="chat-testi-name">তামান্না হাসান</div>
                        <div class="chat-testi-platform">হোয়াটসঅ্যাপ · ৫ দিন আগে</div>
                    </div>
                    <div class="chat-testi-stars" style="margin-left:auto;">★★★★★</div>
                </div>
                <div class="chat-testi-msg">
                    আমার জন্য এটি নতুন দিগন্ত খুলে দিয়েছে। প্রথমে ভয় ছিল, কিন্তু টিমের সহায়তায় সব ভয় কেটে গেছে। এখন ইনকাম শুরু করেছি, খুব ভালো লাগছে। এখন আমি অনলাইন জগত নিয়ে অনেক বেশি আত্মবিশ্বাসী!
                </div>
                <div class="chat-testi-time">গতকাল ৯:২২ PM</div>
            </div>
        </div>

        <div class="review-grid">

            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">নীলা হোসেন</div><div class="review-text">আমি শুরুতে ভেবেছিলাম এটা হয়তো অন্য অনেক অনলাইন সুযোগের মতোই হবে। কিন্তু কাজ শুরু করার পর বুঝলাম এখানে নিয়মিত নির্দেশিকা দেওয়া হয় এবং নতুনদের শেখানোর জন্য আলাদা সহায়তা রয়েছে। এখন প্রতি মাসে নিয়মিত কাজ করছি এবং সময়মতো পেমেন্ট পাচ্ছি। সবচেয়ে ভালো লেগেছে শেখার সুযোগগুলো।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">মিতা ইসলাম</div><div class="review-text">আমি আগে কোনো অনলাইন কাজ করিনি। এখানে যোগ দেওয়ার পর ধাপে ধাপে কাজ শিখেছি। কাজের পাশাপাশি অনেক স্কিল ডেভেলপমেন্ট রিসোর্সও পেয়েছি। পরিবারের পাশে থেকে কাজ করতে পারছি, এটা আমার জন্য সবচেয়ে বড় সুবিধা।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">রাফসান জামান</div><div class="review-text">ই-কমার্সে কাজ করে মাসে ৪০,০০০+ ইনকাম করছি। শুরুতে কেউ বিশ্বাস করেনি, কিন্তু আজ আমি প্রমাণ করেছি। সবার দোয়া চাই।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">সাবরিনা খান</div><div class="review-text">ডিজিটাল মার্কেটিং শিখে এখন নিজেই ক্লায়েন্ট ম্যানেজ করি। এটা শুধু আয়ের জায়গা না, এটি পেশা গড়ার জায়গা।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.8/5</span></div><div class="review-name">রেহানা বেগম</div><div class="review-text">গৃহিণী হয়েও অনলাইনে কাজ করা সম্ভব—এটা প্রমাণ করেছি নিজেই। মাসে ভালো ইনকাম করছি।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">নাদিয়া সুলতানা</div><div class="review-text">প্রথম প্রথম কাজ পেতে সময় লেগেছে, কিন্তু এখন নিয়মিত আয়। সবার ধৈর্য ধরাটা জরুরি।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">নাজনীন আক্তার</div><div class="review-text">যারা শুরু করতে চান তাদের বলব—ভয় না করে শুরু করুন। সঠিক নির্দেশিকা পেলে সফলতা আসবেই।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">তামান্না হাসান</div><div class="review-text">আমার জন্য এটি নতুন দিগন্ত খুলে দিয়েছে। এখন আমি অনলাইন জগত নিয়ে অনেক বেশি আত্মবিশ্বাসী।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">মর্জিনা খাতুন</div><div class="review-text">প্রথমে ভয় ছিল, কিন্তু টিমের সহায়তায় সব ভয় কেটে গেছে। এখন ইনকাম শুরু করেছি, খুব ভালো লাগছে।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">শামীমা আক্তার</div><div class="review-text">যারা এখনো শুরু করেননি, তাদের বলব—সময় নষ্ট না করে শুরু করুন। সঠিক নির্দেশিকা পেতে দেরি করবেন না।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">ফারিয়া ইসলাম</div><div class="review-text">শেখার সুযোগ আর আয়—দুটোই পাচ্ছি। যারা শুরু করবেন, তাদের জন্য এটি সেরা জায়গা।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">আনিকা ইসলাম</div><div class="review-text">ই-কমার্সে কাজ করে মাসে ৪০,০০০+ ইনকাম করছি। শুরুতে কেউ বিশ্বাস করেনি, কিন্তু আজ আমি প্রমাণ করেছি।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">সাবিকুন নাহার</div><div class="review-text">সঠিক নির্দেশিকা এবং সহায়তা পেলে যে কেউ সফল হতে পারে। আমি তার উদাহরণ।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">নুসরাত জাহান</div><div class="review-text">নতুনদের ছোট করে দেখা হয় না, যে কোনো প্রশ্নের উত্তর ধৈর্য সহকারে দেওয়া হয়। এটাই আমার সবচেয়ে পছন্দের দিক।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.8/5</span></div><div class="review-name">ইভা রহমান</div><div class="review-text">শুরুতে ধৈর্য ধরে কাজ করতে হবে, কিন্তু ফলাফল ভালো। আমি এখন সন্তুষ্ট।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>4.9/5</span></div><div class="review-name">মামুন মিয়া</div><div class="review-text">যারা দ্বিধায় আছেন তাদের বলব, শুরু করে দিন। এখানে সঠিক দিকনির্দেশনা পাবেন।</div></div>
            <div class="review-card hidden-review" style="display:none"><div class="review-stars">★★★★★ <span>5.0/5</span></div><div class="review-name">মোর্শেদ মিয়া</div><div class="review-text">পেশাদার পরিবেশ আর নিয়মিত কাজ — এটাই সবার জন্য দরকার।</div></div>

            <button id="showMoreReviewsBtn" onclick="showAllReviews()" style="grid-column:1/-1;padding:14px 24px;border-radius:12px;border:2px solid #1D4ED8;background:transparent;color:#1D4ED8;font-weight:900;font-size:15px;cursor:pointer;font-family:inherit;text-align:center;transition:all .2s;margin-top:4px;">📢 আরো মতামত দেখুন (১৭টি)</button>
        </div>
    </section>

    <!-- TRUST & SECURITY SECTION -->
    <section id="guaranteeSection" class="section-wrap" style="margin:44px auto 0;max-width:900px;">
        <div class="headline-badge" style="display:flex;width:fit-content;margin:0 auto 14px;">🔒 বিশ্বাসযোগ্যতা ও নিরাপত্তা</div>
        <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:16px;">
            <span style="display:flex;align-items:center;gap:6px;padding:12px 18px;border-radius:14px;background:var(--card);border:1.5px solid var(--border);font-weight:800;font-size:14px;color:var(--text);box-shadow:0 4px 12px rgba(0,0,0,.04);">
                🔒 SSL সুরক্ষিত পেমেন্ট
            </span>
            <span style="display:flex;align-items:center;gap:6px;padding:12px 18px;border-radius:14px;background:rgba(16,185,129,.1);border:1.5px solid rgba(16,185,129,.3);font-weight:800;font-size:14px;color:var(--trust);box-shadow:0 4px 12px rgba(0,0,0,.04);">
                ✅ ২৪ ঘণ্টা — কোনো শর্ত ছাড়াই টাকা ফেরত
            </span>
            <span style="display:flex;align-items:center;gap:6px;padding:12px 18px;border-radius:14px;background:var(--card);border:1.5px solid var(--border);font-weight:800;font-size:14px;color:var(--text);box-shadow:0 4px 12px rgba(0,0,0,.04);">
                ⚡ পেমেন্টের সাথে সাথে এক্সেস
            </span>
            <span style="display:flex;align-items:center;gap:6px;padding:12px 18px;border-radius:14px;background:var(--card);border:1.5px solid var(--border);font-weight:800;font-size:14px;color:var(--text);box-shadow:0 4px 12px rgba(0,0,0,.04);">
                📞 ২৪/৭ কাস্টমার সাপোর্ট
            </span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:12px;padding:16px 20px;border-radius:16px;background:linear-gradient(135deg,rgba(29,78,216,.06),rgba(234,88,12,.05));border:1px solid rgba(29,78,216,.12);text-align:center;">
            <span style="font-weight:700;font-size:14px;color:var(--text);line-height:1.7;">
                💳 আমরা গ্রহণ করি: 
                <span style="display:inline-flex;align-items:center;gap:4px;margin-left:6px;padding:4px 12px;border-radius:999px;background:rgba(209,32,83,.08);color:#d12053;font-weight:800;font-size:13px;">বিকাশ</span>
                <span style="display:inline-flex;align-items:center;gap:4px;margin-left:4px;padding:4px 12px;border-radius:999px;background:rgba(246,146,30,.08);color:#e8731a;font-weight:800;font-size:13px;">নগদ</span>
                <span style="display:inline-flex;align-items:center;gap:4px;margin-left:4px;padding:4px 12px;border-radius:999px;background:rgba(226,19,110,.08);color:#e2136e;font-weight:800;font-size:13px;">রকেট</span>
                <span style="display:inline-flex;align-items:center;gap:4px;margin-left:4px;padding:4px 12px;border-radius:999px;background:rgba(29,78,216,.08);color:#1D4ED8;font-weight:800;font-size:13px;">SSL কমার্জ</span>
            </span>
        </div>
        <div style="text-align:center;padding:16px 20px;border-radius:14px;background:rgba(16,185,129,.12);border:2px solid rgba(16,185,129,.35);font-size:15px;font-weight:700;color:var(--trust);line-height:1.7;">
            🔑 এটি কোনো ফি নয় — শুধু আপনার আগ্রহ যাচাইয়ের জন্য ৯৯ টাকা। বিনিময়ে পাচ্ছেন <strong>১০ লক্ষ টাকার কোর্স বান্ডেল</strong> — আপনার কোনো ঝুঁকি নেই!
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <h2>🤔 আপনার মনে কি প্রশ্ন আছে?</h2>
        <p>নিচের প্রশ্নগুলোর উত্তর জেনে নিন — তারপর সিদ্ধান্ত নিন</p>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>💵 ৯৯ টাকা দিয়ে কি সত্যিই অনলাইনে আয় করা সম্ভব?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">হ্যাঁ, সম্ভব। ৯৯ টাকায় আপনি পাচ্ছেন ১০ লক্ষ টাকার কোর্স, সরাসরি সাহায্য ও ৩০ দিনের নির্দেশিকা। আমাদের ৮৬৬+ শিক্ষার্থী প্রমাণ করে সঠিক গাইড পেলে যে কেউ আয় করতে পারেন। মাসে ৫০,০০০+ টাকা আয় করা সম্ভব।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>🛡️ এটি কি কোনো স্ক্যাম বা ফেক প্রোগ্রাম?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">একেবারেই না। জোবায়ের গ্রুপ ৮+ বছর ধরে কাজ করছে। আমাদের শত শত পেমেন্ট প্রমাণ, ২৩০+ মতামত, ফেসবুকে ৪.৯★ রেটিং সবই দেখতে পাচ্ছেন। আমরা ২৪ ঘণ্টায় টাকা ফেরত দিই — কোনো প্রতারক কোম্পানি টাকা ফেরত দেয় না। আপনি নিশ্চিন্তে শুরু করতে পারেন।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>📱 আমার কোনো পূর্ব অভিজ্ঞতা নেই — তবু কি পারব?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">অবশ্যই। আমাদের কোর্স একদম শুরু থেকে শেখানোর জন্য তৈরি। আপনার শুধু দরকার একটি স্মার্টফোন বা ল্যাপটপ আর শেখার ইচ্ছা। বাকি সব — ভিডিও, গাইড, সাহায্য — আমরা দিচ্ছি। যারা শুরুতে কিছুই জানতেন না, তারাও আজ মাসে ২০,০০০-৫০,০০০+ টাকা আয় করছেন।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>💰 কত তাড়াতাড়ি আমি প্রথম পেমেন্ট পাব?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">কোর্স শেষ করার পরপরই কাজ শুরু করতে পারবেন। বেশিরভাগ শিক্ষার্থী প্রথম মাসেই ১,১০০ - ৫,০০০+ টাকা আয় শুরু করেন। কেউ কেউ প্রথম সপ্তাহেই প্রথম পেমেন্ট পেয়ে যান। মাসে ৫০,০০০+ টাকা আয় করতে ৩-৬ মাস লাগতে পারে।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>🔁 টাকা ফেরত পাব কি না — যদি কাজ না হয়?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">হ্যাঁ। ২৪ ঘণ্টার মধ্যে যে কোনো কারণে আপনি ফেরত চাইলে আপনার ৯৯ টাকা পুরো ফেরত পাবেন। কোনো প্রশ্ন নেই, কোনো ঝামেলা নেই। এটা আমাদের আত্মবিশ্বাসের প্রমাণ যে এই কোর্স আপনার কাজে লাগবে।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>📥 পেমেন্ট করার পর কীভাবে কোর্স অ্যাক্সেস পাব?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">পেমেন্ট成功后 ১ মিনিটের মধ্যে আপনার ইমেইলে ও হোয়াটসঅ্যাপে গুগল ড্রাইভ লিংক চলে যাবে। সেখান থেকে সব কোর্স দেখতে বা ডাউনলোড করতে পারবেন। কোনো অপেক্ষা নেই!</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>🔄 কি মাসিক ফি দিতে হবে? নাকি একবারই দিলেই হবে?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">একবার মাত্র ৯৯ টাকা দিলেই আজীবন অ্যাক্সেস! কোনো মাসিক ফি নেই, কোনো লুকানো চার্জ নেই। ১০ লক্ষ টাকার কোর্স পাচ্ছেন মাত্র ৯৯ টাকায় একবারই। পরে কোনো টাকা দিতে হবে না, এমনকি নতুন আপডেটও ফ্রি।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>🎬 ইউটিউবে ফ্রি দেখিয়ে কী লাভ? ইউটিউব থেকে আলাদা কী?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">ইউটিউবে ভালো ভিডিও আছে, কিন্তু সেগুলো সাজানো না, কোনো গাইডলাইন নেই। আমাদের প্যাকেজে পাচ্ছেন: (১) ১০টি বিভাগে সাজানো কোর্স — শুরু থেকে শেষ পর্যন্ত গাইড, (২) সরাসরি সাহায্য — প্রশ্ন করলেই উত্তর, (৩) ক্লায়েন্ট খোঁজার গাইড — শেখার পরপরই কাজ শুরু, (৪) আজীবন আপডেট — নতুন কোর্স ফ্রি। ইউটিউবে এত কিছু একসঙ্গে পাওয়া সম্ভব না।</div>
        </div>

        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>👨‍💼 চাকরির পাশাপাশি কি করা সম্ভব? কতটা সময় দিতে হবে?</span>
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-a">অবশ্যই! আমাদের অনেক শিক্ষার্থী চাকরি বা পড়াশোনার পাশাপাশি কাজ করছেন। দিনে মাত্র ১-২ ঘণ্টা সময় দিলেই কোর্স দেখতে ও প্র্যাকটিস করতে পারবেন। চাকরি থাকা অবস্থায় অনলাইন আয় শুরুর জন্য এটি সেরা জায়গা।</div>
        </div>
        <a class="mid-cta" href="#checkoutSection" onclick="var s=document.getElementById('checkoutSection');if(s){s.scrollIntoView({behavior:'smooth',block:'start'});return false;}">❓ আপনার সব প্রশ্নের উত্তর পেয়েছেন? এখনই ৯৯ টাকায় জয়েন করুন ➔</a>
    </section>

    <!-- FAQ JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "৯৯ টাকা দিয়ে কি সত্যিই অনলাইনে আয় করা সম্ভব?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "হ্যাঁ, সম্ভব। ৯৯ টাকায় আপনি পাচ্ছেন ১০ লক্ষ টাকার কোর্স ও ৩০ দিনের নির্দেশিকা। আমাদের ৮৬৬+ শিক্ষার্থী প্রমাণ করে সঠিক গাইড পেলে যে কেউ আয় করতে পারেন।"
            }
          },
          {
            "@type": "Question",
            "name": "এটি কি কোনো স্ক্যাম বা ফেক প্রোগ্রাম?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "একেবারেই না। জোবায়ের গ্রুপ ৮+ বছর ধরে কাজ করছে। আমরা ২৪ ঘণ্টায় টাকা ফেরত দিই — কোনো প্রতারক কোম্পানি টাকা ফেরত দেয় না।"
            }
          },
          {
            "@type": "Question",
            "name": "আমার কোনো পূর্ব অভিজ্ঞতা নেই — তবু কি পারব?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "অবশ্যই। আমাদের কোর্স একদম শুরু থেকে শেখানোর জন্য তৈরি। আপনার শুধু দরকার একটি স্মার্টফোন বা ল্যাপটপ আর শেখার ইচ্ছা।"
            }
          },
          {
            "@type": "Question",
            "name": "পেমেন্ট করার পর কীভাবে কোর্স অ্যাক্সেস পাব?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "পেমেন্ট成功后 ১ মিনিটের মধ্যে ইমেইলে ও হোয়াটসঅ্যাপে গুগল ড্রাইভ লিংক চলে যাবে। কোনো অপেক্ষা নেই!"
            }
          },
          {
            "@type": "Question",
            "name": "কি মাসিক ফি দিতে হবে? নাকি একবারই দিলেই হবে?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "একবার মাত্র ৯৯ টাকা দিলেই আজীবন অ্যাক্সেস! কোনো মাসিক ফি বা লুকানো চার্জ নেই।"
            }
          }
      ]
    }
    </script>

    <section id="checkoutCtaSection" class="checkoutCta">
        <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:0 auto 12px;">
            <div class="countdown-box" style="font-size:15px;">⏰ অফার শেষ হতে <span id="checkoutTimer">০৩:৪২:১৫</span></div>
        </div>
        <h2>৩০ সেকেন্ডেই শুরু করুন আপনার আয়ের যাত্রা!</h2>
        <p>নিচে আপনার নাম-ফোন দিন, পেমেন্ট করুন। সাথে সাথেই সব কোর্স ও অ্যাক্সেস চলে আসবে!</p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin:14px auto;">
            <span style="background:rgba(29,78,216,.1);color:var(--accent-blue);padding:6px 14px;border-radius:999px;font-size:12px;font-weight:800;">⚡ ইতিমধ্যে ৮৬৬+ সক্রিয় শিক্ষার্থী যুক্ত</span>
        </div>
        <div style="font-size:20px;font-weight:900;margin:10px 0;">
            <span style="text-decoration:line-through;color:var(--muted);font-size:16px;">১০,০০,০০০+ টাকা</span>
            <span style="color:var(--price);font-size:32px;margin-left:8px;">৯৯ টাকা</span>
            <span style="background:var(--gold);color:#1E293B;padding:2px 10px;border-radius:8px;font-size:13px;margin-left:8px;">৯৯.৯৯% ছাড়</span>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:0 auto 18px;padding:14px 18px;border-radius:14px;background:rgba(255,255,255,.06);border:1px solid var(--border);max-width:650px;">
            <span style="padding:6px 14px;border-radius:999px;background:rgba(29,78,216,.1);font-size:12px;font-weight:800;color:var(--accent-blue);">✅ ২৩০+ প্রিমিয়াম কোর্স</span>
            <span style="padding:6px 14px;border-radius:999px;background:rgba(29,78,216,.1);font-size:12px;font-weight:800;color:var(--accent-blue);">✅ লাইফটাইম অ্যাক্সেস</span>
            <span style="padding:6px 14px;border-radius:999px;background:rgba(29,78,216,.1);font-size:12px;font-weight:800;color:var(--accent-blue);">✅ ২৪ ঘণ্টা নিঃশর্ত ফেরত</span>
            <span style="padding:6px 14px;border-radius:999px;background:rgba(29,78,216,.1);font-size:12px;font-weight:800;color:var(--accent-blue);">✅ SSL সুরক্ষিত পেমেন্ট</span>
            <span style="padding:6px 14px;border-radius:999px;background:rgba(29,78,216,.1);font-size:12px;font-weight:800;color:var(--accent-blue);">✅ সাথে সাথে এক্সেস</span>
        </div>

        <a class="checkoutBtn" id="checkoutBtn" href="#checkoutSection"><?php echo esc_html($cfg['cta_text']); ?></a>
    </section>

    <!-- ==================== CHECKOUT SECTION ==================== -->
    <section id="checkoutSection" style="scroll-margin-top:80px;">
      <div class="wrap">

        <div class="urgency">
          🔥 দাম বাড়ার আগে বাকি <strong>মাত্র <span id="timer" style="font-size:22px;">৩০:০০</span></strong>
          <div class="timer-progress"><span id="timerBar"></span></div>
          <div style="margin-top:8px;font-size:14px;">👥 আর মাত্র <strong id="quotaDisplayCheckout" style="font-size:18px;">৫</strong> জন পাচ্ছেন ৯৯ টাকায় — এরপর <strong>মূল্য ১,৪৯৯ টাকা</strong></div>
        </div>

        <?php
        if (current_user_can('manage_options') && function_exists('wc_get_product') && !wc_get_product($light_checkout_product_id)) {
          echo '<div class="admin-product-warning">Admin Notice: Product ID #' . esc_html($light_checkout_product_id) . ' not found. Update the product ID in this file.</div>';
        }

        if (shortcode_exists('woocommerce_checkout')) {
          echo do_shortcode('[woocommerce_checkout]');
        } else {
          echo '<div class="admin-product-warning">WooCommerce checkout shortcode পাওয়া যায়নি। WooCommerce plugin active আছে কিনা দেখুন।</div>';
        }
        ?>

        <input type="hidden" name="jg_affiliate_ref" id="jg_affiliate_ref" value="<?php echo esc_attr($ref); ?>">

      </div>
    </section>
    <!-- ================================================================ -->

</main>

<div id="liveNotifBar" class="live-notif-bar"></div>


<div id="sectionNav" class="section-nav section-nav-merged">
    <div class="tab-group">
        <button class="section-nav-btn" data-section="part1Section" onclick="window.scrollTo({top:0,behavior:'smooth'})"><span class="nav-icon">🏠</span><span class="nav-label">হোম</span></button>
        <button class="section-nav-btn" data-section="offerCards" onclick="showOfferSection()"><span class="nav-icon">📚</span><span class="nav-label">কোর্স</span></button>
        <button class="section-nav-btn" data-section="salarySection" onclick="showPart3Section()"><span class="nav-icon">💰</span><span class="nav-label">লাইভ আয়</span></button>
        <button class="section-nav-btn" data-section="reviewSection" onclick="showReviewSection()"><span class="nav-icon">⭐</span><span class="nav-label">মতামত</span></button>
    </div>
    <a class="nav-cta-btn" href="#checkoutSection" style="flex-direction:column;gap:2px;padding:8px 14px;line-height:1.2;">
        🔥 শুরু করুন
        <span style="font-size:9px;opacity:.9;">👥 <strong id="quotaDisplayNav">৫</strong> টি বাকি</span>
    </a>
</div>

<script>
var JG_AFFILIATE_REF = <?php echo json_encode($ref); ?>;
(function(){
    if (!JG_AFFILIATE_REF) return;
    var hiddenRef = document.getElementById('jg_affiliate_ref');
    if (hiddenRef) hiddenRef.value = JG_AFFILIATE_REF;
})();

/* smooth scroll to checkout on CTA click */
document.addEventListener('click', function(e){
    var target = e.target.closest('a[href="#checkoutSection"], .checkoutBtn, .nav-cta-btn');
    if (!target) return;
    e.preventDefault();
    var section = document.getElementById('checkoutSection');
    if (!section) return;
    section.scrollIntoView({behavior:'smooth', block:'start'});
});
</script>

<script>
// Live notification — shows demo onboarding notifications
var notifBar = document.getElementById("liveNotifBar");
var notifQueue = [];
var notifPlaying = false;
var bdDistricts = ["ঢাকা","চট্টগ্রাম","রাজশাহী","খুলনা","সিলেট","বরিশাল","রংপুর","ময়মনসিংহ","কুমিল্লা","নরসিংদী","গাজীপুর","নারায়ণগঞ্জ","টাঙ্গাইল","ফরিদপুর","বগুড়া","দিনাজপুর","পাবনা","কুষ্টিয়া","যশোর","কক্সবাজার"];

function showLiveNotif(name, isLatest){
    if(!notifBar) return;
    var district = bdDistricts[Math.floor(Math.random() * bdDistricts.length)];
    var msg = name + ', ' + district + ' থেকে সদ্য যুক্ত হলেন এবং ১০ লক্ষ টাকার কোর্স সম্পূর্ণ ফ্রিতে পেলেন!';
    var item = {text: msg};
    if(isLatest){
        notifQueue.unshift(item);
    } else {
        notifQueue.push(item);
    }
    if (!notifPlaying) playNextNotif();
}
function playNextNotif(){
    if (!notifQueue.length) { notifPlaying = false; return; }
    notifPlaying = true;
    var item = notifQueue.shift();
    notifBar.innerHTML = '<span class="notif-icon">🎉</span><span class="notif-text"><strong>' + item.text + '</strong></span>';
    notifBar.classList.add("show");
    setTimeout(function(){
        notifBar.classList.remove("show");
        setTimeout(playNextNotif, 800);
    }, 4000);
}

(function(){
    var a=document.getElementById("headerTimer"),b=document.getElementById("checkoutTimer");
    if(!a&&!b)return;
    var MAX=1800,MIN=300,CYCLE=MAX-MIN,key="jg_timer_epoch",epoch;
    try{ epoch=parseInt(localStorage.getItem(key),10); if(!epoch||isNaN(epoch)){ epoch=Date.now(); localStorage.setItem(key,String(epoch)); } }catch(e){ epoch=Date.now(); }
    function pad(v){return v<10?"0"+v:v}
    function fmt(v){var h=Math.floor(v/3600),m=Math.floor((v%3600)/60),s=v%60;return pad(h)+":"+pad(m)+":"+pad(s)}
    function rn(v){return String(v).replace(/\d/g,function(d){return "০১২৩৪৫৬৭৮৯"[parseInt(d,10)]})}
    function tick(){
        var remaining=MAX-(Math.floor((Date.now()-epoch)/1000)%CYCLE);
        var s=rn(fmt(remaining));
        if(a)a.textContent=s;
        if(b)b.textContent=s;
    }
    tick();
    var timerInt=setInterval(tick,1000);
    document.addEventListener("visibilitychange",function(){
        if(document.hidden){ clearInterval(timerInt); }
        else{ try{ epoch=parseInt(localStorage.getItem(key),10); if(!epoch) epoch=Date.now(); }catch(e){ epoch=Date.now(); } timerInt=setInterval(tick,1000); tick(); }
    });
})();

/* Scarcity Quota System — random quota 3-7, refreshes every 30 min */
(function(){
    var qKey = "jg_quota_value", qTimeKey = "jg_quota_time";
    var CYCLE_MS = 1800000; // 30 min
    function getQuota(){
        var now = Date.now();
        try{
            var storedTime = parseInt(localStorage.getItem(qTimeKey), 10);
            var storedVal = parseInt(localStorage.getItem(qKey), 10);
            if(storedTime && storedVal && (now - storedTime) < CYCLE_MS && storedVal >= 3 && storedVal <= 7){
                return storedVal;
            }
        }catch(e){}
        var val = Math.floor(Math.random() * 5) + 3; // 3-7
        try{
            localStorage.setItem(qKey, String(val));
            localStorage.setItem(qTimeKey, String(now));
        }catch(e){}
        return val;
    }
    function updateAllQuota(){
        var q = getQuota();
        var bn = String(q).replace(/\d/g, function(d){ return "০১২৩৪৫৬৭৮৯"[parseInt(d,10)]; });
        var ids = ["quotaDisplayNav","quotaDisplayCheckout"];
        ids.forEach(function(id){
            var el = document.getElementById(id);
            if(el) el.textContent = bn;
        });
    }
    updateAllQuota();
    setInterval(updateAllQuota, 60000); // check every minute
})();

(function(){
    var c=document.querySelectorAll(".review-card");
    if(!c.length)return;
    var h=["প্রথম উপহার পাওয়ার দিনটা এখনো মনে আছে।","আমি গ্রামের মেয়ে।","আমার সবচেয়ে ভালো লেগেছে","আমি একজন শিক্ষার্থী।","আমি ছয় মাস ধরে যুক্ত"];
    var f=0;
    for(var i=0;i<c.length&&f<5;i++){
        var t=c[i].querySelector(".review-text");
        if(!t)continue;
        var x=t.textContent||"";
        for(var j=0;j<h.length;j++){
            if(x.indexOf(h[j])!==-1){
                c[i].classList.add("super-review");
                f++;
                break;
            }
        }
    }
})();
</script>

<script>
const videos = [
    "nRmNR13u0-g"
];

let player;
let totalDuration = 0;
let progressInterval = null;
let started = false;
let completed = false;

const ytTag = document.createElement("script");
ytTag.src = "https://www.youtube.com/iframe_api";
document.body.appendChild(ytTag);

function onYouTubeIframeAPIReady() {
    player = new YT.Player("videoFrame", {
        videoId: videos[0],
        playerVars: {
            autoplay: 0,
                        controls: 1,
            iv_load_policy: 3,
            modestbranding: 1,
            rel: 0,
            playsinline: 1
        
        },
        events: {
            onReady: function () {
                updateVideoLine(0);
                try{ player.mute(); }catch(e){}
                setupSpeedControls();
                document.getElementById("videoCover").addEventListener("click", function(){
                    if(!started) startPlayer();
                    else if(player && player.playVideo) player.playVideo();
                });
            },
            onStateChange: function (e) {
                if (e.data === 1) {
                    refreshDuration();
                }
                if (e.data === 0) {
                    goToNextVideoOrFinish();
                }
            }
        }
    });
}

function updateVideoLine(percent){
    const fill = document.getElementById("videoLineFill");
    if (fill) fill.style.width = Math.max(0,Math.min(100,percent)) + "%";
}

function startPlayer(){
    if (!player || !player.playVideo) { return; }

    if (started) {
        player.playVideo();
        return;
    }

    started = true;
    hideVideoCover();

    document.getElementById("videoWrapper").classList.add("is-playing");

    try{ player.unMute(); }catch(e1){}
    try{ player.playVideo(); }catch(e2){}

    setupProgress();
}

function hideVideoCover(){
    const cover = document.getElementById("videoCover");
    if (cover) cover.classList.add("is-hidden");
}

function setupSpeedControls(){
    var btns = document.querySelectorAll(".speed-btn");
    btns.forEach(function(btn){
        btn.addEventListener("click", function(e){
            e.stopPropagation();
            var speed = parseFloat(this.getAttribute("data-speed"));
            if(player && player.setPlaybackRate){
                player.setPlaybackRate(speed);
            }
            btns.forEach(function(b){ b.classList.remove("active"); });
            this.classList.add("active");
        });
    });
}

function refreshDuration(){
    setTimeout(function(){
        try{
            totalDuration = player.getDuration() || 0;
        }catch(e){}
    },700);
}

function setupProgress(){
    clearInterval(progressInterval);
    startRealProgress();
}

function startRealProgress(){
    progressInterval = setInterval(function(){
        if (completed || !player || !player.getCurrentTime) return;

        try{
            const currentTime = player.getCurrentTime() || 0;
            if (!totalDuration) totalDuration = player.getDuration() || 0;
            const percent = totalDuration > 0 ? Math.min(100, Math.floor((currentTime / totalDuration) * 100)) : 0;

            updateVideoLine(percent);
        }catch(e){}
    },1000);
}

function goToNextVideoOrFinish(){
    if (completed) return;
    finishMainVideo();
}

function finishMainVideo(){
    if (completed) return;

    completed = true;
    clearInterval(progressInterval);

    updateVideoLine(100);
}

function showPart2Section(){
    document.getElementById("offerCards").scrollIntoView({behavior:"smooth", block:"start"});
}

function showPart3Section(){
    var btn = document.querySelector('.section-toggle-btn[data-target="salary"]');
    if (btn && !btn.classList.contains("open")) toggleSection(btn);
    document.getElementById("salarySection").scrollIntoView({behavior:"smooth", block:"start"});
}

function showPart4Section(){
    var btn = document.querySelector('.section-toggle-btn[data-target="gallery"]');
    if (btn && !btn.classList.contains("open")) toggleSection(btn);
    document.getElementById("gallerySection").scrollIntoView({behavior:"smooth", block:"start"});
}

function showReviewSection(){
    document.getElementById("reviewSection").scrollIntoView({behavior:"smooth", block:"start"});
}

/* ===== SECTION TOGGLE ===== */
function toggleSection(btn){
    btn.classList.toggle("open");
    var content = btn.nextElementSibling;
    if (content) content.classList.toggle("open");
}

/* ===== SCROLL PROGRESS BAR ===== */
(function(){
    var bar = document.createElement("div");
    bar.className = "scroll-progress";
    document.body.appendChild(bar);
    var updateProgress = function(){
        var scrollTop = window.scrollY || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = (scrollTop / Math.max(docHeight,1) * 100) + "%";
    };
    window.addEventListener("scroll", updateProgress, {passive:true});
    updateProgress();
})();

/* ===== FADE-IN ON SCROLL ===== */
(function(){
    var els = document.querySelectorAll(".fade-in");
    if (els.length && "IntersectionObserver" in window){
        var obs = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if (e.isIntersecting){ e.target.classList.add("visible"); obs.unobserve(e.target); }
            });
        }, {threshold:0.15, rootMargin:"0px 0px -40px 0px"});
        els.forEach(function(el){ obs.observe(el); });
    } else {
        els.forEach(function(el){ el.classList.add("visible"); });
    }
})();

function showAllReviews(){
    var hidden = document.querySelectorAll('.review-card.hidden-review');
    hidden.forEach(function(el) { el.style.display = 'block'; });
    var btn = document.getElementById('showMoreReviewsBtn');
    if (btn) btn.style.display = 'none';
}

function showTrainerSection(){
    var el = document.getElementById("trainerSection");
    if (el) el.scrollIntoView({behavior:"smooth", block:"start"});
}

function showOfferSection(){
    var el = document.getElementById("offerCards");
    if (el) el.scrollIntoView({behavior:"smooth", block:"start"});
}

function showGuaranteeSection(){
    var el = document.getElementById("guaranteeSection");
    if (el) el.scrollIntoView({behavior:"smooth", block:"start"});
}

/* Tab bar scroll-spy: highlight active section button */
(function(){
    var navBtns = document.querySelectorAll(".section-nav-btn");
    if(!navBtns.length) return;
    var sections = [];
    navBtns.forEach(function(btn){
        var id = btn.getAttribute("data-section");
        if(id) sections.push({btn: btn, el: document.getElementById(id)});
    });
    function updateActive(){
        var scrollY = window.scrollY;
        var viewportH = window.innerHeight;
        var bestIdx = -1, bestScore = -Infinity;
        sections.forEach(function(s, i){
            if(!s.el) return;
            var rect = s.el.getBoundingClientRect();
            var visible = Math.min(rect.bottom, viewportH) - Math.max(rect.top, 0);
            if(visible > bestScore){
                bestScore = visible;
                bestIdx = i;
            }
        });
        sections.forEach(function(s, i){
            s.btn.classList.toggle("active", i === bestIdx);
        });
    }
    updateActive();
    window.addEventListener("scroll", updateActive, {passive:true});
    window.addEventListener("resize", updateActive, {passive:true});
})();

function initPart2Tabs(){
    var bar = document.getElementById("part2TabBar");
    if (!bar) return;
    var btns = bar.querySelectorAll(".part2-tab-btn");
    btns.forEach(function(btn){
        btn.addEventListener("click", function(){
            btns.forEach(function(b){ b.classList.remove("active"); });
            btn.classList.add("active");
            var contents = document.querySelectorAll(".part2-tab-content");
            contents.forEach(function(c){ c.classList.remove("active"); });
            var target = document.querySelector('[data-tab-content="' + btn.getAttribute("data-tab") + '"]');
            if (target) target.classList.add("active");
        });
    });
}
document.addEventListener("DOMContentLoaded", initPart2Tabs);

function switchTab(index){
    var btn = document.querySelector('.part2-tab-btn[data-tab="' + index + '"]');
    if (btn) { btn.click(); }
    var sec = document.getElementById('offerCards');
    if (sec) sec.scrollIntoView({behavior:'smooth', block:'start'});
}

function toBn(v){var b="০১২৩৪৫৬৭৮৯";return String(v).replace(/\d/g,function(d){return b[parseInt(d,10)]});}
</script>

<script>
(function(){
    const names = [
        "Ayan Rahman","সুমন দাস","Maria Gomes","Ratan Marma","উদয় বড়ুয়া",
        "Nusrat Jahan","অনিক পাল","Rakib Hasan","Bimal Tripura","তানিয়া সুলতানা",
        "Sabbir Hossain","Mithila Roy","Farhan Ahmed","Riya Chakma","Tanvir Islam",
        "Lima Das","Omar Faruk","Puja Rani","Hasan Mahmud","Nabila Noor",
        "Ayesha Rahman","সুমনা দাস","Priya Saha","Farzana Akter","বিজয় বড়ুয়া",
        "Tasnim Karim","রিনা পাল","মাহিরা নূর","Daniel Gomes","Tanjila Islam",
        "তপন দাস","সুমাইয়া আহমেদ","Milan Roy","Lamia Sultana","জিতু ত্রিপুরা",
        "Sohana Noor","বৃষ্টি রায়","রাবেয়া খাতুন","Peter Costa","Tamanna Yasmin",
        "Mariam Akter","লতা বিশ্বাস","আফিফা করিম","Robin Rozario","Nabila Rahman",
        "রাকেশ শীল","Sabina Islam","ডলি সরকার","তাসমিয়া রহমান","Ananda Das",
        "Farhin Sultana","শিউলি রানী","Shabnam Yasmin","Rony Marma","সামিয়া নূর",
        "John Tripura","Rukhsana Begum","সাগর রায়","Hira Ahmed","নির্মল বড়ুয়া",
        "সাদিয়া করিম","Rita Paul","Mehnaz Akter","ডেভিড কস্তা","Nuzhat Jahan",
        "কাব্য পাল","Maliha Noor","উজ্জ্বল দাস","Ishrat Sultana","Pinky Rani",
        "মাহজাবীন নূর","Simon Gomes","Tasnia Islam","বরুণ দাস","Labiba Noor",
        "রিমা সরকার","Afreen Karim","জুয়েল বড়ুয়া","Saima Rahman","রতন মারমা",
        "Halima Khatun","Tanmoy Saha","Fariha Sultana","অনিল বড়ুয়া","Amina Begum",
        "তুলি রানী","Sharmeen Akter","Victor Rozario","Zannat Ara","লিমন ত্রিপুরা",
        "Humaira Noor","রেখা বালা","Farzana Rahman","বর্ষা রায়","Nusrat Sultana",
        "Rafia Islam","Sujan Barua","Afsana Karim","Tamanna Islam","Samira Ahmed"
    ];

    const AVG_DELAY = 4;
    const MAX_VISIBLE = 100;
    const tbody = document.getElementById("jgSalaryBody");
    let intervalId = null;

    function seededRandom(seed){
        const x = Math.sin(seed) * 10000;
        return x - Math.floor(x);
    }

    function getStatus(index){
        const successPositions = [7,12,22,29,38,46,55,68,79,89];
        return successPositions.includes(index % 100) ? "success" : "added";
    }

    function generateRow(index){
        const seed = index * 999;
        const name = names[Math.floor(seededRandom(seed) * names.length)];
        let amount, status, success = false;

        if (getStatus(index) === "success") {
            amount = Math.floor(seededRandom(seed + 2) * 1501) + 1000;
            status = "নগদ অ্যাকাউন্টে সফলভাবে পারফরম্যান্স উপহার ট্রান্সফার হয়েছে";
            success = true;
        } else {
            amount = Math.floor(seededRandom(seed + 3) * 136) + 15;
            status = "বোনাস দেওয়া হয়েছে";
        }

        return { name, amount, status, success };
    }

    function appendCell(row, value){
        const td = document.createElement("td");
        td.textContent = value;
        row.appendChild(td);
    }

    var notifiedSet = new Set();
    var latestSuccessData = null;
    var initialBatchCollected = false;

    function collectAllSuccessRows(start, end){
        var allSuccess = [];
        for (let i = end - 1; i >= start; i--) {
            const data = generateRow(i);
            if (data.success) {
                allSuccess.push({ index: i, name: data.name, amount: toBn(data.amount) });
            }
        }
        return allSuccess;
    }

    function render(){
        if (!navigator.onLine || !tbody) return;

        const now = Date.now();
        const totalUpdates = Math.floor(now / 1000 / AVG_DELAY);
        const start = Math.max(0, totalUpdates - MAX_VISIBLE);
        const end = totalUpdates;

        tbody.innerHTML = "";

        /* On first render, collect ALL success rows and queue them all */
        if (!initialBatchCollected) {
            var allSuccess = collectAllSuccessRows(start, end);
            allSuccess.forEach(function(item){
                notifiedSet.add(item.index);
                showLiveNotif(item.name, false);
            });
            initialBatchCollected = true;
        }

        for (let i = end - 1; i >= start; i--) {
            const data = generateRow(i);
            const tr = document.createElement("tr");
            if (data.success) tr.classList.add("success-row");

            appendCell(tr, data.name);
            appendCell(tr, toBn(data.amount) + " টাকা");
            appendCell(tr, data.status);
            tbody.appendChild(tr);

            if (data.success) {
                latestSuccessData = { name: data.name, amount: toBn(data.amount) };
                if (!notifiedSet.has(i)) {
                    notifiedSet.add(i);
                    showLiveNotif(data.name, false);
                }
            }
        }
    }

    /* Periodically re-show latest success notification (priority - latest comes first) */
    setInterval(function(){
        if (latestSuccessData) {
            showLiveNotif(latestSuccessData.name, true);
        }
    }, 30000);

    function startLive(){
        if (intervalId) clearInterval(intervalId);
        render();
        intervalId = setInterval(render, 3000);
    }

    function stopLive(){
        if (intervalId) clearInterval(intervalId);
        intervalId = null;
    }

    window.addEventListener("online", startLive);
    window.addEventListener("offline", stopLive);

    if (navigator.onLine) startLive();
})();

/* FAQ toggle */
function toggleFaq(el){
    el.classList.toggle("open");
    var a = el.nextElementSibling;
    if(a) a.classList.toggle("open");
}

/* Reviews slider */
(function(){
    var track = document.getElementById("reviewsTrack");
    var dots = document.querySelectorAll(".review-dot");
    if(!track || !dots.length) return;
    var current = 0, total = dots.length, interval;
    function goTo(n){
        if(n < 0) n = total - 1;
        if(n >= total) n = 0;
        current = n;
        track.style.transform = "translateX(-" + (current * 100) + "%)";
        dots.forEach(function(d,i){
            d.classList.toggle("active", i === current);
        });
    }
    dots.forEach(function(d){d.addEventListener("click",function(){goTo(parseInt(this.dataset.slide));clearInterval(interval);interval=setInterval(auto,4000);});});
    function auto(){goTo(current+1);}
    interval = setInterval(auto, 4000);
})();
</script>

<script>
/* Add prices to all course items dynamically — fixed deterministic prices */
(function(){
    var fixedPrices = {
        3: [8500,7500,12500,15500,18500,8500,7500,15500,22500,20500,18500,16500,12500,14500,20500,18500,15500,14500,16500,18500,15500,12500,12500,15500,16500,20500,8500,10500,12500,14500,12500],
        4: [8000,10000,15000,18000,8000,12000,10000,12000,10000,10000,8000,8000,7000,7000,8000,10000,7000,7000,6000,7000,5000,6000,5000,10000,7000,7000,8000,7000,4000,4000,7000,5000,5000,7000,5000,5000,5000,6000,7000,5000,8000,10000,7000,4000,4000,7000,4000,5000,5000],
        5: [10000,12000,25000,30000,18000,20000,15000,12000,10000,15000,20000,18000,22000,25000,15000,8000,15000,10000,15000,20000,18000,20000,12000],
        6: [6000,5000,8000,4000,4000,6000,5000,6000,4000,5000,4000,3000,3000,4000,6000,5000,3000,4000,5000,5000,4000,4000,3000,3000,5000,6000,4000,4000,3000,3000,4000],
        7: [10000,12000,10000,15000,12000,13000,12000,10000,12000,8000,8000,7000,8000,8000,10000,7000,6000,7000,10000,5000,10000,7000,10000,5000,5000,6000,8000,7000],
        8: [5000,6000,4000,8000,6000,5000,6000,6000,8000,8000,4000,10000,10000,8000,8000,7000,8000,5000,4000,7000,7000,7000,7000,8000,10000,8000,7000,10000,7000,8000,10000,8000,10000,7000,7000,7000,5000,7000,5000,4000,4000,8000,7000,4000,4000,4000,4000],
        9: [5000,6000,4000]
    };
    var tabContents = document.querySelectorAll('.part2-tab-content');
    var tabPriceTotals = {};

    tabContents.forEach(function(tab){
        var tabIdx = parseInt(tab.getAttribute('data-tab-content'));
        var prices = fixedPrices[tabIdx];
        var tabTotal = 0;

        if(prices){
            var items = tab.querySelectorAll('.mentor-item');
            items.forEach(function(item, idx){
                if(item.querySelector('.mentor-price')) return;
                var nameEl = item.querySelector('.mentor-name');
                if(!nameEl) return;
                var price = prices[idx] || prices[prices.length-1];
                tabTotal += price;
                var span = document.createElement('span');
                span.className = 'mentor-price';
                span.innerHTML = '<s>\u09F3' + price.toLocaleString('bn-BD') + '</s><span class="free-badge-sm">\u09AB\u09CD\u09B0\u09BF</span>';
                nameEl.appendChild(span);
            });
        }

        var chipPrices = fixedPrices[9];
        if(tabIdx === 9 && chipPrices){
            var chips = tab.querySelectorAll('.chip-row .chip');
            chips.forEach(function(chip, idx){
                if(chip.querySelector('.mentor-price')) return;
                var price = chipPrices[idx] || chipPrices[chipPrices.length-1];
                tabTotal += price;
                var span = document.createElement('span');
                span.className = 'mentor-price';
                span.style.marginLeft = '8px';
                span.innerHTML = '<s>\u09F3' + price.toLocaleString('bn-BD') + '</s><span class="free-badge-sm">\u09AB\u09CD\u09B0\u09BF</span>';
                chip.style.display = 'inline-flex';
                chip.style.alignItems = 'center';
                chip.appendChild(span);
            });
        }

        /* Collect prices from existing mentor-price elements (tabs 1,2 had hardcoded prices with Bengali digits) */
        if(tabIdx === 1 || tabIdx === 2){
            var existingItems = tab.querySelectorAll('.mentor-item .mentor-price');
            existingItems.forEach(function(ep){
                var sEl = ep.querySelector('s');
                if(sEl){
                    var txt = sEl.textContent.replace(/[\u09F3,]/g, '');
                    txt = txt.replace(/[০-৯]/g, function(c){ return '০১২৩৪৫৬৭৮৯'.indexOf(c); });
                    var val = parseInt(txt, 10);
                    if(!isNaN(val)) tabTotal += val;
                }
            });
        }

        tabPriceTotals[tabIdx] = tabTotal;
    });

    /* Now set Tab 0 (knowledge) prices based on linked tab totals */
    function setKnowledgeTabPrices(){
        var tab0 = document.querySelector('.part2-tab-content[data-tab-content="0"]');
        if(!tab0) return;
        var items = tab0.querySelectorAll('.overview-item');
        var tabMapping = {
            0: 3, 1: 5, 2: 3, 3: 4, 4: 7, 5: 1, 6: 6, 7: 2, 8: 8, 9: 9
        };
        items.forEach(function(item, idx){
            var linkedTab = tabMapping[idx];
            if(linkedTab === undefined) return;
            var total = tabPriceTotals[linkedTab] || 0;
            var priceEl = item.querySelector('.mentor-price');
            if(!priceEl) return;
            var sEl = priceEl.querySelector('s');
            if(!sEl) return;
            sEl.textContent = '\u09F3' + total.toLocaleString('bn-BD');
        });
    }

    setKnowledgeTabPrices();
})();
</script>

<script>
(function(){
  var MAX=1800,MIN=300,CYCLE=MAX-MIN,key="jg_timer_epoch",epoch,t=MAX;
  try{ epoch=parseInt(localStorage.getItem(key),10); if(!epoch||isNaN(epoch)){ epoch=Date.now(); localStorage.setItem(key,String(epoch)); } }catch(e){ epoch=Date.now(); }
  t = MAX - (Math.floor((Date.now()-epoch)/1000) % CYCLE);

  const timer    = document.getElementById("timer");
  const timerBar = document.getElementById("timerBar");

  const bnDigits = {"0":"০","1":"১","2":"২","3":"৩","4":"৪","5":"৫","6":"৬","7":"৭","8":"৮","9":"৯"};

  const textMap = {
    "Billing details": "আপনার তথ্য",
    "Your order": "আপনার অর্ডার",
    "Product": "পণ্য",
    "Subtotal": "সাবটোটাল",
    "Total": "মোট",
    "Payment": "পেমেন্ট",
    "Place order": "নিরাপদে অর্ডার সম্পন্ন করুন",
    "Complete Secure Order Now": "নিরাপদে অর্ডার সম্পন্ন করুন",
    "First name": "আপনার নামের প্রথম অংশ",
    "Last name": "নামের শেষ অংশ",
    "Country / Region": "দেশ / অঞ্চল",
    "Email address": "ইমেইল ঠিকানা",
    "Phone": "মোবাইল নম্বর",
    "optional": "ঐচ্ছিক",
    "Required": "আবশ্যক",
    "Returning customer?": "",
    "Click here to login": "",
    "Have a coupon?": "",
    "Click here to enter your code": "",
    "You must be logged in to checkout.": "",
    "Invalid email address.": "সঠিক ইমেইল ঠিকানা লিখুন।",
    "Please enter a valid phone number.": "সঠিক মোবাইল নম্বর লিখুন।",
    "%s is a required field.": "%s তথ্যটি অবশ্যই পূরণ করুন।",
    "Bangladesh": "বাংলাদেশ",
    "Pay Online (Credit/Debit Card/MobileBanking/NetBanking/bKash)": "অনলাইনে পেমেন্ট করুন",
    "Pay securely by Credit/Debit card, Internet banking or Mobile banking through SSLCommerz.": "বিকাশ, নগদ বা রকেট — ১ ক্লিকে পেমেন্ট করুন!",
    "Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.": "আপনার অর্ডার সম্পন্ন করা, পেমেন্ট যাচাই করা এবং প্রয়োজনীয় সহায়তা দেওয়ার জন্য আপনার তথ্য ব্যবহার করা হবে।",
    "privacy policy": "গোপনীয়তা নীতি"
  };

  const fieldLabels = {
    billing_first_name: "আপনার নাম",
    billing_last_name:  "নামের শেষ অংশ",
    billing_phone:      "মোবাইল নম্বর",
    billing_email:      "ইমেইল ঠিকানা",
    billing_country:    "দেশ / অঞ্চল"
  };

  const fieldPlaceholders = {
    billing_first_name: "আপনার নাম লিখুন",
    billing_last_name:  "নামের শেষ অংশ লিখুন",
    billing_phone:      "যেমন: 01XXXXXXXXX",
    billing_email:      "যেমন: yourmail@gmail.com"
  };

  function translateString(value){
    let out = String(value||"");
    Object.keys(textMap).forEach(k=>{ if(out.indexOf(k)!==-1) out=out.split(k).join(textMap[k]); });
    return toBn(out);
  }

  function formatTime(v){
    const m=Math.floor(v/60), s=v%60;
    return toBn((m<10?"0":"")+m+":"+(s<10?"0":"")+s);
  }

  function setFieldLabels(){
    Object.keys(fieldLabels).forEach(function(id){
      const field = document.getElementById(id);
      const label = document.querySelector('label[for="'+id+'"]');
      if(!label) return;
      const row      = label.closest(".form-row");
      const required = row && row.classList.contains("validate-required");
      label.innerHTML = fieldLabels[id] +
        (required?' <abbr class="required" title="আবশ্যক">*</abbr>':'');
      if(field && fieldPlaceholders[id]) field.setAttribute("placeholder", fieldPlaceholders[id]);
    });

    const bdOpt = document.querySelector('#billing_country option[value="BD"]');
    if(bdOpt) bdOpt.textContent="বাংলাদেশ";
    const ctr = document.querySelector("#select2-billing_country-container");
    if(ctr && ctr.textContent.trim()==="Bangladesh"){
      ctr.textContent="বাংলাদেশ";
      ctr.setAttribute("title","বাংলাদেশ");
    }
  }

  function translateTextNodes(root){
    if(!root) return;
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: function(node){
        if(node.nodeValue===null) return NodeFilter.FILTER_REJECT;
        const p=node.parentElement;
        if(!p||["SCRIPT","STYLE","TEXTAREA","INPUT"].indexOf(p.tagName)!==-1) return NodeFilter.FILTER_REJECT;
        return NodeFilter.FILTER_ACCEPT;
      }
    });
    const nodes=[];
    while(walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach(function(node){
      const old=node.nodeValue, nw=translateString(old);
      if(nw!==old) node.nodeValue=nw;
    });
  }

  function setHeadings(){
    const bt=document.querySelector("#checkoutSection .woocommerce-billing-fields h3");
    if(bt) bt.textContent="আপনার তথ্য";
    const ot=document.getElementById("order_review_heading");
    if(ot) ot.textContent="আপনার অর্ডার";
    document.querySelectorAll("#checkoutSection .shop_table th.product-name").forEach(el=>el.textContent="পণ্য");
    document.querySelectorAll("#checkoutSection .shop_table th.product-total").forEach(el=>el.textContent="সাবটোটাল");
    document.querySelectorAll("#checkoutSection .cart-subtotal th").forEach(el=>el.textContent="সাবটোটাল");
    document.querySelectorAll("#checkoutSection .order-total th").forEach(el=>el.textContent="মোট");
  }

  function setPaymentText(){
    document.querySelectorAll("#checkoutSection .wc_payment_method label").forEach(function(label){
      var t=label.textContent||"";
      if(/ssl|bkash|pay online|card|banking/i.test(t))
        label.textContent="অনলাইন পেমেন্ট";
      else if(/cod|cash/i.test(t))
        label.textContent="নগদ";
    });
    document.querySelectorAll("#checkoutSection .payment_box, #checkoutSection .payment_box p").forEach(function(box){
      var label=box.closest(".wc_payment_method");
      if(label && (/cod|cash/i.test(label.querySelector("label")?.textContent||"")))
        box.textContent="হাতে পেমেন্ট";
      else
        box.textContent="বিকাশ/নগদ/রকেট";
    });
    var privacy=document.querySelector("#checkoutSection .woocommerce-privacy-policy-text");
    if(privacy) privacy.innerHTML='<p>আপনার তথ্য নিরাপদে সংরক্ষিত থাকবে।</p>';
  }

  function removeLoginNotice(){
    document.querySelectorAll("#checkoutSection .woocommerce-error li,#checkoutSection .woocommerce-info,#checkoutSection .woocommerce-message").forEach(function(el){
      if(el.textContent&&el.textContent.indexOf("You must be logged in to checkout")!==-1) el.remove();
    });
  }

  window.lcc_nonce = <?php echo json_encode(wp_create_nonce('lcc_duplicate_check')); ?>;
  function attachDuplicateCheck(){
    const phoneField = document.getElementById("billing_phone");
    const emailField = document.getElementById("billing_email");

    function showFieldError(field, msg){
      if(!field) return;
      const row = field.closest(".form-row");
      if(!row) return;
      row.classList.add("woocommerce-invalid","woocommerce-invalid-required-field");
      let notice = row.querySelector(".lcc-duplicate-notice");
      if(!notice){
        notice = document.createElement("span");
        notice.className = "lcc-duplicate-notice";
        notice.style.cssText = "display:block;color:#dc2626;font-size:13px;font-weight:700;margin-top:5px;";
        row.appendChild(notice);
      }
      notice.textContent = msg;
    }

    function clearFieldError(field){
      if(!field) return;
      const row = field.closest(".form-row");
      if(!row) return;
      row.classList.remove("woocommerce-invalid","woocommerce-invalid-required-field");
      const notice = row.querySelector(".lcc-duplicate-notice");
      if(notice) notice.remove();
    }

    function checkDuplicate(type, value, field){
      if(!value || value.length < 6) return;
      fetch(window.wc_checkout_params ? window.wc_checkout_params.ajax_url : "/wp-admin/admin-ajax.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"action=lcc_check_duplicate&type="+encodeURIComponent(type)+"&value="+encodeURIComponent(value)+"&nonce="+encodeURIComponent(window.lcc_nonce||"")
      })
      .then(r=>r.json())
      .then(function(data){
        if(data && data.exists){
          const msg = type==="phone"
            ? "❌ এই মোবাইল নম্বর দিয়ে ইতোমধ্যে অ্যাক্সেস নেওয়া হয়েছে।"
            : "❌ এই ইমেইল দিয়ে ইতোমধ্যে অ্যাক্সেস নেওয়া হয়েছে।";
          showFieldError(field, msg);
        } else {
          clearFieldError(field);
        }
      })
      .catch(function(){});
    }

    if(phoneField){
      phoneField.addEventListener("blur", function(){
        checkDuplicate("phone", this.value.trim(), this);
      });
    }
    if(emailField){
      emailField.addEventListener("blur", function(){
        checkDuplicate("email", this.value.trim(), this);
      });
    }
  }

  function upgradeButton(timeText){
    const btn=document.getElementById("place_order");
    if(!btn) return;
    const isMobile=window.matchMedia("(max-width:768px)").matches;
    btn.textContent=isMobile?"অর্ডার সম্পন্ন করুন • "+timeText:"নিরাপদে অর্ডার সম্পন্ন করুন";
    btn.setAttribute("aria-label","নিরাপদে অর্ডার সম্পন্ন করুন");
  }

  let queued=false;
  function scheduleLocalize(){
    if(queued || window.lcc_submitting) return;
    if(document.querySelector(".lcc-checkout-loading")) return;
    queued=true;
    window.requestAnimationFrame(function(){
      queued=false;
      localizeCheckout();
      upgradeButton(formatTime(t));
    });
  }

  function localizeCheckout(){
    setFieldLabels();
    setHeadings();
    setPaymentText();
    removeLoginNotice();
    translateTextNodes(document.getElementById("customer_details"));
    translateTextNodes(document.getElementById("order_review"));
    translateTextNodes(document.querySelector("#checkoutSection .woocommerce-error"));
    translateTextNodes(document.querySelector("#checkoutSection .woocommerce-message"));
    translateTextNodes(document.querySelector("#checkoutSection .woocommerce-info"));
  }

  function renderTimer(){
    t = MAX - (Math.floor((Date.now()-epoch)/1000) % CYCLE);
    const tt=formatTime(t);
    if(timer){ timer.textContent=tt; if(t<=60) timer.style.color="#DC2626"; }
    if(timerBar) timerBar.style.width=Math.max(0,(t-MIN)/CYCLE*100)+"%";
    upgradeButton(tt);
  }

  function injectSecureBadge(){
    var btn=document.getElementById("place_order");
    if(!btn)return;
    var existing=document.querySelector(".secure-injected");
    if(existing)return;
    var badge=document.createElement("div");
    badge.className="secure-checkout-badge secure-injected";
    badge.innerHTML='<span class="lock-icon">🔒</span> SSL সুরক্ষিত পেমেন্ট';
    btn.parentNode.insertBefore(badge, btn.nextSibling);
  }

  function showCheckoutLoading(){
    var wrap = document.getElementById("checkoutSection");
    if(!wrap || wrap.querySelector(".lcc-checkout-loading")) return;
    var overlay = document.createElement("div");
    overlay.className = "lcc-checkout-loading";
    overlay.style.cssText = "position:absolute;inset:0;z-index:999;";
    overlay.innerHTML = '<div class="lcc-spinner" style="display:flex;flex-direction:column;align-items:center;gap:12px;position:sticky;top:50%;"><div class="lcc-spinner-ring"></div><div class="lcc-spinner-text">⏳ আপনার অর্ডার প্রসেস হচ্ছে...</div></div>';
    wrap.style.position = "relative";
    wrap.appendChild(overlay);
  }

  function hideCheckoutLoading(){
    var el = document.querySelector(".lcc-checkout-loading");
    if(el) el.remove();
  }

  document.addEventListener("DOMContentLoaded", function(){
    if(!window.location.hash && window.scrollY < 10) window.scrollTo(0,0);
    localizeCheckout();
    renderTimer();
    setInterval(renderTimer,1000);
    attachDuplicateCheck();

    /* BD phone format validation before submit */
    var chkForm = document.querySelector('form.checkout');
    if(chkForm && !chkForm.hasAttribute('data-phone-validated')){
      chkForm.setAttribute('data-phone-validated','1');
      chkForm.addEventListener('submit', function(e){
        window.lcc_submitting = true;
        const phoneVal = document.getElementById("billing_phone");
        if(phoneVal){
          const raw = phoneVal.value.replace(/\s/g, "");
          if(!/^01[3-9]\d{8}$/.test(raw)){
            e.preventDefault();
            phoneVal.focus();
            phoneVal.select();
            const row = phoneVal.closest(".form-row");
            if(row) row.classList.add("woocommerce-invalid","woocommerce-invalid-required-field");
            let notice = row ? row.querySelector(".lcc-duplicate-notice") : null;
            if(!notice && row){
              notice = document.createElement("span");
              notice.className = "lcc-duplicate-notice";
              notice.style.cssText = "display:block;color:#dc2626;font-size:13px;font-weight:700;margin-top:5px;";
              row.appendChild(notice);
            }
            if(notice) notice.textContent = "❌ সঠিক ১১ অঙ্কের মোবাইল নম্বর দিন (যেমন: 017XXXXXXXX)";
          } else {
            showCheckoutLoading();
          }
        }
      });
    }

    const or=document.getElementById("order_review");
    if(or && "MutationObserver" in window){
      new MutationObserver(scheduleLocalize).observe(or,{childList:true,subtree:true});
    }

    /* affiliate ref hidden field → checkout form (also on AJAX refresh) */
    function jgAffixRefField() {
      var refField = document.getElementById('jg_affiliate_ref');
      if(refField && refField.form === null) {
        var chkForm = document.querySelector('form.checkout');
        if(chkForm) chkForm.appendChild(refField);
        if (typeof JG_AFFILIATE_REF !== 'undefined') refField.value = JG_AFFILIATE_REF;
      }
    }
    jgAffixRefField();
    /* inject secure badge near place_order */
    setTimeout(injectSecureBadge,500);

    /* live student count — pseudo-random increment */
    var lsc = document.getElementById("liveStudentCount");
    if(lsc){
      var base = parseInt(lsc.textContent.replace(/[^0-9]/g,""),10) || 866;
      function updateLSC(){
        base += Math.floor(Math.random() * 3) + 1;
        lsc.textContent = toBn(base);
      }
      setInterval(updateLSC, 15000);
    }
  });

  window.addEventListener("resize",()=>upgradeButton(formatTime(t)));

  if(window.jQuery){
    window.jQuery(document.body).on("updated_checkout",function(){
      if(window.lcc_submitting) return;
      jgAffixRefField();
      setFieldLabels();
      setHeadings();
    });
  }
})();
</script>

<!-- Hotjar Tracking Code -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:YOUR_HOTJAR_ID,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<!-- Conversion tracking & analytics -->
<script>
(function(){
    var converted = sessionStorage.getItem('lcc_converted');
    if (converted) {
        if(typeof gtag === 'function') gtag('event', 'purchase', {'send_to': 'AW-CONVERSION_ID/CONVERSION_LABEL', 'value': 99.0, 'currency': 'BDT'});
        if(typeof fbq === 'function') fbq('track', 'Purchase', {value: 99.0, currency: 'BDT'});
        if(typeof rdt === 'function') rdt('track', 'Purchase', {value: 99.0, currency: 'BDT'});
    }
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).on('checkout_place_order_success', function() {
            sessionStorage.setItem('lcc_converted', '1');
            hideCheckoutLoading();
        });
        jQuery(document.body).on('checkout_place_order_error updated_checkout', function() {
            hideCheckoutLoading();
        });
    }
})();
document.addEventListener('DOMContentLoaded', function(){
    /* Scroll depth tracking */
    var scrollDepths = [25,50,75,100];
    var sentDepths = {};
    window.addEventListener('scroll', function(){
        var scrollPct = Math.round((window.scrollY + window.innerHeight) / document.documentElement.scrollHeight * 100);
        scrollDepths.forEach(function(d){
            if(scrollPct >= d && !sentDepths[d]){
                sentDepths[d] = true;
                if(typeof gtag === 'function') gtag('event', 'scroll_depth', {event_category: 'Scroll', value: d, label: d + '%'});
                if(typeof fbq === 'function') fbq('track', 'ScrollDepth', {depth: d});
            }
        });
    });
    /* Form abandonment tracking */
    var formFields = document.querySelectorAll('#checkoutSection input, #checkoutSection select');
    var startedForm = false;
    formFields.forEach(function(f){
        f.addEventListener('focus', function(){
            if(!startedForm){
                startedForm = true;
                if(typeof gtag === 'function') gtag('event', 'form_start', {event_category: 'Form'});
                if(typeof fbq === 'function') fbq('track', 'FormStart');
            }
        });
    });
});
</script>
<?php wp_footer(); ?>
</body>
</html>
