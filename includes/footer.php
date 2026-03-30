<?php
/**
 * Shared footer include.
 * Expects: $basePath (string, '' for root, '../' for pages/)
 */
?>
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>MolldSIM</h4>
                <p>Compară abonamente simplu și prietenos.</p>
            </div>
            <div class="footer-section">
                <h4>Secțiuni</h4>
                <ul>
                    <li><a href="<?= $basePath ?>pages/prepay.php">Prepay</a></li>
                    <li><a href="<?= $basePath ?>pages/abonament.php">Abonament</a></li>
                    <li><a href="<?= $basePath ?>pages/internet.php">Internet</a></li>
                    <li><a href="<?= $basePath ?>pages/internet-tv.php">Internet + TV</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Legal</h4>
                <ul>
                    <li>Termeni &amp; condiții</li>
                    <li>Confidențialitate</li>
                    <li>Cookie-uri</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <ul>
                    <li>Email: contact@molldsim.md</li>
                    <li>Telefon: +373 (xxx) xxx xxx</li>
                </ul>
            </div>
        </div>
        <div class="footer-copyright">
            &copy; 2025-2026 MolldSIM. Toate drepturile rezervate.
        </div>
    </footer>
