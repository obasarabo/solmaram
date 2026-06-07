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
    const groupLabels = {
      product_cat: smFilters.labelCategory,
      use_case:    smFilters.labelUseCase,
      min_price:   smFilters.labelMin,
      max_price:   smFilters.labelMax,
    };
    $.each(params, function (key, val) {
      const vals = Array.isArray(val) ? val : [val];
      vals.forEach(function (v) {
        if (!v || key === 'orderby') return;
        if ((key === 'min_price' || key === 'max_price') && parseFloat(v) <= 0) return;
        // Resolve human-readable term name for category/use-case slugs
        let termName = v;
        if (key === 'product_cat' && smFilters.catLabels && smFilters.catLabels[v]) {
          termName = smFilters.catLabels[v];
        } else if (key === 'use_case' && smFilters.useCaseLabels && smFilters.useCaseLabels[v]) {
          termName = smFilters.useCaseLabels[v];
        }
        $('<span class="filter-tag">')
          .attr({ 'data-key': key, 'data-val': v })
          .text((groupLabels[key] || key) + ': ' + termName)
          .append('<button class="filter-tag__remove" aria-label="Remove filter">\xd7</button>')
          .appendTo($activeTags);
      });
    });
  }

  function buildUrl(params, page) {
    const qs = new URLSearchParams();
    if (params.product_cat) {
      [].concat(params.product_cat).forEach(function (v) { qs.append('product_cat[]', v); });
    }
    if (params.use_case) {
      [].concat(params.use_case).forEach(function (v) { qs.append('use_case[]', v); });
    }
    if (params.min_price && Number(params.min_price) > 0) qs.set('min_price', params.min_price);
    if (params.max_price && Number(params.max_price) > 0) qs.set('max_price', params.max_price);
    if (params.orderby && params.orderby !== 'popularity') qs.set('orderby', params.orderby);
    if (page && page > 1) qs.set('paged', page);
    const str = qs.toString();
    return location.pathname + (str ? '?' + str : '');
  }

  function syncLangSwitcher() {
    var qs = location.search;
    document.querySelectorAll('.lang-switcher__option[data-base-url]').forEach(function (a) {
      a.href = a.dataset.baseUrl + qs;
    });
  }

  function applyStateToForm(params) {
    $('[name="product_cat[]"]').prop('checked', false);
    $('[name="use_case[]"]').prop('checked', false);
    [].concat(params.product_cat || []).forEach(function (v) {
      $('[name="product_cat[]"][value="' + v + '"]').prop('checked', true);
    });
    [].concat(params.use_case || []).forEach(function (v) {
      $('[name="use_case[]"][value="' + v + '"]').prop('checked', true);
    });
    if (params.min_price) $('[name="min_price"]').val(params.min_price);
    if (params.max_price) $('[name="max_price"]').val(params.max_price);
    if (params.orderby)   $('[name="orderby"]').val(params.orderby);
  }

  function doFilter(page, skipPush) {
    const params = getParams();
    buildActiveTags(params);

    // Sync browser URL unless called from popstate
    if (!skipPush) {
      history.pushState({ params: params, page: page || 1 }, '', buildUrl(params, page));
    }
    syncLangSwitcher();

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

  // Restore filters when navigating Back / Forward
  window.addEventListener('popstate', function (e) {
    if (e.state && e.state.params) {
      applyStateToForm(e.state.params);
      buildActiveTags(e.state.params);
      doFilter(e.state.page || 1, true);
    } else {
      // Landed back on an unfiltered URL — clear everything
      $('[name="product_cat[]"], [name="use_case[]"]').prop('checked', false);
      $('[name="min_price"], [name="max_price"]').val('');
      doFilter(1, true);
    }
  });

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

  // Auto-apply filters on page load when arriving via a filtered URL.
  // Covers pre-checked checkboxes (category/use-case) and pre-populated price inputs.
  // skipPush=true because the URL is already correct on direct navigation.
  $(function () {
    var hasChecked = $('.js-filter-input:checked').length > 0;
    var hasPrice   = parseFloat($('[name="min_price"]').val()) > 0 ||
                     parseFloat($('[name="max_price"]').val()) > 0;
    if (hasChecked || hasPrice) {
      doFilter(1, true);
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
