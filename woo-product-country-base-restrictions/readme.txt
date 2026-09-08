=== Country Based Restrictions for WooCommerce ===
Contributors: zorem,gaurav1092,virendesai
Tags: country restrictions, geolocation, woocommerce, product restriction, payment gateway
Requires at least: 5.3
Tested up to: 7.1
Stable tag: 3.8.1
Requires PHP: 7.0
WC requires at least: 5.0
WC tested up to: 11.1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict WooCommerce products by country using geolocation. Control what customers can see and buy based on their location — per product or in bulk.

== Description ==

**Country Based Restrictions for WooCommerce** lets you control exactly which products are visible and purchasable based on your customer's country — using WooCommerce's built-in geolocation engine (MaxMind GeoIP2) for 99.5% accuracy.

Whether you're selling region-specific products, dealing with legal compliance restrictions, or managing international distribution agreements, CBR gives you precise control over your catalog by country — without any coding.

**HPOS Compatible** · **5,000+ Active Stores** · **Free & Open Source**

= What You Can Do =

* **Restrict products per country** — For any product, choose a list of countries to include (sell only to) or exclude (block from). Set it per product in seconds.
* **Three visibility modes** — Choose how restricted products behave for blocked customers:
  * Hide completely from shop, search, and category pages
  * Hide from shop and search but allow direct URL access
  * Keep visible but non-purchasable (show with a custom message instead of Add to Cart)
* **Include or exclude rules** — For each product, choose whether the country list means "sell only to these countries" or "do not sell to these countries".
* **Works with WooCommerce Geolocation** — Uses the shipping country the customer enters, falling back to WooCommerce Geolocation (MaxMind GeoIP2) when no shipping country is set yet.
* **Supports product variations** — Apply country restrictions to individual variations within a variable product, not just the whole product.
* **HPOS Compatible** — Fully compatible with WooCommerce High-Performance Order Storage (custom order tables).
* **Translation ready** — Fully translatable via standard WordPress/WooCommerce translation tools.

= How It Works =

1. Install and activate the plugin.
2. Go to **WooCommerce > Shipment Tracking > Country Restrictions** to configure the default visibility settings.
3. Open any product and set the country restriction rule — choose include or exclude, and select the countries.
4. Customers visiting from a restricted country will see the product according to your chosen visibility mode.

= Free vs PRO — What's the Difference? =

CBR Free gives you per-product country restrictions. CBR PRO adds bulk tools, payment gateway control, and advanced display options for stores managing restrictions at scale.

**CBR Free includes:**

* Per-product country restriction rules (include or exclude)
* Three visibility modes: hide completely, hide from shop/search, or show but block purchase
* Variation-level restrictions
* WooCommerce Geolocation detection (MaxMind GeoIP2)
* HPOS compatible

**CBR PRO adds:**

* **Bulk restrictions by category, tag, attribute, or shipping class** — Apply rules to entire product groups at once instead of editing each product individually.
* **Global restriction rules** — Apply one rule across all products sitewide.
* **Bulk CSV import** — Import country rules for hundreds of products at once.
* **Disable payment gateways by country** — Hide or disable specific payment methods (PayPal, Stripe, etc.) for customers from restricted countries.
* **Hide product prices by country** — Show the product but hide the price for restricted countries.
* **Country detection widget** — Let customers manually change their detected country from the storefront.
* **Custom restriction messages** — Set a custom message per product shown to restricted customers instead of the Add to Cart button.
* **Redirect restricted customers** — Redirect blocked visitors to a custom page instead of showing an error.
* **Remove bulk rules** — Clear single-product rules in bulk via the product list bulk actions.
* **Priority support** — Faster, dedicated assistance from the Zorem team.

👉 [Get CBR PRO](https://www.zorem.com/product/country-based-restriction-pro/)

= Compatible With =

CBR is tested and compatible with:

* **Multilingual plugins** — WPML, Polylang, Weglot
* **Page builders** — Elementor, Divi, WPBakery (Visual Composer)
* **Wholesale plugins** — Wholesale for WooCommerce
* **Search plugins** — Advanced Search for WooCommerce
* **Subscriptions** — WooCommerce Subscriptions (variation restriction supported)
* **Multi-currency** — Works alongside currency switcher plugins

⚠️ **Important note for caching:** Caching plugins (WP Rocket, W3 Total Cache, SiteGround Optimizer) and CDNs can serve cached pages that bypass geolocation detection. Ensure your caching is configured to exclude geolocation cookies and country detection from the cache. [See the documentation](https://docs.zorem.com/docs/country-based-restrictions-pro/) for setup guidance.

= Documentation & Support =

* 📖 [Full documentation](https://docs.zorem.com/docs/country-based-restrictions-pro/)
* 💬 [WordPress.org support forum](https://wordpress.org/support/plugin/woo-product-country-base-restrictions/)

= More Plugins by Zorem =

* [Advanced Shipment Tracking for WooCommerce](https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/) — The #1 WooCommerce shipment tracking plugin. Trusted by 80,000+ stores.
* [SMS for WooCommerce](https://www.zorem.com/product/sms-for-woocommerce/) — Automated SMS order and shipping notifications via Twilio, WhatsApp, and 18 other providers.
* [Customer Email Verification PRO](https://www.zorem.com/product/customer-email-verification/) — Block fake accounts and spam orders with OTP-based email verification.
* [Local Pickup PRO](https://www.zorem.com/product/zorem-local-pickup-pro/) — Advanced local pickup with multiple locations, appointment scheduling, and ready-for-pickup notifications.
* [Zorem Returns](https://www.zorem.com/product/zorem-returns/) — Self-service returns, exchanges, and store credit management for WooCommerce.

Explore the full catalog at [zorem.com](https://www.zorem.com/)

== Installation ==

1. Go to **Plugins > Add New** in your WordPress admin and search for "Country Based Restrictions for WooCommerce".
2. Click **Install Now**, then **Activate**.
3. Make sure you have configured **shipping countries** in **WooCommerce > Settings > General**.
4. Go to **WooCommerce > Country Restrictions** to set global visibility defaults.
5. Edit any product to set per-product country restriction rules.

Alternatively, upload the `woo-product-country-base-restrictions` folder to `/wp-content/plugins/` and activate through the Plugins menu.

== Frequently Asked Questions ==

= How does the plugin detect a customer's country? =

CBR uses WooCommerce's built-in country detection. It checks the shipping country the customer enters during checkout first. If no shipping country has been set yet, it falls back to WooCommerce Geolocation (MaxMind GeoIP2), which has 99.5% country-level accuracy.

= Can I restrict a product so only customers from specific countries can buy it? =

Yes. On any product page, set the restriction rule to "Include" and select the countries that are allowed to purchase. Customers from all other countries will see the product according to your chosen visibility mode (hidden, or visible but not purchasable).

= Can I block specific countries from buying a product? =

Yes. Set the restriction rule to "Exclude" and select the countries to block. Customers from those countries will be restricted; everyone else can purchase normally.

= What happens to restricted customers — do they see the product? =

You choose. Three visibility modes are available: hide the product completely from shop and search; hide from shop and search but allow direct URL access; or keep the product visible but replace the Add to Cart button with a custom message.

= Can I restrict individual product variations by country? =

Yes. CBR supports variation-level restrictions, so you can allow some variations to be purchased in certain countries while restricting others within the same variable product.

= Can I restrict products in bulk by category or tag? =

Bulk restrictions by category, tag, attribute, shipping class, or globally (all products) are available in [CBR PRO](https://www.zorem.com/product/country-based-restriction-pro/).

= Can I restrict payment gateways by country? =

Disabling specific payment gateways for customers from restricted countries is available in [CBR PRO](https://www.zorem.com/product/country-based-restriction-pro/).

= Can I hide product prices for restricted countries? =

Hiding the product price for restricted countries is available in [CBR PRO](https://www.zorem.com/product/country-based-restriction-pro/).

= Does CBR work with WPML or Polylang multilingual stores? =

Yes. CBR is compatible with WPML and Polylang. Country rules set on a product apply across all language versions of that product.

= Will caching plugins interfere with country detection? =

Yes — this is a common issue. If your caching plugin serves a cached page, the geolocation check may not run for that visitor. You need to configure your caching plugin to exclude pages with geolocation cookies from the cache. See the [documentation](https://docs.zorem.com/docs/country-based-restrictions-pro/) for specific instructions per caching plugin.

= Does CBR work with HPOS (High-Performance Order Storage)? =

Yes. CBR is fully compatible with WooCommerce High-Performance Order Storage (custom order tables).

= Does CBR work with WooCommerce Subscriptions? =

Yes. CBR supports WooCommerce Subscriptions, including variation-level restrictions on subscription products.

== Changelog ==

= 3.8.1 =
* Dev - WP tested upto 7.1.
* Dev - WC Compatibility added upto 11.1.0.

= 3.8.0 =
* Dev - WP tested upto 7.0.2.
* Dev - WC Compatibility added upto 10.9.4.

= 3.7.9 =
* Improved - Upgrade the Settings page design.
* Dev - WP tested upto 7.0.
* Dev - WC Compatibility added upto 10.8.1.

= 3.7.8 =
* Dev - WP tested upto 6.9.4.
* Dev - WC Compatibility added upto 10.7.0.

= 3.7.7 =
* Dev - WP tested upto 6.9.1.
* Dev - WC Compatibility added upto 10.4.3.
* Improved – Updated PRO promotional notice on the settings page UI.

= 3.7.6 =
* Dev - WP tested upto 6.8.3.
* Dev - WC Compatibility added upto 10.3.5.
* Fix – Update deprecated WooCommerce script handles to new handles (WC 10.3.0+).

= 3.7.5 =
* Improved - Updated the promotional notice.
* Dev - WC Compatibility added upto 10.1.2

= 3.7.4 =
* Improved - Updated the promotional notice.
* Improved - Updated the settings page design.
* Dev - WP tested upto 6.8.2.
* Dev - WC Compatibility added upto 10.0.4.

= 3.7.3 =
* Improved - Updated the promotional notice.
* Dev - WP tested upto 6.8.1.
* Dev - WC Compatibility added upto 9.8.5.

= 3.7.2 =
* Fix - Removed the .htaccess files.

= 3.7.1 =
* Enhancement - Added a review request admin notice.
* Dev - WC Compatibility added upto 9.8.1.

= 3.7.0 =
* Dev - WP tested upto 6.7.2.
* Dev - WC Compatibility added upto 9.7.1.
* Improved - Improved a new admin message design.
* Tweak - Updated the settings page design.
* Tweak - Updated the settings text and tooltips.

= 3.6.8 =
* Dev - WP tested upto 6.7.
* Dev - WC Compatibility added upto 9.4.2.
* Enhancement - Added a Black Friday admin message.

= 3.6.7 =
* Enhancement - Added an admin message for the Returns plugin.

= 3.6.6 =
* Dev - WP tested upto 6.6.
* Dev - WC Compatibility added upto 9.2.3.
* Dev - Added compatibility with Advanced Search for WooCommerce plugin.

= 3.6.5 =
* Dev - WC Compatibility added upto 9.0.2.
* Fix - Restriction not working on search products.

= 3.6.4 =
* Fix - Undefined $suffix variable error.

= 3.6.3 =
* Fix - jQuery(...).block is not a function error.

= 3.6.2 =
* Dev - WC Compatibility added upto 8.7.0.
* Dev - WP tested upto 6.5.
* Enhancement – Added UTM link for all external links to zorem.com.

= 3.6.1 =
* Dev - WC Compatibility added upto 8.5.2.
* Fix - Patched a vulnerability concerning nonces in admin notices.

= 3.6 =
* Dev - Added compatibility with Wholesale for WooCommerce plugin.
* Dev - WC Compatibility added upto 8.4.0.
* Dev - WP tested upto 6.4.

= 3.5 =
* Dev - Added compatibility with PHP 8.2.
* Dev - WC Compatibility added upto 8.2.1.
* Dev - WP tested upto 6.4.

= 3.4 =
* Dev - WC Compatibility with HPOS.
* Dev - WC Compatibility added upto 7.8.1.
* Dev - WP tested upto 6.2.

= 3.3 =
* Dev - WC Compatibility added upto 6.8.
* Dev - WP tested upto 6.0.

= 3.2 =
* Dev - WC Compatibility added upto 6.3.
* Dev - WP tested upto 5.9.
* Enhancement - Added compatibility with Customer Reviews for WooCommerce.
* Enhancement - Added Docs and Review link on plugins page.
* Tweak - Updated the settings page design.
* Fix - Bug on checkout when billing country changes.

= 3.1 =
* Dev - WC Compatibility added upto 5.6.

= 3.0 =
* Dev - WC Compatibility added upto 5.5.2.

= 2.9.1 =
* Dev - WP Compatibility added upto 5.8.

= 2.9.0 =
* Fix - Warning: in_array() expects parameter 2 to be array, null given.
* Fix - Fixed the issue of Subscription variation restriction.

= 2.8.9 =
* Tweak - Updated settings design.
* Dev - WC Compatibility added upto 5.1.

= 2.8.8 =
* Tweak - Updated settings design.
* Dev - WP Compatibility added upto 5.7.

= 2.8.7 =
* Dev - WC Compatibility added upto 5.0.

= 2.8.6 =
* Fix - Toolbar/debug mode critical bug.
* Fix - Related Products / WC products widgets bug.

= 2.8.5 =
* Fix - Hide Completely — select a page to redirect bug.

= 2.8.4 =
* Fix - Fixed country and state dropdown selection issue on checkout page.

= 2.8.3 =
* Tweak - Updated settings design.
* Enhancement - Free plugin does not run if PRO is activated.

= 2.8.2 =
* Tweak - Updated settings design.

= 2.8.1 =
* Fix - Issues with geolocation / widget detectors.

= 2.8.0 =
* Dev - WC Compatibility added upto 4.8.
* Dev - WP Compatibility added upto 5.6.

= 2.7.9 =
* Tweak - Updated settings tab design.
* Enhancement - Added addons tab.

= 2.7.8 =
* Tweak - Updated settings tab design.

= 2.7.7 =
* Tweak - Changed label of option.
* Enhancement - Added options (PRO) for Country detection widget customization.
* Enhancement - Added CBR widget (PRO) for customers.

= 2.7.6 =
* Fix - CSS issue in settings.
* Tweak - Updated settings tab design.
* Enhancement - Added cart message option (PRO) for custom cart restriction message.

= 2.7.5 =
* Fix - CSS issue in settings design.
* Fix - Issue of countries list dropdown option.

= 2.7.4 =
* Fix - CSS issue in settings design.

= 2.7.3 =
* Dev - WC Compatibility added upto 4.5.
* Dev - WP Compatibility added upto 5.5.
* Fix - CSS issue in settings design.
* Fix - Issue of subscription variation product.
* Tweak - Design UI/UX updates.

= 2.7.2 =
* Fix - Invalid argument supplied for foreach().

= 2.7.1 =
* Dev - WC Compatibility added upto 4.3.
* Dev - Added compatibility with Visual Composer.

= 2.7 =
* Enhancement - Added Bulk Action option (PRO) to remove single product rules.
* Tweak - Optimized WP query to improve site speed.

= 2.6.9 =
* Enhancement - Added new PRO option of Global (All Products) in Bulk restriction settings.
* Fix - Issue of WPML compatibility.

= 2.6.8 =
* Enhancement - Added new PRO option to hide restricted product price.

= 2.6.7 =
* Fix - CSS issue in settings design.
* Tweak - Updated settings tab design.

= 2.6.6 =
* Fix - Issue of redirect 404 error page.
* Tweak - Design and label updates.

= 2.6.5 =
* Tweak - Design and label updates.

= 2.6.4 =
* Tweak - PRO tab design.
* Enhancement - Added option of 404 error page redirect to shop page in settings.

= 2.6.3 =
* Tweak - Settings design.

= 2.6.2 =
* Tweak - Design UI/UX.

= 2.6.1 =
* Fix - Black bar appearing at the top of the site.
* Fix - JS issue on email customizer.
* Fix - General error fixes.

= 2.6 =
* Enhancement - Added PRO option of 404 error page redirect to shop page in settings.
* Tweak - Improved settings design.

= 2.5.4 =
* Dev - WC Compatibility added upto 4.0.
* Dev - WP tested upto 5.4.

= 2.5.3 =
* Enhancement - Added PRO tab in settings.
* Tweak - Updated settings design.

= 2.5.2 =
* Fix - Bug fix.

= 2.5.1 =
* Enhancement - Added option to hide variation products in settings.

= 2.5 =
* Dev - CBR PRO compatibility added.

[For the complete changelog](https://www.zorem.com/docs/country-based-restrictions-for-woocommerce/changelog/)
