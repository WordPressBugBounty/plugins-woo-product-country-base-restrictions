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

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound -- Established public class name; renaming breaks existing installs and the CBR Pro bridge.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only $_GET['page'] screen check in init(); the dismiss handlers verify their own nonce via wp_verify_nonce() before any state change.
// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Values are sanitized page slug / nonce tokens; nonce tokens are additionally validated by wp_verify_nonce().

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

	/** First moment of the Black Friday sale (site timezone). */
	const BFCM_START = '2026-11-19 00:00:00';

	/** Last moment of the Black Friday sale (site timezone, inclusive). */
	const BFCM_END = '2026-12-01 23:59:59';

	/** Black Friday coupon code. */
	const BFCM_COUPON = 'BFCM40';

	/** Black Friday discount, as shown to the customer. */
	const BFCM_DISCOUNT = '40%';

	/** Regular Country Based Restrictions Pro single-site yearly price (zorem.com). */
	const BFCM_REGULAR_PRICE = '$119';

	/** Country Based Restrictions Pro price after the Black Friday discount. */
	const BFCM_SALE_PRICE = '$71.40';

	/** User meta key set when a user dismisses the Black Friday notice. */
	const BFCM_DISMISSED_META = 'cbr_bfcm_2026_notice_dismissed';

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

		// Black Friday 2026 Country Based Restrictions Pro sale (ZUI plugin notice, dismissible per user).
		add_action( 'admin_enqueue_scripts', array( $this, 'cbr_bfcm_notice_styles' ), 20 );
		add_action( 'admin_notices', array( $this, 'cbr_bfcm_notice' ) );
		add_action( 'admin_init', array( $this, 'cbr_bfcm_notice_ignore' ) );

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
		// The Black Friday notice replaces this nudge while the sale runs.
		if ( self::cbr_is_bfcm_running() || get_option( 'cbr_pro_update_ignore_382' ) ) {
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

		// The Black Friday notice replaces this nudge while the sale runs.
		if ( self::cbr_is_bfcm_running() ) {
			return;
		}

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

	/**
	 * Black Friday sale end as a Unix timestamp.
	 *
	 * @return int
	 */
	public static function cbr_get_bfcm_end_ts() {
		return ( new DateTimeImmutable( self::BFCM_END, wp_timezone() ) )->getTimestamp();
	}

	/**
	 * Whether the Black Friday sale is running right now.
	 *
	 * @return bool
	 */
	public static function cbr_is_bfcm_running() {
		$cbr_start = ( new DateTimeImmutable( self::BFCM_START, wp_timezone() ) )->getTimestamp();
		$cbr_now   = time();

		return $cbr_now >= $cbr_start && $cbr_now <= self::cbr_get_bfcm_end_ts();
	}

	/**
	 * Country Based Restrictions Pro link for a Black Friday placement.
	 *
	 * @param string $placement Placement slug for utm_content.
	 * @return string
	 */
	public static function cbr_get_bfcm_url( $placement ) {
		return cbr_utm_url( 'https://www.zorem.com/product/country-based-restriction-pro/', $placement, 'black-friday-2026' );
	}

	/**
	 * Upgrade button label, e.g. "Upgrade → $71.40".
	 *
	 * @return string
	 */
	public static function cbr_get_bfcm_button_label() {
		/* translators: %s: Country Based Restrictions Pro price after the Black Friday discount, e.g. $71.40. */
		$cbr_label = __( 'Upgrade → %s', 'woo-product-country-base-restrictions' );
		return sprintf( $cbr_label, self::BFCM_SALE_PRICE );
	}

	/**
	 * Black Friday headline.
	 *
	 * @return string
	 */
	public static function cbr_get_bfcm_title() {
		/* translators: %s: discount, e.g. 40%. */
		$cbr_title = __( 'Black Friday: %s off Country Based Restrictions Pro — only sale of the year', 'woo-product-country-base-restrictions' );
		return sprintf( $cbr_title, self::BFCM_DISCOUNT );
	}

	/**
	 * Print the offer detail line: coupon, price, terms and end date.
	 */
	public static function render_cbr_bfcm_meta() {
		/* translators: %s: date the Black Friday sale ends. */
		$cbr_ends = __( 'Ends %s', 'woo-product-country-base-restrictions' );
		$cbr_ends = sprintf( $cbr_ends, date_i18n( get_option( 'date_format' ), self::cbr_get_bfcm_end_ts() ) );
		?>
		<span class="cbr-bfcm-meta">
			<span class="cbr-bfcm-meta__item">
				<?php esc_html_e( 'Use code', 'woo-product-country-base-restrictions' ); ?>
				<code class="cbr-bfcm-meta__code"><?php echo esc_html( self::BFCM_COUPON ); ?></code>
				<?php esc_html_e( 'at checkout', 'woo-product-country-base-restrictions' ); ?>
			</span>
			<span class="cbr-bfcm-meta__item">
				<s class="cbr-bfcm-meta__was"><?php echo esc_html( self::BFCM_REGULAR_PRICE ); ?></s>
				&rarr;
				<strong class="cbr-bfcm-meta__now"><?php echo esc_html( self::BFCM_SALE_PRICE ); ?></strong>
			</span>
			<span class="cbr-bfcm-meta__item"><?php esc_html_e( 'New purchases only', 'woo-product-country-base-restrictions' ); ?></span>
			<span class="cbr-bfcm-meta__item"><?php echo esc_html( $cbr_ends ); ?></span>
		</span>
		<?php
	}

	/**
	 * Whether the Black Friday notice should show on this request.
	 *
	 * Rules: only during the sale, store managers only, not on the plugin's own
	 * settings page (which carries its own messaging) and hidden for a user
	 * once they dismiss it.
	 *
	 * @return bool
	 */
	public function should_show_cbr_bfcm_notice() {
		if ( ! self::cbr_is_bfcm_running() || ! current_user_can( 'manage_woocommerce' ) ) {
			return false;
		}

		if ( get_user_meta( get_current_user_id(), self::BFCM_DISMISSED_META, true ) ) {
			return false;
		}

		// Read-only check of which admin screen we are on, not form processing.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['page'] ) && 'woocommerce-product-country-base-restrictions' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Load the ZUI plugin-notice component on screens that show the notice.
	 */
	public function cbr_bfcm_notice_styles() {
		global $fzpcr;

		if ( ! $this->should_show_cbr_bfcm_notice() ) {
			return;
		}

		$zui_version_file = $fzpcr->get_plugin_path() . '/assets/zui/VERSION';
		$zui_version      = is_readable( $zui_version_file ) ? trim( file_get_contents( $zui_version_file ) ) : $fzpcr->version;

		wp_enqueue_style( 'cbr-zui-pnotice', $fzpcr->plugin_dir_url() . 'assets/zui/css/components/plugin-notice.css', array(), $zui_version );
		wp_add_inline_style(
			'cbr-zui-pnotice',
			// Full width: clear the floated Screen Options / Help tabs and keep core's 20px right gutter.
			'.zui-pnotice.cbr-bfcm-notice{clear:both;width:auto;margin-inline-end:20px;--zui-pnotice-accent:#0f172a;--zui-pnotice-avatar-bg:#0f172a;--zui-pnotice-avatar-color:#fbbf24}'
			. '@media (max-width:782px){.zui-pnotice.cbr-bfcm-notice{margin-inline-end:10px}}'
			. '.cbr-bfcm-notice .zui-pnotice__close{text-decoration:none}'
			. '.cbr-bfcm-meta{display:flex;flex-wrap:wrap;align-items:center;column-gap:6px;row-gap:4px;font-size:13px;line-height:1.6;color:var(--zui-text-muted,#64748b)}'
			. '.cbr-bfcm-meta__item{display:inline-flex;align-items:center;gap:5px}'
			. '.cbr-bfcm-meta__item+.cbr-bfcm-meta__item:before{content:"\00b7";margin-inline-end:6px;color:var(--zui-text-muted,#94a3b8)}'
			. '.cbr-bfcm-meta__code{display:inline-block;margin:0;padding:1px 8px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:4px;color:#0f172a;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:12px;font-weight:700;letter-spacing:.06em;user-select:all}'
			. '.cbr-bfcm-meta__was{color:#94a3b8}'
			. '.cbr-bfcm-meta__now{color:#0f172a;font-weight:700}'
		);
	}

	/**
	 * Black Friday notice - 40% off Country Based Restrictions Pro with coupon BFCM40.
	 */
	public function cbr_bfcm_notice() {
		if ( ! $this->should_show_cbr_bfcm_notice() ) {
			return;
		}

		$dismiss_url = wp_nonce_url( add_query_arg( 'cbr-bfcm-notice-dismiss', '1' ), 'cbr_bfcm_notice_dismiss', 'cbr_bfcm_nonce' );
		?>
		<div class="zui-pnotice cbr-bfcm-notice" role="status">
			<span class="zui-pnotice__avatar" aria-hidden="true">
				<svg class="zui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
			</span>

			<div class="zui-pnotice__body">
				<strong class="zui-pnotice__title"><?php echo esc_html( self::cbr_get_bfcm_title() ); ?></strong>
				<p class="zui-pnotice__text"><?php self::render_cbr_bfcm_meta(); ?></p>

				<div class="zui-pnotice__actions">
					<a class="zui-pnotice__btn" href="<?php echo esc_url( self::cbr_get_bfcm_url( 'bfcm-admin-notice' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( self::cbr_get_bfcm_button_label() ); ?></a>
				</div>
			</div>

			<a class="zui-pnotice__close" href="<?php echo esc_url( $dismiss_url ); ?>" aria-label="<?php esc_attr_e( 'Dismiss this notice.', 'woo-product-country-base-restrictions' ); ?>">&times;</a>
		</div>
		<?php
	}

	/**
	 * Hide the Black Friday notice for the current user after a nonce-checked dismiss.
	 */
	public function cbr_bfcm_notice_ignore() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified below before anything is saved.
		$cbr_dismiss_requested = isset( $_GET['cbr-bfcm-notice-dismiss'] );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is the nonce value itself.
		$cbr_notice_nonce = isset( $_GET['cbr_bfcm_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['cbr_bfcm_nonce'] ) ) : '';

		if ( ! $cbr_dismiss_requested || '' === $cbr_notice_nonce ) {
			return;
		}
		if ( ! wp_verify_nonce( $cbr_notice_nonce, 'cbr_bfcm_notice_dismiss' ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		update_user_meta( get_current_user_id(), self::BFCM_DISMISSED_META, time() );
		wp_safe_redirect( remove_query_arg( array( 'cbr-bfcm-notice-dismiss', 'cbr_bfcm_nonce' ) ) );
		exit;
	}

}

