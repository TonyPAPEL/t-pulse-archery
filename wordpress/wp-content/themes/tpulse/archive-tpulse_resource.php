<?php
get_header();
?>
<section class="content-page resource-archive">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Articles et téléchargements', 'Articles and downloads')); ?></span>
            <h1><?php echo esc_html(tpulse_text('Ressources gratuites', 'Free resources')); ?></h1>
            <p><?php echo esc_html(tpulse_text('Logiciels, outils, applications Android et articles créés pour les archers.', 'Software, tools, Android apps and articles created for archers.')); ?></p>
        </div>
        <div class="resource-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('resource-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?><a class="resource-image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a><?php endif; ?>
                    <div class="resource-card-body">
                        <div class="resource-meta">
                            <?php echo wp_kses_post(get_the_term_list(get_the_ID(), 'resource_type', '', ' · ')); ?>
                            <?php if (tpulse_resource_version(get_the_ID())) : ?><span><?php echo esc_html(tpulse_resource_version(get_the_ID())); ?></span><?php endif; ?>
                        </div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                        <a class="text-link" href="<?php the_permalink(); ?>"><?php echo esc_html(tpulse_text('Découvrir →', 'Discover →')); ?></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
get_footer();
