(() => {
  "use strict";

  function numberFromDataset(card, key) {
    return Number(card.dataset[key] || 0) || 0;
  }

  function visibleCards(root) {
    const grid = root.querySelector("#plans-grid");
    if (!grid) return [];

    return Array.from(grid.querySelectorAll(".plan-card:not(.plan-skeleton):not(.is-hidden)"));
  }

  function removeAutoRecommendation(cards) {
    cards.forEach((card) => {
      card.classList.remove("is-auto-recommended");
      if (card.dataset.recommended !== "1") {
        card.classList.remove("is-recommended");
      }

      card.querySelectorAll(".auto-recommended-badge").forEach((badge) => {
        badge.remove();
      });
    });
  }

  function maxValue(cards, key) {
    return cards.reduce((max, card) => Math.max(max, numberFromDataset(card, key)), 0);
  }

  function unlimitedAwareValue(card, key, max) {
    const value = numberFromDataset(card, key);
    return value === 0 && max > 0 ? max * 1.25 : value;
  }

  function mobileScore(card, max) {
    return (
      unlimitedAwareValue(card, "data", max.data) * 4 +
      unlimitedAwareValue(card, "minutes", max.minutes) * 0.025 +
      unlimitedAwareValue(card, "sms", max.sms) * 0.01 +
      numberFromDataset(card, "roaming") * 2
    );
  }

  function internetScore(card, max) {
    return (
      numberFromDataset(card, "speed") * 1.2 +
      numberFromDataset(card, "upload") * 0.8 +
      numberFromDataset(card, "tv") * 1.5 +
      numberFromDataset(card, "hd") * 2
    );
  }

  function scoreCard(card, category, max) {
    const price = Math.max(numberFromDataset(card, "price"), 1);
    const benefit = category === "prepay" || category === "abonament"
      ? mobileScore(card, max)
      : internetScore(card, max);
    const cost = category === "prepay"
      ? price / Math.max(numberFromDataset(card, "period"), 1)
      : price;

    return benefit / cost;
  }

  function bestCard(cards, category) {
    const max = {
      data: maxValue(cards, "data"),
      minutes: maxValue(cards, "minutes"),
      sms: maxValue(cards, "sms")
    };

    return cards.reduce((best, card) => {
      const score = scoreCard(card, category, max);
      const price = category === "prepay"
        ? numberFromDataset(card, "price") / Math.max(numberFromDataset(card, "period"), 1)
        : numberFromDataset(card, "price");

      if (!best || score > best.score) {
        return { card: card, score: score, price: price };
      }

      if (score === best.score && price < best.price) {
        return { card: card, score: score, price: price };
      }

      return best;
    }, null);
  }

  function isDefaultSort(root) {
    const sort = root.querySelector("#filter-sort");
    return !sort || sort.value === "default";
  }

  function promoteCard(root, card) {
    const grid = root.querySelector("#plans-grid");
    if (!grid || !card || !isDefaultSort(root)) return;

    const firstCard = grid.querySelector(".plan-card:not(.plan-skeleton):not(.is-hidden)");
    if (firstCard && firstCard !== card) {
      grid.insertBefore(card, firstCard);
    }
  }

  function addBadge(card) {
    const badge = document.createElement("span");
    badge.className = "recommended-badge auto-recommended-badge";
    badge.textContent = "Recomandat";

    card.classList.add("is-recommended", "is-auto-recommended");
    card.insertBefore(badge, card.firstChild);
  }

  function refresh(root) {
    root = root || document.querySelector("[data-plans-page]");
    if (!root) return;

    const cards = visibleCards(root);
    removeAutoRecommendation(cards);

    if (!cards.length) return;

    const manualRecommendation = cards.find((card) => card.dataset.recommended === "1");
    if (manualRecommendation) {
      promoteCard(root, manualRecommendation);
      return;
    }

    const result = bestCard(cards, root.dataset.category || "");
    if (result && result.card && result.score > 0) {
      addBadge(result.card);
      promoteCard(root, result.card);
    }
  }

  window.MolldSIMRecommendations = { refresh };
})();
