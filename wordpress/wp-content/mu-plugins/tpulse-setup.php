<?php
/**
 * Plugin Name: T-Pulse Initialisation
 * Description: Prépare le contenu et les réglages initiaux de la boutique T-Pulse.
 * Version: 1.2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function tpulse_preferred_locale(string $locale): string {
    if (is_admin() && !wp_doing_ajax()) {
        return 'fr_FR';
    }

    $language = isset($_GET['lang']) ? sanitize_key(wp_unslash($_GET['lang'])) : '';
    if (!in_array($language, ['fr', 'en'], true)) {
        $language = isset($_COOKIE['tpulse_language']) ? sanitize_key(wp_unslash($_COOKIE['tpulse_language'])) : 'fr';
    }

    return $language === 'en' ? 'en_US' : 'fr_FR';
}
add_filter('determine_locale', 'tpulse_preferred_locale', 1);

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

function tpulse_update_managed_page(string $title, string $english_title, string $slug, string $french_content, string $english_content, string $version): int {
    $page_id = tpulse_create_page($title, $slug, $french_content);
    if (!$page_id || get_post_meta($page_id, '_tpulse_content_version', true) === $version) {
        return $page_id;
    }

    wp_update_post([
        'ID' => $page_id,
        'post_title' => $title,
        'post_content' => $french_content,
        'post_status' => 'publish',
    ]);
    update_post_meta($page_id, '_tpulse_english_title', $english_title);
    update_post_meta($page_id, '_tpulse_english_content', $english_content);
    update_post_meta($page_id, '_tpulse_content_version', $version);

    return $page_id;
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

function tpulse_sync_managed_pages(): void {
    if (!is_blog_installed()) {
        return;
    }

    $version = '2026-09-01-3';

    $contact_fr = '<div class="legal-document"><p class="legal-intro">Une question sur HeliTwist, le livre <em>Jeux d’archers</em>, une commande ou un projet avec T-Pulse Archery ?</p><div class="legal-card"><h2>Nous contacter</h2><p><strong>E-mail :</strong> <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a><br><strong>Localisation :</strong> Thaon-les-Vosges (88150), France</p><p>Pour une commande, indiquez si possible son numéro afin que nous puissions vous répondre plus rapidement.</p></div></div>';
    $contact_en = '<div class="legal-document"><p class="legal-intro">Have a question about HeliTwist, the <em>Archery Games</em> book, an order or a project with T-Pulse Archery?</p><div class="legal-card"><h2>Contact us</h2><p><strong>Email:</strong> <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a><br><strong>Location:</strong> Thaon-les-Vosges (88150), France</p><p>For an order enquiry, please include your order number whenever possible.</p></div></div>';
    tpulse_update_managed_page('Contact', 'Contact', 'contact', $contact_fr, $contact_en, $version);

    $reviews_fr = '<div class="legal-document"><p class="legal-intro">Vos retours aident les autres archers à choisir et T-Pulse Archery à faire évoluer ses produits.</p><div class="legal-card"><h2>Déposer un avis</h2><p>Choisissez le produit concerné. Tous les avis, positifs comme négatifs, sont relus avant publication afin de garder des retours utiles, honnêtes et liés au produit.</p><div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/produit/helitwist-original/#reviews">Noter HeliTwist</a></div><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/produit/jeux-darchers/#reviews">Noter le livre</a></div></div></div><div class="legal-card tpulse-product-review-card"><span class="eyebrow">Avis HeliTwist</span><h2>Vous utilisez déjà HeliTwist ?</h2><div class="tpulse-review-empty-state"><span class="tpulse-amazon-stars" aria-label="Aucun avis publié pour le moment">★★★★★</span><strong>Les premiers avis arrivent bientôt</strong></div><p>Votre retour sur le ressenti après la décoche, le filetage utilisé et votre configuration peut vraiment aider les prochains archers à choisir le bon modèle.</p><a class="button secondary" href="/produit/helitwist-original/#reviews">Laisser un avis HeliTwist</a></div><div class="legal-card tpulse-amazon-review-card"><span class="eyebrow">Avis du livre</span><h2>Le livre est aussi noté sur Amazon</h2><div class="tpulse-amazon-rating"><span class="external-rating-score">4,5/5</span><span class="tpulse-amazon-stars" aria-label="4,5 étoiles sur 5">★★★★★</span><span>21 évaluations Amazon</span></div><p>Une partie des premiers lecteurs a acheté <em>Jeux d’archers</em> sur Amazon. Pour consulter ces retours, la fiche Amazon reste accessible en complément des avis déposés ici.</p><a class="button secondary" href="https://www.amazon.fr/Jeux-darchers-Perfectionnez-darcherie-samuser/dp/B0DLWNRBPQ#customerReviews" target="_blank" rel="noopener external nofollow">Voir les avis Amazon</a></div></div>';
    $reviews_en = '<div class="legal-document"><p class="legal-intro">Your feedback helps other archers make an informed choice and helps T-Pulse Archery improve its products.</p><div class="legal-card"><h2>Leave a review</h2><p>Select the relevant product. Positive and negative reviews are checked before publication so the page remains useful, honest and product-focused.</p><div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/produit/helitwist-original/?lang=en#reviews">Review HeliTwist</a></div><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/produit/jeux-darchers/?lang=en#reviews">Review the book</a></div></div></div><div class="legal-card tpulse-product-review-card"><span class="eyebrow">HeliTwist reviews</span><h2>Already using HeliTwist?</h2><div class="tpulse-review-empty-state"><span class="tpulse-amazon-stars" aria-label="No published review yet">★★★★★</span><strong>The first reviews are coming soon</strong></div><p>Your feedback on post-shot feel, thread choice and stabilizer setup can really help the next archers choose the right model.</p><a class="button secondary" href="/produit/helitwist-original/?lang=en#reviews">Leave a HeliTwist review</a></div><div class="legal-card tpulse-amazon-review-card"><span class="eyebrow">Book reviews</span><h2>The book is also rated on Amazon</h2><div class="tpulse-amazon-rating"><span class="external-rating-score">4.5/5</span><span class="tpulse-amazon-stars" aria-label="4.5 stars out of 5">★★★★★</span><span>21 Amazon ratings</span></div><p>Some early readers bought <em>Archery Games</em> on Amazon. You can read those comments on the Amazon listing, alongside reviews left directly on this shop.</p><a class="button secondary" href="https://www.amazon.fr/Jeux-darchers-Perfectionnez-darcherie-samuser/dp/B0DLWNRBPQ#customerReviews" target="_blank" rel="noopener external nofollow">Read Amazon reviews</a></div></div>';
    $reviews_fr = str_replace('/produit/', '/product/', $reviews_fr);
    $reviews_en = str_replace('/produit/', '/product/', $reviews_en);
    tpulse_update_managed_page('Retours d’archers', 'Archer reviews', 'retours-archers', $reviews_fr, $reviews_en, $version);

    $legal_fr = '<div class="legal-document"><p class="legal-intro">Dernière mise à jour : 1er septembre 2026.</p><h2>1. Éditeur du site</h2><p>Le site <strong>t-pulse-archery.com</strong> est édité par <strong>Tony P. Créations EI</strong>, entrepreneur individuel exerçant sous la marque commerciale <strong>T-Pulse Archery</strong>.</p><ul><li>Localisation professionnelle affichée : Thaon-les-Vosges (88150), France.</li><li>SIRET : 800 156 507 00025.</li><li>Code APE : 5811Z.</li><li>TVA : TVA non applicable, art. 293 B du CGI.</li><li>Contact principal : <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</li></ul><h2>2. Direction de la publication</h2><p>Directeur de la publication : Tony P. Créations EI.</p><h2>3. Hébergement</h2><p>Le site est hébergé par <strong>OVH SAS</strong>, 2 rue Kellermann, 59100 Roubaix, France. RCS Lille Métropole 424 761 419 00045. Téléphone : 1007 depuis la France.</p><h2>4. Propriété intellectuelle</h2><p>Les textes, photographies, éléments graphiques, logiciels, marques et contenus publiés sur ce site sont protégés par les droits de propriété intellectuelle applicables. Sauf mention contraire, leur reproduction, adaptation ou diffusion nécessite l’autorisation préalable de T-Pulse Archery ou du titulaire concerné.</p><p>Une <strong>demande de brevet a été déposée</strong> pour HeliTwist sous la référence <strong>FR2506128</strong>.</p><h2>5. Contact</h2><p>Pour toute question concernant le site, une commande, un retour ou un droit à exercer : <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p></div>';
    $legal_en = '<div class="legal-document"><p class="legal-intro">Last updated: 1 September 2026.</p><h2>1. Website publisher</h2><p><strong>t-pulse-archery.com</strong> is published by <strong>Tony P. Créations EI</strong>, a French sole trader operating under the commercial brand <strong>T-Pulse Archery</strong>.</p><ul><li>Displayed business location: Thaon-les-Vosges (88150), France.</li><li>SIRET: 800 156 507 00025.</li><li>APE business code: 5811Z.</li><li>VAT: VAT not applicable, Article 293 B of the French General Tax Code.</li><li>Main contact: <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</li></ul><h2>2. Publication director</h2><p>Publication director: Tony P. Créations EI.</p><h2>3. Hosting</h2><p>The website is hosted by <strong>OVH SAS</strong>, 2 rue Kellermann, 59100 Roubaix, France. RCS Lille Métropole 424 761 419 00045.</p><h2>4. Intellectual property</h2><p>Texts, photographs, graphics, software, trademarks and other content published on this website are protected by applicable intellectual property laws. Unless otherwise stated, they may not be reproduced, adapted or distributed without prior permission from T-Pulse Archery or the relevant rights holder.</p><p>A <strong>patent application has been filed</strong> for HeliTwist under reference <strong>FR2506128</strong>.</p><h2>5. Contact</h2><p>For questions about this website, an order, a return or a right to exercise: <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p></div>';
    tpulse_update_managed_page('Mentions légales', 'Legal notice', 'mentions-legales', $legal_fr, $legal_en, $version);

    // Keep the consumer guarantees readable and visually distinct, as required for B2C sales of goods.
    $terms_fr = <<<'HTML'
<div class="legal-document">
<p class="legal-intro">Conditions générales de vente applicables aux commandes passées sur t-pulse-archery.com. Dernière mise à jour : 1er septembre 2026.</p>
<h2>1. Vendeur et champ d’application</h2>
<p>Les présentes CGV régissent les ventes à distance conclues entre <strong>Tony P. Créations EI</strong>, marque commerciale T-Pulse Archery, SIRET 800 156 507 00025, situé à Thaon-les-Vosges (88150), France, et tout consommateur commandant un produit sur le site. Contact : <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p>
<h2>2. Produits</h2>
<p>Les caractéristiques essentielles, le prix et la disponibilité figurent sur chaque fiche produit. Les photographies sont présentées avec soin mais ne peuvent garantir une restitution parfaite des couleurs. Pour HeliTwist, le client doit sélectionner le filetage compatible avec son matériel avant l’ajout au panier. En cas de doute, il peut demander conseil avant la commande.</p>
<h2>3. Prix</h2>
<p>Les prix sont indiqués en euros. TVA non applicable, art. 293 B du CGI. Les frais de livraison, présentés séparément, sont indiqués avant la validation définitive de la commande.</p>
<p>Le livre neuf est soumis à la réglementation française sur le prix du livre. Son prix de vente est fixé à 15 €. Les frais d’expédition applicables aux commandes de livres sont indiqués lors de la commande.</p>
<h2>4. Commande</h2>
<p>Le client sélectionne les produits, vérifie son panier, renseigne ses coordonnées, choisit la livraison et le paiement puis contrôle le récapitulatif. Il peut corriger sa commande avant de cliquer sur le bouton confirmant explicitement son obligation de paiement. La commande est enregistrée après acceptation du paiement. Un e-mail d’accusé de réception récapitulant la commande est envoyé à l’adresse fournie.</p>
<h2>5. Paiement</h2>
<p>Le paiement est exigible à la commande par carte bancaire via Stripe. Les données de carte sont traitées directement par Stripe et ne sont pas enregistrées par T-Pulse Archery. En cas de refus du paiement, la commande n’est pas validée.</p>
<h2>6. Disponibilité</h2>
<p>Les offres sont valables dans la limite des stocks disponibles. En cas d’indisponibilité après commande, le client est informé rapidement et remboursé des sommes correspondantes dans les délais légaux.</p>
<h2>7. Livraison</h2>
<p>La livraison standard est proposée en France métropolitaine via Mondial Relay en point relais. Toute autre destination ou tout autre mode de livraison peut être étudié avant commande par e-mail à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>. Les commandes sont généralement préparées sous 2 à 5 jours ouvrés.</p>
<p>Les frais et le délai estimatif sont communiqués avant paiement. À défaut de date ou de délai convenu, la livraison intervient au plus tard dans les trente jours suivant la commande. Le risque de perte ou d’endommagement est transféré au consommateur lorsqu’il prend physiquement possession du bien.</p>
<h2>8. Réception</h2>
<p>Le client est invité à vérifier l’état du colis et des produits à réception et à signaler rapidement toute anomalie, idéalement avec des photographies. Cette vérification ne limite pas ses garanties légales.</p>
<h2>9. Droit de rétractation</h2>
<p>Le consommateur dispose de quatorze jours à compter du lendemain de la réception du bien pour notifier sa rétractation, sans avoir à la motiver. Il peut utiliser le <a href="/formulaire-retractation/">formulaire type de rétractation</a> ou envoyer toute déclaration dénuée d’ambiguïté à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p>
<p>Le produit doit être renvoyé dans les quatorze jours suivant cette notification. L’adresse de retour est communiquée par e-mail après la demande. Le produit peut être manipulé uniquement dans la mesure nécessaire pour en établir la nature, les caractéristiques et le bon fonctionnement. La responsabilité du client peut être engagée en cas de dépréciation résultant de manipulations excessives. Les frais directs de retour sont à sa charge, sauf erreur de T-Pulse Archery ou produit défectueux.</p>
<p>Le remboursement des sommes dues, frais de livraison standard compris, intervient au plus tard quatorze jours après la notification, par le même moyen de paiement sauf accord contraire. Il peut être différé jusqu’à récupération du bien ou réception d’une preuve d’expédition.</p>
<h2>10. Garanties légales</h2>
<div class="legal-guarantee"><p><strong>Tony P. Créations EI, Thaon-les-Vosges (88150), France, contact@t-pulse-archery.com, répond des garanties légales.</strong></p>
<p>Le consommateur dispose d’un délai de deux ans à compter de la délivrance du bien pour obtenir la mise en œuvre de la garantie légale de conformité en cas d’apparition d’un défaut de conformité. Durant ce délai, le consommateur n’est tenu d’établir que l’existence du défaut de conformité et non la date d’apparition de celui-ci.</p>
<p>Lorsque le contrat de vente du bien prévoit la fourniture d’un contenu numérique ou d’un service numérique de manière continue pendant une durée supérieure à deux ans, la garantie légale est applicable à ce contenu numérique ou ce service numérique tout au long de la période de fourniture prévue. Durant ce délai, le consommateur n’est tenu d’établir que l’existence du défaut de conformité affectant le contenu numérique ou le service numérique et non la date d’apparition de celui-ci.</p>
<p>La garantie légale de conformité emporte obligation pour le professionnel, le cas échéant, de fournir toutes les mises à jour nécessaires au maintien de la conformité du bien.</p>
<p>La garantie légale de conformité donne au consommateur droit à la réparation ou au remplacement du bien dans un délai de trente jours suivant sa demande, sans frais et sans inconvénient majeur pour lui.</p>
<p>Si le bien est réparé dans le cadre de la garantie légale de conformité, le consommateur bénéficie d’une extension de six mois de la garantie initiale.</p>
<p>Si le consommateur demande la réparation du bien, mais que le vendeur impose le remplacement, la garantie légale de conformité est renouvelée pour une période de deux ans à compter de la date de remplacement du bien.</p>
<p>Le consommateur peut obtenir une réduction du prix d’achat en conservant le bien ou mettre fin au contrat en se faisant rembourser intégralement contre restitution du bien, si :</p>
<ol><li>Le professionnel refuse de réparer ou de remplacer le bien ;</li><li>La réparation ou le remplacement du bien intervient après un délai de trente jours ;</li><li>La réparation ou le remplacement du bien occasionne un inconvénient majeur pour le consommateur, notamment lorsque le consommateur supporte définitivement les frais de reprise ou d’enlèvement du bien non conforme, ou s’il supporte les frais d’installation du bien réparé ou de remplacement ;</li><li>La non-conformité du bien persiste en dépit de la tentative de mise en conformité du vendeur restée infructueuse.</li></ol>
<p>Le consommateur a également droit à une réduction du prix du bien ou à la résolution du contrat lorsque le défaut de conformité est si grave qu’il justifie que la réduction du prix ou la résolution du contrat soit immédiate. Le consommateur n’est alors pas tenu de demander la réparation ou le remplacement du bien au préalable.</p>
<p>Le consommateur n’a pas droit à la résolution de la vente si le défaut de conformité est mineur.</p>
<p>Toute période d’immobilisation du bien en vue de sa réparation ou de son remplacement suspend la garantie qui restait à courir jusqu’à la délivrance du bien remis en état.</p>
<p>Les droits mentionnés ci-dessus résultent de l’application des articles L. 217-1 à L. 217-32 du Code de la consommation.</p>
<p>Le vendeur qui fait obstacle de mauvaise foi à la mise en œuvre de la garantie légale de conformité encourt une amende civile d’un montant maximal de 300 000 euros, qui peut être porté jusqu’à 10 % du chiffre d’affaires moyen annuel (article L. 241-5 du Code de la consommation).</p>
<p>Le consommateur bénéficie également de la garantie légale des vices cachés en application des articles 1641 à 1649 du Code civil, pendant une durée de deux ans à compter de la découverte du défaut. Cette garantie donne droit à une réduction de prix si le bien est conservé ou à un remboursement intégral contre restitution du bien.</p></div>
<h2>11. Utilisation et responsabilité</h2>
<p>Les produits doivent être utilisés conformément à leur destination et avec un matériel compatible. T-Pulse Archery ne peut être tenue responsable d’un dommage résultant d’un montage incorrect, d’une modification du produit, d’une utilisation anormale ou du non-respect des consignes, sans préjudice des responsabilités et garanties qui ne peuvent être exclues par la loi.</p>
<h2>12. Facturation et données personnelles</h2>
<p>La facturation et la comptabilité sont gérées avec Abby. Une facture peut être adressée au client par e-mail. Le traitement des données nécessaires aux commandes est décrit dans la <a href="/politique-de-confidentialite/">politique de confidentialité</a>.</p>
<h2>13. Réclamations</h2>
<p>Toute réclamation doit d’abord être adressée à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> avec le numéro de commande et les éléments utiles à son traitement.</p>
<h2>14. Droit applicable et litiges</h2>
<p>Les présentes CGV sont soumises au droit français. Le consommateur conserve les protections impératives de son pays de résidence lorsqu’elles sont applicables. À défaut d’accord amiable, le litige relève des juridictions compétentes selon les règles de droit commun.</p>
</div>
HTML;

    $terms_en = <<<'HTML'
<div class="legal-document">
<p class="legal-intro">Terms and conditions applying to orders placed on t-pulse-archery.com. Last updated: 1 September 2026.</p>
<h2>1. Seller and scope</h2><p>These terms govern distance sales between <strong>Tony P. Créations EI</strong>, commercial brand T-Pulse Archery, SIRET 800 156 507 00025, located in Thaon-les-Vosges (88150), France, and consumers ordering through this website. Contact: <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p>
<h2>2. Products</h2><p>Each product page states its essential characteristics, price and availability. Product photographs are prepared carefully but cannot guarantee exact colour reproduction. Customers must select the HeliTwist thread compatible with their equipment before adding it to the basket and may ask for advice before ordering.</p>
<h2>3. Prices</h2><p>Prices are shown in euros. VAT is not applicable under Article 293 B of the French General Tax Code. Delivery charges are shown separately before final order confirmation. The new book is subject to French fixed book price rules and is sold for €15.</p>
<h2>4. Orders</h2><p>The customer selects products, checks the basket, enters contact details, chooses delivery and payment, and reviews the summary. Errors can be corrected before clicking the button that clearly confirms the obligation to pay. The order is recorded once payment is approved and an acknowledgement email is sent.</p>
<h2>5. Payment</h2><p>Payment is due at the time of order by bank card through Stripe. Card data is handled directly by Stripe and is not stored by T-Pulse Archery. A declined payment does not validate the order.</p>
<h2>6. Availability</h2><p>Products are offered while stocks last. If an item becomes unavailable after ordering, the customer is informed promptly and the corresponding amount is refunded within the statutory period.</p>
<h2>7. Delivery</h2><p>Standard delivery is available in mainland France through Mondial Relay service points. Another destination or delivery method may be discussed before ordering at <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>. Orders are generally prepared within 2 to 5 business days. Charges and estimated timing are shown before payment. If no delivery date or period is agreed, delivery occurs no later than thirty days after the order. Risk passes when the consumer takes physical possession of the goods.</p>
<h2>8. Delivery checks</h2><p>Customers are encouraged to inspect parcels and products on receipt and report any issue promptly, preferably with photographs. This does not restrict statutory rights.</p>
<h2>9. Right of withdrawal</h2><p>Consumers have fourteen days from the day after receipt to notify withdrawal without giving a reason. They may use the <a href="/formulaire-retractation/?lang=en">model withdrawal form</a> or email any unambiguous statement to <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>. Goods must be returned within the following fourteen days to the address supplied by email. Direct return costs are paid by the customer unless the product is defective or T-Pulse Archery made an error. The customer may be liable for loss of value caused by handling beyond what is necessary to inspect the goods.</p>
<p>Amounts due, including standard outbound delivery, are refunded by the original payment method no later than fourteen days after notice. Refund may be withheld until the goods are received or proof of return is supplied.</p>
<h2>10. Statutory guarantees</h2><div class="legal-guarantee"><p><strong>Tony P. Créations EI, Thaon-les-Vosges (88150), France, contact@t-pulse-archery.com, is responsible for the statutory guarantees.</strong></p><p>Consumers may invoke the statutory conformity guarantee for two years from delivery. They are entitled to repair or replacement, free of charge and without major inconvenience, within thirty days. Depending on the circumstances provided by law, they may instead obtain a price reduction or terminate the contract. Repair extends the initial guarantee by six months; replacement imposed instead of a requested repair starts a new two-year period. Rights arise under Articles L. 217-1 to L. 217-32 of the French Consumer Code.</p><p>Consumers also benefit from the statutory guarantee against hidden defects under Articles 1641 to 1649 of the French Civil Code for two years from discovery of the defect. It gives a right to a price reduction if the goods are kept or a full refund against return.</p></div>
<h2>11. Use and liability</h2><p>Products must be used for their intended purpose and with compatible equipment. T-Pulse Archery is not liable for damage caused by incorrect fitting, modification, abnormal use or failure to follow instructions, without prejudice to rights and liability that cannot legally be excluded.</p>
<h2>12. Invoicing and personal data</h2><p>Invoicing and accounting are managed with Abby. An invoice may be emailed to the customer. Processing necessary for orders is described in the <a href="/politique-de-confidentialite/?lang=en">privacy policy</a>.</p>
<h2>13. Complaints</h2><p>Complaints must first be sent to <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> with the order number and relevant supporting information.</p>
<h2>14. Governing law and disputes</h2><p>These terms are governed by French law. Consumers retain any mandatory protections in their country of residence. If no amicable solution is reached, disputes are heard by the courts competent under ordinary legal rules. The French version prevails if this translation differs from it.</p>
</div>
HTML;

    $terms_id = tpulse_update_managed_page('Conditions générales de vente', 'Terms and conditions', 'conditions-generales-de-vente', $terms_fr, $terms_en, $version);

    $privacy_fr = '<div class="legal-document"><p class="legal-intro">Cette politique explique comment T-Pulse Archery traite les données personnelles des visiteurs et clients. Dernière mise à jour : 1er septembre 2026.</p><h2>1. Responsable du traitement</h2><p>Le responsable du traitement est <strong>Tony P. Créations EI</strong>, marque commerciale T-Pulse Archery, SIRET 800 156 507 00025, situé à Thaon-les-Vosges (88150), France. Contact relatif aux données : <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p><h2>2. Données traitées</h2><p>Selon votre utilisation du site : identité et coordonnées, adresses de facturation et de livraison, contenu du panier et des commandes, statut du paiement, compte client, échanges avec le service client, avis et pseudonyme, choix de langue, adresse IP et journaux techniques de sécurité. T-Pulse Archery ne reçoit ni ne conserve le numéro complet de votre carte bancaire.</p><h2>3. Finalités et bases légales</h2><div class="legal-table"><table><thead><tr><th>Finalité</th><th>Base légale</th></tr></thead><tbody><tr><td>Panier, commande, paiement, livraison et service après-vente</td><td>Exécution du contrat</td></tr><tr><td>Facturation, comptabilité et obligations fiscales</td><td>Obligation légale</td></tr><tr><td>Prévention de la fraude, sécurité et défense des droits</td><td>Intérêt légitime</td></tr><tr><td>Gestion et modération des avis</td><td>Intérêt légitime</td></tr><tr><td>Cookies non essentiels et mesure d’audience non exemptée</td><td>Consentement</td></tr></tbody></table></div><h2>4. Destinataires et prestataires</h2><p>Les données peuvent être transmises, uniquement lorsque cela est nécessaire, aux prestataires suivants : OVH pour l’hébergement et l’e-mail, WordPress/WooCommerce/Automattic pour le fonctionnement de la boutique, Stripe pour le paiement par carte bancaire, Sendcloud et Mondial Relay pour l’expédition en point relais, Abby pour la facturation et la comptabilité, Fluent SMTP pour l’envoi des e-mails du site, ainsi que Wordfence, UpdraftPlus et Complianz pour la sécurité, la sauvegarde et la gestion du consentement.</p><h2>5. Transferts hors Espace économique européen</h2><p>Certains prestataires internationaux peuvent traiter des données hors de l’EEE. Le cas échéant, ces transferts reposent sur une décision d’adéquation ou des garanties reconnues par le RGPD, telles que les clauses contractuelles types proposées par les prestataires concernés.</p><h2>6. Durées de conservation</h2><ul><li>Commandes, factures et pièces comptables : durée nécessaire à la relation commerciale puis durée légale, généralement dix ans pour les pièces comptables.</li><li>Compte client : pendant son utilisation puis jusqu’à trois ans après la dernière activité, hors obligations légales.</li><li>Demandes de contact et service client : temps de traitement puis jusqu’à trois ans après le dernier échange, sauf nécessité contentieuse.</li><li>Avis : pendant leur publication et le temps nécessaire à leur modération ou à la défense des droits.</li><li>Journaux techniques et de sécurité : durée définie par les outils utilisés, en principe limitée au nécessaire et réévaluée périodiquement.</li><li>Choix relatifs aux cookies : pendant la durée définie dans l’outil de consentement, généralement jusqu’à treize mois.</li></ul><h2>7. Vos droits</h2><p>Vous pouvez demander l’accès, la rectification, l’effacement, la limitation ou la portabilité de vos données, vous opposer à certains traitements et retirer un consentement à tout moment. Écrivez à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> en précisant votre demande. Une preuve d’identité peut être demandée uniquement en cas de doute raisonnable. Vous pouvez également déposer une réclamation auprès de la <a href="https://www.cnil.fr/" target="_blank" rel="noopener">CNIL</a>.</p><h2>8. Cookies</h2><p>Les cookies strictement nécessaires au panier, à la commande, à la sécurité et au choix de langue peuvent être déposés sans consentement. Tout traceur non essentiel doit rester désactivé jusqu’à votre choix. Vous devez pouvoir accepter, refuser et retirer votre consentement aussi facilement.</p><h2>9. Sécurité et mise à jour</h2><p>Des mesures techniques et organisationnelles raisonnables sont mises en œuvre pour protéger les données. Cette politique sera mise à jour lors de l’ajout ou du changement d’un prestataire ou d’une finalité.</p></div>';
    $privacy_en = '<div class="legal-document"><p class="legal-intro">This policy explains how T-Pulse Archery processes personal data relating to visitors and customers. Last updated: 1 September 2026.</p><h2>1. Data controller</h2><p>The data controller is <strong>Tony P. Créations EI</strong>, commercial brand T-Pulse Archery, SIRET 800 156 507 00025, located in Thaon-les-Vosges (88150), France. Data enquiries: <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p><h2>2. Data processed</h2><p>Depending on how you use the website: identity and contact details, billing and delivery addresses, basket and order contents, payment status, customer account, customer service messages, reviews and display name, language choice, IP address and technical security logs. T-Pulse Archery does not receive or store your full bank card number.</p><h2>3. Purposes and legal bases</h2><div class="legal-table"><table><thead><tr><th>Purpose</th><th>Legal basis</th></tr></thead><tbody><tr><td>Basket, order, payment, delivery and after-sales service</td><td>Performance of a contract</td></tr><tr><td>Invoicing, accounting and tax duties</td><td>Legal obligation</td></tr><tr><td>Fraud prevention, security and legal claims</td><td>Legitimate interests</td></tr><tr><td>Review management and moderation</td><td>Legitimate interests</td></tr><tr><td>Non-essential cookies and non-exempt analytics</td><td>Consent</td></tr></tbody></table></div><h2>4. Recipients and providers</h2><p>Data may be shared, only where necessary, with the following providers: OVH for hosting and email, WordPress/WooCommerce/Automattic for shop operation, Stripe for card payment, Sendcloud and Mondial Relay for service-point delivery, Abby for invoicing and accounting, Fluent SMTP for website email delivery, and Wordfence, UpdraftPlus and Complianz for security, backups and consent management.</p><h2>5. Transfers outside the EEA</h2><p>Some international providers may process data outside the EEA. Where applicable, transfers rely on an adequacy decision or recognised GDPR safeguards such as standard contractual clauses provided by the relevant providers.</p><h2>6. Retention</h2><ul><li>Orders, invoices and accounting records: for the commercial relationship and then the statutory period, generally ten years for accounting records.</li><li>Customer account: while active, then up to three years after last activity, except where a legal duty applies.</li><li>Contact and support requests: while handled and up to three years after the last exchange, unless needed for a legal claim.</li><li>Reviews: while published and as needed for moderation or legal claims.</li><li>Technical and security logs: for the period set by the tools used, limited to what is necessary and reviewed periodically.</li><li>Cookie choices: for the period set in the consent tool, generally up to thirteen months.</li></ul><h2>7. Your rights</h2><p>You may request access, rectification, erasure, restriction or portability, object to certain processing and withdraw consent at any time. Email <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> and describe your request. Proof of identity will be requested only if there is reasonable doubt. You may also complain to the <a href="https://www.cnil.fr/" target="_blank" rel="noopener">French data protection authority (CNIL)</a>.</p><h2>8. Cookies</h2><p>Cookies strictly required for the basket, checkout, security and language choice may be used without consent. Non-essential trackers must remain disabled until you choose. Accepting, refusing and withdrawing consent must be equally easy.</p><h2>9. Security and updates</h2><p>Reasonable technical and organisational measures are used to protect data. This policy will be updated when a provider or processing purpose changes.</p></div>';
    $privacy_id = tpulse_update_managed_page('Politique de confidentialité', 'Privacy policy', 'politique-de-confidentialite', $privacy_fr, $privacy_en, $version);
    if ($terms_id) {
        update_option('woocommerce_terms_page_id', $terms_id);
    }
    if ($privacy_id) {
        update_option('wp_page_for_privacy_policy', $privacy_id);
    }

    $withdrawal_fr = '<div class="legal-document"><p class="legal-intro">Vous pouvez utiliser ce modèle pour nous notifier votre rétractation. Il n’est pas obligatoire : toute déclaration claire exprimant votre décision convient.</p><div class="legal-card"><p><strong>À l’attention de :</strong> T-Pulse Archery, Tony P. Créations EI, Thaon-les-Vosges (88150), France, <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p><p>Je vous notifie par la présente ma rétractation du contrat portant sur la vente du ou des biens suivants :</p><p>Produit(s) : ................................................................................</p><p>Numéro de commande : ................................................................</p><p>Commandé le / reçu le : ................................................................</p><p>Nom du consommateur : ................................................................</p><p>Adresse du consommateur : ............................................................</p><p>Date : .................................... Signature, uniquement pour un envoi papier : ....................................</p></div><p>Envoyez ce formulaire ou votre déclaration avant l’expiration du délai de quatorze jours. L’adresse complète de retour vous sera communiquée par e-mail après réception de la demande. Les modalités de retour figurent dans les <a href="/conditions-generales-de-vente/">CGV</a>.</p></div>';
    $withdrawal_en = '<div class="legal-document"><p class="legal-intro">You may use this model form to notify us of withdrawal. It is optional: any clear statement of your decision is sufficient.</p><div class="legal-card"><p><strong>To:</strong> T-Pulse Archery, Tony P. Créations EI, Thaon-les-Vosges (88150), France, <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>.</p><p>I hereby give notice that I withdraw from my contract for the sale of the following goods:</p><p>Product(s): ................................................................................</p><p>Order number: ................................................................</p><p>Ordered on / received on: ................................................................</p><p>Consumer name: ................................................................</p><p>Consumer address: ............................................................</p><p>Date: .................................... Signature, only for a paper form: ....................................</p></div><p>Send this form or your statement before the fourteen-day period ends. The full return address will be provided by email after receipt of the request. Return details are in the <a href="/conditions-generales-de-vente/?lang=en">terms and conditions</a>.</p></div>';
    tpulse_update_managed_page('Formulaire de rétractation', 'Withdrawal form', 'formulaire-retractation', $withdrawal_fr, $withdrawal_en, $version);

    $delivery_fr = '<div class="legal-document"><p class="legal-intro">Retrouvez ici les informations pratiques de livraison et de retour. Les montants exacts s’affichent toujours dans le panier avant paiement.</p><h2>Préparation</h2><p>Les commandes sont généralement préparées sous 2 à 5 jours ouvrés. Un e-mail confirme la commande puis un second message est envoyé lorsque son statut d’expédition est mis à jour.</p><h2>Livraison</h2><p>La livraison standard est proposée en France métropolitaine via Mondial Relay en point relais. Une autre destination ou un autre mode de livraison peut être étudié sur demande via <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>. Pour le livre neuf, les frais de port doivent respecter le minimum réglementaire français applicable.</p><h2>Retours</h2><p>Pour exercer le droit de rétractation, prévenez-nous dans les quatorze jours suivant la réception, puis renvoyez le produit dans les quatorze jours suivants. L’adresse complète de retour est communiquée par e-mail après la demande. Consultez les <a href="/conditions-generales-de-vente/">CGV</a> pour les conditions complètes.</p><h2>Colis endommagé ou erreur</h2><p>Écrivez à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> avec le numéro de commande et, si possible, des photographies du colis et du produit.</p></div>';
    $delivery_en = '<div class="legal-document"><p class="legal-intro">Practical delivery and return information. Exact charges are always displayed in the basket before payment.</p><h2>Handling</h2><p>Orders are generally prepared within 2 to 5 business days. An email confirms the order and another is sent when its shipping status is updated.</p><h2>Delivery</h2><p>Standard delivery is available in mainland France through Mondial Relay service points. Another destination or delivery method may be discussed on request by email at <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a>. New-book delivery charges must comply with the applicable French statutory minimum.</p><h2>Returns</h2><p>To exercise the right of withdrawal, notify us within fourteen days of receipt and return the product within the following fourteen days. The full return address is provided by email after the request. See the <a href="/conditions-generales-de-vente/?lang=en">terms and conditions</a> for full details.</p><h2>Damage or incorrect item</h2><p>Email <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> with your order number and, where possible, photographs of the parcel and product.</p></div>';
    tpulse_update_managed_page('Livraison et retours', 'Delivery and returns', 'livraison-retours', $delivery_fr, $delivery_en, $version);
}
add_action('init', 'tpulse_sync_managed_pages', 18);

function tpulse_ensure_brand_pages(): void {
    if (!is_blog_installed()) {
        return;
    }

    tpulse_create_page('HeliTwist Original', 'helitwist');
    flush_rewrite_rules();
    update_option('tpulse_brand_pages_created', 1);
}
add_action('init', 'tpulse_ensure_brand_pages', 20);

function tpulse_ensure_reviews_page(): void {
    if (!is_blog_installed()) {
        return;
    }

    tpulse_create_page('Retours d’archers', 'retours-archers');
}
add_action('init', 'tpulse_ensure_reviews_page', 25);

function tpulse_ensure_blog_setup(): void {
    if (!is_blog_installed()) {
        return;
    }

    $blog_page = tpulse_create_page('Actualités', 'actualites');
    update_option('page_for_posts', $blog_page);

    foreach (['Nouveautés', 'Projets en cours', 'Articles techniques'] as $category) {
        if (!term_exists($category, 'category')) {
            wp_insert_term($category, 'category');
        }
    }

    if (get_option('tpulse_blog_seeded') === '1') {
        return;
    }

    $post_id = wp_insert_post([
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => 'Bienvenue dans l’univers T-Pulse Archery',
        'post_excerpt' => 'Produits, essais, outils gratuits et projets en cours : suivez ici les prochaines étapes de T-Pulse Archery.',
        'post_content' => '<p>T-Pulse Archery rassemble des produits et des ressources imaginés sur le pas de tir.</p><p>Vous trouverez ici les évolutions d’HeliTwist, les nouveautés de la boutique, des articles techniques ainsi que les logiciels et applications développés pour les archers.</p><p>Cette première publication ouvre le carnet de bord de la marque. Les prochains articles présenteront les essais, les choix de conception et les projets en cours.</p>',
    ]);

    if (!is_wp_error($post_id)) {
        wp_set_post_terms($post_id, ['Nouveautés'], 'category');
        update_post_meta($post_id, '_tpulse_english_title', 'Welcome to T-Pulse Archery');
        update_post_meta($post_id, '_tpulse_english_excerpt', 'Products, testing, free tools and current projects: follow the next steps for T-Pulse Archery.');
        update_post_meta($post_id, '_tpulse_english_content', '<p>T-Pulse Archery brings together products and resources created from real experience on the shooting line.</p><p>Here you will find HeliTwist updates, shop news, technical articles and software or apps developed for archers.</p><p>This first post opens the brand journal. Future articles will share testing, design decisions and current projects.</p>');
    }

    update_option('tpulse_blog_seeded', '1');
}
add_action('init', 'tpulse_ensure_blog_setup', 30);

function tpulse_ensure_woocommerce_pages(): void {
    if (!is_blog_installed() || !class_exists('WooCommerce')) {
        return;
    }

    $pages = [
        'shop' => ['Boutique', 'boutique', ''],
        'cart' => ['Panier', 'panier', '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->'],
        'checkout' => ['Commande', 'commande', '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->'],
        'myaccount' => ['Mon compte', 'mon-compte', '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->'],
    ];

    foreach ($pages as $key => [$title, $slug, $content]) {
        $option = "woocommerce_{$key}_page_id";
        $page_id = (int) get_option($option);
        $page = $page_id ? get_post($page_id) : null;
        if (!$page instanceof WP_Post || $page->post_status === 'trash') {
            $page_id = tpulse_create_page($title, $slug, $content);
            update_option($option, $page_id);
        }
    }

    flush_rewrite_rules();
}
add_action('wp_loaded', 'tpulse_ensure_woocommerce_pages', 12);

function tpulse_attach_product_image(WC_Product $product, string $asset_filename, string $attachment_filename, string $title): void {
    if ($product->get_image_id()) {
        return;
    }

    $image_path = get_template_directory() . '/assets/images/' . $asset_filename;
    if (!file_exists($image_path)) {
        return;
    }

    $upload = wp_upload_bits($attachment_filename, null, file_get_contents($image_path));
    if (!empty($upload['error'])) {
        return;
    }

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => wp_check_filetype($upload['file'])['type'] ?? 'image/png',
        'post_title' => $title,
        'post_status' => 'inherit',
    ], $upload['file'], $product->get_id());

    if (is_wp_error($attachment_id)) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
    $product->set_image_id((int) $attachment_id);
    $product->save();
}

function tpulse_setup_woocommerce_product(): void {
    if (!is_blog_installed() || !class_exists('WooCommerce')) {
        return;
    }

    $existing_id = (int) wc_get_product_id_by_sku('HELITWIST-ORIGINAL');
    if (!$existing_id) {
        $existing_id = (int) wp_insert_post([
            'post_title' => 'HeliTwist Original',
            'post_name' => 'helitwist-original',
            'post_status' => 'draft',
            'post_type' => 'product',
        ]);
    }

    if (!$existing_id) {
        return;
    }

    try {
        wp_set_object_terms($existing_id, 'variable', 'product_type');

    $product = new WC_Product_Variable($existing_id);
    $product->set_name('HeliTwist Original');
    $product->set_slug('helitwist-original');
    $product->set_status($product->get_status() === 'publish' ? 'publish' : 'draft');
    $product->set_catalog_visibility('visible');
    if ($product->get_description() === '') {
        $product->set_description('Amortisseur axial pour stabilisateurs d’arc. Demande de brevet déposée sous la référence FR2506128.');
    }
    if ($product->get_short_description() === '') {
        $product->set_short_description('Structure spiralée creuse, amortissement axial et compatibilité 5/16, 1/4, M8 et versions combinées.');
    }
    $product->set_sku('HELITWIST-ORIGINAL');
    $product->set_weight('0.027');
    $product->set_manage_stock(false);

    $attribute = new WC_Product_Attribute();
    $attribute->set_id(0);
    $attribute->set_name('Filetage');
    $attribute->set_options(['5/16', '1/4', 'M8', '5/16 + 1/4', 'M8 + 1/4']);
    $attribute->set_position(0);
    $attribute->set_visible(true);
    $attribute->set_variation(true);
    $product->set_attributes([$attribute]);
    // No default: the customer must deliberately choose the compatible thread.
    $product->set_default_attributes([]);
    $product->save();

    $image_path = get_template_directory() . '/assets/images/helitwist-1.png';
    if (get_option('tpulse_helitwist_box_image_version') !== '1' && file_exists($image_path)) {
        $upload = wp_upload_bits('helitwist-original-boite.png', null, file_get_contents($image_path));
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
                update_option('tpulse_helitwist_box_image_version', '1');
            }
        }
    }

    $variations = [
        '5/16' => ['HELITWIST-516', 'HeliTwist Original - 5/16', '30.50'],
        '1/4' => ['HELITWIST-14', 'HeliTwist Original - 1/4', '30.50'],
        'M8' => ['HELITWIST-M8', 'HeliTwist Original - M8', '30.50'],
        '5/16 + 1/4' => ['HELITWIST-516-14', 'HeliTwist Original - 5/16 + 1/4', '33.50'],
        'M8 + 1/4' => ['HELITWIST-M8-14', 'HeliTwist Original - M8 + 1/4', '33.50'],
    ];

    foreach ($variations as $thread => $variation_data) {
        try {
            $sku = $variation_data[0];
            $name = $variation_data[1];
            $price = $variation_data[2];
            $variation_id = (int) wc_get_product_id_by_sku($sku);
            $variation = $variation_id ? wc_get_product($variation_id) : false;

            if ($variation_id && !$variation instanceof WC_Product_Variation) {
                update_option('tpulse_helitwist_setup_error', 'SKU déjà utilisé par un autre produit : ' . $sku);
                continue;
            }

            if (!$variation instanceof WC_Product_Variation) {
                $variation = new WC_Product_Variation();
            }

            $variation->set_parent_id($product->get_id());
            $stock_quantity = $variation->get_id() ? (int) $variation->get_stock_quantity() : 10;
            $variation->set_name($name);
            $variation->set_status('publish');
            $variation->set_attributes(['filetage' => $thread]);
            $variation->set_sku($sku);
            $variation->set_regular_price($price);
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity(max(0, $stock_quantity));
            $variation->set_stock_status($stock_quantity > 0 ? 'instock' : 'outofstock');
            $variation->set_weight('0.027');
            $variation->save();
        } catch (Throwable $variation_error) {
            update_option('tpulse_helitwist_setup_error', $variation_error->getMessage());
        }
    }

    update_option('woocommerce_currency', 'EUR');
    update_option('woocommerce_default_country', 'FR');
    update_option('woocommerce_weight_unit', 'kg');
    update_option('woocommerce_calc_taxes', 'no');
    update_option('woocommerce_manage_stock', 'yes');
    update_option('woocommerce_enable_guest_checkout', 'yes');
    update_option('woocommerce_enable_checkout_login_reminder', 'yes');
    update_option('woocommerce_coming_soon', 'no');
    update_option('woocommerce_store_pages_only', 'no');
    update_option('tpulse_product_created', $product->get_id());
    update_option('tpulse_helitwist_variations_version', '2');
        wc_delete_product_transients($product->get_id());
    } catch (Throwable $error) {
        update_option('tpulse_helitwist_setup_error', $error->getMessage());
    }
}
add_action('wp_loaded', 'tpulse_setup_woocommerce_product');

function tpulse_publish_local_demo_product(): void {
    $host = wp_parse_url(home_url(), PHP_URL_HOST);
    $is_demo_environment = wp_get_environment_type() === 'local' || $host === 'preprod.t-pulse-archery.com';
    if (!$is_demo_environment || !class_exists('WooCommerce') || get_option('tpulse_demo_product_published') === '4') {
        return;
    }

    $product_id = (int) get_option('tpulse_product_created');
    $product = $product_id ? wc_get_product($product_id) : false;
    if (!$product) {
        return;
    }

    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_manage_stock(false);
    $product->set_stock_status('instock');
    $product->save();
    wc_update_product_lookup_tables_column('min_max_price', $product->get_id());
    wc_update_product_lookup_tables_column('stock_quantity', $product->get_id());
    wc_delete_product_transients($product->get_id());

    update_option('tpulse_demo_product_published', '4');
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
    $product->set_weight('0.159');
    $product->set_length('21.01');
    $product->set_width('14.81');
    $product->set_height('0.46');
    if (method_exists($product, 'set_global_unique_id')) {
        $product->set_global_unique_id('9791041554713');
    }
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
    $product->set_weight('0.159');
    $product->set_length('21.01');
    $product->set_width('14.81');
    $product->set_height('0.46');
    if (method_exists($product, 'set_global_unique_id')) {
        $product->set_global_unique_id('9791041554713');
    }
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

function tpulse_refresh_commercial_content(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_commercial_content_version') === '2026-09-01-3') {
        return;
    }

    $helitwist_id = (int) wc_get_product_id_by_sku('HELITWIST-ORIGINAL');
    $helitwist = $helitwist_id ? wc_get_product($helitwist_id) : false;
    if ($helitwist instanceof WC_Product) {
        $helitwist->set_short_description('Amortisseur axial de 27 g pour stabilisateurs d’arc. Sa structure spiralée creuse est conçue pour atténuer les vibrations et adoucir la réaction de l’arc. Demande de brevet déposée sous la référence FR2506128.');
        $helitwist->set_description(
            '<div class="tpulse-product-story">' .
            '<p class="product-intro"><strong>HeliTwist Original</strong> est né sur le pas de tir, à partir d’un besoin simple : obtenir une réaction plus douce après la décoche sans alourdir inutilement la stabilisation.</p>' .
            '<h2>Un amortissement dans l’axe du tir</h2>' .
            '<p>À la différence d’un amortisseur qui travaille principalement par flexion latérale, sa structure spiralée creuse se comprime dans l’axe de la stabilisation. Cette géométrie est conçue pour limiter le transfert des vibrations, atténuer le choc ressenti et laisser circuler l’air afin de réduire la prise au vent.</p>' .
            '<div class="product-benefits"><div><strong>Réaction plus douce</strong><span>Une sensation post-tir plus propre et mieux maîtrisée.</span></div><div><strong>Seulement 27 g</strong><span>Un amortissement pensé pour préserver l’équilibre de la stabilisation.</span></div><div><strong>Cinq configurations</strong><span>Choisissez 5/16, 1/4, M8 ou une version combinée.</span></div></div>' .
            '<h2>Bien choisir votre modèle</h2>' .
            '<p>Vérifiez le filetage de votre stabilisateur ou de vos masses avant de commander. Chaque variante possède son propre stock. En cas de doute, écrivez à <a href="mailto:contact@t-pulse-archery.com">contact@t-pulse-archery.com</a> avec une photo ou la référence de votre stabilisation.</p>' .
            '<ul class="product-facts"><li><strong>Usage :</strong> stabilisateurs d’arc</li><li><strong>Poids :</strong> 27 g</li><li><strong>Filetages :</strong> 5/16, 1/4, M8 ou versions combinées</li><li><strong>Propriété industrielle :</strong> demande de brevet déposée, FR2506128</li></ul>' .
            '<p class="product-note">Les sensations et le comportement peuvent varier selon l’arc, la configuration de stabilisation et les masses utilisées.</p>' .
            '</div>'
        );
        $helitwist->save();
        wc_delete_product_transients($helitwist_id);
    }

    $book_id = (int) wc_get_product_id_by_sku('LIVRE-JEUX-DARCHERS');
    $book = $book_id ? wc_get_product($book_id) : false;
    if ($book instanceof WC_Product) {
        $book->set_regular_price('15.00');
        $book->set_weight('0.159');
        $book->set_length('21.01');
        $book->set_width('14.81');
        $book->set_height('0.46');
        if (method_exists($book, 'set_global_unique_id')) {
            $book->set_global_unique_id('9791041554713');
        }
        $book->set_short_description('26 jeux d’archerie pour varier les entraînements, travailler la précision et retrouver une pratique plus détendue, seul, entre amis ou en club. Livre broché en français, 79 pages.');
        $book->set_description(
            '<div class="tpulse-product-story">' .
            '<p class="product-intro"><strong>Jeux d’archers – Perfectionnez votre tir</strong> propose 26 jeux pour sortir de la répétition, créer de nouveaux défis et progresser sans perdre le plaisir de tirer.</p>' .
            '<h2>Remettez du jeu dans vos séances</h2>' .
            '<p>Le tir à l’arc demande précision, concentration et régularité. Pourtant, répéter toujours la même volée peut finir par installer de la pression ou de la monotonie. Les situations proposées dans ce livre donnent un objectif différent à chaque séance et invitent à rester concentré sur l’action plutôt que sur le seul score.</p>' .
            '<div class="product-benefits"><div><strong>26 jeux variés</strong><span>Des idées faciles à intégrer à vos entraînements.</span></div><div><strong>Seul ou en groupe</strong><span>Pour les archers, entraîneurs, clubs et compagnons de pas de tir.</span></div><div><strong>Progression ludique</strong><span>Précision, adaptabilité, motivation et gestion de la pression.</span></div></div>' .
            '<h2>Ce que vous allez travailler</h2>' .
            '<ul><li>Varier les distances, les objectifs et les contraintes de tir.</li><li>Entretenir la motivation et la concentration au fil des séances.</li><li>Aborder le target panic dans un cadre plus détendu et moins centré sur le résultat.</li><li>Développer l’adaptabilité et favoriser un tir plus fluide.</li></ul>' .
            '<ul class="product-facts"><li><strong>Format :</strong> livre broché</li><li><strong>Langue :</strong> français</li><li><strong>Longueur :</strong> 79 pages</li><li><strong>ISBN-13 :</strong> 979-10-415-5471-3</li><li><strong>Dimensions :</strong> 14,81 × 0,46 × 21,01 cm</li><li><strong>Poids :</strong> 159 g</li><li><strong>Vente :</strong> expédié directement par T-Pulse Archery</li></ul>' .
            '<aside class="external-rating tpulse-amazon-rating-box"><div class="tpulse-amazon-rating"><span class="external-rating-score">4,5/5</span><span class="tpulse-amazon-stars" aria-label="4,5 étoiles sur 5">★★★★★</span><span>21 évaluations Amazon</span></div><p>Le livre a déjà reçu des retours de lecteurs sur Amazon. Consultez-les en complément des avis déposés directement sur la boutique T-Pulse Archery.</p><a href="https://www.amazon.fr/Jeux-darchers-Perfectionnez-darcherie-samuser/dp/B0DLWNRBPQ#customerReviews" target="_blank" rel="noopener external nofollow">Voir les avis Amazon</a></aside>' .
            '</div>'
        );
        $book->save();
        wc_delete_product_transients($book_id);
    }

    update_option('woocommerce_dimension_unit', 'cm');
    update_option('tpulse_commercial_content_version', '2026-09-01-3');
}
add_action('wp_loaded', 'tpulse_refresh_commercial_content', 40);

function tpulse_refresh_store_presentation(): void {
    if (get_option('tpulse_store_presentation_version') === '2026-08-14-1') {
        return;
    }

    update_option('WPLANG', 'fr_FR');
    update_option('woocommerce_currency', 'EUR');
    update_option('woocommerce_currency_pos', 'right_space');
    update_option('woocommerce_price_decimal_sep', ',');
    update_option('woocommerce_price_thousand_sep', ' ');
    update_option('woocommerce_price_num_decimals', '2');
    update_option('woocommerce_enable_checkout_login_reminder', 'no');
    update_option('woocommerce_checkout_privacy_policy_text', 'Vos données personnelles sont utilisées pour traiter votre commande, assurer la livraison et respecter nos obligations légales. Consultez notre [privacy_policy].');
    update_option('woocommerce_registration_privacy_policy_text', 'Vos données personnelles sont utilisées pour gérer votre compte et vos commandes. Consultez notre [privacy_policy].');
    update_option('tpulse_store_presentation_version', '2026-08-14-1');
}
add_action('wp_loaded', 'tpulse_refresh_store_presentation', 41);

function tpulse_configure_default_shipping(): void {
    if (!class_exists('WC_Shipping_Zones') || get_option('tpulse_default_shipping_version') === '2026-08-14-7') {
        return;
    }

    update_option('woocommerce_ship_to_countries', 'specific');
    update_option('woocommerce_specific_ship_to_countries', ['FR']);
    update_option('woocommerce_default_customer_address', 'base');
    update_option('woocommerce_enable_shipping_calc', 'yes');
    update_option('woocommerce_shipping_cost_requires_address', 'no');

    $zone_id = 0;
    foreach (WC_Shipping_Zones::get_zones() as $zone_data) {
        if ($zone_data['zone_name'] === 'France métropolitaine') {
            $zone_id = (int) $zone_data['id'];
            break;
        }
    }

    $default_zone = new WC_Shipping_Zone(0);
    foreach ($default_zone->get_shipping_methods(false) as $method) {
        if ($method->id === 'flat_rate' && $method->title === 'Mondial Relay - Point Relais') {
            $default_zone->delete_shipping_method($method->instance_id);
        }
    }

    $zone = $zone_id > 0 ? new WC_Shipping_Zone($zone_id) : new WC_Shipping_Zone();
    if ($zone_id === 0) {
        $zone->set_zone_name('France métropolitaine');
        $zone->set_zone_order(1);
        $zone->save();
        $zone->add_location('FR', 'country');
    }

    foreach ($zone->get_shipping_methods(false) as $method) {
        if ($method->id === 'flat_rate') {
            $zone->delete_shipping_method($method->instance_id);
            delete_option('woocommerce_flat_rate_' . $method->instance_id . '_settings');
        }
    }

    global $wpdb;
    $old_sendcloud_instances = $wpdb->get_col($wpdb->prepare(
        "SELECT instance_id FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE zone_id = %d AND method_id = %s",
        $zone->get_id(),
        'service_point_v2_shipping_method'
    ));
    foreach ($old_sendcloud_instances as $old_instance_id) {
        delete_option('sendcloudshipping_v2_service_point_v2_shipping_method_' . (int) $old_instance_id . '_settings');
    }
    $wpdb->delete(
        $wpdb->prefix . 'woocommerce_shipping_zone_methods',
        [
            'zone_id' => $zone->get_id(),
            'method_id' => 'service_point_v2_shipping_method',
        ],
        ['%d', '%s']
    );

    $wpdb->insert(
        $wpdb->prefix . 'woocommerce_shipping_zone_methods',
        [
            'zone_id' => $zone->get_id(),
            'method_id' => 'service_point_v2_shipping_method',
            'method_order' => 1,
            'is_enabled' => 1,
        ],
        ['%d', '%s', '%d', '%d']
    );
    $instance_id = (int) $wpdb->insert_id;
    if ($instance_id > 0) {
        update_option('sendcloudshipping_v2_service_point_v2_shipping_method_' . $instance_id . '_settings', [
            'title' => 'Livraison en point relais',
            'tax_status' => 'none',
            'cost' => '4.90',
            'carrier_select_v2' => ['mondial_relay'],
            'free_shipping_enabled' => 'no',
            'free_shipping_requires' => 'min_order_amount',
            'free_shipping_min_amount' => '0',
            'ignore_discounts' => 'no',
        ]);
    }
    delete_option('sendcloudshipping_v2_service_point_v2_shipping_method_0_settings');

    update_option('tpulse_default_shipping_version', '2026-08-14-7');
}
add_action('wp_loaded', 'tpulse_configure_default_shipping', 44);

function tpulse_cleanup_demo_content(): void {
    if (get_option('tpulse_demo_content_cleanup_version') === '2026-08-14-1') {
        return;
    }

    $demo_posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'any',
        'title' => 'Hello world!',
        'numberposts' => 10,
    ]);
    foreach ($demo_posts as $demo_post) {
        if (str_contains($demo_post->post_content, 'Welcome to WordPress')) {
            wp_trash_post($demo_post->ID);
        }
    }

    $welcome_posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        's' => 'Bienvenue dans les actualites T-Pulse',
        'numberposts' => 10,
    ]);
    foreach ($welcome_posts as $welcome_post) {
        if ($welcome_post->post_title !== 'Bienvenue dans les actualites T-Pulse') {
            continue;
        }

        wp_update_post([
            'ID' => $welcome_post->ID,
            'post_title' => 'Bienvenue dans l’univers T-Pulse Archery',
            'post_excerpt' => 'Produits, essais, outils gratuits et projets en cours : suivez ici les prochaines étapes de T-Pulse Archery.',
            'post_content' => '<p>T-Pulse Archery rassemble des produits et des ressources imaginés sur le pas de tir.</p><p>Vous trouverez ici les évolutions d’HeliTwist, les nouveautés de la boutique, des articles techniques ainsi que les logiciels et applications développés pour les archers.</p><p>Cette première publication ouvre le carnet de bord de la marque. Les prochains articles présenteront les essais, les choix de conception et les projets en cours.</p>',
        ]);
        update_post_meta($welcome_post->ID, '_tpulse_english_title', 'Welcome to T-Pulse Archery');
        update_post_meta($welcome_post->ID, '_tpulse_english_excerpt', 'Products, testing, free tools and current projects: follow the next steps for T-Pulse Archery.');
        update_post_meta($welcome_post->ID, '_tpulse_english_content', '<p>T-Pulse Archery brings together products and resources created from real experience on the shooting line.</p><p>Here you will find HeliTwist updates, shop news, technical articles and software or apps developed for archers.</p><p>This first post opens the brand journal. Future articles will share testing, design decisions and current projects.</p>');
    }

    update_option('tpulse_demo_content_cleanup_version', '2026-08-14-1');
}
add_action('wp_loaded', 'tpulse_cleanup_demo_content', 42);

function tpulse_refresh_editorial_content(): void {
    if (get_option('tpulse_editorial_content_version') === '2026-08-14-1') {
        return;
    }

    $resources = get_posts([
        'post_type' => 'tpulse_resource',
        'post_status' => 'any',
        's' => 'Archery Stabilizer Simulator',
        'numberposts' => 10,
    ]);
    foreach ($resources as $resource) {
        if ($resource->post_title !== 'Archery Stabilizer Simulator') {
            continue;
        }

        wp_update_post([
            'ID' => $resource->ID,
            'post_excerpt' => 'Un simulateur gratuit pour explorer l’influence des masses et des dimensions sur le comportement d’un stabilisateur d’arc.',
            'post_content' => '<p><strong>Archery Stabilizer Simulator</strong> est un logiciel gratuit développé par T-Pulse pour aider les archers à visualiser et comparer différentes configurations de stabilisation.</p><p>Le code source, la documentation et les versions disponibles sont publiés sur GitHub. Consultez la page du projet pour connaître les plateformes prises en charge et les dernières évolutions.</p>',
        ]);
        update_post_meta($resource->ID, '_tpulse_english_excerpt', 'A free simulator for exploring how weight and dimensions influence bow stabilizer behaviour.');
        update_post_meta($resource->ID, '_tpulse_english_content', '<p><strong>Archery Stabilizer Simulator</strong> is free software developed by T-Pulse to help archers visualise and compare different stabilizer configurations.</p><p>Source code, documentation and available releases are published on GitHub. Visit the project page for supported platforms and the latest updates.</p>');
    }

    $category_updates = [
        'Nouveautes' => 'Nouveautés',
        'Actualites' => 'Actualités',
    ];
    foreach ($category_updates as $old_name => $new_name) {
        $term = get_term_by('name', $old_name, 'category');
        if ($term instanceof WP_Term) {
            wp_update_term($term->term_id, 'category', ['name' => $new_name]);
        }
    }

    update_option('tpulse_editorial_content_version', '2026-08-14-1');
}
add_action('wp_loaded', 'tpulse_refresh_editorial_content', 43);

function tpulse_disable_local_coming_soon(): void {
    if (wp_get_environment_type() === 'local') {
        update_option('woocommerce_coming_soon', 'no');
        update_option('woocommerce_store_pages_only', 'no');
    }
}
add_action('wp_loaded', 'tpulse_disable_local_coming_soon', 30);

function tpulse_enable_product_reviews(): void {
    if (!class_exists('WooCommerce') || get_option('tpulse_product_reviews_enabled') === '3') {
        return;
    }

    update_option('woocommerce_enable_reviews', 'yes');
    update_option('woocommerce_enable_review_rating', 'yes');
    update_option('woocommerce_review_rating_required', 'yes');
    update_option('woocommerce_review_rating_verification_label', 'yes');
    update_option('woocommerce_review_rating_verification_required', 'no');
    update_option('comment_moderation', '1');
    update_option('comments_notify', '1');
    update_option('moderation_notify', '1');
    update_option('require_name_email', '1');

    $products = wc_get_products(['limit' => -1, 'return' => 'ids']);
    foreach ($products as $product_id) {
        wp_update_post([
            'ID' => $product_id,
            'comment_status' => 'open',
        ]);
    }

    update_option('tpulse_product_reviews_enabled', '3');
}
add_action('wp_loaded', 'tpulse_enable_product_reviews', 35);

function tpulse_admin_notice(): void {
    if (!is_blog_installed() || !current_user_can('manage_options')) {
        return;
    }

    echo '<div class="notice notice-warning"><p><strong>T-Pulse :</strong> la boutique est en préproduction. Avant l’ouverture, passez Stripe en mode production, effectuez une vraie commande à petit montant, vérifiez les e-mails, l’étiquette Sendcloud et la facture Abby, puis remboursez le test.</p></div>';
}
add_action('admin_notices', 'tpulse_admin_notice');

function tpulse_register_abby_order_box(): void {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $screens = ['shop_order'];
    if (function_exists('wc_get_page_screen_id')) {
        $screens[] = wc_get_page_screen_id('shop-order');
    }

    foreach (array_unique($screens) as $screen) {
        add_meta_box('tpulse-abby-order', 'Préparation facture Abby', 'tpulse_render_abby_order_box', $screen, 'side', 'high');
    }
}
add_action('add_meta_boxes', 'tpulse_register_abby_order_box');

function tpulse_render_abby_order_box($object): void {
    $order = $object instanceof WC_Order ? $object : wc_get_order((int) ($object->ID ?? 0));
    if (!$order instanceof WC_Order) {
        echo '<p>Commande introuvable.</p>';
        return;
    }

    $customer_name = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
    $address = array_filter([
        $order->get_billing_company(),
        $order->get_billing_address_1(),
        $order->get_billing_address_2(),
        trim($order->get_billing_postcode() . ' ' . $order->get_billing_city()),
        $order->get_billing_country(),
    ]);
    $lines = [
        'Commande WooCommerce : #' . $order->get_order_number(),
        'Date : ' . wc_format_datetime($order->get_date_created(), 'd/m/Y'),
        'Client : ' . $customer_name,
        'E-mail : ' . $order->get_billing_email(),
        'Téléphone : ' . $order->get_billing_phone(),
        'Adresse : ' . implode(', ', $address),
        '',
        'Produits :',
    ];

    foreach ($order->get_items() as $item) {
        $description = $item->get_name() . ' × ' . $item->get_quantity();
        $meta = [];
        foreach ($item->get_formatted_meta_data() as $meta_item) {
            $meta[] = wp_strip_all_tags($meta_item->display_key . ' : ' . $meta_item->display_value);
        }
        if ($meta) {
            $description .= ' (' . implode(', ', $meta) . ')';
        }
        $lines[] = '- ' . $description . ' : ' . html_entity_decode(wp_strip_all_tags(wc_price($item->get_total(), ['currency' => $order->get_currency()])), ENT_QUOTES, 'UTF-8');
    }

    $lines[] = '';
    $lines[] = 'Livraison : ' . html_entity_decode(wp_strip_all_tags(wc_price($order->get_shipping_total(), ['currency' => $order->get_currency()])), ENT_QUOTES, 'UTF-8') . ' - ' . $order->get_shipping_method();
    $lines[] = 'Total payé : ' . html_entity_decode(wp_strip_all_tags($order->get_formatted_order_total()), ENT_QUOTES, 'UTF-8');
    $lines[] = 'Paiement : ' . $order->get_payment_method_title();
    $lines[] = 'TVA non applicable, art. 293 B du CGI.';

    $field_id = 'tpulse-abby-copy-' . $order->get_id();
    echo '<p>Copiez ce bloc dans Abby : les coordonnées, produits et montants sont déjà regroupés.</p>';
    echo '<textarea id="' . esc_attr($field_id) . '" readonly rows="12" style="width:100%;font-family:monospace;font-size:11px">' . esc_textarea(implode("\n", $lines)) . '</textarea>';
    echo '<p><button type="button" class="button button-primary" data-tpulse-copy="' . esc_attr($field_id) . '">Copier pour Abby</button> <span class="tpulse-copy-status" aria-live="polite"></span></p>';
    echo '<ol style="margin-left:1.2em"><li>Vérifier le paiement dans Stripe.</li><li>Créer et envoyer la facture dans Abby.</li><li>Créer l’étiquette dans Sendcloud.</li><li>Passer la commande en « Terminée » après expédition.</li></ol>';
    echo '<script>document.querySelectorAll("[data-tpulse-copy]").forEach(function(button){button.addEventListener("click",function(){var field=document.getElementById(button.dataset.tpulseCopy);navigator.clipboard.writeText(field.value).then(function(){button.parentNode.querySelector(".tpulse-copy-status").textContent="Copié";});});});</script>';
}

function tpulse_security_auto_updates(?bool $update, object $item): ?bool {
    $trusted_plugins = [
        'woocommerce',
        'woocommerce-gateway-stripe',
        'wordfence',
        'updraftplus',
        'fluent-smtp',
        'complianz-gdpr',
        'fluent-crm',
        'sendcloud-connected-shipping',
    ];

    return isset($item->slug) && in_array($item->slug, $trusted_plugins, true) ? true : $update;
}
add_filter('auto_update_plugin', 'tpulse_security_auto_updates', 10, 2);
