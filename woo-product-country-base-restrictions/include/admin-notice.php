<?php
/**
 * CBR Setting 
 *
 * @class   CBR_Admin_Notice
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_Admin_Notice class
 *
 * @since 1.0.0
 */
class CBR_Admin_Notice {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return CBR_Admin_Notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	* construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->init();
	}

	/*
	* init function
	*
	* @since 1.0.0
	*/
	public function init() {
		add_action( 'admin_init', array( $this, 'cbr_pro_notice_ignore_cb_381' ) );

		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if ( 'woocommerce-product-country-base-restrictions' != $page ) {
			// Analytics for WooCommerce Subscriptions Notice
			add_action( 'admin_notices', array( $this, 'cbr_pro_admin_notice_381' ) );

		}
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );

		// Review request notice (ZUI plugin-notice card)
		// add_action( 'admin_init', array( $this, 'cbr_free_review_notice_ignore' ) );
		// add_action( 'admin_notices', array( $this, 'cbr_free_review_notice' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'cbr_free_review_notice_styles' ) );
	}

	/*
	* Dismiss the review notice
	*/
	public function cbr_free_review_notice_ignore() {
		if ( isset( $_GET['cbr-free-review-ignore-notice'] ) && isset( $_GET['nonce'] ) ) {
			$nonce = sanitize_text_field( $_GET['nonce'] );
			if ( wp_verify_nonce( $nonce, 'cbr_free_review_notice' ) ) {
				update_option( 'cbr_free_review_notice_ignore', 'true' );
			}
		}
	}

	/*
	* ZUI plugin-notice styles — the review notice renders from `admin_notices`
	* on every admin page, outside `.zui-scope`, so the standalone component
	* stylesheet is enqueued here (it has no dependency on the zui.css bundle).
	*/
	public function cbr_free_review_notice_styles() {
		global $fzpcr;

		if ( get_option( 'cbr_free_review_notice_ignore' ) ) {
			return;
		}

		$zui_version_file = $fzpcr->get_plugin_path() . '/assets/zui/VERSION';
		$zui_version      = is_readable( $zui_version_file ) ? trim( file_get_contents( $zui_version_file ) ) : $fzpcr->version;

		wp_enqueue_style( 'cbr-zui-pnotice', $fzpcr->plugin_dir_url() . 'assets/zui/css/components/plugin-notice.css', array(), $zui_version );
	}

	/*
	* Display the review request notice (ZUI plugin-notice card)
	*/
	public function cbr_free_review_notice() {

		if ( get_option( 'cbr_free_review_notice_ignore' ) ) {
			return;
		}

		$nonce           = wp_create_nonce( 'cbr_free_review_notice' );
		$dismissable_url = esc_url( add_query_arg( array( 'cbr-free-review-ignore-notice' => 'true', 'nonce' => $nonce ) ) );
		?>
		<div class="zui-pnotice" role="status">
			<span class="zui-pnotice__avatar" aria-hidden="true">
				<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
			</span>
			<div class="zui-pnotice__body">
				<strong class="zui-pnotice__title">⭐ <?php esc_html_e( 'Enjoying Country Based Restrictions? Leave Us a Review!', 'woo-product-country-base-restrictions' ); ?></strong>
				<p class="zui-pnotice__text"><?php esc_html_e( 'We hope Country Based Restrictions has given you the control you need over what customers see and buy in each country! Your feedback helps us grow and continue improving the plugin.', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( 'If you love using CBR, we\'d really appreciate it if you could take a moment to leave us a 5-star review. It helps us keep improving and providing the best experience for you!', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><strong>👍 <?php esc_html_e( 'Support CBR & Share Your Experience!', 'woo-product-country-base-restrictions' ); ?></strong></p>
				<div class="zui-pnotice__actions">
					<a class="zui-pnotice__btn" href="https://wordpress.org/support/plugin/woo-product-country-base-restrictions/reviews/#new-post" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'Ok, you deserve it', 'woo-product-country-base-restrictions' ); ?></a>
					<a class="zui-pnotice__btn zui-pnotice__btn--ghost" href="<?php echo esc_url( $dismissable_url ); ?>"><?php esc_html_e( 'I already did', 'woo-product-country-base-restrictions' ); ?></a>
					<a class="zui-pnotice__link" href="<?php echo esc_url( $dismissable_url ); ?>"><?php esc_html_e( 'Nope, maybe later', 'woo-product-country-base-restrictions' ); ?></a>
				</div>
			</div>
			<a class="zui-pnotice__close" href="<?php echo esc_url( $dismissable_url ); ?>" aria-label="<?php esc_attr_e( 'Dismiss', 'woo-product-country-base-restrictions' ); ?>">&times;</a>
		</div>
		<?php
	}

	public function cbr_settings_admin_notice() {
		include 'views/admin_message_panel.php';
	}

		/*
	* Dismiss admin notice for trackship
	*/
	public function cbr_pro_notice_ignore_cb_381() {
		if ( isset( $_GET['cbr-pro-update-notice-381'] ) ) {
			if (isset($_GET['nonce'])) {
				$nonce = sanitize_text_field($_GET['nonce']);
				if (wp_verify_nonce($nonce, 'cbr_pro_dismiss_notice_381')) {
					update_option('cbr_pro_update_ignore_381', 'true');
				}
			}
		}
	}

	/*
	* Display admin notice on plugin install or update
	*/
	public function cbr_pro_admin_notice_381() {
		
		if ( get_option('cbr_pro_update_ignore_381') ) {
			return;
		}
		
		$nonce = wp_create_nonce('cbr_pro_dismiss_notice_381');
		$dismissable_url = esc_url(add_query_arg(['cbr-pro-update-notice-381' => 'true', 'nonce' => $nonce]));

		?>
		<style>		
		.wp-core-ui .notice.cbr-pro-dismissable-notice-381 {
			position: relative;
			padding-right: 38px;
			border-left-color: #3b64d3;
		}
		.wp-core-ui .notice.cbr-pro-dismissable-notice-381 h3{
			margin-bottom: 5px;
		} 
		.wp-core-ui .notice.cbr-pro-dismissable-notice-381 a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.cbr_pro_notice_btn_381 {
			background: #3b64d3;
			color: #fff;
			border-color: #3b64d3;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 10px;
		}
		.cbr-pro-dismissable-notice-381 strong{
			font-weight:bold;
		}
		</style>
		<div class="notice updated notice-success cbr-pro-dismissable-notice-381">
			<a href="<?php echo $dismissable_url; ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
			<h2>🌍 Upgrade to Country Based Restrictions PRO – Gain Full Control Over Who Sees What</h2>
			<p>Get full control over who sees what:</p>
			<ul>
				<li>✅ Restrict by country, category, tag, attribute & shipping class</li>
				<li>✅ Hide prices, payment gateways & checkout options by location</li>
				<li>✅ Restrict individual product variations</li>
				<li>✅ Country detection widget + CSV bulk import</li>
				<li>✅ Redirects, custom messages & debug mode</li>
			</ul>
			<p>🎁 20% OFF with code CBRPRO20 — limited time!</p>
			<p>
				<a href="https://www.zorem.com/product/country-based-restriction-pro/" class="button-primary cbr_pro_notice_btn_381">👉 Upgrade to CBR PRO</a>
				<a class="button-primary cbr_pro_notice_btn_381" href="<?php echo $dismissable_url; ?>">Dismiss</a>
			</p>
		</div>
		<?php
	}
	
}

