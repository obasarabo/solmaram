/* SolMaram – ajax-filters.js (FR-01.3) */
(function ($) {
  'use strict';

  let filterTimer = null;
  const $grid       = $('#products-grid');
  const $pagination = $('#shop-pagination');
  const $activeTags = $('#active-filters');

  function getParams() {
    const params = {};
    $('[name="product_cat[]"]:checked').each(function () {
      (params.product_cat = params.product_cat || []).push($(this).val());
    });
    $('[name="use_case[]"]:checked').each(function () {
      (params.use_case = params.use_case || []).push($(this).val());
    });
    const minPrice = $('[name="min_price"]').val();
    const maxPrice = $('[name="max_price"]').val();
    if (minPrice !== '' && Number(minPrice) > 0) params.min_price = minPrice;
    if (maxPrice !== '' && Number(maxPrice) > 0) params.max_price = maxPrice;
    params.orderby = $('[name="orderby"]').val();
    return params;
  }

  function buildActiveTags(params) {
    $activeTags.empty();
    const labels = { product_cat: 'Category', use_case: 'Use case', min_price: 'Min', max_price: 'Max' };
    $.each(params, function (key, val) {
      const vals = Array.isArray(val) ? val : [val];
      vals.forEach(function (v) {
        if (!v || key === 'orderby') return;
        if ((key === 'min_price' || key === 'max_price') && parseFloat(v) <= 0) return;
        $('<span class="filter-tag">')
          .attr({ 'data-key': key, 'data-val': v })
          .text((labels[key] || key) + ': ' + v)
          .append('<button class="filter-tag__remove" aria-label="Remove filter">\xd7</button>')
          .appendTo($activeTags);
      });
    });
  }

  function doFilter(page) {
    const params = getParams();
    buildActiveTags(params);
    $grid.css('opacity', .5);
    $.post(smFilters.ajaxUrl, {
      action: 'solmaram_filter_products',
      nonce: smFilters.nonce,
      paged: page || 1,
      ...params,
    }, function (res) {
      if (res.success) {
        $grid.html(res.data.html).css('opacity', 1);
        $pagination.html(res.data.pagination);
      } else {
        $grid.css('opacity', 1);
      }
    });
  }

  // Debounce checkbox / select changes
  $(document).on('change', '.js-filter-input', function () {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => doFilter(1), 400);
  });

  // Debounce price input keystrokes (fires on every keystroke, longer delay)
  $(document).on('input', '.price-range__input', function () {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => doFilter(1), 600);
  });

  // Remove active tag
  $(document).on('click', '.filter-tag__remove', function () {
    const $tag = $(this).closest('.filter-tag');
    const key  = $tag.attr('data-key');
    const val  = $tag.attr('data-val');
    clearTimeout(filterTimer);
    if (key === 'min_price' || key === 'max_price') {
      const el = document.querySelector('[name="' + key + '"]');
      if (el) el.valueAsNumber = NaN; // clears type=number; el.value='' rejected by constraint validator on min=0
    } else {
      $('[name="' + key + '[]"][value="' + val + '"]').prop('checked', false);
    }
    doFilter(1);
  });

  // Auto-apply pre-checked filters on page load (e.g. arriving from a category link)
  $(function () {
    if ( $('.js-filter-input:checked').length > 0 ) {
      doFilter(1);
    }
  });

  // Pagination inside AJAX area
  $(document).on('click', '#shop-pagination a', function (e) {
    e.preventDefault();
    const href    = $(this).attr('href');
    const match   = href.match(/\/page\/(\d+)/);
    const urlPage = match ? parseInt(match[1], 10) : null;
    const qsPage  = new URL(href, location.href).searchParams.get('paged');
    const page    = urlPage || (qsPage ? parseInt(qsPage, 10) : 1);
    doFilter(page);
    $('html, body').animate({ scrollTop: $('#products-grid').offset().top - 100 }, 300);
  });

})(jQuery);
