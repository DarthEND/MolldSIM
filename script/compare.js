(function () {
  'use strict';

  const STORAGE_KEY = 'moldsim_compare';
  const MAX_PLANS   = 4;


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
    let plans = loadPlans();
    if (plans.length >= MAX_PLANS) return false;
    if (plans.some(function (p) { return p.id === plan.id; })) return false;
    plans.push(plan);
    savePlans(plans);
    return true;
  }

  function removePlan(planId) {
    let plans = loadPlans().filter(function (p) { return p.id !== planId; });
    savePlans(plans);
  }

  function clearPlans() {
    localStorage.removeItem(STORAGE_KEY);
  }

  function extractPlan(card) {
    let badge     = card.querySelector('.operator-badge');
    let nameEl    = card.querySelector('.plan-name');
    let priceEl   = card.querySelector('.plan-price');
    let periodEl  = card.querySelector('.plan-period');
    let btn       = card.querySelector('.plan-button');
    let features  = Array.from(card.querySelectorAll('.plan-features li'))
                        .map(function (li) { return li.textContent.trim(); });

    let operatorName  = badge  ? badge.textContent.trim()  : (card.dataset.operator || '');
    let operatorColor = badge  ? (badge.style.background || '#555') : '#555';

    // ID unic din operator + nume
    let planName = nameEl ? nameEl.textContent.trim() : '';
    let id = `${operatorName}_${planName}`.replace(/\s+/g, '_').toLowerCase();

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

  function initPlanPages() {
    let cards = document.querySelectorAll('.plan-card');
    if (!cards.length) return;

    // Adaugă buton de comparare la fiecare card
    cards.forEach(function (card) {
      let btn = document.createElement('button');
      btn.className = 'compare-btn';
      btn.textContent = '+ Adaugă la comparare';

      let plan = extractPlan(card);
      btn.dataset.planId = plan.id;

      // Restabilește starea la încărcare
      if (isSelected(plan.id)) {
        btn.classList.add('selected');
        btn.textContent = '✓ Adăugat';
      }

      btn.addEventListener('click', function () {
        if (isSelected(plan.id)) {
          // Deselectează
          removePlan(plan.id);
          btn.classList.remove('selected');
          btn.textContent = '+ Adaugă la comparare';
        } else {
          let added = addPlan(plan);
          if (added) {
            btn.classList.add('selected');
            btn.textContent = '✓ Adăugat';
          } else {
            // Limită atinsă — afișează mesaj
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

    // Bara flotantă de comparare
    let bar = document.createElement('div');
    bar.id = 'compare-bar';
    bar.innerHTML =
      `<span id="compare-bar-label"></span>
      <div id="compare-bar-slots"></div>
      <a id="compare-bar-go" href="compare.html">Compară acum</a>
      <button id="compare-bar-clear">Șterge tot</button>`;
    document.body.appendChild(bar);

    document.getElementById('compare-bar-clear').addEventListener('click', function () {
      clearPlans();
      // Deselectează toate butoanele
      document.querySelectorAll('.compare-btn.selected').forEach(function (b) {
        b.classList.remove('selected');
        b.textContent = '+ Adaugă la comparare';
      });
      updateBar();
    });

    updateBar();
  }

  function updateBar() {
    let bar = document.getElementById('compare-bar');
    if (!bar) return;

    let plans = loadPlans();
    let count = plans.length;

    // Sincronizează butoanele (inclusiv alte tab-uri)
    document.querySelectorAll('.compare-btn').forEach(function (btn) {
      let selected = plans.some(function (p) { return p.id === btn.dataset.planId; });
      btn.classList.toggle('selected', selected);
      btn.textContent = selected ? '✓ Adăugat' : '+ Adaugă la comparare';
    });

    // Eticheta barei
    let label = document.getElementById('compare-bar-label');
    if (label) {
      label.textContent = count === 0
        ? 'Niciun plan selectat'
        : `${count} plan${count > 1 ? 'uri' : ''} selectat${count > 1 ? 'e' : ''}`;
    }

    // Punctele de slot
    let slots = document.getElementById('compare-bar-slots');
    if (slots) {
      slots.innerHTML = '';
      for (let i = 0; i < MAX_PLANS; i++) {
        let dot = document.createElement('span');
        dot.className = 'compare-slot' + (i < count ? ' filled' : '');
        slots.appendChild(dot);
      }
    }

    bar.classList.toggle('visible', count > 0);
  }

  function initComparePage() {
    let root = document.getElementById('compare-root');
    if (!root) return;

    renderCompareTable(root);

    // Actualizează la modificări din alt tab
    window.addEventListener('storage', function (e) {
      if (e.key === STORAGE_KEY) renderCompareTable(root);
    });
  }

  function renderCompareTable(root) {
    let plans = loadPlans();
    root.innerHTML = '';

    if (plans.length === 0) {
      root.innerHTML =
      `<div class="compare-empty">
          <p>Nu ai niciun plan selectat pentru comparare.</p>
        </div>`
        ;
      return;
    }

    // Detectăm tipul paginii din primul plan
    let isInternet = plans[0] && plans[0].type === 'internet';

    // Rândurile tabelului de comparare
    let ROWS = isInternet
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

    // Caută o caracteristică după emoji
    function findFeature(features, emoji) {
      let line = features.find(function (f) { return f.indexOf(emoji) === 0; });
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
 
    function bestIndex(plans, key) {
      if (key === 'price') {
        // preț mai mic = mai bun
        let vals = plans.map(function (p) { return parseInt(p.priceVal, 10) || 9999; });
        let min  = Math.min.apply(null, vals);
        return vals.map(function (v) { return v === min; });
      }
      if (key === 'data') {
        let vals = plans.map(function (p) { return parseInt(p.dataVal, 10) || 0; });
        let max  = Math.max.apply(null, vals);
        return vals.map(function (v) { return max > 0 && v === max; });
      }
      if (key === 'speed') {
        let vals = plans.map(function (p) { return parseInt(p.speedVal, 10) || 0; });
        let max  = Math.max.apply(null, vals);
        return vals.map(function (v) { return max > 0 && v === max; });
      }
      return plans.map(function () { return false; });
    }

    // Construim tabelul
    let table = document.createElement('table');
    table.className = 'compare-table';

    // Antet tabel
    let thead = document.createElement('thead');
    let headRow = document.createElement('tr');

    // Coloana cu etichete
    let thLabel = document.createElement('th');
    thLabel.textContent = 'Caracteristică';
    headRow.appendChild(thLabel);

    // O coloană per plan
    plans.forEach(function (plan) {
      let th = document.createElement('th');
      th.innerHTML =
        `<div class="compare-plan-header">
          <span class="operator-badge" style="background:${plan.operatorColor}">${plan.operator}</span>
          <div class="compare-plan-name">${plan.name}</div>
          <div class="compare-plan-price-big">${plan.price} <span class="compare-plan-period">${plan.period}</span></div>
          <button class="compare-remove-btn" data-plan-id="${plan.id}">✕ Elimină</button>
        </div>`;
      headRow.appendChild(th);
    });

    // Slot pentru plan nou (dacă mai e loc)
    if (plans.length < MAX_PLANS) {
      let thAdd = document.createElement('th');
      thAdd.className = 'compare-add-col';
      thAdd.innerHTML =
        `<a class="compare-add-btn" href="prepay.html">
          <span class="compare-add-icon">＋</span>
          Adaugă un plan
        </a>`;
      headRow.appendChild(thAdd);
    }

    thead.appendChild(headRow);
    table.appendChild(thead);

    // Rândurile tabelului
    let tbody = document.createElement('tbody');

    ROWS.forEach(function (row) {
      let tr = document.createElement('tr');

      let th = document.createElement('th');
      th.textContent = row.label;
      tr.appendChild(th);

      let best = bestIndex(plans, row.key);

      plans.forEach(function (plan, idx) {
        let td = document.createElement('td');
        td.textContent = getRowValue(plan, row.key);
        if (best[idx]) td.classList.add('best-value');
        tr.appendChild(td);
      });

      if (plans.length < MAX_PLANS) {
        tr.appendChild(document.createElement('td')); // celulă goală
      }

      tbody.appendChild(tr);
    });

    // Rândul cu butoane de activare
    let ctaRow = document.createElement('tr');
    ctaRow.className = 'compare-cta-row';
    ctaRow.appendChild(document.createElement('th')); // celulă goală

    plans.forEach(function (plan) {
      let td = document.createElement('td');
      td.innerHTML =
        `<a href="${plan.link}" class="plan-button ${plan.operator.toLowerCase()}" target="_blank" style="display:inline-block;width:auto;padding:0.5rem 1.2rem;">Alege planul</a>`;
      ctaRow.appendChild(td);
    });

    if (plans.length < MAX_PLANS) {
      ctaRow.appendChild(document.createElement('td'));
    }

    tbody.appendChild(ctaRow);
    table.appendChild(tbody);

    // Afișăm tabelul
    let wrap = document.createElement('div');
    wrap.className = 'compare-table-wrap';
    wrap.appendChild(table);
    root.appendChild(wrap);

    // Buton de ștergere totală
    let clearWrap = document.createElement('div');
    clearWrap.style.cssText = 'text-align:right;margin-top:1rem;';
    let clearBtn = document.createElement('button');
    clearBtn.className = 'filter-reset-btn';
    clearBtn.textContent = 'Șterge toate planurile';
    clearBtn.addEventListener('click', function () {
      clearPlans();
      renderCompareTable(root);
    });
    clearWrap.appendChild(clearBtn);
    root.appendChild(clearWrap);

    // Butoane de eliminare a planului
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