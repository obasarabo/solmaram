/* SolMaram – shipping-bar.js (FR-04.3) */
(function ($) {
  'use strict';

  function updateBar(subtotal) {
    const $bar = $('.sm-shipping-bar');
    if (!$bar.length || typeof smShippingBar === 'undefined') return;
    const threshold = parseFloat(smShippingBar.threshold) || 500;
    const current   = parseFloat(subtotal)               || 0;
    const pct       = Math.min(100, (current / threshold) * 100);
    const remaining = Math.max(0, threshold - current);

    $bar.find('.sm-shipping-bar__fill').css('width', pct + '%');
    $bar.toggleClass('sm-shipping-bar--complete', pct >= 100);

    if (pct >= 100) {
      $bar.find('.sm-shipping-bar__label').text(smShippingBar.labelFree);
    } else {
      $bar.find('.sm-shipping-bar__label').text(
        smShippingBar.labelRemaining.replace('{amount}', remaining.toFixed(0) + ' ₴')
      );
    }
  }

  // Refresh when WooCommerce cart fragments update
  $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
    const subtotalEl = document.querySelector('.cart-subtotal .woocommerce-Price-amount bdi');
    if (subtotalEl) {
      const raw = subtotalEl.textContent.replace(/[^\d.,]/g, '').replace(',', '.');
      updateBar(parseFloat(raw));
    }
  });

})(jQuery);
