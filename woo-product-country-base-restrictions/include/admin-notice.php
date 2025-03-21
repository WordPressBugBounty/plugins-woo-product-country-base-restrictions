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
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );

		add_action('admin_notices', array( $this, 'cbr_admin_upgrade_notice' ) );
		add_action( 'admin_init', array( $this, 'cbr_notice_ignore' ) );
	}

	public function cbr_settings_admin_notice() {
		include 'views/admin_message_panel.php';
	}

	/*
	* Dismiss admin notice for cbr
	*/
	public function cbr_notice_ignore() {
		if ( isset( $_GET['cbr-notice-dismiss'] ) ) {
			
			if (isset($_GET['nonce'])) {
				$nonce = sanitize_text_field($_GET['nonce']);
				if (wp_verify_nonce($nonce, 'cbr_dismiss_notice')) {
					update_option('cbr_notice_ignore', 'true');
				}
			}
			
		}
	}

	public function cbr_admin_upgrade_notice() {
		// Get the current admin page
		$screen = get_current_screen();
		
		// Exclude notice from a specific page (replace 'cbr_plugin_page' with your actual page slug)
		if (isset($_GET['page']) && $_GET['page'] === 'woocommerce-product-country-base-restrictions') {
			return;
		}

		if ( get_option('cbr_notice_ignore') ) {
			return;
		}	

		$nonce = wp_create_nonce('cbr_dismiss_notice');
		$dismissable_url = esc_url(add_query_arg(['cbr-notice-dismiss' => 'true', 'nonce' => $nonce]));
	
		?>
		<style>		
		.wp-core-ui .notice.cbr-dismissable-notice{
			position: relative;
			padding-right: 38px;
			border-left-color: #005B9A;
		}
		.wp-core-ui .notice.cbr-dismissable-notice h3{
			margin-bottom: 5px;
		} 
		.wp-core-ui .notice.cbr-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.cbr_notice_btn {
			background: #005B9A;
			color: #fff;
			border-color: #005B9A;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 0;
		}
		.cbr-dismissable-notice strong{
			font-weight: bold;
		}
		</style>
		<div class="notice updated notice-success cbr-dismissable-notice">
			<a href="<?php echo $dismissable_url; ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
			<h2>🌍 Expand Your Control with Country Based Restrictions PRO!</h2>
			<p>Upgrade to <strong><a href="https://www.zorem.com/product/country-based-restriction-pro/">Country Based Restrictions PRO</a></strong> and easily restrict products and payment gateways by country, apply bulk restriction rules, and customize the shopping experience for different regions.</p>
			<p><strong>🎉 Get 20% Off*!</strong> Use code <strong>CBRPRO20</strong> at checkout.</p>
			<p>
				<a href="https://www.zorem.com/product/country-based-restriction-pro/" class="button-primary cbr_notice_btn">Upgrade Now</a>
				<a class="button-primary cbr_notice_btn" href="<?php echo $dismissable_url; ?>">Dismiss</a>
			</p>
			<p><strong>★</strong> for new customers only</p>
		</div>
		<?php
	}	
	
}

