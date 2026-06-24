(() => {
  "use strict";

  const STORAGE_KEY = "moldsim_compare";
  const MAX_PLANS = 4;

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

  function loadPlans() {
    try {
      const plans = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
      return Array.isArray(plans) ? plans.slice(0, MAX_PLANS) : [];
    } catch (error) {
      return [];
    }
  }

  function savePlans(plans) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(plans.slice(0, MAX_PLANS)));
  }

  function removePlan(planId) {
    savePlans(loadPlans().filter((plan) => plan.id !== planId));
  }

  function clearPlans() {
    localStorage.removeItem(STORAGE_KEY);
  }

  function showToast(message, tone) {
    let region = document.getElementById("toast-region");
    if (!region) {
      region = document.createElement("div");
      region.id = "toast-region";
      region.className = "toast-region";
      region.setAttribute("aria-live", "polite");
      document.body.appendChild(region);
    }

    const toast = document.createElement("div");
    toast.className = "toast" + (tone ? " toast-" + tone : "");
    toast.textContent = message;
    region.appendChild(toast);
    window.setTimeout(() => {
      toast.classList.add("is-leaving");
      window.setTimeout(() => { toast.remove(); }, 220);
    }, 2600);
  }

  function isSelected(planId) {
    return loadPlans().some((plan) => plan.id === planId);
  }

  function extractPlan(card) {
    const badge = card.querySelector(".operator-badge");
    const nameEl = card.querySelector(".plan-name");
    const priceEl = card.querySelector(".plan-price");
    const periodEl = card.querySelector(".plan-period");
    const linkEl = card.querySelector(".plan-button");
    const features = Array.from(card.querySelectorAll(".plan-features li")).map((item) => item.textContent.trim());
    const operator = badge ? badge.textContent.trim() : card.dataset.operator || "";
    const name = nameEl ? nameEl.textContent.trim() : "";

    return {
      id: (operator + "_" + name).replace(/\s+/g, "_").toLowerCase(),
      operator: operator,
      operatorColor: badge ? badge.style.background || "#555" : "#555",
      name: name,
      price: priceEl ? priceEl.textContent.trim() : card.dataset.price || "—",
      period: periodEl ? periodEl.textContent.trim() : "",
      features: features,
      link: linkEl ? linkEl.href : "#",
      type: Number(card.dataset.speed || 0) > 0 ? "internet" : "mobile",
      dataVal: card.dataset.data || "0",
      speedVal: card.dataset.speed || "0",
      minutesVal: card.dataset.minutes || "0",
      smsVal: card.dataset.sms || "0",
      roamingVal: card.dataset.roaming || "0",
      uploadVal: card.dataset.upload || "0",
      tvVal: card.dataset.tv || "0",
      hdVal: card.dataset.hd || "0",
      priceVal: card.dataset.price || "0"
    };
  }

  function togglePlan(plan) {
    const plans = loadPlans();
    const existing = plans.findIndex((item) => item.id === plan.id);

    if (existing !== -1) {
      plans.splice(existing, 1);
      savePlans(plans);
      showToast("Plan eliminat din comparație.");
      return;
    }

    if (plans.length >= MAX_PLANS) {
      showToast("Poți compara maximum 4 planuri.", "warning");
      return;
    }

    plans.push(plan);
    savePlans(plans);
    showToast("Plan adăugat în comparație.", "success");
  }

  function ensureCompareBar() {
    let bar = document.getElementById("compare-bar");
    if (bar) return bar;

    bar = document.createElement("div");
    bar.id = "compare-bar";
    bar.hidden = true;
    bar.innerHTML =
      '<span id="compare-bar-label"></span>' +
      '<div id="compare-bar-slots" aria-hidden="true"></div>' +
      '<a id="compare-bar-go" href="compare.php">Compară acum</a>' +
      '<button id="compare-bar-clear" type="button">Șterge tot</button>';
    document.body.appendChild(bar);

    document.getElementById("compare-bar-clear").addEventListener("click", () => {
      clearPlans();
      updateBar();
      showToast("Selecția a fost golită.");
    });
    return bar;
  }

  function bindPlanCards(root) {
    root.querySelectorAll(".plan-card:not(.plan-skeleton)").forEach((card) => {
      if (card.querySelector(".compare-btn")) return;
      const plan = extractPlan(card);
      const button = document.createElement("button");
      button.className = "compare-btn";
      button.type = "button";
      button.dataset.planId = plan.id;
      button.addEventListener("click", () => {
        togglePlan(plan);
        updateBar();
      });
      card.appendChild(button);
    });
    syncPlanButtons();
  }

  function syncPlanButtons() {
    const plans = loadPlans();
    document.querySelectorAll(".compare-btn").forEach((button) => {
      const selected = plans.some((plan) => plan.id === button.dataset.planId);
      button.classList.toggle("selected", selected);
      button.textContent = selected ? "✓ Adăugat" : "+ Adaugă la comparare";
      button.setAttribute("aria-pressed", String(selected));
    });
  }

  function updateBar() {
    let bar = document.getElementById("compare-bar");
    if (!bar) return;
    const plans = loadPlans();
    const count = plans.length;
    syncPlanButtons();

    document.getElementById("compare-bar-label").textContent =
      count + " " + (count === 1 ? "plan selectat" : "planuri selectate");

    const slots = document.getElementById("compare-bar-slots");
    slots.innerHTML = "";
    for (let index = 0; index < MAX_PLANS; index += 1) {
      const dot = document.createElement("span");
      dot.className = "compare-slot" + (index < count ? " filled" : "");
      slots.appendChild(dot);
    }

    bar.hidden = count === 0;
    bar.classList.toggle("visible", count > 0);
  }

  function encodePlans(plans) {
    return window.btoa(unescape(encodeURIComponent(JSON.stringify(plans))));
  }

  function decodePlans(value) {
    try {
      const plans = JSON.parse(decodeURIComponent(escape(window.atob(value))));
      return Array.isArray(plans) ? plans.slice(0, MAX_PLANS) : [];
    } catch (error) {
      return [];
    }
  }

  function importSharedPlans() {
    const encoded = new URLSearchParams(window.location.search).get("compare");
    if (!encoded) return;
    const plans = decodePlans(encoded);
    if (plans.length) {
      savePlans(plans);
      showToast("Comparația partajată a fost încărcată.", "success");
    }
  }

  function featureValue(plan, hints) {
    const feature = (plan.features || []).find((item) => {
      const normalized = item.toLowerCase();
      return hints.some((hint) => normalized.indexOf(hint) !== -1);
    });
    if (!feature) return "-";
    const separator = feature.indexOf(":");
    return separator === -1 ? feature : feature.slice(separator + 1).trim();
  }

  function extraFeatures(plan) {
    const standardHints = [
      "date",
      "trafic",
      "download",
      "upload",
      "instalare",
      "router",
      "canale tv",
      "canale hd",
      "minute",
      "sms",
      "roaming",
      "vitez"
    ];

    return (plan.features || []).filter((feature) => {
      const normalized = feature.toLowerCase();
      return !standardHints.some((hint) => normalized.indexOf(hint) !== -1);
    });
  }

  function rowValue(plan, key) {
    if (key === "price") return plan.price + " " + plan.period;
    if (key === "data") return featureValue(plan, ["date", "trafic", "gb"]);
    if (key === "speed") return featureValue(plan, ["vitez", "mbps", "gbps"]);
    if (key === "upload") return featureValue(plan, ["upload"]);
    if (key === "installation") return featureValue(plan, ["instalare"]);
    if (key === "minutes") return featureValue(plan, ["minute"]);
    if (key === "sms") return featureValue(plan, ["sms"]);
    if (key === "roaming") return featureValue(plan, ["roaming"]);
    if (key === "equipment") return featureValue(plan, ["router", "echipament"]);
    if (key === "tv") return featureValue(plan, ["canale tv"]);
    if (key === "hd") return featureValue(plan, ["canale hd"]);
    if (key === "extras") return extraFeatures(plan).join(", ") || "-";
    return "-";
  }

  function hasValue(value) {
    const normalized = String(value || "").trim();
    return normalized !== "" && normalized !== "-";
  }

  function isUnlimitedValue(value) {
    return String(value || "").toLowerCase().indexOf("nelimitat") !== -1;
  }

  function parseComparableNumber(value, key) {
    const text = String(value || "").toLowerCase().replace(",", ".");
    const matches = text.match(/\d+(?:\.\d+)?/g);
    if (!matches) return 0;

    let number = Math.max.apply(null, matches.map(Number));
    if (Number.isNaN(number)) return 0;

    if ((key === "speed" || key === "upload") && text.indexOf("gbps") !== -1) {
      number *= 1000;
    }

    if ((key === "data" || key === "roaming") && text.indexOf("mb") !== -1 && text.indexOf("gb") === -1) {
      number /= 1024;
    }

    return number;
  }

  function comparableScore(plan, key) {
    const displayedValue = rowValue(plan, key);
    if (isUnlimitedValue(displayedValue)) return Infinity;

    const valueKeys = {
      data: "dataVal",
      speed: "speedVal",
      upload: "uploadVal",
      minutes: "minutesVal",
      sms: "smsVal",
      roaming: "roamingVal",
      tv: "tvVal",
      hd: "hdVal"
    };

    const storedValue = valueKeys[key] ? Number(plan[valueKeys[key]]) || 0 : 0;
    const parsedValue = parseComparableNumber(displayedValue, key);
    return Math.max(storedValue, parsedValue);
  }

  function rowsForPlans(plans) {
    const rowDefinitions = [
      { key: "price", label: "Preț", best: true, always: true },
      { key: "data", label: "Date internet", best: true },
      { key: "speed", label: "Viteză download", best: true },
      { key: "upload", label: "Viteză upload", best: true },
      { key: "installation", label: "Instalare", best: true },
      { key: "minutes", label: "Minute", best: true },
      { key: "sms", label: "SMS-uri", best: true },
      { key: "roaming", label: "Roaming", best: true },
      { key: "equipment", label: "Echipament" },
      { key: "tv", label: "Canale TV", best: true },
      { key: "hd", label: "Canale HD", best: true },
      { key: "extras", label: "Alte beneficii" }
    ];

    return rowDefinitions.filter((row) => {
      return row.always || plans.some((plan) => hasValue(rowValue(plan, row.key)));
    });
  }

  function mobileValue(plan, key, plans) {
    const displayedValue = rowValue(plan, key);
    const valueKeys = {
      data: "dataVal",
      minutes: "minutesVal",
      sms: "smsVal",
      roaming: "roamingVal"
    };
    const storedKey = valueKeys[key];
    const finiteValues = plans.map((candidate) => {
      const value = Number(candidate[storedKey]) || parseComparableNumber(rowValue(candidate, key), key);
      return Number.isFinite(value) ? value : 0;
    });
    const maxFinite = Math.max.apply(null, finiteValues);

    if (isUnlimitedValue(displayedValue)) {
      return maxFinite > 0 ? maxFinite * 1.25 : 1000;
    }

    return Number(plan[storedKey]) || parseComparableNumber(displayedValue, key) || 0;
  }

  function planValueScore(plan, plans) {
    const price = Math.max(Number(plan.priceVal) || parseComparableNumber(plan.price, "price") || 1, 1);

    if (plan.type === "internet" || Number(plan.speedVal || 0) > 0) {
      const download = Number(plan.speedVal) || comparableScore(plan, "speed");
      const upload = Number(plan.uploadVal) || comparableScore(plan, "upload");
      const tv = Number(plan.tvVal) || comparableScore(plan, "tv");
      const hd = Number(plan.hdVal) || comparableScore(plan, "hd");

      return (download * 1.4 + upload * 0.8 + tv * 1.2 + hd * 1.5) / price;
    }

    const data = mobileValue(plan, "data", plans);
    const minutes = mobileValue(plan, "minutes", plans);
    const sms = mobileValue(plan, "sms", plans);
    const roaming = mobileValue(plan, "roaming", plans);

    return (data * 5 + minutes * 0.03 + sms * 0.015 + roaming * 4) / price;
  }

  function bestPlanFlags(plans) {
    if (plans.length < 2) {
      return plans.map(() => false);
    }

    const scores = plans.map((plan) => planValueScore(plan, plans));
    const bestScore = Math.max.apply(null, scores);
    const winners = scores.filter((score) => score === bestScore);

    if (winners.length !== 1 || bestScore <= 0) {
      return plans.map(() => false);
    }

    return scores.map((score) => score === bestScore);
  }

  function shareComparison(plans) {
    const url = new URL(window.location.href);
    url.search = "";
    url.searchParams.set("compare", encodePlans(plans));

    if (navigator.share) {
      navigator.share({
        title: "Comparație MolldSIM",
        text: "Vezi planurile comparate pe MolldSIM.",
        url: url.toString()
      })["catch"](() => {});
      return;
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(url.toString()).then(() => {
        showToast("Linkul comparației a fost copiat.", "success");
      })["catch"](() => {
        showToast("Linkul nu a putut fi copiat.", "warning");
      });
      return;
    }

    const field = document.createElement("textarea");
    field.value = url.toString();
    field.setAttribute("readonly", "");
    field.style.position = "fixed";
    field.style.opacity = "0";
    document.body.appendChild(field);
    field.select();
    const copied = document.execCommand("copy");
    field.remove();
    showToast(copied ? "Linkul comparației a fost copiat." : "Linkul nu a putut fi copiat.", copied ? "success" : "warning");
  }

  function renderEmpty(root) {
    root.innerHTML =
      '<div class="compare-empty">' +
        "<h3>Comparația ta este goală</h3>" +
        "<p>Adaugă planuri din orice categorie pentru a vedea diferențele aici.</p>" +
        '<div class="empty-actions">' +
          '<a href="prepay.php" class="secondary-empty-link">Explorează Prepay</a>' +
          '<a href="abonament.php" class="secondary-empty-link">Explorează Abonament</a>' +
          '<a href="internet.php" class="secondary-empty-link">Explorează Internet</a>' +
          '<a href="internet-tv.php" class="secondary-empty-link">Explorează Internet + TV</a>' +
        "</div>" +
      "</div>";
  }

  function renderCompare(root) {
    const plans = loadPlans();
    root.innerHTML = "";
    if (!plans.length) {
      renderEmpty(root);
      return;
    }

    const rows = rowsForPlans(plans);

    const toolbar = document.createElement("div");
    toolbar.className = "compare-toolbar";
    toolbar.innerHTML =
      '<span><strong>' + plans.length + "</strong> din " + MAX_PLANS + " planuri selectate</span>" +
      '<div class="compare-toolbar-actions">' +
        '<button type="button" class="compare-share-btn">Partajează</button>' +
        '<button type="button" class="compare-clear-btn">Șterge toate</button>' +
      "</div>";
    root.appendChild(toolbar);

    const table = document.createElement("table");
    table.className = "compare-table compare-table-" + plans.length;
    table.style.setProperty("--compare-count", String(plans.length));
    const headerCells = plans.map((plan) => {
      return (
        "<th>" +
          '<div class="compare-plan-header">' +
            '<span class="operator-badge" style="background:' + escapeHtml(plan.operatorColor) + '">' + escapeHtml(plan.operator) + "</span>" +
            '<div class="compare-plan-name">' + escapeHtml(plan.name) + "</div>" +
            '<div class="compare-plan-price-big">' + escapeHtml(plan.price) + ' <span class="compare-plan-period">' + escapeHtml(plan.period) + "</span></div>" +
            '<button class="compare-remove-btn" type="button" data-plan-id="' + escapeHtml(plan.id) + '">× Elimină</button>' +
          "</div>" +
        "</th>"
      );
    }).join("");

    const bestPlan = bestPlanFlags(plans);
    const bodyRows = rows.map((row) => {
      const values = plans.map((plan) => rowValue(plan, row.key));
      const differs = new Set(values).size > 1;
      const cells = values.map((value, index) => {
        if (row.key === "extras" && hasValue(value)) {
          const items = extraFeatures(plans[index]).map((feature) => {
            return "<li>" + escapeHtml(feature) + "</li>";
          }).join("");
          return '<td class="' + (bestPlan[index] ? "best-value" : "") + '"><ul class="compare-feature-list">' + items + "</ul></td>";
        }
        return '<td class="' + (bestPlan[index] ? "best-value" : "") + '">' + escapeHtml(value) + "</td>";
      }).join("");
      return '<tr class="' + (differs ? "has-difference" : "") + '"><th>' +
        escapeHtml(row.label) + (differs ? '<span class="difference-badge">Diferă</span>' : "") +
        "</th>" + cells + "</tr>";
    }).join("");

    const ctaCells = plans.map((plan) => {
      return '<td><a href="' + escapeHtml(plan.link) + '" class="plan-button" style="--plan-color:' + escapeHtml(plan.operatorColor || "#d61f69") + ';" target="_blank" rel="noopener noreferrer">Vezi oferta</a></td>';
    }).join("");

    table.innerHTML =
      "<thead><tr><th>Caracteristică</th>" + headerCells + "</tr></thead>" +
      "<tbody>" + bodyRows + '<tr class="compare-cta-row"><th></th>' + ctaCells + "</tr></tbody>";

    const wrap = document.createElement("div");
    wrap.className = "compare-table-wrap";
    wrap.appendChild(table);
    root.appendChild(wrap);

    toolbar.querySelector(".compare-share-btn").addEventListener("click", () => {
      shareComparison(plans);
    });
    toolbar.querySelector(".compare-clear-btn").addEventListener("click", () => {
      clearPlans();
      renderCompare(root);
      showToast("Comparația a fost golită.");
    });
    root.querySelectorAll(".compare-remove-btn").forEach((button) => {
      button.addEventListener("click", () => {
        removePlan(button.dataset.planId);
        renderCompare(root);
        showToast("Plan eliminat din comparație.");
      });
    });
  }

  function initPlanPage() {
    if (!document.querySelector(".plan-card:not(.plan-skeleton)")) return;
    bindPlanCards(document);
    ensureCompareBar();
    updateBar();
  }

  function initComparePage() {
    const root = document.getElementById("compare-root");
    if (!root) return;
    importSharedPlans();
    renderCompare(root);
    window.addEventListener("storage", (event) => {
      if (event.key === STORAGE_KEY) renderCompare(root);
    });
  }

  function init() {
    if (document.getElementById("compare-root")) initComparePage();
    else initPlanPage();
  }

  window.MolldSIMCompare = {
    refreshPlanPage: () => {
      bindPlanCards(document);
      ensureCompareBar();
      updateBar();
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
