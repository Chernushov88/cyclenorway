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

$logic_page = '';
if (class_exists('MeprOptions')) {
	$mepr_options     = MeprOptions::fetch();
	$logic_page       = $mepr_options->login_page_url();
}

$fields = get_fields();
if (!is_array($fields)) {
	$fields = [];
}
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-paywall',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);
$subscribe_benefits = !empty($fields['subscribe']['benefits']) && is_string($fields['subscribe']['benefits']) ? explode(PHP_EOL, $fields['subscribe']['benefits']) : [];
$newsletter_benefits = array_filter(!empty($fields['newsletter']['benefits']) && is_array($fields['newsletter']['benefits']) ? $fields['newsletter']['benefits'] : [], function ($item) {
	return !empty($item) && is_numeric($item['icon']) && is_string($item['label']);
});
$paywall_icons = array_filter(!empty($fields['paywall_icons']) && is_array($fields['paywall_icons']) ? $fields['paywall_icons'] : [], function ($item) {
	return !empty($item) && is_array($item) && is_numeric($item['icon']) && is_string($item['content']);
});

$memberships = !empty($fields['memberships']) && is_array($fields['memberships']) ? $fields['memberships'] : [];
$active_memberships = [];
if (class_exists('MeprProduct')) {
	foreach ( $memberships as $index => $id ) {
		$product = new MeprProduct( $id );

		if ( ! $product->ID ) {
			continue;
		}

		$price_formatted = MeprAppHelper::format_currency( $product->price );

		$billing_term = 'one time';
		if ( ! $product->is_one_time_payment() ) {
			if ( $product->period_type == 'years' ) {
				$billing_term = ( $product->period == 1 ) ? __('per year', 'profidev-theme') : sprintf(__('every %s years', 'profidev-theme'), $product->period);
			} elseif ( $product->period_type == 'months' ) {
				$billing_term = ( $product->period == 1 ) ? __('per month', 'profidev-theme') : sprintf(__('every %s months', 'profidev-theme'), $product->period);
			}
		}

		$access_text = $product->pricing_title;
//		if ( $product->period_type == 'years' ) {
//			$access_text = sprintf(__('%s months access', 'profidev-theme'), ( 12 * $product->period ));
//		} elseif ( $product->period_type == 'months' ) {
//			$access_text = sprintf(__('%s days access', 'profidev-theme'), ( 30 * $product->period ));
//		} elseif ( $product->period_type == 'lifetime' ) {
//			$access_text = __('Lifetime access', 'profidev-theme');
//		} else {
//			$access_text = sprintf(__('%s %s access', 'profidev-theme'), $product->period, $product->period_type);
//		}

		$active_memberships[] = [
			'id' => $id,
			'is_checked' => $index === 0,
			'show_badge' => $index === 1,
			'title' => esc_html( $product->post_title ),
			'description' => esc_html( $access_text ),
			'price' => wp_kses_post( $price_formatted ),
			'billing_term' => esc_html( $billing_term ),
			'permalink' => get_permalink( $id ),
		];
	}
}
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="paywall-main">
			<div class="theme-text-element">
				<?php if (!empty($fields['subscribe']['title'])): ?>
					<h2 class="theme-h4 title"><?php echo wp_kses_post($fields['subscribe']['title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($fields['subscribe']['subtitle'])): ?>
					<p><?php echo wp_kses_post($fields['subscribe']['subtitle']); ?></p>
				<?php endif; ?>
				<?php if (!empty($subscribe_benefits)): ?>
				<ul class="no-list icon-list">
					<?php foreach ($subscribe_benefits as $benefit): ?>
						<li><?php echo wp_kses_post($benefit); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<?php if (!empty($active_memberships)): ?>
			<profidev-paywall>
				<form action="#" method="GET" class="paywall-form">
					<?php foreach ($active_memberships as $index => $subscription): ?>
					<div class="paywall-form-row">
						<input type="radio" id="subscription_<?php echo $subscription['id']; ?>" <?php if ($subscription['is_checked']): ?>checked<?php endif; ?> name="memberships" value="<?php echo $subscription['permalink']; ?>">
						<label for="subscription_<?php echo $subscription['id']; ?>" class="field-radio">
							<div class="radio" aria-hidden="true"></div>
							<div class="label">
								<h3 class="no-margin"><?php echo $subscription['title']; ?></h3>
								<p class="no-margin"><?php echo $subscription['description']; ?></p>
							</div>
							<div class="price">
								<strong><?php echo $subscription['price']; ?></strong>
								<?php echo $subscription['billing_term']; ?>
							</div>
							<?php if ($subscription['show_badge']): ?>
								<span class="badge"><?php echo __('best value', 'profidev-theme'); ?></span>
							<?php endif; ?>
						</label>
					</div>
					<?php endforeach; ?>

					<button type="submit" class="no-btn theme-button-primary"><?php echo __('Become a member', 'profidev-theme'); ?></button>
					<?php if (!empty($logic_page) && get_current_user_id() === 0): ?>
						<p class="form-text"><?php echo __('Already a member?', 'profidev-theme'); ?> <a href="<?php echo $logic_page; ?>"><?php echo __('Log in', 'profidev-theme'); ?></a></p>
					<?php endif; ?>
				</form>
			</profidev-paywall>
			<?php endif; ?>
		</div>
		<?php if (!empty($fields['newsletter']['shortcode'])): ?>
		<div class="paywall-newlatters">
			<div class="theme-text-element">
				<?php if (!empty($fields['newsletter']['title'])): ?>
					<h3 class="theme-h4 title"><?php echo wp_kses_post($fields['newsletter']['title']); ?></h3>
				<?php endif; ?>
				<?php if (!empty($fields['newsletter']['subtitle'])): ?>
					<p><?php echo wp_kses_post($fields['newsletter']['subtitle']); ?></p>
				<?php endif; ?>
			</div>
			<div class="newslatter-form">
				<ul class="no-list theme-grid newlatters-list">
					<?php foreach ($newsletter_benefits as $benefit): ?>
					<li class="item">
						<?php echo wp_get_attachment_image($benefit['icon'], 'full', false, ['loading' => 'lazy', 'class' => 'icon']); ?>
						<span><?php echo nl2br(wp_kses_post($benefit['label'])); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
				<div class="form-block">
					<?php echo do_shortcode($fields['newsletter']['shortcode']); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<?php if (!empty($paywall_icons)): ?>
	<div class="paywall-icon-list">
		<div class="theme-container">
			<ul class="no-list theme-grid icon-list">
				<?php foreach ($paywall_icons as $icon): ?>
				<li class="item">
					<?php echo wp_get_attachment_image($icon['icon'], 'full', false, ['class' => 'icon']); ?>
					<div class="theme-text-element">
						<?php echo wpautop(wp_kses_post($icon['content'])); ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>
</section>
