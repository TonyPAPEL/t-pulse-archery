<?php
get_header();
?>
<section class="content-page resource-archive">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Articles et téléchargements</span>
            <h1>Ressources gratuites</h1>
            <p>Logiciels, outils, APK et articles créés pour les archers.</p>
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
                        <a class="text-link" href="<?php the_permalink(); ?>">Découvrir →</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
get_footer();
