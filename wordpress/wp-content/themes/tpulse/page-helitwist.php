<?php
get_header();
?>
<section class="hero helitwist-hero">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Amortissement axial', 'Axial damping')); ?></span>
            <h1>HeliTwist<br>Original</h1>
            <p class="lead"><?php echo esc_html(tpulse_text('Un amortisseur de 27 g conçu pour atténuer les vibrations et adoucir la réaction de votre arc, sans oublier l’équilibre de la stabilisation.', 'A 27 g damper designed to reduce vibration and soften bow reaction while preserving stabilizer balance.')); ?></p>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/produit/helitwist-original/')); ?>"><?php echo esc_html(tpulse_text('Choisir mon filetage – 30,50 €', 'Choose my thread – €30.50')); ?></a>
                <a class="button secondary" href="#technologie"><?php echo esc_html(tpulse_text('Comprendre le fonctionnement', 'How it works')); ?></a>
            </div>
        </div>
        <div class="product-stage">
            <img src="<?php echo tpulse_asset('helitwist-3.png'); ?>" alt="<?php echo esc_attr(tpulse_text('HeliTwist Original monté sur un stabilisateur d’arc', 'HeliTwist Original fitted to a bow stabilizer')); ?>">
            <div class="floating-note"><strong>27 g</strong><?php echo esc_html(tpulse_text('Filetages 5/16, 1/4 et M8', '5/16, 1/4 and M8 threads')); ?></div>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap">
        <div class="section-head"><span class="eyebrow"><?php echo esc_html(tpulse_text('Pourquoi HeliTwist', 'Why HeliTwist')); ?></span><h2><?php echo esc_html(tpulse_text('Une réaction plus douce après la décoche.', 'A softer reaction after release.')); ?></h2></div>
        <div class="grid-3">
            <article class="card"><span class="number">01</span><h3><?php echo esc_html(tpulse_text('Vibrations atténuées', 'Reduced vibration')); ?></h3><p><?php echo esc_html(tpulse_text('La structure spiralée est conçue pour travailler dans l’axe de la stabilisation et limiter le transfert des vibrations résiduelles.', 'The spiral structure is designed to work along the stabilizer axis and limit residual vibration transfer.')); ?></p></article>
            <article class="card"><span class="number">02</span><h3><?php echo esc_html(tpulse_text('Choc adouci', 'Softer shock')); ?></h3><p><?php echo esc_html(tpulse_text('Une réaction moins sèche pour davantage de confort et une sensation de tir mieux maîtrisée.', 'A less abrupt reaction for greater comfort and a more controlled shooting feel.')); ?></p></article>
            <article class="card"><span class="number">03</span><h3><?php echo esc_html(tpulse_text('Profil ajouré', 'Open profile')); ?></h3><p><?php echo esc_html(tpulse_text('La structure creuse laisse circuler l’air afin de limiter la prise au vent.', 'The hollow structure lets air pass through to limit wind drag.')); ?></p></article>
        </div>
    </div>
</section>

<section class="section helitwist-story" id="histoire">
    <div class="wrap story-grid">
        <div class="story-media"><img src="<?php echo tpulse_asset('helitwist-1.png'); ?>" alt="<?php echo esc_attr(tpulse_text('HeliTwist Original et son emballage', 'HeliTwist Original and its packaging')); ?>"></div>
        <div class="story-copy">
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Pensé par un archer', 'Designed by an archer')); ?></span>
            <h2><?php echo esc_html(tpulse_text('Né d’un besoin réel sur le pas de tir.', 'Born from a real need on the shooting line.')); ?></h2>
            <p><?php echo esc_html(tpulse_text('Passionné de tir à l’arc et de conception technique, j’ai développé HeliTwist après avoir cherché une autre manière de gérer la réaction de la stabilisation. Le prototype a accompagné près de deux années de pratique et d’ajustements avant cette première version.', 'With a passion for archery and technical design, I developed HeliTwist while looking for another way to manage stabilizer reaction. The prototype went through almost two years of use and refinement before this first version.')); ?></p>
            <p><?php echo esc_html(tpulse_text('Sa différence tient à une structure spiralée creuse qui se comprime dans l’axe du tir. L’objectif est d’atténuer les vibrations et le choc perçu tout en conservant un ensemble léger et moins sensible au vent.', 'Its key difference is a hollow spiral structure that compresses along the shot axis. The aim is to reduce vibration and perceived shock while keeping the assembly light and less affected by wind.')); ?></p>
            <ul class="specs">
                <li><strong><?php echo esc_html(tpulse_text('Poids', 'Weight')); ?></strong><span>27 g</span></li>
                <li><strong><?php echo esc_html(tpulse_text('Filetages', 'Threads')); ?></strong><span>5/16, 1/4, M8</span></li>
                <li><strong><?php echo esc_html(tpulse_text('Usage', 'Use')); ?></strong><span><?php echo esc_html(tpulse_text('Stabilisateurs d’arc', 'Bow stabilizers')); ?></span></li>
                <li><strong><?php echo esc_html(tpulse_text('Conception', 'Design')); ?></strong><span><?php echo esc_html(tpulse_text('Développée en France', 'Developed in France')); ?></span></li>
            </ul>
        </div>
    </div>
</section>

<section class="section alt helitwist-technology" id="technologie">
    <div class="wrap">
        <div class="section-head"><span class="eyebrow"><?php echo esc_html(tpulse_text('Fonctionnement', 'How it works')); ?></span><h2><?php echo esc_html(tpulse_text('La compression spiralée, directement dans l’axe.', 'Spiral compression, directly along the axis.')); ?></h2><p><?php echo esc_html(tpulse_text('À la décoche, la géométrie accompagne une compression axiale destinée à limiter le transfert des vibrations et à adoucir la réaction ressentie.', 'At release, the geometry supports axial compression designed to limit vibration transfer and soften the reaction you feel.')); ?></p></div>
        <div class="gallery">
            <img class="tall" src="<?php echo tpulse_asset('helitwist-2.png'); ?>" alt="<?php echo esc_attr(tpulse_text('HeliTwist Original et son emballage', 'HeliTwist Original and its packaging')); ?>">
            <img src="<?php echo tpulse_asset('HeliTwist_Concept_Repos.png'); ?>" alt="<?php echo esc_attr(tpulse_text('Schéma HeliTwist en position de repos', 'Diagram of HeliTwist at rest')); ?>">
            <img src="<?php echo tpulse_asset('HeliTwist_Concept_Compress.png'); ?>" alt="<?php echo esc_attr(tpulse_text('Schéma HeliTwist en compression', 'Diagram of HeliTwist under compression')); ?>">
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap cta">
        <div><span class="eyebrow">HeliTwist Original</span><h2><?php echo esc_html(tpulse_text('Prêt à essayer une autre réaction de stabilisation ?', 'Ready to try a different stabilizer reaction?')); ?></h2><p class="muted"><?php echo esc_html(tpulse_text('Choisissez le filetage adapté à votre matériel dans la boutique.', 'Choose the thread that matches your equipment in the shop.')); ?></p></div>
        <a class="button" href="<?php echo esc_url(home_url('/produit/helitwist-original/')); ?>"><?php echo esc_html(tpulse_text('Choisir mon HeliTwist', 'Choose my HeliTwist')); ?></a>
    </div>
</section>
<?php
get_footer();
