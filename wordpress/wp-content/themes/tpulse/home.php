<?php
get_header();
?>
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Actualites T-Pulse</span>
            <h1>Articles, nouveautes et projets en cours.</h1>
            <p>Suivez les evolutions de T-Pulse Archery, les idees en developpement et les ressources techniques autour du tir a l arc.</p>
        </div>

        <?php if (have_posts()) : ?>
            <div class="post-list">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">Lire l article -></a>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p class="muted">Aucun article publie pour le moment.</p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
