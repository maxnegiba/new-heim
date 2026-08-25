</main>
<footer class="footer">
    <div class="container">
        <div class="footer-column">
            <h3>MB Bau Dienstleistungen</h3>
            <p>Ihr zuverlässiger Partner für Dacharbeiten, Fassadenarbeiten und Zaunbau in Berlin und Umgebung.</p>
        </div>
        <div class="footer-column">
            <h3>Kontakt</h3>
            <p><i class="fas fa-map-marker-alt"></i> Landsberger Allee 366, 12681 Berlin, Deutschland</p>
            <p><i class="fas fa-phone"></i> <a href="tel:+4917614122627">+49 176 141 22 627</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:info@hausmeistermichael-gmbh.de">info@hausmeistermichael-gmbh.de</a></p>
        </div>
        <div class="footer-column">
            <h3>Öffnungszeiten</h3>
            <p>Mo-Fr: 7:00 - 16:00</p>
            <p>Sa: Nach Vereinbarung</p>
            <p>Notdienst: 24/7 erreichbar</p>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2026 MB Bau Dienstleistungen. Alle Rechte vorbehalten.</p>
    </div>
</footer>

<div class="floating-buttons">
    <button class="main-button" aria-label="Kontaktoptionen öffnen"><i class="fas fa-phone"></i></button>
    <div class="sub-buttons">
        <a href="tel:+4917614122627" class="sub-button" aria-label="Anrufen"><i class="fas fa-phone"></i></a>
        <a href="https://wa.me/4917614122627" class="sub-button" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        <a href="mailto:info@hausmeistermichael-gmbh.de" class="sub-button" aria-label="E-Mail"><i class="fas fa-envelope"></i></a>
    </div>
</div>
</body>
</html>
<?php
if (ob_get_level() > 0) {
    $html = ob_get_clean();
    $legacy = [
        'Der Hausmeister Michael GmbH',
        'Dachdeckerei Michael',
        'MeisterDach GmbH',
        'MeisterDach',
        'michael GmbH',
        '+491626781242',
        '+49 162 678 12 42',
        '+49 162 678 1 242',
        '+49 162 6781242',
    ];
    $current = [
        'MB Bau Dienstleistungen',
        'MB Bau Dienstleistungen',
        'MB Bau Dienstleistungen',
        'MB Bau Dienstleistungen',
        'MB Bau Dienstleistungen',
        '+4917614122627',
        '+49 176 141 22 627',
        '+49 176 141 22 627',
        '+49 176 141 22 627',
    ];
    echo str_replace($legacy, $current, $html);
}
?>
