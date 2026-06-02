/* SolMaram – nova-poshta.js (FR-04.1) */
(function ($) {
  'use strict';

  let cityRef  = '';
  let cityTimer = null;

  // ── City autocomplete ──────────────────────────────────────────────
  $(document).on('input', '.np-city-input', function () {
    const $input = $(this);
    const query  = $input.val().trim();
    clearTimeout(cityTimer);
    if (query.length < 2) return closeSuggestions();
    cityTimer = setTimeout(function () {
      $.post(smNP.ajaxUrl, {
        action: 'np_get_cities',
        nonce: smNP.nonce,
        query: query,
      }, function (res) {
        if (res.success && res.data.length) {
          showSuggestions($input, res.data, 'city');
        }
      });
    }, 350);
  });

  // ── Branch dropdown after city selected ───────────────────────────
  $(document).on('change', '.np-branch-select', function () {
    // Value is already the branch description; store ref
  });

  function showSuggestions($input, items, type) {
    closeSuggestions();
    const $list = $('<ul class="np-suggestions">');
    items.forEach(function (item) {
      $('<li>').text(item.label).data('ref', item.ref).appendTo($list);
    });
    $list.insertAfter($input).on('click', 'li', function () {
      $input.val($(this).text());
      if (type === 'city') {
        cityRef = $(this).data('ref');
        loadBranches($input.closest('.shipping-np-wrapper'));
      }
      closeSuggestions();
    });
  }

  function closeSuggestions() {
    $('.np-suggestions').remove();
  }

  function loadBranches($wrapper) {
    const $select = $wrapper.find('.np-branch-select');
    $select.html('<option>' + ($select.data('loading') || 'Loading…') + '</option>');
    $.post(smNP.ajaxUrl, {
      action: 'np_get_warehouses',
      nonce: smNP.nonce,
      city_ref: cityRef,
    }, function (res) {
      if (res.success) {
        $select.empty();
        res.data.forEach(function (branch) {
          $('<option>').val(branch.ref).text(branch.label).appendTo($select);
        });
        $('body').trigger('update_checkout');
      }
    });
  }

  // Close suggestions on outside click
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.np-city-input, .np-suggestions').length) {
      closeSuggestions();
    }
  });

  // Trigger checkout update when delivery type changes
  $(document).on('change', 'input[name="np_delivery_type"]', function () {
    $('body').trigger('update_checkout');
  });

})(jQuery);
