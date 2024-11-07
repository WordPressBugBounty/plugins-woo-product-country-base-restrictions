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
		add_action('admin_init', array( $this, 'cbr_pro_plugin_notice_ignore' ) );
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );

		add_action( 'admin_notices', array( $this, 'cbr_return_for_woocommerce_notice' ) );
		add_action( 'admin_init', array( $this, 'cbr_return_for_woocommerce_notice_ignore' ) );
	}

	/*
	* Dismiss admin notice for return
	*/
	public function cbr_return_for_woocommerce_notice_ignore() {
		if ( isset( $_GET['cbr-return-for-woocommerce-notice'] ) ) {
			
			if (isset($_GET['nonce'])) {
				$nonce = sanitize_text_field($_GET['nonce']);
				if (wp_verify_nonce($nonce, 'cbr_return_for_woocommerce_dismiss_notice')) {
					update_option('cbr_return_for_woocommerce_notice_ignore', 'true');
				}
			}
			
		}
	}

	/*
	* Display admin notice on plugin install or update
	*/
	public function cbr_return_for_woocommerce_notice() { 		
		
		$return_installed = ( function_exists( 'zorem_returns_exchanges' ) ) ? true : false;
		if ( $return_installed ) {
			return;
		}
		
		if ( get_option('cbr_return_for_woocommerce_notice_ignore') ) {
			return;
		}	
		
		$nonce = wp_create_nonce('cbr_return_for_woocommerce_dismiss_notice');
		$dismissable_url = esc_url(add_query_arg(['cbr-return-for-woocommerce-notice' => 'true', 'nonce' => $nonce]));

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
			margin: 5px 0 15px;
		}
		.cbr-dismissable-notice strong{
			font-weight: bold;
		}
		</style>
		<div class="notice updated notice-success cbr-dismissable-notice">			
			<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
			<h3>Launching Zorem Returns!</h3>
			<p>We’re thrilled to announce the launch of our new <a href="<?php echo esc_url( 'https://www.zorem.com/product/zorem-returns/' ); ?>"><strong>Zorem Returns Plugin!</strong></a> This powerful tool is designed to streamline and automate your returns and exchanges management process, freeing up your time to focus on what truly matters—growing your business.</p>

			<p><strong>Act fast!</strong> For a limited time, you can enjoy an exclusive <strong>40% discount</strong> on Zorem Returns Plugin with the coupon code <strong>RETURNS40</strong>. Don’t miss out—the offer expires 2 weeks after installing this plugin or update.</p>
			
			<a class="button-primary alp_notice_btn" target="blank" href="<?php echo esc_url( 'https://www.zorem.com/product/zorem-returns/' ); ?>">Unlock 40% Off</a>
			<a class="button-primary alp_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">Dismiss</a>				
		</div>	
		<?php 				
	}

	/**
	 * CBR pro admin notice ignore
	 *
	 * @since 1.0.0
	 */
	public function cbr_pro_plugin_notice_ignore() {

		if (isset($_GET['cbr-pro-plugin-ignore-notice'])) {
			$nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';
			if (isset($nonce) && wp_verify_nonce($nonce, 'cbr_dismiss_notice')) {
				set_transient( 'cbr_pro_admin_notice_ignore', 'yes', 2592000 );
			}
		}
	}

	public function cbr_settings_admin_notice() {

		$ignore = get_transient( 'cbr_pro_admin_notice_ignore' );
		if ( 'yes' == $ignore ) {
			return;
		}

		include 'views/admin_message_panel.php';
	}
	
}

