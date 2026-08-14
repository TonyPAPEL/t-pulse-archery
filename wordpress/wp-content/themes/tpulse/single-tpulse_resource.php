<?php
get_header();
?>
<section class="content-page">
    <div class="narrow">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('resource-single'); ?>>
                <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('tpulse_resource')); ?>">← <?php echo esc_html(tpulse_text('Toutes les ressources', 'All resources')); ?></a>
                <div class="resource-meta">
                    <?php echo wp_kses_post(get_the_term_list(get_the_ID(), 'resource_type', '', ' · ')); ?>
                    <?php if (tpulse_resource_version(get_the_ID())) : ?><span><?php echo esc_html(tpulse_resource_version(get_the_ID())); ?></span><?php endif; ?>
                </div>
                <h1><?php the_title(); ?></h1>
                <?php if (has_post_thumbnail()) : ?><div class="resource-featured"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
                <div class="entry-content"><?php the_content(); ?></div>
                <?php if (tpulse_resource_url(get_the_ID())) : ?>
                    <a class="button resource-download" href="<?php echo esc_url(tpulse_resource_url(get_the_ID())); ?>" target="_blank" rel="noopener"><?php echo esc_html(tpulse_text('Télécharger ou ouvrir la ressource', 'Download or open the resource')); ?></a>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php
get_footer();
