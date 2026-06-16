# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

**SolMaram** (solmaram.com) — a Ukrainian freeze-dried food e-commerce store built on **WordPress + WooCommerce**. All custom code lives under `wp-content/` (one theme + four plugins); everything else (WP core, WooCommerce, Polylang, Akismet) is third-party and not tracked in git.

The storefront is **trilingual** — **EN** (base locale), **UK**, and **PT** — via Polylang. Products are language-agnostic single posts; their EN/PT text is stored as post meta and injected through filters (see `SM_Product_I18n`). Never hardcode user-facing strings.

## ⚠️ Which checkout to edit

There are **two clones** of `github.com/obasarabo/solmaram.git` on this machine:

| | Path | Status |
|---|---|---|
| **Active** | `~/projects/maram` (WSL: `\\wsl.localhost\Ubuntu\home\obasarab\projects\maram`) | **Work here.** |
| Deprecated | `C:\Users\olehb\PycharmProjects\maram` (Windows) | Stale, behind by several commits. Do **not** edit. |

**Gotcha:** the Bash tool runs git-bash on Windows, so `~` and `cd ~` resolve to the **Windows** home (`C:\Users\olehb`), *not* this WSL project. Always use explicit paths: `cd //wsl.localhost/Ubuntu/home/obasarab/projects/maram` for Bash, and the `\\wsl.localhost\...` UNC form for Read/Edit/Write.

## Development environment

Docker Compose (`docker-compose.yml`) — **no Redis** in this clone:

| Service | Image / build | Notes |
|---|---|---|
| `db` | `mysql:8.0` | DB `wordpress`, user `wp` / `wppass`, root `rootpass`; tuned InnoDB buffer/redo. |
| `wordpress` | built from `./docker` (`wordpress:php8.1-apache`) | **http://localhost:8080**, `WORDPRESS_DEBUG=1`. |
| `wpcli` | built from `./docker/Dockerfile.cli` (`wordpress:cli-php8.3`) | runs as root; for WP-CLI + seed scripts. |

Volumes: named `wp_core` (WP core), `wp_uploads` (uploads), `db_data` (MySQL); the repo's `wp-content/` is bind-mounted into the containers. `docker/php.ini` tunes OPcache.

```bash
# from the project root
docker compose up -d                                   # start the stack (site on :8080)
docker compose run --rm wpcli wp <command>             # run any WP-CLI command
docker compose run --rm wpcli wp eval-file docker/seed-products.php   # run a seed script
```

**Seed / maintenance scripts** (`docker/`, run via `wp eval-file`): `seed-products.php`, `seed-product-translations.php`, `seed-recipes.php`, `seed-reviews.php`, `seed-about-text.php`, `seed-blog-page.php`, `seed-use-cases.php`, `fix-product-terms.php`.

## Architecture

### Theme (`wp-content/themes/solmaram/`)

- **`functions.php`** — theme setup, `wp_enqueue_scripts` (localizes `smFilters` and `smNP` for AJAX), image sizes (incl. `hero-full` 1920×800), WooCommerce support, customizer settings (`sm_hero_tagline`/`sm_hero_cta_label`/`sm_hero_cta_url`/`sm_hero_image`, `sm_fd_image`, `sm_about_text`/`sm_about_image`), Polylang `hreflang`, multilingual/locale + currency helper functions, and `solmaram_pagination()`.
- **`front-page.php`** — homepage, 8 sections in order: **1** Hero (customizer; falls back to bundled `assets/images/hero.jpg`), **2** Value proposition (static), **3** Best sellers (`best-seller` product tag), **4** Freeze-drying teaser (`sm_fd_image`), **5** About us (`sm_about_*`), **6** Reviews carousel (review comments, rating ≥ 4), **7** Blog & Recipes (`WP_Query`), **8** Instagram (`SM_Instagram_Feed`).
- Other templates: `page.php`, `page-sublimation.php` (freeze-drying education), `archive.php`, `single.php`, `search.php`, `404.php`, `sidebar.php`, `header.php`, `footer.php`, `index.php`.
- **`languages/`** — `solmaram.pot`, `uk.po/.mo`, `pt_PT.po/.mo`.
- **`assets/css/`** — `main.css` + `woocommerce.css`. Design tokens: forest-green `--color-primary: #4a7c59`, amber accent `#e8a838`; fonts Playfair Display (headings) + Inter (body).
- **`assets/images/hero.jpg`** — bundled default hero background (used when `sm_hero_image` is unset; Customizer setting overrides it).

### WooCommerce template overrides (`themes/solmaram/woocommerce/`)
| File | Overrides |
|---|---|
| `content-product.php` | Product card — rating, use-case info, Add to Cart |
| `archive-product.php` | Catalogue with AJAX filter sidebar |
| `cart/mini-cart.php` | Slide-in panel, qty input, remove link |
| `checkout/form-checkout.php` | Two-step checkout (JS-driven, no reload) |
| `myaccount/my-account.php` | Sidebar nav + content area |
| `single-product/meta.php` | Single-product meta block |
| `single-product/tabs/description.php` | Description tab with use-case block |

### JavaScript (`themes/solmaram/assets/js/`)
| File | Purpose |
|---|---|
| `main.js` | Language switcher, mobile nav, search toggle, sticky header, reviews carousel, two-step checkout |
| `mini-cart.js` | Open/close mini-cart; listens to `added_to_cart` / `wc_fragments_refreshed`; AJAX qty update |
| `ajax-filters.js` | Reads filter checkboxes + sort → POST `solmaram_filter_products` → replaces grid HTML |
| `shipping-bar.js` | Reads cart subtotal from fragments, updates free-shipping bar width |
| `nova-poshta.js` | City autocomplete (debounced), branch dropdown, triggers `update_checkout` |

### Plugins (`wp-content/plugins/`)

- **`solmaram-core`** — loads on `plugins_loaded` (requires WooCommerce). Classes:
  - `SM_Recipe_Meta` (recipe post meta boxes), `SM_Free_Shipping_Bar` (hook + `[sm_shipping_bar]`), `SM_Instagram_Feed` (Graph API + transient), `SM_CSV_Export` (order CSV admin submenu), `SM_Trust_Counters` (`[sm_trust_counters]`), `SM_Admin_Settings` (options page).
  - **`SM_Inventory_Manager`** — inventory lifecycle: counters `_sm_booked_qty` (paid, unshipped) and `_sm_shipped_qty` (dispatched) alongside WooCommerce `_stock`; per-line `_sm_inv_status` (`booked`/`shipped`); registers a custom **`wc-shipped`** order status; hooks `woocommerce_payment_complete`, `woocommerce_order_status_wc-shipped`, `…_cancelled`, `…_refunded`.
  - **`SM_Product_I18n`** — product translations stored as `_sm_{field}_{lang}` post meta (`_sm_name_en`/`_pt`, `_sm_short_description_*`, `_sm_description_*`), injected via `woocommerce_product_get_name` / `…_short_description` / `…_description` and `the_title` filters so the rest of the codebase needs no changes.
  - Registers the **`sm_use_case`** product taxonomy (with a one-time migration from the old `use case` product tags, flagged by option `sm_use_case_migrated`) and custom WooCommerce order-admin columns.
  - AJAX `solmaram_filter_products` (nonce `sm_filter`) backs `ajax-filters.js`.
- **`solmaram-liqpay`** — `WC_SM_LiqPay_Gateway` (id `sm_liqpay`, card/Google Pay/Apple Pay) and `WC_SM_Monobank_Gateway` (id `sm_monobank`), both extend `WC_Payment_Gateway`; signing via `SM_LiqPay_API`. Callbacks: `/wc-api/sm_liqpay_callback/` and `/wc-api/sm_monobank_callback/`.
- **`solmaram-nova-poshta`** — `WC_SM_Nova_Poshta_Shipping` (id `sm_nova_poshta`) extends `WC_Shipping_Method`; `SM_Nova_Poshta_API` calls `https://api.novaposhta.ua/v2.0/json/`; AJAX `np_get_cities` / `np_get_warehouses` / `np_set_city_ref` (nonce `sm_np`) feed the checkout autocomplete; `SM_NP_Address_Saver` saves the chosen address to customer meta on order completion.
- **`solmaram-ukrposhta`** — `WC_SM_Ukrposhta_Shipping` (id `sm_ukrposhta`), flat-rate, two rate IDs (`sm_ukrposhta_branch`, `sm_ukrposhta_home`).

## Key conventions

- **Text domain** is `solmaram` everywhere; route all strings through `__()` / `_e()` / `esc_html__()`.
- **Trilingual (EN/UK/PT)** via Polylang; `hreflang` emitted in `functions.php` on `wp_head`. Products are language-agnostic — translate via the `_sm_{field}_{lang}` meta pattern handled by `SM_Product_I18n`, not by duplicating posts.
- **No card data stored** on the server — LiqPay and Monobank redirect to their own checkout pages.
- **AJAX nonces**: `sm_filter` (product filters), `sm_np` (Nova Poshta); verified with `check_ajax_referer()`.
- **Best-seller products** carry the `best-seller` product tag.
- **Free-shipping threshold** in option `sm_free_shipping_threshold` (default 500); edit in the SolMaram admin settings page.
- **Recipe meta keys**: `_recipe_prep_time`, `_recipe_servings`, `_recipe_ingredients` (array), `_recipe_steps` (array), `_recipe_products` (product IDs).
- **Inventory**: meta `_sm_booked_qty` / `_sm_shipped_qty` + order-item `_sm_inv_status`; custom `wc-shipped` order status.
- **Instagram photos** cached in the `sm_instagram_photos` transient (1 hour); token in option `sm_instagram_token`.
- **Nova Poshta API key** in option `sm_np_api_key` (also per-instance in the shipping method settings).

## Functional requirements reference

| FR | Implementation location |
|---|---|
| FR-01 Catalogue + filters | `woocommerce/archive-product.php`, `ajax-filters.js`, `solmaram-core.php` AJAX handler; `sm_use_case` taxonomy |
| FR-02 Cart & mini-cart | `woocommerce/cart/mini-cart.php`, `mini-cart.js`, `woocommerce/checkout/form-checkout.php` |
| FR-03 Payments | `solmaram-liqpay/` (LiqPay + Monobank), WooCommerce built-in CoD |
| FR-04 Delivery | `solmaram-nova-poshta/`, `solmaram-ukrposhta/`, `shipping-bar.js` |
| FR-05 Accounts | `woocommerce/myaccount/my-account.php`, WooCommerce built-in endpoints |
| FR-06 Freeze-drying page | `page-sublimation.php` |
| FR-07 Reviews | WooCommerce built-in, `SM_Trust_Counters` shortcode |
| FR-08 Blog & Recipes | `archive.php`, `single.php`, `SM_Recipe_Meta` |
| FR-09 Homepage | `front-page.php` |
| FR-10 Search | `search.php`, `404.php` |
| FR-11 Admin | `SM_CSV_Export`, `SM_Admin_Settings`, `SM_Inventory_Manager`, standard WooCommerce admin |
| FR-12 Multilingual | Polylang (EN/UK/PT); `hreflang` in `functions.php`; `SM_Product_I18n` for product text; all strings i18n-ready |

> Detailed specs live in `Requirements/` (`ENG/SolMaram_Functional_Requirements_v1.1.docx`, `UA/SolMaram_TZ_v1.1.docx`).
