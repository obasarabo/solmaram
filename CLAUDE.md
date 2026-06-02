# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

SolMaram (solmaram.com) — Ukrainian freeze-dried food e-commerce site.
Platform: **WordPress + WooCommerce**. All custom code lives in `wp-content/`.

## Project structure

```
wp-content/
  themes/solmaram/          # Custom theme
  plugins/solmaram-core/    # Recipe meta, free-shipping bar, Instagram feed, CSV export, AJAX filters
  plugins/solmaram-liqpay/  # LiqPay (card, Google Pay, Apple Pay) + Monobank payment gateways
  plugins/solmaram-nova-poshta/  # Nova Poshta shipping — branch + courier, real-time API
  plugins/solmaram-ukrposhta/    # Ukrposhta flat-rate shipping
```

## Architecture

### Theme
- **`functions.php`** — theme setup, `wp_enqueue_scripts`, WooCommerce support declarations, Polylang hreflang, customizer settings, pagination helper.
- **`front-page.php`** — homepage with 8 sections (hero, values, best sellers, freeze-drying teaser, about us, reviews carousel, blog, Instagram). Each section references its data source (customizer, WP_Query, WooCommerce, SM_Instagram_Feed).
- **`woocommerce/`** — WooCommerce template overrides (product card, archive, mini-cart, checkout, my-account).

### WooCommerce template overrides (in `themes/solmaram/woocommerce/`)
| File | Overrides |
|---|---|
| `content-product.php` | Product card with rating, use-case info, Add to Cart |
| `archive-product.php` | Catalogue with AJAX filter sidebar |
| `cart/mini-cart.php` | Slide-in panel, qty input, remove link |
| `checkout/form-checkout.php` | Two-step checkout (JS-driven, no page reload) |
| `myaccount/my-account.php` | Sidebar nav + content area |

### Plugin responsibilities
- **`solmaram-core`** — `SM_Recipe_Meta` (post meta boxes), `SM_Free_Shipping_Bar` (hook + shortcode), `SM_Instagram_Feed` (API + transient), `SM_CSV_Export` (admin submenu), `SM_Trust_Counters` (shortcode), `SM_Admin_Settings` (options page). Also registers the `wp_ajax_solmaram_filter_products` AJAX handler used by `ajax-filters.js`.
- **`solmaram-liqpay`** — `WC_SM_LiqPay_Gateway` extends `WC_Payment_Gateway`; server-side signature via `SM_LiqPay_API`; callback at `/wc-api/sm_liqpay_callback/`. `WC_SM_Monobank_Gateway` uses Monobank Acquiring API.
- **`solmaram-nova-poshta`** — `WC_SM_Nova_Poshta_Shipping` extends `WC_Shipping_Method`; `SM_Nova_Poshta_API` calls `https://api.novaposhta.ua/v2.0/json/`; AJAX handlers `np_get_cities` / `np_get_warehouses` feed the checkout autocomplete; `SM_NP_Address_Saver` saves chosen address to customer meta on order completion.
- **`solmaram-ukrposhta`** — `WC_SM_Ukrposhta_Shipping` flat-rate shipping, two rate IDs (`_branch`, `_home`).

### JavaScript files (`themes/solmaram/assets/js/`)
| File | Purpose |
|---|---|
| `main.js` | Mobile nav, search toggle, sticky header, review carousel, two-step checkout |
| `mini-cart.js` | Opens/closes mini cart panel; listens to `added_to_cart` and `wc_fragments_refreshed` |
| `ajax-filters.js` | Reads filter checkboxes + sort select → POST to `solmaram_filter_products` → replaces grid HTML |
| `shipping-bar.js` | Listens to `wc_fragments_refreshed`, reads cart subtotal, updates bar width |
| `nova-poshta.js` | City autocomplete (debounced), branch dropdown load, triggers `update_checkout` |

## Key conventions

- **Text domain** is `solmaram` everywhere. All user-facing strings go through `__()` / `_e()` / `esc_html__()`.
- **Multilingual**: Polylang is used. `hreflang` output is in `functions.php` via `wp_head` action. Do not hardcode language strings.
- **No card data stored** on server — LiqPay and Monobank redirect to their own checkout pages.
- **AJAX nonces**: filter nonce is `sm_filter`, Nova Poshta nonce is `sm_np`. Both are verified with `check_ajax_referer()` before processing.
- **Best-seller products** are tagged with the `best-seller` product tag in WooCommerce admin.
- **Free shipping threshold** is stored in WordPress option `sm_free_shipping_threshold` (default 500). Change it in the SolMaram admin settings page.
- **Recipe post meta keys**: `_recipe_prep_time`, `_recipe_servings`, `_recipe_ingredients` (array), `_recipe_steps` (array), `_recipe_products` (array of product IDs).
- **Instagram photos** are cached as the `sm_instagram_photos` transient (1 hour). Clear from SolMaram settings.
- **Nova Poshta API key** stored in option `sm_np_api_key`; also exposed per-instance in shipping method settings.

## Functional requirements reference

| FR | Implementation location |
|---|---|
| FR-01 Catalogue + filters | `woocommerce/archive-product.php`, `ajax-filters.js`, `solmaram-core.php` AJAX handler |
| FR-02 Cart & mini-cart | `woocommerce/cart/mini-cart.php`, `mini-cart.js`, `woocommerce/checkout/form-checkout.php` |
| FR-03 Payments | `solmaram-liqpay/` (LiqPay + Monobank), WooCommerce built-in CoD |
| FR-04 Delivery | `solmaram-nova-poshta/`, `solmaram-ukrposhta/`, `shipping-bar.js` |
| FR-05 Accounts | `woocommerce/myaccount/my-account.php`, WooCommerce built-in endpoints |
| FR-06 Freeze-drying page | `page-sublimation.php` |
| FR-07 Reviews | WooCommerce built-in, `SM_Trust_Counters` shortcode |
| FR-08 Blog & Recipes | `archive.php`, `single.php`, `SM_Recipe_Meta` |
| FR-09 Homepage | `front-page.php` |
| FR-10 Search | `search.php` |
| FR-11 Admin | `SM_CSV_Export`, `SM_Admin_Settings`, standard WooCommerce admin |
| FR-12 Multilingual | Polylang; hreflang in `functions.php`; all strings i18n-ready |
