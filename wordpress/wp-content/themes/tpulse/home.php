<?php
get_header();
?>
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow"><?php echo esc_html(tpulse_text('Actualités T-Pulse', 'T-Pulse news')); ?></span>
            <h1><?php echo esc_html(tpulse_text('Articles, nouveautés et projets en cours.', 'Articles, news and current projects.')); ?></h1>
            <p><?php echo esc_html(tpulse_text('Suivez les évolutions de T-Pulse Archery, les idées en développement et les ressources techniques autour du tir à l’arc.', 'Follow T-Pulse Archery updates, ideas in development and technical archery resources.')); ?></p>
        </div>

        <?php if (have_posts()) : ?>
            <div class="post-list">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>"><?php echo esc_html(tpulse_text('Lire l’article →', 'Read the article →')); ?></a>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p class="muted"><?php echo esc_html(tpulse_text('Aucun article publié pour le moment.', 'No articles have been published yet.')); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
