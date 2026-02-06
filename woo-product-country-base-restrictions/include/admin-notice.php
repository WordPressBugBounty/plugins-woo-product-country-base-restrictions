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
		add_action( 'admin_init', array( $this, 'cbr_afws_notice_ignore_cb' ) );

		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		
		if ( 'woocommerce-product-country-base-restrictions' != $page && is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			// Analytics for WooCommerce Subscriptions Notice
			add_action( 'admin_notices', array( $this, 'cbr_afws_admin_notice_376' ) );
			
		}
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );
	}

	public function cbr_settings_admin_notice() {
		include 'views/admin_message_panel.php';
	}

		/*
	* Dismiss admin notice for trackship
	*/
	public function cbr_afws_notice_ignore_cb() {
		if ( isset( $_GET['cbr-afws-update-notice-376'] ) ) {
			if (isset($_GET['nonce'])) {
				$nonce = sanitize_text_field($_GET['nonce']);
				if (wp_verify_nonce($nonce, 'cbr_afws_dismiss_notice_376')) {
					update_option('cbr_afws_update_ignore_376', 'true');
				}
			}
		}
	}

	/*
	* Display admin notice on plugin install or update
	*/
	public function cbr_afws_admin_notice_376() {
		
		if ( get_option('cbr_afws_update_ignore_376') ) {
			return;
		}
		
		$nonce = wp_create_nonce('cbr_afws_dismiss_notice_376');
		$dismissable_url = esc_url(add_query_arg(['cbr-afws-update-notice-376' => 'true', 'nonce' => $nonce]));

		?>
		<style>		
		.wp-core-ui .notice.cbr-afws-dismissable-notice{
			position: relative;
			padding-right: 38px;
			border-left-color: #3b64d3;
		}
		.wp-core-ui .notice.cbr-afws-dismissable-notice h3{
			margin-bottom: 5px;
		} 
		.wp-core-ui .notice.cbr-afws-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.cbr_afws_notice_btn_376 {
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
		.cbr-afws-dismissable-notice strong{
			font-weight:bold;
		}
		</style>
		<div class="notice updated notice-success cbr-afws-dismissable-notice">
			<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
			<h2>🚀 Introducing Analytics for WooCommerce Subscriptions</h2>
			<p>Get powerful insights with <a href="https://woocommerce.com/products/analytics-for-woocommerce-subscriptions/">Analytics for WooCommerce Subscriptions</a> — the all-in-one dashboard to track signups, renewals, cancellations, and recurring revenue.</p>
			
			<p>Discover which products and customers drive the most value, reduce churn, and grow your subscription income with data-driven decisions.</p>
			<a class="button-primary cbr_afws_notice_btn_376" target="blank" href="https://woocommerce.com/products/analytics-for-woocommerce-subscriptions/">👉 Learn More on WooCommerce.com</a>
			<a class="button-primary cbr_afws_notice_btn_376" href="<?php esc_html_e( $dismissable_url ); ?>">Dismiss</a>
		</div>
		<?php
	}
	
}

