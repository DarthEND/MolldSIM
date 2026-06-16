(() => {
  "use strict";

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (char) => {
      return {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
      }[char];
    });
  }

  function debounce(callback, delay) {
    let timer;
    return function () {
      const args = arguments;
      window.clearTimeout(timer);
      timer = window.setTimeout(() => {
        callback.apply(null, args);
      }, delay);
    };
  }

  function clamp(value, min, max) {
    return Math.min(Math.max(Number(value), Number(min)), Number(max));
  }

  function capitalizeFirstLetter(value) {
    return String(value).replace(/\p{L}/u, (letter) => letter.toLocaleUpperCase("ro"));
  }

  function renderPlanCard(plan) {
    const featuresHtml = (plan.features || []).map((feature) => {
      return "<li>" + escapeHtml(capitalizeFirstLetter(feature)) + "</li>";
    }).join("");
    const recommendedBadge = Number(plan.is_recommended || 0) === 1
      ? '<span class="recommended-badge">Recomandat</span>'
      : "";

    return (
      '<article class="plan-card' + (Number(plan.is_recommended || 0) === 1 ? ' is-recommended' : '') + '"' +
        ' data-operator="' + escapeHtml(plan.operator_key) + '"' +
        ' data-price="' + escapeHtml(plan.price) + '"' +
        ' data-period="' + escapeHtml(plan.period || 1) + '"' +
        ' data-data="' + escapeHtml(plan.data_val) + '"' +
        ' data-speed="' + escapeHtml(plan.speed_val) + '"' +
        ' data-minutes="' + escapeHtml(plan.minutes_val || 0) + '"' +
        ' data-sms="' + escapeHtml(plan.sms_val || 0) + '"' +
        ' data-roaming="' + escapeHtml(plan.roaming_val || 0) + '"' +
        ' data-upload="' + escapeHtml(plan.upload_speed_mbps || 0) + '"' +
        ' data-tv="' + escapeHtml(plan.tv_channels || 0) + '"' +
        ' data-hd="' + escapeHtml(plan.hd_channels || 0) + '"' +
        ' data-recommended="' + escapeHtml(plan.is_recommended || 0) + '"' +
      ">" +
        recommendedBadge +
        '<span class="operator-badge" style="background:' + escapeHtml(plan.operator_color) + ';">' + escapeHtml(plan.operator) + "</span>" +
        '<div class="plan-name">' + escapeHtml(capitalizeFirstLetter(plan.name)) + "</div>" +
        '<div class="plan-price-container">' +
          '<div class="plan-price">' + escapeHtml(plan.price) + ' <span class="plan-currency">MDL</span></div>' +
          '<div class="plan-period">' + escapeHtml(plan.period_display) + "</div>" +
        "</div>" +
        '<ul class="plan-features">' + featuresHtml + "</ul>" +
        '<a href="' + escapeHtml(plan.link) + '" class="plan-button ' + escapeHtml(plan.operator_key) + '" style="--plan-color:' + escapeHtml(plan.operator_color) + ';" target="_blank" rel="noopener noreferrer">Alege planul</a>' +
      "</article>"
    );
  }

  function init() {
    const root = document.querySelector("[data-plans-page]");
    if (!root) return;

    const category = root.dataset.category || "";
    const apiUrl = root.dataset.api || "";
    const grid = document.getElementById("plans-grid");
    const countEl = document.getElementById("filter-count");
    const statusEl = document.getElementById("catalog-status");
    const noResults = document.getElementById("no-results");
    const defaultNoResultsText = noResults ? noResults.textContent.trim() : "";
    const operatorWrap = document.getElementById("operator-options");
    const sortEl = document.getElementById("filter-sort");
    const priceEl = document.getElementById("filter-price");
    const dataEl = document.getElementById("filter-data");
    const speedEl = document.getElementById("filter-speed");
    const uploadEl = document.getElementById("filter-upload");
    const minutesEl = document.getElementById("filter-minutes");
    const smsEl = document.getElementById("filter-sms");
    const roamingEl = document.getElementById("filter-roaming");
    const tvChannelsEl = document.getElementById("filter-tv-channels");
    const hdChannelsEl = document.getElementById("filter-hd-channels");
    const priceLabel = document.getElementById("price-label");
    const dataLabel = document.getElementById("data-label");
    const speedLabel = document.getElementById("speed-label");
    const resetBtn = document.getElementById("filter-reset");
    const drawer = document.getElementById("filter-aside");
    const drawerToggle = document.querySelector(".filter-drawer-toggle");
    const drawerClose = document.querySelector(".filter-drawer-close");
    const drawerBackdrop = document.querySelector(".filter-backdrop");
    const activeCountEl = document.querySelector(".filter-active-count");
    let abortController = null;
    let metaInitialized = false;
    let meta = null;
    let masonryResizeTimer = null;
    const rangeFilters = [
      { input: priceEl, key: "max_price", minKey: "min_price", maxKey: "max_price", fallback: "max", suffix: " MDL", step: 5, label: priceLabel, active: "notMax" },
      { input: dataEl, key: "min_data", minKey: "min_data", maxKey: "max_data", fallback: "min", suffix: " GB", step: category === "abonament" ? 10 : 5, label: dataLabel, active: "notMin" },
      { input: speedEl, key: "min_speed", minKey: "min_speed", maxKey: "max_speed", fallback: "min", suffix: " Mbps", step: 100, label: speedLabel, active: "notMin" },
      { input: uploadEl, key: "min_upload", minKey: "min_upload", maxKey: "max_upload", fallback: "min", suffix: " Mbps", step: 100, labelId: "upload-label", active: "notMin" },
      { input: minutesEl, key: "min_minutes", minKey: "min_minutes", maxKey: "max_minutes", fallback: "min", suffix: " min", step: 100, labelId: "minutes-label", active: "notMin" },
      { input: smsEl, key: "min_sms", minKey: "min_sms", maxKey: "max_sms", fallback: "min", suffix: " SMS", step: 100, labelId: "sms-label", active: "notMin" },
      { input: roamingEl, key: "min_roaming", minKey: "min_roaming", maxKey: "max_roaming", fallback: "min", suffix: " GB", step: 0.5, labelId: "roaming-label", active: "notMin" },
      { input: tvChannelsEl, key: "min_tv_channels", minKey: "min_tv_channels", maxKey: "max_tv_channels", fallback: "min", suffix: " canale", step: 10, labelId: "tv-channels-label", active: "notMin" },
      { input: hdChannelsEl, key: "min_hd_channels", minKey: "min_hd_channels", maxKey: "max_hd_channels", fallback: "min", suffix: " HD", step: 10, labelId: "hd-channels-label", active: "notMin" }
    ].filter((item) => {
      if (!item.input) return false;
      if (!item.label && item.labelId) item.label = document.getElementById(item.labelId);
      return true;
    });

    function layoutPlanCards() {
      if (!grid) return;
      const cards = Array.from(grid.querySelectorAll(".plan-card:not(.is-hidden)"));

      if (window.innerWidth < 560) {
        cards.forEach((card) => {
          card.style.gridRowEnd = "";
        });
        return;
      }

      const styles = window.getComputedStyle(grid);
      const rowHeight = parseFloat(styles.gridAutoRows);
      const rowGap = parseFloat(styles.rowGap);
      if (!rowHeight || Number.isNaN(rowHeight) || Number.isNaN(rowGap)) return;

      cards.forEach((card) => {
        card.style.gridRowEnd = "";
        const span = Math.ceil((card.getBoundingClientRect().height + rowGap) / (rowHeight + rowGap));
        card.style.gridRowEnd = "span " + span;
      });
    }

    function queryState() {
      return new URLSearchParams(window.location.search);
    }

    function selectedOperators() {
      return Array.from(document.querySelectorAll(".operator-checkbox:checked")).map((checkbox) => checkbox.value);
    }

    function allOperatorKeys() {
      return Array.from(document.querySelectorAll(".operator-checkbox")).map((checkbox) => checkbox.value);
    }

    function formatRangeValue(value, suffix) {
      const numeric = Number(value);
      const formatted = Number.isInteger(numeric) ? String(numeric) : String(numeric).replace(/0+$/, "").replace(/\.$/, "");
      return formatted + suffix;
    }

    function syncLabels() {
      rangeFilters.forEach((filter) => {
        if (filter.label) filter.label.textContent = formatRangeValue(filter.input.value, filter.suffix);
      });
    }

    function setSliderBounds(filter) {
      const input = filter.input;
      const min = Number(meta.bounds[filter.minKey] || 0);
      const max = Number(meta.bounds[filter.maxKey] || 0);
      const fallback = filter.fallback === "max" ? max : min;
      const state = queryState();
      const requested = state.has(filter.key) ? state.get(filter.key) : fallback;
      input.min = String(min);
      input.max = String(max);
      input.step = String(filter.step);
      input.value = String(clamp(requested, min, max));

      const labels = input.parentElement ? input.parentElement.querySelectorAll(".range-labels span") : [];
      if (labels.length === 2) {
        labels[0].textContent = formatRangeValue(min, filter.suffix);
        labels[1].textContent = formatRangeValue(max, filter.suffix);
      }
    }

    function renderOperators(operators) {
      if (!operatorWrap) return;
      const state = queryState();
      const requested = state.getAll("operator");
      const noneSelected = state.get("operators") === "none";

      operatorWrap.innerHTML = operators.map((operator) => {
        const checked = !noneSelected && (!requested.length || requested.indexOf(operator.key) !== -1);
        return (
          '<label class="operator-option">' +
            '<input type="checkbox" class="operator-checkbox" value="' + escapeHtml(operator.key) + '"' + (checked ? " checked" : "") + ">" +
            '<span class="op-dot" style="background:' + escapeHtml(operator.color) + ';"></span>' +
            escapeHtml(operator.name) +
          "</label>"
        );
      }).join("");

      operatorWrap.querySelectorAll(".operator-checkbox").forEach((checkbox) => {
        checkbox.addEventListener("change", fetchPlans);
      });
    }

    function initializeMeta(nextMeta) {
      if (!nextMeta || metaInitialized) return;
      meta = nextMeta;
      renderOperators(meta.operators || []);

      if (sortEl) {
        const requestedSort = queryState().get("sort");
        if (requestedSort && sortEl.querySelector('option[value="' + requestedSort + '"]')) {
          sortEl.value = requestedSort;
        }
      }

      rangeFilters.forEach(setSliderBounds);

      metaInitialized = true;
      syncLabels();
      updateActiveCount();
    }

    function updateUrl() {
      if (!metaInitialized) return;
      const params = new URLSearchParams();
      const selected = selectedOperators();
      const all = allOperatorKeys();

      if (sortEl && sortEl.value !== "default") params.set("sort", sortEl.value);
      rangeFilters.forEach((filter) => {
        const isActive = filter.active === "notMax"
          ? filter.input.value !== filter.input.max
          : filter.input.value !== filter.input.min;
        if (isActive) params.set(filter.key, filter.input.value);
      });

      if (selected.length === 0) {
        params.set("operators", "none");
      } else if (selected.length !== all.length) {
        selected.forEach((operator) => {
          params.append("operator", operator);
        });
      }

      const query = params.toString();
      window.history.replaceState({}, "", window.location.pathname + (query ? "?" + query : ""));
    }

    function updateActiveCount() {
      if (!activeCountEl || !metaInitialized) return;
      let count = 0;
      if (sortEl && sortEl.value !== "default") count += 1;
      rangeFilters.forEach((filter) => {
        const isActive = filter.active === "notMax"
          ? filter.input.value !== filter.input.max
          : filter.input.value !== filter.input.min;
        if (isActive) count += 1;
      });
      if (selectedOperators().length !== allOperatorKeys().length) count += 1;
      activeCountEl.hidden = count === 0;
      activeCountEl.textContent = count;
    }

    function renderSkeletons() {
      if (!grid) return;
      grid.setAttribute("aria-busy", "true");
      grid.innerHTML = Array.from({ length: 6 }, () => {
        return (
          '<div class="plan-card plan-skeleton" aria-hidden="true">' +
            '<span class="skeleton-line skeleton-badge"></span>' +
            '<span class="skeleton-line skeleton-title"></span>' +
            '<span class="skeleton-line skeleton-price"></span>' +
            '<span class="skeleton-line"></span>' +
            '<span class="skeleton-line"></span>' +
            '<span class="skeleton-line skeleton-button"></span>' +
          "</div>"
        );
      }).join("");
      window.requestAnimationFrame(layoutPlanCards);
    }

    function renderPlans(plans, total) {
      if (!grid) return;
      grid.innerHTML = plans.map(renderPlanCard).join("");
      grid.setAttribute("aria-busy", "false");

      if (noResults) {
        noResults.textContent = defaultNoResultsText;
        noResults.classList.toggle("visible", plans.length === 0);
        grid.appendChild(noResults);
      }

      if (countEl) countEl.textContent = plans.length + " din " + total + " planuri";
      if (statusEl) statusEl.textContent = plans.length + " rezultate afișate";

      if (window.MolldSIMCompare && typeof window.MolldSIMCompare.refreshPlanPage === "function") {
        window.MolldSIMCompare.refreshPlanPage();
      }

      if (window.MolldSIMRecommendations && typeof window.MolldSIMRecommendations.refresh === "function") {
        window.MolldSIMRecommendations.refresh(root);
      }

      window.requestAnimationFrame(() => {
        layoutPlanCards();
        window.requestAnimationFrame(layoutPlanCards);
      });
    }

    function renderError() {
      if (!grid || !noResults) return;
      grid.innerHTML = "";
      grid.setAttribute("aria-busy", "false");
      noResults.innerHTML = 'Planurile nu au putut fi încărcate. <button class="inline-retry" type="button">Încearcă din nou</button>';
      noResults.classList.add("visible");
      grid.appendChild(noResults);
      noResults.querySelector(".inline-retry").addEventListener("click", fetchPlans);
      if (statusEl) statusEl.textContent = "Eroare la încărcarea planurilor";
    }

    function requestParams() {
      const params = new URLSearchParams();
      params.set("category", category);

      if (metaInitialized) {
        if (sortEl) params.set("sort", sortEl.value);
        rangeFilters.forEach((filter) => {
          params.set(filter.key, filter.input.value);
        });

        const selected = selectedOperators();
        if (selected.length === 0) {
          params.append("operators[]", "__none__");
        } else {
          selected.forEach((operator) => {
            params.append("operators[]", operator);
          });
        }
      } else {
        const state = queryState();
        ["sort"].concat(rangeFilters.map((filter) => filter.key)).forEach((key) => {
          if (state.has(key)) params.set(key, state.get(key));
        });
        if (state.get("operators") === "none") {
          params.append("operators[]", "__none__");
        } else {
          state.getAll("operator").forEach((operator) => {
            params.append("operators[]", operator);
          });
        }
      }
      return params;
    }

    function fetchPlans() {
      if (!apiUrl || !category) return;
      if (abortController) abortController.abort();
      abortController = new AbortController();

      syncLabels();
      updateUrl();
      updateActiveCount();
      renderSkeletons();
      if (statusEl) statusEl.textContent = "Se încarcă planurile";

      fetch(apiUrl + "?" + requestParams().toString(), {
        headers: { Accept: "application/json" },
        signal: abortController.signal
      })
        .then((response) => {
          if (!response.ok) throw new Error("Catalog request failed");
          return response.json();
        })
        .then((payload) => {
          initializeMeta(payload.meta);
          updateUrl();
          renderPlans(payload.plans || [], payload.meta ? payload.meta.total : 0);
        })
        ["catch"]((error) => {
          if (error.name !== "AbortError") renderError();
        });
    }

    function openDrawer() {
      if (!drawer || !drawerToggle || !drawerBackdrop) return;
      drawer.classList.add("is-open");
      drawerToggle.setAttribute("aria-expanded", "true");
      drawerBackdrop.hidden = false;
      document.body.classList.add("filter-drawer-open");
      if (drawerClose) drawerClose.focus();
    }

    function closeDrawer() {
      if (!drawer || !drawerToggle || !drawerBackdrop) return;
      drawer.classList.remove("is-open");
      drawerToggle.setAttribute("aria-expanded", "false");
      drawerBackdrop.hidden = true;
      document.body.classList.remove("filter-drawer-open");
    }

    if (drawerToggle) drawerToggle.addEventListener("click", openDrawer);
    if (drawerClose) drawerClose.addEventListener("click", closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener("click", closeDrawer);
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && drawer && drawer.classList.contains("is-open")) {
        closeDrawer();
        drawerToggle.focus();
      }
    });

    if (sortEl) sortEl.addEventListener("change", fetchPlans);

    window.addEventListener("resize", () => {
      window.clearTimeout(masonryResizeTimer);
      masonryResizeTimer = window.setTimeout(layoutPlanCards, 120);
    });
    rangeFilters.forEach((filter) => {
      filter.input.addEventListener("input", debounce(fetchPlans, 180));
      filter.input.addEventListener("change", fetchPlans);
    });

    if (resetBtn) {
      resetBtn.addEventListener("click", () => {
        rangeFilters.forEach((filter) => {
          filter.input.value = filter.active === "notMax" ? filter.input.max : filter.input.min;
        });
        if (sortEl) sortEl.value = "default";
        document.querySelectorAll(".operator-checkbox").forEach((checkbox) => {
          checkbox.checked = true;
        });
        fetchPlans();
      });
    }

    window.addEventListener("popstate", () => {
      window.location.reload();
    });

    renderSkeletons();
    fetchPlans();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
