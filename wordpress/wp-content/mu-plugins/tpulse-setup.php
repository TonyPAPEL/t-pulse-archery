<?php
/**
 * Plugin Name: T-Pulse Initialisation
 * Description: Prépare le contenu et les réglages initiaux de la boutique T-Pulse.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function tpulse_create_page(string $title, string $slug, string $content = ''): int {
    $existing = get_page_by_path($slug);
    if ($existing instanceof WP_Post) {
        return $existing->ID;
    }

    return (int) wp_insert_post([
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'page',
    ]);
}

function tpulse_initial_setup(): void {
    if (!is_blog_installed()) {
        return;
    }

    if (get_option('tpulse_initial_setup_done')) {
        return;
    }

    update_option('blogname', 'T-Pulse Archery');
    update_option('blogdescription', 'Innovation pensée par un archer');
    update_option('timezone_string', 'Europe/Paris');
    update_option('date_format', 'd/m/Y');
    update_option('permalink_structure', '/%postname%/');

    $home = tpulse_create_page('Accueil', 'accueil');
    tpulse_create_page('Contact', 'contact', '<p>Une question sur HeliTwist, une demande archerie ou un projet de partenariat ? Écrivez-nous à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p>');
    tpulse_create_page('Mentions légales', 'mentions-legales', '<p><strong>À compléter avant la mise en ligne :</strong> identité de l’entreprise, adresse, SIREN/SIRET, directeur de publication et hébergeur.</p>');
    tpulse_create_page('Conditions générales de vente', 'conditions-generales-de-vente', '<p><strong>À compléter et faire valider avant l’ouverture des ventes.</strong></p>');
    tpulse_create_page('Politique de confidentialité', 'politique-de-confidentialite', '<p><strong>À compléter avant la mise en ligne.</strong> Cette page devra expliquer les données collectées, leur finalité, leur durée de conservation et les droits des visiteurs.</p>');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home);
    update_option('default_comment_status', 'closed');
    flush_rewrite_rules();
    update_option('tpulse_initial_setup_done', 1);
}
add_action('init', 'tpulse_initial_setup');

function tpulse_ensure_brand_pages(): void {
    if (!is_blog_installed() || get_option('tpulse_brand_pages_created')) {
        return;
    }

    tpulse_create_page('HeliTwist Original', 'helitwist');
    flush_rewrite_rules();
    update_option('tpulse_brand_pages_created', 1);
}
add_action('init', 'tpulse_ensure_brand_pages', 20);

function tpulse_setup_woocommerce_product(): void {
    if (!is_blog_installed() || !class_exists('WooCommerce') || get_option('tpulse_product_created')) {
        return;
    }

    $product = new WC_Product_Simple();
    $product->set_name('HeliTwist Original');
    $product->set_slug('helitwist-original');
    $product->set_status('draft');
    $product->set_catalog_visibility('visible');
    $product->set_description('Amortisseur breveté pour stabilisateurs d’arc, conçu pour réduire les vibrations, le choc du tir, le bruit et la sensibilité au vent.');
    $product->set_short_description('Structure spiralée creuse, amortissement axial et compatibilité 5/16, 1/4 et M8.');
    $product->set_sku('HELITWIST-ORIGINAL');
    $product->set_weight('0.027');
    $product->set_manage_stock(true);
    $product->set_stock_quantity(0);
    $product->set_stock_status('outofstock');
    $product->save();

    $image_path = get_template_directory() . '/assets/images/helitwist-3.png';
    if (file_exists($image_path)) {
        $upload = wp_upload_bits('helitwist-original.png', null, file_get_contents($image_path));
        if (empty($upload['error'])) {
            $attachment_id = wp_insert_attachment([
                'post_mime_type' => 'image/png',
                'post_title' => 'HeliTwist Original',
                'post_status' => 'inherit',
            ], $upload['file'], $product->get_id());

            if (!is_wp_error($attachment_id)) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
                $product->set_image_id((int) $attachment_id);
                $product->save();
            }
        }
    }

    update_option('woocommerce_currency', 'EUR');
    update_option('woocommerce_default_country', 'FR');
    update_option('woocommerce_calc_taxes', 'yes');
    update_option('woocommerce_manage_stock', 'yes');
    update_option('woocommerce_enable_guest_checkout', 'yes');
    update_option('woocommerce_enable_checkout_login_reminder', 'yes');
    update_option('woocommerce_coming_soon', 'no');
    update_option('woocommerce_store_pages_only', 'no');
    update_option('tpulse_product_created', $product->get_id());
}
add_action('wp_loaded', 'tpulse_setup_woocommerce_product');

function tpulse_publish_local_demo_product(): void {
    if (wp_get_environment_type() !== 'local' || !class_exists('WooCommerce') || get_option('tpulse_demo_product_published') === '3') {
        return;
    }

    $product_id = (int) get_option('tpulse_product_created');
    $product = $product_id ? wc_get_product($product_id) : false;
    if (!$product) {
        return;
    }

    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_regular_price('30.50');
    $product->set_manage_stock(true);
    $product->set_stock_quantity(10);
    $product->set_stock_status('instock');
    $product->save();
    wc_update_product_lookup_tables_column('min_max_price', $product->get_id());
    wc_update_product_lookup_tables_column('stock_quantity', $product->get_id());
    wc_delete_product_transients($product->get_id());

    update_option('tpulse_demo_product_published', '3');
}
add_action('wp_loaded', 'tpulse_publish_local_demo_product', 20);

function tpulse_create_book_product(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_book_product_created')) {
        return;
    }

    $product = new WC_Product_Simple();
    $product->set_name('Jeux d’archers');
    $product->set_slug('jeux-darchers');
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_regular_price('15.00');
    $product->set_short_description('Des idées pour perfectionner vos séances d’archerie tout en vous amusant.');
    $product->set_description('Le livre Jeux d’archers propose des idées pour enrichir et perfectionner les séances d’archerie en conservant une approche ludique.');
    $product->set_sku('LIVRE-JEUX-DARCHERS');
    $product->set_manage_stock(true);
    $product->set_stock_quantity(10);
    $product->set_stock_status('instock');
    $product->save();

    $logo_path = get_template_directory() . '/assets/images/logo-t-pulse.png';
    if (file_exists($logo_path)) {
        $upload = wp_upload_bits('jeux-darchers-tpulse.png', null, file_get_contents($logo_path));
        if (empty($upload['error'])) {
            $attachment_id = wp_insert_attachment([
                'post_mime_type' => 'image/png',
                'post_title' => 'Jeux d’archers',
                'post_status' => 'inherit',
            ], $upload['file'], $product->get_id());

            if (!is_wp_error($attachment_id)) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
                $product->set_image_id((int) $attachment_id);
                $product->save();
            }
        }
    }

    wc_delete_product_transients($product->get_id());
    update_option('tpulse_book_product_created', $product->get_id());
}
add_action('wp_loaded', 'tpulse_create_book_product', 25);

function tpulse_convert_book_to_direct_product(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_book_direct_sale') === '1') {
        return;
    }

    $product_id = (int) get_option('tpulse_book_product_created');
    if (!$product_id) {
        return;
    }

    wp_set_object_terms($product_id, 'simple', 'product_type');
    $product = new WC_Product_Simple($product_id);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_regular_price('15.00');
    $product->set_manage_stock(true);
    $product->set_stock_quantity(10);
    $product->set_stock_status('instock');
    $product->set_short_description('Des idées pour perfectionner vos séances d’archerie tout en vous amusant.');
    $product->set_description('Le livre Jeux d’archers propose des idées pour enrichir et perfectionner les séances d’archerie en conservant une approche ludique.');
    $product->save();
    wc_delete_product_transients($product_id);

    update_option('tpulse_book_direct_sale', '1');
}
add_action('wp_loaded', 'tpulse_convert_book_to_direct_product', 28);

function tpulse_enrich_book_product(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_book_content_version') === '2') {
        return;
    }

    $product_id = (int) get_option('tpulse_book_product_created');
    $product = $product_id ? wc_get_product($product_id) : false;
    if (!$product) {
        return;
    }

    $product->set_short_description('Un recueil de jeux et d’exercices ludiques pour varier les entraînements, progresser et partager de bons moments autour du tir à l’arc.');
    $product->set_description(
        '<h2>Perfectionnez vos séances d’archerie en vous amusant</h2>' .
        '<p><strong>Jeux d’archers</strong> rassemble des idées de jeux et d’exercices conçus pour renouveler les séances de tir à l’arc. Le livre s’adresse aux archers, entraîneurs, clubs et groupes souhaitant travailler la précision, la régularité et la concentration avec une approche ludique.</p>' .
        '<h3>Dans ce livre</h3><ul><li>Des jeux faciles à intégrer à une séance</li><li>Des variantes pour adapter la difficulté</li><li>Des idées pour jouer seul ou en groupe</li><li>Une approche qui associe progression et plaisir</li></ul>' .
        '<p>Livre physique vendu directement par T-Pulse Archery.</p>'
    );
    $product->save();
    wc_delete_product_transients($product_id);
    update_option('tpulse_book_content_version', '2');
}
add_action('wp_loaded', 'tpulse_enrich_book_product', 29);

function tpulse_set_book_cover(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_book_cover_version') === '1') {
        return;
    }

    $product_id = (int) get_option('tpulse_book_product_created');
    $product = $product_id ? wc_get_product($product_id) : false;
    $image_path = get_template_directory() . '/assets/images/jeux-darchers-couverture.jpg';
    if (!$product || !file_exists($image_path)) {
        return;
    }

    $upload = wp_upload_bits('jeux-darchers-couverture.jpg', null, file_get_contents($image_path));
    if (!empty($upload['error'])) {
        return;
    }

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => 'image/jpeg',
        'post_title' => 'Couverture Jeux d’archers',
        'post_excerpt' => 'Couverture du livre Jeux d’archers',
        'post_status' => 'inherit',
    ], $upload['file'], $product_id);

    if (is_wp_error($attachment_id)) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
    update_post_meta($attachment_id, '_wp_attachment_image_alt', 'Couverture du livre Jeux d’archers');
    $product->set_image_id((int) $attachment_id);
    $product->save();
    wc_delete_product_transients($product_id);
    update_option('tpulse_book_cover_version', '1');
}
add_action('wp_loaded', 'tpulse_set_book_cover', 30);

function tpulse_disable_local_coming_soon(): void {
    if (wp_get_environment_type() === 'local') {
        update_option('woocommerce_coming_soon', 'no');
        update_option('woocommerce_store_pages_only', 'no');
    }
}
add_action('wp_loaded', 'tpulse_disable_local_coming_soon', 30);

function tpulse_enable_product_reviews(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_product_reviews_enabled') === '1') {
        return;
    }

    update_option('woocommerce_enable_reviews', 'yes');
    update_option('woocommerce_enable_review_rating', 'yes');
    update_option('woocommerce_review_rating_required', 'yes');
    update_option('woocommerce_review_rating_verification_label', 'yes');
    update_option('woocommerce_review_rating_verification_required', 'no');

    $products = wc_get_products(['limit' => -1, 'return' => 'ids']);
    foreach ($products as $product_id) {
        wp_update_post([
            'ID' => $product_id,
            'comment_status' => 'open',
        ]);
    }

    update_option('tpulse_product_reviews_enabled', '1');
}
add_action('wp_loaded', 'tpulse_enable_product_reviews', 35);

function tpulse_admin_notice(): void {
    if (!is_blog_installed() || !current_user_can('manage_options')) {
        return;
    }

    echo '<div class="notice notice-warning"><p><strong>T-Pulse :</strong> avant toute vente, renseignez le prix, le stock, la livraison, les taxes, les pages légales et testez Stripe en mode test.</p></div>';
}
add_action('admin_notices', 'tpulse_admin_notice');

function tpulse_security_auto_updates(?bool $update, object $item): ?bool {
    $trusted_plugins = [
        'woocommerce',
        'woocommerce-gateway-stripe',
        'wordfence',
        'updraftplus',
        'fluent-smtp',
        'complianz-gdpr',
        'fluent-crm',
    ];

    return isset($item->slug) && in_array($item->slug, $trusted_plugins, true) ? true : $update;
}
add_filter('auto_update_plugin', 'tpulse_security_auto_updates', 10, 2);
