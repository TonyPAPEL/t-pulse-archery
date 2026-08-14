<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="wrap header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo tpulse_asset('logo-t-pulse.png'); ?>" alt="">
            <span>T-Pulse Archery</span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation"><?php echo esc_html(tpulse_text('Menu', 'Menu')); ?></button>
        <nav class="main-nav" id="main-navigation" aria-label="<?php esc_attr_e('Navigation principale', 'tpulse'); ?>">
            <?php if (has_nav_menu('primary')) : ?>
                <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s']); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(tpulse_text('La marque', 'The brand')); ?></a>
                <a href="<?php echo esc_url(home_url('/helitwist/')); ?>">HeliTwist</a>
                <a href="<?php echo esc_url(tpulse_shop_url()); ?>"><?php echo esc_html(tpulse_text('Boutique', 'Shop')); ?></a>
                <a href="<?php echo esc_url(home_url('/actualites/')); ?>"><?php echo esc_html(tpulse_text('Actualités', 'News')); ?></a>
                <a href="<?php echo esc_url(home_url('/retours-archers/')); ?>"><?php echo esc_html(tpulse_text('Avis', 'Reviews')); ?></a>
                <a href="<?php echo esc_url(home_url('/ressources/')); ?>"><?php echo esc_html(tpulse_text('Ressources', 'Resources')); ?></a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
            <?php endif; ?>
            <?php if (function_exists('wc_get_cart_url')) : ?>
                <a class="cart-link" href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php echo esc_html(tpulse_text('Panier', 'Cart')); ?> <span class="cart-count"><?php echo esc_html((string) tpulse_cart_count()); ?></span></a>
            <?php endif; ?>
            <span class="language-switch"><a<?php echo !tpulse_is_english() ? ' class="active"' : ''; ?> href="<?php echo esc_url(tpulse_language_url('fr')); ?>">FR</a><a<?php echo tpulse_is_english() ? ' class="active"' : ''; ?> href="<?php echo esc_url(tpulse_language_url('en')); ?>">EN</a></span>
        </nav>
    </div>
</header>
<main class="site-main">
