function toggleMenu() {
      const menu = document.getElementById('mobileMenu');
      const hamburger = document.querySelector('.hamburger');
      menu.classList.toggle('active');
      hamburger.classList.toggle('active');
    }

    const mobilPlansPrepay = {
      orange: `
        <div class="plan-card">
            <h4 class="plan-name">Reîncărcare 30 MDL</h4>
            <div class="plan-price-container">
                <span class="plan-price">30 MDL</span>
                <span class="plan-period">/ reîncărcare</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>3 GB</strong></li>
                <li>📞 Minute naționale: <strong>100</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 30</button>
            <p class="plan-note">Valabile 15 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Reîncărcare 50 MDL</h4>
            <div class="plan-price-container">
                <span class="plan-price">50 MDL</span>
                <span class="plan-period">/ reîncărcare</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>10 GB</strong></li>
                <li>📞 Minute naționale: <strong>250</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 50</button>
            <p class="plan-note">Valabile 15 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Reîncărcare 60 MDL</h4>
            <div class="plan-price-container">
                <span class="plan-price">60 MDL</span>
                <span class="plan-period">/ reîncărcare</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>15 GB</strong></li>
                <li>📞 Minute naționale: <strong>500</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 60</button>
            <p class="plan-note">Valabile 15 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Reîncărcare 100 MDL</h4>
            <div class="plan-price-container">
                <span class="plan-price">100 MDL</span>
                <span class="plan-period">/ reîncărcare</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>100 GB</strong></li>
                <li>📞 Minute naționale: <strong>500</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 100</button>
            <p class="plan-note">Valabile 15 zile.</p>
        </div>
      `,
      moldcell: `
        <div class="plan-card">
          <h4 class="plan-name">Cartelă 49</h4>
          <div class="plan-price-container">
            <span class="plan-price">49 MDL</span>
            <span class="plan-period">/ 10 zile</span>
          </div>
          <ul class="plan-features">
            <li>🌐 Internet: <strong>25 GB</strong></li>
            <li>📞 Minute naționale: <strong>250</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">E-Abonament 80</h4>
          <div class="plan-price-container">
            <span class="plan-price">80 MDL</span>
            <span class="plan-period">/ 28 zile</span>
          </div>
          <ul class="plan-features">
            <li>🌐 Internet: <strong>80 GB</strong></li>
            <li>📞 Minute naționale: <strong>500</strong></li>
            <li>🎁 Bonus de bun venit: <strong>50 GB</strong> la înregistrare</li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>
        <div class="plan-card">
          <h4 class="plan-name">E-Abonament 100</h4>
          <div class="plan-price-container">
            <span class="plan-price">100 MDL</span>
            <span class="plan-period">/ 28 zile</span>
          </div>
          <ul class="plan-features">
            <li>🌐 Internet: <strong>80 GB</strong></li>
            <li>📞 Minute naționale: <strong>500</strong></li>
            <li>✈️ Roaming în UE: <strong>1 GB</strong> + <strong>50 minute</strong></li>
            <li>🎁 Bonus de bun venit: <strong>50 GB</strong> la înregistrare</li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>
      `,
      moldtelecom: `
        <div class="plan-card">
            <h4 class="plan-name">Start 25</h4>
            <div class="plan-price-container">
                <span class="plan-price">25 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>5 GB</strong></li>
                <li>📞 Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>50</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Connect 60</h4>
            <div class="plan-price-container">
                <span class="plan-price">60 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>20 GB</strong></li>
                <li>📞 Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>200</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Young 80</h4>
            <div class="plan-price-container">
                <span class="plan-price">80 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>30 GB</strong></li>
                <li>📞 Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>300</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Family 100</h4>
            <div class="plan-price-container">
                <span class="plan-price">100 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>40 GB</strong></li>
                <li>✈️ Roaming: <strong>1 GB România</strong></li>
                <li>📞♾️ Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>400</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Liberty 130</h4>
            <div class="plan-price-container">
                <span class="plan-price">130 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>50 GB</strong></li>
                <li>✈️ Roaming: <strong>1 GB UE</strong></li>
                <li>📞♾️ Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>500</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Travel 150</h4>
            <div class="plan-price-container">
                <span class="plan-price">150 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>100 GB</strong></li>
                <li>✈️ Roaming: <strong>2 GB UE</strong></li>
                <li>📞♾️ Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>600</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Unlimited 180</h4>
            <div class="plan-price-container">
                <span class="plan-price">180 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>100 GB</strong></li>
                <li>✈️ Roaming: <strong>5 GB UE</strong></li>
                <li>📞♾️ Minute în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>600</strong></li>
                <li>📞✈️ Minute Roaming: <strong>50</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Valabile 14 zile.</p>
        </div>
      ` 
    };

    const mobilPlansAbonament = {
      orange: `
        <div class="plan-card">
            <h4 class="plan-name">Start 100</h4>

            <div class="plan-price-container">
                <span class="plan-price">100 MDL</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>8 GB + 8 GB</strong> <em>(cu Family)</em></li>
                <li>📞♾️ Apeluri nelimitate: <strong>cu 3 Numere Favorite</strong></li>
                <li>📞 Minute naționale: <strong>200</strong></li>
                <li>🎁 Ofertă la conectare: <strong>2× mai mult Internet</strong> timp de <strong>24 luni</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Max 150</h4>

            <div class="plan-price-container">
                <span class="plan-price">105 MDL</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>15 GB + 15 GB</strong> <em>(cu Family)</em></li>
                <li>📞♾️ Apeluri în rețea: <strong>Nelimitate</strong></li>
                <li>📞 Minute naționale: <strong>450</strong></li>
                <li>🎁 Ofertă la conectare: <strong>2× mai mult Internet</strong> timp de <strong>24 luni</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Max 175</h4>

            <div class="plan-price-container">
                <span class="plan-price">122,50 MDL</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>25 GB + 25 GB</strong> <em>(cu Family)</em></li>
                <li>🌍 Internet în roaming RO: <strong>5,79 GB</strong></li>
                <li>📞♾️ Apeluri în rețea: <strong>Nelimitate</strong></li>
                <li>✈️ Minute naționale și roaming RO: <strong>750</strong></li>
                <li>🎁 Ofertă la conectare: <strong>2× mai mult Internet</strong> timp de <strong>24 luni</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Max 200</h4>

            <div class="plan-price-container">
                <span class="plan-price">140 lei</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>60 GB + 60 GB</strong> <em>(cu Family)</em></li>
                <li>🌍 Internet în roaming Europa: <strong>6,62 GB</strong></li>
                <li>📞♾️ Apeluri în rețea: <strong>Nelimitate</strong></li>
                <li>✈️ Minute naționale și roaming Europa: <strong>1000</strong></li>
                <li>🎁 Ofertă la conectare: <strong>2× mai mult Internet</strong> timp de <strong>24 luni</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Max 290</h4>

            <div class="plan-price-container">
                <span class="plan-price">145 lei</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>100 GB + 100 GB</strong> <em>(cu Family)</em></li>
                <li>🌍 Internet în roaming Europa: <strong>9,60 GB</strong></li>
                <li>📞♾️ Apeluri naționale și roaming Europa: <strong>Nelimitate</strong></li>
                <li>📞🌎 Minute internaționale: <strong>50</strong></li>
                <li>✈️ Roaming alte țări: <strong>30 minute + 500 MB</strong></li>
                <li>🎁 Ofertă la conectare: <strong>2× mai mult Internet</strong> timp de <strong>24 luni</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Max 400</h4>

            <div class="plan-price-container">
                <span class="plan-price">400 lei</span>
                <span class="plan-period">/ lunar</span>
            </div>

            <ul class="plan-features">
                <li>🌐 Internet: <strong>200 GB + 200 GB</strong> <em>(cu Family)</em></li>
                <li>🌍 Internet în roaming Europa: <strong>13,25 GB</strong></li>
                <li>📞♾️ Apeluri naționale și roaming Europa: <strong>Nelimitate</strong></li>
                <li>📞🌎 Minute internaționale: <strong>100</strong></li>
                <li>✈️ Roaming alte țări: <strong>30 minute + 500 MB</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--orange, #ff7a00);">
                Alege acest abonament
            </button>
        </div>
      `,
      moldcell: `
        <div class="plan-card">
            <h4 class="plan-name">Mixx 140</h4>

            <div class="plan-price-container">
                <span class="plan-price">100 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>

            <ul class="plan-features">
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitate</strong></li>
                <li>📞 Minute + SMS naționale: <strong>500 min și 100 SMS</strong></li>
                <li>🌐 Internet: <strong>12 GB</strong></li>
                <li>🎁 Opțiuni cadou: <strong>MIXX</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--purple, #a020f0);">
                Comandă
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Mixx 180</h4>

            <div class="plan-price-container">
                <span class="plan-price">125 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>

            <ul class="plan-features">
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitate</strong></li>
                <li>📞 Minute + SMS naționale și roaming RO: <strong>750 min și 100 SMS</strong></li>
                <li>🌐 Internet: <strong>30 GB</strong> (inclusiv <strong>5 GB</strong> roaming RO)</li>
                <li>📞🌎 Minute internaționale: <strong>100</strong> (RO + UA)</li>
                <li>🎁 Opțiuni cadou: <strong>MIXX</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--purple, #a020f0);">
                Comandă
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Mixx 200</h4>

            <div class="plan-price-container">
                <span class="plan-price">145 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>

            <ul class="plan-features">
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitate</strong></li>
                <li>📞 Minute + SMS naționale și roaming RO: <strong>1500 min și 100 SMS</strong></li>
                <li>🌐 Internet: <strong>50 GB</strong> (inclusiv <strong>7 GB</strong> roaming UE)</li>
                <li>📞🌎 Minute internaționale: <strong>100</strong> (UE + UA)</li>
                <li>🎁 Opțiuni cadou: <strong>MIXX</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--purple, #a020f0);">
                Comandă
            </button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Mixx 290</h4>

            <div class="plan-price-container">
                <span class="plan-price">250 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>

            <ul class="plan-features">
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitate</strong></li>
                <li>📞 Minute + SMS naționale și roaming UE: <strong>Nelimitate min. + 100 SMS</strong></li>
                <li>🌐 Internet: <strong>100 GB</strong> (inclusiv <strong>10 GB</strong> roaming UE)</li>
                <li>📞🌎 Minute internaționale: <strong>300</strong> (UE + UA)</li>
                <li>🎁 Opțiuni cadou: <strong>MIXX</strong></li>
            </ul>

            <button class="plan-button" style="background: var(--purple, #a020f0);">
                Comandă
            </button>
        </div>
      `,
      moldtelecom: `
        <div class="plan-card">
            <h4 class="plan-name">Start 120</h4>
            <div class="plan-price-container">
                <span class="plan-price">95 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>Nelimitat GB</strong></li>
                <li>🌎 Roaming: <strong>5 GB RO</strong></li>
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>350</strong></li>
                <li>📞🌎 Minute internaționale: <strong>35</strong></li>
                <li>💬 SMS naționale: <strong>35</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Flex 150</h4>
            <div class="plan-price-container">
                <span class="plan-price">115 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>Nelimitat GB</strong></li>
                <li>🌎 Roaming: <strong>8 GB RO</strong></li>
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>450</strong></li>
                <li>📞🌎 Minute internaționale: <strong>45</strong></li>
                <li>💬 SMS naționale: <strong>45</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Liberty 200</h4>
            <div class="plan-price-container">
                <span class="plan-price">140 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>Nelimitat GB</strong></li>
                <li>🌎 Roaming: <strong>5 GB RO + 5 GB EU</strong></li>
                <li>📞✈️ Minute în roaming UE: <strong>40</strong></li>
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>850</strong></li>
                <li>📞🌎 Minute internaționale: <strong>85</strong></li>
                <li>💬 SMS naționale: <strong>85</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Liberty 290+</h4>
            <div class="plan-price-container">
                <span class="plan-price">190 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>🌐 Internet: <strong>Nelimitat GB</strong></li>
                <li>🌎 Roaming: <strong>6 GB RO + 8 GB EU</strong></li>
                <li>📞✈️ Minute în roaming UE: <strong>50</strong></li>
                <li>📞♾️ Minute și SMS în rețea: <strong>Nelimitat</strong></li>
                <li>📞 Minute naționale: <strong>Nelimitat</strong></li>
                <li>📞🌎 Minute internaționale: <strong>50</strong></li>
                <li>💬 SMS naționale: <strong>Nelimitat</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
            <li>🔌 Tehnologie: <strong>Fibră optică</strong></li>
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Internet M</h4>
          <div class="plan-price-container">
            <span class="plan-price">180 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>600 Mbps</strong></li>
            <li>🔌 Tehnologie: <strong>Fibră optică</strong></li>
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Internet L</h4>
          <div class="plan-price-container">
            <span class="plan-price">240 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>1000 Mbps</strong></li>
            <li>🔌 Tehnologie: <strong>Fibră optică</strong></li>
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
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
            <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
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
            <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
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
            <li>📡 Router: <strong>Router Wi-Fi 7</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      moldtelecom: `
        <div class="plan-card">
          <h4 class="plan-name">Pachet 1</h4>
          <div class="plan-price-container">
              <span class="plan-price">130 MDL</span>
              <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
              <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
              <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Pachet 2</h4>
          <div class="plan-price-container">
              <span class="plan-price">150 MDL</span>
              <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
              <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
              <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 3</h4>
            <div class="plan-price-container">
                <span class="plan-price">200 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>1000 Mbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 4</h4>
            <div class="plan-price-container">
                <span class="plan-price">299 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>2.1 Gbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 5</h4>
            <div class="plan-price-container">
                <span class="plan-price">499 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>5.5 Gbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
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
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
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
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
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
            <li>☎️ Telefonie fixă: <strong>Nelimitat în rețea</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 24 luni. Prețurile sunt orientative.</p>
        </div>
      `,
      moldtelecom: `
        <div class="plan-card">
          <h4 class="plan-name">Pachet 1</h4>
          <div class="plan-price-container">
              <span class="plan-price">170 MDL</span>
              <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
              <li>⚡ Viteză internet: <strong>300 Mbps</strong></li>
              <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
              <li>📺 Canale TV: <strong>123 canale, 77 HD</strong></li>
              <li>🕐 Arhiva TV: <strong>Inclus</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
          <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 2</h4>
            <div class="plan-price-container">
                <span class="plan-price">200 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>500 Mbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
                <li>📺 Canale TV: <strong>172 canale, 111 HD</strong></li>
                <li>🕐 Arhiva TV: <strong>Inclus</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 3</h4>
            <div class="plan-price-container">
                <span class="plan-price">250 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>1000 Mbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
                <li>📺 Canale TV: <strong>172 canale, 111 HD</strong></li>
                <li>🕐 Arhiva TV: <strong>Inclus</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 4</h4>
            <div class="plan-price-container">
                <span class="plan-price">349 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>2.1 Gbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
                <li>📺 Canale TV: <strong>172 canale, 111 HD</strong></li>
                <li>🕐 Arhiva TV: <strong>Inclus</strong></li>
            </ul>
            <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
            <p class="plan-note">Contract 12 luni. Prețurile sunt orientative.</p>
        </div>

        <div class="plan-card">
            <h4 class="plan-name">Pachet 5</h4>
            <div class="plan-price-container">
                <span class="plan-price">549 MDL</span>
                <span class="plan-period">/ lună</span>
            </div>
            <ul class="plan-features">
                <li>⚡ Viteză internet: <strong>5.5 Gbps</strong></li>
                <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
                <li>📺 Canale TV: <strong>172 canale, 111 HD</strong></li>
                <li>🕐 Arhiva TV: <strong>Inclus</strong></li>
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
            <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
            <li>📺 Canale TV: <strong>158 / 118 HD</strong></li>
            <li>🕐 Arhiva TV: <strong>43 canale</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Fibră + TV ULTRA</h4>
          <div class="plan-price-container">
            <span class="plan-price">250 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>940 Mbps</strong></li>
            <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
            <li>📺 Canale TV: <strong>178 / 137 HD</strong></li>
            <li>🕐 Arhiva TV: <strong>50 canale</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--orange);">Alege acest plan</button>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Fibră + TV ULTRA FTTR</h4>
          <div class="plan-price-container">
            <span class="plan-price">400 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>2.2 Gbps</strong></li>
            <li>📡 Router: <strong>Router Wi-Fi 7</strong></li>
            <li>📺 Canale TV: <strong>178 / 137 HD</strong></li>
            <li>🕐 Arhiva TV: <strong>50 canale</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
        </div>

        <div class="plan-card">
          <h4 class="plan-name">Fibră + TV INFINITY</h4>
          <div class="plan-price-container">
            <span class="plan-price">600 MDL</span>
            <span class="plan-period">/ lună</span>
          </div>
          <ul class="plan-features">
            <li>⚡ Viteză internet: <strong>8 Gbps</strong></li>
            <li>📡 Router: <strong>Router Wi-Fi 6</strong></li>
            <li>📺 Canale TV: <strong>178 / 137 HD</strong></li>
            <li>🕐 Arhiva TV: <strong>50 canale</strong></li>
          </ul>
          <button class="plan-button" style="background: var(--purple);">Alege acest plan</button>
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
    }

    function switchMobilTabAbonament(provider) {
      document.getElementById('mobilPlansAbonament').innerHTML = mobilPlansAbonament[provider];
      
      const buttons = document.querySelectorAll('.tabs.abonamenteTabs .tab-button');
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
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