(function () {

  function getCards() {
    return Array.from(document.querySelectorAll('.plan-card'));
  }

  function updateCount(visible, total) {
    const el = document.getElementById('filter-count');
    if (el) el.textContent = visible + ' din ' + total + ' planuri';
  }

  function applyFilters() {
    const cards = getCards();

    // Operator checkboxes — if none checked treat as "all"
    const checkedOps = Array.from(
      document.querySelectorAll('.operator-checkbox:checked')
    ).map(cb => cb.value);

    // Price slider — max price the user accepts
    const priceSlider = document.getElementById('filter-price');
    const maxPrice = priceSlider ? parseInt(priceSlider.value, 10) : Infinity;

    // Speed slider — min Mbps (internet / internet+tv pages)
    const speedSlider = document.getElementById('filter-speed');
    const minSpeed = speedSlider ? parseInt(speedSlider.value, 10) : 0;

    // Data slider — min GB (prepay / abonament / internet pages)
    const dataSlider = document.getElementById('filter-data');
    const minData = dataSlider ? parseInt(dataSlider.value, 10) : 0;

    // Sort
    const sortSelect = document.getElementById('filter-sort');
    const sortVal = sortSelect ? sortSelect.value : 'default';

    let visible = [];
    cards.forEach(card => {
      const op    = card.dataset.operator || '';
      const price = parseInt(card.dataset.price, 10) || 0;
      const speed = parseInt(card.dataset.speed, 10) || 0;
      const data  = parseInt(card.dataset.data,  10) || 0;

      const opOk    = checkedOps.length === 0 || checkedOps.includes(op);
      const priceOk = price <= maxPrice;
      const speedOk = speed >= minSpeed;
      const dataOk  = data  >= minData;

      if (opOk && priceOk && speedOk && dataOk) {
        card.classList.remove('is-hidden');
        visible.push(card);
      } else {
        card.classList.add('is-hidden');
      }
    });

    // Sort visible cards
    const grid = document.getElementById('plans-grid');
    if (grid) {
      visible.sort((a, b) => {
        const aPrice = parseInt(a.dataset.price, 10) || 0;
        const bPrice = parseInt(b.dataset.price, 10) || 0;
        const aSpeed = parseInt(a.dataset.speed, 10) || 0;
        const bSpeed = parseInt(b.dataset.speed, 10) || 0;
        const aData  = parseInt(a.dataset.data,  10) || 0;
        const bData  = parseInt(b.dataset.data,  10) || 0;

        if (sortVal === 'price-asc')  return aPrice - bPrice;
        if (sortVal === 'price-desc') return bPrice - aPrice;
        if (sortVal === 'speed-desc') return bSpeed - aSpeed;
        if (sortVal === 'data-desc')  return bData  - aData;
        return (parseInt(a.dataset.index,10)||0) - (parseInt(b.dataset.index,10)||0);
      });

      const noResults = document.getElementById('no-results');
      visible.forEach(card => grid.insertBefore(card, noResults));
      if (noResults) noResults.classList.toggle('visible', visible.length === 0);
    }

    updateCount(visible.length, cards.length);
  }

  function bindSliderLabel(sliderId, labelId, suffix) {
    const slider = document.getElementById(sliderId);
    const label  = document.getElementById(labelId);
    if (!slider || !label) return;
    const update = () => { label.textContent = slider.value + suffix; };
    slider.addEventListener('input', update);
    update();
  }

  function init() {
    // Record original order
    getCards().forEach((card, i) => { card.dataset.index = i; });

    // Bind operator checkboxes
    document.querySelectorAll('.operator-checkbox').forEach(cb => {
      cb.addEventListener('change', applyFilters);
    });

    // Bind sliders and sort select
    ['filter-price', 'filter-speed', 'filter-data', 'filter-sort'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', applyFilters);
      if (el) el.addEventListener('change', applyFilters);
    });

    // Live slider labels
    bindSliderLabel('filter-price', 'price-label', ' MDL');
    bindSliderLabel('filter-speed', 'speed-label', ' Mbps');
    bindSliderLabel('filter-data',  'data-label',  ' GB');

    // Reset
    const resetBtn = document.getElementById('filter-reset');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        document.querySelectorAll('.operator-checkbox').forEach(cb => cb.checked = true);

        const priceEl = document.getElementById('filter-price');
        if (priceEl) priceEl.value = priceEl.max;

        const speedEl = document.getElementById('filter-speed');
        if (speedEl) speedEl.value = speedEl.min;

        const dataEl = document.getElementById('filter-data');
        if (dataEl) dataEl.value = dataEl.min;

        const sortEl = document.getElementById('filter-sort');
        if (sortEl) sortEl.value = 'default';

        bindSliderLabel('filter-price', 'price-label', ' MDL');
        bindSliderLabel('filter-speed', 'speed-label', ' Mbps');
        bindSliderLabel('filter-data',  'data-label',  ' GB');

        applyFilters();
      });
    }

    applyFilters();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();