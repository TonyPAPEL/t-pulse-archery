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

function tpulse_english_product_content(string $sku): array {
    $products = [
        'HELITWIST-ORIGINAL' => [
            'name' => 'HeliTwist Original',
            'short' => 'A 27 g axial damper for bow stabilizers. Its hollow spiral structure is designed to reduce vibration and soften bow reaction. Patent application filed under reference FR2506128.',
            'description' => '<div class="tpulse-product-story"><p class="product-intro"><strong>HeliTwist Original</strong> was born on the shooting line from a simple need: a softer reaction after release without adding unnecessary weight to the stabilizer.</p><h2>Axial damping</h2><p>Instead of working mainly through sideways flex, its hollow spiral structure compresses along the stabilizer axis. This geometry is designed to limit vibration transfer, soften the perceived shock and allow air to pass through to reduce wind drag.</p><div class="product-benefits"><div><strong>Softer reaction</strong><span>A cleaner, more controlled post-shot feel.</span></div><div><strong>Only 27 g</strong><span>Damping designed to preserve stabilizer balance.</span></div><div><strong>Five configurations</strong><span>Choose 5/16, 1/4, M8 or a combined version.</span></div></div><h2>Choose the correct model</h2><p>Check the thread on your stabilizer or weights before ordering. Each variation has its own stock. If you are unsure, email <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> with a photo or the reference of your stabilizer.</p><ul class="product-facts"><li><strong>Use:</strong> bow stabilizers</li><li><strong>Weight:</strong> 27 g</li><li><strong>Threads:</strong> 5/16, 1/4, M8 or combined versions</li><li><strong>Industrial property:</strong> patent application filed, FR2506128</li></ul><p class="product-note">Feel and behaviour may vary with the bow, stabilizer setup and weights used.</p></div>',
        ],
        'LIVRE-JEUX-DARCHERS' => [
            'name' => 'Archery Games',
            'short' => '26 archery games to vary practice, work on accuracy and enjoy a more relaxed approach, alone, with friends or at a club. French paperback, 79 pages.',
            'description' => '<div class="tpulse-product-story"><p class="product-intro"><strong>Archery Games – Improve your shooting</strong> presents 26 games that break up repetition, create new challenges and help you improve without losing the enjoyment of shooting.</p><h2>Bring play back into practice</h2><p>Archery demands accuracy, concentration and consistency. Repeating the same end can, however, create pressure or monotony. The situations in this book give each session a different objective and encourage attention to the process rather than the score alone.</p><div class="product-benefits"><div><strong>26 varied games</strong><span>Ideas that are easy to include in practice.</span></div><div><strong>Solo or group use</strong><span>For archers, coaches, clubs and shooting partners.</span></div><div><strong>Playful progress</strong><span>Accuracy, adaptability, motivation and pressure management.</span></div></div><h2>What you will work on</h2><ul><li>Varying distances, objectives and shooting constraints.</li><li>Maintaining motivation and concentration throughout practice.</li><li>Approaching target panic in a more relaxed, less result-focused setting.</li><li>Developing adaptability and encouraging a smoother shot.</li></ul><ul class="product-facts"><li><strong>Format:</strong> paperback</li><li><strong>Language:</strong> French</li><li><strong>Length:</strong> 79 pages</li><li><strong>ISBN-13:</strong> 979-10-415-5471-3</li><li><strong>Size:</strong> 14.81 × 0.46 × 21.01 cm</li><li><strong>Weight:</strong> 159 g</li><li><strong>Sold by:</strong> shipped directly by T-Pulse Archery</li></ul><aside class="external-rating tpulse-amazon-rating-box"><div class="tpulse-amazon-rating"><span class="external-rating-score">4.5/5</span><span class="tpulse-amazon-stars" aria-label="4.5 stars out of 5">★★★★★</span><span>21 Amazon ratings</span></div><p>The book has already received reader feedback on Amazon. Read those comments alongside reviews left directly on the T-Pulse Archery shop.</p><a href="https://www.amazon.fr/Jeux-darchers-Perfectionnez-darcherie-samuser/dp/B0DLWNRBPQ#customerReviews" target="_blank" rel="noopener external nofollow">Read Amazon reviews</a></aside></div>',
        ],
    ];

    return $products[$sku] ?? [];
}

function tpulse_translate_product_content(string $content, WC_Product $product): string {
    $translation = tpulse_is_english() ? tpulse_english_product_content($product->get_sku()) : [];
    return $translation['description'] ?? $content;
}
add_filter('woocommerce_product_get_description', 'tpulse_translate_product_content', 10, 2);

function tpulse_translate_product_short_description(string $content, WC_Product $product): string {
    if ($product->is_type('variation')) {
        return $content;
    }

    $translation = tpulse_is_english() ? tpulse_english_product_content($product->get_sku()) : [];
    return $translation['short'] ?? $content;
}
add_filter('woocommerce_product_get_short_description', 'tpulse_translate_product_short_description', 10, 2);

function tpulse_translate_product_excerpt(string $content): string {
    if (!tpulse_is_english()) {
        return $content;
    }

    global $product;
    $current_product = $product instanceof WC_Product ? $product : wc_get_product(get_the_ID());
    if (!$current_product instanceof WC_Product) {
        return $content;
    }

    $translation = tpulse_english_product_content($current_product->get_sku());
    return $translation['short'] ?? $content;
}
add_filter('woocommerce_short_description', 'tpulse_translate_product_excerpt', 20);

function tpulse_language_price_format(string $format): string {
    return tpulse_is_english() ? '%1$s%2$s' : $format;
}
add_filter('woocommerce_price_format', 'tpulse_language_price_format');

function tpulse_language_decimal_separator(string $separator): string {
    return tpulse_is_english() ? '.' : $separator;
}
add_filter('option_woocommerce_price_decimal_sep', 'tpulse_language_decimal_separator');

function tpulse_language_thousand_separator(string $separator): string {
    return tpulse_is_english() ? ',' : $separator;
}
add_filter('option_woocommerce_price_thousand_sep', 'tpulse_language_thousand_separator');

function tpulse_translate_product_name(string $name, WC_Product $product): string {
    $translation = tpulse_is_english() ? tpulse_english_product_content($product->get_sku()) : [];
    return $translation['name'] ?? $name;
}
add_filter('woocommerce_product_get_name', 'tpulse_translate_product_name', 10, 2);

function tpulse_translate_managed_entry_content(string $content): string {
    if (!tpulse_is_english() || !is_singular() || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    if (is_product() && function_exists('wc_get_product')) {
        $product = wc_get_product(get_the_ID());
        if ($product instanceof WC_Product) {
            $translation = tpulse_english_product_content($product->get_sku());
            if (!empty($translation['description'])) {
                return $translation['description'];
            }
        }
    }

    $english = get_post_meta(get_the_ID(), '_tpulse_english_content', true);
    return is_string($english) && $english !== '' ? $english : $content;
}
add_filter('the_content', 'tpulse_translate_managed_entry_content', 20);

function tpulse_translate_managed_entry_title(string $title, int $post_id): string {
    if (!tpulse_is_english() || is_admin() || $post_id <= 0) {
        return $title;
    }

    if (get_post_type($post_id) === 'product' && function_exists('wc_get_product')) {
        $product = wc_get_product($post_id);
        if ($product instanceof WC_Product) {
            $translation = tpulse_english_product_content($product->get_sku());
            if (!empty($translation['name'])) {
                return $translation['name'];
            }
        }
    }

    $english = get_post_meta($post_id, '_tpulse_english_title', true);
    if (is_string($english) && $english !== '') {
        return $english;
    }

    $post = get_post($post_id);
    $page_titles = [
        'boutique' => 'Shop',
        'panier' => 'Cart',
        'commande' => 'Checkout',
        'mon-compte' => 'My account',
        'actualites' => 'News',
    ];
    return $post instanceof WP_Post && isset($page_titles[$post->post_name]) ? $page_titles[$post->post_name] : $title;
}
add_filter('the_title', 'tpulse_translate_managed_entry_title', 20, 2);

function tpulse_document_title_parts(array $parts): array {
    if (!tpulse_is_english()) {
        return $parts;
    }

    $object = get_queried_object();
    if (is_product() && $object instanceof WP_Post && function_exists('wc_get_product')) {
        $product = wc_get_product($object->ID);
        if ($product instanceof WC_Product) {
            $translation = tpulse_english_product_content($product->get_sku());
            $parts['title'] = $translation['name'] ?? $product->get_name();
        }
    } elseif ($object instanceof WP_Post) {
        $english = get_post_meta($object->ID, '_tpulse_english_title', true);
        $page_titles = [
            'accueil' => 'T-Pulse Archery',
            'boutique' => 'Shop',
            'panier' => 'Cart',
            'commande' => 'Checkout',
            'mon-compte' => 'My account',
            'actualites' => 'News',
            'helitwist' => 'HeliTwist',
        ];
        $parts['title'] = is_string($english) && $english !== '' ? $english : ($page_titles[$object->post_name] ?? $parts['title']);
    } elseif (is_shop()) {
        $parts['title'] = 'Shop';
    }

    if (isset($parts['tagline'])) {
        $parts['tagline'] = 'Innovation and progress for archers';
    }

    return $parts;
}
add_filter('document_title_parts', 'tpulse_document_title_parts', 20);

function tpulse_canonical_base_url(): string {
    if (is_front_page()) {
        return home_url('/');
    }
    if (function_exists('is_shop') && is_shop()) {
        return wc_get_page_permalink('shop');
    }
    if (is_singular()) {
        return get_permalink(get_queried_object_id());
    }
    if (is_home()) {
        return get_permalink((int) get_option('page_for_posts'));
    }
    if (is_post_type_archive('tpulse_resource')) {
        return (string) get_post_type_archive_link('tpulse_resource');
    }

    global $wp;
    $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    return home_url($request === '' ? '/' : '/' . $request . '/');
}

function tpulse_localized_public_url(string $base, string $language): string {
    $base = remove_query_arg(['lang', 'add-to-cart', 'avis-envoye'], $base);
    return $language === 'en' ? add_query_arg('lang', 'en', $base) : $base;
}

function tpulse_meta_description(): string {
    $descriptions = [
        'accueil' => tpulse_text('T-Pulse Archery conçoit des produits techniques, un livre et des outils gratuits pensés par un archer pour les archers.', 'T-Pulse Archery creates technical products, a book and free tools designed by an archer for archers.'),
        'helitwist' => tpulse_text('Découvrez HeliTwist Original, l’amortisseur axial T-Pulse de 27 g pour stabilisateurs d’arc, disponible en plusieurs filetages.', 'Discover HeliTwist Original, the 27 g T-Pulse axial damper for bow stabilizers, available with several thread options.'),
        'boutique' => tpulse_text('Achetez HeliTwist Original et le livre Jeux d’archers directement auprès de T-Pulse Archery, avec paiement sécurisé.', 'Buy HeliTwist Original and the Archery Games book directly from T-Pulse Archery with secure payment.'),
        'actualites' => tpulse_text('Nouveautés, articles techniques et projets en cours de T-Pulse Archery.', 'News, technical articles and current T-Pulse Archery projects.'),
        'ressources' => tpulse_text('Logiciels, simulateurs, articles et téléchargements gratuits créés pour les archers.', 'Free software, simulators, articles and downloads created for archers.'),
        'retours-archers' => tpulse_text('Consultez les retours d’archers et partagez votre expérience avec les produits T-Pulse Archery.', 'Read archer feedback and share your experience with T-Pulse Archery products.'),
        'contact' => tpulse_text('Contactez T-Pulse Archery pour une commande, un conseil sur HeliTwist ou un projet lié au tir à l’arc.', 'Contact T-Pulse Archery about an order, HeliTwist advice or an archery project.'),
        'conditions-generales-de-vente' => tpulse_text('Conditions générales de vente de la boutique T-Pulse Archery.', 'Terms and conditions for the T-Pulse Archery shop.'),
        'politique-de-confidentialite' => tpulse_text('Politique de confidentialité et traitement des données de T-Pulse Archery.', 'T-Pulse Archery privacy and personal data policy.'),
        'mentions-legales' => tpulse_text('Mentions légales du site et de la boutique T-Pulse Archery.', 'Legal notice for the T-Pulse Archery website and shop.'),
    ];

    if (is_product() && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product instanceof WC_Product) {
            if (tpulse_is_english()) {
                $translation = tpulse_english_product_content($product->get_sku());
                $source = $translation['short'] ?? '';
            } else {
                $source = $product->get_short_description();
            }
            return wp_html_excerpt(wp_strip_all_tags($source), 160, '…');
        }
    }

    $object = get_queried_object();
    $slug = $object instanceof WP_Post ? $object->post_name : (is_post_type_archive('tpulse_resource') ? 'ressources' : '');
    if (isset($descriptions[$slug])) {
        return $descriptions[$slug];
    }

    if ($object instanceof WP_Post) {
        $source = tpulse_is_english() ? get_post_meta($object->ID, '_tpulse_english_excerpt', true) : $object->post_excerpt;
        if (!$source) {
            $source = tpulse_is_english() ? get_post_meta($object->ID, '_tpulse_english_content', true) : $object->post_content;
        }
        return wp_html_excerpt(wp_strip_all_tags(strip_shortcodes((string) $source)), 160, '…');
    }

    return tpulse_text('Produits, ressources et innovations T-Pulse Archery pour le tir à l’arc.', 'T-Pulse Archery products, resources and innovation for archery.');
}

function tpulse_output_seo_metadata(): void {
    if (is_admin() || is_404() || is_search()) {
        return;
    }

    $base = tpulse_canonical_base_url();
    $canonical = tpulse_localized_public_url($base, tpulse_requested_language());
    $french = tpulse_localized_public_url($base, 'fr');
    $english = tpulse_localized_public_url($base, 'en');
    $description = tpulse_meta_description();
    $title = wp_get_document_title();
    $image = get_template_directory_uri() . '/assets/images/t-pulse-banner.png';

    if (is_singular('product') && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product instanceof WC_Product && $product->get_image_id()) {
            $product_image = wp_get_attachment_image_url($product->get_image_id(), 'full');
            if ($product_image) {
                $image = $product_image;
            }
        }
    } elseif (is_singular() && has_post_thumbnail(get_queried_object_id())) {
        $featured = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
        if ($featured) {
            $image = $featured;
        }
    }

    echo "\n" . '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    echo '<link rel="alternate" hreflang="fr-FR" href="' . esc_url($french) . '">' . "\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url($english) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($french) . '">' . "\n";
    $og_type = is_product() ? 'product' : (is_singular(['post', 'tpulse_resource']) ? 'article' : 'website');
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr(tpulse_is_english() ? 'en_US' : 'fr_FR') . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'tpulse_output_seo_metadata', 2);

function tpulse_translate_managed_excerpt(string $excerpt): string {
    if (!tpulse_is_english()) {
        return $excerpt;
    }

    $english = get_post_meta(get_the_ID(), '_tpulse_english_excerpt', true);
    return is_string($english) && $english !== '' ? $english : $excerpt;
}
add_filter('get_the_excerpt', 'tpulse_translate_managed_excerpt', 20);

function tpulse_translate_french_ui(string $translated, string $text, string $domain): string {
    if (tpulse_is_english()) {
        return $translated;
    }

    $translations = [
        'Add to cart' => 'Ajouter au panier',
        'Select options' => 'Choisir le modèle',
        'Choose an option' => 'Choisir une option',
        'Clear' => 'Effacer',
        'Read more' => 'Lire la suite',
        'View cart' => 'Voir le panier',
        'Update cart' => 'Mettre à jour le panier',
        'Proceed to checkout' => 'Passer commande',
        'Checkout' => 'Commande',
        'Cart' => 'Panier',
        'Have a coupon?' => 'Vous avez un code promo ?',
        'Click here to enter your code' => 'Cliquez ici pour saisir votre code',
        'Coupon' => 'Code promo',
        'Coupon code' => 'Code promo',
        'Apply coupon' => 'Appliquer le code',
        'Product' => 'Produit',
        'Price' => 'Prix',
        'Quantity' => 'Quantité',
        'Subtotal' => 'Sous-total',
        'Total' => 'Total',
        'Remove item' => 'Retirer',
        'First name' => 'Prénom',
        'Last name' => 'Nom',
        'Company name' => 'Entreprise',
        'Country / Region' => 'Pays / région',
        'Update country / region' => 'Mettre à jour le pays ou la région',
        'Street address' => 'Adresse',
        'House number and street name' => 'Numéro et nom de rue',
        'Apartment, suite, unit, etc. (optional)' => 'Appartement, bâtiment, etc. (facultatif)',
        'Apartment, suite, unit, etc.' => 'Appartement, bâtiment, etc.',
        '(optional)' => '(facultatif)',
        'optional' => 'facultatif',
        'Town / City' => 'Ville',
        'State / County' => 'Région / département',
        'Postcode / ZIP' => 'Code postal',
        'Phone' => 'Téléphone',
        'Email address' => 'Adresse e-mail',
        'Order notes' => 'Notes de commande',
        'Notes about your order, e.g. special notes for delivery.' => 'Notes concernant votre commande, par exemple une information utile pour la livraison.',
        'Ship to a different address?' => 'Expédier à une adresse différente ?',
        'Payment' => 'Paiement',
        'Billing details' => 'Coordonnées de facturation',
        'Your order' => 'Votre commande',
        'Place order' => 'Commander et payer',
        'Related products' => 'Produits similaires',
        'Description' => 'Description',
        'Additional information' => 'Informations complémentaires',
        'Weight' => 'Poids',
        'Dimensions' => 'Dimensions',
        'N/A' => 'Non renseigné',
        'Reviews' => 'Avis',
        'There are no reviews yet.' => 'Il n’y a pas encore d’avis.',
        'Be the first to review “%s”' => 'Soyez le premier à donner votre avis sur « %s »',
        'Your rating' => 'Votre note',
        'Rate…' => 'Noter…',
        'Perfect' => 'Parfait',
        'Good' => 'Bien',
        'Average' => 'Moyen',
        'Not that bad' => 'Pas si mal',
        'Very poor' => 'Très mauvais',
        'Your review' => 'Votre avis',
        'Submit' => 'Envoyer',
        'Name' => 'Nom',
        'Email' => 'Email',
        'Save my name, email, and website in this browser for the next time I comment.' => 'Enregistrer mon nom, mon email et mon site dans ce navigateur pour la prochaine fois.',
        'verified owner' => 'acheteur vérifié',
        'SKU:' => 'Référence :',
        'Category:' => 'Catégorie :',
        'Categories:' => 'Catégories :',
        'Uncategorized' => 'Non classé',
        'out of 5' => 'sur 5',
        'Rated %s out of 5' => 'Noté %s sur 5',
        '%s has been added to your cart.' => '%s a été ajouté au panier.',
    ];

    return $translations[$text] ?? $translated;
}
add_filter('gettext', 'tpulse_translate_french_ui', 20, 3);

function tpulse_translate_french_plural_ui(string $translation, string $single, string $plural, int $number, string $domain): string {
    if (tpulse_is_english()) {
        return $translation;
    }

    if ($single === '%s review for %s' || $plural === '%s reviews for %s') {
        return $number === 1 ? '%s avis pour %s' : '%s avis pour %s';
    }

    return $translation;
}
add_filter('ngettext', 'tpulse_translate_french_plural_ui', 20, 5);

function tpulse_french_checkout_fields(array $fields): array {
    if (tpulse_is_english()) {
        return $fields;
    }

    $labels = [
        'billing_first_name' => ['Prénom', 'Votre prénom'],
        'billing_last_name' => ['Nom', 'Votre nom'],
        'billing_company' => ['Entreprise', 'Nom de l’entreprise'],
        'billing_country' => ['Pays / région', 'Pays / région'],
        'billing_address_1' => ['Adresse', 'Numéro et nom de rue'],
        'billing_address_2' => ['Complément d’adresse', 'Appartement, bâtiment, lieu-dit...'],
        'billing_city' => ['Ville', 'Ville'],
        'billing_state' => ['Région / département', 'Région / département'],
        'billing_postcode' => ['Code postal', 'Code postal'],
        'billing_phone' => ['Téléphone', 'Votre numéro de téléphone'],
        'billing_email' => ['Adresse e-mail', 'votre@email.fr'],
        'shipping_first_name' => ['Prénom', 'Votre prénom'],
        'shipping_last_name' => ['Nom', 'Votre nom'],
        'shipping_company' => ['Entreprise', 'Nom de l’entreprise'],
        'shipping_country' => ['Pays / région', 'Pays / région'],
        'shipping_address_1' => ['Adresse', 'Numéro et nom de rue'],
        'shipping_address_2' => ['Complément d’adresse', 'Appartement, bâtiment, lieu-dit...'],
        'shipping_city' => ['Ville', 'Ville'],
        'shipping_state' => ['Région / département', 'Région / département'],
        'shipping_postcode' => ['Code postal', 'Code postal'],
        'order_comments' => ['Notes de commande', 'Information utile pour la livraison ou la commande'],
    ];

    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            if (!isset($labels[$key])) {
                continue;
            }

            [$label, $placeholder] = $labels[$key];
            $fields[$section][$key]['label'] = $label;
            $fields[$section][$key]['placeholder'] = $placeholder;
        }
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'tpulse_french_checkout_fields', 20);

function tpulse_french_product_tabs(array $tabs): array {
    if (tpulse_is_english()) {
        return $tabs;
    }

    if (isset($tabs['description'])) {
        $tabs['description']['title'] = 'Description';
    }
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = 'Informations complémentaires';
    }
    if (isset($tabs['reviews'])) {
        $tabs['reviews']['title'] = 'Avis';
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'tpulse_french_product_tabs', 20);

function tpulse_french_reviews_title(string $title, int $count, WC_Product $product): string {
    if (tpulse_is_english()) {
        return $title;
    }

    return sprintf(_n('%s avis pour %s', '%s avis pour %s', $count, 'tpulse'), number_format_i18n($count), esc_html($product->get_name()));
}
add_filter('woocommerce_reviews_title', 'tpulse_french_reviews_title', 20, 3);

add_filter('woocommerce_product_description_heading', function (string $heading): string {
    return tpulse_is_english() ? $heading : 'Description';
});

add_filter('woocommerce_product_additional_information_heading', function (string $heading): string {
    return tpulse_is_english() ? $heading : 'Informations complémentaires';
});

function tpulse_french_loop_add_to_cart_link(string $html, WC_Product $product): string {
    if (tpulse_is_english()) {
        return $html;
    }

    return strtr($html, [
        'Add to cart:' => 'Ajouter au panier :',
        'Select options for' => 'Choisir le modele pour',
        'has been added to your cart' => 'a ete ajoute au panier',
    ]);
}
add_filter('woocommerce_loop_add_to_cart_link', 'tpulse_french_loop_add_to_cart_link', 20, 2);

function tpulse_translate_frontend_html(string $html): string {
    if (!tpulse_is_english()) {
        return strtr($html, [
            'Amortisseur axial breveté' => 'Amortisseur axial',
            'Technologie brevetée' => 'Demande de brevet déposée',
            'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.' => 'Comme votre navigateur ne prend pas en charge JavaScript ou qu il est desactive, cliquez sur le bouton de mise a jour avant de valider la commande.',
            'Update country / region' => 'Mettre à jour le pays ou la région',
            'Update totals' => 'Mettre à jour les totaux',
            'process your order, support your experience throughout this website, and for other purposes described in our' => 'traiter votre commande, améliorer votre expérience sur ce site et respecter les finalités décrites dans notre',
            'Your order' => 'Votre commande',
            'Phone' => 'Téléphone',
            'Country / Region' => 'Pays / région',
            'country / region' => 'pays / région',
            'has been added to your cart.' => 'a été ajouté au panier.',
            'View cart' => 'Voir le panier',
            'Thumbnail image' => 'Image du produit',
            'Cart totals' => 'Total du panier',
            'Coupon:' => 'Code promo :',
            '&ldquo;' => '« ',
            '&rdquo;' => ' »',
        ]);
    }

    return strtr($html, [
        'Innovation pour le tir à l’arc' => 'Innovation for archery',
        'Des produits et des ressources imaginés par un archer, pour améliorer le matériel, le confort et la compréhension du tir.' => 'Products and resources created by an archer to improve equipment, comfort and understanding of the shot.',
        'Découvrir HeliTwist' => 'Discover HeliTwist',
        'Voir la boutique' => 'Visit the shop',
        'Innovation pensée par un archer' => 'Innovation designed by an archer',
        'Le produit fondateur' => 'The founding product',
        'Un amortisseur axial breveté pour stabilisateurs d’arc. Sa structure spiralée creuse réduit les vibrations, adoucit le choc du tir et limite la prise au vent.' => 'An axial damper for bow stabilizers. Its hollow spiral structure is designed to reduce vibration, soften shot reaction and limit wind drag. Patent application filed.',
        'Demande de brevet déposée' => 'Patent application filed',
        'Technologie brevetée' => 'Patent application filed',
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
        'Technologie brevetée' => 'Patent application filed',
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
        'Logiciel' => 'Software',
        'Gratuit · GitHub' => 'Free · GitHub',
        'Livraison en point relais' => 'Service point delivery',
        'Accepter' => 'Accept',
        'Refuser' => 'Reject',
        'Voir les préférences' => 'View preferences',
        'Enregistrer les préférences' => 'Save preferences',
        'Gérer le consentement' => 'Manage consent',
        'Fonctionnel' => 'Functional',
        'Statistiques' => 'Statistics',
        'Pour offrir les meilleures expériences, nous utilisons des technologies telles que les cookies pour stocker et/ou accéder aux informations des appareils. Le fait de consentir à ces technologies nous permettra de traiter des données telles que le comportement de navigation ou les ID uniques sur ce site. Le fait de ne pas consentir ou de retirer son consentement peut avoir un effet négatif sur certaines caractéristiques et fonctions.' => 'We use cookies and similar technologies to operate the shop and, with your permission, measure usage. You can accept, reject or change your preferences at any time.',
    ]);
}

function tpulse_start_translation_buffer(): void {
    if (!is_admin()) {
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

function tpulse_shop_page_title(string $title): string {
    return tpulse_is_english() ? 'Shop' : 'Boutique';
}
add_filter('woocommerce_page_title', 'tpulse_shop_page_title');

function tpulse_shop_intro(): void {
    if (!is_shop()) {
        return;
    }

    echo '<p class="shop-intro">' . esc_html(tpulse_text('Produits conçus par T-Pulse Archery et expédiés directement depuis la France.', 'Products designed by T-Pulse Archery and shipped directly from France.')) . '</p>';
}
add_action('woocommerce_archive_description', 'tpulse_shop_intro', 15);

function tpulse_attribute_label(string $label): string {
    return tpulse_is_english() && $label === 'Filetage' ? 'Thread' : $label;
}
add_filter('woocommerce_attribute_label', 'tpulse_attribute_label');

function tpulse_variation_dropdown_prompt(array $args): array {
    $args['show_option_none'] = tpulse_text('Choisir le filetage', 'Choose the thread');
    return $args;
}
add_filter('woocommerce_dropdown_variation_attribute_options_args', 'tpulse_variation_dropdown_prompt');

function tpulse_variation_choice_help(): void {
    global $product;
    if ($product instanceof WC_Product && $product->is_type('variable')) {
        echo '<p class="variation-choice-help">' . esc_html(tpulse_text('Sélectionnez le filetage compatible avec votre matériel pour afficher le stock et ajouter HeliTwist au panier.', 'Select the thread compatible with your equipment to view stock and add HeliTwist to the basket.')) . '</p>';
    }
}
add_action('woocommerce_before_variations_form', 'tpulse_variation_choice_help');

function tpulse_translate_shipping_label(string $label, $method): string {
    return tpulse_is_english() ? str_replace('Livraison en point relais', 'Service point delivery', $label) : $label;
}
add_filter('woocommerce_cart_shipping_method_full_label', 'tpulse_translate_shipping_label', 20, 2);

function tpulse_asset(string $name): string {
    return esc_url(get_template_directory_uri() . '/assets/images/' . $name);
}

function tpulse_remove_sidebar(): void {
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
}
add_action('wp', 'tpulse_remove_sidebar');

add_filter('woocommerce_enqueue_styles', function (array $styles): array {
    return $styles;
});

function tpulse_review_model_options(): array {
    return [
        '' => tpulse_text('Sélectionnez le modèle acheté', 'Select the purchased model'),
        'HeliTwist 5/16' => 'HeliTwist 5/16',
        'HeliTwist 1/4' => 'HeliTwist 1/4',
        'HeliTwist M8' => 'HeliTwist M8',
        'HeliTwist 5/16 + 1/4' => 'HeliTwist 5/16 + 1/4',
        'HeliTwist M8 + 1/4' => 'HeliTwist M8 + 1/4',
        'Jeux d archers' => tpulse_text('Livre Jeux d’archers', 'Archery Games book'),
        'Autre achat T-Pulse' => tpulse_text('Autre achat T-Pulse', 'Other T-Pulse purchase'),
    ];
}

function tpulse_product_review_form_args(array $args): array {
    if (!is_product()) {
        return $args;
    }

    $model_options = '';
    foreach (tpulse_review_model_options() as $value => $label) {
        $model_options .= sprintf('<option value="%s">%s</option>', esc_attr($value), esc_html($label));
    }

    $rating_label = tpulse_text('Votre note', 'Your rating');
    $out_of_five = tpulse_text('sur 5', 'out of 5');
    $rating_field = '<div class="comment-form-rating tpulse-rating-field"><label>' . esc_html($rating_label) . '</label><div class="tpulse-rating-stars" role="radiogroup" aria-label="' . esc_attr($rating_label) . '">'
        . '<input type="radio" id="tpulse-rating-5" name="rating" value="5" checked><label for="tpulse-rating-5" title="5 ' . esc_attr($out_of_five) . '">★</label>'
        . '<input type="radio" id="tpulse-rating-4" name="rating" value="4"><label for="tpulse-rating-4" title="4 ' . esc_attr($out_of_five) . '">★</label>'
        . '<input type="radio" id="tpulse-rating-3" name="rating" value="3"><label for="tpulse-rating-3" title="3 ' . esc_attr($out_of_five) . '">★</label>'
        . '<input type="radio" id="tpulse-rating-2" name="rating" value="2"><label for="tpulse-rating-2" title="2 ' . esc_attr($out_of_five) . '">★</label>'
        . '<input type="radio" id="tpulse-rating-1" name="rating" value="1"><label for="tpulse-rating-1" title="1 ' . esc_attr($out_of_five) . '">★</label>'
        . '</div><span class="tpulse-rating-text">' . esc_html(tpulse_text('5/5 sélectionné par défaut', '5/5 selected by default')) . '</span></div>';

    $extra_fields = '<div class="tpulse-review-fields">'
        . '<p class="comment-form-tpulse-model"><label for="tpulse_review_model">' . esc_html(tpulse_text('Modèle acheté', 'Purchased model')) . ' <span class="required">*</span></label><select id="tpulse_review_model" name="tpulse_review_model" required>' . $model_options . '</select></p>'
        . '<p class="comment-form-tpulse-date"><label for="tpulse_purchase_date">' . esc_html(tpulse_text('Date d’achat approximative', 'Approximate purchase date')) . ' <span class="required">*</span></label><input id="tpulse_purchase_date" name="tpulse_purchase_date" type="month" required></p>'
        . '</div>';

    $commenter = wp_get_current_commenter();
    $args['title_reply'] = tpulse_text('Laisser votre avis', 'Leave a review');
    $args['fields'] = [
        'author' => '<p class="comment-form-author"><label for="author">' . esc_html(tpulse_text('Nom, prénom ou pseudo', 'Name or display name')) . ' <span class="required">*</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" autocomplete="name" required></p>',
        'email' => '<p class="comment-form-email"><label for="email">' . esc_html(tpulse_text('Adresse e-mail', 'Email address')) . ' <span class="required">*</span></label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" autocomplete="email" required><small>' . esc_html(tpulse_text('Elle ne sera pas publiée.', 'It will not be published.')) . '</small></p>',
    ];
    $args['comment_notes_before'] = '<p class="comment-notes">' . esc_html(tpulse_text('Tous les champs sont obligatoires.', 'All fields are required.')) . '</p>';
    $args['comment_field'] = $rating_field . $extra_fields . '<p class="comment-form-comment"><label for="comment">' . esc_html(tpulse_text('Votre avis', 'Your review')) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="7" required></textarea></p>';
    $args['label_submit'] = tpulse_text('Envoyer mon avis', 'Submit my review');
    unset($args['fields']['rating']);

    return $args;
}
add_filter('woocommerce_product_review_comment_form_args', 'tpulse_product_review_form_args');

function tpulse_reorder_product_review_fields(array $fields): array {
    if (!is_product()) {
        return $fields;
    }

    $ordered = [];
    foreach (['author', 'email', 'comment'] as $key) {
        if (isset($fields[$key])) {
            $ordered[$key] = $fields[$key];
        }
    }
    return $ordered;
}
add_filter('comment_form_fields', 'tpulse_reorder_product_review_fields', 100);

function tpulse_validate_product_review(array $commentdata): array {
    if (($commentdata['comment_type'] ?? '') !== 'review') {
        return $commentdata;
    }

    $model = sanitize_text_field(wp_unslash($_POST['tpulse_review_model'] ?? ''));
    $purchase_date = sanitize_text_field(wp_unslash($_POST['tpulse_purchase_date'] ?? ''));
    $rating = absint($_POST['rating'] ?? 0);

    if ((!is_user_logged_in() && (trim((string) ($commentdata['comment_author'] ?? '')) === '' || !is_email($commentdata['comment_author_email'] ?? ''))) || $model === '' || $purchase_date === '' || $rating < 1 || $rating > 5) {
        wp_die(tpulse_text('Merci de renseigner votre nom ou pseudo, une adresse e-mail valide, le modèle, la date d’achat et la note.', 'Please enter your name or display name, a valid email address, the model, purchase date and rating.'), tpulse_text('Avis incomplet', 'Incomplete review'), ['response' => 400]);
    }

    if (!preg_match('/^\d{4}-\d{2}$/', $purchase_date)) {
        wp_die(tpulse_text('Merci d’indiquer une date d’achat au format mois/année.', 'Please enter the purchase date in month/year format.'), tpulse_text('Date invalide', 'Invalid date'), ['response' => 400]);
    }

    if (strtotime($purchase_date . '-01') > current_time('timestamp')) {
        wp_die(tpulse_text('La date d’achat ne peut pas être dans le futur.', 'The purchase date cannot be in the future.'), tpulse_text('Date invalide', 'Invalid date'), ['response' => 400]);
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'tpulse_validate_product_review');

function tpulse_save_product_review_meta(int $comment_id): void {
    $comment = get_comment($comment_id);
    if (!$comment instanceof WP_Comment || $comment->comment_type !== 'review') {
        return;
    }

    update_comment_meta($comment_id, 'tpulse_review_model', sanitize_text_field(wp_unslash($_POST['tpulse_review_model'] ?? '')));
    update_comment_meta($comment_id, 'tpulse_purchase_date', sanitize_text_field(wp_unslash($_POST['tpulse_purchase_date'] ?? '')));
}
add_action('comment_post', 'tpulse_save_product_review_meta');

function tpulse_force_product_review_moderation($approved, array $commentdata) {
    return (($commentdata['comment_type'] ?? '') === 'review') ? 0 : $approved;
}
add_filter('pre_comment_approved', 'tpulse_force_product_review_moderation', 10, 2);

function tpulse_show_product_review_meta(WP_Comment $comment): void {
    $model = get_comment_meta($comment->comment_ID, 'tpulse_review_model', true);
    $purchase_date = get_comment_meta($comment->comment_ID, 'tpulse_purchase_date', true);

    if (!$model && !$purchase_date) {
        return;
    }

    echo '<p class="tpulse-review-meta">';
    if ($model) {
        echo '<span>' . esc_html(tpulse_text('Modèle :', 'Model:')) . ' ' . esc_html($model) . '</span>';
    }
    if ($purchase_date) {
        echo '<span>' . esc_html(tpulse_text('Achat :', 'Purchased:')) . ' ' . esc_html(date_i18n('m/Y', strtotime($purchase_date . '-01'))) . '</span>';
    }
    echo '</p>';
}
add_action('woocommerce_review_before_comment_text', 'tpulse_show_product_review_meta');

function tpulse_review_submission_redirect(string $location, WP_Comment $comment): string {
    if ($comment->comment_type !== 'review') {
        return $location;
    }

    return add_query_arg('avis-envoye', '1', get_permalink($comment->comment_post_ID)) . '#reviews';
}
add_filter('comment_post_redirect', 'tpulse_review_submission_redirect', 20, 2);

function tpulse_review_submission_notice(): void {
    if (isset($_GET['avis-envoye']) && $_GET['avis-envoye'] === '1') {
        wc_print_notice(tpulse_text('Avis envoyé. Merci pour votre retour !', 'Review submitted. Thank you for your feedback!'), 'success');
    }
}
add_action('woocommerce_before_single_product', 'tpulse_review_submission_notice', 5);

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
