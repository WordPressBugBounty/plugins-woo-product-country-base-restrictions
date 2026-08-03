<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $fzpcr;

// Per-slug emblem icons (lucide path data) come from the library's icon
// registry — assets/zui/icons.php is included so we can call \Zorem\UI\get_icon().
$cbr_zui_dir = $fzpcr->get_plugin_path() . '/assets/zui';
if ( ! function_exists( 'Zorem\UI\get_icon' ) && file_exists( $cbr_zui_dir . '/icons.php' ) ) {
	require_once $cbr_zui_dir . '/icons.php';
}

$more_plugins = array(
	0 => array(
		'title' => 'Advanced Shipment Tracking',
		'description' => __( 'AST Pro provides powerful features to easily add tracking information to WooCommerce orders, automate fulfillment workflows, and keep your customers happy and informed.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/?utm_source=wp-admin&utm_medium=AST&utm_campaign=add-ons',
		'icon'   => 'package',
		'tint'   => '#DBEAFE',
		'accent' => '#2563EB',
		'file' => 'ast-pro/ast-pro.php',
	),
	1 => array(
		'title' => 'TrackShip for WooCommerce',
		'description' => __( 'Take control of your post-shipping workflows, reduce time spent on customer service and provide a superior post-purchase experience to your customers. Beyond automatic shipment tracking, TrackShip brings a branded tracking experience into your store.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://wordpress.org/plugins/trackship-for-woocommerce/?utm_source=wp-admin&utm_medium=TS4WC&utm_campaign=add-ons',
		'img'    => $fzpcr->plugin_dir_url() . 'assets/images/ts-45.png',
		'tint'   => '#CCFBF1',
		'accent' => '#0D9488',
		'file' => 'trackship-for-woocommerce/trackship-for-woocommerce.php',
	),
	2 => array(
		'title' => 'Zorem Local Pickup Pro',
		'description' => __( 'The Advanced Local Pickup (ALP) helps you manage local pickup order workflows more conveniently by extending the WooCommerce Local Pickup shipping method. The Pro version lets you set up multiple pickup locations, split business hours, and apply discounts by pickup location.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://www.zorem.com/product/zorem-local-pickup-pro/?utm_source=wp-admin&utm_medium=ALPPRO&utm_campaign=add-ons',
		'icon'   => 'store',
		'tint'   => '#DCFCE7',
		'accent' => '#16A34A',
		'file' => 'advanced-local-pickup-pro/advanced-local-pickup-pro.php',
	),
	3 => array(
		'title' => 'Customer Email Verification',
		'description' => __( 'Customer Email Verification helps WooCommerce store owners reduce registration spam and fraudulent orders by requiring customers to verify their email address when registering an account or before placing an order.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://www.zorem.com/product/customer-email-verification/?utm_source=wp-admin&utm_medium=CEV&utm_campaign=add-ons',
		'icon'   => 'shield-check',
		'tint'   => '#FEE2E2',
		'accent' => '#DC2626',
		'file' => 'customer-email-verification/customer-email-verification.php',
	),
	4 => array(
		'title' => 'SMS for WooCommerce',
		'description' => __( 'Keep your customers informed by sending them automated SMS text messages with order and delivery updates. You can send SMS notifications to customers when the order status is updated or when the shipment is out for delivery and more.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://www.zorem.com/product/sms-for-woocommerce/?utm_source=wp-admin&utm_medium=SMSWOO&utm_campaign=add-ons',
		'icon'   => 'phone',
		'tint'   => '#DBEAFE',
		'accent' => '#2563EB',
		'file' => 'sms-for-woocommerce/sms-for-woocommerce.php',
	),
	5 => array(
		'title' => 'Email Reports for WooCommerce',
		'description' => __( 'Sales Report Email Pro helps you understand how well your store is performing and how your products are selling by sending daily, weekly, or monthly sales reports directly from your WooCommerce store to your email.', 'woo-product-country-base-restrictions' ),
		'url' => 'https://www.zorem.com/product/email-reports-for-woocommerce/?utm_source=wp-admin&utm_medium=SRE&utm_campaign=add-ons',
		'icon'   => 'mail',
		'tint'   => '#F3E8FF',
		'accent' => '#9333EA',
		'file' => 'sales-report-email-pro/sales-report-email-pro.php',
	),
);

?>
<div class="zui-tab-panel" id="cbr_content4" data-tab="go-pro"<?php echo ( isset( $active_tab ) && 'go-pro' === $active_tab ) ? '' : ' hidden'; ?>>
<div class="zui-layout zui-layout--full">
<main class="zui-content zui-content--full">
	<section id="cbr-section-go-pro" class="zui-section is-active" data-section="go-pro">

		<!-- CBR Go Pro v2 layout (mirrors AST structure exactly) -->
		<div class="cbr-go-pro-v2">
			<!-- Hero -->
			<div class="gopro-hero">
				<h1><?php esc_html_e( 'Take Your Country Restrictions to the Next Level', 'woo-product-country-base-restrictions' ); ?></h1>
				<p>
				<?php 
				echo wp_kses_post(
					__( 'Stop limiting your store with basic restrictions. Switch from a <strong>basic setup</strong> to a <a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRPRO&utm_campaign=go-pro" target="_blank">fully advanced restriction powerhouse</a>.', 'woo-product-country-base-restrictions' )
				);
				?>
				</p>
			</div>

			<!-- Feature comparison -->
			<div class="gopro-comparison">
				<!-- Table header -->
				<div class="gopro-comp-header">
					<div class="gopro-comp-header-label"><?php esc_html_e( 'Feature Comparison', 'woo-product-country-base-restrictions' ); ?></div>
					<div class="gopro-comp-header-col">
						<span class="comp-header-badge badge-current"><?php esc_html_e( 'Current', 'woo-product-country-base-restrictions' ); ?></span>
						<span class="comp-header-title"><?php esc_html_e( 'CBR FREE', 'woo-product-country-base-restrictions' ); ?></span>
					</div>
					<div class="gopro-comp-header-col is-pro">
						<span class="comp-header-badge badge-recommended"><?php esc_html_e( 'Recommended', 'woo-product-country-base-restrictions' ); ?></span>
						<span class="comp-header-title"><?php esc_html_e( 'CBR PRO', 'woo-product-country-base-restrictions' ); ?></span>
					</div>
				</div>
				<?php
				$comp_features = array(
					array(
						'title'     => __( 'GeoLocation Detection', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Detect customer country using WooCommerce geolocation or shipping address.', 'woo-product-country-base-restrictions' ),
						'free'      => 'check',
						'free_label'=> '',
						'pro'       => __( 'Advanced Detection', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Product Restrictions', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Restrict specific products from being purchased in certain countries.', 'woo-product-country-base-restrictions' ),
						'free'      => 'check',
						'free_label'=> '',
						'pro'       => __( 'Full Restrictions', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Catalog Visibility', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Hide or show products in your catalog based on the customer\'s country.', 'woo-product-country-base-restrictions' ),
						'free'      => 'limited',
						'free_label'=> __( 'Basic', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Full Control', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Catalog Restriction Rules', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Create restriction rules by product categories and apply to multiple countries at once.', 'woo-product-country-base-restrictions' ),
						'free'      => 'cross',
						'free_label'=> __( 'Not Available', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Category Rules', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Payment Gateway by Country', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Restrict specific payment gateways based on the customer\'s country.', 'woo-product-country-base-restrictions' ),
						'free'      => 'cross',
						'free_label'=> __( 'Not Available', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Gateway Control', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Country Detection Widget', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Display a country detection widget on the frontend with customizer support.', 'woo-product-country-base-restrictions' ),
						'free'      => 'cross',
						'free_label'=> __( 'Not Available', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Full Widget', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Debug Mode', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Show geolocation country in the frontend toolbar for easy debugging.', 'woo-product-country-base-restrictions' ),
						'free'      => 'check',
						'free_label'=> '',
						'pro'       => __( 'Advanced Debug', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Restrict on Place Order (Checkout)', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Restrict products at checkout based on the customer\'s billing/shipping country.', 'woo-product-country-base-restrictions' ),
						'free'      => 'cross',
						'free_label'=> __( 'Not Available', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Checkout Control', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Bypass Restrictions for Users', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Allow specific users or user roles to bypass country-based restrictions.', 'woo-product-country-base-restrictions' ),
						'free'      => 'cross',
						'free_label'=> __( 'Not Available', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'User Bypass', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Compatible with Popular Plugins', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Built-in support for WPML, Polylang, and other popular plugins.', 'woo-product-country-base-restrictions' ),
						'free'      => 'limited',
						'free_label'=> __( 'Limited Support', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Full Integration', 'woo-product-country-base-restrictions' ),
					),
					array(
						'title'     => __( 'Premium Support', 'woo-product-country-base-restrictions' ),
						'desc'      => __( 'Priority ticket handling and dedicated help center access.', 'woo-product-country-base-restrictions' ),
						'free'      => 'limited',
						'free_label'=> __( 'Standard Only', 'woo-product-country-base-restrictions' ),
						'pro'       => __( 'Priority Support', 'woo-product-country-base-restrictions' ),
					),
				);
				foreach ( $comp_features as $feat ) :
					?>
				<div class="gopro-comp-row">
					<div class="gopro-comp-feature">
						<strong><?php echo esc_html( $feat['title'] ); ?></strong>
						<span><?php echo esc_html( $feat['desc'] ); ?></span>
					</div>
					<div class="gopro-comp-cell">
						<?php if ( 'check' === $feat['free'] ) : ?>
							<span class="comp-icon icon-check">
								<svg fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
							</span>
						<?php else : ?>
							<span class="comp-icon icon-x">
								<svg fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5"><line x1="16" y1="8" x2="8" y2="16"/><line x1="8" y1="8" x2="16" y2="16"/></svg>
							</span>
						<?php endif; ?>
						<?php if ( ! empty( $feat['free_label'] ) ) : ?>
							<span class="comp-status"><?php echo esc_html( $feat['free_label'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="gopro-comp-cell is-pro">
						<span class="comp-icon icon-check">
							<svg fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
						</span>
						<span class="comp-status"><?php echo esc_html( $feat['pro'] ); ?></span>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<!-- CTA -->
			<div class="gopro-cta">
				<a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRPRO&utm_campaign=go-pro" class="gopro-cta-btn" target="_blank"><?php esc_html_e( 'GET STARTED WITH PRO', 'woo-product-country-base-restrictions' ); ?></a>
				<p class="gopro-cta-sub"><?php esc_html_e( 'Join store owners optimizing their country restrictions', 'woo-product-country-base-restrictions' ); ?></p>
			</div>

		</div>
		<!-- End .cbr-go-pro-v2 hero + comparison -->

		<!-- Powerful Add-ons (ZUI License Ecosystem) -->
		<div class="cbr-go-pro-v2">
			<div class="zui-lic-eco">
				<div class="zui-lic-eco__head">
					<div class="zui-lic-eco__heading">
						<span class="zui-lic-eco__icon">
							<svg class="zui-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
						</span>
						<div>
							<h3><?php esc_html_e( 'Powerful Add-ons', 'woo-product-country-base-restrictions' ); ?></h3>
							<p><?php esc_html_e( 'Extend your store\'s capabilities with our ecosystem.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
					</div>
					<div class="zui-lic-eco__filters">
						<button type="button" class="zui-lic-eco__filter is-active" data-filter="all"><?php esc_html_e( 'All', 'woo-product-country-base-restrictions' ); ?></button>
						<button type="button" class="zui-lic-eco__filter" data-filter="active"><?php esc_html_e( 'Active', 'woo-product-country-base-restrictions' ); ?></button>
						<button type="button" class="zui-lic-eco__filter" data-filter="addons"><?php esc_html_e( 'Add-ons', 'woo-product-country-base-restrictions' ); ?></button>
					</div>
				</div>
				<div class="zui-lic-eco__search">
					<span class="zui-lic-eco__search-icon">
						<svg class="zui-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
					</span>
					<input type="text" class="zui-input" id="cbr-lic-search" placeholder="<?php esc_attr_e( 'Search&hellip;', 'woo-product-country-base-restrictions' ); ?>">
				</div>
				<div class="zui-lic-eco__grid" id="cbr-lic-grid">
					<?php
					foreach ( $more_plugins as $index => $addon ) :
						$is_active = is_plugin_active( $addon['file'] );
						?>
						<div class="zui-card zui-lic-plugin" data-name="<?php echo esc_attr( strtolower( $addon['title'] ) ); ?>" data-active="<?php echo $is_active ? '1' : '0'; ?>">
							<div class="zui-lic-plugin__head">
								<span class="zui-lic-plugin__logo" style="background: <?php echo esc_attr( $addon['tint'] ); ?>; color: <?php echo esc_attr( $addon['accent'] ); ?>;">
									<?php
									if ( ! empty( $addon['img'] ) ) {
										?>
										<img src="<?php echo esc_url( $addon['img'] ); ?>" alt="<?php echo esc_attr( $addon['title'] ); ?>" loading="lazy">
										<?php
									} elseif ( function_exists( 'Zorem\UI\get_icon' ) ) {
										echo \Zorem\UI\get_icon( $addon['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static trusted SVG.
									}
									?>
								</span>
								<div class="zui-lic-plugin__id">
									<h4 class="zui-lic-plugin__name"><?php echo esc_html( $addon['title'] ); ?></h4>
									<span class="zui-lic-plugin__type"><?php esc_html_e( 'WOO EXTENSION', 'woo-product-country-base-restrictions' ); ?></span>
								</div>
								<?php if ( ! $is_active && 0 === $index ) : ?>
									<span class="zui-lic-plugin__badge"><?php esc_html_e( 'Recommended', 'woo-product-country-base-restrictions' ); ?></span>
								<?php endif; ?>
							</div>
							<div class="zui-lic-plugin__body">
								<p class="zui-lic-plugin__desc"><?php echo esc_html( $addon['description'] ); ?></p>
								<div class="zui-lic-plugin__foot">
									<?php if ( $is_active ) : ?>
										<span class="zui-lic-plugin__active">
											<span class="zui-lic-plugin__dot"></span>
											<?php esc_html_e( 'Active', 'woo-product-country-base-restrictions' ); ?>
										</span>
										<span class="zui-lic-plugin__stat"><?php esc_html_e( 'Active in this store', 'woo-product-country-base-restrictions' ); ?></span>
									<?php else : ?>
										<a class="zui-lic-plugin__get" href="<?php echo esc_url( $addon['url'] ); ?>" target="_blank">
											<span><?php esc_html_e( 'Get Extension', 'woo-product-country-base-restrictions' ); ?></span>
											<svg class="zui-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
										</a>
										<span class="zui-lic-plugin__stat"><?php esc_html_e( 'By zorem.com', 'woo-product-country-base-restrictions' ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					<div class="zui-lic-eco__empty" hidden><?php esc_html_e( 'No plugins matched.', 'woo-product-country-base-restrictions' ); ?></div>
				</div>
			</div>
		</div>

	</section>
</main>
</div>
</div>
