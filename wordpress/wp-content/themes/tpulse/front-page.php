<?php
get_header();
?>
<section class="hero brand-hero">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow">Innovation pour le tir à l’arc</span>
            <h1>T-Pulse<br>Archery</h1>
            <p class="lead">Des produits et des ressources imaginés par un archer, pour améliorer le matériel, le confort et la compréhension du tir.</p>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/helitwist/')); ?>">Découvrir HeliTwist</a>
                <a class="button secondary" href="<?php echo esc_url(tpulse_shop_url()); ?>">Voir la boutique</a>
            </div>
        </div>
        <div class="brand-stage">
            <img src="<?php echo tpulse_asset('logo-t-pulse.png'); ?>" alt="T-Pulse Archery">
            <p>Innovation pensée par un archer</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap cta review-cta">
        <div><span class="eyebrow">Avis clients</span><h2>Vous avez teste un produit T-Pulse ?</h2><p class="muted">Partagez votre retour sur HeliTwist ou le livre. Les avis sont relus avant publication.</p></div>
        <a class="button secondary" href="<?php echo esc_url(home_url('/retours-archers/')); ?>">Laisser un avis</a>
    </div>
</section>

<section class="section alt">
    <div class="wrap feature-product">
        <div class="feature-product-media">
            <img src="<?php echo tpulse_asset('helitwist-3.png'); ?>" alt="HeliTwist Original monté sur un stabilisateur d’arc">
        </div>
        <div>
            <span class="eyebrow">Le produit fondateur</span>
            <h2>HeliTwist Original</h2>
            <p class="lead">Un amortisseur axial breveté pour stabilisateurs d’arc. Sa structure spiralée creuse réduit les vibrations, adoucit le choc du tir et limite la prise au vent.</p>
            <div class="feature-points">
                <span>27 g</span>
                <span>5/16, 1/4 et M8</span>
                <span>Technologie brevetée</span>
            </div>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/helitwist/')); ?>">Découvrir HeliTwist</a>
                <a class="button secondary" href="<?php echo esc_url(home_url('/produit/helitwist-original/')); ?>">Acheter à 30,50 €</a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">T-Pulse aujourd’hui</span>
            <h2>Des idées qui prennent forme.</h2>
            <p>T-Pulse Archery évolue autour de produits techniques, de ressources pour les archers et de nouvelles solutions actuellement en développement.</p>
        </div>
        <div class="brand-offers">
            <article class="offer-card">
                <div class="offer-visual product-visual"><img src="<?php echo tpulse_asset('helitwist-1.png'); ?>" alt="HeliTwist Original"></div>
                <span class="eyebrow">Produit</span>
                <h3>HeliTwist Original</h3>
                <p>L’amortisseur qui a lancé T-Pulse Archery et une nouvelle approche de l’amortissement axial.</p>
                <a class="text-link" href="<?php echo esc_url(home_url('/helitwist/')); ?>">Voir la présentation complète →</a>
            </article>
            <article class="offer-card">
                <div class="offer-visual book-visual"><img src="<?php echo tpulse_asset('jeux-darchers-couverture.jpg'); ?>" alt="Couverture du livre Jeux d’archers"></div>
                <span class="eyebrow">Livre</span>
                <h3>Jeux d’archers</h3>
                <p>Des idées pour perfectionner vos séances d’archerie tout en vous amusant. Disponible directement dans la boutique au prix de 15 €.</p>
                <a class="text-link" href="<?php echo esc_url(home_url('/produit/jeux-darchers/')); ?>">Découvrir le livre →</a>
            </article>
            <article class="offer-card">
                <div class="offer-visual future-visual"><span>&lt;/&gt;</span></div>
                <span class="eyebrow">Ressources gratuites</span>
                <h3>Logiciels et articles</h3>
                <p>Des outils, simulateurs, articles et futures applications Android créés pour les archers.</p>
                <a class="text-link" href="<?php echo esc_url(home_url('/ressources/')); ?>">Voir les ressources →</a>
            </article>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Actualites</span>
            <h2>Nouveautes et projets en cours.</h2>
            <p>Un espace pour suivre les prochains produits, les essais, les articles techniques et les coulisses de T-Pulse Archery.</p>
        </div>
        <div class="posts-grid">
            <?php
            $latest_posts = new WP_Query(['posts_per_page' => 3, 'post_status' => 'publish']);
            if ($latest_posts->have_posts()) :
                while ($latest_posts->have_posts()) :
                    $latest_posts->the_post();
                    ?>
                    <article class="post-card">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">Lire -></a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <article class="post-card">
                    <time>En preparation</time>
                    <h3>Articles et nouveautes arrivent ici.</h3>
                    <p>Publiez vos projets, tests et actualites depuis l admin WordPress.</p>
                    <a class="text-link" href="<?php echo esc_url(home_url('/actualites/')); ?>">Voir les actualites -></a>
                </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap cta">
        <div><span class="eyebrow">T-Pulse Archery</span><h2>Découvrez tout l’univers de la marque.</h2><p class="muted">Produits disponibles, livre et prochaines innovations.</p></div>
        <a class="button" href="<?php echo esc_url(tpulse_shop_url()); ?>">Visiter la boutique</a>
    </div>
</section>
<?php
get_footer();
