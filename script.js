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
        <a href="https://www.orange.md/ro/prepay/cartela" class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 30</a>
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 30</button> -->
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
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 50</button> -->
        <a href="https://www.orange.md/ro/prepay/cartela" class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 50</a>
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
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 60</button> -->
        <a href="https://www.orange.md/ro/prepay/cartela" class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 60</a>
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
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 100</button> -->
        <a href="https://www.orange.md/ro/prepay/cartela" class="plan-button" style="background: var(--orange, #ff7a00);">Reîncarcă 100</a>
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
      <a href="https://eshop.moldcell.md/ro/prepay/e-abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
      <a href="https://eshop.moldcell.md/ro/prepay/e-abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
      <a href="https://eshop.moldcell.md/ro/prepay/e-abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
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
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/prepay" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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

        <a href="https://www.orange.md/ro/abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">
            Alege acest abonament
        </button> -->
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

        <a href="https://www.orange.md/ro/abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">
            Alege acest abonament
        </button> -->
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

        <a href="https://www.orange.md/ro/abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">
            Alege acest abonament
        </button> -->
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

        <a href="https://www.orange.md/ro/abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">
            Alege acest abonament
        </button> -->
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

        <a href="https://www.orange.md/ro/abonament" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--orange, #ff7a00);">
            Alege acest abonament
        </button> -->
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

        <a href="https://eshop.moldcell.md/ro/abonament/conectare" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        
        <!-- <button class="plan-button" style="background: var(--purple, #a020f0);">
            Comandă
        </button> -->
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
        <a href="https://eshop.moldcell.md/ro/abonament/conectare" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple, #a020f0);">
            Comandă
        </button> -->
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

        <a href="https://eshop.moldcell.md/ro/abonament/conectare" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple, #a020f0);">
            Comandă
        </button> -->
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

        <a href="https://eshop.moldcell.md/ro/abonament/conectare" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple, #a020f0);">
            Comandă
        </button> -->
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
        <a href="https://new.moldtelecom.md/ro/mobile" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/mobile" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/mobile" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
        <a href="https://new.moldtelecom.md/ro/mobile" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
        <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
      <a href="https://www.starnet.md/ro/promo-reducere/internet" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
      <a href="https://www.starnet.md/ro/promo-reducere/internet" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--orange);">Alege acest plan</button> -->
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
      <a href="https://www.starnet.md/ro/promo-reducere/internet" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
      <!-- <button class="plan-button" style="background: var(--purple);">Alege acest plan</button> -->
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
      <a href="https://www.starnet.md/ro/promo-reducere/internet" class="plan-button" style="background: var(--orange, #ff7a00);">Alege acest plan</a>
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
  
  const buttons = document.querySelectorAll('#internet_tv .tabs.bundleTabs .tab-button');
  buttons.forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
  initHeroSlider();
  initRevealOnScroll();
  initParallax();          // very subtle
  initSmoothAnchors();     // offsets sticky header
});

/* ===== Hero Slider (vanilla) ===== */
function initHeroSlider() {
  const root = document.querySelector('.hero-slider');
  if (!root) return;

  const slides = Array.from(root.querySelectorAll('.hero-slide'));
  const bulletsWrap = root.querySelector('.hero-bullets');
  const prevBtn = root.querySelector('.hero-prev');
  const nextBtn = root.querySelector('.hero-next');
  let i = 0;
  let autoplayMs = parseInt(root.dataset.autoplay || '5000', 10);
  let timer = null;
  let paused = false;

  slides.forEach((s, idx) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.setAttribute('aria-label', `Slide ${idx + 1}`);
    b.addEventListener('click', () => goTo(idx, true));
    bulletsWrap.appendChild(b);
  });

  function applyActive() {
    slides.forEach((s, k) => s.classList.toggle('is-active', k === i));
    bulletsWrap.querySelectorAll('button').forEach((b, k) => {
      b.toggleAttribute('aria-current', k === i);
    });
  }

  function goTo(n, user = false) {
    i = (n + slides.length) % slides.length;
    applyActive();
    if (user) restart();
  }

  function next() { goTo(i + 1); }
  function prev() { goTo(i - 1); }

  function start() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    stop();
    timer = setInterval(next, autoplayMs);
  }
  function stop() { if (timer) clearInterval(timer); timer = null; }
  function restart() { if (!paused) start(); }

  // touch swipe
  let startX = 0;
  root.addEventListener('pointerdown', e => { startX = e.clientX; });
  root.addEventListener('pointerup', e => {
    const dx = e.clientX - startX;
    if (Math.abs(dx) > 40) (dx < 0 ? next() : prev());
  }, { passive: true });

  // pause on hover
  root.addEventListener('mouseenter', () => { paused = true; stop(); });
  root.addEventListener('mouseleave', () => { paused = false; start(); });

  // init
  applyActive();
  start();

  prevBtn.addEventListener('click', prev);
  nextBtn.addEventListener('click', next);
}

/* ===== Reveal on Scroll (fade + up) ===== */
function initRevealOnScroll() {
  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  const io = new IntersectionObserver((entries, obs) => {
    entries.forEach((e) => {
      if (e.isIntersecting) {
        e.target.classList.add('reveal-in');
        obs.unobserve(e.target);
      }
    });
  }, { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

  els.forEach(el => io.observe(el));
}

/* ===== Very subtle Parallax for hero images ===== */
function initParallax() {
  const imgEls = document.querySelectorAll('.hero-slide img');
  if (!imgEls.length) return;

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  let ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      const y = window.scrollY;
      // Cap max translate to ~16px for subtlety
      const offset = Math.max(-16, Math.min(16, y * 0.06));
      imgEls.forEach(img => img.style.setProperty('--parallax-y', `${offset}px`));
      ticking = false;
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

/* ===== Smooth Anchors with sticky header offset ===== */
function initSmoothAnchors() {
  const header = document.querySelector('header');
  const offset = () => (header ? header.getBoundingClientRect().height : 0) + 8;

  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      const target = document.querySelector(id);
      if (!target) return;

      e.preventDefault();
      const top = Math.max(0, target.getBoundingClientRect().top + window.scrollY - offset());
      window.scrollTo({ top, behavior: 'smooth' });

      // If there’s a mobile menu open in existing code, close it here (safe guard):
      const mobileMenu = document.querySelector('[data-mobile-menu]');
      if (mobileMenu && mobileMenu.classList.contains('open')) {
        mobileMenu.classList.remove('open');
      }
    });
  });
}