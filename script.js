function toggleMenu() {
      const menu = document.getElementById('mobileMenu');
      const hamburger = document.querySelector('.hamburger');
      menu.classList.toggle('active');
      hamburger.classList.toggle('active');
    }

    const mobilPlansPrepay = {
      orange: `
        <div class="plan-card">
          <h4 class="plan-name">Lite</h4>
          <div class="plan-price-container">
            <span class="plan-price">95 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>📶 Internet mobil: <strong>10 GB</strong></li>
            <li>📞 Minute naționale: <strong>200</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
        </div>
      `,
      unite: `
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
        </div>
      ` 
    };

    const mobilPlansAbonament = {
      orange: `
        <div class="plan-card">
            <h4 class="plan-name">Lite</h4>
            <div class="plan-price-container">
              <span class="plan-price">95 MDL</span>
              <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
              <li>📶 Internet mobil: <strong>10 GB</strong></li>
              <li>📞 Minute naționale: <strong>200</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
              <span class="plan-price">95 MDL</span>
              <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
              <li>📶 Internet mobil: <strong>10 GB</strong></li>
              <li>📞 Minute naționale: <strong>200</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
      unite: `
        <div class="plan-card">
            <h4 class="plan-name">Lite</h4>
            <div class="plan-price-container">
              <span class="plan-price">95 MDL</span>
              <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
              <li>📶 Internet mobil: <strong>10 GB</strong></li>
              <li>📞 Minute naționale: <strong>200</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
      ` 
    };

    const internetPlans = {
      starnet: `
        <div class="plan-card">
          <h4 class="plan-name">Internet S</h4>
          <div class="plan-price-container">
            <span class="plan-price">140 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
            <li>⚡ Tehnologie: <strong>Fibră optică</strong></li>
            <li>⚡ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Internet M</h4>
          <div class="plan-price-container">
            <span class="plan-price">180 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>600 Mbps</strong></li>
            <li>⚡ Tehnologie: <strong>Fibră optică</strong></li>
            <li>⚡ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Internet L</h4>
          <div class="plan-price-container">
            <span class="plan-price">240 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1000 Mbps</strong></li>
            <li>⚡ Tehnologie: <strong>Fibră optică</strong></li>
            <li>⚡ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      orange: `
        <div class="plan-card">
          <h4 class="plan-name">Fibră OPTIM</h4>
          <div class="plan-price-container">
            <span class="plan-price">200 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
            <li>⚡ Router: <strong>Router Wi-Fi 6</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card highlight">
          <h4 class="plan-name">Fibră ULTRA</h4>
          <div class="plan-price-container">
            <span class="plan-price">250 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>940 Mbps</strong></li>
            <li>⚡ Router: <strong>Router Wi-Fi 6</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Fibră ULTRA FTTR</h4>
          <div class="plan-price-container">
            <span class="plan-price">450 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>2.2 Gbps</strong></li>
            <li>⚡ Router: <strong>Router Wi-Fi 7</strong></li>
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
      starnet: `
        <div class="plan-card">
          <h4 class="plan-name">Internet S + TV App</h4>
          <div class="plan-price-container">
            <span class="plan-price">185 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
            <li>📺 Canale TV: <strong>217 / 150 HD</strong></li>
            <li>📞 Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Internet M + TV App</h4>
          <div class="plan-price-container">
            <span class="plan-price">225 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>600 Mbps</strong></li>
            <li>📺 Canale TV: <strong>217 / 150 HD</strong></li>
            <li>📞 Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">Internet L + TV App</h4>
          <div class="plan-price-container">
            <span class="plan-price">285 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1000 Mbps</strong></li>
            <li>📺 Canale TV: <strong>217 / 150 HD</strong></li>
            <li>📞 Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
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
      orange: `
        <div class="plan-card">
          <h4 class="plan-name">Fibră + TV OPTIM</h4>
          <div class="plan-price-container">
            <span class="plan-price">190 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
            <li>📺 Canale TV: <strong>158</strong></li>
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

    document.getElementById('mobilPlansPrepay').innerHTML = mobilPlansPrepay["orange"];
    document.getElementById('mobilPlansAbonament').innerHTML = mobilPlansAbonament["orange"];
    document.getElementById('internetPlans').innerHTML = internetPlans["starnet"];
    document.getElementById('bundlePlans').innerHTML = bundlePlans["starnet"];

    function switchMobilTabPrepay(provider) {
      document.getElementById('mobilPlansPrepay').innerHTML = mobilPlansPrepay[provider];
      
      const buttons = document.querySelectorAll('.tabs.prepayTabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
      console.log('Switched to ' + provider);
    }

    function switchMobilTabAbonament(provider) {
      document.getElementById('mobilPlansAbonament').innerHTML = mobilPlansAbonament[provider];
      
      const buttons = document.querySelectorAll('.tabs.abonamenteTabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
      console.log('Switched to ' + provider);
    }

    function switchInternetTab(provider) {
      document.getElementById('internetPlans').innerHTML = internetPlans[provider];
      
      const buttons = document.querySelectorAll('#internet .tabs.internetTabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }

    function switchBundleTab(provider) {
      document.getElementById('bundlePlans').innerHTML = bundlePlans[provider];
      
      const buttons = document.querySelectorAll('#internet .tabs.bundleTabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }