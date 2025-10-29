function toggleMenu() {
      const menu = document.getElementById('mobileMenu');
      const hamburger = document.querySelector('.hamburger');
      menu.classList.toggle('active');
      hamburger.classList.toggle('active');
    }

    const mobilPlans = {
      orange: `
        <div class="plan-card">
          <span class="badge">Recomandat</span>
          <h4 class="plan-name">Start</h4>
          <div class="plan-price-container">
            <span class="plan-price">95 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>10 GB</strong></li>
            <li>📞 Minute naționale: <strong>200</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Smart</h4>
          <div class="plan-price-container">
            <span class="plan-price">150 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>25 GB</strong></li>
            <li>📞 Minute naționale: <strong>nelimitat</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Max</h4>
          <div class="plan-price-container">
            <span class="plan-price">220 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>50 GB</strong></li>
            <li>📞 Minute naționale: <strong>nelimitat</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      moldcell: `
        <div class="plan-card">
          <h4 class="plan-name">Lite</h4>
          <div class="plan-price-container">
            <span class="plan-price">90 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>12 GB</strong></li>
            <li>📞 Minute naționale: <strong>200</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Plus</h4>
          <div class="plan-price-container">
            <span class="plan-price">140 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>30 GB</strong></li>
            <li>📞 Minute naționale: <strong>nelimitat</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Ultra</h4>
          <div class="plan-price-container">
            <span class="plan-price">210 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>60 GB</strong></li>
            <li>📞 Minute naționale: <strong>nelimitat</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `
    };

    const internetPlans = {
      starnet: `
        <div class="plan-card">
          <span class="badge">Recomandat</span>
          <h4 class="plan-name">Fiber 200</h4>
          <div class="plan-price-container">
            <span class="plan-price">149 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>200 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Fiber 500</h4>
          <div class="plan-price-container">
            <span class="plan-price">199 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Fiber 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">249 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      orange: `
        <div class="plan-card">
          <h4 class="plan-name">Home 100</h4>
          <div class="plan-price-container">
            <span class="plan-price">139 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>100 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Home 300</h4>
          <div class="plan-price-container">
            <span class="plan-price">179 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Home 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">239 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      moldtelecom: `
        <div class="plan-card">
          <h4 class="plan-name">Connect 200</h4>
          <div class="plan-price-container">
            <span class="plan-price">145 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>200 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <span class="badge">Recomandat</span>
          <h4 class="plan-name">Connect 500</h4>
          <div class="plan-price-container">
            <span class="plan-price">195 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Connect 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">245 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `
    };

    const bundlePlans = {
      moldtelecom: `
        <div class="plan-card">
          <h4 class="plan-name">TV Start + 200</h4>
          <div class="plan-price-container">
            <span class="plan-price">199 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>200 Mbps</strong></li>
            <li>📺 Canale TV: <strong>60+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">TV Plus + 500</h4>
          <div class="plan-price-container">
            <span class="plan-price">259 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
            <li>📺 Canale TV: <strong>120+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">TV Max + 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">309 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
            <li>📺 Canale TV: <strong>170+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      starnet: `
        <div class="plan-card">
          <h4 class="plan-name">Mini + 200</h4>
          <div class="plan-price-container">
            <span class="plan-price">189 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>200 Mbps</strong></li>
            <li>📺 Canale TV: <strong>50+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <span class="badge">Recomandat</span>
          <h4 class="plan-name">Family + 500</h4>
          <div class="plan-price-container">
            <span class="plan-price">239 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
            <li>📺 Canale TV: <strong>110+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Extra + 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">289 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
            <li>📺 Canale TV: <strong>160+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      orange: `
        <div class="plan-card">
          <h4 class="plan-name">TV S + 100</h4>
          <div class="plan-price-container">
            <span class="plan-price">195 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>100 Mbps</strong></li>
            <li>📺 Canale TV: <strong>70+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">TV M + 300</h4>
          <div class="plan-price-container">
            <span class="plan-price">245 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
            <li>📺 Canale TV: <strong>130+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">TV L + 1000</h4>
          <div class="plan-price-container">
            <span class="plan-price">295 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1 Gbps</strong></li>
            <li>📺 Canale TV: <strong>180+</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `
    };

    function switchMobilTab(provider) {
      document.getElementById('mobilPlans').innerHTML = mobilPlans[provider];
      
      const buttons = document.querySelectorAll('#abonamente .tabs:first-of-type .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }

    function switchInternetTab(provider) {
      document.getElementById('internetPlans').innerHTML = internetPlans[provider];
      
      const buttons = document.querySelectorAll('#abonamente > div:nth-child(2) .tabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }

    function switchBundleTab(provider) {
      document.getElementById('bundlePlans').innerHTML = bundlePlans[provider];
      
      const buttons = document.querySelectorAll('#abonamente > div:nth-child(3) .tabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }