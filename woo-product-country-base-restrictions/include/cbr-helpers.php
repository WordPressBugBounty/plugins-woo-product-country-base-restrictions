<?php
/**
 * Shared helpers for CBR Free — the in-plugin upgrade UTM convention.
 *
 * Mirrors the AST Free pattern: every outbound sales link in the plugin goes
 * through one builder so placements can't drift into separate custom mediums
 * again (CBRFREE / CBR / CBRPRO were three mediums doing the same job, which is
 * why those sessions landed in GA4's "Unassigned" bucket).
 *
 * @package WooProductCountryBaseRestrictions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the in-plugin UTM convention to a zorem.com link.
 *
 * Source and medium are FIXED — GA4's "In-Plugin" channel group matches on
 * them — and the placement goes in utm_content, never in medium.
 *
 * @param string $url      Destination URL.
 * @param string $content  Placement slug, unique per link location (e.g. plugin-setting).
 * @param string $campaign Campaign slug.
 * @return string
 */
if ( ! function_exists( 'cbr_utm_url' ) ) {
	function cbr_utm_url( $url, $content, $campaign = 'upgrade-to-pro' ) {
		return add_query_arg(
			array(
				'utm_source'   => 'cbr-free',
				'utm_medium'   => 'in-plugin',
				'utm_campaign' => $campaign,
				'utm_content'  => $content,
			),
			$url
		);
	}
}

/**
 * Country Based Restrictions Pro product-page link for one upgrade placement.
 *
 * NOTE: verify this URL does not 301-redirect and strip the query string — if
 * it does (as the ALP product alias did), point it at the live/canonical
 * product URL instead so the UTM params survive. The GA4 item name comes from
 * the product itself, not the slug.
 *
 * @param string $content Placement slug for utm_content.
 * @return string
 */
if ( ! function_exists( 'cbr_pro_upgrade_url' ) ) {
	function cbr_pro_upgrade_url( $content ) {
		return cbr_utm_url( 'https://www.zorem.com/product/country-based-restriction-pro/', $content );
	}
}
