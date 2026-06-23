<?php

/**
 * Form Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$innerTemplate = [
	// H1 Title
	[ 'core/heading', [
		'level' => 1,
		'className' => 'has-h-3-font-size',
		'content' => '<strong>Terms and Conditions for <a href="https://www.cyclenorway.com" target="_blank" rel="noopener">https://www.cyclenorway.com</a></strong>'
	] ],

	// Last Updated
	[ 'core/paragraph', [
		'content' => 'Last updated: 24.04.2025'
	] ],

	// 1. Introduction
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>1. Introduction</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'These Terms and Conditions apply to all purchases made on www.cyclenorway.com and govern your access to digital services and physical merchandise offered through the website. By making a purchase, you agree to these terms.' ] ],

	// 2. The Parties
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>2. The Parties</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'The seller is:' ] ],
	[ 'core/paragraph', [ 'content' => '<strong>M Tolley AS</strong> <br> Org. no.: 913 254 228 <br> Address: Prinsens Gate 8, 0152 Oslo, Norway <br> Email: info@cyclenorway.com <br> Phone: +47 90746004' ] ],
	[ 'core/paragraph', [ 'content' => 'The buyer is the consumer who places the order and accepts these terms.' ] ],

	// 3. Prices
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>3. Prices</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'All prices are stated in Euros (EUR) and include applicable VAT for each country. Any additional costs, such as shipping, will be clearly stated before you complete your purchase.' ] ],

	// 4. Agreement
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>4. Agreement</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'The agreement between the buyer and M Tolley AS consists of these terms, your order, and the order confirmation. In case of conflict, the order confirmation will prevail.' ] ],

	// 5. Payment
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>5. Payment</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'We accept secure payments through <strong>Stripe</strong>, which allows for payment via most major credit and debit cards and other payment systems available in certain countries. The amount will be charged at the time of purchase for digital services or at the time the merchandise order is placed.' ] ],

	// 6. Delivery
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>6. Delivery</strong>' ] ],
	[ 'core/paragraph', [ 'content' => '<strong>a) Digital Services (subscriptions, online consultations, and route planning and itineraries)</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'Access to Subscription digital services is granted immediately after payment is confirmed. Username and password created by the customer at checkout gives valid access for the time specified at checkout. Annual subscriptions renew automatically unless cancelled.' ] ],
	[ 'core/paragraph', [ 'content' => '<strong>b) Merchandise</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'All merchandise is made to order. Production time is normally 5-10 business days, with shipping times depending on your location. Total delivery time is generally 10-20 business days.' ] ],

	// 7. Right of Withdrawal
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>7. Right of Withdrawal</strong>' ] ],
	[ 'core/paragraph', [ 'content' => '<strong>a) Digital Services – Subscriptions</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'By purchasing a digital subscription or service, you <strong>expressly agree</strong> that access begins immediately after payment. Under Section 22 of the Norwegian Right of Withdrawal Act, the <strong>14-day right of withdrawal does not apply once access is granted</strong>.' ] ],
	[ 'core/list', [ 'values' => '<li>Refunds are not offered for change of mind, dissatisfaction with content, or cases where information was available but not what the user expected.</li><li>Refunds are only considered in cases of duplicate payment, verified technical failure, or material misrepresentation of content.</li>' ] ],

	// 8. Complaints
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>8. Complaints and Defective Products</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'If your product is defective, missing, or damaged, you have the right to complain under the Consumer Purchase Act. You must notify us within a reasonable time (within 2 months of discovery).' ] ],

	// 9. Refund Policy
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>9. Refund Policy – Digital Products</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'Due to the nature of digital products, we do not offer refunds for change of mind. Refunds will only be considered for Duplicate Purchase, Technical Issues, or Misrepresentation.' ] ],

	// 12. Subscriptions
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>12. Subscription Terms</strong>' ] ],
	[ 'core/list', [ 'values' => '<li>An email reminder will be sent at least 7 days before renewal.</li><li>You can cancel any time through your account dashboard.</li><li>Misuse of subscription access may result in immediate suspension.</li>' ] ],

	// 19. Changes
	[ 'core/heading', [ 'level' => 2, 'className' => 'has-h-4-font-size', 'content' => '<strong>19. Changes to Terms and Conditions</strong>' ] ],
	[ 'core/paragraph', [ 'content' => 'Cycle Norway reserves the right to modify these Terms and Conditions at any time. Changes will be effective immediately upon posting on our website.' ] ],
];

$innerBlocks = [
	'core/heading',
	'core/paragraph',
	'core/list',
	'core/image',
	'core/buttons',
	'core/separator',
	'core/html'
];

$fields = get_fields();
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-text',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);

?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<InnerBlocks class="theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($innerBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
	</div>
</section>
