<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="zui-tab-panel" id="cbr_content5" data-tab="catalog-restrictions"<?php echo ( isset( $active_tab ) && 'catalog-restrictions' === $active_tab ) ? '' : ' hidden'; ?>>
<div class="zui-layout zui-layout--full">
<main class="zui-content zui-content--full">
	<section id="cbr-section-catalog-restrictions" class="zui-section is-active" data-section="catalog-restrictions">

		<div class="zui-card cbr-pro-locked-card">
			<div class="cbr-pro-locked-card__overlay">
				<h3 class="cbr-pro-locked-card__title">
					<?php esc_html_e( 'Catalog Restrictions', 'woo-product-country-base-restrictions' ); ?>
					<span class="cbr-pro-badge"><?php esc_html_e( 'PRO', 'woo-product-country-base-restrictions' ); ?></span>
					<span class="cbr-pro-lock"><span class="dashicons dashicons-lock"></span></span>
				</h3>
				<p class="cbr-pro-locked-card__desc"><?php esc_html_e( 'Create bulk restriction rules to restrict products by categories, tags, attributes, and shipping classes for specific countries. Upgrade to PRO to unlock this feature.', 'woo-product-country-base-restrictions' ); ?></p>
				<a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRPRO&utm_campaign=catalog-restrictions" class="zui-btn-locked" target="_blank"><?php esc_html_e( 'Upgrade To PRO', 'woo-product-country-base-restrictions' ); ?></a>
			</div>
		</div>

		<!-- Locked preview of sample rules -->
		<div class="cbr-pro-locked-preview">
			<div class="accordion zui-list-row cbr-pro-sample-row">
				<div class="br_cbr_row_title">
					<span class="br_cbr_row_title__name"><?php esc_html_e( 'Restrictions Rule 1', 'woo-product-country-base-restrictions' ); ?></span>
					<span class="br_cbr_row_title__meta"><?php esc_html_e( 'By Categories', 'woo-product-country-base-restrictions' ); ?></span>
				</div>
				<div class="cbr-pro-sample-row__icons">
					<span class="dashicons dashicons-menu"></span>
					<span class="dashicons dashicons-no-alt"></span>
					<span class="dashicons dashicons-admin-page"></span>
				</div>
			</div>
			<div class="accordion zui-list-row cbr-pro-sample-row">
				<div class="br_cbr_row_title">
					<span class="br_cbr_row_title__name"><?php esc_html_e( 'Restrictions Rule 2', 'woo-product-country-base-restrictions' ); ?></span>
					<span class="br_cbr_row_title__meta"><?php esc_html_e( 'By Tags', 'woo-product-country-base-restrictions' ); ?></span>
				</div>
				<div class="cbr-pro-sample-row__icons">
					<span class="dashicons dashicons-menu"></span>
					<span class="dashicons dashicons-no-alt"></span>
					<span class="dashicons dashicons-admin-page"></span>
				</div>
			</div>
			<div class="cbr-pro-locked-preview__action">
				<button type="button" class="zui-btn-secondary cbr-pro-locked-btn" disabled><?php esc_html_e( 'Add New Restriction Rule', 'woo-product-country-base-restrictions' ); ?> <span class="dashicons dashicons-plus"></span></button>
			</div>
		</div>

	</section>
</main>
</div>
</div>
