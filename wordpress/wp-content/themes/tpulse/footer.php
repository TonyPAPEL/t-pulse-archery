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
                <p><?php echo wp_kses_post(tpulse_text('Innovation pensée par un archer.<br>Conçu et développé en France.', 'Innovation designed by an archer.<br>Designed and developed in France.')); ?></p>
            </div>
            <div class="footer-links">
                <p class="footer-title"><?php echo esc_html(tpulse_text('Découvrir', 'Explore')); ?></p>
                <a href="<?php echo esc_url(tpulse_shop_url()); ?>"><?php echo esc_html(tpulse_text('Boutique', 'Shop')); ?></a>
                <a href="<?php echo esc_url(home_url('/helitwist/#technologie')); ?>"><?php echo esc_html(tpulse_text('La technologie', 'Technology')); ?></a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
            </div>
            <div class="footer-links">
                <p class="footer-title"><?php echo esc_html(tpulse_text('Informations', 'Information')); ?></p>
                <?php if (function_exists('wc_get_page_permalink')) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php echo esc_html(tpulse_text('Mon compte', 'My account')); ?></a>
                <?php endif; ?>
                <a href="<?php echo esc_url(home_url('/livraison-retours/')); ?>"><?php echo esc_html(tpulse_text('Livraison et retours', 'Delivery and returns')); ?></a>
                <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>"><?php echo esc_html(tpulse_text('Mentions légales', 'Legal notice')); ?></a>
                <a href="<?php echo esc_url(home_url('/conditions-generales-de-vente/')); ?>"><?php echo esc_html(tpulse_text('Conditions générales de vente', 'Terms and conditions')); ?></a>
                <a href="<?php echo esc_url(home_url('/politique-de-confidentialite/')); ?>"><?php echo esc_html(tpulse_text('Confidentialité', 'Privacy')); ?></a>
            </div>
        </div>
        <div class="copyright">© <?php echo esc_html(wp_date('Y')); ?> T-Pulse Archery. <?php echo esc_html(tpulse_text('Tous droits réservés.', 'All rights reserved.')); ?></div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
