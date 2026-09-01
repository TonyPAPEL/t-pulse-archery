<?php
get_header();
?>
<section class="hero brand-hero">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Innovation pour le tir à l’arc', 'Innovation for archery')); ?></span>
            <h1>T-Pulse<br>Archery</h1>
            <p class="lead"><?php echo esc_html(tpulse_text('Des produits, des outils et des idées nés sur le pas de tir, pour mieux comprendre votre matériel et faire évoluer votre pratique.', 'Products, tools and ideas born on the shooting line to help you understand your equipment and develop your archery.')); ?></p>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/helitwist/')); ?>"><?php echo esc_html(tpulse_text('Découvrir HeliTwist', 'Discover HeliTwist')); ?></a>
                <a class="button secondary" href="<?php echo esc_url(tpulse_shop_url()); ?>"><?php echo esc_html(tpulse_text('Voir la boutique', 'Visit the shop')); ?></a>
            </div>
        </div>
        <div class="brand-stage">
            <img src="<?php echo tpulse_asset('logo-t-pulse.png'); ?>" alt="T-Pulse Archery">
            <p><?php echo esc_html(tpulse_text('Imaginé et développé en France', 'Designed and developed in France')); ?></p>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap feature-product">
        <div class="feature-product-media">
            <img src="<?php echo tpulse_asset('helitwist-3.png'); ?>" alt="<?php echo esc_attr(tpulse_text('HeliTwist Original monté sur un stabilisateur d’arc', 'HeliTwist Original fitted to a bow stabilizer')); ?>">
        </div>
        <div>
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Le produit fondateur', 'The founding product')); ?></span>
            <h2>HeliTwist Original</h2>
            <p class="lead"><?php echo esc_html(tpulse_text('Un amortisseur axial de 27 g pour stabilisateurs d’arc. Sa structure spiralée creuse est conçue pour atténuer les vibrations, adoucir la réaction du tir et limiter la prise au vent.', 'A 27 g axial damper for bow stabilizers. Its hollow spiral structure is designed to reduce vibration, soften shot reaction and limit wind drag.')); ?></p>
            <div class="feature-points">
                <span>27 g</span>
                <span>5/16, 1/4 <?php echo esc_html(tpulse_text('et', 'and')); ?> M8</span>
                <span><?php echo esc_html(tpulse_text('Conception T-Pulse', 'T-Pulse design')); ?></span>
            </div>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/helitwist/')); ?>"><?php echo esc_html(tpulse_text('Comprendre HeliTwist', 'Understand HeliTwist')); ?></a>
                <a class="button secondary" href="<?php echo esc_url(home_url('/product/helitwist-original/')); ?>"><?php echo esc_html(tpulse_text('Choisir mon modèle – 30,50 €', 'Choose my model – €30.50')); ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow"><?php echo esc_html(tpulse_text('T-Pulse aujourd’hui', 'T-Pulse today')); ?></span>
            <h2><?php echo esc_html(tpulse_text('Des projets utiles, du matériel aux logiciels.', 'Useful projects, from equipment to software.')); ?></h2>
            <p><?php echo esc_html(tpulse_text('T-Pulse Archery réunit des produits techniques, des ressources gratuites et des contenus pensés pour les archers.', 'T-Pulse Archery brings together technical products, free resources and content made for archers.')); ?></p>
        </div>
        <div class="brand-offers">
            <article class="offer-card">
                <div class="offer-visual product-visual"><img src="<?php echo tpulse_asset('helitwist-1.png'); ?>" alt="HeliTwist Original"></div>
                <span class="eyebrow"><?php echo esc_html(tpulse_text('Amortisseur', 'Damper')); ?></span>
                <h3>HeliTwist Original</h3>
                <p><?php echo esc_html(tpulse_text('Une nouvelle approche de l’amortissement axial, disponible en trois filetages.', 'A new approach to axial damping, available with three thread options.')); ?></p>
                <a class="text-link" href="<?php echo esc_url(home_url('/helitwist/')); ?>"><?php echo esc_html(tpulse_text('Voir la présentation complète →', 'View the full presentation →')); ?></a>
            </article>
            <article class="offer-card">
                <div class="offer-visual book-visual"><img src="<?php echo tpulse_asset('jeux-darchers-couverture.jpg'); ?>" alt="<?php echo esc_attr(tpulse_text('Couverture du livre Jeux d’archers', 'Archery Games book cover')); ?>"></div>
                <span class="eyebrow"><?php echo esc_html(tpulse_text('Livre', 'Book')); ?></span>
                <h3><?php echo esc_html(tpulse_text('Jeux d’archers', 'Archery Games')); ?></h3>
                <p><?php echo esc_html(tpulse_text('26 jeux pour varier vos entraînements, progresser et retrouver une pratique plus détendue. Livre broché, 79 pages.', '26 games to vary practice, improve and enjoy a more relaxed approach. French paperback, 79 pages.')); ?></p>
                <a class="text-link" href="<?php echo esc_url(home_url('/product/jeux-darchers/')); ?>"><?php echo esc_html(tpulse_text('Découvrir le livre →', 'Discover the book →')); ?></a>
            </article>
            <article class="offer-card">
                <div class="offer-visual future-visual"><span>&lt;/&gt;</span></div>
                <span class="eyebrow"><?php echo esc_html(tpulse_text('Ressources gratuites', 'Free resources')); ?></span>
                <h3><?php echo esc_html(tpulse_text('Logiciels et articles', 'Software and articles')); ?></h3>
                <p><?php echo esc_html(tpulse_text('Simulateurs, outils, articles techniques et futures applications Android créés pour les archers.', 'Simulators, tools, technical articles and future Android apps created for archers.')); ?></p>
                <a class="text-link" href="<?php echo esc_url(home_url('/ressources/')); ?>"><?php echo esc_html(tpulse_text('Explorer les ressources →', 'Explore resources →')); ?></a>
            </article>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap cta review-cta">
        <div><span class="eyebrow"><?php echo esc_html(tpulse_text('Retours d’archers', 'Archer reviews')); ?></span><h2><?php echo esc_html(tpulse_text('Votre expérience compte.', 'Your experience matters.')); ?></h2><p class="muted"><?php echo esc_html(tpulse_text('Consultez les avis ou partagez votre retour sur HeliTwist et le livre.', 'Read reviews or share your experience with HeliTwist and the book.')); ?></p></div>
        <a class="button secondary" href="<?php echo esc_url(home_url('/retours-archers/')); ?>"><?php echo esc_html(tpulse_text('Voir les avis', 'Read reviews')); ?></a>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Actualités', 'News')); ?></span>
            <h2><?php echo esc_html(tpulse_text('Nouveautés, essais et projets en cours.', 'News, testing and current projects.')); ?></h2>
            <p><?php echo esc_html(tpulse_text('Suivez les prochains produits, les choix de conception, les articles techniques et les coulisses de T-Pulse Archery.', 'Follow upcoming products, design decisions, technical articles and work behind the scenes at T-Pulse Archery.')); ?></p>
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
                        <a class="text-link" href="<?php the_permalink(); ?>"><?php echo esc_html(tpulse_text('Lire l’article →', 'Read the article →')); ?></a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <article class="post-card">
                    <time><?php echo esc_html(tpulse_text('En préparation', 'Coming soon')); ?></time>
                    <h3><?php echo esc_html(tpulse_text('Le carnet de bord T-Pulse arrive.', 'The T-Pulse journal is coming.')); ?></h3>
                    <p><?php echo esc_html(tpulse_text('Les prochains essais et projets seront publiés ici.', 'Upcoming tests and projects will be published here.')); ?></p>
                </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap cta">
        <div><span class="eyebrow">T-Pulse Archery</span><h2><?php echo esc_html(tpulse_text('Découvrez les produits disponibles.', 'Discover the available products.')); ?></h2><p class="muted"><?php echo esc_html(tpulse_text('HeliTwist Original, le livre Jeux d’archers et les prochaines innovations.', 'HeliTwist Original, the Archery Games book and upcoming innovations.')); ?></p></div>
        <a class="button" href="<?php echo esc_url(tpulse_shop_url()); ?>"><?php echo esc_html(tpulse_text('Visiter la boutique', 'Visit the shop')); ?></a>
    </div>
</section>
<?php
get_footer();
