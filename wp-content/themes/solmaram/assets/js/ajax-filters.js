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
    if (minPrice) params.min_price = minPrice;
    if (maxPrice) params.max_price = maxPrice;
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
        $('<span class="filter-tag">')
          .text((labels[key] || key) + ': ' + v)
          .append('<button class="filter-tag__remove" aria-label="Remove filter">×</button>')
          .data({ key: key, val: v })
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

  // Debounce filter inputs
  $(document).on('change', '.js-filter-input', function () {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => doFilter(1), 400);
  });

  // Remove active tag
  $(document).on('click', '.filter-tag__remove', function () {
    const $tag = $(this).closest('.filter-tag');
    const key  = $tag.data('key');
    const val  = $tag.data('val');
    $(`[name="${key}[]"][value="${val}"]`).prop('checked', false);
    $(`[name="${key}"][value="${val}"]`).val('');
    doFilter(1);
  });

  // Pagination inside AJAX area
  $(document).on('click', '#shop-pagination a', function (e) {
    e.preventDefault();
    const href  = $(this).attr('href');
    const match = href.match(/\/page\/(\d+)/);
    const page  = match ? parseInt(match[1], 10) : 1;
    doFilter(page);
    $('html, body').animate({ scrollTop: $('#products-grid').offset().top - 100 }, 300);
  });

})(jQuery);
