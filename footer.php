<!-- footer.php - NAPRAWIONY -->
<!-- Stopka -->
<footer id="kontakt">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kino 67</h3>
                <p><i class="fas fa-map-marker-alt"></i> ul. Kinowa 67</p>
                <p>00-000 Miasto</p>
                <p><i class="fas fa-phone"></i> tel: 123 456 789</p>
                <p><i class="fas fa-envelope"></i> kontakt@kino67.pl</p>
            </div>
            <div class="footer-section">
                <h3>Godziny otwarcia</h3>
                <p>Pon-Pt: 10:00-22:00</p>
                <p>Sob-Nd: 12:00-24:00</p>
                <h3 style="margin-top: 1.5rem;">Przydatne linki</h3>
                <p><a href="movies.php" style="color: var(--text-gray); text-decoration: none;">Repertuar</a></p>
                <p><a href="#regulamin" style="color: var(--text-gray); text-decoration: none;">Regulamin</a></p>
                <p><a href="#polityka" style="color: var(--text-gray); text-decoration: none;">Polityka prywatności</a></p>
            </div>
            <div class="footer-section">
                <h3>Social Media</h3>
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                    <a href="#" style="color: var(--accent-blue);"><i class="fab fa-facebook fa-2x"></i></a>
                    <a href="#" style="color: var(--accent-blue);"><i class="fab fa-instagram fa-2x"></i></a>
                    <a href="#" style="color: var(--accent-blue);"><i class="fab fa-twitter fa-2x"></i></a>
                    <a href="#" style="color: var(--accent-blue);"><i class="fab fa-youtube fa-2x"></i></a>
                </div>
                <h3>Newsletter</h3>
                <p style="color: var(--text-gray);">Zapisz się do newslettera</p>
                <div style="display: flex; margin-top: 0.5rem;">
                    <input type="email" placeholder="Twój email" 
                           style="flex: 1; padding: 0.5rem; background: var(--primary-dark); border: 1px solid #333; color: white; border-radius: 4px 0 0 4px;">
                    <button style="background: var(--primary-blue); color: white; border: none; padding: 0.5rem 1rem; cursor: pointer; border-radius: 0 4px 4px 0;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #333; color: var(--text-gray);">
            <p>&copy; <?php echo date('Y'); ?> Kino 67. Wszelkie prawa zastrzeżone.</p>
            <p>Projekt edukacyjny - system rezerwacji biletów kinowych</p>
        </div>
    </div>
</footer>
</body>
</html>