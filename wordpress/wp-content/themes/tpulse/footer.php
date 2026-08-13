<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
</main>
<footer class="site-footer">
    <div class="wrap">
        <div class="footer-grid">
            <div>
                <p class="footer-title">T-Pulse Archery</p>
                <p>Innovation pensée par un archer.<br>Conçu et développé en France.</p>
            </div>
            <div class="footer-links">
                <p class="footer-title">Découvrir</p>
                <a href="<?php echo esc_url(tpulse_shop_url()); ?>">Boutique</a>
                <a href="<?php echo esc_url(home_url('/#technologie')); ?>">La technologie</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
            </div>
            <div class="footer-links">
                <p class="footer-title">Informations</p>
                <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>">Mentions légales</a>
                <a href="<?php echo esc_url(home_url('/conditions-generales-de-vente/')); ?>">Conditions générales de vente</a>
                <a href="<?php echo esc_url(home_url('/politique-de-confidentialite/')); ?>">Confidentialité</a>
            </div>
        </div>
        <div class="copyright">© <?php echo esc_html(wp_date('Y')); ?> T-Pulse Archery. Tous droits réservés.</div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
