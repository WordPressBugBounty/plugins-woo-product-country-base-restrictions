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

	/** Dismissing the first-restriction card snoozes it for this long rather than hiding it forever. */
	const FIRST_RESTRICTION_SNOOZE = 60 * DAY_IN_SECONDS;

	/** Screens the first-restriction card may appear on (where restriction work happens). */
	const PRODUCT_SCREENS = array( 'edit-product', 'product' );

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
		add_action( 'admin_init', array( $this, 'cbr_pro_notice_ignore_cb_382' ) );

		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if ( 'woocommerce-product-country-base-restrictions' != $page ) {
			// Upgrade-to-PRO notice (ZUI plugin-notice card).
			add_action( 'admin_notices', array( $this, 'cbr_pro_admin_notice_382' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'cbr_pro_admin_notice_382_styles' ), 20 );

		}
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );

		// Second upgrade trigger at a moment of value: once the merchant has
		// created their first country restriction rule, on the product screens.
		add_action( 'woocommerce_process_product_meta', array( $this, 'cbr_track_first_restriction' ), 20 );
		add_action( 'admin_notices', array( $this, 'cbr_first_restriction_notice' ) );
		add_action( 'admin_init', array( $this, 'cbr_handle_first_restriction_dismiss' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'cbr_first_restriction_notice_styles' ), 20 );

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
	public function cbr_pro_notice_ignore_cb_382() {
		if ( isset( $_GET['cbr-pro-update-notice-382'] ) ) {
			if (isset($_GET['nonce'])) {
				$nonce = sanitize_text_field($_GET['nonce']);
				if (wp_verify_nonce($nonce, 'cbr_pro_dismiss_notice_382')) {
					update_option('cbr_pro_update_ignore_382', 'true');
				}
			}
		}
	}

	/*
	* ZUI plugin-notice styles for the PRO upgrade card. It renders from
	* `admin_notices` on non-settings admin pages (outside `.zui-scope`), so the
	* standalone component stylesheet is enqueued here.
	*/
	public function cbr_pro_admin_notice_382_styles() {
		global $fzpcr;
		if ( get_option( 'cbr_pro_update_ignore_382' ) ) {
			return;
		}
		$zui_version_file = $fzpcr->get_plugin_path() . '/assets/zui/VERSION';
		$zui_version      = is_readable( $zui_version_file ) ? trim( file_get_contents( $zui_version_file ) ) : $fzpcr->version;
		wp_enqueue_style( 'cbr-zui-pnotice', $fzpcr->plugin_dir_url() . 'assets/zui/css/components/plugin-notice.css', array(), $zui_version );
	}

	/*
	* Display admin notice on plugin install or update (ZUI plugin-notice card).
	*/
	public function cbr_pro_admin_notice_382() {

		if ( get_option( 'cbr_pro_update_ignore_382' ) ) {
			return;
		}

		$nonce           = wp_create_nonce( 'cbr_pro_dismiss_notice_382' );
		$dismissable_url = esc_url( add_query_arg( array( 'cbr-pro-update-notice-382' => 'true', 'nonce' => $nonce ) ) );
		?>
		<div class="zui-pnotice" id="cbr-pro-upgrade-notice" role="status">
			<span class="zui-pnotice__avatar" aria-hidden="true">
				<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
			</span>
			<div class="zui-pnotice__body">
				<strong class="zui-pnotice__title"><?php esc_html_e( '🌍 Upgrade to Country Based Restrictions PRO – Gain Full Control Over Who Sees What', 'woo-product-country-base-restrictions' ); ?></strong>
				<p class="zui-pnotice__text"><?php esc_html_e( 'Get full control over who sees what:', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( '✅ Restrict by country, category, tag, attribute & shipping class', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( '✅ Hide prices, payment gateways & checkout options by location', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( '✅ Restrict individual product variations', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( '✅ Country detection widget + CSV bulk import', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php esc_html_e( '✅ Redirects, custom messages & debug mode', 'woo-product-country-base-restrictions' ); ?></p>
				<p class="zui-pnotice__text"><?php echo wp_kses_post( __( '🎁 <strong>20% OFF</strong> with code <strong>CBRPRO20</strong> — limited time!', 'woo-product-country-base-restrictions' ) ); ?></p>
				<div class="zui-pnotice__actions">
					<a class="zui-pnotice__btn" href="<?php echo esc_url( cbr_pro_upgrade_url( 'admin-notice' ) ); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e( '👉 Upgrade to CBR PRO', 'woo-product-country-base-restrictions' ); ?></a>
					<a class="zui-pnotice__link" href="<?php echo esc_url( $dismissable_url ); ?>"><?php esc_html_e( 'Dismiss', 'woo-product-country-base-restrictions' ); ?></a>
				</div>
			</div>
			<a class="zui-pnotice__close" href="<?php echo esc_url( $dismissable_url ); ?>" aria-label="<?php esc_attr_e( 'Dismiss', 'woo-product-country-base-restrictions' ); ?>">&times;</a>
		</div>
		<?php
	}

	/**
	 * Flag the first time a merchant saves a country restriction rule on a
	 * product. Runs inside WooCommerce's product save (which verifies its own
	 * nonce). Only the first rule flips the flag; after that it's a no-op.
	 *
	 * @param int $post_id Product ID being saved.
	 */
	public function cbr_track_first_restriction( $post_id ) {
		if ( get_option( 'cbr_first_restriction_created' ) ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- fires within WC's product save, which has already verified its nonce.
		$type = isset( $_POST['_fz_country_restriction_type'] ) ? sanitize_text_field( wp_unslash( $_POST['_fz_country_restriction_type'] ) ) : '';
		if ( in_array( $type, array( 'specific', 'excluded' ), true ) ) {
			update_option( 'cbr_first_restriction_created', time(), false );
		}
	}

	/**
	 * The first-restriction card shows only on the product screens, to shop
	 * managers, once a rule exists and the snooze has lapsed.
	 */
	private function cbr_first_restriction_notice_visible() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		return $screen && in_array( $screen->id, self::PRODUCT_SCREENS, true )
			&& current_user_can( 'manage_woocommerce' )
			&& get_option( 'cbr_first_restriction_created' )
			&& (int) get_option( 'cbr_first_restriction_notice_snoozed_until', 0 ) < time();
	}

	/**
	 * Dismiss snoozes for FIRST_RESTRICTION_SNOOZE rather than hiding forever.
	 */
	public function cbr_handle_first_restriction_dismiss() {
		if ( isset( $_GET['cbr-first-restriction-snooze'], $_GET['nonce'] )
			&& wp_verify_nonce( sanitize_key( wp_unslash( $_GET['nonce'] ) ), 'cbr_first_restriction_snooze' ) ) {
			update_option( 'cbr_first_restriction_notice_snoozed_until', time() + self::FIRST_RESTRICTION_SNOOZE, false );
		}
	}

	/**
	 * Load the .zui-pnotice component only where the card renders.
	 */
	public function cbr_first_restriction_notice_styles() {
		global $fzpcr;
		if ( ! $this->cbr_first_restriction_notice_visible() ) {
			return;
		}
		$zui_version_file = $fzpcr->get_plugin_path() . '/assets/zui/VERSION';
		$zui_version      = is_readable( $zui_version_file ) ? trim( file_get_contents( $zui_version_file ) ) : $fzpcr->version;
		wp_enqueue_style( 'cbr-zui-pnotice', $fzpcr->plugin_dir_url() . 'assets/zui/css/components/plugin-notice.css', array(), $zui_version );
	}

	/**
	 * Second upgrade trigger — shown at a moment of value (the merchant just set
	 * up their first restriction) rather than during setup. Dismissible (snooze).
	 */
	public function cbr_first_restriction_notice() {
		if ( ! $this->cbr_first_restriction_notice_visible() ) {
			return;
		}

		$upgrade_url = cbr_pro_upgrade_url( 'first-restriction' );
		$nonce       = wp_create_nonce( 'cbr_first_restriction_snooze' );
		$dismiss_url = add_query_arg( array( 'cbr-first-restriction-snooze' => 'true', 'nonce' => $nonce ) );
		?>
		<div class="zui-pnotice" id="cbr-first-restriction-notice" role="status">
			<span class="zui-pnotice__avatar" aria-hidden="true">
				<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
			</span>
			<div class="zui-pnotice__body">
				<strong class="zui-pnotice__title">🌍 <?php esc_html_e( 'Your first country restriction is live', 'woo-product-country-base-restrictions' ); ?></strong>
				<p class="zui-pnotice__text"><?php esc_html_e( 'Country Based Restrictions Pro takes it further: restrict by category, tag, attribute & shipping class; hide prices and payment gateways by location; restrict individual variations; bulk CSV import; redirects and a country-detection widget.', 'woo-product-country-base-restrictions' ); ?></p>
				<div class="zui-pnotice__actions">
					<a class="zui-pnotice__btn" href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'See what Pro unlocks', 'woo-product-country-base-restrictions' ); ?></a>
					<a class="zui-pnotice__link" href="<?php echo esc_url( $dismiss_url ); ?>"><?php esc_html_e( 'Not now', 'woo-product-country-base-restrictions' ); ?></a>
				</div>
			</div>
			<a class="zui-pnotice__close" href="<?php echo esc_url( $dismiss_url ); ?>" aria-label="<?php esc_attr_e( 'Dismiss', 'woo-product-country-base-restrictions' ); ?>">&times;</a>
		</div>
		<?php
	}

}

