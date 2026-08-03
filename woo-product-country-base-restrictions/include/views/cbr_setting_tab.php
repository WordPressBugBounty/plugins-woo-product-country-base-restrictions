<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $fzpcr;

$product_visibility = get_option( 'product_visibility' );
if ( empty( $product_visibility ) ) {
	$product_visibility = 'hide_completely';
}

// Active sidebar section.
$cbr_section = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : 'catalog-visibility';
$cbr_valid_sections = array( 'catalog-visibility', 'restriction-settings', 'country-detection-widget' );
if ( ! in_array( $cbr_section, $cbr_valid_sections, true ) ) {
	$cbr_section = 'catalog-visibility';
}
?>
<div class="zui-tab-panel" id="cbr_content1" data-tab="settings"<?php echo ( isset( $active_tab ) && 'settings' === $active_tab ) ? '' : ' hidden'; ?>>
<div class="zui-layout">

	<div class="zui-sidebar__overlay" data-ast-drawer-close></div>

	<aside class="zui-sidebar" id="ast-set-sidebar">

		<div class="zui-sidebar__mobile-head">
			<span class="zui-sidebar__mobile-title"><?php esc_html_e( 'CBR Settings', 'woo-product-country-base-restrictions' ); ?></span>
			<button type="button" class="zui-sidebar__close" data-ast-drawer-close aria-label="<?php esc_attr_e( 'Close menu', 'woo-product-country-base-restrictions' ); ?>">
				<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>
		</div>

		<nav class="zui-sidebar__nav" aria-label="<?php esc_attr_e( 'Settings sections', 'woo-product-country-base-restrictions' ); ?>">

			<button type="button" class="zui-sidebar__item<?php echo ( 'catalog-visibility' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="catalog-visibility" aria-controls="cbr-section-catalog-visibility"<?php echo ( 'catalog-visibility' === $cbr_section ) ? ' aria-current="true"' : ''; ?>>
				<span class="zui-sidebar__icon">
					<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
				</span>
				<span class="zui-sidebar__label"><?php esc_html_e( 'Catalog Visibility', 'woo-product-country-base-restrictions' ); ?></span>
			</button>

			<button type="button" class="zui-sidebar__item<?php echo ( 'restriction-settings' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="restriction-settings" aria-controls="cbr-section-restriction-settings"<?php echo ( 'restriction-settings' === $cbr_section ) ? ' aria-current="true"' : ''; ?>>
				<span class="zui-sidebar__icon">
					<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
				</span>
				<span class="zui-sidebar__label"><?php esc_html_e( 'Restriction Settings', 'woo-product-country-base-restrictions' ); ?></span>
			</button>

			<button type="button" class="zui-sidebar__item<?php echo ( 'country-detection-widget' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="country-detection-widget" aria-controls="cbr-section-country-detection-widget"<?php echo ( 'country-detection-widget' === $cbr_section ) ? ' aria-current="true"' : ''; ?>>
				<span class="zui-sidebar__icon">
					<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
				</span>
				<span class="zui-sidebar__label"><?php esc_html_e( 'Country Detection Widget', 'woo-product-country-base-restrictions' ); ?></span>
			</button>

		</nav>

		<div class="zui-quickhelp">
			<h4 class="zui-quickhelp__title"><?php esc_html_e( 'Quick Help', 'woo-product-country-base-restrictions' ); ?></h4>
			<p class="zui-quickhelp__text"><?php esc_html_e( 'Learn how to configure CBR for best results.', 'woo-product-country-base-restrictions' ); ?></p>

			<div class="zui-quickhelp__links">
				<a class="zui-quickhelp__link" href="https://docs.zorem.com/docs/country-based-restrictions-free/?utm_source=wp-admin&utm_medium=CBR&utm_campaign=QuickHelp" target="_blank" rel="noreferrer noopener">
					<span><?php esc_html_e( 'View Documentation', 'woo-product-country-base-restrictions' ); ?></span>
					<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</a>
				<a class="zui-quickhelp__link zui-quickhelp__link--muted" href="https://wordpress.org/support/plugin/woo-product-country-base-restrictions/" target="_blank" rel="noreferrer noopener">
					<span><?php esc_html_e( 'Get Support', 'woo-product-country-base-restrictions' ); ?></span>
					<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</a>
			</div>

			<div class="zui-quickhelp__art" aria-hidden="true">
				<span class="zui-quickhelp__glow"></span>
				<span class="zui-quickhelp__sphere"></span>
				<span class="zui-quickhelp__paper zui-quickhelp__paper--1">
				<span class="zui-quickhelp__line zui-quickhelp__line--brand"></span>
				<span class="zui-quickhelp__line zui-quickhelp__line--mid"></span>
				<span class="zui-quickhelp__line zui-quickhelp__line--short"></span>
				</span>
				<span class="zui-quickhelp__paper zui-quickhelp__paper--2">
				<span class="zui-quickhelp__line zui-quickhelp__line--accent"></span>
				<span class="zui-quickhelp__line zui-quickhelp__line--mid"></span>
				<span class="zui-quickhelp__line zui-quickhelp__line--mid"></span>
				<span class="zui-quickhelp__line zui-quickhelp__line--short"></span>
				</span>
				<span class="zui-quickhelp__check">✓</span>
			</div>
		</div>

	</aside>

	<main class="zui-content" id="ast-set-content">
		<form id="cbr_setting_tab_form" method="post" action="" enctype="multipart/form-data" novalidate>

			<!-- Section 1: Catalog Visibility -->
			<section id="cbr-section-catalog-visibility" class="zui-section<?php echo ( 'catalog-visibility' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="catalog-visibility"<?php echo ( 'catalog-visibility' === $cbr_section ) ? '' : ' hidden'; ?>>

				<div class="zui-section-header">
					<div class="zui-section-header__main">
						<span class="zui-section-header__icon">
							<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
						</span>
						<div class="zui-section-header__text">
							<div class="zui-section-header__titlewrap">
								<h2 class="zui-section-header__title"><?php esc_html_e( 'Catalog Visibility', 'woo-product-country-base-restrictions' ); ?></h2>
							</div>
							<p class="zui-section-header__sub"><?php esc_html_e( 'Configure how restricted products are displayed to customers.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
					</div>
					<div class="zui-section-header__actions">
						<span class="zui-savebtn-wrap ast-accordion-btn">
							<button name="save" type="button" class="zui-savebtn cbr-save woocommerce-save-button btn_ast2" value="Save Changes">
								<span class="zui-savebtn__spinner" aria-hidden="true"></span>
								<span class="zui-savebtn__label"><?php esc_html_e( 'Save Changes', 'woo-product-country-base-restrictions' ); ?></span>
							</button>
						</span>
					</div>
				</div>

				<div class="zui-card">
					<p class="zui-row__desc" style="padding: 16px 20px 0; margin: 0;"><?php esc_html_e( 'Select how restricted products should be handled in your catalog. Only one option can be active.', 'woo-product-country-base-restrictions' ); ?></p>
					<div class="zui-radio-cards zui-radio-cards--list">

						<!-- Option 1: Hide Completely (FREE) -->
						<label class="zui-radio-card product_visibility<?php echo ( 'hide_completely' === $product_visibility ) ? ' is-selected' : ''; ?>" for="product_visibility_hide_completely">
							<input type="radio" id="product_visibility_hide_completely" name="product_visibility" value="hide_completely" class="zui-radio-card__input product_visibility" <?php checked( 'hide_completely', $product_visibility ); ?>>
							<span class="zui-radio-card__body">
								<span class="zui-radio-card__label"><?php esc_html_e( 'Hide Completely', 'woo-product-country-base-restrictions' ); ?></span>
								<span class="zui-radio-card__desc"><?php esc_html_e( 'Completely hide restricted products from your store.', 'woo-product-country-base-restrictions' ); ?></span>
							</span>
						</label>
						<div class="main-panel">
							<div class="inside <?php echo ( 'hide_completely' == $product_visibility ) ? 'active' : ''; ?>" data-value="hide_completely">
								<?php $this->get_html_visibility_setting( $this->get_hide_completely_settings() ); ?>
							</div>
						</div>

						<!-- Option 2: Hide Catalog Visibility (FREE, with PRO-locked sub-option) -->
						<label class="zui-radio-card product_visibility<?php echo ( 'hide_catalog_visibility' === $product_visibility ) ? ' is-selected' : ''; ?>" for="product_visibility_hide_catalog_visibility">
							<input type="radio" id="product_visibility_hide_catalog_visibility" name="product_visibility" value="hide_catalog_visibility" class="zui-radio-card__input product_visibility" <?php checked( 'hide_catalog_visibility', $product_visibility ); ?>>
							<span class="zui-radio-card__body">
								<span class="zui-radio-card__label"><?php esc_html_e( 'Hide Catalog Visibility', 'woo-product-country-base-restrictions' ); ?></span>
								<span class="zui-radio-card__desc"><?php esc_html_e( 'Hide restricted products from your shop and search results. Products remain accessible via direct links.', 'woo-product-country-base-restrictions' ); ?></span>
							</span>
						</label>
						<div class="main-panel">
							<div class="inside <?php echo ( 'hide_catalog_visibility' == $product_visibility ) ? 'active' : ''; ?>" data-value="hide_catalog_visibility">
								<?php $this->get_html_visibility_setting( $this->get_product_settings() ); ?>
								<!-- PRO: Hide Restricted Price -->
								<div class="zui-row zui-row--inline border_1 cbr-pro-feature-row">
									<div class="zui-row__head">
										<span class="zui-row__label">
											<?php esc_html_e( 'Hide Restricted Price', 'woo-product-country-base-restrictions' ); ?>
											<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Enable this option to hide prices for restricted products.', 'woo-product-country-base-restrictions' ); ?>">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
												<span class="zui-tooltip__bubble"><?php esc_html_e( 'Enable this option to hide prices for restricted products.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
											</span>
										</span>
									</div>
									<div class="zui-row__control">
										<span class="zui-pro-feature">
											<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
											<span class="zui-pro-feature__lock" aria-hidden="true">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
											</span>
										</span>
										<label class="zui-toggle">
											<input type="hidden" value="0">
											<input type="checkbox" disabled value="1" class="zui-toggle__input pro_feature">
											<span class="zui-toggle__track">
												<span class="zui-toggle__thumb"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Option 3: Catalog Visible (non purchasable) - FREE has Hide Product Variations only -->
						<label class="zui-radio-card product_visibility<?php echo ( 'show_catalog_visibility' === $product_visibility ) ? ' is-selected' : ''; ?>" for="product_visibility_show_catalog_visibility">
							<input type="radio" id="product_visibility_show_catalog_visibility" name="product_visibility" value="show_catalog_visibility" class="zui-radio-card__input product_visibility" <?php checked( 'show_catalog_visibility', $product_visibility ); ?>>
							<span class="zui-radio-card__body">
								<span class="zui-radio-card__label"><?php esc_html_e( 'Catalog Visible (non purchasable)', 'woo-product-country-base-restrictions' ); ?></span>
								<span class="zui-radio-card__desc"><?php esc_html_e( 'Display restricted products in your catalog but make them non-purchasable.', 'woo-product-country-base-restrictions' ); ?></span>
							</span>
						</label>
						<div class="main-panel">
							<div class="inside <?php echo ( 'show_catalog_visibility' == $product_visibility ) ? 'active' : ''; ?>" data-value="show_catalog_visibility">
								<!-- PRO: Hide Restricted Product Price (first, locked) -->
								<div class="zui-row zui-row--inline border_1 cbr-pro-feature-row">
									<div class="zui-row__head">
										<span class="zui-row__label">
											<?php esc_html_e( 'Hide Restricted Product Price', 'woo-product-country-base-restrictions' ); ?>
											<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Enable this option to hide prices for restricted products.', 'woo-product-country-base-restrictions' ); ?>">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
												<span class="zui-tooltip__bubble"><?php esc_html_e( 'Enable this option to hide prices for restricted products.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
											</span>
										</span>
									</div>
									<div class="zui-row__control">
										<span class="zui-pro-feature">
											<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
											<span class="zui-pro-feature__lock" aria-hidden="true">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
											</span>
										</span>
										<label class="zui-toggle">
											<input type="hidden" value="0">
											<input type="checkbox" disabled value="1" class="zui-toggle__input pro_feature">
											<span class="zui-toggle__track">
												<span class="zui-toggle__thumb"></span>
											</span>
										</label>
									</div>
								</div>
								<!-- FREE: Hide Product Variations -->
								<?php $this->get_html_visibility_setting( $this->get_product_catelog_settings() ); ?>
								<!-- PRO: Allow Add To Cart -->
								<div class="zui-row zui-row--inline border_1 cbr-pro-feature-row">
									<div class="zui-row__head">
										<span class="zui-row__label">
											<?php esc_html_e( 'Allow Add To Cart', 'woo-product-country-base-restrictions' ); ?>
											<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( "Enable this option to allow add to cart the restricted products but the customer can't process to checkout.", 'woo-product-country-base-restrictions' ); ?>">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
												<span class="zui-tooltip__bubble"><?php esc_html_e( "Enable this option to allow add to cart the restricted products but the customer can't process to checkout.", 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
											</span>
										</span>
									</div>
									<div class="zui-row__control">
										<span class="zui-pro-feature">
											<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
											<span class="zui-pro-feature__lock" aria-hidden="true">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
											</span>
										</span>
										<label class="zui-toggle">
											<input type="hidden" value="0">
											<input type="checkbox" disabled value="1" class="zui-toggle__input pro_feature">
											<span class="zui-toggle__track">
												<span class="zui-toggle__thumb"></span>
											</span>
										</label>
									</div>
								</div>
								<!-- PRO: Sort products by availability -->
								<div class="zui-row zui-row--inline border_1 cbr-pro-feature-row">
									<div class="zui-row__head">
										<span class="zui-row__label">
											<?php esc_html_e( 'Sort products by availability (Available → Restricted)', 'woo-product-country-base-restrictions' ); ?>
											<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Move restricted products to the end of product listings.', 'woo-product-country-base-restrictions' ); ?>">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
												<span class="zui-tooltip__bubble"><?php esc_html_e( 'Move restricted products to the end of product listings.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
											</span>
										</span>
									</div>
									<div class="zui-row__control">
										<span class="zui-pro-feature">
											<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
											<span class="zui-pro-feature__lock" aria-hidden="true">
												<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
											</span>
										</span>
										<label class="zui-toggle">
											<input type="hidden" value="0">
											<input type="checkbox" disabled value="1" class="zui-toggle__input pro_feature">
											<span class="zui-toggle__track">
												<span class="zui-toggle__thumb"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Option 4: Restrict on Place Order (fully PRO-locked) -->
						<label class="zui-radio-card zui-radio-card--locked cbr-pro-feature-row" for="product_visibility_product_restrict_on_place" aria-disabled="true">
							<input type="radio" id="product_visibility_product_restrict_on_place" name="" value="" class="zui-radio-card__input" disabled>
							<span class="zui-radio-card__body">
								<span class="zui-radio-card__label"><?php esc_html_e( 'Restrict product only on Place Order (Checkout)', 'woo-product-country-base-restrictions' ); ?></span>
								<span class="zui-radio-card__desc"><?php esc_html_e( 'Enable this option to apply country-based restriction only when the customer clicks the Place Order button on the checkout page.', 'woo-product-country-base-restrictions' ); ?></span>
							</span>
							<span class="zui-pro-feature">
								<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
								<span class="zui-pro-feature__lock" aria-hidden="true">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
								</span>
							</span>
						</label>

					</div>
				</div>

			</section>

			<!-- Section 2: Restriction Settings -->
			<section id="cbr-section-restriction-settings" class="zui-section<?php echo ( 'restriction-settings' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="restriction-settings"<?php echo ( 'restriction-settings' === $cbr_section ) ? '' : ' hidden'; ?>>

				<div class="zui-section-header">
					<div class="zui-section-header__main">
						<span class="zui-section-header__icon">
							<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
						</span>
						<div class="zui-section-header__text">
							<div class="zui-section-header__titlewrap">
								<h2 class="zui-section-header__title"><?php esc_html_e( 'Restriction Settings', 'woo-product-country-base-restrictions' ); ?></h2>
							</div>
							<p class="zui-section-header__sub"><?php esc_html_e( 'Configure the base geolocation parameters and override options for constraints.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
					</div>
					<div class="zui-section-header__actions">
						<span class="zui-savebtn-wrap ast-accordion-btn">
							<button name="save" type="button" class="zui-savebtn cbr-save woocommerce-save-button btn_ast2" value="Save Changes">
								<span class="zui-savebtn__spinner" aria-hidden="true"></span>
								<span class="zui-savebtn__label"><?php esc_html_e( 'Save Changes', 'woo-product-country-base-restrictions' ); ?></span>
							</button>
						</span>
					</div>
				</div>

				<div class="zui-card">
					<!-- FREE: Force Geolocation + Enable Debug Toolbar -->
					<?php $this->get_html_general_setting( $this->get_general_settings() ); ?>

					<!-- PRO: Bypass Restriction for Specific Users -->
					<div class="zui-row border_1 cbr-pro-feature-row">
						<div class="zui-row__head">
							<span class="zui-row__label">
								<?php esc_html_e( 'Bypass Restriction for Specific Users', 'woo-product-country-base-restrictions' ); ?>
								<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Select individual users who should be exempt from all country-based restrictions. These users will see all products and can purchase without restriction regardless of their detected country.', 'woo-product-country-base-restrictions' ); ?>">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
									<span class="zui-tooltip__bubble"><?php esc_html_e( 'Select individual users who should be exempt from all country-based restrictions.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
								</span>
							</span>
						</div>
						<div class="zui-row__control">
							<div class="zui-select-wrap">
								<select id="cbr_bypass_users_pro" class="zui-select select pro_feature" disabled>
									<option value=""><?php esc_html_e( 'Search for users…', 'woo-product-country-base-restrictions' ); ?></option>
								</select>
								<span class="zui-select-chevron">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</span>
							</div>
						</div>
						<span class="zui-pro-feature">
							<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
							<span class="zui-pro-feature__lock" aria-hidden="true">
								<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
							</span>
						</span>
					</div>

					<!-- FREE: Product restriction message + position -->
					<?php $this->get_html_general_setting( $this->get_visibility_message_settings() ); ?>

					<!-- PRO: Cart restriction message -->
					<div class="zui-row border_1 cbr-pro-feature-row">
						<div class="zui-row__head">
							<span class="zui-row__label">
								<?php esc_html_e( 'Cart restriction message', 'woo-product-country-base-restrictions' ); ?>
								<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Displayed on the cart page when a product is removed due to country restrictions.', 'woo-product-country-base-restrictions' ); ?>">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
									<span class="zui-tooltip__bubble"><?php esc_html_e( 'Displayed on the cart page when a product is removed due to country restrictions.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
								</span>
							</span>
						</div>
						<div class="zui-row__control">
							<textarea id="wpcbr_cart_message_pro" rows="3" cols="20" class="zui-input regular-input input-text pro_feature" disabled placeholder="<?php esc_attr_e( '{Product_Name} has been removed from your cart since it is not available for purchase to your Country.', 'woo-product-country-base-restrictions' ); ?>"></textarea>
							<p class="zui-row__hint description"><?php esc_html_e( 'Available variable: {Product_Name}, {Product_name_with_link}', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
						<span class="zui-pro-feature">
							<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
							<span class="zui-pro-feature__lock" aria-hidden="true">
								<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
							</span>
						</span>
					</div>

					<!-- PRO: Category restriction message -->
					<div class="zui-row border_1 cbr-pro-feature-row">
						<div class="zui-row__head">
							<span class="zui-row__label">
								<?php esc_html_e( 'Category restriction message', 'woo-product-country-base-restrictions' ); ?>
								<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Displayed on category/tag pages when restricted.', 'woo-product-country-base-restrictions' ); ?>">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
									<span class="zui-tooltip__bubble"><?php esc_html_e( 'Displayed on category/tag pages when restricted.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
								</span>
							</span>
						</div>
						<div class="zui-row__control">
							<textarea id="cbr_cat_default_message_pro" rows="3" cols="20" class="zui-input regular-input input-text pro_feature" disabled placeholder="<?php esc_attr_e( 'Sorry, products from this category are not available to purchase in your country.', 'woo-product-country-base-restrictions' ); ?>"></textarea>
							<p class="zui-row__hint description"><?php esc_html_e( 'You can use shortcode [cbr_category_message] in your category template.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
						<span class="zui-pro-feature">
							<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
							<span class="zui-pro-feature__lock" aria-hidden="true">
								<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
							</span>
						</span>
					</div>

					<!-- PRO: Product Listing Restriction Message -->
					<div class="zui-row border_1 cbr-pro-feature-row">
						<div class="zui-row__head">
							<span class="zui-row__label">
								<?php esc_html_e( 'Product Listing Restriction Message', 'woo-product-country-base-restrictions' ); ?>
								<span class="zui-tooltip" tabindex="0" role="img" aria-label="<?php esc_attr_e( 'Display a custom message on product listing pages when all products are restricted.', 'woo-product-country-base-restrictions' ); ?>">
									<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
									<span class="zui-tooltip__bubble"><?php esc_html_e( 'Display a custom message on product listing pages when all products are restricted.', 'woo-product-country-base-restrictions' ); ?><span class="zui-tooltip__arrow"></span></span>
								</span>
							</span>
						</div>
						<div class="zui-row__control">
							<textarea id="cbr_catalog_default_message_pro" rows="3" cols="20" class="zui-input regular-input input-text pro_feature" disabled placeholder="<?php esc_attr_e( 'Sorry, products are not available in your country.', 'woo-product-country-base-restrictions' ); ?>"></textarea>
							<p class="zui-row__hint description"><?php esc_html_e( 'Shown when no products are available due to country-based restrictions.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
						<span class="zui-pro-feature">
							<span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
							<span class="zui-pro-feature__lock" aria-hidden="true">
								<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
							</span>
						</span>
					</div>
				</div>

			</section>

			<!-- Section 3: Country Detection Widget (PRO-locked) -->
			<section id="cbr-section-country-detection-widget" class="zui-section<?php echo ( 'country-detection-widget' === $cbr_section ) ? ' is-active' : ''; ?>" data-section="country-detection-widget"<?php echo ( 'country-detection-widget' === $cbr_section ) ? '' : ' hidden'; ?>>

				<div class="zui-section-header">
					<div class="zui-section-header__main">
						<span class="zui-section-header__icon">
							<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
						</span>
						<div class="zui-section-header__text">
							<div class="zui-section-header__titlewrap">
								<h2 class="zui-section-header__title"><?php esc_html_e( 'Country Detection Widget', 'woo-product-country-base-restrictions' ); ?></h2>
							</div>
							<p class="zui-section-header__sub"><?php esc_html_e( 'Configure a visual widget to notify end-users about detected country rules.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
					</div>
					<div class="zui-section-header__actions"></div>
				</div>

				<div class="zui-card customizer cbr-widget-customizer-section">
					<div class="zui-row zui-row--inline border_1 cbr-pro-feature-row">
						<div class="zui-row__head">
							<span class="zui-row__label"><?php esc_html_e( 'Country Detection Widget', 'woo-product-country-base-restrictions' ); ?> <span class="zui-pro-feature"><span class="zui-pro-feature__badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span><span class="zui-pro-feature__lock" aria-hidden="true"><svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span></span></span>
							<p class="zui-row__desc"><?php esc_html_e( 'Customize the country detection widget', 'woo-product-country-base-restrictions' ); ?></p>
							<p class="zui-row__desc zui-row__desc--muted"><?php esc_html_e( 'Show the detected country and allow customers to change their shipping country on any page.', 'woo-product-country-base-restrictions' ); ?></p>
						</div>
						<div class="zui-row__control">
							<span class="row cbr-btn preview-btn">
								<a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRPRO&utm_campaign=widget" class="zui-btn-secondary zui-btn-secondary--locked" target="_blank"><?php esc_html_e( 'Upgrade to PRO', 'woo-product-country-base-restrictions' ); ?></a>
							</span>
						</div>
					</div>
				</div>

			</section>

			<?php wp_nonce_field( 'cbr_setting_form_action', 'cbr_setting_form_nonce_field' ); ?>
			<input type="hidden" name="action" value="cbr_setting_form_update">

		</form>

		<?php
		// ---- Library .zui-upsell panel at the bottom of the Settings tab.
		$cbr_upsell_features = array(
			esc_html__( 'Catalog restriction rules by category', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Payment gateway restrictions by country', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Country detection widget & customizer', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Bypass restrictions for specific users', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Cart & category restriction messages', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Restrict on Place Order (Checkout)', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Bulk restriction management', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Country selector widget for the storefront', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Live preview customizer support', 'woo-product-country-base-restrictions' ),
			esc_html__( 'Priority support & auto-updates', 'woo-product-country-base-restrictions' ),
		);
		$cbr_upsell_url = 'https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRFREE&utm_campaign=UpsellPanel';
		?>
		<section class="zui-upsell" aria-labelledby="cbr-upsell-title">

			<header class="zui-upsell__head">
				<span class="zui-upsell__emblem" aria-hidden="true">
					<svg class="zui-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
				</span>
				<div class="zui-upsell__head-text">
					<span class="zui-upsell__eyebrow"><?php esc_html_e( 'CBR PRO', 'woo-product-country-base-restrictions' ); ?></span>
					<h3 class="zui-upsell__title" id="cbr-upsell-title"><?php esc_html_e( 'Unlock Advanced Country Based Restrictions with CBR PRO', 'woo-product-country-base-restrictions' ); ?></h3>
					<p class="zui-upsell__sub"><?php esc_html_e( 'Upgrade to CBR PRO to extend your country-based restrictions beyond the basics — category rules, payment gateway restrictions, a country selector widget, the live customizer, and more.', 'woo-product-country-base-restrictions' ); ?></p>
				</div>
			</header>

			<ul class="zui-upsell__features">
				<?php foreach ( $cbr_upsell_features as $cbr_feat ) : ?>
					<li class="zui-upsell__feature">
						<span class="zui-upsell__check" aria-hidden="true">
							<svg class="zui-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
						</span>
						<span class="zui-upsell__label"><?php echo esc_html( $cbr_feat ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<footer class="zui-upsell__foot">
				<div class="zui-upsell__offer">
					<span class="zui-upsell__offer-label"><?php esc_html_e( 'Launch offer', 'woo-product-country-base-restrictions' ); ?></span>
					<span class="zui-upsell__offer-body">
						<?php esc_html_e( 'Get 20% off — use code', 'woo-product-country-base-restrictions' ); ?>
						<span class="zui-upsell__code">CBRPRO20</span>
						<?php esc_html_e( 'at checkout.', 'woo-product-country-base-restrictions' ); ?>
					</span>
					<span class="zui-upsell__offer-note">★ <?php esc_html_e( 'for new customers only', 'woo-product-country-base-restrictions' ); ?></span>
				</div>
				<a class="zui-upsell__cta" href="<?php echo esc_url( $cbr_upsell_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Upgrade to CBR PRO', 'woo-product-country-base-restrictions' ); ?> <span aria-hidden="true">&rarr;</span>
				</a>
			</footer>

		</section>

	</main>

</div>
</div>
