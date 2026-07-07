<?php
/**
 * Thankyou page
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;

$pass_status = isset( $_GET['jg_pass_status'] ) ? sanitize_key( $_GET['jg_pass_status'] ) : '';
$error_msg   = '';
switch ( $pass_status ) {
	case 'weak_password':  $error_msg = 'পাসওয়ার্ড যথেষ্ট শক্তিশালী নয়। বড়+ছোট হাতের অক্ষর, সংখ্যা ও বিশেষ চিহ্ন ব্যবহার করুন।'; break;
	case 'mismatch':       $error_msg = 'পাসওয়ার্ড দুটি মিলছে না।'; break;
	case 'empty_password': $error_msg = 'পাসওয়ার্ড দিন।'; break;
	case 'wrong_current':  $error_msg = 'বর্তমান পাসওয়ার্ড ভুল।'; break;
	case 'security_error': $error_msg = 'নিরাপত্তা ত্রুটি। আবার চেষ্টা করুন।'; break;
	case 'invalid_order':  $error_msg = 'অর্ডার পাওয়া যায়নি।'; break;
	case 'invalid_key':    $error_msg = 'অর্ডার কী ভুল।'; break;
	case 'no_user_found':  $error_msg = 'ইউজার পাওয়া যায়নি।'; break;
}
?>
<style>
*{margin:0;padding:0;box-sizing:border-box}
.ty-wrap{max-width:380px;margin:0 auto;padding:8px 10px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI','Hind Siliguri',sans-serif}
.ty-card{background:#fff;border-radius:14px;padding:14px 12px;box-shadow:0 2px 16px rgba(0,0,0,.06)}
.ty-icon{text-align:center;font-size:30px;margin-bottom:2px}
.ty-title{text-align:center;font-size:16px;font-weight:700;color:#111;margin-bottom:8px}
.ty-info{background:#f0fdf4;border-radius:10px;padding:6px 10px;margin-bottom:10px}
.ty-row{display:flex;justify-content:space-between;font-size:13px;padding:4px 0;color:#333}
.ty-row+.ty-row{border-top:1px solid #dcfce7}
.ty-lbl{color:#666}
.ty-val{font-weight:600;color:#111}
.ty-field{margin-bottom:6px}
.ty-field label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:2px}
.ty-field input{width:100%;padding:8px 34px 8px 10px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;outline:none;box-sizing:border-box;font-size:13px}
.ty-field input:focus{border-color:#22c55e;box-shadow:0 0 0 2px rgba(34,197,94,.12)}
.ty-pw{position:relative}
.ty-eye{position:absolute;right:9px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;user-select:none}
.ty-str{font-size:11px;margin:1px 0 3px;min-height:16px}
.ty-str.w{color:#dc2625}
.ty-str.m{color:#d97706}
.ty-str.s{color:#16a34a}
.ty-sub{width:100%;padding:9px;background:#22c55e;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;margin-top:2px}
.ty-sub:hover{background:#16a34a}
.ty-err{color:#dc2625;font-size:12px;text-align:center;margin-bottom:4px;line-height:1.4}
.ty-guide{font-size:11px;padding:5px 10px;border-radius:6px;margin-bottom:4px;line-height:1.6;min-height:18px}
.ty-guide.pending{background:#fef2f2;border-left:3px solid #dc2625;color:#991b1b}
.ty-guide.done{background:#f0fdf4;border-left:3px solid #16a34a;color:#166534}
.ty-hide{display:none}
</style>

<div class="ty-wrap">

<?php if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

	<div class="ty-card">
		<div class="ty-icon">❌</div>
		<div class="ty-title">দুঃখিত! অর্ডার ব্যর্থ হয়েছে</div>
		<div class="ty-info">
			<div class="ty-row"><span class="ty-lbl">অর্ডার নম্বর</span><span class="ty-val"><?php echo $order->get_order_number(); ?></span></div>
		</div>
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="ty-sub" style="display:block;text-align:center;text-decoration:none">পুনরায় পেমেন্ট করুন</a>
	</div>

	<?php else :

		$billing_phone = $order->get_billing_phone();
		$billing_email = $order->get_billing_email();
	?>

	<div class="ty-card">
		<div class="ty-icon">✅</div>
		<div class="ty-title">অর্ডার সফল হয়েছে!</div>

		<div class="ty-info">
			<div class="ty-row"><span class="ty-lbl">অর্ডার নম্বর</span><span class="ty-val"><?php echo $order->get_order_number(); ?></span></div>
			<div class="ty-row"><span class="ty-lbl">মোট টাকা</span><span class="ty-val"><?php echo $order->get_formatted_order_total(); ?></span></div>
			<div class="ty-row"><span class="ty-lbl">ইমেইল</span><span class="ty-val" style="font-size:12px"><?php echo esc_html( $billing_email ); ?></span></div>
		</div>

		<?php if ( $error_msg ) : ?>
			<div class="ty-err"><?php echo esc_html( $error_msg ); ?></div>
		<?php endif; ?>

		<form method="POST" onsubmit="return tyValidate()">

			<div class="ty-field">
				<label>📱 হোয়াটসঅ্যাপ নাম্বার</label>
				<input type="tel" name="phone" id="ty_phone" placeholder="01XXXXXXXXX" value="<?php echo esc_attr( $billing_phone ); ?>">
			</div>

			<div class="ty-field">
				<label>🔐 নতুন পাসওয়ার্ড</label>
				<div class="ty-pw">
					<input type="password" name="pass1" id="ty_p1" placeholder="নতুন পাসওয়ার্ড" onkeyup="tyStrength()" required>
					<span class="ty-eye" onclick="tyToggle('ty_p1',this)">👁️</span>
				</div>
			</div>

			<div id="ty_str" class="ty-str"></div>
			<div id="ty_guide" class="ty-guide"></div>

			<div class="ty-field">
				<label>🔐 পুনরায় পাসওয়ার্ড</label>
				<div class="ty-pw">
					<input type="password" name="pass2" id="ty_p2" placeholder="পুনরায় পাসওয়ার্ড" required>
					<span class="ty-eye" onclick="tyToggle('ty_p2',this)">👁️</span>
				</div>
			</div>

			<input type="hidden" name="jg_set_password" value="1">
			<?php wp_nonce_field( 'jg_set_password_action', 'jg_nonce' ); ?>
			<input type="hidden" name="order_id" value="<?php echo $order->get_id(); ?>">
			<input type="hidden" name="order_key" value="<?php echo $order->get_order_key(); ?>">

			<button type="submit" class="ty-sub">সাবমিট</button>
		</form>
	</div>

	<?php endif; ?>

<?php else : ?>

	<div class="ty-card">
		<div class="ty-icon">✅</div>
		<div class="ty-title">ধন্যবাদ!</div>
		<div class="ty-info"><div class="ty-row"><span class="ty-lbl">আপনার অর্ডারটি সফল হয়েছে।</span></div></div>
	</div>

<?php endif; ?>

</div>

<script>
function tyToggle(id,el){
	var i=document.getElementById(id);
	if(i.type==='password'){i.type='text';el.textContent='🙈'}
	else{i.type='password';el.textContent='👁️'}
}
function tyStrength(){
	var v=document.getElementById('ty_p1').value,s=document.getElementById('ty_str'),g=document.getElementById('ty_guide'),rules=[],remain=[];
	if(v.length<8)remain.push('👉 কমপক্ষে ৮ অক্ষর');else rules.push('✅');
	if(!/[a-z]/.test(v))remain.push('👉 ছোট হাতের অক্ষর (a-z)');else rules.push('✅');
	if(!/[A-Z]/.test(v))remain.push('👉 বড় হাতের অক্ষর (A-Z)');else rules.push('✅');
	if(!/\d/.test(v))remain.push('👉 সংখ্যা (0-9)');else rules.push('✅');
	if(!/[\W_]/.test(v))remain.push('👉 বিশেষ চিহ্ন (!@#)');else rules.push('✅');
	if(rules.length===5){s.innerHTML='💪 শক্তিশালী';s.className='ty-str s';g.innerHTML='✅ পাসওয়ার্ড সম্পূর্ণ ঠিক আছে';g.className='ty-guide done'}
	else{var c=remain.length;if(c<=2){s.innerHTML='⚠️ মাঝারি';s.className='ty-str m'}else{s.innerHTML='❌ দুর্বল';s.className='ty-str w'};g.innerHTML=remain.join('<br>');g.className='ty-guide pending'}
}
function tyValidate(){
	var v=document.getElementById('ty_p1').value;
	if(!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(v)){
		alert('পাসওয়ার্ড নিয়ম অনুসরণ করুন');
		return false;
	}
	if(v!==document.getElementById('ty_p2').value){
		alert('পাসওয়ার্ড দুটি মিলছে না');
		return false;
	}
	return true;
}
</script>
