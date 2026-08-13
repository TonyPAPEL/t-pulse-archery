<?php
get_header();
?>
<section class="section">
    <div class="wrap narrow single-post">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('content-page'); ?>>
                <span class="eyebrow">Actualites T-Pulse</span>
                <h1><?php the_title(); ?></h1>
                <div class="entry-meta"><?php echo esc_html(get_the_date()); ?></div>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php
get_footer();
