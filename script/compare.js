(function () {
  'use strict';

  var STORAGE_KEY = 'moldsim_compare';
  var MAX_PLANS   = 4;


  function loadPlans() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch (e) {
      return [];
    }
  }

  function savePlans(plans) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(plans));
  }

  function isSelected(planId) {
    return loadPlans().some(function (p) { return p.id === planId; });
  }

  function addPlan(plan) {
    var plans = loadPlans();
    if (plans.length >= MAX_PLANS) return false;
    if (plans.some(function (p) { return p.id === plan.id; })) return false;
    plans.push(plan);
    savePlans(plans);
    return true;
  }

  function removePlan(planId) {
    var plans = loadPlans().filter(function (p) { return p.id !== planId; });
    savePlans(plans);
  }

  function clearPlans() {
    localStorage.removeItem(STORAGE_KEY);
  }

  /* ── Extract plan data from a .plan-card DOM element ─────────────────── */

  function extractPlan(card) {
    var badge     = card.querySelector('.operator-badge');
    var nameEl    = card.querySelector('.plan-name');
    var priceEl   = card.querySelector('.plan-price');
    var periodEl  = card.querySelector('.plan-period');
    var btn       = card.querySelector('.plan-button');
    var features  = Array.from(card.querySelectorAll('.plan-features li'))
                        .map(function (li) { return li.textContent.trim(); });

    var operatorName  = badge  ? badge.textContent.trim()  : (card.dataset.operator || '');
    var operatorColor = badge  ? (badge.style.background || '#555') : '#555';

    // Build a stable ID from operator + name
    var planName = nameEl ? nameEl.textContent.trim() : '';
    var id = (operatorName + '_' + planName).replace(/\s+/g, '_').toLowerCase();

    return {
      id:            id,
      operator:      operatorName,
      operatorColor: operatorColor,
      name:          planName,
      price:         priceEl  ? priceEl.textContent.trim()  : card.dataset.price || '—',
      period:        periodEl ? periodEl.textContent.trim() : '',
      features:      features,
      link:          btn ? btn.href : '#',
      type:          card.dataset.speed ? 'internet' : 'mobile',
      dataVal:       card.dataset.data  || '0',
      speedVal:      card.dataset.speed || '0',
      priceVal:      card.dataset.price || '0',
    };
  }

  /* ── Plan pages: inject compare buttons + floating bar ──────────────── */

  function initPlanPages() {
    var cards = document.querySelectorAll('.plan-card');
    if (!cards.length) return;

    // Inject a compare button into each card
    cards.forEach(function (card) {
      var btn = document.createElement('button');
      btn.className = 'compare-btn';
      btn.textContent = '+ Adaugă la comparare';

      var plan = extractPlan(card);
      btn.dataset.planId = plan.id;

      // Restore selected state on load
      if (isSelected(plan.id)) {
        btn.classList.add('selected');
        btn.textContent = '✓ Adăugat';
      }

      btn.addEventListener('click', function () {
        if (isSelected(plan.id)) {
          // Deselect
          removePlan(plan.id);
          btn.classList.remove('selected');
          btn.textContent = '+ Adaugă la comparare';
        } else {
          var added = addPlan(plan);
          if (added) {
            btn.classList.add('selected');
            btn.textContent = '✓ Adăugat';
          } else {
            // Max reached — flash button
            btn.textContent = 'Max 4 planuri!';
            setTimeout(function () {
              btn.textContent = '+ Adaugă la comparare';
            }, 1500);
          }
        }
        updateBar();
      });

      card.appendChild(btn);
    });

    // Build floating compare bar
    var bar = document.createElement('div');
    bar.id = 'compare-bar';
    bar.innerHTML =
      '<span id="compare-bar-label"></span>' +
      '<div id="compare-bar-slots"></div>' +
      '<a id="compare-bar-go" href="compare.html">Compară acum</a>' +
      '<button id="compare-bar-clear">Șterge tot</button>';
    document.body.appendChild(bar);

    document.getElementById('compare-bar-clear').addEventListener('click', function () {
      clearPlans();
      // Deselect all buttons on the page
      document.querySelectorAll('.compare-btn.selected').forEach(function (b) {
        b.classList.remove('selected');
        b.textContent = '+ Adaugă la comparare';
      });
      updateBar();
    });

    updateBar();
  }

  function updateBar() {
    var bar = document.getElementById('compare-bar');
    if (!bar) return;

    var plans = loadPlans();
    var count = plans.length;

    // Sync button states (in case of cross-tab, etc.)
    document.querySelectorAll('.compare-btn').forEach(function (btn) {
      var selected = plans.some(function (p) { return p.id === btn.dataset.planId; });
      btn.classList.toggle('selected', selected);
      btn.textContent = selected ? '✓ Adăugat' : '+ Adaugă la comparare';
    });

    // Label
    var label = document.getElementById('compare-bar-label');
    if (label) {
      label.textContent = count === 0
        ? 'Niciun plan selectat'
        : count + ' plan' + (count > 1 ? 'uri' : '') + ' selectat' + (count > 1 ? 'e' : '');
    }

    // Slots
    var slots = document.getElementById('compare-bar-slots');
    if (slots) {
      slots.innerHTML = '';
      for (var i = 0; i < MAX_PLANS; i++) {
        var dot = document.createElement('span');
        dot.className = 'compare-slot' + (i < count ? ' filled' : '');
        slots.appendChild(dot);
      }
    }

    bar.classList.toggle('visible', count > 0);
  }

  function initComparePage() {
    var root = document.getElementById('compare-root');
    if (!root) return;

    renderCompareTable(root);

    // Listen for storage events (if user removes a plan in another tab)
    window.addEventListener('storage', function (e) {
      if (e.key === STORAGE_KEY) renderCompareTable(root);
    });
  }

  function renderCompareTable(root) {
    var plans = loadPlans();
    root.innerHTML = '';

    if (plans.length === 0) {
      root.innerHTML =
      `<div class="compare-empty">
          <p>Nu ai niciun plan selectat pentru comparare.</p>
        </div>`
        ;
      return;
    }

    /* ── Collect all feature keys ── */
    // We detect page type from the first plan
    var isInternet = plans[0] && plans[0].type === 'internet';

    // Build a row-by-row structure
    // Each plan's features are free-text. We normalise to known rows.
    var ROWS = isInternet
      ? [
          { key: 'price',    label: '💰 Preț lunar'   },
          { key: 'speed',    label: '🚀 Viteză'        },
          { key: 'traffic',  label: '📶 Trafic'        },
          { key: 'router',   label: '📡 Echipament'    },
          { key: 'extras',   label: '➕ Extra'          },
          { key: 'tv',       label: '📺 TV'            },
        ]
      : [
          { key: 'price',    label: '💰 Preț'          },
          { key: 'data',     label: '📊 Date internet' },
          { key: 'minutes',  label: '📞 Minute'        },
          { key: 'sms',      label: '💬 SMS-uri'       },
          { key: 'roaming',  label: '🌍 Roaming UE'    },
          { key: 'validity', label: '⏱️ Valabilitate'  },
        ];

    // Extract a value from a features array for a given key
    function findFeature(features, emoji) {
      var line = features.find(function (f) { return f.indexOf(emoji) === 0; });
      return line ? line.replace(emoji, '').replace(/^[\s:]+/, '') : '—';
    }

    function getRowValue(plan, key) {
      switch (key) {
        case 'price':
          return plan.price + ' ' + plan.period;
        case 'data':
          return findFeature(plan.features, '📊');
        case 'minutes':
          return findFeature(plan.features, '📞');
        case 'sms':
          return findFeature(plan.features, '💬');
        case 'roaming':
          return findFeature(plan.features, '🌍');
        case 'validity':
          return findFeature(plan.features, '⏱️');
        case 'speed':
          return findFeature(plan.features, '🚀');
        case 'traffic':
          return findFeature(plan.features, '📶');
        case 'router':
          return findFeature(plan.features, '📡');
        case 'extras':
          return findFeature(plan.features, '➕');
        case 'tv':
          return findFeature(plan.features, '📺');
        default:
          return '—';
      }
    }

    /* ── Find best numeric value per row (for highlighting) ── */
    function bestIndex(plans, key) {
      if (key === 'price') {
        // lower is better
        var vals = plans.map(function (p) { return parseInt(p.priceVal, 10) || 9999; });
        var min  = Math.min.apply(null, vals);
        return vals.map(function (v) { return v === min; });
      }
      if (key === 'data') {
        var vals = plans.map(function (p) { return parseInt(p.dataVal, 10) || 0; });
        var max  = Math.max.apply(null, vals);
        return vals.map(function (v) { return max > 0 && v === max; });
      }
      if (key === 'speed') {
        var vals = plans.map(function (p) { return parseInt(p.speedVal, 10) || 0; });
        var max  = Math.max.apply(null, vals);
        return vals.map(function (v) { return max > 0 && v === max; });
      }
      return plans.map(function () { return false; });
    }

    /* ── Build table ── */
    var table = document.createElement('table');
    table.className = 'compare-table';

    // -- THEAD --
    var thead = document.createElement('thead');
    var headRow = document.createElement('tr');

    // Label column
    var thLabel = document.createElement('th');
    thLabel.textContent = 'Caracteristică';
    headRow.appendChild(thLabel);

    // One column per plan
    plans.forEach(function (plan) {
      var th = document.createElement('th');
      th.innerHTML =
        '<div class="compare-plan-header">' +
          '<span class="operator-badge" style="background:' + plan.operatorColor + '">' + plan.operator + '</span>' +
          '<div class="compare-plan-name">' + plan.name + '</div>' +
          '<div class="compare-plan-price-big">' + plan.price + ' <span class="compare-plan-period">' + plan.period + '</span></div>' +
          '<button class="compare-remove-btn" data-plan-id="' + plan.id + '">✕ Elimină</button>' +
        '</div>';
      headRow.appendChild(th);
    });

    // Add plan slot (if room)
    if (plans.length < MAX_PLANS) {
      var thAdd = document.createElement('th');
      thAdd.className = 'compare-add-col';
      thAdd.innerHTML =
        '<a class="compare-add-btn" href="prepay.html">' +
          '<span class="compare-add-icon">＋</span>' +
          'Adaugă un plan' +
        '</a>';
      headRow.appendChild(thAdd);
    }

    thead.appendChild(headRow);
    table.appendChild(thead);

    // -- TBODY rows --
    var tbody = document.createElement('tbody');

    ROWS.forEach(function (row) {
      var tr = document.createElement('tr');

      var th = document.createElement('th');
      th.textContent = row.label;
      tr.appendChild(th);

      var best = bestIndex(plans, row.key);

      plans.forEach(function (plan, idx) {
        var td = document.createElement('td');
        td.textContent = getRowValue(plan, row.key);
        if (best[idx]) td.classList.add('best-value');
        tr.appendChild(td);
      });

      if (plans.length < MAX_PLANS) {
        tr.appendChild(document.createElement('td')); // empty add-col cell
      }

      tbody.appendChild(tr);
    });

    // -- CTA row --
    var ctaRow = document.createElement('tr');
    ctaRow.className = 'compare-cta-row';
    ctaRow.appendChild(document.createElement('th')); // empty label cell

    plans.forEach(function (plan) {
      var td = document.createElement('td');
      td.innerHTML =
        '<a href="' + plan.link + '" class="plan-button ' + plan.operator.toLowerCase() + '" target="_blank" ' +
        'style="display:inline-block;width:auto;padding:0.5rem 1.2rem;">Alege planul</a>';
      ctaRow.appendChild(td);
    });

    if (plans.length < MAX_PLANS) {
      ctaRow.appendChild(document.createElement('td'));
    }

    tbody.appendChild(ctaRow);
    table.appendChild(tbody);

    /* ── Wrap and render ── */
    var wrap = document.createElement('div');
    wrap.className = 'compare-table-wrap';
    wrap.appendChild(table);
    root.appendChild(wrap);

    // Clear all button
    var clearWrap = document.createElement('div');
    clearWrap.style.cssText = 'text-align:right;margin-top:1rem;';
    var clearBtn = document.createElement('button');
    clearBtn.className = 'filter-reset-btn';
    clearBtn.textContent = 'Șterge toate planurile';
    clearBtn.addEventListener('click', function () {
      clearPlans();
      renderCompareTable(root);
    });
    clearWrap.appendChild(clearBtn);
    root.appendChild(clearWrap);

    // Remove-plan buttons
    root.querySelectorAll('.compare-remove-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        removePlan(btn.dataset.planId);
        renderCompareTable(root);
      });
    });
  }

  function init() {
    if (document.getElementById('compare-root')) {
      initComparePage();
    } else {
      initPlanPages();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();