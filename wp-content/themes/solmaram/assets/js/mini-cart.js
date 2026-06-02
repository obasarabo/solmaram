/* SolMaram – mini-cart.js */
(function ($) {
  'use strict';

  const $overlay  = $('.js-mini-cart-overlay');
  const $cart     = $('.js-mini-cart');
  const $triggers = $('.js-mini-cart-trigger');
  const $closes   = $('.js-mini-cart-close');

  function openCart() {
    $cart.addClass('is-open').attr('aria-hidden', 'false');
    $overlay.addClass('is-open');
    $('body').css('overflow', 'hidden');
  }

  function closeCart() {
    $cart.removeClass('is-open').attr('aria-hidden', 'true');
    $overlay.removeClass('is-open');
    $('body').css('overflow', '');
  }

  $triggers.on('click', function (e) {
    e.preventDefault();
    openCart();
  });

  $closes.add($overlay).on('click', closeCart);

  // Open mini cart after adding a product
  $(document.body).on('added_to_cart', function () {
    openCart();
    // Update cart count badge via wc-cart-fragments (handled by WooCommerce)
  });

  // Update count badge from fragments
  $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
    const count = parseInt($('.cart-count').first().text(), 10) || 0;
    $('.cart-count').text(count || '');
  });

  // Quantity change inside mini cart
  $(document.body).on('change', '.mini-cart-qty', function () {
    const key = $(this).data('cart-key');
    const qty = parseInt($(this).val(), 10);
    if (!key || qty < 0) return;
    $.post(wc_cart_params?.ajax_url ?? '/wp-admin/admin-ajax.php', {
      action: 'update_mini_cart_qty',
      cart_key: key,
      quantity: qty,
      security: wc_cart_params?.nonce ?? '',
    }).done(() => {
      $(document.body).trigger('wc_fragment_refresh');
    });
  });

  // Keyboard: Escape closes
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape' && $cart.hasClass('is-open')) closeCart();
  });

})(jQuery);
