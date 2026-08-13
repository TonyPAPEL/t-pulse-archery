<?php
get_header();
?>
<section class="hero helitwist-hero">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow">Technologie brevetée</span>
            <h1>HeliTwist<br>Original</h1>
            <p class="lead">L’amortisseur axial nouvelle génération pour stabilisateurs d’arc. Moins de vibrations, moins de choc et une stabilité pensée pour votre tir.</p>
            <div class="actions">
                <a class="button" href="<?php echo esc_url(home_url('/produit/helitwist-original/')); ?>">Acheter à 30,50 €</a>
                <a class="button secondary" href="#technologie">Comprendre la technologie</a>
            </div>
        </div>
        <div class="product-stage">
            <img src="<?php echo tpulse_asset('helitwist-3.png'); ?>" alt="HeliTwist Original monté sur un stabilisateur d’arc">
            <div class="floating-note"><strong>27 g</strong>Compatible 5/16, 1/4 et M8</div>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap">
        <div class="section-head"><span class="eyebrow">Pourquoi HeliTwist</span><h2>Une réaction plus propre après la décoche.</h2></div>
        <div class="grid-3">
            <article class="card"><span class="number">01</span><h3>Vibrations réduites</h3><p>La structure spiralée travaille dans l’axe du tir afin d’absorber les vibrations résiduelles.</p></article>
            <article class="card"><span class="number">02</span><h3>Moins de choc</h3><p>Une sensation d’impact adoucie pour davantage de confort pendant les longues séances.</p></article>
            <article class="card"><span class="number">03</span><h3>Stabilité améliorée</h3><p>Une structure ajourée qui limite la prise au vent et accompagne la stabilisation post-tir.</p></article>
        </div>
    </div>
</section>

<section class="section helitwist-story" id="histoire">
    <div class="wrap story-grid">
        <div class="story-media"><img src="<?php echo tpulse_asset('helitwist-1.png'); ?>" alt="Détail du HeliTwist Original"></div>
        <div class="story-copy">
            <span class="eyebrow">Pensé par un archer</span>
            <h2>Né d’un besoin réel sur le pas de tir.</h2>
            <p>Passionné de tir à l’arc et d’innovation technique, j’ai imaginé et développé cet amortisseur après avoir constaté les limites des solutions actuelles. Après près de deux ans d’utilisation du prototype, HeliTwist est prêt à être partagé.</p>
            <p>Contrairement aux systèmes traditionnels reposant principalement sur le flambage transversal d’un matériau élastique, HeliTwist utilise une structure spiralée creuse innovante permettant un amortissement dans l’axe du tir.</p>
            <ul class="specs">
                <li><strong>Poids</strong><span>27 g</span></li>
                <li><strong>Filetages</strong><span>5/16, 1/4, M8</span></li>
                <li><strong>Usage</strong><span>Stabilisateurs d’arc</span></li>
                <li><strong>Technologie</strong><span>Concept breveté</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="section alt helitwist-technology" id="technologie">
    <div class="wrap">
        <div class="section-head"><span class="eyebrow">Fonctionnement</span><h2>La compression spiralée, directement dans l’axe.</h2><p>Lors de la décoche, la structure se comprime pour réduire le transfert de vibration et adoucir la réaction perçue de l’arc.</p></div>
        <div class="gallery">
            <img class="tall" src="<?php echo tpulse_asset('helitwist-2.png'); ?>" alt="HeliTwist Original et son emballage">
            <img src="<?php echo tpulse_asset('HeliTwist_Concept_Repos.png'); ?>" alt="HeliTwist en position de repos">
            <img src="<?php echo tpulse_asset('HeliTwist_Concept_Compress.png'); ?>" alt="HeliTwist en compression">
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap cta">
        <div><span class="eyebrow">HeliTwist Original</span><h2>Prêt à transformer votre stabilisation ?</h2><p class="muted">Disponible dans la boutique T-Pulse Archery.</p></div>
        <a class="button" href="<?php echo esc_url(home_url('/produit/helitwist-original/')); ?>">Acheter HeliTwist</a>
    </div>
</section>
<?php
get_footer();
