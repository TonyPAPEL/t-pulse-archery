<?php

if (!defined('ABSPATH')) {
    exit;
}

function tpulse_setup(): void {
    load_theme_textdomain('tpulse', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'primary' => __('Navigation principale', 'tpulse'),
        'footer' => __('Navigation de pied de page', 'tpulse'),
    ]);
}
add_action('after_setup_theme', 'tpulse_setup');

function tpulse_assets(): void {
    wp_enqueue_style('tpulse-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_script('tpulse-site', get_template_directory_uri() . '/assets/js/site.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'tpulse_assets');

function tpulse_requested_language(): string {
    $language = isset($_GET['lang']) ? sanitize_key(wp_unslash($_GET['lang'])) : '';
    if (in_array($language, ['fr', 'en'], true)) {
        return $language;
    }

    return isset($_COOKIE['tpulse_language']) && $_COOKIE['tpulse_language'] === 'en' ? 'en' : 'fr';
}

function tpulse_store_language(): void {
    if (!isset($_GET['lang'])) {
        return;
    }

    $language = sanitize_key(wp_unslash($_GET['lang']));
    if (in_array($language, ['fr', 'en'], true)) {
        setcookie('tpulse_language', $language, time() + YEAR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true);
        $_COOKIE['tpulse_language'] = $language;
    }
}
add_action('init', 'tpulse_store_language', 1);

function tpulse_is_english(): bool {
    return tpulse_requested_language() === 'en';
}

function tpulse_text(string $french, string $english): string {
    return tpulse_is_english() ? $english : $french;
}

function tpulse_language_url(string $language): string {
    return add_query_arg('lang', $language);
}

function tpulse_translate_book_content(string $content, WC_Product $product): string {
    if (!tpulse_is_english() || $product->get_sku() !== 'LIVRE-JEUX-DARCHERS') {
        return $content;
    }

    return '<h2>Improve your archery sessions while having fun</h2><p><strong>Archery Games</strong> brings together games and exercises designed to add variety to archery practice. It is intended for archers, coaches, clubs and groups who want to work on accuracy, consistency and concentration through a playful approach.</p><h3>Inside the book</h3><ul><li>Games that are easy to include in a practice session</li><li>Variations for different difficulty levels</li><li>Ideas for individual and group activities</li><li>An approach combining progress and enjoyment</li></ul><p>Physical book sold directly by T-Pulse Archery.</p>';
}
add_filter('woocommerce_product_get_description', 'tpulse_translate_book_content', 10, 2);

function tpulse_translate_book_short_description(string $content, WC_Product $product): string {
    if (tpulse_is_english() && $product->get_sku() === 'LIVRE-JEUX-DARCHERS') {
        return 'A collection of playful games and exercises to vary practice sessions, improve skills and enjoy archery together.';
    }

    return $content;
}
add_filter('woocommerce_product_get_short_description', 'tpulse_translate_book_short_description', 10, 2);

function tpulse_translate_book_name(string $name, WC_Product $product): string {
    return tpulse_is_english() && $product->get_sku() === 'LIVRE-JEUX-DARCHERS' ? 'Archery Games' : $name;
}
add_filter('woocommerce_product_get_name', 'tpulse_translate_book_name', 10, 2);

function tpulse_translate_frontend_html(string $html): string {
    if (!tpulse_is_english()) {
        return $html;
    }

    return strtr($html, [
        'Innovation pour le tir à l’arc' => 'Innovation for archery',
        'Des produits et des ressources imaginés par un archer, pour améliorer le matériel, le confort et la compréhension du tir.' => 'Products and resources created by an archer to improve equipment, comfort and understanding of the shot.',
        'Découvrir HeliTwist' => 'Discover HeliTwist',
        'Voir la boutique' => 'Visit the shop',
        'Innovation pensée par un archer' => 'Innovation designed by an archer',
        'Le produit fondateur' => 'The founding product',
        'Un amortisseur axial breveté pour stabilisateurs d’arc. Sa structure spiralée creuse réduit les vibrations, adoucit le choc du tir et limite la prise au vent.' => 'A patented axial damper for bow stabilizers. Its hollow spiral structure reduces vibration, softens shot reaction and limits wind drag.',
        'Technologie brevetée' => 'Patented technology',
        'Acheter à 30,50 €' => 'Buy for €30.50',
        'T-Pulse aujourd’hui' => 'T-Pulse today',
        'Des idées qui prennent forme.' => 'Ideas taking shape.',
        'T-Pulse Archery évolue autour de produits techniques, de ressources pour les archers et de nouvelles solutions actuellement en développement.' => 'T-Pulse Archery develops technical products, resources for archers and new solutions currently in development.',
        'Produit' => 'Product',
        'L’amortisseur qui a lancé T-Pulse Archery et une nouvelle approche de l’amortissement axial.' => 'The damper that launched T-Pulse Archery and a new approach to axial damping.',
        'Voir la présentation complète →' => 'View the full presentation →',
        'Livre' => 'Book',
        'Des idées pour perfectionner vos séances d’archerie tout en vous amusant. Disponible directement dans la boutique au prix de 15 €.' => 'Ideas to improve your archery sessions while having fun. Available directly from the shop for €15.',
        'Découvrir le livre →' => 'Discover the book →',
        'Perfectionnez vos séances d’archerie en vous amusant' => 'Improve your archery sessions while having fun',
        'Jeux d’archers rassemble des idées de jeux et d’exercices conçus pour renouveler les séances de tir à l’arc. Le livre s’adresse aux archers, entraîneurs, clubs et groupes souhaitant travailler la précision, la régularité et la concentration avec une approche ludique.' => 'Archery Games brings together games and exercises designed to add variety to archery practice. It is intended for archers, coaches, clubs and groups who want to work on accuracy, consistency and concentration through a playful approach.',
        'Dans ce livre' => 'Inside the book',
        'Des jeux faciles à intégrer à une séance' => 'Games that are easy to include in a practice session',
        'Des variantes pour adapter la difficulté' => 'Variations for different difficulty levels',
        'Des idées pour jouer seul ou en groupe' => 'Ideas for individual and group activities',
        'Une approche qui associe progression et plaisir' => 'An approach combining progress and enjoyment',
        'Livre physique vendu directement par T-Pulse Archery.' => 'Physical book sold directly by T-Pulse Archery.',
        'Ressources gratuites' => 'Free resources',
        'Logiciels et articles' => 'Software and articles',
        'Des outils, simulateurs, articles et futures applications Android créés pour les archers.' => 'Tools, simulators, articles and future Android apps created for archers.',
        'Voir les ressources →' => 'View resources →',
        'Découvrez tout l’univers de la marque.' => 'Discover the T-Pulse world.',
        'Produits disponibles, livre et prochaines innovations.' => 'Available products, book and upcoming innovations.',
        'Visiter la boutique' => 'Visit the shop',
        'Technologie brevetée' => 'Patented technology',
        'L’amortisseur axial nouvelle génération pour stabilisateurs d’arc. Moins de vibrations, moins de choc et une stabilité pensée pour votre tir.' => 'The next-generation axial damper for bow stabilizers. Less vibration, less shock and stability designed for your shot.',
        'Comprendre la technologie' => 'Understand the technology',
        'Pourquoi HeliTwist' => 'Why HeliTwist',
        'Une réaction plus propre après la décoche.' => 'A cleaner reaction after release.',
        'Vibrations réduites' => 'Reduced vibration',
        'La structure spiralée travaille dans l’axe du tir afin d’absorber les vibrations résiduelles.' => 'The spiral structure works along the shot axis to absorb residual vibration.',
        'Moins de choc' => 'Less shock',
        'Une sensation d’impact adoucie pour davantage de confort pendant les longues séances.' => 'A softer impact feel for greater comfort during long sessions.',
        'Stabilité améliorée' => 'Improved stability',
        'Une structure ajourée qui limite la prise au vent et accompagne la stabilisation post-tir.' => 'An open structure that limits wind drag and supports post-shot stabilization.',
        'Pensé par un archer' => 'Designed by an archer',
        'Né d’un besoin réel sur le pas de tir.' => 'Born from a real need on the shooting line.',
        'Fonctionnement' => 'How it works',
        'La compression spiralée, directement dans l’axe.' => 'Spiral compression, directly along the axis.',
        'Lors de la décoche, la structure se comprime pour réduire le transfert de vibration et adoucir la réaction perçue de l’arc.' => 'At release, the structure compresses to reduce vibration transfer and soften the bow reaction.',
        'Prêt à transformer votre stabilisation ?' => 'Ready to transform your stabilization?',
        'Disponible dans la boutique T-Pulse Archery.' => 'Available from the T-Pulse Archery shop.',
        'Acheter HeliTwist' => 'Buy HeliTwist',
        'Articles et téléchargements' => 'Articles and downloads',
        'Logiciels, outils, APK et articles créés pour les archers.' => 'Software, tools, APKs and articles created for archers.',
        'Découvrir →' => 'Discover →',
        'Toutes les ressources' => 'All resources',
        'Télécharger ou ouvrir la ressource' => 'Download or open the resource',
        'Ajouter au panier' => 'Add to cart',
        'Description' => 'Description',
        'Avis' => 'Reviews',
        'Produits similaires' => 'Related products',
    ]);
}

function tpulse_start_translation_buffer(): void {
    if (tpulse_is_english() && !is_admin()) {
        ob_start('tpulse_translate_frontend_html');
    }
}
add_action('template_redirect', 'tpulse_start_translation_buffer');

function tpulse_cart_count(): int {
    return function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function tpulse_shop_url(): string {
    return function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/boutique/');
}

function tpulse_asset(string $name): string {
    return esc_url(get_template_directory_uri() . '/assets/images/' . $name);
}

function tpulse_remove_sidebar(): void {
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}
add_action('wp', 'tpulse_remove_sidebar');

add_filter('woocommerce_enqueue_styles', function (array $styles): array {
    unset($styles['woocommerce-general']);
    return $styles;
});

function tpulse_register_resources(): void {
    register_post_type('tpulse_resource', [
        'labels' => [
            'name' => 'Ressources',
            'singular_name' => 'Ressource',
            'add_new_item' => 'Ajouter une ressource',
            'edit_item' => 'Modifier la ressource',
        ],
        'public' => true,
        'has_archive' => 'ressources',
        'rewrite' => ['slug' => 'ressources'],
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
    ]);

    register_taxonomy('resource_type', 'tpulse_resource', [
        'labels' => ['name' => 'Types de ressource', 'singular_name' => 'Type de ressource'],
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'type-ressource'],
    ]);
}
add_action('init', 'tpulse_register_resources');

function tpulse_flush_resource_routes(): void {
    if (get_option('tpulse_resource_routes_flushed') === '1') {
        return;
    }

    flush_rewrite_rules();
    update_option('tpulse_resource_routes_flushed', '1');
}
add_action('init', 'tpulse_flush_resource_routes', 100);

function tpulse_resource_meta_box(): void {
    add_meta_box('tpulse-resource-download', 'Téléchargement ou lien', 'tpulse_resource_meta_box_html', 'tpulse_resource', 'normal', 'high');
}
add_action('add_meta_boxes', 'tpulse_resource_meta_box');

function tpulse_resource_meta_box_html(WP_Post $post): void {
    wp_nonce_field('tpulse_resource_save', 'tpulse_resource_nonce');
    $url = get_post_meta($post->ID, '_tpulse_resource_url', true);
    $version = get_post_meta($post->ID, '_tpulse_resource_version', true);
    ?>
    <p><label for="tpulse-resource-url"><strong>Lien GitHub, page web ou URL du fichier</strong></label></p>
    <input class="widefat" id="tpulse-resource-url" name="tpulse_resource_url" type="url" value="<?php echo esc_attr($url); ?>" placeholder="https://...">
    <p><label for="tpulse-resource-version"><strong>Version ou plateforme</strong></label></p>
    <input class="widefat" id="tpulse-resource-version" name="tpulse_resource_version" type="text" value="<?php echo esc_attr($version); ?>" placeholder="Windows, Android, version 1.0...">
    <p>Pour proposer un APK directement, ajoutez-le dans <strong>Médias</strong>, puis collez son URL ci-dessus.</p>
    <?php
}

function tpulse_save_resource_meta(int $post_id): void {
    if (!isset($_POST['tpulse_resource_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tpulse_resource_nonce'])), 'tpulse_resource_save')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    update_post_meta($post_id, '_tpulse_resource_url', esc_url_raw(wp_unslash($_POST['tpulse_resource_url'] ?? '')));
    update_post_meta($post_id, '_tpulse_resource_version', sanitize_text_field(wp_unslash($_POST['tpulse_resource_version'] ?? '')));
}
add_action('save_post_tpulse_resource', 'tpulse_save_resource_meta');

function tpulse_allow_apk_upload(array $mimes): array {
    $mimes['apk'] = 'application/vnd.android.package-archive';
    return $mimes;
}
add_filter('upload_mimes', 'tpulse_allow_apk_upload');

function tpulse_seed_resources(): void {
    if (get_option('tpulse_resources_seeded') === '1') {
        return;
    }

    $post_id = wp_insert_post([
        'post_type' => 'tpulse_resource',
        'post_status' => 'publish',
        'post_title' => 'Archery Stabilizer Simulator',
        'post_excerpt' => 'Un simulateur gratuit pour explorer le comportement d’un stabilisateur d’arc.',
        'post_content' => '<p>Archery Stabilizer Simulator est un logiciel gratuit développé par T-Pulse pour aider les archers à mieux comprendre et explorer leur stabilisation.</p><p>Le code source, la documentation et les téléchargements disponibles sont accessibles sur GitHub.</p>',
    ]);

    if (!is_wp_error($post_id)) {
        wp_set_object_terms($post_id, 'Logiciel', 'resource_type');
        update_post_meta($post_id, '_tpulse_resource_url', 'https://github.com/TonyPAPEL/Archery-Stabilizer-Simulator');
        update_post_meta($post_id, '_tpulse_resource_version', 'Gratuit · GitHub');
    }

    flush_rewrite_rules();
    update_option('tpulse_resources_seeded', '1');
}
add_action('init', 'tpulse_seed_resources', 30);

function tpulse_resource_url(int $post_id): string {
    return (string) get_post_meta($post_id, '_tpulse_resource_url', true);
}

function tpulse_resource_version(int $post_id): string {
    return (string) get_post_meta($post_id, '_tpulse_resource_version', true);
}
