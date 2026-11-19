<?php
// This template is include()d from inside a class method, so the variables
// below are function-scoped, not globals. PHPCS analyses the file in isolation
// and cannot see that, hence the false positives.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
/**
 * Black Friday Country Based Restrictions Pro banner (settings page).
 *
 * Left: dark "Black Friday 40% off CBR PRO" block. Middle: headline and the
 * offer line (coupon, price, terms, end date). Right: the upgrade CTA.
 * Not dismissible. The caller only includes it while the sale is running,
 * so it disappears on its own after the sale ends.
 *
 * @package WooProductCountryBaseRestrictions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cbr_offer_url = CBR_Admin_Notice::cbr_get_bfcm_url( 'bfcm-settings-banner' );
$cbr_offer_pct = rtrim( CBR_Admin_Notice::BFCM_DISCOUNT, '%' );
?>
<section class="cbr-offer cbr-offer--bfcm" id="cbr-offer-banner" aria-labelledby="cbr-offer-title">
	<div class="cbr-offer__badge">
		<span class="cbr-offer__save"><?php esc_html_e( 'Black Friday', 'woo-product-country-base-restrictions' ); ?></span>
		<span class="cbr-offer__pct"><?php echo esc_html( $cbr_offer_pct ); ?><small>%</small></span>
		<span class="cbr-offer__on"><?php esc_html_e( 'off CBR PRO', 'woo-product-country-base-restrictions' ); ?></span>
	</div>

	<div class="cbr-offer__body">
		<h3 class="cbr-offer__title" id="cbr-offer-title"><?php echo esc_html( CBR_Admin_Notice::cbr_get_bfcm_title() ); ?></h3>
		<?php CBR_Admin_Notice::render_cbr_bfcm_meta(); ?>
	</div>

	<div class="cbr-offer__action">
		<a class="zui-btn-primary cbr-offer__cta" href="<?php echo esc_url( $cbr_offer_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( CBR_Admin_Notice::cbr_get_bfcm_button_label() ); ?></a>
	</div>
</section>
